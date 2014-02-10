# Notification

This package came up with a structure and a database design of a 
scalable notifications system for web applications. It provides
an easy and extensible way to manage notifications for your web 
application. 



### Installation

Currently only a installation guide for Laravel 4 framework
are provided.

 * todo - add the package on packagist

 * Run database migrations 

`php artisan migrate --bench=softservlet/notification`

 * Add notification service provider to `app/config/app.php

`'Softservlet\Notification\Laravel\Providers\NotificationServiceProvider'`
 
 * Define the Notificable object in your application

The package gives you the opportunity to decide who will receive the 
notifications. You need to implements the NotificableInterface from
Softservlet\Notification namespace. 

The method getId() must return a unique id specify to each user. Where
you define IoC bindings in Laravel 4 write:

`App::bind('Softservlet\Notification\NotificableInterface', 'YourApp\User');`

More about IoC binding on Laravel documentation.

### How to use

A notification is made by a notifier object and an actor. A notification
belongs to atleast one user. 

##### Create a notification

First thing before creating a notification is to understand
the notification object and notification actor terms.

Let's analyse the next notification:

`Foo user has been received a Like to a Photo with name Summer.`

This could be a rendered notification(we'll see later how to render a 
notification).

We can abstracting that sentence and identify three main objects:
 * Foo user - which implements NotificableInterface
 * Photo - a photo which name is Summer
 * Like - a like object

A good place to write this code is in a NotificationController
from your application. I'll skip this step.

```php
//create a user which implements NotificableInterface
$user = User::find(5);

//define the Photo object
$photo = Photo::find(12); //get the Photo object with id 12

//get the like object of this photo
$like = Photo::like()->first(); //a basic Eloquent usage
```

From now, we can create a notification and attach this to the `$user`

```php
//create an instance of notification repository
$notificationRepository = App::make('Softservlet\Notification\Repositories\NotificationRepositoryInterface');

//create the notification 
$notification = $notificationRepository->create($object, $actor);

```






