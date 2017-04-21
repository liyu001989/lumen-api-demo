<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // 注册mail
        $this->app->singleton('mailer', function ($app) {
            $app->configure('services');
            $app->configure('mail');

            return $app->loadComponent('mail', 'Illuminate\Mail\MailServiceProvider', 'mailer');
        });

        // 处理dingo的findOrFail 问题
        // 或许可以放在  ApiExceptionServiceProvider 这样的地方
        app('api.exception')->register(function (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            abort(404);
        });
    }
}
