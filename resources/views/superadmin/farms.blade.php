@extends('layouts.app')

@section('title', 'LBDAIRY: SuperAdmin - Manage Farms')

@push('styles')
<style>
    /* Custom styles for farm management */
    .border-left-primary {
        border-left: 0.25rem solid #18375d !important;
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
    
    /* Farm ID link styling - match manage farmers */
    .farm-id-link {
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
    
    .farm-id-link:hover {
        color: #fff;
        background-color: #18375d;
        border-color: #18375d;
        text-decoration: none;
    }

    .farm-id-link:active {
        color: #fff;
        background-color: #122a4e;
        border-color: #122a4e;
    }
    
    /* Header action buttons styling to match User Management */
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

    .btn-action-refresh {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    .btn-action-refresh:hover {
        background-color: #e69500;
        border-color: #e69500;
        color: white;
    }

    .btn-action-add {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    .btn-action-add:hover {
        background-color: #2d5a47;
        border-color: #2d5a47;
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
    
    /* Align Farm Directory table styling with User Management */
    #dataTable {
        width: 100% !important;
        min-width: 1280px;
        border-collapse: collapse;
    }

    /* Consistent table styling */
    .table {
        margin-bottom: 0;
    }

    .table-bordered {
        border: 1px solid #dee2e6;
    }

    #dataTable th,
    #dataTable td {
        vertical-align: middle;
        padding: 0.75rem;
        text-align: center;
        border: 1px solid #dee2e6;
        white-space: nowrap;
        overflow: visible;
    }

    /* Table headers styling */
    #dataTable thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: bold;
        color: #495057;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 0.75rem;
        text-align: left;
        vertical-align: middle;
        position: relative;
        white-space: nowrap;
    }

    /* Fix DataTables sorting button overlap */
    #dataTable thead th.sorting,
    #dataTable thead th.sorting_asc,
    #dataTable thead th.sorting_desc {
        padding-right: 2rem !important;
    }

    /* Remove default DataTables sort indicators to prevent overlap */
    #dataTable thead th.sorting::after,
    #dataTable thead th.sorting_asc::after,
    #dataTable thead th.sorting_desc::after {
        display: none;
    }

    /* DataTables Pagination Styling */
    .dataTables_wrapper .dataTables_paginate {
        text-align: left !important;
        margin-top: 1rem;
        margin-bottom: 0.75rem !important; /* Match farmers directory gap */
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

    /* Ensure table has enough space for actions column */
    .table th:last-child,
    .table td:last-child {
        min-width: 200px;
        width: auto;
    }

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

    .btn-action-view {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }

    .btn-action-view:hover {
        background-color: #2d5a47;
        border-color: #2d5a47;
        color: white;
    }

    .btn-action-toggle {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }

    .btn-action-toggle:hover {
        background-color: #e69500;
        border-color: #e69500;
        color: white;
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

    /* Edit button (dark green) */
    .btn-action-edit {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }

    .btn-action-edit:hover {
        background-color: #2d5a47;
        border-color: #2d5a47;
        color: white;
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

    /* Farm Details Modal Styling */
    #detailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }

    #detailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }

    #detailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }

    #detailsModal .modal-body {
        padding: 2rem;
        background: white;
    }

    #detailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }

    /* Prevent dark blue bars on headings inside details modal */
    #detailsModal .text-primary {
        background-color: transparent !important;
        color: #18375d !important;
    }

    /* Extra safety: ensure headings never inherit dark backgrounds */
    #detailsModal h6,
    #detailsModal h6.mb-3,
    #detailsModal h6.text-primary {
        background-color: transparent !important;
        background: transparent !important;
    }
    #detailsModal h6.mb-3::before,
    #detailsModal h6.mb-3::after {
        background: transparent !important;
        content: none !important;
    }

    #detailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }

    #detailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Match badge colors for status */
    #detailsModal .badge-success {
        background-color: #387057 !important;
        color: white !important;
    }
    #detailsModal .badge-danger {
        background-color: #dc3545 !important;
        color: white !important;
    }

    /* Enhanced Farm Details Modal Styling - Fix for dark blue background covering text */
    /* Force reset all backgrounds in modal body */
    #detailsModal .modal-body,
    #detailsModal .modal-body *,
    #detailsModal .modal-body div,
    #detailsModal .modal-body p,
    #detailsModal .modal-body strong,
    #detailsModal .modal-body h6 {
        background: transparent !important;
        background-color: transparent !important;
    }

    /* Modal content structure */
    #detailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        overflow: hidden;
    }

    /* Modal header - only this should have dark blue */
    #detailsModal .modal-header {
        background: #18375d !important;
        background-color: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
        padding: 1.5rem;
    }

    #detailsModal .modal-header .modal-title,
    #detailsModal .modal-header .modal-title * {
        color: white !important;
        background: transparent !important;
        background-color: transparent !important;
        font-weight: 600;
    }

    #detailsModal .modal-header .close {
        color: white !important;
        opacity: 0.8;
        background: transparent !important;
    }

    #detailsModal .modal-header .close:hover {
        opacity: 1;
        background: transparent !important;
    }

    /* Modal body - force white background and dark text */
    #detailsModal .modal-body {
        padding: 2rem !important;
        background: white !important;
        background-color: white !important;
        color: #333 !important;
    }

    /* Section headings in modal body */
    #detailsModal .modal-body h6,
    #detailsModal .modal-body h6.mb-3,
    #detailsModal .modal-body h6.text-primary {
        color: #18375d !important;
        background: transparent !important;
        background-color: transparent !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0 !important;
        padding-bottom: 0.5rem !important;
        margin-bottom: 1rem !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        padding-top: 0 !important;
    }

    /* Ensure no pseudo-elements add background */
    #detailsModal .modal-body h6::before,
    #detailsModal .modal-body h6::after,
    #detailsModal .modal-body h6.mb-3::before,
    #detailsModal .modal-body h6.mb-3::after {
        display: none !important;
        background: transparent !important;
        content: none !important;
    }

    /* Text content styling */
    #detailsModal .modal-body p {
        margin-bottom: 0.75rem !important;
        color: #333 !important;
        background: transparent !important;
        background-color: transparent !important;
        line-height: 1.5;
    }

    #detailsModal .modal-body strong {
        color: #5a5c69 !important;
        background: transparent !important;
        background-color: transparent !important;
        font-weight: 600;
    }

    /* Row and column containers */
    #detailsModal .modal-body .row,
    #detailsModal .modal-body .col-md-6,
    #detailsModal .modal-body .col-md-12,
    #detailsModal .modal-body .col-12 {
        background: transparent !important;
        background-color: transparent !important;
    }

    /* Badge styling */
    #detailsModal .badge {
        font-size: 0.75em;
        padding: 0.375em 0.75em;
        border-radius: 0.25rem;
        display: inline-block;
    }

    #detailsModal .badge-success {
        background-color: #387057 !important;
        color: white !important;
        border: none;
    }

    #detailsModal .badge-danger {
        background-color: #dc3545 !important;
        color: white !important;
        border: none;
    }

    /* Modal footer */
    #detailsModal .modal-footer {
        background: #f8f9fa !important;
        background-color: #f8f9fa !important;
        border-top: 1px solid #dee2e6 !important;
        padding: 1rem 1.5rem;
    }

    #detailsModal .modal-footer .btn {
        background: #6c757d !important;
        border-color: #6c757d !important;
        color: white !important;
    }

    #detailsModal .modal-footer .btn:hover {
        background: #5a6268 !important;
        border-color: #5a6268 !important;
    }

    /* Additional safety measures */
    #detailsModal .text-primary {
        background: transparent !important;
        background-color: transparent !important;
        color: #18375d !important;
    }

    #detailsModal .bg-primary,
    #detailsModal .bg-primary * {
        background: transparent !important;
        background-color: transparent !important;
    }

    /* Ensure all divs in modal body are transparent */
    #detailsModal .modal-body div {
        background: transparent !important;
        background-color: transparent !important;
    }

    /* Force override any Bootstrap or custom classes that might interfere */
    #detailsModal .modal-body .card,
    #detailsModal .modal-body .card-body,
    #detailsModal .modal-body .card-header {
        background: transparent !important;
        background-color: transparent !important;
        border: none !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #detailsModal .modal-body {
            padding: 1.5rem !important;
        }
        #detailsModal .modal-body h6 {
            font-size: 1rem;
        }
        #detailsModal .modal-body p {
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-university"></i>
            Farm Management
        </h1>
        <p>Monitor and manage registered farms, owners, and operational status</p>
    </div>

    <!-- Stats Cards -->
    <div class="row fade-in">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Active Farms</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeFarmsCount">0</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle fa-2x" style="color: #18375d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Inactive Farms</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="inactiveFarmsCount">0</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle fa-2x" style="color: #18375d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Farms</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalFarmsCount">0</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-university fa-2x" style="color: #18375d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Barangays Covered</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="barangayCount">0</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-map-marker-alt fa-2x" style="color: #18375d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Farm Management Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-primary text-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-list"></i>
                    Farm Directory
                </h6>
                <div class="d-flex flex-column flex-sm-row align-items-center gap-2">
                    <button class="btn-action btn-action-add" onclick="showAddFarmModal()">
                        <i class="fas fa-university"></i> Add Farm
                    </button>
                    <button class="btn-action btn-action-print" onclick="printTable()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button class="btn-action btn-action-refresh" onclick="refreshData()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <button class="btn-action btn-action-tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportCSV()">
                                <i class="fas fa-file-csv"></i> Download CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPNG()">
                                <i class="fas fa-image"></i> Download PNG
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPDF()">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div class="input-group" style="max-width: 300px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search farms..." id="farmSearch">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Farm ID</th>
                            <th>Farm Name</th>
                            <th>Owner Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Barangay</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="farmsTableBody">
                        <!-- Farms will be loaded here -->
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
                    Confirm Delete
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this farm? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Farm Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">
                    <i class="fas fa-university"></i>
                    Farm Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="farmDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Farm Modal -->
