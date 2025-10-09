@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers-Suppliers')
@push('styles')
<style>
    /* ===== DATATABLE STYLES ===== */
.dataTables_length {
    margin-bottom: 1rem;
}

.dataTables_length select {
    min-width: 80px;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background-color: #fff;
    margin: 0 0.5rem;
}

.dataTables_length label {
    display: flex;
    align-items: center;
    margin-bottom: 0;
    font-weight: 500;
    color: var(--dark-color);
}

.dataTables_info {
    padding-top: 0.5rem;
    font-weight: 500;
    color: var(--dark-color);
}

.dataTables_paginate {
    margin-top: 1rem;
}

.dataTables_paginate .paginate_button {
    padding: 0.5rem 0.75rem;
    margin: 0 0.125rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background-color: #fff;
    color: var(--dark-color);
    text-decoration: none;
    transition: var(--transition-fast);
}

.dataTables_paginate .paginate_button:hover {
    background-color: var(--light-color);
    border-color: var(--primary-light);
    color: var(--primary-color);
}

.dataTables_paginate .paginate_button.current {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

.dataTables_paginate .paginate_button.disabled {
    color: var(--text-muted);
    cursor: not-allowed;
    background-color: var(--light-color);
    border-color: var(--border-color);
}

.dataTables_filter {
    margin-bottom: 1rem;
}

.dataTables_filter input {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background-color: #fff;
    transition: var(--transition-fast);
}

.dataTables_filter input:focus {
    border-color: var(--primary-light);
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    outline: 0;
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
    
    #usersTable {
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
    /* Apply consistent styling for Pending Farmers and Active Farmers tables */
#salesTable th,
#salesTable td,
#activeFarmersTable th,
#activeFarmersTable td {
    vertical-align: middle;
    padding: 0.75rem;
    text-align: center;
    border: 1px solid #dee2e6;
    white-space: nowrap;
    overflow: visible;
}

/* Ensure all table headers have consistent styling */
#salesTable thead th,
#activeFarmersTable thead th {
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
#salesTable thead th.sorting,
#salesTable thead th.sorting_asc,
#salesTable thead th.sorting_desc,
#activeFarmersTable thead th.sorting,
#activeFarmersTable thead th.sorting_asc,
#activeFarmersTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#salesTable thead th::after,
#activeFarmersTable thead th::after {
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
#salesTable thead th.sorting::after,
#salesTable thead th.sorting_asc::after,
#salesTable thead th.sorting_desc::after,
#activeFarmersTable thead th.sorting::after,
#activeFarmersTable thead th.sorting_asc::after,
#activeFarmersTable thead th.sorting_desc::after {
    display: none;
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

    .btn-action-history {
        background-color: #5a6268;
        border-color: #5a6268;
        color: white;
    }
    
    .btn-action-history:hover {
        background-color: #e69500;
        border-color: #e69500;
        color: white;
    }

    .btn-action-import {
        background-color: #1b3043;
        border-color: #1b3043;
        color: white;
    }
    
    .btn-action-import:hover {
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
/* CRITICAL FIX FOR DROPDOWN TEXT CUTTING */
    .farmer-modal select.form-control,
    .modal.farmer-modal select.form-control,
    .farmer-modal .modal-body select.form-control {
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
    
    /* Ensure columns don't constrain dropdowns */
    .farmer-modal .col-md-6 {
        min-width: 280px !important;
        overflow: visible !important;
    }

     #addLivestockDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #addLivestockDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #addLivestockDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #addLivestockDetailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #addLivestockDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #addLivestockDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #addLivestockDetailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #addLivestockDetailsModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }
    /* */
    #historyModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #historyModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #historyModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #historyModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #historyModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #historyModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #historyModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #historyModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }
</style>
@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-truck"></i>
        Suppliers Management
    </h1>
    <p>Manage your farm suppliers, track purchases, and maintain supplier ledgers</p>
</div>
<!-- Statistics Grid -->
    <div class="row fade-in">
        <!-- Total Livestock -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Suppliers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSuppliers }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-users fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Healthy Livestock -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Active Suppliers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeSuppliers }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-heart fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Needs Attention -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Spent</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($totalSpent) }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-exclamation-triangle fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Production Ready -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Pending Payments</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($pendingPayments) }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-tint fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-list mr-2"></i> Suppliers List
                </h6>
            </div>
            <div class="card-body">
                <!-- Search (left) + Actions (right) -->
                <div class="search-controls mb-3">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch">
                        <div class="input-group" style="max-width: 380px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" id="supplierSearch" class="form-control" placeholder="Search suppliers...">
                        </div>
                        <div class="btn-group d-flex gap-2 align-items-center mt-2 mt-sm-0">
                            <button class="btn-action btn-action-edits" data-toggle="modal" data-target="#addLivestockDetailsModal">
                                <i class="fas fa-plus mr-1"></i> Add Supplier
                            </button>
                            <button class="btn-action btn-action-print" onclick="printSuppliersTable()">
                                <i class="fas fa-print mr-1"></i> Print
                            </button>
                            <button class="btn-action btn-action-refresh" onclick="refreshSuppliersTable()">
                                <i class="fas fa-sync-alt mr-1"></i> Refresh
                            </button>
                            <button class="btn-action btn-action-history" data-toggle="modal" data-target="#historyModal">
                                <i class="fas fa-history mr-1"></i> History
                            </button>
                            <div class="dropdown">
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
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered" id="suppliersTable">
                        <thead>
                            <tr>
                                <th>Supplier ID</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="suppliersTableBody">
                            @forelse($suppliersData as $supplier)
                            <tr>
                                <td>{{ $supplier['supplier_id'] }}</td>
                                <td>{{ $supplier['name'] }}</td>
                                <td>{{ $supplier['address'] }}</td>
                                <td>{{ $supplier['contact'] }}</td>
                                <td><span class="status-badge {{ $supplier['status_badge'] }}">{{ $supplier['status_label'] }}</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-action-ledger" onclick="viewLedger('{{ $supplier['name'] }}')" title="View Ledger">
                                            <i class="fas fa-book"></i>
                                            <span>Ledger</span>
                                        </button>
                                        <button class="btn-action btn-action-view" onclick="viewDetails('{{ $supplier['name'] }}')" title="View Details">
                                            <i class="fas fa-eye"></i>
                                            <span>View</span>
                                        </button>
                                        <button class="btn-action btn-action-delete" onclick="confirmDelete('{{ $supplier['name'] }}')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">No suppliers found</td>
                                <td class="text-center text-muted">N/A</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SUPPLIER LEDGER MODAL -->
