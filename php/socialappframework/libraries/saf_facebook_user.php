<?php if ( ! defined('SOCIAL_APP_FRAMEWORK') ) exit('No direct script access allowed');
/**
 * Social App Framework Facebook User class
 *
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 * @version      1.0
 * @copyright    2012 X Studios
 * @link         http://www.xstudiosinc.com
 *
 * You should have received a copy of the license along with this program.
 * If not, see <http://socialappframework.com/license/>.
 */
abstract class SAF_Facebook_User extends SAF_Fan_Page {

    private $_fb_user;

    private $_extended_perms = ''; // extended perms we are asking for
    private $_granted_perms  = array(); // extended perms the user granted
    private $_revoked_perms  = array(); // extended perms the user revoked

    private $_redirect_url;
    private $_login_url;
    private $_logout_url;

    private $_login_link;
    private $_logout_link;

    private $_app_developer = false;
    private $_authenticated = false;

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------
    public function getUserData() { return $this->_fb_user; }

    public function getUserName() { return $this->_getUserValue('name', ''); }
    public function getUserFirstName() { return $this->_getUserValue('first_name', ''); }
    public function getUserLastName() { return $this->_getUserValue('last_name', ''); }
    public function getUserGender() { return $this->_getUserValue('gender'); }

    public function getUserEmail() { return $this->_getUserValue('email'); }

    public function getUserProfileURL() { return $this->_getUserValue('link'); }
    public function getUserProfilePicture() {
        $picture = $this->_getUserValue('picture');
        if (!empty($picture)) {
            $picture = $picture['data']['url'];
        } else {
            $picture = FB_Helper::picture_url($this->getUserID());
        }
        return $picture;
    }

    public function getExtendedPerms() { return $this->_extended_perms; }
    public function getGrantedPerms() { return $this->_granted_perms; }
    public function getRevokedPerms() { return $this->_revoked_perms; }

    //public function getLoginURL() { return $this->_login_url; }
    //public function getLogoutURL() { return $this->_logout_url; }

    public function getLoginLink() { return $this->_login_link; }
    public function getLogoutLink() { return $this->_logout_link; }

    public function isAppDeveloper() { return $this->_app_developer; }
    public function isAuthenticated() { return $this->_authenticated; }

    /**
     * CHECK IF USER HAS PERMISSION
     * Determine if a user has allowed a specific permission
     *
     * @access    public
     * @param     string $perm permission to check
     * @return    bool
     */
    public function hasPermission($perm) {
        if ( in_array($perm, $this->_granted_perms) ) {
            return true;
        } else {
            return false;
        }
    }

    public function setRedirectURL($value) {
        $this->_redirect_url = $value;
        // update login url/link too
        $this->_login_url = $this->_getLoginURL();
        $this->_login_link = FB_Helper::login_link($this->_login_url);
    }

    // ------------------------------------------------------------------------

    /**
     * CONSTRUCTOR
     *
     * @access    public
     * @return    void
     */
    public function __construct() {
        parent::__construct();

        // determine our redirect url
        switch (SAF_Config::getAppType()) {

            // tab
            case SAF_Config::APP_TYPE_TAB:
                if (SAF_Config::getForceSessionRedirect() == true) {
                    $redirect_param = '?saf_redirect='.urlencode($this->getPageTabURL());
                    $this->_redirect_url = SAF_Config::getBaseURL().$redirect_param;
                } else {
                    $this->_redirect_url = $this->getPageTabURL();
                }
                break;

            // canvas app
            case SAF_Config::APP_TYPE_CANVAS:
                if (SAF_Config::getForceSessionRedirect() == true) {
                    $redirect_param = '?saf_redirect='.urlencode($this->getCanvasAppURL());
                    $this->_redirect_url = SAF_Config::getBaseURL().$redirect_param;
                } else {
                    $this->_redirect_url = $this->getCanvasAppURL();
                }
                break;

            // facebook connect
            default:
                $this->_redirect_url = SAF_Config::getBaseURL();

        }

        // is the user a regular user or page admin and what extended perms should we ask for?
        if ( $this->isPageAdmin() == false ) {
            $this->_extended_perms = SAF_Config::getExtendedPerms();
        } else {
            $this->_extended_perms = SAF_Config::getExtendedPermsAdmin();
        }

        // login URL (always determine this in case we need more permissions later from the user)
        $this->_login_url = $this->_getLoginURL();
        $this->_login_link = FB_Helper::login_link($this->_login_url);

        // if we have an access token
        $access_token = $this->getAccessToken();
        if ( !empty($access_token) ) {

            // we have a user id and an access token, so probably a logged in user...
            // if not, we'll get an exception, which we will handle below
            try {

                $this->_fb_user = $this->api('/me', 'GET', array(
                    'access_token' => $this->getAccessToken(),
                    'fields' => SAF_Config::getGraphUserFields()
                ));

                // if we have user data
                if ( !empty($this->_fb_user) ) {

                    // logout URL
                    $params = array( 'next' => $this->_redirect_url );
                    $this->_logout_url = $this->getLogoutUrl($params);
                    $this->_logout_link = FB_Helper::logout_link($this->_logout_url);

                    // user is authenticated (obviously since we have user data)
                    $this->_authenticated = true;

                    // if this is a facebook connect app this is where we will
                    // finally get a user id as there is no signed request
                    if (SAF_Config::getAppType() == SAF_Config::APP_TYPE_FACEBOOK_CONNECT) {
                        $this->_user_id = $this->_fb_user['id'];
                    }

                    // check user permissions if we are asking for any
                    if ( !empty($this->_extended_perms) ) {
                        $this->_checkPermissions();
                    }

                    // is the user the app developer?
                    $this->_app_developer = $this->_isAppDeveloper();

                    // add our own useful social app framework parameter(s) to the fb_user object
                    $this->_fb_user['saf_perms_granted'] = $this->_granted_perms;
                    $this->_fb_user['saf_perms_revoked'] = $this->_revoked_perms;
                    $this->_fb_user['saf_page_admin'] = $this->isPageAdmin();
                    $this->_fb_user['saf_app_developer'] = $this->_app_developer;
                    $this->_fb_user['saf_authenticated'] = $this->_authenticated;

                    // add our social app framework user data into the session as well
                    $this->setPersistentData('saf_user', $this->_fb_user);

                    $this->debug(__CLASS__.':: User ('.$this->_user_id.') is authenticated with data:', $this->_fb_user);

                }

            } catch (FacebookApiException $e) {

                $this->debug(__CLASS__.':: '.$e, null, 3);
                $this->_handleException();

            }

        } else {

            $this->_handleException();

        }

        $this->debug('--------------------');
    }

