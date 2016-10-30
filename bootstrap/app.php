<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

// phpunit 报错？？？
$app->withFacades();

$app->withEloquent();

//config
// jwt
$app->configure('jwt');
// mail
$app->configure('mail');
// cors 配置
$app->configure('cors');
$app->configure('services');

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->middleware([
]);

$app->routeMiddleware([
    'auth' => App\Http\Middleware\Authenticate::class,
    'cors' => App\Http\Middleware\Cors::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
$app->register(Illuminate\Redis\RedisServiceProvider::class);
//$app->register(App\Providers\EventServiceProvider::class);
//
// 注入repository
$app->register(App\Providers\RepositoryServiceProvider::class);
// dingo/api
$app->register(Dingo\Api\Provider\LumenServiceProvider::class);
//jwt
$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);
// email 或者放在 provider里面
$app->register(Illuminate\Mail\MailServiceProvider::class);

app('Dingo\Api\Auth\Auth')->extend('jwt', function ($app) {
    return new Dingo\Api\Auth\Provider\JWT($app['Tymon\JWTAuth\JWTAuth']);
});

//Injecting auth
$app->singleton(Illuminate\Auth\AuthManager::class, function ($app) {
    return $app->make('auth');
});

// 设置 transformer 的 serializer
//$app['Dingo\Api\Transformer\Factory']->setAdapter(function ($app) {
    //$fractal = new League\Fractal\Manager;
    //$serializer = new League\Fractal\Serializer\ArraySerializer();
    ////$serializer = new League\Fractal\Serializer\JsonApiSerializer();
    ////$serializer = new ApiDemo\Serializers\NoDataArraySerializer();
    //$fractal->setSerializer($serializer);
    //return new Dingo\Api\Transformer\Adapter\Fractal($fractal);
//});


/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__.'/../app/Http/routes.php';
});

return $app;
