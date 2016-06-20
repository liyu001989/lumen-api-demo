<?php

namespace ApiDemo\Repositories;

class PostRepository extends BaseRepository
{
    public function model()
    {
        return 'ApiDemo\Models\Post';
    }
}
