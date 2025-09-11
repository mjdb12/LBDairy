@extends('layouts.app')

@section('title', 'Livestock Management')
@push('styles')
<style>
    /* Farmer Details Modal Styling (ensure header doesn't cover content) */
    #farmerDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        overflow: hidden;
    }

    #farmerDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
    }

    #farmerDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }

    #farmerDetailsModal .modal-body {
        padding: 2rem;
        background: #ffffff;
        position: relative;
        z-index: 1;
    }

    #farmerDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }

    /* Prevent dark blue bars behind headings in farmer details */
    #farmerDetailsModal .text-primary {
        background-color: transparent !important;
        color: #18375d !important;
    }

    #farmerDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }

    #farmerDetailsModal .modal-footer {
        background: #ffffff;
    }
    /* Custom styles for farmer management */
    .border-left-primary {
        border-left: 0.25rem solid #18375d !important;
    }
    
    .fade-in {
        animation: fadeIn 0.6s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
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

    /* Action buttons styling to match admin management */
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
    
    .btn-action-refresh-admins, .btn-action-refresh-farmers {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-refresh-admin:hover, .btn-action-refresh-farmers:hover {
        background-color: #e69500;
        border-color: #e69500;
        color: white;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .d-flex.flex-column.flex-sm-row {
            flex-direction: column !important;
        }
        
        .gap-2 {
            gap: 0.5rem !important;
        }
    }
    
    /* Dashboard-style stat card styling */
    .dashboard-card {
        transition: transform 0.2s ease-in-out;
        background: #fff !important;
    }
    
    .dashboard-card:hover {
        transform: translateY(-2px);
    }
    
    /* Force override any blue styling on stat cards */
    .card.stat-card,
    .card.dashboard-card {
        background: #fff !important;
        background-color: #fff !important;
    }
    
    .card.stat-card .card-body,
    .card.dashboard-card .card-body {
        background: #fff !important;
        background-color: #fff !important;
        color: inherit !important;
    }
    
    .stat-card {
        border-radius: 10px;
        overflow: hidden;
        background: #fff !important;
    }
    
    .stat-card .card-body {
        padding: 1.5rem;
        background: #fff !important;
    }
    
    .stat-card .icon {
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }
    
    .stat-card:hover .icon {
        opacity: 1;
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
    
    #farmersTable th,
    #farmersTable td {
        vertical-align: middle;
        padding: 0.75rem;
        text-align: center;
        border: 1px solid #dee2e6;
        white-space: nowrap;
        overflow: visible;
    }
    
    /* Ensure all table headers have consistent styling */
    #farmersTable thead th {
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
    #farmersTable thead th.sorting,
    #farmersTable thead th.sorting_asc,
    #farmersTable thead th.sorting_desc {
        padding-right: 2rem !important;
    }
    
    /* Ensure proper spacing for sort indicators */
    #farmersTable thead th::after {
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
    
    /* Remove default DataTables sort indicators to prevent overlap */
    #farmersTable thead th.sorting::after,
    #farmersTable thead th.sorting_asc::after,
    #farmersTable thead th.sorting_desc::after {
        display: none;
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
        color: #18375d !important; /* Darker blue color for numbers */
        border: 1px solid #18375d !important;
        border-radius: 0.25rem;
        background-color: #fff;
        transition: all 0.15s ease-in-out;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        color: #fff !important;
        background-color: #18375d !important;
        border-color: #18375d !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        color: #fff !important;
        background-color: #18375d !important;
        border-color: #18375d !important;
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
        color: #18375d !important;
        border-color: #18375d !important;
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
    
    .btn-action-view {
        background-color: #  #4466ca;
        border-color: #  #4466ca;
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
    
    /* Ensure table has enough space for actions column */
    .table th:last-child,
    .table td:last-child {
        min-width: 200px;
        width: auto;
        text-align: center;
        vertical-align: middle;
    }
    
    /* Responsive action buttons */
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
</style>
@endpush

@section('content')
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-cow"></i>
            Livestock Management
        </h1>
        <p>Select a farmer to view and manage their livestock</p>
    </div>

    <!-- Farmer Selection Section -->
     <div class="card shadow mb-4 fade-in" id="farmerSelectionCard">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">
                <i class="fas fa-users"></i>
                Select Farmer
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
                    <input type="text" class="form-control" placeholder="Search pending farmers..." id="pendingSearch">
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-center">
                    <button class="btn-action btn-action-refresh-farmers" onclick="refreshPendingFarmersTable('pendingFarmersTable')">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
            <div class="card-body">
            <div class="table-responsive">
                <div class="table-responsive">
                <table class="table table-bordered table-hover" id="farmersTable">
                    <thead>
                        <tr>
                            <th>Farmer ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Total Livestock</th>
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
        </div>
    </div>
    

    <!-- Livestock Section (Initially Hidden) -->
    <div class="card shadow mb-4" id="livestockCard" style="display: none;" id="selectedLivestockId">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">
                <i class="fas fa-list"></i>
                Active Farmers
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
                    <input type="text" class="form-control" placeholder="Search livestock..." id="activeSearch">
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-center">
                    <button class="btn-action btn-secondary btn-sm" onclick="backToFarmers()">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button class="btn-action btn-action-add btn-sm" data-toggle="modal" data-target="#addLivestockModal">
                        <i class="fas fa-plus"></i> Add Livestock
                    </button>
                    <button class="btn-action btn-action-refresh-admins" onclick="refreshAdminsTable('activeFarmersTable')">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
            <div class="row mb-3">
                <!-- Total Livestock -->
                <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Livestock</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="farmerTotalLivestock">0</div>
                            </div>
                            <div class="icon">
                                <i class="fas fa-cow fa-2x" style="color: #18375d !important;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Livestock -->
                <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Active Livestock</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="farmerActiveLivestock">0</div>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-circle fa-2x" style="color: #18375d !important;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inactive Livestock -->
                <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Inactive Livestock</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="farmerInactiveLivestock">0</div>
                            </div>
                            <div class="icon">
                                <i class="fas fa-times-circle fa-2x" style="color: #18375d !important;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Farms -->
                <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Farms</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="farmerTotalFarms">0</div>
                            </div>
                            <div class="icon">
                                <i class="fas fa-university fa-2x" style="color: #18375d !important;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Livestock Table -->
            <div class="table-responsive">
                <table class="table table-bordered" id="livestockTable">
                    <thead>
                        <tr>
                            <th>Tag Number</th>
                            <th>Type</th>
                            <th>Breed</th>
                            <th>Gender</th>
                            <th>Farm</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="livestockTableBody">
                        <!-- Livestock will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Livestock Modal -->
<div class="modal fade" id="addLivestockModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Livestock</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="addLivestockForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="selectedFarmerId" name="farmer_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tag Number</label>
                                <input type="text" class="form-control" name="livestock_id" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="cow">Cow</option>
                                    <option value="buffalo">Buffalo</option>
                                    <option value="goat">Goat</option>
                                    <option value="sheep">Sheep</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Breed</label>
                                <select class="form-control" name="breed" required>
                                    <option value="">Select Breed</option>
                                    <option value="holstein">Holstein</option>
                                    <option value="jersey">Jersey</option>
                                    <option value="guernsey">Guernsey</option>
                                    <option value="ayrshire">Ayrshire</option>
                                    <option value="brown_swiss">Brown Swiss</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Farm</label>
                                <select class="form-control" name="farm_id" required>
                                    <option value="">Select Farm</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Birth Date</label>
                                <input type="date" class="form-control" name="birth_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gender</label>
                                <select class="form-control" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Livestock</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Livestock Details Modal -->
<div class="modal fade" id="livestockDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-cow"></i>
                    Livestock Details
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="livestockDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div class="modal fade" id="qrCodeModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-qrcode"></i>
                    QR Code
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div id="qrCodeContent"></div>
                <p class="mt-3" id="qrCodeText"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="downloadQRCode()">
                    <i class="fas fa-download"></i> Download
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Issue Alert Modal -->
<div class="modal fade" id="issueAlertModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Issue Alert
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="issueAlertForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="alertLivestockId">
                    <div class="form-group">
                        <label for="issueType">Issue Type</label>
                        <select class="form-control" id="issueType" required>
                            <option value="">Select Issue Type</option>
                            <option value="health">Health Issue</option>
                            <option value="injury">Injury</option>
                            <option value="production">Production Issue</option>
                            <option value="behavioral">Behavioral Issue</option>
                            <option value="environmental">Environmental Issue</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="issuePriority">Priority</label>
                        <select class="form-control" id="issuePriority" required>
                            <option value="">Select Priority</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="issueDescription">Description</label>
                        <textarea class="form-control" id="issueDescription" rows="4" required placeholder="Describe the issue in detail..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-exclamation-triangle"></i> Issue Alert
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .page-header {
        background: #18375d;
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }
    
    .farmer-link {
        color: #18375d;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
    }
    
    .farmer-link:hover {
        color: #122a47;
        text-decoration: underline;
    }
    
    .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
    .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
    .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
    .border-left-primary { border-left: 0.25rem solid #18375d !important; }
    
    .gap-2 { gap: 0.5rem !important; }
</style>
@endpush

@push('scripts')
<script>
    let selectedFarmerId = null;
    let selectedFarmerName = '';
    let selectedLivestockId = null;

    $(document).ready(function() {
        console.log('Document ready, loading farmers...');
        loadFarmers();
        
        // Search functionality
        $('#activeSearch').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('#farmersTable tbody tr').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(searchTerm) > -1);
            });
        });
    });

