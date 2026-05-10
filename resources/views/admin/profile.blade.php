@extends('layouts.app')

@section('title', 'Admin Profile')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            <i class="fas fa-user-circle me-2"></i>Admin Profile
        </h4>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
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
                             style="width: 200px; height: 200px; object-fit: cover; border: 3px solid #667eea;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=667eea&color=fff&size=200&rounded=true&bold=true" 
                             id="profileImage"
                             class="img-fluid rounded-circle mb-3" 
                             style="width: 200px; height: 200px; border: 3px solid #667eea;">
                    @endif
                    
                    <!-- Separate form for image upload only -->
                    <form action="{{ route('admin.update.profile.image') }}" method="POST" enctype="multipart/form-data" id="imageUploadForm">
                        @csrf
                        @method('PUT')
                        <div class="upload-btn">
                            <i class="fas fa-camera"></i>
                            <input type="file" name="profile_image" accept="image/*" onchange="document.getElementById('imageUploadForm').submit()">
                        </div>
                    </form>
                </div>
                <h4>{{ Auth::user()->name }}</h4>
                <span class="badge bg-primary">Administrator</span>
            </div>
            <div class="col-md-9">
                <!-- Separate form for profile info update -->
                <form action="{{ route('admin.update.profile.info') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile Info
                        </button>
                    </div>
                </form>
                
                <hr class="my-4">
                
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Role:</th>
                        <td><span class="badge bg-danger">Administrator</span>
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
        background: #667eea;
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
        background: #5a67d8;
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