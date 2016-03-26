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
        'as' => 'auth.login',
        'uses' => 'AuthController@login',
    ]);
    // 注册
    $api->post('auth/signup', [
        'as' => 'auth.signup',
        'uses' => 'AuthController@signup',
    ]);

    # User
    // 用户列表
    $api->get('users', [
        'as' => 'users.index',
        'uses' => 'UserController@index',
    ]);
    // 用户信息
    $api->get('users/{id}', [
        'as' => 'users.show',
        'uses' => 'UserController@show',
    ]);

    # POST
    // 所有帖子列表
    $api->get('posts', [
        'as' => 'posts.index',
        'uses' => 'PostController@index',
    ]);
    // 帖子详情
    $api->get('posts/{id}', [
        'as' => 'posts.show',
        'uses' => 'PostController@show',
    ]);

    # POST COMMENT
    // 帖子评论列表
    $api->get('posts/{id}/comments', [
        'as' => 'posts.comments.index',
        'uses' => 'PostCommentController@index',
    ]);

    // 需要jwt验证后才能使用的API
    $api->group(['middleware' => 'api.auth'], function ($api) {

        # AUTH
        // 刷新token
        $api->post('auth/token/refresh', [
            'as' => 'auth.token.refresh',
            'uses' => 'AuthController@refreshToken',
        ]);

        # USER
        // 获得个人信息
        $api->get('user', [
            'as' => 'user.show',
            'uses' => 'UserController@userShow',
        ]);
        // 更新个人信息
        $api->put('user', [
            'as' => 'user.update',
            'uses' => 'UserController@update',
        ]);
        // 修改密码
        $api->post('user/password', [
            'as' => 'user.password.update',
            'uses' => 'UserController@editPassword',
        ]);

        # POST
        // 发帖
        $api->post('posts', [
            'as' => 'posts.store',
            'uses' => 'PostController@store',
        ]);
        // 修改帖子
        $api->put('posts/{id}', [
            'as' => 'posts.store',
            'uses' => 'PostController@store',
        ]);
        // 删除帖子
        $api->delete('posts/{id}', [
            'as' => 'posts.destroy',
            'uses' => 'PostController@destroy',
        ]);

        # POST COMMENT
        // 发表回复
        $api->post('posts/{id}/comments', [
            'as' => 'posts.comments.store',
            'uses' => 'PostCommentController@store',
        ]);
        // 删除回复
        $api->delete('posts/{id}/comments/{id}', [
            'as' => 'posts.comments.destroy',
            'uses' => 'PostCommentController@destroy',
        ]);
    });
});

// v2版本的API
// 头里面需要加    Accept:application/vnd.lumen.v1+json
$api->version('v2', function ($api) {
    $api->get('foos', 'App\Http\Controllers\Api\V2\FooController@index');
});
