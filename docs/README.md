# Social App Framework Docs
### Version 1.0.0

**Everything you need to know about Social App Framework**. While the framework
does have individual classes, once instatiated and inited, all of its methods
are available to you.  Here's a simple up and running example (after setting 
your config file of course):
    
    require_once 'socialappframework/saf.php';

    $saf = SAF::instance();

    // call any user methods
    $name = $saf->user->getName();
    $friends = $saf->user->connection->getFriends();

    // call any page methods
    $page_name = $saf->page->getName();
    $photos = $saf->page->connection->getPhotos();

## Table of Contents

* [SAF Config](saf_config.md)
* [SAF Class](saf.md)
* [SAF Base Class](saf_base.md)
* [SAF Signed Request Class](saf_signed_request.md)
* [SAF Page Class](saf_page.md)
* [SAF Page Connection Class](saf_page_connection.md)
* [SAF User Class](saf_user.md)
* [SAF User Connection Class](saf_user_connection.md)
