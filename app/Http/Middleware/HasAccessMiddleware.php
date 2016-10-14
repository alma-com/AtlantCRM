<?php

namespace App\Http\Middleware;

use Closure;

class HasAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
		if (! $request->user()->hasAccess($permission)) {
			abort(404);
        }
        return $next($request);
    }
}
