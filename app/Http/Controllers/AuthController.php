<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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
            'password' => 'required|string'
        ]);

        $user = User::where('name', $request->username)->first();

        if (md5($request->password) !== $user->password) {
            return back()->withErrors(['username' => 'Username atau password salah'])->withInput();
        }

        // set session
        session([
            'user_id'   => $user->id,
            'user_role' => $user->role,
            'user_name' => $user->name
        ]);

        // redirect sesuai role
        if ($user->role === 'gudang') {
            return redirect()->route('gudang.index');
        } else {
            return redirect()->route('dapur.index');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login')->with('success', 'Berhasil logout');
    }
}
