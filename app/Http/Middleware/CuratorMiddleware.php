<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CuratorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->hasRole('curator')) {
            return $next($request);
        }

        return abort(403, 'Unauthorized action.');
    }
}
