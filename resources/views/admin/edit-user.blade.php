@extends('layouts.main')

@section('title', 'Edit User — Admin · BloodConnect')

@section('content')
<div class="container py-5">
<div class="row justify-content-center">
<div class="col-xl-9 col-lg-10">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                 style="width:52px;height:52px;background:var(--gradient-blood);font-size:.9rem;">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <h2 class="fw-bold display-font mb-0">
                    <i class="bi bi-pencil-fill text-danger me-2"></i>Edit User
                </h2>
                <p class="text-muted mb-0 small">{{ $user->name }} · <span class="text-capitalize">{{ $user->role }}</span></p>
            </div>
        </div>
        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Back to Users
        </a>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-4 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0 small mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('success'))
        <div class="alert border-0 rounded-4 mb-4 d-flex align-items-center" style="background:#d1fae5;color:#065f46;">
            <i class="bi bi-check-circle-fill fs-5 me-2" style="color:#10b981;"></i>
            <span class="fw-bold small">{{ session('success') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" id="editUserForm">
        @csrf

        {{-- ── Section 1: Basic Info ─────────────────────────────────────── --}}
        <div class="glass-card p-4 mb-4">
            <h6 class="fw-bold text-uppercase text-muted mb-4" style="font-size:.72rem;letter-spacing:.1em;">
                <i class="bi bi-person-fill text-danger me-1"></i> Basic Information
            </h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control rounded-3"
                           value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control rounded-3"
                           value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Role <span class="text-danger">*</span></label>
                    <select name="role" id="roleSelect" class="form-select rounded-3" required>
                        <option value="donor"     {{ $user->role == 'donor'     ? 'selected' : '' }}>Donor</option>
                        <option value="recipient" {{ $user->role == 'recipient' ? 'selected' : '' }}>Recipient</option>
                        <option value="admin"     {{ $user->role == 'admin'     ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Gender</label>
                    <select name="gender" class="form-select rounded-3">
                        <option value="">Select gender</option>
                        <option value="Male"   {{ old('gender', $user->gender) == 'Male'   ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other"  {{ old('gender', $user->gender) == 'Other'  ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Date of Birth</label>
                    <input type="date" name="dob" class="form-control rounded-3"
                           value="{{ old('dob', $user->dob ? $user->dob->format('Y-m-d') : '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Father's Name</label>
                    <input type="text" name="father_name" class="form-control rounded-3"
                           value="{{ old('father_name', $user->father_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">CNIC</label>
                    <input type="text" name="cnic" class="form-control rounded-3"
                           placeholder="e.g. 35202-1234567-1"
                           value="{{ old('cnic', $user->cnic) }}">
                </div>
            </div>
        </div>

        {{-- ── Section 2: Contact & Location ────────────────────────────── --}}
        <div class="glass-card p-4 mb-4">
            <h6 class="fw-bold text-uppercase text-muted mb-4" style="font-size:.72rem;letter-spacing:.1em;">
                <i class="bi bi-telephone-fill text-danger me-1"></i> Contact & Location
            </h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Blood Group</label>
                    <select name="blood_group" class="form-select rounded-3">
                        <option value="">Select</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                            <option value="{{ $bg }}" {{ old('blood_group', $user->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Phone</label>
                    <input type="text" name="phone" class="form-control rounded-3"
                           value="{{ old('phone', $user->phone) }}" placeholder="03XX-XXXXXXX">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">WhatsApp</label>
                    <input type="text" name="whatsapp_number" class="form-control rounded-3"
                           value="{{ old('whatsapp_number', $user->whatsapp_number) }}" placeholder="03XX-XXXXXXX">
                </div>
                <div class="col-md-12">
                    <label class="form-label small fw-bold">City</label>
                    <input type="text" name="city" list="cities-list" class="form-control rounded-3" placeholder="Choose or type city" value="{{ old('city', $user->city) }}" autocomplete="off">
                    <datalist id="cities-list">
                        @foreach(config('cities') as $p_city)
                            <option value="{{ $p_city }}">
                        @endforeach
                    </datalist>
                </div>
            </div>
        </div>

        {{-- ── Section 3: Donor-specific ────────────────────────────────── --}}
        <div class="glass-card p-4 mb-4" id="donorSection"
             style="{{ old('role', $user->role) !== 'donor' ? 'display:none;' : '' }}">
            <h6 class="fw-bold text-uppercase text-muted mb-4" style="font-size:.72rem;letter-spacing:.1em;">
                <i class="bi bi-heart-fill text-danger me-1"></i> Donor Details
            </h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Last Donation Date</label>
                    <input type="date" name="last_donation_date" class="form-control rounded-3"
                           value="{{ old('last_donation_date', $user->last_donation_date ? $user->last_donation_date->format('Y-m-d') : '') }}">
                    <div class="form-text">Leave blank if never donated.</div>
                </div>
            </div>
        </div>

        {{-- ── Section 4: Recipient-specific ───────────────────────────── --}}
        <div class="glass-card p-4 mb-4" id="recipientSection"
             style="{{ old('role', $user->role) !== 'recipient' ? 'display:none;' : '' }}">
            <h6 class="fw-bold text-uppercase text-muted mb-4" style="font-size:.72rem;letter-spacing:.1em;">
                <i class="bi bi-hospital-fill text-primary me-1"></i> Recipient / Hospital Details
            </h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Hospital Name</label>
                    <input type="text" name="hospital_name" class="form-control rounded-3"
                           value="{{ old('hospital_name', $user->hospital_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Hospital Location</label>
                    <input type="text" name="hospital_location" class="form-control rounded-3"
                           value="{{ old('hospital_location', $user->hospital_location) }}">
                </div>
                <div class="col-md-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="willing_to_donate"
                               id="willingSwitch" value="1"
                               {{ $user->willing_to_donate ? 'checked' : '' }}>
                        <label class="form-check-label small fw-bold" for="willingSwitch">
                            Willing to Donate (cross-role)
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Section 5: Password Reset ─────────────────────────────────── --}}
        <div class="glass-card p-4 mb-4">
            <h6 class="fw-bold text-uppercase text-muted mb-1" style="font-size:.72rem;letter-spacing:.1em;">
                <i class="bi bi-lock-fill text-warning me-1"></i> Reset Password
            </h6>
            <p class="text-muted small mb-3">Leave blank to keep the current password unchanged.</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">New Password</label>
                    <input type="password" name="password" class="form-control rounded-3"
                           placeholder="Min. 8 characters" autocomplete="new-password">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control rounded-3"
                           placeholder="Repeat new password" autocomplete="new-password">
                </div>
            </div>
        </div>

        {{-- Save Button --}}
        <div class="d-flex gap-2 mb-5">
            <button type="submit" class="btn btn-premium rounded-pill px-5 fw-bold">
                <i class="bi bi-check-circle-fill me-1"></i> Save Changes
            </button>
            <a href="{{ route('admin.users') }}" class="btn btn-light rounded-pill px-4 fw-bold">Cancel</a>
        </div>

    </form>

    {{-- ── Danger Zone ──────────────────────────────────────────────────── --}}
    @if($user->id !== Auth::id())
    <div class="rounded-4 border border-danger p-4 mb-5" style="background:#fff5f5;">
        <h6 class="fw-bold text-danger mb-1">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> Danger Zone
        </h6>
        <p class="text-muted small mb-3">
            Permanently delete <strong>{{ $user->name }}</strong> and <strong>all their related data</strong>
            (donations, blood requests, alerts, notifications). <span class="text-danger fw-bold">This cannot be undone.</span>
        </p>
        <button type="button" class="btn btn-danger rounded-pill px-4 fw-bold"
                data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
            <i class="bi bi-trash3-fill me-1"></i> Delete This User
        </button>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
          <div class="modal-header border-0" style="background:linear-gradient(135deg,#E63946,#9b1d24);padding:1.25rem 1.5rem;">
            <div class="d-flex align-items-center gap-2 text-white">
              <i class="bi bi-exclamation-triangle-fill fs-4"></i>
              <h5 class="modal-title fw-bold mb-0">Confirm Permanent Deletion</h5>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4">
            <p class="mb-2">You are about to permanently delete:</p>
            <div class="d-flex align-items-center gap-3 glass-card p-3 mb-3">
              <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                   style="width:44px;height:44px;background:var(--gradient-blood);font-size:.8rem;">
                {{ strtoupper(substr($user->name, 0, 2)) }}
              </div>
              <div>
                <p class="fw-bold mb-0">{{ $user->name }}</p>
                <p class="text-muted small mb-0">{{ $user->email }} · <span class="text-capitalize">{{ $user->role }}</span></p>
              </div>
            </div>
            <div class="rounded-3 p-3 mb-3" style="background:#fef2f2;">
              <p class="small fw-bold text-danger mb-1"><i class="bi bi-trash3-fill me-1"></i> The following will also be deleted:</p>
              <ul class="mb-0 small text-danger">
                <li>All donations (as donor or recipient)</li>
                <li>All blood requests submitted</li>
                <li>All donor alerts & notifications</li>
                <li>The user account itself</li>
              </ul>
            </div>
            <p class="text-muted small mb-0">Type the user's name below to confirm:</p>
            <input type="text" id="deleteConfirmInput" class="form-control rounded-3 mt-2"
                   placeholder="Type: {{ $user->name }}">
          </div>
          <div class="modal-footer border-0 pt-0 px-4 pb-4">
            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
            <form method="POST" action="{{ route('admin.users.delete', $user->id) }}" id="deleteUserForm">
                @csrf
                @method('DELETE')
                <button type="submit" id="confirmDeleteBtn" class="btn btn-danger rounded-pill px-4 fw-bold" disabled>
                    <i class="bi bi-trash3-fill me-1"></i> Yes, Delete Permanently
                </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    @endif

</div>
</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Role-based section toggle ─────────────────────────────────────────
    const roleSelect      = document.getElementById('roleSelect');
    const donorSection    = document.getElementById('donorSection');
    const recipientSection = document.getElementById('recipientSection');

    function toggleSections() {
        const role = roleSelect.value;
        donorSection.style.display     = role === 'donor'     ? '' : 'none';
        recipientSection.style.display = role === 'recipient' ? '' : 'none';
    }

    roleSelect.addEventListener('change', toggleSections);
    toggleSections();

    // ── Delete confirmation input guard ───────────────────────────────────
    const deleteInput  = document.getElementById('deleteConfirmInput');
    const confirmBtn   = document.getElementById('confirmDeleteBtn');
    const expectedName = {{ json_encode($user->name) }};

    if (deleteInput && confirmBtn) {
        deleteInput.addEventListener('input', function () {
            confirmBtn.disabled = this.value.trim() !== expectedName;
        });
    }
});
</script>
@endpush
