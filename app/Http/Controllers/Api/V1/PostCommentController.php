<?php

namespace App\Http\Controllers\Api\V1;

use App\Transformers\PostTransformer;
use App\Models\Post;

class PostCommentController extends BaseController
{
    public function index($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return $this->response->errorNotFound();
        }

        // 研究一下cursor，这里应该无限下拉
        $comment = $post->comments()->paginate();

        return $this->response->paginate($posts, new PostTransformer());
    }

    public function store($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return $this->response->errorNotFound();
        }
    }

    public function destroy($postId, $id)
    {
        $comment = Post::find($postId)->comments()->find($id);

        $user = $this->user();
        $comment = $user->postComments()
            ->where(['post_id' => $postId, 'id' => $id])
            ->first();

        if (!$comment) {
            return $this->response->errorNotFound();
        }

        $comment->delete();

        return $this->response->noContent();
    }
}
