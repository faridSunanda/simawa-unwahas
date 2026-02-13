<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }

        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if (!Auth::user()->is_active) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                ]);
            }

            return $this->redirectToDashboard();
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Redirect user to their dashboard based on role.
     */
    protected function redirectToDashboard()
    {
        $user = Auth::user();

        $roleName = session('impersonated_role', $user->role?->name);
        $routeName = $roleName . '.dashboard';

        if (\Route::has($routeName)) {
            return redirect()->route($routeName);
        }

        return redirect("/{$roleName}/dashboard");
    }

    /**
     * Switch role for superadmin (impersonation).
     */
    public function switchRole(Request $request, string $role)
    {
        $user = Auth::user();

        if ($user->role?->name !== 'superadmin') {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini.');
        }
        $validRoles = ['superadmin', 'kemahasiswaan', 'pimpinan', 'dekan', 'kaprodi', 'mahasiswa'];
        if (!in_array($role, $validRoles)) {
            abort(404, 'Role tidak ditemukan.');
        }

        session(['impersonated_role' => $role]);

        return redirect()->route($role . '.dashboard')
            ->with('success', 'Berhasil beralih ke role ' . ucfirst($role));
    }

    /**
     * Reset to original superadmin role.
     */
    public function resetRole(Request $request)
    {
        $user = Auth::user();

        if ($user->role?->name !== 'superadmin') {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini.');
        }
        session()->forget('impersonated_role');

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Berhasil kembali ke role Superadmin');
    }
}

