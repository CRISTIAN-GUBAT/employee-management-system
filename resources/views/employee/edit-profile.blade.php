@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<style>
    .form-label {
        font-weight: 500;
    }
    
    .profile-preview {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #28a745;
        margin-bottom: 15px;
    }
    
    .preview-container {
        text-align: center;
        margin-bottom: 20px;
    }
    
    .password-requirements {
        font-size: 12px;
        color: #6c757d;
        margin-top: 5px;
    }
    
    .readonly-field {
        background-color: #e9ecef;
        opacity: 0.8;
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            <i class="fas fa-edit me-2"></i>Edit Profile
        </h4>
        <div>
            <a href="{{ route('employee.profile') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Profile
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('employee.update.profile.info') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Profile Image Preview -->
            <div class="preview-container">
                @php
                    $imagePath = Auth::user()->profile_image;
                    $imageExists = $imagePath && file_exists(public_path('storage/' . $imagePath));
                @endphp
                
                <div class="profile-image-container" style="position: relative; display: inline-block;">
                    @if($imageExists)
                        <img src="{{ asset('storage/' . $imagePath) }}" 
                             class="profile-preview" 
                             id="imagePreview">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=28a745&color=fff&size=150&rounded=true&bold=true" 
                             class="profile-preview" 
                             id="imagePreview">
                    @endif
                    
                    <div class="upload-btn" style="position: absolute; bottom: 10px; right: 10px; background: #28a745; color: white; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                        <i class="fas fa-camera fa-sm"></i>
                        <input type="file" name="profile_image" id="profile_image" accept="image/*" style="position: absolute; opacity: 0; width: 100%; height: 100%; cursor: pointer;" onchange="previewImage(this)">
                    </div>
                </div>
                <small class="text-muted d-block mt-2">Click the camera icon to change profile picture</small>
                <small class="text-muted d-block">Allowed: jpeg, png, jpg.</small>
            </div>
            
            <hr>
            
            <!-- Personal Information -->
            <h5 class="mb-3"><i class="fas fa-user-circle me-2"></i>Personal Information</h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $employee->phone ?? '') }}" 
                                   placeholder="Enter your phone number">
                        </div>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="emergency_contact" class="form-label">Emergency Contact</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-exclamation-circle"></i></span>
                            <input type="text" class="form-control @error('emergency_contact') is-invalid @enderror" 
                                   id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact', $employee->emergency_contact ?? '') }}" 
                                   placeholder="Emergency contact number">
                        </div>
                        @error('emergency_contact')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                              id="address" name="address" rows="3" 
                              placeholder="Your complete address">{{ old('address', $employee->address ?? '') }}</textarea>
                </div>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <hr>
            
            <!-- Change Password Section -->
            <h5 class="mb-3"><i class="fas fa-lock me-2"></i>Change Password</h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" placeholder="Leave blank to keep current password">
                        </div>
                        <div class="password-requirements">
                            <small><i class="fas fa-info-circle me-1"></i> Password must be at least 8 characters with uppercase, lowercase, number, and special character (@$!%*?&)</small>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-check"></i></span>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" placeholder="Confirm your new password">
                        </div>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <!-- Read-only Information -->
            <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Employment Information (Read Only)</h5>
            <p class="text-muted small mb-3">For security reasons, these fields cannot be edited. Please contact HR for changes.</p>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Employee ID</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input type="text" class="form-control readonly-field" value="{{ $employee->employee_id ?? 'N/A' }}" readonly disabled>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Position</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                            <input type="text" class="form-control readonly-field" value="{{ $employee->position ?? 'N/A' }}" readonly disabled>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                            <input type="text" class="form-control readonly-field" value="{{ $employee->department->name ?? 'N/A' }}" readonly disabled>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Salary</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input type="text" class="form-control readonly-field" value="${{ number_format($employee->salary ?? 0, 2) }}" readonly disabled>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Hire Date</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <input type="text" class="form-control readonly-field" value="{{ $employee->hire_date ? $employee->hire_date->format('F d, Y') : 'N/A' }}" readonly disabled>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Note:</strong> Position, Department, and Salary can only be changed by Admin.
            </div>
            
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Update Profile
                </button>
                <a href="{{ route('employee.profile') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                // Auto submit the form when image is selected
                input.form.submit();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection