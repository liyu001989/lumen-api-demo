<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Post;
use App\Transformers\PostTransformer;

class PostController extends BaseController
{
    public function index()
    {
        $posts = Post::paginate($this->perPage);

        return $this->response->paginator($posts, new PostTransformer());
    }

    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->response->errorNotFound();
        }

        return $this->response->item($post, new PostTransformer());
    }

    public function store()
    {
        $validator = \Validator::make($this->request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages());
        }

        $user = $this->user();
        $post = new Post();
        $post->user()->associate($user);
        $post->fill($this->request->input());
        $post->save();

        $location = dingo_route('v1', 'posts.show', $post->id);
        // 协议里是这么返回，把资源位置放在header里面
        // 也可以返回200加数据
        return $this->response->created($location);
    }

    public function update($id)
    {
        $validator = \Validator::make($this->request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages());
        }

        $user = $this->user();
        $post = $user->posts()->find($id);

        if (!$post) {
            return $this->response->errorNotFound();
        }

        $post->fill($this->request->input());
        $post->save();

        return $this->response->noContent();
    }

    public function destroy($id)
    {
        $user = $this->user();
        // 找到自己的帖子
        $post = $user->posts()->find($id);

        if (!$post) {
            return $this->response->errorNotFound();
        }

        $post->delete();

        return $this->response->noContent();
    }
}
