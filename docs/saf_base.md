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

###setAppId($value)
####Parameter: _string_
Set the application ID. Usage example:

    $saf->setAppId('APP_ID');

***

###getAppId()
####Returns: _string_
Get the application ID. Usage example:

    $app_id = $saf->getAppId();

***

###setAppSecret($value)
####Parameter: _string_
Set the application secret. Usage example:

    $saf->setAppSecret('APP_SECRET');

***

###getAppSecret()
####Returns: _string_
Get the application secret. Usage example:

    $app_secret = $saf->getAppSecret();

***

###setFileUploadSupport($value)
####Parameter: _bool_
Set the file upload support status. Usage example:

    $saf->setFileUploadSupport(true);

***

###getFileUploadSupport()
####Returns: _bool_
Get the file upload support status. Usage example:

    $file_upload_support = $saf->getFileUploadSupport();

***

###setAccessToken($value)
####Parameter: _string_
Sets the access token for API calls. Usage example:

    $saf->setAccessToken('ACCESS_TOKEN');

***

###getAccessToken()
####Returns: _string_
Gets the access token for API calls. Usage example:

    $access_token = $saf->getAccessToken();

###getSignedRequest()
####Returns: _array_
Retrieve the signed request, either from a request parameter or, if not present,
from a cookie. Usage example:

    $signed_request = $saf->getSignedRequest();

***


###getUser()
####Returns: _string_
Get the user ID of the connected user, or 0 if the Facebook user is not
connected. Usage example:

    $user_id = $saf->getUser();

***

###getLoginStatusUrl($params)
####Parameter: _array_
####Returns: _string_
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
