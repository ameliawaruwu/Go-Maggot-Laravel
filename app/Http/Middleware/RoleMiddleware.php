<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // 1. Cek Login (Pastikan user ada)
        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated. Silakan login terlebih dahulu.'
            ], 401);
        }

        if ($user->role === 'admin') {
            return $next($request);
        }
        
        if (! in_array($user->role, $roles)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses Ditolak. Anda tidak memiliki izin.'
            ], 403);
        }

        return $next($request);
    }
}