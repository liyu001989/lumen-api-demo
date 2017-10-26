<?php

namespace App\Models;

class Comment extends BaseModel
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public static function boot()
    {
        static::created(function ($comment) {
            $comment->post->increment('comment_count');
        });

        static::deleted(function ($comment) {
            $comment->post->decrement('comment_count');
        });

        parent::boot();
    }
}
