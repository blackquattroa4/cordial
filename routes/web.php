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

$router->group(['prefix' => 'person'], function() use ($router) {

  //  GET /person
  $router->get('', ['uses' => 'PersonController@list', 'middleware' => [ 'person:list' ]]);

  //  POST /person
  $router->post('', ['uses' => 'PersonController@create', 'middleware' => [ 'person:create' ]]);

  $router->group(['prefix' => '{person}'], function() use ($router) {

    //  POST /person/{id}
    $router->post('', ['uses' => 'PersonController@update', 'middleware' => [ 'person:update' ]]);

    //  DELETE /person/{id}
    $router->delete('', ['uses' => 'PersonController@delete', 'middleware' => [ 'person:delete' ]]);

  });

});
