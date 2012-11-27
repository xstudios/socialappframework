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
// set app type (tab, canvas or facebook connect)
SAF_Config::setAppType(SAF_Config::APP_TYPE_FACEBOOK_CONNECT);

// ------------------------------------------------------------------------
// FACEBOOK CONFIG SETTINGS
// ------------------------------------------------------------------------
// app id & secret
SAF_Config::setAppID('APP_ID');
SAF_Config::setAppSecret('APP_SECRET');
SAF_Config::setAppDomain('DOMAIN.COM');

// use cookie and enable file uploads
SAF_Config::setUseCookie(true);
SAF_Config::setFileUpload(false);

// app namespace (must match the one on your Facebook app's settings)
// only needed if you are developing a canvas app
SAF_Config::setAppNamespace('APP_NAMESPACE');

// admin id(s) - comma delimited
SAF_Config::setAdmins('743687287');

// app developer id(s) - comma delimited
SAF_Config::setDevelopers('743687287');

// ------------------------------------------------------------------------
// FACEBOOK GRAPH FIELDS
// ------------------------------------------------------------------------
// the fields we want returned from the graph API for users and pages (comma delimited)
// if you just want all the default fields set the parameter to an empty string
SAF_Config::setGraphUserFields('id, name, first_name, last_name, gender, username, email, link, picture, website');
SAF_Config::setGraphPageFields('id, name, category, is_published, likes, link, picture, website');

// ------------------------------------------------------------------------
// FACEBOOK PERMISSIONS
// ------------------------------------------------------------------------
// https://developers.facebook.com/docs/reference/api/permissions/

// extended permissions - comma delimited
SAF_Config::setExtendedPerms('');

// required extended permissions for admin(s) - comma delimited
SAF_Config::setExtendedPermsAdmin('');

// auto request permissions upon tab and/or canvas app load
SAF_Config::setAutoRequestPermsTab(false);
SAF_Config::setAutoRequestPermsCanvas(false);

// force admin to auth app
SAF_Config::setAutoRequestPermsAdmin(false);

// ------------------------------------------------------------------------
// FACEBOOK SIGNED REQUEST
// ------------------------------------------------------------------------
// there is no way to determine our fan page tab link without page info
// supplied from the signed request so we need to assign a fallback value
SAF_Config::setFanPageHash('FAN_PAGE_HASH');

// force the user to view tab and canvas app in Facebook
SAF_Config::setForceFacebookView(true);

// ------------------------------------------------------------------------
// SESSION REDIRECT - 3RD PARTY COOKIE BLOCK WORK-AROUND
// ------------------------------------------------------------------------
// force the app to redirect to the base url (where the app is hosted) before
// redirecting back to the app tab (allows session to be started on the app domain)
// this is used as a work around for browsers which block 3rd party cookies
SAF_Config::setForceSessionRedirect(false);

// ------------------------------------------------------------------------

/* End of file */
