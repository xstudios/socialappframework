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


# FB Graph Link Class
A small class with a simple task: Publish a link.

Usage example:

    require_once 'socialappframework/facebook/graph/fb_graph_link.php';

    $post = FB_Graph_Link('http://domain.com/');
    $post->create();

***

##Public Methods

###setLink($url)
####Parameter: _string_
Set the link attached to this post. Usage example:

    $post->setLink('http://domain.com/');

***

###setMessage($value)
####Parameter: _string_
Set the post message. Usage example:

    $post->setMessage('message');

>**Note:** Can only be used if link is specified

***

###setPublished($value)
####Parameter: _boolean_
Set whether a post is published. Default is published. Usage example:

    $post->setPublished(false);

>**Permissions required:** `publish_stream` and `manage_pages`
>**Only available when publishing to a page.**

***

###setScheduledPublishTime($$timestamp)
####Parameter: _string_ a unix timestamp
Set scheduled publish time. This is the time when the page post should go live, 
this should be between 10 mins and 6 months from the time of publishing the post. 
Usage example:

    $post->setScheduledPublishTime(false);

>**Permissions required:** `publish_stream` and `manage_pages`
>**Only available when publishing to a page.**

***

###create($profile_id='me')
####Param: _string_ $profile_id the profile ID (eg - me)
####Return: _int_ the Post ID
Create the post and return the Post ID. Usage example:

    $id = $post->create();

***
