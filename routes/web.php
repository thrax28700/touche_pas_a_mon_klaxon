<?php

declare(strict_types=1);

use Buki\Router\Router;

/** @var Router $router */

$router->get('/', 'HomeController@index');

$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->post('/logout', 'AuthController@logout');

$router->get('/trajets/create', 'TrajetController@create');
$router->post('/trajets', 'TrajetController@store');
$router->get('/trajets/:id/edit', 'TrajetController@edit');
$router->post('/trajets/:id', 'TrajetController@update');
$router->post('/trajets/:id/delete', 'TrajetController@destroy');

$router->get('/admin', 'Admin\\DashboardController@index');
$router->get('/admin/utilisateurs', 'Admin\\UserController@index');

$router->get('/admin/agences', 'Admin\\AgenceController@index');
$router->get('/admin/agences/create', 'Admin\\AgenceController@create');
$router->post('/admin/agences', 'Admin\\AgenceController@store');
$router->get('/admin/agences/:id/edit', 'Admin\\AgenceController@edit');
$router->post('/admin/agences/:id', 'Admin\\AgenceController@update');
$router->post('/admin/agences/:id/delete', 'Admin\\AgenceController@destroy');

$router->get('/admin/trajets', 'Admin\\TrajetController@index');
$router->post('/admin/trajets/:id/delete', 'Admin\\TrajetController@destroy');
