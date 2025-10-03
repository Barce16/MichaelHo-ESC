<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureStaff
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && ($user->customer || $user->user_type === 'staff')) {
            return $next($request);
        }

        abort(403);
    }
}
