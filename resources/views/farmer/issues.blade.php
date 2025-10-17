@extends('layouts.app')

@section('title', 'Issue Management')

@section('content')
    <!-- Page Header -->
    <div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
        <h1>
            <i class="fas fa-exclamation-triangle"></i>
            Issue Management
        </h1>
        <p>View issues reported by administrators for your livestock</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row fade-in">
        <!-- Total Issues -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Issues</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalIssues }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-exclamation-triangle fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Issues -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Pending</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingIssues }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-clock fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Urgent Issues -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Urgent</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $urgentIssues }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-fire fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resolved Issues -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Resolved</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $resolvedIssues }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-check-circle fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


 <!-- Alerts Section -->
@if($urgentIssues > 0)
<div class="alert alert-danger alert-dismissible fade show refresh-notification" role="alert">
    <i class="fas fa-times-circle"></i>
    <strong>Urgent Issues Detected!</strong>
    You have {{ $urgentIssues }} urgent issues that require immediate attention.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if($pendingIssues > 0)
<div class="alert alert-warning alert-dismissible fade show refresh-notification" role="alert">
    <i class="fas fa-exclamation-triangle"></i>
    <strong>Pending Issues:</strong>
    You have {{ $pendingIssues }} issues awaiting resolution.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<!-- Livestock Alerts Section -->
