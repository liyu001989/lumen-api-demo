<?php

namespace App\Transformers;

use App\Models\PostComment;
use League\Fractal\TransformerAbstract;

class PostCommentTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user'];

    public function transform(PostComment $comment)
    {
        return $comment->attributesToArray();
    }

    public function includeUser(PostComment $comment)
    {
        return $this->item($comment->user, new UserTransformer());
    }
}
