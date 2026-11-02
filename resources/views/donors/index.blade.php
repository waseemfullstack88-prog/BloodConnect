@extends('layouts.main')

@section('title', 'Find Blood Donors')

@section('content')
<div class="container py-5">

    {{-- ── Page Heading ─────────────────────────────────────────────────── --}}
    <div class="text-center mb-5 reveal">
        <h6 class="text-danger fw-bold text-uppercase mb-2">Connect with Heroes</h6>
        <h2 class="fw-bold display-4">Find Blood <span class="text-danger">Donors</span></h2>
        <p class="text-muted mt-2">All donors listed are currently eligible to donate.</p>
    </div>

    {{-- ── Search Form ───────────────────────────────────────────────────── --}}
    <div class="row justify-content-center mb-4">
        <div class="col-lg-10">
            <div class="glass-card p-4 p-md-5">
                <form action="{{ route('donors.search') }}" method="GET" class="row g-4 align-items-end">
                    <div class="col-md-4">
                        <label for="name" class="form-label fw-bold small text-muted text-uppercase mb-2">Donor Name</label>
                        <div class="position-relative">
                            <i class="bi bi-person-fill position-absolute text-danger" style="left: 15px; top: 50%; transform: translateY(-50%); z-index: 10;"></i>
                            <input type="search" id="name" name="name" value="{{ $name ?? '' }}" placeholder="Search by name..." class="form-control border-0 bg-light py-3 ps-5 rounded-3">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="blood_group" class="form-label fw-bold small text-muted text-uppercase mb-2">Blood Group</label>
                        <div class="position-relative">
                            <i class="bi bi-droplet-fill position-absolute text-danger" style="left: 15px; top: 50%; transform: translateY(-50%); z-index: 10;"></i>
                            <select id="blood_group" name="blood_group" class="form-select border-0 bg-light py-3 ps-5 rounded-3">
                                <option value="">Any</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                                    <option value="{{ $group }}" {{ (isset($bloodGroup) && $bloodGroup == $group) ? 'selected' : '' }}>
                                        {{ $group }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="city" class="form-label fw-bold small text-muted text-uppercase mb-2">City</label>
                        <div class="position-relative">
                            <i class="bi bi-geo-alt-fill position-absolute text-danger" style="left: 15px; top: 50%; transform: translateY(-50%); z-index: 10;"></i>
                            <input type="text" id="city" name="city" list="cities-list" class="form-control border-0 bg-light py-3 ps-5 rounded-3" placeholder="Any" value="{{ isset($city) ? $city : '' }}" autocomplete="off">
                            <datalist id="cities-list">
                                @foreach(config('cities') as $p_city)
                                    <option value="{{ $p_city }}">
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex flex-column gap-2 justify-content-end">
                        <button type="submit" class="btn-premium w-100 shadow" style="padding: 10px;">Search</button>
                        @if((isset($name) && $name) || (isset($bloodGroup) && $bloodGroup) || (isset($city) && $city))
                            <a href="{{ route('donors.index') }}" class="btn btn-light w-100 shadow-sm border fw-bold text-muted" style="padding: 10px;">Clear All</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-submit form when search input is cleared via the "X" button
        document.getElementById('name').addEventListener('search', function(e) {
            if (this.value === '') {
                this.form.submit();
            }
        });
        
        // Auto-submit if completely deleted
        document.getElementById('name').addEventListener('input', function(e) {
            if (this.value === '') {
                this.form.submit();
            }
        });
    </script>

    {{-- ── Guest Login Prompt Banner ─────────────────────────────────────── --}}
    @guest
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div class="alert border-0 rounded-4 px-4 py-3 d-flex align-items-center gap-3"
                     style="background: linear-gradient(135deg, #fff3cd, #ffeeba); border-left: 4px solid #f59e0b !important;">
                    <i class="bi bi-lock-fill fs-4 text-warning flex-shrink-0"></i>
                    <div>
                        <strong>Login required to contact donors.</strong>
                        <span class="text-muted"> Create an account or sign in to view contact details and connect with donors directly.</span>
                    </div>
                    <a href="{{ route('login') }}"
                       class="btn btn-sm btn-warning rounded-pill px-3 ms-auto fw-bold text-dark text-nowrap">
                        Login / Register
                    </a>
                </div>
            </div>
        </div>
    @endguest

    {{-- ── Results Grid ──────────────────────────────────────────────────── --}}
    <div class="row g-4 reveal">
        @if(isset($donors) && $donors->count() > 0)

            @foreach($donors as $donor)
                @php
                    $neverDonated  = is_null($donor->last_donation_date);
                    $isResting     = !$neverDonated && $donor->last_donation_date->gt(now()->subDays(90));
                    $waNumber      = preg_replace('/^0/', '92', $donor->whatsapp_number ?? '');
                    $isFirstTimer  = $donor->donations_count == 0;
                @endphp

                <div class="col-md-6 col-lg-4">
                    <div class="glass-card h-100 border-0 overflow-hidden position-relative">

                        {{-- ── Availability stripe at very top ──────────── --}}
                        @if($isResting)
                            <div class="w-100 text-center py-1 small fw-semibold text-white"
                                 style="background: linear-gradient(90deg, #A81C26, #E63946); letter-spacing: .03em;">
                                💖 Resting Life Saver — Saved a life recently!
                            </div>
                        @elseif($neverDonated)
                            <div class="w-100 text-center py-1 small fw-semibold"
                                 style="background: linear-gradient(90deg, #d4edda, #c3e6cb); color: #155724; letter-spacing: .03em;">
                                🟢 Available Now — Never Donated
                            </div>
                        @else
                            <div class="w-100 text-center py-1 small fw-semibold"
                                 style="background: linear-gradient(90deg, #d1ecf1, #bee5eb); color: #0c5460; letter-spacing: .03em;">
                                ✅ Eligible — Last donated: {{ $donor->last_donation_date->format('d M, Y') }}
                            </div>
                        @endif

                        <div class="p-4">

                            {{-- ── Header row: blood group + name + city ── --}}
                            <div class="d-flex align-items-center mb-4">
                                {{-- Blood group box --}}
                                <div class="text-white rounded-4 fs-3 fw-bold d-flex align-items-center justify-content-center flex-shrink-0 shadow"
                                     style="width: 70px; height: 70px; background: linear-gradient(135deg, #E63946 0%, #A81C26 100%);">
                                    {{ $donor->blood_group }}
                                </div>

                                <div class="ms-3">
                                    <h5 class="fw-bold m-0 text-dark text-wrap text-break">{{ $donor->name }}</h5>
                                    <p class="text-muted small m-0 mt-1">
                                        <i class="bi bi-geo-alt-fill text-danger"></i>
                                        {{ $donor->city }}, Punjab
                                    </p>

                                    {{-- Availability badge row --}}
                                    <div class="d-flex flex-wrap gap-1 mt-2">
                                        @if($isResting)
                                            <span class="badge rounded-pill fw-semibold px-2 py-1 text-white"
                                                  style="background: linear-gradient(135deg, #6f42c1, #a926a7); font-size:.7rem;">
                                                🎉 Life Saver (Resting)
                                            </span>
                                        @elseif($neverDonated)
                                            <span class="badge rounded-pill fw-semibold px-2 py-1"
                                                  style="background:#198754; font-size:.7rem;">
                                                <i class="bi bi-circle-fill me-1" style="font-size:.45rem;"></i>Available Now
                                            </span>
                                        @else
                                            <span class="badge rounded-pill fw-semibold px-2 py-1"
                                                  style="background:#20c997; font-size:.7rem;">
                                                <i class="bi bi-check-circle-fill me-1"></i>Available
                                            </span>
                                        @endif

                                        @if($isFirstTimer)
                                            <span class="badge rounded-pill fw-semibold px-2 py-1"
                                                  style="background: linear-gradient(135deg,#f59e0b,#d97706); font-size:.7rem;">
                                                ⭐ First Time Donor
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- ── Stats panel ─────────────────────────── --}}
                            @if($isResting)
                                <div class="rounded-4 p-3 mb-4 small"
                                     style="background: rgba(111,66,193,0.06); border: 1px solid rgba(111,66,193,0.15);">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted">
                                            <i class="bi bi-calendar-heart me-1 text-purple" style="color: #6f42c1;"></i>Life Saved On
                                        </span>
                                        <span class="fw-bold text-dark">
                                            {{ $donor->last_donation_date->format('d M, Y') }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">
                                            <i class="bi bi-clock-history me-1 text-purple" style="color: #6f42c1;"></i>Recovery Rest
                                        </span>
                                        <span class="fw-bold" style="color: #6f42c1;">
                                            Resting (Eligible in {{ now()->diffInDays($donor->last_donation_date->copy()->addDays(90)) }} days)
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div class="rounded-4 p-3 mb-4 small"
                                     style="background: rgba(241,250,238,0.8); border: 1px solid rgba(200,230,201,.6);">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted">
                                            <i class="bi bi-calendar-heart me-1 text-danger"></i>Last Donation
                                        </span>
                                        <span class="fw-bold text-dark">
                                            {{ $neverDonated ? 'Never Donated' : $donor->last_donation_date->format('d M, Y') }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">
                                            <i class="bi bi-award-fill me-1 text-danger"></i>Total Donations
                                        </span>
                                        <span class="fw-bold text-dark">
                                            {{ $donor->donations_count }} time(s)
                                        </span>
                                    </div>
                                </div>
                            @endif

                            {{-- ── Contact buttons (auth-gated) ────────── --}}
                            @if($isResting)
                                <div class="d-flex gap-2">
                                    <button class="btn btn-secondary w-100 py-2 d-flex align-items-center justify-content-center gap-2" disabled>
                                        <i class="bi bi-lock-fill"></i> Unavailable (Resting)
                                    </button>
                                </div>
                            @else
                                @auth
                                    <div class="d-flex gap-2">
                                        <a href="tel:{{ $donor->phone }}"
                                           class="btn-premium w-100 py-2 text-center text-decoration-none d-flex align-items-center justify-content-center gap-2">
                                            <i class="bi bi-telephone-outbound-fill"></i> Call Now
                                        </a>
                                        <a href="https://wa.me/{{ $waNumber }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           title="Chat on WhatsApp"
                                           class="btn btn-success rounded-3 px-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                           style="min-width:48px;">
                                            <i class="bi bi-whatsapp fs-5"></i>
                                        </a>
                                    </div>
                                @else
                                    {{-- Locked state for guests --}}
                                    <a href="{{ route('login') }}"
                                       class="d-flex align-items-center justify-content-center gap-2 text-decoration-none rounded-3 py-2 px-3 fw-semibold small w-100"
                                       style="background: linear-gradient(135deg,#f3f4f6,#e5e7eb); color:#374151; border: 1.5px dashed #9ca3af; transition: all .2s;">
                                        <i class="bi bi-lock-fill text-warning"></i>
                                        🔒 Login to Contact Donor
                                    </a>
                                @endauth
                            @endif

                        </div>{{-- /p-4 --}}
                    </div>{{-- /glass-card --}}
                </div>{{-- /col --}}
            @endforeach

        @else
            {{-- ── Empty State ──────────────────────────────────────────── --}}
            <div class="col-12 text-center py-5">
                <div class="opacity-25 mb-4">
                    <i class="bi bi-search" style="font-size: 5rem;"></i>
                </div>
                <h4 class="text-muted fw-bold">No High-Match Donors Found</h4>
                <p class="text-muted mb-4">Try broadening your search or choosing a nearby city in Punjab.</p>
                <a href="{{ route('donors.index') }}" class="btn-premium-outline">Reset Filters</a>
            </div>
        @endif
    </div>{{-- /row --}}

</div>{{-- /container --}}

<style>
    /* Locked contact button hover */
    a[href="{{ route('login') }}"]:hover {
        background: linear-gradient(135deg, #fee2e2, #fecaca) !important;
        border-color: #f87171 !important;
        color: #b91c1c !important;
    }

    /* WhatsApp button pulse on hover */
    .btn-success:hover {
        transform: scale(1.06);
        box-shadow: 0 4px 14px rgba(37,211,102,.45);
    }

    /* Subtle card entrance stagger */
    .col-md-6:nth-child(1) .glass-card { animation-delay: .05s; }
    .col-md-6:nth-child(2) .glass-card { animation-delay: .10s; }
    .col-md-6:nth-child(3) .glass-card { animation-delay: .15s; }
    .col-md-6:nth-child(4) .glass-card { animation-delay: .20s; }
    .col-md-6:nth-child(5) .glass-card { animation-delay: .25s; }
    .col-md-6:nth-child(6) .glass-card { animation-delay: .30s; }
</style>
@endsection
