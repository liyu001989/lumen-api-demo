<?php

/**
 * @author    liyu
 * @desc      基础模型
 */

namespace ApiDemo\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $casts = ['created_at', 'updated_at'];

    protected $hidden = ['updated_at', 'deleted_at', 'extra'];
}
