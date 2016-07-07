<?php

namespace ApiDemo\Repositories\Eloquent;

use ApiDemo\Repositories\Contracts\UserRepositoryContract;

class UserRepository extends BaseRepository implements UserRepositoryContract
{
    public function model()
    {
        return 'ApiDemo\Models\User';
    }
}
