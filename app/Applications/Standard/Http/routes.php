<?php

/** @var \Illuminate\Routing\Router $router */

// Login handler
$router->get('/signin', ['as' => 'signin', 'uses' => 'HomeController@signin']);
$router->get('/login', ['as' => 'login', 'uses' => 'HomeController@signin']);
$router->post('/signin', ['as' => 'signin.post', 'uses' => 'HomeController@postSignin']);

// Admin stuff (auth)
$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/signout', ['as' => 'signout', 'uses' => 'HomeController@signout']);
    $router->get('/logout', ['as' => 'logout', 'uses' => 'HomeController@signout']);

    $router->get('/', ['as' => 'home', 'uses' => 'HomeController@home']);
    $router->get('/dashboard', ['as' => 'dashboard', 'uses' => 'HomeController@dashboard']);

    $router->get('/users', ['middleware' => ['role:admin|super_admin'], 'as' => 'users', 'uses' => 'HomeController@users']);
    $router->get('/translations', ['middleware' => ['role:admin|super_admin'], 'as' => 'translations', 'uses' => 'HomeController@translations']);
});