@extends('layouts.app')

@section('title', 'LBDAIRY: Admin - Schedule Inspections')

@push('styles')
<style>
    /* Custom styles for schedule inspections */
    
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
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-calendar-check"></i>
            Schedule Inspections
        </h1>
        <p>Manage farm inspections, schedule new inspections, and track inspection status</p>
    </div>

    <!-- Stats Cards -->
    <div class="row fade-in">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Total Inspections</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalInspections">0</div>
                    </div>
                    <div class="icon" style="color: #18375d !important;">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Scheduled</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="scheduledInspections">0</div>
                    </div>
                    <div class="icon" style="color: #18375d !important;">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Completed</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="completedInspections">0</div>
                    </div>
                    <div class="icon" style="color: #18375d !important;">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Urgent</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="urgentInspections">0</div>
                    </div>
                    <div class="icon" style="color: #18375d !important;">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Farmers Table -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-success text-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h6 class="mb-0 mb-md-0">
                    <i class="fas fa-users"></i>
                    Select Farmer for Inspection
                </h6>
                <div class="d-flex flex-column flex-sm-row gap-2 mt-2 mt-md-0">
                    <div class="input-group" style="max-width: 300px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" placeholder="Search farmers..." id="farmerSearch">
                    </div>
                    <div class="btn-group" role="group">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportFarmersCSV()">
                                <i class="fas fa-file-csv"></i> CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportFarmersPDF()">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportFarmersPNG()">
                                <i class="fas fa-image"></i> PNG
                            </a>
                        </div>
                    </div>
                    <button class="btn btn-secondary btn-sm" onclick="printFarmersTable()" title="Print">
                        <i class="fas fa-print"></i>
                    </button>
                    <button class="btn btn-info btn-sm" onclick="refreshFarmersData()" title="Refresh">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="farmersTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Farmer Name</th>
                            <th>Farm Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Barangay</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="farmersTableBody">
                        <!-- Farmers will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Inspections Table -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-info text-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h6 class="mb-0 mb-md-0">
                    <i class="fas fa-list"></i>
                    All Inspections
                </h6>
                <div class="d-flex flex-column flex-sm-row gap-2 mt-2 mt-md-0">
                    <div class="input-group" style="max-width: 300px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" placeholder="Search inspections..." id="inspectionSearch">
                    </div>
                    <div class="btn-group" role="group">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportInspectionsCSV()">
                                <i class="fas fa-file-csv"></i> CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportInspectionsPDF()">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportInspectionsPNG()">
                                <i class="fas fa-image"></i> PNG
                            </a>
                        </div>
                    </div>
                    <button class="btn btn-secondary btn-sm" onclick="printInspectionsTable()" title="Print">
                        <i class="fas fa-print"></i>
                    </button>
                    <button class="btn btn-info btn-sm" onclick="refreshInspectionsData()" title="Refresh">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="inspectionsTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Farmer Name</th>
                            <th>Farm Name</th>
                            <th>Inspection Date</th>
                            <th>Time</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Scheduled By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="inspectionsTableBody">
                        <!-- Inspections will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Schedule Inspection Modal -->
<div class="modal fade" id="scheduleInspectionModal" tabindex="-1" role="dialog" aria-labelledby="scheduleInspectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleInspectionModalLabel">
                    <i class="fas fa-calendar-check"></i>
                    Schedule Farm Inspection
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form onsubmit="submitInspectionSchedule(event)">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inspectionFarmer">Select Farmer</label>
                                <select class="form-control" id="inspectionFarmer" required>
                                    <option value="">Select Farmer</option>
                                    <!-- Farmers will be loaded here -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inspectionDate">Inspection Date</label>
                                <input type="date" class="form-control" id="inspectionDate" required min="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inspectionTime">Inspection Time</label>
                                <input type="time" class="form-control" id="inspectionTime" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inspectionPriority">Priority Level</label>
                                <select class="form-control" id="inspectionPriority" required>
                                    <option value="">Select Priority</option>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inspectionNotes">Inspection Notes</label>
                        <textarea class="form-control" id="inspectionNotes" rows="3" placeholder="Enter any specific notes or instructions for the inspection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-calendar-check"></i> Schedule Inspection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Inspection Details Modal -->
