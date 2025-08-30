<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        if ($user->hasRole('assistant')) {
            return redirect()->route('pets.index')->with('error', 'You do not have access to this area.');
        }
        
        return redirect()->route('login')->with('error', 'Access denied.');
    }
}