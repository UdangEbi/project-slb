<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekLogin
{
    /**
     * Blokir akses kalau belum login (session 'login' belum true).
     * Kalau parameter $role diisi (misal 'kasir' atau 'admin'), user
     * yang sudah login tapi role-nya beda juga akan ditolak (403).
     *
     * Contoh pemakaian di routes:
     *   ->middleware('cek.login')          -> wajib login (role bebas)
     *   ->middleware('cek.login:kasir')    -> wajib login sebagai kasir
     *   ->middleware('cek.login:admin')    -> wajib login sebagai admin
     */
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        if (!session('login')) {
            return redirect()
                ->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($role !== null && session('role') !== $role) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}