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

# SAF Page Connection Class
The SAF Page Connection class provides easy access to all of a page's available
connections. See API docs on a [Page](https://developers.facebook.com/docs/reference/api/page/).

##Public Methods

###getAdmins()
####Returns: _array_
Returns a list of the page's admins. Usage example:

    $saf->page->connection->getAdmins();

***

###getAlbums()
####Returns: _array_
Returns the photo albums the page has uploaded. Usage example:

    $saf->page->connection->getAlbums();

***

###getBlocked()
####Returns: _array_
Returns a list of users blocked from the page. Usage example:

    $saf->page->connection->getBlocked();

***

###getCheckins()
####Returns: _array_
Returns the checkins made to this place page by the current user, and friends 
of the current user. Usage example:

    $saf->page->connection->getCheckins();

>**Requires permissions:** `user_checkins` or `friends_checkins`

***

###getConversations()
####Returns: _array_
Returns a list of the page's conversations. Usage example:

    $saf->page->connection->getConversations();

>**Requires permissions:** `read_mailbox`

***

###getEvents()
####Returns: _array_
Returns the events the page is attending. Usage example:

    $saf->page->connection->getEvents();

***

###getFeed()
####Returns: _array_
Get the page's wall. Usage example:

    $saf->page->connection->getFeed();

***

###getGlobalBrandChildren()
####Returns: _array_
Returns information of all children pages. Usage example:

    $saf->page->connection->getGlobalBrandChildren();

***

###getGroups()
####Returns: _array_
Returns the groups to which the page belongs. Usage example:

    $saf->page->connection->getGroups();

***

###getInsights()
####Returns: _array_
Get the page's Insights data. Usage example:

    $saf->page->connection->getInsights();

>**Requires permissions:** `read_insights`

***

###getLinks()
####Returns: _array_
Returns the the page's posted links. Usage example:

    $saf->page->connection->getLinks();

***

###getMilestones()
####Returns: _array_
Returns a list of the page's milestones. Usage example:

    $saf->page->connection->getMilestones();

***

###getNotes()
####Returns: _array_
Returns the page's notes. Usage example:

    $saf->page->connection->getNotes();

***

###getPhotos()
####Returns: _array_
Returns the page's uploaded photos. Usage example:

    $saf->page->connection->getPhotos();

***

###getPicture($type)
####Param: _string_ (square [default], small, normal, large)
####Returns: _string_
Return the page's profile picture. Usage example:

    $saf->page->connection->getPicture('normal');

***

###getPosts()
####Returns: _array_
Returns the page's own posts. Usage example:

    $saf->page->connection->getPosts();

***

###getPromotablePosts()
####Returns: _array_
Returns the page's own posts, including unpublished and scheduled posts. 
Usage example:

    $saf->page->connection->getPromotablePosts();

***

###getQuestions()
####Returns: _array_
Returns the page's questions. Usage example:

    $saf->page->connection->getQuestions();

***

###getSettings()
####Returns: _array_
Returns the page's settings. Usage example:

    $saf->page->connection->getSettings();

***

###getStatuses()
####Returns: _array_
Returns the page's status updates. Usage example:

    $saf->page->connection->getStatuses();

***

###getTabs()
####Returns: _array_
Returns the page's tabs. Usage example:

    $saf->page->connection->getTabs();

***

###getTagged()
####Returns: _array_
Returns the photos, videos, and posts in which the Page has been tagged. 
Usage example:

    $saf->page->connection->getTagged();

***

###getVideos()
####Returns: _array_
Returns the videos the page has uploaded. Usage example:

    $saf->page->connection->getVideos();

***
