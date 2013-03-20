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
class SAF_Page extends SAF_Base {

    const RSS = 'https://www.facebook.com/feeds/page.php?id=%s&format=rss20';

    // ------------------------------------------------------------------------
    // PRIVATE VARS
    // ------------------------------------------------------------------------

    /**
     * Facebook instance
     *
     * @access    private
     * @var       SAF_Facebook
     */
    private $_facebook;

    /**
     * Page ID
     *
     * @access    private
     * @var       string|int
     */
    private $_id;

    /**
     * Page data
     *
     * @access    private
     * @var       array
     */
    private $_data;

    /**
     * Page connection
     *
     * @access    private
     * @var       SAF_Page_Connection
     */
    public $connection;

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------

    /**
     * Returns the page ID
     *
     * @access    public
     * @return    string|int
     */
    public function getId() {
        return $this->_getValue('id');
    }

    /**
     * Returns the page data
     *
     * @access    public
     * @return    array
     */
    public function getData() {
        return $this->_data;
    }

    /**
     * Returns the page's access token
     *
     * @access    public
     * @return    string
     */
    public function getAccessToken() {
        return $this->_getValue('access_token');
    }

    /**
     * Returns the page's name
     *
     * @access    public
     * @return    string
     */
    public function getName() {
        return $this->_getValue('name', '');
    }

    /**
     * Returns the page's profile URL
     *
     * @access    public
     * @return    string
     */
    public function getProfileUrl() {
        return $this->_getValue('link');
    }

    /**
     * Returns the page's profile picture
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
     * Returns the page's like count
     *
     * @access    public
     * @return    string|int
     */
    public function getLikes() {
        return $this->_getValue('likes');
    }

    /**
     * Returns the page's website
     *
     * @access    public
     * @return    string
     */
    public function getWebsite() {
        return $this->_getValue('website');
    }

    /**
     * Returns the app tab URL
     *
     * @access    public
     * @return    string
     */
    public function getTabUrl() {
        return $this->_getValue('saf_app_tab_url');
    }

    /**
     * Returns the URL needed to add the app to a page
     *
     * @access    public
     * @return    string
     */
    public function getAddTabUrl() {
        return $this->_getValue('saf_add_tab_url');
    }

    /**
     * Returns true if the page is published
     *
     * @access    public
     * @return    boolean
     */
    public function isPublished() {
        return $this->_getValue('is_published');
    }

    /**
     * Returns true if the user likes this page
     *
     * @access    public
     * @return    boolean
     */
    public function isLiked() {
        return $this->_getValue('saf_liked');
    }

    /**
     * Returns true if the page has restrictions
     *
     * @access    public
     * @return    boolean
     */
    public function hasRestrictions() {
        return $this->_getValue('saf_restrictions');
    }

    /**
     * Returns the page's RSS URL
     *
     * @access    public
     * @return    string
     */
    public function getRssUrl() {
        return sprintf(self::RSS, $this->_id);
    }

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    public
     * @param     SAF_Facebook  $facebook
     * @param     string|int    $page_id
     * @return    void
     */
    public function __construct($facebook, $page_id) {
        $this->_facebook = $facebook;
        $this->_id       = $page_id;

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
        try {

            // get page data
            $this->_data = $this->_facebook->api('/'.$this->_id, 'GET', array(
                //'access_token' => $this->getAccessToken(),
                'fields' => 'access_token, '.SAF_Config::getGraphPageFields()
            ));

            // if we have page data
            if ( !empty($this->_data) ) {

                // inject SAF data, no page restrictions we are aware of
                $this->_data = $this->_injectSAFData(false);

                // if this is an app tab set the redirect URL to this tab
                if (SAF_Config::getAppType() === SAF_Config::APP_TYPE_TAB) {
                    $this->_facebook->setRedirectUrl($this->getTabUrl());
                }

            // probably some sort of page restriction (country/age)
            } else {

                // inject SAF data, some sort of page restriction (country/age)
                $this->_data = $this->_injectSAFData(true);

                // if this is an app tab set the redirect URL to this tab
                if (SAF_Config::getAppType() === SAF_Config::APP_TYPE_TAB) {
                    $this->_facebook->setRedirectUrl($this->getTabUrl());
                }

                $this->debug(__CLASS__.':: Page ('.$this->_id.') may be unpublished or have country/age restrictions', null, 3, true);

            }

            // create page connection
            $this->connection = new SAF_Page_Connection($this, $this->_facebook);

            $this->debug(__CLASS__.':: Page ('.$this->_id.') data: ', $this->_data);

        } catch (FacebookApiException $e) {

            $this->debug(__CLASS__.':: '.$e, null, 3, true);

        }

        $this->debug('--------------------');
    }

    // ------------------------------------------------------------------------

    /**
     * Add our own useful social app framework parameter(s) to the page data
     *
     * @access    private
     * @return    array
     */
    private function _injectSAFData($page_restrictions=false) {
        if ( isset($this->_data['link']) ) {
            $url = str_replace( 'http', 'https', $this->_data['link'].'?sk=app_'.SAF_Config::getAppId() );
            $this->_data['saf_app_tab_url'] = $url;
        } else {
            $this->_data['saf_app_tab_url'] = SAF_Config::getTabUrl();
        }

        $this->_data['saf_add_tab_url']  = SAF_Config::getAddTabUrl();
        $this->_data['saf_restrictions'] = $page_restrictions;
        $this->_data['saf_liked']        = $this->_facebook->sr->isPageLiked();
        $this->_data['saf_rss_url']      = $this->getRssUrl();

        return $this->_data;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns a page key value whether it exists or not
     *
     * @access    private
     * @param     string  $key      key to check for
     * @param     mixed   $default  default value if not set
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
