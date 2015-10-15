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

$app->get('/', function () use ($app) {
    return $app->welcome();
});

// 我们写好了好多不同版本的api
// web端可以调用自己的api，而且返回的是对象
$dispatcher = app('Dingo\Api\Dispatcher');
$app->get('webUsers', function() use ($dispatcher) {

    $users = $dispatcher->version('v2')->get('users');
    return view('index', ['users'=>$users]);
});

$api = app('Dingo\Api\Routing\Router');
//$api = app('api.router'); //也可以这样

// v1版本的API
$api->version('v1', function ($api) {
    $api->resource('foos', 'App\Http\Controllers\Api\V1\FooController');
});

// v2版本的API, 私有接口，但是这个怎么用？
$api->version('v2', ['protected' => true], function ($api) {
    $api->resource('users', 'App\Http\Controllers\Api\V2\UserController');
});
