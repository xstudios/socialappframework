<?php
/*
 * This file is part of the Social App Framework package.
 * (c) 2011-2013 X Studios
 *
 * You should have received a copy of the license (license.txt) distributed
 * with this package. If not, see <http://socialappframework.com/license/>.
 */

/**
 * Social App Framework Page class
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
abstract class SAF_Page extends SAF_Signed_Request {

    const RSS = 'https://www.facebook.com/feeds/page.php?id=%s&format=rss20';

    private $_fb_page;

    private $_access_token;

    private $_page_tab_url;
    private $_add_page_tab_url;
    private $_canvas_url;

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------
    public function getPageData() { return $this->_fb_page; }
    public function getPageAccessToken() { return $this->_access_token; }

    public function getPageName() { return $this->_getPageValue('name', ''); }
    public function getPageProfileUrl() { return $this->_getPageValue('link'); }

    public function getPageProfilePicture() {
        $picture = $this->_getPageValue('picture');
        if (!empty($picture)) {
            $picture = $picture['data']['url'];
        } else {
            $picture = FB_Helper::picture_url($this->getPageId());
        }
        return $picture;
    }

    public function getPageLikes() { return $this->_getPageValue('likes'); }
    public function getPageWebsite() { return $this->_getPageValue('website'); }

    public function getPageTabUrl() { return $this->_page_tab_url; }
    public function getAddPageTabUrl() { return $this->_add_page_tab_url; }
    public function getCanvasUrl() { return $this->_canvas_url; }

    public function isPagePublished() { return $this->_getPageValue('is_published'); }
    public function hasPageRestrictions() { return $this->_getPageValue('saf_page_restrictions'); }

    public function getRssUrl() {
        return sprintf(self::RSS, $this->_page_id);
    }

    // used to set the page id only when we are a Canvas or Facebook Connect
    // app and we need to get page data for a known page ID (eg - our own page)
    public function setPageId($value) { $this->_page_id = $value; }

    // ------------------------------------------------------------------------

    /**
     * CONSTRUCTOR
     *
     * @access    public
     * @return    void
     */
    public function __construct() {
        parent::__construct();

        // we should always have a page id if its a tab app (unless it is being
        // viewed outside the Facebook chrome). Canvas and Facebook Connect apps
        // will not have the page id unless we explicitly set it with setPageId()
        // before calling init()
        if ( !empty($this->_page_id) ) {

            try {

                // get page data
                $this->_fb_page = $this->api('/'.$this->_page_id, 'GET', array(
                    //'access_token' => $this->getAccessToken(),
                    'fields' => 'access_token, '.SAF_Config::getGraphPageFields()
                ));

                // if we have page data
                if ( !empty($this->_fb_page) ) {

                    // set page access token...only returned if the user is a
                    // page admin with the 'manage_pages' permission
                    if ( isset($this->_fb_page['access_token'])) {
                        $this->_access_token = $this->_fb_page['access_token'];
                    }

                    // inject SAF data, no page restrictions we are aware of
                    $this->_fb_page = $this->_injectSAFData(false);

                    // add our social app framework page data into the session
                    //$this->setPersistentData('saf_page', $this->_fb_page);

                    //$this->debug(__CLASS__.':: Fan page ('.$this->_page_id.') data: ', $this->_fb_page);

                // probably some sort of page restriction (country/age)
                } else {

                    // clear any existing stored page data
                    $this->clearPersistentData('saf_page');

                    // inject SAF data, some sort of page restriction (country/age)
                    $this->_fb_page = $this->_injectSAFData(true);

                    // add our social app framework page data into the session
                    //$this->setPersistentData('saf_page', $this->_fb_page);

                    // fall back to default page URL as SAF_FacebookUser will need this value
                    // however, simply trying to force in the page id will cause API errors for some reason
                    // even though navigating to https://www.facebook.com/PAGE_ID resolves to the correct fan page we want
                    $this->_page_tab_url = 'https://www.facebook.com/';

                    $this->debug(__CLASS__.':: Page ('.$this->_page_id.') may be unpublished or have country/age restrictions', null, 3, true);

                }

                // add our social app framework page data into the session
                //$this->setPersistentData('saf_page', $this->_fb_page);

                $this->debug(__CLASS__.':: Fan page ('.$this->_page_id.') data: ', $this->_fb_page);

            } catch (FacebookApiException $e) {

                // wipe the 'page_obj' session object
                $this->clearPersistentData('saf_page');

                $this->debug(__CLASS__.':: '.$e, null, 3, true);

            }

        } else {

            // wipe the 'page_obj' session object
            $this->clearPersistentData('saf_page');

            // tab
            if (SAF_Config::getAppType() === SAF_Config::APP_TYPE_TAB) {
                $this->debug(__CLASS__.':: No page data. Viewing Tab app outside of Facebook.', null, 3);
            }

            // canvas
            if (SAF_Config::getAppType() === SAF_Config::APP_TYPE_CANVAS) {
                $this->debug(__CLASS__.':: No page data. Viewing Canvas app.', null, 3);
            }

            // facebook connect
            if (SAF_Config::getAppType() === SAF_Config::APP_TYPE_FACEBOOK_CONNECT) {
                $this->debug(__CLASS__.':: No page data. Viewing Facebook Connect app.', null, 3);
            }

            $this->debug(__CLASS__.':: Use setPageId() before calling init() if fan page data is required.');

        }

        $this->debug('--------------------');
    }

    // ------------------------------------------------------------------------
    // PRIVATE METHODS
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
        if ( isset($this->_fb_page['link']) ) {
            $this->_page_tab_url = str_replace( 'http', 'https', $this->_fb_page['link'].'?sk=app_'.SAF_Config::getAppId() );
        }
        $this->_add_page_tab_url = SAF_Config::getAddPageTabUrl();
        $this->_canvas_url = SAF_Config::getCanvasUrl();

        $this->_fb_page['saf_page_tab_url'] = $this->_page_tab_url;
        $this->_fb_page['saf_add_page_tab_url'] = $this->_add_page_tab_url;
        $this->_fb_page['saf_canvas_url'] = $this->_canvas_url;
        $this->_fb_page['saf_page_restrictions'] = $page_restrictions;
        $this->_fb_page['saf_page_liked'] = $this->isPageLiked();
        $this->_fb_page['saf_rss_url'] = $this->getRssUrl();

        return $this->_fb_page;
    }

    // ------------------------------------------------------------------------

    /**
     * GET PAGE VALUE
     *
     * Return a clean value whether the key exits or not
     *
     * @access    private
     * @param     string $key key to check for
     * @param     mixed $default default value if not set
     * @return    mixed
     */
    private function _getPageValue($key, $default=false) {
        if ( !isset($this->_fb_page[$key]) ) {
            return $default;
        } else {
            return $this->_fb_page[$key];
        }
    }

    // ------------------------------------------------------------------------
    // CONNECTIONS
    // ------------------------------------------------------------------------

    /**
     * Get tabs
     *
     * @access    public
     * @return    array
     */
    public function getPageTabs() {
        // call the api
        $result = $this->api('/'.$this->_page_id.'/tabs', 'GET', array(
            'access_token' => $this->getPageAccessToken()
        ));

        return $result['data'];
    }

    // ------------------------------------------------------------------------

}

/* End of file */
