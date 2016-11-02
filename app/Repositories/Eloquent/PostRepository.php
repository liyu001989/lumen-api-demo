<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\PostRepositoryContract;

class PostRepository extends BaseRepository implements PostRepositoryContract
{
    public function model()
    {
        return 'App\Models\Post';
    }
}
