@extends('layouts.app')

@section('title', 'Issue Alerts')

@section('content')
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-exclamation-triangle"></i>
            Issue Alerts
        </h1>
        <p>Create and manage alerts to notify administrators about livestock issues</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row fade-in">
        <!-- Total Alerts -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Alerts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAlerts }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-bell fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Alerts -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Active</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeAlerts }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-clock fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Critical Alerts -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Critical</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $criticalAlerts }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-fire fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resolved Alerts -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Resolved</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $resolvedAlerts }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-check-circle fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow fade-in">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-list"></i>
                        My Alerts
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
                            <input type="text" class="form-control" placeholder="Search alerts..." id="alertSearch">
                        </div>
                        <div class="d-flex flex-column flex-sm-row align-items-center">
                            <button class="btn-action btn-action-edit" onclick="openCreateAlertModal()">
                                <i class="fas fa-plus"></i>Add Alert
                            </button>
                            <button class="btn-action btn-action-print" onclick="printTable()">
                                <i class="fas fa-print"></i> Print
                            </button>
                            <button class="btn-action btn-action-refresh" onclick="refreshAlertsTable('alertsTable')">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                            <div class="dropdown">
                                <button class="btn-action btn-action-tools" type="button" data-toggle="dropdown">
                                    <i class="fas fa-tools"></i> Tools
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#" onclick="exportToCSV()">
                                        <i class="fas fa-file-csv"></i> Download CSV
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="exportToPNG()">
                                        <i class="fas fa-image"></i> Download PNG
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="exportToPDF()">
                                        <i class="fas fa-file-pdf"></i> Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="alertsTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Livestock ID</th>
                                    <th>Topic</th>
                                    <th>Description</th>
                                    <th>Severity</th>
                                    <th>Date Created</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alerts as $alert)
                                <tr class="{{ $alert->severity === 'critical' ? 'table-danger' : ($alert->severity === 'high' ? 'table-warning' : ($alert->status === 'resolved' ? 'table-success' : '')) }}">
                                    <td>
                                        <strong>{{ $alert->livestock->livestock_id ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $alert->topic }}</td>
                                    <td>{{ Str::limit($alert->description, 50) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $alert->severity_badge_class }}">
                                            {{ ucfirst($alert->severity) }}
                                        </span>
                                    </td>
                                    <td>{{ $alert->alert_date ? $alert->alert_date->format('M d, Y') : $alert->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $alert->status_badge_class }}">
                                            {{ ucfirst($alert->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action btn-action-view" onclick="viewAlertDetails('{{ $alert->id }}')" title="View Details">
                                                <i class="fas fa-eye"></i>
                                                <span>View</span>
                                            </button>
                                            @if($alert->status === 'active')
                                            <button class="btn-action btn-action-approve" onclick="markAsResolved('{{ $alert->id }}')" title="Mark as Resolved">
                                                <i class="fas fa-check"></i>
                                                <span>Resolve</span>
                                            </button>
                                            <button class="btn-action btn-action-reject" onclick="dismissAlert('{{ $alert->id }}')" title="Dismiss Alert">
                                                <i class="fas fa-times"></i>
                                                <span>Dismiss</span>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">No alerts created yet</td>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">N/A</td>
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
</div>

<!-- Create Alert Modal -->
<div class="modal fade" id="createAlertModal" tabindex="-1" role="dialog" aria-labelledby="createAlertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAlertModalLabel">
                    <i class="fas fa-plus"></i>
                    Create New Alert
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createAlertForm" method="POST" action="{{ route('farmer.issue-alerts.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="livestock_id">Livestock</label>
                                <select class="form-control" id="livestock_id" name="livestock_id" required>
                                    <option value="">Select Livestock</option>
                                    @foreach($livestock as $animal)
                                    <option value="{{ $animal->id }}">{{ $animal->livestock_id }} - {{ $animal->type }} ({{ $animal->breed }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="severity">Severity Level</label>
                                <select class="form-control" id="severity" name="severity" required>
                                    <option value="">Select Severity</option>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="topic">Alert Topic</label>
                        <input type="text" class="form-control" id="topic" name="topic" required placeholder="Brief description of the issue">
                    </div>
                    <div class="form-group">
                        <label for="description">Detailed Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Provide detailed information about the issue, symptoms, and any observations"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="alert_date">Alert Date</label>
                        <input type="date" class="form-control" id="alert_date" name="alert_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-action btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-action btn-action-ok">
                        Create Alert
                    </button>
                </div>
            </form>
        </div>
    </div>

<!-- Bottom spacing to match farm analysis tab -->
<div style="margin-bottom: 3rem;"></div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTables with Super Admin configuration
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
                title: 'Farmer_Alerts_Report',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Farmer_Alerts_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Farmer Alerts Report',
                className: 'd-none'
            }
        ],
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No data available</h5><p>There are no records to display at this time.</p></div>'
        },
        order: [[4, 'desc']] // Sort by date created
    };

    // Initialize Alerts Table
    if ($('#alertsTable').length) {
        const alertsTable = $('#alertsTable');
        const hasData = alertsTable.find('tbody tr').length > 0;
        const hasEmptyRow = alertsTable.find('tbody tr td[colspan]').length > 0;
        
        if (hasData && !hasEmptyRow) {
            try {
                const dt = alertsTable.DataTable({
                    ...commonConfig,
                    columnDefs: [
                        { width: '100px', targets: 0 }, // Livestock ID
                        { width: '120px', targets: 1 }, // Topic
                        { width: '200px', targets: 2 }, // Description
                        { width: '100px', targets: 3 }, // Severity
                        { width: '140px', targets: 4 }, // Date Created
                        { width: '120px', targets: 5 }, // Status
                        { width: '200px', targets: 6, orderable: false } // Actions
                    ]
                });
            } catch (error) {
                console.error('Error initializing Alerts DataTable:', error);
            }
        }
    }

    // Hide default DataTables search boxes (we use custom ones)
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
    
    // Connect custom search box to DataTables
    $('#alertSearch').on('keyup', function() {
        if ($.fn.DataTable.isDataTable('#alertsTable')) {
            $('#alertsTable').DataTable().search(this.value).draw();
        }
    });

    // Handle form submission
    $('#createAlertForm').on('submit', function(e) {
        e.preventDefault();
        submitAlertForm();
    });
});

function openCreateAlertModal() {
    $('#createAlertForm')[0].reset();
    $('#alert_date').val('{{ date("Y-m-d") }}');
    $('#createAlertModal').modal('show');
}

function submitAlertForm() {
    const formData = new FormData($('#createAlertForm')[0]);
    
    $.ajax({
        url: $('#createAlertForm').attr('action'),
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            alert('Alert created successfully!');
            $('#createAlertModal').modal('hide');
            location.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(field => {
                    alert(errors[field][0]);
                });
            } else {
                alert('Error creating alert');
            }
        }
    });
}

