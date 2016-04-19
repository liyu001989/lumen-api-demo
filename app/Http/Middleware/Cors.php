<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{

    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Auth\Factory $auth
     */
    public function __construct()
    {
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
        $response = $next($request);

        $response->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
        $response->header('Access-Control-Max-Age', 60);
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Methods', 'GET, HEAD, POST, PUT, DELETE');
        return $response;
    }
 }
