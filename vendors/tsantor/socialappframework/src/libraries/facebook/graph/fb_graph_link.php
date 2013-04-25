<?php
/*
 * This file is part of the Social App Framework package.
 * (c) 2011-2013 X Studios
 *
 * You should have received a copy of the license (license.txt) distributed
 * with this package. If not, see <http://socialappframework.com/license/>.
 */

require_once dirname(__FILE__).'/fb_graph_object.php';

/**
 * Facebook Link object class
 * Requires extended permission: publish_stream or share_item
 *
 * Assists with creating links.
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
class FB_Graph_Link extends FB_Graph_Object {

    const CONNECTION = 'links';

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------

    /**
     * Set link
     *
     * The link attached to this post
     *
     * @access    public
     * @param     string  $url
     * @return    void
     */
    public function setLink($url) {
        $this->_post['link'] = $url;
    }

    /**
     * Set message
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setMessage($value) {
        $this->_post['message'] = $value;
    }

    /**
     * Set published
     *
     * Whether a post is published. Default is published.
     *
     * Requires extended permissions: publish_stream, manage_pages
     * Only available when publishing to a page.
     *
     * @access    public
     * @param     boolean  $value
     * @return    void
     */
    public function setPublished($value) {
        $this->_post['published'] = $value;
    }

    /**
     * Set scheduled publish time
     *
     * Time when the page post should go live, this should be between 10 mins
     * and 6 months from the time of publishing the post.
     *
     * Requires extended permissions: publish_stream, manage_pages
     * Only available when publishing to a page.
     *
     * @access    public
     * @param     string  $timestamp  a unix timestamp
     * @return    void
     */
    public function setScheduledPublishTime($timestamp) {
        $this->_post['scheduled_publish_time'] = $timestamp;
    }

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    public
     * @param     string  $url  the url
     * @return    void
     */
    public function __construct($url='') {
        parent::__construct();
		$this->_post['link'] = $url;
    }

    // ------------------------------------------------------------------------

    /**
     * Create a link
     *
     * @access    public
     * @param     string|int  $id  the profile ID (eg - me)
     * @return    string      the new link ID
     */
    public function create($profile_id='me') {
        // verify the profile has required permissions
        if ($this->_facebook->user->hasPermission('share_item') === false &&
            $this->_facebook->user->hasPermission('publish_stream') === false) {
            $result['error']['message'] = 'Requires permission: publish_stream or share_item';
            throw new FB_Api_Exception($result);
        }

        // call the api
        $result = $this->_facebook->api('/'.$profile_id.'/feed', 'post', $this->_post);

        return $result['id'];
    }

    // ------------------------------------------------------------------------

}

/* End of file */
