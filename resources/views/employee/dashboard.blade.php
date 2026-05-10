@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
<style>
    .profile-img-large {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 3px solid #28a745;
        border-radius: 50%;
    }
    
    .info-table td {
        padding: 10px 5px;
    }
    
    .info-table th {
        width: 35%;
        padding: 10px 5px;
        font-weight: 600;
    }
</style>

<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h2><i class="fas fa-user-circle me-2"></i>Welcome, {{ Auth::user()->name }}!</h2>
                <p class="mb-0">Employee Dashboard - View your profile and information.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header text-center">
                <h5 class="mb-0"><i class="fas fa-camera me-2"></i>Profile Photo</h5>
            </div>
            <div class="card-body text-center">
                @php
                    $imagePath = Auth::user()->profile_image;
                    $imageExists = $imagePath && file_exists(public_path('storage/' . $imagePath));
                @endphp
                
                @if($imageExists)
                    <img src="{{ asset('storage/' . $imagePath) }}" 
                         class="profile-img-large mb-3">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=28a745&color=fff&size=150&rounded=true&bold=true" 
                         class="profile-img-large mb-3">
                @endif
                
                <form action="{{ route('employee.update.profile.image') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-2">
                        <label class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-upload me-1"></i> Change Photo
                            <input type="file" name="profile_image" accept="image/*" style="display: none;" onchange="this.form.submit()">
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Personal Information</h5>
            </div>
            <div class="card-body">
                @if($employee)
                    <table class="table table-borderless info-table">
                        <tr>
                            <th>Employee ID:</th>
                            <td><strong>{{ $employee->employee_id }}</strong></td>
                        </tr>
                        <tr>
                            <th>Full Name:</th>
                            <td>{{ $employee->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $employee->user->email }}</td>
                        </tr>
                        <tr>
                            <th>Position:</th>
                            <td>{{ $employee->position }}</td>
                        </tr>
                        <tr>
                            <th>Department:</th>
                            <td>{{ $employee->department->name }}</td>
                        </tr>
                        <tr>
                            <th>Salary:</th>
                            <td>${{ number_format($employee->salary, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Hire Date:</th>
                            <td>{{ $employee->hire_date->format('F d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $employee->phone ?? 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <th>Emergency Contact:</th>
                            <td>{{ $employee->emergency_contact ?? 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $employee->address ?? 'Not provided' }}</td>
                        </tr>
                    </table>
                    
                    <div class="mt-3">
                        <a href="{{ route('employee.edit-profile') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Edit Profile
                        </a>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Your profile is incomplete. 
                        <a href="{{ route('employee.complete-profile') }}" class="alert-link">Click here to complete your profile</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection