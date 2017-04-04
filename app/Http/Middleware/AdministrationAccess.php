<?php

namespace App\Http\Middleware;

use Closure;

class AdministrationAccess
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
        if ($request->user()->role->sys_name == 'admin')
            return $next($request);
        return redirect('403');
    }
}
