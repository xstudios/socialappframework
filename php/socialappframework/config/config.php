<?php
 /**
 * SAF Config
 *
 * **DO NOT** DELETE ANY CONFIG ITEMS, BUT **DO** ENSURE THEY
 * ARE ALL SET CORRECTLY FOR YOUR PARTICULAR APP
 *
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 * @version      1.0
 * @copyright    2012 X Studios
 * @link         http://www.xstudiosinc.com
 */
// ------------------------------------------------------------------------
// SAF CONFIG
// ------------------------------------------------------------------------
// Set app type (tab, canvas or facebook connect)
SAF_Config::setAppType(SAF_Config::APP_TYPE_FACEBOOK_CONNECT);

// ------------------------------------------------------------------------
// FACEBOOK CONFIG SETTINGS
// ------------------------------------------------------------------------
// App ID and secret
SAF_Config::setAppId('APP_ID');
SAF_Config::setAppSecret('APP_SECRET');
SAF_Config::setFileUpload(false);

// App name
SAF_Config::setAppName('APP_NAME');

// App namespace (must match the one on your Facebook app's settings).
// Only needed if you are developing a canvas app
SAF_Config::setAppNamespace('APP_NAMESPACE');

// Admin id(s) - comma delimited
SAF_Config::setAdmins('743687287');

// App developer id(s) - comma delimited
SAF_Config::setDevelopers('743687287');

// ------------------------------------------------------------------------
// FACEBOOK GRAPH FIELDS
// ------------------------------------------------------------------------
// Fields we want returned from the graph API for users and pages (comma delimited).
// If you just want all the default fields set the parameter to an empty string
// User fields: https://developers.facebook.com/docs/reference/api/user/
// Page fields: https://developers.facebook.com/docs/reference/api/page/
SAF_Config::setGraphUserFields('id, name, first_name, last_name, gender, username, email, link, picture, website');
SAF_Config::setGraphPageFields('id, name, link, picture, website, is_published, likes');

// ------------------------------------------------------------------------
// FACEBOOK PERMISSIONS
// ------------------------------------------------------------------------
// https://developers.facebook.com/docs/reference/api/permissions/

// Extended permissions - comma delimited
SAF_Config::setExtendedPerms('');

// Required extended permissions for admin(s) - comma delimited
// Usually the extended permissions above, plus additional permissions (eg - manage_pages)
SAF_Config::setExtendedPermsAdmin('');

// ------------------------------------------------------------------------
// FACEBOOK SIGNED REQUEST
// ------------------------------------------------------------------------
// There is no way to determine our fan page tab link without page info
// supplied from the signed request so we need to assign a fallback value
SAF_Config::setFanPageHash('FAN_PAGE_HASH');

// Force the user to view tab and canvas app in Facebook
SAF_Config::setForceFacebookView(true);

// ------------------------------------------------------------------------
// 3RD PARTY COOKIE FIX
// ------------------------------------------------------------------------
// force the app to redirect to the base url (where the app is hosted) before
// redirecting back to the app (allows session to be started on the app domain)
// this is used as a work around for browsers which block 3rd party cookies
// only needed for tab and canvas apps
SAF_Config::setThirdPartyCookieFix(true);

// ------------------------------------------------------------------------
// LOGOUT ROUTE
// ------------------------------------------------------------------------
// Used as the 'next' parameter in the Facebook SDK's getLogoutUrl() method.
// Once logged out out of facebook (using a logout link in your app), the user would
// be redirected here. This is where you would clear your session, etc. to properly
// log them out of the application. This value gets appended to the base URL.
// Example: http://domain.com/logout
SAF_Config::setLogoutRoute('logout');

// ------------------------------------------------------------------------

/* End of file */
