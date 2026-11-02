@extends('layouts.main')

@section('title', 'Login to BloodConnect')

@section('content')
<div class="container py-5 my-lg-5">
    <div class="row justify-content-center">
        <div class="col-md-5 reveal">
            <div class="glass-card shadow-lg border-0 overflow-hidden">
                <div id="login-header" class="p-4 p-md-5 text-center bg-primary-dark text-white" style="background: var(--primary-dark); transition: background 0.4s ease;">
                    <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3 mb-md-4">
                        <i id="login-header-icon" class="bi bi-shield-lock-fill fs-1 text-danger"></i>
                    </div>
                    <h2 id="login-header-title" class="fw-bold display-font mb-1">Donor Login</h2>
                    <p id="login-header-sub" class="small opacity-75 m-0 text-uppercase tracking-widest">Secure Login Portal</p>
                </div>
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- ── Role Selection Banner ── --}}
                    <div class="p-3 p-md-4" style="background: #f8f9fa; border-bottom: 1px solid rgba(0,0,0,0.06);">
                        <p class="fw-bold text-center small text-uppercase text-muted mb-3" style="letter-spacing:1px;">I am a</p>
                        <div class="d-flex gap-2 gap-md-3 justify-content-center">
                            <div class="flex-fill" style="max-width: 200px;">
                                <input type="radio" class="btn-check" name="role" id="role_donor" value="donor"
                                    {{ old('role', 'donor') === 'donor' ? 'checked' : '' }}>
                                <label class="btn w-100 py-2 py-md-3 role-btn-donor" for="role_donor"
                                       style="border: 2px solid #E63946; border-radius: 14px; font-weight: 700; transition: all 0.3s ease;">
                                    <i class="bi bi-heart-pulse-fill d-block fs-4 fs-md-3 mb-1 text-danger"></i>
                                    <span class="d-block small md-normal">Donor</span>
                                </label>
                            </div>
                            <div class="flex-fill" style="max-width: 200px;">
                                <input type="radio" class="btn-check" name="role" id="role_recipient" value="recipient"
                                    {{ old('role') === 'recipient' ? 'checked' : '' }}>
                                <label class="btn w-100 py-2 py-md-3 role-btn-recipient" for="role_recipient"
                                       style="border: 2px solid #1D3557; border-radius: 14px; font-weight: 700; transition: all 0.3s ease;">
                                    <i class="bi bi-droplet-half d-block fs-4 fs-md-3 mb-1" style="color:#1D3557;"></i>
                                    <span class="d-block small md-normal">Recipient</span>
                                </label>
                            </div>
                            <div class="flex-fill" style="max-width: 200px;">
                                <input type="radio" class="btn-check" name="role" id="role_admin" value="admin"
                                    {{ old('role') === 'admin' ? 'checked' : '' }}>
                                <label class="btn w-100 py-2 py-md-3 role-btn-admin" for="role_admin"
                                       style="border: 2px solid #333; border-radius: 14px; font-weight: 700; transition: all 0.3s ease;">
                                    <i class="bi bi-person-badge d-block fs-4 fs-md-3 mb-1" style="color:#333;"></i>
                                    <span class="d-block small md-normal">Admin</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- ── Important SOP Disclaimer ── --}}
                    <div class="p-3 bg-light border-bottom">
                        <div class="alert alert-warning mb-0 small" style="border-left: 4px solid #ffc107;">
                            <strong>⚠️ Important Disclaimer & SOPs:</strong>
                            <ul class="mb-0 mt-1 ps-3">
                                <li><strong>Always</strong> test blood medically before donating or receiving.</li>
                                <li><strong>Verify</strong> the identity of the person (Check original CNIC).</li>
                                <li>Ensure you are donating to a deserving and verified patient.</li>
                                <li>BloodConnect is a connecting platform and holds no medical liability.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="p-4 p-md-5 bg-white">
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

                        <!-- Email Address -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold small text-muted text-uppercase mb-2">Email Address</label>
                            <input id="email" type="email" class="form-control form-control-lg border-0 bg-light rounded-3 py-3 fs-6" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="password" class="form-label fw-bold small text-muted text-uppercase m-0">Password</label>
                                @if (Route::has('password.request'))
                                    <a class="text-danger small text-decoration-none fw-bold" href="{{ route('password.request') }}">Forgot?</a>
                                @endif
                            </div>
                            <div class="input-group">
                                <input id="password" type="password" class="form-control form-control-lg border-0 bg-light rounded-start-3 py-3 fs-6" name="password" required autocomplete="current-password" placeholder="••••••••">
                                <button class="btn btn-light bg-light border-0 rounded-end-3 px-3 toggle-password" type="button" data-target="password">
                                    <i class="bi bi-eye-fill text-muted"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-4 form-check">
                            <input id="remember_me" type="checkbox" class="form-check-input border-secondary" name="remember">
                            <label for="remember_me" class="form-check-label text-muted small">Stay logged in on this device</label>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" id="submit-btn" class="btn-premium py-3 fs-5">Sign In</button>
                        </div>

                        <div class="text-center pt-2">
                            <p class="text-muted small">
                                Don't have an account? <a href="{{ route('register') }}" class="text-danger fw-bold text-decoration-none">Create a Profile</a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Role button selected states */
    #role_donor:checked + .role-btn-donor {
        background: linear-gradient(135deg, #E63946, #a81c26) !important;
        color: white !important;
        border-color: #E63946 !important;
        box-shadow: 0 6px 20px rgba(230,57,70,0.35);
    }
    #role_donor:checked + .role-btn-donor .text-danger { color: white !important; }

    #role_recipient:checked + .role-btn-recipient {
        background: linear-gradient(135deg, #1D3557, #2a4a73) !important;
        color: white !important;
        border-color: #1D3557 !important;
        box-shadow: 0 6px 20px rgba(29,53,87,0.35);
    }
    #role_recipient:checked + .role-btn-recipient [style*="color:#1D3557"] {
        color: white !important;
    }

    /* Unselected role buttons */
    .role-btn-donor { background: white; color: #E63946; }
    .role-btn-recipient { background: white; color: #1D3557; }
    .role-btn-donor:hover { background: rgba(230,57,70,0.06) !important; }
    .role-btn-recipient:hover { background: rgba(29,53,87,0.06) !important; }
    /* Admin role button */
    #role_admin:checked + .role-btn-admin {
        background: linear-gradient(135deg, #333, #000) !important;
        color: white !important;
        border-color: #333 !important;
        box-shadow: 0 6px 20px rgba(0,0,0,0.35);
    }
    #role_admin:checked + .role-btn-admin [style*="color:#333"] { color: white !important; }
    .role-btn-admin { background: white; color: #333; }
    .role-btn-admin:hover { background: rgba(0,0,0,0.06) !important; }
</style>

<script>
    /* ── Toggle UI based on role ── */
    function toggleLoginUI() {
        const roleEl = document.querySelector('input[name="role"]:checked');
        const isRecipient = roleEl && roleEl.value === 'recipient';
        
        const header = document.getElementById('login-header');
        const headerTitle = document.getElementById('login-header-title');
        const headerIcon = document.getElementById('login-header-icon');
        const submitBtn = document.getElementById('submit-btn');

        const isAdmin = roleEl && roleEl.value === 'admin';

        if (isRecipient) {
            header.style.background = 'linear-gradient(135deg, #1D3557 0%, #2a4a73 100%)';
            headerTitle.textContent = 'Recipient Login';
            headerIcon.className = 'bi bi-droplet-half fs-1';
            headerIcon.style.color = '#90caf9';
            submitBtn.style.background = 'linear-gradient(135deg, #1D3557, #2a4a73)';
            submitBtn.style.boxShadow = '0 8px 25px rgba(29,53,87,0.3)';
            document.body.style.background = 'linear-gradient(135deg, #f0f7ff 0%, #e0eaff 100%)';
            document.body.style.transition = 'background 0.5s ease';
        } else if (isAdmin) {
            header.style.background = 'linear-gradient(135deg, #333 0%, #000 100%)';
            headerTitle.textContent = 'Admin Login';
            headerIcon.className = 'bi bi-person-badge fs-1 text-light';
            headerIcon.style.color = '#fff';
            submitBtn.style.background = 'linear-gradient(135deg, #333, #000)';
            submitBtn.style.boxShadow = '0 8px 25px rgba(0,0,0,0.3)';
            document.body.style.background = '#f8f9fa';
        } else {
            header.style.background = 'var(--primary-dark)';
            headerTitle.textContent = 'Donor Login';
            headerIcon.className = 'bi bi-shield-lock-fill fs-1 text-danger';
            headerIcon.style.color = '';
            submitBtn.style.background = 'var(--gradient-blood)';
            submitBtn.style.boxShadow = '0 8px 25px rgba(230, 57, 70, 0.4)';
            document.body.style.background = 'linear-gradient(135deg, #fff5f5 0%, #ffe3e3 100%)';
            document.body.style.transition = 'background 0.5s ease';
        }
    }

    document.querySelectorAll('input[name="role"]').forEach(function (radio) {
        radio.addEventListener('change', toggleLoginUI);
    });
    toggleLoginUI(); // initialize

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
