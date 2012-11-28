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

# Signed Request Class
The SAF Signed Request class gathers info from the signed request and does a lot
of prep work for you such as extending access tokens, fan-gate management, 
obtaining data passed to the application , etc. **It is important to note that
the Signed Request class is only used in Tab and Canvas applications.**

##Public Methods

###getSignedRequestData()
Get the signed request. Usage example:
    
    $saf->getSignedRequestData();

***

###getAccessToken()
Get the access token for use with Facebook Graph API requests. Usage example:
    
    $saf->getAccessToken();

***

###getAppData()
Get any data passed to the app via the signed request. Usage example:
    
    $saf->getAppData();

**NOTE: This method is for Tab apps only.**

***

###isPageAdmin() 
Allows you to determine if the current user is the page admin. Usage example:
    
    $saf->isPageAdmin();

**NOTE: This method is for Tab apps only.**

***

###isPageLiked() 
Allows you to determine if the current user likes the fan page. Usage example:
    
    $saf->isPageLiked();

**NOTE: This method is for Tab apps only.**

***

###isPageLikeViaFanGate() 
Allows you to determine if the current user liked the fan page via a fan-gate
method. Usage example:
    
    $saf->isPageLikeViaFanGate();

**NOTE: This method is for Tab apps only.**

***
