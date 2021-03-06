<?php
/**
 * Social App Framework Facebook Helper
 *
 * Helps generate Facebook Open Graph meta tags and other useful methods
 * for Facebook development
 *
 * Helpers are wrapped in a class to avoid name collisions with other
 * 3rd party helper methods as well as native PHP methods
 *
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 * @version      1.0
 * @copyright    2012 X Studios
 * @link         http://www.xstudiosinc.com
 *
 * You should have received a copy of the license along with this program.
 * If not, see <http://socialappframework.com/license/>.
 */
class FB_Helper {

    // ------------------------------------------------------------------------

    /**
     * Returns a facebook open graph meta tag
     *
     * @access    public
     * @param     string $property property of tag
     * @param     string $content content of tag
     * @return    string
     */
    public static function meta($property, $content) {
        $html = '<meta property="%s" content="%s" />'.PHP_EOL;
        return sprintf($html, $property, $content);
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the Facebook picture url
     *
     * @access    public
     * @param     int $id
     * @param     string $size square (50x50), small (50x?), normal (100x?) or large (200x?)
     * @return    string
     */
    public static function picture_url($id, $size='square') {
        $html = 'https://graph.facebook.com/%s/picture?type=%s';
        return sprintf($html, $id, $size);
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the Facebook profile url for a user or a page
     *
     * @access    public
     * @param     int $id
     * @return    string
     */
    public static function profile_url($id) {
        $html = 'https://www.facebook.com/profile.php?id=%s';
        return sprintf($html, $id);
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the Facebook fan page url
     *
     * This method is deprecated, use the profile_url method
     *
     * @access    public
     * @param     int $fb_page_id
     * @return    string
     * @deprecated
     */
    public static function fan_page_url($fb_page_id) {
        $html = 'https://www.facebook.com/%s';
        return sprintf($html, $fb_page_id);
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the Facebook fan page tab url
     *
     * @access    public
     * @param     int $fb_page_hash (eg - pages/X-Studios/262672746449)
     * @param     int $app_id
     * @return    string
     */
    public static function page_tab_url($fb_page_hash, $app_id) {
        $html = 'https://www.facebook.com/%s/app_%s)';
        return sprintf($html, $fb_page_hash, $app_id);
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the url needed to add the app as a page tab
     *
     * @access    public
     * @param     int $fb_app_id
     * @param     string $redirect_url
     * @return    string
     */
    public static function add_page_tab_url($fb_app_id, $redirect_url='https://www.facebook.com/') {
        $html = 'http://www.facebook.com/dialog/pagetab?app_id=%s&next=%s';
        return sprintf($html, $fb_app_id, $redirect_url);
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the link needed to correctly login a user
     * Works for both a tab or canvas app
     *
     * @access    public
     * @param     string $login_url
     * @return    string
     */
    public static function login_link($login_url) {
        $html = '<a href="javascript:top.location.href = \'%s\'">Login</a>';
        return sprintf($html, $login_url);
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the link needed to correctly logout a user
     * Works for both a tab or canvas app
     *
     * @access    public
     * @param     string $logout_url
     * @return    string
     */
    public static function logout_link($logout_url) {
        $html = '<a href="javascript:top.location.href = \'%s\'">Logout</a>';
        return sprintf($html, $logout_url);
    }

    // ------------------------------------------------------------------------

    /**
     * Returns public info from the Facebook graph api
     * Most of the time Facebook will respond with JSON data,
     * but there are special cases where it will not
     *
     * @access    public
     * @param     int $object_id id of the object we want public info for
     * @param     bool $json_decode decode json response
     * @return    mixed
     */
    public static function graph_request($object_id, $json_decode=true) {
        $url = 'https://graph.facebook.com/'.$object_id;

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_USERAGENT, "I-am-browser");
        curl_setopt($c, CURLOPT_HEADER, 0);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($c);
        $err = curl_getinfo($c, CURLINFO_HTTP_CODE);
        curl_close($c);

        if ($response) {
            if ($json_decode == true) $response = json_decode($response, true);
            return $response;
        } else {
            return false;
        }
    }

    // ------------------------------------------------------------------------

}

/* End of file */
