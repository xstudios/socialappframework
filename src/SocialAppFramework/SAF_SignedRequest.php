<?php
/*
 * This file is part of the Social App Framework package.
 * (c) 2011-2013 X Studios
 *
 * You should have received a copy of the license (license.txt) distributed
 * with this package. If not, see <http://socialappframework.com/license/>.
 */

//namespace SocialAppFramework;

/**
 * Social App Framework Signed Request class
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
class SAF_SignedRequest extends SAF_Base {

    // ------------------------------------------------------------------------
    // PRIVATE VARS
    // ------------------------------------------------------------------------

    /**
     * Facebook instance
     *
     * @access    private
     * @var       SAF_Base
     */
    private $_facebook;

    /**
     * Signed Request data
     *
     * @access    private
     * @var       array
     */
    private $_data;

    /**
     * The user ID
     *
     * @access    private
     * @var       string|int
     */
    private $_user_id;

    /**
     * The page ID
     *
     * @access    private
     * @var       string|int
     */
    private $_page_id;

    /**
     * Is the user the page admin?
     *
     * @access    private
     * @var       boolean
     */
    private $_page_admin = false;

    /**
     * Does the user like this page?
     *
     * @access    private
     * @var       boolean
     */
    private $_page_liked = false;

    /**
     * Was the page liked via a Fan Gate?
     *
     * @access    private
     * @var       boolean
     */
    private $_like_via_fan_gate = false;

    /**
     * App data passed to the app tab via &app_data=something
     *
     * @access    private
     * @var       mixed
     */
    private $_app_data;

     /**
     * Is the page being viewed in the Facebook chrome?
     *
     * @access    private
     * @var       boolean
     */
    private $_in_facebook_chrome = false;

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------

    /**
     * Returns the user ID
     *
     * @access    public
     * @return    string|int
     */
    public function getUserId() {
        return $this->_user_id;
    }

    /**
     * Returns the user's country
     *
     * @access    public
     * @return    string
     */
    public function getUserCountry() {
        return $this->_data['user']['country'];
    }

    /**
     * Returns the user's locale
     *
     * @access    public
     * @return    string
     */
    public function getUserLocale() {
        return $this->_data['user']['locale'];
    }

    /**
     * Returns the user's age range
     *
     * The age object, which is part of the user object will only be
     * returned in the signed_request parameter for Canvas Apps;
     * it will not be returned for external apps.
     *
     * @access    public
     * @return    string
     */
    public function getUserAgeRange() {
        if (isset($this->_data['user']['age'])) {
            switch($this->_data['user']['age']['min']) {
                case 13:
                    return '13-17';
                    break;
                case 18:
                    return '18-20';
                    break;
                case 21:
                    return '21+';
                    break;
                default:
                    return 'Unknown';
            }
        }
    }

    /**
     * Returns the page ID
     *
     * @access    public
     * @return    string|int
     */
    public function getPageId() {
        return $this->_page_id;
    }

    /**
     * Returns the app data
     *
     * @access    public
     * @return    mixed
     */
    public function getAppData() {
        return $this->_app_data;
    }

    /**
     * Returns true if the user is the page admin
     *
     * @access    public
     * @return    boolean
     */
    public function isPageAdmin() {
        return $this->_page_admin;
    }

    /**
     * Returns true if the user likes this page
     *
     * @access    public
     * @return    boolean
     */
    public function isPageLiked() {
        return $this->_page_liked;
    }

    /**
     * Returns true if the page was liked via fan gate
     *
     * @access    public
     * @return    boolean
     */
    public function isPageLikeViaFanGate() {
        return $this->_like_via_fan_gate;
    }

    /**
     * Returns true if the page is being viewed in the Facebook chrome
     *
     * @access    public
     * @return    boolean
     */
    public function isInFacebookChrome() {
        return $this->_in_facebook_chrome;
    }

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    public
     * @param     SAF_Base  $facebook
     * @return    void
     */
    public function __construct($facebook) {
        $this->_facebook = $facebook;

        $this->_init();
    }

    // ------------------------------------------------------------------------
    // PRIVATE METHODS
    // ------------------------------------------------------------------------

    /**
     * Init
     *
     * @access    private
     * @return    void
     */
    private function _init() {

        // get the signed request (only available for tab or canvas apps)
        // however, it will exist on Facebook Connect apps if using the
        // Javascript SDK.
        $this->_data = $this->_facebook->getSignedRequest();

        // if we have a signed request
        if ( !empty($this->_data) ) {

            // a code will only be present if the app also uses the Javascript SDK
            if ( isset($this->_data['code']) ) {

                // force user to view tab or canvas app within Facebook chrome
                $this->_forceFacebookChrome();

            }

            // the signed request should solely determine who the user is
            if (isset($this->_data['user_id'])) {
                $this->_user_id = $this->_data['user_id'];
                //$this->debug(__CLASS__.':: User ID ('.$this->_user_id.').');
            }

            // are we viewing this within a fan page?
            // only present if the app is being loaded within a page tab
            if ( isset($this->_data['page']) ) {

                // get page id
                $this->_page_id = $this->_data['page']['id'];
                //$this->debug(__CLASS__.':: Page ID ('.$this->_page_id.').');

                // flag whether or not we are in the Facebook chrome
                if ($this->_page_id) {
                    $this->_in_facebook_chrome = true;
                }

                // does the user like this page?
                $this->_page_liked = $this->_data['page']['liked'];

                if ($this->_page_liked == true) {

                    if ($this->getSafPersistentData('fan_gate')) {

                        $this->_like_via_fan_gate = true;
                        // unset fan gate flag so we don't keep assuming the
                        // user liked this via fan gate
                        $this->clearSafPersistentData('fan_gate');
                        $this->debug(__CLASS__.':: User likes this page (via Fan Gate).');

                    } else {

                        $this->debug(__CLASS__.':: User likes this page.');

                    }

                } else {

                    // if we have a user id
                    if (!empty($this->_user_id)) {
                        $this->debug(__CLASS__.':: User does not like this page.');
                        // set a session flag so we know when the user originally
                        // came here they did not like the page
                        $this->setSafPersistentData('fan_gate', true);
                    }

                }

                // is user a page admin?
                if ( isset($this->_data['page']['admin']) && $this->_data['page']['admin'] == true ) {
                    $this->_page_admin = true;
                    $this->debug(__CLASS__.':: User is the page admin.');
                }

                // get app data passed via the url
                if ( isset($this->_data['app_data']))  {
                    $this->_app_data = $this->_data['app_data'];
                    $this->debug(__CLASS__.':: App data (passed via \'app_data\' GET param):', $this->_app_data);
                }

            }

            $this->debug(__CLASS__.':: SAF Signed request data:', $this->_data);

        // we are looking at the app outside of the Facebook chrome
        // or it's a subsequent page load or ajax request
        } else {

            if (SAF_Config::getAppType() == SAF_Config::APP_TYPE_FACEBOOK_CONNECT) {
                $this->debug(__CLASS__.':: No signed request. Viewing Facebook Connect app.', null, 3);
            } else {
                $this->debug(__CLASS__.':: No signed request.', null, 3);
            }

            $this->_forceFacebookChrome();

        }

        $this->debug('--------------------');

    }

    // ------------------------------------------------------------------------

    /**
     * Ensure the user is viewing the tab or canvas app within the
     * Facebook chrome.
     *
     * @access    private
     * @return    string
     */
    private function _forceFacebookChrome() {
        // tab app
        if (SAF_Config::getAppType() == SAF_Config::APP_TYPE_TAB) {
            $this->debug(__CLASS__.':: Viewing Tab app outside of Facebook.', null, 3);
            if ( SAF_Config::getForceFacebookView() == true ) {
                header('Location: '.SAF_Config::getTabUrl());
                exit;
            }
        }

        // canvas app
        if (SAF_Config::getAppType() == SAF_Config::APP_TYPE_CANVAS) {
            $this->debug(__CLASS__.':: Viewing Canvas app outside of Facebook.', null, 3);
            if ( SAF_Config::getForceFacebookView() == true ) {
                header('Location: '.SAF_Config::getCanvasUrl());
                exit;
            }
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Returns a key value whether it exists or not
     *
     * @access    private
     * @param     string $key key to check for
     * @param     mixed $default default value if not set
     * @return    mixed
     */
    private function _getValue($key, $default=false) {
        // first, look in data we *should* have recevied from the graph
        if ( isset($this->_data[$key]) ) {
            return $this->_data[$key];
        }

        // if all else fails, we return the default value
        return $default;
    }

    // ------------------------------------------------------------------------

}

/* End of file */
