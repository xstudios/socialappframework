<?php
/*
 * This file is part of the Social App Framework package.
 * (c) 2011-2013 X Studios
 *
 * You should have received a copy of the license (license.txt) distributed
 * with this package. If not, see <http://socialappframework.com/license/>.
 */

//namespace SocialAppFramework;

// facebook sdk
require_once dirname(__FILE__).'/../../../../facebook/php-sdk/src/facebook.php';

// saf facebook graph libraries
require_once dirname(__FILE__).'/Graph/Object.php';
require_once dirname(__FILE__).'/Graph/Notification.php';
require_once dirname(__FILE__).'/Graph/Post.php';
require_once dirname(__FILE__).'/Graph/Comment.php';
require_once dirname(__FILE__).'/Graph/Question.php';
require_once dirname(__FILE__).'/Graph/Note.php';
require_once dirname(__FILE__).'/Graph/Link.php';
require_once dirname(__FILE__).'/Graph/Album.php';
require_once dirname(__FILE__).'/Graph/Event.php';

// saf libraries
require_once dirname(__FILE__).'/SAF_Config.php';
require_once dirname(__FILE__).'/BaseSaf.php';
require_once dirname(__FILE__).'/SafFacebook.php';
require_once dirname(__FILE__).'/SignedRequest.php';
require_once dirname(__FILE__).'/Page/Page.php';
require_once dirname(__FILE__).'/Page/PageConnection.php';
require_once dirname(__FILE__).'/User/User.php';
require_once dirname(__FILE__).'/User/UserConnection.php';

// config (must be loaded after saf_config.php)
require_once dirname(__FILE__).'/config/config.php';

// helpers
require_once dirname(__FILE__).'/Helpers/FB_Helper.php';

/**
 * SAF class
 * We don't really do anything here but load all required files
 * and give ourselves a nice way to instantiate with new SAF:instance()
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
class SAF extends SafFacebook {

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
        return new Album($name);
    }

    /**
     * Returns a Facebook Comment
     *
     * @access    public
     * @param     string  $message
     * @return    FB_Graph_Comment
     */
    public static function createComment($message) {
        return new Comment($message);
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
        return new Event($name, $start_time);
    }

    /**
     * Returns a Facebook Link
     *
     * @access    public
     * @param     string  $url
     * @return    FB_Graph_Link
     */
    public static function createLink($url) {
        return new Link($url);
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
        return new Note($subject, $message);
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
        return new Notification($template, $href);
    }

    /**
     * Returns a Facebook Post
     *
     * @access    public
     * @param     string  $message
     * @return    FB_Graph_Post
     */
    public static function createPost($message) {
        return new Post($message);
    }

    /**
     * Returns a Facebook Question
     *
     * @access    public
     * @param     string  $question  the text of the question
     * @return    FB_Graph_Question
     */
    public static function createQuestion($question) {
        return new Question($question);
    }

    // ------------------------------------------------------------------------

}

/* End of file */