<div class="modal fade" id="supplierLedgerModal" tabindex="-1" role="dialog" aria-labelledby="supplierLedgerLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-book"></i>
                    Supplier Ledger
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Supplier Info Card -->
                <div class="client-info-card">
                    <div class="d-flex align-items-center">
                        <div class="icon mr-3">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1" id="ledgerSupplierName">Supplier Name</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="opacity-75">Supplier ID:</small>
                                    <div id="supplierInfoId">SP001</div>
                                </div>
                                <div class="col-md-8">
                                    <small class="opacity-75">Address:</small>
                                    <div id="supplierInfoAddress">Supplier Address</div>
                                </div>
                            </div>
                            <button class="btn btn-light btn-sm" onclick="showAddSupplierLedgerEntryForm()">
                                <i class="fas fa-plus mr-2"></i>Add Entry
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Ledger Table -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="supplierLedgerTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Payable (₱)</th>
                                <th>Paid (₱)</th>
                                <th>Due (₱)</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Ledger entries will be populated here -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Add Entry Form -->
                <form id="supplierLedgerEntryForm" class="mt-4" style="display:none;">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Add New Ledger Entry</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="purchaseDate" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="purchaseDate" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="purchaseType" class="form-label">Type</label>
                                    <select class="form-control" id="purchaseType" required>
                                        <option value="" disabled selected>Select</option>
                                        <option value="Feed">Feed</option>
                                        <option value="Medicine">Medicine</option>
                                        <option value="Equipment">Equipment</option>
                                        <option value="Livestock">Livestock</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="payableAmount" class="form-label">Payable (₱)</label>
                                    <input type="number" class="form-control" id="payableAmount" min="0" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="paidAmount" class="form-label">Paid (₱)</label>
                                    <input type="number" class="form-control" id="paidAmount" min="0" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="paymentStatus" class="form-label">Status</label>
                                    <select class="form-control" id="paymentStatus" required>
                                        <option value="Unpaid">Unpaid</option>
                                        <option value="Partial">Partial</option>
                                        <option value="Paid">Paid</option>
                                    </select>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
                Are you sure you want to delete this entry? This action cannot be undone.
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

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel">
                    <i class="fas fa-history mr-2"></i>
                    Supplier Purchase History
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="sortHistory" class="font-weight-bold">Sort By:</label>
                        <select id="sortHistory" class="form-control form-control-sm">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="filterHistory" class="font-weight-bold">Filter By:</label>
                        <select id="filterHistory" class="form-control form-control-sm">
                            <option value="all">All</option>
                            <option value="feed">Feed</option>
                            <option value="medicine">Medicine</option>
                            <option value="equipment">Equipment</option>
                            <option value="livestock">Livestock</option>
                        </select>
                    </div>
                </div>
                <div id="historyContent" class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Supplier ID</th>
                                <th>Type</th>
                                <th>Amount Payable (₱)</th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody">
                            <!-- Supplier history will be dynamically populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-action btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn-action btn-action-ok" onclick="exportHistory()">
                    Export History
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ADD SUPPLIER MODAL -->
<div class="modal fade" id="addLivestockDetailsModal" tabindex="-1" role="dialog" aria-labelledby="addLivestockDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Add New Supplier
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addLivestockDetailsForm">
                    <div class="form-group">
                        <label for="add_supplierId">Supplier ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="add_supplierId" name="supplierId" required>
                    </div>
                    <div class="form-group">
                        <label for="add_supplierName">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="add_supplierName" name="supplierName" required>
                    </div>
                    <div class="form-group">
                        <label for="add_supplierAddress">Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="add_supplierAddress" name="supplierAddress" required>
                    </div>
                    <div class="form-group">
                        <label for="add_supplierContact">Contact Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="add_supplierContact" name="supplierContact" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-action btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" id="saveSupplierBtn" form="addLivestockDetailsForm" class="btn-action btn-action-edits">
                    Save Supplier
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Supplier Details Modal -->
<div class="modal fade" id="supplierDetailsModal" tabindex="-1" role="dialog" aria-labelledby="supplierDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="supplierDetailsLabel">
                    <i class="fas fa-info-circle"></i>
                    Supplier Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Supplier details will be populated here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
