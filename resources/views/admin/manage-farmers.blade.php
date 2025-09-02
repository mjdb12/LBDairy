@extends('layouts.app')

@section('title', 'LBDAIRY: Admin - Manage Farmers')

@push('styles')
<style>
    /* Custom styles for farmer management */
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
    
    /* DataTables Pagination Styling - FIXED */
    .dataTables_wrapper .dataTables_paginate {
        text-align: left !important;
        margin-top: 1rem;
        clear: both;
        width: 100%;
        float: left !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        display: inline-block;
        min-width: 2.5rem;
        padding: 0.5rem 0.75rem;
        margin: 0 0.125rem;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        color: #1cc88a !important; /* Admin theme color for numbers */
        border: 1px solid #1cc88a !important;
        border-radius: 0.25rem;
        background-color: #fff;
        transition: all 0.15s ease-in-out;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        color: #fff !important;
        background-color: #1cc88a !important;
        border-color: #1cc88a !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        color: #fff !important;
        background-color: #1cc88a !important;
        border-color: #1cc88a !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        color: #6c757d !important;
        background-color: #fff !important;
        border-color: #dee2e6 !important;
        cursor: not-allowed;
        opacity: 0.5;
    }
    
    .dataTables_wrapper .dataTables_info {
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        color: #495057;
        font-size: 0.875rem;
        text-align: left !important;
        float: left !important;
        clear: both;
    }
    
    .dataTables_wrapper .dataTables_length {
        margin-bottom: 1rem;
    }
    
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
    }
    
    /* Force DataTables wrapper to have proper layout */
    .dataTables_wrapper .row {
        display: block !important;
        width: 100% !important;
        margin: 0 !important;
    }
    
    .dataTables_wrapper .row > div {
        padding: 0 !important;
        width: 100% !important;
        float: left !important;
        clear: both !important;
    }
    
    /* Ensure pagination container stays left */
    .dataTables_wrapper .dataTables_paginate,
    .dataTables_wrapper .dataTables_info {
        text-align: left !important;
        float: left !important;
        clear: both !important;
        display: block !important;
        width: auto !important;
        margin-right: 1rem !important;
    }
    
    /* Override any Bootstrap or other framework styles that might interfere */
    .dataTables_wrapper .col-sm-12.col-md-7,
    .dataTables_wrapper .col-sm-12.col-md-5 {
        width: 100% !important;
        padding: 0 !important;
    }
    
    /* Additional override to ensure left positioning */
    .dataTables_wrapper .dataTables_paginate.paging_simple_numbers {
        text-align: left !important;
        float: left !important;
    }
    
    .dataTables_wrapper .dataTables_paginate.paging_simple_numbers .paginate_button {
        color: #1cc88a !important;
        border-color: #1cc88a !important;
    }
    
    /* User ID link styling */
    .user-id-link {
        color: #1cc88a;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
        transition: color 0.2s ease;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        background-color: rgba(28, 200, 138, 0.1);
        border: 1px solid rgba(28, 200, 138, 0.2);
    }
    
    .user-id-link:hover {
        color: #fff;
        background-color: #1cc88a;
        border-color: #1cc88a;
        text-decoration: none;
    }
    
    .user-id-link:active {
        color: #fff;
        background-color: #17a673;
        border-color: #17a673;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-users"></i>
            Farmer Management
        </h1>
        <p>Manage farmer registrations, approvals, and account status</p>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Farmers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeCount">0</div>
                    </div>
                    <div class="icon text-success">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Farmers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalCount">0</div>
                    </div>
                    <div class="icon text-info">
                        <i class="fas fa-users fa-2x"></i>
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
    </div>

    <!-- Pending Farmers Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-primary text-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h6 class="mb-0 mb-md-0">
                    <i class="fas fa-clock"></i>
                    Pending Farmer Registrations
                </h6>
                <div class="d-flex flex-column flex-sm-row gap-2 mt-2 mt-md-0">
                    <div class="input-group" style="max-width: 300px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" placeholder="Search pending farmers..." id="pendingSearch">
                    </div>
                    <div class="btn-group" role="group">
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
                    <button class="btn btn-secondary btn-sm" onclick="printTable('pendingFarmersTable')" title="Print">
                        <i class="fas fa-print"></i>
                    </button>
                    <button class="btn btn-info btn-sm" onclick="refreshPendingData()" title="Refresh">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="pendingFarmersTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>User ID</th>
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
        <div class="card-header bg-success text-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h6 class="mb-0 mb-md-0">
                    <i class="fas fa-user-check"></i>
                    Active Farmers
                </h6>
                <div class="d-flex flex-column flex-sm-row gap-2 mt-2 mt-md-0">
                    <div class="input-group" style="max-width: 300px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" placeholder="Search active farmers..." id="activeSearch">
                    </div>
                    <div class="btn-group" role="group">
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
                    <button class="btn btn-secondary btn-sm" onclick="printTable('activeFarmersTable')" title="Print">
                        <i class="fas fa-print"></i>
                    </button>
                    <button class="btn btn-info btn-sm" onclick="refreshActiveData()" title="Refresh">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="activeFarmersTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Farm Name</th>
                            <th>Barangay</th>
                            <th>Contact</th>
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
                    <input type="hidden" id="inspectionFarmerIdHidden">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inspectionFarmerName">Farmer Name</label>
                                <input type="text" class="form-control" id="inspectionFarmerName" readonly>
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
    $('#pendingSearch').on('keyup', function() {
        pendingFarmersTable.search(this.value).draw();
    });
    
    $('#activeSearch').on('keyup', function() {
        activeFarmersTable.search(this.value).draw();
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
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No data available</h5><p>There are no records to display at this time.</p></div>',
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        },
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
        columnDefs: [
            {
                targets: -1, // Last column (Actions)
                orderable: false,
                searchable: false
            }
        ]
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
    
    // Add event listeners to force pagination positioning on table updates
    pendingFarmersTable.on('draw.dt', function() {
        setTimeout(forcePaginationLeft, 50);
    });
    
    activeFarmersTable.on('draw.dt', function() {
        setTimeout(forcePaginationLeft, 50);
    });
    
    // Force initial positioning
    setTimeout(forcePaginationLeft, 200);



    // Hide default DataTables elements
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
    
    // Force pagination to left side after initialization
    setTimeout(() => {
        forcePaginationLeft();
    }, 100);
}

    // Function to force pagination positioning to the left - ENHANCED VERSION
    function forcePaginationLeft() {
        console.log('Forcing pagination to left - enhanced version...');
        
        // Force wrapper layout
        $('.dataTables_wrapper .row').css({
            'display': 'block',
            'width': '100%',
            'margin': '0',
            'padding': '0'
        });
        
        $('.dataTables_wrapper .row > div').css({
            'width': '100%',
            'float': 'left',
            'clear': 'both',
            'padding': '0',
            'margin': '0'
        });
        
        // Force pagination and info to left
        $('.dataTables_wrapper .dataTables_paginate').css({
            'text-align': 'left',
            'float': 'left',
            'clear': 'both',
            'display': 'block',
            'width': 'auto',
            'margin-right': '1rem',
            'margin-top': '1rem'
        });
        
        $('.dataTables_wrapper .dataTables_info').css({
            'text-align': 'left',
            'float': 'left',
            'clear': 'both',
            'display': 'block',
            'width': 'auto',
            'margin-right': '1rem',
            'margin-top': '1rem'
        });
        
        // Apply admin theme color to pagination buttons
        $('.dataTables_wrapper .dataTables_paginate .paginate_button').css({
            'color': '#1cc88a',
            'border-color': '#1cc88a'
        });
        
        $('.dataTables_wrapper .dataTables_paginate .paginate_button.current').css({
            'color': '#fff',
            'background-color': '#1cc88a',
            'border-color': '#1cc88a'
        });
        
        console.log('Enhanced pagination positioning applied');
    }

function loadPendingFarmers() {
    // Show loading state
    const tableBody = $('#pendingFarmersBody');
    tableBody.html('<tr><td colspan="9" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading pending farmers...</td></tr>');
    
    // Load pending farmers from the database via AJAX
    $.ajax({
        url: '{{ route("admin.farmers.pending") }}',
        method: 'GET',
        success: function(response) {
            pendingFarmersTable.clear();
            
            if (response.success && response.data && response.data.length > 0) {
                response.data.forEach((farmer) => {
                    const rowData = [
                        `<a href="#" class="user-id-link" onclick="showFarmerDetails(${farmer.id})" title="Click to view details">${farmer.id}</a>`,
                        `${farmer.first_name || ''} ${farmer.last_name || ''}`,
                        farmer.farm_name || 'N/A',
                        farmer.barangay || 'N/A',
                        farmer.phone || 'N/A',
                        farmer.email || 'N/A',
                        farmer.username || 'N/A',
                        farmer.created_at ? new Date(farmer.created_at).toLocaleDateString() : 'N/A',
                        `<div class="action-buttons">
                            <button class="btn-action btn-action-approve" onclick="approveFarmer('${farmer.id}')" title="Approve">
                                <i class="fas fa-check"></i>
                                <span>Approve</span>
                            </button>
                            <button class="btn-action btn-action-reject" onclick="showRejectionModal('${farmer.id}')" title="Reject">
                                <i class="fas fa-times"></i>
                                <span>Reject</span>
                            </button>
                        </div>`
                    ];
                    
                    pendingFarmersTable.row.add(rowData);
                });
                pendingFarmersTable.draw();
            } else {
                // Handle empty data properly
                pendingFarmersTable.clear().draw();
                $('#pendingFarmersBody').html('<tr><td colspan="9" class="text-center">No pending farmers found</td></tr>');
            }
        },
        error: function(xhr) {
            console.error('Error loading pending farmers:', xhr);
            pendingFarmersTable.clear().draw();
            $('#pendingFarmersBody').html('<tr><td colspan="9" class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Error loading pending farmers</td></tr>');
        }
    });
}

function loadActiveFarmers() {
    // Show loading state
    const tableBody = $('#activeFarmersBody');
    tableBody.html('<tr><td colspan="9" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading active farmers...</td></tr>');
    
    // Load active farmers from the database via AJAX
    $.ajax({
        url: '{{ route("admin.farmers.active") }}',
        method: 'GET',
        success: function(response) {
            activeFarmersTable.clear();
            
            if (response.success && response.data && response.data.length > 0) {
                response.data.forEach((farmer) => {
                    const rowData = [
                        `<a href="#" class="user-id-link" onclick="showFarmerDetails(${farmer.id})" title="Click to view details">${farmer.id}</a>`,
                        `${farmer.first_name || ''} ${farmer.last_name || ''}`,
                        farmer.farm_name || 'N/A',
                        farmer.barangay || 'N/A',
                        farmer.phone || 'N/A',
                        farmer.email || 'N/A',
                        farmer.username || 'N/A',
                        farmer.created_at ? new Date(farmer.created_at).toLocaleDateString() : 'N/A',
                        `<div class="action-buttons">
                            <button class="btn-action btn-action-view" onclick="showFarmerDetails(${farmer.id})" title="View Details">
                                <i class="fas fa-eye"></i>
                                <span>View</span>
                            </button>
                            <button class="btn-action btn-action-view" onclick="openContactModal()" title="Contact">
                                <i class="fas fa-envelope"></i>
                                <span>Contact</span>
                            </button>
                            <button class="btn-action btn-action-toggle" onclick="deactivateFarmer('${farmer.id}')" title="Deactivate">
                                <i class="fas fa-user-slash"></i>
                                <span>Deactivate</span>
                            </button>
                        </div>`
                    ];
                    
                    activeFarmersTable.row.add(rowData);
                });
                activeFarmersTable.draw();
            } else {
                // Handle empty data properly
                activeFarmersTable.clear().draw();
                $('#activeFarmersBody').html('<tr><td colspan="9" class="text-center">No active farmers found</td></tr>');
            }
        },
        error: function(xhr) {
            console.error('Error loading active farmers:', xhr);
            activeFarmersTable.clear().draw();
            $('#activeFarmersBody').html('<tr><td colspan="9" class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Error loading active farmers</td></tr>');
        }
    });
}

function updateStats() {
    // Update stats via AJAX
    $.ajax({
        url: '{{ route("admin.farmers.stats") }}',
        method: 'GET',
        success: function(response) {
            if (response.success && response.data) {
                document.getElementById('pendingCount').textContent = response.data.pending || 0;
                document.getElementById('activeCount').textContent = response.data.active || 0;
                document.getElementById('totalCount').textContent = response.data.total || 0;
                document.getElementById('rejectedCount').textContent = response.data.rejected || 0;
            }
        },
        error: function(xhr) {
            console.error('Error loading stats:', xhr);
        }
    });
}

function showFarmerDetails(farmerId) {
    // Load farmer details via AJAX
    $.ajax({
        url: `/admin/farmers/${farmerId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const farmer = response.farmer;
                const details = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #1cc88a; font-weight: 600;">Personal Information</h6>
                            <p><strong>Full Name:</strong> ${farmer.first_name || ''} ${farmer.last_name || ''}</p>
                            <p><strong>Email:</strong> ${farmer.email || 'N/A'}</p>
                            <p><strong>Username:</strong> ${farmer.username || 'N/A'}</p>
                            <p><strong>Contact Number:</strong> ${farmer.phone || 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #1cc88a; font-weight: 600;">Account Information</h6>
                            <p><strong>Role:</strong> <span class="badge badge-success">Farmer</span></p>
                            <p><strong>Status:</strong> <span class="badge badge-${farmer.status === 'active' || farmer.status === 'approved' ? 'success' : 'warning'}">${farmer.status}</span></p>
                            <p><strong>Barangay:</strong> ${farmer.barangay || 'N/A'}</p>
                            <p><strong>Registration Date:</strong> ${farmer.created_at ? new Date(farmer.created_at).toLocaleDateString() : 'N/A'}</p>
                            <p><strong>Last Updated:</strong> ${farmer.updated_at ? new Date(farmer.updated_at).toLocaleDateString() : 'N/A'}</p>
                        </div>
                    </div>
                `;

                document.getElementById('farmerDetails').innerHTML = details;
                document.getElementById('farmerNameHidden').value = `${farmer.first_name || ''} ${farmer.last_name || ''}`;
                $('#detailsModal').modal('show');
            } else {
                alert('Error loading farmer details');
            }
        },
        error: function() {
            alert('Error loading farmer details');
        }
    });
}

function approveFarmer(farmerId) {
    if (!confirm('Are you sure you want to approve this farmer?')) return;
    
    $.ajax({
        url: `{{ route("admin.farmers.approve", ":id") }}`.replace(':id', farmerId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                loadPendingFarmers();
                loadActiveFarmers();
                updateStats();
                showNotification('Farmer approved successfully!', 'success');
            } else {
                showNotification(response.message || 'Error approving farmer', 'danger');
            }
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
            if (response.success) {
                $('#rejectionModal').modal('hide');
                document.getElementById('rejectionReason').value = '';
                loadPendingFarmers();
                updateStats();
                showNotification('Farmer registration rejected.', 'warning');
            } else {
                showNotification(response.message || 'Error rejecting farmer', 'danger');
            }
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
            if (response.success) {
                loadActiveFarmers();
                updateStats();
                showNotification('Farmer deactivated successfully.', 'warning');
            } else {
                showNotification(response.message || 'Error deactivating farmer', 'danger');
            }
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
            if (response.success) {
                document.getElementById('messageNotification').innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Message sent to <strong>${name}</strong> successfully!
                    </div>
                `;
                document.getElementById('messageNotification').style.display = 'block';

                document.getElementById('messageSubject').value = '';
                document.getElementById('messageBody').value = '';
                
                setTimeout(() => {
                    $('#contactModal').modal('hide');
                    document.getElementById('messageNotification').style.display = 'none';
                }, 2000);
            } else {
                document.getElementById('messageNotification').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        ${response.message || 'Error sending message. Please try again.'}
                    </div>
                `;
                document.getElementById('messageNotification').style.display = 'block';
            }
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

function refreshPendingData() {
    const refreshBtn = $('button[onclick="refreshPendingData()"]');
    const originalIcon = refreshBtn.html();
    refreshBtn.html('<i class="fas fa-spinner fa-spin"></i>');
    refreshBtn.prop('disabled', true);
    
    loadPendingFarmers();
    updateStats();
    
    setTimeout(() => {
        refreshBtn.html(originalIcon);
        refreshBtn.prop('disabled', false);
        showNotification('Pending farmers data refreshed', 'success');
    }, 1000);
}

function refreshActiveData() {
    const refreshBtn = $('button[onclick="refreshActiveData()"]');
    const originalIcon = refreshBtn.html();
    refreshBtn.html('<i class="fas fa-spinner fa-spin"></i>');
    refreshBtn.prop('disabled', true);
    
    loadActiveFarmers();
    updateStats();
    
    setTimeout(() => {
        refreshBtn.html(originalIcon);
        refreshBtn.prop('disabled', false);
        showNotification('Active farmers data refreshed', 'success');
    }, 1000);
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
