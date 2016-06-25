<?php

namespace ApiDemo\Repositories;

class PostCommentRepository extends BaseRepository
{
    public function model()
    {
        return 'ApiDemo\Models\PostComment';
    }
}
