<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:admin');
        // $this->middleware('auth:student');
        $this->middleware('auth:college');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return view('home');
        if (auth()->check()) {
            // User is authenticated
            return view('home');
        } else {
            // User is not authenticated
            return redirect()->route('admin.loginForm'); // Redirect to the login page
        }
    }

    public function home()
    {
        return view('home.index');
    }
}
