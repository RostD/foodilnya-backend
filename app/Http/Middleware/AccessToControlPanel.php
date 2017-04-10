<?php

namespace App\Http\Middleware;

use Closure;

class AccessToControlPanel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->role->sys_name != 'client')
            return $next($request);
        abort(403);
    }
}
