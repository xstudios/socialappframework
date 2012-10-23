<?php
/**
 * Social App Framework Facebook Feed Class
 *
 * This class is simply a custom wrapper around
 * Facebook's own /profile_id/feed API call and gives
 * us some nice code completion as well
 *
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 * @version      1.0
 * @copyright    2012 X Studios
 * @link         http://www.xstudiosinc.com
 *
 * You should have received a copy of the license along with this program.
 * If not, see <http://socialappframework.com/license/>.
 */
class FB_Feed {

    const TYPE_USER = 'user';
    const TYPE_PAGE = 'page';

    private $_facebook; // Facebook SDK

    private $_publish_data = array();

    private $_access_token;

    private $_from;
    private $_to;

    private $_link;
    private $_picture;
    private $_source;

    private $_name;
    private $_caption;
    private $_description;
    private $_message;

    private $_properties;
    private $_actions;

    private $_ref;

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------

    /**
     * SET FACEBOOK
     *
     * Set the facebook sdk instance
     *
     * @access    public
     * @param     $facebook facebook SDK instance
     * @return    void
     */
    public function setFacebook($facebook) {
        $this->_facebook = $facebook;
    }

    /**
     * SET ACCESS TOKEN
     *
     * Set the access token to use
     *
     * @access    public
     * @param     $token access token
     * @return    void
     */
    public function setAccessToken($token) {
        $this->_access_token = $token;
    }

    /**
     * SET FROM
     *
     * The ID or username of the user posting the message.
     * If this is unspecified, it defaults to the current user.
     * If specified, it must be the ID of the user or of a
     * page that the user administers
     *
     * @access    public
     * @param     $user_id
     * @return    void
     */
    public function setFrom($user_id) {
        $this->_from = $user_id;
    }

    /**
     * SET TO
     *
     * The ID or username of the profile that this story will be
     * published to. If this is unspecified, it defaults to the
     * value of from.
     *
     * @access    public
     * @param     $user_id
     * @return    void
     */
    public function setTo($user_id) {
        $this->_to = $user_id;
    }

    /**
     * SET LINK
     *
     * The link attached to this post
     *
     * @access    public
     * @param     $url
     * @return    void
     */
    public function setLink($url) {
        $this->_link = $url;
    }

    /**
     * SET PICTURE
     *
     * The URL of a picture attached to this post. The picture
     * must be at least 50px by 50px and have a maximum aspect
     * ratio of 3:1
     *
     * @access    public
     * @param     $url
     * @return    void
     */
    public function setPicture($url) {
        $this->_picture = $url;
    }

    /**
     * SET SOURCE
     *
     * The URL of a media file (either SWF or MP3) attached to
     * this post. If both source and picture are specified,
     * only source is used.
     *
     * @access    public
     * @param     $url
     * @return    void
     */
    public function setSource($url) {
        $this->_source = $url;
    }

    /**
     * SET NAME
     *
     * The name of the link attachment.
     *
     * @access    public
     * @param     $value
     * @return    void
     */
    public function setName($value) {
        $this->_name = $value;
    }

    /**
     * SET CAPTION
     *
     * The caption of the link (appears beneath the link name).
     * If not specified, this field is automatically populated
     * with the URL of the link.
     *
     * @access    public
     * @param     $value
     * @return    void
     */
    public function setCaption($value) {
        $this->_caption = $value;
    }

    /**
     * SET DESCRIPTION
     *
     * The description of the link (appears beneath the link
     * caption). If not specified, this field is automatically
     * populated by information scraped from the link,
     * typically the title of the page.
     *
     * @access    public
     * @param     $value
     * @return    void
     */
    public function setDescription($value) {
        $this->_description = $value;
    }

    /**
     * SET MESSAGE
     *
     * The message that appears in the post. Facebook
     * suggests not pre-populating this parameter?
     *
     * @access    public
     * @param     $value
     * @return    void
     */
    public function setMessage($value) {
        $this->_message = $value;
    }

