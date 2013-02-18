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
 * Facebook Notification object class
 * Requires extended permission: none
 * Required access token: app
 *
 * This does not require any special permissions but can only
 * be used with TOS'd users.
 *
 * Assists with creating notifcations.
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
class FB_Graph_Notification extends FB_Graph_Object {

    const CONNECTION = 'notifications';

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------

    /**
     * Set template
     *
     * The template text to be shown to the user.
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setTemplate($value) {
        $this->_post['template'] = $value;
    }

    /**
     * Set href
     *
     * The unique tracking data you want added to the url
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setHref($value) {
        $this->_post['href'] = $value;
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
     * User mentions in the template should use the new syntax @[USER_ID]
     * instead of the old syntax {USER_ID}
     *
     * @access    public
     * @param     string  $template  the template text
     * @param     string  $href      the tracking data added to the url
     * @return    void
     */
    public function __construct($template, $href) {
        parent::__construct();
        $this->_post['template'] = $template;
        $this->_post['href'] = $href;
    }

    // ------------------------------------------------------------------------

    /**
     * Create
     *
     * @access    public
     * @param     string|int  $profile_id  the user ID
     * @return    boolean     true if the post succeeded
     */
    public function create($profile_id=null) {
        // if id is not passed assume we are targeting the current user
        if (empty($profile_id)) {
            $profile_id = $this->_facebook->getUser();
        }

        // url
        $url = '/'.$profile_id.'/notifications';

        // add the app access token to the post
        $this->_post['access_token'] = $this->_facebook->getAppAccessToken();

        // call the api
        $result = $this->_facebook->api($url, 'post', $this->_post);

        // check for success
        if (isset($result['success'])) {
            return true;
        }

        return false;
    }

    // ------------------------------------------------------------------------

}

/* End of file */
