<?php

namespace App\Models;

class Post extends BaseModel
{
    // 可填充的字段
    protected $fillable = ['title', 'description'];

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
