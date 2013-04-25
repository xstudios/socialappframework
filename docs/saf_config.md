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

# SAF Config Class
The SAF class is a small class with a simple task: Configure the framework. All
of its methods are static. This class exists purely to make configuration 
extremely easy and allow for code completion in IDEs.

## Public Methods

###setAppType($value)
####Parameter: _string_
Set the app type. Possible values are `tab`, `canvas`, or `facebook connect`. It
is highly recommended to use the constants supplied in SAF_Config. This should
be set __before__ calling `SAF::instance()`. Usage example:

    // set the framework app type for use with a tab app
    SAF_Config::setAppType(SAF_Config::APP_TYPE_TAB);

***

###getAppType()
####Returns: _string_
Returns the app type. Usage example:

    SAF_Config::getAppType();

***

###setAppId($value)
####Parameter: _string_
Set the Facebook app ID. Usage example:

    SAF_Config::setAppId('YOUR_APP_ID');

***

###getAppId()
####Returns: _string_
Returns the Facebook app ID. Usage example:

    SAF_Config::getAppId();

***

###setAppSecret($value)
####Parameter: _string_
Set the Facebook app secret. Usage example:

    SAF_Config::setAppSecret('YOUR_APP_SECRET');

***

###getAppSecret()
####Returns: _string_
Returns the Facebook app secret. Usage example:

    SAF_Config::getAppSecret();

***


###setFileUpload($value)
####Parameter: _bool_
Set the Facebook SDK to allow file uploads. Usage example:

    SAF_Config::setFileUpload(true);

***

###getFileUpload()
####Returns: _bool_
Returns the Facebook SDK allow file upload value. Usage example:

    SAF_Config::getFileUpload();

***

###setAppName($value)
####Parameter: _string_
Set the Facebook app name. Usage example:

    SAF_Config::setAppName('APP_NAME');

***

###getAppName()
####Returns: _string_
Returns the Facebook app name. Usage example:

    SAF_Config::getAppName();

***

###setAppNamespace($value)
####Parameter: _string_
Set the Facebook app namespace. **Canvas apps only**. Usage example:

    SAF_Config::setAppNamespace('APP_NAMESPACE');

***

###getAppNamespace()
####Returns: _string_
Returns the Facebook app namespace. Usage example:

    SAF_Config::getAppNamespace();

***

###setAdmins($value)
####Parameter: _string_
Set the Facebook fan page admins. The value should be a comma delimited string.
**Tab apps only**. Usage example:

    SAF_Config::setAdmins('USER_ID_1, USER_ID_2, USER_ID_3');

***

###getAdmins()
####Returns: _string_
Returns the Facebook fan page admins. Usage example:

    SAF_Config::getAdmins();

***

###setDevelopers($value)
####Parameter: _string_
Set the app developers. The value should be a comma delimited string.
Usage example:

    SAF_Config::setDevelopers('USER_ID_1, USER_ID_2, USER_ID_3');

***

###getDevelopers()
####Returns: _string_
Returns the app developers. Usage example:

    SAF_Config::getDevelopers();

***

###setBaseUrl($value)
####Parameter: _string_
Set the base URL. Usage example:

    SAF_Config::setBaseUrl('BASE_URL');

***

###getBaseUrl()
####Returns: _string_
Returns the base URL. Usage example:

    SAF_Config::getBaseUrl();

***

###getTabUrl()
####Returns: _string_
Returns the Tab app URL. Usage example:

    SAF_Config::getTabUrl();

***

###getCanvasUrl()
####Returns: _string_
Returns the Canvas app URL. Usage example:

    SAF_Config::getCanvasUrl();

***

###getAddTabUrl()
####Returns: _string_
Returns the add page tab URL. Usage example:

    SAF_Config::getAddTabUrl();

***

###setExtendedPerms($value)
####Parameter: _string_
Set the extended permissions to ask of the user. The value should be a comma
delimited string. Usage example:

    SAF_Config::setExtendedPerms('publish_stream, email');

***

