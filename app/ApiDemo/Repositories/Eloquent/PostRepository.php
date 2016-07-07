<?php

namespace ApiDemo\Repositories\Eloquent;

use ApiDemo\Repositories\Contracts\PostRepositoryContract;

class PostRepository extends BaseRepository implements PostRepositoryContract
{
    public function model()
    {
        return 'ApiDemo\Models\Post';
    }
}
