<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Post;
use App\Transformers\PostTransformer;

class PostController extends BaseController
{
    /**
     * @api {get} /posts 帖子列表(post list)
     * @apiDescription 帖子列表(post list)
     * @apiGroup Post
     * @apiPermission none
     * @apiParam {String='comments:limit(x)','user'} [include]  include
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 200 OK
     *   {
     *     "data": [
     *       {
     *         "id": 1,
     *         "user_id": 3,
     *         "title": "foo",
     *         "content": "",
     *         "created_at": "2016-03-30 15:36:30",
     *         "user": {
     *           "data": {
     *             "id": 3,
     *             "email": "foo@bar.com1",
     *             "name": "",
     *             "avatar": "",
     *             "created_at": "2016-03-30 15:34:01",
     *             "updated_at": "2016-03-30 15:34:01",
     *             "deleted_at": null
     *           }
     *         },
     *         "comments": {
     *           "data": [],
     *           "meta": {
     *             "total": 0
     *           }
     *         }
     *       }
     *     ],
     *     "meta": {
     *       "pagination": {
     *         "total": 2,
     *         "count": 2,
     *         "per_page": 15,
     *         "current_page": 1,
     *         "total_pages": 1,
     *         "links": []
     *       }
     *     }
     *   }
     */
    public function index()
    {
        $validator = \Validator::make($this->request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages());
        }
        $posts = Post::paginate($this->perPage);

        return $this->response->paginator($posts, new PostTransformer());
    }

    /**
     * @api {get} /posts/{id} 帖子详情(post detail)
     * @apiDescription 帖子详情(post detail)
     * @apiGroup Post
     * @apiPermission none
     * @apiParam {String='comments','user'} [include]  include
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 200 OK
     *   {
     *     "data": {
     *       "id": 1,
     *       "user_id": 3,
     *       "title": "foo",
     *       "content": "",
     *       "created_at": "2016-03-30 15:36:30",
     *       "user": {
     *         "data": {
     *           "id": 3,
     *           "email": "foo@bar.com1",
     *           "name": "",
     *           "avatar": "",
     *           "created_at": "2016-03-30 15:34:01",
     *           "updated_at": "2016-03-30 15:34:01",
     *           "deleted_at": null
     *         }
     *       },
     *       "comments": {
     *         "data": [
     *           {
     *             "id": 1,
     *             "post_id": 1,
     *             "user_id": 1,
     *             "reply_user_id": 0,
     *             "content": "foobar",
     *             "created_at": "2016-04-06 14:51:34"
     *           }
     *         ],
     *         "meta": {
     *           "total": 1
     *         }
     *       }
     *     }
     *   }
     */
    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->response->errorNotFound();
        }

        return $this->response->item($post, new PostTransformer());
    }

    /**
     * @api {post} /posts 发布帖子(create post)
     * @apiDescription 发布帖子(create post)
     * @apiGroup Post
     * @apiPermission jwt
     * @apiParam {String} title  post title
     * @apiParam {String} content  post content
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 201 Created
     */
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

    /**
     * @api {put} /posts/{id} 修改帖子(update post)
     * @apiDescription 修改帖子(update post)
     * @apiGroup Post
     * @apiPermission jwt
     * @apiParam {String} title  post title
     * @apiParam {String} content  post content
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 204 NO CONTENT
     */
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

    /**
     * @api {delete} /posts/{id} 删除帖子(delete post)
     * @apiDescription 删除帖子(delete post)
     * @apiGroup Post
     * @apiPermission jwt
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 204 NO CONTENT
     */
    public function destroy($id)
    {
        $user = $this->user();
        // find my post
        $post = $user->posts()->find($id);

        if (!$post) {
            return $this->response->errorNotFound();
        }

        $post->delete();

        return $this->response->noContent();
    }
}
