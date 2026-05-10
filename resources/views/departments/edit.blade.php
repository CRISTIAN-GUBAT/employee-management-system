@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            <i class="fas fa-edit me-2"></i>Edit Department
        </h4>
        <div>
            <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Note:</strong> You can edit all department fields.
        </div>

        <form action="{{ route('admin.departments.update', $department) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Department Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $department->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="code" class="form-label">Department Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code', $department->code) }}" required>
                        <small class="text-muted">Unique code for the department (e.g., IT, HR, FIN)</small>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="5">{{ old('description', $department->description) }}</textarea>
                <small class="text-muted">Brief description of the department's responsibilities</small>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Delete Button Section (if no employees) -->
            @if($department->employees()->count() == 0)
            <div class="alert alert-warning mt-3">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Warning:</strong> This department has no employees and can be deleted.
            </div>
            <div class="mt-3 d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Department
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash me-2"></i>Delete Department
                </button>
            </div>
            @else
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Note:</strong> This department has {{ $department->employees()->count() }} employee(s) and cannot be deleted.
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Department
                </button>
            </div>
            @endif
            
            <div class="mt-2">
                <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 10px; width: 400px; max-width: 90%; box-shadow: 0 5px 20px rgba(0,0,0,0.3);">
        <div style="background: #dc3545; color: white; padding: 15px; border-radius: 10px 10px 0 0; display: flex; justify-content: space-between; align-items: center;">
            <h5 style="margin: 0;"><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h5>
            <button onclick="closeModal()" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <div style="padding: 20px; text-align: center;">
            <i class="fas fa-building" style="font-size: 60px; color: #dc3545; margin-bottom: 15px;"></i>
            <h5>Are you sure you want to delete this department?</h5>
            <p>You are about to delete <strong>{{ $department->name }}</strong>.</p>
            <small style="color: #dc3545;">⚠️ This action cannot be undone!</small>
        </div>
        <div style="padding: 15px; border-top: 1px solid #dee2e6; display: flex; justify-content: space-between;">
            <button onclick="closeModal()" style="background: #6c757d; color: white; border: none; padding: 8px 20px; border-radius: 5px; cursor: pointer;">
                <i class="fas fa-times"></i> Cancel
            </button>
            <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" style="display: inline-block;">
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
    function confirmDelete() {
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endsection