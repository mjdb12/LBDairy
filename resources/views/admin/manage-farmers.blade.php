@extends('layouts.app')

@section('title', 'LBDAIRY: Admin - Manage Farmers')

@section('content')
<div class="container-fluid">
    <br><br><br><br>
    
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-users"></i>
            Farmer Management
        </h1>
        <p>Manage farmer registrations, approvals, and account status</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container fade-in">
        <div class="stat-card">
            <div class="stat-number" style="color: var(--warning-color);" id="pendingCount">0</div>
            <div class="stat-label">Pending Approvals</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: var(--success-color);" id="activeCount">0</div>
            <div class="stat-label">Active Farmers</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: var(--info-color);" id="totalCount">0</div>
            <div class="stat-label">Total Farmers</div>
        </div>
    </div>

    <!-- Pending Farmers Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header">
            <h6>
                <i class="fas fa-clock"></i>
                Pending Farmer Registrations
            </h6>
            <div class="table-controls">
                <div class="search-container">
                    <input type="text" class="form-control custom-search" placeholder="Search pending farmers...">
                </div>
                <div class="export-controls">
                    <div class="btn-group">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportCSV('pendingFarmersTable')">
                                <i class="fas fa-file-csv"></i> CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPDF('pendingFarmersTable')">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPNG('pendingFarmersTable')">
                                <i class="fas fa-image"></i> PNG
                            </a>
                        </div>
                    </div>
                    <button class="btn btn-secondary btn-sm" onclick="printTable('pendingFarmersTable')">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="pendingFarmersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Farm Name</th>
                            <th>Barangay</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Registration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="pendingFarmersBody">
                        <!-- Pending farmers will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Active Farmers Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header">
            <h6>
                <i class="fas fa-user-check"></i>
                Active Farmers
            </h6>
            <div class="table-controls">
                <div class="search-container">
                    <input type="text" class="form-control custom-search" placeholder="Search active farmers...">
                </div>
                <div class="export-controls">
                    <div class="btn-group">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportCSV('activeFarmersTable')">
                                <i class="fas fa-file-csv"></i> CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPDF('activeFarmersTable')">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPNG('activeFarmersTable')">
                                <i class="fas fa-image"></i> PNG
                            </a>
                        </div>
                    </div>
                    <button class="btn btn-secondary btn-sm" onclick="printTable('activeFarmersTable')">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="activeFarmersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Farm Name</th>
                            <th>Barangay</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Registration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="activeFarmersBody">
                        <!-- Active farmers will be loaded here -->
                    </tbody>
                </table>
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
                    Farmer Details
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
                    <i class="fas fa-envelope"></i> Contact Farmer
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Contact Farmer Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">
                    <i class="fas fa-paper-plane"></i>
                    Send Message to Farmer
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

<!-- Rejection Reason Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1" role="dialog" aria-labelledby="rejectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectionModalLabel">
                    <i class="fas fa-times-circle"></i>
                    Reject Farmer Registration
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form onsubmit="submitRejection(event)">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="farmerIdHidden">
                    <div class="form-group">
                        <label for="rejectionReason">Reason for Rejection</label>
                        <textarea class="form-control" id="rejectionReason" rows="4" required placeholder="Please provide a reason for rejecting this farmer registration..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Reject Registration
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
let pendingFarmersTable;
let activeFarmersTable;

$(document).ready(function () {
    // Initialize DataTables
    initializeDataTables();
    
    // Load data
    loadPendingFarmers();
    loadActiveFarmers();
    updateStats();

    // Custom search functionality
    $('.custom-search').on('keyup', function() {
        const tableId = $(this).closest('.card').find('table').attr('id');
        const table = tableId === 'pendingFarmersTable' ? pendingFarmersTable : activeFarmersTable;
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

    pendingFarmersTable = $('#pendingFarmersTable').DataTable({
        ...commonConfig,
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Pending_Farmers_Report',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Pending_Farmers_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Pending Farmers Report',
                className: 'd-none'
            }
        ]
    });

    activeFarmersTable = $('#activeFarmersTable').DataTable({
        ...commonConfig,
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Active_Farmers_Report',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Active_Farmers_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Active Farmers Report',
                className: 'd-none'
            }
        ]
    });

    // Hide default DataTables elements
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
}

