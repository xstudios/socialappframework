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
// output benchmark info to console
SAF_Config::benchmark(false);

// ------------------------------------------------------------------------
// FACEBOOK CONFIG SETTINGS
// ------------------------------------------------------------------------
// app id & secret
SAF_Config::fbAppID('app_id');
SAF_Config::fbAppSecret('app_secret');
SAF_Config::fbAppDomain('domain.com');

// use cookie and enable file uploads
SAF_Config::fbUseCookie(true);
SAF_Config::fbFileUpload(false);

// app namespace (must match the one on your Facebook app's settings)
// only needed if you are developing a canvas app
SAF_Config::fbAppNamespace('app-namespace');

// admin id(s) - comma delimited
SAF_Config::fbAdmins('743687287');

// app developer id(s) - comma delimited
SAF_Config::fbDevelopers('743687287');

// ------------------------------------------------------------------------
// FACEBOOK GRAPH FIELDS
// ------------------------------------------------------------------------
// the fields we want returned from the graph API for users and pages (comma delimited)
// if you just want all the default fields set the parameter to an empty string
SAF_Config::graphUserFields('id, name, first_name, last_name, gender, username, email, link, picture, website');
SAF_Config::graphPageFields('id, name, category, is_published, likes, link, picture, website');

// ------------------------------------------------------------------------
// FACEBOOK PERMISSIONS
// ------------------------------------------------------------------------
// https://developers.facebook.com/docs/reference/api/permissions/

// extended permissions - comma delimited
SAF_Config::permsExtended('');

// required extended permissions for admin(s) - comma delimited
SAF_Config::permsExtendedAdmin('');

// auto request permissions upon tab/app load
SAF_Config::permsAutoRequestTab(false);
SAF_Config::permsAutoRequestApp(false);

// force admin to auth app
SAF_Config::permsAutoRequestAdmin(false);

// ------------------------------------------------------------------------
// FACEBOOK SIGNED REQUEST
// ------------------------------------------------------------------------
// there is no way to determine our fan page tab link without page info
// supplied from the signed request so we need to assign a fallback value
SAF_Config::srFanPageHash('SocialAppFramework');

// force the user to view tab and/or app in Facebook
SAF_Config::srRedirectTab(true);
SAF_Config::srRedirectApp(true);

// preferred redirect urls if the above redirects are true
SAF_Config::srRedirectTabURL( SAF_Config::urlPageTabFallback() );
SAF_Config::srRedirectAppURL( SAF_Config::urlPageTabFallback() );

// ------------------------------------------------------------------------
// 3RD PARTY COOKIE BLOCK WORK-AROUND
// ------------------------------------------------------------------------
// force the app to redirect to the base url (where the app is hosted) before
// redirecting back to the app tab (allows session to be started on the app domain)
// this is used as a work around for browsers which block 3rd party cookies
SAF_Config::forceRedirect(false);

// ------------------------------------------------------------------------

/* End of file config.php */
/* Location: ./socialappframework/config/config.php */
