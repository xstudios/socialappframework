<?php
/**
 * Social App Framework Config class
 *
 * Helps manage the SAF config so we have a nice and easy get/set methods
 * as well as helpful code completion in IDEs
 *
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 * @version      1.0
 * @copyright    2012 X Studios
 * @link         http://www.xstudiosinc.com
 *
 * You should have received a copy of the license along with this program.
 * If not, see <http://socialappframework.com/license/>.
 */
class SAF_Config {

    // CONSTANTS
    const APP_TYPE_TAB              = 'tab';
    const APP_TYPE_CANVAS           = 'canvas';
    const APP_TYPE_FACEBOOK_CONNECT = 'facebook connect';

    // app type
    private static $_app_type = self::APP_TYPE_FACEBOOK_CONNECT;

    // facebook vars
    private static $_app_id;
    private static $_app_secret;
    private static $_file_upload = true;

    private static $_app_name      = '';
    private static $_app_namespace = '';
    private static $_admins        = '';
    private static $_developers    = '';

    // app url vars
    private static $_base_url = '';

    // permission vars
    private static $_extended_perms            = '';
    private static $_extended_perms_admin      = '';

    // signed request vars
    private static $_fan_page_hash       = '';
    private static $_force_facebook_view = false;

    // graph fields
    private static $_user_fields = '';
    private static $_page_fields = '';

    // logout route
    private static $_logout_route;

    // 3rd party cookie fix
    private static $_third_party_cookie_fix = false;

    // ------------------------------------------------------------------------

    /**
     * Set app type
     *
     * @param    string  $value
     */
    public static function setAppType($value) {
        self::$_app_type = $value;
    }

    /**
     * Get app id
     */
    public static function getAppType() {
        return self::$_app_type;
    }

    // ------------------------------------------------------------------------
    // FACEBOOK
    // ------------------------------------------------------------------------

    /**
     * Set app id
     *
     * @param    string  $value
     */
    public static function setAppId($value) {
        self::$_app_id = $value;
    }

    /**
     * Get app id
     */
    public static function getAppId() {
        return self::$_app_id;
    }

    // ------------------------------------------------------------------------

    /**
     * Set app secret
     *
     * @param    string  $value
     */
    public static function setAppSecret($value) {
        self::$_app_secret = $value;
    }

    /**
     * Get app secret
     */
    public static function getAppSecret() {
        return self::$_app_secret;
    }

    // ------------------------------------------------------------------------

    /**
     * Set file upload
     *
     * @param    bool  $value
     */
    public static function setFileUpload($value) {
        self::$_file_upload = $value;
    }

    /**
     * Get file upload
     */
    public static function getFileUpload() {
        return self::$_file_upload;
    }

    // ------------------------------------------------------------------------

    /**
     * Set app name
     *
     * @param    string  $value
     */
    public static function setAppName($value) {
        self::$_app_name = $value;
    }

    /**
     * Get app name
     */
    public static function getAppName() {
        return self::$_app_name;
    }

    // ------------------------------------------------------------------------

    /**
     * Set app namespace
     *
     * @param    string  $value
     */
    public static function setAppNamespace($value) {
        self::$_app_namespace = $value;
    }

    /**
     * Get app namespace
     */
    public static function getAppNamespace() {
        return self::$_app_namespace;
    }

    // ------------------------------------------------------------------------

    /**
     * Set admins
     *
     * @param    string  $value  comma delimited
     */
    public static function setAdmins($value) {
        self::$_admins = $value;
    }

    /**
     * Get admins
     */
    public static function getAdmins() {
        return self::$_admins;
    }

    // ------------------------------------------------------------------------

    /**
     * Set developers
     *
     * @param    string  $value  comma delimited
     */
    public static function setDevelopers($value) {
        self::$_developers = $value;
    }

    /**
     * Get developers
     */
    public static function getDevelopers() {
        return self::$_developers;
    }

    // ------------------------------------------------------------------------
    // APP URLS
    // ------------------------------------------------------------------------

    /**
     * Set base URL
     *
     * @param    string  $value
     */
    public static function setBaseUrl($value) {
        self::$_base_url = $value;
    }

