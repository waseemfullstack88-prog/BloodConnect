@extends('layouts.main')

@section('title', 'Manage Donations — Admin')

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold display-font mb-1"><i class="bi bi-heart-fill text-danger me-2"></i>All Donations</h2>
            <p class="text-muted mb-0">Complete donation history across the system</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    {{-- Filter --}}
    <div class="glass-card p-4 mb-4">
        <form method="GET" action="{{ route('admin.donations') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Blood Group</label>
                <select name="blood_group" class="form-select rounded-3">
                    <option value="">All Groups</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">
                    <i class="bi bi-funnel-fill me-1"></i> Filter
                </button>
                @if(request()->hasAny(['blood_group']))
                <a href="{{ route('admin.donations') }}" class="btn btn-outline-secondary rounded-pill px-3 fw-bold" title="Clear Filters">
                    <i class="bi bi-x-circle"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <div class="glass-card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">#</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Donor</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Recipient</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Blood Group</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Units</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Donation Date</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donations as $d)
                    <tr>
                        <td class="small text-muted">{{ $d->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                     style="width:34px; height:34px; background: var(--gradient-blood); font-size:.65rem;">
                                    {{ strtoupper(substr(optional($d->donor)->name ?? 'U', 0, 2)) }}
                                </div>
                                <span class="fw-bold small">{{ optional($d->donor)->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td>
                            @if($d->recipient)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                         style="width:34px; height:34px; background: #1D3557; font-size:.65rem;">
                                        {{ strtoupper(substr($d->recipient->name, 0, 2)) }}
                                    </div>
                                    <span class="fw-bold small">{{ $d->recipient->name }}</span>
                                </div>
                            @else
                                <span class="text-muted small fst-italic">To Blood Stock</span>
                            @endif
                        </td>
                        <td><span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-3 fw-bold">{{ $d->blood_group }}</span></td>
                        <td class="small fw-bold">{{ $d->units }}</td>
                        <td class="small text-muted">{{ $d->donation_date }}</td>
                        <td><span class="badge bg-success rounded-pill px-3 py-1">{{ $d->status }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">No donation records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 d-flex justify-content-center">
            {{ $donations->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
