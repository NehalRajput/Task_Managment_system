<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access. Super admin privileges required.');
        }

        return $next($request);
    }
} 