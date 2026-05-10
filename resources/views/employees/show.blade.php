@extends('layouts.app')

@section('title', 'Employee Details')

@section('content')
<style>
    .info-card {
        background: #f8f9fc;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .info-label {
        font-weight: 600;
        color: #4e73df;
        margin-bottom: 5px;
    }
    .info-value {
        font-size: 16px;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e3e6f0;
    }
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 10px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            <i class="fas fa-user-circle me-2"></i>Employee Details
        </h4>
        <div>
            <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>Edit Employee
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary me-2">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a href="{{ route('admin.employees.index') }}" class="btn btn-info text-white">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Profile Image Column -->
            <div class="col-md-3 text-center">
                @php
                    $imagePath = $employee->user->profile_image;
                    $imageExists = $imagePath && file_exists(public_path('storage/' . $imagePath));
                @endphp
                
                @if($imageExists)
                    <img src="{{ asset('storage/' . $imagePath) }}" 
                         class="img-fluid rounded-circle mb-3" 
                         style="width: 200px; height: 200px; object-fit: cover; border: 3px solid #667eea; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->user->name) }}&background=667eea&color=fff&size=200&rounded=true&bold=true" 
                         class="img-fluid rounded-circle mb-3" 
                         style="width: 200px; height: 200px; border: 3px solid #667eea; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                @endif
                
                <h3 class="mt-2">{{ $employee->user->name }}</h3>
                <p class="text-muted">{{ $employee->position }}</p>
                <span class="badge bg-success px-3 py-2">{{ ucfirst($employee->user->role) }}</span>
            </div>
            
            <!-- Employee Information Column -->
            <div class="col-md-9">
                <!-- Personal Information Section -->
                <div class="section-title">
                    <i class="fas fa-user me-2"></i> Personal Information
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-id-card me-1"></i> Full Name
                            </div>
                            <div class="info-value">{{ $employee->user->name }}</div>
                            
                            <div class="info-label">
                                <i class="fas fa-envelope me-1"></i> Email Address
                            </div>
                            <div class="info-value">{{ $employee->user->email }}</div>
                            
                            <div class="info-label">
                                <i class="fas fa-phone me-1"></i> Phone Number
                            </div>
                            <div class="info-value">{{ $employee->phone ?? 'Not provided' }}</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-exclamation-circle me-1"></i> Emergency Contact
                            </div>
                            <div class="info-value">{{ $employee->emergency_contact ?? 'Not provided' }}</div>
                            
                            <div class="info-label">
                                <i class="fas fa-map-marker-alt me-1"></i> Address
                            </div>
                            <div class="info-value">{{ $employee->address ?? 'Not provided' }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Employment Information Section -->
                <div class="section-title mt-3">
                    <i class="fas fa-briefcase me-2"></i> Employment Information
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-hashtag me-1"></i> Employee ID
                            </div>
                            <div class="info-value">
                                <span class="badge bg-primary">{{ $employee->employee_id }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-briefcase me-1"></i> Position
                            </div>
                            <div class="info-value">{{ $employee->position }}</div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-building me-1"></i> Department
                            </div>
                            <div class="info-value">{{ $employee->department->name }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-dollar-sign me-1"></i> Salary
                            </div>
                            <div class="info-value">
                                <span class="fw-bold text-success">${{ number_format($employee->salary, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-calendar-alt me-1"></i> Hire Date
                            </div>
                            <div class="info-value">{{ $employee->hire_date->format('F d, Y') }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Account Information Section -->
                <div class="section-title mt-3">
                    <i class="fas fa-shield-alt me-2"></i> Account Information
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-user-tag me-1"></i> Role
                            </div>
                            <div class="info-value">
                                <span class="badge bg-success">{{ ucfirst($employee->user->role) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-calendar-check me-1"></i> Account Created
                            </div>
                            <div class="info-value">{{ $employee->user->created_at->format('F d, Y h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection