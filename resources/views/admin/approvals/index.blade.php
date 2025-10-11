@extends('layouts.app')

@section('title', 'LBDAIRY: Admin - User Approvals')

@section('content')
    <div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
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
        <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start">
            <h6 class="mb-0">
                <i class="fas fa-clock mr-2"></i>
                Pending Approvals ({{ $pendingUsers->count() }})
            </h6>
        </div>
        <div class="card-body">
            <div class="search-controls mb-3">
                <div class="input-group" style="max-width: 300px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search pending approvals..." id="pendingSearch">
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-center">
                    <button class="btn-action btn-action-refresh-pending" title="Refresh" onclick="refreshPendingData('pendingTable')">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="pendingTable" width="100%" cellspacing="0">
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
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Recent Approved Users -->
    <div class="card shadow mb-4">
        <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-check-circle me-2"></i>
                Recently Approved Users
            </h6>
        </div>
        <div class="card-body">
            @if($approvedUsers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="approvedTable" width="100%" cellspacing="0">
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
                                    <span class="role-badge role-{{ $user->role === 'farmer' ? 'success' : 'info' }}">
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
        <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start">
            <h6 class="m-0 font-weight-bold text-danger">
                <i class="fas fa-times-circle me-2"></i>
                Recently Rejected Users
            </h6>
        </div>
        <div class="card-body">
            @if($rejectedUsers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="rejectedTable" width="100%" cellspacing="0">
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
                                    <span class="role-badge role-{{ $user->role === 'farmer' ? 'success' : 'info' }}">
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

<!-- Smart Approve User Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content smart-modal text-center p-4">

      <!-- Icon -->
      <div class="icon-wrapper mx-auto mb-3 text-success">
        <i class="fas fa-check-circle fa-2x"></i>
      </div>

      <!-- Title -->
      <h5 id="approveModalLabel" class="fw-bold mb-2">
        Approve User Registration
      </h5>

      <!-- Message -->
      <p class="text-muted mb-1">
        Are you sure you want to approve this user registration?
      The user will gain full access to the system immediately after approval.
      </p>

      <!-- Footer -->
      <div class="modal-footer d-flex justify-content-center gap-2 flex-wrap">
        <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">
          Close
        </button>
        <form id="approveForm" method="POST" style="display: inline;">
          @csrf
          <button type="submit" class="btn-modern btn-approve">
            Approve User
          </button>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Modern Reject User Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content smart-modal text-center p-4">

      <!-- Icon -->
      <div class="icon-wrapper mx-auto mb-4 text-danger">
        <i class="fas fa-times-circle fa-2x"></i>
      </div>

      <!-- Title -->
      <h5 id="rejectModalLabel" class="fw-bold mb-2">Reject User Registration</h5>

      <!-- Description -->
      <p class="text-muted mb-4 px-4">
        Please provide a reason for rejecting this user’s registration. This ensures proper record keeping and transparency.
      </p>

      <!-- Form -->
      <form id="rejectForm" method="POST" onsubmit="submitUserRejection(event)">
        @csrf

        <!-- Reason Field -->
        <div class="form-group px-3 text-start">
          <label for="rejectionReason" class="fw-semibold ">
            Reason for Rejection <span class="text-danger">*</span>
          </label>
          <textarea
            class="form-control mt-2"
            id="rejectionReason"
            name="rejection_reason"
            rows="4"
            required
            placeholder="Enter reason for rejection..."></textarea>
        </div>

        <!-- Buttons -->
        <div class="modal-footer d-flex justify-content-center gap-2 flex-wrap mt-4 border-0">
          <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">
            Cancel
          </button>
          <button type="submit" class="btn-modern btn-delete">
            Reject User
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


@endsection

@push('scripts')
<script>
let pendingTable; // global reference

$(document).ready(function () {
    // ✅ Initialize DataTable
    initializeDataTables();

    // ✅ Custom search functionality
    $('#pendingSearch').on('keyup', function () {
        if (pendingTable) {
            pendingTable.search(this.value).draw();
        }
    });

    // ✅ Show notification if refresh flag is set
    if (sessionStorage.getItem('showRefreshNotificationPending') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationPending');
        setTimeout(() => {
            showNotification('Pending Approvals data refreshed successfully!', 'success');
        }, 500);
    }
});

// ✅ Initialize DataTables
function initializeDataTables() {
    const commonConfig = {
        dom: 'Bfrtip',
        searching: true,
        paging: true,
        info: true,
        ordering: true,
        lengthChange: false,
        pageLength: 10,
        processing: true,
        serverSide: false,
        language: {
            search: "",
            emptyTable: `
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h5>No data available</h5>
                    <p>There are no records to display at this time.</p>
                </div>`,
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        }
    };

    // ✅ Assign to global variable so search + export work
    pendingTable = $('#pendingTable').DataTable({
        ...commonConfig,
        ajax: {
            url: '/admin/approvals/pending-data',
            dataSrc: function(json) {
                return Array.isArray(json) ? json : [];
            }
        },
        columns: [
            {
                data: null,
                render: function(data) {
                    const initials = `${(data.first_name||'').charAt(0)}${(data.last_name||'').charAt(0)}`.toUpperCase();
                    const img = data.profile_image ? `<img src="/storage/${data.profile_image}" class="rounded-circle" width="40" height="40" alt="Profile">` : `<div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><span class="text-white font-weight-bold">${initials}</span></div>`;
                    const name = `${data.first_name||''} ${data.last_name||''}`.trim();
                    const username = data.username ? `@${data.username}` : '';
                    return `
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm mr-3">${img}</div>
                            <div>
                                <div class="font-weight-bold">${name}</div>
                                <small class="text-muted">${username}</small>
                            </div>
                        </div>`;
                }
            },
            {
                data: 'role',
                render: function(role) {
                    const cls = role === 'farmer' ? 'success' : 'info';
                    const icon = role === 'farmer' ? 'seedling' : 'user-shield';
                    const label = role ? role.charAt(0).toUpperCase() + role.slice(1) : '';
                    return `<span class="badge badge-${cls}"><i class="fas fa-${icon} mr-1"></i>${label}</span>`;
                }
            },
            { data: 'email' },
            { data: 'barangay' },
            { data: 'created_at' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(row) {
                    return `
                        <div class="btn-group" role="group">
                            <a href="/admin/approvals/${row.id}" title="View" class="btn-action btn-action-ok">
                                <i class="fas fa-eye mr-1"></i>View
                            </a>
                            <button type="button" class="btn-action btn-action-edit" title="Approve" onclick="approveUser(${row.id})">
                                <i class="fas fa-check mr-1"></i>Approve
                            </button>
                            <button type="button" class="btn-action btn-action-deletes" title="Reject" onclick="rejectUser(${row.id})">
                                <i class="fas fa-times mr-1"></i>Reject
                            </button>
                        </div>`;
                }
            }
        ],
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Pending_Approvals',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Pending_Approvals',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Pending Approvals',
                className: 'd-none'
            }
        ]
    });

    // Hide default DataTables elements
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();

    console.log("✅ pendingTable initialized", pendingTable);
}

