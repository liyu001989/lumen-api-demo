<?php

namespace App\Http\Controllers\Api\V1;

use ApiDemo\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    protected $userRepository;

    protected $auth;

    public function __construct(UserRepositoryInterface $userRepository, AuthManager $auth)
    {
        $this->userRepository = $userRepository;

        $this->auth = $auth;
    }

    /**
     * @api {post} /auth/login 登录(login)
     * @apiDescription 登录(login)
     * @apiGroup Auth
     * @apiPermission none
     * @apiParam {Email} email     邮箱
     * @apiParam {String} password  密码
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL21vYmlsZS5kZWZhcmEuY29tXC9hdXRoXC90b2tlbiIsImlhdCI6IjE0NDU0MjY0MTAiLCJleHAiOiIxNDQ1NjQyNDIxIiwibmJmIjoiMTQ0NTQyNjQyMSIsImp0aSI6Ijk3OTRjMTljYTk1NTdkNDQyYzBiMzk0ZjI2N2QzMTMxIn0.9UPMTxo3_PudxTWldsf4ag0PHq1rK8yO9e5vqdwRZLY
     *     }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "UserNotFound"
     *     }
     */
    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages());
        }

        $credentials = $request->only('email', 'password');

        // 验证失败返回403
        if (! $token = $this->auth->attempt($credentials)) {
            $this->response->errorForbidden(trans('auth.incorrect'));
        }

        return $this->response->array(compact('token'));
    }

    /**
     * @api {post} /auth/token/refresh 刷新token(refresh token)
     * @apiDescription 刷新token(refresh token)
     * @apiGroup Auth
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiHeader {String} Authorization 用户旧的jwt-token, value已Bearer开头
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL21vYmlsZS5kZWZhcmEuY29tXC9hdXRoXC90b2tlbiIsImlhdCI6IjE0NDU0MjY0MTAiLCJleHAiOiIxNDQ1NjQyNDIxIiwibmJmIjoiMTQ0NTQyNjQyMSIsImp0aSI6Ijk3OTRjMTljYTk1NTdkNDQyYzBiMzk0ZjI2N2QzMTMxIn0.9UPMTxo3_PudxTWldsf4ag0PHq1rK8yO9e5vqdwRZLY"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         token: 9UPMTxo3_PudxTWldsf4ag0PHq1rK8yO9e5vqdwRZLY.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL21vYmlsZS5kZWZhcmEuY29tXC9hdXRoXC90b2tlbiIsImlhdCI6IjE0NDU0MjY0MTAiLCJleHAiOiIxNDQ1NjQyNDIxIiwibmJmIjoiMTQ0NTQyNjQyMSIsImp0aSI6Ijk3OTRjMTljYTk1NTdkNDQyYzBiMzk0ZjI2N2QzMTMxIn0.eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9
     *     }
     */
    public function refreshToken()
    {
        $token = $this->auth->refresh();

        return $this->response->array(compact('token'));
    }

    /**
     * @api {post} /auth/register 注册(register)
     * @apiDescription 注册(register)
     * @apiGroup Auth
     * @apiPermission none
     * @apiVersion 0.1.0
     * @apiParam {Email}  email   email[unique]
     * @apiParam {String} password   password
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL21vYmlsZS5kZWZhcmEuY29tXC9hdXRoXC90b2tlbiIsImlhdCI6IjE0NDU0MjY0MTAiLCJleHAiOiIxNDQ1NjQyNDIxIiwibmJmIjoiMTQ0NTQyNjQyMSIsImp0aSI6Ijk3OTRjMTljYTk1NTdkNDQyYzBiMzk0ZjI2N2QzMTMxIn0.9UPMTxo3_PudxTWldsf4ag0PHq1rK8yO9e5vqdwRZLY
     *     }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 400 Bad Request
     *     {
     *         "email": [
     *             "该邮箱已被他人注册"
     *         ],
     *     }
     */
    public function register(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages());
        }

        $email = $request->get('email');
        $password = $request->get('password');

        //$user = new User();
        $attributes = [
            'email' => $email,
            'password' => app('hash')->make($password),
        ];

        $user = $this->userRepository->create($attributes);

        // 用户注册事件
        $token = $this->auth->fromUser($user);

        return $this->response->array(compact('token'));
    }
}
