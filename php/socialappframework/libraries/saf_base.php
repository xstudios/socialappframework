<?php
/*
 * This file is part of the Social App Framework package.
 * (c) 2011-2013 X Studios
 *
 * You should have received a copy of the license (license.txt) distributed
 * with this package. If not, see <http://socialappframework.com/license/>.
 */

/**
 * Social App Framework Base class
 *
 * We extend the Facebook class in order to make Social App Framework
 * a much more powerful and helpful library than the Facebook SDK is alone.
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
abstract class SAF_Base extends Facebook {

    const SAF_VERSION = '1.0.0';

    // ------------------------------------------------------------------------
    // PUBLIC VARS
    // ------------------------------------------------------------------------

    /**
     * SAF_Page object
     *
     * @access    public
     * @var       SAF_Page
     */
    public $page;

    /**
     * SAF_User object
     *
     * @access    public
     * @var       SAF_User
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

        // push additional allowed session keys into the Facebook SDK
        array_push(
            self::$kSupportedKeys,
            'saf_fan_gate'
        );

        // determine our redirect url
        $this->_redirect_url = $this->_determineRedirectUrl();

        // set what perms the app requires
        $this->_extended_perms = SAF_Config::getExtendedPerms();
    }

    // ------------------------------------------------------------------------
    // PUBLIC METHODS
    // ------------------------------------------------------------------------

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
     * Sets the redirect URL to be used with getLoginUrl()
     *
     * @access    public
     * @param     string  $value
     * @return    void
     */
    public function setRedirectUrl($url) {
        $this->_redirect_url = $url;
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
     * Returns a SAF variable name in the form of "saf_APPID_key".
     *
     * @access    public
     * @param     string  $key  the key name
     * @return    string
     */
    protected function createSafVariableName($key) {
        $parts = array('saf', SAF_Config::getAppId(), $key);
        return implode('_', $parts);
    }

    // ------------------------------------------------------------------------
    // WRAPPER METHODS
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