// Refresh Admins Table
function refreshPendingData() {
    const refreshBtn = document.querySelector('.btn-action-refresh-pending');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    refreshBtn.disabled = true;

    // Use unique flag for admins
    sessionStorage.setItem('showRefreshNotificationAlerts', 'true');

    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Check notifications after reload
$(document).ready(function() {
    if (sessionStorage.getItem('showRefreshNotificationAlerts') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationAlerts');
        setTimeout(() => {
            showNotification('Pending Approvals data refreshed successfully!', 'success');
        }, 500);
    }
});

function showNotification(message, type) {
    const notification = $(`
        <div class="alert alert-${type} alert-dismissible fade show refresh-notification">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'times-circle'}"></i>
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `);
    
    $('body').append(notification);
    
    setTimeout(() => {
        notification.alert('close');
    }, 5000);
}


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
/* Role and Action Badges */
    .role-badge,
    .action-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .role-admin {
        background: #18375d;
        color: #ffffffff;
    }

    .role-farmer {
        background: #387057;
        color: #ffffffff;
    }

    .role-superadmin {
        background: #fca700;
        color: #ffffffff;
    }

    .role-system {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }
    /* Badge Base */
.badge {
    display: inline-block;
    padding: 0.35em 0.75em;
    font-size: 0.875rem;
    font-weight: 600;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.5rem;
    color: #fff; /* default text color */
}

/* Farmer Role */
.badge-success {
    background-color: #387057; /* green */
}

/* Admin or other Role */
.badge-info {
    background-color: #18375d;/* blue */
}

/* Optional: small icon inside badge */
.badge i {
    margin-right: 0.25rem;
}

    /* Optional: Add smooth focus effect */
#rejectModal .form-control:focus {
    border-color: #198754; /* Bootstrap green */
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
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
.btn-delete {
  background: #dc3545;
  color: #fff;
  border: none;
}

.btn-delete:hover {
  background: #fca700;
}
.btn-approve {
  background: #387057;
  color: #fff;
  border: none;
}

.btn-approve:hover {
  background: #fca700;
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
    /* Custom Badges */
    .badge {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.35em 0.65em;
        border-radius: 0.5rem;
    }

    .badge-success {
        background-color: #387057;
        color: #fff;
    }

    .badge-danger {
        background-color: #dc3545;
        color: #fff;
    }

    .badge-warning {
        background-color: #f39c12;
        color: #fff;
    }

    .badge-info {
        background-color: #18375d;
        color: #fff;
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
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    /* ========================= */
/* Approve Modal Styling     */
/* ========================= */
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


/* ========================= */
/* Reject Modal Styling      */
/* ========================= */
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
    
    .btn-action-ok{
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
    
    .btn-action-refresh-pending, .btn-action-refresh- {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-refresh-pending:hover, .btn-action-refresh-:hover {
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

/* General cell styling */
#pendingTable th,
#pendingTable td,
#approvedTable th,
#approvedTable td,
#rejectedTable th,
#rejectedTable td {
    vertical-align: middle;
    padding: 0.75rem;
    text-align: center;
    border: 1px solid #dee2e6;
    white-space: nowrap;
    overflow: visible;
}

/* Consistent header styling */
#pendingTable thead th,
#approvedTable thead th,
#rejectedTable thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: bold;
    color: #495057;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    text-align: center;
    vertical-align: middle;
    position: relative;
    white-space: nowrap;
}

/* Fix DataTables sorting button overlap */
#pendingTable thead th.sorting,
#pendingTable thead th.sorting_asc,
#pendingTable thead th.sorting_desc,
#approvedTable thead th.sorting,
#approvedTable thead th.sorting_asc,
#approvedTable thead th.sorting_desc,
#rejectedTable thead th.sorting,
#rejectedTable thead th.sorting_asc,
#rejectedTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Custom sort indicator positioning */
#pendingTable thead th::after,
#approvedTable thead th::after,
#rejectedTable thead th::after {
    content: '';
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
}

/* Remove default DataTables sort indicators */
#pendingTable thead th.sorting::after,
#pendingTable thead th.sorting_asc::after,
#pendingTable thead th.sorting_desc::after,
#approvedTable thead th.sorting::after,
#approvedTable thead th.sorting_asc::after,
#approvedTable thead th.sorting_desc::after,
#rejectedTable thead th.sorting::after,
#rejectedTable thead th.sorting_asc::after,
#rejectedTable thead th.sorting_desc::after {
    display: none;
}

/* Make table cells wrap instead of forcing them all inline */
#pendingTable td, 
#pendingTable th {
    white-space: normal !important;  /* allow wrapping */
    vertical-align: middle;
}

/* Make sure action buttons don’t overflow */
#pendingTable td .btn-group {
    display: flex;
    flex-wrap: wrap; /* buttons wrap if not enough space */
    gap: 0.25rem;    /* small gap between buttons */
}