let suppliersDT = null;
// Initialize DataTable
$(document).ready(function() {
    const commonConfig = {
        dom: 'Bfrtip',
        searching: true,
        paging: true,
        info: true,
        ordering: true,
        lengthChange: false,
        pageLength: 10,
        buttons: [
            { extend: 'csvHtml5', title: 'Farmer_Suppliers_Report', className: 'd-none' },
            { extend: 'pdfHtml5', title: 'Farmer_Suppliers_Report', orientation: 'landscape', pageSize: 'Letter', className: 'd-none' },
            { extend: 'print', title: 'Farmer Suppliers Report', className: 'd-none' }
        ],
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No data available</h5><p>There are no records to display at this time.</p></div>'
        }
    };

    if ($('#suppliersTable').length) {
        try {
            suppliersDT = $('#suppliersTable').DataTable({
                ...commonConfig,
                order: [[0, 'asc']],
                columnDefs: [
                    { width: '140px', targets: 0 }, // Supplier ID
                    { width: '200px', targets: 1 }, // Name
                    { width: '240px', targets: 2 }, // Address
                    { width: '160px', targets: 3 }, // Contact
                    { width: '120px', targets: 4 }, // Status
                    { width: '200px', targets: 5, orderable: false } // Actions
                ]
            });
        } catch (e) {
            console.error('Failed to initialize suppliers DataTable:', e);
        }
    }

    // Hide default DataTables search and buttons
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();

    // Wire up custom search
    $('#supplierSearch').on('keyup', function(){
        if (suppliersDT) suppliersDT.search(this.value).draw();
    });
});

