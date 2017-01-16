<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\PostRepository;

class PostEloquentRepository extends BaseRepository implements PostRepository
{
    protected $model = 'App\Models\Post';

    protected $repositoryId = 'repository.post';
}
