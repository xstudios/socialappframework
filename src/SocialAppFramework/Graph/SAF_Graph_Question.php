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
 * Facebook Question object class
 * Requires extended permission: publish_stream
 *
 * Assists with creating question posts
 *
 * April 3, 2013 Breaking Change: Removing ability to POST to USER_ID/questions
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
class SAF_Graph_Question extends SAF_Graph_Object {

    const CONNECTION = 'questions';

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------

    /**
     * Set question
     *
     * The text of the question
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setQuestion($value) {
        $this->_post['question'] = $value;
    }

    /**
     * Set options
     *
     * Array of answer options
     *
     * @access    public
     * @param     array  $options
     * @return    void
     */
    public function setOptions($options) {
        $this->_post['options'] = $options;
    }

    /**
     * Set allow new options
     *
     * Allows other users to add new options (True by default)
     *
     * @access    public
     * @param     boolean  $value
     * @return    void
     */
    public function setAllowNewOptions($value) {
        $this->_post['allow_new_options'] = $value;
    }

    /**
     * Set published
     *
     * Whether a post is published. Default is published.
     *
     * Requires extended permissions: publish_stream, manage_pages
     * Only available when publishing to a page.
     *
     * @access    public
     * @param     boolean  $value
     * @return    void
     */
    public function setPublished($value) {
        $this->_post['published'] = $value;
    }

    /**
     * Set scheduled publish time
     *
     * Time when the page post should go live, this should be between 10 mins
     * and 6 months from the time of publishing the post.
     *
     * Requires extended permissions: publish_stream, manage_pages
     * Only available when publishing to a page.
     *
     * @access    public
     * @param     string  $timestamp  a unix timestamp
     * @return    void
     */
    public function setScheduledPublishTime($timestamp) {
        $this->_post['scheduled_publish_time'] = $timestamp;
    }

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * Requires extended permission: publish_stream
     *
     * @access    public
     * @param     string  $question  the text of the question
     * @return    void
     */
    public function __construct($question) {
        parent::__construct();
        $this->_post['question'] = $question;
    }

    // ------------------------------------------------------------------------

    /**
     * Create a question
     *
     * @access    public
     * @param     string|int  $id  the profile ID (must be a page)
     * @return    string      the new question ID
     */
    public function create($profile_id) {
        // call the api
        $result = $this->_facebook->api('/'.$profile_id.'/questions', 'post', $this->_post);

        // return the post ID
        return $result['id'];
    }

    // ------------------------------------------------------------------------

}

/* End of file */
