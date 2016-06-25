<?php

namespace App\Http\Controllers\Api\V1;

use ApiDemo\Transformers\PostCommentTransformer;
use ApiDemo\Repositories\PostRepository;
use ApiDemo\Repositories\PostCommentRepository;
use Illuminate\Http\Request;

class PostCommentController extends BaseController
{
    protected $postRepository;

    protected $postCommentRepository;

    public function __construct(PostCommentRepository $postCommentRepository, PostRepository $postRepository)
    {
        $this->postCommentRepository = $postCommentRepository;

        $this->postRepository = $postRepository;
    }

    /**
     * @api {get} /posts/{postId}/comments 评论列表(post comment list)
     * @apiDescription 评论列表(post comment list)
     * @apiGroup Post
     * @apiPermission none
     * @apiParam {String='user'} include  include
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 200 OK
     *   {
     *    "data": [
     *      {
     *        "id": 1,
     *        "post_id": 1,
     *        "user_id": 1,
     *        "reply_user_id": 0,
     *        "content": "foobar",
     *        "created_at": "2016-04-06 14:51:34",
     *        "user": {
     *          "data": {
     *            "id": 1,
     *            "email": "foo@bar.com",
     *            "name": "foobar",
     *            "avatar": "",
     *            "created_at": "2016-01-28 07:23:37",
     *            "updated_at": "2016-01-28 07:24:05",
     *            "deleted_at": null
     *          }
     *        }
     *      },
     *      {
     *        "id": 2,
     *        "post_id": 1,
     *        "user_id": 1,
     *        "reply_user_id": 0,
     *        "content": "foobar1",
     *        "created_at": "2016-04-06 15:10:22",
     *        "user": {
     *          "data": {
     *            "id": 1,
     *            "email": "foo@bar.com",
     *            "name": "foobar",
     *            "avatar": "",
     *            "created_at": "2016-01-28 07:23:37",
     *            "updated_at": "2016-01-28 07:24:05",
     *            "deleted_at": null
     *          }
     *        }
     *      },
     *      {
     *        "id": 3,
     *        "post_id": 1,
     *        "user_id": 1,
     *        "reply_user_id": 0,
     *        "content": "foobar2",
     *        "created_at": "2016-04-06 15:10:23",
     *        "user": {
     *          "data": {
     *            "id": 1,
     *            "email": "foo@bar.com",
     *            "name": "foobar",
     *            "avatar": "",
     *            "created_at": "2016-01-28 07:23:37",
     *            "updated_at": "2016-01-28 07:24:05",
     *            "deleted_at": null
     *          }
     *        }
     *      }
     *    ],
     *    "meta": {
     *      "pagination": {
     *        "total": 3,
     *        "count": 3,
     *        "per_page": 15,
     *        "current_page": 1,
     *        "total_pages": 1,
     *        "links": []
     *      }
     *    }
     *  }
     */
    public function index($postId)
    {
        $post = $this->postRepository->find($postId);

        if (!$post) {
            return $this->response->errorNotFound();
        }

        // 研究一下cursor，这里应该无限下拉
        $comments = $this->postCommentRepository
            ->where(['post_id'=>$postId])
            ->paginate();

        return $this->response->paginator($comments, new PostCommentTransformer());
    }

    /**
     * @api {post} /posts/{postId}/comments 发布评论(create post comment)
     * @apiDescription 发布评论(create post comment)
     * @apiGroup Post
     * @apiPermission jwt
     * @apiParam {String} content  post content
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 201 Created
     */
    public function store($postId, Request $request)
    {
        $post = $this->postRepository->find($postId);

        if (!$post) {
            return $this->response->errorNotFound();
        }

        $validator = \Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages());
        }

        $user = $this->user();

        $attributes = $request->only('content');
        $attributes['user_id'] = $user->id;
        $attributes['post_id'] = $postId;

        $this->postCommentRepository->create($attributes);

        return $this->response->created();
    }

    /**
     * @api {delete} /posts/{postId}/comments/{id} 删除评论(delete post comment)
     * @apiDescription 删除评论(delete post comment)
     * @apiGroup Post
     * @apiPermission jwt
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 204 NO CONTENT
     */
    public function destroy($postId, $id)
    {
        $user = $this->user();

        $comment = $this->postCommentRepository
            ->where(['post_id'=>$postId, 'user_id'=>$user->id])
            ->find($id);

        if (!$comment) {
            return $this->response->errorNotFound();
        }

        $this->postCommentRepository->destroy($id);

        return $this->response->noContent();
    }
}
