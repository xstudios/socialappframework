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

    $saf = new SAF();
    $saf->init();

    // to authenticate the user manually, then call
    // you would use this value in a link the user clicks
    $this->getLoginURL();

***
