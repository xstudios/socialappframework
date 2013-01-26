<?php
/*
 * This file is part of the Social App Framework package.
 * (c) 2011-2013 X Studios
 *
 * You should have received a copy of the license (license.txt) distributed
 * with this package. If not, see <http://socialappframework.com/license/>.
 */

// facebook api
require_once dirname(__FILE__).'/facebook/sdk/facebook.php';
require_once dirname(__FILE__).'/facebook/graph/fb_graph_object.php';
require_once dirname(__FILE__).'/facebook/graph/fb_graph_notification.php';
require_once dirname(__FILE__).'/facebook/graph/fb_graph_post.php';
require_once dirname(__FILE__).'/facebook/graph/fb_graph_comment.php';
require_once dirname(__FILE__).'/facebook/graph/fb_graph_question.php';
require_once dirname(__FILE__).'/facebook/graph/fb_graph_note.php';
require_once dirname(__FILE__).'/facebook/graph/fb_graph_link.php';
require_once dirname(__FILE__).'/facebook/graph/fb_graph_album.php';
require_once dirname(__FILE__).'/facebook/graph/fb_graph_checkin.php';
require_once dirname(__FILE__).'/facebook/graph/fb_graph_event.php';

// saf
require_once dirname(__FILE__).'/saf_config.php';
require_once dirname(__FILE__).'/saf_base.php';
require_once dirname(__FILE__).'/saf_debug.php';
require_once dirname(__FILE__).'/saf_signed_request.php';
require_once dirname(__FILE__).'/saf_page.php';
require_once dirname(__FILE__).'/saf_page_connection.php';
require_once dirname(__FILE__).'/saf_user.php';
require_once dirname(__FILE__).'/saf_user_connection.php';

// config (must be loaded after saf_config.php)
require_once dirname(__FILE__).'/../config/config.php';

// helpers
require_once dirname(__FILE__).'/../helpers/fb_helper.php';

/**
 * SAF class
 * We don't really do anything here but load all required files
 * and give ourselves a nice way to instantiate with new SAF:instance()
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
class SAF extends SAF_Base {

    /**
     * SAF object
     *
     * @access    protected
     * @var       SAF
     */
    protected static $_instance = null;

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    public
     * @return    void
     */
    public function __construct() {
        // this is used in conjuction with SAF_Config::setThirdPartyCookieFix(true)
        // allows us a workaround for browsers which do not allow 3rd party
        // cookies (eg - cookies from iframe apps)
        if ( SAF_Config::getThirdPartyCookieFix() == true && isset($_GET['saf_redirect']) == true ) {
            header('Location: '.$_GET['saf_redirect']);
            exit;
        }

        parent::__construct();
    }

    // ------------------------------------------------------------------------
    // PUBLIC METHODS
    // ------------------------------------------------------------------------

    /**
     * Get instance
     *
     * @access    public
     * @return    SAF
     */
    final public static function instance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    // ------------------------------------------------------------------------

    /**
     * Disallow cloning
     */
    final public function __clone() {
        return false;
    }

    // ------------------------------------------------------------------------
    // FACTORY METHODS
    // ------------------------------------------------------------------------

    /**
     * Returns a Facebook Album
     *
     * @access    public
     * @param     string  $name
     * @return    FB_Graph_Album
     */
    public static function createAlbum($name) {
        return new FB_Graph_Album($name);
    }

    /**
     * Returns a Facebook Checkin
     *
     * @access    public
     * @param     string  $place  the Place Page ID
     * @param     string  $latitude
     * @param     string  $longitude
     * @return    FB_Graph_Checkin
     */
    public static function createCheckin($place, $latitude, $longitude) {
        return new FB_Graph_Checkin($place, $latitude, $longitude);
    }

    /**
     * Returns a Facebook Comment
     *
     * @access    public
     * @param     string  $message
     * @return    FB_Graph_Comment
     */
    public static function createComment($message) {
        return new FB_Graph_Comment($message);
    }

    /**
     * Returns a Facebook Event
     *
     * @access    public
     * @param     string  $name        the event name
     * @param     string  $start_time  the event start time, in ISO-8601
     * @return    FB_Graph_Event
     */
    public static function createEvent($name, $start_time) {
        return new FB_Graph_Event($name, $start_time);
    }

    /**
     * Returns a Facebook Link
     *
     * @access    public
     * @param     string  $url
     * @return    FB_Graph_Link
     */
    public static function createLink($url) {
        return new FB_Graph_Link($url);
    }

    /**
     * Returns a Facebook Note
     *
     * @access    public
     * @param     string  $subject  the subject
     * @param     string  $message  the comment
     * @return    FB_Graph_Note
     */
    public static function createNote($subject, $message) {
        return new FB_Graph_Note($subject, $message);
    }

    /**
     * Returns a Facebook Notification
     *
     * @access    public
     * @param     string  $template  the template text
     * @param     string  $href      the tracking data added to the url
     * @return    FB_Graph_Notification
     */
    public static function createNotification($template, $href) {
        return new FB_Graph_Notification($template, $href);
    }

    /**
     * Returns a Facebook Post
     *
     * @access    public
     * @param     string  $message
     * @return    FB_Graph_Post
     */
    public static function createPost($message) {
        return new FB_Graph_Post($message);
    }

    /**
     * Returns a Facebook Question
     *
     * @access    public
     * @param     string  $question  the text of the question
     * @return    FB_Graph_Question
     */
    public static function createQuestion($question) {
        return new FB_Graph_Question($question);
    }

    // ------------------------------------------------------------------------

}

/* End of file */
