<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Post;

class PostTransformer extends TransformerAbstract
{
    protected $availableInclude = ['user'];

    public function transform(Post $post)
    {
        return $post->toArray();
    }

    public function includeUser(Post $post)
    {
        return $this->item($post->user, new UserTransformer());
    }
}
