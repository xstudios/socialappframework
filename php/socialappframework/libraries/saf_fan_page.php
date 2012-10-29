<?php if ( ! defined('SOCIAL_APP_FRAMEWORK') ) exit('No direct script access allowed');
/**
 * Social App Framework Fan Page class
 *
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 * @version      1.0
 * @copyright    2012 X Studios
 * @link         http://www.xstudiosinc.com
 *
 * You should have received a copy of the license along with this program.
 * If not, see <http://socialappframework.com/license/>.
 */
abstract class SAF_Fan_Page extends SAF_Signed_Request {

    private $_fb_page = null;

    private $_access_token = null;

    private $_page_tab_url; // url of the page tab
    private $_add_page_tab_url; // url to add the app to a fan page
    private $_canvas_app_url; // url of app on facebook (eg - http://apps.facebook.com/app-name)

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------
    public function getPageData() { return $this->_getPageData(); }

    public function getPageName() { return $this->_getPageValue('name', ''); }
    public function getPageProfileURL() { return $this->_getPageValue('link'); }
    public function getPageProfilePicture() {
        $picture = $this->_getPageValue('picture');
        if (!empty($picture)) $picture = $picture['data']['url'];
        return $picture;
    }

    public function getPageLikes() { return $this->_getPageValue('likes'); }
    public function getPageCategory() { return $this->_getPageValue('category'); }
    public function getPageWebsite() { return $this->_getPageValue('website'); }

    public function getPageTabURL() { return $this->_page_tab_url; }
    public function getAddPageTabURL() { return $this->_add_page_tab_url; }
    public function getCanvasAppURL() { return $this->_canvas_app_url; }

    public function isPagePublished() { return $this->_getPageValue('is_published'); }
    public function hasPageAddedApp() { return $this->_getPageValue('has_added_app'); }
    public function hasPageRestrictions() { return $this->_getPageValue('saf_page_restrictions'); }

    // used to set the page id only when we are a Canvas app or Facebook Connect
    // app and we need to get page data for a known page ID (eg - our own page)
    public function setPageID($value) { $this->page_id = $value; }

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

        // We may or may not have the page id based on whether this is a tab or an app.
        // Canvas apps or Facebook Connect apps supply no 'page' data in the
        // Signed Request, but page tab apps do supply data
        if ( !empty($this->page_id) ) {

            try {

                // get page data
                // note that we don't utilize _getPageData() as this is reserved for calls
                // when we don't know the page id because the app is a Canvas or Facebook Connect app
                $this->_fb_page = $this->facebook->api('/'.$this->page_id, 'GET', array(
                    'fields' => SAF_Config::graphPageFields()
                ));

                // if we have page data
                if ( !empty($this->_fb_page) ) {

                    // inject SAF data, no page restrictions we are aware of
                    $this->_fb_page = $this->_injectSAFData(false);

                    // add our social app framework page data into the session
                    SAF_Session::setPersistentData('page_obj', $this->_fb_page);

                    $this->debug(__CLASS__.':: Fan page ('.$this->page_id.') data: ', $this->_fb_page);

                } else {

                    // clear any existing stored page data
                    $this->_fb_page = null;
                    SAF_Session::clearPersistentData('page_obj');

                    // inject SAF data, probably safe to assume some sort of page restriction (country/age)
                    $this->_fb_page = $this->_injectSAFData(true);

                    // add our social app framework page data into the session
                    SAF_Session::setPersistentData('page_obj', $this->_fb_page);

                    // fall back to default page URL as SAF_FacebookUser will need this value
                    // however, simply trying to force in the page id will cause API errors for some reason
                    // even though navigating to https://www.facebook.com/PAGE_ID resolves to the correct fan page we want
                    $this->_page_tab_url = 'https://www.facebook.com/'; //.$this->page_id;

                    //$this->debug(__CLASS__.':: Page ('.$this->page_id.') may be unpublished or have country/age restrictions', null, 3, true);

                }

            } catch (FacebookApiException $e) {

                $this->debug(__CLASS__.':: Page ('.$this->page_id.') error. '.$e, null, 1, true);

                $this->_fb_page = null;

                // wipe the 'page_obj' session object
                SAF_Session::clearPersistentData('page_obj');

                // FALLBACK
                // something is up, so let's try to get public data as a fallback
                $this->debug(__CLASS__.':: Fallback, attempting to access public data for page ('.$this->page_id.')...', null, 3, true);

                // get public data
                $this->_fb_page = $this->getPublicData($this->page_id);

                // if we have public data
                if ( !empty($this->_fb_page) ) {

                    // inject SAF data
                    $this->_fb_page = $this->_injectSAFData(false);

                    // add our social app framework page data into the session
                    SAF_Session::setPersistentData('page_obj', $this->_fb_page);

                    $this->debug(__CLASS__.':: Page ('.$this->page_id.') public data:', $this->_fb_page);

                } else {

                    $this->debug(__CLASS__.':: Page ('.$this->page_id.') public data is empty', null, 3, true);

                }

            }

        } else {

            $this->debug(__CLASS__.':: Unable to access page data...most likely a Canvas or Facebook Connect app.', null, 3);
            $this->debug(__CLASS__.':: You can manually call setPageID() and then getPageData() if public fan page data is required.');

            // wipe the 'page_obj' session object
            SAF_Session::clearPersistentData('page_obj');

        }

        $this->debug('--------------------');

