@extends('layouts.app')

@section('title', 'LBDAIRY: SuperAdmin - Manage Farmers')

@push('styles')
<style>
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
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Farmers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalFarmers }}</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users fa-2x" style="color: #18375d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Active Farmers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeFarmers }}</div>
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
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Pending Farmers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingFarmers }}</div>
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
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Farms</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalFarms }}</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tractor fa-2x" style="color: #18375d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Farmers Directory -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-success text-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h6 class="mb-0 mb-md-0">
                    <i class="fas fa-users"></i>
                    Farmers Directory
                </h6>
                <div class="d-flex flex-column flex-sm-row align-items-center gap-2 mt-2 mt-md-0">
                    <button class="btn-action btn-action-add" onclick="showAddUserModal()">
                        <i class="fas fa-user-plus"></i> Add User
                    </button>
                    <button class="btn-action btn-action-print" onclick="printFarmersTable()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button class="btn-action btn-action-refresh" onclick="refreshFarmers()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportCSV('farmersTable')">
                                <i class="fas fa-file-csv"></i> Download CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPDF('farmersTable')">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPNG('farmersTable')">
                                <i class="fas fa-image"></i> Download PNG
                            </a>
                        </div>
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
                    <input type="text" class="form-control" placeholder="Search active farmers..." id="farmersSearch">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="farmersTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Barangay</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($farmers as $farmer)
                        <tr>
                            <td><a href="#" class="user-id-link" onclick="showFarmerDetails({{ $farmer->id }})" title="Click to view details">{{ $farmer->id }}</a></td>
                            <td>{{ $farmer->name }}</td>
                            <td>{{ $farmer->barangay }}</td>
                            <td>{{ $farmer->phone }}</td>
                            <td>{{ $farmer->email }}</td>
                            <td>{{ $farmer->username ?? 'N/A' }}</td>
                            <td>
                                @if($farmer->status == 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif($farmer->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($farmer->status == 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($farmer->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $farmer->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-action-edit" onclick="editFarmer({{ $farmer->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                        <span>Edit</span>
                                    </button>
                                                        <button class="btn-action btn-action-delete" onclick="confirmDeleteFarmer({{ $farmer->id }})" title="Delete">
                        <i class="fas fa-trash"></i>
                        <span>Delete</span>
                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addUserModalLabel">
                    <i class="fas fa-user-plus"></i> Add New User
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addUserForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userName" class="font-weight-bold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="userName" name="name" placeholder="Enter full name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userEmail" class="font-weight-bold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="userEmail" name="email" placeholder="Enter email address" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userPhone" class="font-weight-bold">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="userPhone" name="phone" placeholder="Enter phone number" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userBarangay" class="font-weight-bold">Barangay <span class="text-danger">*</span></label>
                                <select class="form-control" id="userBarangay" name="barangay" required>
                                    <option value="">Select Barangay</option>
                                    <option value="Barangay 1">Barangay 1</option>
                                    <option value="Barangay 2">Barangay 2</option>
                                    <option value="Barangay 3">Barangay 3</option>
                                    <option value="Barangay 4">Barangay 4</option>
                                    <option value="Barangay 5">Barangay 5</option>
                                    <option value="Barangay 6">Barangay 6</option>
                                    <option value="Barangay 7">Barangay 7</option>
                                    <option value="Barangay 8">Barangay 8</option>
                                    <option value="Barangay 9">Barangay 9</option>
                                    <option value="Barangay 10">Barangay 10</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userUsername" class="font-weight-bold">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="userUsername" name="username" placeholder="Enter username" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userStatus" class="font-weight-bold">Account Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="userStatus" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userPassword" class="font-weight-bold">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="userPassword" name="password" placeholder="Enter password" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('userPassword')">
                                            <i class="fas fa-eye" id="userPasswordIcon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userConfirmPassword" class="font-weight-bold">Confirm Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="userConfirmPassword" name="confirm_password" placeholder="Confirm password" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('userConfirmPassword')">
                                            <i class="fas fa-eye" id="userConfirmPasswordIcon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                                         <button type="submit" class="btn btn-primary">
                         <i class="fas fa-save"></i> Save User
                     </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Farmer Details Modal -->
<div class="modal fade" id="farmerDetailsModal" tabindex="-1" role="dialog" aria-labelledby="farmerDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="farmerDetailsModalLabel">
                    <i class="fas fa-user"></i>
                    Farmer Details
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="farmerDetailsContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Farmer Modal -->
<div class="modal fade" id="editFarmerModal" tabindex="-1" role="dialog" aria-labelledby="editFarmerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFarmerModalLabel">
                    <i class="fas fa-edit"></i>
                    Edit Farmer
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editFarmerForm">
                <div class="modal-body">
                    <input type="hidden" id="editFarmerId" name="farmer_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editFarmerName">Full Name *</label>
                                <input type="text" class="form-control" id="editFarmerName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editFarmerEmail">Email *</label>
                                <input type="email" class="form-control" id="editFarmerEmail" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editFarmerPhone">Contact Number</label>
                                <input type="text" class="form-control" id="editFarmerPhone" name="phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editFarmerUsername">Username *</label>
                                <input type="text" class="form-control" id="editFarmerUsername" name="username" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editFarmerBarangay">Barangay *</label>
                                <select class="form-control" id="editFarmerBarangay" name="barangay" required>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editFarmerStatus">Status *</label>
                                <select class="form-control" id="editFarmerStatus" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editFarmerPassword">New Password (leave blank to keep current)</label>
                                <input type="password" class="form-control" id="editFarmerPassword" name="password">
                            </div>
                        </div>
                    </div>
                    <div id="editFarmerFormNotification" class="mt-2" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="updateFarmerBtn">
                        <i class="fas fa-save"></i> Update Farmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteFarmerModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteFarmerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteFarmerLabel">
                    <i class="fas fa-exclamation-triangle"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this farmer? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteFarmerBtn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Required libraries for PDF/Excel (System-wide standard format) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
// Libraries loaded, ready to use
$(document).ready(function() {
    // Initialize DataTable with enhanced configuration
    const table = $('#farmersTable').DataTable({
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
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        drawCallback: function(settings) {
            // Force pagination left after each draw
            setTimeout(forcePaginationLeft, 10);
        },
        initComplete: function(settings, json) {
            // Force pagination left after initialization
            setTimeout(forcePaginationLeft, 100);
        }
    });
    
    // Force pagination on table redraw
    table.on('draw.dt', function() {
        setTimeout(forcePaginationLeft, 10);
    });
    
    // Hide default DataTables search bar
    $('.dataTables_filter').hide();
    
    // Multiple attempts to ensure pagination stays left
    setTimeout(forcePaginationLeft, 200);
    setTimeout(forcePaginationLeft, 500);
    setTimeout(forcePaginationLeft, 1000);
    
    // Custom search functionality for Farmers Directory
    $('#farmersSearch').on('keyup', function() {
        table.search(this.value).draw();
    });
    
    // Monitor for any DOM changes that might affect pagination
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' && 
                mutation.target.className && 
                mutation.target.className.includes('dataTables')) {
                setTimeout(forcePaginationLeft, 50);
            }
        });
    });
    
    // Observe the DataTables wrapper
    const wrapper = document.querySelector('.dataTables_wrapper');
    if (wrapper) {
        observer.observe(wrapper, {
            childList: true,
            subtree: true
        });
    }
});

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
    
    // Apply darker blue color to pagination buttons
    $('.dataTables_wrapper .dataTables_paginate .paginate_button').css({
        'color': '#18375d',
        'border-color': '#18375d'
    });
    
    $('.dataTables_wrapper .dataTables_paginate .paginate_button.current').css({
        'color': '#fff',
        'background-color': '#18375d',
        'border-color': '#18375d'
    });
    
    console.log('Enhanced pagination positioning applied');
}

// Show Add User Modal
function showAddUserModal() {
    $('#addUserModal').modal('show');
}

// Edit Farmer Function
function editFarmer(farmerId) {
    // Load farmer details via AJAX
    $.ajax({
        url: `/superadmin/farmers/${farmerId}/details`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const farmer = response.data;
                populateEditFarmerForm(farmer);
                $('#editFarmerModal').modal('show');
            } else {
                alert('Error loading farmer details');
            }
        },
        error: function() {
            alert('Error loading farmer details');
        }
    });
}

