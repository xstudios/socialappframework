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
 * Facebook Note object class
 * Requires extended permission: publish_stream (and manage_pages if posting as
 * a page)
 *
 * Assists with creating notes.
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
class FB_Note extends FB_Object {

    const CONNECTION = 'notes';

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------

    /**
     * Set subject
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setSubject($value) {
        $this->_post['subject'] = $value;
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
     * Get post
     *
     * @access    public
     * @return    array
     */
    public function getPost() {
        return $this->_post;
    }

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    public
     * @param     string  $subject  the subject
     * @param     string  $message  the comment
     * @return    void
     */
    public function __construct($subject, $message) {
        parent::__construct();
        $this->_post['subject'] = $subject;
        $this->_post['message'] = $message;
    }

    // ------------------------------------------------------------------------

    /**
     * Create a note
     *
     * @access    public
     * @param     string|int  $id  the profile ID (eg - me)
     * @return    string      the new note ID
     */
    public function create($profile_id='me') {
        // verify the profile has required permissions
        $this->_verifyPermission('publish_stream');

        // call the api
        $result = $this->_facebook->api('/'.$profile_id.'/notes', 'post', $this->_post);

        return $result['id'];
    }

    // ------------------------------------------------------------------------

}

/* End of file */
