<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        // Ambil user yang sedang login
        $user = Auth::user();
        $roles = explode('|', $role); // Mendukung multiple roles (contoh: 'admin|editor')

        // Periksa apakah user memiliki salah satu role yang diminta
        if (!$user->roles()->whereIn('name', $roles)->exists()) {
            abort(403, 'Unauthorized'); // Abort jika tidak memiliki role
        }

        // Lanjutkan request jika valid
        return $next($request);
    }
}
