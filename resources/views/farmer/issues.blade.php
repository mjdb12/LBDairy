@extends('layouts.app')

@section('title', 'Issue Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-exclamation-triangle"></i>
            Issue Management
        </h1>
        <p>View issues reported by administrators for your livestock</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Issues</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalIssues }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                                Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingIssues }}</div>
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
                                Urgent</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $urgentIssues }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $resolvedIssues }}</div>
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
    @if($urgentIssues > 0)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Urgent Issues Detected!</strong> You have {{ $urgentIssues }} urgent issues that require immediate attention.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if($pendingIssues > 0)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-clock"></i>
        <strong>Pending Issues:</strong> You have {{ $pendingIssues }} issues awaiting resolution.
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
                        Issues Reported by Administrators
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
                        <table class="table table-bordered" id="issuesTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Livestock ID</th>
                                    <th>Animal Type</th>
                                    <th>Breed</th>
                                    <th>Issue Type</th>
                                    <th>Description</th>
                                    <th>Priority</th>
                                    <th>Date Reported</th>
                                    <th>Status</th>
                                    <th>Reported By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($issues as $issue)
                                <tr class="{{ $issue->priority === 'Urgent' ? 'table-danger' : ($issue->status === 'Pending' ? 'table-warning' : ($issue->status === 'Resolved' ? 'table-success' : '')) }}">
                                    <td>
                                        <strong>{{ $issue->livestock->livestock_id ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $issue->livestock->type ?? 'N/A' }}</td>
                                    <td>{{ $issue->livestock->breed ?? 'N/A' }}</td>
                                    <td>
                                        <span class="issue-type-badge issue-{{ strtolower($issue->issue_type) }}">
                                            {{ $issue->issue_type }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($issue->description, 50) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $issue->priority === 'Urgent' ? 'danger' : ($issue->priority === 'High' ? 'warning' : ($issue->priority === 'Medium' ? 'info' : 'success')) }}">
                                            {{ $issue->priority }}
                                        </span>
                                    </td>
                                    <td>{{ $issue->date_reported ? $issue->date_reported->format('M d, Y') : $issue->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $issue->status === 'Pending' ? 'warning' : ($issue->status === 'In Progress' ? 'info' : ($issue->status === 'Resolved' ? 'success' : 'secondary')) }}">
                                            {{ $issue->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ $issue->reportedBy->name ?? 'Admin' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action btn-action-view" onclick="viewIssueDetails('{{ $issue->id }}')" title="View Details">
                                                <i class="fas fa-eye"></i>
                                                <span>View Details</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                                        <p>No issues reported by administrators. Your livestock are healthy and well-managed!</p>
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

    <!-- Scheduled Inspections Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow fade-in">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-calendar-check"></i>
                        Scheduled Farm Inspections
                    </h6>
                    <div class="d-flex align-items-center">
                        <div class="export-controls">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" onclick="exportInspectionsToCSV()">
                                    <i class="fas fa-file-csv"></i> CSV
                                </a>
                                <a class="dropdown-item" href="#" onclick="exportInspectionsToPDF()">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                                <a class="dropdown-item" href="#" onclick="printInspectionsTable()">
                                    <i class="fas fa-print"></i> Print
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="inspectionsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Inspection Date</th>
                                    <th>Time</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Scheduled By</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($scheduledInspections ?? [] as $inspection)
                                <tr class="{{ $inspection->priority === 'urgent' ? 'table-danger' : ($inspection->priority === 'high' ? 'table-warning' : '') }}">
                                    <td>
                                        <strong>{{ $inspection->inspection_date ? $inspection->inspection_date->format('M d, Y') : 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $inspection->inspection_time ? $inspection->inspection_time->format('h:i A') : 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $inspection->priority === 'urgent' ? 'danger' : ($inspection->priority === 'high' ? 'warning' : ($inspection->priority === 'medium' ? 'info' : 'success')) }}">
                                            {{ ucfirst($inspection->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $inspection->status === 'scheduled' ? 'primary' : ($inspection->status === 'completed' ? 'success' : ($inspection->status === 'cancelled' ? 'danger' : 'warning')) }}">
                                            {{ ucfirst($inspection->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">
                                            {{ $inspection->scheduledBy->name ?? 'Admin' }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($inspection->notes, 50) }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action btn-action-view" onclick="viewInspectionDetails('{{ $inspection->id }}')" title="View Details">
                                                <i class="fas fa-eye"></i>
                                                <span>View</span>
                                            </button>
                                            @if($inspection->status === 'scheduled')
                                            <button class="btn-action btn-action-approve" onclick="markInspectionComplete('{{ $inspection->id }}')" title="Mark Complete">
                                                <i class="fas fa-check"></i>
                                                <span>Complete</span>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-calendar fa-3x mb-3 text-info"></i>
                                        <p>No scheduled inspections at this time.</p>
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



<!-- Issue Details Modal -->
<div class="modal fade" id="issueDetailsModal" tabindex="-1" role="dialog" aria-labelledby="issueDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="issueDetailsLabel">
                    <i class="fas fa-info-circle"></i>
                    Issue Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="issueDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Inspection Details Modal -->
<div class="modal fade" id="inspectionDetailsModal" tabindex="-1" role="dialog" aria-labelledby="inspectionDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inspectionDetailsLabel">
                    <i class="fas fa-calendar-check"></i>
                    Inspection Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="inspectionDetailsContent">
                <!-- Content will be loaded dynamically -->
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
let currentIssueId = null;

$(document).ready(function() {
    // Initialize DataTable for issues
    $('#issuesTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[6, 'desc']], // Sort by date reported
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search issues...",
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

    // Initialize DataTable for inspections
    $('#inspectionsTable').DataTable({
        responsive: true,
        pageLength: 15,
        order: [[0, 'asc']], // Sort by inspection date
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search inspections...",
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
});

function viewIssueDetails(issueId) {
    currentIssueId = issueId;
    loadIssueDetails(issueId);
    $('#issueDetailsModal').modal('show');
}

function loadIssueDetails(issueId) {
    $.ajax({
        url: `/farmer/issues/${issueId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const issue = response.issue;
                $('#issueDetailsContent').html(`
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Issue Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr><td><strong>Type:</strong></td><td><span class="issue-type-badge issue-${issue.issue_type.toLowerCase()}">${issue.issue_type}</span></td></tr>
                                        <tr><td><strong>Status:</strong></td><td><span class="badge badge-${getStatusColor(issue.status)}">${issue.status}</span></td></tr>
                                        <tr><td><strong>Priority:</strong></td><td><span class="badge badge-${getPriorityColor(issue.priority)}">${issue.priority}</span></td></tr>
                                        <tr><td><strong>Reported:</strong></td><td>${issue.date_reported}</td></tr>
                                        <tr><td><strong>Reported By:</strong></td><td><span class="badge badge-primary">${issue.reported_by ? issue.reported_by.name : 'Admin'}</span></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-paw"></i> Livestock Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr><td><strong>ID:</strong></td><td>${issue.livestock ? issue.livestock.livestock_id : 'N/A'}</td></tr>
                                        <tr><td><strong>Type:</strong></td><td>${issue.livestock ? issue.livestock.type : 'N/A'}</td></tr>
                                        <tr><td><strong>Breed:</strong></td><td>${issue.livestock ? issue.livestock.breed : 'N/A'}</td></tr>
                                        <tr><td><strong>Age:</strong></td><td>${issue.livestock ? issue.livestock.age + ' years' : 'N/A'}</td></tr>
                                        <tr><td><strong>Health Status:</strong></td><td>${issue.livestock ? issue.livestock.health_status : 'N/A'}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    <h6 class="mb-0"><i class="fas fa-file-alt"></i> Issue Details</h6>
                                </div>
                                <div class="card-body">
                                    <h6>Description:</h6>
                                    <p>${issue.description}</p>
                                    ${issue.notes ? `<h6>Additional Notes:</h6><p>${issue.notes}</p>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
        },
        error: function() {
            showToast('Error loading issue details', 'error');
        }
    });
}

function getSeverityColor(severity) {
    switch(severity) {
        case 'Low': return 'success';
        case 'Medium': return 'warning';
        case 'High': return 'danger';
        case 'Critical': return 'dark';
        default: return 'secondary';
    }
}

function getStatusColor(status) {
    switch(status) {
        case 'Pending': return 'warning';
        case 'In Progress': return 'info';
        case 'Resolved': return 'success';
        case 'Urgent': return 'danger';
        default: return 'secondary';
    }
}

function getPriorityColor(priority) {
    switch(priority) {
        case 'Low': return 'success';
        case 'Medium': return 'info';
        case 'High': return 'warning';
        case 'Urgent': return 'danger';
        default: return 'secondary';
    }
}



function exportToCSV() {
    const table = $('#issuesTable').DataTable();
    const data = table.data().toArray();
    
    let csv = 'Livestock ID,Animal Type,Breed,Issue Type,Description,Priority,Date Reported,Status,Reported By\n';
    data.forEach(row => {
        csv += `${row[0]},${row[1]},${row[2]},${row[3]},${row[4]},${row[5]},${row[6]},${row[7]},${row[8]}\n`;
    });
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'issues_report.csv';
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

// Inspection related functions
function viewInspectionDetails(inspectionId) {
    $.ajax({
        url: `/farmer/inspections/${inspectionId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const inspection = response.inspection;
                $('#inspectionDetailsContent').html(`
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-calendar"></i> Inspection Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr><td><strong>Date:</strong></td><td>${inspection.inspection_date}</td></tr>
                                        <tr><td><strong>Time:</strong></td><td>${inspection.inspection_time}</td></tr>
                                        <tr><td><strong>Status:</strong></td><td><span class="badge badge-${getInspectionStatusColor(inspection.status)}">${inspection.status}</span></td></tr>
                                        <tr><td><strong>Priority:</strong></td><td><span class="badge badge-${getInspectionPriorityColor(inspection.priority)}">${inspection.priority}</span></td></tr>
                                        <tr><td><strong>Scheduled By:</strong></td><td>${inspection.scheduled_by ? inspection.scheduled_by.name : 'Admin'}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    <h6 class="mb-0"><i class="fas fa-file-alt"></i> Notes & Findings</h6>
                                </div>
                                <div class="card-body">
                                    <h6>Notes:</h6>
                                    <p>${inspection.notes || 'No notes provided'}</p>
                                    ${inspection.findings ? `<h6>Findings:</h6><p>${inspection.findings}</p>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `);
                $('#inspectionDetailsModal').modal('show');
            }
        },
        error: function() {
            showToast('Error loading inspection details', 'error');
        }
    });
}

function markInspectionComplete(inspectionId) {
    if (confirm('Mark this inspection as complete?')) {
        $.ajax({
            url: `/farmer/inspections/${inspectionId}/complete`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showToast('Inspection marked as complete', 'success');
                    location.reload();
                } else {
                    showToast(response.message || 'Error completing inspection', 'error');
                }
            },
            error: function() {
                showToast('Error completing inspection', 'error');
            }
        });
    }
}

function getInspectionStatusColor(status) {
    switch(status) {
        case 'scheduled': return 'primary';
        case 'completed': return 'success';
        case 'cancelled': return 'danger';
        case 'rescheduled': return 'warning';
        default: return 'secondary';
    }
}

function getInspectionPriorityColor(priority) {
    switch(priority) {
        case 'low': return 'success';
        case 'medium': return 'info';
        case 'high': return 'warning';
        case 'urgent': return 'danger';
        default: return 'secondary';
    }
}

// Export functions for inspections
function exportInspectionsToCSV() {
    const table = $('#inspectionsTable').DataTable();
    const data = table.data().toArray();
    
    let csv = 'Inspection Date,Time,Priority,Status,Scheduled By,Notes\n';
    data.forEach(row => {
        csv += `${row[0]},${row[1]},${row[2]},${row[3]},${row[4]},${row[5]}\n`;
    });
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'scheduled_inspections.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

function exportInspectionsToPDF() {
    showToast('PDF export feature coming soon!', 'info');
}

function printInspectionsTable() {
    window.print();
}
</script>
@endpush

@push('styles')
<style>


.issue-type-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.issue-health {
    background: rgba(231, 74, 59, 0.1);
    color: #e74a3b;
    border: 1px solid rgba(231, 74, 59, 0.3);
}

.issue-injury {
    background: rgba(246, 194, 62, 0.1);
    color: #f6c23e;
    border: 1px solid rgba(246, 194, 62, 0.3);
}

.issue-production {
    background: rgba(54, 185, 204, 0.1);
    color: #36b9cc;
    border: 1px solid rgba(54, 185, 204, 0.3);
}

.issue-behavioral {
    background: rgba(102, 16, 242, 0.1);
    color: #6610f2;
    border: 1px solid rgba(102, 16, 242, 0.3);
}

.issue-environmental {
    background: rgba(78, 115, 223, 0.1);
    color: #4e73df;
    border: 1px solid rgba(78, 115, 223, 0.3);
}

.issue-other {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
    border: 1px solid rgba(108, 117, 125, 0.3);
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
