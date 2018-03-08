<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class EndResponse
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info('I am terminator handler');

        return $next($request);
    }

    public function terminate($request, $response)
    {
        Log::info('terminate handle and whoole!  i am success!'.$request->url());
    }
}
