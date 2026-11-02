@extends('layouts.main')

@section('title', 'Edit Profile — BloodConnect')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold display-font mb-1"><i class="bi bi-person-gear text-danger me-2"></i>Edit Profile</h2>
                    <p class="text-muted mb-0">Update your personal information</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-1"></i> Dashboard
                </a>
            </div>

            @if(session('success'))
                <div class="alert border-0 shadow-sm rounded-4 mb-4 p-3 d-flex align-items-center" style="background: #d1fae5; color: #065f46;">
                    <i class="bi bi-check-circle-fill fs-4 me-3" style="color: #10b981;"></i>
                    <p class="fw-bold mb-0 small">{{ session('success') }}</p>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger border-0 rounded-4 mb-4">
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="glass-card p-4">
                {{-- Profile Header --}}
                <div class="text-center mb-4 pb-4" style="border-bottom: 1px solid rgba(0,0,0,0.06);">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold mx-auto mb-3"
                         style="width:80px; height:80px; background: var(--gradient-blood); font-size:1.5rem;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <span class="badge bg-danger rounded-pill px-3 py-1 fw-bold">{{ $user->blood_group }}</span>
                    <span class="badge bg-dark rounded-pill px-3 py-1 text-capitalize ms-1">{{ $user->role }}</span>
                </div>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Full Name <i class="bi bi-lock-fill text-muted ms-1" title="This field is locked"></i></label>
                            <input type="text" name="name" class="form-control rounded-3 bg-light text-muted" value="{{ old('name', $user->name) }}" readonly required style="cursor: not-allowed;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email <i class="bi bi-lock-fill text-muted ms-1" title="This field is locked"></i></label>
                            <input type="email" name="email" class="form-control rounded-3 bg-light text-muted" value="{{ old('email', $user->email) }}" readonly required style="cursor: not-allowed;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Blood Group <i class="bi bi-lock-fill text-muted ms-1" title="This field is locked"></i></label>
                            <select class="form-select rounded-3 bg-light text-muted" disabled style="cursor: not-allowed;">
                                <option value="{{ $user->blood_group }}">{{ $user->blood_group }}</option>
                            </select>
                            <input type="hidden" name="blood_group" value="{{ $user->blood_group }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">City <span class="text-danger">*</span></label>
                            <input type="text" name="city" list="cities-list" class="form-control rounded-3" placeholder="Choose or type city" value="{{ old('city', $user->city) }}" required autocomplete="off">
                            <datalist id="cities-list">
                                @foreach(config('cities') as $p_city)
                                    <option value="{{ $p_city }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Date of Birth <i class="bi bi-lock-fill text-muted ms-1" title="This field is locked"></i></label>
                            <input type="date" class="form-control rounded-3 bg-light text-muted" value="{{ old('dob', $user->dob ? $user->dob->format('Y-m-d') : '') }}" readonly style="cursor: not-allowed;">
                            <input type="hidden" name="dob" value="{{ $user->dob ? $user->dob->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control rounded-3" value="{{ old('phone', $user->phone) }}" placeholder="03xxxxxxxxx" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" name="whatsapp_number" class="form-control rounded-3" value="{{ old('whatsapp_number', $user->whatsapp_number) }}" placeholder="03xxxxxxxxx" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">CNIC <i class="bi bi-lock-fill text-muted ms-1" title="This field is locked"></i></label>
                            <input type="text" name="cnic" class="form-control rounded-3 bg-light text-muted" value="{{ old('cnic', $user->cnic) }}" readonly style="cursor: not-allowed;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Father's Name <i class="bi bi-lock-fill text-muted ms-1" title="This field is locked"></i></label>
                            <input type="text" name="father_name" class="form-control rounded-3 bg-light text-muted" value="{{ old('father_name', $user->father_name) }}" readonly style="cursor: not-allowed;">
                        </div>
                        @if($user->role == 'recipient')
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Hospital Name</label>
                            <input type="text" name="hospital_name" class="form-control rounded-3" value="{{ old('hospital_name', $user->hospital_name) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Hospital Location</label>
                            <input type="text" name="hospital_location" class="form-control rounded-3" value="{{ old('hospital_location', $user->hospital_location) }}">
                        </div>
                        @endif
                    </div>

                    <div class="mt-4 pt-3" style="border-top: 1px solid rgba(0,0,0,0.06);">
                        <button type="submit" class="btn btn-premium rounded-pill px-5 fw-bold">
                            <i class="bi bi-check-circle-fill me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
