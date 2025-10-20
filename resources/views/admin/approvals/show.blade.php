@extends('layouts.app')

@section('content')
<div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
    <div>
        <h1>
            <i class="fas fa-user-check"></i>
            User Registration Details
        </h1>
    </div>
</div>

  <!-- User Information Card -->
<div class="row">
  <div class="col-xl-8 col-lg-7">
    <div class="card shadow mb-4 userinfo-card">
      <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start">
        <h6 class="m-0 fw-bold text-white">
          <i class="fas fa-user me-2"></i>
          Registration Information
        </h6>
        <div class="d-flex justify-content-end">
                <a href="{{ route('admin.approvals') }}" title="Back" class="btn-action btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
      </div>
        <div class="card-body" id="userinfoCardBody">

            <!-- Section: Basic Information -->
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-user me-2"></i><i class="fas fa-clipboard-list me-2"></i> Basic Information</h6>
                    <p class="text-left"><strong>Full Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
                    <p class="text-left"><strong>Username:</strong> {{ $user->username }}</p>
                    <p class="text-left"><strong>Email Address:</strong> {{ $user->email }}</p>
                    <p class="text-left"><strong>Contact Number:</strong> {{ $user->phone ?? 'Not provided' }}</p>
                    <p class="text-left"><strong>Role:</strong><span class="role-badge role-{{ $user->role === 'farmer' ? 'success' : 'info' }}">
                        <i class="fas fa-{{ $user->role === 'farmer' ? 'seedling' : 'user-shield' }} me-1"></i>{{ ucfirst($user->role) }}</span>
                    </p>
                    <p class="text-left"><strong>Barangay:</strong> {{ $user->barangay }}</p>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-user-shield me-2"></i><i class="fas fa-id-card me-2"></i> Admin Details</h6>
                    <p class="text-left"><strong>Admin Code:</strong> {{ $user->admin_code }}</p>
                    <p class="text-left"><strong>Position:</strong> {{ $user->position }}</p>
                    <p class="text-left"><strong>Farmer Code:</strong> {{ $user->farmer_code }}</p>
                    <p class="text-left"><strong>Farm Address:</strong> {{ $user->farm_address }}</p>
                    <p class="text-left"><strong>Registration Date:</strong> {{ $user->created_at->format('F d, Y \a\t g:i A') }}</p>
                    <p class="text-left"><strong>Status:</strong> <span class="role-badge status-{{ $user->status === 'pending' ? 'warning' : ($user->status === 'approved' ? 'success' : 'danger') }}">
                        <i class="fas fa-{{ $user->status === 'pending' ? 'clock' : ($user->status === 'approved' ? 'check' : 'times') }} me-1"></i>{{ ucfirst($user->status) }}</span>
                    </p>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-file-contract me-2"></i> Terms</h6>
                    <p class="text-left">Terms Accepted: User has accepted the terms and conditions</p>
                </div>
            </div>
        </div>
    </div>
  </div>



        <!-- Action Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-cogs me-2"></i>
                        Actions
                    </h6>
                </div>
                <div class="card-body">
                    @if($user->status === 'pending')
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <button type="button" class="btn-action btn-success btn-lg" title="Approve Registration" onclick="approveUser({{ $user->id }})">
                                    <i class="fas fa-check mr-2"></i> Approve Registration
                                </button>
                            </div>
                            <div class="col-12 col-md-6">
                                <button type="button" class="btn-action btn-danger  btn-lg" title="Reject Registration" onclick="rejectUser({{ $user->id }})">
                                    <i class="fas fa-times mr-2"></i> Reject Registration
                                </button>
                            </div>
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
                        <a href="mailto:{{ $user->email }}" title="Send Email" class="btn-action btn-action-send">
                            <i class="fas fa-envelope me-2"></i>
                            Send Email
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card shadow mb-4">
                <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start">
                    <h6 class="m-0 font-weight-bold ">
                        <i class="fas fa-chart-bar me-2"></i>
                        Quick Stats
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="h4 mb-0 ">{{ $user->created_at->diffForHumans() }}</div>
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

