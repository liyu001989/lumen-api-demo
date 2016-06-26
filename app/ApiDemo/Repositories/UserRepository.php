<?php

namespace ApiDemo\Repositories;

class UserRepository extends BaseRepository
{
    public function model()
    {
        return 'ApiDemo\Models\User';
    }
}
