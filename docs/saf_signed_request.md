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

# SAF Signed Request Class
The SAF Signed Request class gathers info from the signed request and does a lot
of leg work for you such as getting an access token via any means available, 
extending access tokens, obtaining data passed to the application, etc. 

##Public Methods

###getUserId()
####Returns: _string_
Returns the user id of the logged in user. An alias for `getUser()`. Usage example:

    $user_id = $saf->getUserId();

***

###getPageId()
####Returns: _string_
Returns the page ID. Usage example:

    $page_id = $saf->getPageId();

>**NOTE: This method is for Tab apps only.**

***

###getAppAccessToken()
####Returns: _string_
Returns the app access token. Usage example:

    $page_id = $saf->getAppAccessToken();

***

###getAppData()
####Returns: _string_
Returns any data passed to the app via the signed request. Usage example:

    $app_data = $saf->getAppData();

>**NOTE: This method is for Tab apps only.**

***

###isPageAdmin()
####Returns: _bool_
Returns true if the current user is the page admin. Usage example:

    $saf->isPageAdmin();

>**NOTE: This method is for Tab apps only.**

***

###isPageLiked()
####Returns: _bool_
Allows you to determine if the current user likes the fan page. Usage example:

    $saf->isPageLiked();

>**NOTE: This method is for Tab apps only.**

***

###isPageLikeViaFanGate()
####Returns: _bool_
Returns true if the current user liked the fan page via a fan-gate method. 
Usage example:

    $saf->isPageLikeViaFanGate();

>**NOTE: This method is for Tab apps only.**

***

###setExtendedAccessToken()
Extend an access token. This method is poorly handled by the Facebook SDK.  This
method **should not** be called manually as Social App Framework handles extending
access tokens for you automatically when possible. This method is documented solely
for completeness. Usage example:

    $saf->setExtendedAccessToken();

***
