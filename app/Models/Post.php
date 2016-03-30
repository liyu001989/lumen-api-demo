<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends BaseModel
{
    use SoftDeletes;
    // 可填充的字段
    protected $fillable = ['title', 'content'];

    protected $casts = ['extra' => 'array'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\PostComment');
    }
}
