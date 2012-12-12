<?php
/**
 * Social App Framework Session class
 *
 * We DO NOT extend SAF_Base as this class can be included
 * in any script to assist with managing SAF Sessions
 *
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 * @version      1.0
 * @copyright    2012 X Studios
 * @link         http://www.xstudiosinc.com
 *
 * You should have received a copy of the license along with this program.
 * If not, see <http://socialappframework.com/license/>.
 */

class SAF_Session {

    private static $supported_keys = array(
        'app_id',
        'access_token',
        'signed_request_obj',
        'page_obj',
        'user_obj'
    );

    // ------------------------------------------------------------------------
    // GETTERS
    // ------------------------------------------------------------------------

    // app info
    public static function getAppID() {
        return self::getPersistentData('app_id');
    }

    // ------------------------------------------------------------------------

    // signed request info
    public static function getSignedRequestData() {
        return self::getPersistentData('signed_request_obj', array());
    }

    public static function getAccessToken() {
        // first, look in the signed request
        if (self::_getPersistentSignedRequestData('saf_access_token')) {
            return self::_getPersistentSignedRequestData('saf_access_token');
        // fallback to looking in the user data
        } else {
            return self::_getPersistentUserData('saf_access_token');
        }
    }

    public static function isPageAdmin() {
        return self::_getPersistentSignedRequestData('saf_page_admin');
    }
    public static function isPageLiked() {
        return self::_getPersistentSignedRequestData('saf_page_liked');
    }

    // ------------------------------------------------------------------------

    // page info
    public static function getPageData() {
        return self::getPersistentData('page_obj', array());
    }

    public static function getPageID() {
        return self::_getPersistentPageData('id');
    }
    public static function getPageName() {
        return self::_getPersistentPageData('name');
    }
    public static function getPageProfileURL() {
        return self::_getPersistentPageData('link');
    }
    public static function getPageProfilePicture() {
        return FB_Helper::picture_url(self::getPageID());
    }

    public static function getPageLikes() {
        return self::_getPersistentPageData('likes');
    }
    public static function getPageCategory() {
        return self::_getPersistentPageData('category');
    }
    public static function getPageWebsite() {
        return self::_getPersistentPageData('website');
    }

    public static function getPageTabURL() {
        return self::_getPersistentPageData('saf_page_tab_url');
    }
    public static function getAddPageTabURL() {
        return self::_getPersistentPageData('saf_add_page_tab_url');
    }
    public static function getCanvasAppURL() {
        return self::_getPersistentPageData('saf_canvas_app_url');
    }

    public static function isPagePublished() {
        return self::_getPersistentPageData('is_published');
    }
    public static function hasAddedApp() {
        return self::_getPersistentPageData('has_added_app');
    }
    public static function hasPageRestrictions() {
        return self::_getPersistentPageData('saf_page_restrictions');
    }

    // ------------------------------------------------------------------------

    // user info
    public static function getUserData() {
        return self::getPersistentData('user_obj', array());
    }

    public static function getUserID() {
        return self::_getPersistentUserData('id');
    }
    public static function getUserName() {
        return self::_getPersistentUserData('name');
    }
    public static function getUserFirstName() {
        return self::_getPersistentUserData('first_name');
    }
    public static function getUserLastName() {
        return self::_getPersistentUserData('last_name');
    }
    public static function getUserGender() {
        return self::_getPersistentUserData('gender');
    }
    public static function getUserEmail() {
        return self::_getPersistentUserData('email');
    }

    public static function getUserProfileURL() {
        return self::_getPersistentUserData('link');
    }
    public static function getUserProfilePicture() {
        return FB_Helper::picture_url(self::getUserID());
    }

    public static function getUserGrantedPerms() {
        return self::_getPersistentUserData('saf_perms_granted', array());
    }
    public static function getUserRevokedPerms() {
        return self::_getPersistentUserData('saf_perms_revoked', array());
    }

    public static function isAppDeveloper() {
        return self::_getPersistentUserData('saf_app_developer');
    }
    public static function isAuthenticated() {
        return self::_getPersistentUserData('saf_authenticated');
    }

    public static function hasPermission($perm) {
        if ( in_array($perm, self::getUserGrantedPerms()) ) {
            return true;
        } else {
            return false;
        }
    }

    // ------------------------------------------------------------------------

