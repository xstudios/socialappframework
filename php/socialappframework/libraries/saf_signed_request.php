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

    private $_signed_request = null;

    private $_access_token = null;

    private $_page_admin = false;

    private $_page_liked = false;
    private $_like_via_fan_gate = false;

    // holds data we pass to the app tab via &app_data=something
    private $_app_data = null;

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------
    public function getSignedRequestData() { return $this->_signed_request; }

    public function getAccessToken() { return $this->_access_token; }

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

        // begin benchmark
        $benchmark = $this->benchmark();
        if ($benchmark) $benchmark->begin();

        // get the signed request (only available for tab or canvas apps)
        $this->_signed_request = $this->_facebook->getSignedRequest();

        if ( !empty($this->_signed_request) ) {

            // get the user id
            $this->_user_id = $this->_facebook->getUser();

            // get us an access token for a tab or canvas app
            $this->_access_token = $this->_getLongLivedAccessToken();

            // are we viewing this within a fan page?
            if ( isset($this->_signed_request['page']) ) {

                // get page id
                $this->_page_id = $this->_signed_request['page']['id'];
                $this->debug(__CLASS__.':: User is viewing tab on fan page ('.$this->_page_id.')');

                // does the user like this page?
                $this->_page_liked = $this->_signed_request['page']['liked'];

                if ($this->_page_liked == true) {

                    if ( isset($_SESSION['saf_fan_gate']) && $_SESSION['saf_fan_gate'] == true ) {

                        $this->_like_via_fan_gate = true;
                        // unset fan gate flag so we don't keep assuming the user liked this via fan gate
                        unset( $_SESSION['saf_fan_gate'] );
                        $this->debug(__CLASS__.':: User likes this page (via Fan Gate)');

                    } else {

                        $this->debug(__CLASS__.':: User likes this page');

                    }

                } else {

                    $this->debug(__CLASS__.':: User does not like this page');
                    // set a session flag so we know when the user originally came here they did not like the page
                    $_SESSION['saf_fan_gate'] = true;

                }

                // is user a page admin?
                $this->_page_admin = $this->_isPageAdmin();

                // get app data passed via the url
                if ( isset($this->_signed_request['app_data']))  {
                    $this->_app_data = $this->_signed_request['app_data'];
                    $this->debug(__CLASS__.':: App data (passed via \'app_data\' GET param):', $this->_app_data);
                }

            // we are most likely looking at the tab from somewhere we don't want
            } else {

                header("Location: ".SAF_Config::srRedirectTabURL());
                exit;

            }

            // remove some params we don't want nor need to store since we create our own
            unset($this->_signed_request['page']);
            unset($this->_signed_request['user']);
            unset($this->_signed_request['user_id']);

            // add our own useful Social App Framework parameter(s) to the signed_request object
            $this->_signed_request['saf_user_id'] = $this->_user_id;
            $this->_signed_request['saf_page_id'] = $this->_page_id;
            $this->_signed_request['saf_page_liked'] = $this->_page_liked;
            $this->_signed_request['saf_page_admin'] = $this->_page_admin;

            // add our signed request data into the session
            SAF_Session::setPersistentData('signed_request_obj', $this->_signed_request);

            $this->debug(__CLASS__.':: SAF Signed request data:', $this->_signed_request);

        // we are looking at the tab or app outside of the Facebook chrome
        } else {

            // make sure we clear all SAF Session data since we can't assume anything is still valid
            SAF_Session::clearAllPersistentData();

            // tab
            if (SAF_Config::pageType() == SAF_Config::PAGE_TYPE_TAB) {

                $this->debug(__CLASS__.':: No signed request. Viewing tab outside of Facebook.', null, 3);

                if ( SAF_Config::srRedirectTab() == true ) {
                    header("Location: ".SAF_Config::srRedirectTabURL());
                    exit;
                }

            }

            // app
            if (SAF_Config::pageType() == SAF_Config::PAGE_TYPE_APP) {

                $this->debug(__CLASS__.':: No signed request. Viewing app outside of Facebook.', null, 3);

                if ( SAF_Config::srRedirectApp() == true ) {
                    header("Location: ".SAF_Config::srRedirectAppURL());
                    exit;
                }

            }

            // facebook connect
            if (SAF_Config::pageType() == SAF_Config::PAGE_TYPE_FACEBOOK_CONNECT) {

                $this->debug(__CLASS__.':: No signed request. Viewing Facebook Connect app.', null, 3);

                // get user access token
                if (isset($_REQUEST['code'])) {

                    $access_token = $this->_getAccessTokenFromCode();

                    if (!empty($access_token)) {

                        $this->_facebook->setAccessToken($access_token);
                        $this->_access_token = $this->_facebook->getAccessToken();

                        $this->debug(__CLASS__.':: Obtained access token from the request code.');

                    } else {

                        $this->debug(__CLASS__.':: Unable to obtain access token.', null, 3);

                    }

                } else {

                    $this->debug(__CLASS__.':: Request code is not present. Prompt user to login...', null, 3);

                }

            }

        }

        $this->debug('--------------------');

        // end benchmark
        if ($benchmark) if ($benchmark) $benchmark->end(__CLASS__);
    }

    // ------------------------------------------------------------------------
    // PRIVATE METHODS
    // ------------------------------------------------------------------------

    /**
     * DETERMINE IF USER IS THE PAGE ADMIN
     *
     * @access    private
     * @return    boolean
     */
    private function _isPageAdmin() {
        if ( isset($this->_signed_request['page']['admin']) && $this->_signed_request['page']['admin'] == true ) {
            $this->debug(__CLASS__.':: User is the page admin');
            return true;
        } else {
            return false;
        }
    }

    // ------------------------------------------------------------------------

    /**
     * GET ACCESS TOKEN FROM CODE
     *
     * Return an access token (used with Facebook Connect only)
     *
     * @access    private
     * @return    string
     */
    private function _getAccessTokenFromCode() {
        $params = array(
            'client_id'     => $this->getAppID(),
            'client_secret' => $this->getAppSecret(),
            'redirect_uri'  => SAF_Config::urlBase(),
            'code'          => $_REQUEST['code']
        );
        $url = 'oauth/access_token?'.http_build_query($params);
        $access_token_response = SAF_FBHelper::graph_request($url, false);

        if (empty($access_token_response)) {
            return false;
        }

        // response returns a query string, output it as an associative array
        $response_params = array();
        parse_str($access_token_response, $response_params);
        if (!isset($response_params['access_token'])) {
            return false;
        }

        return $response_params['access_token'];
    }

    // ------------------------------------------------------------------------

    /**
     * GET LONG-LIVED ACCESS TOKEN
     *
     * Return a 60 day access token by exchanging the
     * short-lived token for a long-lived one
     *
     * @access    private
     * @return    string
     */
    private function _getLongLivedAccessToken() {
        // get long-lived access token
        $params = array(
            'grant_type'        => 'fb_exchange_token',
            'client_id'         => $this->getAppID(),
            'client_secret'     => $this->getAppSecret(),
            'fb_exchange_token' => $this->_facebook->getAccessToken()
        );
        $url = 'oauth/access_token?'.http_build_query($params);
        $access_token_response = SAF_FBHelper::graph_request($url, false);

        if (empty($access_token_response)) {
            return false;
        }

        // response returns a query string, output it as an associative array
        $response_params = array();
        parse_str($access_token_response, $response_params);
        if (!isset($response_params['access_token'])) {
            return false;
        }

        return $response_params['access_token'];
    }

    // ------------------------------------------------------------------------

}

/* End of file */
