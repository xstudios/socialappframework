# Social App Framework Docs
### Version 1.0.0

## Table of Contents
* [FB Graph Album](fb_graph_album.md)
* [FB Graph Checkin](fb_graph_checkin.md)
* [FB Graph Comment](fb_graph_comment.md)
* [FB Graph Event](fb_graph_event.md)
* [FB Graph Link](fb_graph_link.md)
* [FB Graph Note](fb_graph_note.md)
* [FB Graph Notification](fb_graph_notification.md)
* [FB Graph Post](fb_graph_post.md)
* [FB Graph Question](fb_graph_question.md)


# FB Graph Checkin Class
A small class with a simple task: Publish a checkin.

Usage example:

    require_once 'socialappframework/facebook/graph/fb_graph_checkin.php';

    $checkin = FB_Graph_Checkin('262672746449', '28.6275', '-81.3633333');
    $checkin->create();

***

##Public Methods

###setLink($url)
####Parameter: _string_
Set the link attached to this post. Usage example:

    $checkin->setLink('http://domain.com/');

***

###setMessage($value)
####Parameter: _string_
Set the post message. Usage example:

    $checkin->setMessage('message');

***

###setPicture($url)
####Parameter: _string_
Set the post thumbnail image. Usage example:

    $checkin->setPicture('http://domain.com/img/thumb.jpg');

***

###setTags($value)
####Parameter: _string_ comma separated list of user IDs
Set a list of tagged friends. Usage example:

    $checkin->setTags('12345', 54321, 67890');

***

###create($profile_id='me')
####Param: _string_ $profile_id the profile ID (eg - me)
####Return: _int_ the Post ID
Create the post and return the Post ID. Usage example:

    $id = $checkin->create();

***
