<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'siswa') {
                return redirect()->route('siswa.dashboard');
            }
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses Login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Nomor Peserta atau Nama Pengguna wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');
        $username = trim($credentials['username']);
        $password = $credentials['password'];

        // Coba login dengan username
        if (Auth::attempt(['username' => $username, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->status !== 'aktif') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'username' => 'Akun Anda dinonaktifkan oleh Administrator / Proktor.',
                ])->withInput($request->only('username'));
            }

            $user->update(['last_login_at' => now()]);

            if ($user->role === 'siswa') {
                if ($user->siswa) {
                    $user->siswa->update([
                        'status_login' => 'online',
                        'ip_address' => $request->ip(),
                    ]);
                }
                return redirect()->intended(route('siswa.dashboard'));
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'username' => 'Nomor Peserta / Username atau Password tidak sesuai. Silahkan periksa kembali.',
        ])->withInput($request->only('username'));
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->role === 'siswa' && $user->siswa) {
            $user->siswa->update(['status_login' => 'offline']);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sesi.');
    }
}
