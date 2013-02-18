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


# FB Graph Note Class
A small class with a simple task: Publish a note.

Usage example:

    require_once 'socialappframework/facebook/graph/fb_graph_note.php';

    $note = FB_Graph_Note('subject', 'message');
    $note->create();

***

##Public Methods

###setSubject($value)
####Parameter: _string_
Set the note subject. Usage example:

    $note->setSubject('subject');

***

###setMessage($value)
####Parameter: _string_
Set the note message. Usage example:

    $note->setMessage('message');

***

###create($profile_id='me')
####Param: _string_ $profile_id the profile ID (eg - me)
####Return: _int_ the Note ID
Create the note and return the Note ID. Usage example:

    $id = $note->create();

***
