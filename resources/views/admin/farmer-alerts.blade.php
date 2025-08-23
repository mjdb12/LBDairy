@extends('layouts.app')

@section('title', 'Farmer Alerts')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-bell"></i>
            Farmer Alerts
        </h1>
        <p>Monitor and respond to alerts issued by farmers about livestock issues</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Alerts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAlerts }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Active Alerts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeAlerts }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Critical Alerts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $criticalAlerts }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-fire fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Resolved</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $resolvedAlerts }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts Section -->
    @if($criticalAlerts > 0)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Critical Alerts!</strong> You have {{ $criticalAlerts }} critical alerts that require immediate attention.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if($activeAlerts > 0)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-clock"></i>
        <strong>Active Alerts:</strong> You have {{ $activeAlerts }} active alerts awaiting your response.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow fade-in">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-list"></i>
                        All Farmer Alerts
                    </h6>
                    <div class="d-flex align-items-center">
                        <div class="export-controls">
                            <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" onclick="exportToCSV()">
                                    <i class="fas fa-file-csv"></i> CSV
                                </a>
                                <a class="dropdown-item" href="#" onclick="exportToPDF()">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                                <a class="dropdown-item" href="#" onclick="printTable()">
                                    <i class="fas fa-print"></i> Print
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="alertsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Farmer</th>
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
                                        <strong>{{ $alert->issuedBy->name ?? 'Unknown' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $alert->issuedBy->email ?? '' }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $alert->livestock->livestock_id ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $alert->livestock->type ?? '' }} ({{ $alert->livestock->breed ?? '' }})</small>
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
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-info" onclick="viewAlertDetails('{{ $alert->id }}')" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($alert->status === 'active')
                                            <button class="btn btn-sm btn-success" onclick="markAsResolved('{{ $alert->id }}')" title="Mark as Resolved">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" onclick="dismissAlert('{{ $alert->id }}')" title="Dismiss Alert">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-bell-slash fa-3x mb-3 text-muted"></i>
                                        <p>No alerts have been issued by farmers yet.</p>
                                    </td>
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

<!-- Alert Details Modal -->
<div class="modal fade" id="alertDetailsModal" tabindex="-1" role="dialog" aria-labelledby="alertDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertDetailsLabel">
                    <i class="fas fa-info-circle"></i>
                    Alert Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="alertDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1" role="dialog" aria-labelledby="statusUpdateLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusUpdateLabel">
                    <i class="fas fa-edit"></i>
                    Update Alert Status
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="statusUpdateForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="resolution_notes">Response Notes</label>
                        <textarea class="form-control" id="resolution_notes" name="resolution_notes" rows="3" placeholder="Add your response or resolution notes for the farmer"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentAlertId = null;
let currentAction = null;

$(document).ready(function() {
    // Initialize DataTable
    $('#alertsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[5, 'desc']], // Sort by date created
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search alerts...",
            lengthMenu: "_MENU_ records per page",
            info: "Showing _START_ to _END_ of _TOTAL_ records",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });

    $('#statusUpdateForm').on('submit', function(e) {
        e.preventDefault();
        submitStatusUpdate();
    });
});

function viewAlertDetails(alertId) {
    currentAlertId = alertId;
    loadAlertDetails(alertId);
    $('#alertDetailsModal').modal('show');
}

