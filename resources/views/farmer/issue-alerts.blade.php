@extends('layouts.app')

@section('title', 'Issue Alerts')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-exclamation-triangle"></i>
            Issue Alerts
        </h1>
        <p>Create and manage alerts to notify administrators about livestock issues</p>
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

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow fade-in">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-list"></i>
                        My Alerts
                    </h6>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-primary btn-sm" onclick="openCreateAlertModal()">
                            <i class="fas fa-plus"></i> Create New Alert
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="alertsTable" width="100%" cellspacing="0">
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
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-bell-slash fa-3x mb-3 text-muted"></i>
                                        <p>No alerts created yet. Create your first alert to notify administrators about livestock issues!</p>
                                        <button class="btn btn-primary" onclick="openCreateAlertModal()">
                                            <i class="fas fa-plus"></i> Create First Alert
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Alert
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#alertsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[4, 'desc']], // Sort by date created
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

.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endpush
