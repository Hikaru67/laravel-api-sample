<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Config;

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
        if (
            $name
            && config('constant.authorization')
            && Auth::guard('api')->check()
            && ! Auth::guard('api')->user()->can($name)
        ) {
            return response()->json(['message' => 'You don\'t have permission to do this'], 403);
        }

        return $next($request);
    }
}
