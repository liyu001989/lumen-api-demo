<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Http\Request;
use League\Fractal\Pagination\Cursor;
use App\Transformers\PostCommentTransformer;

class PostCommentController extends BaseController
{
    protected $post;

    protected $postComment;

    public function __construct(PostComment $postComment, Post $post)
    {
        $this->postComment = $postComment;

        $this->post = $post;
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
    public function index($postId, Request $request)
    {
        $post = $this->post->find($postId);

        if (! $post) {
            return $this->response->errorNotFound();
        }

        $comments = $this->postComment->where(['post_id' => $postId]);

        $currentCursor = $request->get('cursor');

        if ($currentCursor !== null) {
            $currentCursor = (int) $request->get('cursor', null);
            // how to use previous ??
            // $prevCursor = $request->get('previous', null);
            $limit = $request->get('limit', 10);

            $comments = $comments->where([['id', '>', $currentCursor]])->limit($limit)->get();

            $nextCursor = $comments->last()->id;
            $prevCursor = $currentCursor;
            $cursor = new Cursor($currentCursor, $nextCursor, $prevCursor, $comments->count());

            $cursorPatination = new Cursor($currentCursor, $prevCursor, $nextCursor, $comments->count());

            return $this->response->collection($comments, new PostCommentTransformer(), [], function ($resource) use ($cursorPatination) {
                $resource->setCursor($cursorPatination);
            });
        } else {
            $comments = $comments->paginate();

            return $this->response->paginator($comments, new PostCommentTransformer());
        }
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
        $validator = \Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $post = $this->post->find($postId);

        if (! $post) {
            return $this->response->errorNotFound();
        }

        $user = $this->user();

        $attributes = $request->only('content');
        $attributes['user_id'] = $user->id;
        $attributes['post_id'] = $postId;

        $this->postComment->create($attributes);

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

        $comment = $this->postComment
            ->where(['post_id' => $postId, 'user_id' => $user->id])
            ->find($id);

        if (! $comment) {
            return $this->response->errorNotFound();
        }

        $comment->delete();

        return $this->response->noContent();
    }
}
