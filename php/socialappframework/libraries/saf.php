<?php
/**
 * Social App Framework class
 *
 * Instantiates the entire SAF Core
 * We don't really do anything here but load all required files
 * and give ourselves a nice way to instantiate with new SAF()
 *
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 * @version      1.0
 * @copyright    2012 X Studios
 * @link         http://www.xstudiosinc.com
 *
 * You should have received a copy of the license along with this program.
 * If not, see <http://socialappframework.com/license/>.
 */
define('SOCIAL_APP_FRAMEWORK', 'SAF');

// get directory name and then load all required files
$dirname = dirname(__FILE__);
$dirname .= (substr($dirname, -1) == '/' ? '' : '/');
$required_files = array(
    'facebook_sdk/facebook',
    'saf_config',
    'saf_session',
    'saf_base',
    'saf_signed_request',
    'saf_fan_page',
    'saf_facebook_user',
    '../config/config',
    '../helpers/fb_helper'
);
foreach ($required_files as $file) {
    require_once $dirname.$file.'.php';
}

class SAF extends SAF_Facebook_User {

    // ------------------------------------------------------------------------

    /**
     * CONSTRUCTOR
     *
     * @access    public
     * @return    void
     */
    public function __construct() {
        // this is used in conjuction with SAF_Config::setForceRedirect(true)
        // allows us a workaround for browsers which do not allow 3rd party
        // cookies (eg - cookies from iframe apps)
        if ( SAF_Config::getForceSessionRedirect() == true && isset($_GET['saf_redirect']) == true ) {
            header('Location: '.$_GET['saf_redirect']);
            exit;
        }
    }

    // ------------------------------------------------------------------------

    /**
     * INIT SAF
     *
     * Must be called to init the framework. Manual call to allow us to have
     * finer control over when the framework actually initializes after it's
     * been constructed.
     *
     * @access    public
     * @return    void
     */
    public function init() {
        parent::__construct();
    }

    // ------------------------------------------------------------------------

}

/* End of file */
