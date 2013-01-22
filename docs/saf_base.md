# Social App Framework Docs
### Version 1.0.0

## Table of Contents

* [SAF Config](saf_config.md)
* [SAF Class](saf.md)
* [SAF Base Class](saf_base.md)
* [SAF Signed Request Class](saf_signed_request.md)
* [SAF Page Class](saf_page.md)
* [SAF Page Connection Class](saf_page_connection.md)
* [SAF User Class](saf_user.md)
* [SAF User Connection Class](saf_user_connection.md)

# SAF Base Class
The SAF Base class is a small class with a simple task: Initialize the
Facebook SDK and start a session.

##Public Methods
The public methods are the **same** methods available to the Facebook PHP SDK.

###setAppId($value)
####Parameter: _string_
Set the application ID. Usage example:

    $saf->setAppId('APP_ID');

***

###getAppId()
####Returns: _string_
Returns the app ID that the SDK is currently using. Usage example:

    $saf->getAppId();

***

###setAppSecret($value)
####Parameter: _string_
Set the app secret that the SDK is currently using. Usage example:

    $saf->setAppSecret('APP_SECRET');

***

###getAppSecret()
####Returns: _string_
Returns the app secret that the SDK is currently using. Usage example:

    $saf->getAppSecret();

***

###setAccessToken($value)
####Parameter: _string_
Sets the current access token being used by the SDK. Usage example:

    $saf->setAccessToken('ACCESS_TOKEN');

***

###getAccessToken()
####Returns: _string_
Returns the current access token being used by the SDK. Usage example:

    $saf->getAccessToken();

***

###setFileUploadSupport($value)
####Parameter: _bool_
Set the file upload support status. Usage example:

    $saf->setFileUploadSupport(true);

***

###getFileUploadSupport()
####Returns: _bool_
Returns true if file upload support has been enabled in the SDK. Usage example:

    $saf->getFileUploadSupport();

***

###getAppAccessToken()
####Returns: _string_
Returns the app access token. Usage example:

    $page_id = $saf->getAppAccessToken();

***

###getSignedRequest()
####Returns: _array_
Retrieve the signed request, either from a request parameter or, if not present,
from a cookie. Usage example:

    $saf->getSignedRequest();

***

###getUser()
####Returns: _string_
Returns the user ID of the connected user, or 0 if the Facebook user is not
connected. Usage example:

    $saf->getUser();

***

###getExtendedPerms()
####Returns: _string_
Returns the permissions the app requested. Usage example:

    $saf->user->getExtendedPerms();

***

###getLoginUrl()
####Returns: _string_
Returns the login URL. Usage example:

    $saf->user->getLoginUrl();

>**NOTE:** This method already takes into account the proper redirect URL and
desired scope.

***

###getLogoutUrl()
####Returns: _string_
Returns the logout URL. Usage example:

    $saf->user->getLogoutUrl();

>**NOTE:** This method already takes into account the proper redirect URL.

***

###getLoginLink()
####Returns: _string_
Returns the login link (anchor tag). Usage example:

    $saf->user->getLoginLink();

***

###getLogoutLink()
####Returns: _string_
 Returns the logout link (anchor tag). Usage example:

    $saf->user->getLogoutLink();

***

###getLoginStatusUrl($params)
####Parameter: _array_
####Returns: _string_
Returns a login status URL to fetch the status from Facebook.  The parameters:

- **ok_session**: the URL to go to if a session is found
- **no_session**: the URL to go to if the user is not connected
- **no_user**: the URL to go to if the user is not signed into facebook

Usage example:

    $params = array(
        'ok_session' => 'https://domain.com/ok-session',
        'no_session' => 'https://domain.com/no-session',
        'no_user'    => 'https://domain.com/no-user'
    );
    $login_status_url = $saf->getLoginStatusUrl($params);

***

###api()
Make an API call. Usage example:

    $friends = $saf->api('/me/friends', 'GET', array(
        'access_token' => $saf->getAccessToken()
    ));

***

###destroySession()
Destroy the current session. Only the Facebook and SAF session are destroyed.
Usage example:

    $saf->destroySession();

***
