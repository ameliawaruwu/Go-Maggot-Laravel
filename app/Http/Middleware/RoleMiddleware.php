<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$roles  role yang diizinkan: admin, user, dll
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // kalau belum login, lempar ke login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // pastikan user punya kolom 'role'
        if (!in_array($user->role, $roles)) {
            abort(403); // Forbidden
        }

        return $next($request);
    }
}
