<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\UserRepositoryContract;

class UserRepository extends BaseRepository implements UserRepositoryContract
{
    public function model()
    {
        return 'App\Models\User';
    }
}