    /**
     * SET PROPERTIES
     *
     * A JSON object of key/value pairs which will appear in
     * the stream attachment beneath the description, with
     * each property on its own line. Keys must be strings,
     * and values can be either strings or JSON objects with
     * the keys text and href.
     *
     * @access    public
     * @param     $json_obj
     * @return    void
     */
    public function setProperties($json_obj) {
        $this->_properties = $json_obj;
    }

    /**
     * SET ACTIONS
     *
     * A JSON array containing a single object describing the
     * action link which will appear next to the "Comment"
     * and "Like" link under posts. The contained object must
     * have the keys name and link.
     *
     * @access    public
     * @param     $json_obj
     * @return    void
     */
    public function setActions($json_obj) {
        $this->_actions = $json_obj;
    }

    /**
     * SET REF
     *
     * A text reference for the category of feed post. This
     * category is used in Facebook Insights to help you
     * measure the performance of different types of post
     *
     * @access    public
     * @param     $json_obj
     * @return    void
     */
    public function setRef($value) {
        $this->_ref = $value;
    }

    // ------------------------------------------------------------------------

    /**
     * CONSTRUCTOR
     *
     * @access    public
     * @param     $facebook Facebook SDK instance (can be set later before publish is called)
     * @return    void
     */
    public function __construct($facebook=null) {
        // set reference to Facebook SDK instance
        $this->_facebook = $facebook;
    }

    // ------------------------------------------------------------------------

    /**
     * PUBLISH
     *
     * Publish a facebook feed and returns an object
     * with success or failure info
     *
     * @access    public
     * @param     $profile_id id of the user/page to publish to
     * @param     $type user or page eg - FB_PublishFeed::TYPE_USER
     * @return    object
     */
    public function publish($profile_id, $type=FB_Feed::TYPE_USER) {
        // create response object
        $response = new stdClass();
        $response->error = false;

        try {

            $result = $this->_facebook->api('/'.$profile_id.'/feed', 'post', $this->_publishData());

            // pass back publish data (for debug usually)
            //$response->publishData = $this->_publishData();

            if ($result['id']) {
                $response->message = ' Posted as '.$type.' ('.$profile_id.') with post id: '.$result['id'].'.';
                $response->post_id = $result['id'];
            } else {
                $response->error = true;
                $response->message = ' Failed to post as '.$type.' ('.$profile_id.').';
            }

        } catch (FacebookApiException $e) {

            $response->error = true;
            $response->message = ' Failed to post as '.$type.' ('.$profile_id.'). '.$e;

        }

        return $response;
    }

    // ------------------------------------------------------------------------

    /**
     * CREATE ACTION
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
     * CREATE PROPERTY
     *
     * Returns an associative array for an property link
     *
     * @access    public
     * @param     $name
     * @param     $text display text
     * @param     $href
     * @return    array
     */
    public function createProperty($name, $text, $href) {
        $property = array(
            'name' => $name,
            'text' => $text,
            'href' => $href
        );

        return $property;
    }

    // ------------------------------------------------------------------------

    /**
     * PUBLISH DATA
     *
     * Returns an associative array with only valid (non null) params
     *
     * @access    private
     * @return    array
     */
    private function _publishData() {
        $publish_vars = array(
            'access_token' => $this->_access_token,

            'from' => $this->_from,
            'to' => $this->_to,

            'link' => $this->_link,
            'picture' => $this->_picture,
            'source' => $this->_source,

            'name' => $this->_name,
            'caption' => $this->_caption,
            'description' => $this->_description,
            'message' => $this->_message,

            'properties' => $this->_properties,
            'actions' => $this->_actions,

            'ref' => $this->_ref
        );

        // for each of the publish vars add them to the publish data array (if not null)
        foreach ($publish_vars as $k => $v) {
            if ( !empty($v) ) $this->_publish_data[$k] = $v;
        }

        return $this->_publish_data;
    }

    // ------------------------------------------------------------------------

}

/* End of file FB_Feed.php */
/* Location: ./socialappframework/libraries/FB_Feed.php */