// Refresh Pending Farmers Table
function refreshPendingFarmersTable() {
    const refreshBtn = document.querySelector('.btn-action-refresh-farmers');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    refreshBtn.disabled = true;

    // Use unique flag for farmers
    sessionStorage.setItem('showRefreshNotificationFarmers', 'true');

    setTimeout(() => {
        location.reload();
    }, 1000);
}
// Check notifications after reload
$(document).ready(function() {
    if (sessionStorage.getItem('showRefreshNotificationFarmers') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationFarmers');
        setTimeout(() => {
            showNotification('Farmers data refreshed successfully!', 'success');
        }, 500);
    }
});
// Refresh Admins Table
function refreshAdminsTable() {
    const refreshBtn = document.querySelector('.btn-action-refresh-admins');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    refreshBtn.disabled = true;

    // Save which tab is active
    const activeTab = $('.nav-tabs .nav-link.active').attr('id'); 
    sessionStorage.setItem('activeTab', activeTab);

    // Use unique flag for admins refresh
    sessionStorage.setItem('showRefreshNotificationAdmins', 'true');

    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Restore tab + check notifications after reload
$(document).ready(function () {
    // Restore active tab
    const activeTab = sessionStorage.getItem('activeTab');
    if (activeTab) {
        $(`#${activeTab}`).tab('show'); 
        sessionStorage.removeItem('activeTab');
    }

    // Show notification if refresh happened
    if (sessionStorage.getItem('showRefreshNotificationAdmins') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationAdmins');
        setTimeout(() => {
            showNotification('Livestock data refreshed successfully!', 'success');
        }, 500);
    }
});


    let farmersTable;

$(document).ready(function () {
    // Initialize DataTables
    initializeDataTables();
    
    // Load data
    loadfarmersTable();
    updateStats();

    // Custom search functionality
    $('#pendingSearch').on('keyup', function() {
        pendingFarmersTable.search(this.value).draw();
    });
    
});

    function loadFarmers() {
        $('#farmersTableBody').html('<tr><td colspan="7" class="text-center">Loading farmers...</td></tr>');
        
        $.ajax({
            url: '{{ route("admin.livestock.farmers") }}',
            method: 'GET',
            success: function(response) {
                console.log('Farmers response:', response);
                if (response.success) {
                    let html = '';
                    if (response.data.length === 0) {
                        html = '<tr><td colspan="7" class="text-center">No farmers found</td></tr>';
                    } else {
                        response.data.forEach(farmer => {
                            const displayName = farmer.first_name && farmer.last_name 
                                ? `${farmer.first_name} ${farmer.last_name}` 
                                : farmer.name || 'N/A';
                            
                            html += `
                                <tr>
                                    <td>${farmer.id}</td>
                                    <td><a href="#" class="farmer-link" onclick="selectFarmer('${farmer.id}', '${displayName}')">${displayName}</a></td>
                                    <td>${farmer.email}</td>
                                    <td>${farmer.contact_number || 'N/A'}</td>
                                    <td>${farmer.livestock_count || 0}</td>
                                    <td><span class="badge badge-${getStatusBadgeClass(farmer.status)}">${farmer.status}</span></td>
                                    <td>
                                        <button class="btn btn-action-view btn-sm" onclick="selectFarmer('${farmer.id}', '${displayName}')">
                                            <i class="fas fa-cow"></i> View Livestock
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    $('#farmersTableBody').html(html);
                } else {
                    $('#farmersTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading farmers: ' + (response.message || 'Unknown error') + '</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr, status, error);
                console.log('Response Text:', xhr.responseText);
                $('#farmersTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading farmers. Check console for details.</td></tr>');
            }
        });
    }

    function selectFarmer(farmerId, farmerName) {
        selectedFarmerId = farmerId;
        selectedFarmerName = farmerName;
        
        $('#selectedFarmerName').text(farmerName);
        $('#selectedFarmerId').val(farmerId);
        
        $('#farmerSelectionCard').hide();
        $('#livestockCard').show();
        
        loadFarmerLivestock(farmerId);
        loadFarmerFarms(farmerId);
    }

    function backToFarmers() {
        selectedFarmerId = null;
        selectedFarmerName = '';
        
        $('#farmerSelectionCard').show();
        $('#livestockCard').hide();
        
        $('#livestockTableBody').empty();
    }

    function loadFarmerLivestock(farmerId) {
        $('#livestockTableBody').html('<tr><td colspan="7" class="text-center">Loading livestock...</td></tr>');
        
        $.ajax({
            url: `{{ route("admin.livestock.farmer-livestock", ":id") }}`.replace(':id', farmerId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let html = '';
                    if (response.data.livestock.length === 0) {
                        html = '<tr><td colspan="7" class="text-center">No livestock found for this farmer</td></tr>';
                    } else {
                        response.data.livestock.forEach(animal => {
                            html += `
                                <tr>
                                    <td>${animal.tag_number}</td>
                                    <td>${animal.type}</td>
                                    <td>${animal.breed}</td>
                                    <td>${animal.gender}</td>
                                    <td>${animal.farm ? animal.farm.name : 'N/A'}</td>
                                    <td>
                                        <select class="form-control" onchange="updateStatus(this, '${animal.id}')">
                                            <option value="active" ${animal.status === 'active' ? 'selected' : ''}>Active</option>
                                            <option value="inactive" ${animal.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action btn-action-view" onclick="viewLivestockDetails('${animal.id}')" title="View Details">
                                                <i class="fas fa-eye"></i>
                                                <span>View</span>
                                            </button>
                                            <button class="btn-action btn-action-print" onclick="generateQRCode('${animal.id}')" title="Generate QR Code">
                                                <i class="fas fa-qrcode"></i>
                                                <span>QR Code</span>
                                            </button>
                                            <button class="btn-action btn-action-flag" onclick="issueAlert('${animal.id}')" title="Issue Alert">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <span>Alert</span>
                                            </button>
                                            <button class="btn-action btn-action-edit" onclick="editLivestock('${animal.id}')" title="Edit">
                                                <i class="fas fa-edit"></i>
                                                <span>Edit</span>
                                            </button>
                                            <button class="btn-action btn-action-delete" onclick="deleteLivestock('${animal.id}')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                                <span>Delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    $('#livestockTableBody').html(html);
                    updateFarmerStats(response.data.stats);
                } else {
                    $('#livestockTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading livestock</td></tr>');
                }
            },
            error: function() {
                $('#livestockTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading livestock</td></tr>');
            }
        });
    }

    function loadFarmerFarms(farmerId) {
        $.ajax({
            url: `{{ route("admin.livestock.farmer-farms", ":id") }}`.replace(':id', farmerId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const farmSelect = $('select[name="farm_id"]');
                    farmSelect.empty().append('<option value="">Select Farm</option>');
                    
                    response.data.forEach(farm => {
                        farmSelect.append(`<option value="${farm.id}">${farm.name}</option>`);
                    });
                }
            }
        });
    }

    function updateFarmerStats(stats) {
        $('#farmerTotalLivestock').text(stats.total || 0);
        $('#farmerActiveLivestock').text(stats.active || 0);
        $('#farmerInactiveLivestock').text(stats.inactive || 0);
        $('#farmerTotalFarms').text(stats.farms || 0);
    }

    function refreshData() {
        if (selectedFarmerId) {
            loadFarmerLivestock(selectedFarmerId);
        } else {
            loadFarmers();
        }
    }

    function getStatusBadgeClass(status) {
        switch(status) {
            case 'approved': return 'success';
            case 'pending': return 'warning';
            case 'suspended': return 'danger';
            default: return 'secondary';
        }
    }

    function updateStatus(select, livestockId) {
        const status = select.value;
        
        $.ajax({
            url: `{{ route('admin.livestock.update-status', ':id') }}`.replace(':id', livestockId),
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({ status: status }),
            success: function(response) {
                if (response.success) {
                    if (selectedFarmerId) {
                        loadFarmerLivestock(selectedFarmerId);
                    }
                }
            }
        });
    }

    function editLivestock(livestockId) {
        // Implementation for editing livestock
        alert('Edit functionality coming soon');
    }

    function deleteLivestock(livestockId) {
        if (confirm('Are you sure you want to delete this livestock?')) {
            $.ajax({
                url: `{{ route('admin.livestock.destroy', ':id') }}`.replace(':id', livestockId),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (selectedFarmerId) {
                        loadFarmerLivestock(selectedFarmerId);
                    }
                }
            });
        }
    }

    // Handle add livestock form submission
    $('#addLivestockForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('farmer_id', selectedFarmerId);
        
        $.ajax({
            url: '{{ route("admin.livestock.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#addLivestockModal').modal('hide');
                $('#addLivestockForm')[0].reset();
                loadFarmerLivestock(selectedFarmerId);
            }
        });
    });

    function viewLivestockDetails(livestockId) {
        $.ajax({
            url: `{{ route("admin.livestock.details", ":id") }}`.replace(':id', livestockId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const livestock = response.data;
                    $('#livestockDetailsContent').html(`
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary">Basic Information</h6>
                                <table class="table table-borderless">
                                    <tr><td><strong>Tag Number:</strong></td><td>${livestock.tag_number}</td></tr>
                                    <tr><td><strong>Type:</strong></td><td>${livestock.type}</td></tr>
                                    <tr><td><strong>Breed:</strong></td><td>${livestock.breed}</td></tr>
                                    <tr><td><strong>Gender:</strong></td><td>${livestock.gender}</td></tr>
                                    <tr><td><strong>Birth Date:</strong></td><td>${livestock.birth_date}</td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Farm Information</h6>
                                <table class="table table-borderless">
                                    <tr><td><strong>Farm:</strong></td><td>${livestock.farm ? livestock.farm.name : 'N/A'}</td></tr>
                                    <tr><td><strong>Status:</strong></td><td><span class="badge badge-${livestock.status === 'active' ? 'success' : 'danger'}">${livestock.status}</span></td></tr>
                                    <tr><td><strong>Health Status:</strong></td><td>${livestock.health_status || 'N/A'}</td></tr>
                                    <tr><td><strong>Weight:</strong></td><td>${livestock.weight || 'N/A'}</td></tr>
                                    <tr><td><strong>Registration Date:</strong></td><td>${livestock.created_at}</td></tr>
                                </table>
                            </div>
                        </div>
                        ${livestock.description ? `
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="text-primary">Description</h6>
                                <p>${livestock.description}</p>
                            </div>
                        </div>
                        ` : ''}
                    `);
                    $('#livestockDetailsModal').modal('show');
                } else {
                    showNotification('Error loading livestock details', 'danger');
                }
            },
            error: function() {
                showNotification('Error loading livestock details', 'danger');
            }
        });
    }

    function generateQRCode(livestockId) {
        $.ajax({
            url: `{{ route("admin.livestock.qr-code", ":id") }}`.replace(':id', livestockId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#qrCodeContent').html(`<img src="${response.qr_code}" alt="QR Code" class="img-fluid">`);
                    $('#qrCodeText').text(`QR Code for ${response.livestock_id}`);
                    $('#qrCodeModal').modal('show');
                } else {
                    showNotification('Error generating QR code', 'danger');
                }
            },
            error: function() {
                showNotification('Error generating QR code', 'danger');
            }
        });
    }

    function downloadQRCode() {
        const img = $('#qrCodeContent img');
        const link = document.createElement('a');
        link.download = 'livestock_qr_code.png';
        link.href = img.attr('src');
        link.click();
    }

    function issueAlert(livestockId) {
        $('#alertLivestockId').val(livestockId);
        $('#issueAlertModal').modal('show');
    }

    $('#issueAlertForm').on('submit', function(e) {
        e.preventDefault();
        
        const livestockId = $('#alertLivestockId').val();
        const issueType = $('#issueType').val();
        const priority = $('#issuePriority').val();
        const description = $('#issueDescription').val();

        $.ajax({
            url: '{{ route("admin.livestock.issue-alert") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                livestock_id: livestockId,
                issue_type: issueType,
                priority: priority,
                description: description
            },
            success: function(response) {
                if (response.success) {
                    $('#issueAlertModal').modal('hide');
                    // Reset form
                    $('#issueType').val('');
                    $('#issuePriority').val('');
                    $('#issueDescription').val('');
                    
                    showNotification('Issue alert created successfully!', 'success');
                } else {
                    showNotification(response.message || 'Error creating issue alert', 'danger');
                }
            },
            error: function() {
                showNotification('Error creating issue alert', 'danger');
            }
        });
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
</script>
@endpush
