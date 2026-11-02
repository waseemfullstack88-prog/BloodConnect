@extends('layouts.main')

@section('title', 'Join BloodConnect Network')

@section('content')
<div class="container py-4 py-lg-5 my-lg-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-9 col-lg-8 col-xl-7">
            <div class="shadow-lg border-0 overflow-hidden" style="border-radius: 20px;">

                {{-- ── Dynamic Header (changes via JS based on role) ── --}}
                <div id="reg-header" class="p-4 p-md-5 text-center text-white" style="background: var(--primary-dark); transition: background 0.4s ease;">
                    <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                        <i id="reg-header-icon" class="bi bi-heart-pulse-fill fs-1 text-danger"></i>
                    </div>
                    <h2 class="fw-bold display-font mb-1" id="reg-header-title">Register as Donor</h2>
                    <p class="small opacity-75 m-0 text-uppercase" style="letter-spacing:2px;" id="reg-header-sub">Join our blood-saving community</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                {{-- ── Role Selection Banner ── --}}
                <div class="p-3 p-md-4" style="background: #f8f9fa; border-bottom: 1px solid rgba(0,0,0,0.06);">
                    <p class="fw-bold text-center small text-uppercase text-muted mb-3" style="letter-spacing:1px;">I want to register as</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <div class="flex-fill" style="max-width: 200px;">
                            <input type="radio" class="btn-check" name="role" id="role_donor"
                                   value="donor" {{ old('role', 'donor') == 'donor' ? 'checked' : '' }}>
                            <label class="btn w-100 py-3 role-btn-donor" for="role_donor"
                                   style="border: 2px solid #E63946; border-radius: 14px; font-weight: 700; transition: all 0.3s ease;">
                                <i class="bi bi-heart-pulse-fill d-block fs-3 mb-1 text-danger"></i>
                                <span class="d-block">Donor</span>
                                <small class="d-block opacity-75 fw-normal mt-1" style="font-size:0.75rem;">I want to donate</small>
                            </label>
                        </div>
                        <div class="flex-fill" style="max-width: 200px;">
                            <input type="radio" class="btn-check" name="role" id="role_recipient"
                                   value="recipient" {{ old('role') == 'recipient' ? 'checked' : '' }}>
                            <label class="btn w-100 py-3 role-btn-recipient" for="role_recipient"
                                   style="border: 2px solid #1D3557; border-radius: 14px; font-weight: 700; transition: all 0.3s ease;">
                                <i class="bi bi-droplet-half d-block fs-3 mb-1" style="color:#1D3557;"></i>
                                <span class="d-block">Recipient</span>
                                <small class="d-block opacity-75 fw-normal mt-1" style="font-size:0.75rem;">I need blood</small>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- ── Important SOP Disclaimer ── --}}
                <div class="p-3 bg-light border-bottom">
                    <div class="alert alert-warning mb-0 small" style="border-left: 4px solid #ffc107;">
                        <strong>⚠️ Important Registration Disclaimer & SOPs:</strong>
                        <ul class="mb-0 mt-1 ps-3">
                            <li><strong>Always</strong> test blood medically before donating or receiving.</li>
                            <li><strong>Verify</strong> the identity of the person (Check original CNIC).</li>
                            <li>Ensure you are donating to a deserving and verified patient.</li>
                            <li>BloodConnect is a connecting platform and holds no medical liability.</li>
                            <li>You can register both a Donor and Recipient profile using the same email.</li>
                        </ul>
                    </div>
                </div>

                {{-- ── Form Body ── --}}
                <div class="p-3 p-md-5 bg-white">

                    {{-- Global Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 rounded-4 mb-4 small shadow-sm">
                            <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix the following:</strong>
                            <ul class="mb-0 mt-2 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                        {{-- ── Row 1: Full Name | Blood Group ── --}}
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-sm-6">
                                <label for="name" class="form-label fw-bold small text-muted text-uppercase mb-1">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input id="name" type="text"
                                       class="form-control form-control-lg border-0 bg-light rounded-3 py-3 fs-6 {{ $errors->has('name') ? 'border border-danger' : '' }}"
                                       name="name" value="{{ old('name') }}" required autofocus
                                       placeholder="e.g. Wassim Bari">
                                @error('name')
                                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="blood_group" class="form-label fw-bold small text-muted text-uppercase mb-1">
                                    Blood Group <span class="text-danger">*</span>
                                </label>
                                <select id="blood_group" name="blood_group"
                                        class="form-select form-select-lg border-0 bg-light rounded-3 py-3 fs-6 {{ $errors->has('blood_group') ? 'border border-danger' : '' }}" required>
                                    <option value="">Choose blood group</option>
                                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $group)
                                        <option value="{{ $group }}" {{ old('blood_group') == $group ? 'selected' : '' }}>
                                            {{ $group }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('blood_group')
                                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ── Row 2: Father Name | City ── --}}
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-sm-6">
                                <label for="father_name" class="form-label fw-bold small text-muted text-uppercase mb-1">
                                    Father Name <span class="text-danger">*</span>
                                </label>
                                <input id="father_name" type="text"
                                       class="form-control form-control-lg border-0 bg-light rounded-3 py-3 fs-6"
                                       name="father_name" value="{{ old('father_name') }}"
                                       placeholder="e.g. Ali Bari">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="city" class="form-label fw-bold small text-muted text-uppercase mb-1">
                                    City <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="city" name="city" list="cities-list"
                                       class="form-control form-control-lg border-0 bg-light rounded-3 py-3 fs-6 {{ $errors->has('city') ? 'is-invalid' : '' }}" 
                                       placeholder="Choose or type city" value="{{ old('city') }}" required autocomplete="off">
                                <datalist id="cities-list">
                                    @foreach(config('cities') as $p_city)
                                        <option value="{{ $p_city }}">
                                    @endforeach
                                </datalist>
                                @error('city')
                                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ── Row 3: Email | Phone Number ── --}}
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-sm-6">
                                <label for="email" class="form-label fw-bold small text-muted text-uppercase mb-1">
                                    Email Address <span class="text-danger">*</span>
                                </label>
                                <input id="email" type="email"
                                       class="form-control form-control-lg border-0 bg-light rounded-3 py-3 fs-6 {{ $errors->has('email') ? 'border border-danger' : '' }}"
                                       name="email" value="{{ old('email') }}" required
                                       placeholder="your@email.com">
                                @error('email')
                                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="phone" class="form-label fw-bold small text-muted text-uppercase mb-1">
                                    Phone Number <span class="text-danger">*</span>
                                </label>
                                <input id="phone" type="text"
                                       class="form-control form-control-lg border-0 bg-light rounded-3 py-3 fs-6 {{ $errors->has('phone') ? 'border border-danger' : '' }}"
                                       name="phone" value="{{ old('phone') }}" required
                                       placeholder="03xxxxxxxxx" maxlength="11" pattern="03[0-9]{9}">
                                <div class="form-text small text-muted">Format: 03001234567</div>
                                @error('phone')
                                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ── Row 4: WhatsApp | CNIC ── --}}
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-sm-6">
                                <label for="whatsapp_number" class="form-label fw-bold small text-muted text-uppercase mb-1">
                                    WhatsApp Number <span class="text-danger">*</span>
                                </label>
                                <input id="whatsapp_number" type="text"
                                       class="form-control form-control-lg border-0 bg-light rounded-3 py-3 fs-6 border-start border-success border-3 {{ $errors->has('whatsapp_number') ? 'border border-danger' : '' }}"
                                       name="whatsapp_number" value="{{ old('whatsapp_number') }}" required
                                       placeholder="03xxxxxxxxx" maxlength="11" pattern="03[0-9]{9}">
                                <div class="form-check mt-2 small text-success">
                                    <input class="form-check-input border-success" type="checkbox" id="same_as_phone">
                                    <label class="form-check-label fw-semibold" for="same_as_phone" style="cursor:pointer;">
                                        <i class="bi bi-arrow-up-circle me-1"></i>Same as Phone number above
                                    </label>
                                </div>
                                @error('whatsapp_number')
                                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="cnic" class="form-label fw-bold small text-muted text-uppercase mb-1">
                                    CNIC <span class="text-danger">*</span>
                                </label>
                                <input id="cnic" type="text"
                                       class="form-control form-control-lg border-0 bg-light rounded-3 py-3 fs-6"
                                       name="cnic" value="{{ old('cnic') }}"
                                       placeholder="12345-1234567-1" maxlength="15">
                                <div class="form-text small text-muted">Format: 12345-1234567-1</div>
                                @error('cnic')
                                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ── Row 5: Date of Birth | Gender ── --}}
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-sm-6">
                                <label for="dob" class="form-label fw-bold small text-muted text-uppercase mb-1">
                                    Date of Birth <span class="text-danger">*</span>
                                </label>
                                <input id="dob" type="date"
                                       class="form-control form-control-lg border-0 bg-light rounded-3 py-3 fs-6"
                                       name="dob" value="{{ old('dob') }}">
                                <span id="age_display" class="text-success small fw-bold mt-1 d-block"></span>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="gender" class="form-label fw-bold small text-muted text-uppercase mb-1">
                                    Gender <span class="text-danger">*</span>
                                </label>
                                <select id="gender" name="gender" class="form-select form-select-lg border-0 bg-light rounded-3 py-3 fs-6 {{ $errors->has('gender') ? 'border border-danger' : '' }}" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ── Row 6: Hospital Name & Location (recipient only) ── --}}
                        <div class="row g-3 mb-3 recipient-field" style="display: {{ old('role') == 'recipient' ? 'flex' : 'none' }};">
                            <div class="col-12 col-sm-6">
                                <label for="hospital_name" class="form-label fw-bold small text-muted text-uppercase mb-1">
                                    Hospital Name <span class="text-primary small">(Recipient)</span>
                                </label>
                                <input id="hospital_name" type="text"
                                       class="form-control form-control-lg border-0 bg-light rounded-3 py-3 fs-6"
                                       name="hospital_name" value="{{ old('hospital_name') }}"
                                       placeholder="e.g. Jinnah Hospital">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="hospital_location" class="form-label fw-bold small text-muted text-uppercase mb-1">
                                    Hospital Location <span class="text-primary small">(Recipient)</span>
                                </label>
                                <input id="hospital_location" type="text"
                                       class="form-control form-control-lg border-0 bg-light rounded-3 py-3 fs-6"
                                       name="hospital_location" value="{{ old('hospital_location') }}"
                                       placeholder="e.g. Jail Road, Lahore">
                            </div>
                        </div>

                        {{-- ── Recipient-Only Extra Questions ── --}}
                        <div class="recipient-only-fields recipient-field mb-3" style="display: {{ old('role') == 'recipient' ? 'block' : 'none' }};">
                            <div class="rounded-4 p-4 mb-3" style="background: rgba(29,53,87,0.05); border: 1px solid rgba(29,53,87,0.12);">
                                <p class="fw-bold small text-uppercase mb-3" style="color:#1D3557; letter-spacing:1px;">
                                    <i class="bi bi-info-circle-fill me-1"></i> Additional Info for Recipients
                                </p>

                                {{-- Who needs blood? --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">
                                        Who needs blood?
                                    </label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <div>
                                            <input type="radio" class="btn-check" name="needs_blood_for" id="nbf_self" value="self" {{ old('needs_blood_for') == 'self' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger rounded-pill px-4 py-2 fw-semibold" for="nbf_self">
                                                <i class="bi bi-person-fill me-1"></i> Myself
                                            </label>
                                        </div>
                                        <div>
                                            <input type="radio" class="btn-check" name="needs_blood_for" id="nbf_family" value="family" {{ old('needs_blood_for') == 'family' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger rounded-pill px-4 py-2 fw-semibold" for="nbf_family">
                                                <i class="bi bi-people-fill me-1"></i> Family Member
                                            </label>
                                        </div>
                                        <div>
                                            <input type="radio" class="btn-check" name="needs_blood_for" id="nbf_friend" value="friend" {{ old('needs_blood_for') == 'friend' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger rounded-pill px-4 py-2 fw-semibold" for="nbf_friend">
                                                <i class="bi bi-heart-fill me-1"></i> Friend
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Patient CNIC (only shown if family or friend needs blood) --}}
                                <div class="mb-3" id="patient_cnic_wrapper" style="display: none;">
                                    <label for="patient_cnic" class="form-label fw-bold small text-muted text-uppercase mb-1">
                                        Patient CNIC <span class="text-danger">*</span>
                                    </label>
                                    <input id="patient_cnic" type="text"
                                           class="form-control form-control-lg border-0 bg-light rounded-3 py-3 fs-6 @error('patient_cnic') is-invalid @enderror"
                                           name="patient_cnic" value="{{ old('patient_cnic') }}"
                                           placeholder="12345-1234567-1">
                                    <div class="form-text text-muted small mt-1">Format: 12345-1234567-1</div>
                                    @error('patient_cnic')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                {{-- Willing to donate in future? --}}
                                <div>
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">
                                        Willing to donate blood in the future?
                                    </label>
                                    <p class="small text-muted mb-2">
                                        <i class="bi bi-info-circle me-1 text-success"></i>
                                        Select <strong>Yes</strong> to also be registered as a Donor — help others too!
                                    </p>
                                    <div class="d-flex gap-3 flex-wrap">
                                        <div>
                                            <input type="radio" class="btn-check" name="willing_to_donate" id="wtd_yes" value="yes" {{ old('willing_to_donate') == 'yes' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-success rounded-pill px-4 py-2 fw-semibold" for="wtd_yes">
                                                <i class="bi bi-heart-fill me-1 text-danger"></i> Yes, I'm Willing
                                            </label>
                                        </div>
                                        <div>
                                            <input type="radio" class="btn-check" name="willing_to_donate" id="wtd_no" value="no" {{ old('willing_to_donate', 'no') == 'no' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold" for="wtd_no">
                                                <i class="bi bi-x-circle me-1"></i> Not Now
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="mb-4 text-muted opacity-25">

                        {{-- ── Passwords ── --}}
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-sm-6">
                                <label for="password" class="form-label fw-bold small text-muted text-uppercase mb-1">
                                    Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input id="password" type="password"
                                           class="form-control form-control-lg border-0 bg-light rounded-start-3 py-3 fs-6 {{ $errors->has('password') ? 'border border-danger' : '' }}"
                                           name="password" required autocomplete="new-password"
                                           placeholder="Min. 6 characters">
                                    <button class="btn btn-light bg-light border-0 rounded-end-3 px-3 toggle-password" type="button" data-target="password">
                                        <i class="bi bi-eye-fill text-muted"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="password_confirmation" class="form-label fw-bold small text-muted text-uppercase mb-1">
                                    Confirm Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input id="password_confirmation" type="password"
                                           class="form-control form-control-lg border-0 bg-light rounded-start-3 py-3 fs-6"
                                           name="password_confirmation" required
                                           placeholder="Repeat password">
                                    <button class="btn btn-light bg-light border-0 rounded-end-3 px-3 toggle-password" type="button" data-target="password_confirmation">
                                        <i class="bi bi-eye-fill text-muted"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- ── Submit ── --}}
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn-premium py-3 fs-5 shadow" id="submit-btn">
                                <i class="bi bi-person-check-fill me-2"></i>
                                <span id="submit-text">Create My Donor Profile</span>
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="text-muted small mb-0">
                                Already a member?
                                <a href="{{ route('login') }}" class="text-danger fw-bold text-decoration-none">Sign In</a>
                            </p>
                        </div>

                    </form>
                </div>{{-- /form body --}}
            </div>{{-- /card --}}
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

    @media (max-width: 575px) {
        .container { padding-left: 12px; padding-right: 12px; }
        /* Shrink massive form elements on mobile */
        .form-control-lg, .form-select-lg {
            font-size: 0.9rem !important;
            padding-top: 0.6rem !important;
            padding-bottom: 0.6rem !important;
            min-height: 44px;
        }
        /* Reduce card paddings */
        .p-4.p-md-5.text-center { padding: 1.5rem 1rem !important; }
        .p-3.p-md-5.bg-white { padding: 1.25rem !important; }
        
        .d-flex.gap-3 { gap: 0.5rem !important; }
        /* Shrink role buttons */
        .btn.role-btn-donor, .btn.role-btn-recipient {
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
        }
        .role-btn-donor i, .role-btn-recipient i { font-size: 1.5rem !important; }
        /* Reduce CTA height */
        .btn-premium { font-size: 1.1rem !important; padding: 0.75rem !important; }
    }
</style>

<script>
    /* ── WhatsApp same-as-phone sync ── */
    const phoneInput    = document.getElementById('phone');
    const whatsappInput = document.getElementById('whatsapp_number');
    const sameAsPhone   = document.getElementById('same_as_phone');

    sameAsPhone.addEventListener('change', function () {
        if (this.checked) {
            whatsappInput.value    = phoneInput.value;
            whatsappInput.readOnly = true;
            whatsappInput.style.background = 'rgba(25,135,84,0.08)';
            whatsappInput.placeholder = '✓ Synced with phone number';
        } else {
            whatsappInput.value    = '';
            whatsappInput.readOnly = false;
            whatsappInput.style.background = '';
            whatsappInput.placeholder = '03xxxxxxxxx';
        }
    });

    phoneInput.addEventListener('input', function () {
        if (sameAsPhone.checked) {
            whatsappInput.value = this.value;
        }
    });

    /* ── Auto-format CNIC ── */
    function formatCnic(e) {
        let digits = e.target.value.replace(/\D/g, '');
        let match  = digits.match(/(\d{0,5})(\d{0,7})(\d{0,1})/);
        e.target.value = !match[2]
            ? match[1]
            : match[1] + '-' + match[2] + (match[3] ? '-' + match[3] : '');
    }
    document.getElementById('cnic').addEventListener('input', formatCnic);
    
    const patientCnicEl = document.getElementById('patient_cnic');
    if (patientCnicEl) {
        patientCnicEl.addEventListener('input', formatCnic);
    }

    /* ── Toggle Patient CNIC field based on recipient needs ── */
    function togglePatientCnic() {
        const needsBloodFor = document.querySelector('input[name="needs_blood_for"]:checked');
        const patientCnicWrapper = document.getElementById('patient_cnic_wrapper');
        if (patientCnicWrapper) {
            if (needsBloodFor && (needsBloodFor.value === 'family' || needsBloodFor.value === 'friend')) {
                patientCnicWrapper.style.display = 'block';
            } else {
                patientCnicWrapper.style.display = 'none';
            }
        }
    }
    document.querySelectorAll('input[name="needs_blood_for"]').forEach(function (radio) {
        radio.addEventListener('change', togglePatientCnic);
    });
    togglePatientCnic(); // Run on load

    /* ── Toggle recipient fields + update header ── */
    function toggleRoleFields() {
        const roleEl       = document.querySelector('input[name="role"]:checked');
        const isRecipient  = roleEl && roleEl.value === 'recipient';
        const header       = document.getElementById('reg-header');
        const headerTitle  = document.getElementById('reg-header-title');
        const headerSub    = document.getElementById('reg-header-sub');
        const headerIcon   = document.getElementById('reg-header-icon');
        const submitText   = document.getElementById('submit-text');

        document.querySelectorAll('.recipient-field').forEach(function (el) {
            el.style.display = isRecipient
                ? (el.classList.contains('row') ? 'flex' : 'block')
                : 'none';
        });

        if (isRecipient) {
            header.style.background    = 'linear-gradient(135deg, #1D3557 0%, #2a4a73 100%)';
            headerTitle.textContent    = 'Register as Recipient';
            headerSub.textContent      = 'Find blood donors quickly';
            headerIcon.className       = 'bi bi-droplet-half fs-1';
            headerIcon.style.color     = '#90caf9';
            submitText.textContent     = 'Create My Recipient Profile';
            document.body.style.background = 'linear-gradient(135deg, #f0f7ff 0%, #e0eaff 100%)';
            document.body.style.transition = 'background 0.5s ease';
        } else {
            header.style.background    = 'var(--primary-dark)';
            headerTitle.textContent    = 'Register as Donor';
            headerSub.textContent      = 'Join our blood-saving community';
            headerIcon.className       = 'bi bi-heart-pulse-fill fs-1 text-danger';
            headerIcon.style.color     = '';
            submitText.textContent     = 'Create My Donor Profile';
            document.body.style.background = 'linear-gradient(135deg, #fff5f5 0%, #ffe3e3 100%)';
            document.body.style.transition = 'background 0.5s ease';
        }
    }

    document.querySelectorAll('input[name="role"]').forEach(function (radio) {
        radio.addEventListener('change', toggleRoleFields);
    });

    toggleRoleFields(); // run on page load

    /* ── Password Visibility Toggle ── */
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const target = document.getElementById(this.getAttribute('data-target'));
            const icon   = this.querySelector('i');
            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
            } else {
                target.type = 'password';
                icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
            }
        });
    });

    /* ── Auto Calculate Age ── */
    const dobInput = document.getElementById('dob');
    const ageDisplay = document.getElementById('age_display');

    function calculateAge() {
        if (dobInput.value) {
            const dob = new Date(dobInput.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            if(age >= 0) {
                ageDisplay.innerHTML = `<i class="bi bi-person-badge"></i> Calculated Age: ${age} years`;
            } else {
                ageDisplay.innerHTML = `<i class="bi bi-exclamation-triangle"></i> Invalid Date`;
            }
        } else {
            ageDisplay.innerHTML = '';
        }
    }

    dobInput.addEventListener('change', calculateAge);
    calculateAge(); // run on load in case of old input
</script>
@endsection