// Populate Edit Farmer Form
function populateEditFarmerForm(farmer) {
    document.getElementById('editFarmerId').value = farmer.id;
    document.getElementById('editFarmerName').value = farmer.name || '';
    document.getElementById('editFarmerEmail').value = farmer.email || '';
    document.getElementById('editFarmerPhone').value = farmer.phone || '';
    document.getElementById('editFarmerUsername').value = farmer.username || '';
    document.getElementById('editFarmerBarangay').value = farmer.barangay || '';
    document.getElementById('editFarmerStatus').value = farmer.status || '';
    document.getElementById('editFarmerPassword').value = '';
    
    // Clear any previous notifications
    document.getElementById('editFarmerFormNotification').style.display = 'none';
}

// Confirm Delete Farmer Function
function confirmDeleteFarmer(farmerId) {
    farmerToDelete = farmerId;
    $('#confirmDeleteFarmerModal').modal('show');
}

// Delete Farmer Function
function deleteFarmer(farmerId) {
    $.ajax({
        url: `/superadmin/farmers/${farmerId}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Refresh the page to show updated data
                location.reload();
            } else {
                alert('Error deleting farmer: ' + (response.message || 'Unknown error'));
            }
        },
        error: function() {
            alert('Error deleting farmer');
        }
    });
}

// Add event listener for confirm delete farmer button
document.addEventListener('DOMContentLoaded', function() {
    const confirmDeleteFarmerBtn = document.getElementById('confirmDeleteFarmerBtn');
    if (confirmDeleteFarmerBtn) {
        confirmDeleteFarmerBtn.addEventListener('click', function() {
            if (farmerToDelete) {
                deleteFarmer(farmerToDelete);
                farmerToDelete = null;
                $('#confirmDeleteFarmerModal').modal('hide');
            }
        });
    }
});

// Handle edit farmer form submission
document.addEventListener('DOMContentLoaded', function() {
    const editFarmerForm = document.getElementById('editFarmerForm');
    if (editFarmerForm) {
        editFarmerForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const form = document.getElementById('editFarmerForm');
            const submitBtn = document.getElementById('updateFarmerBtn');
            const notification = document.getElementById('editFarmerFormNotification');
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            
            // Collect form data
            const formData = {
                farmer_id: document.getElementById('editFarmerId').value,
                name: document.getElementById('editFarmerName').value,
                email: document.getElementById('editFarmerEmail').value,
                phone: document.getElementById('editFarmerPhone').value,
                username: document.getElementById('editFarmerUsername').value,
                barangay: document.getElementById('editFarmerBarangay').value,
                status: document.getElementById('editFarmerStatus').value,
                password: document.getElementById('editFarmerPassword').value,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };
            
            // Submit via AJAX
            $.ajax({
                url: `/superadmin/farmers/${formData.farmer_id}/update`,
                method: 'PUT',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Close modal
                        $('#editFarmerModal').modal('hide');
                        
                        // Refresh the page to show updated data
                        location.reload();
                        
                        // Show success notification
                        alert('Farmer updated successfully!');
                    } else {
                        notification.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i>
                                ${response.message || 'Error updating farmer. Please try again.'}
                            </div>
                        `;
                        notification.style.display = 'block';
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Error updating farmer. Please try again.';
                    
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
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Farmer';
                }
            });
        });
    }
});

