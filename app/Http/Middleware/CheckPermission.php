<?php

namespace App\Http\Middleware;

use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $name = $request->route()->getName();
        if ($name && !auth()->guard('api')->user()->can($name)) {
            abort(403, 'You don\'t have permission to do this');
        }

        return $next($request);
    }
}
