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
 * Facebook Comment object class
 * Requires extended permission: publish_stream
 *
 * Assists in creating comments.
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
class SAF_Graph_Comment extends SAF_Graph_Object {

    const CONNECTION = 'comments';

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------

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

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    public
     * @param     int     $id       the object ID to comment on
     * @param     string  $message  the comment
     * @return    void
     */
    public function __construct($message) {
        parent::__construct();
        $this->_post['message'] = $message;
    }

    // ------------------------------------------------------------------------

    /**
     * Create
     *
     * @access    public
     * @param     string|int  $id  the object ID to comment on
     * @return    string      the new comment ID
     */
    public function create($object_id) {
        // verify the profile has required permissions
        $this->_verifyPermission('publish_stream');

        // call the api
        $result = $this->_facebook->api('/'.$object_id.'/comments', 'post', $this->_post);

        return $result['id'];
    }

    // ------------------------------------------------------------------------

}

/* End of file */
