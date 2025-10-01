@extends('layouts.app')

@section('title', 'LBDAIRY: Farmer - Audit Logs')

@push('styles')
<style>
    /* Custom styles for farmer audit logs */
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
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-clipboard-list"></i>
            My Activity Logs
        </h1>
        <p>Track your system activities and actions</p>
    </div>

    <!-- Stats Cards -->
    <div class="row fade-in">
        <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Activities</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLogs ?? 0 }}</div>
                    </div>
                    <div class="icon text-info">
                        <i class="fas fa-list fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Today's Activities</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayLogs ?? 0 }}</div>
                    </div>
                    <div class="icon text-success">
                        <i class="fas fa-calendar fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Important Events</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $criticalEvents ?? 0 }}</div>
                    </div>
                    <div class="icon text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Last Updated Indicator -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="text-muted text-right">
                <small><i class="fas fa-clock"></i> Last updated: <span id="lastUpdated">Never</span></small>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="fas fa-filter"></i>
                Filter Activities
            </h6>
        </div>
        <div class="card-body">
            <form id="filterForm" method="GET" action="{{ route('farmer.audit-logs') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Search activities...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="action">Action</label>
                            <select class="form-control" id="action" name="action">
                                <option value="">All Actions</option>
                                @foreach($actionOptions as $action)
                                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                        {{ ucfirst($action) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="severity">Severity</label>
                            <select class="form-control" id="severity" name="severity">
                                <option value="">All Severities</option>
                                @foreach($severityOptions as $severity)
                                    <option value="{{ $severity }}" {{ request('severity') == $severity ? 'selected' : '' }}>
                                        {{ ucfirst($severity) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="{{ request('start_date') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary btn-sm mr-1">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ route('farmer.audit-logs') }}" class="btn btn-secondary btn-sm" title="Clear Filters">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Audit Logs Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-primary text-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h6 class="mb-0 mb-md-0">
                    <i class="fas fa-clipboard-list"></i>
                    Activity History
                    @if(request('search') || request('action') || request('severity') || request('start_date') || request('end_date'))
                        <span class="badge badge-light ml-2">Filtered</span>
                    @endif
                </h6>
                <div class="d-flex flex-column flex-sm-row gap-2 mt-2 mt-md-0">
                    <div class="btn-group" role="group">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportLogs('csv')">
                                <i class="fas fa-file-csv"></i> Download CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportLogs('pdf')">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </div>
                    </div>
                    <button class="btn btn-info btn-sm" onclick="refreshData()" title="Refresh Data">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="auditLogsTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Activity ID</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>Severity</th>
                            <th>IP Address</th>
                            <th>Date & Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($auditLogs as $log)
                        <tr>
                            <td>
                                <code class="small">LOG{{ str_pad($log->id, 3, '0', STR_PAD_LEFT) }}</code>
                            </td>
                                                         <td>
                                 <span class="badge badge-{{ $getActionBadgeClass($log->action) }}">
                                     {{ ucfirst($log->action) }}
                                 </span>
                             </td>
                             <td>{{ $log->description ?? 'No description available' }}</td>
                             <td>
                                 <span class="badge badge-{{ $getSeverityBadgeClass($log->severity) }}">
                                     {{ ucfirst($log->severity ?? 'info') }}
                                 </span>
                             </td>
                            <td>
                                <code class="small">{{ $log->ip_address ?? 'N/A' }}</code>
                            </td>
                            <td>{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-primary btn-sm" onclick="showLogDetails('{{ $log->id }}')" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">No audit logs available</td>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">N/A</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            @if($auditLogs->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $auditLogs->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1" role="dialog" aria-labelledby="logDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logDetailsModalLabel">
                    <i class="fas fa-clipboard-list"></i>
                    Activity Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="logDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let auditLogsTable;

$(document).ready(function () {
    // Initialize DataTable
    initializeDataTable();
    
    // Auto-refresh data every 30 seconds
    setInterval(function() {
        refreshData();
    }, 30000);
    
    // Update last updated time
    updateLastUpdated();
});

function initializeDataTable() {
    auditLogsTable = $('#auditLogsTable').DataTable({
        searching: true,
        paging: false, // We're using Laravel pagination
        info: false,
        ordering: true,
        lengthChange: false,
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No activities found</h5><p>You haven\'t performed any actions that generate logs yet.</p></div>'
        }
    });

    // Hide default DataTables elements
    $('.dataTables_filter').hide();
}

function showLogDetails(logId) {
    // Load log details via AJAX
    $.ajax({
        url: `{{ route("farmer.audit-logs.details", ":id") }}`.replace(':id', logId),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const log = response.auditLog;
                const details = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3 text-primary">Activity Information</h6>
                            <p><strong>Activity ID:</strong> <code>LOG${String(log.id).padStart(3, '0')}</code></p>
                            <p><strong>Action:</strong> <span class="badge badge-${getActionBadgeClass(log.action)}">${log.action}</span></p>
                            <p><strong>Description:</strong> ${log.description || 'No description available'}</p>
                            <p><strong>Severity:</strong> <span class="badge badge-${getSeverityBadgeClass(log.severity)}">${log.severity || 'info'}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3 text-primary">Technical Details</h6>
                            <p><strong>IP Address:</strong> <code>${log.ip_address || 'N/A'}</code></p>
                            <p><strong>User Agent:</strong> <small>${log.user_agent || 'N/A'}</small></p>
                            <p><strong>Date & Time:</strong> ${new Date(log.created_at).toLocaleString()}</p>
                            <p><strong>Table:</strong> ${log.table_name || 'N/A'}</p>
                            <p><strong>Record ID:</strong> ${log.record_id || 'N/A'}</p>
                        </div>
                    </div>
                    ${log.old_values || log.new_values ? `
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="mb-3 text-primary">Changes</h6>
                            ${log.old_values ? `<p><strong>Previous Values:</strong> <code>${JSON.stringify(log.old_values, null, 2)}</code></p>` : ''}
                            ${log.new_values ? `<p><strong>New Values:</strong> <code>${JSON.stringify(log.new_values, null, 2)}</code></p>` : ''}
                        </div>
                    </div>
                    ` : ''}
                `;

                document.getElementById('logDetails').innerHTML = details;
                $('#logDetailsModal').modal('show');
            } else {
                showNotification('Error loading activity details', 'danger');
            }
        },
        error: function(xhr) {
            console.error('Error loading log details:', xhr);
            showNotification('Error loading activity details', 'danger');
        }
    });
}

function exportLogs(format) {
    // Get current filter parameters
    const searchParams = new URLSearchParams(window.location.search);
    searchParams.set('format', format);
    
    const url = `{{ route("farmer.audit-logs.export") }}?${searchParams.toString()}`;
    window.open(url, '_blank');
}

function refreshData() {
    // Show loading indicator
    const refreshBtn = $('button[onclick="refreshData()"]');
    const originalIcon = refreshBtn.html();
    refreshBtn.html('<i class="fas fa-spinner fa-spin"></i>');
    refreshBtn.prop('disabled', true);
    
    // Refresh the page to get updated data
    location.reload();
}

function updateLastUpdated() {
    const now = new Date();
    const timeString = now.toLocaleTimeString();
    document.getElementById('lastUpdated').textContent = timeString;
}

function getActionBadgeClass(action) {
    switch(action) {
        case 'create': return 'success';
        case 'update': return 'primary';
        case 'delete': return 'danger';
        case 'login': return 'info';
        case 'logout': return 'secondary';
        case 'export': return 'warning';
        case 'import': return 'info';
        default: return 'secondary';
    }
}

function getSeverityBadgeClass(severity) {
    switch(severity) {
        case 'critical': return 'danger';
        case 'error': return 'danger';
        case 'warning': return 'warning';
        case 'info': return 'info';
        case 'debug': return 'secondary';
        default: return 'secondary';
    }
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
