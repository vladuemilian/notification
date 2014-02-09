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

App::bind('Softservlet\Notification\NotificableInterface', 'YourApp\User');

More about IoC binding on Laravel documentation.

### How to use









