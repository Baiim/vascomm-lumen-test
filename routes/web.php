<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/login', 'AuthController@login');
$router->group(['prefix' => 'api', 'middleware' => 'auth:api'], function () use ($router) {
    $router->get('/users', ['middleware' => 'checkRole:admin', 'uses' => 'ApiController@getUsers']);
    $router->post('/users', ['middleware' => 'checkRole:admin', 'uses' => 'ApiController@createUser']);
    $router->put('/users/{id}', ['middleware' => 'checkRole:admin', 'uses' => 'ApiController@updateUser']);

    // Routing untuk CRUD produk
    $router->get('/products', 'ApiController@getProducts');
    $router->post('/products', ['middleware' => 'checkRole:admin', 'uses' => 'ApiController@createProduct']);
    $router->put('/products/{id}', ['middleware' => 'checkRole:admin', 'uses' => 'ApiController@updateProduct']);
    $router->delete('/products/{id}', ['middleware' => 'checkRole:admin', 'uses' => 'ApiController@deleteProduct']);

    // Routing untuk Auth 
});


