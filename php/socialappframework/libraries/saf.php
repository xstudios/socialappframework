<?php
/*
 * This file is part of the Social App Framework package.
 * (c) 2011-2013 X Studios
 *
 * You should have received a copy of the license (license.txt) distributed
 * with this package. If not, see <http://socialappframework.com/license/>.
 */

require_once dirname(__FILE__).'/facebook/sdk/facebook.php';
require_once dirname(__FILE__).'/saf_config.php';
require_once dirname(__FILE__).'/saf_base.php';
require_once dirname(__FILE__).'/saf_signed_request.php';
require_once dirname(__FILE__).'/saf_page.php';
require_once dirname(__FILE__).'/saf_user.php';
require_once dirname(__FILE__).'/../config/config.php';
require_once dirname(__FILE__).'/../helpers/fb_helper.php';

/**
 * SAF class
 * We don't really do anything here but load all required files
 * and give ourselves a nice way to instantiate with new SAF:instance()
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
class SAF extends SAF_Signed_Request {

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

}

/* End of file */
