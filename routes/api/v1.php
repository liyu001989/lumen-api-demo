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
$api = app('Dingo\Api\Routing\Router');

// v2 version API
// add in header    Accept:application/vnd.lumen.v2+json
$api->version('v1', ['namespace' => 'App\Http\Controllers\Api\V1'], function ($api) {
    // Auth
    // login
    $api->post('authorizations', [
        'as' => 'authorizations.store',
        'uses' => 'AuthController@store',
    ]);

    // User
    $api->post('users', [
        'as' => 'users.store',
        'uses' => 'UserController@store',
    ]);
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

    // POST
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

    // POST COMMENT
    // post comment list
    $api->get('posts/{postId}/comments', [
        'as' => 'posts.comments.index',
        'uses' => 'PostCommentController@index',
    ]);

    /*
     * 对于authorizations 并没有保存在数据库，所以并没有id，那么对于
     * 刷新（put) 和 删除（delete) 我没有想到更好的命名方式
     * 所以暂时就是 authorizations/current 表示当前header中的这个token。
     * 如果 tokekn 保存保存在数据库，那么就是 authorizations/{id}，像 github 那样。
     */
    $api->put('authorizations/current', [
        'as' => 'authorizations.update',
        'uses' => 'AuthController@update',
    ]);

    // need authentication
    $api->group(['middleware' => 'api.auth'], function ($api) {
        /*
         * 对于authorizations 并没有保存在数据库，所以并没有id，那么对于
         * 刷新（put) 和 删除（delete) 我没有想到更好的命名方式
         * 所以暂时就是 authorizations/current 表示当前header中的这个token。
         * 如果 tokekn 保存保存在数据库，那么就是 authorizations/{id}，像 github 那样。
         */
        $api->delete('authorizations/current', [
            'as' => 'authorizations.destroy',
            'uses' => 'AuthController@destroy',
        ]);

        // USER
        // my detail
        $api->get('user', [
            'as' => 'user.show',
            'uses' => 'UserController@userShow',
        ]);

        // update part of me
        $api->patch('user', [
            'as' => 'user.update',
            'uses' => 'UserController@patch',
        ]);
        // update my password
        $api->put('user/password', [
            'as' => 'user.password.update',
            'uses' => 'UserController@editPassword',
        ]);

        // POST
        // user's posts index
        $api->get('user/posts', [
            'as' => 'user.posts.index',
            'uses' => 'PostController@userIndex',
        ]);
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
        // update part of a post
        $api->patch('posts/{id}', [
            'as' => 'posts.patch',
            'uses' => 'PostController@patch',
        ]);
        // delete a post
        $api->delete('posts/{id}', [
            'as' => 'posts.destroy',
            'uses' => 'PostController@destroy',
        ]);

        // POST COMMENT
        // create a comment
        $api->post('posts/{postId}/comments', [
            'as' => 'posts.comments.store',
            'uses' => 'PostCommentController@store',
        ]);
        $api->put('posts/{postId}/comments/{id}', [
            'as' => 'posts.comments.update',
            'uses' => 'PostCommentController@update',
        ]);
        // delete a comment
        $api->delete('posts/{postId}/comments/{id}', [
            'as' => 'posts.comments.destroy',
            'uses' => 'PostCommentController@destroy',
        ]);
    });
});
