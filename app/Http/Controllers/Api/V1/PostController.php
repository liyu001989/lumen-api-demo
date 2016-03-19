<?php

namespace App\Http\Controllers\Api\V1;

use App\Transformers\PostTransformer;
use App\Models\Post;

class PostController extends BaseController
{
    public function index()
    {
        $posts = Post::paginate($this->perPage);

        return $this->response->paginate($posts, new PostTransformer());
    }

    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->response->errorNotFound();
        }

        return $this->response->item($post, new PostTransformer());
    }
}
