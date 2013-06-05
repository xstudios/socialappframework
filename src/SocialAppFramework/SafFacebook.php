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
 * Social App Framework Facebook class
 *
 * We extend the Facebook class in order to make Social App Framework
 * a much more powerful and helpful library than the Facebook SDK is alone.
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
abstract class SafFacebook extends Facebook {

    const SAF_VERSION = '1.0.0';

    // ------------------------------------------------------------------------
    // PUBLIC VARS
    // ------------------------------------------------------------------------

    /**
     * SAF_Signed_Request object
     *
     * @access    public
     * @var       SignedRequest
     */
    public $sr;

    /**
     * SAF_Page object
     *
     * @access    public
     * @var       Page
     */
    public $page;

    /**
     * SAF_User object
     *
     * @access    public
     * @var       User
     */
    public $user;

    // ------------------------------------------------------------------------
    // PRIVATE VARS
    // ------------------------------------------------------------------------

    /**
     * The permissions the app is asking for
     *
     * @access    private
     * @var       string
     */
    private $_extended_perms = '';

    /**
     * Redirect URL used for login URL
     *
     * @access    private
     * @var       string
     */
    private $_redirect_url;

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    public
     * @return    void
     */
    public function __construct() {
        // cookies for iframes in IE fix
        header('P3P: CP="SAF"');

        // construct Facebook SDK
        parent::__construct(array(
            'appId'      => SAF_Config::getAppId(),
            'secret'     => SAF_Config::getAppSecret(),
            'fileUpload' => SAF_Config::getFileUpload()
        ));

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
        // determine our redirect url
        $this->_redirect_url = $this->_determineRedirectUrl();

        // set what perms the app requires
        $this->_extended_perms = SAF_Config::getExtendedPerms();

        // get user id
        $user_id = $this->getUser();
        if ($user_id) {
            $this->debug(__CLASS__.':: User ID ('.$user_id.').');
            // immediately exchange the short-lived access token for a long-lived one
            $this->setExtendedAccessToken();
        }

        // create a signed request object
        $this->sr = new SignedRequest($this);

        // NOTE: Page must be created before User since the User object
        // will reference the Page object for Tab and Canvas apps

        // get page id
        $page_id = $this->sr->getPageId() ? $this->sr->getPageId() : SAF_Config::getPageId();

        // if we have a page id, create a new page
        //if (!empty($page_id)) {
            $this->page = new Page($this, $page_id);
        //}

        // if we have a user id, create a new user
        //if (!empty($this->_user_id)) {
            $this->user = new User($this, $user_id);
        //}
    }

    // ------------------------------------------------------------------------
    // PUBLIC METHODS
    // ------------------------------------------------------------------------

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
     * Returns the login URL
     *
     * Override's the Facebook SDK's native method
     *
     * @access    public
     * @param     array  of parameters to pass
     * @return    string
     */
    public function getLoginUrl($params=null) {
        // use defaults in no params passed
        if (empty($params)) {
            $params = array(
                'scope'        => $this->_extended_perms,
                'redirect_uri' => $this->_redirect_url
            );
        }
        return parent::getLoginUrl($params);
    }

    /**
     * Returns the logout URL
     *
     * Override's the Facebook SDK's native method
     *
     * @access    public
     * @param     array  of parameters to pass
     * @return    string
     */
    public function getLogoutUrl($params=null) {
        // use defaults in no params passed
        if (empty($params)) {
            $params = array( 'next' => SAF_Config::getLogoutRoute() );
        }
        return parent::getLogoutUrl($params);
    }

    /**
     * Returns the login link (anchor tag)
     *
     * @access    public
     * @return    string
     */
    public function getLoginLink() {
        return FB_Helper::login_link($this->getLoginUrl());
    }

    /**
     * Returns the logout link (anchor tag)
     *
     * @access    public
     * @return    string
     */
    public function getLogoutLink() {
        return FB_Helper::logout_link($this->getLogoutUrl());
    }

    /**
     * Returns the permissions the app requested
     *
     * @access    public
     * @return    string  comma delimited string of perms
     */
    public function getExtendedPerms() {
        return $this->_extended_perms;
    }

    /**
     * Sets the extended perms to be used with getLoginURL();
     *
     * @access    public
     * @param     string  comma delimited perms
     * @return    void
     */
    public function setExtendedPerms($perms) {
        $this->_extended_perms = $perms;
    }

    /**
     * Returns the redirect URL to be used with getLoginUrl()
     *
     * @access    public
     * @return    string
     */
    public function getRedirectUrl() {
        return $this->_redirect_url;
    }

    /**
     * Sets the redirect URL to be used with getLoginUrl()
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setRedirectUrl($url) {
        $this->_redirect_url = $url;
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

        // setExtendedAccessToken() does not return a value on success,
        // but will return false on an error
        $result = parent::setExtendedAccessToken();
        if ($result === false) {
            $this->debug(__CLASS__.':: Error trying to extend the access token.');
            return;
        }

        $access_token = $this->getPersistentData('access_token');
        $this->setAccessToken($access_token);

        // store in our session (which the SDK will not overwrite)
        //$_SESSION[$this->constructSessionVariableName('access_token')] = $access_token;

        $this->debug(__CLASS__.':: Set extended access token.');
    }

    // ------------------------------------------------------------------------
    // PRIVATE METHODS
    // ------------------------------------------------------------------------

    /**
     * Returns the proper redirect URL for use with getLoginUrl()
     *
     * @access    private
     * @return    string
     */
    private function _determineRedirectUrl() {
        switch (SAF_Config::getAppType()) {

            // tab
            case SAF_Config::APP_TYPE_TAB:
                return SAF_Config::getTabUrl();
                break;

            // canvas app
            case SAF_Config::APP_TYPE_CANVAS:
                return SAF_Config::getCanvasUrl();
                break;

            // facebook connect
            default:
                return SAF_Config::getBaseUrl();

        }
    }

    // ------------------------------------------------------------------------
    // PROTECTED METHODS
    // ------------------------------------------------------------------------

    /**
     * Wrapper around an external class so we can do a simple check if the
     * class (XS_Debug) is avaliable before we attempt to use its method.
     *
     * @access    protected
     * @param     string  $name  name, label, message
     * @param     var     $var   a variable
     * @param     int     $type  (1)log, (2)info, (3)warn, (4)error
     * @param     bool    $log   log to text file
     * @return    void
     */
    protected function debug($name, $var=null, $type=1, $log=false) {
        if (class_exists('XS_Debug')) {
            XS_Debug::addMessage($name, $var, $type, $log);
        }
    }

    // ------------------------------------------------------------------------

}

/* End of file */
