@extends('layouts.app')

@section('title', 'Employee List')

@section('content')
<style>
    /* CRITICAL FIX: Remove all animations and transitions */
    .btn, .btn-group, .btn-group .btn, .btn-sm, .btn-danger, .btn-info, .btn-warning {
        transition: none !important;
        transform: none !important;
        -webkit-transform: none !important;
        animation: none !important;
        box-shadow: none !important;
    }
    
    .btn:hover, .btn:focus, .btn:active {
        transform: none !important;
        transition: none !important;
        animation: none !important;
        box-shadow: none !important;
    }
    
    .btn-group .btn:hover {
        transform: none !important;
    }
    
    /* Remove card hover effect */
    .card, .card:hover {
        transform: none !important;
        transition: none !important;
    }
    
    /* Remove table row hover effect */
    .table tbody tr {
        transition: none !important;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fc;
    }
    
    /* Button styles */
    .view-btn {
        background-color: #17a2b8;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        color: white;
        text-decoration: none;
        display: inline-block;
    }
    
    .view-btn:hover {
        background-color: #138496;
        color: white;
    }
    
    .edit-btn {
        background-color: #ffc107;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        color: #212529;
        text-decoration: none;
        display: inline-block;
    }
    
    .edit-btn:hover {
        background-color: #e0a800;
        color: #212529;
    }
    
    .delete-btn {
        background-color: #dc3545;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        color: white;
        cursor: pointer;
    }
    
    .delete-btn:hover {
        background-color: #c82333;
    }
    
    .btn-group {
        display: inline-flex;
        gap: 5px;
    }
    
    .action-column {
        width: 120px;
        text-align: center;
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            <i class="fas fa-users me-2"></i>Employee Management
        </h4>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-tachometer-alt me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Salary</th>
                        <th>Hire Date</th>
                        <th class="action-column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr>
                        <td>{{ $employee->employee_id }}</td>
                        <td>
                            @php
                                $imagePath = $employee->user->profile_image;
                                $imageExists = $imagePath && file_exists(public_path('storage/' . $imagePath));
                            @endphp
                            
                            @if($imageExists)
                                <img src="{{ asset('storage/' . $imagePath) }}" 
                                     class="profile-img" width="40" height="40" style="object-fit: cover; border-radius: 50%;">
                            @else
                                <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $employee->user->name }}</td>
                        <td>{{ $employee->user->email }}</td>
                        <td>{{ $employee->position }}</td>
                        <td>
                            <span class="badge bg-info text-white">
                                {{ $employee->department->name }}
                            </span>
                        </td>
                        <td>${{ number_format($employee->salary, 2) }}</td>
                        <td>{{ $employee->hire_date->format('M d, Y') }}</td>
                        <td class="action-column">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.employees.show', $employee) }}" 
                                   class="view-btn" 
                                   title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.employees.edit', $employee) }}" 
                                   class="edit-btn" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="delete-btn" 
                                        data-id="{{ $employee->id }}"
                                        data-name="{{ addslashes($employee->user->name) }}"
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">No employees found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $employees->links() }}
        </div>
    </div>
</div>

<!-- Single Modal for all delete confirmations -->
<div id="deleteConfirmModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 10px; width: 400px; max-width: 90%; box-shadow: 0 5px 20px rgba(0,0,0,0.3);">
        <div style="background: #dc3545; color: white; padding: 15px; border-radius: 10px 10px 0 0; display: flex; justify-content: space-between; align-items: center;">
            <h5 style="margin: 0;"><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h5>
            <button onclick="closeModal()" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <div style="padding: 20px; text-align: center;">
            <i class="fas fa-user-times" style="font-size: 60px; color: #dc3545; margin-bottom: 15px;"></i>
            <h5>Are you sure you want to delete?</h5>
            <p>You are about to delete <strong id="deleteEmployeeName"></strong>.</p>
            <small style="color: #dc3545;">⚠️ This action cannot be undone!</small>
        </div>
        <div style="padding: 15px; border-top: 1px solid #dee2e6; display: flex; justify-content: space-between;">
            <button onclick="closeModal()" style="background: #6c757d; color: white; border: none; padding: 8px 20px; border-radius: 5px; cursor: pointer;">
                <i class="fas fa-times"></i> Cancel
            </button>
            <form id="deleteForm" method="POST" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" style="background: #dc3545; color: white; border: none; padding: 8px 20px; border-radius: 5px; cursor: pointer;">
                    <i class="fas fa-trash"></i> Yes, Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Delete confirmation
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const employeeId = this.dataset.id;
            const employeeName = this.dataset.name;
            
            document.getElementById('deleteEmployeeName').innerText = employeeName;
            document.getElementById('deleteForm').action = '/admin/employees/' + employeeId;
            document.getElementById('deleteConfirmModal').style.display = 'flex';
        });
    });
    
    function closeModal() {
        document.getElementById('deleteConfirmModal').style.display = 'none';
    }
    
    // Close modal when clicking outside
    document.getElementById('deleteConfirmModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endsection