    /**
     * Get base URL
     */
    public static function getBaseUrl() {
        return self::$_base_url;
    }

    // ------------------------------------------------------------------------

    /**
     * Get Canvas app URL
     */
    public static function getCanvasUrl() {
        return 'https://apps.facebook.com/'.self::getAppNamespace().'/';
    }

    /**
     * Get Page Tab URL
     */
    public static function getPageTabUrl() {
        return 'https://www.facebook.com/'.self::getFanPageHash().'?sk=app_'.self::getAppId();
    }

    /**
     * Get Add Page Tab URL
     */
    public static function getAddPageTabUrl() {
        return 'https://www.facebook.com/dialog/pagetab?app_id='.self::getAppId().'&next=https://www.facebook.com/';
    }

    // ------------------------------------------------------------------------
    // PERMISSIONS
    // ------------------------------------------------------------------------

    /**
     * Set extended perms
     *
     * @param    string  $value
     */
    public static function setExtendedPerms($value) {
        self::$_extended_perms = $value;
    }

    /**
     * Get extended perms
     */
    public static function getExtendedPerms() {
        return self::$_extended_perms;
    }

    // ------------------------------------------------------------------------

    /**
     * Set extended perms for the admin
     *
     * @param    string  $value
     */
    public static function setExtendedPermsAdmin($value) {
        self::$_extended_perms_admin = $value;
    }

    /**
     * Get extended perms for the admin
     */
    public static function getExtendedPermsAdmin() {
        return self::$_extended_perms_admin;
    }

    // ------------------------------------------------------------------------
    // SIGNED REQUEST
    // ------------------------------------------------------------------------

    /**
     * Set fan page hash
     *
     * This is used as a fallback value
     *
     * @param    string  $value
     */
    public static function setFanPageHash($value) {
        self::$_fan_page_hash = $value;
    }

    /**
     * Get fan page hash
     */
    public static function getFanPageHash() {
        return self::$_fan_page_hash;
    }

    // ------------------------------------------------------------------------

    /**
     * Set force Facebook view
     *
     * Force user to view the tab or canvas app within Facebook
     *
     * @param    bool  $value
     */
    public static function setForceFacebookView($value) {
        self::$_force_facebook_view = $value;
    }

    /**
     * Get force Facebook view
     */
    public static function getForceFacebookView() {
        return self::$_force_facebook_view;
    }

    // ------------------------------------------------------------------------
    // 3RD PARTY COOKIE FIX
    // ------------------------------------------------------------------------

    /**
     * Set force session redirect
     *
     * Fixes an issue with browsers that block 3rd party cookies
     *
     * @param    bool  $value
     */
    public static function setThirdPartyCookieFix($value) {
        self::$_third_party_cookie_fix = $value;
    }

    /**
     * Get force session redirect
     */
    public static function getThirdPartyCookieFix() {
        return self::$_third_party_cookie_fix;
    }

    // ------------------------------------------------------------------------
    // GRAPH FIELDS
    // ------------------------------------------------------------------------

    /**
     * Set graph user fields
     *
     * @param    string  $value  comma delimited
     */
    public static function setGraphUserFields($value) {
        self::$_user_fields = $value;
    }

    /**
     * Get graph user fields
     */
    public static function getGraphUserFields() {
        return self::$_user_fields;
    }

    // ------------------------------------------------------------------------

    /**
     * Set graph page fields
     *
     * @param    string  $value  comma delimited
     */
    public static function setGraphPageFields($value) {
        self::$_page_fields = $value;
    }

    /**
     * Get graph page fields
     */
    public static function getGraphPageFields() {
        return self::$_page_fields;
    }

    // ------------------------------------------------------------------------
    // LOGOUT
    // ------------------------------------------------------------------------

    /**
     * Set logout route
     *
     * @param    string  $value
     */
    public static function setLogoutRoute($value) {
        self::$_logout_route = $value;
    }

    /**
     * Get logout route
     */
    public static function getLogoutRoute() {
        return self::$_base_url.self::$_logout_route;
    }

    // ------------------------------------------------------------------------

}

/* End of file */