function loadAlertDetails(alertId) {
    $.ajax({
        url: `/admin/farmer-alerts/${alertId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const alert = response.alert;
                $('#alertDetailsContent').html(`
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Alert Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr><td><strong>Topic:</strong></td><td>${alert.topic}</td></tr>
                                        <tr><td><strong>Status:</strong></td><td><span class="badge badge-${alert.status_badge_class}">${alert.status}</span></td></tr>
                                        <tr><td><strong>Severity:</strong></td><td><span class="badge badge-${alert.severity_badge_class}">${alert.severity}</span></td></tr>
                                        <tr><td><strong>Created:</strong></td><td>${alert.alert_date}</td></tr>
                                        ${alert.resolved_at ? `<tr><td><strong>Resolved:</strong></td><td>${alert.resolved_at}</td></tr>` : ''}
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-user"></i> Farmer Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr><td><strong>Name:</strong></td><td>${alert.issued_by ? alert.issued_by.name : 'N/A'}</td></tr>
                                        <tr><td><strong>Email:</strong></td><td>${alert.issued_by ? alert.issued_by.email : 'N/A'}</td></tr>
                                        <tr><td><strong>Phone:</strong></td><td>${alert.issued_by ? alert.issued_by.phone : 'N/A'}</td></tr>
                                        <tr><td><strong>Farm:</strong></td><td>${alert.livestock && alert.livestock.farm ? alert.livestock.farm.name : 'N/A'}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    <h6 class="mb-0"><i class="fas fa-paw"></i> Livestock Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr><td><strong>ID:</strong></td><td>${alert.livestock ? alert.livestock.livestock_id : 'N/A'}</td></tr>
                                        <tr><td><strong>Type:</strong></td><td>${alert.livestock ? alert.livestock.type : 'N/A'}</td></tr>
                                        <tr><td><strong>Breed:</strong></td><td>${alert.livestock ? alert.livestock.breed : 'N/A'}</td></tr>
                                        <tr><td><strong>Age:</strong></td><td>${alert.livestock ? alert.livestock.age + ' years' : 'N/A'}</td></tr>
                                        <tr><td><strong>Health Status:</strong></td><td>${alert.livestock ? alert.livestock.health_status : 'N/A'}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-file-alt"></i> Alert Details</h6>
                                </div>
                                <div class="card-body">
                                    <h6>Description:</h6>
                                    <p>${alert.description}</p>
                                    ${alert.resolution_notes ? `<h6>Resolution Notes:</h6><p>${alert.resolution_notes}</p>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
        },
        error: function() {
            showToast('Error loading alert details', 'error');
        }
    });
}

function markAsResolved(alertId) {
    currentAlertId = alertId;
    currentAction = 'resolved';
    $('#statusUpdateForm').attr('action', `/admin/farmer-alerts/${alertId}/status`);
    $('#statusUpdateForm').append('<input type="hidden" name="status" value="resolved">');
    $('#statusUpdateLabel').html('<i class="fas fa-check"></i> Mark as Resolved');
    $('#statusUpdateModal').modal('show');
}

function dismissAlert(alertId) {
    currentAlertId = alertId;
    currentAction = 'dismissed';
    $('#statusUpdateForm').attr('action', `/admin/farmer-alerts/${alertId}/status`);
    $('#statusUpdateForm').append('<input type="hidden" name="status" value="dismissed">');
    $('#statusUpdateLabel').html('<i class="fas fa-times"></i> Dismiss Alert');
    $('#statusUpdateModal').modal('show');
}

function submitStatusUpdate() {
    const formData = new FormData($('#statusUpdateForm')[0]);
    
    $.ajax({
        url: $('#statusUpdateForm').attr('action'),
        method: 'PATCH',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            showToast('Alert status updated successfully!', 'success');
            $('#statusUpdateModal').modal('hide');
            location.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(field => {
                    showToast(errors[field][0], 'error');
                });
            } else {
                showToast('Error updating alert status', 'error');
            }
        }
    });
}

function exportToCSV() {
    const table = $('#alertsTable').DataTable();
    const data = table.data().toArray();
    
    let csv = 'Farmer,Livestock ID,Topic,Description,Severity,Date Created,Status\n';
    data.forEach(row => {
        csv += `${row[0]},${row[1]},${row[2]},${row[3]},${row[4]},${row[5]},${row[6]}\n`;
    });
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'farmer_alerts_report.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

function exportToPDF() {
    showToast('PDF export feature coming soon!', 'info');
}

function printTable() {
    window.print();
}

function showToast(message, type = 'info') {
    const toast = `
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} text-white">
                <strong class="me-auto">${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">${message}</div>
        </div>
    `;
    
    // Add toast to page and show it
    const toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
    toastContainer.innerHTML = toast;
    document.body.appendChild(toastContainer);
    
    const toastElement = toastContainer.querySelector('.toast');
    const bsToast = new bootstrap.Toast(toastElement);
    bsToast.show();
    
    // Remove toast after it's hidden
    toastElement.addEventListener('hidden.bs.toast', () => {
        document.body.removeChild(toastContainer);
    });
}
</script>
@endpush

@push('styles')
<style>
.page-header {
    background: linear-gradient(135deg, #4e73df 0%, #3c5aa6 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
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

.export-controls {
    display: flex;
    gap: 0.5rem;
    margin-left: auto;
}

.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.toast-container {
    z-index: 9999;
}

.alert {
    border: none;
    border-radius: 8px;
    font-weight: 500;
}

.alert-success {
    background: rgba(28, 200, 138, 0.1);
    color: #25855a;
    border-left: 4px solid #1cc88a;
}

.alert-danger {
    background: rgba(231, 74, 59, 0.1);
    color: #c53030;
    border-left: 4px solid #e74a3b;
}

.alert-warning {
    background: rgba(246, 194, 62, 0.1);
    color: #d69e2e;
    border-left: 4px solid #f6c23e;
}
</style>
@endpush
