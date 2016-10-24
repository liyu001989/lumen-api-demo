<?php

namespace ApiDemo\Transformers;

use ApiDemo\Models\Post;
use League\Fractal\TransformerAbstract;
use League\Fractal\ParamBag;

class PostTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'comments'];

    public function transform(Post $post)
    {
        return $post->attributesToArray();
    }

    public function includeUser(Post $post)
    {
        return $this->item($post->user, new UserTransformer());
    }

    public function includeComments(Post $post, ParamBag $params = null)
    {
        $limit = 10;
        if ($params) {
            $limit = (array) $params->get('limit');
            $limit = (int) current($limit);
        }

        $comments = $post->comments()->limit($limit)->get();
        $total = $post->comments()->count();

        return $this->collection($comments, new PostCommentTransformer())->setMeta(['total' => $total]);
    }
}
