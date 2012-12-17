<?php if ( ! defined('SOCIAL_APP_FRAMEWORK') ) exit('No direct script access allowed');
/**
 * Social App Framework Signed Request class
 *
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 * @version      1.0
 * @copyright    2012 X Studios
 * @link         http://www.xstudiosinc.com
 *
 * You should have received a copy of the license along with this program.
 * If not, see <http://socialappframework.com/license/>.
 */
abstract class SAF_Signed_Request extends SAF_Base {

    private $_page_admin = false;

    private $_page_liked = false;
    private $_like_via_fan_gate = false;

    // holds data we pass to the app tab via &app_data=something
    private $_app_data;

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------
    public function getAppData() { return $this->_app_data; }

    public function isPageAdmin() { return $this->_page_admin; }

    public function isPageLiked() { return $this->_page_liked; }
    public function isPageLikeViaFanGate() { return $this->_like_via_fan_gate; }

    // ------------------------------------------------------------------------

    /**
     * CONSTRUCTOR
     *
     * @access    public
     * @return    void
     */
    public function __construct() {
        parent::__construct();

        // get the signed request (only available for tab or canvas apps)
        // however, it will exist on Facebook Connect apps if using the
        // Javascript SDK.
        $signed_request = $this->getSignedRequest();

        // get the user id by any available means (signed request, auth code, session)
        $this->_user_id = $this->getUser();

        // if we have a signed request
        if ( !empty($signed_request) ) {

            // force user to view tab or canvas app within Facebook chrome
            // a code will only be present if the app also uses the Javascript SDK
            if ( isset($signed_request['code']) ) {
                $this->_forceFacebookChrome();
            }

            // get us an extended access token for a tab or canvas app
            // add our own useful Social App Framework parameters
            $signed_request['saf_user_id'] = $this->_user_id;
            $this->setPersistentData('saf_access_token', $this->_getLongLivedAccessToken());

            // are we viewing this within a fan page?
            // this key is only present if the app is being loaded within a page tab
            if ( isset($signed_request['page']) ) {

                // get page id
                $this->_page_id = $signed_request['page']['id'];
                $this->debug(__CLASS__.':: User is viewing tab on fan page ('.$this->_page_id.').');

                // does the user like this page?
                $this->_page_liked = $signed_request['page']['liked'];

                if ($this->_page_liked == true) {

                    if ( $this->getPersistentData('fan_gate') ) {

                        $this->_like_via_fan_gate = true;
                        // unset fan gate flag so we don't keep assuming the
                        // user liked this via fan gate
                        $this->clearPersistentData('fan_gate');
                        $this->debug(__CLASS__.':: User likes this page (via Fan Gate).');

                    } else {

                        $this->debug(__CLASS__.':: User likes this page.');

                    }

                } else {

                    $this->debug(__CLASS__.':: User does not like this page.');
                    // set a session flag so we know when the user originally
                    // came here they did not like the page
                    $this->setPersistentData('fan_gate', true);

                }

                // is user a page admin?
                if ( isset($signed_request['page']['admin']) && $signed_request['page']['admin'] == true ) {
                    $this->_page_admin = true;
                    $this->debug(__CLASS__.':: User is the page admin.');
                }

                // get app data passed via the url
                if ( isset($signed_request['app_data']))  {
                    $this->_app_data = $signed_request['app_data'];
                    $this->debug(__CLASS__.':: App data (passed via \'app_data\' GET param):', $this->_app_data);
                }

                // add our own useful Social App Framework parameters
                $signed_request['saf_page_id'] = $this->_page_id;
                $signed_request['saf_page_liked'] = $this->_page_liked;
                $signed_request['saf_page_admin'] = $this->_page_admin;

            }

            // add our saf data into the session
            $this->setPersistentData('saf_signed_request', $signed_request);

            // if it's a Facebook Connect app the only way we'd have a Signed
            // Request is if the app is also using the Javascript SDK
            if (SAF_Config::getAppType() == SAF_Config::APP_TYPE_FACEBOOK_CONNECT) {
                $this->debug(__CLASS__.':: We have a Signed Request thanks to the Javascript SDK.');
            }

            $this->debug(__CLASS__.':: SAF Signed request data:', $signed_request);

        // we are looking at the app outside of the Facebook chrome
        } else {

            // clear all SAF Signed Request data since we can't assume anything is still valid
            $this->clearPersistentData('saf_signed_request');

            $this->_forceFacebookChrome('No signed request. ');

            // facebook connect app
            if (SAF_Config::getAppType() == SAF_Config::APP_TYPE_FACEBOOK_CONNECT) {
                $this->debug(__CLASS__.':: No signed request. Viewing Facebook Connect app.', null, 3);
                $this->_facebookConnect();
            }

        }

        $this->debug('--------------------');
    }

