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

# SAF Class
The SAF class is a small class with a simple task: Initialize the framework.
Usage Example:

    require_once 'socialappframework/saf.php';

    // set the framework app type for use with a tab app
    SAF_Config::setAppType(SAF_Config::APP_TYPE_TAB);

    // create and initialize the SAF instance
    $saf = SAF::instance();

    // login and authenticate the user (this URL would be the href of your link)
    $login_url = $saf->getLoginUrl();

>**NOTE:** You would use the `$login_url` value as the href value of a link
the user clicks.

***
