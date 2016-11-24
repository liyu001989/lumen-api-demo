<?php

namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Routing\Router;
use Illuminate\Contracts\Auth\Factory as Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Auth\Factory $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // 验证token是来自对应的模型
        $payload = \Auth::getPayload();
        if ($payload->get('type') != $guard) {
            return response('Forbidden', 403);
        }

        if (! $this->auth->check(false)) {
            return response('Unauthorized.', 401);
        }

        // 自己try catch
        $user = $this->auth->guard($guard)->authenticate();

        app('Dingo\Api\Auth\Auth')->setUser($user);
        return $next($request);
    }
}