<div class="modal fade" id="inspectionDetailsModal" tabindex="-1" role="dialog" aria-labelledby="inspectionDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inspectionDetailsModalLabel">
                    <i class="fas fa-eye"></i>
                    Inspection Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="inspectionDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Farmer Details Modal -->
<div class="modal fade" id="farmerDetailsModal" tabindex="-1" role="dialog" aria-labelledby="farmerDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="farmerDetailsModalLabel">
                    <i class="fas fa-user"></i>
                    Farmer Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="farmerDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
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
let inspectionsTable;
let farmersTable;

$(document).ready(function () {
    // Initialize DataTables
    initializeDataTables();
    
    // Load data
    loadInspections();
    loadFarmersTable();
    updateStats();

    // Custom search functionality
    $('#inspectionSearch').on('keyup', function() {
        inspectionsTable.search(this.value).draw();
    });

    $('#farmerSearch').on('keyup', function() {
        farmersTable.search(this.value).draw();
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
        processing: true,
        serverSide: false,
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
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No data available</h5><p>There are no records to display at this time.</p></div>',
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        },
        columnDefs: [
            {
                targets: -1, // Last column (Actions)
                orderable: false,
                searchable: false
            }
        ]
    };

    inspectionsTable = $('#inspectionsTable').DataTable({
        ...commonConfig,
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Inspections_Report',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Inspections_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Inspections Report',
                className: 'd-none'
            }
        ]
    });

    farmersTable = $('#farmersTable').DataTable({
        ...commonConfig,
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Farmers_List',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Farmers_List',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Farmers List',
                className: 'd-none'
            }
        ]
    });

    // Hide default DataTables elements
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
}

function loadInspections() {
    // Show loading state
    const tableBody = $('#inspectionsTableBody');
    tableBody.html('<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading inspections...</td></tr>');
    
    // Load inspections from the database via AJAX
    $.ajax({
        url: '{{ route("admin.inspections.list") }}',
        method: 'GET',
        success: function(response) {
            inspectionsTable.clear();
            
            if (response.success && response.data && response.data.length > 0) {
                response.data.forEach((inspection) => {
                    const rowData = [
                        `${inspection.farmer?.first_name || ''} ${inspection.farmer?.last_name || ''}`,
                        inspection.farmer?.farm_name || 'N/A',
                        inspection.inspection_date ? new Date(inspection.inspection_date).toLocaleDateString() : 'N/A',
                        inspection.inspection_time ? new Date(`2000-01-01T${inspection.inspection_time}`).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : 'N/A',
                        `<span class="badge badge-${inspection.priority === 'urgent' ? 'danger' : (inspection.priority === 'high' ? 'warning' : (inspection.priority === 'medium' ? 'info' : 'success'))}">${inspection.priority}</span>`,
                        `<span class="badge badge-${inspection.status === 'scheduled' ? 'primary' : (inspection.status === 'completed' ? 'success' : (inspection.status === 'cancelled' ? 'danger' : 'warning'))}">${inspection.status}</span>`,
                        inspection.scheduled_by?.name || 'Admin',
                        `<div class="action-buttons">
                            <button class="btn-action btn-action-view" onclick="viewInspectionDetails('${inspection.id}')" title="View Details">
                                <i class="fas fa-eye"></i>
                                <span>View</span>
                            </button>
                            <button class="btn-action btn-action-edit" onclick="editInspection('${inspection.id}')" title="Edit">
                                <i class="fas fa-edit"></i>
                                <span>Edit</span>
                            </button>
                            <button class="btn-action btn-action-reject" onclick="cancelInspection('${inspection.id}')" title="Cancel">
                                <i class="fas fa-times"></i>
                                <span>Cancel</span>
                            </button>
                        </div>`
                    ];
                    
                    inspectionsTable.row.add(rowData);
                });
                inspectionsTable.draw();
            } else {
                // Handle empty data properly
                inspectionsTable.clear().draw();
                $('#inspectionsTableBody').html('<tr><td colspan="8" class="text-center">No inspections found</td></tr>');
            }
        },
        error: function(xhr) {
            console.error('Error loading inspections:', xhr);
            inspectionsTable.clear().draw();
            $('#inspectionsTableBody').html('<tr><td colspan="8" class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Error loading inspections</td></tr>');
        }
    });
}

