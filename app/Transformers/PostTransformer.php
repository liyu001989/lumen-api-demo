<?php

namespace App\Transformers;

use App\Models\Post;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'comments', 'recentComments'];

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
        if ($params->get('limit')) {
            $limit = (array) $params->get('limit');
            $limit = (int) current($limit);
        }

        $comments = $post->comments()->limit($limit)->get();
        $total = $post->comments()->count();

        return $this->collection($comments, new PostCommentTransformer())->setMeta(['total' => $total]);
    }

    public function includeRecentComments(Post $post, ParamBag $params = null)
    {
        if ($limit = $params->get('limit')) {
            $limit = (int) current($limit);
        } else {
            $limit = 15;
        }

        $comments = $post->recentComments($limit)->get();

        return $this->collection($comments, new PostCommentTransformer())
            ->setMeta(['limit' => $limit]);
    }
}
