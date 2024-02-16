<?php

namespace Tasawk\Http\Middleware;

use Api;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tasawk\Exceptions\APIException;
use Tasawk\Models\Branch;

class EnsureThatBranchPresentInRequestHeader {

    public function handle(Request $request, Closure $next) {
        if (!$request->hasHeader('x-branch-id') || !Branch::where('id', $request->header('x-branch-id'))->exists()) {
            return Api::isError(__("x-branch-id header is missing"));

        }
        return $next($request);
    }
}