@if($livestockAlerts && $livestockAlerts->count() > 0)
<div class="alert alert-info alert-dismissible fade show refresh-notification" role="alert">
    <i class="fas fa-bell"></i>
    <strong>New Livestock Alerts:</strong>
    You have {{ $livestockAlerts->count() }} new alerts from administrators about your livestock.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif


    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow fade-in">
                <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
                    <h6 class="mb-0">
                        <i class="fas fa-list"></i>
                        Issues Reported by Administrators
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
                            <input type="text" class="form-control" placeholder="Search issues..." id="issueSearch">
                        </div>
                        <div class="d-flex flex-column flex-sm-row align-items-center">
                            <button class="btn-action btn-action-edit" onclick="printTable()">
                                <i class="fas fa-print"></i> Print
                            </button>
                            <button class="btn-action btn-action-refresh" onclick="refreshIssuesTable('issuesTable')">
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
                        <table class="table table-bordered " id="issuesTable" width="100%" cellspacing="0">
                            <thead >
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
                                {{-- Debug: Issues count: {{ $issues->count() }} --}}
                                @forelse($issues as $issue)
                                <tr>
                                    <td>
                                        <strong>{{ $issue->livestock->tag_number ?? 'N/A' }}</strong>
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
                                        <div class="btn-group">
                                            <button class="btn-action btn-action-ok" onclick="viewIssueDetails('{{ $issue->id }}')" title="View Details">
                                                <i class="fas fa-eye"></i>
                                                <span>View Details</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">No issues reported by administrators</td>
                                    <td class="text-center text-muted">N/A</td>
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

    <!-- Livestock Alerts Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow fade-in">
                <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
                    <h6 class="mb-0">
                        <i class="fas fa-bell"></i>
                        Livestock Alerts from Administrators
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
                            <button class="btn-action btn-action-edit" onclick="printAlertsTable()">
                                <i class="fas fa-print"></i> Print
                            </button>
                            <button class="btn-action btn-action-refresh-alerts" onclick="refreshAlertsTable('alertsTable')">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                            <div class="dropdown">
                                <button class="btn-action btn-action-tools" type="button" data-toggle="dropdown">
                                    <i class="fas fa-tools"></i> Tools
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#" onclick="exportAlertsToCSV()">
                                        <i class="fas fa-file-csv"></i> Download CSV
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="exportAlertsToPNG()">
                                        <i class="fas fa-image"></i> Download PNG
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="exportAlertsToPDF()">
                                        <i class="fas fa-file-pdf"></i> Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="alertsTable" width="100%" cellspacing="0">
                            <thead >
                                <tr>
                                    <th>Livestock ID</th>
                                    <th>Animal Type</th>
                                    <th>Breed</th>
                                    <th>Alert Topic</th>
                                    <th>Description</th>
                                    <th>Severity</th>
                                    <th>Alert Date</th>
                                    <th>Issued By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($livestockAlerts as $alert)
                                <tr>
                                    <td>
                                        <strong>{{ $alert->livestock->tag_number ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $alert->livestock->type ?? 'N/A' }}</td>
                                    <td>{{ $alert->livestock->breed ?? 'N/A' }}</td>
                                    <td>
                                        <span class="alert-topic-badge alert-{{ strtolower($alert->topic) }}">
                                            {{ $alert->topic }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($alert->description, 50) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $alert->severity === 'critical' ? 'danger' : ($alert->severity === 'high' ? 'warning' : ($alert->severity === 'medium' ? 'info' : 'success')) }}">
                                            {{ ucfirst($alert->severity) }}
                                        </span>
                                    </td>
                                    <td>{{ $alert->alert_date ? $alert->alert_date->format('M d, Y') : $alert->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ $alert->issuedBy->name ?? 'Admin' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn-action btn-action-ok" onclick="viewAlertDetails('{{ $alert->id }}')" title="View Details">
                                                <i class="fas fa-eye"></i>
                                                <span>View Details</span>
                                            </button>
                                            <button class="btn-action btn-action-edit" onclick="markAlertAsRead('{{ $alert->id }}')" title="Mark as Read">
                                                <i class="fas fa-check"></i>
                                                <span>Mark as Read</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">No livestock alerts at this time</td>
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

    <!-- Scheduled Inspections Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow fade-in">
                <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
                    <h6 class="mb-0">
                        <i class="fas fa-calendar-check"></i>
                        Scheduled Farm Inspections
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
                            <input type="text" class="form-control" placeholder="Search inspections..." id="inspectionSearch">
                        </div>
                        <div class="d-flex align-items-center justify-content-center flex-nowrap gap-2 action-toolbar">
                            <button class="btn-action btn-action-refresh-inspection" onclick="refreshInspectionTable('inspectionsTable')">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                            <div class="dropdown">
                                <button class="btn-action btn-action-tools" type="button" data-toggle="dropdown">
                                    <i class="fas fa-tools"></i> Tools
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#" onclick="printInspectionsTable()">
                                        <i class="fas fa-print"></i> Print Table
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="exportInspectionsToCSV()">
                                        <i class="fas fa-file-csv"></i> Download CSV
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="exportInspectionsToPNG()">
                                        <i class="fas fa-image"></i> Download PNG
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="exportInspectionsToPDF()">
                                        <i class="fas fa-file-pdf"></i> Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="inspectionsTable" width="100%" cellspacing="0">
                            <thead >
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
                                <tr>
                                    <td>
                                        <strong>{{ $inspection->inspection_date ? \Carbon\Carbon::parse($inspection->inspection_date)->format('M d, Y') : 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $inspection->inspection_time ? \Carbon\Carbon::parse($inspection->inspection_time)->format('h:i A') : 'N/A' }}</td>
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
                                        <div class="btn-group">
                                            <button class="btn-action btn-action-ok" onclick="viewInspectionDetails('{{ $inspection->id }}')" title="View Details">
                                                <i class="fas fa-eye"></i>View
                                            </button>
                                            @if($inspection->status === 'scheduled')
                                            <button class="btn-action btn-action-edit" onclick="markInspectionComplete('{{ $inspection->id }}')" title="Mark Complete">
                                                <i class="fas fa-check"></i>Complete
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">No scheduled inspections at this time</td>
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

<!-- Issue Modal -->
<div class="modal fade admin-modal" id="issueDetailsModal" tabindex="-1" role="dialog" aria-labelledby="issueDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

        <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-info-circle fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">Issue Details</h5>
                <p class="text-muted mb-0 small text-center">Below are the complete details of the selected issue.</p>
            </div>

      <!-- Body -->
      <div class="modal-body">
        <div id="issueDetailsContent" class="detail-wrapper">
          <!-- Dynamic details injected here -->
        </div>
      </div>

        <div class="modal-footer justify-content-center mt-4">
            <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
        </div>

    </div>
  </div>
</div>
<!-- Issue Modal -->
<div class="modal fade admin-modal" id="alertDetailsModal" tabindex="-1" role="dialog" aria-labelledby="alertDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

        <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-info-circle fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">Alert Details</h5>
                <p class="text-muted mb-0 small text-center">Below are the complete details of the selected issue.</p>
            </div>

      <!-- Body -->
      <div class="modal-body">
        <div id="alertDetailsContent" class="detail-wrapper">
          <!-- Dynamic details injected here -->
        </div>
      </div>

        <div class="modal-footer justify-content-center mt-4">
            <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
        </div>

    </div>
  </div>
</div>
<!-- Inspection Modal -->
<div class="modal fade admin-modal" id="inspectionDetailsModal" tabindex="-1" role="dialog" aria-labelledby="inspectionDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

        <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-info-circle fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">Inspection Details</h5>
                <p class="text-muted mb-0 small text-center">Below are the complete details of the selected issue.</p>
            </div>

      <!-- Body -->
      <div class="modal-body">
        <div id="inspectionDetailsContent" class="detail-wrapper">
          <!-- Dynamic details injected here -->
        </div>
      </div>

        <div class="modal-footer justify-content-center mt-4">
            <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
        </div>

    </div>
  </div>
</div>

<!-- Delete Issue Confirmation Modal -->
<div class="modal fade" id="completeInspectionModal" tabindex="-1" role="dialog" aria-labelledby="completeInspectionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeInspectionLabel">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Mark as Complete
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to mark this inpection complete? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-action btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn-action btn-action-edit" id="completeInspectionBtn">
                    <i class="fas fa-trash"></i> Mark as Complete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bottom spacing to match farm analysis tab -->
<div style="margin-bottom: 3rem;"></div>

@endsection

@push('scripts')
<!-- DataTables CSS and Core -->
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

<!-- jsPDF and autoTable for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
let currentIssueId = null;
let downloadCounter = 1;

$(document).ready(function() {
    // Initialize DataTables with EXACT Super Admin configuration
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
                title: 'Farmer_Issues_Report',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Farmer_Issues_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Farmer Issues Report',
                className: 'd-none'
            }
        ],
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No data available</h5><p>There are no records to display at this time.</p></div>'
        }
    };

    // Initialize Issues Table
    if ($('#issuesTable').length) {
        const issuesTable = $('#issuesTable');
        const hasData = issuesTable.find('tbody tr').length > 0;
        const hasEmptyRow = issuesTable.find('tbody tr td[colspan]').length > 0;
        
        console.log('Issues Table - Has data:', hasData, 'Has empty row:', hasEmptyRow);
        
        if (hasData && !hasEmptyRow) {
            try {
                const dt = issuesTable.DataTable({
                    ...commonConfig,
                    order: [[6, 'desc']], // Sort by date reported
                    columnDefs: [
                        { width: '100px', targets: 0 }, // Livestock ID
                        { width: '120px', targets: 1 }, // Animal Type
                        { width: '120px', targets: 2 }, // Breed
                        { width: '140px', targets: 3 }, // Issue Type
                        { width: '200px', targets: 4 }, // Description
                        { width: '100px', targets: 5 }, // Priority
                        { width: '140px', targets: 6 }, // Date Reported
                        { width: '120px', targets: 7 }, // Status
                        { width: '140px', targets: 8 }, // Reported By
                        { width: '180px', targets: 9, orderable: false } // Actions
                    ],
                    buttons: [
                        { extend: 'csvHtml5', title: 'Farmer_Issues_Report', className: 'd-none', exportOptions: { columns: [0,1,2,3,4,5,6,7,8], modifier: { page: 'all' } } },
                        { extend: 'pdfHtml5', title: 'Farmer_Issues_Report', orientation: 'landscape', pageSize: 'Letter', className: 'd-none', exportOptions: { columns: [0,1,2,3,4,5,6,7,8], modifier: { page: 'all' } } },
                        { extend: 'print', title: 'Farmer Issues Report', className: 'd-none', exportOptions: { columns: [0,1,2,3,4,5,6,7,8], modifier: { page: 'all' } } }
                    ]
                });
                console.log('Issues DataTable initialized successfully');
            } catch (error) {
                console.error('Error initializing Issues DataTable:', error);
            }
        } else {
            console.log('Issues table not initialized - no data or has empty row');
        }
    }

    // Initialize Alerts Table
    if ($('#alertsTable').length) {
        const alertsTable = $('#alertsTable');
        const hasEmptyRow = alertsTable.find('tbody tr td[colspan]').length > 0;
        
        if (!hasEmptyRow) {
            alertsTable.DataTable({
                ...commonConfig,
                order: [[6, 'desc']], // Sort by alert date
                buttons: [
                    { extend: 'csvHtml5', title: 'Farmer_Alerts_Report', className: 'd-none', exportOptions: { columns: [0,1,2,3,4,5,6,7], modifier: { page: 'all' } } },
                    { extend: 'pdfHtml5', title: 'Farmer_Alerts_Report', orientation: 'landscape', pageSize: 'Letter', className: 'd-none', exportOptions: { columns: [0,1,2,3,4,5,6,7], modifier: { page: 'all' } } },
                    { extend: 'print', title: 'Farmer Alerts Report', className: 'd-none', exportOptions: { columns: [0,1,2,3,4,5,6,7], modifier: { page: 'all' } } }
                ],
                columnDefs: [
                    { width: '100px', targets: 0 }, // Livestock ID
                    { width: '120px', targets: 1 }, // Animal Type
                    { width: '120px', targets: 2 }, // Breed
                    { width: '140px', targets: 3 }, // Alert Topic
                    { width: '200px', targets: 4 }, // Description
                    { width: '100px', targets: 5 }, // Severity
                    { width: '140px', targets: 6 }, // Alert Date
                    { width: '140px', targets: 7 }, // Issued By
                    { width: '220px', targets: 8, orderable: false } // Actions
                ]
            });
        }
    }

    // Initialize Inspections Table
    if ($('#inspectionsTable').length) {
        const inspectionsTable = $('#inspectionsTable');
        const hasEmptyRow = inspectionsTable.find('tbody tr td[colspan]').length > 0;
        
        if (!hasEmptyRow) {
            inspectionsTable.DataTable({
                ...commonConfig,
                order: [[0, 'asc']], // Sort by inspection date
                buttons: [
                    { extend: 'csvHtml5', title: 'Farmer_Inspections_Report', className: 'd-none', exportOptions: { columns: [0,1,2,3,4,5], modifier: { page: 'all' } } },
                    { extend: 'pdfHtml5', title: 'Farmer_Inspections_Report', orientation: 'landscape', pageSize: 'Letter', className: 'd-none', exportOptions: { columns: [0,1,2,3,4,5], modifier: { page: 'all' } } },
                    { extend: 'print', title: 'Farmer Inspections Report', className: 'd-none', exportOptions: { columns: [0,1,2,3,4,5], modifier: { page: 'all' } } }
                ],
                columnDefs: [
                    { width: '140px', targets: 0 }, // Inspection Date
                    { width: '120px', targets: 1 }, // Time
                    { width: '100px', targets: 2 }, // Priority
                    { width: '120px', targets: 3 }, // Status
                    { width: '140px', targets: 4 }, // Scheduled By
                    { width: '250px', targets: 5 }, // Notes
                    { width: '200px', targets: 6, orderable: false } // Actions
                ]
            });
        }
    }

    // Hide default DataTables search boxes (we use custom ones)
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
    
    // Connect custom search boxes to DataTables
    $('#issueSearch').on('keyup', function() {
        if ($.fn.DataTable.isDataTable('#issuesTable')) {
            $('#issuesTable').DataTable().search(this.value).draw();
        }
    });
    
    $('#alertSearch').on('keyup', function() {
        if ($.fn.DataTable.isDataTable('#alertsTable')) {
            $('#alertsTable').DataTable().search(this.value).draw();
        }
    });
    
    $('#inspectionSearch').on('keyup', function() {
        if ($.fn.DataTable.isDataTable('#inspectionsTable')) {
            $('#inspectionsTable').DataTable().search(this.value).draw();
        }
    });
});


