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
 * Facebook Album object class
 * Requires extended permission: publish_stream
 *
 * This class can be used completely stand-alone as long as you have a valid
 * access token to make Facebook graph calls.
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
class SAF_Graph_Album extends SAF_Graph_Object {

    const CONNECTION = 'albums';

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------

    /**
     * Set message
     *
     * Album description
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setMessage($value) {
        $this->_post['message'] = $value;
    }

    /**
     * Set privacy
     *
     * https://developers.facebook.com/docs/reference/api/privacy-parameter/
     *
     * @access    public
     * @param     array  $array  privacy parameter
     * @return    void
     */
    public function setPrivacy($array) {
        $this->_post['privacy'] = json_encode($array);
    }

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    public
     * @param     string  $name  the album name
     * @return    void
     */
    public function __construct($name) {
        parent::__construct();
        $this->_post['name'] = $name;
        // set default privacy to ALL_FRIENDS and not EVERYONE
        $this->_post['privacy'] = json_encode(array('value'=>'ALL_FRIENDS'));
    }

    // ------------------------------------------------------------------------

    /**
     * Create a album
     *
     * @access    public
     * @param     string|int  $id  the profile ID (eg - me)
     * @return    string      the new album ID
     */
    public function create($profile_id='me') {
        // call the api
        $result = $this->_facebook->api('/'.$profile_id.'/albums', 'post', $this->_post);

        return $result['id'];
    }

    // ------------------------------------------------------------------------

}

/* End of file */
