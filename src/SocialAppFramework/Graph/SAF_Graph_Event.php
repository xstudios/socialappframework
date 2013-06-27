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
 * Facebook Event object class
 * Requires extended permission: create_event
 * Requires access token: user
 *
 * Assists in creating events.
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
class SAF_Graph_Event extends SAF_Graph_Object {

    const CONNECTION = 'events';

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------

    /**
     * Sets the name
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setName($value) {
        $this->_post['name'] = $value;
    }

    /**
     * Sets the start time, in ISO-8601
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setStartTime($value) {
        $this->_post['start_time'] = $value;
    }

    /**
     * Sets the end time, in ISO-8601
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setEndTime($value) {
        $this->_post['end_time'] = $value;
    }

    /**
     * Sets the description
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setDescription($value) {
        $this->_post['description'] = $value;
    }

    /**
     * Sets the location
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setLocation($value) {
        $this->_post['location'] = $value;
    }

    /**
     * Sets the location ID
     *
     * Facebook Place ID where the event is taking place
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setLocationId($value) {
        $this->_post['location_id'] = $value;
    }

    /**
     * Sets the privacy type
     *
     * OPEN (default), SECRET, or FRIENDS
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setPrivacyType($value='OPEN') {
        $this->_post['privacy_type'] = $value;
    }

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    public
     * @param     string  $name        the event name
     * @param     string  $start_time  the event start time, in ISO-8601
     * @return    void
     */
    public function __construct($name, $start_time) {
        parent::__construct();
        $this->_post['name'] = $name;
        $this->_post['start_time'] = $start_time;
    }

    // ------------------------------------------------------------------------

    /**
     * Create
     *
     * @access    public
     * @param     string|int  $object_id  the object ID (eg - me)
     * @return    string      the new event ID
     */
    public function create($object_id='me') {
        // verify the profile has required permissions
        $this->_verifyPermission('create_event');

        // call the api
        $result = $this->_facebook->api('/'.$object_id.'/events', 'post', $this->_post);

        return $result['id'];
    }

    // ------------------------------------------------------------------------

}

/* End of file */
