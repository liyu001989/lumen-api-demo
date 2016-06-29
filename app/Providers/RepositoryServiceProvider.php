<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('ApiDemo\Repositories\Contracts\UserRepositoryInterface', 'ApiDemo\Repositories\Eloquent\UserRepository');
        $this->app->bind('ApiDemo\Repositories\Contracts\PostRepositoryInterface', 'ApiDemo\Repositories\Eloquent\PostRepository');
        $this->app->bind('ApiDemo\Repositories\Contracts\PostCommentRepositoryInterface', 'ApiDemo\Repositories\Eloquent\PostCommentRepository');
    }
}
