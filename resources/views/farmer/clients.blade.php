@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers - Clients')
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
#dataTable th,
#dataTable td,
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
#dataTable thead th,
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
#dataTable thead th.sorting,
#dataTable thead th.sorting_asc,
#dataTable thead th.sorting_desc,
#activeFarmersTable thead th.sorting,
#activeFarmersTable thead th.sorting_asc,
#activeFarmersTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#dataTable thead th::after,
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
#dataTable thead th.sorting::after,
#dataTable thead th.sorting_asc::after,
#dataTable thead th.sorting_desc::after,
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

     #addClientModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #addClientModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #addClientModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #addClientModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #addClientModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #addClientModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #addClientModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #addClientModal .form-group label {
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
</style>
@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-users"></i>
        Clients Management
    </h1>
    <p>Manage your client relationships and track sales performance</p>
</div>

<!-- Statistics Grid -->
    <div class="row fade-in">
        <!-- Total Livestock -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Clients</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalClients }}</div>
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
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Active Clients</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeClients }}</div>
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
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Monthly Revenue</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($monthlyRevenue) }}</div>
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
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">New This Month</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $newThisMonth }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-tint fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- 1. Client Directory -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold">Client Directory</h6>
                <button class="btn-action btn-action-edits btn-sm" data-toggle="modal" data-target="#addClientModal">
                    <i class="fas fa-plus"></i> Add New Client
                </button>
            </div>
            <div class="card-body">
                <!-- Search + Actions controls -->
                <div class="search-controls mb-3">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch">
                        <div class="input-group" style="max-width: 380px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" id="clientSearch" class="form-control" placeholder="Search clients...">
                        </div>
                        <div class="btn-group d-flex gap-2 align-items-center mt-2 mt-sm-0">
                            <button class="btn-action btn-action-print" onclick="printClientsTable()">
                                <i class="fas fa-print"></i> Print
                            </button>
                            <button class="btn-action btn-action-refresh" onclick="refreshClientsTable()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                            <div class="dropdown">
                                <button class="btn-action btn-action-tools" type="button" data-toggle="dropdown">
                                    <i class="fas fa-tools"></i> Tools
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#" onclick="exportClientsCSV()">
                                        <i class="fas fa-file-csv"></i> Download CSV
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="exportClientsPNG()">
                                        <i class="fas fa-image"></i> Download PNG
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="exportClientsPDF()">
                                        <i class="fas fa-file-pdf"></i> Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Contact</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Total Orders</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clientsData as $client)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img class="img-profile rounded-circle mr-3" src="{{ asset('img/ronaldo.png') }}" width="40">
                                        <div>
                                            <div class="font-weight-bold">{{ $client['name'] }}</div>
                                            <small class="text-muted">{{ $client['type_label'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $client['phone'] ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $client['email'] ?? 'N/A' }}</small>
                                </td>
                                <td><span class="badge {{ $client['type_badge'] }}">{{ $client['type_label'] }}</span></td>
                                <td><span class="badge {{ $client['status_badge'] }}">{{ $client['status_label'] }}</span></td>
                                <td>{{ $client['total_orders'] }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewClient('{{ $client['name'] }}')">View</button>
                                    <button class="btn btn-sm btn-outline-info" onclick="editClient('{{ $client['name'] }}')">Edit</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">No clients found</td>
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

<!-- 2. Client Distribution -->
<div class="row">
  <div class="col-12">
    <div class="card shadow mb-4 fade-in">
      <div class="card-header bg-primary text-white d-flex align-items-center">
        <h6 class="mb-0">
          <i class="fas fa-chart-pie mr-2"></i>
          Client Distribution
        </h6>
      </div>

      <div class="card-body">
        <div class="row align-items-center">
          <!-- Chart Section -->
          <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="chart-pie pt-3 pb-2">
              <canvas id="clientDistributionChart"></canvas>
            </div>
          </div>

          <!-- Legend Section -->
          <div class="col-lg-4">
            <div class="text-center small mt-2">
              <div class="mb-3">
                <span class="mr-2">
                  <i class="fas fa-circle text-success"></i> Retail
                </span>
                <div class="font-weight-bold text-success">
                  {{ $clientDistribution['retail'] ?? 0 }} clients
                </div>
              </div>

              <div class="mb-3">
                <span class="mr-2">
                  <i class="fas fa-circle text-info"></i> Wholesale
                </span>
                <div class="font-weight-bold text-info">
                  {{ $clientDistribution['wholesale'] ?? 0 }} clients
                </div>
              </div>

              <div class="mb-3">
                <span class="mr-2">
                  <i class="fas fa-circle text-warning"></i> Business
                </span>
                <div class="font-weight-bold text-warning">
                  {{ $clientDistribution['business'] ?? 0 }} clients
                </div>
              </div>

              <div class="mb-3">
                <span class="mr-2">
                  <i class="fas fa-circle text-secondary"></i> Market
                </span>
                <div class="font-weight-bold text-secondary">
                  {{ $clientDistribution['market'] ?? 0 }} clients
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- 3. Top Clients -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-trophy"></i>
                    Top Clients
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($topClients as $client)
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="card border-left-primary h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <img class="img-profile rounded-circle mr-3" src="{{ asset('img/ronaldo.png') }}" width="50">
                                    <div class="flex-grow-1">
                                        <div class="font-weight-bold text-primary">{{ $client['name'] }}</div>
                                        <small class="text-muted">{{ $client['type'] }}</small>
                                        <div class="mt-2">
                                            <span class="badge badge-primary badge-pill">₱{{ number_format($client['total_spent']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-trophy fa-3x mb-3 text-muted"></i>
                            <p>No top clients data available yet.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Client Modal -->
<div class="modal fade" id="addClientModal" tabindex="-1" role="dialog" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClientModalLabel">
                    <i class="fas fa-user-plus mr-2"></i>
                    Add New Client
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addClientForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clientName">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="clientName" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clientType">Client Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="clientType" required>
                                    <option value="">Select Type</option>
                                    <option value="retail">Retail</option>
                                    <option value="wholesale">Wholesale</option>
                                    <option value="business">Business</option>
                                    <option value="market">Market</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clientPhone">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="clientPhone" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clientEmail">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="clientEmail">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="clientAddress">Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="clientAddress" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clientNotes">Notes <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="clientNotes" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clientStatus">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="clientStatus">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-action btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-action btn-action-edits">
                        Save Client
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables + Buttons -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<!-- PDF/PNG helpers -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let clientsDT = null;
document.addEventListener('DOMContentLoaded', function() {
    // Client Distribution Chart
    const clientCtx = document.getElementById('clientDistributionChart').getContext('2d');
    window.clientChart = new Chart(clientCtx, {
        type: 'doughnut',
        data: {
            labels: ['Retail', 'Wholesale', 'Business', 'Market'],
            datasets: [{
                data: [
                    {{ $clientDistribution['retail'] ?? 0 }},
                    {{ $clientDistribution['wholesale'] ?? 0 }},
                    {{ $clientDistribution['business'] ?? 0 }},
                    {{ $clientDistribution['market'] ?? 0 }}
                ],
                backgroundColor: ['#4e73df', '#36b9cc', '#f6c23e', '#6c757d'],
                hoverBackgroundColor: ['#2e59d9', '#2c9faf', '#f4b619', '#545b62'],
                hoverBorderColor: 'rgba(234, 236, 244, 1)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });

    // Initialize DataTable for Client Directory
    const commonConfig = {
        dom: 'Bfrtip',
        searching: true,
        paging: true,
        info: true,
        ordering: true,
        lengthChange: false,
        pageLength: 10,
        buttons: [
            { extend: 'csvHtml5', title: 'Farmer_Clients_Report', className: 'd-none' },
            { extend: 'pdfHtml5', title: 'Farmer_Clients_Report', orientation: 'landscape', pageSize: 'Letter', className: 'd-none' },
            { extend: 'print', title: 'Farmer Clients Report', className: 'd-none' }
        ],
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No data available</h5><p>There are no records to display at this time.</p></div>'
        }
    };

    if ($('#dataTable').length) {
        try {
            clientsDT = $('#dataTable').DataTable({
                ...commonConfig,
                order: [[0, 'asc']],
                columnDefs: [
                    { width: '260px', targets: 0 }, // Client Name
                    { width: '180px', targets: 1 }, // Contact
                    { width: '120px', targets: 2 }, // Type
                    { width: '120px', targets: 3 }, // Status
                    { width: '140px', targets: 4 }, // Total Orders
                    { width: '180px', targets: 5, orderable: false } // Action
                ]
            });
        } catch (e) {
            console.error('Failed to initialize Client Directory table:', e);
        }
    }

    // Hide default DataTables search and buttons; use custom controls
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();

    // Wire custom search
    $('#clientSearch').on('keyup', function(){
        if (clientsDT) clientsDT.search(this.value).draw();
    });

    // Form submission
    document.getElementById('addClientForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const name = document.getElementById('clientName').value.trim();
        const type = document.getElementById('clientType').value;
        const phone = document.getElementById('clientPhone').value.trim();
        const email = document.getElementById('clientEmail').value.trim();
        const address = document.getElementById('clientAddress').value.trim();
        const notes = document.getElementById('clientNotes').value.trim();
        const status = document.getElementById('clientStatus').value;

        if (!name || !type || !phone) {
            showNotification('Please fill in the required fields.', 'error');
            return;
        }

        // Map labels and badges
        const typeLabelMap = { retail: 'Retail', wholesale: 'Wholesale', business: 'Business', market: 'Market' };
        const typeBadgeMap = { retail: 'badge badge-success', wholesale: 'badge badge-info', business: 'badge badge-warning', market: 'badge badge-secondary' };
        const statusLabelMap = { active: 'Active', inactive: 'Inactive', pending: 'Pending' };
        const statusBadgeMap = { active: 'badge badge-success', inactive: 'badge badge-secondary', pending: 'badge badge-warning' };

        const nameCell = `
            <div class="d-flex align-items-center">
                <img class="img-profile rounded-circle mr-3" src="{{ asset('img/ronaldo.png') }}" width="40">
                <div>
                    <div class="font-weight-bold">${name}</div>
                    <small class="text-muted">${typeLabelMap[type] || 'N/A'}</small>
                </div>
            </div>`;
        const contactCell = `<div>${phone || 'N/A'}</div><small class="text-muted">${email || 'N/A'}</small>`;
        const typeCell = `<span class="${typeBadgeMap[type] || 'badge badge-secondary'}">${typeLabelMap[type] || 'N/A'}</span>`;
        const statusCell = `<span class="${statusBadgeMap[status] || 'badge badge-secondary'}">${statusLabelMap[status] || 'N/A'}</span>`;
        const totalOrdersCell = `0`;
        const actionCell = `
            <button class="btn btn-sm btn-outline-primary" onclick="viewClient('${name.replace(/'/g, "&#39;")}')">View</button>
            <button class="btn btn-sm btn-outline-info" onclick="editClient('${name.replace(/'/g, "&#39;")}')">Edit</button>`;

        if (clientsDT) {
            clientsDT.row.add([nameCell, contactCell, typeCell, statusCell, totalOrdersCell, actionCell]).draw(false);
        }

        // Update Chart counts
        try {
            const idxMap = { retail: 0, wholesale: 1, business: 2, market: 3 };
            const idx = idxMap[type];
            if (typeof idx !== 'undefined' && window.clientChart) {
                window.clientChart.data.datasets[0].data[idx] = (window.clientChart.data.datasets[0].data[idx] || 0) + 1;
                window.clientChart.update();
            }
        } catch (err) { console.warn('Chart update failed:', err); }

        showNotification('Client added successfully!', 'success');
        $('#addClientModal').modal('hide');
        this.reset();
    });
});

// Print using DataTables button
function printClientsTable(){
    try { if (clientsDT) clientsDT.button('.buttons-print').trigger(); else window.print(); }
    catch(e){ console.error('printClientsTable error:', e); window.print(); }
}

function refreshClientsTable(){
    const btn = document.querySelector('.btn-action-refresh');
    if (btn){ btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...'; }
    sessionStorage.setItem('showRefreshNotificationClients','true');
    setTimeout(()=>location.reload(), 800);
}

// After reload, show notification
$(document).ready(function(){
    if (sessionStorage.getItem('showRefreshNotificationClients') === 'true'){
        sessionStorage.removeItem('showRefreshNotificationClients');
        setTimeout(()=>showNotification('Data refreshed successfully!','success'), 400);
    }
});

function exportClientsCSV(){
    if (!clientsDT) return showNotification('Table not ready', 'error');
    const rows = clientsDT.data().toArray();
    const headers = ['Client Name','Contact','Type','Status','Total Orders'];
    const csv = [headers.join(',')];
    rows.forEach(r=>{
        const arr = [];
        for (let i=0;i<r.length-1;i++){
            let t=''; const tmp=document.createElement('div'); tmp.innerHTML=r[i]; t=tmp.textContent||tmp.innerText||''; t=t.replace(/\s+/g,' ').trim(); if (t.includes(',')||t.includes('"')||t.includes('\n')) t='"'+t.replace(/"/g,'""')+'"'; arr.push(t);
        }
        csv.push(arr.join(','));
    });
    const blob=new Blob([csv.join('\n')],{type:'text/csv;charset=utf-8;'});
    const a=document.createElement('a'); a.href=URL.createObjectURL(blob); a.download=`Farmer_ClientsReport_${Date.now()}.csv`; a.click();
}

function exportClientsPDF(){
    try{
        if (!clientsDT) return showNotification('Table not ready','error');
        const rows = clientsDT.data().toArray();
        const data = rows.map(r=>[r[0]||'',r[1]||'',r[2]||'',r[3]||'',r[4]||'']);
        const { jsPDF } = window.jspdf; const doc = new jsPDF('landscape','mm','a4');
        doc.setFontSize(18); doc.text('Farmer Clients Report',14,22);
        doc.setFontSize(12); doc.text(`Generated on: ${new Date().toLocaleDateString()}`,14,32);
        doc.autoTable({ head: [['Client Name','Contact','Type','Status','Total Orders']], body: data, startY: 40, styles:{fontSize:8, cellPadding:2}, headStyles:{ fillColor:[24,55,93], textColor:255, fontStyle:'bold' }, alternateRowStyles:{ fillColor:[245,245,245] } });
        doc.save(`Farmer_ClientsReport_${Date.now()}.pdf`);
    }catch(e){ console.error('PDF export error:', e); showNotification('Error generating PDF','error'); }
}

function exportClientsPNG(){
    const tbl = document.getElementById('dataTable'); if (!tbl) return;
    const clone = tbl.cloneNode(true);
    const headRow = clone.querySelector('thead tr'); if (headRow) headRow.lastElementChild && headRow.lastElementChild.remove();
    clone.querySelectorAll('tbody tr').forEach(tr=>{ tr.lastElementChild && tr.lastElementChild.remove(); });
    clone.style.position='absolute'; clone.style.left='-9999px'; document.body.appendChild(clone);
    html2canvas(clone,{scale:2, backgroundColor:'#ffffff', width:clone.offsetWidth, height:clone.offsetHeight}).then(canvas=>{
        const a=document.createElement('a'); a.download=`Farmer_ClientsReport_${Date.now()}.png`; a.href=canvas.toDataURL('image/png'); a.click(); document.body.removeChild(clone);
    }).catch(err=>{ console.error('PNG export error:', err); document.body.contains(clone)&&document.body.removeChild(clone); showNotification('Error generating PNG','error'); });
}

function showNotification(message, type){
    const t = document.createElement('div');
    t.className = `alert alert-${type==='error'?'danger':type} alert-dismissible fade show position-fixed`;
    t.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    t.innerHTML = `${message}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>`;
    document.body.appendChild(t); setTimeout(()=>t.remove(), 5000);
}

function viewClient(name){ showNotification(`View client: ${name}`,'info'); }
function editClient(name){ showNotification(`Edit client: ${name}`,'info'); }
</script>
@endpush
