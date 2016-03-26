<?php

namespace App\Http\Controllers\Api\V1;

use App\Transformers\PostTransformer;
use App\Models\Post;

class PostCommentController extends BaseController
{
    public function index($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->response->errorNotFound();
        }

        // 研究一下cursor，这里应该无限下拉
        $comment = $post->comments()->paginate();

        return $this->response->paginate($posts, new PostTransformer());
    }
}
