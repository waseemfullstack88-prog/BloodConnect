@extends('layouts.main')

@section('title', 'Admin Dashboard — BloodConnect')

@section('content')
<div class="container py-5">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="fw-bold display-font mb-1" style="font-size:clamp(1.3rem,4vw,2rem);"><i class="bi bi-shield-lock-fill text-danger me-2"></i>Admin Dashboard</h2>
            <p class="text-muted mb-0 small">System overview and analytics</p>
        </div>
        <a href="{{ route('admin.reports') }}" class="btn btn-outline-danger rounded-pill px-3 fw-semibold">
            <i class="bi bi-file-earmark-bar-graph me-1"></i> Reports
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert border-0 shadow-sm rounded-4 mb-4 p-3 d-flex align-items-center" style="background: #d1fae5; color: #065f46;" role="alert">
            <i class="bi bi-check-circle-fill fs-4 me-3" style="color: #10b981;"></i>
            <p class="fw-bold mb-0 small">{{ session('success') }}</p>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats Cards Row 1 --}}
    <div class="row g-3 mb-3">
        <div class="col-md-3 col-6">
            <div class="glass-card admin-stat-card h-100" style="border-bottom: 4px solid #E63946;">
                <p class="stat-label text-muted fw-bold text-uppercase mb-1">Donors</p>
                <h1 class="stat-num fw-bold m-0 display-font text-danger">{{ $totalDonors }}</h1>
                <a href="{{ route('admin.users', ['role' => 'donor']) }}" class="small text-danger fw-bold text-decoration-none">View All →</a>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="glass-card admin-stat-card h-100" style="border-bottom: 4px solid #1D3557;">
                <p class="stat-label text-muted fw-bold text-uppercase mb-1">Recipients</p>
                <h1 class="stat-num fw-bold m-0 display-font" style="color: #1D3557;">{{ $totalRecipients }}</h1>
                <a href="{{ route('admin.users', ['role' => 'recipient']) }}" class="small text-muted fw-bold text-decoration-none">View All →</a>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="glass-card admin-stat-card h-100" style="border-bottom: 4px solid #f59e0b;">
                <p class="stat-label text-muted fw-bold text-uppercase mb-1">Pending</p>
                <h1 class="stat-num fw-bold m-0 display-font text-warning">{{ $pendingRequests }}</h1>
                <a href="{{ route('admin.requests', ['status' => 'Pending']) }}" class="small text-warning fw-bold text-decoration-none">View All →</a>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="glass-card admin-stat-card h-100" style="border-bottom: 4px solid #10b981;">
                <p class="stat-label text-muted fw-bold text-uppercase mb-1">Donations</p>
                <h1 class="stat-num fw-bold m-0 display-font text-success">{{ $totalDonations }}</h1>
                <a href="{{ route('admin.donations') }}" class="small text-success fw-bold text-decoration-none">View All →</a>
            </div>
        </div>
    </div>

    {{-- Stats Row 2 --}}
    <div class="row g-3 mb-3">
        <div class="col-md-3 col-4">
            <div class="glass-card admin-stat-card h-100">
                <p class="stat-label text-muted fw-bold text-uppercase mb-1">Requests</p>
                <h2 class="stat-num fw-bold m-0 display-font">{{ $totalRequests }}</h2>
            </div>
        </div>
        <div class="col-md-3 col-4">
            <div class="glass-card admin-stat-card h-100">
                <p class="stat-label text-muted fw-bold text-uppercase mb-1">Fulfilled</p>
                <h2 class="stat-num fw-bold m-0 display-font text-success">{{ $fulfilledRequests }}</h2>
            </div>
        </div>
        <div class="col-md-3 col-4">
            <div class="glass-card admin-stat-card h-100">
                <p class="stat-label text-muted fw-bold text-uppercase mb-1">Rate</p>
                <h2 class="stat-num fw-bold m-0 display-font text-danger">
                    {{ $totalRequests > 0 ? round(($fulfilledRequests / $totalRequests) * 100) : 0 }}%
                </h2>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="glass-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-bar-chart-fill text-danger me-2"></i>Blood Group Distribution</h5>
                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 small">Users by Blood Type</span>
                </div>
                <div style="position:relative; height:240px;">
                    <canvas id="bloodGroupChart"></canvas>
                    @if(empty($bloodGroupData))
                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <i class="bi bi-bar-chart text-muted opacity-25" style="font-size:3rem;"></i>
                            <p class="text-muted small mt-2">No user data yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="glass-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-graph-up text-danger me-2"></i>Monthly Activity</h5>
                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 small">Last 12 Months</span>
                </div>
                <div style="position:relative; height:240px;">
                    <canvas id="monthlyChart"></canvas>
                    @if(empty($monthlyDonations) && empty($monthlyRequests))
                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <i class="bi bi-graph-up text-muted opacity-25" style="font-size:3rem;"></i>
                            <p class="text-muted small mt-2">No activity data yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Admin Sidebar Navigation --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-6">
            <a href="{{ route('admin.users') }}" class="glass-card p-4 text-center d-block text-decoration-none text-dark h-100">
                <i class="bi bi-people-fill fs-2 text-danger d-block mb-2"></i>
                <span class="fw-bold small">Users</span>
            </a>
        </div>
        <div class="col-md-3 col-6">
            <a href="{{ route('admin.requests') }}" class="glass-card p-4 text-center d-block text-decoration-none text-dark h-100">
                <i class="bi bi-clipboard2-pulse-fill fs-2 text-danger d-block mb-2"></i>
                <span class="fw-bold small">Requests</span>
            </a>
        </div>
        <div class="col-md-3 col-6">
            <a href="{{ route('admin.donations') }}" class="glass-card p-4 text-center d-block text-decoration-none text-dark h-100">
                <i class="bi bi-heart-fill fs-2 text-danger d-block mb-2"></i>
                <span class="fw-bold small">Donations</span>
            </a>
        </div>
        <div class="col-md-3 col-6">
            <a href="{{ route('admin.reports') }}" class="glass-card p-4 text-center d-block text-decoration-none text-dark h-100">
                <i class="bi bi-file-earmark-bar-graph fs-2 text-danger d-block mb-2"></i>
                <span class="fw-bold small">Reports</span>
            </a>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="row g-4">

        {{-- Recent Registrations --}}
        <div class="col-lg-4">
            <div class="glass-card p-4 h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-person-plus-fill text-danger me-2"></i>Recent Registrations</h6>
                @foreach($recentUsers as $u)
                <div class="recent-item d-flex align-items-center gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}"
                     style="cursor:pointer;"
                     data-bs-toggle="modal" data-bs-target="#userModal"
                     data-name="{{ $u->name }}"
                     data-role="{{ ucfirst($u->role) }}"
                     data-email="{{ $u->email }}"
                     data-blood="{{ $u->blood_group ?? 'N/A' }}"
                     data-phone="{{ $u->phone ?? 'N/A' }}"
                     data-whatsapp="{{ $u->whatsapp_number ?? 'N/A' }}"
                     data-city="{{ $u->city ?? 'N/A' }}"
                     data-cnic="{{ $u->cnic ?? 'N/A' }}"
                     data-dob="{{ $u->dob ? $u->dob->format('d M Y') : 'N/A' }}"
                     data-gender="{{ $u->gender ?? 'N/A' }}"
                     data-father="{{ $u->father_name ?? 'N/A' }}"
                     data-hospital="{{ $u->hospital_name ?? 'N/A' }}"
                     data-lastdonation="{{ $u->last_donation_date ? $u->last_donation_date->format('d M Y') : 'Never' }}"
                     data-registered="{{ $u->created_at->format('d M Y, h:i A') }}"
                     data-editurl="{{ route('admin.users.edit', $u->id) }}"
                     data-initials="{{ strtoupper(substr($u->name, 0, 2)) }}">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                         style="width:36px; height:36px; background: var(--gradient-blood); font-size:.7rem;">
                        {{ strtoupper(substr($u->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-grow-1">
                        <p class="fw-bold small mb-0 text-truncate">{{ $u->name }}</p>
                        <p class="text-muted mb-0" style="font-size:.7rem;">{{ $u->role }} · {{ $u->created_at->diffForHumans() }}</p>
                    </div>
                    <i class="bi bi-chevron-right text-muted" style="font-size:.65rem;"></i>
                </div>
                @endforeach
                @if($recentUsers->isEmpty())
                    <p class="text-muted small text-center py-3">No users yet.</p>
                @endif
            </div>
        </div>

        {{-- Recent Requests --}}
        <div class="col-lg-4">
            <div class="glass-card p-4 h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-clipboard2-pulse-fill text-danger me-2"></i>Recent Requests</h6>
                @foreach($recentRequests as $r)
                <div class="recent-item d-flex align-items-center gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}"
                     style="cursor:pointer;"
                     data-bs-toggle="modal" data-bs-target="#requestModal"
                     data-id="{{ $r->id }}"
                     data-patient="{{ $r->patient_name }}"
                     data-cnic="{{ $r->patient_cnic ?? 'N/A' }}"
                     data-blood="{{ $r->blood_group }}"
                     data-units="{{ $r->units }}"
                     data-hospital="{{ $r->hospital_location }}"
                     data-urgency="{{ $r->urgency_level }}"
                     data-status="{{ $r->status }}"
                     data-description="{{ $r->description ?? 'N/A' }}"
                     data-requestedby="{{ optional($r->user)->name ?? 'N/A' }}"
                     data-requesteremail="{{ optional($r->user)->email ?? 'N/A' }}"
                     data-date="{{ $r->created_at->format('d M Y, h:i A') }}">
                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-2 rounded-3 fw-bold flex-shrink-0">{{ $r->blood_group }}</span>
                    <div class="min-w-0 flex-grow-1">
                        <p class="fw-bold small mb-0 text-truncate">{{ $r->patient_name }}</p>
                        <p class="text-muted mb-0" style="font-size:.7rem;">{{ $r->status }} · {{ $r->created_at->diffForHumans() }}</p>
                    </div>
                    <i class="bi bi-chevron-right text-muted" style="font-size:.65rem;"></i>
                </div>
                @endforeach
                @if($recentRequests->isEmpty())
                    <p class="text-muted small text-center py-3">No requests yet.</p>
                @endif
            </div>
        </div>

        {{-- Recent Donations --}}
        <div class="col-lg-4">
            <div class="glass-card p-4 h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-heart-fill text-danger me-2"></i>Recent Donations</h6>
                @foreach($recentDonations as $d)
                <div class="recent-item d-flex align-items-center gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}"
                     style="cursor:pointer;"
                     data-bs-toggle="modal" data-bs-target="#donationModal"
                     data-id="{{ $d->id }}"
                     data-donor="{{ optional($d->donor)->name ?? 'Unknown' }}"
                     data-donoremail="{{ optional($d->donor)->email ?? 'N/A' }}"
                     data-donorcity="{{ optional($d->donor)->city ?? 'N/A' }}"
                     data-donorphone="{{ optional($d->donor)->phone ?? 'N/A' }}"
                     data-recipient="{{ optional($d->recipient)->name ?? 'N/A' }}"
                     data-recipientemail="{{ optional($d->recipient)->email ?? 'N/A' }}"
                     data-blood="{{ $d->blood_group }}"
                     data-units="{{ $d->units }}"
                     data-date="{{ $d->donation_date }}"
                     data-status="{{ $d->status }}"
                     data-initials="{{ strtoupper(substr(optional($d->donor)->name ?? 'UN', 0, 2)) }}">
                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-2 rounded-3 fw-bold flex-shrink-0">{{ $d->blood_group }}</span>
                    <div class="min-w-0 flex-grow-1">
                        <p class="fw-bold small mb-0 text-truncate">{{ optional($d->donor)->name ?? 'Unknown' }}</p>
                        <p class="text-muted mb-0" style="font-size:.7rem;">{{ $d->units }} unit(s) · {{ $d->donation_date }}</p>
                    </div>
                    <i class="bi bi-chevron-right text-muted" style="font-size:.65rem;"></i>
                </div>
                @endforeach
                @if($recentDonations->isEmpty())
                    <p class="text-muted small text-center py-3">No donations yet.</p>
                @endif
            </div>
        </div>
    </div>

</div>{{-- /container --}}

{{-- ══════════════════════════════════════════════
     MODAL: User / Registration Detail
══════════════════════════════════════════════ --}}
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg" style="border-radius:1.25rem; overflow:hidden;">

      {{-- Header --}}
      <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg,#E63946 0%,#9b1d24 100%); padding: 1.5rem 1.75rem;">
        <div class="d-flex align-items-center gap-3 w-100">
          <div id="um-avatar" class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
               style="width:52px;height:52px;background:rgba(255,255,255,.25);font-size:.95rem;"></div>
          <div class="text-white">
            <h5 class="modal-title fw-bold mb-0" id="um-name"></h5>
            <small id="um-role" class="opacity-75 text-uppercase fw-semibold" style="letter-spacing:.05em;font-size:.7rem;"></small>
          </div>
          <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
        </div>
      </div>

      {{-- Body --}}
      <div class="modal-body p-0">
        <div class="row g-0">

          {{-- Left column --}}
          <div class="col-md-6 p-4" style="background:#fafafa; border-right:1px solid #f0f0f0;">
            <p class="text-muted text-uppercase fw-bold mb-3" style="font-size:.68rem;letter-spacing:.08em;">Contact Info</p>
            <div class="d-flex flex-column gap-3">
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-envelope-fill text-danger mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Email</p><p id="um-email" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-telephone-fill text-danger mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Phone</p><p id="um-phone" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-whatsapp text-success mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">WhatsApp</p><p id="um-whatsapp" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-geo-alt-fill text-danger mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">City</p><p id="um-city" class="mb-0 text-muted small"></p></div>
              </div>
            </div>
          </div>

          {{-- Right column --}}
          <div class="col-md-6 p-4">
            <p class="text-muted text-uppercase fw-bold mb-3" style="font-size:.68rem;letter-spacing:.08em;">Personal Details</p>
            <div class="d-flex flex-column gap-3">
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-droplet-fill text-danger mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Blood Group</p><p id="um-blood" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-credit-card-fill text-secondary mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">CNIC</p><p id="um-cnic" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-calendar3 text-secondary mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Date of Birth</p><p id="um-dob" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-person text-secondary mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Father's Name</p><p id="um-father" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-heart-pulse-fill text-danger mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Last Donation</p><p id="um-lastdonation" class="mb-0 text-muted small"></p></div>
              </div>
            </div>
          </div>

        </div>

        {{-- Footer info bar --}}
        <div class="px-4 py-3 d-flex align-items-center justify-content-between border-top flex-wrap gap-2" style="background:#fff3f3;">
          <small class="text-muted"><i class="bi bi-clock me-1"></i>Registered: <span id="um-registered" class="fw-semibold text-dark"></span></small>
          <a id="um-editlink" href="#" class="btn btn-sm btn-danger rounded-pill px-3 fw-semibold">
            <i class="bi bi-pencil-fill me-1"></i>Edit User
          </a>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════
     MODAL: Blood Request Detail
══════════════════════════════════════════════ --}}
<div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg" style="border-radius:1.25rem; overflow:hidden;">

      {{-- Header --}}
      <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg,#1D3557 0%,#0d1f33 100%); padding: 1.5rem 1.75rem;">
        <div class="d-flex align-items-center gap-3 w-100">
          <div id="rm-bloodbadge" class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
               style="width:52px;height:52px;background:rgba(255,255,255,.18);color:#fff;font-size:1rem;"></div>
          <div class="text-white">
            <h5 class="modal-title fw-bold mb-0" id="rm-patient"></h5>
            <small id="rm-statusbadge" class="opacity-75" style="font-size:.75rem;"></small>
          </div>
          <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
        </div>
      </div>

      {{-- Body --}}
      <div class="modal-body p-0">
        <div class="row g-0">

          <div class="col-md-6 p-4" style="background:#fafafa; border-right:1px solid #f0f0f0;">
            <p class="text-muted text-uppercase fw-bold mb-3" style="font-size:.68rem;letter-spacing:.08em;">Request Details</p>
            <div class="d-flex flex-column gap-3">
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-droplet-fill text-danger mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Blood Group</p><p id="rm-blood" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-collection-fill text-danger mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Units Required</p><p id="rm-units" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-exclamation-triangle-fill text-warning mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Urgency Level</p><p id="rm-urgency" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-hospital-fill text-primary mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Hospital / Location</p><p id="rm-hospital" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-credit-card-fill text-secondary mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Patient CNIC</p><p id="rm-cnic" class="mb-0 text-muted small"></p></div>
              </div>
            </div>
          </div>

          <div class="col-md-6 p-4">
            <p class="text-muted text-uppercase fw-bold mb-3" style="font-size:.68rem;letter-spacing:.08em;">Requester Info</p>
            <div class="d-flex flex-column gap-3">
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-person-fill text-secondary mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Requested By</p><p id="rm-requestedby" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-envelope-fill text-secondary mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Requester Email</p><p id="rm-requesteremail" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-chat-left-text-fill text-secondary mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Description</p><p id="rm-description" class="mb-0 text-muted small"></p></div>
              </div>
            </div>
          </div>

        </div>

        <div class="px-4 py-3 d-flex align-items-center justify-content-between border-top flex-wrap gap-2" style="background:#f0f5ff;">
          <small class="text-muted"><i class="bi bi-clock me-1"></i>Submitted: <span id="rm-date" class="fw-semibold text-dark"></span></small>
          <a href="{{ route('admin.requests') }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">
            <i class="bi bi-list-ul me-1"></i>View All Requests
          </a>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════
     MODAL: Donation Detail
══════════════════════════════════════════════ --}}
<div class="modal fade" id="donationModal" tabindex="-1" aria-labelledby="donationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg" style="border-radius:1.25rem; overflow:hidden;">

      {{-- Header --}}
      <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg,#10b981 0%,#047857 100%); padding: 1.5rem 1.75rem;">
        <div class="d-flex align-items-center gap-3 w-100">
          <div id="dm-avatar" class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
               style="width:52px;height:52px;background:rgba(255,255,255,.25);font-size:.95rem;"></div>
          <div class="text-white">
            <h5 class="modal-title fw-bold mb-0" id="dm-donor"></h5>
            <small class="opacity-75" style="font-size:.72rem;">Donation Record</small>
          </div>
          <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
        </div>
      </div>

      {{-- Body --}}
      <div class="modal-body p-0">
        <div class="row g-0">

          <div class="col-md-6 p-4" style="background:#fafafa; border-right:1px solid #f0f0f0;">
            <p class="text-muted text-uppercase fw-bold mb-3" style="font-size:.68rem;letter-spacing:.08em;">Donation Info</p>
            <div class="d-flex flex-column gap-3">
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-droplet-fill text-danger mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Blood Group</p><p id="dm-blood" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-collection-fill text-success mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Units Donated</p><p id="dm-units" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-calendar-check-fill text-success mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Donation Date</p><p id="dm-date" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-check-circle-fill text-success mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Status</p><p id="dm-status" class="mb-0 text-muted small"></p></div>
              </div>
            </div>
          </div>

          <div class="col-md-6 p-4">
            <p class="text-muted text-uppercase fw-bold mb-3" style="font-size:.68rem;letter-spacing:.08em;">Donor & Recipient</p>
            <div class="d-flex flex-column gap-3">
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-person-fill text-success mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Donor Name</p><p id="dm-donorname" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-envelope-fill text-secondary mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Donor Email</p><p id="dm-donoremail" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-geo-alt-fill text-secondary mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Donor City</p><p id="dm-donorcity" class="mb-0 text-muted small"></p></div>
              </div>
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-person-heart text-danger mt-1" style="font-size:.85rem;"></i>
                <div><p class="mb-0 small fw-semibold">Recipient</p><p id="dm-recipient" class="mb-0 text-muted small"></p></div>
              </div>
            </div>
          </div>

        </div>

        <div class="px-4 py-3 d-flex align-items-center justify-content-between border-top flex-wrap gap-2" style="background:#f0fff8;">
          <small class="text-muted"><i class="bi bi-hash me-1"></i>Donation ID: <span id="dm-id" class="fw-semibold text-dark"></span></small>
          <a href="{{ route('admin.donations') }}" class="btn btn-sm btn-success rounded-pill px-3 fw-semibold">
            <i class="bi bi-list-ul me-1"></i>View All Donations
          </a>
        </div>
      </div>

    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ── Blood Group Distribution Chart ──────────────────────────────────────
    const bgLabels = {!! json_encode(array_keys($bloodGroupData)) !!};
    const bgData   = {!! json_encode(array_values($bloodGroupData)) !!};

    const allGroups = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
    const finalLabels = bgLabels.length ? bgLabels : allGroups;
    const finalData   = bgLabels.length ? bgData   : allGroups.map(() => 0);

    new Chart(document.getElementById('bloodGroupChart'), {
        type: 'bar',
        data: {
            labels: finalLabels,
            datasets: [{
                label: 'Registered Users',
                data: finalData,
                backgroundColor: [
                    '#E63946cc','#1D3557cc','#f59e0bcc','#10b981cc',
                    '#6f42c1cc','#3b82f6cc','#ec4899cc','#14b8a6cc'
                ],
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y} user(s)` } }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 }, grid: { color: 'rgba(0,0,0,0.04)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // ── Monthly Activity Chart ──────────────────────────────────────────────
    const allMonths = [];
    const now = new Date();
    for (let i = 11; i >= 0; i--) {
        const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
        allMonths.push(d.toISOString().slice(0, 7));
    }

    const monthlyDonations = {!! json_encode($monthlyDonations) !!};
    const monthlyRequests  = {!! json_encode($monthlyRequests) !!};

    const donationValues = allMonths.map(m => monthlyDonations[m] || 0);
    const requestValues  = allMonths.map(m => monthlyRequests[m] || 0);
    const monthLabels    = allMonths.map(m => {
        const [y, mo] = m.split('-');
        return ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][parseInt(mo)-1] + "'" + y.slice(2);
    });

    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [
                {
                    label: 'Donations',
                    data: donationValues,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,0.08)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointBackgroundColor: '#10b981',
                },
                {
                    label: 'Requests',
                    data: requestValues,
                    borderColor: '#E63946',
                    backgroundColor: 'rgba(230,57,70,0.08)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointBackgroundColor: '#E63946',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8 } }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 }, grid: { color: 'rgba(0,0,0,0.04)' } },
                x: { grid: { display: false }, ticks: { font: { size: 11 } } }
            }
        }
    });

    // ── User Modal Population ───────────────────────────────────────────────
    document.getElementById('userModal').addEventListener('show.bs.modal', function(e) {
        const row = e.relatedTarget;
        const d   = row.dataset;
        document.getElementById('um-avatar').textContent   = d.initials;
        document.getElementById('um-name').textContent     = d.name;
        document.getElementById('um-role').textContent     = d.role;
        document.getElementById('um-email').textContent    = d.email;
        document.getElementById('um-phone').textContent    = d.phone;
        document.getElementById('um-whatsapp').textContent = d.whatsapp;
        document.getElementById('um-city').textContent     = d.city;
        document.getElementById('um-blood').textContent    = d.blood;
        document.getElementById('um-cnic').textContent     = d.cnic;
        document.getElementById('um-dob').textContent      = d.dob;
        document.getElementById('um-father').textContent   = d.father;
        document.getElementById('um-lastdonation').textContent = d.lastdonation;
        document.getElementById('um-registered').textContent   = d.registered;
        document.getElementById('um-editlink').href        = d.editurl;
    });

    // ── Request Modal Population ────────────────────────────────────────────
    document.getElementById('requestModal').addEventListener('show.bs.modal', function(e) {
        const row = e.relatedTarget;
        const d   = row.dataset;

        document.getElementById('rm-bloodbadge').textContent   = d.blood;
        document.getElementById('rm-patient').textContent      = d.patient;
        document.getElementById('rm-blood').textContent        = d.blood;
        document.getElementById('rm-units').textContent        = d.units + ' unit(s)';
        document.getElementById('rm-urgency').textContent      = d.urgency;
        document.getElementById('rm-hospital').textContent     = d.hospital;
        document.getElementById('rm-cnic').textContent         = d.cnic;
        document.getElementById('rm-requestedby').textContent  = d.requestedby;
        document.getElementById('rm-requesteremail').textContent = d.requesteremail;
        document.getElementById('rm-description').textContent  = d.description;
        document.getElementById('rm-date').textContent         = d.date;

        // Status badge with colour
        const statusEl  = document.getElementById('rm-statusbadge');
        const colors    = { Pending:'#f59e0b', Approved:'#3b82f6', Fulfilled:'#10b981', Rejected:'#ef4444' };
        statusEl.textContent = d.status;
        statusEl.style.color = colors[d.status] || '#fff';
    });

    // ── Donation Modal Population ───────────────────────────────────────────
    document.getElementById('donationModal').addEventListener('show.bs.modal', function(e) {
        const row = e.relatedTarget;
        const d   = row.dataset;

        document.getElementById('dm-avatar').textContent    = d.initials;
        document.getElementById('dm-donor').textContent     = d.donor;
        document.getElementById('dm-blood').textContent     = d.blood;
        document.getElementById('dm-units').textContent     = d.units + ' unit(s)';
        document.getElementById('dm-date').textContent      = d.date;
        document.getElementById('dm-status').textContent    = d.status;
        document.getElementById('dm-donorname').textContent = d.donor;
        document.getElementById('dm-donoremail').textContent = d.donoremail;
        document.getElementById('dm-donorcity').textContent = d.donorcity;
        document.getElementById('dm-recipient').textContent = d.recipient !== 'N/A' ? d.recipient + ' (' + d.recipientemail + ')' : 'N/A';
        document.getElementById('dm-id').textContent        = '#' + d.id;
    });

    // ── Hover highlight on recent rows ─────────────────────────────────────
    document.querySelectorAll('.recent-item').forEach(function(el) {
        el.addEventListener('mouseenter', function() {
            this.style.background = 'rgba(230,57,70,0.04)';
            this.style.borderRadius = '0.5rem';
            this.style.marginInline = '-0.25rem';
            this.style.paddingInline = '0.25rem';
        });
        el.addEventListener('mouseleave', function() {
            this.style.background = '';
            this.style.borderRadius = '';
            this.style.marginInline = '';
            this.style.paddingInline = '';
        });
    });
});
</script>
@endpush

<style>
.min-w-0 { min-width: 0; }
.recent-item { transition: background .15s ease, border-radius .15s ease; }
.recent-item:hover .bi-chevron-right { color: #E63946 !important; }

/* Admin Stat Cards — responsive */
.admin-stat-card {
    padding: 1.25rem;
    border-radius: 24px;
}
.stat-label {
    font-size: .68rem;
    letter-spacing: .06em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: .35rem;
}
.stat-num { font-size: clamp(1.6rem, 5vw, 2.5rem); }

@media (max-width: 575.98px) {
    /* Tighten container */
    .container.py-5 { padding-top: 1.25rem !important; padding-bottom: 1.25rem !important; }

    /* Stat cards */
    .admin-stat-card { padding: .85rem !important; border-radius: 16px !important; }
    .stat-label { font-size: .6rem !important; }
    .stat-num { font-size: 1.6rem !important; }
    .admin-stat-card .small { font-size: .65rem !important; }

    /* Charts — stack and give breathing room */
    .col-lg-6 { flex: 0 0 100%; max-width: 100%; }

    /* Sidebar nav quick-links — 2×2 grid */
    .col-md-3.col-6 { flex: 0 0 50%; max-width: 50%; }

    /* Recent activity panels — full width stack */
    .col-lg-4 { flex: 0 0 100%; max-width: 100%; }

    /* Glass card general padding reduction */
    .glass-card.p-4 { padding: 1rem !important; }

    /* Recent item rows */
    .recent-item { padding-top: .4rem !important; padding-bottom: .4rem !important; }

    /* Row gaps */
    .g-4 { --bs-gutter-x: .75rem; --bs-gutter-y: .75rem; }
    .g-3 { --bs-gutter-x: .6rem; --bs-gutter-y: .6rem; }
}
</style>