// Add Supplier form submission -> add a new row to the DataTable
$(document).ready(function(){
    // Ensure Save button reliably triggers the form submit
    $('#saveSupplierBtn').on('click', function(e){
        e.preventDefault();
        $('#addLivestockDetailsForm').trigger('submit');
    });

    $('#addLivestockDetailsForm').on('submit', function(e){
        e.preventDefault();
        const id = $('#add_supplierId').val().trim();
        const name = $('#add_supplierName').val().trim();
        const address = $('#add_supplierAddress').val().trim();
        const contact = $('#add_supplierContact').val().trim();

        if (!id || !name || !address || !contact){
            showNotification('Please fill in all required fields.', 'error');
            return;
        }

        const esc = s => s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        const safeId = esc(id);
        const safeName = esc(name);
        const safeAddress = esc(address);
        const safeContact = esc(contact);

        const statusCell = '<span class="status-badge badge badge-success">Active</span>';
        const actionCell = `
            <div class="action-buttons">
                <button class="btn-action btn-action-ledger" onclick="viewLedger('${safeName.replace(/'/g, '&#39;')}')" title="View Ledger">
                    <i class="fas fa-book"></i>
                    <span>Ledger</span>
                </button>
                <button class="btn-action btn-action-view" onclick="viewDetails('${safeName.replace(/'/g, '&#39;')}')" title="View Details">
                    <i class="fas fa-eye"></i>
                    <span>View</span>
                </button>
                <button class="btn-action btn-action-delete" onclick="confirmDelete('${safeName.replace(/'/g, '&#39;')}')" title="Delete">
                    <i class="fas fa-trash"></i>
                    <span>Delete</span>
                </button>
            </div>`;

        if (suppliersDT){
            // Remove placeholder row if present
            suppliersDT.rows().every(function(){
                const d = this.data();
                if (Array.isArray(d) && d[4] && (d[4]+"").toLowerCase().includes('no suppliers')){
                    this.remove();
                }
            });

            const rowNode = suppliersDT
                .row.add([safeId, safeName, safeAddress, safeContact, statusCell, actionCell])
                .draw(false)
                .node();

            // Ensure it's visible: clear search and go to last page
            if (suppliersDT.search()) suppliersDT.search('');
            suppliersDT.page('last').draw('page');

            if (rowNode){
                rowNode.classList.add('table-success');
                rowNode.scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(()=> rowNode.classList.remove('table-success'), 2000);
            }
        } else {
            // Fallback if DataTables not initialized
            $('#suppliersTableBody').append(`
                <tr>
                    <td>${safeId}</td>
                    <td>${safeName}</td>
                    <td>${safeAddress}</td>
                    <td>${safeContact}</td>
                    <td>${statusCell}</td>
                    <td>${actionCell}</td>
                </tr>`);
            const last = $('#suppliersTableBody tr:last')[0];
            if (last){
                last.classList.add('table-success');
                last.scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(()=> last.classList.remove('table-success'), 2000);
            }
        }

        // Close modal, reset form, and notify
        $('#addLivestockDetailsModal').modal('hide');
        this.reset();
        showNotification('Supplier added successfully!', 'success');
    });
});

// View Ledger function
function viewLedger(supplierName) {
    // Update modal with supplier information
    document.getElementById('ledgerSupplierName').textContent = supplierName;
    
    // In a real implementation, you would fetch supplier details and ledger data
    // For now, we'll show the modal with placeholder data
    $('#supplierLedgerModal').modal('show');
}