    // ------------------------------------------------------------------------
    // PRIVATE METHODS
    // ------------------------------------------------------------------------

    /**
     * GET LONG-LIVED ACCESS TOKEN
     *
     * Return a long-lived access token (60 days or more) by exchanging the
     * short-lived token for a long-lived token
     *
     * @access    private
     * @return    string
     */
    private function _getLongLivedAccessToken() {
        // exchange short-lived token for long-lived one
        $this->setExtendedAccessToken();
        // the Facebook SDK (3.2.2) doesn't set the access token property to the
        // long-lived token for some strange reason so $facebook->getAccessToken()
        // will still return the short-lived token. So we have to get it from
        // the app session where the Facebook SDK stores it.
        $key = 'fb_'.SAF_Config::getAppID().'_access_token';
        if (array_key_exists($key, $_SESSION)) {
            $access_token = $_SESSION[$key];
            // update the Facebook SDK access token to the long-lived one
            $this->setAccessToken($access_token);
        }

        return $this->getAccessToken();
    }

    // ------------------------------------------------------------------------

    /**
     * Facebook Connect
     *
     * Upon user login (authentication) Facebook Connect apps will have 'state'
     * and 'code' query string values.  The code must be exchanged for an access
     * token.
     *
     * @access    private
     * @return    void
     */
    private function _facebookConnect() {
        // Dec 5th breaking change fix: we must rely on the access token
        // we already got from exchanging the code as codes are now one-time
        // use and expire within 10 mins.
        $access_token = $this->getPersistentData('saf_access_token');
        if ($access_token) {

            // set the SDK to use the access token
            $this->setAccessToken($access_token);

            $this->debug(__CLASS__.':: We have an existing access token.');
            return;

        }

        // if we have a code we need to exhange it for an access token
        // this is a one-time deal so we need to store it for future visits
        if (isset($_REQUEST['code'])) {

            // exchange the code for an access token
            $access_token = $this->getAccessTokenFromCode($_REQUEST['code'], SAF_Config::getBaseURL());

            if (!empty($access_token)) {

                // set the SDK to use the access token
                $this->setAccessToken($access_token);
                // immediately exchange the short-lived access token for a long-lived one
                $access_token = $this->_getLongLivedAccessToken();
                // store the access token for future visits
                $this->setPersistentData('saf_access_token', $access_token);

                $this->debug(__CLASS__.':: Obtained access token from the request code.');

            } else {

                $this->debug(__CLASS__.':: Unable to obtain access token.', null, 3);

            }

        } else {

            $this->debug(__CLASS__.':: Request code is not present. Prompt user to login...', null, 3);

        }
    }

    // ------------------------------------------------------------------------

    /**
     * FORCE FACEBOOK CHROME
     *
     * Ensure the user is viewing the tab or canvas app within the
     * Facebook chrome.
     *
     * @access    private
     * $param     string $reason
     * @return    string
     */
    private function _forceFacebookChrome($reason='') {
        // tab app
        if (SAF_Config::getAppType() == SAF_Config::APP_TYPE_TAB) {
            $this->debug(__CLASS__.':: '.$reason.'Viewing Tab app outside of Facebook.', null, 3);
            if ( SAF_Config::getForceFacebookView() == true ) {
                header('Location: '.SAF_Config::getPageTabURL());
                exit;
            }
        }

        // canvas app
        if (SAF_Config::getAppType() == SAF_Config::APP_TYPE_CANVAS) {
            $this->debug(__CLASS__.':: '.$reason.'Viewing Canvas app outside of Facebook.', null, 3);
            if ( SAF_Config::getForceFacebookView() == true ) {
                header('Location: '.SAF_Config::getCanvasURL());
                exit;
            }
        }
    }

    // ------------------------------------------------------------------------

}

/* End of file */
