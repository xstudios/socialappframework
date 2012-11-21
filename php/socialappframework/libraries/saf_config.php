<?php
/**
 * Social App Framework Config class
 *
 * Helps manage the SAF config so we have a nice easy get/set method and code completion
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
    const PAGE_TYPE_TAB              = 'tab';
    const PAGE_TYPE_APP              = 'app';
    const PAGE_TYPE_FACEBOOK_CONNECT = 'facebook connect';
    const PAGE_TYPE_WIDGET           = 'widget';

    // page type
    private static $_page_type = self::PAGE_TYPE_FACEBOOK_CONNECT; // tab, app, facebook connect or widget

    // benchmark
    private static $_benchmark = false;

    // facebook vars
    private static $_fb_app_id;
    private static $_fb_app_secret;
    private static $_fb_app_domain;

    private static $_fb_use_cookie = true;
    private static $_fb_file_upload = true;

    private static $_fb_app_namespace = ''; // app namespace (must match the one on your Facebook app's settings)
    private static $_fb_admins = ''; // admin id(s) - comma delimited
    private static $_fb_developers = ''; // app developer id(s) - comma delimited

    // app url vars
    private static $_url_base = ''; // base url of our app

    // permission vars
    private static $_perms_extended = ''; // extended permissions
    private static $_perms_extended_admin = ''; // // required extended permissions for admin(s)
    private static $_perms_auto_request_tab = false; // auto request permissions when visiting tab
    private static $_perms_auto_request_app = false; // auto request permissions when visiting app
    private static $_perms_auto_request_admin = false; // auto request permissions if page admin

    // signed request vars
    private static $_sr_fan_page_hash = ''; // fan page hash (for fallback)
    private static $_sr_redirect_tab = false; // force the user to view tab in Facebook
    private static $_sr_redirect_app = false; // force the user to view app in Facebook canvas
    private static $_sr_redirect_tab_url = ''; // preferred redirect url
    private static $_sr_redirect_app_url = ''; // preferred redirect url

    // SAF redirect
    private static $_force_redirect = false; // used to work around 3rd party cookie issues

    // graph fields
    private static $_user_fields = '';
    private static $_page_fields = '';

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------
    public static function benchmark($value=null) {
        if ($value) self::$_benchmark = $value;
        else return self::$_benchmark;
    }

    // page type
    public static function pageType($value=null) {
        if ($value) self::$_page_type = $value;
        else return self::$_page_type;
    }

    // facebook
    public static function fbAppID($value=null) {
        if ($value) self::$_fb_app_id = $value;
        else return self::$_fb_app_id;
    }
    public static function fbAppSecret($value=null) {
        if ($value) self::$_fb_app_secret = $value;
        else return self::$_fb_app_secret;
    }
    public static function fbAppDomain($value=null) {
        if ($value) self::$_fb_app_domain = $value;
        else return self::$_fb_app_domain;
    }

    public static function fbUseCookie($value=null) {
        if ($value) self::$_fb_use_cookie = $value;
        else return self::$_fb_use_cookie;
    }
    public static function fbFileUpload($value=null) {
        if ($value) self::$_fb_file_upload = $value;
        else return self::$_fb_file_upload;
    }

    public static function fbAppNamespace($value=null) {
        if ($value) self::$_fb_app_namespace = $value;
        else return self::$_fb_app_namespace;
    }
    public static function fbAdmins($value=null) {
        if ($value) self::$_fb_admins = $value;
        else return self::$_fb_admins;
    }
    public static function fbDevelopers($value=null) {
        if ($value) self::$_fb_developers = $value;
        else return self::$_fb_developers;
    }

    // app urls
    public static function urlBase($value=null) {
        if ($value) self::$_url_base = $value;
        else return self::$_url_base;
    }
    public static function urlAppCanvas() {
        return 'https://apps.facebook.com/'.self::fbAppNamespace().'/';
    }
    public static function urlAddPageTab() {
        return 'https://www.facebook.com/dialog/pagetab?app_id='.self::fbAppID().'&next=https://www.facebook.com/';
    }
    public static function urlPageTabFallback() {
        return 'https://www.facebook.com/'.self::srFanPageHash().'?sk=app_'.self::fbAppID();
    }

    // perms
    public static function permsExtended($value=null) {
        if ($value) self::$_perms_extended = $value;
        else return self::$_perms_extended;
    }
    public static function permsExtendedAdmin($value=null) {
        if ($value) self::$_perms_extended_admin = $value;
        else return self::$_perms_extended_admin;
    }
    public static function permsAutoRequestTab($value=null) {
        if ($value) self::$_perms_auto_request_tab = $value;
        else return self::$_perms_auto_request_tab;
    }
    public static function permsAutoRequestApp($value=null) {
        if ($value) self::$_perms_auto_request_app = $value;
        else return self::$_perms_auto_request_app;
    }
    public static function permsAutoRequestAdmin($value=null) {
        if ($value) self::$_perms_auto_request_admin = $value;
        else return self::$_perms_auto_request_admin;
    }

    // signed request
    public static function srFanPageHash($value=null) {
        if ($value) self::$_sr_fan_page_hash = $value;
        else return self::$_sr_fan_page_hash;
    }
    public static function srRedirectTab($value=null) {
        if ($value) self::$_sr_redirect_tab = $value;
        else return self::$_sr_redirect_tab;
    }
    public static function srRedirectApp($value=null) {
        if ($value) self::$_sr_redirect_app = $value;
        else return self::$_sr_redirect_app;
    }
    public static function srRedirectTabURL($value=null) {
        if ($value) self::$_sr_redirect_tab_url = $value;
        else return self::$_sr_redirect_tab_url;
    }
    public static function srRedirectAppURL($value=null) {
        if ($value) self::$_sr_redirect_app_url = $value;
        else return self::$_sr_redirect_app_url;
    }

    // SAF redirect
    public static function forceRedirect($value=null) {
        if ($value) self::$_force_redirect = $value;
        else return self::$_force_redirect;
    }

    // graph fields
    public static function graphUserFields($value=null) {
        if ($value) self::$_user_fields = $value;
        else return self::$_user_fields;
    }

    public static function graphPageFields($value=null) {
        if ($value) self::$_page_fields = $value;
        else return self::$_page_fields;
    }

    // ------------------------------------------------------------------------

}

/* End of file SAF_Config.php */
/* Location: ./socialappframework/libraries/SAF_Config.php */
