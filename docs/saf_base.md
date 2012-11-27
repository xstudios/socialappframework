# Social App Framework Docs
### Version 1.0.0

## Table of Contents

* [SAF Config](saf_config.md)
* [SAF Class](saf.md)
* [SAF Base Class](saf_base.md)
* [SAF Signed Request Class](saf_signed_request.md)
* [SAF Fan Page Class](saf_fan_page.md)
* [SAF Facebook User Class](saf_facebook_user.md)

# SAF Base Class
The SAF Base class is a small class with a simple task: Initialize the 
Facebook SDK and start a session.

##Public Methods

###getFacebook()
Get the instance of the Facebook SDK. Usage example:
    
    $saf->getFacebook();

***
    
###getAppID()
Get the application ID. Usage example:

    $saf->getAppID();

***

###getAppSecret()
Get the application secret. Usage example:

    $saf->getAppSecret();

***

###getUserID()
Get the user ID of an authenticated user. Usage example:

    $saf->getUserID();

***

###getPageID()
Get the page ID of the current fan page the app resides in. Usage example:

     $saf->getPageID();

***