function loadFarmersTable() {
    // Show loading state
    const tableBody = $('#farmersTableBody');
    tableBody.html('<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading farmers...</td></tr>');
    
    // Load farmers from the database via AJAX
    $.ajax({
        url: '{{ route("admin.farmers.active") }}',
        method: 'GET',
        success: function(response) {
            farmersTable.clear();
            
            if (response.success && response.data && response.data.length > 0) {
                response.data.forEach((farmer) => {
                    const rowData = [
                        `${farmer.first_name || ''} ${farmer.last_name || ''}`,
                        farmer.farm_name || 'N/A',
                        farmer.email || 'N/A',
                        farmer.phone || 'N/A',
                        farmer.barangay || 'N/A',
                        `<span class="badge badge-success">Active</span>`,
                        `<div class="action-buttons">
                            <button class="btn-action btn-action-approve" onclick="scheduleInspectionForFarmer('${farmer.id}', '${farmer.first_name || ''} ${farmer.last_name || ''}', '${farmer.farm_name || 'N/A'}')" title="Schedule Inspection">
                                <i class="fas fa-calendar-check"></i>
                                <span>Schedule</span>
                            </button>
                            <button class="btn-action btn-action-view" onclick="viewFarmerDetails('${farmer.id}')" title="View Details">
                                <i class="fas fa-eye"></i>
                                <span>View</span>
                            </button>
                        </div>`
                    ];
                    
                    farmersTable.row.add(rowData);
                });
                farmersTable.draw();
            } else {
                // Handle empty data properly
                farmersTable.clear().draw();
                $('#farmersTableBody').html('<tr><td colspan="7" class="text-center">No active farmers found</td></tr>');
            }
        },
        error: function(xhr) {
            console.error('Error loading farmers:', xhr);
            farmersTable.clear().draw();
            $('#farmersTableBody').html('<tr><td colspan="7" class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Error loading farmers</td></tr>');
        }
    });
}

