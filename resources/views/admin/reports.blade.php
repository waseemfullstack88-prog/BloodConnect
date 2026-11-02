@extends('layouts.main')

@section('title', 'Reports — Admin')

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold display-font mb-1"><i class="bi bi-file-earmark-bar-graph text-danger me-2"></i>Generate Reports</h2>
            <p class="text-muted mb-0">Download system data as CSV reports for analysis</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row g-4">
        {{-- Report Cards --}}
        @php
            $reportTypes = [
                ['type' => 'donors', 'icon' => 'bi-people-fill', 'title' => 'Donors Report', 'desc' => 'Export all registered donor profiles with contact details, blood groups, and donation dates.', 'color' => '#E63946'],
                ['type' => 'recipients', 'icon' => 'bi-person-heart', 'title' => 'Recipients Report', 'desc' => 'Export all registered recipients with hospital info and blood requirements.', 'color' => '#1D3557'],
                ['type' => 'requests', 'icon' => 'bi-clipboard2-pulse-fill', 'title' => 'Blood Requests Report', 'desc' => 'Export all blood requests with patient details, urgency levels, and current status.', 'color' => '#f59e0b'],
                ['type' => 'donations', 'icon' => 'bi-heart-fill', 'title' => 'Donations Report', 'desc' => 'Export complete donation history with donor names, blood groups, and units donated.', 'color' => '#10b981'],
            ];
        @endphp

        @foreach($reportTypes as $report)
        <div class="col-md-6">
            <div class="glass-card p-4 h-100">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:50px; height:50px; background: {{ $report['color'] }}15;">
                        <i class="bi {{ $report['icon'] }} fs-3" style="color: {{ $report['color'] }};"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">{{ $report['title'] }}</h5>
                        <p class="text-muted small mb-0">{{ $report['desc'] }}</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.reports.download') }}" class="row g-2 align-items-end">
                    <input type="hidden" name="type" value="{{ $report['type'] }}">
                    <div class="col-5">
                        <label class="form-label small fw-bold mb-1">From</label>
                        <input type="date" name="from" class="form-control form-control-sm rounded-3">
                    </div>
                    <div class="col-5">
                        <label class="form-label small fw-bold mb-1">To</label>
                        <input type="date" name="to" class="form-control form-control-sm rounded-3">
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn btn-sm btn-danger rounded-pill w-100 fw-bold" title="Download CSV">
                            <i class="bi bi-download"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
