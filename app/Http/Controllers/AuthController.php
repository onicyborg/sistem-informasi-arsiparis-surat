<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $remember = $request->has('remember_me');
        if (Auth::attempt($credentials, $remember)) {
            return redirect('/');
        }

        return redirect()->back()->withInput()->with('error', 'Username or password is incorrect');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