    // ------------------------------------------------------------------------
    // PRIVATE METHODS
    // ------------------------------------------------------------------------

    /**
     * HANDLE EXCEPTION
     *
     * @access    private
     * @return    void
     */
    private function _handleException() {
        // wipe the 'saf_user_obj' session object
        $this->clearPersistentData('saf_user');

        // facebook connect app
        if (SAF_Config::getAppType() == SAF_Config::APP_TYPE_FACEBOOK_CONNECT) {
            $this->debug(__CLASS__.':: No user data. Viewing Facebook Connect app.', null, 3);
        }

        // force admin to login to the app if desired
        if ($this->isPageAdmin() == true && SAF_Config::getAutoRequestPermsAdmin() == true) {
            echo '<script>top.location.href = "'.$this->_login_url.'";</script>';
            exit;
        }

        // if it's a tab app and we are auto-requesting perms then direct user to login url
        if (SAF_Config::getAppType() == SAF_Config::APP_TYPE_TAB) {
            if (SAF_Config::getAutoRequestPermsTab() == true) {
                echo '<script>top.location.href = "'.$this->_login_url.'";</script>';
                exit;
            }
        }

        // if it's a canvas app and we are auto-requesting perms then direct user to login url
        if (SAF_Config::getAppType() == SAF_Config::APP_TYPE_CANVAS) {
            if (SAF_Config::getAutoRequestPermsCanvas() == true) {
                echo '<script>top.location.href = "'.$this->_login_url.'";</script>';
                exit;
            }
        }

        // proceed knowing we require user login and/or authentication
        $this->debug(__CLASS__.':: User is not authenticated. Prompt user to login...', null, 3);
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
        $developers = preg_replace('/\s+/', '', SAF_Config::getDevelopers());
        $developers = explode(',', $developers);

        if ( in_array($this->_user_id, $developers) == true ) {
            $this->debug(__CLASS__.':: User is the app developer.');
            return true;
        } else {
            return false;
        }
    }

    // ------------------------------------------------------------------------

    /**
     * CHECK PERMISSIONS
     *
     * @access    private
     * @return    void
     */
    private function _checkPermissions() {
        // explode our comma seperated perms into an array
        $extended_perms = preg_replace('/\s+/', '', $this->_extended_perms);
        $extended_perms = explode(',', $extended_perms);

        try {
            // check permissions list
            $permissions_list = $this->api('/me/permissions', 'GET', array(
                'access_token' => $this->getAccessToken()
            ));

            // set permissions equal to the resulting data
            $permissions = $permissions_list['data'][0];

            // loop through all user's permissions and see if they have everything we require
            foreach($extended_perms as $perm) {
                if ( !isset($permissions[$perm]) || $permissions[$perm] != 1 ) {
                    array_push($this->_revoked_perms, $perm);
                } else {
                    array_push($this->_granted_perms, $perm);
                }
            }

        } catch (FacebookApiException $e) {

            $this->debug(__CLASS__.':: Unable to check permissions. '.$e, null, 3, true);

        }

    }

    // ------------------------------------------------------------------------

    /**
     * GET LOGIN URL
     *
     * @access    private
     * @return    string
     */
    private function _getLoginURL() {
        $url = $this->getLoginUrl(array(
            'scope'        => $this->_extended_perms,
            'redirect_uri' => $this->_redirect_url
        ));
        return $url;
    }

    // ------------------------------------------------------------------------

    /**
     * GET USER VALUE
     *
     * Return a clean value whether the key exits or not
     *
     * @access    private
     * @param     string $key key to check for
     * @param     mixed $default default value if not set
     * @return    mixed
     */
    private function _getUserValue($key, $default=false) {
        if ( !isset($this->_fb_user[$key]) ) {
            return $default;
        } else {
            return $this->_fb_user[$key];
        }
    }

    // ------------------------------------------------------------------------

}

/* End of file */
