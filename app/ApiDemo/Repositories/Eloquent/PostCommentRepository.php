<?php

namespace ApiDemo\Repositories\Eloquent;

use ApiDemo\Repositories\Contracts\PostCommentRepositoryContract;

class PostCommentRepository extends BaseRepository implements PostCommentRepositoryContract
{
    public function model()
    {
        return 'ApiDemo\Models\PostComment';
    }
}
