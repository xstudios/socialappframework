<?php
/*
 * This file is part of the Social App Framework package.
 * (c) 2011-2013 X Studios
 *
 * You should have received a copy of the license (license.txt) distributed
 * with this package. If not, see <http://socialappframework.com/license/>.
 */

//namespace SocialAppFramework\Graph;

require_once dirname(__FILE__).'/SAF_Graph_Object.php';

/**
 * Facebook Post (aka - Feed) object class
 * Requires extended permission: publish_stream or publish_actions
 *
 * Assists with creating feed posts.
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
class SAF_Graph_Post extends SAF_Graph_Object {

    const CONNECTION = 'feed';

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------

    /**
     * Set caption
     *
     * Post caption (can only be used if link is specified)
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setCaption($value) {
        $this->_post['caption'] = $value;
    }

    /**
     * Set description
     *
     * Post description (can only be used if link is specified)
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setDescription($value) {
        $this->_post['description'] = $value;
    }

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
     * The message that appears in the post.
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setMessage($value) {
        $this->_post['message'] = $value;
    }

    /**
     * Set name
     *
     * Post name (can only be used if link is specified)
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setName($value) {
        $this->_post['name'] = $value;
    }

    /**
     * Set picture
     *
     * Post thumbnail image (can only be used if link is specified)
     *
     * @access    public
     * @param     string  $url
     * @return    void
     */
    public function setPicture($url) {
        $this->_post['picture'] = $url;
    }

    /**
     * Set properties
     *
     * A JSON object of key/value pairs which will appear in
     * the stream attachment beneath the description, with
     * each property on its own line. Keys must be strings,
     * and values can be either strings or JSON objects with
     * the keys text and href.
     *
     * @access    public
     * @param     array  $array
     * @return    void
     */
    /*public function setProperties($array) {
        $this->_post['properties'] = json_encode($array);
    }*/

    /**
     * Set actions
     *
     * Array of objects containing 'name' and 'link' keys
     *
     * @access    public
     * @param     array  $array
     * @return    void
     */
    public function setActions($array) {
        $this->_post['actions'] = json_encode($array);
    }

    /**
     * Set targeting
     *
     * JSON object containing countries, cities, regions or locales
     * Example: {'countries':['US','GB']}
     *
     * @access    public
     * @param     string  $json_obj
     * @return    void
     */
    public function setTargeting($json_obj) {
        $this->_post['targeting'] = $json_obj;
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
     * @param     string  $message  the message
     * @return    void
     */
    public function __construct($message='') {
        parent::__construct();
		$this->_post['message'] = $message;
    }

    // ------------------------------------------------------------------------

    /**
     * Create action
     *
     * Returns an associative array for an action link
     *
     * @access    public
     * @param     $name display name
     * @param     $link url value
     * @return    array
     */
    public function createAction($name, $link) {
        $action = array(
            'name' => $name,
            'link' => $link
        );
        return $action;
    }

    // ------------------------------------------------------------------------

    /**
     * Create property
     *
     * Returns an associative array for a property link
     *
     * @access    public
     * @param     $text display text
     * @param     $href
     * @return    array
     */
    public function createProperty($text, $href) {
        $property = array(
            'text' => $text,
            'href' => $href
        );
        return $property;
    }

    // ------------------------------------------------------------------------

    /**
     * Create a post
     *
     * @access    public
     * @param     string|int  $id  the profile ID (eg - me)
     * @return    string      the new post ID
     */
    public function create($profile_id='me') {
        // verify the profile has required permissions
        /*if ($this->_facebook->user->hasPermission('publish_stream') === false &&
            $this->_facebook->user->hasPermission('publish_actions') === false) {
            $result['error']['message'] = 'Requires permission: publish_stream or publish_actions';
            throw new FacebookApiException($result);
        }*/

        // call the api
        $result = $this->_facebook->api('/'.$profile_id.'/feed', 'post', $this->_post);

        // return the post ID
        return $result['id'];
    }

    // ------------------------------------------------------------------------

}

/* End of file */
