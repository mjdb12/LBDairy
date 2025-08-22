@extends('layouts.app')

@section('title', 'LBDAIRY: SuperAdmin - Manage Admins')

@push('styles')
<style>
    /* Custom styles for admin management */
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
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-users-cog"></i>
            Admin Management
        </h1>
        <p>Manage admin registrations, approvals, and account status</p>
    </div>

    <!-- Stats Cards -->
    <div class="row fade-in">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Approvals</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingCount">0</div>
                    </div>
                    <div class="icon text-warning">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Admins</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeCount">0</div>
                    </div>
                    <div class="icon text-success">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rejected</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="rejectedCount">0</div>
                    </div>
                    <div class="icon text-danger">
                        <i class="fas fa-user-times fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Admins</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalCount">0</div>
                    </div>
                    <div class="icon text-info">
                        <i class="fas fa-users-cog fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Admins Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-warning text-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h6 class="mb-0 mb-md-0">
                    <i class="fas fa-clock"></i>
                    Pending Admin Registrations
                </h6>
                <div class="d-flex flex-column flex-sm-row gap-2 mt-2 mt-md-0">
                    <div class="input-group" style="max-width: 300px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" placeholder="Search pending admins..." id="pendingSearch">
                    </div>
                    <div class="btn-group" role="group">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportCSV('pendingAdminsTable')">
                                <i class="fas fa-file-csv"></i> CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPDF('pendingAdminsTable')">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPNG('pendingAdminsTable')">
                                <i class="fas fa-image"></i> PNG
                            </a>
                        </div>
                    </div>
                    <button class="btn btn-secondary btn-sm" onclick="printTable('pendingAdminsTable')" title="Print">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="pendingAdminsTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Barangay</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Registration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="pendingAdminsBody">
                        <!-- Pending admins will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Active Admins Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-success text-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h6 class="mb-0 mb-md-0">
                    <i class="fas fa-user-check"></i>
                    Active Admins
                </h6>
                <div class="d-flex flex-column flex-sm-row gap-2 mt-2 mt-md-0">
                    <div class="input-group" style="max-width: 300px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" placeholder="Search active admins..." id="activeSearch">
                    </div>
                    <div class="btn-group" role="group">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportCSV('activeAdminsTable')">
                                <i class="fas fa-file-csv"></i> CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPDF('activeAdminsTable')">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPNG('activeAdminsTable')">
                                <i class="fas fa-image"></i> PNG
                            </a>
                        </div>
                    </div>
                    <button class="btn btn-secondary btn-sm" onclick="printTable('activeAdminsTable')" title="Print">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="activeAdminsTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Barangay</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Approval Date</th>
                            <th>Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="activeAdminsBody">
                        <!-- Active admins will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteLabel">
                    <i class="fas fa-exclamation-triangle"></i>
                    Confirm Action
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to perform this action? This cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">
                    <i class="fas fa-user"></i>
                    Admin Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="farmerDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="openContactModal()">
                    <i class="fas fa-envelope"></i> Contact Admin
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Contact Admin Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">
                    <i class="fas fa-paper-plane"></i>
                    Send Message to Admin
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form onsubmit="sendMessage(event)">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="farmerNameHidden">
                    <div class="form-group">
                        <label for="messageSubject">Subject</label>
                        <input type="text" class="form-control" id="messageSubject" required>
                    </div>
                    <div class="form-group">
                        <label for="messageBody">Message</label>
                        <textarea class="form-control" id="messageBody" rows="4" required></textarea>
                    </div>
                    <div id="messageNotification" class="mt-2" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables Core -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<!-- Required libraries for PDF/Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
let pendingAdminsTable;
let activeAdminsTable;
let farmerToDelete = null;

$(document).ready(function () {
    // Initialize DataTables
    initializeDataTables();
    
    // Load data
    loadPendingAdmins();
    loadActiveAdmins();
    updateStats();

    // Custom search functionality
    $('#pendingSearch').on('keyup', function() {
        pendingAdminsTable.search(this.value).draw();
    });
    $('#activeSearch').on('keyup', function() {
        activeAdminsTable.search(this.value).draw();
    });
});

