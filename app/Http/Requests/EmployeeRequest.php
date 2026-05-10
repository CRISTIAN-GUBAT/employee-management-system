<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee');
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($employeeId ? $employeeId->user_id : ''),
            'employee_id' => 'required|string|unique:employees,employee_id,' . ($employeeId ? $employeeId->id : ''),
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'hire_date' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'resume_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Employee name is required.',
            'email.required' => 'Email address is required.',
            'email.unique' => 'This email is already taken.',
            'employee_id.required' => 'Employee ID is required.',
            'employee_id.unique' => 'This Employee ID already exists.',
            'department_id.required' => 'Please select a department.',
            'position.required' => 'Position is required.',
            'salary.required' => 'Salary is required.',
            'salary.numeric' => 'Salary must be a number.',
            'hire_date.required' => 'Hire date is required.',
            'profile_image.image' => 'Profile image must be an image file.',
            'profile_image.mimes' => 'Profile image must be jpeg, png, or jpg format.',
            'resume_file.mimes' => 'Resume must be pdf, doc, or docx format.'
        ];
    }
}