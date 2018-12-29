<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Events\QueryExecuted;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // register mail
        $this->app->singleton('mailer', function ($app) {
            $app->configure('services');
            $app->configure('mail');

            return $app->loadComponent('mail', 'Illuminate\Mail\MailServiceProvider', 'mailer');
        });

        // dingo findOrFail
        app('api.exception')->register(function (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            abort(404);
        });

        // sql log
        if (app()->environment('local')) {
            \DB::listen(function (QueryExecuted $query) {
                $sqlWithPlaceholders = str_replace(['%', '?'], ['%%', '%s'], $query->sql);
                $bindings = $query->connection->prepareBindings($query->bindings);
                $pdo = $query->connection->getPdo();
                \Log::info(vsprintf($sqlWithPlaceholders, array_map([$pdo, 'quote'], $bindings)));
            });
        }
    }
}