<div class="modal fade" id="farmModal" tabindex="-1" role="dialog" aria-labelledby="farmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="farmModalLabel">
                    <i class="fas fa-university"></i>
                    Add New Farm
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="farmForm" onsubmit="saveFarm(event)">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="farmId" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="farmName">Farm Name *</label>
                                <input type="text" class="form-control" id="farmName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="farmBarangay">Barangay</label>
                                <input type="text" class="form-control" id="farmBarangay" name="barangay">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ownerName">Owner Name</label>
                                <input type="text" class="form-control" id="ownerName" name="owner_name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ownerEmail">Owner Email</label>
                                <input type="email" class="form-control" id="ownerEmail" name="owner_email">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ownerPhone">Owner Phone</label>
                                <input type="text" class="form-control" id="ownerPhone" name="owner_phone">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="farmStatus">Status *</label>
                                <select class="form-control" id="farmStatus" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="farmFormNotification" class="mt-2" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Farm
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
let farmsTable;
let farmToDelete = null;

$(document).ready(function () {
    // Initialize DataTables
    initializeDataTables();
    
    // Load data
    loadFarms();
    updateStats();

    // Custom search functionality
    $('#farmSearch').on('keyup', function() {
        farmsTable.search(this.value).draw();
    });
});

function initializeDataTables() {
    farmsTable = $('#dataTable').DataTable({
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
                title: 'Farms_Report',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Farms_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Farms Report',
                className: 'd-none'
            }
        ],
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No farms available</h5><p>There are no farms to display at this time.</p></div>'
        }
    });

    // Hide default DataTables elements
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
}