function loadPendingFarmers() {
    // Load pending farmers from the database via AJAX
    $.ajax({
        url: '{{ route("admin.farmers.pending") }}',
        method: 'GET',
        success: function(response) {
            console.log('Pending farmers response:', response);
            pendingFarmersTable.clear();
            
            if (response.success && response.data) {
                response.data.forEach((farmer, index) => {
                    console.log(`Farmer ${index}:`, farmer);
                    const rowData = [
                        `${farmer.first_name || ''} ${farmer.last_name || ''}`,
                        farmer.farm_name || '',
                        farmer.barangay || '',
                        farmer.phone || '',
                        farmer.email || '',
                        farmer.username || '',
                        farmer.created_at ? new Date(farmer.created_at).toLocaleDateString() : '',
                        `<div class="btn-group" role="group">
                            <button class="btn btn-success btn-sm" onclick="approveFarmer('${farmer.id}')" title="Approve">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="showRejectionModal('${farmer.id}')" title="Reject">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>`
                    ];
                    
                    console.log(`Row data for farmer ${index}:`, rowData);
                    const row = pendingFarmersTable.row.add(rowData).draw(false);
                    
                    // Add click handler for row details (excluding action buttons)
                    $(row.node()).on('click', function(e) {
                        if (!$(e.target).closest('button').length) {
                            showFarmerDetails(farmer);
                        }
                    });
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading pending farmers:', xhr);
        }
    });
}

function loadActiveFarmers() {
    // Load active farmers from the database via AJAX
    $.ajax({
        url: '{{ route("admin.farmers.active") }}',
        method: 'GET',
        success: function(response) {
            console.log('Active farmers response:', response);
            activeFarmersTable.clear();
            
            if (response.success && response.data) {
                response.data.forEach((farmer, index) => {
                    console.log(`Active farmer ${index}:`, farmer);
                    const rowData = [
                        `${farmer.first_name || ''} ${farmer.last_name || ''}`,
                        farmer.farm_name || '',
                        farmer.barangay || '',
                        farmer.phone || '',
                        farmer.email || '',
                        farmer.username || '',
                        farmer.created_at ? new Date(farmer.created_at).toLocaleDateString() : '',
                        `<button class="btn btn-danger btn-sm" onclick="deactivateFarmer('${farmer.id}')" title="Deactivate">
                            <i class="fas fa-user-slash"></i>
                        </button>`
                    ];
                    
                    console.log(`Row data for active farmer ${index}:`, rowData);
                    const row = activeFarmersTable.row.add(rowData).draw(false);
                    
                    // Add click handler for row details (excluding action buttons)
                    $(row.node()).on('click', function(e) {
                        if (!$(e.target).closest('button').length) {
                            showFarmerDetails(farmer);
                        }
                    });
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading active farmers:', xhr);
        }
    });
}

function updateStats() {
    // Update stats via AJAX
    $.ajax({
        url: '{{ route("admin.farmers.stats") }}',
        method: 'GET',
        success: function(response) {
            console.log('Stats response:', response);
            if (response.success && response.data) {
                document.getElementById('pendingCount').textContent = response.data.pending;
                document.getElementById('activeCount').textContent = response.data.active;
                document.getElementById('totalCount').textContent = response.data.total;
            }
        },
        error: function(xhr) {
            console.error('Error loading stats:', xhr);
        }
    });
}

function showFarmerDetails(farmer) {
    const details = `
        <div class="row">
            <div class="col-md-6">
                <h6 class="mb-3 text-primary">Personal Information</h6>
                <p><strong>Full Name:</strong> ${farmer.first_name || ''} ${farmer.last_name || ''}</p>
                <p><strong>Email:</strong> ${farmer.email || ''}</p>
                <p><strong>Contact Number:</strong> ${farmer.phone || ''}</p>
                <p><strong>Barangay:</strong> ${farmer.barangay || ''}</p>
            </div>
            <div class="col-md-6">
                <h6 class="mb-3 text-primary">Farm Information</h6>
                <p><strong>Farm Name:</strong> ${farmer.farm_name || ''}</p>
                <p><strong>Farm Address:</strong> ${farmer.farm_address || ''}</p>
                <p><strong>Username:</strong> ${farmer.username || ''}</p>
                <p><strong>Registration Date:</strong> ${farmer.created_at ? new Date(farmer.created_at).toLocaleDateString() : ''}</p>
                ${farmer.status ? `<p><strong>Status:</strong> <span class="status-badge status-${farmer.status}">${farmer.status}</span></p>` : ''}
            </div>
        </div>
    `;

    document.getElementById('farmerDetails').innerHTML = details;
    document.getElementById('farmerNameHidden').value = `${farmer.first_name || ''} ${farmer.last_name || ''}`;
    $('#detailsModal').modal('show');
}

function approveFarmer(farmerId) {
    $.ajax({
        url: `{{ route("admin.farmers.approve", ":id") }}`.replace(':id', farmerId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            loadPendingFarmers();
            loadActiveFarmers();
            updateStats();
            showNotification('Farmer approved successfully!', 'success');
        },
        error: function(xhr) {
            showNotification('Error approving farmer', 'danger');
        }
    });
}

function showRejectionModal(farmerId) {
    document.getElementById('farmerIdHidden').value = farmerId;
    $('#rejectionModal').modal('show');
}

function submitRejection(event) {
    event.preventDefault();
    const farmerId = document.getElementById('farmerIdHidden').value;
    const reason = document.getElementById('rejectionReason').value;

    $.ajax({
        url: `{{ route("admin.farmers.reject", ":id") }}`.replace(':id', farmerId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            rejection_reason: reason
        },
        success: function(response) {
            $('#rejectionModal').modal('hide');
            document.getElementById('rejectionReason').value = '';
            loadPendingFarmers();
            updateStats();
            showNotification('Farmer registration rejected.', 'warning');
        },
        error: function(xhr) {
            showNotification('Error rejecting farmer', 'danger');
        }
    });
}

function deactivateFarmer(farmerId) {
    if (!confirm('Are you sure you want to deactivate this farmer?')) return;

    $.ajax({
        url: `{{ route("admin.farmers.deactivate", ":id") }}`.replace(':id', farmerId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            loadActiveFarmers();
            updateStats();
            showNotification('Farmer deactivated successfully.', 'danger');
        },
        error: function(xhr) {
            showNotification('Error deactivating farmer', 'danger');
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
        url: '{{ route("admin.farmers.contact") }}',
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
    const table = tableId === 'pendingFarmersTable' ? pendingFarmersTable : activeFarmersTable;
    table.button('.buttons-csv').trigger();
}

function exportPDF(tableId) {
    const table = tableId === 'pendingFarmersTable' ? pendingFarmersTable : activeFarmersTable;
    table.button('.buttons-pdf').trigger();
}

function printTable(tableId) {
    const table = tableId === 'pendingFarmersTable' ? pendingFarmersTable : activeFarmersTable;
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
</script>
@endpush
