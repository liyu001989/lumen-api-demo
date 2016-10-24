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
        $acceptLanguage = $request->header('accept-language');
        if ($acceptLanguage) {
            $language = current(explode(',', $acceptLanguage));
            app('translator')->setLocale($language);
        }

        // cors 增加对options的处理
        if ($request->isMethod('OPTIONS')) {
            app()->options($request->path(), function() {
                $headers = config('cors');
                return response('', 200, $headers);
            });
        }
    }
}
