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

# SAF User Connection Class
The SAF User Connection class provides easy access to all of a user's available
connections. See API docs on a [User](https://developers.facebook.com/docs/reference/api/user/).

##Public Methods

###getAccounts()
####Returns: _array_
Returns the pages owned by the current user. Usage example:

    $saf->user->connection->getAccounts();

>**Requires permissions:** `manage_pages`

***

###getAchievements()
####Returns: _array_
Returns the achievements for the user. Usage example:

    $saf->user->connection->getAchievements();

>**Requires permissions:** `user_games_activity` or `friends_games_activity`

***

###getActivities()
####Returns: _array_
Returns the activities listed on the user's profile. Usage example:

    $saf->user->connection->getActivities();

>**Requires permissions:** `user_activities` or `friends_activities`

***

###getAlbums()
####Returns: _array_
Returns the photo albums the user has created. Usage example:

    $saf->user->connection->getAlbums();

>**Requires permissions:** `user_photos` or `friends_photos`

***

###getApplications()
####Returns: _array_
Returns the apps owned by the current user. Usage example:

    $saf->user->connection->getApplications();

>**Requires permissions:** app access token

***

###getAppRequests()
####Returns: _array_
Returns the user's outstanding requests from an app. Usage example:

    $saf->user->connection->getAppRequests();

>**Requires permissions:** app access token

***

###getBooks()
####Returns: _array_
Returns the books listed on the user's profile. Usage example:

    $saf->user->connection->getBooks();

>**Requires permissions:** `user_likes` or `friends_likes`

***

###getCheckins()
####Returns: _array_
Returns the places that the user has checked-into. Usage example:

    $saf->user->connection->getCheckins();

>**Requires permissions:** `user_checkins` or `friends_checkins`

***

###getEvents()
####Returns: _array_
Returns the events this user is attending. Usage example:

    $saf->user->connection->getEvents();

>**Requires permissions:** `user_events` or `friends_events`

***

###getFamily()
####Returns: _array_
Returns the user's family relationships. Usage example:

    $saf->user->connection->getFamily();

>**Requires permissions:** `user_relationships`

***

###getFeed()
####Returns: _array_
Returns the users's wall. Usage example:

    $saf->user->connection->getFeed();

>**Requires permissions:** `read_stream`

***

###getFriends()
####Returns: _array_
Returns the user's friends. Usage example:

    $saf->user->connection->getFriends();

***

###getFriendLists()
####Returns: _array_
Returns the user's friend lists. Usage example:

    $saf->user->connection->getFriendLists();

>**Requires permissions:** `read_friendlists`

***

###getFriendRequests()
####Returns: _array_
Returns the user's incoming friend requests. Usage example:

    $saf->user->connection->getFriendRequests();

>**Requires permissions:** `read_requests`

***

###getGames()
####Returns: _array_
Returns the games the user has added to the Arts and Entertainment section 
of their profile. Usage example:

    $saf->user->connection->getGames();

>**Requires permissions:** `user_likes`

***

###getGroups()
####Returns: _array_
Returns the groups that the user belongs to. Usage example:

    $saf->user->connection->getGroups();

>**Requires permissions:** `user_groups` or `friends_groups`

***

###getNewsFeed()
####Returns: _array_
Returns the user's news feed. Usage example:

    $saf->user->connection->getNewsFeed();

>**Requires permissions:** `read_stream`

***

###getInbox()
####Returns: _array_
Returns the threads in this user's inbox. Usage example:

    $saf->user->connection->getInbox();

>**Requires permissions:** `read_mailbox`

***

###getInterests()
####Returns: _array_
Returns the interests listed on the user's profile. Usage example:

    $saf->user->connection->getInterests();

>**Requires permissions:** `user_interests` or `friends_interests`

***

###getLikes()
####Returns: _array_
Returns the pages this user has liked. Usage example:

    $saf->user->connection->getLikes();

>**Requires permissions:** `user_likes` or `friends_likes`

***

###getLinks()
####Returns: _array_
Returns the user's posted links. Usage example:

    $saf->user->connection->getLinks();

>**Requires permissions:** `read_stream`

***

###getLocations()
####Returns: _array_
Returns the posts, statuses, and photos in which the user has been tagged 
at a location, or where the user has authored content. Usage example:

    $saf->user->connection->getLocations();

>**Requires permissions:** `user_photos`, `friend_photos`, `user_status`, `friends_status`, 
`user_checkins`, or `friends_checkins`

***

###getMovies()
####Returns: _array_
Returns the movies listed on the user's profile. Usage example:

    $saf->user->connection->getMovies();

>**Requires permissions:** `user_likes` or `friends_likes`

***

###getMusic()
####Returns: _array_
Returns the music listed on the user's profile. Usage example:

    $saf->user->connection->getMusic();

>**Requires permissions:** `user_likes` or `friends_likes`

***

###getMutualFriends()
####Returns: _array_
Returns the mutual friends between two users. Usage example:

    $saf->user->connection->getMutualFriends();

***

###getNotes()
####Returns: _array_
Returns the user's notes. Usage example:

    $saf->user->connection->getNotes();

>**Requires permissions:** `read_stream`

***

###getNotifications()
####Returns: _array_
Returns the app notifications for the user. Usage example:

    $saf->user->connection->getNotifications();

>**Requires permissions:** `manage_notifications`

***

###getOutbox()
####Returns: _array_
Returns the messages in this user's outbox. Usage example:

    $saf->user->connection->getOutbox();

>**Requires permissions:** `read_mailbox`

***

###getPayments()
####Returns: _array_
Returns the Facebook Credits orders the user placed with an application. 
Requires application to be payments enabled. Usage example:

    $saf->user->connection->getPayments();

***

###getPermissions()
####Returns: _array_
Returns the permissions that user has granted the application. Usage example:

    $saf->user->connection->getPermissions();

***

###getPhotos()
####Returns: _array_
Returns the photos the user (or friend) is tagged in. Usage example:

    $saf->user->connection->getPhotos();

***

###getPhotosUploaded()
####Returns: _array_
Returns the photos of a user. Usage example:

    $saf->user->connection->getPhotosUploaded();

>**Requires permissions:** `user_photos`

***

###getPicture($type)
####Param: _string_ (square [default], small, normal, large)
####Returns: _string_
Return the page's profile picture. Usage example:

    $saf->page->connection->getPicture('normal');

***

###getPokes()
####Returns: _array_
Returns the user's pokes. Usage example:

    $saf->user->connection->getPokes();

>**Requires permissions:** `read_mailbox`

***

###getPosts()
####Returns: _array_
Returns the user's own posts. Usage example:

    $saf->user->connection->getPosts();

>**Requires permissions:** `read_stream` for non-public posts

***

###getQuestions()
####Returns: _array_
Returns the user's questions. Usage example:

    $saf->user->connection->getQuestions();

>**Requires permissions:** `user_questions`

***

###getScores()
####Returns: _array_
Returns the current scores for the user in games. Usage example:

    $saf->user->connection->getScores();

>**Requires permissions:** `user_games_activity` or `friends_games_activity`

***

###getSharedPosts()
####Returns: _array_
Returns the shares of the object. Usage example:

    $saf->user->connection->getSharedPosts();

>**Requires permissions:** `read_stream`

***

###getStatuses()
####Returns: _array_
Returns the user's status updates. Usage example:

    $saf->user->connection->getStatuses();

>**Requires permissions:** `read_stream`

***

###getSubscribedTo()
####Returns: _array_
Returns the people the user is subscribed to. Usage example:

    $saf->user->connection->getSubscribedTo();

***

###getSubscribers()
####Returns: _array_
Returns the user's subscribers. Usage example:

    $saf->user->connection->getSubscribers();

***

###getTagged()
####Returns: _array_
Returns the posts the user is tagged in. Usage example:

    $saf->user->connection->getTagged();

>**Requires permissions:** `read_stream`

***

###getTelevision()
####Returns: _array_
Returns the television listed on the user's profile. Usage example:

    $saf->user->connection->getTelevision();

>**Requires permissions:** `user_likes` or `friends_likes`

***

###getUpdates()
####Returns: _array_
Returns the updates in this user's inbox. Usage example:

    $saf->user->connection->getUpdates();

>**Requires permissions:** `read_mailbox`

***

###getVideos()
####Returns: _array_
Returns the videos this user has been tagged in. Usage example:

    $saf->user->connection->getVideos();

>**Requires permissions:** `user_videos` or `friends_videos`

***
