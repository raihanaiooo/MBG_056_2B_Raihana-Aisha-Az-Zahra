<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Validasi Login
    public function login(Request $request)
    {
        $usernameOrEmail = $request->username;
        $user = User::where('name', $usernameOrEmail)
                    ->orWhere('email', $usernameOrEmail)
                    ->first();

        // Jika user tidak ada atau password salah
        if (!$user || md5($request->password) !== $user->password) {
            return back()->with('error', 'Username atau password salah')->withInput();
        }

        // Set session
        session([
            'user_id'   => $user->id,
            'user_role' => $user->role,
            'user_name' => $user->name
        ]);

        // Redirect sesuai role
        if ($user->role === 'gudang') {
            return redirect()->route('gudang.index')->with('success', 'Login berhasil sebagai Gudang');
        } else {
            return redirect()->route('dapur.index')->with('success', 'Login berhasil sebagai Dapur');
        }
    }

    // Fungsi untuk Logout
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login')->with('success', 'Berhasil logout');
    }
}