// Show Farmer Details Modal
function showFarmerDetails(farmerId) {
    // Load farmer details via AJAX
    $.ajax({
        url: `/superadmin/farmers/${farmerId}/details`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const farmer = response.data;
                const detailsHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Personal Information</h6>
                            <p><strong>Full Name:</strong> ${farmer.name || 'N/A'}</p>
                            <p><strong>Email:</strong> ${farmer.email || 'N/A'}</p>
                            <p><strong>Username:</strong> ${farmer.username || 'N/A'}</p>
                            <p><strong>Contact Number:</strong> ${farmer.phone || 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Account Information</h6>
                            <p><strong>Role:</strong> <span class="badge badge-success">Farmer</span></p>
                            <p><strong>Status:</strong> <span class="badge badge-${farmer.status === 'approved' ? 'success' : (farmer.status === 'pending' ? 'warning' : (farmer.status === 'rejected' ? 'danger' : 'secondary'))}">${farmer.status}</span></p>
                            <p><strong>Barangay:</strong> ${farmer.barangay || 'N/A'}</p>
                            <p><strong>Registration Date:</strong> ${farmer.created_at ? new Date(farmer.created_at).toLocaleDateString() : 'N/A'}</p>
                            <p><strong>Last Updated:</strong> ${farmer.updated_at ? new Date(farmer.updated_at).toLocaleDateString() : 'N/A'}</p>
                        </div>
                    </div>
                `;
                $('#farmerDetailsContent').html(detailsHtml);
                $('#farmerDetailsModal').modal('show');
            } else {
                alert('Error loading farmer details');
            }
        },
        error: function() {
            alert('Error loading farmer details');
        }
    });
}

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

// Form submission handling
$(document).ready(function() {
    $('#addUserForm').on('submit', function(e) {
        e.preventDefault();
        
        // Basic validation
        const password = $('#userPassword').val();
        const confirmPassword = $('#userConfirmPassword').val();
        
        if (password !== confirmPassword) {
            showNotification('Passwords do not match!', 'danger');
            return;
        }
        
        if (password.length < 6) {
            showNotification('Password must be at least 6 characters long!', 'warning');
            return;
        }
        
        // Collect form data
        const formData = {
            name: $('#userName').val(),
            email: $('#userEmail').val(),
            phone: $('#userPhone').val(),
            barangay: $('#userBarangay').val(),
            username: $('#userUsername').val(),
            status: $('#userStatus').val(),
            password: password
        };
        
        // Here you would typically send the data to your backend
        console.log('Form data:', formData);
        
        // Show success message
        showNotification('User added successfully!', 'success');
        
        // Close modal and reset form
        $('#addUserModal').modal('hide');
        $('#addUserForm')[0].reset();
        
        // Optionally refresh the table
        // refreshFarmers();
    });
});

// Print Farmers Table (System-wide standard format)
function printFarmersTable() {
    try {
        // Get current table data without actions column
        const tableData = $('#farmersTable').DataTable().data().toArray();
        
        if (!tableData || tableData.length === 0) {
            showNotification('No data available to print', 'warning');
            return;
        }
        
        // Create print content directly in current page
        const originalContent = document.body.innerHTML;
        
        let printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #18375d; margin-bottom: 5px;">SuperAdmin Farmers Directory Report</h1>
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
                             <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Status</th>
                             <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Created Date</th>
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
            $('#farmersTable').DataTable().button('.buttons-print').trigger();
        } catch (fallbackError) {
            console.error('Fallback print also failed:', fallbackError);
            showNotification('Print failed. Please try again.', 'danger');
        }
    }
}

// Refresh Farmers Table
function refreshFarmers() {
    location.reload();
}

// Download counter for file naming
let farmersDownloadCounter = 1;
let farmerToDelete = null;

// Export functions for Farmers Directory (System-wide standard format)
function exportCSV(tableId) {
    if (tableId === 'farmersTable') {
        try {
            // Get current table data without actions column
            const tableData = $('#farmersTable').DataTable().data().toArray();
            const csvData = [];
            
            if (!tableData || tableData.length === 0) {
                showNotification('No data available to export', 'warning');
                return;
            }
            
                         // Add headers (excluding Actions column)
             const headers = ['User ID', 'Name', 'Barangay', 'Contact', 'Email', 'Username', 'Status', 'Created Date'];
             csvData.push(headers.join(','));
            
            // Add data rows (excluding Actions column)
            tableData.forEach(row => {
                // Extract text content from each cell, excluding the last column (Actions)
                const rowData = [];
                for (let i = 0; i < row.length - 1; i++) {
                    let cellText = '';
                    if (row[i]) {
                        // Remove HTML tags and get clean text
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = row[i];
                        cellText = tempDiv.textContent || tempDiv.innerText || '';
                        // Clean up the text (remove extra spaces, newlines)
                        cellText = cellText.replace(/\s+/g, ' ').trim();
                    }
                    // Escape commas and quotes for CSV
                    if (cellText.includes(',') || cellText.includes('"') || cellText.includes('\n')) {
                        cellText = '"' + cellText.replace(/"/g, '""') + '"';
                    }
                    rowData.push(cellText);
                }
                csvData.push(rowData.join(','));
            });
            
            // Create and download CSV file
            const csvContent = csvData.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `SuperAdmin_FarmersDirectoryReport_${farmersDownloadCounter}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Increment download counter
            farmersDownloadCounter++;
            
            showNotification('CSV exported successfully!', 'success');
            
        } catch (error) {
            console.error('Error exporting CSV:', error);
            showNotification('Error exporting CSV. Please try again.', 'danger');
        }
    } else {
        // Fallback to DataTables export
        $('#farmersTable').DataTable().button('.buttons-csv').trigger();
    }
}

function exportPDF(tableId) {
    if (tableId === 'farmersTable') {
        try {
            // Check if jsPDF is available
            if (typeof window.jspdf === 'undefined') {
                console.warn('jsPDF not available, falling back to DataTables PDF export');
                // Fallback to DataTables PDF export
                $('#farmersTable').DataTable().button('.buttons-pdf').trigger();
                return;
            }
            
            // Get current table data without actions column
            const tableData = $('#farmersTable').DataTable().data().toArray();
            const pdfData = [];
            
            if (!tableData || tableData.length === 0) {
                showNotification('No data available to export', 'warning');
                return;
            }
            
                         // Add headers (excluding Actions column)
             const headers = ['User ID', 'Name', 'Barangay', 'Contact', 'Email', 'Username', 'Status', 'Created Date'];
            
            // Add data rows (excluding Actions column)
            tableData.forEach(row => {
                const rowData = [];
                for (let i = 0; i < row.length - 1; i++) {
                    let cellText = '';
                    if (row[i]) {
                        // Remove HTML tags and get clean text
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = row[i];
                        cellText = tempDiv.textContent || tempDiv.innerText || '';
                        // Clean up the text (remove extra spaces, newlines)
                        cellText = cellText.replace(/\s+/g, ' ').trim();
                    }
                    rowData.push(cellText);
                }
                pdfData.push(rowData);
            });
            
            // Create PDF using jsPDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('landscape', 'mm', 'a4');
            
            // Set title
            doc.setFontSize(18);
            doc.text('SuperAdmin Farmers Directory Report', 14, 22);
            doc.setFontSize(12);
            doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 14, 32);
            
            // Add table
            doc.autoTable({
                head: [headers],
                body: pdfData,
                startY: 40,
                styles: {
                    fontSize: 8,
                    cellPadding: 2
                },
                headStyles: {
                    fillColor: [24, 55, 93],
                    textColor: 255,
                    fontStyle: 'bold'
                },
                alternateRowStyles: {
                    fillColor: [245, 245, 245]
                }
            });
            
            // Save PDF with counter
            doc.save(`SuperAdmin_FarmersDirectoryReport_${farmersDownloadCounter}.pdf`);
            
            // Increment download counter
            farmersDownloadCounter++;
            
            showNotification('PDF exported successfully!', 'success');
            
        } catch (error) {
            console.error('Error generating PDF:', error);
            showNotification('Error generating PDF. Falling back to DataTables export.', 'warning');
            
            // Fallback to DataTables PDF export
            try {
                $('#farmersTable').DataTable().button('.buttons-pdf').trigger();
            } catch (fallbackError) {
                console.error('Fallback PDF export also failed:', fallbackError);
                showNotification('PDF export failed. Please try again.', 'danger');
            }
        }
    } else {
        // Fallback to DataTables export
        $('#farmersTable').DataTable().button('.buttons-pdf').trigger();
    }
}

