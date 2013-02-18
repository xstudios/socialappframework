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


# FB Graph Post Class
A small class with a simple task: Publish a post.

Usage example:

    require_once 'socialappframework/facebook/graph/fb_graph_post.php';

    $post = FB_Graph_Post('message');
    $post->create();

***

##Public Methods

###setLink($url)
####Parameter: _string_
Set the link attached to this post. Usage example:

    $post->setLink('http://domain.com/');

***

###setCaption($value)
####Parameter: _string_
Set the post caption. Usage example:

    $post->setCaption('caption');

>**Note:** Can only be used if link is specified

***

###setDescription($value)
####Parameter: _string_
Set the post description. Usage example:

    $post->setDescription('description');

>**Note:** Can only be used if link is specified

***

###setName($value)
####Parameter: _string_
Set the post name. Usage example:

    $post->setName('name');

>**Note:** Can only be used if link is specified

***

###setPicture($url)
####Parameter: _string_
Set the post thumbnail image. Usage example:

    $post->setPicture('http://domain.com/img/thumb.jpg');

>**Note:** Can only be used if link is specified

***

###setActions($array)
####Parameter: _array_ of objects containing 'name' and 'link' keys
Set the post actions. Array of objects containing 'name' and 'link' keys. 
Usage example:

    $action = array(
        'name' => 'Play Now', 
        'link' => 'TAB_URL'
    );

    $post->setActions($action);

***

###setTargeting($json_obj)
####Parameter: _string_ JSON object
Set the post target. JSON object containing countries, cities, regions 
or locales. Usage example:

    $post->setTargeting();

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

###createAction($name, $link)
####Param: _string_ $name display name
####Param: _string_ $link URL value
Returns an associative array for an action link. Usage example:

    $action = $post->createAction('name', 'URL');

    // then you can simply set the post action(s) using
    $post->setActions($action);

***

###create($profile_id='me')
####Param: _string_ $profile_id the profile ID (eg - me)
####Return: _int_ the Post ID
Create the post and return the Post ID. Usage example:

    $id = $post->create();

***
