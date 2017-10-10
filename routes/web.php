<?php

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
$router->post('/create', 'UploadController@create');
$router->post('/upload/{code}/', ['as' => 'upload', 'uses' => 'UploadController@upload']);
$router->get('/image/{image}/{command:.*}', 'ImageController@handler');
$router->get('/i/{image}/{command:.*}', 'ImageController@handler');
