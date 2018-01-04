<?php

namespace App\Http\Middleware;

use Closure;

class TerminateTest
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Log::info("termi handle");

        return $next($request);
    }

    public function terminate($request, $response)
    {
        \Log::info("termi terminate");

        return ;
    }
}
