<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
        }

        if (!$user->is_active) {
            return back()->withErrors(['username' => 'Akun Anda tidak aktif. Hubungi administrator.']);
        }

        Auth::login($user, $request->boolean('remember'));

        return $this->redirectByRole($user);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'username'     => 'required|string|max:100|unique:users,username',
            'email'        => 'nullable|email|max:150|unique:users,email',
            'no_hp'        => 'nullable|string|max:25',
            'password'     => 'required|string|min:6|confirmed',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'username.required'     => 'Username wajib diisi.',
            'username.unique'       => 'Username sudah digunakan.',
            'password.confirmed'    => 'Konfirmasi password tidak sesuai.',
            'password.min'          => 'Password minimal 6 karakter.',
        ]);

        $klienRole = UserRole::where('nama_role', 'klien')->first();

        $user = User::create([
            'role_id'      => $klienRole->id,
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'email'        => $request->email,
            'no_hp'        => $request->no_hp,
            'password'     => Hash::make($request->password),
            'is_active'    => true,
        ]);

        Auth::login($user);

        return redirect()->route('klien.dashboard')->with('success', 'Registrasi berhasil! Selamat datang di Ahmad Handoko Law Office.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda berhasil keluar dari sistem.');
    }

    private function redirectByRole(User $user)
    {
        return match ($user->role->nama_role) {
            'sekretaris' => redirect()->route('admin.dashboard'),
            'pengacara'  => redirect()->route('pengacara.dashboard'),
            'klien'      => redirect()->route('klien.dashboard'),
            default      => redirect('/'),
        };
    }
}

