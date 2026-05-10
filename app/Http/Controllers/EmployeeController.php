<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['user', 'department'])->paginate(10);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('employees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|unique:employees',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string',
            'salary' => 'required|numeric|min:0',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        // Create user with name from email
        $name = explode('@', $request->email)[0];
        
        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'employee',
            'is_active' => true
        ]);

        // Create employee
        Employee::create([
            'employee_id' => $request->employee_id,
            'user_id' => $user->id,
            'department_id' => $request->department_id,
            'position' => $request->position,
            'salary' => $request->salary,
            'hire_date' => now(),
            'phone' => null,
            'address' => null,
            'emergency_contact' => null,
            
        ]);

        // Send Email Notification using Blade Template
        try {
            $department = Department::find($request->department_id);
            
            $data = [
                'name' => $user->name,
                'email' => $request->email,
                'password' => $request->password,
                'employee_id' => $request->employee_id,
                'position' => $request->position,
                'department' => $department->name,
                'salary' => $request->salary
            ];
            
            Mail::send('emails.employee-welcome', $data, function($message) use ($request) {
                $message->to($request->email)
                        ->subject('🎉 Your Employee Account Has Been Created');
            });
            
        } catch(\Exception $e) {
            Log::error('Employee account creation email failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.employees.index')->with('success', 'Employee account created successfully! Email notification sent to ' . $request->email);
    }

    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::all();
        return view('employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'employee_id' => 'required|unique:employees,employee_id,' . $employee->id,
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'hire_date' => 'required|date',
        ]);
        
        // Update only these fields
        $employee->update([
            'employee_id' => $request->employee_id,
            'department_id' => $request->department_id,
            'position' => $request->position,
            'salary' => $request->salary,
            'hire_date' => $request->hire_date,
        ]);
        
        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully!');
    }

    public function destroy(Employee $employee)
    {
        // Delete files
        if ($employee->user->profile_image) {
            Storage::disk('public')->delete($employee->user->profile_image);
        }
        if ($employee->resume_file) {
            Storage::disk('public')->delete($employee->resume_file);
        }
        
        $employee->user->delete();
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully!');
    }
}