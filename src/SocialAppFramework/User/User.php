<?php
/*
 * This file is part of the Social App Framework package.
 * (c) 2011-2013 X Studios
 *
 * You should have received a copy of the license (license.txt) distributed
 * with this package. If not, see <http://socialappframework.com/license/>.
 */

//namespace SocialAppFramework\User;

require_once dirname(__FILE__).'/../BaseSaf.php';

/**
 * Social App Framework User class
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
class User extends BaseSaf {

    // ------------------------------------------------------------------------
    // PRIVATE VARS
    // ------------------------------------------------------------------------

    /**
     * Facebook instance
     *
     * @access    private
     * @var       SafFacebook
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
     * Authenticated
     *
     * @access    private
     * @var       boolean
     */
    private $_authenticated = false;

    /**
     * User data
     *
     * @access    private
     * @var       array
     */
    private $_data;

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
     * User connection
     *
     * @access    private
     * @var       UserConnection
     */
    public $connection;

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------

    /**
     * Returns user's Facebook ID
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
     * Returns the user's full name
     *
     * @access    public
     * @return    string
     */
    public function getName() {
        return $this->_getValue('name', '');
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
     * Returns the user's age range
     *
     * @access    public
     * @return    object  containing min and max
     */
    public function getAgeRange() {
        return $this->_getValue('age_range');
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
     * Returns true if the user is the app developer
     *
     * @access    public
     * @return    string
     */
    public function isAppDeveloper() {
        return $this->_getValue('saf_app_developer');
    }

    /**
     * Returns true if the user is authenticated
     *
     * @access    public
     * @return    boolean
     */
    public function isAuthenticated() {
        return $this->_authenticated;
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

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    public
     * @param     SafFacebook  $facebook
     * @param     string|int    $user_id
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
        // 3rd party cookie fix
        $this->_thirdPartyCookieFix();

        // if the user is the page admin he/she may require additional perms
        if ( $this->_facebook->sr->isPageAdmin() === true ) {
            $this->_facebook->setExtendedPerms(SAF_Config::getExtendedPermsAdmin());
        }

        // failsafe, use the user id or 'me', which allows us to still
        // get public user data if we know the user id since all we need
        // is the app access token and not a user access token
        $uid = $this->_facebook->getUser() ? $this->_facebook->getUser() : 'me';

        // we have a user id and an access token, so probably a logged in user...
        // if not, we'll get an exception, which we will handle below
        if ($this->_facebook->getUser()) {
            try {

                $this->_data = $this->_facebook->api('/'.$uid, 'GET', array(
                    //'access_token' => $access_token,
                    'fields' => SAF_Config::getGraphUserFields()
                ));

                // if we have user data
                if ( !empty($this->_data) ) {

                    // user is authenticated (we have data)
                    $this->_authenticated = true;

                    // set user ID
                    $this->_id = $this->_data['id'];

                    // check user permissions
                    $this->_checkPermissions();

                    // add our own useful social app framework parameter(s) to the fb_user object
                    $this->_data['saf_perms_granted'] = $this->_granted_perms;
                    $this->_data['saf_perms_revoked'] = $this->_revoked_perms;
                    $this->_data['saf_page_admin']    = $this->_facebook->sr->isPageAdmin();
                    $this->_data['saf_app_developer'] = $this->_isAppDeveloper();

                    // create user connection
                    //$this->connection = new UserConnection($this, $this->_facebook);

                    // set session data
                    $this->setSafPersistentData('user', $this->_data);

                    $this->debug(__CLASS__.':: User ('.$this->_id.') is authenticated with data:', $this->_data);

                }

            } catch (FacebookApiException $e) {

                // If the user is logged out, you can have a user ID even though
                // the access token is invalid. In this case, we'll get an
                // exception, so we should ask the user to login again.

                // clear session data - don't do this or we can't access the
                // user session data later on on AJAX requests.
                //$this->clearSafPersistentData('user');

                $this->debug(__CLASS__.':: '.$e->getMessage(), null, 3);
                $this->debug(__CLASS__.':: User is not authenticated. Prompt user to login...', null, 3);

            }
        } else {

            $this->debug(__CLASS__.':: User is not authenticated. Prompt user to login...', null, 3);

        }

        // create user connection
        $this->connection = new UserConnection($this, $this->_facebook);

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
                    $redirect_param = '?saf_redirect='.urlencode($this->_facebook->getRedirectUrl());
                    echo '<script>top.location.href = "'.SAF_Config::getBaseUrl().$redirect_param.'";</script>';
                    exit();
                }
            }
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
        }
        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Check permissions
     *
     * @access    private
     * @return    void
     */
    private function _checkPermissions() {
        // bail if we only have an app access token
        if ($this->_facebook->getAccessToken() === $this->_facebook->getAppAccessToken()) {
            return;
        }

        // explode our comma seperated perms into an array
        $extended_perms = preg_replace('/\s+/', '', $this->_facebook->getExtendedPerms());
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
                    array_push($this->_revoked_perms, $key);
                }
            }

        } catch (FacebookApiException $e) {

            $this->debug(__CLASS__.':: Unable to check permissions. '.$e->getMessage(), null, 3, true);

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
        // first, look in data we *should* have recevied from the graph
        if ( isset($this->_data[$key]) ) {
            return $this->_data[$key];
        }

        // second, look at the data we have in the session
        $session_data = $this->getSafPersistentData('user');
        if ( isset($session_data[$key]) ) {
            return $session_data[$key];
        }

        // if all else fails, we return the default value
        return $default;
    }

    // ------------------------------------------------------------------------

}

/* End of file */
