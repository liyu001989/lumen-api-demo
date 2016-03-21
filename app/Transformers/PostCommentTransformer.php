<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\PostComment;

class PostCommentTransformer extends TransformerAbstract
{
    protected $availableInclude = ['user'];

    public function transform(PostComment $comment)
    {
        return $comment->toArray();
    }

    public function includeUser(PostComment $comment)
    {
        return $this->item($comment->user, new UserTransformer());
    }
}