function exportPNG(tableId) {
    if (tableId === 'farmersTable') {
        try {
            // Create a temporary table without the Actions column for export
            const originalTable = document.getElementById('farmersTable');
            const tempTable = originalTable.cloneNode(true);
            
            if (!tempTable) {
                showNotification('Table not found for PNG export', 'warning');
                return;
            }
            
            // Remove the Actions column header
            const headerRow = tempTable.querySelector('thead tr');
            if (headerRow) {
                const lastHeaderCell = headerRow.lastElementChild;
                if (lastHeaderCell) {
                    lastHeaderCell.remove();
                }
            }
            
            // Remove the Actions column from all data rows
            const dataRows = tempTable.querySelectorAll('tbody tr');
            dataRows.forEach(row => {
                const lastDataCell = row.lastElementChild;
                if (lastDataCell) {
                    lastDataCell.remove();
                }
            });
            
            // Temporarily add the temp table to the DOM (hidden)
            tempTable.style.position = 'absolute';
            tempTable.style.left = '-9999px';
            tempTable.style.top = '-9999px';
            document.body.appendChild(tempTable);
            
            // Generate PNG using html2canvas
            html2canvas(tempTable, {
                scale: 2, // Higher quality
                backgroundColor: '#ffffff',
                width: tempTable.offsetWidth,
                height: tempTable.offsetHeight
            }).then(canvas => {
                // Create download link
                const link = document.createElement('a');
                link.download = `SuperAdmin_FarmersDirectoryReport_${farmersDownloadCounter}.png`;
                link.href = canvas.toDataURL("image/png");
                link.click();
                
                // Increment download counter
                farmersDownloadCounter++;
                
                // Clean up - remove temporary table
                document.body.removeChild(tempTable);
                
                showNotification('PNG exported successfully!', 'success');
                
            }).catch(error => {
                console.error('Error generating PNG:', error);
                // Clean up on error
                if (document.body.contains(tempTable)) {
                    document.body.removeChild(tempTable);
                }
                showNotification('Error generating PNG export', 'danger');
            });
            
        } catch (error) {
            console.error('Error exporting PNG:', error);
            showNotification('Error exporting PNG. Please try again.', 'danger');
        }
    } else {
        // For other tables, use simple HTML2Canvas export
        const tableElement = document.getElementById(tableId);
        html2canvas(tableElement).then(canvas => {
            let link = document.createElement('a');
            link.download = `${tableId}_Report.png`;
            link.href = canvas.toDataURL("image/png");
            link.click();
        });
    }
}

// System-wide notification function (matching user directory standard)
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


