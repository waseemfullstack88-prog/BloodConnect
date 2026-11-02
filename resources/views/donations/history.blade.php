@extends('layouts.main')

@section('title', 'Donation History — BloodConnect')

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold display-font mb-1"><i class="bi bi-clock-history text-danger me-2"></i>Donation History</h2>
            <p class="text-muted mb-0">Your complete blood donation records</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Dashboard
        </a>
    </div>

    {{-- Stats Summary --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-6">
            <div class="glass-card p-4 text-center h-100" style="border-bottom: 4px solid #E63946;">
                <i class="bi bi-heart-fill fs-2 text-danger d-block mb-2"></i>
                <h2 class="fw-bold display-font m-0">{{ $totalDonations }}</h2>
                <p class="text-muted small mb-0">Total Donations</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="glass-card p-4 text-center h-100" style="border-bottom: 4px solid #1D3557;">
                <i class="bi bi-droplet-fill fs-2 text-danger d-block mb-2"></i>
                <h2 class="fw-bold display-font m-0">{{ $totalUnits }}</h2>
                <p class="text-muted small mb-0">Total Units Donated</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="glass-card p-4 text-center h-100" style="border-bottom: 4px solid #10b981;">
                <i class="bi bi-people-fill fs-2 text-success d-block mb-2"></i>
                <h2 class="fw-bold display-font m-0 text-success">{{ $livesImpacted }}</h2>
                <p class="text-muted small mb-0">Lives Impacted</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="glass-card p-4 text-center h-100" style="border-bottom: 4px solid #6f42c1;">
                <i class="bi bi-calendar-check fs-2 d-block mb-2" style="color: #6f42c1;"></i>
                <h2 class="fw-bold display-font m-0" style="color: #6f42c1; font-size: 1.3rem;">
                    {{ $lastDonation ? $lastDonation->format('d M Y') : 'Never' }}
                </h2>
                <p class="text-muted small mb-0">Last Donation</p>
            </div>
        </div>
    </div>

    {{-- Donation History Table --}}
    <div class="glass-card p-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-list-ul text-danger me-2"></i>All Donations</h5>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">#</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Date</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Blood Group</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Units</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donations as $index => $d)
                    <tr>
                        <td class="small text-muted">{{ $index + 1 }}</td>
                        <td class="small fw-bold">
                            <i class="bi bi-calendar3 me-1 text-danger"></i>
                            {{ \Carbon\Carbon::parse($d->donation_date)->format('d M, Y') }}
                        </td>
                        <td><span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-3 fw-bold">{{ $d->blood_group }}</span></td>
                        <td>
                            <span class="fw-bold">{{ $d->units }}</span>
                            <span class="text-muted small"> unit(s)</span>
                        </td>
                        <td>
                            @php
                                $statusBg = match($d->status) {
                                    'Completed' => 'bg-success',
                                    'Scheduled' => 'bg-info text-dark',
                                    'Cancelled' => 'bg-secondary',
                                    default     => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $statusBg }} rounded-pill px-3 py-1">{{ $d->status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width:72px; height:72px; background:rgba(230,57,70,0.08);">
                                <i class="bi bi-heart fs-2 text-danger opacity-50"></i>
                            </div>
                            <p class="fw-semibold text-muted mb-1">No donations yet</p>
                            <p class="small text-muted mb-0">When you confirm a blood donation, it will appear here.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Encouragement Banner --}}
    @if($totalDonations > 0)
    <div class="rounded-4 mt-4 p-4 text-center text-white"
         style="background: linear-gradient(135deg, #1D3557 0%, #2a4a73 100%); border-left: 6px solid #E63946;">
        <h4 class="fw-bold mb-2">🩸 You're a Life Saver!</h4>
        <p class="mb-0 opacity-75">With {{ $totalDonations }} donation(s) and {{ $totalUnits }} unit(s) donated, you've potentially impacted <strong>{{ $livesImpacted }} lives</strong>. Thank you for your incredible generosity!</p>
    </div>
    @endif
</div>
@endsection
