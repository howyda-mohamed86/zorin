<?php

namespace Tasawk\Http\Middleware;

use Api;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureThatOrderNotRatedBeforeMiddleware {

    public function handle(Request $request, Closure $next) {
        if (!$request->route('order')->canRate()) {
            return Api::isError(__('validation.api.order_already_rated'));
        }
        return $next($request);
    }
}
