@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Registration Details</h1>
        <a href="{{ route('admin.approvals') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-2"></i>Back to Approvals
        </a>
    </div>

    <!-- User Information Card -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user me-2"></i>
                        Registration Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Full Name</label>
                                <p class="form-control-plaintext">{{ $user->first_name }} {{ $user->last_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Username</label>
                                <p class="form-control-plaintext">@{{ $user->username }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email Address</label>
                                <p class="form-control-plaintext">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Contact Number</label>
                                <p class="form-control-plaintext">{{ $user->phone ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Role</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-{{ $user->role === 'farmer' ? 'success' : 'info' }}">
                                        <i class="fas fa-{{ $user->role === 'farmer' ? 'seedling' : 'user-shield' }} me-1"></i>
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Barangay</label>
                                <p class="form-control-plaintext">{{ $user->barangay }}</p>
                            </div>
                        </div>
                    </div>

                    @if($user->role === 'admin')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Admin Code</label>
                                    <p class="form-control-plaintext">{{ $user->admin_code }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Position</label>
                                    <p class="form-control-plaintext">{{ $user->position }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($user->role === 'farmer')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Farmer Code</label>
                                    <p class="form-control-plaintext">{{ $user->farmer_code }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Farm Name</label>
                                    <p class="form-control-plaintext">{{ $user->farm_name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Farm Address</label>
                                    <p class="form-control-plaintext">{{ $user->farm_address }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Registration Date</label>
                                <p class="form-control-plaintext">{{ $user->created_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-{{ $user->status === 'pending' ? 'warning' : ($user->status === 'approved' ? 'success' : 'danger') }}">
                                        <i class="fas fa-{{ $user->status === 'pending' ? 'clock' : ($user->status === 'approved' ? 'check' : 'times') }} me-1"></i>
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($user->terms_accepted)
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Terms Accepted</label>
                                    <p class="form-control-plaintext text-success">
                                        <i class="fas fa-check-circle me-2"></i>
                                        User has accepted the terms and conditions
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs me-2"></i>
                        Actions
                    </h6>
                </div>
                <div class="card-body">
                    @if($user->status === 'pending')
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success btn-lg" onclick="approveUser({{ $user->id }})">
                                <i class="fas fa-check me-2"></i>
                                Approve Registration
                            </button>
                            <button type="button" class="btn btn-danger btn-lg" onclick="rejectUser({{ $user->id }})">
                                <i class="fas fa-times me-2"></i>
                                Reject Registration
                            </button>
                        </div>
                    @elseif($user->status === 'approved')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Approved!</strong><br>
                            This user registration was approved on {{ $user->updated_at->format('F d, Y \a\t g:i A') }}
                        </div>
                    @elseif($user->status === 'rejected')
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            <strong>Rejected!</strong><br>
                            This user registration was rejected on {{ $user->updated_at->format('F d, Y \a\t g:i A') }}
                        </div>
                    @endif

                    <hr>

                    <div class="text-center">
                        <a href="mailto:{{ $user->email }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-envelope me-2"></i>
                            Send Email
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>
                        Quick Stats
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="h4 mb-0 text-primary">{{ $user->created_at->diffForHumans() }}</div>
                                <div class="small text-muted">Registered</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-0 text-{{ $user->status === 'pending' ? 'warning' : ($user->status === 'approved' ? 'success' : 'danger') }}">
                                {{ ucfirst($user->status) }}
                            </div>
                            <div class="small text-muted">Status</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                <p>Are you sure you want to approve <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>'s registration?</p>
                <p class="text-muted">The user will be able to access the system immediately after approval.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.approvals.approve', $user->id) }}" style="display: inline;">
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
                <p>Are you sure you want to reject <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>'s registration?</p>
                <div class="mb-3">
                    <label for="rejectionReason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="rejectionReason" name="rejection_reason" rows="3" required placeholder="Please provide a reason for rejection..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.approvals.reject', $user->id) }}" style="display: inline;">
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
        modal.show();
    }

    function rejectUser(userId) {
        const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
        modal.show();
    }

    // Update rejection reason when form is submitted
    document.getElementById('rejectModal').addEventListener('submit', function() {
        const reason = document.getElementById('rejectionReason').value;
        document.getElementById('rejectionReasonInput').value = reason;
    });
</script>
@endpush
