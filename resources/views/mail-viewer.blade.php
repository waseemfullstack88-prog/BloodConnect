@extends('layouts.main')

@section('title', 'Local Mail Inbox')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-inbox text-danger me-2"></i>Local Inbox</h2>
            <p class="text-muted small">All system emails are intercepted and stored here for testing.</p>
        </div>
        <form action="{{ route('inbox') }}" method="GET">
            <button type="submit" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button>
        </form>
    </div>

    @if($emails->isEmpty())
        <div class="text-center py-5 glass-card">
            <i class="bi bi-envelope-x display-1 text-muted opacity-25"></i>
            <h4 class="mt-3 text-muted">Inbox is empty</h4>
            <p class="text-muted small">Try requesting a password reset to see emails appear here.</p>
        </div>
    @else
        <div class="row g-4">
            {{-- Email List (Sidebar on desktop) --}}
            <div class="col-md-4">
                <div class="glass-card p-0 overflow-hidden" style="max-height: 600px; overflow-y: auto;">
                    <div class="list-group list-group-flush border-0">
                        @foreach($emails as $index => $email)
                            <a href="#email-{{ $email->id }}" class="list-group-item list-group-item-action p-3 {{ $index === 0 ? 'active-mail' : '' }}" onclick="showEmail('email-{{ $email->id }}', this)">
                                <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                    <small class="fw-bold text-truncate" style="max-width: 150px;">{{ $email->to_email }}</small>
                                    <small class="text-muted" style="font-size: 0.7rem;">{{ $email->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="small fw-semibold mb-1 text-truncate">{{ $email->subject }}</div>
                                <div class="small text-muted text-truncate" style="font-size: 0.75rem;">Click to view content</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Email Body Content --}}
            <div class="col-md-8">
                @foreach($emails as $index => $email)
                    <div id="email-{{ $email->id }}" class="email-content glass-card h-100 d-flex flex-column" style="display: {{ $index === 0 ? 'flex' : 'none' }} !important; min-height: 500px;">
                        {{-- Header --}}
                        <div class="p-3 p-md-4 border-bottom bg-light bg-opacity-50">
                            <h4 class="fw-bold mb-3">{{ $email->subject }}</h4>
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person text-secondary"></i>
                                </div>
                                <div>
                                    <div class="small text-muted mb-1">To: <span class="text-dark fw-semibold">{{ $email->to_email }}</span></div>
                                    <div class="small text-muted" style="font-size: 0.75rem;">Sent: {{ $email->created_at->format('M d, Y h:i A') }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Body --}}
                        <div class="p-3 p-md-4 flex-grow-1" style="overflow-y: auto; max-height: 500px;">
                            <div class="border rounded-3 p-3 bg-white h-100 shadow-sm" style="overflow-x: auto;">
                                {!! $email->body !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
    .list-group-item {
        border-left: 3px solid transparent !important;
        transition: all 0.2s ease;
        background: transparent;
    }
    .list-group-item:hover {
        background: rgba(0,0,0,0.02);
    }
    .active-mail {
        border-left: 3px solid #E63946 !important;
        background: rgba(230,57,70,0.05) !important;
    }
    .active-mail .fw-bold {
        color: #E63946 !important;
    }
</style>

<script>
    function showEmail(id, element) {
        // Hide all emails
        document.querySelectorAll('.email-content').forEach(el => {
            el.style.setProperty('display', 'none', 'important');
        });
        
        // Remove active class
        document.querySelectorAll('.list-group-item').forEach(el => {
            el.classList.remove('active-mail');
        });

        // Show selected email
        const target = document.getElementById(id);
        if(target) target.style.setProperty('display', 'flex', 'important');
        
        // Add active class
        element.classList.add('active-mail');
    }
</script>
@endsection
