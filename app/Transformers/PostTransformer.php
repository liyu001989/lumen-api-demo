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
        if (! $post->user) {
            return $this->null();
        }

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

        return $this->collection($comments, new CommentTransformer())
            ->setMeta([
                'limit' => $limit,
                'count' => $comments->count(),
            ]);
    }

    /**
     * 列表加载列表不是一件很好的事情，因为dingo的预加载机制
     * 自动预加载include的参数, 所以会读取所有帖子的所有评论
     * 所以可以增加一个recentComments, 增加一个limit条件
     * 但是依然不够完美.
     */
    public function includeRecentComments(Post $post)
    {
        // 倒叙
        $comments = $post->recentComments->sortByDesc('id');

        return $this->collection($comments, new CommentTransformer())
            ->setMeta([
                'count' => $comments->count(),
            ]);
    }
}
