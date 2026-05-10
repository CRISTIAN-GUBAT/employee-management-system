@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h2><i class="fas fa-tachometer-alt me-2"></i>Welcome back, {{ Auth::user()->name }}!</h2>
                <p class="mb-0">Admin Dashboard - Manage your employees and departments efficiently.</p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="dashboard-card">
            <div class="dashboard-icon">
                <i class="fas fa-users text-primary"></i>
            </div>
            <h3>{{ $totalEmployees ?? 0 }}</h3>
            <p class="text-muted mb-0">Total Employees</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card">
            <div class="dashboard-icon">
                <i class="fas fa-building text-success"></i>
            </div>
            <h3>{{ $totalDepartments ?? 0 }}</h3>
            <p class="text-muted mb-0">Departments</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card">
            <div class="dashboard-icon">
                <i class="fas fa-user-tie text-info"></i>
            </div>
            <h3>{{ $totalUsers ?? 0 }}</h3>
            <p class="text-muted mb-0">System Users</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card">
            <div class="dashboard-icon">
                <i class="fas fa-chart-line text-warning"></i>
            </div>
            <h3>Active</h3>
            <p class="text-muted mb-0">System Status</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Employees</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Department</th>
                                <th>Hire Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentEmployees ?? [] as $employee)
                            <tr>
                                <td>{{ $employee->employee_id }}</td>
                                <td>{{ $employee->user->name }}</td>
                                <td>{{ $employee->position }}</td>
                                <td>{{ $employee->department->name }}</td>
                                <td>{{ $employee->hire_date->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No employees found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-external-link-alt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Create Employee Account
                    </a>
                    <a href="{{ route('admin.departments.create') }}" class="btn btn-success">
                        <i class="fas fa-building me-2"></i>Add Department
                    </a>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-info text-white">
                        <i class="fas fa-list me-2"></i>View All Employees
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection