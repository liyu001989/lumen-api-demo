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

$api = app('Dingo\Api\Routing\Router');

// v1版本的API
// 不需要验证jwt-token
// 默认是v1，头里面可以加    Accept:application/vnd.lumen.v1+json
$api->version('v1', ['namespace' => 'App\Http\Controllers\Api\V1'], function ($api) {
    # Auth
    // 登录
    $api->post('auth/login', [
        'as'   => 'auth.login',
        'uses' => 'AuthController@login'
    ]);
    // 注册
    $api->post('auth/signup', [
        'as'   => 'auth.signup',
        'uses' => 'AuthController@signup'
    ]);

    # User
    // 用户列表
    $api->get('users', [
        'as'   => 'users.index',
        'uses' => 'UsersController@Index'
    ]);
    // 用户信息
    $api->get('users/{id}', [
        'as'   => 'users.show',
        'uses' => 'UsersController@show'
    ]);

    // 需要jwt验证后才能使用的API
    $api->group(['middleware' => 'jwt.auth'], function ($api) {
        # Auth
        // 刷新token
        $api->post('auth/refreshToken', [
            'as'   => 'auth.refreshToken',
            'uses' => 'AuthController@refreshToken'
        ]);

        #User
        // 获得个人信息
        $api->get('/user', [
            'as'   => 'user.show',
            'uses' => 'UserController@show'
        ]);
        // 更新个人信息
        $api->put('/user', [
            'as'   => 'user.update',
            'uses' => 'UserController@update'
        ]);
        // 修改密码
        $api->post('/user/password', [
            'as'   => 'user.password.update',
            'uses' => 'UserController@editPassword'
        ]);
    });
});


// v2版本的API
// 头里面需要加    Accept:application/vnd.lumen.v1+json
$api->version('v2', function ($api) {
    $api->get('foos', 'App\Http\Controllers\Api\V2\FooController@index');
});