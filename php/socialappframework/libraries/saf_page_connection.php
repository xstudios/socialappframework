<?php
/*
 * This file is part of the Social App Framework package.
 * (c) 2011-2013 X Studios
 *
 * You should have received a copy of the license (license.txt) distributed
 * with this package. If not, see <http://socialappframework.com/license/>.
 */

/**
 * Social App Framework Page Connection class
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 * @link         https://developers.facebook.com/docs/reference/api/page/
 */
class SAF_Page_Connection {

    // ------------------------------------------------------------------------
    // PRIVATE VARS
    // ------------------------------------------------------------------------

    /**
     * SAF_Page instance
     *
     * @access    private
     * @var       SAF_Page
     */
    private $_page;

    /**
     * SAF instance
     *
     * @access    private
     * @var       SAF
     */
    private $_facebook;

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    public
     * @param     SAF_Page  $page
     * @param     SAF       $facebook
     * @return    void
     */
    public function __construct($page, $facebook) {
        $this->_page     = $page;
        $this->_facebook = $facebook;
    }

    // ------------------------------------------------------------------------

    /**
     * Get a page connection
     *
     * @access    public
     * @param     string  $connection  connection name
     * @param     array   $query       query params
     * @return    mixed
     */
    public function getConnection($connection, $query=array()) {
        $connection = '/'.$connection;

        // call the api
        $result = $this->_facebook->api('/'.$this->_page->getId().$connection, 'GET', $query);

        return $result['data'];
    }

    // ------------------------------------------------------------------------
    // CONNECTIONS
    // ------------------------------------------------------------------------

    /**
     * Get the page's wall
     *
     * @access    public
     * @return    array  of Post objects
     */
    public function getFeed() {
        return $this->getConnection('feed');
    }

    // ------------------------------------------------------------------------

    /**
     * Get the page's profile picture
     *
     * @access    public
     * @param     string  $type  square, small, normal, large
     * @return    string  URL of the page's profile picture
     */
    public function getPicture($type='square') {
        return $this->getConnection('picture', array(
            'type' => $type
        ));
    }

    // ------------------------------------------------------------------------

    /**
     * Get the page's settings
     *
     * @access    public
     * @return    array  of objects containing setting and value fields
     */
    public function getSettings() {
        return $this->getConnection('settings', array(
            'access_token' => $this->_getPageAccessToken()
        ));
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the photos, videos, and posts in which the Page has been tagged.
     *
     * @access    public
     * @return    array  of Photo, Video or Post objects
     */
    public function getTagged() {
        return $this->getConnection('tagged');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the the page's posted links.
     *
     * @access    public
     * @return    array  of link objects
     */
    public function getLinks() {
        return $this->getConnection('links');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the page's uploaded photos.
     *
     * @access    public
     * @return    array  of Photo objects
     */
    public function getPhotos() {
        return $this->getConnection('photos');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the groups to which the page belongs.
     *
     * @access    public
     * @return    array  array containing group id, version, name & unread fields
     */
    public function getGroups() {
        return $this->getConnection('groups');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the photo albums the page has uploaded.
     *
     * @access    public
     * @return    array  of album objects
     */
    public function getAlbums() {
        return $this->getConnection('albums');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the page's status updates.
     *
     * @access    public
     * @return    array  of status message objects
     */
    public function getStatuses() {
        return $this->getConnection('statuses');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the videos the page has uploaded.
     *
     * @access    public
     * @return    array  of video objects
     */
    public function getVideos() {
        return $this->getConnection('videos');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the page's notes.
     *
     * @access    public
     * @return    array  of note objects
     */
    public function getNotes() {
        return $this->getConnection('notes');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the page's own posts.
     *
     * @access    public
     * @return    array  of post objects
     */
    public function getPosts() {
        return $this->getConnection('posts');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the page's own posts, including unpublished
     * and scheduled posts.
     *
     * @access    public
     * @return    array  of post objects
     */
    public function getPromotablePosts() {
        return $this->getConnection('promotable_posts');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the page's questions.
     *
     * @access    public
     * @return    array  of question objects
     */
    public function getQuestions() {
        return $this->getConnection('questions');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the events the page is attending.
     *
     * @access    public
     * @return    array  of event objects
     */
    public function getEvents() {
        return $this->getConnection('events');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the checkins made to this place page by the
     * current user, and friends of the current user.
     *
     * Permissions: user_checkins or friends_checkins
     *
     * @access    public
     * @return    array  of checkin objects
     */
    public function getCheckins() {
        return $this->getConnection('checkins');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns a list of the page's admins.
     *
     * @access    public
     * @return    array  of objects containing id, name
     */
    public function getAdmins() {
        return $this->getConnection('admins', array(
            'access_token' => $this->_getPageAccessToken()
        ));
    }

    // ------------------------------------------------------------------------

    /**
     * Returns a list of the page's conversations.
     *
     * Permissions: Page admin access token with read_mailbox permission
     *
     * @access    public
     * @return    array  of checkin objects
     */
    public function getConversations() {
        return $this->getConnection('conversations', array(
            'access_token' => $this->_getPageAccessToken()
        ));
    }

    // ------------------------------------------------------------------------

    /**
     * Returns a list of the page's milestones.
     *
     * @access    public
     * @return    array  of milestone objects
     */
    public function getMilestones() {
        return $this->getConnection('milestones');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns a list of users blocked from the page.
     *
     * @access    public
     * @return    array  of objects containing id, name
     */
    public function getBlocked() {
        return $this->getConnection('blocked', array(
            'access_token' => $this->_getPageAccessToken()
        ));
    }

    // ------------------------------------------------------------------------

    /**
     * Get the page's tabs
     *
     * Permissions: Page admin access token
     *
     * @access    public
     * @return    array  of tab objects
     */
    public function getTabs() {
        return $this->getConnection('tabs', array(
            'access_token' => $this->_getPageAccessToken()
        ));
    }

    // ------------------------------------------------------------------------

    /**
     * Returns information of all children pages.
     *
     * @access    public
     * @return    array  of JSON objects
     */
    public function getGlobalBrandChildren() {
        return $this->getConnection('global_brand_children');
    }

    // ------------------------------------------------------------------------

    /**
     * Get the page's Insights data
     *
     * Permissions: read_insights permission
     *
     * @access    public
     * @return    array  of insights objects
     */
    public function getInsights() {
        return $this->getConnection('insights', array(
            'access_token' => $this->_getPageAccessToken()
        ));
    }

    // ------------------------------------------------------------------------
    // PRIVATE METHODS
    // ------------------------------------------------------------------------

    /**
     * Get the page's access token.
     *
     * @access    private
     * @throws    FacebookApiException
     * @return    string
     */
    private function _getPageAccessToken() {
        $page_access_token = $this->_page->getAccessToken();
        if (empty($page_access_token)) {
            $result['error']['message'] = 'Requires page aceess token.';
            throw new FacebookApiException($result);
        }

        return $page_access_token;
    }

    // ------------------------------------------------------------------------

}

/* End of file */
