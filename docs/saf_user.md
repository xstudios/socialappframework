# Social App Framework Docs
### Version 1.0.0

## Table of Contents

* [SAF Config](saf_config.md)
* [SAF Class](saf.md)
* [SAF Facebook Class](saf_facebook.md)
* [SAF Signed Request Class](saf_signed_request.md)
* [SAF Page Class](saf_page.md)
* [SAF Page Connection Class](saf_page_connection.md)
* [SAF User Class](saf_user.md)
* [SAF User Connection Class](saf_user_connection.md)

# SAF User Class
The SAF User class provides easy access to User data and all a User's available
connectionsas well as checking permissions, providing login and logout URLs 
and provides a method to determine if the user is the app developer (useful for 
debugging). See API docs on a [User](https://developers.facebook.com/docs/reference/api/user/).

##Public Methods

###getId()
####Returns: _array_
Returns the user ID. Usage example:

    $saf->user->user->getId();

***

###getData()
####Returns: _array_
Returns the user data. Usage example:

    $saf->user->getData();

***

###getFirstName()
####Returns: _string_
Returns the user's first name. Usage example:

    $saf->user->getFirstName();

***

###getLastName()
####Returns: _string_
Returns the user's last name. Usage example:

    $saf->user->getLastName();

***

###getName()
####Returns: _string_
Returns the user's full name. Usage example:

    $saf->user->getName();

***

###getGender()
####Returns: _string_ (male, female or false if not known)
Returns the user's gender. Usage example:

    $saf->user->getGender();

***

###getEmail()
####Returns: _string_
Returns the user's email. Usage example:

    $saf->user->getEmail();

***

###getAgeRange()
####Returns: _object_ with `min` and `max` values
Returns the user's age range. Usage example:

    $saf->user->getAgeRange();

***

###getProfileUrl()
####Returns: _string_
Returns the user's profile URL. Usage example:

    $saf->user->getProfileUrl();

    // Produces: https://www.facebook.com/profile.php?id=743687287

***

###getProfilePicture()
####Returns: _string_
Returns the user's profile picture URL. Usage example:

    $saf->user->getProfilePicture();

    // Produces: https://graph.facebook.com/743687287/picture

***

###getGrantedPerms()
####Returns: _array_
Returns the permissions the user granted. Usage example:

    $saf->user->getGrantedPerms();

***

###getRevokedPerms()
####Returns: _array_
Returns the permissions the user revoked. Usage example:

    $saf->user->getRevokedPerms();

***

###isAppDeveloper()
####Returns: _bool_
Returns true if the user is the app developer. Usage example:

    $saf->user->isAppDeveloper();

>**NOTE: This method relies on the developer Id(s) being properly set in the 
config file.**

***

###isAuthenticated()
####Returns: _array_
Returns true if the user is authenticated. Usage example:

    $saf->user->isAuthenticated();

***

###hasPermission()
####Returns: _bool_
Returns true if a user has permission. Usage Example:

    $saf->user->hasPermission('publish_stream');

***

###setRedirectUrl()
####Param: _string_
Sets the redirect URL to be used with getLoginUrl(). Usage Example:

    $saf->user->setRedirectUrl('http://domain.com/');

***

