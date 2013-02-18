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
 * Facebook Checkin object class
 * Requires extended permission: publish_checkins
 *
 * Assists in creating a checkin.
 *
 * NOTE: Publishing a Checkin object is deprecated in favor
 * of creating a Post with a location attached.
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
class FB_Graph_Checkin extends FB_Graph_Object {

    const CONNECTION = 'checkins';

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------

    /**
     * Set link
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
     * Set picture
     *
     * @access    public
     * @param     string  $url
     * @return    void
     */
    public function setPicture($url) {
        $this->_post['picture'] = $url;
    }

    /**
     * Set tags
     *
     * List of tagged friends
     *
     * @access    public
     * @param     string  $value  comma separated list of user IDs
     * @return    void
     */
    public function setTags($value) {
        $this->_post['tags'] = $value;
    }

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    public
     * @param     string  $place  the Place Page ID
     * @param     string  $latitude
     * @param     string  $longitude
     * @return    void
     */
    public function __construct($place, $latitude='', $longitude='') {
        parent::__construct();

        // set place
        $this->_post['place'] = $place;

        // set coordinates
        $this->_post['coordinates'] = json_encode(array(
            'latitude'  => $latitude,
            'longitude' => $longitude
        ));
    }

    // ------------------------------------------------------------------------

    /**
     * Create
     *
     * @access    public
     * @param     string|int  $id  the profile ID (eg - me)
     * @return    string      the new checkin ID
     */
    public function create($profile_id='me') {
        // verify the profile has required permissions
        $this->_verifyPermission('publish_checkins');

        // call the api
        $result = $this->_facebook->api('/'.$profile_id.'/checkins', 'post', $this->_post);

        return $result['id'];
    }

    // ------------------------------------------------------------------------

}

/* End of file */
