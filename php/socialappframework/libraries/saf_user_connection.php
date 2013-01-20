<?php
/*
 * This file is part of the Social App Framework package.
 * (c) 2011-2013 X Studios
 *
 * You should have received a copy of the license (license.txt) distributed
 * with this package. If not, see <http://socialappframework.com/license/>.
 */

/**
 * Social App Framework User Connection class
 *
 * @package      Social App Framework
 * @category     Facebook
 * @author       Tim Santor <tsantor@xstudiosinc.com>
 */
class SAF_User_Connection {

    // ------------------------------------------------------------------------
    // PRIVATE VARS
    // ------------------------------------------------------------------------

    /**
     * SAF_User instance
     *
     * @access    private
     * @var       SAF_User
     */
    private $_user;

    /**
     * SAF instance
     *
     * @access    private
     * @var       SAF
     */
    private $_facebook;

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    public
     * @param     SAF_User  $user
     * @param     SAF       $facebook
     * @return    void
     */
    public function __construct($user, $facebook) {
        $this->_user     = $user;
        $this->_facebook = $facebook;
    }

    // ------------------------------------------------------------------------

    /**
     * Get a user connection
     *
     * @access    public
     * @param     string  $connection  connection name
     * @param     array   $query       query params
     * @return    mixed
     */
    public function getConnection($connection, $query=array()) {
        $connection = '/'.$connection;

        // call the api
        $result = $this->_facebook->api('/'.$this->_user->getId().$connection, 'GET', $query);

        return $result['data'];
    }

    // ------------------------------------------------------------------------
    // CONNECTIONS
    // ------------------------------------------------------------------------

