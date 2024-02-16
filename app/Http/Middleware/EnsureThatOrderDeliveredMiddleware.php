<?php

namespace Tasawk\Http\Middleware;

use Api;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tasawk\Enum\OrderStatus;

class EnsureThatOrderDeliveredMiddleware {

    public function handle(Request $request, Closure $next) {
        if ($request->route('order')->status->value != OrderStatus::DELIVERED->value) {
            return Api::isError(__('validation.api.order_not_delivered_yet'));
        }
        return $next($request);
    }
}
