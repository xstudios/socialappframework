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
class SAF_User extends SAF_Debug {

    // ------------------------------------------------------------------------
    // PRIVATE VARS
    // ------------------------------------------------------------------------

    /**
     * Facebook instance
     *
     * @access    private
     * @var       SAF
     */
    private $_facebook;

    /**
     * User ID
     *
     * @access    private
     * @var       string|int
     */
    private $_id;

    /**
     * User data
     *
     * @access    private
     * @var       array
     */
    private $_data;

    /**
     * The permissions the app is asking for
     *
     * @access    private
     * @var       string
     */
    private $_extended_perms = '';

    /**
     * The permissions granted
     *
     * @access    private
     * @var       array
     */
    private $_granted_perms  = array();

    /**
     * The permissions revoked
     *
     * @access    private
     * @var       array
     */
    private $_revoked_perms  = array();

    /**
     * Redirect URL used for login URL
     *
     * @var    string
     */
    private $_redirect_url;

    /**
     * User connection
     *
     * @access    private
     * @var       SAF_User_Connection
     */
    public $connection;

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------

    /**
     * Returns the user ID
     *
     * @access    public
     * @return    string|int
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * Returns the user data
     *
     * @access    public
     * @return    array
     */
    public function getData() {
        return $this->_data;
    }

    /**
     * Returns the user's full name
     *
     * @access    public
     * @return    string
     */
    public function getName() {
        return $this->_getValue('name', '');
    }

    /**
     * Returns the user's first name
     *
     * @access    public
     * @return    string
     */
    public function getFirstName() {
        return $this->_getValue('first_name', '');
    }

    /**
     * Returns the user's last name
     *
     * @access    public
     * @return    string
     */
    public function getLastName() {
        return $this->_getValue('last_name', '');
    }

    /**
     * Returns the user's gender
     *
     * @access    public
     * @return    string
     */
    public function getGender() {
        return $this->_getValue('gender');
    }

    /**
     * Returns the user's email
     *
     * @access    public
     * @return    string
     */
    public function getEmail() {
        return $this->_getValue('email');
    }

    /**
     * Returns the user's profile URL
     *
     * @access    public
     * @return    string
     */
    public function getProfileUrl() {
        return $this->_getValue('link');
    }

    /**
     * Returns the user's profile picture URL
     *
     * @access    public
     * @return    string
     */
    public function getProfilePicture() {
        $picture = $this->_getValue('picture');
        if (!empty($picture)) {
            if (isset($picture['data']['url'])) {
                return $picture['data']['url'];
            }
        }

        return FB_Helper::picture_url($this->_id);
    }

    /**
     * Returns the permissions the app requested
     *
     * @access    public
     * @return    string
     */
    public function getExtendedPerms() {
        return $this->_extended_perms;
    }

    /**
     * Returns the permissions the user granted
     *
     * @access    public
     * @return    array
     */
    public function getGrantedPerms() {
        return $this->_granted_perms;
    }

    /**
     * Returns the permissions the user revoked
     *
     * @access    public
     * @return    array
     */
    public function getRevokedPerms() {
        return $this->_revoked_perms;
    }

    /**
     * Returns the login URL
     *
     * Override's the Facebook SDK's native method
     *
     * @access    public
     * @return    string
     */
    public function getLoginUrl() {
        $params = array(
            'scope'        => $this->_extended_perms,
            'redirect_uri' => $this->_redirect_url
        );
        return $this->_facebook->getLoginUrl($params);
    }

    /**
     * Returns the logout URL
     *
     * Override's the Facebook SDK's native method
     *
     * @access    public
     * @return    string
     */
    public function getLogoutUrl() {
        $params = array( 'next' => SAF_Config::getLogoutRoute() );
        return $this->_facebook->getLogoutUrl($params);
    }

    /**
     * Returns the login link (anchor tag)
     *
     * @access    public
     * @return    string
     */
    public function getLoginLink() {
        return FB_Helper::login_link($this->getLoginUrl());
    }

    /**
     * Returns the logout link (anchor tag)
     *
     * @access    public
     * @return    string
     */
    public function getLogoutLink() {
        return FB_Helper::logout_link($this->getLogoutUrl());
    }

    /**
     * Returns true if the user is the app developer
     *
     * @access    public
     * @return    string
     */
    public function isAppDeveloper() {
        return $this->_getValue('saf_app_developer');
    }

    /**
     * Returns true if a user has permission
     *
     * @access    public
     * @param     string  $perm  permission to check
     * @return    boolean
     */
    public function hasPermission($perm) {
        if ( in_array($perm, $this->_granted_perms) ) {
            return true;
        }
        return false;
    }

    /**
     * Sets the redirect URL to be used with getLoginUrl()
     *
     * @access    public
     * @return    void
     */
    public function setRedirectUrl($value) {
        $this->_redirect_url = $value;
    }

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    public
     * @param     SAF         $facebook
     * @param     string|int  $user_id
     * @return    void
     */
    public function __construct($facebook, $user_id) {
        $this->_facebook = $facebook;
        $this->_id  = $user_id;

        $this->_init();
    }

