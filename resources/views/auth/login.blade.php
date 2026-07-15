@extends('layouts.app')

@section('content')
<style>
    body {
        /* Optional: add a subtle pattern or gradient to the body specifically for the login page if needed, 
           but the layout already handles the background color */
    }
    
    .login-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .login-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
        width: 100%;
        max-width: 450px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        transform: translateY(0);
        animation: floatUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }

    @keyframes floatUp {
        0% { transform: translateY(30px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }

    .login-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        padding: 2.5rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .login-header::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at center, rgba(16, 185, 129, 0.15) 0%, transparent 50%);
        animation: rotateBg 20s linear infinite;
    }

    .login-title {
        color: #ffffff;
        font-family: 'Outfit', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
        position: relative;
        z-index: 2;
    }
    
    .login-subtitle {
        color: #94a3b8;
        font-size: 0.95rem;
        margin-top: 0.5rem;
        position: relative;
        z-index: 2;
    }

    .login-body {
        padding: 2.5rem 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .custom-input {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        color: #0f172a;
        transition: all 0.2s ease;
        width: 100%;
    }

    .custom-input:focus {
        background: #ffffff;
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        outline: none;
    }

    .custom-btn {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.85rem;
        font-weight: 600;
        font-family: 'Outfit', sans-serif;
        width: 100%;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        margin-top: 1rem;
    }

    .custom-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .form-check-input:checked {
        background-color: #10b981;
        border-color: #10b981;
    }

    .forgot-password {
        color: #64748b;
        font-size: 0.9rem;
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .forgot-password:hover {
        color: #10b981;
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h2 class="login-title">Admin Access</h2>
            <div class="login-subtitle">Sign in to manage Foodinfo</div>
        </div>
        
        <div class="login-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <input id="email" type="email" class="custom-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="admin@example.com">

                    @error('email')
                        <span class="invalid-feedback d-block mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input id="password" type="password" class="custom-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">

                    @error('password')
                        <span class="invalid-feedback d-block mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                    <div class="form-check">
                        <input class="form-check-input shadow-none" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-muted small fw-medium" for="remember">
                            {{ __('Remember me') }}
                        </label>
                    </div>
                    
                    @if (Route::has('password.request'))
                        <a class="forgot-password fw-medium" href="{{ route('password.request') }}">
                            {{ __('Forgot Password?') }}
                        </a>
                    @endif
                </div>

                <button type="submit" class="custom-btn">
                    <i class="fas fa-sign-in-alt me-2"></i> {{ __('Secure Login') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
