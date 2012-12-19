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

# SAF Class
The SAF class is a small class with a simple task: Initialize the framework.
Usage Example:

    require_once 'socialappframework/saf.php';

    // set the framework app type for use with a tab app
    SAF_Config::setAppType(SAF_Config::APP_TYPE_TAB);

    // create and initialize the SAF instance
    $saf = new SAF();
    $saf->init();

    // to login and authenticate the user manually, then call
    $login_url = $this->getLoginUrl();

>**NOTE:** You would use the `$login_url` value as the href value of a link
the user clicks.

***