function initializeDataTables() {
    const commonConfig = {
        dom: 'Bfrtip',
        searching: true,
        paging: true,
        info: true,
        ordering: true,
        lengthChange: false,
        pageLength: 10,
        buttons: [
            {
                extend: 'csvHtml5',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                className: 'd-none'
            }
        ],
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No data available</h5><p>There are no records to display at this time.</p></div>'
        }
    };

    pendingAdminsTable = $('#pendingAdminsTable').DataTable({
        ...commonConfig,
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Pending_Admins_Report',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Pending_Admins_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Pending Admins Report',
                className: 'd-none'
            }
        ]
    });

    activeAdminsTable = $('#activeAdminsTable').DataTable({
        ...commonConfig,
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Active_Admins_Report',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Active_Admins_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Active Admins Report',
                className: 'd-none'
            }
        ]
    });

    // Hide default DataTables elements
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
}

function loadPendingAdmins() {
    // Load pending admins from the database via AJAX
    $.ajax({
        url: '{{ route("superadmin.admins.pending") }}',
        method: 'GET',
        success: function(response) {
            console.log('Pending admins response:', response);
            pendingAdminsTable.clear();
            
            if (response.success && response.data) {
                response.data.forEach((admin, index) => {
                    console.log(`Admin ${index}:`, admin);
                    const rowData = [
                        `${admin.first_name || ''} ${admin.last_name || ''}`,
                        admin.barangay || '',
                        admin.phone || '',
                        admin.email || '',
                        admin.username || '',
                        admin.created_at ? new Date(admin.created_at).toLocaleDateString() : '',
                        `<div class="btn-group" role="group">
                            <button class="btn btn-success btn-sm" onclick="approveAdmin('${admin.id}')" title="Approve">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="rejectAdmin('${admin.id}')" title="Reject">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>`
                    ];
                    
                    console.log(`Row data for admin ${index}:`, rowData);
                    const row = pendingAdminsTable.row.add(rowData).draw(false);
                    
                    // Add click handler for row details (excluding action buttons)
                    $(row.node()).on('click', function(e) {
                        if (!$(e.target).closest('button').length) {
                            showAdminDetails(admin);
                        }
                    });
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading pending admins:', xhr);
        }
    });
}

function loadActiveAdmins() {
    // Load active admins from the database via AJAX
    $.ajax({
        url: '{{ route("superadmin.admins.active") }}',
        method: 'GET',
        success: function(response) {
            console.log('Active admins response:', response);
            activeAdminsTable.clear();
            
            if (response.success && response.data) {
                response.data.forEach((admin, index) => {
                    console.log(`Active admin ${index}:`, admin);
                    const rowData = [
                        `${admin.first_name || ''} ${admin.last_name || ''}`,
                        admin.barangay || '',
                        admin.phone || '',
                        admin.email || '',
                        admin.username || '',
                        admin.created_at ? new Date(admin.created_at).toLocaleDateString() : '',
                        admin.last_login_at ? new Date(admin.last_login_at).toLocaleDateString() : 'Never',
                        `<div class="btn-group" role="group">
                            <button class="btn btn-danger btn-sm" onclick="deactivateAdmin('${admin.id}')" title="Deactivate">
                                <i class="fas fa-user-slash"></i>
                            </button>
                            <button class="btn btn-info btn-sm" onclick="contactAdmin('${admin.id}')" title="Contact">
                                <i class="fas fa-envelope"></i>
                            </button>
                        </div>`
                    ];
                    
                    console.log(`Row data for active admin ${index}:`, rowData);
                    const row = activeAdminsTable.row.add(rowData).draw(false);
                    
                    // Add click handler for row details (excluding action buttons)
                    $(row.node()).on('click', function(e) {
                        if (!$(e.target).closest('button').length) {
                            showAdminDetails(admin);
                        }
                    });
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading active admins:', xhr);
        }
    });
}

function updateStats() {
    // Update stats via AJAX
    $.ajax({
        url: '{{ route("superadmin.admins.stats") }}',
        method: 'GET',
        success: function(response) {
            console.log('Stats response:', response);
            if (response.success && response.data) {
                document.getElementById('pendingCount').textContent = response.data.pending;
                document.getElementById('activeCount').textContent = response.data.active;
                if (document.getElementById('rejectedCount')) {
                    document.getElementById('rejectedCount').textContent = response.data.rejected || 0;
                }
                document.getElementById('totalCount').textContent = response.data.total;
            }
        },
        error: function(xhr) {
            console.error('Error loading stats:', xhr);
        }
    });
}

function showAdminDetails(admin) {
    const details = `
        <div class="row">
            <div class="col-md-6">
                <h6 class="mb-3 text-primary">Personal Information</h6>
                <p><strong>Full Name:</strong> ${admin.first_name || ''} ${admin.last_name || ''}</p>
                <p><strong>Email:</strong> ${admin.email || ''}</p>
                <p><strong>Contact Number:</strong> ${admin.phone || ''}</p>
            </div>
            <div class="col-md-6">
                <h6 class="mb-3 text-primary">Account Information</h6>
                <p><strong>Username:</strong> ${admin.username || ''}</p>
                <p><strong>Barangay:</strong> ${admin.barangay || ''}</p>
                <p><strong>Registration Date:</strong> ${admin.created_at ? new Date(admin.created_at).toLocaleDateString() : ''}</p>
                ${admin.status ? `<p><strong>Status:</strong> <span class="status-badge status-${admin.status}">${admin.status}</span></p>` : ''}
            </div>
        </div>
    `;

    document.getElementById('farmerDetails').innerHTML = details;
    document.getElementById('farmerNameHidden').value = `${admin.first_name || ''} ${admin.last_name || ''}`;
    $('#detailsModal').modal('show');
}

function approveAdmin(adminId) {
    $.ajax({
        url: `{{ route("superadmin.admins.approve", ":id") }}`.replace(':id', adminId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            loadPendingAdmins();
            loadActiveAdmins();
            updateStats();
            showNotification('Admin approved successfully!', 'success');
        },
        error: function(xhr) {
            showNotification('Error approving admin', 'danger');
        }
    });
}

function rejectAdmin(adminId) {
    if (!confirm('Are you sure you want to reject this admin registration?')) return;

    $.ajax({
        url: `{{ route("superadmin.admins.reject", ":id") }}`.replace(':id', adminId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            loadPendingAdmins();
            updateStats();
            showNotification('Admin registration rejected.', 'warning');
        },
        error: function(xhr) {
            showNotification('Error rejecting admin', 'danger');
        }
    });
}

function deactivateAdmin(adminId) {
    if (!confirm('Are you sure you want to deactivate this admin?')) return;

    $.ajax({
        url: `{{ route("superadmin.admins.deactivate", ":id") }}`.replace(':id', adminId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            loadActiveAdmins();
            updateStats();
            showNotification('Admin deactivated successfully.', 'danger');
        },
        error: function(xhr) {
            showNotification('Error deactivating admin', 'danger');
        }
    });
}

function openContactModal() {
    $('#detailsModal').modal('hide');
    $('#contactModal').modal('show');
}

function sendMessage(event) {
    event.preventDefault();
    const name = document.getElementById('farmerNameHidden').value;
    const subject = document.getElementById('messageSubject').value;
    const message = document.getElementById('messageBody').value;

    // Send message via AJAX
    $.ajax({
        url: '{{ route("superadmin.admins.contact") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            name: name,
            subject: subject,
            message: message
        },
        success: function(response) {
            document.getElementById('messageNotification').innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    Message sent to <strong>${name}</strong> successfully!
                </div>
            `;
            document.getElementById('messageNotification').style.display = 'block';

            document.getElementById('messageSubject').value = '';
            document.getElementById('messageBody').value = '';
        },
        error: function(xhr) {
            document.getElementById('messageNotification').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    Error sending message. Please try again.
                </div>
            `;
            document.getElementById('messageNotification').style.display = 'block';
        }
    });
}

function showNotification(message, type) {
    const notification = $(`
        <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
             style="top: 100px; right: 20px; z-index: 9999; min-width: 300px;">
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

// Export functions
function exportCSV(tableId) {
    const table = tableId === 'pendingAdminsTable' ? pendingAdminsTable : activeAdminsTable;
    table.button('.buttons-csv').trigger();
}

function exportPDF(tableId) {
    const table = tableId === 'pendingAdminsTable' ? pendingAdminsTable : activeAdminsTable;
    table.button('.buttons-pdf').trigger();
}

function printTable(tableId) {
    const table = tableId === 'pendingAdminsTable' ? pendingAdminsTable : activeAdminsTable;
    table.button('.buttons-print').trigger();
}

function exportPNG(tableId) {
    const tableElement = document.getElementById(tableId);
    html2canvas(tableElement).then(canvas => {
        let link = document.createElement('a');
        link.download = `${tableId}_Report.png`;
        link.href = canvas.toDataURL("image/png");
        link.click();
    });
}

// Confirmation modal handlers
function confirmDelete(button) {
    farmerToDelete = button;
    $('#confirmDeleteModal').modal('show');
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (farmerToDelete) {
        deleteFarmer(farmerToDelete);
        farmerToDelete = null;
        $('#confirmDeleteModal').modal('hide');
    }
});

function deleteFarmer(button) {
    let row = button.closest('tr');
    row.remove();
}
</script>
@endpush
