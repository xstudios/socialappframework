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
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
abstract class SAF_Base {

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    public
     * @return    void
     */
    public function __construct() {}

    // ------------------------------------------------------------------------
    // PROTECTED METHODS
    // ------------------------------------------------------------------------

    /**
     * Returns a SAF variable name in the form of "saf_APPID_key".
     *
     * @access    protected
     * @param     string  $key  the key name
     * @return    string
     */
    protected function createSafVariableName($key) {
        $parts = array('saf', SAF_Config::getAppId(), $key);
        return implode('_', $parts);
    }

    /**
     * Sets a SAF session variable
     *
     * @access    protected
     * @param     string  $key      the key name
     * @param     string  $default  the value
     * @return    void
     */
    protected function setSafPersistentData($key, $value) {
        $key = $this->createSafVariableName($key);
        return $_SESSION[$key] = $value;
    }

    /**
     * Returns a SAF session variable
     *
     * @access    protected
     * @param     string  $key      the key name
     * @param     string  $default  the default value to return
     * @return    mixed
     */
    protected function getSafPersistentData($key, $default=false) {
        $key = $this->createSafVariableName($key);
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    /**
     * Unsets a SAF session variable
     *
     * @access    protected
     * @param     string  $key  the key name
     * @return    void
     */
    protected function clearSafPersistentData($key) {
        $key = $this->createSafVariableName($key);
        unset($_SESSION[$key]);
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
