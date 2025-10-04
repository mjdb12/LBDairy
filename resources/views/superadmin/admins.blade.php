@extends('layouts.app')

@section('title', 'LBDAIRY: SuperAdmin - Manage Admins')

@push('styles')
<style>#detailsModal .modal-content {
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
    
    #detailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #detailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #detailsModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
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

    /* Custom styles for admin management */
    .border-left-primary {
        border-left: 0.25rem solid #18375d !important;
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
    
    .btn-group .btn {
        margin-right: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .gap-2 {
        gap: 0.5rem !important;
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
    
    /* Force text colors to be correct */
    .text-primary {
        color: #18375d !important;
    }
    
    /* Ensure no blue backgrounds anywhere in stat cards */
    .card.stat-card *,
    .card.dashboard-card * {
        background-color: transparent !important;
    }
    
    .card.stat-card,
    .card.dashboard-card {
        background-color: #fff !important;
    }
    
    .fade-in {
        animation: fadeIn 0.6s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
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

    .btn-action-approve {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
    .btn-action-approve:hover {
        background-color: #2d5a47;
        border-color: #2d5a47;
        color: white;
    }
    
    .btn-action-reject {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .btn-action-reject:hover {
        background-color: #c82333;
        border-color: #c82333;
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

    .btn-action-edits {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
    .btn-action-edits:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-edit-profile {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
    .btn-action-edit-profile:hover {
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
    
    /* Table layout styling to match user directory */
    
    /* Ensure table has enough space for actions column */
    #pendingAdminsTable th:last-child,
    #pendingAdminsTable td:last-child,
    #activeAdminsTable th:last-child,
    #activeAdminsTable td:last-child {
        min-width: 200px;
        width: auto;
    }
    
    /* Table responsiveness and spacing */
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
    
    /* Fix pagination positioning for wide tables */
    .table-responsive .dataTables_wrapper .dataTables_paginate {
        position: relative;
        width: 100%;
        text-align: left;
        margin: 1rem 0;
        left: 0;
        right: 0;
    }
    
    #pendingAdminsTable,
    #activeAdminsTable {
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
    
    #pendingAdminsTable th,
    #pendingAdminsTable td,
    #activeAdminsTable th,
    #activeAdminsTable td {
        vertical-align: middle;
        padding: 0.75rem;
        text-align: center;
        border: 1px solid #dee2e6;
        white-space: nowrap;
        overflow: visible;
    }
    
    /* Ensure all table headers have consistent styling */
    #pendingAdminsTable thead th,
    #activeAdminsTable thead th {
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
    #pendingAdminsTable thead th.sorting,
    #pendingAdminsTable thead th.sorting_asc,
    #pendingAdminsTable thead th.sorting_desc,
    #activeAdminsTable thead th.sorting,
    #activeAdminsTable thead th.sorting_asc,
    #activeAdminsTable thead th.sorting_desc {
        padding-right: 2rem !important;
    }
    
    /* Ensure proper spacing for sort indicators */
    #pendingAdminsTable thead th::after,
    #activeAdminsTable thead th::after {
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
    #pendingAdminsTable thead th.sorting::after,
    #pendingAdminsTable thead th.sorting_asc::after,
    #pendingAdminsTable thead th.sorting_desc::after,
    #activeAdminsTable thead th.sorting::after,
    #activeAdminsTable thead th.sorting_asc::after,
    #activeAdminsTable thead th.sorting_desc::after {
        display: none;
    }
    
    #addAdminModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #addAdminModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #addAdminModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #addAdminModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #addAdminModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #addAdminModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #addAdminModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #addAdminModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
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

@section('content')
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
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Pending Approvals</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingCount">0</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock fa-2x" style="color: #18375d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Active Admins</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeCount">0</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-check fa-2x" style="color: #18375d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Rejected</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="rejectedCount">0</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-times fa-2x" style="color: #18375d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Admins</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalCount">0</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users-cog fa-2x" style="color: #18375d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Admins Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-warning text-white">
            <h6 class="mb-0">
                <i class="fas fa-clock"></i>
                Pending Admin Registrations
            </h6>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div class="input-group" style="max-width: 300px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search pending admins..." id="pendingSearch">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="pendingAdminsTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>User ID</th>
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
            <h6 class="mb-0">
                <i class="fas fa-user-check"></i>
                Active Admins
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
                    <input type="text" class="form-control" placeholder="Search active admins..." id="activeSearch">
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-center">
                    <button class="btn-action btn-action-edit" onclick="showAddAdminModal()">
                        <i class="fas fa-user-plus"></i> Add User
                    </button>
                    <button class="btn-action btn-action-print" onclick="printActiveAdminsTable()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button class="btn-action btn-action-refresh" onclick="refreshActiveAdmins()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportCSV('activeAdminsTable')">
                                <i class="fas fa-file-csv"></i> Download CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPDF('activeAdminsTable')">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPNG('activeAdminsTable')">
                                <i class="fas fa-image"></i> Download PNG
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="activeAdminsTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>User ID</th>
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

<!-- Reject Admin Confirmation Modal -->
<div class="modal fade" id="confirmRejectModal" tabindex="-1" role="dialog" aria-labelledby="confirmRejectLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmRejectLabel">
                    <i class="fas fa-exclamation-triangle"></i>
                    Confirm Rejection
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reject this admin registration? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmRejectBtn" class="btn btn-danger">
                    <i class="fas fa-times"></i> Reject
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
                <button type="button" class="btn-action btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn-action btn-action-ok" onclick="openContactModal()">
                    Contact Admin
                </button>
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
                    <input type="hidden" id="adminIdHidden">
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
                    <button type="button" class="btn-action btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-action btn-action-ok">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Admin Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1" role="dialog" aria-labelledby="addAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAdminModalLabel">
                    <i class="fas fa-user-plus mr-2"></i>
                    Add New Admin
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addAdminForm" onsubmit="saveNewAdmin(event)">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adminFirstName">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="adminFirstName" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adminLastName">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="adminLastName" name="last_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adminEmail">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="adminEmail" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adminUsername">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="adminUsername" name="username" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adminPhone">Contact Number<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="adminPhone" name="phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adminBarangay">Barangay<span class="text-danger">*</span></label>
                                <select class="form-control" id="adminBarangay" name="barangay">
                                    <option value="">Select Barangay</option>
                                    <option value="Abang">Abang</option>
                                    <option value="Aliliw">Aliliw</option>
                                    <option value="Atulinao">Atulinao</option>
                                    <option value="Ayuti (Poblacion)">Ayuti (Poblacion)</option>
                                    <option value="Barangay 1 (Poblacion)">Barangay 1 (Poblacion)</option>
                                    <option value="Barangay 2 (Poblacion)">Barangay 2 (Poblacion)</option>
                                    <option value="Barangay 3 (Poblacion)">Barangay 3 (Poblacion)</option>
                                    <option value="Barangay 4 (Poblacion)">Barangay 4 (Poblacion)</option>
                                    <option value="Barangay 5 (Poblacion)">Barangay 5 (Poblacion)</option>
                                    <option value="Barangay 6 (Poblacion)">Barangay 6 (Poblacion)</option>
                                    <option value="Barangay 7 (Poblacion)">Barangay 7 (Poblacion)</option>
                                    <option value="Barangay 8 (Poblacion)">Barangay 8 (Poblacion)</option>
                                    <option value="Barangay 9 (Poblacion)">Barangay 9 (Poblacion)</option>
                                    <option value="Barangay 10 (Poblacion)">Barangay 10 (Poblacion)</option>
                                    <option value="Igang">Igang</option>
                                    <option value="Kabatete">Kabatete</option>
                                    <option value="Kakawit">Kakawit</option>
                                    <option value="Kalangay">Kalangay</option>
                                    <option value="Kalyaat">Kalyaat</option>
                                    <option value="Kilib">Kilib</option>
                                    <option value="Kulapi">Kulapi</option>
                                    <option value="Mahabang Parang">Mahabang Parang</option>
                                    <option value="Malupak">Malupak</option>
                                    <option value="Manasa">Manasa</option>
                                    <option value="May-It">May-It</option>
                                    <option value="Nagsinamo">Nagsinamo</option>
                                    <option value="Nalunao">Nalunao</option>
                                    <option value="Palola">Palola</option>
                                    <option value="Piis">Piis</option>
                                    <option value="Samil">Samil</option>
                                    <option value="Tiawe">Tiawe</option>
                                    <option value="Tinamnan">Tinamnan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adminRole">Role <span class="text-danger">*</span></label>
                                <select class="form-control" id="adminRole" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="admin" selected>Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adminStatus">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="adminStatus" name="status" required>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="passwordFields">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adminPasswordConfirm">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="adminPassword" name="password" required>
                                <small class="form-text text-muted">Leave blank to keep existing password when editing</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="passwordConfirmation">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="adminPasswordConfirm" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>
                    <div id="adminFormNotification" class="mt-2" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-action btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-action btn-action-save" id="saveAdminBtn">
                        Save User
                    </button>
            </form>
        </div>
    </div>
</div>

<!-- Edit Admin Modal -->
<div class="modal fade" id="editAdminModal" tabindex="-1" role="dialog" aria-labelledby="editAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAdminModalLabel">
                    <i class="fas fa-edit"></i>
                    Edit Admin
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editAdminForm">
                <div class="modal-body">
                    <input type="hidden" id="editAdminId" name="admin_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editFirstName">First Name *</label>
                                <input type="text" class="form-control" id="editFirstName" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editLastName">Last Name *</label>
                                <input type="text" class="form-control" id="editLastName" name="last_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editEmail">Email *</label>
                                <input type="email" class="form-control" id="editEmail" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editUsername">Username *</label>
                                <input type="text" class="form-control" id="editUsername" name="username" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editPhone">Contact Number</label>
                                <input type="text" class="form-control" id="editPhone" name="phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editBarangay">Barangay *</label>
                                <select class="form-control" id="editBarangay" name="barangay" required>
                                    <option value="">Select Barangay</option>
                                    <option value="Alupay">Alupay</option>
                                    <option value="Ayaas">Ayaas</option>
                                    <option value="Bagong Pook">Bagong Pook</option>
                                    <option value="Bukal">Bukal</option>
                                    <option value="Calumpang">Calumpang</option>
                                    <option value="Candelaria">Candelaria</option>
                                    <option value="Castañas">Castañas</option>
                                    <option value="Ibabang Dupay">Ibabang Dupay</option>
                                    <option value="Ibabang Ilog">Ibabang Ilog</option>
                                    <option value="Ibabang San Isidro">Ibabang San Isidro</option>
                                    <option value="Ilayang Dupay">Ilayang Dupay</option>
                                    <option value="Ilayang Ilog">Ilayang Ilog</option>
                                    <option value="Ilayang San Isidro">Ilayang San Isidro</option>
                                    <option value="Igang">Igang</option>
                                    <option value="Kabatete">Kabatete</option>
                                    <option value="Kakawit">Kakawit</option>
                                    <option value="Kalangay">Kalangay</option>
                                    <option value="Kalyaat">Kalyaat</option>
                                    <option value="Kilib">Kilib</option>
                                    <option value="Kulapi">Kulapi</option>
                                    <option value="Mahabang Parang">Mahabang Parang</option>
                                    <option value="Malupak">Malupak</option>
                                    <option value="Manasa">Manasa</option>
                                    <option value="May-It">May-It</option>
                                    <option value="Nagsinamo">Nagsinamo</option>
                                    <option value="Nalunao">Nalunao</option>
                                    <option value="Palola">Palola</option>
                                    <option value="Piis">Piis</option>
                                    <option value="Samil">Samil</option>
                                    <option value="Tiawe">Tiawe</option>
                                    <option value="Tinamnan">Tinamnan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editRole">Role *</label>
                                <select class="form-control" id="editRole" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editPassword">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="editPassword" name="password">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('editPassword')">
                                            <i class="fas fa-eye" id="editPasswordIcon"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Leave blank to keep current password</small>
                            </div>
                        </div>
                    </div>
                    <div id="editAdminFormNotification" class="mt-2" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="updateAdminBtn">
                        <i class="fas fa-save"></i> Update Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteAdminModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteAdminLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteAdminLabel">
                    <i class="fas fa-exclamation-triangle"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this admin? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteAdminBtn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Yes, Delete
                </button>
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
// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + 'Icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

let pendingAdminsTable;
let activeAdminsTable;
let farmerToDelete = null;
let adminToDelete = null;

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

// Add event listener for confirm delete admin button
document.getElementById('confirmDeleteAdminBtn').addEventListener('click', function() {
    if (adminToDelete) {
        deleteAdmin(adminToDelete);
        adminToDelete = null;
        $('#confirmDeleteAdminModal').modal('hide');
    }
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
        columnDefs: [
            { width: '80px', targets: 0 }, // User ID
            { width: '180px', targets: 1 }, // Name
            { width: '140px', targets: 2 }, // Barangay
            { width: '140px', targets: 3 }, // Contact
            { width: '220px', targets: 4 }, // Email
            { width: '140px', targets: 5 }, // Username
            { width: '140px', targets: 6 }, // Registration Date
            { width: '220px', targets: 7, className: 'text-left' }  // Actions
        ],
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
        columnDefs: [
            { width: '80px', targets: 0 }, // User ID
            { width: '180px', targets: 1 }, // Name
            { width: '140px', targets: 2 }, // Barangay
            { width: '140px', targets: 3 }, // Contact
            { width: '220px', targets: 4 }, // Email
            { width: '140px', targets: 5 }, // Username
            { width: '140px', targets: 6 }, // Approval Date
            { width: '160px', targets: 7 }, // Last Login
            { width: '220px', targets: 8, className: 'text-left' }  // Actions
        ],
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
                        `<a href="#" class="user-id-link" onclick="showAdminDetails('${admin.id}')">${admin.id}</a>`,
                        `${admin.first_name || ''} ${admin.last_name || ''}`,
                        admin.barangay || '',
                        admin.phone || '',
                        admin.email || '',
                        admin.username || '',
                        admin.created_at ? new Date(admin.created_at).toLocaleDateString() : '',
                        `<div class="action-buttons">
                            <button class="btn-action btn-action-edit" onclick="approveAdmin('${admin.id}')" title="Approve">
                                <i class="fas fa-check"></i>
                                <span>Approve</span>
                            </button>
                            <button class="btn-action btn-action-delete" onclick="rejectAdmin('${admin.id}')" title="Reject">
                                <i class="fas fa-times"></i>
                                <span>Reject</span>
                            </button>
                        </div>`
                    ];
                    
                    console.log(`Row data for admin ${index}:`, rowData);
                    pendingAdminsTable.row.add(rowData).draw(false);
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
                        `<a href="#" class="user-id-link" onclick="showAdminDetails('${admin.id}')">${admin.id}</a>`,
                        `${admin.first_name || ''} ${admin.last_name || ''}`,
                        admin.barangay || '',
                        admin.phone || '',
                        admin.email || '',
                        admin.username || '',
                        admin.created_at ? new Date(admin.created_at).toLocaleDateString() : '',
                        admin.last_login_at ? new Date(admin.last_login_at).toLocaleDateString() : 'Never',
                        `<div class="action-buttons">
                            <button class="btn-action btn-action-edit" onclick="editAdmin('${admin.id}')" title="Edit">
                                <i class="fas fa-edit"></i>
                                <span>Edit</span>
                            </button>
                            <button class="btn-action btn-action-deletes" onclick="confirmDeleteAdmin('${admin.id}')" title="Delete">
                                <i class="fas fa-trash"></i>
                                <span>Delete</span>
                            </button>
                        </div>`
                    ];
                    
                    console.log(`Row data for active admin ${index}:`, rowData);
                    activeAdminsTable.row.add(rowData).draw(false);
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

function getRoleBadgeClass(role) {
    switch(role) {
        case 'superadmin': return 'primary';
        case 'admin': return 'warning';
        case 'farmer': return 'success';
        default: return 'secondary badge-pill';
    }
}

function getStatusBadgeClass(status) {
    switch(status) {
        case 'approved': return 'success';
        case 'pending': return 'warning';
        case 'rejected': return 'danger';
        default: return 'secondary';
    }
}

function formatLastLogin(lastLoginAt) {
    if (!lastLoginAt) return 'Never';
    
    const loginDate = new Date(lastLoginAt);
    const now = new Date();
    const diffInHours = Math.floor((now - loginDate) / (1000 * 60 * 60));
    const diffInDays = Math.floor(diffInHours / 24);
    
    if (diffInDays > 0) {
        return `${diffInDays} day${diffInDays > 1 ? 's' : ''} ago`;
    } else if (diffInHours > 0) {
        return `${diffInHours} hour${diffInHours > 1 ? 's' : ''} ago`;
    } else {
        const diffInMinutes = Math.floor((now - loginDate) / (1000 * 60));
        if (diffInMinutes > 0) {
            return `${diffInMinutes} minute${diffInMinutes > 1 ? 's' : ''} ago`;
        } else {
            return 'Just now';
        }
    }
}

function showAdminDetails(adminId) {
    // Load admin details via AJAX
    $.ajax({
        url: `{{ route("superadmin.admins.show", ":id") }}`.replace(':id', adminId),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const admin = response.data;
                const displayName = admin.first_name && admin.last_name 
                    ? `${admin.first_name} ${admin.last_name}` 
                    : admin.name || 'N/A';
                
                const details = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Personal Information</h6>
                            <p><strong>Full Name:</strong> ${displayName}</p>
                            <p><strong>Email:</strong> ${admin.email}</p>
                            <p><strong>Username:</strong> ${admin.username}</p>
                            <p><strong>Contact Number:</strong> ${admin.phone || 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Account Information</h6>
                            <p><strong>Role:</strong> <span class="badge badge-${getRoleBadgeClass(admin.role)}">${admin.role}</span></p>
                            <p><strong>Status:</strong> <span class="badge badge-${getStatusBadgeClass(admin.status)}">${admin.status}</span></p>
                            <p><strong>Barangay:</strong> ${admin.barangay || 'N/A'}</p>
                            <p><strong>Registration Date:</strong> ${new Date(admin.created_at).toLocaleDateString()}</p>
                            <p><strong>Last Login:</strong> ${admin.last_login_at ? formatLastLogin(admin.last_login_at) : 'Never'}${admin.is_online ? ' <span class="badge badge-success badge-sm" title="Currently Online"><i class="fas fa-circle"></i> Online</span>' : ''}</p>
                        </div>
                    </div>
                `;

                document.getElementById('farmerDetails').innerHTML = details;
                document.getElementById('farmerNameHidden').value = displayName;
                document.getElementById('adminIdHidden').value = admin.id;
                $('#detailsModal').modal('show');
            } else {
                showNotification('Error loading admin details', 'danger');
            }
        },
        error: function(xhr) {
            console.error('Error loading admin details:', xhr);
            showNotification('Error loading admin details', 'danger');
        }
    });
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
    // Store the admin ID for the modal handler
    window.adminToReject = adminId;
    $('#confirmRejectModal').modal('show');
}

function performRejection() {
    if (!window.adminToReject) return;

    $.ajax({
        url: `{{ route("superadmin.admins.reject", ":id") }}`.replace(':id', window.adminToReject),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            loadPendingAdmins();
            updateStats();
            showNotification('Admin registration rejected.', 'warning');
            $('#confirmRejectModal').modal('hide');
            window.adminToReject = null;
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
    const adminId = document.getElementById('adminIdHidden').value;
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
            admin_id: adminId,
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

// Download counter for file naming
let activeAdminsDownloadCounter = 1;

// Export functions for Active Admins (matching user directory style)
function exportCSV(tableId) {
    if (tableId === 'activeAdminsTable') {
        try {
            // Get current table data without actions column
            const tableData = activeAdminsTable.data().toArray();
            
            if (!tableData || tableData.length === 0) {
                showNotification('No data available to export', 'warning');
                return;
            }
            
            // Create CSV content manually
            let csvContent = "data:text/csv;charset=utf-8,";
            
            // Add headers (excluding Actions column)
            const headers = ['User ID', 'Name', 'Barangay', 'Contact', 'Email', 'Username', 'Approval Date', 'Last Login'];
            csvContent += headers.join(',') + '\n';
            
            // Add data rows (excluding Actions column)
            tableData.forEach(row => {
                const csvRow = [];
                for (let i = 0; i < row.length - 1; i++) { // Skip last column (Actions)
                    let cellText = '';
                    if (row[i]) {
                        // Remove HTML tags and get clean text
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = row[i];
                        cellText = tempDiv.textContent || tempDiv.innerText || '';
                        // Clean up the text and escape quotes
                        cellText = cellText.replace(/\s+/g, ' ').trim().replace(/"/g, '""');
                    }
                    csvRow.push('"' + cellText + '"');
                }
                csvContent += csvRow.join(',') + '\n';
            });
            
            // Create download link
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `SuperAdmin_ActiveAdminsReport_${activeAdminsDownloadCounter}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            activeAdminsDownloadCounter++;
            showNotification('CSV export completed successfully', 'success');
            
        } catch (error) {
            console.error('Error exporting CSV:', error);
            showNotification('Error exporting CSV. Please try again.', 'danger');
        }
    } else {
        // For pending admins, use simple DataTables export
        const table = tableId === 'pendingAdminsTable' ? pendingAdminsTable : activeAdminsTable;
        table.button('.buttons-csv').trigger();
    }
}

function exportPDF(tableId) {
    if (tableId === 'activeAdminsTable') {
        try {
            // Get current table data without actions column
            const tableData = activeAdminsTable.data().toArray();
            
            if (!tableData || tableData.length === 0) {
                showNotification('No data available to export', 'warning');
                return;
            }
            
            // Check if jsPDF is available
            if (typeof window.jsPDF === 'undefined') {
                console.warn('jsPDF not available, falling back to DataTables PDF export');
                activeAdminsTable.button('.buttons-pdf').trigger();
                return;
            }
            
            const { jsPDF } = window.jsPDF;
            const doc = new jsPDF('landscape');
            
            // Add title
            doc.setFontSize(18);
            doc.setFont(undefined, 'bold');
            doc.text('SuperAdmin Active Admins Report', 148, 20, { align: 'center' });
            
            doc.setFontSize(12);
            doc.setFont(undefined, 'normal');
            doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 148, 30, { align: 'center' });
            
            // Prepare table data
            const headers = ['User ID', 'Name', 'Barangay', 'Contact', 'Email', 'Username', 'Approval Date', 'Last Login'];
            const pdfData = [];
            
            tableData.forEach(row => {
                const pdfRow = [];
                for (let i = 0; i < row.length - 1; i++) { // Skip last column (Actions)
                    let cellText = '';
                    if (row[i]) {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = row[i];
                        cellText = tempDiv.textContent || tempDiv.innerText || '';
                        cellText = cellText.replace(/\s+/g, ' ').trim();
                    }
                    pdfRow.push(cellText);
                }
                pdfData.push(pdfRow);
            });
            
            // Add table
            doc.autoTable({
                head: [headers],
                body: pdfData,
                startY: 40,
                styles: { fontSize: 8, cellPadding: 2 },
                headStyles: { fillColor: [24, 55, 93], textColor: 255 },
                columnStyles: {
                    0: { cellWidth: 25 }, // Name
                    1: { cellWidth: 25 }, // Barangay  
                    2: { cellWidth: 25 }, // Contact
                    3: { cellWidth: 40 }, // Email
                    4: { cellWidth: 25 }, // Username
                    5: { cellWidth: 30 }, // Approval Date
                    6: { cellWidth: 30 }  // Last Login
                }
            });
            
            // Save the PDF
            doc.save(`SuperAdmin_ActiveAdminsReport_${activeAdminsDownloadCounter}.pdf`);
            activeAdminsDownloadCounter++;
            showNotification('PDF export completed successfully', 'success');
            
        } catch (error) {
            console.error('Error exporting PDF:', error);
            showNotification('Error exporting PDF. Please try again.', 'danger');
            
            // Fallback to DataTables PDF export
            try {
                activeAdminsTable.button('.buttons-pdf').trigger();
            } catch (fallbackError) {
                console.error('Fallback PDF export also failed:', fallbackError);
            }
        }
    } else {
        // For pending admins, use simple DataTables export
        const table = tableId === 'pendingAdminsTable' ? pendingAdminsTable : activeAdminsTable;
        table.button('.buttons-pdf').trigger();
    }
}

function exportPNG(tableId) {
    if (tableId === 'activeAdminsTable') {
        try {
            // Create a temporary table without actions column for PNG export
            const tableData = activeAdminsTable.data().toArray();
            
            if (!tableData || tableData.length === 0) {
                showNotification('No data available to export', 'warning');
                return;
            }
            
            // Create temporary container
            const tempContainer = document.createElement('div');
            tempContainer.style.position = 'absolute';
            tempContainer.style.left = '-9999px';
            tempContainer.style.top = '-9999px';
            tempContainer.style.background = 'white';
            tempContainer.style.padding = '20px';
            
            // Create content for PNG
            let pngContent = `
                <div style="font-family: Arial, sans-serif;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h1 style="color: #18375d; margin-bottom: 5px;">SuperAdmin Active Admins Report</h1>
                        <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                    </div>
                    <table style="border-collapse: collapse; width: 100%; border: 2px solid #000;">
                        <thead>
                            <tr style="background-color: #f2f2f2;">
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">User ID</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">Name</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">Barangay</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">Contact</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">Email</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">Username</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">Approval Date</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">Last Login</th>
                            </tr>
                        </thead>
                        <tbody>`;
            
            // Add data rows (excluding Actions column)
            tableData.forEach(row => {
                pngContent += '<tr>';
                for (let i = 0; i < row.length - 1; i++) { // Skip last column (Actions)
                    let cellText = '';
                    if (row[i]) {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = row[i];
                        cellText = tempDiv.textContent || tempDiv.innerText || '';
                        cellText = cellText.replace(/\s+/g, ' ').trim();
                    }
                    pngContent += `<td style="border: 1px solid #000; padding: 8px;">${cellText}</td>`;
                }
                pngContent += '</tr>';
            });
            
            pngContent += `
                        </tbody>
                    </table>
                </div>`;
            
            tempContainer.innerHTML = pngContent;
            document.body.appendChild(tempContainer);
            
            // Use html2canvas to capture the content
            html2canvas(tempContainer, {
                backgroundColor: 'white',
                scale: 2,
                useCORS: true
            }).then(canvas => {
                // Create download link
                const link = document.createElement('a');
                link.download = `SuperAdmin_ActiveAdminsReport_${activeAdminsDownloadCounter}.png`;
                link.href = canvas.toDataURL("image/png");
                link.click();
                
                // Clean up
                document.body.removeChild(tempContainer);
                activeAdminsDownloadCounter++;
                showNotification('PNG export completed successfully', 'success');
            }).catch(error => {
                console.error('Error generating PNG:', error);
                document.body.removeChild(tempContainer);
                showNotification('Error exporting PNG. Please try again.', 'danger');
            });
            
        } catch (error) {
            console.error('Error exporting PNG:', error);
            showNotification('Error exporting PNG. Please try again.', 'danger');
        }
    } else {
        // For pending admins, use simple HTML2Canvas export
        const tableElement = document.getElementById(tableId);
        html2canvas(tableElement).then(canvas => {
            let link = document.createElement('a');
            link.download = `${tableId}_Report.png`;
            link.href = canvas.toDataURL("image/png");
            link.click();
        });
    }
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

// Reject modal event handler
document.getElementById('confirmRejectBtn').addEventListener('click', function() {
    performRejection();
});

function deleteFarmer(button) {
    let row = button.closest('tr');
    row.remove();
}

// Header action button functions
function showAddAdminModal() {
    // Clear the form
    document.getElementById('addAdminForm').reset();
    document.getElementById('adminFormNotification').style.display = 'none';
    
    // Show the modal
    $('#addAdminModal').modal('show');
}

function refreshActiveAdmins() {
    // Show loading indicator
    const refreshBtn = document.querySelector('.btn-action-refresh');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    refreshBtn.disabled = true;
    
    // Refresh active admins data
    loadActiveAdmins();
    loadPendingAdmins(); // Also refresh pending admins
    updateStats();
    
    // Re-enable button after a short delay
    setTimeout(() => {
        refreshBtn.innerHTML = originalText;
        refreshBtn.disabled = false;
        showNotification('Active admins data refreshed successfully!', 'success');
    }, 1000);
}

function printActiveAdminsTable() {
    try {
        // Get current table data without actions column
        const tableData = activeAdminsTable.data().toArray();
        
        if (!tableData || tableData.length === 0) {
            showNotification('No data available to print', 'warning');
            return;
        }
        
        // Create print content directly in current page
        const originalContent = document.body.innerHTML;
        
        let printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #18375d; margin-bottom: 5px;">SuperAdmin Active Admins Report</h1>
                    <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                <table border="3" style="border-collapse: collapse; width: 100%; border: 3px solid #000;">
                    <thead>
                        <tr>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">User ID</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Name</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Barangay</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Contact</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Email</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Username</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Approval Date</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Last Login</th>
                        </tr>
                    </thead>
                    <tbody>`;
        
        // Add data rows (excluding Actions column)
        tableData.forEach(row => {
            printContent += '<tr>';
            for (let i = 0; i < row.length - 1; i++) { // Skip last column (Actions)
                let cellText = '';
                if (row[i]) {
                    // Remove HTML tags and get clean text
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = row[i];
                    cellText = tempDiv.textContent || tempDiv.innerText || '';
                    // Clean up the text
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
        
        // Replace page content with print content
        document.body.innerHTML = printContent;
        
        // Print the page
        window.print();
        
        // Restore original content after print dialog closes
        setTimeout(() => {
            document.body.innerHTML = originalContent;
            // Re-initialize any JavaScript that might be needed
            location.reload(); // Reload to restore full functionality
        }, 100);
        
    } catch (error) {
        console.error('Error in print function:', error);
        showNotification('Error generating print. Please try again.', 'danger');
        
        // Fallback to DataTables print
        try {
            activeAdminsTable.button('.buttons-print').trigger();
        } catch (fallbackError) {
            console.error('Fallback print also failed:', fallbackError);
            showNotification('Print failed. Please try again.', 'danger');
        }
    }
}

function saveNewAdmin(event) {
    event.preventDefault();
    
    const form = document.getElementById('addAdminForm');
    const submitBtn = document.getElementById('saveAdminBtn');
    const notification = document.getElementById('adminFormNotification');
    
    // Validate password confirmation
    const password = document.getElementById('adminPassword').value;
    const confirmPassword = document.getElementById('adminPasswordConfirm').value;
    
    if (password !== confirmPassword) {
        notification.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                Passwords do not match. Please try again.
            </div>
        `;
        notification.style.display = 'block';
        return;
    }
    
    // Disable submit button
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding Admin...';
    
    // Collect form data
    const formData = {
        first_name: document.getElementById('adminFirstName').value,
        last_name: document.getElementById('adminLastName').value,
        email: document.getElementById('adminEmail').value,
        phone: document.getElementById('adminPhone').value,
        username: document.getElementById('adminUsername').value,
        barangay: document.getElementById('adminBarangay').value,
        password: password,
        password_confirmation: confirmPassword,
        status: document.getElementById('adminStatus').value,
        role: 'admin',
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    
    // Submit via AJAX
    $.ajax({
        url: '{{ route("superadmin.admins.store") }}',
        method: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                // Close modal
                $('#addAdminModal').modal('hide');
                
                // Refresh data
                loadPendingAdmins();
                loadActiveAdmins();
                updateStats();
                
                // Show success notification
                showNotification('Admin added successfully!', 'success');
            } else {
                notification.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        ${response.message || 'Error adding admin. Please try again.'}
                    </div>
                `;
                notification.style.display = 'block';
            }
        },
        error: function(xhr) {
            let errorMessage = 'Error adding admin. Please try again.';
            
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                errorMessage = Object.values(errors).flat().join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            notification.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    ${errorMessage}
                </div>
            `;
            notification.style.display = 'block';
        },
        complete: function() {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-user-plus"></i> Add Admin';
        }
    });
}

// Edit and Delete Admin Functions
function editAdmin(adminId) {
    // Load admin data via AJAX
    $.ajax({
        url: `{{ route("superadmin.admins.show", ":id") }}`.replace(':id', adminId),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const admin = response.data;
                populateEditAdminForm(admin);
                $('#editAdminModal').modal('show');
            } else {
                showNotification('Error loading admin data', 'danger');
            }
        },
        error: function(xhr) {
            console.error('Error loading admin data:', xhr);
            showNotification('Error loading admin data', 'danger');
        }
    });
}

function populateEditAdminForm(admin) {
    document.getElementById('editAdminId').value = admin.id;
    document.getElementById('editFirstName').value = admin.first_name || '';
    document.getElementById('editLastName').value = admin.last_name || '';
    document.getElementById('editEmail').value = admin.email || '';
    document.getElementById('editUsername').value = admin.username || '';
    document.getElementById('editPhone').value = admin.phone || '';
    document.getElementById('editBarangay').value = admin.barangay || '';
    document.getElementById('editRole').value = admin.role || '';
    document.getElementById('editPassword').value = '';
    
    // Clear any previous notifications
    document.getElementById('editAdminFormNotification').style.display = 'none';
}
function confirmDelete(userId) {
    userToDelete = userId;
    $('#confirmDeleteModal').modal('show');
}

function deleteUser(userId) {
    $.ajax({
        url: `{{ route("superadmin.users.destroy", ":id") }}`.replace(':id', userId),
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            loadUsers();
            updateStats();
            showNotification('User deleted successfully', 'success');
        },
        error: function(xhr) {
            showNotification('Error deleting user', 'danger');
        }
    });
}

function confirmDeleteAdmin(adminId) {
    adminToDelete = adminId;
    $('#confirmDeleteAdminModal').modal('show');
}

function deleteAdmin(adminId) {
    $.ajax({
        url: `{{ route("superadmin.admins.destroy", ":id") }}`.replace(':id', adminId),
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            loadActiveAdmins();
            updateStats();
            showNotification('Admin deleted successfully', 'success');
        },
        error: function(xhr) {
            showNotification('Error deleting admin', 'danger');
        }
    });
}

// Handle edit admin form submission
document.getElementById('editAdminForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const form = document.getElementById('editAdminForm');
    const submitBtn = document.getElementById('updateAdminBtn');
    const notification = document.getElementById('editAdminFormNotification');
    
    // Disable submit button
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    
    // Collect form data
    const formData = {
        admin_id: document.getElementById('editAdminId').value,
        first_name: document.getElementById('editFirstName').value,
        last_name: document.getElementById('editLastName').value,
        email: document.getElementById('editEmail').value,
        phone: document.getElementById('editPhone').value,
        username: document.getElementById('editUsername').value,
        barangay: document.getElementById('editBarangay').value,
        role: document.getElementById('editRole').value,
        password: document.getElementById('editPassword').value,
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    
    // Submit via AJAX
    $.ajax({
        url: `{{ route("superadmin.admins.update", ":id") }}`.replace(':id', formData.admin_id),
        method: 'PUT',
        data: formData,
        success: function(response) {
            if (response.success) {
                // Close modal
                $('#editAdminModal').modal('hide');
                
                // Refresh data
                loadActiveAdmins();
                updateStats();
                
                // Show success notification
                showNotification('Admin updated successfully!', 'success');
            } else {
                notification.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        ${response.message || 'Error updating admin. Please try again.'}
                    </div>
                `;
                notification.style.display = 'block';
            }
        },
        error: function(xhr) {
            let errorMessage = 'Error updating admin. Please try again.';
            
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                errorMessage = Object.values(errors).flat().join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            notification.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    ${errorMessage}
                </div>
            `;
            notification.style.display = 'block';
        },
        complete: function() {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Admin';
        }
    });
});
</script>
@endpush

