@extends('layouts.app')

@section('title', 'Issue Alerts')

@section('content')
    <!-- Page Header -->
    <div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
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
                <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
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
                            <button class="btn-action btn-action-ok" onclick="openCreateAlertModal()">
                                <i class="fas fa-plus"></i>Add Alert
                            </button>
                            <button class="btn-action btn-action-edit" onclick="printTable()">
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
                            <thead>
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
                                        <div class="btn-group">
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
<div class="modal fade admin-modal" id="createAlertModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content smart-form text-center p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle mb-3">
                    <i class="fas fa-plus fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1" id="createAlertModalLabel">Create New Alert</h5>
                <p class="text-muted small mb-0">
                    Fill out the details below to create a new alert for your livestock.
                </p>
            </div>

            <!-- Form -->
            <form id="createAlertForm" method="POST" action="{{ route('farmer.issue-alerts.store') }}">
                @csrf
                <div class="form-wrapper text-start mx-auto">
                    <div class="row g-3">

                        <!-- Livestock -->
                        <div class="col-md-6">
                            <label for="livestock_id" class="fw-semibold">
                                Livestock <span class="text-danger">*</span>
                            </label>
                            <select class="form-control mt-1" id="livestock_id" name="livestock_id" required>
                                <option value="">Select Livestock</option>
                                @foreach($livestock as $animal)
                                    <option value="{{ $animal->id }}">
                                        {{ $animal->livestock_id }} - {{ $animal->type }} ({{ $animal->breed }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Severity Level -->
                        <div class="col-md-6">
                            <label for="severity" class="fw-semibold">
                                Severity Level <span class="text-danger">*</span>
                            </label>
                            <select class="form-control mt-1" id="severity" name="severity" required>
                                <option value="">Select Severity</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>

                        <!-- Alert Topic -->
                        <div class="col-md-6">
                            <label for="topic" class="fw-semibold">
                                Alert Topic <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control mt-1" id="topic" name="topic" required
                                   placeholder="Brief description of the issue">
                        </div>

                        <!-- Alert Date -->
                        <div class="col-md-6">
                            <label for="alert_date" class="fw-semibold">
                                Alert Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control mt-1" id="alert_date" name="alert_date"
                                   value="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- Description -->
                        <div class="col-md-12">
                            <label for="description" class="fw-semibold">
                                Detailed Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control mt-1" id="description" name="description" rows="4" required
                                      placeholder="Provide detailed information about the issue, symptoms, and any observations"
                                      style="resize: none;"></textarea>
                        </div>

                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="modal-footer d-flex gap-2 justify-content-center flex-wrap mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-ok">
                        Create Alert
                    </button>
                </div>
            </form>

        </div>
    </div>


<!-- Alert Action Modal -->
<div class="modal fade admin-modal" id="alertActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content smart-form text-center p-4">
            <div class="d-flex flex-column align-items-center mb-3">
                <div class="icon-circle mb-2">
                    <i class="fas fa-question-circle fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1" id="alertActionTitle">Confirm Action</h5>
                <p class="text-muted small mb-0" id="alertActionMessage"></p>
            </div>
            <div class="modal-footer d-flex gap-2 justify-content-center flex-wrap mt-3">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn-modern btn-ok" id="confirmAlertActionBtn">Confirm</button>
            </div>
        </div>
    </div>
    
</div>

<!-- Bottom spacing to match farm analysis tab -->
<div style="margin-bottom: 3rem;"></div>

@endsection

@push('scripts')
<!-- DataTables CSS and dependencies (needed for print/csv/pdf buttons) -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<!-- Required libraries for PDF/Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<!-- jsPDF and autoTable for custom PDF generation (if needed) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
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
        autoWidth: false,
        scrollX: true,
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Farmer_Alerts_Report',
                className: 'd-none',
                exportOptions: {
                    columns: [0,1,2,3,4,5],
                    modifier: { page: 'all' }
                }
            },
            {
                extend: 'pdfHtml5',
                title: 'Farmer_Alerts_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none',
                exportOptions: {
                    columns: [0,1,2,3,4,5],
                    modifier: { page: 'all' }
                }
            },
            {
                extend: 'print',
                title: 'Farmer Alerts Report',
                className: 'd-none',
                exportOptions: {
                    columns: [0,1,2,3,4,5],
                    modifier: { page: 'all' }
                }
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
            showNotification('Alerts data refreshed successfully!', 'success');
        }, 500);
    }
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

