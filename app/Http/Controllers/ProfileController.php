<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function completeProfile()
    {
        $employee = Auth::user()->employee;
        $departments = Department::all();
        
        // If employee already has profile, redirect to dashboard
        if ($employee && $employee->employee_id) {
            return redirect()->route('employee.dashboard');
        }
        
        return view('employee.complete-profile', compact('departments'));
    }

    public function storeProfile(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|unique:employees',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        $user = Auth::user();

        // Handle profile image
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $profileImage = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $profileImage;
            $user->save();
        }

        // Create employee profile
        Employee::create([
            'employee_id' => $request->employee_id,
            'user_id' => $user->id,
            'department_id' => $request->department_id,
            'position' => $request->position,
            'hire_date' => $request->hire_date,
            'phone' => $request->phone,
            'address' => $request->address,
            'emergency_contact' => $request->emergency_contact,
            'salary' => 0, // Default salary, admin can update later
            
        ]);

        return redirect()->route('employee.dashboard')->with('success', 'Profile completed successfully!');
    }
}