// Refresh Admins Table
function refreshIssuesTable() {
    const refreshBtn = document.querySelector('.btn-action-refresh');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    refreshBtn.disabled = true;

    // Use unique flag for admins
    sessionStorage.setItem('showRefreshNotificationIssues', 'true');

    setTimeout(() => {
        location.reload();
    }, 1000);
}

function refreshAlertsTable() {
    const refreshBtn = document.querySelector('.btn-action-refresh-alerts');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    refreshBtn.disabled = true;

    // Use unique flag for admins
    sessionStorage.setItem('showRefreshNotificationAlerts', 'true');

    setTimeout(() => {
        location.reload();
    }, 1000);
}

function refreshInspectionTable() {
    const refreshBtn = document.querySelector('.btn-action-refresh-inspection');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    refreshBtn.disabled = true;

    // Use unique flag for admins
    sessionStorage.setItem('showRefreshNotificationInspection', 'true');

    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Check notifications after reload
$(document).ready(function() {
    if (sessionStorage.getItem('showRefreshNotificationIssues') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationIssues');
        setTimeout(() => {
            showNotification('Issues data refreshed successfully!');
        }, 500);
    }

    if (sessionStorage.getItem('showRefreshNotificationAlerts') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationAlerts');
        setTimeout(() => {
            showNotification('Alerts data refreshed successfully!', 'success');
        }, 500);
    }

    if (sessionStorage.getItem('showRefreshNotificationInspection') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationInspection');
        setTimeout(() => {
            showNotification('Inspection data refreshed successfully!', 'success');
        }, 500);
    }
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
                        <!-- Issue Information -->
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Issue Information</h6>
                            <p><strong>Type:</strong> <span class="issue-type-badge issue-${issue.issue_type.toLowerCase()}">${issue.issue_type}</span></p>
                            <p><strong>Status:</strong> <span class="badge badge-${getStatusColor(issue.status)}">${issue.status}</span></p>
                            <p><strong>Priority:</strong> <span class="badge badge-${getPriorityColor(issue.priority)}">${issue.priority}</span></p>
                            <p><strong>Reported:</strong> ${issue.date_reported}</p>
                            <p><strong>Reported By:</strong> <span class="badge badge-primary">${issue.reported_by ? issue.reported_by.name : 'Admin'}</span></p>
                        </div>

                        <!-- Livestock Information -->
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Livestock Information</h6>
                            <p><strong>ID:</strong> ${issue.livestock ? issue.livestock.livestock_id : 'N/A'}</p>
                            <p><strong>Type:</strong> ${issue.livestock ? issue.livestock.type : 'N/A'}</p>
                            <p><strong>Breed:</strong> ${issue.livestock ? issue.livestock.breed : 'N/A'}</p>
                            <p><strong>Age:</strong> ${issue.livestock ? issue.livestock.age + ' years' : 'N/A'}</p>
                            <p><strong>Health Status:</strong> ${issue.livestock ? issue.livestock.health_status : 'N/A'}</p>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <!-- Issue Details -->
                        <div class="col-12">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Issue Details</h6>
                            <p><strong>Description:</strong></p>
                            <p>${issue.description}</p>
                            ${issue.notes ? `
                                <p><strong>Additional Notes:</strong></p>
                                <p>${issue.notes}</p>
                            ` : ''}
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
    const s = (severity || '').toString().toLowerCase();
    switch(s) {
        case 'low': return 'success';
        case 'medium': return 'warning';
        case 'high': return 'danger';
        case 'critical': return 'danger';
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
    try {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#issuesTable')) {
            $('#issuesTable').DataTable().button('.buttons-csv').trigger();
        }
    } catch (e) {
        console.error('exportToCSV error:', e);
    }
}

function exportToPDF() {
    try {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#issuesTable')) {
            $('#issuesTable').DataTable().button('.buttons-pdf').trigger();
        }
    } catch (e) {
        console.error('exportToPDF error:', e);
    }
}

function exportToPNG() {
    // Create a temporary table without the Actions column for export
    const originalTable = document.getElementById('issuesTable');
    const tempTable = originalTable.cloneNode(true);
    
    // Remove the Actions column header
    const headerRow = tempTable.querySelector('thead tr');
    if (headerRow) {
        const lastHeaderCell = headerRow.lastElementChild;
        if (lastHeaderCell) {
            lastHeaderCell.remove();
        }
    }
    
    // Remove the Actions column from all data rows
    const dataRows = tempTable.querySelectorAll('tbody tr');
    dataRows.forEach(row => {
        const lastDataCell = row.lastElementChild;
        if (lastDataCell) {
            lastDataCell.remove();
        }
    });
    
    // Temporarily add the temp table to the DOM (hidden)
    tempTable.style.position = 'absolute';
    tempTable.style.left = '-9999px';
    tempTable.style.top = '-9999px';
    document.body.appendChild(tempTable);
    
    // Generate PNG using html2canvas
    html2canvas(tempTable, {
        scale: 2, // Higher quality
        backgroundColor: '#ffffff',
        width: tempTable.offsetWidth,
        height: tempTable.offsetHeight
    }).then(canvas => {
        // Create download link
        const link = document.createElement('a');
        link.download = `Farmer_IssuesReport_${downloadCounter}.png`;
        link.href = canvas.toDataURL("image/png");
        link.click();
        
        // Increment download counter
        downloadCounter++;
        
        // Clean up - remove temporary table
        document.body.removeChild(tempTable);
        
        showToast('PNG exported successfully!', 'success');
    }).catch(error => {
        console.error('Error generating PNG:', error);
        // Clean up on error
        if (document.body.contains(tempTable)) {
            document.body.removeChild(tempTable);
        }
        showToast('Error generating PNG export', 'error');
    });
}

function printTable() {
    try {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#issuesTable')) {
            $('#issuesTable').DataTable().button('.buttons-print').trigger();
        } else {
            window.print();
        }
    } catch (e) {
        console.error('printTable error:', e);
        window.print();
    }
}

function showToast(message, type = 'info') {
    const toast = `
        <div class="alert alert-${type} alert-dismissible fade show refresh-notification">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'times-circle'}"></i>
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
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
        <h6 class="mb-3" style="color: #18375d; font-weight: 600;">
            <i class="fas fa-calendar-check mr-2"></i>Inspection Information
        </h6>
        <p><strong>Date:</strong> ${inspection.inspection_date}</p>
        <p><strong>Time:</strong> ${inspection.inspection_time}</p>
        <p>
            <strong>Status:</strong>
            <span class="badge badge-${getInspectionStatusColor(inspection.status)}">
                <i class="fas fa-${inspection.status === 'Completed' ? 'check-circle' : 'clock'} mr-1"></i>
                ${inspection.status}
            </span>
        </p>
        <p>
            <strong>Priority:</strong>
            <span class="badge badge-${getInspectionPriorityColor(inspection.priority)}">
                ${inspection.priority}
            </span>
        </p>
        <p><strong>Scheduled By:</strong> ${inspection.scheduled_by ? inspection.scheduled_by.name : 'Admin'}</p>
    </div>

    <div class="col-md-6">
        <h6 class="mb-3" style="color: #18375d; font-weight: 600;">
            <i class="fas fa-file-alt mr-2"></i>Notes & Findings
        </h6>
        <p><strong>Notes:</strong> ${inspection.notes || 'No notes provided.'}</p>
        ${inspection.findings ? `
        <p><strong>Findings:</strong> ${inspection.findings}</p>
        ` : ''}
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

let currentInspectionId = null;

// Function to open modal
function openCompleteInspectionModal(inspectionId) {
    currentInspectionId = inspectionId;
    $('#completeInspectionModal').modal('show');
}

// Handle button click inside modal
$('#completeInspectionBtn').on('click', function() {
    if (currentInspectionId) {
        markInspectionComplete(currentInspectionId);
        $('#completeInspectionModal').modal('hide');
    }
});

// Existing function
function markInspectionComplete(inspectionId) {
    $.ajax({
        url: `/farmer/inspections/${inspectionId}/complete`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showToast('Inspection marked as complete', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(response.message || 'Error completing inspection', 'error');
            }
        },
        error: function() {
            showToast('Error completing inspection', 'error');
        }
    });
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
    try {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#inspectionsTable')) {
            $('#inspectionsTable').DataTable().button('.buttons-csv').trigger();
        }
    } catch (e) {
        console.error('exportInspectionsToCSV error:', e);
    }
}

function exportInspectionsToPDF() {
    try {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#inspectionsTable')) {
            $('#inspectionsTable').DataTable().button('.buttons-pdf').trigger();
        }
    } catch (e) {
        console.error('exportInspectionsToPDF error:', e);
    }
}

function exportInspectionsToPNG() {
    // Create a temporary table without the Actions column for export
    const originalTable = document.getElementById('inspectionsTable');
    const tempTable = originalTable.cloneNode(true);
    
    // Remove the Actions column header
    const headerRow = tempTable.querySelector('thead tr');
    if (headerRow) {
        const lastHeaderCell = headerRow.lastElementChild;
        if (lastHeaderCell) {
            lastHeaderCell.remove();
        }
    }
    
    // Remove the Actions column from all data rows
    const dataRows = tempTable.querySelectorAll('tbody tr');
    dataRows.forEach(row => {
        const lastDataCell = row.lastElementChild;
        if (lastDataCell) {
            lastDataCell.remove();
        }
    });
    
    // Temporarily add the temp table to the DOM (hidden)
    tempTable.style.position = 'absolute';
    tempTable.style.left = '-9999px';
    tempTable.style.top = '-9999px';
    document.body.appendChild(tempTable);
    
    // Generate PNG using html2canvas
    html2canvas(tempTable, {
        scale: 2, // Higher quality
        backgroundColor: '#ffffff',
        width: tempTable.offsetWidth,
        height: tempTable.offsetHeight
    }).then(canvas => {
        // Create download link
        const link = document.createElement('a');
        link.download = `Farmer_InspectionsReport_${downloadCounter}.png`;
        link.href = canvas.toDataURL("image/png");
        link.click();
        
        // Increment download counter
        downloadCounter++;
        
        // Clean up - remove temporary table
        document.body.removeChild(tempTable);
        
        showToast('PNG exported successfully!', 'success');
    }).catch(error => {
        console.error('Error generating PNG:', error);
        // Clean up on error
        if (document.body.contains(tempTable)) {
            document.body.removeChild(tempTable);
        }
        showToast('Error generating PNG export', 'error');
    });
}

function printInspectionsTable() {
    try {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#inspectionsTable')) {
            $('#inspectionsTable').DataTable().button('.buttons-print').trigger();
        } else {
            window.print();
        }
    } catch (e) {
        console.error('printInspectionsTable error:', e);
        window.print();
    }
}

// Trigger DataTables print for Livestock Alerts table
function printAlertsTable() {
    try {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#alertsTable')) {
            $('#alertsTable').DataTable().button('.buttons-print').trigger();
        } else {
            window.print();
        }
    } catch (e) {
        console.error('printAlertsTable error:', e);
        window.print();
    }
}

// Alerts export helpers
function exportAlertsToCSV() {
    try {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#alertsTable')) {
            $('#alertsTable').DataTable().button('.buttons-csv').trigger();
        }
    } catch (e) {
        console.error('exportAlertsToCSV error:', e);
    }
}

function exportAlertsToPDF() {
    try {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#alertsTable')) {
            $('#alertsTable').DataTable().button('.buttons-pdf').trigger();
        }
    } catch (e) {
        console.error('exportAlertsToPDF error:', e);
    }
}

function exportAlertsToPNG() {
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
            console.error('exportAlertsToPNG error:', err);
            if (document.body.contains(tempTable)) document.body.removeChild(tempTable);
        });
    } catch (e) {
        console.error('exportAlertsToPNG wrapper error:', e);
    }
}

function viewAlertDetails(alertId) {
    currentAlertId = alertId;

    $.ajax({
        url: `/farmer/issue-alerts/${alertId}`,
        method: 'GET',
        success: function (response) {
            if (response.success) {
                const alert = response.alert || {};
                const issuedRel = alert.issuedBy || alert.issued_by; // handle relation vs FK id
                const issuedName = issuedRel && typeof issuedRel === 'object' && issuedRel.name ? issuedRel.name : 'You';
                const safeTopic = (alert.topic || '').toString();
                const safeSeverity = (alert.severity || '').toString();
                $('#issueDetailsContent').html(`
                    <div class="row">
                        <!-- Alert Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    <h6 class="mb-0"><i class="fas fa-bell"></i> Alert Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr><td><strong>Topic:</strong></td><td><span class="alert-topic-badge alert-${safeTopic.toLowerCase()}">${safeTopic}</span></td></tr>
                                        <tr><td><strong>Severity:</strong></td><td><span class="badge badge-${getSeverityColor(safeSeverity)}">${safeSeverity}</span></td></tr>
                                        <tr><td><strong>Alert Date:</strong></td><td>${alert.alert_date || 'N/A'}</td></tr>
                                        <tr><td><strong>Issued By:</strong></td><td><span class="badge badge-primary">${issuedName}</span></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Livestock Information -->
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">
                                Livestock Information
                            </h6>
                            <p><strong>Tag Number:</strong> ${alert.livestock ? alert.livestock.tag_number : 'N/A'}</p>
                            <p><strong>Type:</strong> ${alert.livestock ? alert.livestock.type : 'N/A'}</p>
                            <p><strong>Breed:</strong> ${alert.livestock ? alert.livestock.breed : 'N/A'}</p>
                            <p><strong>Health Status:</strong> ${alert.livestock ? alert.livestock.health_status : 'N/A'}</p>
                            <p><strong>Farm:</strong> ${alert.livestock && alert.livestock.farm ? alert.livestock.farm.name : 'N/A'}</p>
                        </div>
                    </div>

                    <!-- Alert Details -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">
                                Alert Details
                            </h6>
                            <p><strong>Description:</strong></p>
                            <p>${alert.description || 'No description provided.'}</p>
                            
                            ${alert.resolution_notes ? `
                                <p><strong>Resolution Notes:</strong></p>
                                <p>${alert.resolution_notes}</p>
                            ` : ''}
                        </div>
                    </div>
                `);

                $('#issueDetailsModal').modal('show');
            } else {
                showToast('Failed to load alert details', 'error');
            }
        },
        error: function () {
            showToast('Error loading alert details', 'error');
        }
    });
}


function markAlertAsRead(alertId) {
    $.ajax({
        url: `/farmer/alerts/${alertId}/mark-read`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showNotification('Alert marked as read', 'success');
                // Remove the alert row from the table
                $(`button[onclick="markAlertAsRead('${alertId}')"]`).closest('tr').fadeOut();
            } else {
                showToast(response.message || 'Error marking alert as read', 'error');
            }
        },
        error: function() {
            showToast('Error marking alert as read', 'error');
        }
    });
}

<<<<<<< HEAD
function showNotification(message, type = 'info') {
    const icon = type === 'success' ? 'check-circle' : (type === 'warning' ? 'exclamation-triangle' : (type === 'danger' ? 'times-circle' : 'info-circle'));
    const notification = $(`
        <div class="alert alert-${type} alert-dismissible fade show refresh-notification" style="position: fixed; top: 1rem; right: 1rem; z-index: 1060;">
            <i class="fas fa-${icon}"></i>
=======
function showNotification(message, type) {
    const notification = $(`
        <div class="alert alert-${type} alert-dismissible fade show refresh-notification">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'times-circle'}"></i>
>>>>>>> a314ef8f855da84d4c74eef223c1412319ebb9f6
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `);
<<<<<<< HEAD
    $('body').append(notification);
    setTimeout(() => { notification.alert('close'); }, 4000);
}
=======
    
    $('body').append(notification);
    
    setTimeout(() => {
        notification.alert('close');
    }, 5000);
}

>>>>>>> a314ef8f855da84d4c74eef223c1412319ebb9f6
</script>
@endpush

@push('styles')
<style>
    /* User Details Modal Styling */
    #alertDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #alertDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #alertDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #alertDetailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #alertDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #alertDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #alertDetailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }
    /* User Details Modal Styling */
    #issueDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #issueDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #issueDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #issueDetailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    /* User Details Modal Styling */
    #inspectionDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #inspectionDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #inspectionDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #inspectionDetailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #inspectionDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #inspectionDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #inspectionDetailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }
    /* User Details Modal Styling */
    #issueDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #issueDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #issueDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #issueDetailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #issueDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #issueDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #issueDetailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }
    /* SMART DETAIL MODAL TEMPLATE */
.smart-detail .modal-content {
    border-radius: 1.5rem;
    border: none;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12);
    background-color: #fff;
    transition: all 0.3s ease-in-out;
}

/* Icon Header */
.smart-detail .icon-circle {
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
.smart-detail h5 {
    color: #18375d;
    font-weight: 700;
    margin-bottom: 0.4rem;
    letter-spacing: 0.5px;
}

.smart-detail p {
    color: #6b7280;
    font-size: 0.96rem;
    margin-bottom: 1.8rem;
    line-height: 1.5;
}

/* MODAL BODY */
.smart-detail .modal-body {
    background: #ffffff;
    padding: 1rem 1.25rem; /* smaller padding for compact layout */
    border-radius: 1rem;
    max-height: none; /* allow detail-wrapper to expand naturally */
    overflow-y: visible; /* remove scroll restriction so it can grow */
}

/* Detail Section */
.smart-detail .detail-wrapper {
    background: #f9fafb;
    border-radius: 1rem;
    padding: 1rem;
    font-size: 0.95rem;
}

.smart-detail .detail-row {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px dashed #ddd;
    padding: 0.5rem 0;
}

.smart-detail .detail-row:last-child {
    border-bottom: none;
}

.smart-detail .detail-label {
    font-weight: 600;
    color: #1b3043;
}

.smart-detail .detail-value {
    color: #333;
    text-align: right;
}

/* Footer */
#userDetailsModal .modal-footer {
    text-align: center;
    border-top: 1px solid #e5e7eb;
    padding-top: 1.25rem;
    margin-top: 1.5rem;
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

/* COMPREHENSIVE STYLING TO MATCH SUPERADMIN FARMS TABLE */

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

/* Specific colored headers for modals */
.modal .card-header.bg-primary {
    background: #4e73df !important;
    color: white !important;
}

.modal .card-header.bg-warning {
    background: #f6c23e !important;
    color: white !important;
}

.modal .card-header.bg-info {
    background: #36b9cc !important;
    color: white !important;
}

.modal .card-header.bg-danger {
    background: #e74a3b !important;
    color: white !important;
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
    background-color: #fca700;
    border-color: #fca700;
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

/* Alert Topic Badge Styling */
.alert-topic-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.alert-health {
    background: rgba(231, 74, 59, 0.1);
    color: #e74a3b;
    border: 1px solid rgba(231, 74, 59, 0.3);
}

.alert-injury {
    background: rgba(246, 194, 62, 0.1);
    color: #f6c23e;
    border: 1px solid rgba(246, 194, 62, 0.3);
}

.alert-production {
    background: rgba(54, 185, 204, 0.1);
    color: #36b9cc;
    border: 1px solid rgba(54, 185, 204, 0.3);
}

.alert-behavioral {
    background: rgba(102, 16, 242, 0.1);
    color: #6610f2;
    border: 1px solid rgba(102, 16, 242, 0.3);
}

.alert-environmental {
    background: rgba(78, 115, 223, 0.1);
    color: #4e73df;
    border: 1px solid rgba(78, 115, 223, 0.3);
}

.alert-other {
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

.alert-livestock-notification {
    background: #387057;
    color: #ffffff;
    border-left: 4px solid #2d5a47;
    border: none;
    border-radius: 8px;
    font-weight: 500;
}

.alert-livestock-notification strong {
    color: #ffffff;
}

.alert-livestock-notification i {
    color: #ffffff;
}

.alert-livestock-notification .close {
    color: #ffffff;
    opacity: 0.8;
}

.alert-livestock-notification .close:hover {
    color: #ffffff;
    opacity: 1;
}

/* Search Controls - Match Super Admin */
.search-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.search-controls .input-group {
    flex: 0 0 auto;
}

.search-controls .d-flex {
    flex: 0 0 auto;
    gap: 0.5rem;
}

.input-group-text {
    background-color: #f8f9fc;
    border: 1px solid #d1d3e2;
    color: #858796;
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
.btn-action-refresh-alerts {
    background-color: #fca700 !important;
    border-color: #fca700 !important;
    color: white !important;
}

.btn-action-refresh-alerts:hover {
    background-color: #e69500 !important;
    border-color: #e69500 !important;
    color: white !important;
}
.btn-action-refresh-inspection {
    background-color: #fca700 !important;
    border-color: #fca700 !important;
    color: white !important;
}

.btn-action-refresh-inspection:hover {
    background-color: #e69500 !important;
    border-color: #e69500 !important;
    color: white !important;
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
/* Allow table to scroll horizontally if too wide */
.table-responsive {
    overflow-x: auto;
}

/* Make table cells wrap instead of forcing them all inline */
#alertsTable td, 
#alertsTable th {
    white-space: normal !important;  /* allow wrapping */
    vertical-align: middle;
}

/* Make sure action buttons don’t overflow */
#alertsTable td .btn-group {
    display: flex;
    flex-wrap: wrap; /* buttons wrap if not enough space */
    gap: 0.25rem;    /* small gap between buttons */
}

#alertsTable td .btn-action {
    flex: 1 1 auto; /* allow buttons to shrink/grow */
    min-width: 90px; /* prevent too tiny buttons */
    text-align: center;
}

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
#issuesTable,
#alertsTable,
#inspectionsTable {
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

#issuesTable th,
#issuesTable td,
#alertsTable th,
#alertsTable td,
#inspectionsTable th,
#inspectionsTable td {
    vertical-align: middle;
    padding: 0.75rem;
    text-align: center;
    border: 1px solid #dee2e6;
    white-space: nowrap;
    overflow: visible;
}

/* Table headers styling */
#issuesTable thead th,
#alertsTable thead th,
#inspectionsTable thead th {
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
#issuesTable thead th.sorting,
#issuesTable thead th.sorting_asc,
#issuesTable thead th.sorting_desc,
#alertsTable thead th.sorting,
#alertsTable thead th.sorting_asc,
#alertsTable thead th.sorting_desc,
#inspectionsTable thead th.sorting,
#inspectionsTable thead th.sorting_asc,
#inspectionsTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Remove default DataTables sort indicators to prevent overlap */
#issuesTable thead th.sorting::after,
#issuesTable thead th.sorting_asc::after,
#issuesTable thead th.sorting_desc::after,
#alertsTable thead th.sorting::after,
#alertsTable thead th.sorting_asc::after,
#alertsTable thead th.sorting_desc::after,
#inspectionsTable thead th.sorting::after,
#inspectionsTable thead th.sorting_asc::after,
#inspectionsTable thead th.sorting_desc::after {
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
</style>
@endpush
