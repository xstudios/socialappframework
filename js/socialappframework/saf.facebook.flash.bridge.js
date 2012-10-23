/**
 * SAF Facebook Flash Bridge
 *
 * The primary purpose of this script is to be a "bridge" between your app and
 * the Facebook JS SDK. It simply wraps common JS SDK methods we use in
 * our apps and gives us some nice code completion. It contains ZERO application
 * logic. The wrapper methods here simply allow you to pass data back to your
 * app via a callback.
 *
 * @author      Tim Santor <tsantor@xstudiosinc.com>
 * @version     1.0
 * @copyright   2012 X Studios
 * @link        http://www.xstudiosinc.com
 */
var SAF_Facebook = function(obj) {
    //"use strict";

    var self = this;

    var mDebugMode = obj.debug_mode || false;

    var mAppID = obj.app_id;
    var mBaseURL = obj.base_url;
    var mLoginURL = obj.login_url;

    var mPermissions = obj.permissions || '';
    var mUserFields = obj.user_fields || 'id, name, first_name, last_name, gender, username, email, link, picture, website';

    var mUserID;
    var mAccessToken;

    var mAuthenticated = false;

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------
    this.isAuthenticated = function() {
        debug('Facebook::isAuthenticated ('+mAuthenticated+')');
        return mAuthenticated;
    }

    var getFlash = function() {
        return document.getElementById('Main');
    };

    // ------------------------------------------------------------------------
    // INIT
    // ------------------------------------------------------------------------
    var __construct__ = function() {
        FB._https = (window.location.protocol == "https:");
        FB._secure = (window.location.protocol == "https:");

        FB.init({
            appId: mAppID,
            channelUrl: mBaseURL+'js/socialappframework/fb-channel.html',
            status: true,
            cookie: true,
            oauth: true,
            xfbml: true
        });

        FB.Canvas.setAutoGrow();

        // subscribe to events
        FB.Event.subscribe('auth.authResponseChange', authResponseChangeHandler);

        debug('Facebook:: __construct__', obj);
    };

    // ------------------------------------------------------------------------
    // UPDATE AUTH STATUS
    // ------------------------------------------------------------------------
    var updateAuthStatus = function(_response) {
        if (_response.status === 'connected') {
            // the user is logged in and has authenticated our app
            debug('Facebook::updateAuthStatus:: User is logged in and has authenticated our app.');
            mUserID = _response.authResponse.userID;
            mAccessToken = _response.authResponse.accessToken;
            mAuthenticated = true;
        } else if (_response.status === 'not_authorized') {
            // the user is logged in to Facebook,
            // but has not authenticated with our app
            debug('Facebook::updateAuthStatus:: User is logged in, but has not authenticated our app.');
            mAuthenticated = false;
        } else {
            // the user isn't logged in to Facebook
            debug('Facebook::updateAuthStatus:: User is not logged in to Facebook');
            mAuthenticated = false;
        }
    };

    // ------------------------------------------------------------------------
    // LOGIN / LOGOUT
    // ------------------------------------------------------------------------
    // manual login
    this.login = function() {
        debug('Facebook::login');
        var options = {
            scope: mPermissions
        };
        FB.login(loginHandler, options);
    };

    // fired when user logs in via prompt
    var loginHandler = function(_response) {
        debug('Facebook::loginHandler', _response);
        //updateAuthStatus(_response);
        getFlash().swfLoginStatusHandler(_response);
    };

    // manual logout
    this.logout = function() {
        debug('Facebook::logout');
        FB.logout(logoutHandler);
    };

    // fired when user logs out
    var logoutHandler = function(_response){
        debug('Facebook::logoutHandler', _response);
        //updateAuthStatus(_response);
    };

    // fired for any session auth related change
    // (eg - login, logout, session refresh)
    var authResponseChangeHandler = function(_response) {
        debug('Facebook::authResponseChangeHandler', _response);
        updateAuthStatus(_response);
    };

    // ------------------------------------------------------------------------
    // SUBSCRIBE TO LIKE/UNLIKE EVENTS
    // ------------------------------------------------------------------------
    this.subscribeToLike = function() {
        FB.Event.subscribe('edge.create', function(_response) {
            debug('Facebook::subscribeToLike', _response);
            getFlash().swfSubscribeToLikeHandler(_response);
        });
    };

    this.subscribeToUnlike = function() {
        FB.Event.subscribe('edge.remove', function(_response) {
            debug('Facebook::subscribeToUnlike', _response);
            getFlash().swfSubscribeToUnlikeHandler(_response);
        });
    };

    // ------------------------------------------------------------------------
    // GET USER DATA
    // ------------------------------------------------------------------------
    this.getUserData = function(){
        FB.api('/' + mUserID, {fields:mUserFields}, function(_response) {
            debug('Facebook::getUserData', _response);
            getFlash().swfGetUserDataHandler(_response);
        });
    };

    // ------------------------------------------------------------------------
    // GET PHOTO ALBUMS
    // ------------------------------------------------------------------------
    this.getPhotoAlbums = function() {
        FB.api('/' + mUserID + '/albums', function(_response) {
            debug('Facebook::getPhotoAlbums', _response);
            getFlash().swfGetPhotoAlbumsHandler(_response);
        });
    };

    // ------------------------------------------------------------------------
    // GET ALBUM PHOTOS
    // ------------------------------------------------------------------------
    this.getAlbumPhotos = function(id) {
        FB.api('/' + id + '/photos', {limit:200}, function(_response) {
            debug('Facebook::getAlbumPhotos', _response);
            getFlash().swfGetAlbumPhotosHandler(_response);
        });
    };

    // ------------------------------------------------------------------------
    // FEED
    // ------------------------------------------------------------------------
    this.feed = function(_publishObj) {
        debug('Facebook::feed', _publishObj);
        FB.api('/' + mUserID + '/feed', 'post', _publishObj, function(_response) {
            debug('Facebook::feed', _response);
            getFlash().swfPostFeedHandler(_response);
        });
    };

    // ------------------------------------------------------------------------
    // SHARE --no need for user to be logged in
    // ------------------------------------------------------------------------
    this.share = function(_publishObj) {
        debug('Facebook::share', _publishObj);
        FB.ui(_publishObj, function(_response) {
            debug('Facebook::share', _response);
            getFlash().swfShareHandler(_response);
        });
    };

    // ------------------------------------------------------------------------
    // FORCE LOGIN
    // ------------------------------------------------------------------------
    this.forceLogin = function() {
        top.location.href = mLoginURL;
    };

    // ------------------------------------------------------------------------
    // HELPER FUNCTIONS
    // ------------------------------------------------------------------------
    // helps set defaults for empty params
    var defaultValue = function(_value, _defaultValue) {
        return typeof(_value) !== 'undefined' ? _value : _defaultValue;
    };

    // will only output to the console if debug mode is on
    var debug = function(_string, _obj) {
        _obj = typeof(_obj) !== 'undefined' ? _obj : '';
        if ( (mDebugMode === 1 || mDebugMode === true) && window.console) {
            console.log(_string, _obj);
        }
    };

    // ------------------------------------------------------------------------
    __construct__();
};
