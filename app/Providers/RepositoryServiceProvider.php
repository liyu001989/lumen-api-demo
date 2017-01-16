<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \App\Repositories\Contracts\UserRepository::class,
            \App\Repositories\Eloquent\UserEloquentRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\PostRepository::class,
            \App\Repositories\Eloquent\PostEloquentRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\PostCommentRepository::class,
            \App\Repositories\Eloquent\PostCommentEloquentRepository::class
        );
    }
}
