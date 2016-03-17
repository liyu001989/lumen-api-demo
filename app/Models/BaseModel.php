<?php
/**
 * @access    public
 * @author    liyu
 * @desc      基础模型
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $casts = ['created_at', 'updated_at'];
}