function loadFarmers() {
    $.ajax({
        url: '{{ route("admin.farmers.active") }}',
        method: 'GET',
        success: function(response) {
            const farmerSelect = $('#inspectionFarmer');
            farmerSelect.empty().append('<option value="">Select Farmer</option>');
            
            if (response.success && response.data) {
                response.data.forEach(farmer => {
                    const farmerName = `${farmer.first_name || ''} ${farmer.last_name || ''}`.trim();
                    farmerSelect.append(`<option value="${farmer.id}">${farmerName} - ${farmer.farm_name || 'N/A'}</option>`);
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading farmers:', xhr);
        }
    });
}

function updateStats() {
    // Update stats via AJAX
    $.ajax({
        url: '{{ route("admin.inspections.stats") }}',
        method: 'GET',
        success: function(response) {
            if (response.success && response.data) {
                document.getElementById('totalInspections').textContent = response.data.total || 0;
                document.getElementById('scheduledInspections').textContent = response.data.scheduled || 0;
                document.getElementById('completedInspections').textContent = response.data.completed || 0;
                document.getElementById('urgentInspections').textContent = response.data.urgent || 0;
            }
        },
        error: function(xhr) {
            console.error('Error loading stats:', xhr);
        }
    });
}

function scheduleInspectionForFarmer(farmerId, farmerName, farmName) {
    // Pre-populate the modal with farmer information
    document.getElementById('inspectionFarmer').value = farmerId;
    
    // Show the modal
    $('#scheduleInspectionModal').modal('show');
    
    // Show a notification that farmer is selected
    showNotification(`Selected farmer: ${farmerName} (${farmName})`, 'info');
}

function openScheduleModal() {
    $('#scheduleInspectionModal').modal('show');
}

function viewFarmerDetails(farmerId) {
    $.ajax({
        url: `{{ route("admin.farmers.show", ":id") }}`.replace(':id', farmerId),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const farmer = response.data;
                $('#farmerDetailsContent').html(`
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Personal Information</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Name:</strong></td><td>${farmer.first_name || ''} ${farmer.last_name || ''}</td></tr>
                                <tr><td><strong>Email:</strong></td><td>${farmer.email || 'N/A'}</td></tr>
                                <tr><td><strong>Phone:</strong></td><td>${farmer.phone || 'N/A'}</td></tr>
                                <tr><td><strong>Username:</strong></td><td>${farmer.username || 'N/A'}</td></tr>
                                <tr><td><strong>Registration Date:</strong></td><td>${farmer.created_at || 'N/A'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Farm Information</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Farm Name:</strong></td><td>${farmer.farm_name || 'N/A'}</td></tr>
                                <tr><td><strong>Barangay:</strong></td><td>${farmer.barangay || 'N/A'}</td></tr>
                                <tr><td><strong>Status:</strong></td><td><span class="badge badge-success">Active</span></td></tr>
                            </table>
                        </div>
                    </div>
                `);
                $('#farmerDetailsModal').modal('show');
            } else {
                showNotification('Error loading farmer details', 'danger');
            }
        },
        error: function() {
            showNotification('Error loading farmer details', 'danger');
        }
    });
}

function submitInspectionSchedule(event) {
    event.preventDefault();
    
    const farmerId = document.getElementById('inspectionFarmer').value;
    const inspectionDate = document.getElementById('inspectionDate').value;
    const inspectionTime = document.getElementById('inspectionTime').value;
    const priority = document.getElementById('inspectionPriority').value;
    const notes = document.getElementById('inspectionNotes').value;

    $.ajax({
        url: '{{ route("admin.inspections.schedule") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            farmer_id: farmerId,
            inspection_date: inspectionDate,
            inspection_time: inspectionTime,
            priority: priority,
            notes: notes
        },
        success: function(response) {
            if (response.success) {
                $('#scheduleInspectionModal').modal('hide');
                // Reset form
                document.getElementById('inspectionFarmer').value = '';
                document.getElementById('inspectionDate').value = '';
                document.getElementById('inspectionTime').value = '';
                document.getElementById('inspectionPriority').value = '';
                document.getElementById('inspectionNotes').value = '';
                
                loadInspections();
                updateStats();
                showNotification('Inspection scheduled successfully!', 'success');
            } else {
                showNotification(response.message || 'Error scheduling inspection', 'danger');
            }
        },
        error: function(xhr) {
            showNotification('Error scheduling inspection', 'danger');
        }
    });
}

function viewInspectionDetails(inspectionId) {
    $.ajax({
        url: `{{ route("admin.inspections.show", ":id") }}`.replace(':id', inspectionId),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const inspection = response.data;
                $('#inspectionDetailsContent').html(`
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Inspection Information</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Date:</strong></td><td>${inspection.inspection_date}</td></tr>
                                <tr><td><strong>Time:</strong></td><td>${inspection.inspection_time}</td></tr>
                                <tr><td><strong>Status:</strong></td><td><span class="badge badge-${inspection.status === 'scheduled' ? 'primary' : (inspection.status === 'completed' ? 'success' : (inspection.status === 'cancelled' ? 'danger' : 'warning'))}">${inspection.status}</span></td></tr>
                                <tr><td><strong>Priority:</strong></td><td><span class="badge badge-${inspection.priority === 'urgent' ? 'danger' : (inspection.priority === 'high' ? 'warning' : (inspection.priority === 'medium' ? 'info' : 'success'))}">${inspection.priority}</span></td></tr>
                                <tr><td><strong>Scheduled By:</strong></td><td>${inspection.scheduled_by?.name || 'Admin'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Farmer Information</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Name:</strong></td><td>${inspection.farmer?.first_name || ''} ${inspection.farmer?.last_name || ''}</td></tr>
                                <tr><td><strong>Email:</strong></td><td>${inspection.farmer?.email || 'N/A'}</td></tr>
                                <tr><td><strong>Contact:</strong></td><td>${inspection.farmer?.phone || 'N/A'}</td></tr>
                                <tr><td><strong>Farm Name:</strong></td><td>${inspection.farmer?.farm_name || 'N/A'}</td></tr>
                                <tr><td><strong>Barangay:</strong></td><td>${inspection.farmer?.barangay || 'N/A'}</td></tr>
                            </table>
                        </div>
                    </div>
                    ${inspection.notes ? `
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-primary">Notes</h6>
                            <p>${inspection.notes}</p>
                        </div>
                    </div>
                    ` : ''}
                    ${inspection.findings ? `
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-primary">Findings</h6>
                            <p>${inspection.findings}</p>
                        </div>
                    </div>
                    ` : ''}
                `);
                $('#inspectionDetailsModal').modal('show');
            } else {
                showNotification('Error loading inspection details', 'danger');
            }
        },
        error: function() {
            showNotification('Error loading inspection details', 'danger');
        }
    });
}