// View Details function
function viewDetails(supplierName) {
    // Update modal with supplier information
    const modalBody = document.querySelector('#supplierDetailsModal .modal-body');
    modalBody.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h6>Supplier Information</h6>
                <p><strong>Name:</strong> ${supplierName}</p>
                <p><strong>Status:</strong> Active</p>
                <p><strong>Total Transactions:</strong> ${Math.floor(Math.random() * 20) + 5}</p>
                <p><strong>Total Spent:</strong> ₱${(Math.random() * 50000 + 10000).toLocaleString()}</p>
            </div>
            <div class="col-md-6">
                <h6>Recent Activity</h6>
                <p><strong>Last Transaction:</strong> ${new Date().toLocaleDateString()}</p>
                <p><strong>Payment Status:</strong> Good Standing</p>
            </div>
        </div>
    `;
    
    $('#supplierDetailsModal').modal('show');
}

// Confirm Delete function
function confirmDelete(supplierName) {
    // Update modal message
    document.querySelector('#confirmDeleteModal .modal-body').textContent = 
        `Are you sure you want to delete supplier "${supplierName}"? This action cannot be undone.`;
    
    $('#confirmDeleteModal').modal('show');
}

// Show Add Entry Form
function showAddSupplierLedgerEntryForm() {
    document.getElementById('supplierLedgerEntryForm').style.display = 'block';
}

// Import CSV function
function importCSV(event) {
    const file = event.target.files[0];
    if (file) {
        // Handle CSV import logic here
        console.log('Importing CSV:', file.name);
    }
}

// Export History function
function exportHistory() {
    // Handle export logic here
    console.log('Exporting history...');
}

// Print suppliers table using DataTables
function printSuppliersTable(){
    try { if (suppliersDT) suppliersDT.button('.buttons-print').trigger(); else window.print(); }
    catch(e){ console.error('printSuppliersTable error:', e); window.print(); }
}

// Refresh suppliers table
function refreshSuppliersTable(){
    const btn = document.querySelector('.btn-action-refresh');
    if (btn){ btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...'; }
    sessionStorage.setItem('showRefreshNotificationSuppliers','true');
    setTimeout(()=>location.reload(), 800);
}

// After reload, show notification
$(document).ready(function(){
    if (sessionStorage.getItem('showRefreshNotificationSuppliers') === 'true'){
        sessionStorage.removeItem('showRefreshNotificationSuppliers');
        setTimeout(()=>showNotification('Data refreshed successfully!','success'), 400);
    }
});

// CSV export (exclude Actions column)
function exportCSV(){
    if (!suppliersDT) return showNotification('Table not ready','error');
    const rows = suppliersDT.data().toArray();
    const headers = ['Supplier ID','Name','Address','Contact','Status'];
    const csv = [headers.join(',')];
    rows.forEach(r=>{
        const arr=[]; for(let i=0;i<r.length-1;i++){ // exclude Actions
            const tmp=document.createElement('div'); tmp.innerHTML=r[i];
            let t=tmp.textContent||tmp.innerText||''; t=t.replace(/\s+/g,' ').trim();
            if(t.includes(',')||t.includes('"')||t.includes('\n')) t='"'+t.replace(/"/g,'""')+'"';
            arr.push(t);
        }
        csv.push(arr.join(','));
    });
    const blob=new Blob([csv.join('\n')],{type:'text/csv;charset=utf-8;'});
    const a=document.createElement('a'); a.href=URL.createObjectURL(blob); a.download=`Farmer_SuppliersReport_${Date.now()}.csv`; a.click();
}

// PDF export via jsPDF
function exportPDF(){
    try{
        if (!suppliersDT) return showNotification('Table not ready','error');
        const rows = suppliersDT.data().toArray();
        const data = rows.map(r=>[r[0]||'', r[1]||'', r[2]||'', r[3]||'', r[4]||'']);
        const { jsPDF } = window.jspdf; const doc = new jsPDF('landscape','mm','a4');
        doc.setFontSize(18); doc.text('Farmer Suppliers Report',14,22);
        doc.setFontSize(12); doc.text(`Generated on: ${new Date().toLocaleDateString()}`,14,32);
        doc.autoTable({ head: [['Supplier ID','Name','Address','Contact','Status']], body: data, startY:40, styles:{fontSize:8, cellPadding:2}, headStyles:{ fillColor:[24,55,93], textColor:255, fontStyle:'bold' }, alternateRowStyles:{ fillColor:[245,245,245] } });
        doc.save(`Farmer_SuppliersReport_${Date.now()}.pdf`);
    }catch(e){ console.error('PDF export error:', e); showNotification('Error generating PDF','error'); }
}

// PNG export via html2canvas
function exportPNG(){
    const tbl = document.getElementById('suppliersTable'); if (!tbl) return;
    const clone = tbl.cloneNode(true);
    const headRow = clone.querySelector('thead tr'); if (headRow) headRow.lastElementChild && headRow.lastElementChild.remove();
    clone.querySelectorAll('tbody tr').forEach(tr=>{ tr.lastElementChild && tr.lastElementChild.remove(); });
    clone.style.position='absolute'; clone.style.left='-9999px'; document.body.appendChild(clone);
    html2canvas(clone,{scale:2, backgroundColor:'#ffffff', width:clone.offsetWidth, height:clone.offsetHeight}).then(canvas=>{
        const a=document.createElement('a'); a.download=`Farmer_SuppliersReport_${Date.now()}.png`; a.href=canvas.toDataURL('image/png'); a.click(); document.body.removeChild(clone);
    }).catch(err=>{ console.error('PNG export error:', err); document.body.contains(clone)&&document.body.removeChild(clone); showNotification('Error generating PNG','error'); });
}

function showNotification(message, type){
    const t=document.createElement('div');
    t.className = `alert alert-${type==='error'?'danger':type} alert-dismissible fade show position-fixed`;
    t.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    t.innerHTML = `${message}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>`;
    document.body.appendChild(t); setTimeout(()=>t.remove(), 5000);
}
</script>
@endpush

