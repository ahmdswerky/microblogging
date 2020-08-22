<?php

namespace App\Http\Middleware;

use Closure;

class TwitterAccountMiddleware
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
        if ( auth('api')->check() && !$request->user('api')->has_twitter ) {
            abort(403, __('auth.account_required', ['account' => __('auth.attributes.twitter')]));
        }

        if ( auth()->check() && !$request->user()->has_twitter ) {
            abort(403, __('auth.account_required', ['account' => __('auth.attributes.twitter')]));
        }

        return $next($request);
    }
}
