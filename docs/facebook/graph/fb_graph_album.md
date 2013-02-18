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


# FB Graph Album Class
A small class with a simple task: Publish a photo album.

Usage example:

    require_once 'socialappframework/facebook/graph/fb_graph_album.php';

    $album = FB_Graph_Album('name');
    $album->create();

***

##Public Methods

###setMessage($value)
####Parameter: _string_
Set the album description. Usage example:

    $album->setMessage('album description');

***

###setPrivacy($array)
####Parameter: _array_
Set the album privacy level. Usage example:

    $album->setPrivacy(array('value'=>'ALL_FRIENDS'));

>**More info:** https://developers.facebook.com/docs/reference/api/privacy-parameter/

***

###create($profile_id='me')
####Param: _string_ $profile_id the profile ID (eg - me)
####Return: _int_ the Album ID
Create the album and return the Album ID. Usage example:

    $id = $album->create();

***
