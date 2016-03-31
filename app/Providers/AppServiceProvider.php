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
        $this->app->singleton('mailer', function ($app) {
            $app->configure('services');

            return $app->loadComponent('mail', 'Illuminate\Mail\MailServiceProvider', 'mailer');
        });

        // set locale
        $request = app('request');
        $language = $request->get('language') ?: $request->header('accept-language') ?: 'en';
        app('translator')->setLocale($language);
    }
}
