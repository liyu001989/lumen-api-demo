<?php

namespace App\Http\Controllers\Api\V1;

use ApiDemo\Models\User;
use ApiDemo\Transformers\UserTransformer;

class UserController extends BaseController
{
    /**
     * @api {get} /users 用户列表(user list)
     * @apiDescription 用户列表(user list)
     * @apiGroup user
     * @apiPermission none
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "data": [
     *         {
     *           "id": 2,
     *           "email": "490554191@qq.com",
     *           "name": "fff",
     *           "created_at": "2015-11-12 10:37:14",
     *           "updated_at": "2015-11-13 02:26:36",
     *           "deleted_at": null
     *         }
     *       ],
     *       "meta": {
     *         "pagination": {
     *           "total": 1,
     *           "count": 1,
     *           "per_page": 15,
     *           "current_page": 1,
     *           "total_pages": 1,
     *           "links": []
     *         }
     *       }
     *     }
     */
    public function index()
    {
        $users = User::paginate($this->perPage);

        return $this->response->paginator($users, new UserTransformer());
    }

    /**
     * @api {post} /user/password 修改密码(edit password)
     * @apiDescription 修改密码(edit password)
     * @apiGroup user
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiParam {String} old_password          旧密码
     * @apiParam {String} password              新密码
     * @apiParam {String} password_confirmation 确认新密码
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 204 No Content
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 400 Bad Request
     *     {
     *         "password": [
     *             "两次输入的密码不一致",
     *             "新旧密码不能相同"
     *         ],
     *         "password_confirmation": [
     *             "两次输入的密码不一致"
     *         ],
     *         "old_password": [
     *             "密码错误"
     *         ]
     *     }
     */
    public function editPassword()
    {
        $user = $this->user();

        $validator = \Validator::make($this->request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed|different:old_password',
            'password_confirmation' => 'required|same:password',
        ]);

        $auth = \Auth::once([
            'email' => $user->email,
            'password' => $this->request->get('old_password'),
        ]);

        if (!$auth) {
            $validator->after(function ($validator) {
                $validator->errors()->add('old_password', trans('auth.invalid_password'));
            });
        }

        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages());
        }

        $user->password = bcrypt($this->request->get('password'));
        $user->password = app('hash')->make($this->request->get('password'));

        $user->save();

        return $this->response->noContent();
    }

    /**
     * @api {get} /users/{id} 某个用户信息(some user's info)
     * @apiDescription 某个用户信息(some user's info)
     * @apiGroup user
     * @apiPermission none
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "data": {
     *         "id": 2,
     *         "email": "490554191@qq.com",
     *         "name": "fff",
     *         "created_at": "2015-11-12 10:37:14",
     *         "updated_at": "2015-11-13 02:26:36",
     *         "deleted_at": null
     *       }
     *     }
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->response->errorNotFound();
        }

        return $this->response->item($user, new UserTransformer());
    }

    /**
     * @api {get} /user 当前用户信息(current user info)
     * @apiDescription 当前用户信息(current user info)
     * @apiGroup user
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "data": {
     *         "id": 2,
     *         "email": 'liyu01989@gmail.com',
     *         "name": "foobar",
     *         "created_at": "2015-09-08 09:13:57",
     *         "updated_at": "2015-09-08 09:13:57",
     *         "deleted_at": null
     *       }
     *     }
     */
    public function userShow()
    {
        return $this->response->item($this->user(), new UserTransformer());
    }

    /**
     * @api {put} /user 修改个人信息(update my info)
     * @apiDescription 修改个人信息(update my info)
     * @apiGroup user
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiParam {String} [name] name
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *        "id": 2,
     *        "email": 'liyu01989@gmail.com',
     *        "name": "ffff",
     *        "created_at": "2015-10-28 07:30:56",
     *        "updated_at": "2015-10-28 09:42:43",
     *        "deleted_at": null,
     *     }
     */
    public function update()
    {
        $validator = \Validator::make($this->request->input(), [
            'email' => 'required|email',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages());
        }

        $user = $this->user();

        $user->fill($this->request->input());

        $user->save();

        return $this->response->item($user, new UserTransformer());
    }

    public function patch()
    {
        $user = $this->user();

    }
}
