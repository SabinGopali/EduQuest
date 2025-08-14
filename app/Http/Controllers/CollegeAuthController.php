<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\College;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CollegeAuthController extends Controller
{
    // Show the college login form
    public function showLoginForm()
    {
        return view('auth.college-login');
    }

    public function showRegisterForm()
    {
        return view('auth.college-register');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['user_type'] = 'colleges';

        if (Auth::guard('college')->attempt($credentials)) {
            return redirect()->intended('/college/dashboard');
        }
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // Log out the college user
    public function logout()
    {
        Auth::guard('college')->logout();
        return redirect('/college/login');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'newPassword' => 'required',
            'confirmPassword' => 'required|same:newPassword',
        ]);

        $user = Auth::guard('college')->user();

        $user->update([
            'password' => Hash::make($request->input('newPassword')),
        ]);

        return redirect()->route('college.dashboard')->with('success', 'Password updated successfully!');
    }

    public function getChangeView(){
        return view('college.change-password');
    }
}

