@extends('layouts.app')

@section('title', 'Audit Logs')

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
    <div class="stats-container fade-in">
        <div class="stat-card">
            <i class="fas fa-list stat-icon"></i>
            <h3>{{ $totalLogs ?? 0 }}</h3>
            <p>Total Logs</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-calendar stat-icon"></i>
            <h3>{{ $todayLogs ?? 0 }}</h3>
            <p>Today's Logs</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-exclamation-triangle stat-icon"></i>
            <h3>{{ $criticalEvents ?? 0 }}</h3>
            <p>Critical Events</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-users stat-icon"></i>
            <h3>{{ $activeUsers ?? 0 }}</h3>
            <p>Active Users</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4 fade-in">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <button class="btn btn-primary btn-block" onclick="exportLogs()">
                                <i class="fas fa-download"></i>
                                Export Logs
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success btn-block" onclick="generateReport()">
                                <i class="fas fa-file-alt"></i>
                                Generate Report
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info btn-block" onclick="openFilterModal()">
                                <i class="fas fa-filter"></i>
                                Advanced Filter
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button id="clearLogsBtn" class="btn btn-warning btn-block" onclick="clearOldLogs()">
                                <i class="fas fa-trash"></i>
                                Clear Logs
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table"></i>
                System Activity Logs
            </h6>
            <div class="table-controls">
                <div class="search-container">
                    <input type="text" class="form-control custom-search" placeholder="Search logs...">
                </div>
                <div class="export-controls">
                    <div class="btn-group">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fas fa-download"></i> Export
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
                    </div>
                    <button class="btn btn-secondary btn-sm" onclick="printTable()">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="auditDataTable" width="100%" cellspacing="0">
                    <thead>
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
                        @forelse($auditLogs ?? [] as $log)
                        <tr class="{{ $log->severity === 'critical' ? 'table-danger' : ($log->severity === 'warning' ? 'table-warning' : '') }}">
                            <td>
                                <a href="#" onclick="openLogDetails('{{ $log->id }}')">
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
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-info" onclick="viewLogDetails('{{ $log->id }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="investigateLog('{{ $log->id }}')">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="flagLog('{{ $log->id }}')">
                                        <i class="fas fa-flag"></i>
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

    <!-- Activity Analytics -->
    <div class="row fade-in">
        <!-- Activity Timeline Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line"></i>
                        System Activity Timeline (Last 24 Hours)
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <a class="dropdown-item" href="#" onclick="exportChart('activityTimeline')">Export Chart</a>
                            <a class="dropdown-item" href="#" onclick="printChart('activityTimeline')">Print Chart</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="activityTimelineChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>

        <!-- Severity Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
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
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">
                <i class="fas fa-shield-alt"></i>
                Security Alerts & Critical Events
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-danger">
                    <thead>
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
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-users"></i>
                User Activity Summary
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
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
        language: {
            search: "Search logs:",
            lengthMenu: "Show _MENU_ logs per page",
            info: "Showing _START_ to _END_ of _TOTAL_ logs"
        }
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

    const $btn = $('#clearLogsBtn');
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
