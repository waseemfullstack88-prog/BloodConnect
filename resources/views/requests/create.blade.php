@extends('layouts.main')

@section('title', 'Post a Blood Request')

@section('content')
<div class="container py-5 my-lg-3">
    <div class="row justify-content-center">
        <div class="col-md-9 reveal">
            <div class="glass-card shadow-lg border-0 overflow-hidden">

                {{-- Card Header --}}
                <div class="p-5 text-center text-white" style="background: var(--primary-dark);">
                    <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex p-3 mb-4">
                        <i class="bi bi-megaphone-fill fs-1 text-danger"></i>
                    </div>
                    <h2 class="fw-bold display-font mb-1">Post a Blood Request</h2>
                    <p class="small opacity-75 m-0 text-uppercase tracking-widest">Connect with donors across Punjab</p>
                </div>

                <div class="p-5 bg-white">

                    {{-- Email Notification Info Banner --}}
                    <div class="alert border-0 rounded-4 mb-4 shadow-sm d-flex align-items-start gap-3"
                         style="background: #EFF6FF; border-left: 4px solid #3B82F6 !important; border-left-width: 4px;">
                        <i class="bi bi-envelope-check-fill fs-4 mt-1" style="color: #3B82F6;"></i>
                        <div>
                            <span class="fw-semibold" style="color: #1E40AF;">📧 Automatic Donor Alerts</span>
                            <p class="mb-0 small mt-1" style="color: #1D4ED8;">
                                Email notifications will be sent to <strong>all donors with a matching blood group</strong> automatically when you submit this request.
                            </p>
                        </div>
                    </div>

                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="alert alert-success border-0 rounded-4 mb-4 shadow-sm d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                            <span class="fw-bold">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 rounded-4 mb-4 small shadow-sm">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Please fix the following errors:</strong>
                            </div>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('requests.store') }}" method="POST">
                        @csrf

                        <div class="row g-4">

                            {{-- Row 1: Patient Name | Patient CNIC --}}
                            <div class="col-md-6">
                                <label for="patient_name" class="form-label fw-bold small text-muted text-uppercase mb-2">
                                    <i class="bi bi-person-fill me-1 text-danger"></i>Patient Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="patient_name" name="patient_name"
                                    class="form-control form-control-lg border-0 bg-light rounded-3 py-3 fs-6"
                                    placeholder="e.g. Ali Ahmed"
                                    value="{{ old('patient_name', Auth::user()->needs_blood_for === 'self' ? Auth::user()->name : '') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="patient_cnic" class="form-label fw-bold small text-muted text-uppercase mb-2">
                                    <i class="bi bi-card-text me-1 text-danger"></i>Patient CNIC <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="patient_cnic" name="patient_cnic"
                                    class="form-control form-control-lg border-0 bg-light rounded-3 py-3 fs-6 @error('patient_cnic') is-invalid @enderror"
                                    placeholder="xxxxx-xxxxxxx-x"
                                    value="{{ old('patient_cnic', Auth::user()->needs_blood_for === 'self' ? Auth::user()->cnic : (Auth::user()->patient_cnic ?? '')) }}" required>
                                @error('patient_cnic')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Row 2: Blood Group | Units | Urgency --}}
                            <div class="col-md-4">
                                <label for="blood_group" class="form-label fw-bold small text-muted text-uppercase mb-2">
                                    <i class="bi bi-droplet-fill me-1 text-danger"></i>Blood Group Needed <span class="text-danger">*</span>
                                </label>
                                <select id="blood_group" name="blood_group"
                                    class="form-select form-select-lg border-0 bg-light rounded-3 py-3 fs-6" required>
                                    <option value="">Choose blood group</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                                        <option value="{{ $group }}" {{ old('blood_group', (Auth::user()->needs_blood_for === 'self' ? Auth::user()->blood_group : '')) == $group ? 'selected' : '' }}>
                                            {{ $group }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="units" class="form-label fw-bold small text-muted text-uppercase mb-2">
                                    <i class="bi bi-bag-fill me-1 text-danger"></i>Number of Units (Bags) <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="units" name="units"
                                    class="form-control form-control-lg border-0 bg-light rounded-3 py-3 fs-6"
                                    placeholder="e.g. 2" min="1" max="10"
                                    value="{{ old('units') }}" required>
                            </div>

                            <div class="col-md-4">
                                <label for="urgency" class="form-label fw-bold small text-muted text-uppercase mb-2">
                                    <i class="bi bi-exclamation-circle-fill me-1 text-danger"></i>Urgency Level <span class="text-danger">*</span>
                                </label>
                                <select id="urgency" name="urgency"
                                    class="form-select form-select-lg border-0 bg-light rounded-3 py-3 fs-6" required>
                                    <option value="Normal"   {{ old('urgency', 'Normal') == 'Normal'   ? 'selected' : '' }}>🟢 Normal</option>
                                    <option value="High"     {{ old('urgency') == 'High'               ? 'selected' : '' }}>🟡 High (Immediate)</option>
                                    <option value="Critical" {{ old('urgency') == 'Critical'           ? 'selected' : '' }}>🔴 Critical (Emergency)</option>
                                </select>
                            </div>

                            {{-- Row 3: Location (full width) --}}
                            <div class="col-12">
                                <label for="location" class="form-label fw-bold small text-muted text-uppercase mb-2">
                                    <i class="bi bi-hospital-fill me-1 text-danger"></i>Hospital / Donation Location
                                </label>
                                <input type="text" id="location" name="location"
                                    class="form-control form-control-lg border-0 bg-light rounded-3 py-3 fs-6"
                                    placeholder="e.g. Mayo Hospital, Lahore"
                                    value="{{ old('location') }}" required>
                            </div>

                            {{-- Row 4: Contact Details (readonly, visible to donors) --}}
                            <div class="col-12">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-telephone-fill text-danger"></i>
                                    <span class="fw-bold small text-muted text-uppercase">Your Contact Details for Donors</span>
                                </div>
                                <div class="alert border-0 rounded-3 mb-3 py-2 px-3 d-flex align-items-center gap-2"
                                     style="background: #FEF3C7; font-size: 0.8rem; color: #92400E;">
                                    <i class="bi bi-lock-fill"></i>
                                    Donors will see this contact info when responding to your request. These are pre-filled from your profile.
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="contact_phone" class="form-label fw-bold small text-muted text-uppercase mb-2">
                                            <i class="bi bi-phone-fill me-1"></i>Phone Number
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text border-0 bg-light rounded-start-3">
                                                <i class="bi bi-telephone text-muted"></i>
                                            </span>
                                            <input type="text" id="contact_phone" name="contact_phone"
                                                class="form-control form-control-lg border-0 bg-light rounded-end-3 py-3 fs-6"
                                                placeholder="e.g. 0300-1234567"
                                                value="{{ old('contact_phone', Auth::user()->phone ?? '') }}"
                                                readonly
                                                style="cursor: not-allowed; opacity: 0.85;">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="contact_whatsapp" class="form-label fw-bold small text-muted text-uppercase mb-2">
                                            <i class="bi bi-whatsapp me-1"></i>WhatsApp Number
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text border-0 bg-light rounded-start-3">
                                                <i class="bi bi-whatsapp text-success"></i>
                                            </span>
                                            <input type="text" id="contact_whatsapp" name="contact_whatsapp"
                                                class="form-control form-control-lg border-0 bg-light rounded-end-3 py-3 fs-6"
                                                placeholder="e.g. 0300-1234567"
                                                value="{{ old('contact_whatsapp', Auth::user()->whatsapp_number ?? '') }}"
                                                readonly
                                                style="cursor: not-allowed; opacity: 0.85;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Row 5: Description (full width) --}}
                            <div class="col-12">
                                <label for="description" class="form-label fw-bold small text-muted text-uppercase mb-2">
                                    <i class="bi bi-chat-text-fill me-1 text-danger"></i>Additional Information
                                </label>
                                <textarea id="description" name="description" rows="4"
                                    class="form-control border-0 bg-light rounded-3 fs-6 py-3"
                                    placeholder="Describe the reason or any additional details for the donor...">{{ old('description') }}</textarea>
                            </div>

                        </div>{{-- /row --}}

                        {{-- Submit Button --}}
                        <div class="d-grid mt-5">
                            <button type="submit" class="btn-premium py-3 fs-5 shadow">
                                <i class="bi bi-send-fill me-2"></i> Submit Blood Request
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('dashboard') }}" class="text-muted small text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                            </a>
                        </div>

                    </form>
                </div>{{-- /bg-white --}}
            </div>{{-- /glass-card --}}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const patientCnicEl = document.getElementById('patient_cnic');
    if (patientCnicEl) {
        patientCnicEl.addEventListener('input', function (e) {
            let digits = e.target.value.replace(/\D/g, '');
            let match  = digits.match(/(\d{0,5})(\d{0,7})(\d{0,1})/);
            e.target.value = !match[2]
                ? match[1]
                : match[1] + '-' + match[2] + (match[3] ? '-' + match[3] : '');
        });
    }
});
</script>
@endsection
