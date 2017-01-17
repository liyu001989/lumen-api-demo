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

        // set locale
        $request = app('request');
        if ($request->isMethod('OPTIONS'))
        {
          app()->options($request->path(), function() { return response('', 200); });
        }
        $acceptLanguage = $request->header('accept-language');
        if ($acceptLanguage) {
            $language = current(explode(',', $acceptLanguage));
            app('translator')->setLocale($language);
        }
    }
}
