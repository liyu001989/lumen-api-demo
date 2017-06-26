<?php

namespace App\Transformers;

use App\Models\Comment;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user'];

    public function transform(Comment $comment)
    {
        return $comment->attributesToArray();
    }

    public function includeUser(Comment $comment)
    {
        if (! ($comment->user)) {
            return $this->null();
        }

        return $this->item($comment->user, new UserTransformer());
    }
}
