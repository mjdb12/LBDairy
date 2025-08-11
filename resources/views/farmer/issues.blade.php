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
        <p>Track and manage livestock health issues, injuries, and production concerns</p>
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
                        Issues & Alerts
                    </h6>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-primary btn-sm" onclick="openAddIssueModal()">
                            <i class="fas fa-plus"></i> Report New Issue
                        </button>
                        <div class="export-controls ml-3">
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
                                    <th>Date Reported</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($issues as $issue)
                                <tr class="{{ $issue->status === 'Urgent' ? 'table-danger' : ($issue->status === 'Pending' ? 'table-warning' : 'table-success') }}">
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
                                    <td>{{ $issue->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <select class="form-control form-control-sm" onchange="updateIssueStatus(this, '{{ $issue->id }}')">
                                            <option value="Pending" {{ $issue->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Resolved" {{ $issue->status === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                            <option value="Urgent" {{ $issue->status === 'Urgent' ? 'selected' : '' }}>Urgent</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-info" onclick="viewIssueDetails('{{ $issue->id }}')" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning" onclick="openEditIssueModal('{{ $issue->id }}')" title="Edit Issue">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $issue->id }}')" title="Delete Issue">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                                        <p>No issues reported. Your livestock are healthy and well-managed!</p>
                                        <button class="btn btn-primary" onclick="openAddIssueModal()">
                                            <i class="fas fa-plus"></i> Report First Issue
                                        </button>
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

<!-- Add/Edit Issue Modal -->
<div class="modal fade" id="issueModal" tabindex="-1" role="dialog" aria-labelledby="issueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="issueModalLabel">
                    <i class="fas fa-plus"></i>
                    Report New Issue
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="issueForm" method="POST" action="{{ route('farmer.issues.store') }}">
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
                                <label for="issue_type">Issue Type</label>
                                <select class="form-control" id="issue_type" name="issue_type" required>
                                    <option value="">Select Issue Type</option>
                                    <option value="Health">Health</option>
                                    <option value="Injury">Injury</option>
                                    <option value="Production">Production</option>
                                    <option value="Behavioral">Behavioral</option>
                                    <option value="Environmental">Environmental</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="severity">Severity Level</label>
                                <select class="form-control" id="severity" name="severity" required>
                                    <option value="">Select Severity</option>
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="Pending">Pending</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Resolved">Resolved</option>
                                    <option value="Urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title">Issue Title</label>
                        <input type="text" class="form-control" id="title" name="title" required placeholder="Brief description of the issue">
                    </div>
                    <div class="form-group">
                        <label for="description">Detailed Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Provide detailed information about the issue, symptoms, and any observations"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reported_date">Date Reported</label>
                                <input type="date" class="form-control" id="reported_date" name="reported_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="priority">Priority</label>
                                <select class="form-control" id="priority" name="priority" required>
                                    <option value="Low">Low</option>
                                    <option value="Normal">Normal</option>
                                    <option value="High">High</option>
                                    <option value="Emergency">Emergency</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notes">Additional Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional notes, treatment plans, or follow-up actions"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Issue
                    </button>
                </div>
            </form>
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
                <button type="button" class="btn btn-primary" onclick="editCurrentIssue()">
                    <i class="fas fa-edit"></i> Edit
                </button>
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
                Are you sure you want to delete this issue? This action cannot be undone.
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

@endsection

@push('scripts')
<script>
let currentIssueId = null;

$(document).ready(function() {
    // Initialize DataTable
    $('#issuesTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[5, 'desc']], // Sort by date reported
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

    // Handle form submission
    $('#issueForm').on('submit', function(e) {
        e.preventDefault();
        submitIssueForm();
    });
});

function openAddIssueModal() {
    $('#issueModalLabel').html('<i class="fas fa-plus"></i> Report New Issue');
    $('#issueForm')[0].reset();
    $('#issueForm').attr('action', '{{ route("farmer.issues.store") }}');
    $('#issueForm').attr('method', 'POST');
    $('#reported_date').val('{{ date("Y-m-d") }}');
    $('#issueModal').modal('show');
}

function openEditIssueModal(issueId) {
    currentIssueId = issueId;
    $('#issueModalLabel').html('<i class="fas fa-edit"></i> Edit Issue');
    $('#issueForm').attr('action', `/farmer/issues/${issueId}`);
    $('#issueForm').attr('method', 'POST');
    $('#issueForm').append('<input type="hidden" name="_method" value="PUT">');
    
    // Load issue data
    loadIssueData(issueId);
    $('#issueModal').modal('show');
}

function viewIssueDetails(issueId) {
    currentIssueId = issueId;
    loadIssueDetails(issueId);
    $('#issueDetailsModal').modal('show');
}

function loadIssueData(issueId) {
    $.ajax({
        url: `/farmer/issues/${issueId}/edit`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const issue = response.issue;
                $('#livestock_id').val(issue.livestock_id);
                $('#issue_type').val(issue.issue_type);
                $('#severity').val(issue.severity);
                $('#status').val(issue.status);
                $('#title').val(issue.title);
                $('#description').val(issue.description);
                $('#reported_date').val(issue.reported_date);
                $('#priority').val(issue.priority);
                $('#notes').val(issue.notes);
            }
        },
        error: function() {
            showToast('Error loading issue data', 'error');
        }
    });
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
                                        <tr><td><strong>Title:</strong></td><td>${issue.title}</td></tr>
                                        <tr><td><strong>Type:</strong></td><td><span class="issue-type-badge issue-${issue.issue_type.toLowerCase()}">${issue.issue_type}</span></td></tr>
                                        <tr><td><strong>Severity:</strong></td><td><span class="badge badge-${getSeverityColor(issue.severity)}">${issue.severity}</span></td></tr>
                                        <tr><td><strong>Status:</strong></td><td><span class="badge badge-${getStatusColor(issue.status)}">${issue.status}</span></td></tr>
                                        <tr><td><strong>Priority:</strong></td><td><span class="badge badge-${getPriorityColor(issue.priority)}">${issue.priority}</span></td></tr>
                                        <tr><td><strong>Reported:</strong></td><td>${issue.reported_date}</td></tr>
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
        case 'Normal': return 'info';
        case 'High': return 'warning';
        case 'Emergency': return 'danger';
        default: return 'secondary';
    }
}

function updateIssueStatus(selectElement, issueId) {
    const newStatus = selectElement.value;
    
    $.ajax({
        url: `/farmer/issues/${issueId}/status`,
        method: 'PATCH',
        data: {
            status: newStatus,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showToast(`Issue status updated to ${newStatus}`, 'success');
                // Update row styling based on new status
                const row = $(selectElement).closest('tr');
                row.removeClass('table-danger table-warning table-success');
                if (newStatus === 'Urgent') {
                    row.addClass('table-danger');
                } else if (newStatus === 'Pending') {
                    row.addClass('table-warning');
                } else if (newStatus === 'Resolved') {
                    row.addClass('table-success');
                }
            } else {
                showToast(response.message || 'Error updating status', 'error');
            }
        },
        error: function() {
            showToast('Error updating issue status', 'error');
            // Reset select to previous value
            $(selectElement).val($(selectElement).find('option:selected').attr('data-original-value'));
        }
    });
}

function submitIssueForm() {
    const formData = new FormData($('#issueForm')[0]);
    
    $.ajax({
        url: $('#issueForm').attr('action'),
        method: $('#issueForm').attr('method'),
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                $('#issueModal').modal('hide');
                location.reload();
            } else {
                showToast(response.message || 'Error saving issue', 'error');
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(field => {
                    showToast(errors[field][0], 'error');
                });
            } else {
                showToast('Error saving issue', 'error');
            }
        }
    });
}

function confirmDelete(issueId) {
    currentIssueId = issueId;
    $('#confirmDeleteModal').modal('show');
}

$('#confirmDeleteBtn').on('click', function() {
    if (currentIssueId) {
        deleteIssue(currentIssueId);
    }
});

function deleteIssue(issueId) {
    $.ajax({
        url: `/farmer/issues/${issueId}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                $('#confirmDeleteModal').modal('hide');
                location.reload();
            } else {
                showToast(response.message || 'Error deleting issue', 'error');
            }
        },
        error: function() {
            showToast('Error deleting issue', 'error');
        }
    });
}

function editCurrentIssue() {
    if (currentIssueId) {
        $('#issueDetailsModal').modal('hide');
        openEditIssueModal(currentIssueId);
    }
}

function exportToCSV() {
    const table = $('#issuesTable').DataTable();
    const data = table.data().toArray();
    
    let csv = 'Livestock ID,Animal Type,Breed,Issue Type,Description,Date Reported,Status\n';
    data.forEach(row => {
        csv += `${row[0]},${row[1]},${row[2]},${row[3]},${row[4]},${row[5]},${row[6]}\n`;
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
