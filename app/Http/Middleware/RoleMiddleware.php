<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $actualRole = $user->role?->name;

        // Impersonating role
        $impersonatedRole = session('impersonated_role');

        // Superadmin with impersonation
        if ($actualRole === 'superadmin' && $impersonatedRole) {
            foreach ($roles as $role) {
                if ($impersonatedRole === $role) {
                    return $next($request);
                }
            }
            if (in_array('superadmin', $roles)) {
                return $next($request);
            }
        }

        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}

