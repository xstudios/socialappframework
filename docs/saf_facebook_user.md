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

# Facebook User Class
The SAF Facebook User class authenticates users, checks extended permissions and
provides easy access to user data.

##Public Methods

###getUserData()
####Returns: _array_
Get the user's data. Usage example:

    $user_data = $saf->getUserData();

***

###getUserFirstName()
####Returns: _string_
Get the user's first name. Usage example:

    $first_name = $saf->getUserFirstName();

***

###getUserLastName()
####Returns: _string_
Get the user's last name. Usage example:

    $last_name = $saf->getUserLastName();

***

###getUserName()
####Returns: _string_
Get the user's full name. Usage example:

    $username = $saf->getUserName();

***

###getUserGender()
####Returns: _string_
Get the user's gender. Returns male, female or false (if not known). Usage example:

    $gender = $saf->getUserGender();

***

###getUserEmail()
####Returns: _string_
Get the user's email address. Usage example:

    $email = $saf->getUserEmail();

***

###getUserProfileUrl()
####Returns: _string_
Get the user's profile URL. Usage example:

    $profile_url = $saf->getUserProfileUrl();

    // Produces: https://www.facebook.com/profile.php?id=743687287

***

###getUserProfilePicture()
####Returns: _string_
Get the user's profile picture URL. Usage example:

    $picture = $saf->getUserProfilePicture();

    // Produces: https://graph.facebook.com/743687287/picture

***

###getExtendedPerms()
####Returns: _string_
Get the extended permissions we are asking for. Usage example:

    $extended_perms = $saf->getExtendedPerms();

***

###getUserGrantedPerms()
####Returns: _array_
Get the extended permissions the user has granted. Usage example:

    $granted_perms = $saf->getUserGrantedPerms();

***

###getUserRevokedPerms()
####Returns: _array_
Get the extended permissions the user has revoked. Usage example:

    $revoked_perms = $saf->getUserRevokedPerms();

***

###getLoginUrl()
####Returns: _string_
Get the login URL for app authentication. Usage example:

    $login_url = $saf->getLoginUrl();

>**NOTE:** This method already takes into account the proper redirect URL and
desired scope.

***

###getLogoutUrl()
####Returns: _string_
Get the logout URL in order to log the user out of Facebook and your app.
Usage example:

    $saf->getLogoutUrl();

>**NOTE:** This method already takes into account the proper redirect URL.

***

###getLoginLink()
####Returns: _string_
Get the login link for app authentication. Returns an anchor tag. Usage example:

    $login_link = $saf->getLoginLink();

***

###getLogoutLink()
####Returns: _string_
Get the logout link in order to log the user out of Facebook and the app. Returns
an anchor tag. Usage example:

    $logout_link = $saf->getLogoutLink();

***

###isAppDeveloper()
####Returns: _bool_
Determines whether or not the user is the app developer. Usage example:

    $saf->isAppDeveloper();

>**NOTE: This method relies on the developer Id(s) being properly set config file.**

***

###isAuthenticated()
####Returns: _bool_
Determines whether or not the user is authenticated (eg - user has allowed the app).
Usage example:

    $saf->isAuthenticated();

***

###hasPermission()
####Returns: _bool_
Determines whether or not the user has granted a specific permission.
Usage Example:

    $saf->hasPermission('publish_stream');

***

###setRedirectUrl($value)
####Parameter: _string_
Allows you to set the redirect URL. Useful if you need to override the default.
Usage example:

    $saf->setRedirectUrl('http://domain.com/');

>**NOTE: See Facebook documentation for restrictions on Redirect URLs.**

***
