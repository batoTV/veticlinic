<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user has any of the required roles
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // User doesn't have required role - redirect to appropriate page
        if ($user->hasRole('assistant')) {
            return redirect()->route('pets.index')->with('error', 'You do not have access to this area.');
        }
        
        // For other roles or if no role, redirect to login
        return redirect()->route('login')->with('error', 'Access denied.');
    }
}