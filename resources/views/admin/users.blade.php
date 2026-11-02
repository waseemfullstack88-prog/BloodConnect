@extends('layouts.main')

@section('title', 'Manage Users — Admin')

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold display-font mb-1"><i class="bi bi-people-fill text-danger me-2"></i>Manage Users</h2>
            <p class="text-muted mb-0">View, search, edit, and delete system users</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert border-0 shadow-sm rounded-4 mb-4 p-3 d-flex align-items-center" style="background: #d1fae5; color: #065f46;">
            <i class="bi bi-check-circle-fill fs-4 me-3" style="color: #10b981;"></i>
            <p class="fw-bold mb-0 small">{{ session('success') }}</p>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 p-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        </div>
    @endif

    {{-- Search/Filter --}}
    <div class="glass-card p-4 mb-4">
        <form method="GET" action="{{ route('admin.users') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Search</label>
                <input type="text" name="search" class="form-control rounded-3"
                       placeholder="Name, email, CNIC..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Role</label>
                <select name="role" class="form-select rounded-3">
                    <option value="">All Roles</option>
                    <option value="donor"     {{ request('role') == 'donor'     ? 'selected' : '' }}>Donor</option>
                    <option value="recipient" {{ request('role') == 'recipient' ? 'selected' : '' }}>Recipient</option>
                    <option value="admin"     {{ request('role') == 'admin'     ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Blood Group</label>
                <select name="blood_group" class="form-select rounded-3">
                    <option value="">All Groups</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">City</label>
                <input type="text" name="city" class="form-control rounded-3"
                       placeholder="City..." value="{{ request('city') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold w-100">
                    <i class="bi bi-search me-1"></i> Search
                </button>
                @if(request()->hasAny(['search', 'role', 'blood_group', 'city']))
                <a href="{{ route('admin.users') }}"
                   class="btn btn-outline-secondary rounded-pill px-3 fw-bold" title="Clear Filters">
                    <i class="bi bi-x-circle"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="glass-card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">User</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Role</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Blood</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">City</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Phone</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold">Registered</th>
                        <th class="border-0 pb-3 small text-uppercase text-muted fw-bold text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                     style="width:36px;height:36px;background:var(--gradient-blood);font-size:.7rem;">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <div>
                                    <span class="fw-bold small d-block">{{ $u->name }}</span>
                                    <span class="text-muted" style="font-size:.72rem;">{{ $u->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $roleBg = match($u->role) {
                                    'admin'  => 'bg-dark',
                                    'donor'  => 'bg-success',
                                    default  => 'bg-primary',
                                };
                            @endphp
                            <span class="badge {{ $roleBg }} rounded-pill px-3 py-1 text-capitalize">{{ $u->role }}</span>
                        </td>
                        <td>
                            <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-3 fw-bold">
                                {{ $u->blood_group ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="small text-muted">{{ $u->city ?? '-' }}</td>
                        <td class="small text-muted">{{ $u->phone ?? '-' }}</td>
                        <td class="small text-muted">{{ $u->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                {{-- Edit --}}
                                <a href="{{ route('admin.users.edit', $u->id) }}"
                                   class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                   title="Edit User" data-bs-toggle="tooltip">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>

                                {{-- Delete --}}
                                @if($u->id !== Auth::id())
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger rounded-pill px-3 delete-trigger"
                                        title="Delete User" data-bs-toggle="tooltip"
                                        data-user-name="{{ $u->name }}"
                                        data-user-email="{{ $u->email }}"
                                        data-user-role="{{ $u->role }}"
                                        data-user-initials="{{ strtoupper(substr($u->name, 0, 2)) }}"
                                        data-delete-url="{{ route('admin.users.delete', $u->id) }}">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                                @else
                                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 disabled"
                                        title="Cannot delete yourself" data-bs-toggle="tooltip">
                                    <i class="bi bi-trash3-fill opacity-25"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            No users found matching your criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
</div>

{{-- ── Delete Confirmation Modal ────────────────────────────────── --}}
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

      <div class="modal-header border-0"
           style="background:linear-gradient(135deg,#E63946,#9b1d24);padding:1.25rem 1.5rem;">
        <div class="d-flex align-items-center gap-2 text-white">
          <i class="bi bi-exclamation-triangle-fill fs-4"></i>
          <h5 class="modal-title fw-bold mb-0">Confirm Permanent Deletion</h5>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body p-4">
        <p class="text-muted small mb-3">You are about to permanently delete:</p>
        <div class="d-flex align-items-center gap-3 glass-card p-3 mb-3">
          <div id="del-avatar"
               class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
               style="width:44px;height:44px;background:var(--gradient-blood);font-size:.8rem;"></div>
          <div>
            <p id="del-name" class="fw-bold mb-0"></p>
            <p id="del-sub"  class="text-muted small mb-0"></p>
          </div>
        </div>

        <div class="rounded-3 p-3 mb-3" style="background:#fef2f2;">
          <p class="small fw-bold text-danger mb-1">
            <i class="bi bi-trash3-fill me-1"></i>The following will also be deleted:
          </p>
          <ul class="mb-0 small text-danger">
            <li>All donations (as donor or recipient)</li>
            <li>All blood requests submitted</li>
            <li>All donor alerts &amp; notifications</li>
            <li>The user account itself</li>
          </ul>
        </div>

        <p class="text-muted small mb-1">Type the user's name below to confirm:</p>
        <input type="text" id="delConfirmInput" class="form-control rounded-3 mt-1"
               placeholder="Type user name here">
      </div>

      <div class="modal-footer border-0 pt-0 px-4 pb-4">
        <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                data-bs-dismiss="modal">Cancel</button>
        <form id="deleteUserForm" method="POST" action="">
          @csrf
          @method('DELETE')
          <button type="submit" id="confirmDeleteBtn"
                  class="btn btn-danger rounded-pill px-4 fw-bold" disabled>
            <i class="bi bi-trash3-fill me-1"></i> Yes, Delete Permanently
          </button>
        </form>
      </div>

    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Bootstrap Tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
        new bootstrap.Tooltip(el);
    });

    let expectedName = '';

    // Populate delete modal from data-* attrs
    document.querySelectorAll('.delete-trigger').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const name     = this.dataset.userName;
            const email    = this.dataset.userEmail;
            const role     = this.dataset.userRole;
            const initials = this.dataset.userInitials;
            const url      = this.dataset.deleteUrl;

            expectedName = name;

            document.getElementById('del-avatar').textContent  = initials;
            document.getElementById('del-name').textContent    = name;
            document.getElementById('del-sub').textContent     = email + ' · ' + role;
            document.getElementById('deleteUserForm').action   = url;
            document.getElementById('delConfirmInput').value   = '';
            document.getElementById('confirmDeleteBtn').disabled = true;

            new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
        });
    });

    // Enable confirm button only when name matches
    document.getElementById('delConfirmInput').addEventListener('input', function () {
        document.getElementById('confirmDeleteBtn').disabled =
            this.value.trim() !== expectedName;
    });
});
</script>
@endpush