// Print handler for My Alerts table - triggers DataTables print button
function printTable() {
    try {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#alertsTable')) {
            $('#alertsTable').DataTable().button('.buttons-print').trigger();
        } else {
            window.print();
        }
    } catch (e) {
        console.error('printTable error:', e);
        window.print();
    }
}

function viewAlertDetails(alertId) {
    try {
        $.ajax({
            url: `/farmer/issue-alerts/${alertId}`,
            method: 'GET',
            headers: { 'Accept': 'application/json' },
            success: function(response) {
                if (!response || !response.success || !response.alert) {
                    showNotification('Could not load alert details.', 'danger');
                    return;
                }
                const a = response.alert || {};
                const l = a.livestock || {};
                const issued = a.issuedBy || a.issued_by || {};
                const dateRaw = a.alert_date || a.created_at;
                let dateText = '';
                try {
                    dateText = dateRaw ? new Date(dateRaw).toLocaleDateString() : '';
                } catch (e) {
                    dateText = dateRaw || '';
                }

                const escapeHtml = function(s) {
                    return String(s == null ? '' : s).replace(/[&<>"']/g, function(c){
                        return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]);
                    });
                };

                const modalHtml = `
                    <div class="modal fade" id="viewAlertModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Alert Details</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-2">
                                        <div class="col-md-6"><strong>Livestock ID:</strong> ${escapeHtml(l.livestock_id || 'N/A')}</div>
                                        <div class="col-md-6"><strong>Severity:</strong> ${escapeHtml((a.severity || '').toString().toUpperCase())}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-6"><strong>Date:</strong> ${escapeHtml(dateText)}</div>
                                        <div class="col-md-6"><strong>Status:</strong> ${escapeHtml((a.status || '').toString().toUpperCase())}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-12"><strong>Topic:</strong> ${escapeHtml(a.topic || '')}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <strong>Description:</strong>
                                            <div class="mt-1">${escapeHtml(a.description || '')}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>`;

                $('#viewAlertModal').remove();
                $('body').append(modalHtml);
                $('#viewAlertModal').modal('show');
            },
            error: function() {
                showNotification('Failed to load alert details.', 'danger');
            }
        });
    } catch (e) {}
}

// Action buttons modal logic
let pendingAlertAction = { id: null, type: null };

function markAsResolved(alertId) {
    pendingAlertAction = { id: alertId, type: 'resolve' };
    $('#alertActionTitle').text('Mark as Resolved');
    $('#alertActionMessage').text('Are you sure you want to mark this alert as resolved?');
    $('#alertActionModal').modal('show');
}

function dismissAlert(alertId) {
    pendingAlertAction = { id: alertId, type: 'dismiss' };
    $('#alertActionTitle').text('Dismiss Alert');
    $('#alertActionMessage').text('Are you sure you want to dismiss this alert?');
    $('#alertActionModal').modal('show');
}

$('#confirmAlertActionBtn').on('click', function() {
    try {
        $('#alertActionModal').modal('hide');
        // Provide immediate UI feedback
        if (pendingAlertAction && pendingAlertAction.id) {
            const selector = pendingAlertAction.type === 'resolve'
                ? `button[onclick="markAsResolved('${pendingAlertAction.id}')"]`
                : `button[onclick="dismissAlert('${pendingAlertAction.id}')"]`;
            const row = document.querySelector(selector)?.closest('tr');
            if (row) {
                row.style.opacity = '0.6';
                row.style.transition = 'opacity 0.3s ease';
            }
        }
        // Backend call to update status
        if (pendingAlertAction && pendingAlertAction.id) {
            const status = pendingAlertAction.type === 'resolve' ? 'resolved' : 'dismissed';
            $.ajax({
                url: `/farmer/issue-alerts/${pendingAlertAction.id}/status`,
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: { status, resolution_notes: '' },
                success: function() {
                    showNotification('Alert ' + (status === 'resolved' ? 'resolved' : 'dismissed') + ' successfully', 'success');
                    setTimeout(() => location.reload(), 600);
                },
                error: function() {
                    showNotification('Failed to update alert status', 'danger');
                }
            });
        }
        pendingAlertAction = { id: null, type: null };
    } catch (e) {
        console.error('confirmAlertAction error:', e);
    }
});

// Export helpers using DataTables buttons
function exportToCSV() {
    try {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#alertsTable')) {
            $('#alertsTable').DataTable().button('.buttons-csv').trigger();
        }
    } catch (e) {
        console.error('exportToCSV error:', e);
    }
}

function exportToPDF() {
    try {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#alertsTable')) {
            $('#alertsTable').DataTable().button('.buttons-pdf').trigger();
        }
    } catch (e) {
        console.error('exportToPDF error:', e);
    }
}

