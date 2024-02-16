<?php

namespace Tasawk\Http\Middleware;

use Api;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureThatAddressBelongToAuthUserMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next) {
        if ($request->route('address')->user_id != auth()->id()) {
            return Api::setMessage('):')->setStatus(401);
        }
        return $next($request);
    }
}
