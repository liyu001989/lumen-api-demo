<?php

namespace ApiDemo\Repositories\Eloquent;

use ApiDemo\Repositories\Contracts\PostCommentRepositoryInterface;

class PostCommentRepository extends BaseRepository implements PostCommentRepositoryInterface
{
    public function model()
    {
        return 'ApiDemo\Models\PostComment';
    }
}
