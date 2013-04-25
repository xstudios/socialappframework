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


# FB Graph Event Class
A small class with a simple task: Publish an event.

Usage example:

    require_once 'socialappframework/facebook/graph/fb_graph_event.php';

    $event = FB_Graph_Event('name', 'start_time');
    $event->create();

***

##Public Methods

###setName($value)
####Parameter: _string_
Set the event name. Usage example:

    $event->setName('name');

***

###setStartTime($value)
####Parameter: _string_ in ISO-8601
Set the start time. Usage example:

    $event->setStartTime('');

***

###setEndTime($value)
####Parameter: _string_ in ISO-8601
Set the end time. Usage example:

    $event->setEndTime('');

***

###setDescription($value)
####Parameter: _string_
Set the event description. Usage example:

    $event->setDescription('description');

***

###setLocation($value)
####Parameter: _string_
Set the event location. Usage example:

    $event->setLocation('location');

***

###setLocationId($value)
####Parameter: _string_ place ID where the event is taking place
Set the event location ID. Usage example:

    $event->setLocationId('123456');

***

###setPrivacyType($value)
####Parameter: _string_ OPEN (default), SECRET, or FRIENDS
Set the event privacy type. Usage example:

    $event->setPrivacyType('OPEN');

***

###create($object_id='me')
####Param: _string_ $object_id the object ID (eg - me)
####Return: _int_ the Event ID
Create the event and return the Event ID. Usage example:

    $id = $event->create();

***
