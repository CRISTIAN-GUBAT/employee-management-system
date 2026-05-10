@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            <i class="fas fa-user-circle me-2"></i>My Profile
        </h4>
        <div>
            <a href="{{ route('employee.edit-profile') }}" class="btn btn-primary me-2">
                <i class="fas fa-edit me-2"></i>Edit Profile
            </a>
            <a href="{{ route('employee.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center">
                @php
                    $imagePath = Auth::user()->profile_image;
                    $imageExists = $imagePath && file_exists(public_path('storage/' . $imagePath));
                @endphp
                
                <div class="profile-image-container">
                    @if($imageExists)
                        <img src="{{ asset('storage/' . $imagePath) }}" 
                             id="profileImage"
                             class="img-fluid rounded-circle mb-3" 
                             style="width: 200px; height: 200px; object-fit: cover; border: 3px solid #28a745;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=28a745&color=fff&size=200&rounded=true&bold=true" 
                             id="profileImage"
                             class="img-fluid rounded-circle mb-3" 
                             style="width: 200px; height: 200px; border: 3px solid #28a745;">
                    @endif
                    
                    <!-- Image upload form -->
                    <form action="{{ route('employee.update.profile.image') }}" method="POST" enctype="multipart/form-data" id="imageUploadForm">
                        @csrf
                        @method('PUT')
                        <div class="upload-btn">
                            <i class="fas fa-camera"></i>
                            <input type="file" name="profile_image" accept="image/*" onchange="document.getElementById('imageUploadForm').submit()">
                        </div>
                    </form>
                </div>
                <h4>{{ Auth::user()->name }}</h4>
                <span class="badge bg-success">Employee</span>
            </div>
            <div class="col-md-9">
                @if($employee)
                    <!-- Profile Information Display -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Employee ID</label>
                            <p class="fw-bold">{{ $employee->employee_id }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Full Name</label>
                            <p class="fw-bold">{{ $employee->user->name }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Email Address</label>
                            <p class="fw-bold">{{ $employee->user->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Position</label>
                            <p class="fw-bold">{{ $employee->position }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Department</label>
                            <p class="fw-bold">{{ $employee->department->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Salary</label>
                            <p class="fw-bold">${{ number_format($employee->salary, 2) }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Hire Date</label>
                            <p class="fw-bold">{{ $employee->hire_date->format('F d, Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Phone Number</label>
                            <p class="fw-bold">{{ $employee->phone ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Emergency Contact</label>
                            <p class="fw-bold">{{ $employee->emergency_contact ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Address</label>
                        <p class="fw-bold">{{ $employee->address ?? 'Not provided' }}</p>
                    </div>
                    
                    <hr class="my-4">
                    
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Role:</th>
                            <td><span class="badge bg-success">Employee</span>
                        </tr>
                        <tr>
                            <th>Account Status:</th>
                            <td><span class="badge bg-success">Active</span>
                        </tr>
                        <tr>
                            <th>Member Since:</th>
                            <td>{{ Auth::user()->created_at->format('F d, Y') }}
                        </tr>
                    </table>
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

<style>
    .profile-image-container {
        position: relative;
        display: inline-block;
    }
    .upload-btn {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: #28a745;
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    .upload-btn:hover {
        background: #218838;
        transform: scale(1.05);
    }
    .upload-btn input {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
</style>
@endsection