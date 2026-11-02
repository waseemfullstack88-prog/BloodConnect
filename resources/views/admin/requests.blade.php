@extends('layouts.main')

@section('title', 'Manage Requests — Admin')

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold display-font mb-1"><i class="bi bi-clipboard2-pulse-fill text-danger me-2"></i>Manage Blood Requests</h2>
            <p class="text-muted mb-0">View and manage all blood requests in the system</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    @if(session('success'))
        <div class="alert border-0 shadow-sm rounded-4 mb-4 p-3 d-flex align-items-center" style="background: #d1fae5; color: #065f46;">
            <i class="bi bi-check-circle-fill fs-4 me-3" style="color: #10b981;"></i>
            <p class="fw-bold mb-0 small">{{ session('success') }}</p>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="glass-card p-4 mb-4">
        <form method="GET" action="{{ route('admin.requests') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Status</label>
                <select name="status" class="form-select rounded-3">
                    <option value="">All Statuses</option>
                    @foreach(['Pending', 'Approved', 'Fulfilled', 'Rejected'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Blood Group</label>
                <select name="blood_group" class="form-select rounded-3">
                    <option value="">All Groups</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Urgency</label>
                <select name="urgency" class="form-select rounded-3">
                    <option value="">All Levels</option>
                    @foreach(['Normal', 'High', 'Critical'] as $u)
                        <option value="{{ $u }}" {{ request('urgency') == $u ? 'selected' : '' }}>{{ $u }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold w-100">
                    <i class="bi bi-funnel-fill me-1"></i> Filter
                </button>
                @if(request()->hasAny(['status', 'blood_group', 'urgency']))
                <a href="{{ route('admin.requests') }}" class="btn btn-outline-secondary rounded-pill px-3 fw-bold" title="Clear Filters">
                    <i class="bi bi-x-circle"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Requests Table --}}
    <div class="glass-card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">#</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Patient</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Blood</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Units</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Hospital</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Urgency</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Requested By</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Date</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold text-end">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $r)
                    @php
                        $statusBg = match($r->status) {
                            'Pending'   => 'bg-warning text-dark',
                            'Approved'  => 'bg-info text-dark',
                            'Fulfilled' => 'bg-success',
                            'Rejected'  => 'bg-danger',
                            default     => 'bg-secondary',
                        };
                        $urgencyBg = match($r->urgency_level) {
                            'Critical' => 'bg-danger',
                            'High'     => 'bg-warning text-dark',
                            default    => 'bg-info text-dark',
                        };
                    @endphp
                    <tr>
                        <td class="small text-muted">{{ $r->id }}</td>
                        <td>
                            <span class="fw-bold small d-block">{{ $r->patient_name }}</span>
                            <span class="text-muted" style="font-size:.7rem;">{{ $r->patient_cnic ?? 'No CNIC' }}</span>
                        </td>
                        <td><span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-3 fw-bold">{{ $r->blood_group }}</span></td>
                        <td class="small fw-bold">{{ $r->units }}</td>
                        <td class="small text-muted" style="max-width:150px;">{{ Str::limit($r->hospital_location, 30) }}</td>
                        <td><span class="badge {{ $urgencyBg }} rounded-pill px-2 py-1 small">{{ $r->urgency_level }}</span></td>
                        <td class="small text-muted">{{ optional($r->user)->name ?? 'Unknown' }}</td>
                        <td class="small text-muted">{{ $r->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('admin.requests.status', $r->id) }}" class="d-inline">
                                @csrf
                                <select name="status" class="form-select form-select-sm rounded-3 d-inline-block fw-bold" style="width:130px; font-size:.78rem;"
                                        onchange="this.form.submit()">
                                    @foreach(['Pending', 'Approved', 'Fulfilled', 'Rejected'] as $s)
                                        <option value="{{ $s }}" {{ $r->status == $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">No blood requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 d-flex justify-content-center">
            {{ $requests->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
