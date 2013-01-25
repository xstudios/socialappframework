<?php
/*
 * This file is part of the Social App Framework package.
 * (c) 2011-2013 X Studios
 *
 * You should have received a copy of the license (license.txt) distributed
 * with this package. If not, see <http://socialappframework.com/license/>.
 */

/**
 * Facebook object class
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
abstract class FB_Object {

    /**
     * Post array
     *
     * @var    array
     */
	protected $_post = array();

    /**
     * SAF instance
     *
     * @var  SAF
     */
    protected $_facebook;

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    public
     * @return    void
     */
    public function __construct() {
        $this->_facebook = SAF::instance();
    }

    // ------------------------------------------------------------------------

    /**
     * Delete an object
     *
     * Note that some objects can't be deleted: checkins, albums, notifications
     *
     * @access    protected
     * @param     string|int  $object_id  the object ID
     * @return    boolean     true if the delete succeeded
     */
    public function delete($object_id) {
        // verify the profile has required permissions
        $this->_verifyPermission('publish_stream');

        // call the api
        $result = $this->_facebook->api('/'.$object_id, 'delete');

        return $result;
    }

    // ------------------------------------------------------------------------

    /**
     * Verify permissions
     *
     * @access    protected
     * @return    void
     */
    protected function _verifyPermission($perm) {
        if ($this->_facebook->user->hasPermission($perm) === false) {
            $result['error']['message'] = 'Requires permission: '.$perm;
            throw new FacebookApiException($result);
        }
    }

    // ------------------------------------------------------------------------

}

/* End of file */
