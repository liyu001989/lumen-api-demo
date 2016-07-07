<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \ApiDemo\Repositories\Contracts\UserRepositoryContract::class,
            \ApiDemo\Repositories\Eloquent\UserRepository::class
        );
        $this->app->bind(
            \ApiDemo\Repositories\Contracts\PostRepositoryContract::class,
            \ApiDemo\Repositories\Eloquent\PostRepository::class
        );
        $this->app->bind(
            \ApiDemo\Repositories\Contracts\PostCommentRepositoryContract::class,
            \ApiDemo\Repositories\Eloquent\PostCommentRepository::class
        );
    }
}
