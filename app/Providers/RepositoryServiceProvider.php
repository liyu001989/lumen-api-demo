<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \ApiDemo\Repositories\Contracts\UserRepositoryInterface::class,
            \ApiDemo\Repositories\Eloquent\UserRepository::class
        );
        $this->app->bind(
            \ApiDemo\Repositories\Contracts\PostRepositoryInterface::class,
            \ApiDemo\Repositories\Eloquent\PostRepository::class
        );
        $this->app->bind(
            \ApiDemo\Repositories\Contracts\PostCommentRepositoryInterface::class,
            \ApiDemo\Repositories\Eloquent\PostCommentRepository::class
        );
    }
}
