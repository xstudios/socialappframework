<?php
/*
 * This file is part of the Social App Framework package.
 * (c) 2011-2013 X Studios
 *
 * You should have received a copy of the license (license.txt) distributed
 * with this package. If not, see <http://socialappframework.com/license/>.
 */

/**
 * Social App Framework User class
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
abstract class SAF_User extends SAF_Page {

    private $_fb_user;

    private $_extended_perms = ''; // extended perms we are asking for
    private $_granted_perms  = array(); // extended perms the user granted
    private $_revoked_perms  = array(); // extended perms the user revoked

    private $_redirect_url;

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

    public function getUserProfileUrl() { return $this->_getUserValue('link'); }
    public function getUserProfilePicture() {
        $picture = $this->_getUserValue('picture');
        if (!empty($picture)) {
            $picture = $picture['data']['url'];
        } else {
            $picture = FB_Helper::picture_url($this->getUserId());
        }
        return $picture;
    }

    public function getExtendedPerms() { return $this->_extended_perms; }
    public function getGrantedPerms() { return $this->_granted_perms; }
    public function getRevokedPerms() { return $this->_revoked_perms; }

    // override's the SDK's native method
    public function getLoginUrl() {
        $params = array(
            'scope'        => $this->_extended_perms,
            'redirect_uri' => $this->_redirect_url
        );
        return parent::getLoginUrl($params);
    }
    // override's the SDK's native method
    public function getLogoutUrl() {
        $params = array( 'next' => SAF_Config::getLogoutRoute() );
        return parent::getLogoutUrl($params);
    }

    public function getLoginLink() { return FB_Helper::login_link($this->getLoginUrl()); }
    public function getLogoutLink() { return FB_Helper::logout_link($this->getLogoutUrl()); }

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
        }
        return false;
    }

    public function setRedirectUrl($value) {
        $this->_redirect_url = $value;
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
        $this->_redirect_url = $this->_determineRedirectUrl();

        // 3rd party cookie fix
        $this->_thirdPartyCookieFix();

        // is the user a regular user or page admin and what extended perms should we ask for?
        if ( $this->isPageAdmin() == false ) {
            $this->_extended_perms = SAF_Config::getExtendedPerms();
        } else {
            $this->_extended_perms = SAF_Config::getExtendedPermsAdmin();
        }

        // failsafe, use the user id or 'me', which allows us to still
        // get public user data if we know the user id since all we need
        // is the app access token and not a user access token
        $uid = $this->_user_id ? $this->_user_id : 'me';

        // do we have enough info to attempt to get user data?
        $access_token = $this->getAccessToken();
        $proceed = true;
        if ($uid === 'me' && $access_token === $this->getApplicationAccessToken()) {
            $proceed = false;
        }

        // if we have an access token and it's not the app access token
        //$access_token = $this->getAccessToken();
        //if ( !empty($access_token) ) {
        if ($proceed === true) {

            // we have a user id and an access token, so probably a logged in user...
            // if not, we'll get an exception, which we will handle below
            try {

                $this->_fb_user = $this->api('/'.$uid, 'GET', array(
                    //'access_token' => $access_token,
                    'fields' => SAF_Config::getGraphUserFields()
                ));

                // if we have user data
                if ( !empty($this->_fb_user) ) {

                    // user is authenticated (obviously since we have user data)
                    $this->_authenticated = true;

                    // if this is a facebook connect app this is where we will
                    // finally get a user id as there is no signed request
                    if (SAF_Config::getAppType() == SAF_Config::APP_TYPE_FACEBOOK_CONNECT) {
                        $this->_user_id = $this->_fb_user['id'];
                    }

                    // check user permissions if we are asking for any
                    //if ( !empty($this->_extended_perms) ) {
                        $this->_checkPermissions();
                    //}

                    // is the user the app developer?
                    $this->_app_developer = $this->_isAppDeveloper();

                    // add our own useful social app framework parameter(s) to the fb_user object
                    $this->_fb_user['saf_perms_granted'] = $this->_granted_perms;
                    $this->_fb_user['saf_perms_revoked'] = $this->_revoked_perms;
                    $this->_fb_user['saf_page_admin'] = $this->isPageAdmin();
                    $this->_fb_user['saf_app_developer'] = $this->_app_developer;
                    $this->_fb_user['saf_authenticated'] = $this->_authenticated;

                    // add our social app framework user data into the session as well
                    //$this->setPersistentData('saf_user', $this->_fb_user);

                    $this->debug(__CLASS__.':: User ('.$this->_user_id.') is authenticated with data:', $this->_fb_user);

                }

            } catch (FacebookApiException $e) {

                $this->debug(__CLASS__.':: '.$e, null, 3);
                $this->_handleException();

            }

        } else {

            //$this->debug(__CLASS__.':: No Access Token. Unable to get user data.', null, 3);
            $this->_handleException();

        }

        $this->debug('--------------------');
    }

    // ------------------------------------------------------------------------
    // PRIVATE METHODS
    // ------------------------------------------------------------------------

    /**
     * THIRD PARTY COOKIE FIX
     *
     * Checks if a session cookie is not set and if so, automatically redirects
     * the user to the base URL with a 'saf_redirect' URL param. The app then
     * starts the session on the 'real' server and immediately redirects back
     * to the proper URL (tab or convas);
     *
     * @access    private
     * @return    string
     */
    private function _thirdPartyCookieFix() {
        if (SAF_Config::getThirdPartyCookieFix() == true) {
            // if the app is anything other than a Facebook Connect app
            if (SAF_Config::getAppType() != SAF_Config::APP_TYPE_FACEBOOK_CONNECT) {
                // if there is no cookie set then we have 3rd party cookie problem
                if ( !isset($_COOKIE[session_name()]) ) {
                    // redirect the user to the real server
                    $redirect_param = '?saf_redirect='.urlencode($this->_determineRedirectUrl());
                    echo '<script>top.location.href = "'.SAF_Config::getBaseUrl().$redirect_param.'";</script>';
                    exit();
                }
            }
        }
    }

    // ------------------------------------------------------------------------

    /**
     * DETERMINE REDIRECT URL
     *
     * Returns the proper redirect URL for use with getLoginUrl()
     *
     * @access    private
     * @return    string
     */
    private function _determineRedirectUrl() {
        switch (SAF_Config::getAppType()) {

            // tab
            case SAF_Config::APP_TYPE_TAB:
                return $this->getPageTabUrl();
                break;

            // canvas app
            case SAF_Config::APP_TYPE_CANVAS:
                return $this->getCanvasUrl();
                break;

            // facebook connect
            default:
                return SAF_Config::getBaseUrl();

        }
    }

    // ------------------------------------------------------------------------

    /**
     * HANDLE EXCEPTION
     *
     * @access    private
     * @return    void
     */
    private function _handleException() {
        // wipe the 'saf_user_obj' session object
        //$this->clearPersistentData('saf_user');

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

            // add each permission to our granted permissions
            foreach($permissions as $key => $value) {
                array_push($this->_granted_perms, $key);
            }

            //$this->_granted_perms = $permissions;

            // loop through all user's permissions and see if they have everything we require
            /*foreach($extended_perms as $perm) {
                if ( !isset($permissions[$perm]) || $permissions[$perm] != 1 ) {
                    array_push($this->_revoked_perms, $perm);
                } else {
                    array_push($this->_granted_perms, $perm);
                }
            }*/

        } catch (FacebookApiException $e) {

            $this->debug(__CLASS__.':: Unable to check permissions. '.$e, null, 3, true);

        }

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
