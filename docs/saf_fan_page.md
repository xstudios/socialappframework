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

# Fan Page Class
The SAF Fan Page class provides easy access to fan page data. **It is important
to note that the Fan Page class is only used in Tab applications, unless
you explicitly call `setPageID()` before calling `init()`.**

##Public Methods

###getPageData()
Get the fan page data. Usage example:

    $page_data = $saf->getPageData();

***

###getPageAccessToken()
Get the page access token for use with Facebook Graph API requests. This will allow
the app to post as the page when the user is not logged in. Usage example:

    $page_access_token = $saf->getPageAccessToken();

>**NOTE: This method is only available to a page admin with the `manage_pages` 
permission.**

***

###getPageName()
Get the fan page name. Usage example:

    $page_name = $saf->getPageName();

***

###getPageProfileURL()
Get the fan page profile URL. Usage example:

    $profile_url = $saf->getPageProfileURL();

    // Produces: https://www.facebook.com/SocialAppFramework

***

###getPageProfilePicture()
Get the fan page profile picture URL. Usage example:

    $picture = $saf->getPageProfilePicture();

    // Produces: https://graph.facebook.com/236913163089998/picture

***

###getPageLikes()
Get the total number of likes for the fan page. Usage example:

    $likes = $saf->getPageLikes();

***

###getPageWebsite()
Get the page's website. Usage example:

    $website_url = $saf->getPageWebsite();

***

###getPageTabURL()
Get the page tab URL. Usage example:

    $tab_url = $saf->getPageTabURL();

    // Produces: https://www.facebook.com/SocialAppFramework/app_156875707703556

***

###getAddPageTabURL()
Get the add page tab URL. Usage example:

    $add_tab_url = $saf->getAddPageTabURL();

    // Produces: https://www.facebook.com/dialog/pagetab?app_id=156875707703556

***

###getCanvasURL()
Get the canvas app URL. Usage example:

    $canvas_url = $saf->getCanvasURL();

    // Produces: https://apps.facebook.com/social-app-framework/

***

###isPageLiked()
Determine whether the current user likes the fan page or not. Usage example:

    $saf->isPageLiked();

***

###isPagePublished()
Determine whether the fan page is published or not. Usage example:

    $saf->isPagePublished();

***

###hasPageRestrictions()
Determine whether the page has restrictions or not. Usage example:

    $saf->hasPageRestrictions();

>**NOTE: This method is still in development. Do not use.**

***

###setPageID()
Allows you to set the page id manually, after which you would call `init()`. 
Usage example:

    $saf->setPageID('236913163089998');
    $saf->init();

>**NOTE: This method is useful with a Canvas or Facebook Connect app.**

***
