@extends('layouts.app')

@section('title', 'LBDAIRY: SuperAdmin - Audit Logs')

@push('styles')
<style>
    /* Custom styles for audit logs */
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
    
    .log-id-link {
        color: #4e73df;
        text-decoration: none;
        font-weight: 600;
    }
    
    .log-id-link:hover {
        color: #2e59d9;
        text-decoration: underline;
    }
    
    /* Fix DataTable length menu overlapping */
    .dataTables_length {
        margin-bottom: 1rem;
    }
    
    .dataTables_length select {
        min-width: 80px;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        background-color: #fff;
        margin: 0 0.5rem;
    }
    
    .dataTables_length label {
        display: flex;
        align-items: center;
        margin-bottom: 0;
        font-weight: 500;
        color: #5a5c69;
    }
    
    /* Fix DataTable info overlapping */
    .dataTables_info {
        padding-top: 0.5rem;
        font-weight: 500;
        color: #5a5c69;
    }
    
    /* Fix DataTable pagination */
    .dataTables_paginate {
        margin-top: 1rem;
    }
    
    .dataTables_paginate .paginate_button {
        padding: 0.5rem 0.75rem;
        margin: 0 0.125rem;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        background-color: #fff;
        color: #5a5c69;
        text-decoration: none;
        transition: all 0.15s ease-in-out;
    }
    
    .dataTables_paginate .paginate_button:hover {
        background-color: #e3e6f0;
        border-color: #bac8f3;
        color: #4e73df;
    }
    
    .dataTables_paginate .paginate_button.current {
        background-color: #4e73df;
        border-color: #4e73df;
        color: #fff;
    }
    
    .dataTables_paginate .paginate_button.disabled {
        color: #858796;
        cursor: not-allowed;
        background-color: #f8f9fc;
        border-color: #e3e6f0;
    }
    
    /* Fix table header text wrapping */
    .table thead th {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
        padding: 0.75rem 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        vertical-align: middle;
        background-color: #f8f9fc;
        border-bottom: 2px solid #e3e6f0;
        color: #5a5c69;
    }
    
    /* Specific column width adjustments */
    .table thead th:nth-child(1) { /* Log ID */
        min-width: 80px;
        max-width: 80px;
    }
    
    .table thead th:nth-child(2) { /* Timestamp */
        min-width: 120px;
        max-width: 120px;
    }
    
    .table thead th:nth-child(3) { /* User */
        min-width: 120px;
        max-width: 120px;
    }
    
    .table thead th:nth-child(4) { /* Action */
        min-width: 80px;
        max-width: 80px;
    }
    
    .table thead th:nth-child(5) { /* Module */
        min-width: 100px;
        max-width: 100px;
    }
    
    .table thead th:nth-child(6) { /* Details */
        min-width: 150px;
        max-width: 200px;
    }
    
    .table thead th:nth-child(7) { /* IP Address */
        min-width: 100px;
        max-width: 100px;
    }
    
    .table thead th:nth-child(8) { /* Severity */
        min-width: 80px;
        max-width: 80px;
    }
    
    .table thead th:nth-child(9) { /* Actions */
        min-width: 100px;
        max-width: 100px;
    }
    
    /* Responsive fixes for DataTable */
    @media (max-width: 768px) {
        .dataTables_length,
        .dataTables_filter,
        .dataTables_info,
        .dataTables_paginate {
            text-align: center;
            margin-bottom: 0.5rem;
        }
        
        .dataTables_length label {
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        
        .dataTables_length select {
            margin: 0;
        }
        
        /* Mobile table header adjustments */
        .table thead th {
            font-size: 0.7rem;
            padding: 0.5rem 0.25rem;
            min-width: 60px;
            max-width: 80px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-history"></i>
            Audit Logs
        </h1>
        <p>Monitor system activities, user actions, and security events</p>
    </div>

    <!-- Stats Cards -->
    <div class="row fade-in">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left-color: #1a365d !important;">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #1a365d !important;">Total Logs</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLogs ?? 0 }}</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-list fa-2x" style="color: #1a365d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left-color: #1a365d !important;">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #1a365d !important;">Today's Logs</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayLogs ?? 0 }}</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar fa-2x" style="color: #1a365d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left-color: #1a365d !important;">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #1a365d !important;">Critical Events</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $criticalEvents ?? 0 }}</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle fa-2x" style="color: #1a365d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left-color: #1a365d !important;">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #1a365d !important;">Active Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeUsers ?? 0 }}</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users fa-2x" style="color: #1a365d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Analytics -->
    <div class="row fade-in">
        <!-- Activity Timeline Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-line"></i>
                        System Activity Timeline (Last 24 Hours)
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="activityTimelineChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>

        <!-- Severity Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header bg-warning text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie"></i>
                        Events by Severity
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="severityChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Alerts -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-danger text-white">
            <h6 class="mb-0">
                <i class="fas fa-shield-alt"></i>
                Security Alerts & Critical Events
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Timestamp</th>
                            <th>User</th>
                            <th>Event</th>
                            <th>Severity</th>
                            <th>Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($securityAlerts ?? [] as $alert)
                        <tr>
                            <td>{{ $alert->timestamp ?? 'N/A' }}</td>
                            <td>{{ $alert->user_name ?? 'N/A' }}</td>
                            <td>{{ $alert->event ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-danger">Critical</span>
                            </td>
                            <td>{{ $alert->details ?? 'N/A' }}</td>
                            <td>
                                <button class="btn btn-sm btn-danger" onclick="investigateAlert('{{ $alert->id }}')">
                                    <i class="fas fa-search"></i> Investigate
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                <i class="fas fa-check-circle"></i>
                                No security alerts at this time
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- User Activity Summary -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-success text-white">
            <h6 class="mb-0">
                <i class="fas fa-users"></i>
                User Activity Summary
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>User</th>
                            <th>Last Activity</th>
                            <th>Total Actions</th>
                            <th>Critical Events</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($userActivity ?? [] as $user)
                        <tr>
                            <td>{{ $user->name ?? 'N/A' }}</td>
                            <td>{{ $user->last_activity ?? 'N/A' }}</td>
                            <td>{{ $user->total_actions ?? 0 }}</td>
                            <td>{{ $user->critical_events ?? 0 }}</td>
                            <td>
                                @php
                                    $status = $user->status ?? 'active';
                                    $statusClass = $status === 'active' ? 'success' : 
                                                  ($status === 'suspended' ? 'danger' : 'warning');
                                @endphp
                                <span class="badge badge-{{ $statusClass }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="fas fa-info-circle"></i>
                                No user activity data available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- System Activity Logs Table (Moved to bottom) -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-secondary text-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h6 class="mb-0 mb-md-0">
                    <i class="fas fa-table"></i>
                    System Activity Logs (Latest 30 Entries)
                </h6>
                <div class="d-flex flex-column flex-sm-row gap-2 mt-2 mt-md-0">
                    <div class="input-group" style="max-width: 300px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" placeholder="Search logs..." id="logSearch">
                    </div>
                    <div class="btn-group" role="group">
                        <button class="btn btn-primary btn-sm" onclick="exportLogs()" title="Export Logs">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <button class="btn btn-success btn-sm" onclick="generateReport()" title="Generate Report">
                            <i class="fas fa-file-alt"></i> Report
                        </button>
                        <button class="btn btn-info btn-sm" onclick="openFilterModal()" title="Advanced Filter">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <button class="btn btn-warning btn-sm" onclick="clearOldLogs()" title="Clear Logs">
                            <i class="fas fa-trash"></i> Clear
                        </button>
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" title="Export Options">
                            <i class="fas fa-download"></i> Export Options
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportCSV()">
                                <i class="fas fa-file-csv"></i> CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPNG()">
                                <i class="fas fa-image"></i> PNG
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPDF()">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                        </div>
                        <button class="btn btn-light btn-sm" onclick="printTable()" title="Print">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="auditDataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Log ID</th>
                            <th>Timestamp</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Module</th>
                            <th>Details</th>
                            <th>IP Address</th>
                            <th>Severity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($auditLogs ?? [])->take(30) as $log)
                        <tr class="{{ $log->severity === 'critical' ? 'table-danger' : ($log->severity === 'warning' ? 'table-warning' : '') }}">
                            <td>
                                <a href="#" class="log-id-link" onclick="openLogDetails('{{ $log->id }}')">
                                    {{ $log->log_id ?? 'L' . str_pad($log->id, 3, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <td>{{ $log->timestamp ?? 'N/A' }}</td>
                            <td>
                                @if($log->user)
                                    <span class="font-weight-bold">{{ $log->user->name ?? 'N/A' }}</span>
                                    <br><small class="text-muted">{{ $log->user->email ?? 'N/A' }}</small>
                                @else
                                    <span class="text-muted">System</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $action = $log->action ?? 'unknown';
                                    $actionClass = $action === 'delete' ? 'danger' : 
                                                  ($action === 'update' ? 'warning' : 
                                                  ($action === 'create' ? 'success' : 'info'));
                                @endphp
                                <span class="badge badge-{{ $actionClass }}">
                                    {{ ucfirst($action) }}
                                </span>
                            </td>
                            <td>{{ $log->module ?? 'N/A' }}</td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $log->details ?? 'N/A' }}">
                                    {{ $log->details ?? 'N/A' }}
                                </span>
                            </td>
                            <td>{{ $log->ip_address ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $severity = $log->severity ?? 'info';
                                    $severityClass = $severity === 'critical' ? 'danger' : 
                                                    ($severity === 'warning' ? 'warning' : 
                                                    ($severity === 'error' ? 'danger' : 'info'));
                                @endphp
                                <span class="badge badge-{{ $severityClass }}">
                                    {{ ucfirst($severity) }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-action-view" onclick="viewLogDetails('{{ $log->id }}')" title="View Details">
                                        <i class="fas fa-eye"></i>
                                        <span>View</span>
                                    </button>
                                    <button class="btn-action btn-action-investigate" onclick="investigateLog('{{ $log->id }}')" title="Investigate">
                                        <i class="fas fa-search"></i>
                                        <span>Investigate</span>
                                    </button>
                                    <button class="btn-action btn-action-flag" onclick="flagLog('{{ $log->id }}')" title="Flag">
                                        <i class="fas fa-flag"></i>
                                        <span>Flag</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                <i class="fas fa-info-circle"></i>
                                No audit log data available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-history"></i>
                    Audit Log Details
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="logDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" onclick="investigateFromModal()">Investigate</button>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-filter"></i>
                    Advanced Filter Options
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="filterForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filterDateFrom">Date From</label>
                                <input type="date" class="form-control" id="filterDateFrom" name="date_from">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filterDateTo">Date To</label>
                                <input type="date" class="form-control" id="filterDateTo" name="date_to">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filterUser">User</label>
                                <select class="form-control" id="filterUser" name="user_id">
                                    <option value="">All Users</option>
                                    @foreach($users ?? [] as $user)
                                    <option value="{{ $user->id }}">{{ $user->name ?? 'N/A' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filterSeverity">Severity</label>
                                <select class="form-control" id="filterSeverity" name="severity">
                                    <option value="">All Severities</option>
                                    <option value="info">Info</option>
                                    <option value="warning">Warning</option>
                                    <option value="error">Error</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filterModule">Module</label>
                                <select class="form-control" id="filterModule" name="module">
                                    <option value="">All Modules</option>
                                    <option value="auth">Authentication</option>
                                    <option value="users">User Management</option>
                                    <option value="farms">Farm Management</option>
                                    <option value="livestock">Livestock Management</option>
                                    <option value="system">System</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filterAction">Action</label>
                                <select class="form-control" id="filterAction" name="action">
                                    <option value="">All Actions</option>
                                    <option value="create">Create</option>
                                    <option value="read">Read</option>
                                    <option value="update">Update</option>
                                    <option value="delete">Delete</option>
                                    <option value="login">Login</option>
                                    <option value="logout">Logout</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize charts and DataTable when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    initializeDataTable();
});

function initializeCharts() {
    // Activity Timeline Chart
    const timelineCtx = document.getElementById('activityTimelineChart').getContext('2d');
    new Chart(timelineCtx, {
        type: 'line',
        data: {
            labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '24:00'],
            datasets: [{
                label: 'System Events',
                data: [15, 8, 25, 45, 38, 52, 18],
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Severity Distribution Chart
    const severityCtx = document.getElementById('severityChart').getContext('2d');
    new Chart(severityCtx, {
        type: 'doughnut',
        data: {
            labels: ['Info', 'Warning', 'Error', 'Critical'],
            datasets: [{
                data: [65, 20, 10, 5],
                backgroundColor: ['#36b9cc', '#f6c23e', '#e74a3b', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

function initializeDataTable() {
    $('#auditDataTable').DataTable({
        responsive: true,
        order: [[1, 'desc']], // Sort by timestamp
        pageLength: 25,
        searching: false, // Disable built-in search to remove duplicate
        language: {
            lengthMenu: "Show _MENU_ logs per page",
            info: "Showing _START_ to _END_ of _TOTAL_ logs"
        }
    });

    // Custom search functionality
    $('#logSearch').on('keyup', function() {
        $('#auditDataTable').DataTable().search(this.value).draw();
    });

}

function openLogDetails(logId) {
    // Load log details via AJAX
    $.get(`{{ route("superadmin.audit-logs.details", ":id") }}`.replace(':id', logId), function(data) {
        $('#logDetailsContent').html(data);
        $('#logDetailsModal').modal('show');
    });
}

function viewLogDetails(logId) {
    openLogDetails(logId);
}

function investigateLog(logId) {
    // Open investigation view
    window.open(`{{ route("superadmin.audit-logs.details", ":id") }}`.replace(':id', logId), '_blank');
}

function investigateFromModal() {
    // Get log ID from modal and investigate
    const logId = $('#logDetailsModal').data('log-id');
    investigateLog(logId);
}

function flagLog(logId) {
    if (confirm('Flag this log entry for further investigation?')) {
        $.post(`{{ route("superadmin.audit-logs.details", ":id") }}`.replace(':id', logId), {
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.success) {
                alert('Log entry flagged successfully');
            } else {
                alert('Error flagging log entry');
            }
        });
    }
}

function investigateAlert(alertId) {
    // Open investigation view for security alert
    window.open(`/superadmin/security-alerts/${alertId}/investigate`, '_blank');
}

function exportLogs() {
    window.location.href = '{{ route("superadmin.audit-logs.export") }}';
}

function generateReport() {
    window.open('{{ route("superadmin.audit-logs.export") }}', '_blank');
}

function openFilterModal() {
    $('#filterModal').modal('show');
}

function clearOldLogs() {
    if (!confirm('Are you absolutely sure you want to CLEAR ALL audit logs? This cannot be undone.')) return;

    const $btn = $('.btn-warning[onclick="clearOldLogs()"]');
    const originalHtml = $btn.html();
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Clearing...');

    $.post('{{ route("superadmin.audit-logs.clear") }}', {
        _token: '{{ csrf_token() }}',
        days: 0
    }, function(response) {
        if (response.success) {
            alert(response.message || 'Logs cleared successfully');
            location.reload();
        } else {
            alert(response.message || 'Error clearing logs');
            $btn.prop('disabled', false).html(originalHtml);
        }
    }).fail(function(xhr) {
        const msg = (xhr && xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Request failed while clearing logs';
        alert(msg);
        $btn.prop('disabled', false).html(originalHtml);
    });
}

// Form submission
$('#filterForm').on('submit', function(e) {
    e.preventDefault();
    
    // Apply filter and reload table
    const formData = $(this).serialize();
    $.get('{{ route("superadmin.audit-logs.export") }}?' + formData, function(response) {
        if (response.success) {
            $('#filterModal').modal('hide');
            location.reload();
        } else {
            alert('Error applying filter');
        }
    });
});

function exportCSV() {
    // Export table data to CSV
    const table = $('#auditDataTable').DataTable();
    const data = table.data().toArray();
    // Implementation for CSV export
}

function exportPNG() {
    // Export table as PNG
    window.print();
}

function exportPDF() {
    // Export as PDF
    window.print();
}

function printTable() {
    window.print();
}

function exportChart(chartType) {
    const canvas = document.getElementById(chartType + 'Chart');
    const link = document.createElement('a');
    link.download = `${chartType}-chart.png`;
    link.href = canvas.toDataURL();
    link.click();
}

function printChart(chartType) {
    const canvas = document.getElementById(chartType + 'Chart');
    const win = window.open();
    win.document.write('<html><head><title>Chart</title></head><body>');
    win.document.write('<img src="' + canvas.toDataURL() + '"/>');
    win.document.write('</body></html>');
    win.document.close();
    win.print();
}
</script>
@endpush