<!-- Modern Approve Farmer Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content smart-modal text-center p-4">
            <!-- Icon -->
            <div class="icon-wrapper mx-auto mb-4 text-success">
                <i class="fas fa-check-circle fa-2x"></i>
            </div>
            <!-- Title -->
            <h5>Approve User Registration</h5>
            <!-- Description -->
            <div class="text-muted mb-4 px-3">
                <p> Are you sure you want to <strong>approve</strong> this user’s registration?
                    The user will gain full access to the system immediately after approval. 
                </p>
            </div>


            <!-- Form -->
            <div class="modal-footer d-flex gap-2 justify-content-center flex-wrap">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.approvals.approve', $user->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-modern btn-approve">
                        Approve User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modern Rejection Reason Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content smart-modal text-center p-4">
            <!-- Icon -->
            <div class="icon-wrapper mx-auto mb-4 text-danger">
                <i class="fas fa-times-circle fa-2x"></i>
            </div>
            <!-- Title -->
            <h5>Reject User Registration</h5>
            <!-- Description -->
            <div class="text-muted mb-4 px-3">
                <p>Are you sure you want to <strong>reject</strong> this user’s registration?
                The user will <strong>not</strong> be able to access the system after rejection.</p>
            </div>



            <!-- Form -->
            <form onsubmit="submitRejection(event)">
                @csrf
                <input type="hidden" id="farmerIdHidden">

                <!-- Reason Field -->
                <div class="form-group px-3 text-start">
                    <label for="rejectionReason" class="fw-semibold  text-start">
                        Reason for Rejection <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control mt-1" id="rejectionReason" rows="4" required placeholder="Enter reason for rejection..."></textarea>
                </div>


                <!-- Buttons -->
                <div class="modal-footer d-flex gap-2 justify-content-center flex-wrap mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.approvals.reject', $user->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-modern btn-delete">
                        Reject User
                    </button>
                </form>
                </div>
            </form>

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
@push('styles')
<style>
    /* Role badge base styles */
    .role-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }

    /* Success variant (farmer) */
    .role-badge.role-success {
        background: #387057;
        color: #ffffff;
    }
    /* Info variant (admin/other roles) */
    .role-badge.role-info {
        background: #18375d;
        color: #ffffff;
    }
    .role-badge.role-warning {
        background: #fca700;
        color: #ffffff;
    }

    /* Success variant (farmer) */
    .role-badge.status-pending {
        background: #387057;
        color: #ffffff;
    }
    /* Info variant (admin/other roles) */
    .role-badge.status-warning {
        background: #fca700;
        color: #ffffff;
    }
    .role-badge.status-danger {
        background: #dc3545;
        color: #ffffff;
    }
    /* Success variant (farmer) */
    .role-badge.status-approve {
        background: #387057;
        color: #ffffff;
    }=
.smart-modal {
  border: none;
  border-radius: 16px;
  background: #fff;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  max-width: 500px;
  margin: auto;
  transition: all 0.3s ease;
}

.smart-modal .icon-wrapper {
  background-color: #ffffffff;
  color: #18375d;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
}

.smart-modal h5 {
  color: #18375d;
  font-weight: 600;
}

.smart-modal p {
  color: #6b7280;
  font-size: 0.95rem;
}
.btn-approve {
  background: #387057;
  color: #fff;
  border: none;
}
.btn-approve:hover {
  background: #fca700;
}
.btn-delete {
  background: #dc3545;
  color: #fff;
  border: none;
}
.btn-delete:hover {
  background: #fca700;
}
.btn-ok {
  background: #18375d;
  color: #fff;
  border: none;
}
.btn-ok:hover {
  background: #fca700;
}

/* Contact Farmer Modal Alignment */
#rejectModal .smart-modal {
    text-align: center; /* Keep header text centered */
}

#rejectModal form {
    text-align: left; /* Align form content to the left */
}

/* Make sure labels, inputs, and textareas are properly aligned */
#rejectModal .form-group {
    width: 100%;
    max-width: 700px; /* Optional: limits width for large screens */
    margin: 0 auto; /* Centers the form container */
}

/* Label styling */
#rejectModal label {
    display: block;
    font-weight: 600;
    color: #333;
}

/* Inputs and Textareas */
#rejectModal .form-control {
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 15px;
    box-shadow: none;
}

/* Keep modal buttons centered */
#rejectModal .modal-footer {
    text-align: center;
}

/* Optional: Add smooth focus effect */
#rejectModal .form-control:focus {
    border-color: #198754; /* Bootstrap green */
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}



/* Contact Farmer Modal Alignment */
#approveModal .smart-modal {
    text-align: center; /* Keep header text centered */
}

#rejectapproveModalionModal form {
    text-align: left; /* Align form content to the left */
}

/* Make sure labels, inputs, and textareas are properly aligned */
#approveModal .form-group {
    width: 100%;
    max-width: 700px; /* Optional: limits width for large screens */
    margin: 0 auto; /* Centers the form container */
}

/* Label styling */
#approveModal label {
    display: block;
    font-weight: 600;
    color: #333;
}

/* Inputs and Textareas */
#approveModal .form-control {
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 15px;
    box-shadow: none;
}

/* Keep modal buttons centered */
#approveModal .modal-footer {
    text-align: center;
}

/* Optional: Add smooth focus effect */
#approveModal .form-control:focus {
    border-color: #198754; /* Bootstrap green */
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}
/* Ensure internal content aligns properly */
.smart-modal .details-content {
    text-align: left;
}
/* Custom modal width */
.custom-width {
  max-width: 1000px !important; /* adjust as needed */
  width: 90%; /* keeps it responsive */
}

/* Optional: make sure it scales well on smaller screens */
@media (max-width: 992px) {
  .custom-width {
    max-width: 95% !important;
    width: 95%;
  }
}
    /* 🌟 Page Header Styling */
.page {
    background-color: #18375d;
    border-radius: 12px;
    padding: 1.5rem 2rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease-in-out;
    animation: fadeIn 0.6s ease-in-out;
}

/* Hover lift effect for interactivity */
.page:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
}

/* 🧭 Header Title */
.page h1 {
    color: #18375d;
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Icon style */
.page i {
    color: #18375d; /* Bootstrap primary color */
}

/* 💬 Subtitle text */
.page p {
    color: #18375d;
    font-size: 1rem;
    margin: 0;
}

/* ✨ Fade-in Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
/* Base Card Style */
.card {
    background-color: #ffffff !important;
    border: none;
    border-radius: 0.75rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
}

/* Top Section (Header inside card-body) */
.card-body:first-of-type {
    background-color: #ffffff;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    border-top-left-radius: 0.75rem;
    border-top-right-radius: 0.75rem;
    padding: 1rem 1.5rem;
}

/* Title (h6) */
.card-body:first-of-type h6 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 700;
    color: #18375d !important;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Second Card Body (Main Content) */
.card-body:last-of-type {
    background-color: #ffffff;
    padding: 1.25rem 1.5rem;
    border-bottom-left-radius: 0.75rem;
    border-bottom-right-radius: 0.75rem;
}
    /* CRITICAL FIX FOR DROPDOWN TEXT CUTTING */
    .admin-modal select.form-control,
    .modal.admin-modal select.form-control,
    .admin-modal .modal-body select.form-control {
        min-width: 250px !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding: 0.75rem 2rem 0.75rem 0.75rem !important;
        white-space: nowrap !important;
        text-overflow: clip !important;
        overflow: visible !important;
        font-size: 0.875rem !important;
        line-height: 1.5 !important;
    }
    
    /* Ensure columns don't constrain dropdowns */
    .admin-modal .col-md-6 {
        min-width: 280px !important;
        overflow: visible !important;
    }

    #approveModal .modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
}

#approveModal .modal-header {
    background: #18375d !important; /* vivid green for approve */
    color: white !important;
    border-bottom: none !important;
    border-radius: 12px 12px 0 0 !important;
}

#approveModal .modal-title {
    color: white !important;
    font-weight: 600;
}

#approveModal .modal-body {
    padding: 2rem;
    background: white;
}

#approveModal .modal-body h6 {
    color: #18375d !important;
    font-weight: 600 !important;
    border-bottom: 2px solid #e3e6f0;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem !important;
}

#approveModal .modal-body p {
    margin-bottom: 0.75rem;
    color: #333 !important;
}

#approveModal .modal-body strong {
    color: #5a5c69 !important;
    font-weight: 600;
}

#approveModal .form-group label {
    font-weight: 600;
    color: #39a400;
    display: inline-block;
    margin-bottom: 0.5rem;
}
#rejectModal .modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
}

#rejectModal .modal-header {
    background: #18375d !important; /* red for reject */
    color: white !important;
    border-bottom: none !important;
    border-radius: 12px 12px 0 0 !important;
}

