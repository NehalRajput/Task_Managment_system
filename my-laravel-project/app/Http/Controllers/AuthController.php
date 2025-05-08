<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show Register Page
    public function showRegister()
    {
        return view('Auth.Register');
    }

    // Handle Register
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
    

        $validated['role_id'] = 3;
               // Get the intern role ID
      
        
       

        $user = User::create($validated);

        // Login after registration using web guard
        Auth::guard('web')->login($user);

        return redirect('dashboard');
    }

    // Show Login Page
    public function showLogin()
    {
        return view('Auth.Login');
    }

    // Handle Login
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();


        if (Auth::guard('user')->attempt($validated)) {
            $request->session()->regenerate();
            return redirect('dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    // Show Admin Login Page
    public function showAdminLogin()
    {
        return view('Admin.login');
    }

    // Handle Admin Login
    public function adminLogin(LoginRequest $request)
    {
        $validated = $request->validated();

        if (Auth::guard('admin')->attempt($validated)) {
            $request->session()->regenerate();
            return redirect('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    // Handle User Logout
    public function logout(Request $request)
    {
        Auth::guard('user')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('intern.login');
    }

    // Handle Admin Logout
    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('admin.login');
    }
}