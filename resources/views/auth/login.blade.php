@extends('layouts.app')

@section('title', 'Login')

@section('content')
<style>
    .login-card {
        background: white;
        border-radius: 25px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.25);
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    .login-card:hover {
        transform: translateY(-5px);
    }
    .login-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        padding: 35px 30px;
        text-align: center;
    }
    .login-header h2 {
        color: white;
        margin: 0;
        font-size: 28px;
        font-weight: 600;
    }
    .login-header p {
        color: rgba(255,255,255,0.85);
        margin: 10px 0 0;
        font-size: 14px;
    }
    .login-header i {
        filter: drop-shadow(0 2px 5px rgba(0,0,0,0.2));
    }
    .login-body {
        padding: 40px;
        background: #f8fafc;
    }
    .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #2a5298;
        font-size: 16px;
    }
    .form-control-custom {
        padding-left: 45px;
        height: 52px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s;
        width: 100%;
        font-size: 15px;
        background: white;
    }
    .form-control-custom:focus {
        border-color: #2a5298;
        box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.1);
        outline: none;
    }
    .form-control-custom:hover {
        border-color: #2a5298;
    }
    .btn-login {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        height: 52px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s;
        width: 100%;
        border: none;
        letter-spacing: 0.5px;
    }
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(30, 60, 114, 0.3);
        background: linear-gradient(135deg, #152e5c 0%, #1e4070 100%);
    }
    .alert-auto {
        animation: slideDown 0.5s ease;
        border-radius: 12px;
        font-size: 14px;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .form-check {
        padding-left: 1.8rem;
    }
    .form-check-input {
        width: 18px;
        height: 18px;
        margin-top: 0;
        border: 2px solid #cbd5e1;
        cursor: pointer;
    }
    .form-check-input:checked {
        background-color: #2a5298;
        border-color: #2a5298;
    }
    .form-check-label {
        font-size: 14px;
        color: #475569;
        cursor: pointer;
        margin-left: 8px;
    }
    .text-muted {
        color: #64748b !important;
        font-size: 12px;
    }
    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.5rem;
        font-size: 0.75rem;
        color: #dc2626;
    }
    .alert-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
    }
    .alert-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
    }
    .alert-info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
    }
    .alert-info i, .alert-success i, .alert-danger i {
        color: white;
    }
    hr {
        margin: 20px 0;
        border-color: #e2e8f0;
    }
    /* Demo Credentials Box */
    .demo-box {
        background: #f1f5f9;
        border-radius: 12px;
        padding: 15px;
        margin-top: 20px;
        border-left: 4px solid #2a5298;
    }
    .demo-title {
        font-size: 13px;
        font-weight: 600;
        color: #1e3c72;
        margin-bottom: 10px;
    }
    .demo-item {
        font-size: 12px;
        padding: 5px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .demo-label {
        font-weight: 600;
        color: #475569;
        width: 45px;
    }
    .demo-value {
        color: #2a5298;
        font-family: monospace;
        background: white;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 12px;
    }
    .demo-copy {
        cursor: pointer;
        color: #64748b;
        font-size: 11px;
        transition: color 0.2s;
    }
    .demo-copy:hover {
        color: #2a5298;
    }
</style>

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-building fa-3x mb-3"></i>
                <h2>Employee Management System</h2>
                <p>Sign in to access your dashboard</p>
            </div>
            <div class="login-body">
                @if(session('success'))
                    <div class="alert alert-success alert-auto">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-auto">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    </div>
                @endif

                @if(isset($rememberedEmail) && $rememberedEmail)
                    <div class="alert alert-info alert-auto mb-3">
                        <i class="fas fa-info-circle me-2"></i> 
                        Welcome back! Your email has been remembered.
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                    @csrf
                    <div class="mb-3 position-relative">
                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input type="email" class="form-control form-control-custom @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $rememberedEmail ?? '') }}" 
                               placeholder="Email Address" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 position-relative">
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" class="form-control form-control-custom @error('password') is-invalid @enderror" 
                               id="password" name="password" placeholder="Password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" 
                                   {{ old('remember', isset($rememberedEmail) ? 'checked' : '') }}>
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i> Keeps your email
                        </small>
                    </div>
                    
                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Sign In
                    </button>
                </form>
              
                <!-- Demo Credentials Box -->
                <div class="demo-box">
                    <div class="demo-title">
                        <i class="fas fa-info-circle me-1"></i> Demo Credentials
                    </div>
                    <div class="demo-item">
                        <span class="demo-label">Admin:</span>
                        <span class="demo-value">admin@example.com</span>
                        <span class="demo-copy" onclick="copyToClipboard('admin@example.com')">
                            <i class="far fa-copy"></i> Copy
                        </span>
                    </div>
                    <div class="demo-item">
                        <span class="demo-label">Password:</span>
                        <span class="demo-value">password</span>
                        <span class="demo-copy" onclick="copyToClipboard('password')">
                            <i class="far fa-copy"></i> Copy
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-hide alerts after 3 seconds
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert-auto');
        alerts.forEach(function(alert) {
            alert.style.display = 'none';
        });
    }, 3000);
    
    // Add loading effect on submit
    document.getElementById('loginForm')?.addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing in...';
        btn.disabled = true;
    });

    // Copy to clipboard function
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show temporary tooltip/notification
            const copyBtn = event.target.closest('.demo-copy');
            const originalText = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="fas fa-check me-1"></i> Copied!';
            setTimeout(function() {
                copyBtn.innerHTML = originalText;
            }, 1500);
        });
    }
</script>
@endsection