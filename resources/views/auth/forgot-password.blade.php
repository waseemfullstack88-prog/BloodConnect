@extends('layouts.main')

@section('title', 'Forgot Password - BloodConnect')

@section('content')
<div class="container py-5 my-lg-5">
    <div class="row justify-content-center">
        <div class="col-md-5 reveal">
            <div class="glass-card shadow-lg border-0 overflow-hidden">
                <div class="p-5 text-center bg-primary-dark text-white" style="background: var(--primary-dark);">
                    <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex p-3 mb-4">
                        <i class="bi bi-key-fill fs-1 text-danger"></i>
                    </div>
                    <h2 class="fw-bold display-font mb-1">Reset Password</h2>
                    <p class="small opacity-75 m-0 text-uppercase tracking-widest">Recovery Portal</p>
                </div>
                
                <div class="p-5 bg-white">
                    <div class="mb-4 text-muted small text-center">
                        Forgot your password? No problem. Select your account type and enter your email address — we'll send you a password reset link.
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success border-0 rounded-4 mb-4 small shadow-sm text-center fw-bold">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 rounded-4 mb-4 small shadow-sm">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        {{-- ── Role Selection ── --}}
                        <div class="mb-4">
                            <p class="fw-bold text-center small text-uppercase text-muted mb-3" style="letter-spacing:1px;">I am a</p>
                            <div class="d-flex gap-2 justify-content-center">
                                <div class="flex-fill" style="max-width:180px;">
                                    <input type="radio" class="btn-check" name="role" id="frole_donor" value="donor"
                                        {{ old('role', 'donor') === 'donor' ? 'checked' : '' }}>
                                    <label class="btn w-100 py-2 frole-btn" for="frole_donor"
                                           style="border:2px solid #E63946; border-radius:12px; font-weight:700; transition:all 0.3s;">
                                        <i class="bi bi-heart-pulse-fill d-block fs-5 mb-1 text-danger"></i>
                                        <span class="small">Donor</span>
                                    </label>
                                </div>
                                <div class="flex-fill" style="max-width:180px;">
                                    <input type="radio" class="btn-check" name="role" id="frole_recipient" value="recipient"
                                        {{ old('role') === 'recipient' ? 'checked' : '' }}>
                                    <label class="btn w-100 py-2 frole-btn" for="frole_recipient"
                                           style="border:2px solid #1D3557; border-radius:12px; font-weight:700; transition:all 0.3s; color:#1D3557;">
                                        <i class="bi bi-droplet-half d-block fs-5 mb-1" style="color:#1D3557;"></i>
                                        <span class="small">Recipient</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold small text-muted text-uppercase mb-2">Email Address</label>
                            <input id="email" type="email" class="form-control form-control-lg border-0 bg-light rounded-3 py-3 fs-6" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn-premium py-3 fs-5">
                                {{ __('Email Reset Link') }}
                            </button>
                        </div>

                        <div class="text-center pt-2">
                            <p class="text-muted small">
                                Remembered your password? <a href="{{ route('login') }}" class="text-danger fw-bold text-decoration-none">Back to Login</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #frole_donor:checked + .frole-btn { background: linear-gradient(135deg,#E63946,#a81c26)!important; color:#fff!important; border-color:#E63946!important; box-shadow:0 6px 20px rgba(230,57,70,.35); }
    #frole_donor:checked + .frole-btn .text-danger { color:#fff!important; }
    #frole_recipient:checked + .frole-btn { background: linear-gradient(135deg,#1D3557,#2a4a73)!important; color:#fff!important; border-color:#1D3557!important; box-shadow:0 6px 20px rgba(29,53,87,.35); }
    #frole_recipient:checked + .frole-btn [style*="color:#1D3557"] { color:#fff!important; }
</style>
@endsection
