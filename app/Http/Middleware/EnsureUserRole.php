<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Memastikan pengguna memiliki peran yang diizinkan untuk mengakses rute.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles, true)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses ditolak.'], 403);
            }

            if ($user && $user->role === 'siswa') {
                return redirect()->route('siswa.dashboard')->with('error', 'Akses ditolak: halaman khusus operator/proktor.');
            }

            if ($user) {
                return redirect()->route('admin.dashboard')->with('error', 'Halaman ini khusus untuk peserta ujian.');
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
