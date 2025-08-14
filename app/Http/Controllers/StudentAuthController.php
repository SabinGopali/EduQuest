<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Students;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class StudentAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Process student login
    public function login(Request $request)
    {
        // Validate the user's input
        $credentials = $request->only('email', 'password');
        $credentials['user_type'] = 'students';

        if (Auth::guard('student')->attempt($credentials)) {
            // Authentication successful, redirect to the nearest colleges page by default
            return redirect()->intended(route('home.nearest'));
        }

        // Authentication failed, display an error message
        // return redirect('/admin/login');
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // Log out the student user
    public function logout()
    {
        Auth::guard('student')->logout();
        return redirect('/student/login');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'newPassword' => 'required',
            'confirmPassword' => 'required|same:newPassword',
        ]);

        $user = Auth::guard('student')->user();

        $user->update([
            'password' => Hash::make($request->input('newPassword')),
        ]);

        return redirect()->route('home')->with('success', 'Password updated successfully!');
    }

    public function getHome(){
         return view('home.index');

    }
}