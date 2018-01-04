<?php

namespace App\Http\Middleware;

use Closure;

class Log
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
        \Illuminate\Support\Facades\Log::info("logs ");

        return $next($request);
    }
}
