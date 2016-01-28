<?php

/**
 * 当前用户控制器
 */
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Transformer\UserTransformer;
use App\Models\User;

class UserController extends BaseController
{
    /**
     * @api {post} /user/password 修改密码
     * @apiDescription 修改密码
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
            'old_password'          => 'required',
            'password'              => 'required|confirmed|different:old_password',
            'password_confirmation' => 'required|same:password',
        ], [
            'password.confirmed'         => '两次输入的密码不一致',
            'password_confirmation.same' => '两次输入的密码不一致',
            'password.different'         => '新旧密码不能相同',
        ]);

        $auth = \Auth::once([
            'email'    => $user->email,
            'password' => $this->request->get('old_password'),
        ]);

        if (!$auth) {
            $validator->after(function ($validator) {
                $validator->errors()->add('old_password', '密码错误');
            });
        }

        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages());
        }

        $user->password = bcrypt($this->request->get('password'));

        $user->save();

        return $this->response->noContent();
    }

    /**
     * @api {get} /user 当前用户信息
     * @apiDescription 当前用户信息
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
    public function show()
    {
        return $this->response->item($this->user(), new UserTransformer);
    }

    /**
     * @api {put} /user 修改个人信息
     * @apiDescription 修改个人信息
     * @apiGroup user
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiParam {String} [name] 姓名
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
        $user = $this->user();

        $user->fill($this->request->input());

        $user->save();

        return $this->response->item($user, new UserTransformer);
    }
}
