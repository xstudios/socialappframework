<?php if ( ! defined('SOCIAL_APP_FRAMEWORK') ) exit('No direct script access allowed');
/**
 * Social App Framework Base class
 *
 * We could extend Facebook, (eg - SAF_Base extends Facebook), but we
 * only want to see SAF methods for code completion, not Facebook SDK methods
 * Doing it this way means we can access the instance of Faceboook when needed
 * and it stays on its own, we simply 'wrap' it
 *
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 * @version      1.0
 * @copyright    2012 X Studios
 * @link         http://www.xstudiosinc.com
 *
 * You should have received a copy of the license along with this program.
 * If not, see <http://socialappframework.com/license/>.
 */
abstract class SAF_Base {

    const VERSION = '1.0.0';

    protected $_facebook; // instance of Facebook SDK

    protected $_user_id = null; // facebook user id
    protected $page_id = null; // facebook page id

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------
    public function getFacebook() { return $this->_facebook; }
    public function getAppID() { return $this->_facebook->getAppId(); }
    public function getAppSecret() { return $this->_facebook->getAppSecret(); }

    public function getUserID() { return $this->_user_id; }
    public function getPageID() { return $this->_page_id; }

    // ------------------------------------------------------------------------

    /**
     * CONSTRUCTOR
     *
     * @access    public
     * @return    void
     */
    public function __construct() {
        // create application instance
        $this->_facebook = new Facebook(array(
            'appId'  => SAF_Config::getAppID(),
            'secret' => SAF_Config::getAppSecret(),
            'cookie' => SAF_Config::getUseCookie(),
            'domain' => SAF_Config::getAppDomain(),
            'fileUpload' => SAF_Config::getFileUpload()
        ));

        // init saf session
        SAF_Session::init(SAF_Config::getAppID());
    }

    // ------------------------------------------------------------------------

    /**
     * GET PUBLIC DATA
     *
     * @access    protected
     * @param     string $object_id Facebook graph object id
     * @return    array
     */
    protected function getPublicData($object_id) {
        $url = 'https://graph.facebook.com/'.$object_id;

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, "I-am-browser");
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $response = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($response, true);
            return $response;
        } catch (Exception $e) {
            $this->debug(__METHOD__.':: '.$e, null, 3, true);
            return false;
        }
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

    /**
     * BENCHMARK
     *
     * Wrapper around an external class so we can do a simple check if the
     * class (XS_Benchmark) is even loaded before we attempt to use its methods
     *
     * @access    protected
     * @return    XS_Benchmark instance
     */
    protected function benchmark() {
        if (class_exists('XS_Benchmark')) {
            if (SAF_Config::benchmark() == true) {
                return new XS_Benchmark();
            }
        }
    }

    // ------------------------------------------------------------------------

}

/* End of file */
