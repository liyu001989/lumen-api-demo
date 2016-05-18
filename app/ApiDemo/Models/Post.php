<?php

namespace ApiDemo\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends BaseModel
{
    use SoftDeletes;
    // 可填充的字段
    protected $fillable = ['title', 'content'];

    protected $casts = ['extra' => 'array'];

    public function user()
    {
        return $this->belongsTo('ApiDemo\Models\User');
    }

    public function comments()
    {
        return $this->hasMany('ApiDemo\Models\PostComment');
    }
}
