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


# FB Graph Notification Class
A small class with a simple task: Publish a notification.

Usage example:

    require_once 'socialappframework/facebook/graph/fb_graph_notification.php';

    $notification = FB_Graph_Notification('template', 'href');
    $notification->create('profile_id');

***

##Public Methods

###setTemplate($value)
####Parameter: _string_ the template text
Set the template text. Usage example:

    $notification->setTemplate('@[USER_ID] just beat your high score. 
    Regain your title!');

> **Note: User mentions in the template should use the new syntax 
> @[USER_ID] instead of the old syntax {USER_ID}

***

###setHref($value)
####Parameter: _string_
Set the unique tracking data you want added to the url. Usage example:

    $notification->setHref('tracking');

***

###create($profile_id)
####Param: _string_ $profile_id the user ID
####Return: _boolean_
Create the notification and return true if the post succeeded. Usage example:

    $result = $notification->create('profile_id');

***
