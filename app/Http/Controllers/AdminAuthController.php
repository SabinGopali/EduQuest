<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;


class AdminAuthController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('guest:admin'); // Apply guest middleware to prevent already authenticated admins from accessing registration
    // }
    // protected function validator(array $data)
    // {
    //     return Validator::make($data, [
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
    //         'password' => ['required', 'string', 'min:8', 'confirmed'],
    //     ]);
    // }


    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function showRegisterForm()
    {
        return view('auth.admin-register');
    }

    public function login(Request $request)
    {
        // Validate the user's input
        $credentials = $request->only('email', 'password');
        $credentials['user_type'] = 'admin';

        if (Auth::guard('admin')->attempt($credentials)) {
            // Authentication successful, redirect to the admin dashboard
            return redirect('/admin/dashboard');
        }

        // Authentication failed, display an error message
        return back()->withErrors(['email' => 'Invalid credentials']);

    }

    public function register(Request $request)
    {
        // Validation rules
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create a new admin user
        $admin = Admin::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // Log in the newly registered admin (optional)
        // Auth::guard('admin')->login($admin);

        // Redirect to a dashboard or home page
        return redirect('/');
    }

    // Log out the admin user
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'newPassword' => 'required',
            'confirmPassword' => 'required|same:newPassword',
        ]);

        $user = Auth::guard('admin')->user();

        $user->update([
            'password' => Hash::make($request->input('newPassword')),
        ]);

        return redirect()->route('adminController.count')->with('success', 'Password updated successfully!');
    }

    public function getChangeView(){
        return view('admin.change-password');
    }
}
