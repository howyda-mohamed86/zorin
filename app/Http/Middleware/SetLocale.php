<?php

namespace Tasawk\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale {
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response {
        if (session()->get('locale') == 'ar' || request()->header('Accept-language') == 'ar') {
            config()->set('money.locale', 'ar');
        }
        return $next($request);
    }
}
