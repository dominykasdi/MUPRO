<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LearnerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->hasRole('learner')) {
            return $next($request);
        }
        
        return abort(403, 'Unauthorized action.');
    }
}
