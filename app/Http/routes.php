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
    return $app->version();
});

$app->group(['prefix'=>'v1', 'namespace'=>'App\Http\Controllers\Api\V1'], function ($app) {
    $app->get('foobar', function () {
    });
    // 注册
    $app->post('auth/signup', [
        'as'   => 'v1.auth.login',
        'user' => 'AuthController@login'
    ]);

    // 登录
    $app->post('auth/login', [
        'as'   => 'v1.auth.login',
        'user' => 'AuthController@login'
    ]);

    $app->post('auth/login', [
        'as'   => 'v1.auth.login',
        'user' => 'AuthController@login'
    ]);
});
