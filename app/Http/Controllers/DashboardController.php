<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        
        $totalEmployees = Employee::count();
        $totalDepartments = Department::count();
        $totalUsers = User::count();
        $recentEmployees = Employee::with(['user', 'department'])->latest()->take(5)->get();
        
        return view('admin.dashboard', compact('totalEmployees', 'totalDepartments', 'totalUsers', 'recentEmployees'));
    }

    public function employeeDashboard()
    {
        $employee = Auth::user()->employee;
        return view('employee.dashboard', compact('employee'));
    }

    // ==================== ADMIN PROFILE METHODS ====================

    // Admin Profile Page
    public function adminProfile()
    {
        return view('admin.profile');
    }

    // Update Admin Profile Image - NO SIZE LIMIT
    public function updateAdminProfileImage(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg' // NO size limit
        ]);
        
        // Delete old image
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }
        
        // Store new image
        $imagePath = $request->file('profile_image')->store('profiles', 'public');
        $user->profile_image = $imagePath;
        $user->save();
        
        return redirect()->route('admin.profile')->with('success', 'Profile picture updated successfully!');
    }

    // Update Admin Profile Information (name, email, password)
    public function updateAdminProfileInfo(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);
        
        // Update name and email
        $user->name = $request->name;
        $user->email = $request->email;
        
        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        return redirect()->route('admin.profile')->with('success', 'Profile information updated successfully!');
    }

    // ==================== EMPLOYEE PROFILE METHODS ====================

    // Employee Profile Page
    public function employeeProfile()
    {
        $employee = Auth::user()->employee;
        return view('employee.profile', compact('employee'));
    }

    // Update Employee Profile Image - NO SIZE LIMIT
    public function updateEmployeeProfileImage(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg' // NO size limit
        ]);
        
        // Delete old image
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }
        
        // Store new image
        $imagePath = $request->file('profile_image')->store('profiles', 'public');
        $user->profile_image = $imagePath;
        $user->save();
        
        return redirect()->route('employee.dashboard')->with('success', 'Profile picture updated successfully!');
    }

    // Update Employee Profile Information (name, email, phone, address, emergency_contact, password)
    public function updateEmployeeProfileInfo(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg' 
        ]);
        
        // Update user information (name, email)
        $user->name = $request->name;
        $user->email = $request->email;
        
        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        // Update profile image if uploaded
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $imagePath;
        }
        
        $user->save();
        
        // Update employee details
        if ($employee) {
            $employee->phone = $request->phone;
            $employee->address = $request->address;
            $employee->emergency_contact = $request->emergency_contact;
            $employee->save();
        }
        
        return redirect()->route('employee.dashboard')->with('success', 'Profile updated successfully!');
    }

    // Edit Employee Profile Page
    public function editEmployeeProfile()
    {
        $employee = Auth::user()->employee;
        $departments = Department::all();
        return view('employee.edit-profile', compact('employee', 'departments'));
    }
}