<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\ResponseFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        # bug https://github.com/tymondesigns/jwt-auth/pull/532
        $this->app->singleton('Illuminate\Contracts\Routing\ResponseFactory', function ($app) {
            return new ResponseFactory($app['Illuminate\Contracts\View\Factory'], $app['Illuminate\Routing\Redirector']);
        });
    }
}
