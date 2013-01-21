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

# SAF Page Class
The SAF Page class provides easy access to Page data and all a Page's available
connections as well as to the Page Access Token (only if the User is the Page
admin). See API docs on a [Page](https://developers.facebook.com/docs/reference/api/page/).
**It is important to note that the Page class is only available in Tab 
applications, unless you explicitly call `SAF_Config::setPageId('PAGE_ID')` 
before calling `SAF::instance()`.**

##Public Methods

###getId()
####Returns: _string_
Returns the page ID. Usage example:

    $saf->page->getId();

***

###getData()
####Returns: _array_
Returns the page data. Usage example:

    $saf->page->getData();

***

###getAccessToken()
####Returns: _string_
Returns the page's acces token. Usage example:

    $saf->page->getAccessToken();

>**NOTE: This method is only available to a page admin with the `manage_pages`
permission.**

***

###getName()
####Returns: _string_
Returns the page's name. Usage example:

    $saf->page->getName();

***

###getProfileUrl()
####Returns: _string_
Returns the page's profile URL. Usage example:

    $saf->page->getProfileUrl();

    // Produces: https://www.facebook.com/SocialAppFramework

***

###getProfilePicture()
####Returns: _string_
Returns the page's profile picture. Usage example:

    $saf->page->getProfilePicture();

    // Produces: https://graph.facebook.com/236913163089998/picture

***

###getLikes()
####Returns: _string_
Returns the page's like count. Usage example:

    $saf->page->getLikes();

***

###getWebsite()
####Returns: _string_
Returns the page's website. Usage example:

    $saf->page->getWebsite();

***

###getTabUrl()
####Returns: _string_
Returns the tab URL. Usage example:

    $saf->page->getTabUrl();

    // Produces: https://www.facebook.com/SocialAppFramework/app_156875707703556

***

###getAddTabUrl()
####Returns: _string_
Returns the URL needed to add the app to a page. Usage example:

    $saf->page->getAddTabUrl();

    // Produces: https://www.facebook.com/dialog/pagetab?app_id=156875707703556

***

###getCanvasUrl()
####Returns: _string_
Returns the app's Canvas URL (if it has one). Usage example:

    $saf->page->getCanvasUrl();

    // Produces: https://apps.facebook.com/social-app-framework/

***

###isPublished()
####Returns: _bool_
Returns true if the page is published. Usage example:

    $saf->page->isPublished();

***

###isLiked()
####Returns: _bool_
Returns true if the user likes this page. Usage example:

    $saf->page->isLiked();

***

###hasRestrictions()
####Returns: _bool_
Returns true if the page has restrictions. Usage example:

    $saf->page->hasRestrictions();

>**NOTE: This method is still in development. Use at your own risk.**

***

###getRssUrl()
####Returns: _string_
Returns the page's RSS URL. Usage example:

    $saf->page->getRssUrl();

***