#pendingTable td .btn-action {
    flex: 1 1 auto; /* allow buttons to shrink/grow */
    min-width: 90px; /* prevent too tiny buttons */
    text-align: center;
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
        background-color: #ffffffff;
        color: #856404;
    }
    
    .status-approved {
        background-color: #ffffffff;
        color: #155724;
    }
    
    .status-rejected {
        background-color: #ffffffff;
        color: #721c24;
    }
    
    .status-active {
        background-color: #ffffffff;
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
    
  /* DataTables Pagination Styling */
    .dataTables_wrapper .dataTables_paginate {
        text-align: left !important;
        margin-top: 1rem;
        clear: both;
        width: 100%;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        display: inline-block;
        min-width: 2.5rem;
        padding: 0.5rem 0.75rem;
        margin: 0 0.125rem;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        color: #495057;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        background-color: #fff;
        transition: all 0.15s ease-in-out;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        color: #18375d;
        background-color: #e9ecef;
        border-color: #adb5bd;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        color: #fff;
        background-color: #18375d;
        border-color: #18375d;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        color: #6c757d;
        background-color: #fff;
        border-color: #dee2e6;
        cursor: not-allowed;
        opacity: 0.5;
    }
    
    .dataTables_wrapper .dataTables_info {
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        color: #495057;
        font-size: 0.875rem;
    }
    
    /* Ensure pagination container is properly positioned */
    .dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    
    .dataTables_wrapper .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0;
    }
    
    .dataTables_wrapper .row > div {
        padding: 0;
    }

    
</style>
@endpush

