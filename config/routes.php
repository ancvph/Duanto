<?php

// Auth routes
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// Admin routes
$router->get('/admin', 'AdminController@index');
$router->get('/admin/rooms', 'AdminController@rooms');
$router->get('/admin/bookings', 'AdminController@bookings');
$router->get('/admin/services', 'AdminController@services');
$router->get('/admin/users', 'AdminController@users');
$router->get('/admin/reviews', 'AdminController@reviews');
$router->get('/admin/settings', 'AdminController@settings');

// Home routes
$router->get('/', 'HomeController@index');
$router->get('/search', 'HomeController@search');
$router->get('/rooms', 'HomeController@rooms');
$router->get('/rooms/{id}', 'HomeController@roomDetail');
$router->get('/services', 'HomeController@services');
$router->get('/about', 'HomeController@about');
$router->get('/contact', 'HomeController@contact');

// User routes
$router->get('/profile', 'UserController@profile');
$router->post('/profile', 'UserController@updateProfile');
$router->get('/bookings', 'UserController@bookings');
$router->get('/reviews', 'UserController@reviews'); 