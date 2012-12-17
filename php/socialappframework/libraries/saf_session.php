<?php
/**
 * Social App Framework Session class
 *
 * Assist with managing SAF Sessions when a SAF instance is not directly
 * accessible. For example, an AJAX request.
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

    private $_app_id;

    // ------------------------------------------------------------------------
    // GETTERS
    // ------------------------------------------------------------------------

    public function getAppID() { return $this->_app_id; }

    // ------------------------------------------------------------------------

    // page info
    public function getPageData() {
        return $this->getPersistentData('page', array());
    }

    public function getPageID() {
        return $this->_getPersistentPageData('id');
    }
    public function getPageName() {
        return $this->_getPersistentPageData('name');
    }
    public function getPageProfileURL() {
        return $this->_getPersistentPageData('link');
    }
    public function getPageProfilePicture() {
        return FB_Helper::picture_url($this->getPageID());
    }

    public function getPageLikes() {
        return $this->_getPersistentPageData('likes');
    }
    public function getPageWebsite() {
        return $this->_getPersistentPageData('website');
    }

    public function getPageTabURL() {
        return $this->_getPersistentPageData('saf_page_tab_url');
    }
    public function getAddPageTabURL() {
        return $this->_getPersistentPageData('saf_add_page_tab_url');
    }
    public function getCanvasAppURL() {
        return $this->_getPersistentPageData('saf_canvas_app_url');
    }

    public function isPageLiked() {
        return $this->_getPersistentPageData('saf_page_liked');
    }
    public function isPagePublished() {
        return $this->_getPersistentPageData('is_published');
    }
    public function hasPageRestrictions() {
        return $this->_getPersistentPageData('saf_page_restrictions');
    }

    // ------------------------------------------------------------------------

    // user info
    public function getUserData() {
        return $this->getPersistentData('user', array());
    }

    public function getUserID() {
        return $this->_getPersistentUserData('id');
    }
    public function getUserName() {
        return $this->_getPersistentUserData('name');
    }
    public function getUserFirstName() {
        return $this->_getPersistentUserData('first_name');
    }
    public function getUserLastName() {
        return $this->_getPersistentUserData('last_name');
    }
    public function getUserGender() {
        return $this->_getPersistentUserData('gender');
    }
    public function getUserEmail() {
        return $this->_getPersistentUserData('email');
    }

    public function getUserProfileURL() {
        return $this->_getPersistentUserData('link');
    }
    public function getUserProfilePicture() {
        return FB_Helper::picture_url($this->getUserID());
    }

    public function getUserGrantedPerms() {
        return $this->_getPersistentUserData('saf_perms_granted', array());
    }
    public function getUserRevokedPerms() {
        return $this->_getPersistentUserData('saf_perms_revoked', array());
    }

    public function isPageAdmin() {
        return $this->_getPersistentUserData('saf_page_admin');
    }
    public function isAppDeveloper() {
        return $this->_getPersistentUserData('saf_app_developer');
    }
    public function isAuthenticated() {
        return $this->_getPersistentUserData('saf_authenticated');
    }

    public function hasPermission($perm) {
        if ( in_array($perm, $this->getUserGrantedPerms()) ) {
            return true;
        } else {
            return false;
        }
    }

    // ------------------------------------------------------------------------

    /**
     * CONSTRUCTOR
     *
     * @access    public
     * @param     string $app_id app id for the SAF session we want to access
     * @return    void
     */
    public function __construct($app_id) {
        // we'll need this for all method calls so we can construct the
        // session key name
        $this->_app_id = $app_id;
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
    public function setPersistentData($key, $value) {
        $session_var_name = $this->_constructSessionVariableName($key);
        $_SESSION[$session_var_name] = $value;
    }

    // ------------------------------------------------------------------------

    /**
     * Get the data for $key
     *
     * @access    public
     * @param     string $key The key of the data to retrieve
     * @param     boolean $default The default value to return if $key is not found
     * @return    mixed
     */
    public function getPersistentData($key, $default=false) {
        $session_var_name = $this->_constructSessionVariableName($key);
        return isset($_SESSION[$session_var_name]) ? $_SESSION[$session_var_name] : $default;
    }

    // ------------------------------------------------------------------------
    // PRIVATE METHODS
    // ------------------------------------------------------------------------

    /**
     * Helper to get to signed request data sub key
     * Actually returns $_SESSION['fb_APPID_saf_signed_request'][$key]
     *
     * @access    private
     * @param     string $key The key of the data to retrieve
     * @param     boolean $default The default value to return if $key is not found
     * @return    mixed
     */
    private function _getPersistentSignedRequestData($key, $default=false) {
        $array = $this->getPersistentData('signed_request', array());
        if ( empty($array) ) return $default;

        return isset($array[$key]) ? $array[$key] : $default;
    }

    // ------------------------------------------------------------------------

    /**
     * Helper to get to page data sub key
     * Actually returns $_SESSION['fb_APPID_saf_page][$key]
     *
     * @access    private
     * @param     string $key The key of the data to retrieve
     * @param     boolean $default The default value to return if $key is not found
     * @return    mixed
     */
    private function _getPersistentPageData($key, $default=false) {
        $array = $this->getPersistentData('page', array());
        if ( empty($array) ) return $default;

        return isset($array[$key]) ? $array[$key] : $default;
    }

    // ------------------------------------------------------------------------

    /**
     * Helper to get to user data sub key
     * Actually returns $_SESSION['fb_APPID_saf_user][$key]
     *
     * @access    private
     * @param     string $key The key of the data to retrieve
     * @param     boolean $default The default value to return if $key is not found
     * @return    mixed
     */
    private function _getPersistentUserData($key, $default=false) {
        $array = $this->getPersistentData('user', array());
        if ( empty($array) ) return $default;

        return isset($array[$key]) ? $array[$key] : $default;
    }

    // ------------------------------------------------------------------------

    /**
     * Construct variable name just for our SAF persistent data
     * eg - 'key' becomes 'fb_APPID_saf_key'
     *
     * access     private
     * @return    void
     */
    private function _constructSessionVariableName($key) {
        return implode( '_', array('fb', $this->_app_id, 'saf', $key) );
    }

    // ------------------------------------------------------------------------

}

/* End of file */
