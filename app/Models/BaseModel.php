<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $guarded = ['id', 'updated_at'];

    protected $casts = ['created_at', 'updated_at'];

    protected $hidden = ['updated_at', 'deleted_at', 'extra'];
}