    /**
     * INIT
     *
     * @access    public
     * @param     string $appID the app id of the app currently running ( eg - $facebook->getAppId() )
     * @return    void
     */
    public static function init($app_id=null) {
        // start session
        self::start();

        // bail if no app id supplied
        if ($app_id == null) return;

        // check app id
        if ( self::getPersistentData('app_id') == false || self::getPersistentData('app_id') !== $app_id ) {
            // wipe existing SAF Session data
            self::clearAllPersistentData();

            // set app id
            self::setPersistentData('app_id', $app_id);

            XS_Debug::addMessage(__METHOD__.':: Initiated new SAF Session.');

            XS_Debug::addMessage('--------------------');
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Start a session if one has not been started by checking the session id
     *
     * @access    public
     * @return    void
     */
    public static function start() {
        if (!session_id()) {
            session_start();
        }
    }

    // ------------------------------------------------------------------------

    /**
     * End SAF session
     *
     * @access    public
     * @return    void
     */
    public static function end() {
        self::destroySession();
    }

    // ------------------------------------------------------------------------

    /**
     * Destroy session
     * Destroys the entire session, including non-SAF related data
     *
     * @access    public
     * @return    void
     */
    public static function destroySession() {
        // kill session
        $_SESSION = array();
        session_unset();
        session_destroy();
    }

    // ------------------------------------------------------------------------

    /**
     * Stores the given ($key, $value) pair, so that future calls to
     * getPersistentData($key) return $value. This call may be in another request.
     *
     * @access    public
     * @param     string $key
     * @param     mixed $value
     * @return    void
     */
    public static function setPersistentData($key, $value) {
        if (!in_array($key, self::$supported_keys)) {
            XS_Debug::logError(__METHOD__.':: Unsupported key ('.$key.')', true);
            return;
        }

        $session_var_name = self::_constructSessionVariableName($key);
        $_SESSION[$session_var_name] = $value;
    }

    // ------------------------------------------------------------------------

    /**
     * Get the data for $key, persisted by SAF_Session::setPersistentData()
     *
     * @access    public
     * @param     string $key The key of the data to retrieve
     * @param     boolean $default The default value to return if $key is not found
     * @return    mixed
     */
    public static function getPersistentData($key, $default=false) {
        if (!in_array($key, self::$supported_keys)) {
            XS_Debug::logError(__METHOD__.':: Unsupported key ('.$key.')', true);
            return $default;
        }

        $session_var_name = self::_constructSessionVariableName($key);
        return isset($_SESSION[$session_var_name]) ? $_SESSION[$session_var_name] : $default;
    }

    // ------------------------------------------------------------------------

    /**
     * Clear the data with $key from the persistent storage
     *
     * @access    public
     * @param     string $key
     * @return    void
     */
    public static function clearPersistentData($key) {
        if (!in_array($key, self::$supported_keys)) {
            XS_Debug::logError(__METHOD__.':: Unsupported key ('.$key.')', true);
            return;
        }

        $session_var_name = self::_constructSessionVariableName($key);
        unset($_SESSION[$session_var_name]);
    }

    // ------------------------------------------------------------------------

    /**
     * Clear all data from the persistent storage
     *
     * @access public
     * @return void
     */
    public static function clearAllPersistentData() {
        foreach (self::$supported_keys as $key) {
            self::clearPersistentData($key);
        }
        XS_Debug::addMessage(__METHOD__.':: Cleared all SAF session data.');
    }

    // ------------------------------------------------------------------------
    // PRIVATE METHODS
    // ------------------------------------------------------------------------

    /**
     * Helper to get to signed request data sub key
     * Actually returns $_SESSION['saf_signed_request_obj'][$key]
     *
     * @access    private
     * @param     string $key The key of the data to retrieve
     * @param     boolean $default The default value to return if $key is not found
     * @return    mixed
     */
    private static function _getPersistentSignedRequestData($key, $default=false) {
        $array = self::getPersistentData('signed_request_obj', array());
        if ( empty($array) ) return $default;

        return isset($array[$key]) ? $array[$key] : $default;
    }

    // ------------------------------------------------------------------------

    /**
     * Helper to get to page data sub key
     * Actually returns $_SESSION['saf_page_obj][$key]
     *
     * @access    private
     * @param     string $key The key of the data to retrieve
     * @param     boolean $default The default value to return if $key is not found
     * @return    mixed
     */
    private static function _getPersistentPageData($key, $default=false) {
        $array = self::getPersistentData('page_obj', array());
        if ( empty($array) ) return $default;

        return isset($array[$key]) ? $array[$key] : $default;
    }

    // ------------------------------------------------------------------------

    /**
     * Helper to get to user data sub key
     * Actually returns $_SESSION['saf_user_obj][$key]
     *
     * @access    private
     * @param     string $key The key of the data to retrieve
     * @param     boolean $default The default value to return if $key is not found
     * @return    mixed
     */
    private static function _getPersistentUserData($key, $default=false) {
        $array = self::getPersistentData('user_obj', array());
        if ( empty($array) ) return $default;

        return isset($array[$key]) ? $array[$key] : $default;
    }

    // ------------------------------------------------------------------------

    /**
     * Construct variable name just for our SAF persistent data
     * eg - 'key' becomes 'saf_key'
     *
     * access     private
     * @return    void
     */
    private static function _constructSessionVariableName($key) {
        return implode( '_', array('saf', $key) );
    }

    // ------------------------------------------------------------------------

}

/* End of file */
