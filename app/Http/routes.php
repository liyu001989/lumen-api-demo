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

// v1 version API
// choose version add this in header    Accept:application/vnd.lumen.v1+json
$api->version('v1', ['namespace' => 'App\Http\Controllers\Api\V1'], function ($api) {
    # Auth
    // login
    $api->post('auth/login', [
        'as' => 'auth.login',
        'uses' => 'AuthController@login',
    ]);
    // signup
    $api->post('auth/signup', [
        'as' => 'auth.signup',
        'uses' => 'AuthController@signup',
    ]);

    # User
    // user list
    $api->get('users', [
        'as' => 'users.index',
        'uses' => 'UserController@index',
    ]);
    // user detail
    $api->get('users/{id}', [
        'as' => 'users.show',
        'uses' => 'UserController@show',
    ]);

    # POST
    // post list
    $api->get('posts', [
        'as' => 'posts.index',
        'uses' => 'PostController@index',
    ]);
    // post detail
    $api->get('posts/{id}', [
        'as' => 'posts.show',
        'uses' => 'PostController@show',
    ]);

    # POST COMMENT
    // post comment list
    $api->get('posts/{postId}/comments', [
        'as' => 'posts.comments.index',
        'uses' => 'PostCommentController@index',
    ]);

    // need authentication
    $api->group(['middleware' => 'api.auth'], function ($api) {

        # AUTH
        // refresh jwt token
        $api->post('auth/token/refresh', [
            'as' => 'auth.token.refresh',
            'uses' => 'AuthController@refreshToken',
        ]);

        # USER
        // my detail
        $api->get('user', [
            'as' => 'user.show',
            'uses' => 'UserController@userShow',
        ]);
        // update my info
        $api->put('user', [
            'as' => 'user.update',
            'uses' => 'UserController@update',
        ]);
        // update my password
        $api->post('user/password', [
            'as' => 'user.password.update',
            'uses' => 'UserController@editPassword',
        ]);

        # POST
        // create a post
        $api->post('posts', [
            'as' => 'posts.store',
            'uses' => 'PostController@store',
        ]);
        // update a post
        $api->put('posts/{id}', [
            'as' => 'posts.update',
            'uses' => 'PostController@update',
        ]);

        // delete a post
        $api->delete('posts/{id}', [
            'as' => 'posts.destroy',
            'uses' => 'PostController@destroy',
        ]);

        # POST COMMENT
        // create a comment
        $api->post('posts/{postId}/comments', [
            'as' => 'posts.comments.store',
            'uses' => 'PostCommentController@store',
        ]);
        // delete a comment
        $api->delete('posts/{postId}/comments/{id}', [
            'as' => 'posts.comments.destroy',
            'uses' => 'PostCommentController@destroy',
        ]);
    });
});

// v2 version API
// add in header    Accept:application/vnd.lumen.v2+json
$api->version('v2', function ($api) {
    $api->get('foos', 'App\Http\Controllers\Api\V2\FooController@index');
});
