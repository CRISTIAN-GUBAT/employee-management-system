@extends('layouts.app')

@section('title', 'Departments')

@section('content')
<style>
    /* Fix for button glitching */
    .btn-group {
        display: inline-flex;
        gap: 5px;
        align-items: center;
    }
    
    .btn-group .btn {
        transition: none !important;
        transform: none !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        padding: 0;
    }
    
    .btn-group .btn:hover {
        transform: none !important;
    }
    
    .btn-group .btn i {
        font-size: 14px;
    }
    
    /* Fix table spacing */
    .table td, .table th {
        vertical-align: middle;
        padding: 12px;
    }
    
    /* Fix badge spacing */
    .badge {
        display: inline-block;
        margin: 0;
        padding: 6px 12px;
    }
    
    /* Action column width */
    .action-column {
        width: 80px;
        text-align: center;
    }
    
    /* Table header styling */
    .table thead th {
        text-align: center;
    }
    
    .table thead th:first-child {
        text-align: left;
    }
    
    .table tbody td {
        text-align: center;
    }
    
    .table tbody td:first-child,
    .table tbody td:nth-child(2),
    .table tbody td:nth-child(3) {
        text-align: left;
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            <i class="fas fa-building me-2"></i>Department Management
        </h4>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-tachometer-alt me-2"></i>Back to Dashboard
            </a>
            <a href="{{ route('admin.departments.create') }}" class="btn btn-primary ms-2">
                <i class="fas fa-plus me-2"></i>Add Department
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Department Name</th>
                        <th>Description</th>
                        <th class="text-center">No. of Employees</th>
                        <th class="text-center action-column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $department)
                    <tr>
                        <td><span class="badge bg-primary">{{ $department->code }}</span></td>
                        <td><strong>{{ $department->name }}</strong></td>
                        <td>{{ Str::limit($department->description, 50) }}</td>
                        <td class="text-center">
                            <span class="badge bg-info text-white">
                                <i class="fas fa-users me-1"></i>{{ $department->employees_count }}
                            </span>
                        </td>
                        <td class="text-center action-column">
                            <div class="btn-group">
                                <a href="{{ route('admin.departments.edit', $department) }}"
                                   class="btn btn-sm btn-warning"
                                   title="Edit Department">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($department->employees_count == 0)
                                <button type="button"
                                        class="btn btn-sm btn-danger"
                                        title="Delete Department"
                                        data-id="{{ $department->id }}"
                                        data-name="{{ $department->name }}"
                                        onclick="confirmDepartmentDelete(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fas fa-building fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">No departments found.</p>
                            <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create First Department
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $departments->links() }}
        </div>
    </div>
</div>

<!-- Single Delete Modal for Departments -->
<div id="deleteDepartmentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 10px; width: 400px; max-width: 90%; box-shadow: 0 5px 20px rgba(0,0,0,0.3);">
        <div style="background: #dc3545; color: white; padding: 15px; border-radius: 10px 10px 0 0; display: flex; justify-content: space-between; align-items: center;">
            <h5 style="margin: 0;">
                <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
            </h5>
            <button onclick="closeDepartmentModal()" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <div style="padding: 20px; text-align: center;">
            <i class="fas fa-building" style="font-size: 60px; color: #dc3545; margin-bottom: 15px;"></i>
            <h5>Are you sure you want to delete this department?</h5>
            <p>You are about to delete <strong id="deleteDepartmentName"></strong>.</p>
            <small style="color: #dc3545;">
                <i class="fas fa-exclamation-circle me-1"></i>This action cannot be undone!
            </small>
        </div>
        <div style="padding: 15px; border-top: 1px solid #dee2e6; display: flex; justify-content: space-between;">
            <button onclick="closeDepartmentModal()" style="background: #6c757d; color: white; border: none; padding: 8px 20px; border-radius: 5px; cursor: pointer;">
                <i class="fas fa-times me-2"></i>Cancel
            </button>
            <form id="deleteDepartmentForm" method="POST" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" style="background: #dc3545; color: white; border: none; padding: 8px 20px; border-radius: 5px; cursor: pointer;">
                    <i class="fas fa-trash me-2"></i>Yes, Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDepartmentDelete(button) {
        var departmentId = button.getAttribute('data-id');
        var departmentName = button.getAttribute('data-name');
        document.getElementById('deleteDepartmentName').innerText = departmentName;
        var form = document.getElementById('deleteDepartmentForm');
        form.action = '/admin/departments/' + departmentId;
        document.getElementById('deleteDepartmentModal').style.display = 'flex';
    }
    
    function closeDepartmentModal() {
        document.getElementById('deleteDepartmentModal').style.display = 'none';
    }
    
    // Close modal when clicking outside
    document.getElementById('deleteDepartmentModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDepartmentModal();
        }
    });
    
    // Close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDepartmentModal();
        }
    });
</script>
@endsection