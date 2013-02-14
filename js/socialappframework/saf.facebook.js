/**
 * SAF Facebook Helper
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

    var mAppID    = obj.app_id;
    var mPageID   = obj.page_id;

    var mBaseURL  = obj.base_url;
    var mAppURL   = obj.app_url;

    var mPermissions = obj.permissions || '';

    var mUserFields  = obj.user_fields || '';
    var mPageFields  = obj.page_fields || '';

    var mUserID;
    var mAccessToken;

    var mAuthenticated = false;

    // ------------------------------------------------------------------------
    // GETTERS / SETTERS
    // ------------------------------------------------------------------------
    this.setDebugMode = function(_value) {
        mDebugMode = _value;
    };

    this.setPageID = function(_value) {
        mPageID = _value;
    };

    this.setGraphPageFields = function(_value) {
        mPageFields = _value;
    };

    this.setGraphUserFields = function(_value) {
        mUserFields = _value;
    };

    this.setPermissions = function(_value) {
        mPermissions = _value;
    };

    this.isAuthenticated = function() {
        debug('Facebook::isAuthenticated ('+mAuthenticated+')');
        return mAuthenticated;
    };

    this.getPageId = function() {
        return mPageID;
    }

    this.getUserId = function() {
        return mUserID;
    }

    // ------------------------------------------------------------------------
    // INIT
    // ------------------------------------------------------------------------
    var __construct__ = function() {
        FB._https = (window.location.protocol == "https:");
        FB._secure = (window.location.protocol == "https:");

        FB.init({
            appId: mAppID,
            channelUrl: 'https://fbrell.com/channel',
            status: true,
            cookie: true,
            oauth: true,
            xfbml: true,
            frictionlessRequests: true
        });

        FB.Canvas.setAutoGrow();

        // subscribe to events
        FB.Event.subscribe('auth.authResponseChange', authResponseChangeHandler);

        //debug('Facebook:: __construct__', obj);
    };

    // ------------------------------------------------------------------------
    // UPDATE AUTH STATUS
    // ------------------------------------------------------------------------
    var updateAuthStatus = function(_response) {
        if (_response.status === 'connected') {
            // the user is logged in and has authenticated our app
            debug('Facebook::updateAuthStatus:: User is logged in and has '+
                  'authenticated our app.');
            mUserID = _response.authResponse.userID;
            mAccessToken = _response.authResponse.accessToken;
            mAuthenticated = true;
        } else if (_response.status === 'not_authorized') {
            // the user is logged in to Facebook,
            // but has not authenticated with our app
            debug('Facebook::updateAuthStatus:: User is logged in, but has not '+
                  'authenticated our app.');
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
    this.login = function(_callbackFunc) {
        var options = {
            scope: mPermissions
        };
        FB.login(function(_response) {
            debug('Facebook::login', _response);
            if ( isValidCallback(_callbackFunc) ) {
                _callbackFunc(_response);
            } else {
                top.location.href = mAppURL;
            }
        }, options);
    };

    // manual logout
    this.logout = function(_callbackFunc) {
        FB.logout(function(_response) {
            debug('Facebook::logout', _response);
            if ( isValidCallback(_callbackFunc) ) {
                _callbackFunc(_response);
            } else {
                top.location.href = mAppURL;
            }
        });
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
    this.subscribeToLike = function(_callbackFunc) {
        FB.Event.subscribe('edge.create', function(_response) {
            debug('Facebook::subscribeToLike', _response);
            doCallback(_callbackFunc, _response);
        });
    };

    this.subscribeToUnlike = function(_callbackFunc) {
        FB.Event.subscribe('edge.remove', function(_response) {
            debug('Facebook::subscribeToUnlike', _response);
            doCallback(_callbackFunc, _response);
        });
    };

    // ------------------------------------------------------------------------
    // GET PAGE DATA
    // ------------------------------------------------------------------------
    this.getPageData = function(_pageID, _callbackFunc) {
        // set defaults
        _pageID = defaultValue(_pageID, mPageID);

        FB.api('/' + _pageID, {fields:mPageFields}, function(_response) {
            debug('Facebook::getPageData', _response);
            doCallback(_callbackFunc, _response);
        });
    };

    // ------------------------------------------------------------------------
    // GET USER DATA
    // ------------------------------------------------------------------------
    this.getUserData = function(_userID, _callbackFunc){
        // set defaults
        _userID = defaultValue(_userID, mUserID);

        FB.api('/' + _userID, {fields:mUserFields}, function(_response) {
            debug('Facebook::getUserData', _response);
            doCallback(_callbackFunc, _response);
        });
    };

    // ------------------------------------------------------------------------
    // GET PHOTO ALBUMS
    // ------------------------------------------------------------------------
    this.getPhotoAlbums = function(_callbackFunc) {
        FB.api('/' + mUserID + '/albums', function(_response) {
            debug('Facebook::getPhotoAlbums', _response);
            doCallback(_callbackFunc, _response);
        });
    };

    // ------------------------------------------------------------------------
    // GET ALBUM PHOTOS
    // ------------------------------------------------------------------------
    this.getAlbumPhotos = function(_albumID, _callbackFunc) {
        FB.api('/' + _albumID + '/photos', {limit:200}, function(_response) {
            debug('Facebook::getAlbumPhotos', _response);
            doCallback(_callbackFunc, _response);
        });
    };

    // ------------------------------------------------------------------------
    // FEED
    // ------------------------------------------------------------------------
    this.feed = function(_publishObj, _callbackFunc) {
        FB.api('/' + mUserID + '/feed', 'post', _publishObj, function(_response) {
            debug('Facebook::feed', _response);
            doCallback(_callbackFunc, _response);
        });
    };

    // ------------------------------------------------------------------------
    // SHARE -- no need for user to be logged in, uses Facebook UI dialog
    // ------------------------------------------------------------------------
    this.share = function(_publishObj, _callbackFunc) {
        FB.ui(_publishObj, function(_response) {
            debug('Facebook::share', _response);
            doCallback(_callbackFunc, _response);
        });
    };

    // ------------------------------------------------------------------------
    // SEND -- lets people to send content to specific friends via messages
    // ------------------------------------------------------------------------
    this.send = function(_publishObj, _callbackFunc) {
        // ensure method is correct
        _publishObj.method = 'send';

        FB.ui(_publishObj, function(_response) {
            debug('Facebook::send', _response)
            doCallback(_callbackFunc, _response);
        });
    };

    // ------------------------------------------------------------------------
    // SEND REQUEST
    // ------------------------------------------------------------------------
    this.sendRequest = function(_userID, _message, _callbackFunc) {
        if (!_userID) {
            debug('Facebook::sendRequest > You must supply a user ID.');
            return;
        }

        // set defaults
        _message = defaultValue(_message, 'Message goes here.');

        var publishObj = {
            method: 'apprequests',
            message: _message,
            to: _userID
        };

        // callback will pass response back as a param
        // response.request for successful post
        FB.ui(publishObj, function(_response) {
            debug('Facebook::sendRequest', _response);
            doCallback(_callbackFunc, _response);
        });
    };

    this.sendRequestViaMultiFriendSelector = function(_message, _callbackFunc) {
        // set defaults
        _message = defaultValue(_message, 'Message goes here.');

        var publishObj = {
            method: 'apprequests',
            message: _message
        };

        // callback will pass response back as a param
        // response.request for successful post
        FB.ui(publishObj, function(_response) {
            debug('Facebook::sendRequestViaMultiFriendSelector', _response);
            doCallback(_callbackFunc, _response);
        });
    };

    // ------------------------------------------------------------------------
    // DELETE POST
    // ------------------------------------------------------------------------
    this.deletePost = function(_postID, _callbackFunc) {
        FB.api(_postID, 'delete', function(_response) {
            debug('Facebook::deletePost', _response);
            doCallback(_callbackFunc, _response);
        });
    };

    // ------------------------------------------------------------------------
    // ADD PAGE TAB
    // ------------------------------------------------------------------------
    this.addPageTab = function() {
        var obj = {
            app_id: mAppID,
            method: 'pagetab',
            redirect_uri: mAppURL,
        };
        // no callback needed
        FB.ui(obj);
    };

    // ------------------------------------------------------------------------
    // SCROLL TO TOP -- Tab and Canvas apps only
    // ------------------------------------------------------------------------
    this.scrollToTop = function() {
        FB.Canvas.scrollTo(0,0);
    };

    // ------------------------------------------------------------------------
    // HELPER FUNCTIONS
    // ------------------------------------------------------------------------
    // do we have a valid callback?
    var isValidCallback = function(_callbackFunc) {
        return typeof(_callbackFunc) === 'function';
    };

    // invoke callback
    var doCallback = function(_callbackFunc, _response) {
        if (isValidCallback(_callbackFunc)) {
            _callbackFunc(_response);
        } else {
            debug('Facebook::doCallback > You must define a valid callback '+
                  'function in order to handle the response.');
        }
    };

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
