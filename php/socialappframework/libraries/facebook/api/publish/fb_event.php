<?php
/*
 * This file is part of the Social App Framework package.
 * (c) 2011-2013 X Studios
 *
 * You should have received a copy of the license (license.txt) distributed
 * with this package. If not, see <http://socialappframework.com/license/>.
 */

require_once dirname(__FILE__).'/fb_object.php';

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
class FB_Event extends FB_Object {

    const CONNECTION = 'events';

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------

    /**
     * Set name
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setName($value) {
        $this->_post['name'] = $value;
    }

    /**
     * Set start time, in ISO-8601
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setStartTime($value) {
        $this->_post['start_time'] = $value;
    }

    /**
     * Set end time, in ISO-8601
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setEndTime($value) {
        $this->_post['end_time'] = $value;
    }

    /**
     * Set description
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setDescription($value) {
        $this->_post['description'] = $value;
    }

    /**
     * Set location
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setLocation($value) {
        $this->_post['location'] = $value;
    }

    /**
     * Set location ID
     *
     * Facebook Place ID of the place the Event is taking place
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setLocationId($value) {
        $this->_post['location_id'] = $value;
    }

    /**
     * Set privacy type
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
