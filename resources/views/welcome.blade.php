@extends('layouts.main')

@section('title', 'Welcome to BloodConnect')

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5 position-relative overflow-hidden" style="background: linear-gradient(rgba(29, 53, 87, 0.9), rgba(29, 53, 87, 0.9)), url('{{ asset('images/hero_blood_donation.png') }}') no-repeat center center; background-size: cover; border-radius: 0 0 50px 50px;">
    <div class="container py-5 text-center text-white">
        <div class="reveal">
            <h1 class="display-3 fw-bold mb-4">Donate Blood, Save <span class="text-danger">Lives</span>.</h1>
            <p class="lead mb-5 opacity-75 mx-auto" style="max-width: 700px;">
                Become a hero in someone's life today. Join our unified virtual community of lifesavers across Pakistan and help us bridge the gap between donors and those in need.
            </p>
            <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                <a href="{{ route('register') }}" class="btn-premium fs-5 px-5 py-3 w-100 w-sm-auto">Register as Donor</a>
                <a href="{{ route('donors.index') }}" class="btn-premium-outline bg-white text-dark border-0 fs-5 px-5 py-3 shadow w-100 w-sm-auto">Find Donors</a>
            </div>
        </div>
    </div>

    <!-- Decorative Shape -->
    <div class="position-absolute bottom-0 start-0 w-100 h-25 bg-accent-light" style="clip-path: polygon(0 100%, 100% 100%, 100% 0);"></div>
</section>

<!-- Statistics Section -->
<section class="container py-5 mt-n5">
    <div class="row g-4 justify-content-center">
        <div class="col-md-3">
            <div class="glass-card p-4 text-center">
                <h2 class="display-5 fw-bold text-danger mb-0">{{ number_format($donorCount) }}</h2>
                <p class="text-muted small fw-bold text-uppercase">Verified Donors</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-4 text-center border-start border-danger border-4">
                <h2 class="display-5 fw-bold text-danger mb-0">850+</h2>
                <p class="text-muted small fw-bold text-uppercase">Hearts Healed</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-4 text-center">
                <h2 class="display-5 fw-bold text-danger mb-0" style="font-size: clamp(1.4rem, 3.5vw, 2.2rem); white-space: nowrap; line-height: 1.2;">All Pakistan</h2>
                <p class="text-muted small fw-bold text-uppercase">Active Network</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="container py-5">
    <div class="text-center mb-5 reveal">
        <h6 class="text-danger fw-bold text-uppercase tracking-widest mb-2">Why BloodConnect?</h6>
        <h2 class="fw-bold display-5">A Smarter Way to <span class="text-danger">Donate</span></h2>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
                <div class="glass-card p-5 h-100 border-0">
                <div class="bg-danger bg-opacity-10 rounded-4 p-3 d-inline-block mb-4">
                    <i class="bi bi-search-heart fs-2 text-danger"></i>
                </div>
                <h4 class="fw-bold mb-3">Smart Blood Matching</h4>
                <p class="text-muted">Our intelligent system instantly connects compatible donors with recipients across the entire Punjab network — all online.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card p-5 h-100 border-0">
                <div class="bg-danger bg-opacity-10 rounded-4 p-3 d-inline-block mb-4">
                    <i class="bi bi-shield-check fs-2 text-danger"></i>
                </div>
                <h4 class="fw-bold mb-3">100% Verified</h4>
                <p class="text-muted">Every donor profile is strictly vetted to ensure the highest safety standards for recipients.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card p-5 h-100 border-0">
                <div class="bg-danger bg-opacity-10 rounded-4 p-3 d-inline-block mb-4">
                    <i class="bi bi-lightning-charge-fill fs-2 text-danger"></i>
                </div>
                <h4 class="fw-bold mb-3">Rapid Response</h4>
                <p class="text-muted">In critical situations, our system broadcasts alerts to the nearest donors within seconds.</p>
            </div>
        </div>
    </div>
</section>

