<?php
/*
 * This file is part of the Social App Framework package.
 * (c) 2011-2013 X Studios
 *
 * You should have received a copy of the license (license.txt) distributed
 * with this package. If not, see <http://socialappframework.com/license/>.
 */

/**
 * Social App Framework Signed Request class
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
abstract class SAF_Signed_Request extends SAF_Base {

    // ------------------------------------------------------------------------
    // PRIVATE VARS
    // ------------------------------------------------------------------------

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
     * Returns the page ID
     *
     * @access    public
     * @return    string|int
     */
    public function getPageId() {
        return $this->_page_id;
    }

     /**
     * Returns the app's access token
     *
     * @access    public
     * @return    string|int
     */
    public function getAppAccessToken() {
        return $this->getApplicationAccessToken();
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
     * Overrides the Facebook SDK's setExtendedAccessToken() method.
     * The Facebook SDK (3.2.2) doesn't set the access token property to the
     * long-lived token for some strange reason so getAccessToken() will still
     * return the short-lived token. So we have to get it from the app session
     * where the Facebook SDK stores it and manually set the access token to the
     * long-lived one.
     *
     * @access    public
     * @return    void
     */
    public function setExtendedAccessToken() {
        // if all we have is the app access token, bail...
        if ($this->getAccessToken() === $this->getApplicationAccessToken()) {
            return;
        }

        // setExtendedAccessToken() does not return a value on success, but will
        // return false on an error
        $result = parent::setExtendedAccessToken();
        if ($result === false) {
            $this->debug(__CLASS__.':: Error trying to extend the access token.');
            return;
        }

        $access_token = $this->getPersistentData('access_token');
        $this->setAccessToken($access_token);
        $this->debug(__CLASS__.':: Set extended access token.');
    }

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    public
     * @return    void
     */
    public function __construct() {
        parent::__construct();

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
        $signed_request = $this->getSignedRequest();

        // TEMP FIX, ALLOWS US TO MAKE AJAX CALLS FROM OUR APP BY GETTING
        // THE CODE FROM THE SR AND EXCHANGING IT FOR AN ACCESS TOKEN
        $this->_facebookConnect();

        // get the user id by any available means (signed request, auth code, session)
        $this->_user_id = $this->getUser();

        // immediately exchange the short-lived access token for a long-lived one
        $this->setExtendedAccessToken();

        if (!empty($this->_user_id)) {
            $this->debug(__CLASS__.':: User ID ('.$this->_user_id.').');
        }

        // if we have a signed request
        if ( !empty($signed_request) ) {

            // force user to view tab or canvas app within Facebook chrome
            // a code will only be present if the app also uses the Javascript SDK
            if ( isset($signed_request['code']) ) {
                $this->_forceFacebookChrome();
            }

            // are we viewing this within a fan page?
            // this key is only present if the app is being loaded within a page tab
            if ( isset($signed_request['page']) ) {

                // get page id
                $this->_page_id = $signed_request['page']['id'];
                $this->debug(__CLASS__.':: Page ID ('.$this->_page_id.').');

                // does the user like this page?
                $this->_page_liked = $signed_request['page']['liked'];

                if ($this->_page_liked == true) {

                    if ( $this->getPersistentData('saf_fan_gate') ) {

                        $this->_like_via_fan_gate = true;
                        // unset fan gate flag so we don't keep assuming the
                        // user liked this via fan gate
                        $this->clearPersistentData('saf_fan_gate');
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
                        $this->setPersistentData('saf_fan_gate', true);
                    }

                }

                // is user a page admin?
                if ( isset($signed_request['page']['admin']) && $signed_request['page']['admin'] == true ) {
                    $this->_page_admin = true;
                    $this->debug(__CLASS__.':: User is the page admin.');
                }

                // get app data passed via the url
                if ( isset($signed_request['app_data']))  {
                    $this->_app_data = $signed_request['app_data'];
                    $this->debug(__CLASS__.':: App data (passed via \'app_data\' GET param):', $this->_app_data);
                }

            }

            $this->debug(__CLASS__.':: SAF Signed request data:', $signed_request);

        // we are looking at the app outside of the Facebook chrome
        } else {

            $this->_forceFacebookChrome('No signed request. ');

            // facebook connect app
            if (SAF_Config::getAppType() == SAF_Config::APP_TYPE_FACEBOOK_CONNECT) {
                $this->debug(__CLASS__.':: No signed request. Viewing Facebook Connect app.', null, 3);
                $this->_facebookConnect();
            }

        }

        $this->debug('--------------------');

        // if we have a user id, create a new user
        if (!empty($this->_user_id)) {
            $this->user = new SAF_User($this, $this->_user_id);
        }

        // check if config forced a page ID
        $this->_page_id = SAF_Config::getPageId();

        // if we have a page id, create a new page
        if (!empty($this->_page_id)) {
            $this->page = new SAF_Page($this, $this->_page_id);
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Upon user login (authentication) Facebook Connect apps will have 'state'
     * and 'code' parameters passed.  The code must be exchanged for an access
     * token.
     *
     * @access    private
     * @return    void
     */
    private function _facebookConnect() {
        // Dec 5th breaking change fix: we must rely on the access token
        // we already got from exchanging the code as codes are now one-time
        // use and expire within 10 mins.
        $access_token = $this->getPersistentData('access_token');
        if ($access_token) {

            // set the SDK to use the access token
            $this->setAccessToken($access_token);

            $this->debug(__CLASS__.':: We have an existing access token.');
            return;

        }

        $signed_request = $this->getSignedRequest();
        $code = null;
        if (!empty($signed_request)) {
            $code = isset($signed_request['code']) ? $signed_request['code'] : null;
        } else {
            $code = isset($_REQUEST['code']) ? $_REQUEST['code'] : null;
        }

        // if we have a code we need to exhange it for an access token
        // this is a one-time deal so we need to store it for future visits
        if (!empty($code)) {

            // exchange the code for an access token
            $access_token = $this->getAccessTokenFromCode($code, SAF_Config::getBaseUrl());

            if (!empty($access_token)) {

                // set the SDK to use the access token
                $this->setAccessToken($access_token);
                // immediately exchange the short-lived access token for a long-lived one
                $this->setExtendedAccessToken();

                $this->debug(__CLASS__.':: Obtained access token from the request code.');

            } else {

                $this->debug(__CLASS__.':: Unable to obtain access token.', null, 3);

            }

        } 
    }

    // ------------------------------------------------------------------------

    /**
     * Ensure the user is viewing the tab or canvas app within the
     * Facebook chrome.
     *
     * @access    private
     * $param     string $reason
     * @return    string
     */
    private function _forceFacebookChrome($reason='') {
        // tab app
        if (SAF_Config::getAppType() == SAF_Config::APP_TYPE_TAB) {
            $this->debug(__CLASS__.':: '.$reason.'Viewing Tab app outside of Facebook.', null, 3);
            if ( SAF_Config::getForceFacebookView() == true ) {
                header('Location: '.SAF_Config::getPageTabUrl());
                exit;
            }
        }

        // canvas app
        if (SAF_Config::getAppType() == SAF_Config::APP_TYPE_CANVAS) {
            $this->debug(__CLASS__.':: '.$reason.'Viewing Canvas app outside of Facebook.', null, 3);
            if ( SAF_Config::getForceFacebookView() == true ) {
                header('Location: '.SAF_Config::getCanvasUrl());
                exit;
            }
        }
    }

    // ------------------------------------------------------------------------

}

/* End of file */
