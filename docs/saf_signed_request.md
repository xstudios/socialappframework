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
of leg work for you such as extending access tokens, obtaining data passed to the 
application, etc. **It is important to note thatthe Signed Request class is 
only used in Tab and Canvas applications.**

##Public Methods

###getUserID()
Get the user id of the logged in user. An alias for `getUser()`. Usage example:
    
    $user_id = $saf->getUserID();

***

###getPageID()
Get the page ID. Usage example:
    
    $page_id = $saf->getPageID();

>**NOTE: This method is for Tab apps only.**

***

###getAppData()
Get any data passed to the app via the signed request. Usage example:
    
    $app_data = $saf->getAppData();

>**NOTE: This method is for Tab apps only.**

***

###isPageAdmin() 
Allows you to determine if the current user is the page admin. Usage example:
    
    $saf->isPageAdmin();

>**NOTE: This method is for Tab apps only.**

***

###isPageLiked() 
Allows you to determine if the current user likes the fan page. Usage example:
    
    $saf->isPageLiked();

>**NOTE: This method is for Tab apps only.**

***

###isPageLikeViaFanGate() 
Allows you to determine if the current user liked the fan page via a fan-gate
method. Usage example:
    
    $saf->isPageLikeViaFanGate();

>**NOTE: This method is for Tab apps only.**

***