<!-- Moments of Hope Gallery (nibb.pk inspired) -->
<section class="bg-white py-5">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-end mb-5 reveal">
            <div>
                <h6 class="text-danger fw-bold text-uppercase mb-2">Our Impact</h6>
                <h2 class="fw-bold display-5 m-0">Moments of <span class="text-danger">Hope</span></h2>
            </div>
            <a href="{{ route('stories.index') }}" class="btn-premium-outline">View All Stories</a>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="position-relative overflow-hidden rounded-4 shadow-lg h-100 group" style="min-height: clamp(240px, 55vw, 500px);">
                    <img src="{{ asset('images/featured_blood_drive.png') }}" class="w-100 h-100 object-fit-cover" alt="Blood Drive Event" style="object-fit: cover;">
                    <div class="position-absolute bottom-0 start-0 p-5 text-white w-100" style="background: linear-gradient(transparent, rgba(0,0,0,0.9));">
                        <span class="badge bg-danger mb-3 px-3 py-2 rounded-pill">FEATURED EVENT</span>
                        <h3 class="fw-bold display-font">LHR Medical Center Drive</h3>
                        <p class="m-0 opacity-75">Successful donation camp with 50+ units collected within 4 hours.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="rounded-4 overflow-hidden position-relative shadow-sm group" style="height: clamp(160px, 30vw, 238px);">
                            <img src="{{ asset('images/volunteer_donor.png') }}" class="w-100 h-100 object-fit-cover transition-all duration-500 hover-zoom" alt="Volunteer Donor">
                            <div class="position-absolute bottom-0 start-0 p-3 text-white w-100 bg-black bg-opacity-40">
                                <p class="small fw-bold m-0 text-truncate">Hero of the Month: Ali Khan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="rounded-4 overflow-hidden position-relative shadow-sm group" style="height: clamp(160px, 30vw, 238px);">
                            <img src="{{ asset('images/medical_team.png') }}" class="w-100 h-100 object-fit-cover transition-all duration-500 hover-zoom" alt="Medical Team">
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-danger bg-opacity-10 flex-center">
                                <i class="bi bi-play-circle-fill fs-1 text-white opacity-90 drop-shadow"></i>
                            </div>
                            <div class="position-absolute bottom-0 start-0 p-3 text-white w-100 bg-black bg-opacity-40">
                                <p class="small fw-bold m-0">Video: Safety Protocols</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="rounded-4 overflow-hidden position-relative shadow-sm" style="height: 238px;">
                            <img src="{{ asset('images/happy_recipient.png') }}" class="w-100 h-100 object-fit-cover" alt="Happy Recipient">
                            <div class="position-absolute bottom-0 start-0 p-4 text-white w-100" style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                <h5 class="fw-bold m-0">Multan Civil Hospital Outreach</h5>
                                <p class="small m-0 text-white-50">Expanding our lifesaver network in South Punjab.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="rounded-4 overflow-hidden shadow-sm" style="height: clamp(180px, 30vw, 250px);">
                    <img src="{{ asset('images/blood_donation_room.png') }}" class="w-100 h-100 object-fit-cover" alt="Donation Room">
                </div>
            </div>
            <div class="col-md-4">
                <div class="rounded-4 shadow-sm h-100 d-flex flex-column justify-content-between p-4"
                     style="background: linear-gradient(135deg, #1D3557 0%, #2a4a73 100%); color: white; min-height: 250px;">
                    <div>
                        <span class="badge bg-danger rounded-pill px-3 py-2 mb-3 fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">
                            🩸 DID YOU KNOW?
                        </span>
                        <h5 class="fw-bold text-white mb-3" style="font-family: 'Outfit', sans-serif; line-height: 1.3;">
                            One donation can save up to <span class="text-danger">3 lives</span>
                        </h5>
                        <ul class="list-unstyled small mb-0" style="opacity: 0.85; line-height: 1.8;">
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i> Every 2 seconds someone needs blood</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i> Blood cannot be manufactured</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i> Only 3% of eligible donors donate</li>
                        </ul>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <span class="small" style="opacity: 0.6;">Punjab Blood Authority</span>
                        <a href="{{ route('register') }}" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold"
                           style="background: var(--gradient-blood); border: none;">
                            Donate Now →
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="rounded-4 overflow-hidden shadow-sm" style="height: clamp(180px, 30vw, 250px);">
                    <img src="{{ asset('images/medical_team_with_patient.png') }}" class="w-100 h-100 object-fit-cover" alt="Medical Team with Patient">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Emergency CTA -->