function editInspection(inspectionId) {
    // Implementation for editing inspection
    showNotification('Edit functionality coming soon!', 'info');
}

function cancelInspection(inspectionId) {
    if (confirm('Are you sure you want to cancel this inspection?')) {
        $.ajax({
            url: `{{ route("admin.inspections.cancel", ":id") }}`.replace(':id', inspectionId),
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    loadInspections();
                    updateStats();
                    showNotification('Inspection cancelled successfully!', 'warning');
                } else {
                    showNotification(response.message || 'Error cancelling inspection', 'danger');
                }
            },
            error: function() {
                showNotification('Error cancelling inspection', 'danger');
            }
        });
    }
}

function refreshInspectionsData() {
    const refreshBtn = $('button[onclick="refreshInspectionsData()"]');
    const originalIcon = refreshBtn.html();
    refreshBtn.html('<i class="fas fa-spinner fa-spin"></i>');
    refreshBtn.prop('disabled', true);
    
    loadInspections();
    updateStats();
    
    setTimeout(() => {
        refreshBtn.html(originalIcon);
        refreshBtn.prop('disabled', false);
        showNotification('Inspections data refreshed', 'success');
    }, 1000);
}

function refreshFarmersData() {
    const refreshBtn = $('button[onclick="refreshFarmersData()"]');
    const originalIcon = refreshBtn.html();
    refreshBtn.html('<i class="fas fa-spinner fa-spin"></i>');
    refreshBtn.prop('disabled', true);
    
    loadFarmersTable();
    
    setTimeout(() => {
        refreshBtn.html(originalIcon);
        refreshBtn.prop('disabled', false);
        showNotification('Farmers data refreshed', 'success');
    }, 1000);
}

function exportInspectionsCSV() {
    inspectionsTable.button('.buttons-csv').trigger();
}

function exportInspectionsPDF() {
    inspectionsTable.button('.buttons-pdf').trigger();
}

function exportInspectionsPNG() {
    const tableElement = document.getElementById('inspectionsTable');
    html2canvas(tableElement).then(canvas => {
        let link = document.createElement('a');
        link.download = 'Inspections_Report.png';
        link.href = canvas.toDataURL("image/png");
        link.click();
    });
}

function printInspectionsTable() {
    inspectionsTable.button('.buttons-print').trigger();
}

function exportFarmersCSV() {
    farmersTable.button('.buttons-csv').trigger();
}

function exportFarmersPDF() {
    farmersTable.button('.buttons-pdf').trigger();
}

function exportFarmersPNG() {
    const tableElement = document.getElementById('farmersTable');
    html2canvas(tableElement).then(canvas => {
        let link = document.createElement('a');
        link.download = 'Farmers_List.png';
        link.href = canvas.toDataURL("image/png");
        link.click();
    });
}

function printFarmersTable() {
    farmersTable.button('.buttons-print').trigger();
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
</script>
@endpush
