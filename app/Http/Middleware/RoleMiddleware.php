<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    protected $redirects = [
        'admin' => 'admin/dashboard',
        'tutor' => 'tutor/dashboard',
        'user' => 'user/dashboard',
    ];

    public function handle(Request $request, Closure $next, $role)
    {
        // Check if the user has the specified role
        if (!auth()->user() || !auth()->user()->hasRole($role)) {
            // If not, deny access
            abort(403, 'Access denied');
        }

        // Allow the request to proceed
        return $next($request);
    }
}