<section class="container py-5 mb-5">
    <div class="bg-dark rounded-5 p-5 text-white position-relative overflow-hidden shadow-lg border-bottom border-danger border-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="display-3 fw-bold mb-3">Facing an <span class="text-danger">Emergency</span>?</h2>
                <p class="lead opacity-90 mb-0">Don't panic. Our emergency search bypasses the typical queue to find you the most critical donors first.</p>
            </div>
            <div class="col-lg-4 text-center text-lg-end mt-5 mt-lg-0">
                <a href="{{ route('donors.index') }}" class="btn-premium px-4 px-lg-5 py-3 py-lg-4 fs-5 fs-lg-4 shadow-lg d-inline-block text-center">
                    <i class="bi bi-search-heart"></i> Search Emergency Donors
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

<style>
    .mt-n5 { margin-top: -3rem !important; }
    .flex-center { display: flex; align-items: center; justify-content: center; }
    .object-fit-cover { object-fit: cover; }
    .tracking-widest { letter-spacing: 2px; }

    /* ── Mobile Fixes ─────────────────────────────────────────────── */
    @media (max-width: 575.98px) {
        .w-sm-auto { width: auto !important; }

        /* Hero */
        .hero-section { border-radius: 0 0 28px 28px !important; }
        .hero-section .container.py-5 { padding-top: 2.5rem !important; padding-bottom: 2.5rem !important; }
        .hero-section h1 { font-size: 2rem !important; margin-bottom: 0.75rem !important; }
        .hero-section .lead { font-size: 0.9rem !important; margin-bottom: 1.25rem !important; }
        .hero-section .d-flex { flex-direction: column !important; gap: 0.6rem !important; }
        .hero-section .btn-premium,
        .hero-section .btn-premium-outline {
            width: 100% !important;
            text-align: center !important;
            font-size: 1rem !important;
            padding: 0.85rem 1rem !important;
            border-radius: 12px !important;
        }

        /* Stats — 2-per-row */
        .mt-n5 { margin-top: -1rem !important; }
        .mt-n5 .row > div { flex: 0 0 50%; max-width: 50%; }
        .mt-n5 .glass-card { border-radius: 14px !important; padding: 0.85rem !important; }
        .mt-n5 h2.display-5 { font-size: 1.6rem !important; }
        .mt-n5 .text-muted { font-size: 0.7rem !important; }

        /* Section heading */
        .display-5 { font-size: 1.75rem !important; }

        /* Features — full width single col */
        .col-md-4 { flex: 0 0 100%; max-width: 100%; }
        .glass-card.p-5 { padding: 1.25rem !important; }

        /* Gallery images */
        [style*="min-height: clamp"] { min-height: 200px !important; }
        [style*="height: clamp(160"] { height: 150px !important; }
        [style*="height: 238px"] { height: 160px !important; }
        [style*="height: clamp(180"] { height: 160px !important; }

        /* Moments of Hope header */
        .d-flex.justify-content-between.align-items-end { flex-direction: column !important; align-items: flex-start !important; gap: 0.75rem; }

        /* Emergency CTA */
        .bg-dark.rounded-5 { border-radius: 1.25rem !important; padding: 1.5rem !important; }
        .bg-dark.rounded-5 h2 { font-size: 1.6rem !important; }
        .bg-dark.rounded-5 .lead { font-size: 0.9rem !important; }
        .bg-dark.rounded-5 .col-lg-4 { text-align: center !important; }
        .bg-dark.rounded-5 .btn-premium {
            display: block !important;
            width: 100% !important;
            margin-top: 1.25rem !important;
            padding: 0.85rem !important;
            font-size: 1rem !important;
        }

        /* Disable hover lift on touch */
        .glass-card:hover { transform: none !important; box-shadow: none !important; }
    }

    /* Tablet tweaks */
    @media (min-width: 576px) and (max-width: 767.98px) {
        .hero-section h1 { font-size: 2.4rem !important; }
        .bg-dark.rounded-5 h2 { font-size: 2rem !important; }
        .col-md-4 { flex: 0 0 100%; max-width: 100%; }
    }
</style>

