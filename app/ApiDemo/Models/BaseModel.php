<?php

/**
 * @author    liyu
 * @desc      基础模型
 */
namespace ApiDemo\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $guarded = ['id', 'updated_at'];

    protected $casts = ['created_at', 'updated_at'];

    protected $hidden = ['updated_at', 'deleted_at', 'extra'];
}
