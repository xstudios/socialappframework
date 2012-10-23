<?php if ( ! defined('SOCIAL_APP_FRAMEWORK') ) exit('No direct script access allowed');
/**
 * Social App Framework Facebook User class
 *
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 * @version      1.0
 * @copyright    2012 X Studios
 * @link         http://www.xstudiosinc.com
 *
 * You should have received a copy of the license along with this program.
 * If not, see <http://socialappframework.com/license/>.
 */
abstract class SAF_FacebookUser extends SAF_FanPage {

    private $_fb_user = null;

    private $_extended_perms = ''; // extended perms we are asking for
    private $_granted_perms = array(); // extended perms the user granted
    private $_revoked_perms = array(); // extended perms the user revoked

    private $_redirect_url;
    private $_login_url;
    private $_logout_url;

    private $_login_link;
    private $_logout_link;

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------
    public function getUserData() { return $this->_fb_user; }

    public function getUserName() { return $this->_getUserValue('name', ''); }
    public function getUserFirstName() { return $this->_getUserValue('first_name', ''); }
    public function getUserLastName() { return $this->_getUserValue('last_name', ''); }
    public function getUserGender() { return $this->_getUserValue('gender'); }

    public function getUserEmail() { return $this->_getUserValue('email'); }

    public function getUserProfileURL() { return $this->_getUserValue('link'); }
    public function getUserProfilePicture() {
        $picture = $this->_getUserValue('picture');
        if (!empty($picture)) $picture = $picture['data']['url'];
        return $picture;
    }

    public function getExtendedPerms() { return $this->_extended_perms; }
    public function getUserGrantedPerms() { return $this->_granted_perms; }
    public function getUserRevokedPerms() { return $this->_revoked_perms; }

    public function getRedirectURL() { return $this->_redirect_url; }
    public function getLoginURL() { return $this->_login_url; }
    public function getLogoutURL() { return $this->_logout_url; }

    public function getLoginLink() { return $this->_login_link; }
    public function getLogoutLink() { return $this->_logout_link; }

    public function setRedirectURL($value) {
        $this->_redirect_url = $value;
        // update login url/link too
        $this->_login_url = $this->_getLoginURL();
        $this->_login_link = SAF_FBHelper::login_link($this->_login_url);
    }

    // ------------------------------------------------------------------------

    /**
     * CONSTRUCTOR
     *
     * @access    public
     * @return    void
     */
    public function __construct() {
        parent::__construct();

        // begin benchmark
        $benchmark = $this->benchmark();
        if ($benchmark) $benchmark->begin();

        // determine our redirect url
        if ( SAF_Config::pageType() == SAF_Config::PAGE_TYPE_TAB) {
            $this->_redirect_url = SAF_Config::forceRedirect() == false ? $this->getPageTabURL() : SAF_Config::urlBase().'?saf_redirect='.urlencode($this->getPageTabURL());
        } else {
            $this->_redirect_url = SAF_Config::urlBase();
        }

        // is the user a regular user or page admin and what extended perms should we ask for?
        if ( $this->isPageAdmin() == false ) {
            $this->_extended_perms = SAF_Config::permsExtended();
        } else {
            $this->_extended_perms = SAF_Config::permsExtendedAdmin();
        }

        // login URL (always determine this in case we need more permissions later from the user)
        $this->_login_url = $this->_getLoginURL();
        $this->_login_link = SAF_FBHelper::login_link($this->_login_url);

        // if we have a user id
        if ( !empty($this->user_id) ) {
            // proceed knowing you have a logged in user who's authenticated

            // logout URL
            $params = array( 'next' => $this->_redirect_url );
            $this->_logout_url = $this->facebook->getLogoutUrl($params);
            $this->_logout_link = SAF_FBHelper::logout_link($this->_logout_url);

            try {

                // we have a user id, so probably a logged in user...if not, we'll get an exception, which we handle below
                $this->_fb_user = $this->facebook->api('/me', 'GET', array(
                    'access_token' => $this->getAccessToken(),
                    'fields' => SAF_Config::graphUserFields()
                ));

                // if we have user data
                if ( !empty($this->_fb_user) ) {

                    // fix user data
                    $this->_fb_user = $this->_fixUserData();

                    // check user permissions if we are asking for any
                    if ( !empty($this->_extended_perms) ) {
                        $this->_checkPermissions();
                    }

                    // if user is the admin and authenticated and has the manage_pages perm,
                    // then let's get the long-lived access token for the page
                    // only if we actually have a page (eg - a page id)
                    if ( $this->isPageAdmin() == true && $this->isAuthenticated() == true && $this->hasPermission('manage_pages') == true && !empty($this->page_id) ) {
                        $this->getPageAccessToken();
                    }

                    // add our own useful social app framework parameter(s) to the fb_user object
                    $this->_fb_user['saf_perms_granted'] = $this->_granted_perms;
                    $this->_fb_user['saf_perms_revoked'] = $this->_revoked_perms;

                    // add our social app framework user data into the session as well
                    SAF_Session::setPersistentData('user_obj', $this->_fb_user);

                    $this->debug(__CLASS__.':: User ('.$this->user_id.') is authenticated with data:', $this->_fb_user);

                } else {

                    $this->debug(__CLASS__.':: User ('.$this->user_id.') is authenticated, but user data is empty', null, 1, true);

                }

            } catch (FacebookApiException $e) {

                // if the user is logged out, you can have a user ID even though the access token is invalid
                // in this case, we'll get an exception, so we'll just prompt the user to login again here

                $this->debug(__CLASS__.':: User ('.$this->user_id.') is authenticated, but... '.$e, null, 1, true);

                // wipe the 'saf_user_obj' session object
                SAF_Session::clearPersistentData('user_obj');

                // something is up, so let's try to get public data as a fallback
                $this->_fb_user = $this->getPublicData($this->user_id);

                // if we have public data
                if ( !empty($this->_fb_user) ) {

                    // fix user data
                    $this->_fb_user = $this->_fixUserData();

                    // add our social app framework user data into the session
                    SAF_Session::setPersistentData('user_obj', $this->_fb_user);

                    $this->debug(__CLASS__.':: User ('.$this->user_id.') is authenticated, public data:', $this->_fb_user);

                } else {

                    $this->debug(__CLASS__.':: User ('.$this->user_id.') is authenticated, but public data is empty', null, 3, true);

                }

            }

        } else {

            // wipe the 'saf_user_obj' session object
            SAF_Session::clearPersistentData('user_obj');

            // proceed knowing we require user login and/or authentication
            $this->debug(__CLASS__.':: User is not authenticated. Prompt them to login...', null, 3);

            // force admin to login to the app if desired
            if ($this->isPageAdmin() == true && SAF_Config::permsAutoRequestAdmin() == true) {
                // get OAuth dialog and redirect back to our callback url (redirect_url)
                echo '<script>top.location.href = "'.$this->_login_url.'";</script>';
                exit;
            }

            // if normal user and we are auto-requesting perms then direct user to login url
            if ( (SAF_Config::pageType() == SAF_Config::PAGE_TYPE_TAB && SAF_Config::permsAutoRequestTab() == true) || (SAF_Config::pageType() == SAF_Config::PAGE_TYPE_APP && SAF_Config::permsAutoRequestApp() == true) ) {

                // get OAuth dialog and redirect back to our callback url (redirect_url)
                echo '<script>top.location.href = "'.$this->_login_url.'";</script>';
                exit;

            }

        }

        $this->debug('--------------------');

        // end benchmark
        if ($benchmark) $benchmark->end(__CLASS__);
    }

    // ------------------------------------------------------------------------

    /**
     * CHECK IF USER HAS PERMISSION
     * Determine if a user has allowed a specific permission
     *
     * @access    public
     * @param     string $perm permission to check
     * @return    bool
     */
    public function hasPermission($perm) {
        if ( in_array($perm, $this->_granted_perms) ) {
            return true;
        } else {
            return false;
        }
    }

    // ------------------------------------------------------------------------
    // PRIVATE METHODS
    // ------------------------------------------------------------------------

    /**
     * CHECK PERMISSIONS
     *
     * @access    private
     * @return    void
     */
    private function _checkPermissions() {
        // explode our comma seperated perms into an array
        $this->_extended_perms = preg_replace('/\s+/', '', $this->_extended_perms);
        $this->_extended_perms = explode(',', $this->_extended_perms);

        try {
            // check permissions list
            $permissions_list = $this->facebook->api('/me/permissions', 'GET', array(
                'access_token' => $this->getAccessToken()
            ));

            // loop through all user's permissions and see if they have everything we require
            foreach($this->_extended_perms as $perm) {
                if( !isset($permissions_list['data'][0][$perm]) || $permissions_list['data'][0][$perm] != 1 ) {
                    array_push($this->_revoked_perms, $perm);
                } else {
                    array_push($this->_granted_perms, $perm);
                }
            }

        } catch (FacebookApiException $e) {

            $this->debug(__CLASS__.':: User ('.$this->user_id.') is authenticated, but can\'t check permissions without a valid access token. '.$e, null, 1, true);

        }

    }

    // ------------------------------------------------------------------------

    /**
     * GET LOGIN URL
     *
     * @access    private
     * @return    string
     */
    private function _getLoginURL() {
        $url = $this->facebook->getLoginUrl(array(
            'scope' => $this->_extended_perms,
            'fbconnect' => 1,
            'display' => 'page', // popup or page
            'redirect_uri' => $this->_redirect_url
        ));

        return $url;
    }

    // ------------------------------------------------------------------------

    /**
     * FIX USER DATA
     *
     * Sometimes Facebook 'breaks' things so let's check
     * for things we can fix using data we already have
     *
     * @access private
     * @return associative array
     */
    private function _fixUserData() {
        if ( !isset($this->_fb_user['id']) ) {
            // set user id using the one from the signed request
            $this->_fb_user['id'] = $this->user_id;
        }

        if ( !isset($this->_fb_user['picture']) ) {
            $segment = isset($this->_fb_user['username']) ? $this->_fb_user['username'] : $this->user_id;
            $this->_fb_user['picture']['data']['url'] = 'https://graph.facebook.com/'.$segment.'/picture';
        }

        if ( !isset($this->_fb_user['link']) ) {
            $segment = isset($this->_fb_user['username']) ? $this->_fb_user['username'] : $this->user_id;
            $this->_fb_user['link'] = 'https://www.facebook.com/'.$segment;
        }

        return $this->_fb_user;
    }

    // ------------------------------------------------------------------------

    /**
     * GET USER VALUE
     *
     * Return a clean var value (eg - if something doesn't exist, return default value)
     *
     * @access private
     * @param mixed $key key to check for
     * @param mixed $default default value if not set
     * @return void
     */
    private function _getUserValue($key, $default=false) {
        if ( !isset($this->_fb_user[$key]) ) {
            return $default;
        } else {
            return $this->_fb_user[$key];
        }
    }

    // ------------------------------------------------------------------------

}

/* End of file SAF_FacebookUser.php */
/* Location: ./socialappframework/libraries/SAF_FacebookUser.php */
