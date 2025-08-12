@extends('layouts.app')

@section('title', 'LBDAIRY: SuperAdmin - Manage Admins')

@section('content')
<div class="container-fluid">
    <br><br><br><br>
    
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-users-cog"></i>
            Admin Management
        </h1>
        <p>Manage admin registrations, approvals, and account status</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container fade-in">
        <div class="stat-card">
            <div class="stat-number" style="color: var(--warning-color);" id="pendingCount">0</div>
            <div class="stat-label">Pending Approvals</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: var(--success-color);" id="activeCount">0</div>
            <div class="stat-label">Active Admins</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: var(--info-color);" id="totalCount">0</div>
            <div class="stat-label">Total Admins</div>
        </div>
    </div>

    <!-- Pending Admins Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header">
            <h6>
                <i class="fas fa-clock"></i>
                Pending Admin Registrations
            </h6>
            <div class="table-controls">
                <div class="search-container">
                    <input type="text" class="form-control custom-search" placeholder="Search pending admins...">
                </div>
                <div class="export-controls">
                    <div class="btn-group">
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
                    <button class="btn btn-secondary btn-sm" onclick="printTable('pendingAdminsTable')">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="pendingAdminsTable" width="100%" cellspacing="0">
                    <thead>
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
        <div class="card-header">
            <h6>
                <i class="fas fa-user-check"></i>
                Active Admins
            </h6>
            <div class="table-controls">
                <div class="search-container">
                    <input type="text" class="form-control custom-search" placeholder="Search active admins...">
                </div>
                <div class="export-controls">
                    <div class="btn-group">
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
                    <button class="btn btn-secondary btn-sm" onclick="printTable('activeAdminsTable')">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="activeAdminsTable" width="100%" cellspacing="0">
                    <thead>
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
    $('.custom-search').on('keyup', function() {
        const tableId = $(this).closest('.card').find('table').attr('id');
        const table = tableId === 'pendingAdminsTable' ? pendingAdminsTable : activeAdminsTable;
        table.search(this.value).draw();
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
            pendingAdminsTable.clear();
            
            response.data.forEach(admin => {
                const rowData = [
                    `${admin.first_name} ${admin.last_name}`,
                    admin.barangay,
                    admin.contact_number,
                    admin.email,
                    admin.username,
                    new Date(admin.created_at).toLocaleDateString(),
                    `<div class="btn-group" role="group">
                        <button class="btn btn-success btn-sm" onclick="approveAdmin('${admin.id}')" title="Approve">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="rejectAdmin('${admin.id}')" title="Reject">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>`
                ];
                
                const row = pendingAdminsTable.row.add(rowData).draw(false);
                
                // Add click handler for row details (excluding action buttons)
                $(row.node()).on('click', function(e) {
                    if (!$(e.target).closest('button').length) {
                        showAdminDetails(admin);
                    }
                });
            });
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
            activeAdminsTable.clear();
            
            response.data.forEach(admin => {
                const rowData = [
                    `${admin.first_name} ${admin.last_name}`,
                    admin.barangay,
                    admin.contact_number,
                    admin.email,
                    admin.username,
                    new Date(admin.created_at).toLocaleDateString(),
                    `<button class="btn btn-danger btn-sm" onclick="deactivateAdmin('${admin.id}')" title="Deactivate">
                        <i class="fas fa-user-slash"></i>
                    </button>`
                ];
                
                const row = activeAdminsTable.row.add(rowData).draw(false);
                
                // Add click handler for row details (excluding action buttons)
                $(row.node()).on('click', function(e) {
                    if (!$(e.target).closest('button').length) {
                        showAdminDetails(admin);
                    }
                });
            });
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
            document.getElementById('pendingCount').textContent = response.pending;
            document.getElementById('activeCount').textContent = response.active;
            document.getElementById('totalCount').textContent = response.total;
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
                <p><strong>Full Name:</strong> ${admin.first_name} ${admin.last_name}</p>
                <p><strong>Email:</strong> ${admin.email}</p>
                <p><strong>Contact Number:</strong> ${admin.contact_number}</p>
            </div>
            <div class="col-md-6">
                <h6 class="mb-3 text-primary">Account Information</h6>
                <p><strong>Username:</strong> ${admin.username}</p>
                <p><strong>Barangay:</strong> ${admin.barangay}</p>
                <p><strong>Registration Date:</strong> ${new Date(admin.created_at).toLocaleDateString()}</p>
                ${admin.status ? `<p><strong>Status:</strong> <span class="status-badge status-${admin.status}">${admin.status}</span></p>` : ''}
            </div>
        </div>
    `;

    document.getElementById('farmerDetails').innerHTML = details;
    document.getElementById('farmerNameHidden').value = `${admin.first_name} ${admin.last_name}`;
    $('#detailsModal').modal('show');
}

function approveAdmin(adminId) {
    $.ajax({
        url: `{{ route("superadmin.admins.approve", "") }}/${adminId}`,
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
        url: `{{ route("superadmin.admins.reject", "") }}/${adminId}`,
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
        url: `{{ route("superadmin.admins.deactivate", "") }}/${adminId}`,
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