    // ------------------------------------------------------------------------
    // PRIVATE METHODS
    // ------------------------------------------------------------------------

    /**
     * Init
     *
     * @access    private
     * @return    void
     */
    private function _init() {
        // determine our redirect url
        $this->_redirect_url = $this->_determineRedirectUrl();

        // 3rd party cookie fix
        $this->_thirdPartyCookieFix();

        // is the user a regular user or page admin and what extended perms should we ask for?
        if ( $this->_facebook->isPageAdmin() == false ) {
            $this->_extended_perms = SAF_Config::getExtendedPerms();
        } else {
            $this->_extended_perms = SAF_Config::getExtendedPermsAdmin();
        }

        // failsafe, use the user id or 'me', which allows us to still
        // get public user data if we know the user id since all we need
        // is the app access token and not a user access token
        $uid = $this->_facebook->getUserId() ? $this->_facebook->getUserId() : 'me';

        // we have a user id and an access token, so probably a logged in user...
        // if not, we'll get an exception, which we will handle below
        try {

            $this->_data = $this->_facebook->api('/'.$uid, 'GET', array(
                //'access_token' => $access_token,
                'fields' => SAF_Config::getGraphUserFields()
            ));

            // if we have user data
            if ( !empty($this->_data) ) {

                // set user ID
                $this->_id = $this->_data['id'];

                // if this is a facebook connect app this is where we will
                // finally get a user id as there is no signed request
                if (SAF_Config::getAppType() == SAF_Config::APP_TYPE_FACEBOOK_CONNECT) {
                    $this->_id = $this->_data['id'];
                }

                // check user permissions
                $this->_checkPermissions();

                // add our own useful social app framework parameter(s) to the fb_user object
                $this->_data['saf_perms_granted'] = $this->_granted_perms;
                $this->_data['saf_perms_revoked'] = $this->_revoked_perms;
                $this->_data['saf_page_admin']    = $this->_facebook->isPageAdmin();
                $this->_data['saf_app_developer'] = $this->_isAppDeveloper();

                // create user connection
                $this->connection = new SAF_User_Connection($this, $this->_facebook);

                $this->debug(__CLASS__.':: User ('.$this->_id.') is authenticated with data:', $this->_data);

            }

        } catch (FacebookApiException $e) {

            $this->debug(__CLASS__.':: '.$e, null, 3);
            $this->debug(__CLASS__.':: User is not authenticated. Prompt user to login...', null, 3);

        }

        $this->debug('--------------------');
    }

    // ------------------------------------------------------------------------

    /**
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
     * Returns the proper redirect URL for use with getLoginUrl()
     *
     * @access    private
     * @return    string
     */
    private function _determineRedirectUrl() {
        switch (SAF_Config::getAppType()) {

            // tab
            case SAF_Config::APP_TYPE_TAB:
                return $this->page->getTabUrl();
                break;

            // canvas app
            case SAF_Config::APP_TYPE_CANVAS:
                return $this->page->getCanvasUrl();
                break;

            // facebook connect
            default:
                return SAF_Config::getBaseUrl();

        }
    }

    // ------------------------------------------------------------------------

    /**
     * Returns true if the user is the app developer
     *
     * @access    private
     * @return    boolean
     */
    private function _isAppDeveloper() {
        // explode our comma seperated developer ids into an array
        $developers = preg_replace('/\s+/', '', SAF_Config::getDevelopers());
        $developers = explode(',', $developers);

        if ( in_array($this->_id, $developers) === true ) {
            $this->debug(__CLASS__.':: User is the app developer.');
            return true;
        } else {
            return false;
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Check permissions
     *
     * @access    private
     * @return    void
     */
    private function _checkPermissions() {
        // explode our comma seperated perms into an array
        $extended_perms = preg_replace('/\s+/', '', $this->_extended_perms);
        $required_perms = explode(',', $extended_perms);

        try {
            // call api
            $result = $this->_facebook->api('/me/permissions', 'GET', array(
                'access_token' => $this->_facebook->getAccessToken()
            ));

            // set granted permissions
            $permissions = $result['data'][0];

            // add each permission to our granted permissions
            foreach ($permissions as $key => $value) {
                array_push($this->_granted_perms, $key);
            }

            // loop through all required permissions and see if they granted
            // everything we require
            foreach ($required_perms as $key => $value) {
                if ( in_array($key, $this->_granted_perms) === false ) {
                    $this->debug('Added revoked perm:', $key);
                    array_push($this->_revoked_perms, $key);
                }
            }

        } catch (FacebookApiException $e) {

            $this->debug(__CLASS__.':: Unable to check permissions. '.$e, null, 3, true);

        }

    }

    // ------------------------------------------------------------------------

    /**
     * Returns a user key value whether it exists or not
     *
     * @access    private
     * @param     string $key key to check for
     * @param     mixed $default default value if not set
     * @return    mixed
     */
    private function _getValue($key, $default=false) {
        if ( !isset($this->_data[$key]) ) {
            return $default;
        }

        return $this->_data[$key];
    }

    // ------------------------------------------------------------------------

}

/* End of file */
