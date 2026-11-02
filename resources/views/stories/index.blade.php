@extends('layouts.main')

@section('title', 'Moments of Hope - Stories')

@section('content')
<div class="container py-5 my-lg-4">
    <!-- Header -->
    <div class="text-center mb-5 reveal">
        <h6 class="text-danger fw-bold text-uppercase tracking-widest mb-2">Moments of Hope</h6>
        <h2 class="fw-bold display-3 mb-3">Our <span class="text-danger">Stories</span></h2>
        <p class="lead opacity-75 mx-auto" style="max-width: 700px;">
            Watch and read real-life stories from our heroes. See the impact your blood donation can have on someone's life.
        </p>
    </div>

    <!-- Vlog Video Gallery -->
    <div class="row g-4 mb-5 reveal">
        <!-- Video 1 -->
        <div class="col-md-6">
            <div class="glass-card overflow-hidden shadow-lg border-0 h-100 position-relative group">
                <img src="{{ asset('images/vlog1.png') }}" class="w-100 object-fit-cover transition-all duration-500 hover-zoom" style="height: 300px;" alt="Donor Vlog">
                <div class="position-absolute top-0 start-0 w-100 h-100 bg-black bg-opacity-25 flex-center">
                    <i class="bi bi-play-circle-fill text-white drop-shadow" style="font-size: 4rem; opacity: 0.9; cursor: pointer;"></i>
                </div>
                <div class="position-absolute bottom-0 start-0 p-4 text-white w-100 bg-black bg-opacity-50">
                    <span class="badge bg-danger mb-2">VLOG #12</span>
                    <h4 class="fw-bold m-0">Why I Donate Every 3 Months</h4>
                    <p class="small text-white-50 m-0"><i class="bi bi-clock me-1"></i> 5:20 • By Hassan Ali</p>
                </div>
            </div>
        </div>

        <!-- Video 2 -->
        <div class="col-md-6">
            <div class="glass-card overflow-hidden shadow-lg border-0 h-100 position-relative group">
                <img src="{{ asset('images/vlog2.png') }}" class="w-100 object-fit-cover transition-all duration-500 hover-zoom" style="height: 300px;" alt="Ambulance Team Vlog">
                <div class="position-absolute top-0 start-0 w-100 h-100 bg-black bg-opacity-25 flex-center">
                    <i class="bi bi-play-circle-fill text-white drop-shadow" style="font-size: 4rem; opacity: 0.9; cursor: pointer;"></i>
                </div>
                <div class="position-absolute bottom-0 start-0 p-4 text-white w-100 bg-black bg-opacity-50">
                    <span class="badge bg-danger mb-2">BEHIND THE SCENES</span>
                    <h4 class="fw-bold m-0">A Day in the Life of a Rescue Worker</h4>
                    <p class="small text-white-50 m-0"><i class="bi bi-clock me-1"></i> 8:45 • Rescue 1122 Team</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Masonry Grid for Photo Stories -->
    <div class="row g-4 reveal">
        <!-- Story 1 -->
        <div class="col-lg-4 col-md-6">
            <div class="glass-card overflow-hidden h-100 shadow-sm border-0 transition-all hover:scale-105">
                <img src="{{ asset('images/featured_blood_drive.png') }}" class="w-100 object-fit-cover" style="height: 250px;" alt="Blood Drive">
                <div class="p-4 bg-white">
                    <h5 class="fw-bold text-dark mb-2">University Mega Blood Drive</h5>
                    <p class="text-muted small">Over 500 students gathered to donate blood in a single day, breaking the regional record.</p>
                    <a href="#" class="text-danger fw-bold text-decoration-none small">Read More <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Story 2 -->
        <div class="col-lg-4 col-md-6">
            <div class="glass-card overflow-hidden h-100 shadow-sm border-0 transition-all hover:scale-105">
                <img src="{{ asset('images/happy_recipient.png') }}" class="w-100 object-fit-cover" style="height: 250px;" alt="Happy Recipient">
                <div class="p-4 bg-white">
                    <h5 class="fw-bold text-dark mb-2">A Second Chance at Life</h5>
                    <p class="text-muted small">"Without the 4 pints of O- blood from unknown heroes, I wouldn't be here today." - Ayesha's Story.</p>
                    <a href="#" class="text-danger fw-bold text-decoration-none small">Read More <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Story 3 -->
        <div class="col-lg-4 col-md-6">
            <div class="glass-card overflow-hidden h-100 shadow-sm border-0 transition-all hover:scale-105">
                <img src="{{ asset('images/medical_team.png') }}" class="w-100 object-fit-cover" style="height: 250px;" alt="Medical Team">
                <div class="p-4 bg-white">
                    <h5 class="fw-bold text-dark mb-2">Our Dedicated Medical Staff</h5>
                    <p class="text-muted small">Meet the unsung heroes who work around the clock to ensure every donation is safe and swift.</p>
                    <a href="#" class="text-danger fw-bold text-decoration-none small">Read More <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-5 pt-3">
        <a href="{{ route('register') }}" class="btn-premium fs-5 px-5 py-3 shadow-lg">Become a Hero Today</a>
    </div>
</div>
@endsection

<style>
    .flex-center { display: flex; align-items: center; justify-content: center; }
    .object-fit-cover { object-fit: cover; }
    .tracking-widest { letter-spacing: 2px; }
    .hover-zoom:hover { transform: scale(1.05); }
    .transition-all { transition: all 0.5s ease; }
    .hover\:scale-105:hover { transform: scale(1.03); }
    .drop-shadow { filter: drop-shadow(0 4px 6px rgba(0,0,0,0.5)); }
</style>
