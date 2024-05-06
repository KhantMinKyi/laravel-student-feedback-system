<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);
        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return redirect('/')->with('error', 'Incorrect User Name');
        }
        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error', 'Password is Wrong , Try Again');
        }
        auth()->login($user);
        if ($user->type == 'admin') {
            return redirect('/admin');
        } else if ($user->type == 'teacher') {
            return redirect('/teacher');
        } else if ($user->type == 'student') {
            return redirect('/student');
        } else {
            return 'Error';
        }
    }
    public function logout(Request $request)
    {
        auth()->logout();
        return redirect()->back()->with('success', 'You Have Been Logout');
    }
}