function exportToPNG() {
    try {
        const originalTable = document.getElementById('alertsTable');
        const tempTable = originalTable.cloneNode(true);
        const headerRow = tempTable.querySelector('thead tr');
        if (headerRow && headerRow.lastElementChild) headerRow.lastElementChild.remove();
        const dataRows = tempTable.querySelectorAll('tbody tr');
        dataRows.forEach(row => { if (row.lastElementChild) row.lastElementChild.remove(); });
        tempTable.style.position = 'absolute';
        tempTable.style.left = '-9999px';
        tempTable.style.top = '-9999px';
        document.body.appendChild(tempTable);
        html2canvas(tempTable, { scale: 2, backgroundColor: '#ffffff' }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'Farmer_Alerts_Report.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
            document.body.removeChild(tempTable);
        }).catch(err => {
            console.error('exportToPNG error:', err);
            if (document.body.contains(tempTable)) document.body.removeChild(tempTable);
        });
    } catch (e) {
        console.error('exportToPNG wrapper error:', e);
    }
}

function showNotification(message, type = 'info') {
    const icon = type === 'success' ? 'check-circle' : (type === 'warning' ? 'exclamation-triangle' : (type === 'danger' ? 'times-circle' : 'info-circle'));
    const notification = $(`
        <div class="alert alert-${type} alert-dismissible fade show refresh-notification" style="position: fixed; top: 1rem; right: 1rem; z-index: 1060;">
            <i class="fas fa-${icon}"></i>
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `);
    $('body').append(notification);
    setTimeout(() => { notification.alert('close'); }, 4000);
}
</script>
@endpush

@push('styles')
<style>
     /* ============================
   SMART FORM - Enhanced Version
   ============================ */
.smart-form {
  border: none;
  border-radius: 22px; /* slightly more rounded */
  box-shadow: 0 15px 45px rgba(0, 0, 0, 0.15);
  background-color: #ffffff;
  padding: 3rem 3.5rem; /* bigger spacing */
  transition: all 0.3s ease;
  max-width: 900px; /* slightly wider form container */
  margin: 2rem auto;
}

.smart-form:hover {
  box-shadow: 0 18px 55px rgba(0, 0, 0, 0.18);
}

/* Header Icon */
.smart-form .icon-circle {
  width: 60px;
    height: 60px;
    background-color: #e8f0fe;
    color: #18375d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

/* Titles & Paragraphs */
.smart-form h5 {
  color: #18375d;
  font-weight: 700;
  margin-bottom: 0.4rem;
  letter-spacing: 0.5px;
}

.smart-form p {
  color: #6b7280;
  font-size: 0.96rem;
  margin-bottom: 1.8rem;
  line-height: 1.5;
}

/* Form Container */
.smart-form .form-wrapper {
  max-width: 720px;
  margin: 0 auto;
}

/* ============================
   FORM ELEMENT STYLES
   ============================ */
#createAlertModal form {
  text-align: left;
}

#createAlertModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#createAlertModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#createAlertModal .form-control,
#createAlertModal select.form-control,
#createAlertModal textarea.form-control {
  border-radius: 12px;
  border: 1px solid #d1d5db;
  padding: 12px 15px;          /* consistent padding */
  font-size: 15px;             /* consistent font */
  line-height: 1.5;
  transition: all 0.2s ease;
  width: 100%;
  height: 46px;                /* unified height */
  box-sizing: border-box;
  margin-top: 0.5rem;
  margin-bottom: 1rem;
  background-color: #fff;
}

/* Keep textarea resizable but visually aligned */
#createAlertModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#createAlertModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}


#editLivestockModal form {
  text-align: left;
}

#editLivestockModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#editLivestockModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#editLivestockModal .form-control,
#editLivestockModal select.form-control,
#editLivestockModal textarea.form-control {
  border-radius: 12px;
  border: 1px solid #d1d5db;
  padding: 12px 15px;          /* consistent padding */
  font-size: 15px;             /* consistent font */
  line-height: 1.5;
  transition: all 0.2s ease;
  width: 100%;
  height: 46px;                /* unified height */
  box-sizing: border-box;
  margin-top: 0.5rem;
  margin-bottom: 1rem;
  background-color: #fff;
}

/* Keep textarea resizable but visually aligned */
#editLivestockModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#editLivestockModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}
/* ============================
   FORM ELEMENT STYLES
   ============================ */
#reportIssueModal form {
  text-align: left;
}

#reportIssueModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#reportIssueModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#reportIssueModal .form-control,
#reportIssueModal select.form-control,
#reportIssueModal textarea.form-control {
  border-radius: 12px;
  border: 1px solid #d1d5db;
  padding: 12px 15px;          /* consistent padding */
  font-size: 15px;             /* consistent font */
  line-height: 1.5;
  transition: all 0.2s ease;
  width: 100%;
  height: 46px;                /* unified height */
  box-sizing: border-box;
  margin-top: 0.5rem;
  margin-bottom: 1rem;
  background-color: #fff;
}

/* Keep textarea resizable but visually aligned */
#reportIssueModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#reportIssueModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}

/* ============================
   CRITICAL FIX FOR DROPDOWN TEXT CUTTING
   ============================ */
.admin-modal select.form-control,
.modal.admin-modal select.form-control,
.admin-modal .modal-body select.form-control {
  min-width: 250px !important;
  width: 100% !important;
  max-width: 100% !important;
  box-sizing: border-box !important;
  padding: 12px 15px !important;  /* match input padding */
  white-space: nowrap !important;
  text-overflow: clip !important;
  overflow: visible !important;
  font-size: 15px !important;     /* match input font */
  line-height: 1.5 !important;
  height: 46px !important;        /* same height as input */
  background-color: #fff !important;
}

/* Ensure columns don't constrain dropdowns */
.admin-modal .col-md-6 {
  min-width: 280px !important;
  overflow: visible !important;
}

/* Prevent modal body from clipping dropdowns */
.admin-modal .modal-body {
  overflow: visible !important;
}

/* ============================
   BUTTONS
   ============================ */
.btn-approve,
.btn-delete,
.btn-ok {
  font-weight: 600;
  border: none;
  border-radius: 10px;
  padding: 10px 24px;
  transition: all 0.2s ease-in-out;
}

.btn-approves {
  background: #387057;
  color: #fff;
}
.btn-approves:hover {
  background: #fca700;
  color: #fff;
}
.btn-cancel {
  background: #387057;
  color: #fff;
}
.btn-cancel:hover {
  background: #fca700;
  color: #fff;
}

.btn-delete {
  background: #dc3545;
  color: #fff;
}
.btn-delete:hover {
  background: #fca700;
  color: #fff;
}

.btn-ok {
  background: #18375d;
  color: #fff;
}
.btn-ok:hover {
  background: #fca700;
  color: #fff;
}

/* ============================
   FOOTER & ALIGNMENT
   ============================ */
#reportIssueModal .modal-footer {
  text-align: center;
  border-top: 1px solid #e5e7eb;
  padding-top: 1.25rem;
  margin-top: 1.5rem;
}

/* ============================
   RESPONSIVE DESIGN
   ============================ */
@media (max-width: 768px) {
  .smart-form {
    padding: 1.5rem;
  }

  .smart-form .form-wrapper {
    max-width: 100%;
  }

  #createAlertModal .form-control {
    font-size: 14px;
  }

  #editLivestockModal .form-control {
    font-size: 14px;
  }
   #reportIssueModal .form-control {
    font-size: 14px;
  }

  .btn-ok,
  .btn-delete,
  .btn-approves {
    width: 100%;
    margin-top: 0.5rem;
  }
}
    /* 🌟 Page Header Styling */
.page {
    background-color: #18375d;
    border-radius: 12px;
    padding: 1.5rem 2rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease-in-out;
    animation: fadeIn 0.6s ease-in-out;
}

/* Hover lift effect for interactivity */
.page:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
}

/* 🧭 Header Title */
.page h1 {
    color: #18375d;
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Icon style */
.page i {
    color: #18375d; /* Bootstrap primary color */
}

/* 💬 Subtitle text */
.page p {
    color: #18375d;
    font-size: 1rem;
    margin: 0;
}

/* ✨ Fade-in Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
/* Base Card Style */
.card {
    background-color: #ffffff !important;
    border: none;
    border-radius: 0.75rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
}

/* Top Section (Header inside card-body) */
.card-body:first-of-type {
    background-color: #ffffff;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    border-top-left-radius: 0.75rem;
    border-top-right-radius: 0.75rem;
    padding: 1rem 1.5rem;
}

/* Title (h6) */
.card-body:first-of-type h6 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 700;
    color: #18375d !important;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Second Card Body (Main Content) */
.card-body:last-of-type {
    background-color: #ffffff;
    padding: 1.25rem 1.5rem;
    border-bottom-left-radius: 0.75rem;
    border-bottom-right-radius: 0.75rem;
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
