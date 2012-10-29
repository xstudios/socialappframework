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

    private $_authenticated = false;

    private $_page_admin = false;
    private $_app_developer = false;

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

    public function isAuthenticated() { return $this->_authenticated; }

    public function isPageAdmin() { return $this->_page_admin; }
    public function isAppDeveloper() { return $this->_app_developer; }

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

        // get the signed request
        $this->_signed_request = $this->facebook->getSignedRequest();

        if ( !empty($this->_signed_request) ) {

            // get the user id
            $this->user_id = $this->facebook->getUser();

            if ( !empty($this->user_id) ) {
                // get user access token (preferably the long-lived access token)
                $this->_access_token = $this->_getLongLivedAccessToken();
            }

            // by testing for the presence of the user_id and oauth_token parameters,
            // we can determine if the user has authorized our application
            if ( !empty($this->user_id) && !empty($this->_access_token) ) {

                $this->_authenticated = true;
                $this->debug(__CLASS__.':: User ('.$this->user_id.') is authenticated');

            } else {

                $this->_authenticated = false;
                $this->debug(__CLASS__.':: User is not authenticated, unable to obtain User ID and/or Access Token', null, 3);

            }

            // is this a tab?
            if ( SAF_Config::pageType() == SAF_Config::PAGE_TYPE_TAB ) {

                // are we viewing this within a fan page?
                if ( isset($this->_signed_request['page']) ) {

                    // get page id
                    $this->page_id = $this->_signed_request['page']['id'];
                    $this->debug(__CLASS__.':: User is viewing tab on fan page ('.$this->page_id.')');

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

            }

            // are we the app developer?
            $this->_app_developer = $this->_isAppDeveloper();

            // remove some params we don't want nor need to store since we create our own
            unset($this->_signed_request['page']);
            unset($this->_signed_request['user']);
            unset($this->_signed_request['user_id']);

            // add our own useful Social App Framework parameter(s) to the signed_request object
            $this->_signed_request['saf_user_id'] = $this->user_id;
            $this->_signed_request['saf_authenticated'] = $this->_authenticated;
            $this->_signed_request['saf_page_id'] = $this->page_id;
            $this->_signed_request['saf_page_liked'] = $this->_page_liked;
            $this->_signed_request['saf_page_admin'] = $this->_page_admin;
            $this->_signed_request['saf_app_developer'] = $this->_app_developer;

            // add our social app framework user data into the session
            SAF_Session::setPersistentData('signed_request_obj', $this->_signed_request);

            $this->debug(__CLASS__.':: SAF Signed request data:', $this->_signed_request);

        // we are looking at the tab or app outside of the Facebook chrome
        } else {

            // make sure we clear all SAF Session data since we can't assume anything is still valid
            $this->debug(__CLASS__.':: No signed request. Wipe session data.');
            SAF_Session::clearAllPersistentData();

            // tab
            if (SAF_Config::pageType() == SAF_Config::PAGE_TYPE_TAB) {

                $this->debug(__CLASS__.':: No signed request. Viewing tab outside of Facebook.', null, 3, false);

                if ( SAF_Config::srRedirectTab() == true ) {
                    header("Location: ".SAF_Config::srRedirectTabURL());
                    exit;
                }

            }

            // app
            if (SAF_Config::pageType() == SAF_Config::PAGE_TYPE_APP) {

                $this->debug(__CLASS__.':: No signed request. Viewing app outside of Facebook.', null, 3, false);

                if ( SAF_Config::srRedirectApp() == true ) {
                    header("Location: ".SAF_Config::srRedirectAppURL());
                    exit;
                }

            }

            // widget
            if (SAF_Config::pageType() == SAF_Config::PAGE_TYPE_WIDGET) {
                $this->debug(__CLASS__.':: No signed request. Viewing widget.', null, 3, false);
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
     * DETERMINE IF USER IS THE APP DEVELOPER
     *
     * @access    private
     * @return    boolean
     */
    private function _isAppDeveloper() {
        // explode our comma seperated developer ids into an array
        $developers = preg_replace('/\s+/', '', SAF_Config::fbDevelopers());
        $developers = explode(',', $developers);

        if ( in_array($this->user_id, $developers) == true ) {
            $this->debug(__CLASS__.':: User is the app developer');
            return true;
        } else {
            return false;
        }
    }

    // ------------------------------------------------------------------------

    /**
     * GET LONG-LIVED ACCESS TOKEN
     *
     * Gives us a 60 day access token by exchanging the short-lived token
     * for a long-lived one
     *
     * @access    private
     * @return    string
     */
    private function _getLongLivedAccessToken() {
        // get access token
        $access_token = $this->facebook->getAccessToken();

        // get long-lived access token
        $url = 'oauth/access_token?grant_type=fb_exchange_token&client_id=%s&client_secret=%s&fb_exchange_token=%s';
        $url = sprintf($url, $this->getAppID(), $this->getAppSecret(), $access_token);
        $access_token_response = SAF_FBHelper::graph_request($url, false);

        // response returns a query string, output it as an associative array
        $response_params = array();
        parse_str($access_token_response, $response_params);

        // override the old access token with our new long-lived one
        if ( !empty($response_params) ) {
            if (isset($response_params['access_token'])) {
                $access_token = $response_params['access_token'];
            }
        }

        return $access_token;
    }

    // ------------------------------------------------------------------------

}

/* End of file SAF_SignedRequest.php */
/* Location: ./socialappframework/libraries/SAF_SignedRequest.php */
