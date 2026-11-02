@extends('layouts.main')

@section('title', 'Dashboard — BloodConnect')

@section('content')
<div class="container py-5">
    <div class="row g-4">

        {{-- ══════════════════════════════════════════════════
             SIDEBAR
        ══════════════════════════════════════════════════ --}}
        <div class="col-lg-3">
            <div class="glass-card overflow-hidden sticky-top-lg" style="z-index: 1;">

                {{-- Profile Header --}}
                <div class="p-4 text-center text-white" style="background: var(--primary-dark);">
                    <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-person-fill fs-1 text-danger"></i>
                    </div>
                    <h5 class="fw-bold mb-0">{{ Auth::user()->name }}</h5>
                    <p class="small opacity-75 mb-1">{{ Auth::user()->email }}</p>
                    <span class="badge rounded-pill px-3 py-1 mt-1 text-capitalize"
                          style="background: rgba(255,255,255,0.15); font-size: 0.72rem; letter-spacing: .5px;">
                        {{ Auth::user()->role }}
                    </span>
                    @if(Auth::user()->blood_group)
                        <br>
                        <span class="badge bg-danger rounded-pill px-3 py-1 mt-2 fw-bold" style="font-size: 0.8rem;">
                            {{ Auth::user()->blood_group }}
                        </span>
                    @endif
                </div>

                {{-- Nav Links --}}
                <div class="list-group list-group-flush p-3">

                    <a href="{{ route('dashboard') }}"
                       class="list-group-item list-group-item-action border-0 rounded-3 mb-2 py-3 active-nav">
                        <i class="bi bi-grid-1x2-fill me-2"></i> Overview
                    </a>

                    @if(Auth::user()->role == 'recipient')
                    <a href="{{ route('requests.create') }}"
                       class="list-group-item list-group-item-action border-0 rounded-3 mb-2 py-3 fw-semibold text-danger">
                        <i class="bi bi-plus-circle-fill me-2"></i> Post Blood Request
                    </a>
                    @endif

                    <a href="{{ route('donors.index') }}"
                       class="list-group-item list-group-item-action border-0 rounded-3 mb-2 py-3">
                        <i class="bi bi-search me-2"></i> Find Donors
                    </a>

                    @if(Auth::user()->role == 'donor' || (Auth::user()->role == 'recipient' && Auth::user()->willing_to_donate))
                    <a href="#notifications-panel"
                       class="list-group-item list-group-item-action border-0 rounded-3 mb-2 py-3 d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-bell-fill me-2"></i> Alerts</span>
                        @if($unreadCount > 0)
                            <span class="badge bg-danger rounded-pill" style="min-width:22px; font-size:.7rem;">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>
                    @endif

                    <a href="{{ route('profile.edit') }}"
                       class="list-group-item list-group-item-action border-0 rounded-3 mb-2 py-3">
                        <i class="bi bi-gear-fill me-2"></i> Settings
                    </a>

                    {{-- Logout --}}
                    <div class="mt-2 pt-2" style="border-top: 1px solid rgba(0,0,0,0.06);">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="list-group-item list-group-item-action border-0 rounded-3 py-3 text-danger w-100 text-start bg-transparent"
                                    style="cursor:pointer;">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════
             MAIN CONTENT
        ══════════════════════════════════════════════════ --}}
        <div class="col-lg-9">

            @php
                $isResting = Auth::user()->last_donation_date && Auth::user()->last_donation_date->gt(now()->subDays(90));
                $daysRemaining = $isResting ? now()->diffInDays(Auth::user()->last_donation_date->copy()->addDays(90)) : 0;
            @endphp

            @if($isResting && (Auth::user()->role == 'donor' || (Auth::user()->role == 'recipient' && Auth::user()->willing_to_donate)))
                <div class="alert border-0 shadow-sm rounded-4 mb-4 p-4 d-flex align-items-start"
                     style="background: rgba(111, 66, 193, 0.08); color: #4c1d95; border-left: 5px solid #6f42c1 !important;" role="alert">
                    <i class="bi bi-heart-fill fs-2 me-3 mt-1" style="color: #6f42c1;"></i>
                    <div class="flex-grow-1">
                        <h5 class="fw-bold mb-1" style="color: #6f42c1;">💖 Recovery Resting Phase Active ({{ $daysRemaining }} days remaining)</h5>
                        <p class="mb-0 small text-muted">
                            Thank you for your recent life-saving blood donation on <strong>{{ Auth::user()->last_donation_date->format('d M, Y') }}</strong>! 
                            To protect your health, you cannot donate again or click the "Donated" button until your 90-day recovery window completes. You will be automatically notified via email when you are fully eligible.
                        </p>
                    </div>
                </div>
            @endif

            {{-- Flash Message --}}
            @if(session('success'))
                <div class="alert border-0 shadow-sm rounded-4 mb-4 p-4 d-flex align-items-center"
                     style="background: #d1fae5; color: #065f46;" role="alert">
                    <i class="bi bi-check-circle-fill fs-3 me-3" style="color: #10b981;"></i>
                    <div class="flex-grow-1">
                        <p class="fw-bold mb-0 small">{{ session('success') }}</p>
                    </div>
                    <button type="button" class="btn-close ms-3" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 p-4 d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                    <div class="flex-grow-1">
                        <p class="fw-bold mb-0 small">{{ session('error') }}</p>
                    </div>
                    <button type="button" class="btn-close ms-3" data-bs-dismiss="alert"></button>
                </div>
            @endif


            {{-- ══════════════════════════════════════════════════
                 DONOR DASHBOARD
            ══════════════════════════════════════════════════ --}}
            @if(Auth::user()->role == 'donor')

                {{-- Stats Row --}}
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="glass-card p-4 h-100" style="border-bottom: 4px solid #E63946;">
                            <p class="text-muted small fw-bold text-uppercase mb-2">Live Requests</p>
                            <h1 class="fw-bold m-0 display-font">{{ $recipientRequests->count() }}</h1>
                            <span class="text-danger small fw-bold mt-1 d-block">
                                <i class="bi bi-activity me-1"></i> Active Now
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card p-4 h-100" style="border-bottom: 4px solid #1D3557;">
                            <p class="text-muted small fw-bold text-uppercase mb-2">My Donations</p>
                            <h1 class="fw-bold m-0 display-font">{{ $totalDonationsCount }}</h1>
                            <span class="text-muted small mt-1 d-block">Thank you for saving lives</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card p-4 h-100" style="border-bottom: 4px solid #10b981;">
                            <p class="text-muted small fw-bold text-uppercase mb-2">Unread Alerts</p>
                            <h1 class="fw-bold m-0 display-font {{ $unreadCount > 0 ? 'text-danger' : 'text-success' }}">
                                {{ $unreadCount }}
                            </h1>
                            <span class="small mt-1 d-block {{ $unreadCount > 0 ? 'text-danger fw-bold' : 'text-muted' }}">
                                {{ $unreadCount > 0 ? 'New blood requests!' : 'All caught up ✓' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- ── NOTIFICATION / ALERT PANEL ── --}}
                <div class="glass-card p-4 mb-4" id="notifications-panel">

                    {{-- Panel Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="fw-bold m-0">
                                <i class="bi bi-bell-fill text-danger me-2"></i>Blood Request Alerts
                            </h5>
                            @if($unreadCount > 0)
                                <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size:.72rem;">
                                    {{ $unreadCount }} new
                                </span>
                            @endif
                        </div>

                        @if($unreadCount > 0)
                            <form method="POST" action="{{ route('notifications.readAll') }}" class="m-0">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold">
                                    <i class="bi bi-check2-all me-1"></i> Mark All Read
                                </button>
                            </form>
                        @endif
                    </div>

                    {{-- Info strip when no unread --}}
                    @if($unreadCount == 0 && $donorNotifications->count() > 0)
                        <div class="rounded-3 px-3 py-2 mb-3 small text-muted d-flex align-items-center gap-2"
                             style="background: rgba(16,185,129,0.07); border: 1px solid rgba(16,185,129,0.2);">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            All alerts have been read. You are up to date.
                        </div>
                    @endif

                    {{-- Alert Items --}}
                    @if($donorNotifications->count() > 0)
                        <div class="d-flex flex-column gap-3">
                            @foreach($donorNotifications as $alert)
                                @php
                                    $urgency = $alert->urgency_level;
                                    $isUnread = !$alert->is_read;

                                    $borderColor = match($urgency) {
                                        'Critical' => '#E63946',
                                        'High'     => '#f59e0b',
                                        default    => '#3b82f6',
                                    };
                                    $urgencyBg = match($urgency) {
                                        'Critical' => '#E63946',
                                        'High'     => '#f59e0b',
                                        default    => '#3b82f6',
                                    };
                                    $waClean = preg_replace('/\D/', '', $alert->requester_whatsapp ?? '');
                                @endphp

                                <div class="rounded-4 p-3 alert-card"
                                     style="border: 1px solid rgba(0,0,0,0.07);
                                            border-left: 5px solid {{ $borderColor }};
                                            background: {{ $isUnread ? 'rgba(230,57,70,0.04)' : 'rgba(255,255,255,0.5)' }};
                                            transition: background .3s ease;">

                                    <div class="d-flex align-items-start gap-3">
                                        {{-- Blood Group Circle --}}
                                        <div class="flex-shrink-0 rounded-3 d-flex align-items-center justify-content-center fw-bold text-white"
                                             style="width:52px; height:52px; background:{{ $borderColor }}; font-size:1rem; line-height:1;">
                                            {{ $alert->blood_group }}
                                        </div>

                                        {{-- Main Content --}}
                                        <div class="flex-grow-1 min-w-0">

                                            {{-- Top row: badges + time --}}
                                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-1">
                                                <div class="d-flex flex-wrap align-items-center gap-2">
                                                    <span class="badge rounded-pill px-2 py-1 text-white"
                                                          style="background:{{ $urgencyBg }}; font-size:.7rem;">
                                                        {{ $urgency }}
                                                    </span>
                                                    @if($isUnread)
                                                        <span class="badge rounded-pill px-2 py-1"
                                                              style="background: rgba(230,57,70,0.12); color:#E63946; font-size:.68rem;">
                                                            ● New
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="text-muted" style="font-size:.72rem;">
                                                    {{ $alert->created_at->diffForHumans() }}
                                                </span>
                                            </div>

                                            {{-- Patient Name --}}
                                            <p class="fw-bold text-dark mb-1" style="font-size:.95rem;">
                                                Patient: {{ $alert->patient_name }}
                                            </p>

                                            {{-- Hospital & Units --}}
                                            <p class="small text-muted mb-2">
                                                <i class="bi bi-hospital me-1"></i>
                                                {{ $alert->hospital_location }}
                                                &nbsp;&bull;&nbsp;
                                                <i class="bi bi-droplet-fill text-danger me-1"></i>
                                                {{ $alert->units }} unit(s) needed
                                            </p>

                                            {{-- Requester Info --}}
                                            <p class="small text-muted mb-2">
                                                <i class="bi bi-person-fill me-1"></i>
                                                Requested by: <strong>{{ $alert->requester_name }}</strong>
                                            </p>

                                            {{-- Action Buttons --}}
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                @if(!empty($alert->requester_phone))
                                                    <a href="tel:{{ $alert->requester_phone }}"
                                                       class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                        <i class="bi bi-telephone-fill me-1"></i>
                                                        {{ $alert->requester_phone }}
                                                    </a>
                                                @endif

                                                @if(!empty($waClean))
                                                    <a href="https://wa.me/{{ $waClean }}"
                                                       target="_blank" rel="noopener noreferrer"
                                                       class="btn btn-sm btn-success rounded-pill px-3 fw-semibold">
                                                        <i class="bi bi-whatsapp me-1"></i> WhatsApp Requester
                                                    </a>
                                                @endif

                                                @if($isUnread)
                                                    <form method="POST"
                                                          action="{{ route('notifications.read', $alert->id) }}"
                                                          class="m-0">
                                                        @csrf
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                            <i class="bi bi-check2 me-1"></i> Mark Read
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($isResting)
                                                    <button type="button" 
                                                            class="btn btn-sm btn-secondary rounded-pill px-3 py-1 opacity-75"
                                                            style="cursor: not-allowed;"
                                                            title="You are in a 90-day recovery resting phase"
                                                            disabled>
                                                        <i class="bi bi-heart-fill me-1 text-danger"></i> I Donated (Resting)
                                                    </button>
                                                @else
                                                    <button type="button" 
                                                            class="btn btn-sm btn-premium rounded-pill px-3 py-1"
                                                            onclick="openDonationModal({{ $alert->blood_request_id }}, '{{ $alert->patient_name }}')">
                                                        <i class="bi bi-heart-fill me-1"></i> I Donated
                                                    </button>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    @else
                        {{-- Empty State --}}
                        <div class="text-center py-5">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width:72px; height:72px; background:rgba(230,57,70,0.08);">
                                <i class="bi bi-bell-slash fs-2 text-danger opacity-50"></i>
                            </div>
                            <p class="fw-semibold text-muted mb-1">No alerts yet</p>
                            <p class="small text-muted">You will be notified here when a recipient posts a blood request matching your blood group.</p>
                        </div>
                    @endif

                </div>{{-- /notification panel --}}


                {{-- ── ACTIVE PATIENT REQUESTS TABLE ── --}}
                <div class="glass-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <h5 class="fw-bold m-0">
                            <i class="bi bi-clipboard2-pulse-fill text-danger me-2"></i>Active Patient Requests
                        </h5>
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle custom-table mb-0">
                            <thead>
                                <tr>
                                    <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Patient</th>
                                    <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Blood</th>
                                    <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Hospital</th>
                                    <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Urgency</th>
                                    <th class="border-0 pb-3 small text-uppercase text-muted fw-bold text-end">Contact</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recipientRequests as $req)
                                    @php
                                        $reqWa = preg_replace('/\D/', '', optional($req->user)->whatsapp_number ?? '');
                                        $reqPhone = optional($req->user)->phone ?? '';
                                        $urgencyClass = match($req->urgency_level) {
                                            'Critical' => 'bg-danger',
                                            'High'     => 'bg-warning text-dark',
                                            default    => 'bg-info text-dark',
                                        };
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-dark d-block">{{ $req->patient_name }}</span>
                                            <span class="small text-muted">{{ $req->units }} unit(s) needed</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-3 fw-bold">
                                                {{ $req->blood_group }}
                                            </span>
                                        </td>
                                        <td class="small text-muted" style="max-width: 180px;">
                                            <i class="bi bi-hospital me-1"></i>
                                            {{ Str::limit($req->hospital_location, 35) }}
                                        </td>
                                        <td>
                                            <span class="badge {{ $urgencyClass }} px-3 py-2 rounded-pill small shadow-sm">
                                                {{ $req->urgency_level }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                                @if(!empty($reqPhone))
                                                    <a href="tel:{{ $reqPhone }}"
                                                       class="btn btn-sm btn-outline-danger rounded-pill px-2 fw-bold"
                                                       title="Call Requester">
                                                        <i class="bi bi-telephone-fill"></i>
                                                    </a>
                                                @endif
                                                @if(!empty($reqWa))
                                                    <a href="https://wa.me/{{ $reqWa }}"
                                                       target="_blank" rel="noopener noreferrer"
                                                       class="btn btn-sm btn-success rounded-pill px-3 fw-bold">
                                                        <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                                    </a>
                                                @endif
                                                
                                                @if($isResting)
                                                    <button type="button" 
                                                            class="btn btn-sm btn-secondary rounded-pill px-3 fw-bold opacity-75"
                                                            style="cursor: not-allowed;"
                                                            title="You are in a 90-day recovery resting phase"
                                                            disabled>
                                                        <i class="bi bi-heart-fill me-1 text-danger"></i> Donated (Resting)
                                                    </button>
                                                @else
                                                    <button type="button" 
                                                            class="btn btn-sm btn-premium rounded-pill px-3 fw-bold"
                                                            onclick="openDonationModal({{ $req->id }}, '{{ $req->patient_name }}')">
                                                        <i class="bi bi-heart-fill me-1"></i> Donated
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="bi bi-clipboard2-x fs-1 d-block mb-3 text-muted opacity-25"></i>
                                            <p class="text-muted m-0">No active requests at the moment.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>{{-- /active requests --}}


            {{-- ══════════════════════════════════════════════════
                 RECIPIENT DASHBOARD
            ══════════════════════════════════════════════════ --}}
            @elseif(Auth::user()->role == 'recipient')

                {{-- Stats Row --}}
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="glass-card p-4 h-100" style="border-bottom: 4px solid #E63946;">
                            <p class="text-muted small fw-bold text-uppercase mb-2">Available Donors</p>
                            <h1 class="fw-bold m-0 display-font">{{ $availableDonors->count() }}</h1>
                            <span class="text-success small fw-bold mt-1 d-block">
                                <i class="bi bi-people-fill me-1"></i> Ready to Help
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card p-4 h-100" style="border-bottom: 4px solid #1D3557;">
                            <p class="text-muted small fw-bold text-uppercase mb-2">My Requests</p>
                            <h1 class="fw-bold m-0 display-font">{{ $totalRequestsCount }}</h1>
                            <span class="text-muted small mt-1 d-block">Total requests posted</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card p-4 h-100" style="border-bottom: 4px solid #10b981;">
                            <p class="text-muted small fw-bold text-uppercase mb-2">Status</p>
                            <h1 class="fw-bold m-0 display-font text-success">Verified</h1>
                            <span class="text-muted small mt-1 d-block">You can request blood</span>
                        </div>
                    </div>
                </div>

                @if(Auth::user()->willing_to_donate)
                {{-- ── NOTIFICATION / ALERT PANEL FOR RECIPIENT ── --}}
                <div class="glass-card p-4 mb-4" id="notifications-panel">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="fw-bold m-0">
                                <i class="bi bi-bell-fill text-danger me-2"></i>Blood Request Alerts
                            </h5>
                            @if($unreadCount > 0)
                                <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size:.72rem;">
                                    {{ $unreadCount }} new
                                </span>
                            @endif
                        </div>
                        @if($unreadCount > 0)
                            <form method="POST" action="{{ route('notifications.readAll') }}" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold">
                                    <i class="bi bi-check2-all me-1"></i> Mark All Read
                                </button>
                            </form>
                        @endif
                    </div>
                    
                    @if($unreadCount == 0 && $donorNotifications->count() > 0)
                        <div class="rounded-3 px-3 py-2 mb-3 small text-muted d-flex align-items-center gap-2" style="background: rgba(16,185,129,0.07); border: 1px solid rgba(16,185,129,0.2);">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            All alerts have been read. You are up to date.
                        </div>
                    @endif

                    @if($donorNotifications->count() > 0)
                        <div class="d-flex flex-column gap-3">
                            @foreach($donorNotifications as $alert)
                                @php
                                    $urgency = $alert->urgency_level;
                                    $isUnread = !$alert->is_read;
                                    $borderColor = match($urgency) { 'Critical' => '#E63946', 'High' => '#f59e0b', default => '#3b82f6' };
                                    $urgencyBg = match($urgency) { 'Critical' => '#E63946', 'High' => '#f59e0b', default => '#3b82f6' };
                                    $waClean = preg_replace('/\D/', '', $alert->requester_whatsapp ?? '');
                                @endphp
                                <div class="rounded-4 p-3 alert-card" style="border: 1px solid rgba(0,0,0,0.07); border-left: 5px solid {{ $borderColor }}; background: {{ $isUnread ? 'rgba(230,57,70,0.04)' : 'rgba(255,255,255,0.5)' }}; transition: background .3s ease;">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="flex-shrink-0 rounded-3 d-flex align-items-center justify-content-center fw-bold text-white" style="width:52px; height:52px; background:{{ $borderColor }}; font-size:1rem; line-height:1;">
                                            {{ $alert->blood_group }}
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-1">
                                                <div class="d-flex flex-wrap align-items-center gap-2">
                                                    <span class="badge rounded-pill px-2 py-1 text-white" style="background:{{ $urgencyBg }}; font-size:.7rem;">{{ $urgency }}</span>
                                                    @if($isUnread) <span class="badge rounded-pill px-2 py-1" style="background: rgba(230,57,70,0.12); color:#E63946; font-size:.68rem;">● New</span> @endif
                                                </div>
                                                <span class="text-muted" style="font-size:.72rem;">{{ $alert->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="fw-bold text-dark mb-1" style="font-size:.95rem;">Patient: {{ $alert->patient_name }}</p>
                                            <p class="small text-muted mb-2"><i class="bi bi-hospital me-1"></i>{{ $alert->hospital_location }} &nbsp;&bull;&nbsp; <i class="bi bi-droplet-fill text-danger me-1"></i>{{ $alert->units }} unit(s) needed</p>
                                            <p class="small text-muted mb-2"><i class="bi bi-person-fill me-1"></i>Requested by: <strong>{{ $alert->requester_name }}</strong></p>
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                @if(!empty($alert->requester_phone)) <a href="tel:{{ $alert->requester_phone }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="bi bi-telephone-fill me-1"></i>{{ $alert->requester_phone }}</a> @endif
                                                @if(!empty($waClean)) <a href="https://wa.me/{{ $waClean }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-3 fw-semibold"><i class="bi bi-whatsapp me-1"></i> WhatsApp Requester</a> @endif
                                                @if($isUnread)
                                                    <form method="POST" action="{{ route('notifications.read', $alert->id) }}" class="m-0">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="bi bi-check2 me-1"></i> Mark Read</button>
                                                    </form>
                                                @endif
                                                @if($isResting)
                                                    <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3 py-1 opacity-75" style="cursor: not-allowed;" title="You are in a 90-day recovery resting phase" disabled>
                                                        <i class="bi bi-heart-fill me-1 text-danger"></i> I Donated (Resting)
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-premium rounded-pill px-3 py-1" onclick="openDonationModal({{ $alert->blood_request_id }}, '{{ $alert->patient_name }}')">
                                                        <i class="bi bi-heart-fill me-1"></i> I Donated
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:72px; height:72px; background:rgba(230,57,70,0.08);">
                                <i class="bi bi-bell-slash fs-2 text-danger opacity-50"></i>
                            </div>
                            <p class="fw-semibold text-muted mb-1">No alerts yet</p>
                            <p class="small text-muted">You will be notified here when a recipient posts a blood request matching your blood group.</p>
                        </div>
                    @endif
                </div>
                @endif

                {{-- ── EMERGENCY REQUEST BANNER ── --}}
                <div class="rounded-4 mb-4 p-4 d-flex align-items-center gap-4 flex-wrap"
                     style="background: linear-gradient(135deg, #1D3557 0%, #2a4a73 100%);
                            border-left: 6px solid #E63946;
                            box-shadow: 0 8px 30px rgba(230,57,70,0.2);">
                    <div class="emergency-pulse-icon text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:64px; height:64px; background: rgba(230,57,70,0.25);">
                        <i class="bi bi-exclamation-triangle-fill fs-2 text-danger"></i>
                    </div>
                    <div class="flex-grow-1 text-white">
                        <h5 class="fw-bold mb-1">Need Blood Urgently?</h5>
                        <p class="small mb-0 opacity-75">
                            Post an emergency request and all matching donors will be notified instantly via email &amp; in-app alerts.
                        </p>
                    </div>
                    <a href="{{ route('requests.create') }}"
                       class="btn btn-danger fw-bold px-4 py-2 rounded-3 shadow flex-shrink-0"
                       style="background: var(--gradient-blood); border:none; white-space:nowrap;">
                        <i class="bi bi-send-fill me-2"></i> Post Emergency Request
                    </a>
                </div>

                {{-- ── AVAILABLE DONORS TABLE ── --}}
                <div class="glass-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h5 class="fw-bold m-0">
                            <i class="bi bi-people-fill text-danger me-2"></i>Available Donors
                        </h5>
                        <a href="{{ route('requests.create') }}" class="btn btn-sm btn-danger rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-megaphone-fill me-1"></i> Notify All Matching Donors
                        </a>
                    </div>

                    {{-- Blood Group Filter Pills --}}
                    <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
                        <span class="small text-muted fw-semibold me-1">Filter:</span>
                        <button type="button" class="btn btn-sm btn-danger rounded-pill px-3 fw-semibold filter-btn active-filter"
                                data-filter="all" onclick="filterDonors('all', this)">
                            All Groups
                        </button>
                        @foreach($compatibleGroups as $bg)
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold filter-btn"
                                    data-filter="{{ $bg }}"
                                    onclick="filterDonors('{{ $bg }}', this)">
                                {{ $bg }}
                            </button>
                        @endforeach
                    </div>

                    {{-- No results message (hidden by default) --}}
                    <div id="no-donors-msg" class="text-center py-3 d-none">
                        <i class="bi bi-people fs-3 text-muted opacity-25 d-block mb-2"></i>
                        <p class="text-muted small mb-0">No donors found for this blood group.</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle custom-table mb-0" id="donors-table">
                            <thead>
                                <tr>
                                    <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Donor Name</th>
                                    <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Blood Group</th>
                                    <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">City</th>
                                    <th class="border-0 pb-3 small text-uppercase text-muted fw-bold text-end">Contact</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($availableDonors as $donor)
                                    @php
                                        $donorWa = preg_replace('/\D/', '', $donor->whatsapp_number ?? '');
                                    @endphp
                                    <tr class="donor-row" data-blood-group="{{ $donor->blood_group }}">
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                                     style="width:38px; height:38px; background: var(--gradient-blood); font-size:.75rem;">
                                                    {{ strtoupper(substr($donor->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark d-block">{{ $donor->name }}</span>
                                                    @if($donor->father_name)
                                                        <span class="small text-muted d-block">S/O {{ $donor->father_name }}</span>
                                                    @endif
                                                    @if($donor->last_donation_date && $donor->last_donation_date->gt(now()->subDays(90)))
                                                        <span class="badge text-white px-2 py-0.5 rounded-pill small mt-1 d-inline-block shadow-sm" style="font-size: 0.65rem; background: linear-gradient(135deg, #6f42c1, #a926a7);">
                                                            💖 Life Saver (Resting)
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-3 fw-bold">
                                                {{ $donor->blood_group }}
                                            </span>
                                        </td>
                                        <td class="small text-muted">
                                            <i class="bi bi-geo-alt me-1"></i>{{ $donor->city }}
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                                @if($donor->last_donation_date && $donor->last_donation_date->gt(now()->subDays(90)))
                                                    <span class="badge bg-secondary rounded-pill px-3 py-2 text-white opacity-75 small">
                                                        <i class="bi bi-lock-fill me-1"></i> Unavailable (Resting)
                                                    </span>
                                                @else
                                                    @if(!empty($donor->phone))
                                                        <a href="tel:{{ $donor->phone }}"
                                                           class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold">
                                                            <i class="bi bi-telephone-fill me-1"></i> Call
                                                        </a>
                                                    @endif
                                                    @if(!empty($donorWa))
                                                        <a href="https://wa.me/{{ $donorWa }}"
                                                           target="_blank" rel="noopener noreferrer"
                                                           class="btn btn-sm btn-success rounded-pill px-3 fw-semibold">
                                                            <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="empty-donors-row">
                                        <td colspan="4" class="text-center py-5">
                                            <i class="bi bi-people fs-1 d-block mb-3 text-muted opacity-25"></i>
                                            <p class="text-muted m-0">No donors available at the moment.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>{{-- /donors table --}}


            {{-- ══════════════════════════════════════════════════
                 ADMIN DASHBOARD (placeholder)
            ══════════════════════════════════════════════════ --}}
            @elseif(Auth::user()->role == 'admin')

                <div class="glass-card p-5 text-center">
                    <i class="bi bi-shield-lock-fill fs-1 text-danger mb-3 d-block"></i>
                    <h4 class="fw-bold">Admin Panel</h4>
                    <p class="text-muted">Full admin controls are coming soon. You can view all data from the database directly.</p>
                </div>

            @endif

        </div>{{-- /main content --}}
    </div>{{-- /row --}}
</div>{{-- /container --}}


{{-- ══════════════════════════════════════════════════
     BROWSER PUSH NOTIFICATION (for donors online)
══════════════════════════════════════════════════ --}}
@if((Auth::user()->role == 'donor' || (Auth::user()->role == 'recipient' && Auth::user()->willing_to_donate)) && $unreadCount > 0)
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Request browser notification permission and show alert
    if ('Notification' in window && Notification.permission !== 'denied') {
        Notification.requestPermission().then(function (permission) {
            if (permission === 'granted') {
                new Notification('🩸 BloodConnect — {{ $unreadCount }} New Alert(s)', {
                    body: 'You have {{ $unreadCount }} unread blood request alert(s) matching your blood group ({{ Auth::user()->blood_group }}). Open the app to respond.',
                    icon: '{{ asset("favicon.ico") }}',
                    badge: '{{ asset("favicon.ico") }}',
                    tag: 'blood-alert',
                    requireInteraction: true,
                });
            }
        });
    }
});
</script>
@endif


@if(Auth::user()->role == 'donor' || (Auth::user()->role == 'recipient' && Auth::user()->willing_to_donate))
<!-- Donation Confirmation Modal -->
<div class="modal fade" id="donationConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-danger text-white border-0 py-3">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-heart-fill me-2"></i>Confirm Your Donation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('donations.confirm') }}" method="POST">
                @csrf
                <input type="hidden" name="blood_request_id" id="modal_request_id">
                
                <div class="modal-body p-4 text-center">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-droplet-fill fs-2 text-danger"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Did you donate blood?</h5>
                    <p class="text-muted small">
                        Confirming this will mark the request for <strong id="modal_patient_name" class="text-dark"></strong> as fulfilled and update your donation history.
                    </p>
                    
                    <div class="form-check d-inline-block text-start mt-3 p-3 rounded-3 border" style="background: #f8f9fa;">
                        <input class="form-check-input" type="checkbox" name="confirm_donation" id="confirm_check" required>
                        <label class="form-check-label fw-semibold small" for="confirm_check" style="cursor:pointer;">
                            I confirm that I have successfully donated blood for this patient.
                        </label>
                    </div>
                </div>
                
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-premium rounded-pill px-4 fw-bold">
                        <i class="bi bi-check-circle-fill me-1"></i> Submit Confirmation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openDonationModal(requestId, patientName) {
        document.getElementById('modal_request_id').value = requestId;
        document.getElementById('modal_patient_name').textContent = patientName;
        document.getElementById('confirm_check').checked = false;
        
        var myModal = new bootstrap.Modal(document.getElementById('donationConfirmModal'));
        myModal.show();
    }
</script>
@endif

@endsection


<script>
/* ─── Blood Group Filter for Recipient Donor Table ─── */
function filterDonors(bloodGroup, btn) {
    // Update active pill style
    document.querySelectorAll('.filter-btn').forEach(function (b) {
        b.classList.remove('btn-danger', 'active-filter');
        b.classList.add('btn-outline-danger');
    });
    btn.classList.remove('btn-outline-danger');
    btn.classList.add('btn-danger', 'active-filter');

    var rows      = document.querySelectorAll('.donor-row');
    var noMsg     = document.getElementById('no-donors-msg');
    var visible   = 0;

    rows.forEach(function (row) {
        if (bloodGroup === 'all' || row.dataset.bloodGroup === bloodGroup) {
            row.style.display = '';
            visible++;
        } else {
            row.style.display = 'none';
        }
    });

    if (noMsg) {
        noMsg.classList.toggle('d-none', visible > 0);
    }
}
</script>


<style>
    .active-nav {
        background: var(--gradient-blood) !important;
        color: white !important;
        box-shadow: 0 4px 15px rgba(230, 57, 70, 0.2);
    }

    .custom-table td {
        padding: 1rem 0.85rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        vertical-align: middle;
    }

    .custom-table thead th {
        padding: 0 0.85rem 0.85rem;
    }

    .alert-card:hover {
        background: rgba(230, 57, 70, 0.06) !important;
    }

    .filter-btn {
        transition: all .2s ease;
        font-size: .8rem;
    }

    /* Pulsing emergency icon */
    @keyframes pulse-ring {
        0%   { box-shadow: 0 0 0 0 rgba(230,57,70,0.5); }
        70%  { box-shadow: 0 0 0 14px rgba(230,57,70,0); }
        100% { box-shadow: 0 0 0 0 rgba(230,57,70,0); }
    }
    .emergency-pulse-icon {
        animation: pulse-ring 2s ease-in-out infinite;
    }

    .min-w-0 { min-width: 0; }

    /* Smooth scroll for sidebar anchor links */
    html { scroll-behavior: smooth; }

    /* Sidebar only sticks once it sits beside the content (lg+); below that
       it stacks above the content in normal flow, so sticky would just
       float oddly over it while scrolling. */
    @media (min-width: 992px) {
        .sticky-top-lg {
            position: sticky;
            top: 100px;
        }
    }
</style>
