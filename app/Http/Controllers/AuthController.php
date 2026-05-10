<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        // Get remembered email from cookie
        $rememberedEmail = Cookie::get('remembered_email');
        
        return view('auth.login', compact('rememberedEmail'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Attempt login with remember me functionality
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember') ? true : false;
        
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            $authenticatedUser = Auth::user();
            
            // Store email in cookie if remember me is checked
            if ($remember) {
                Cookie::queue('remembered_email', $request->email, 60 * 24 * 30); // 30 days
            } else {
                Cookie::queue(Cookie::forget('remembered_email'));
            }
            
            // Check if user is active
            if (!$authenticatedUser->is_active) {
                Auth::logout();
                return back()->with('error', 'Your account is deactivated.');
            }
            
            // Log login activity
            Log::info('User logged in', [
                'user_id' => $authenticatedUser->id,
                'email' => $authenticatedUser->email,
                'time' => now(),
                'ip' => $request->ip()
            ]);
            
            if ($authenticatedUser->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome back, Admin!');
            }
            
            // Check if employee profile is complete
            $employee = $authenticatedUser->employee;
            if (!$employee || !$employee->employee_id) {
                return redirect()->route('employee.complete-profile')->with('warning', 'Please complete your profile information.');
            }
            
            return redirect()->route('employee.dashboard')->with('success', 'Welcome back, ' . $authenticatedUser->name . '!');
        }

        return back()->with('error', 'Invalid credentials.')->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            Log::info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'time' => now()
            ]);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Don't clear the remembered email cookie on logout
        // The email will stay in the login form
        
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}