###getExtendedPerms()
####Returns: _string_
Returns the extended permissions to ask of the user. Usage example:

    SAF_Config::getExtendedPerms();

***

###setExtendedPermsAdmin($value)
####Parameter: _string_
Set the extended permissions to ask of the admin. This is usually all the permissions
asked of a normal user, plus some others that are required of the admin. The value
should be a comma delimited string. Usage example:

    SAF_Config::setExtendedPermsAdmin('publish_stream, email, manage_pages');

***

###getExtendedPermsAdmin()
####Returns: _string_
Returns the extended permissions to ask of the admin. Usage example:

    SAF_Config::getExtendedPermsAdmin();

***

###setFanPageHash($value)
####Parameter: _string_
Set the fan page URL hash. This is used as a fallback value when the framework
is unable to determine the fan page URL (when the user has not yet authed the app).
Usage example:

    SAF_Config::setFanPageHash('SocialAppFramework');
    //Produces: https://www.facebook.com/SocialAppFramework

***

###getFanPageHash()
####Returns: _string_
Returns the fan page hash. Usage example:

    SAF_Config::getFanPageHash();

***

###setForceFacebookView($value)
####Parameter: _bool_
Set the force Facebook view value. This will force the app to ensure the user is
viewing the Tab or Canvas app within Facebook. Usage example:

    SAF_Config::setForceFacebookView(true);

***

###getForceFacebookView()
####Returns: _bool_
Returns the force Facebook view value. Usage example:

    SAF_Config::getForceFacebookView();

***

###setThirdPartyCookieFix($value)
####Parameter: _bool_
Set the 3rd party cookie fix value. This will force the app to redirect the user
to the app's base URL, inititate the session, and then immediately redirect them
to the app's URL within Facebook. This is a work-around for browsers which like to
block 3rd party cookies (eg - any app within an iframe). Usage example:

    SAF_Config::setThirdPartyCookieFix(true);

***

###getThirdPartyCookieFix()
####Returns: _bool_
Returns the 3rd party cookie fix value. Usage example:

    SAF_Config::getThirdPartyCookieFix();

***

###setGraphUserFields($value)
####Parameter: _string_
Set the fields we want to retrieve from the Graph API for a user. The value
should be a comma delimited string. Usage example:

    SAF_Config::setGraphUserFields('id, first_name, last_name, gender, email, link, picture');

***

###getGraphUserFields()
####Returns: _string_
Returns the fields we want to retrive from the Graph API for a user. Usage example:

    SAF_Config::getGraphUserFields();

***

###setGraphPageFields($value)
####Parameter: _string_
Set the fields we want to retrieve from the Graph API for a page. The value
should be a comma delimited string. Usage example:

    SAF_Config::setGraphPageFields('id, name, likes, link, picture');

***

###getGraphPageFields()
####Returns: _string_
Returns the fields we want to retrive from the Graph API for a page. Usage example:

    SAF_Config::getGraphPageFields();

***

###setLogoutRoute($value)
####Parameter: _string_
Set the logout route to be used when the user logs out of Facebook (using a
logout button/link in the app). This logout URL would be obtained by calling the
`getLogoutUrl()` or `getLogoutLink()` methods. Usage example:

    SAF_Config::setLogoutRoute('logout');

***

###getLogoutRoute()
####Returns: _string_
Returns the logout route. Usage example:

    SAF_Config::getLogoutRoute();
    // Produces: http://domain.com/logout

Typically, your logout endpoint would do something like this:

    $saf->destroySession();
    // we use Javascript's top.location.href and not PHP's header location
    // because it will work with any type of app: Tab, Canvas or Facebook Connect
    echo '<script>top.location.href = "https://facebook.com/";</script>';


***

###setPageId($value)
####Parameter: _string_
Set the Page ID. Usage example:

    SAF_Config::setPageId('PAGE_ID');

>**NOTE:** Only use this if you need page data on a Canvas, Facebook Connect app
or AJAX request where Page data is not available (by default).

***

###getPageId()
####Returns: _string_
Returns the Page ID. Usage example:

    SAF_Config::getPageId();

***