function loadFarms() {
    // Load farms from the database via AJAX
    $.ajax({
        url: '{{ route("superadmin.farms.list") }}',
        method: 'GET',
        success: function(response) {
            farmsTable.clear();
            const items = response.data || [];
            items.forEach(farm => {
                const rowData = [
                    `<a href="#" class="farm-id-link" onclick="openDetailsModal('${farm.id}')">${farm.farm_id}</a>`,
                    `${farm.name || 'N/A'}`,
                    `${farm.owner?.name || ''}`,
                    `${farm.owner?.email || ''}`,
                    `${farm.owner?.phone || ''}`,
                    `${farm.barangay || ''}`,
                    `<span class="badge badge-${farm.status === 'active' ? 'success' : 'danger'}">${farm.status}</span>`,
                    `<div class=\"action-buttons\">\n\	\t\t\t\t\t<button class=\"btn-action btn-action-edit\" onclick=\"editFarm('${farm.id}')\" title=\"Edit\">\n\	\t\t\t\t\t\t<i class=\"fas fa-edit\"></i>\n\	\t\t\t\t\t\t<span>Edit</span>\n\	\t\t\t\t\t</button>\n\	\t\t\t\t\t<button class=\"btn-action btn-action-delete\" onclick=\"confirmDelete('${farm.id}')\" title=\"Delete\">\n\	\t\t\t\t\t\t<i class=\"fas fa-trash\"></i>\n\	\t\t\t\t\t\t<span>Delete</span>\n\	\t\t\t\t\t</button>\n\	\t\t\t\t</div>`
                ];
                
                farmsTable.row.add(rowData).draw(false);
            });
        },
        error: function(xhr) {
            console.error('Error loading farms:', xhr);
        }
    });
}

