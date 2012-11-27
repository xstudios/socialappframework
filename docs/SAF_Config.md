# Social App Framework Docs
### Version 1.0.0

# SAF Config Class
The SAF class is a small class with a simple task: Configure the framework. All
of its methods are static. This class exists purely to make configuration very 
easy and allow for code completion in IDEs.

## Public Methods

###setAppType()
Set the app type. Possible values are `tab`, `canvas`, or `facebook connect`. It
is highly recommended to use the constants supplied in SAF_Config. This should
be set __before__ calling `$saf->init()`. Usage example:
    
    // set the framework app type for use with a tab app
    SAF_Config::setAppType(SAF_Config::APP_TYPE_TAB);

***

###getAppType()
Get the app type. Usage example:
    
    SAF_Config::getAppType();

***

###setAppID()
Set the Facebook app ID. Usage example:
    
    SAF_Config::setAppID('YOUR_APP_ID');

***

###getAppID()
Get the Facebook app ID. Usage example:
    
    SAF_Config::getAppID();

***

###setAppSecret()
Set the Facebook app secret. Usage example:
    
    SAF_Config::setAppSecret('YOUR_APP_SECRET');

***

###getAppSecret()
Get the Facebook app secret. Usage example:
    
    SAF_Config::getAppSecret();

***

###setAppDomain()
Set the Facebook app domain. Usage example:
    
    SAF_Config::setAppDomain('YOUR_DOMAIN.COM');

***

###getAppDomain()
Get the Facebook app domain. Usage example:
    
    SAF_Config::getAppDomain();

***

###setUseCookie()
Set the Facebook SDK to use cookies. Usage example:
    
    SAF_Config::setUseCookie(true);

***

###getUseCookie()
Get the Facebook SDK use cookie value. Usage example:
    
    SAF_Config::getUseCookie();

***

###setFileUpload()
Set the Facebook SDK to allow file uploads. Usage example:
    
    SAF_Config::setFileUpload(true);

***

###getFileUpload()
Get the Facebook SDK allow file upload value. Usage example:
    
    SAF_Config::getFileUpload();

***

###setAppNamespace()
Set the Facebook app namespace. **Canvas apps only**. Usage example:
    
    SAF_Config::setAppNamespace('APP_NAMESPACE');

***

###getAppNamespace()
Get the Facebook app namespace. Usage example:
    
    SAF_Config::getAppNamespace();

***

###setAdmins()
Set the Facebook fan page admins. The value should be a comma delimited string. 
**Tab apps only**. Usage example:
    
    SAF_Config::setAdmins('USER_ID_1, USER_ID_2, USER_ID_3');

***

###getAdmins()
Get the Facebook fan page admins. Usage example:
    
    SAF_Config::getAdmins();

***

###setDevelopers()
Set the app developers. The value should be a comma delimited string. 
Usage example:
    
    SAF_Config::setDevelopers('USER_ID_1, USER_ID_2, USER_ID_3');

***

###getDevelopers()
Get the app developers. Usage example:
    
    SAF_Config::getDevelopers();

***

###setBaseURL()
Set the base URL. Usage example:
    
    SAF_Config::setBaseURL('BASE_URL');

***

###getBaseURL()
Get the base URL. Usage example:
    
    SAF_Config::getBaseURL();

***

###getPageTabURL()
Get the page Tab app URL. Usage example:
    
    SAF_Config::getPageTabURL();

***

###getCanvasURL()
Get the Canvas app URL. Usage example:
    
    SAF_Config::getCanvasURL();

***

###getAddPageTabURL()
Get the add page tab URL. Usage example:
    
    SAF_Config::getAddPageTabURL();

***

###setExtendedPerms()
Set the extended permissions to ask of the user. The value should be a comma 
delimited string. Usage example:
    
    SAF_Config::setExtendedPerms('publish_stream, email');

***

###getExtendedPerms()
Get the extended permissions to ask of the user. Usage example:
    
    SAF_Config::getExtendedPerms();

***

###setExtendedPermsAdmin()
Set the extended permissions to ask of the admin. This is usually all the permissions
asked of a normal user, plus some others that are required of the admin. The value 
should be a comma delimited string. Usage example:
    
    SAF_Config::setExtendedPermsAdmin('publish_stream, email, manage_pages');

***

###getExtendedPermsAdmin()
Get the extended permissions to ask of the admin. Usage example:
    
    SAF_Config::getExtendedPermsAdmin();

***

###setAutoRequestPermsTab()
Force the auth dialog upon loading the Tab app. Usage example:
    
    SAF_Config::setAutoRequestPermsTab(true);

***

###getAutoRequestPermsTab()
Get the force auth dialog value for the Tab app. Usage example:
    
    SAF_Config::getAutoRequestPermsTab();

***

###setAutoRequestPermsCanvas()
Force the auth dialog upon loading the Canvas app. Usage example:
    
    SAF_Config::setAutoRequestPermsCanvas(true);

***

###getAutoRequestPermsCanvas()
Get the force auth dialog value for the Canvas app. Usage example:
    
    SAF_Config::getAutoRequestPermsCanvas();

***

###setAutoRequestPermsAdmin()
Force the auth dialog upon loading the app for the admin only. Usage example:
    
    SAF_Config::setAutoRequestPermsAdmin(true);

***

###getAutoRequestPermsAdmin()
Get the force auth dialog value of the app for the admin only. Usage example:
    
    SAF_Config::getAutoRequestPermsAdmin();

***

###setFanPageHash()
Set the fan page URL hash. This is used as a fallback value when the framework
is unable to determine the fan page URL (when the user has not yet authed the app).
Usage example:
    
    SAF_Config::setFanPageHash('SocialAppFramework');
    //Produces: https://www.facebook.com/SocialAppFramework

***

###getFanPageHash()
Get the fan page hash. Usage example:
    
    SAF_Config::getFanPageHash();

***

###setForceFacebookView()
Set the force Facebook view value. This will force the app to ensure the user is
viewing the Tab or Canvas app within Facebook. Usage example:
    
    SAF_Config::setForceFacebookView(true);

***

###getForceFacebookView()
Get the force Facebook view value. Usage example:
    
    SAF_Config::getForceFacebookView();

***

###setForceSessionRedirect()
Set the force session redirect value. This will force the app to redirect the user
to the app's base URL, inititate the session, and then immediately redirect them
to the app's URL within Facebook. This is a work-around for browsers which like to
block 3rd party cookies (eg - any app within an iframe). Usage example:
    
    SAF_Config::setForceSessionRedirect(true);

***

###getForceSessionRedirect()
Get the force session redirect value. Usage example:
    
    SAF_Config::getForceSessionRedirect();

***

###setGraphUserFields()
Set the fields we want to retrieve from the Graph API for a user. The value 
should be a comma delimited string. Usage example:
    
    SAF_Config::setGraphUserFields('id, name, first_name, last_name, gender, email, link, picture, website');

***

###getGraphUserFields()
Get the fields we want to retrive from the Graph API for a user. Usage example:
    
    SAF_Config::getGraphUserFields();

***

###setGraphPageFields()
Set the fields we want to retrieve from the Graph API for a page. The value 
should be a comma delimited string. Usage example:
    
    SAF_Config::setGraphPageFields('id, name, category, is_published, likes, link, picture, website');

***

###getGraphPageFields()
Get the fields we want to retrive from the Graph API for a page. Usage example:
    
    SAF_Config::getGraphPageFields();

***

