@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-clipboard-list"></i>
        Audit Logs
    </h1>
    <p>Monitor and track all system activities</p>
</div>

<!-- Statistics Grid -->
<div class="row fade-in">
    <!-- Total Logs -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2" style="border-left-color: #1a365d !important;">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #1a365d !important;">Total Logs</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLogs ?? 0 }}</div>
                </div>
                <div class="icon">
                    <i class="fas fa-clipboard-list fa-2x" style="color: #1a365d !important;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Activity -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2" style="border-left-color: #1a365d !important;">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #1a365d !important;">Today's Activity</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayLogs ?? 0 }}</div>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-day fa-2x" style="color: #1a365d !important;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Critical Actions -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2" style="border-left-color: #1a365d !important;">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #1a365d !important;">Critical Actions</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $criticalEvents ?? 0 }}</div>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle fa-2x" style="color: #1a365d !important;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Users -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2" style="border-left-color: #1a365d !important;">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #1a365d !important;">Active Users</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('is_active', true)->count() }}</div>
                </div>
                <div class="icon">
                    <i class="fas fa-users fa-2x" style="color: #1a365d !important;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Audit Logs Table Card -->
 <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">
                <i class="fas fa-clipboard-list"></i>
            System Activity Logs
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
                    <input type="text" class="form-control" placeholder="Search logs..." id="auditSearch">
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-center">
                    <button class="btn-action btn-action-print" onclick="printTable()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button class="btn-action btn-action-refresh-farmers" onclick="refreshAuditData()">
                        <i class="fas fa-sync-alt"></i> Refresh
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

            <!-- Filter Controls -->
            <div class="filter-controls">
                <div class="filter-row">
                    <!-- Role Filter -->
                    <div class="filter-group">
                        <label for="roleFilter">Role</label>
                        <select id="roleFilter" class="filter-input">
                            <option value="">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="farmer">Farmer</option>
                            <option value="superadmin">Super Admin</option>
                        </select>
                    </div>

                    <!-- Action Filter -->
                    <div class="filter-group">
                        <label for="actionFilter">Action</label>
                        <select id="actionFilter" class="filter-input">
                            <option value="">All Actions</option>
                            <option value="login">Login</option>
                            <option value="update">Update</option>
                            <option value="delete">Delete</option>
                            <option value="create">Create</option>
                            <option value="export">Export</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="filter-group">
                        <label for="dateFrom">From</label>
                        <input type="date" id="dateFrom" class="filter-input">
                    </div>
                    <div class="filter-group">
                        <label for="dateTo">To</label>
                        <input type="date" id="dateTo" class="filter-input">
                    </div>

                    <!-- Buttons -->
                    <div class="filter-actions">
                        <button class="btn-action btn-action-apply" onclick="applyFilters()">
                            <i class="fas fa-filter"></i> Apply
                        </button>
                        <button class="btn-action btn-action-clear" onclick="clearFilters()">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="auditLogsTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Log ID</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditLogs as $log)
                    <tr>
                        <td><code class="small">LOG{{ str_pad($log->id, 3, '0', STR_PAD_LEFT) }}</code></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('img/ronaldo.png') }}" class="rounded-circle mr-2" width="32" height="32" alt="User">
                                <span class="font-weight-bold">{{ $log->user->name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="role-badge role-{{ $log->user->role ?? 'system' }}">
                                {{ ucfirst($log->user->role ?? 'System') }}
                            </span>
                        </td>
                        <td>
                            <span class="action-badge action-{{ strtolower($log->action) }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td>{{ $log->description ?? 'No details available' }}</td>
                        <td>{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h5>No audit logs found</h5>
                                <p>There are no audit logs to display at this time.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>

        
        <!-- Pagination -->
        @if($auditLogs->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $auditLogs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --primary-color: #18375d;
        --primary-dark: #122a47;
        --success-color: #1cc88a;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;
        --info-color: #36b9cc;
        --light-color: #fff;
        --dark-color: #5a5c69;
        --border-color: #e3e6f0;
        --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }

    /* Page Header Enhancement */
    .page-header {
        background: var(--primary-color);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: var(--shadow);
    }

    .page-header h1 {
        margin: 0;
        font-weight: 700;
        font-size: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
        font-size: 1.1rem;
    }


    /* Table Controls */
    .table-controls {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .search-container {
        min-width: 250px;
    }

    .custom-search {
        border-radius: 8px;
        border: 1px solid var(--border-color);
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }

    .export-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-controls {
    background: #ffffffff;
    border: 1px solid #ddd;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    }

    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        flex: 1 1 180px;
    }

    .filter-group label {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
        color: #333;
    }

    .filter-input {
        padding: 0.5rem;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 0.9rem;
    }

    .filter-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .btn {
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.2s;
    }

    .btn-filter {
        background: #387057; /* success green */
        color: white;
    }

    .btn-clear {
        background: #f44336; /* red */
        color: white;
    }
    
    /* Role and Action Badges */
    .role-badge,
    .action-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .role-admin {
        background: rgba(78, 115, 223, 0.1);
        color: var(--primary-color);
    }

    .role-farmer {
        background: rgba(28, 200, 138, 0.1);
        color: var(--success-color);
    }

    .role-superadmin {
        background: rgba(231, 74, 59, 0.1);
        color: var(--danger-color);
    }

    .role-system {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }

    .action-login {
        background: rgba(28, 200, 138, 0.1);
        color: var(--success-color);
    }

    .action-update {
        background: rgba(54, 185, 204, 0.1);
        color: var(--info-color);
    }

    .action-delete {
        background: rgba(231, 74, 59, 0.1);
        color: var(--danger-color);
    }

    .action-create {
        background: rgba(28, 200, 138, 0.1);
        color: var(--success-color);
    }

    .action-export {
        background: rgba(246, 194, 62, 0.1);
        color: var(--warning-color);
    }

    /* Animation Classes */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    .slide-in {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .search-container {
            min-width: 100%;
        }

        .filter-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group {
            min-width: 100%;
        }

        .export-controls {
            margin-left: 0;
            margin-top: 1rem;
        }


        .table-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .card-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }
    }
    /* CRITICAL FIX FOR DROPDOWN TEXT CUTTING */
    .admin-modal select.form-control,
    .modal.admin-modal select.form-control,
    .admin-modal .modal-body select.form-control {
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
    .admin-modal .col-md-6 {
        min-width: 280px !important;
        overflow: visible !important;
    }
    
    /* Apply consistent styling for tables */

#auditLogsTable th,
#auditLogsTable td{
    vertical-align: middle;
    padding: 0.75rem;
    text-align: center;
    border: 1px solid #dee2e6;
    white-space: nowrap;
    overflow: visible;
}

/* Ensure all table headers have consistent styling */
#auditLogsTable thead th{
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
#auditLogsTable thead th.sorting,
#auditLogsTable thead th.sorting_asc,
#auditLogsTable thead th.sorting_desc{
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#auditLogsTable thead th::after{
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
#auditLogsTable thead th.sorting::after,
#auditLogsTable thead th.sorting_asc::after,
#auditLogsTable thead th.sorting_desc::after{
    display: none;
}

    /* Custom styles for user management */
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
    
    .badge-pill {
        border-radius: 50rem;
        padding-left: 0.75em;
        padding-right: 0.75em;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Ensure all role badges have identical pill shape */
    .badge-danger.badge-pill,
    .badge-primary.badge-pill,
    .badge-success.badge-pill,
    .badge-secondary.badge-pill {
        border-radius: 50rem !important;
        padding: 0.5em 1em !important;
        font-size: 0.7em;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        line-height: 1.2;
        display: inline-block;
        white-space: nowrap;
        vertical-align: baseline;
    }
    
    /* Force pill shape override for any conflicting styles */
    .badge.badge-pill {
        border-radius: 50rem !important;
        padding: 0.5em 1em !important;
    }
    
    /* Make admin badge (primary) look identical to superadmin pill shape */
    .badge-primary {
        border-radius: 50rem !important;
        padding: 0.5em 1em !important;
        font-size: 0.7em;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        line-height: 1.2;
        display: inline-block;
        white-space: nowrap;
        vertical-align: baseline;
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
    
    .badge-sm {
        font-size: 0.6em;
        padding: 0.2em 0.4em;
    }
    
    .badge-success .fas.fa-circle {
        animation: pulse 2s infinite;
    }

    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    /* Apply consistent buttons */
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
        background-color: #2d5a47;
        border-color: #2d5a47;
        color: white;
    }
    
    .btn-action-view-livestock, .btn-action-report-livestock {
        background-color: #18375d;
        border-color: #18375d;
        color: white;
    }
    
    .btn-action-view-livestock:hover, .btn-action-report-livestock:hover {
        background-color: #e69500;
        border-color: #e69500;
        color: white;
    }

    /* Header action buttons styling to match Edit/Delete buttons */
    .btn-action-add {
        background-color: #387057;
        border-color: #387057;
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

    .btn-action-apply {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
    .btn-action-apply:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }

    .btn-action-clear{
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .btn-action-clear:hover {
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
    
    .btn-action-refresh-farmers, .btn-action-refresh- {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-refresh-farmers:hover, .btn-action-refresh-:hover {
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

</style>
@endpush

@push('scripts')
<!-- DataTables Core -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- Required libraries for PDF/Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<!-- jsPDF and autoTable for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
let auditLogsTable; // global reference
let downloadCounter = 1;

$(document).ready(function () {
    // ✅ Initialize DataTable
    initializeAuditLogsTable();

    // ✅ Load your data (replace with your own function if needed)
    loadAuditLogs();

    // ✅ Custom search functionality
    $('#auditSearch').on('keyup', function () {
        if (auditLogsTable) {
            auditLogsTable.search(this.value).draw();
        }
    });
});

function initializeAuditLogsTable() {
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
            emptyTable: `
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h5>No data available</h5>
                    <p>No audit logs found at this time.</p>
                </div>`,
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        }
    };

    auditLogsTable = $('#auditLogsTable').DataTable({
        ...commonConfig,
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Audit_Logs',
                className: 'export-csv d-none'   // ✅ added unique class
            },
            {
                extend: 'pdfHtml5',
                title: 'Audit_Logs',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'export-pdf d-none'   // ✅ added unique class
            },
            {
                extend: 'print',
                title: 'Audit Logs',
                className: 'export-print d-none' // ✅ added unique class
            }
        ]
    });

    // Hide DataTables default filter + buttons
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
}

function refreshAuditData() {
    const refreshBtn = $('button[onclick="refreshAuditData()"]');
    const originalIcon = refreshBtn.html();

    // Show spinner
    refreshBtn.html('<i class="fas fa-spinner fa-spin"></i> Refreshing...');
    refreshBtn.prop('disabled', true);

    // After reload, reset button & show notification
    setTimeout(() => {
        refreshBtn.html(originalIcon);
        refreshBtn.prop('disabled', false);
        showNotification('Audit logs data refreshed successfully!', 'success');
    }, 800);
}

function exportCSV() {
    const tableData = auditLogsTable.data().toArray();
    const csvData = [];
    
    // Add headers (all columns for audit logs)
    const headers = ['Log ID', 'User', 'Role', 'Action', 'Details', 'Timestamp'];
    csvData.push(headers.join(','));
    
    // Add data rows (all columns)
    tableData.forEach(row => {
        const rowData = [];
        for (let i = 0; i < row.length; i++) {
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
    link.setAttribute('download', `Admin_AuditLogsReport_${downloadCounter}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    downloadCounter++;
}

function exportPDF() {
    try {
        // Force custom PDF generation to match superadmin styling
        // Don't fall back to DataTables PDF export as it has different styling
        
        const tableData = auditLogsTable.data().toArray();
        const pdfData = [];
        
        const headers = ['Log ID', 'User', 'Role', 'Action', 'Details', 'Timestamp'];
        
        tableData.forEach(row => {
            const rowData = [];
            for (let i = 0; i < row.length; i++) {
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
        
        // Set title
        doc.setFontSize(18);
        doc.text('Admin Audit Logs Report', 14, 22);
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
        doc.save(`Admin_AuditLogsReport_${downloadCounter}.pdf`);
        
        // Increment download counter
        downloadCounter++;
        
        showNotification('PDF exported successfully!', 'success');
        
    } catch (error) {
        console.error('Error generating PDF:', error);
        showNotification('Error generating PDF. Please try again.', 'danger');
    }
}

function exportPNG() {
    const originalTable = document.getElementById('auditLogsTable');
    const tempTable = originalTable.cloneNode(true);
    
    // For audit logs, we include all columns (no Actions column to remove)
    
    tempTable.style.position = 'absolute';
    tempTable.style.left = '-9999px';
    tempTable.style.top = '-9999px';
    document.body.appendChild(tempTable);
    
    html2canvas(tempTable, {
        scale: 2,
        backgroundColor: '#ffffff',
        width: tempTable.offsetWidth,
        height: tempTable.offsetHeight
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = `Admin_AuditLogsReport_${downloadCounter}.png`;
        link.href = canvas.toDataURL("image/png");
        link.click();
        
        downloadCounter++;
        document.body.removeChild(tempTable);
    }).catch(error => {
        console.error('Error generating PNG:', error);
        if (document.body.contains(tempTable)) {
            document.body.removeChild(tempTable);
        }
        showNotification('Error generating PNG export', 'danger');
    });
}

function printTable() {
    try {
        const tableData = auditLogsTable.data().toArray();
        
        if (!tableData || tableData.length === 0) {
            showNotification('No data available to print', 'warning');
            return;
        }
        
        // Create print content directly in current page
        const originalContent = document.body.innerHTML;
        
        let printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #18375d; margin-bottom: 5px;">Audit Logs Report</h1>
                    <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                <table border="3" style="border-collapse: collapse; width: 100%; border: 3px solid #000;">
                    <thead>
                        <tr>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Log ID</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">User</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Role</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Action</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Details</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>`;
        
        // Add data rows (excluding Actions column if it exists)
        tableData.forEach(row => {
            printContent += '<tr>';
            for (let i = 0; i < row.length; i++) { // Include all columns for audit logs
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
            auditLogsTable.button('.export-print').trigger();
        } catch (fallbackError) {
            console.error('Fallback print also failed:', fallbackError);
            showNotification('Print failed. Please try again.', 'danger');
        }
    }
}


function applyFilters() {
    const roleFilter = document.getElementById('roleFilter').value;
    const actionFilter = document.getElementById('actionFilter').value;
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    
    // Here you would implement the actual filtering logic
    console.log('Applying filters:', { roleFilter, actionFilter, dateFrom, dateTo });
    alert('Filter functionality would be implemented here');
}

function clearFilters() {
    document.getElementById('roleFilter').value = '';
    document.getElementById('actionFilter').value = '';
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
}

</script>
@endpush