#rejectModal .modal-title {
    color: white !important;
    font-weight: 600;
}

#rejectModal .modal-body {
    padding: 2rem;
    background: white;
}

#rejectModal .modal-body h6 {
    color: #18375d !important;
    font-weight: 600 !important;
    border-bottom: 2px solid #e3e6f0;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem !important;
}

#rejectModal .modal-body p {
    margin-bottom: 0.75rem;
    color: #18375d !important;
}
#rejectModal .modal-body strong {
    color: #18375d !important;
    font-weight: 600;
}

#rejectModal .form-group label {
    font-weight: 600;
    color: #18375d;
    display: inline-block;
    margin-bottom: 0.5rem;
}
    
    /* Custom styles for user management */
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    
    .border-left-danger {
        border-left: 0.25rem solid #e74a3b !important;
    }
    
    /* Search and button group alignment */
    .search-controls {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    @media (min-width: 768px) {
        .search-controls {
            flex-direction: row;
            justify-content: space-between;
            align-items: flex-end; /* Align to bottom for perfect leveling */
        }
    }
    
    .search-controls .input-group {
        flex-shrink: 0;
        align-self: flex-end; /* Ensure input group aligns to bottom */
    }
    
    .search-controls .btn-group {
        flex-shrink: 0;
        align-self: flex-end; /* Ensure button group aligns to bottom */
        display: flex;
        align-items: center;
    }
    
    /* Ensure buttons have consistent height with input */
    .search-controls .btn-action {
        height: 38px; /* Match Bootstrap input height */
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }
    
    /* Ensure dropdown button is perfectly aligned */
    .search-controls .dropdown .btn-action {
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Ensure all buttons in the group have the same baseline */
    .search-controls .d-flex {
        align-items: center;
        gap: 0.75rem; /* Increased gap between buttons */
    }
    
    @media (max-width: 767px) {
        .search-controls {
            align-items: stretch;
        }
        
        .search-controls .btn-group {
            margin-top: 0.5rem;
            justify-content: center;
            align-self: center;
        }
        
        .search-controls .input-group {
            max-width: 100% !important;
        }
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.075);
    }
    
    .badge {
        font-size: 0.75em;
        padding: 0.375em 0.75em;
    }
    
    .badge-pill {
        border-radius: 50rem;
        padding-left: 0.75em;
        padding-right: 0.75em;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Ensure all role badges have identical pill shape */
    .badge-danger.badge-pill,
    .badge-primary.badge-pill,
    .badge-success.badge-pill,
    .badge-secondary.badge-pill {
        border-radius: 50rem !important;
        padding: 0.5em 1em !important;
        font-size: 0.7em;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        line-height: 1.2;
        display: inline-block;
        white-space: nowrap;
        vertical-align: baseline;
    }
    
    /* Force pill shape override for any conflicting styles */
    .badge.badge-pill {
        border-radius: 50rem !important;
        padding: 0.5em 1em !important;
    }
    
    /* Make admin badge (primary) look identical to superadmin pill shape */
    .badge-primary {
        border-radius: 50rem !important;
        padding: 0.5em 1em !important;
        font-size: 0.7em;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        line-height: 1.2;
        display: inline-block;
        white-space: nowrap;
        vertical-align: baseline;
    }

    /* User ID link styling - superadmin theme */
    .user-id-link {
        color: #18375d;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
        transition: color 0.2s ease;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        background-color: rgba(24, 55, 93, 0.1);
        border: 1px solid rgba(24, 55, 93, 0.2);
    }

    .user-id-link:hover {
        color: #fff;
        background-color: #18375d;
        border-color: #18375d;
        text-decoration: none;
    }

    .user-id-link:active {
        color: #fff;
        background-color: #122a4e;
        border-color: #122a4e;
    }
    
    .btn-group .btn {
        margin-right: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .gap-2 {
        gap: 0.5rem !important;
    }
    
    .badge-sm {
        font-size: 0.6em;
        padding: 0.2em 0.4em;
    }
    
    .badge-success .fas.fa-circle {
        animation: pulse 2s infinite;
    }

  /* User Info Card Styling */
.userinfo-card {
  border: none;
  border-radius: 12px;
  overflow: hidden;
}

.userinfo-card .card-header {
  background: #18375d !important;
  color: white !important;
  border: none !important;
  border-radius: 12px 12px 0 0 !important;
}

.userinfo-card .card-header h6 {
  color: white !important;
  font-weight: 600;
}

#userinfoCardBody {
  padding: 2rem;
  background: #fff;
}

#userinfoCardBody h6 {
  color: #18375d !important;
  font-weight: 600 !important;
  border-bottom: 2px solid #e3e6f0;
  padding-bottom: 0.5rem;
  margin-bottom: 1rem !important;
}

#userinfoCardBody p {
  margin-bottom: 0.75rem;
  color: #333 !important;
}

