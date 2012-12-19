# Social App Framework Docs
### Version 1.0.0

## Table of Contents

* [SAF Config](saf_config.md)
* [SAF Class](saf.md)
* [SAF Base Class](saf_base.md)
* [SAF Signed Request Class](saf_signed_request.md)
* [SAF Fan Page Class](saf_fan_page.md)
* [SAF Facebook User Class](saf_facebook_user.md)
* [SAF Session](saf_session.md)

# SAF Base Class
The SAF Base class is a small class with a simple task: Initialize the
Facebook SDK and start a session.

##Public Methods
The public methods are the **same** methods available to the Facebook PHP SDK.

###setAppId()
Set the application ID. Usage example:

    $saf->setAppId('APP_ID');

***

###getAppId()
Get the application ID. Usage example:

    $app_id = $saf->getAppId();

***

###setAppSecret()
Set the application secret. Usage example:

    $saf->setAppSecret('APP_SECRET');

***

###getAppSecret()
Get the application secret. Usage example:

    $app_secret = $saf->getAppSecret();

***

###setFileUploadSupport()
Set the file upload support status. Usage example:

    $saf->setFileUploadSupport(true);

***

###getFileUploadSupport()
Get the file upload support status. Usage example:

    $file_upload_support = $saf->getFileUploadSupport();

***

###setAccessToken()
Sets the access token for API calls. Usage example:

    $saf->setAccessToken('ACCESS_TOKEN');

***

###getAccessToken()
Gets the access token for API calls. Usage example:

    $access_token = $saf->getAccessToken();

***

###setExtendedAccessToken()
Extend an access token. This method is poorly handled by the Facebook SDK.  This
method **should not** be called manually as Social App Framework handles extending
access tokens for you automatically when possible. This method is documented solely
for completeness. Usage example:

    $saf->setExtendedAccessToken();

###getSignedRequest()
Retrieve the signed request, either from a request parameter or, if not present,
from a cookie. Usage example:

    $signed_request = $saf->getSignedRequest();

***


###getUser()
Get the user ID of the connected user, or 0 if the Facebook user is not
connected. Usage example:

    $user_id = $saf->getUser();

***

###getLoginUrl()
Get a Login URL suitable for use with redirects. The parameters:

- **redirect_uri**: the url to go to after a successful login
- **scope**: comma separated list of requested extended perms

Usage example:

    $params = array(
        'redirect_uri' => 'https:://domain.com/logged-in',
        'scope' => 'email, publish_stream'
    );
    $login_url = $saf->getLoginUrl($params);

>**NOTE**: This method is overridden in the `SAF_Facebook_User` class.

***

###getLogoutUrl()
Get a Logout URL suitable for use with redirects. The parameters:

- **next**: the url to go to after a successful logout

Usage example:

    $params = array(
        'next' => 'https:://domain.com/logout'
    );
    $logout_url = $saf->getLogoutUrl($params);

>**NOTE**: This method is overridden in the `SAF_Facebook_User` class.

***

###getLoginStatusUrl()
Get a login status URL to fetch the status from Facebook.  The parameters:

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
