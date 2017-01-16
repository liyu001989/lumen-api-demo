<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\PostCommentRepository;

class PostCommentEloquentRepository extends BaseRepository implements PostCommentRepository
{
    protected $model = 'App\Models\PostComment';

    protected $repositoryId = 'repository.postComment';
}