#userinfoCardBody strong {
  color: #5a5c69 !important;
  font-weight: 600;
}

/* Style all labels inside the card */
#userinfoCardBody .form-group label {
  font-weight: 600;
  color: #18375d;
  display: inline-block;
  margin-bottom: 0.5rem;
}


    
    /* Override badge colors for status column to ensure proper colors */
    #usersTable .badge-danger {
        background-color: #dc3545 !important;
        color: white !important;
    }
    
    #usersTable .badge-warning {
        background-color: #ffc107 !important;
        color: #212529 !important;
    }
    
    #usersTable .badge-success {
        background-color: #387057 !important;
        color: white !important;
    }
    
    /* Fix admin role badge text color */
    #usersTable .badge-warning {
        background-color: #fca700 !important;
        color: white !important;
    }
    
    /* Ensure superadmin stays dark blue */
    #usersTable .badge-primary {
        background-color: #18375d !important;
        color: white !important;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
   /* Apply consistent buttons */
/* Action buttons styling */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        justify-content: center;
        min-width: 200px;
    }
    
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.25rem;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        white-space: nowrap;
    }
    
    .btn-action-edit {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
    .btn-action-edit:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: #ffffffff;
    }

    .btn-action-send {
        background-color: #ffffffff;
        border-color: #ffffffff;
        color: #18375d;
    }
    
    .btn-action-send:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-view-livestock, .btn-action-report-livestock {
        background-color: #18375d;
        border-color: #18375d;
        color: white;
    }
    
    .btn-action-view-livestock:hover, .btn-action-report-livestock:hover {
        background-color: #e69500;
        border-color: #e69500;
        color: white;
    }

    /* Header action buttons styling to match Edit/Delete buttons */
    .btn-action-add {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    .btn-action-ok {
        background-color: #18375d;
        border-color: #18375d;
        color: white;
    }
    .btn-action-ok:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }

    .btn-action-deletes {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .btn-action-deletes:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-print {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: white !important;
    }
    
    .btn-action-print:hover {
        background-color: #5a6268 !important;
        border-color: #5a6268 !important;
        color: white !important;
    }
    
    .btn-action-cancel {
        background-color: #6c757d ;
        border-color: #6c757d ;
        color: white ;
    }
    
    .btn-action-refresh-, .btn-action-refresh- {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-refresh-:hover, .btn-action-refresh-:hover {
        background-color: #e69500;
        border-color: #e69500;
        color: white;
    }

    .btn-action-tools {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #495057;
    }
    
    .btn-action-tools:hover {
        background-color: #e2e6ea;
        border-color: #cbd3da;
        color: #495057;
    }
    
    /* Ensure table has enough space for actions column */
    .table th:last-child,
    .table td:last-child {
        min-width: 200px;
        width: auto;
    }
    
    /* Table responsiveness and spacing */
    .table-responsive {
        overflow-x: auto;
        min-width: 100%;
        position: relative;
    }
    
    /* Ensure DataTables controls are properly positioned */
    .table-responsive + .dataTables_wrapper,
    .table-responsive .dataTables_wrapper {
        width: 100%;
        position: relative;
    }
    
    /* Fix pagination positioning for wide tables */
    .table-responsive .dataTables_wrapper .dataTables_paginate {
        position: relative;
        width: 100%;
        text-align: left;
        margin: 1rem 0;
        left: 0;
        right: 0;
    }
    
    #usersTable {
        width: 100% !important;
        min-width: 1280px;
        border-collapse: collapse;
    }
    
    /* Ensure consistent table styling */
    .table {
        margin-bottom: 0;
    }
    
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.075);
    }
    

    /* Ensure consistent table styling */
    .table {
        margin-bottom: 0;
    }
    
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.075);
    }
    
    
    /* Table-responsive wrapper positioning - match active admins spacing */
    .table-responsive {
        overflow-x: auto;
        min-width: 100%;
        position: relative;
        border-radius: 8px;
        overflow: hidden;
    }
    
    /* Ensure DataTables controls are properly positioned */
    .table-responsive + .dataTables_wrapper,
    .table-responsive .dataTables_wrapper {
        width: 100%;
        position: relative;
    }
    
    /* Fix pagination positioning for wide tables - match active admins spacing */
    .table-responsive .dataTables_wrapper .dataTables_paginate {
        position: relative;
        width: 100%;
        text-align: left;
        margin: 1rem 0;
        left: 0;
        right: 0;
    }
    
    /* Action buttons styling to match active admins table */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        justify-content: center;
        min-width: 200px;
    }
    
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        border: 1px solid transparent;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        white-space: nowrap;
    }
    
    .btn-action-delete {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .btn-action-delete:hover {
        background-color: #c82333;
        border-color: #c82333;
        color: white;
    }
    
    /* Ensure table has enough space for actions column */
    .table th:last-child,
    .table td:last-child {
        min-width: 200px;
        width: auto;
        text-align: center;
        vertical-align: middle;
    }
    
    /* Responsive adjustments */
    @media (max-width: 1200px) {
        .action-buttons {
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .btn-action {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
    }
    /* Custom styles for farmer management */
    
    .card-header .btn-group {
        margin-left: 0.5rem;
    }
    
    .card-header .input-group {
        margin-bottom: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .card-header .d-flex {
            flex-direction: column !important;
        }
        
        .card-header .btn-group {
            margin-left: 0;
            margin-top: 0.5rem;
        }
        
        .card-header .input-group {
            margin-bottom: 0.5rem;
            max-width: 100% !important;
        }
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.075);
    }
    
    .badge {
        font-size: 0.75em;
        padding: 0.375em 0.75em;
    }
    
    .btn-group .btn {
        margin-right: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .gap-2 {
        gap: 0.5rem !important;
    }
    
    /* Status badges */
    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status-approved {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .status-active {
        background-color: #d1ecf1;
        color: #0c5460;
    }
    
    /* Table-responsive wrapper positioning */
    .table-responsive {
        overflow-x: auto;
        min-width: 100%;
        position: relative;
        border-radius: 8px;
        overflow: hidden;
    }
    
    /* Ensure DataTables controls are properly positioned */
    .table-responsive + .dataTables_wrapper,
    .table-responsive .dataTables_wrapper {
        width: 100%;
        position: relative;
    }
    
    /* Fix pagination positioning for wide tables - match active admins spacing */
    .table-responsive .dataTables_wrapper .dataTables_paginate {
        position: relative;
        width: 100%;
        text-align: left;
        margin: 1rem 0;
        left: 0;
        right: 0;
    }
    
    /* ===== DATATABLE STYLES ===== */
.dataTables_length {
    margin-bottom: 1rem;
}

.dataTables_length select {
    min-width: 80px;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background-color: #fff;
    margin: 0 0.5rem;
}

.dataTables_length label {
    display: flex;
    align-items: center;
    margin-bottom: 0;
    font-weight: 500;
    color: var(--dark-color);
}

.dataTables_info {
    padding-top: 0.5rem;
    font-weight: 500;
    color: var(--dark-color);
}

.dataTables_paginate {
    margin-top: 1rem;
}

.dataTables_paginate .paginate_button {
    padding: 0.5rem 0.75rem;
    margin: 0 0.125rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background-color: #fff;
    color: var(--dark-color);
    text-decoration: none;
    transition: var(--transition-fast);
}

.dataTables_paginate .paginate_button:hover {
    background-color: var(--light-color);
    border-color: var(--primary-light);
    color: var(--primary-color);
}

.dataTables_paginate .paginate_button.current {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

.dataTables_paginate .paginate_button.disabled {
    color: var(--text-muted);
    cursor: not-allowed;
    background-color: var(--light-color);
    border-color: var(--border-color);
}

.dataTables_filter {
    margin-bottom: 1rem;
}

.dataTables_filter input {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background-color: #fff;
    transition: var(--transition-fast);
}

.dataTables_filter input:focus {
    border-color: var(--primary-light);
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    outline: 0;
}
    
</style>
@endpush


