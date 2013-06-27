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
require_once dirname(__FILE__).'/Graph/SAF_Graph_Object.php';
require_once dirname(__FILE__).'/Graph/SAF_Graph_Notification.php';
require_once dirname(__FILE__).'/Graph/SAF_Graph_Post.php';
require_once dirname(__FILE__).'/Graph/SAF_Graph_Comment.php';
require_once dirname(__FILE__).'/Graph/SAF_Graph_Question.php';
require_once dirname(__FILE__).'/Graph/SAF_Graph_Note.php';
require_once dirname(__FILE__).'/Graph/SAF_Graph_Link.php';
require_once dirname(__FILE__).'/Graph/SAF_Graph_Album.php';
require_once dirname(__FILE__).'/Graph/SAF_Graph_Event.php';

// saf libraries
require_once dirname(__FILE__).'/SAF_Config.php';
require_once dirname(__FILE__).'/SAF_Base.php';
require_once dirname(__FILE__).'/SAF_Facebook.php';
require_once dirname(__FILE__).'/SAF_SignedRequest.php';

require_once dirname(__FILE__).'/Page/SAF_Page.php';
require_once dirname(__FILE__).'/Page/SAF_PageConnection.php';
require_once dirname(__FILE__).'/User/SAF_User.php';
require_once dirname(__FILE__).'/User/SAF_UserConnection.php';

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
class SAF extends SAF_Facebook {

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
     * @return    SAF_Graph_Album
     */
    public static function createAlbum($name) {
        return new SAF_Graph_Album($name);
    }

    /**
     * Returns a Facebook Comment
     *
     * @access    public
     * @param     string  $message
     * @return    SAF_Graph_Comment
     */
    public static function createComment($message) {
        return new SAF_Graph_Comment($message);
    }

    /**
     * Returns a Facebook Event
     *
     * @access    public
     * @param     string  $name        the event name
     * @param     string  $start_time  the event start time, in ISO-8601
     * @return    SAF_Graph_Event
     */
    public static function createEvent($name, $start_time) {
        return new SAF_Graph_Event($name, $start_time);
    }

    /**
     * Returns a Facebook Link
     *
     * @access    public
     * @param     string  $url
     * @return    SAF_Graph_Link
     */
    public static function createLink($url) {
        return new SAF_Graph_Link($url);
    }

    /**
     * Returns a Facebook Note
     *
     * @access    public
     * @param     string  $subject  the subject
     * @param     string  $message  the comment
     * @return    SAF_Graph_Note
     */
    public static function createNote($subject, $message) {
        return new SAF_Graph_Note($subject, $message);
    }

    /**
     * Returns a Facebook Notification
     *
     * @access    public
     * @param     string  $template  the template text
     * @param     string  $href      the tracking data added to the url
     * @return    SAF_Graph_Notification
     */
    public static function createNotification($template, $href) {
        return new SAF_Graph_Notification($template, $href);
    }

    /**
     * Returns a Facebook Post
     *
     * @access    public
     * @param     string  $message
     * @return    SAF_Graph_Post
     */
    public static function createPost($message) {
        return new SAF_Graph_Post($message);
    }

    /**
     * Returns a Facebook Question
     *
     * @access    public
     * @param     string  $question  the text of the question
     * @return    SAF_Graph_Question
     */
    public static function createQuestion($question) {
        return new SAF_Graph_Question($question);
    }

    // ------------------------------------------------------------------------

}

/* End of file */
