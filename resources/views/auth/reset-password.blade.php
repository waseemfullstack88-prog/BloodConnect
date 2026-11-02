@extends('layouts.main')

@section('title', 'Reset Password - BloodConnect')

@section('content')
<div class="container py-5 my-lg-5">
    <div class="row justify-content-center">
        <div class="col-md-5 reveal">
            <div class="glass-card shadow-lg border-0 overflow-hidden">
                <div class="p-5 text-center bg-primary-dark text-white" style="background: var(--primary-dark);">
                    <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex p-3 mb-4">
                        <i class="bi bi-shield-lock-fill fs-1 text-danger"></i>
                    </div>
                    <h2 class="fw-bold display-font mb-1">Set New Password</h2>
                    <p class="small opacity-75 m-0 text-uppercase tracking-widest">Secure Reset Portal</p>
                </div>
                
                <div class="p-5 bg-white">
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

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Role (passed from reset link URL) -->
                        <input type="hidden" name="role" value="{{ old('role', request()->query('role', 'donor')) }}">

                        <!-- Email Address -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold small text-muted text-uppercase mb-2">Email Address</label>
                            <input id="email" type="email" class="form-control form-control-lg border-0 bg-light rounded-3 py-3 fs-6" name="email" value="{{ old('email', $request->email ?? request()->query('email')) }}" required autofocus placeholder="name@example.com">
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold small text-muted text-uppercase mb-2">New Password</label>
                            <div class="input-group">
                                <input id="password" type="password" class="form-control form-control-lg border-0 bg-light rounded-start-3 py-3 fs-6" name="password" required autocomplete="new-password" placeholder="••••••••">
                                <button class="btn btn-light bg-light border-0 rounded-end-3 px-3 toggle-password" type="button" data-target="password">
                                    <i class="bi bi-eye-fill text-muted"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold small text-muted text-uppercase mb-2">Confirm Password</label>
                            <div class="input-group">
                                <input id="password_confirmation" type="password" class="form-control form-control-lg border-0 bg-light rounded-start-3 py-3 fs-6" name="password_confirmation" required placeholder="••••••••">
                                <button class="btn btn-light bg-light border-0 rounded-end-3 px-3 toggle-password" type="button" data-target="password_confirmation">
                                    <i class="bi bi-eye-fill text-muted"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn-premium py-3 fs-5">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    /* ── Password Visibility Toggle ── */
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const target = document.getElementById(this.getAttribute('data-target'));
            const icon = this.querySelector('i');
            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.remove('bi-eye-fill');
                icon.classList.add('bi-eye-slash-fill');
            } else {
                target.type = 'password';
                icon.classList.remove('bi-eye-slash-fill');
                icon.classList.add('bi-eye-fill');
            }
        });
    });
</script>
@endsection
