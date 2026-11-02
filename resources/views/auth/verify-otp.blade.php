@extends('layouts.main')

@section('title', 'Verify OTP — BloodConnect')

@section('content')
<div class="container py-5 my-lg-5">
    <div class="row justify-content-center">
        <div class="col-md-5 reveal">
            <div class="glass-card shadow-lg border-0 overflow-hidden">
                <div class="p-4 p-md-5 text-center bg-primary-dark text-white" style="background: var(--primary-dark);">
                    <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3 mb-md-4">
                        <i class="bi bi-shield-check fs-1 text-danger"></i>
                    </div>
                    <h2 class="fw-bold display-font mb-1">Verify Email</h2>
                    <p class="small opacity-75 m-0 text-uppercase tracking-widest">Enter the 6-Digit OTP</p>
                </div>
                
                <div class="p-4 p-md-5 bg-white">
                    <p class="text-muted text-center small mb-4">
                        We have sent a 6-digit One-Time Password (OTP) to your registered email address <strong>{{ session('registration_data.email') ?? 'your email' }}</strong>.
                    </p>

                    <div class="alert alert-warning border-0 rounded-4 mb-4 small shadow-sm text-center fw-bold" style="background-color: #fff3cd; color: #856404;">
                        <i class="bi bi-exclamation-triangle-fill me-1 text-warning"></i> 
                        Didn't receive the email? Please also check your <span class="text-danger text-uppercase">Spam</span> or <span class="text-danger text-uppercase">Junk</span> folder!
                    </div>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success border-0 rounded-4 mb-4 small shadow-sm text-center fw-bold">
                            {{ session('success') }}
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

                    <form method="POST" action="{{ route('register.otp.verify') }}" id="otp-form">
                        @csrf
                        <input type="hidden" name="otp" id="otp-complete-value">

                        <!-- OTP Inputs row -->
                        <div class="d-flex justify-content-between gap-2 mb-4">
                            @for ($i = 1; $i <= 6; $i++)
                                <input type="text" 
                                       class="form-control form-control-lg text-center fw-bold border-0 bg-light rounded-3 py-3 otp-input-field" 
                                       id="otp_digit_{{ $i }}" 
                                       maxlength="1" 
                                       pattern="[0-9]"
                                       inputmode="numeric"
                                       style="font-size: 1.5rem; transition: all 0.2s ease; border: 2px solid transparent !important;"
                                       required>
                            @endfor
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn-premium py-3 fs-5">Verify & Register</button>
                        </div>
                    </form>

                    <!-- Resend option -->
                    <div class="text-center pt-2">
                        <form method="POST" action="{{ route('register.otp.resend') }}" id="resend-form" class="d-inline">
                            @csrf
                            <p class="text-muted small">
                                Didn't receive the code? 
                                <button type="submit" id="resend-btn" class="btn btn-link p-0 text-danger fw-bold text-decoration-none" style="font-size: 0.875rem;">
                                    Resend OTP
                                </button>
                                <span id="timer-text" class="text-muted fw-semibold ms-1 d-none">(Resend in <span id="countdown">60</span>s)</span>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('.otp-input-field');
    const form = document.getElementById('otp-form');
    const otpVal = document.getElementById('otp-complete-value');
    
    // Auto focus first field
    if(inputs[0]) inputs[0].focus();

    inputs.forEach((input, index) => {
        // Highlighting active input
        input.addEventListener('focus', () => {
            input.style.border = '2px solid #E63946 !important';
            input.style.background = '#ffffff';
        });
        input.addEventListener('blur', () => {
            input.style.border = '2px solid transparent !important';
            input.style.background = '#f8f9fa';
        });

        // Numeric entry restriction & auto-advance
        input.addEventListener('input', (e) => {
            input.value = input.value.replace(/[^0-9]/g, '');
            if (input.value.length === 1) {
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            }
        });

        // Keydown handling for Backspace & arrow keys
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && input.value.length === 0 && index > 0) {
                inputs[index - 1].focus();
            } else if (e.key === 'ArrowLeft' && index > 0) {
                inputs[index - 1].focus();
            } else if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        // Direct paste support!
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text').trim();
            if (/^\d{6}$/.test(pasteData)) {
                for (let i = 0; i < inputs.length; i++) {
                    inputs[i].value = pasteData[i];
                }
                inputs[inputs.length - 1].focus();
            }
        });
    });

    // Form submission assembly
    form.addEventListener('submit', (e) => {
        let fullOtp = '';
        inputs.forEach(input => {
            fullOtp += input.value;
        });
        otpVal.value = fullOtp;
    });

    // Resend countdown timer
    const resendBtn = document.getElementById('resend-btn');
    const timerText = document.getElementById('timer-text');
    const countdown = document.getElementById('countdown');
    
    // Check if session has resent
    if (sessionStorage.getItem('otp_timer_active') === 'true') {
        startTimer(parseInt(sessionStorage.getItem('otp_timer_seconds') || '60'));
    }

    document.getElementById('resend-form').addEventListener('submit', () => {
        sessionStorage.setItem('otp_timer_active', 'true');
        sessionStorage.setItem('otp_timer_seconds', '60');
    });

    function startTimer(seconds) {
        resendBtn.classList.add('disabled');
        resendBtn.style.pointerEvents = 'none';
        timerText.classList.remove('d-none');
        
        let remaining = seconds;
        countdown.textContent = remaining;

        const interval = setInterval(() => {
            remaining--;
            sessionStorage.setItem('otp_timer_seconds', remaining.toString());
            countdown.textContent = remaining;

            if (remaining <= 0) {
                clearInterval(interval);
                sessionStorage.removeItem('otp_timer_active');
                sessionStorage.removeItem('otp_timer_seconds');
                resendBtn.classList.remove('disabled');
                resendBtn.style.pointerEvents = 'auto';
                timerText.classList.add('d-none');
            }
        }, 1000);
    }

    if (sessionStorage.getItem('otp_timer_active') === 'true') {
        startTimer(parseInt(sessionStorage.getItem('otp_timer_seconds') || '60'));
    } else {
        // Automatically start the timer on page load for fresh UX
        startTimer(60);
    }
});
</script>
@endsection