        // end benchmark
        if ($benchmark) $benchmark->end(__CLASS__);
    }

    // ------------------------------------------------------------------------

    /**
     * GET PAGE ACCESS TOKEN
     *
     * Returns a long-lived access token which never expires
     * Only available to a page admin (called from SAF_FacebookUser)
     *
     * @access    private
     * @return    string or null
     */
    public function getPageAccessToken() {
        // if we already have an access token then just return it
        if ( !empty($this->_access_token) ) return $this->_access_token;

        $access_token = null;

        try {

            // get long-lived access token
            $response = $this->facebook->api('/'.$this->page_id, 'GET', array(
                'fields' => 'access_token',
                'access_token' => $this->getAccessToken()
            ));

            if ( isset($response['access_token']) ) {
                $access_token = $response['access_token'];
                //$this->debug(__METHOD__.':: Page access token:', $access_token);
            } else {
                $this->debug(__CLASS__.':: Unable to get the page ('.$this->page_id.') long-lived access token as user ('.$this->user_id.').', null, 3, true);
            }

        } catch (FacebookApiException $e) {
            $this->debug(__CLASS__.':: Unable to get the page ('.$this->page_id.') long-lived access token as user ('.$this->user_id.'). '.$e, null, 3, true);
        }

        // set access token
        $this->_access_token = $access_token;
        return $access_token;
    }

    // ------------------------------------------------------------------------
    // PRIVATE METHODS
    // ------------------------------------------------------------------------

    /**
     * GET PAGE DATA
     *
     * @access    private
     * @return    mixed
     */
    private function _getPageData() {
        // bail out scenario
        if ( empty($this->page_id) ) {
            $this->debug(__CLASS__.':: Unable to access page data without a page ID', null, 3, true);
            return;
        }

        // if we already have page data just return it
        if ( !empty($this->_fb_page) ) {
            return $this->_fb_page;
        }

        // ok, let's try and get page data
        try {

            $data = $this->facebook->api('/'.$this->page_id, 'GET');

            if ( !empty($data) ) {
                return $data;
            } else {
                $this->debug(__CLASS__.':: Fan page ('.$this->page_id.') data is empty', null, 3, true);
                return false;
            }

        } catch (FacebookApiException $e) {

            $this->debug(__CLASS__.':: Unable to access fan page ('.$this->page_id.') data. '.$e, null, 3, true);
            return false;
        }
    }

    // ------------------------------------------------------------------------

    /**
     * INJECT SAF DATA
     *
     * Add our own useful social app framework parameter(s) to the fb_page object
     *
     * @access    private
     * @return    array
     */
    private function _injectSAFData($page_restrictions=false) {
        // page tab url (eg - https://www.facebook.com/XXXXXXXXXX?sk=app_XXXXXXXXXX)
        if ( isset($this->_fb_page['link']) ) {
            $this->_page_tab_url = str_replace( 'http', 'https', $this->_fb_page['link'].'?sk=app_'.$this->facebook->getAppId() );
        }

        // add page tab url (eg - https://www.facebook.com/dialog/pagetab?app_id=XXXXXXXXXX&next=https://www.facebook.com/)
        $this->_add_page_tab_url = SAF_Config::urlAddPageTab();

        // canvas app url (eg - https://apps.facebook.com/app-namespace)
        $this->_canvas_app_url = SAF_Config::urlAppCanvas();


        $this->_fb_page['saf_page_tab_url'] = $this->_page_tab_url;
        $this->_fb_page['saf_add_page_tab_url'] = $this->_add_page_tab_url;
        $this->_fb_page['saf_canvas_app_url'] = $this->_canvas_app_url;
        $this->_fb_page['saf_page_restrictions'] = $page_restrictions;

        $this->_fb_page['liked'] = $this->isPageLiked();

        // fix data which may be missing (yes, Facebook makes mistakes)
        $this->_fb_page = $this->_fixPageData();

        return $this->_fb_page;
    }

    // ------------------------------------------------------------------------

    /**
     * FIX PAGE DATA
     *
     * Sometimes Facebook 'breaks' things so let's check
     * for things we can fix using data we already have
     *
     * @access    private
     * @return    array
     */
    private function _fixPageData() {
        if ( !isset($this->_fb_page['id']) ) {
            // set page id using the one from the signed request
            $this->_fb_page['id'] = $this->page_id;
        }

        if ( !isset($this->_fb_page['picture']) ) {
            $segment = isset($this->_fb_page['username']) ? $this->_fb_page['username'] : $this->page_id;
            $this->_fb_page['picture']['data']['url'] = 'https://graph.facebook.com/'.$segment.'/picture';
        }

        if ( !isset($this->_fb_page['link']) ) {
            $segment = isset($this->_fb_page['username']) ? $this->_fb_page['username'] : $this->page_id;
            $this->_fb_page['link'] = 'https://www.facebook.com/'.$segment;
        }

        return $this->_fb_page;
    }

    // ------------------------------------------------------------------------

    /**
     * GET PAGE VALUE
     *
     * Return a clean var value
     * (eg - if something doesn't exist, return default value)
     *
     * @access    private
     * @param     mixed $key key to check for
     * @param     mixed $default default value if not set
     * @return    void
     */
    private function _getPageValue($key, $default=false) {
        if ( !isset($this->_fb_page[$key]) ) {
            return $default;
        } else {
            return $this->_fb_page[$key];
        }
    }

    // ------------------------------------------------------------------------

}

/* End of file SAF_FanPage.php */
/* Location: ./socialappframework/libraries/SAF_FanPage.php */
