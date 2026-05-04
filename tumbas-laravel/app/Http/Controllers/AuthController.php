<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('staff_id')) {
            return redirect()->route('kasir');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = DB::table('users')
            ->where('username', $request->username)
            ->first();

        if ($user && password_verify($request->password, $user->password)) {
            session([
                'staff_id'   => $user->id,
                'staff_nama' => $user->nama ?? $user->name,
                'staff_role' => $user->role,
            ]);

            // Redirect berdasarkan role
            if ($user->role === 'dapur') {
                return redirect()->route('dapur');
            }
            return redirect()->route('kasir');
        }

        return back()
            ->withInput($request->only('username'))
            ->with('error', 'Username atau password salah.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['staff_id', 'staff_nama', 'staff_role']);
        return redirect()->route('login');
    }
}
