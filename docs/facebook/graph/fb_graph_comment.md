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


# FB Graph Comment Class
A small class with a simple task: Publish a comment.

Usage example:

    require_once 'socialappframework/facebook/graph/fb_graph_comment.php';

    $comment = FB_Graph_Comment('object_id', 'comment');
    $comment->create();

***

##Public Methods

###setMessage($value)
####Parameter: _string_
Set the post message. Usage example:

    $comment->setMessage('comment');

***

###create($object_id)
####Param: _string_ $object_id the object ID to comment on
####Return: _int_ the Comment ID
Create the comment and return the Comment ID. Usage example:

    $id = $comment->create();

***
