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
 * a much more powerful and helpful SDK than the Facebook SDK is alone.
 * The 'magic' happens here and all your Facebook development woes will be
 * a thing of the past.
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
abstract class SAF_Base extends Facebook {

    const SAF_VERSION = '1.0.0';

    // ------------------------------------------------------------------------

    /**
     * Get app access token
     *
     * @access    public
     * @return    string
     */
    public function getAppAccessToken() {
        // url
        //$url   = '/oauth/access_token';
        $query = array(
            'grant_type'    => 'client_credentials',
            'client_id'     => SAF_Config::getAppId(),
            'client_secret' => SAF_Config::getAppSecret()
        );
        //$url  .= '?'.http_build_query($query);

        $result = $this->api('/oauth/access_token', 'get', $query);

        // parse query string result
        parse_str($result);

        return $access_token;
    }

    // ------------------------------------------------------------------------

    /**
     * CONSTRUCTOR
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
            'saf_fan_gate',
            'saf_extended_access_token',
            'saf_user',
            'saf_page'
        );
    }

    // ------------------------------------------------------------------------
    // WRAPPER METHODS
    // ------------------------------------------------------------------------

    /**
     * DEBUG
     *
     * Wrapper around an external class so we can do a simple check if the
     * class (XS_Debug) is even loaded before we attempt to use its method
     *
     * @access    protected
     * @param     string $name name, label, message
     * @param     var $var a variable/array/object
     * @param     int $type (1)log, (2)info, (3)warn, (4)error
     * @param     bool $log log to text file
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
