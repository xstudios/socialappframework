# Social App Framework Docs
### Version 1.0.0

**Everything you need to know about Social App Framework**. While the framework
does have individual classes, once instatiated and inited, all of it's methods
are available to you.  Here's a simple up and running example (after setting 
your config file of course):
    
    require_once 'socialappframework/saf.php';

    $saf = new SAF();
    $saf->init();

    // call a method
    $saf->getUserFirstName(); 

## Table of Contents

* [SAF Config](docs/saf_config.md)
* [SAF Class](saf.md)
* [SAF Base Class](saf_base.md)
* [SAF Signed Request Class](saf_signed_request.md)
* [SAF Fan Page Class](saf_fan_page.md)
* [SAF Facebook User Class](saf_facebook_user.md)
