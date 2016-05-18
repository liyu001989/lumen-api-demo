<?php

namespace ApiDemo\Models;

class PostComment extends BaseModel
{
    public function user()
    {
        return $this->belongsTo('ApiDemo\Models\User');
    }

    public function post()
    {
        return $this->belongsTo('ApiDemo\Models\Post');
    }
}
