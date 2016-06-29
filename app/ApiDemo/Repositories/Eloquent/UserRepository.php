<?php

namespace ApiDemo\Repositories\Eloquent;

use ApiDemo\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function model()
    {
        return 'ApiDemo\Models\User';
    }
}
