<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\PostCommentRepositoryContract;

class PostCommentRepository extends BaseRepository implements PostCommentRepositoryContract
{
    public function model()
    {
        return 'App\Models\PostComment';
    }
}
