# Social App Framework Docs
### Version 1.0.0

## Table of Contents

* [SAF Class](#saf-class)
* [Base Class](#base-class)
* [Signed Request Class](#signed-request-class)
* [Fan Page Class](#fan-page-class)
* [Facebook User Class](#facebook-user-class)

<a id="saf-class"></a>
# SAF Class
The SAF class is a small class with a simple task: Initialize the framework.
Usage Example:

    require_once 'socialappframework/saf.php';

    $saf = new SAF();
    $saf->init();

***

<a id="base-class"></a>
# Base Class
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

<a id="signed-request-class"></a>
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

<a id="fan-page-class"></a>
# Fan Page Class
The SAF Fan Page class provides easy access to fan page data. **It is important 
to note that the Fan Page class is only used in Tab applications.**

##Public Methods

###getPageData()
Get the fan page data. Usage example:
    
    $saf->getPageData();

***

###getPageAccessToken()
Get the page access token for use with Facebook Graph API requests. This will allow
the app to post as the page when the user is not logged in. Usage example:
    
    $saf->getPageAccessToken();

**NOTE: This method is only available to a page admin.**

***

###getPageName()
Get the fan page name. Usage example:
    
    $saf->getPageName();

***

###getPageProfileURL()
Get the fan page profile URL. Usage example:
    
    $saf->getPageProfileURL();

    // Produces: https://www.facebook.com/SocialAppFramework

***

###getPageProfilePicture()
Get the fan page profile picture URL. Usage example:
    
    $saf->getPageProfilePicture();

    // Produces: https://graph.facebook.com/236913163089998/picture

***

###getPageLikes()
Get the total number of likes for the fan page. Usage example:
    
    $saf->getPageLikes();

***

###getPageTabURL()
Get the page tab URL. Usage example:
    
    $saf->getPageTabURL();

    // Produces: https://www.facebook.com/SocialAppFramework/app_156875707703556

***

###getAddPageTabURL()
Get the add page tab URL. Usage example:
    
    $saf->getAddPageTabURL();

    // Produces: https://www.facebook.com/dialog/pagetab?app_id=156875707703556

***

###getCanvasAppURL()
Get the canvas app URL. Usage example:
    
    $saf->getCanvasAppURL();

    // Produces: https://apps.facebook.com/social-app-framework/

***

###isPagePublished()
Determine whether the fan page is published or not. Usage example:
    
    $saf->isPagePublished();

***

###hasPageAddedApp()
Determine whether the page has added the app or not. Usage example:
    
    $saf->hasPageAddedApp();

###hasPageRestrictions()
Determine whether the page has restrictions or not. Usage example:
    
    $saf->hasPageRestrictions();

**NOTE: This method is still in development. Do not use.**

***

###setPageID()
Allows you to set the page id, after which you would call `getPageData()` to get 
the page data. Usage example:
    
    $saf->setPageID('236913163089998');
    $data = $saf->getPageData();

**NOTE: This method is useful with a Canvas or Facebook Connect app.**

***

<a id="facebook-user-class"></a>
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
