<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\UserRepository;

class UserEloquentRepository extends BaseRepository implements UserRepository
{
    protected $model = 'App\Models\User';

    protected $repositoryId = 'repository.user';
}