    /**
     * Returns the apps and pages owned by the current user.
     *
     * Permissions: manage_pages
     *
     * @access    public
     * @return    array  of objects
     */
    public function getAccounts() {
        $this->_checkPermission('manage_pages');
        return $this->getConnection('accounts');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the achievements for the user.
     *
     * Permissions: user_games_activity or friends_games_activity
     *
     * @access    public
     * @return    array  of objects
     */
    public function getAchievements() {
        $this->_checkPermission('user_games_activity');
        return $this->getConnection('achievements');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the activities listed on the user's profile.
     *
     * Permissions: user_activities or friends_activities
     *
     * @access    public
     * @return    array  of objects
     */
    public function getActivities() {
        $this->_checkPermission('user_activities');
        return $this->getConnection('activities');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the photo albums the user has created.
     *
     * Permissions: user_photos or friends_photos
     *
     * @access    public
     * @return    array  of album objects
     */
    public function getAlbums() {
        $this->_checkPermission('user_photos');
        return $this->getConnection('albums');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the user's outstanding requests from an app.
     *
     * Permissions: app access token
     *
     * @access    public
     * @return    array  of app requests
     */
    public function getAppRequests() {
        return $this->getConnection('apprequests', array(
            'access_token' => $this->_facebook->getAppAccessToken()
        ));
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the books listed on the user's profile.
     *
     * Permissions: user_likes or friends_likes
     *
     * @access    public
     * @return    array  of objects
     */
    public function getBooks() {
        $this->_checkPermission('user_photos');
        return $this->getConnection('books');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the places that the user has checked-into.
     *
     * Permissions: user_checkins or friends_checkins
     *
     * @access    public
     * @return    array  of checkin objects
     */
    public function getCheckins() {
        $this->_checkPermission('user_checkins');
        return $this->getConnection('checkins');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the events this user is attending.
     *
     * Permissions: user_events or friends_events
     *
     * @access    public
     * @return    array  of objects
     */
    public function getEvents() {
        $this->_checkPermission('user_events');
        return $this->getConnection('events');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the user's family relationships.
     *
     * Permissions: user_relationships
     *
     * @access    public
     * @return    array  of objects
     */
    public function getFamily() {
        $this->_checkPermission('user_relationships');
        return $this->getConnection('family');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the users's wall.
     *
     * Permissions: read_stream
     *
     * @access    public
     * @return    array  of post objects
     */
    public function getFeed() {
        $this->_checkPermission('read_stream');
        return $this->getConnection('feed');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the user's friend lists.
     *
     * Permissions: read_friendlists
     *
     * @access    public
     * @return    array  of post objects
     */
    public function getFriendLists() {
        $this->_checkPermission('read_friendlists');
        return $this->getConnection('friendlists');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the user's incoming friend requests.
     *
     * Permissions: user_requests
     *
     * @access    public
     * @return    array  of objects
     */
    public function getFriendRequests() {
        $this->_checkPermission('user_requests');
        return $this->getConnection('friendrequests');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the user's friends.
     *
     * @access    public
     * @return    array  of objects
     */
    public function getFriends() {
        return $this->getConnection('friends');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the games the user has added to the Arts and
     * Entertainment section of their profile.
     *
     * Permissions: user_likes
     *
     * @access    public
     * @return    array  of objects
     */
    public function getGames() {
        $this->_checkPermission('user_likes');
        return $this->getConnection('games');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the groups that the user belongs to.
     *
     * Permissions: user_groups or friends_groups
     *
     * @access    public
     * @return    array  of objects
     */
    public function getGroups() {
        $this->_checkPermission('user_groups');
        return $this->getConnection('groups');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the user's news feed.
     *
     * Permissions: read_stream
     *
     * @access    public
     * @return    array  of post objects
     */
    public function getNews() {
        $this->_checkPermission('read_stream');
        return $this->getConnection('home');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the threads in this user's inbox.
     *
     * Permissions: read_mailbox
     *
     * @access    public
     * @return    array  of thread objects
     */
    public function getInbox() {
        $this->_checkPermission('read_mailbox');
        return $this->getConnection('inbox');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the interests listed on the user's profile.
     *
     * Permissions: user_interests or friends_interests
     *
     * @access    public
     * @return    array  of objects
     */
    public function getInterests() {
        $this->_checkPermission('user_interests');
        return $this->getConnection('interests');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the pages this user has liked.
     *
     * Permissions: user_likes or friends_likes
     *
     * @access    public
     * @return    array  of objects
     */
    public function getLikes() {
        $this->_checkPermission('user_likes');
        return $this->getConnection('likes');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the user's posted links.
     *
     * Permissions: read_stream
     *
     * @access    public
     * @return    array  of link objects
     */
    public function getLinks() {
        $this->_checkPermission('read_stream');
        return $this->getConnection('links');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the posts, statuses, and photos in which the user has
     * been tagged at a location, or where the user has authored
     * content.
     *
     * Permissions: user_photos, friend_photos, user_status,
     * friends_status, user_checkins, or friends_checkins.
     *
     * @access    public
     * @return    array  of objects
     */
    public function getLocations() {
        return $this->getConnection('locations');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the movies listed on the user's profile.
     *
     * Permissions: user_likes or friends_likes.
     *
     * @access    public
     * @return    array  of objects
     */
    public function getMovies() {
        $this->_checkPermission('user_likes');
        return $this->getConnection('movies');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the music listed on the user's profile.
     *
     * Permissions: user_likes or friends_likes
     *
     * @access    public
     * @return    array  of objects
     */
    public function getMusic() {
        $this->_checkPermission('user_likes');
        return $this->getConnection('music');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the mutual friends between two users.
     *
     * @access    public
     * @return    array  of objects
     */
    public function getMutualFriends() {
        return $this->getConnection('mutualfriends');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the user's notes.
     *
     * Permissions: read_stream
     *
     * @access    public
     * @return    array  of objects
     */
    public function getNotes() {
        $this->_checkPermission('read_stream');
        return $this->getConnection('notes');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the app notifications for the user.
     *
     * @access    public
     * @return    array  of objects
     */
    public function getNotifications() {
        return $this->getConnection('notifications');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the messages in this user's outbox.
     *
     * Permissions: read_mailbox
     *
     * @access    public
     * @return    array  of objects
     */
    public function getOutbox() {
        $this->_checkPermission('read_mailbox');
        return $this->getConnection('outbox');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the Facebook Credits orders the user placed with
     * an application.
     *
     * @access    public
     * @return    array  of objects
     */
    public function getPayments() {
        return $this->getConnection('ayments');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the permissions that user has granted the application.
     *
     * @access    public
     * @return    array  a single object with keys as the permission name
     */
    public function getPermissions() {
        return $this->getConnection('permissions');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the photos the user (or friend) is tagged in.
     *
     * Permissions: user_photo_video_tags or friends_photo_video_tags
     *
     * @access    public
     * @return    array  of photo objects
     */
    public function getPhotos() {
        $this->_checkPermission('user_photo_video_tags');
        return $this->getConnection('photos');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the photos of a user.
     *
     * Permissions: user_photos
     *
     * @access    public
     * @return    array  of photo objects
     */
    public function getPhotosUploaded() {
        $this->_checkPermission('user_photos');
        return $this->getConnection('photos/uploaded');
    }

    // ------------------------------------------------------------------------

    /**
     * Get the user's profile picture
     *
     * @access    public
     * @param     string  $type  square, small, normal, large
     * @return    string  URL of the user'sprofile picture
     */
    public function getPicture($type='square') {
        return $this->getConnection('picture', array(
            'type' => $type
        ));
    }

    // ------------------------------------------------------------------------

    /**
     * Returns theuser's pokes.
     *
     * Permissions: read_mailbox
     *
     * @access    public
     * @return    array  of objects
     */
    public function getPokes() {
        $this->_checkPermission('read_mailbox');
        return $this->getConnection('pokes');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the user's own posts.
     *
     * Permissions: read_stream for non-public posts
     *
     * @access    public
     * @param     boolean  $non_public  return non-public posts as well
     * @return    array    of post objects
     */
    public function getPosts($non_public=false) {
        if ($non_public === true) {
            $this->_checkPermission('read_mailbox');
        }
        return $this->getConnection('posts');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the user's questions.
     *
     * Permissions: user_questions
     *
     * @access    public
     * @return    array  of question objects
     */
    public function getQuestions() {
        $this->_checkPermission('user_questions');
        return $this->getConnection('questions');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the current scores for the user in games.
     *
     * Permissions: user_games_activity or friends_games_activity
     *
     * @access    public
     * @return    array  of objects
     */
    public function getScores() {
        $this->_checkPermission('user_games_activity');
        return $this->getConnection('scores');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the shares of the object.
     *
     * Permissions: read_stream
     *
     * @access    public
     * @return    array  of post objects
     */
    public function getSharedPosts() {
        $this->_checkPermission('read_stream');
        return $this->getConnection('sharedposts');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the user's status updates.
     *
     * Permissions: read_stream
     *
     * @access    public
     * @return    array  of post objects
     */
    public function getStatuses() {
        $this->_checkPermission('read_stream');
        return $this->getConnection('statuses');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the people the user is subscribed to.
     *
     * @access    public
     * @return    array  of objects
     */
    public function getSubscribedTo() {
        return $this->getConnection('subscribedto');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the user's subscribers.
     *
     * @access    public
     * @return    array  of objects
     */
    public function getSubscribers() {
        return $this->getConnection('subscribers');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the posts the user is tagged in.
     *
     * Permissions: read_stream
     *
     * @access    public
     * @return    array  of objects
     */
    public function getTagged() {
        $this->_checkPermission('read_stream');
        return $this->getConnection('tagged');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the television listed on the user's profile.
     *
     * Permissions: user_likes or friends_likes
     *
     * @access    public
     * @return    array  of objects
     */
    public function getTelevision() {
        $this->_checkPermission('user_likes');
        return $this->getConnection('television');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the updates in this user's inbox.
     *
     * Permissions: read_mailbox
     *
     * @access    public
     * @return    array  of objects
     */
    public function getUpdates() {
        $this->_checkPermission('read_mailbox');
        return $this->getConnection('updates');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the videos this user has been tagged in.
     *
     * Permissions: user_videos or friends_videos
     *
     * @access    public
     * @return    array  of video objects
     */
    public function getVideos() {
        $this->_checkPermission('user_videos');
        return $this->getConnection('videos');
    }

    // ------------------------------------------------------------------------
    // PRIVATE METHODS
    // ------------------------------------------------------------------------

    /**
     * Check the user's permissions
     *
     * @access    private
     * @throws    FacebookApiException
     * @return    void
     */
    private function _checkPermission($perm) {
        if ($this->_user->hasPermission($perm) === false) {
            $result['error']['message'] = 'Requires '.$perm.' permission.';
            throw new FacebookApiException($result);
        }
    }

    // ------------------------------------------------------------------------

}

/* End of file */