// Refresh Pending Farmers Table
function refreshAlertsTable() {
    const refreshBtn = document.querySelector('.btn-action-refresh');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    refreshBtn.disabled = true;

    // Use unique flag for farmers
    sessionStorage.setItem('showRefreshNotificationAlerts', 'true');

    setTimeout(() => {
        location.reload();
    }, 1000);
}
// Check notifications after reload
$(document).ready(function() {
    if (sessionStorage.getItem('showRefreshNotificationAlerts') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationAlerts');
        setTimeout(() => {
            showNotification('Data refreshed successfully!', 'success');
        }, 500);
    }
});
</script>
@endpush

@push('styles')
<style>
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

    #createAlertModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #createAlertModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #createAlertModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #createAlertModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #createAlertModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #createAlertModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #createAlertModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #createAlertModal .form-group label {
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
/* Search and button group alignment - EXACT COPY FROM SUPERADMIN */
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

/* Page Header Styling */
.page-header {
    background: #18375d;
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.page-header h1 {
    color: white;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.page-header p {
    color: rgba(255, 255, 255, 0.8);
    margin: 0;
}

.page-header h1 i {
    color: white !important;
    margin-right: 10px;
}

/* Statistics Cards - Match Super Admin Style */
.card.border-left-primary {
    border-left: 4px solid #18375d !important;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.25);
    transform: translateY(-2px);
}

.card-body {
    padding: 1.25rem;
}

/* Icon styling for stat cards */
.card-body .icon {
    display: block !important;
    width: 60px;
    height: 60px;
    text-align: center;
    line-height: 60px;
}

.card-body .icon i {
    color: #18375d !important;
    display: inline-block !important;
    opacity: 1;
}

/* Text styling for stat cards */
.text-xs {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.text-gray-800 {
    color: #5a5c69 !important;
}

/* Card header styling - Match Super Admin */
.card-header {
    padding: 1rem 1.5rem;
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.card-header h6 {
    color: #18375d;
    margin: 0;
    font-weight: 600;
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

.btn-action-approve {
    background-color: #387057;
    border-color: #387057;
    color: white;
}

.btn-action-approve:hover {
    background-color: #fca700;
    border-color: #fca700;
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
    background-color: #fca700 !important;
    border-color: #fca700 !important;
    color: white !important;
}

.btn-action-refresh:hover {
    background-color: #e69500 !important;
    border-color: #e69500 !important;
    color: white !important;
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

.btn-action-tools {
    background-color: #f8f9fa !important;
    border-color: #dee2e6 !important;
    color: #495057 !important;
}

.btn-action-tools:hover {
    background-color: #e2e6ea !important;
    border-color: #cbd3da !important;
    color: #495057 !important;
}

/* COMPLETE TABLE STYLING TO MATCH SUPERADMIN FARMS - EXACT COPY */

/* Table hover effects */
.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.075);
}

/* Badge styling */
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

/* Align Farm Directory table styling with User Management */
#alertsTable {
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

#alertsTable th,
#alertsTable td {
    vertical-align: middle;
    padding: 0.75rem;
    text-align: center;
    border: 1px solid #dee2e6;
    white-space: nowrap;
    overflow: visible;
}

/* Table headers styling */
#alertsTable thead th {
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
#alertsTable thead th.sorting,
#alertsTable thead th.sorting_asc,
#alertsTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Remove default DataTables sort indicators to prevent overlap */
#alertsTable thead th.sorting::after,
#alertsTable thead th.sorting_asc::after,
#alertsTable thead th.sorting_desc::after {
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

.dataTables_wrapper .dataTables_paginate .pagination {
    justify-content: flex-start !important;
    margin: 0;
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

.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endpush