function updateStats() {
    // Update stats via AJAX
    $.ajax({
        url: '{{ route("superadmin.farms.stats") }}',
        method: 'GET',
        success: function(response) {
            document.getElementById('activeFarmsCount').textContent = response.active;
            document.getElementById('inactiveFarmsCount').textContent = response.inactive;
            document.getElementById('totalFarmsCount').textContent = response.total;
            document.getElementById('barangayCount').textContent = response.barangays;
        },
        error: function(xhr) {
            console.error('Error loading stats:', xhr);
        }
    });
}

function openDetailsModal(farmId) {
    $.ajax({
        url: `{{ route("superadmin.farms.show", ":id") }}`.replace(':id', farmId),
        method: 'GET',
        success: function(response) {
            const farm = response.data;
            const details = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Farm Information</h6>
                        <p><strong>Farm ID:</strong> ${farm.farm_id}</p>
                        <p><strong>Farm Name:</strong> ${farm.name || 'N/A'}</p>
                        <p><strong>Barangay:</strong> ${farm.barangay || ''}</p>
                        <p><strong>Status:</strong> <span class="badge badge-${farm.status === 'active' ? 'success' : 'danger'}">${farm.status}</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Owner Information</h6>
                        <p><strong>Owner Name:</strong> ${farm.owner?.name || ''}</p>
                        <p><strong>Email:</strong> ${farm.owner?.email || ''}</p>
                        <p><strong>Contact Number:</strong> ${farm.owner?.phone || ''}</p>
                        <p><strong>Registration Date:</strong> ${new Date(farm.created_at).toLocaleDateString()}</p>
                    </div>
                </div>
                ${farm.description ? `
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Description</h6>
                        <p>${farm.description}</p>
                    </div>
                </div>
                ` : ''}
            `;

            document.getElementById('farmDetails').innerHTML = details;
            $('#detailsModal').modal('show');
        },
        error: function(xhr) {
            console.error('Error loading farm details:', xhr);
        }
    });
}

function toggleFarmStatus(farmId, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    
    $.ajax({
        url: `{{ route("superadmin.farms.update-status", ":id") }}`.replace(':id', farmId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            status: newStatus
        },
        success: function(response) {
            loadFarms();
            updateStats();
            showNotification(`Farm status updated to ${newStatus}`, 'success');
        },
        error: function(xhr) {
            showNotification('Error updating farm status', 'danger');
        }
    });
}

function editFarm(farmId) {
    $.ajax({
        url: `{{ route('superadmin.farms.show', ':id') }}`.replace(':id', farmId),
        method: 'GET',
        success: function(response) {
            const farm = response.data;
            // Populate Add Farm modal as Edit Farm
            $('#farmModalLabel').html('<i class="fas fa-university"></i> Edit Farm');
            $('#farmId').val(farm.id);
            $('#farmName').val(farm.name || '');
            $('#farmBarangay').val(farm.barangay || '');
            $('#ownerName').val(farm.owner?.name || '');
            $('#ownerEmail').val(farm.owner?.email || '');
            $('#ownerPhone').val(farm.owner?.phone || '');
            $('#farmStatus').val(farm.status || 'active');
            $('#farmModal').modal('show');
        },
        error: function() {
            showNotification('Error loading farm data', 'danger');
        }
    });
}

function updateActivity(selectElement, farmId) {
    const newStatus = selectElement.value;
    
    $.ajax({
        url: `{{ route("superadmin.farms.update-status", ":id") }}`.replace(':id', farmId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            status: newStatus
        },
        success: function(response) {
            updateStats();
            showNotification(`Farm status updated to ${newStatus}`, 'success');
        },
        error: function(xhr) {
            // Revert the select element if update fails
            selectElement.value = selectElement.value === 'active' ? 'inactive' : 'active';
            showNotification('Error updating farm status', 'danger');
        }
    });
}

function confirmDelete(farmId) {
    farmToDelete = farmId;
    $('#confirmDeleteModal').modal('show');
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (farmToDelete) {
        deleteFarm(farmToDelete);
        farmToDelete = null;
        $('#confirmDeleteModal').modal('hide');
    }
});

function deleteFarm(farmId) {
    $.ajax({
        url: `{{ route("superadmin.farms.destroy", ":id") }}`.replace(':id', farmId),
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            loadFarms();
            updateStats();
            showNotification('Farm deleted successfully', 'success');
        },
        error: function(xhr) {
            showNotification('Error deleting farm', 'danger');
        }
    });
}

function importCSV(event) {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('csv_file', file);

    $.ajax({
        url: '{{ route("superadmin.farms.import") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            loadFarms();
            updateStats();
            showNotification(`Successfully imported ${response.imported} farms`, 'success');
        },
        error: function(xhr) {
            showNotification('Error importing CSV file', 'danger');
        }
    });

    // Reset file input
    event.target.value = '';
}

function exportCSV() {
    const tableData = farmsTable.data().toArray();
    const csvData = [];
    const headers = ['Farm ID', 'Farm Name', 'Owner Name', 'Email', 'Phone', 'Barangay', 'Status'];
    csvData.push(headers.join(','));
    tableData.forEach(row => {
        const rowData = [];
        for (let i = 0; i < row.length - 1; i++) {
            let cellText = '';
            if (row[i]) {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = row[i];
                cellText = tempDiv.textContent || tempDiv.innerText || '';
                cellText = cellText.replace(/\s+/g, ' ').trim();
            }
            if (cellText.includes(',') || cellText.includes('"') || cellText.includes('\n')) {
                cellText = '"' + cellText.replace(/"/g, '""') + '"';
            }
            rowData.push(cellText);
        }
        csvData.push(rowData.join(','));
    });
    const csvContent = csvData.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'Farms_Report.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function exportPDF() {
    try {
        if (typeof window.jspdf === 'undefined') {
    farmsTable.button('.buttons-pdf').trigger();
            return;
        }
        const tableData = farmsTable.data().toArray();
        const pdfData = [];
        const headers = ['Farm ID', 'Farm Name', 'Owner Name', 'Email', 'Phone', 'Barangay', 'Status'];
        tableData.forEach(row => {
            const rowData = [];
            for (let i = 0; i < row.length - 1; i++) {
                let cellText = '';
                if (row[i]) {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = row[i];
                    cellText = tempDiv.textContent || tempDiv.innerText || '';
                    cellText = cellText.replace(/\s+/g, ' ').trim();
                }
                rowData.push(cellText);
            }
            pdfData.push(rowData);
        });
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape', 'mm', 'a4');
        doc.setFontSize(18);
        doc.text('SuperAdmin Farms Report', 14, 22);
        doc.setFontSize(12);
        doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 14, 32);
        doc.autoTable({
            head: [headers],
            body: pdfData,
            startY: 40,
            styles: { fontSize: 8, cellPadding: 2 },
            headStyles: { fillColor: [24, 55, 93], textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [245, 245, 245] }
        });
        doc.save('Farms_Report.pdf');
        showNotification('PDF exported successfully!', 'success');
    } catch (error) {
        console.error('Error generating PDF:', error);
        showNotification('Error generating PDF. Falling back to DataTables export.', 'warning');
        try { farmsTable.button('.buttons-pdf').trigger(); } catch {}
    }
}

function exportPNG() {
    const originalTable = document.getElementById('dataTable');
    const tempTable = originalTable.cloneNode(true);
    const headerRow = tempTable.querySelector('thead tr');
    if (headerRow) {
        const lastHeaderCell = headerRow.lastElementChild;
        if (lastHeaderCell) lastHeaderCell.remove();
    }
    const dataRows = tempTable.querySelectorAll('tbody tr');
    dataRows.forEach(row => {
        const lastDataCell = row.lastElementChild;
        if (lastDataCell) lastDataCell.remove();
    });
    tempTable.style.position = 'absolute';
    tempTable.style.left = '-9999px';
    tempTable.style.top = '-9999px';
    document.body.appendChild(tempTable);
    html2canvas(tempTable, { scale: 2, backgroundColor: '#ffffff', width: tempTable.offsetWidth, height: tempTable.offsetHeight })
        .then(canvas => {
            const link = document.createElement('a');
        link.download = 'Farms_Report.png';
            link.href = canvas.toDataURL('image/png');
        link.click();
            document.body.removeChild(tempTable);
        })
        .catch(error => {
            console.error('Error generating PNG:', error);
            if (document.body.contains(tempTable)) document.body.removeChild(tempTable);
            showNotification('Error generating PNG export', 'danger');
    });
}

function printTable() {
    try {
        const tableData = farmsTable.data().toArray();
        if (!tableData || tableData.length === 0) {
            showNotification('No data available to print', 'warning');
            return;
        }
        const originalContent = document.body.innerHTML;
        let printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #18375d; margin-bottom: 5px;">SuperAdmin Farms Report</h1>
                    <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                <table border="3" style="border-collapse: collapse; width: 100%; border: 3px solid #000;">
                    <thead>
                        <tr>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Farm ID</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Farm Name</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Owner Name</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Email</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Phone</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Barangay</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Status</th>
                        </tr>
                    </thead>
                    <tbody>`;
        tableData.forEach(row => {
            printContent += '<tr>';
            for (let i = 0; i < row.length - 1; i++) {
                let cellText = '';
                if (row[i]) {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = row[i];
                    cellText = tempDiv.textContent || tempDiv.innerText || '';
                    cellText = cellText.replace(/\s+/g, ' ').trim();
                }
                printContent += `<td style="border: 3px solid #000; padding: 10px; text-align: left;">${cellText}</td>`;
            }
            printContent += '</tr>';
        });
        printContent += `
                    </tbody>
                </table>
            </div>`;
        document.body.innerHTML = printContent;
        window.print();
        setTimeout(() => { document.body.innerHTML = originalContent; location.reload(); }, 100);
    } catch (error) {
        console.error('Error in print function:', error);
        showNotification('Error generating print. Please try again.', 'danger');
        try { farmsTable.button('.buttons-print').trigger(); } catch {}
    }
}

function refreshData() {
    const refreshBtn = $('button[onclick="refreshData()"]');
    const originalIcon = refreshBtn.html();
    refreshBtn.html('<i class="fas fa-spinner fa-spin"></i>');
    refreshBtn.prop('disabled', true);

    loadFarms();
    updateStats();

    setTimeout(() => {
        refreshBtn.html(originalIcon);
        refreshBtn.prop('disabled', false);
        showNotification('Data refreshed successfully', 'success');
    }, 1000);
}

function showAddFarmModal() {
    resetFarmForm();
    $('#farmModalLabel').html('<i class="fas fa-university"></i> Add New Farm');
    $('#farmModal').modal('show');
}

function resetFarmForm() {
    $('#farmForm')[0].reset();
    $('#farmId').val('');
    $('#farmFormNotification').hide();
}

function saveFarm(event) {
    event.preventDefault();

    const farmId = $('#farmId').val();
    const url = farmId ? `{{ route("superadmin.farms.update", ":id") }}`.replace(':id', farmId) : '{{ route("superadmin.farms.store") }}';
    const formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        name: $('#farmName').val(),
        barangay: $('#farmBarangay').val(),
        owner_name: $('#ownerName').val(),
        owner_email: $('#ownerEmail').val(),
        owner_phone: $('#ownerPhone').val(),
        status: $('#farmStatus').val()
    };

    $.ajax({
        url: url,
        method: farmId ? 'PUT' : 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        success: function(response) {
            $('#farmModal').modal('hide');
            loadFarms();
            updateStats();
            showNotification(farmId ? 'Farm updated successfully' : 'Farm created successfully', 'success');
        },
        error: function(xhr) {
            const errors = xhr.responseJSON?.errors || {};
            let errorMessage = 'Please fix the following errors:';
            Object.keys(errors).forEach(field => {
                errorMessage += `\n• ${field}: ${errors[field][0]}`;
            });
            document.getElementById('farmFormNotification').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    ${errorMessage}
                </div>
            `;
            document.getElementById('farmFormNotification').style.display = 'block';
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
</script>
@endpush
