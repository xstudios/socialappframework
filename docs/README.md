# Social App Framework Docs
### Version 1.0.0

**Everything you need to know about Social App Framework**. While the framework
does have individual classes, once instatiated and inited, all of its methods
are available to you.  Here's a simple up and running example (after setting 
your config file of course):
    
    require_once 'socialappframework/saf.php';

    $saf = SAF::instance();

    // authenticate the user
    $login_url = $saf->getLoginUrl();

    // once the user is authenticated...

    // call any user methods
    $name = $saf->user->getName();
    $friends = $saf->user->connection->getFriends();

    // call any page methods
    $page_name = $saf->page->getName();
    $photos = $saf->page->connection->getPhotos();

## Table of Contents

* [SAF Config](docs/saf_config.md)
* [SAF Class](docs/saf.md)
* [SAF Facebook Class](docs/saf_facebook.md)
* [SAF Signed Request Class](docs/saf_signed_request.md)
* [SAF Page Class](docs/saf_page.md)
* [SAF Page Connection Class](docs/saf_page_connection.md)
* [SAF User Class](docs/saf_user.md)
* [SAF User Connection Class](docs/saf_user_connection.md)
