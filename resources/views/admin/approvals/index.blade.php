@extends('layouts.app')

@section('title', 'LBDAIRY: Admin - User Approvals')

@section('content')
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-user-check"></i>
            User Registration Approvals
        </h1>
        <p>Review and approve pending user registrations</p>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Pending Registrations -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header">
            <h6>
                <i class="fas fa-clock mr-2"></i>
                Pending Approvals ({{ $pendingUsers->count() }})
            </h6>
        </div>
        <div class="card-body">
            @if($pendingUsers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="pendingTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Barangay</th>
                                <th>Registration Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingUsers as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm mr-3">
                                            @if($user->profile_image)
                                                <img src="{{ asset('storage/' . $user->profile_image) }}" class="rounded-circle" width="40" height="40" alt="Profile">
                                            @else
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <span class="text-white font-weight-bold">{{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                            <small class="text-muted">@{{ $user->username }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $user->role === 'farmer' ? 'success' : 'info' }}">
                                        <i class="fas fa-{{ $user->role === 'farmer' ? 'seedling' : 'user-shield' }} mr-1"></i>
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->barangay }}</td>
                                <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.approvals.show', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                        <button type="button" class="btn btn-sm btn-success" onclick="approveUser({{ $user->id }})">
                                            <i class="fas fa-check mr-1"></i>Approve
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="rejectUser({{ $user->id }})">
                                            <i class="fas fa-times mr-1"></i>Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">No pending approvals</h5>
                    <p class="text-muted">All user registrations have been processed.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Approved Users -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-check-circle me-2"></i>
                Recently Approved Users
            </h6>
        </div>
        <div class="card-body">
            @if($approvedUsers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Approval Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvedUsers as $user)
                            <tr>
                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role === 'farmer' ? 'success' : 'info' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center">No approved users yet.</p>
            @endif
        </div>
    </div>

    <!-- Recent Rejected Users -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">
                <i class="fas fa-times-circle me-2"></i>
                Recently Rejected Users
            </h6>
        </div>
        <div class="card-body">
            @if($rejectedUsers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Rejection Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rejectedUsers as $user)
                            <tr>
                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role === 'farmer' ? 'success' : 'info' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center">No rejected users yet.</p>
            @endif
        </div>
    </div>

<!-- Approve User Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Approve User Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve this user registration?</p>
                <p class="text-muted">The user will be able to access the system immediately after approval.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="approveForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Approve User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject User Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject User Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reject this user registration?</p>
                <div class="mb-3">
                    <label for="rejectionReason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="rejectionReason" name="rejection_reason" rows="3" required placeholder="Please provide a reason for rejection..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="rejectForm" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="rejection_reason" id="rejectionReasonInput">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>Reject User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function approveUser(userId) {
        const modal = new bootstrap.Modal(document.getElementById('approveModal'));
        const form = document.getElementById('approveForm');
        form.action = `/admin/approvals/${userId}/approve`;
        modal.show();
    }

    function rejectUser(userId) {
        const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
        const form = document.getElementById('rejectForm');
        form.action = `/admin/approvals/${userId}/reject`;
        modal.show();
    }

    // Update rejection reason when form is submitted
    document.getElementById('rejectForm').addEventListener('submit', function() {
        const reason = document.getElementById('rejectionReason').value;
        document.getElementById('rejectionReasonInput').value = reason;
    });

    // DataTable initialization
    $(document).ready(function() {
        $('#pendingTable').DataTable({
            order: [[4, 'asc']], // Sort by registration date
            pageLength: 10,
            language: {
                search: "Search pending users:",
                lengthMenu: "Show _MENU_ users per page",
                info: "Showing _START_ to _END_ of _TOTAL_ pending users"
            }
        });
    });
</script>
@endpush
