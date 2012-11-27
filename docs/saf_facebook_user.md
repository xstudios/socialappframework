# Social App Framework Docs
### Version 1.0.0

## Table of Contents

* [SAF Config](saf_config.md)
* [SAF Class](saf.md)
* [SAF Base Class](saf_base.md)
* [SAF Signed Request Class](saf_signed_request.md)
* [SAF Fan Page Class](saf_fan_page.md)
* [SAF Facebook User Class](saf_facebook_user.md)

# Facebook User Class
The SAF Facebook User class authenticates users, checks extended permissions and
provides wasy access to user data.

##Public Methods

###getUserData()
Get the user's data. Usage example:
    
    $saf->getUserData();

***

###getUserFirstName()
Get the user's first name. Usage example:
    
    $saf->getUserFirstName();

***

###getUserLastName()
Get the user's last name. Usage example:
    
    $saf->getUserLastName();

***

###getUserName()
Get the user's full name. Usage example:
    
    $saf->getUserName();

***

###getUserGender()
Get the user's gender. Returns male, female or false (if not known). Usage example:
    
    $saf->getUserGender();

***

###getUserEmail()
Get the user's email address. Usage example:
    
    $saf->getUserEmail();

***

###getUserProfileURL()
Get the user's profile URL. Usage example:
    
    $saf->getUserProfileURL();

    // Produces: https://www.facebook.com/profile.php?id=743687287

***

###getUserProfilePicture()
Get the user's profile picture URL. Usage example:
    
    $saf->getUserProfilePicture();

    // Produces: https://graph.facebook.com/743687287/picture

***

###getExtendedPerms()
Get the extended permissions we are asking for. Returns a comma delimited 
string. Usage example:
    
    $saf->getExtendedPerms();

***

###getUserGrantedPerms()
Get the extended permissions the user has granted. Returns a comma delimited 
string. Usage example:
    
    $saf->getUserGrantedPerms();

***

###getUserRevokedPerms()
Get the extended permissions the user has revoked. Returns a comma delimited 
string. Usage example:
    
    $saf->getUserRevokedPerms();

***

###getRedirectURL()
Get the URL to which the user will be redirected to after authentication. 
Usage example:
    
    $saf->getRedirectURL();

***

###getLoginURL()
Get the login URL for app authentication. Usage example:
    
    $saf->getLoginURL();

***

###getLogoutURL()
Get the logout URL in order to log the user out of Facebook. Usage example:
    
    $saf->getLogoutURL();

***

###getLoginLink()
Get the login link for app authentication. Returns an anchor tag. Usage example:
    
    $saf->getLoginLink();

***

###getLogoutLink()
Get the logout link in order to log the user out of Faceboo. Returns an anchor 
tag. Usage example:
    
    $saf->getLogoutLink();

***

###isAppDeveloper()
Determines whether or not the user is the app developer. Usage example:
    
    $saf->isAppDeveloper();

**NOTE: This method relies on the developer ID(s) being properly set config file.**

***

###isAuthenticated()
Determines whether or not the user is authenticated (eg - user has allowed the app). 
Usage example:
    
    $saf->isAuthenticated();

***

###hasPermission()
Determines whether or not the user has granted a specific permission. 
Usage Example:
    
    $saf->hasPermission('publish_stream');

    // Produces: true or false

***

###setRedirectURL()
Allows you to set the redirect URL. Useful if you need to override the default. 
Usage example:
    
    $saf->setRedirectURL('http://domain.com/');

**NOTE: See Facebook documentation for rectrictions on Redirect URLs.**

***
