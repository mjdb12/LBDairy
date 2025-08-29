@extends('layouts.app')

@section('title', 'Issue Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-exclamation-triangle"></i>
            Issue Management
        </h1>
        <p>Select a farmer to report issues for their livestock</p>
    </div>

    <!-- Farmer Selection Section -->
    <div class="card shadow mb-4" id="farmerSelectionCard">
        <div class="card-header">
            <h6>
                <i class="fas fa-users"></i>
                Select Farmer
            </h6>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" id="farmerSearch" placeholder="Search farmers..." style="max-width: 300px;">
                <button class="btn btn-info btn-sm" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="farmersTable">
                    <thead>
                        <tr>
                            <th>Farmer ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Total Livestock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="farmersTableBody">
                        <!-- Farmers will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Livestock Selection Section (Initially Hidden) -->
    <div class="card shadow mb-4" id="livestockCard" style="display: none;">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>
                        <i class="fas fa-cow"></i>
                        Select Livestock for: <span id="selectedFarmerName" class="text-primary font-weight-bold"></span>
                    </h6>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-secondary btn-sm" onclick="backToFarmers()">
                        <i class="fas fa-arrow-left"></i> Back to Farmers
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Livestock Table -->
            <div class="table-responsive">
                <table class="table table-bordered" id="livestockTable">
                    <thead>
                        <tr>
                            <th>Tag Number</th>
                            <th>Type</th>
                            <th>Breed</th>
                            <th>Gender</th>
                            <th>Farm</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="livestockTableBody">
                        <!-- Livestock will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- All Issues Section -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6>
                <i class="fas fa-list"></i>
                All Issues
            </h6>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" id="issueSearch" placeholder="Search issues..." style="max-width: 300px;">
                <button class="btn btn-info btn-sm" onclick="refreshIssues()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="issuesTable">
                    <thead>
                        <tr>
                            <th>Livestock ID</th>
                            <th>Type</th>
                            <th>Breed</th>
                            <th>Issue Type</th>
                            <th>Description</th>
                            <th>Date Reported</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="issuesTableBody">
                        @forelse($issues as $issue)
                        <tr>
                            <td><strong>{{ $issue->livestock->tag_number ?? 'N/A' }}</strong></td>
                            <td>{{ $issue->livestock->type ?? 'N/A' }}</td>
                            <td>{{ $issue->livestock->breed ?? 'N/A' }}</td>
                            <td>
                                <span class="issue-type-{{ strtolower($issue->issue_type) }}">
                                    {{ $issue->issue_type }}
                                </span>
                            </td>
                            <td>{{ Str::limit($issue->description, 50) }}</td>
                            <td>{{ $issue->date_reported }}</td>
                            <td>
                                <span class="badge badge-{{ $issue->status === 'Pending' ? 'warning' : ($issue->status === 'Resolved' ? 'success' : 'info') }}">
                                    {{ $issue->status }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-action-view" onclick="viewIssue('{{ $issue->id }}')" title="View">
                                        <i class="fas fa-eye"></i>
                                        <span>View</span>
                                    </button>
                                    <button class="btn-action btn-action-edit" onclick="editIssue('{{ $issue->id }}')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                        <span>Edit</span>
                                    </button>
                                    <button class="btn-action btn-action-delete" onclick="deleteIssue('{{ $issue->id }}')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No issues found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Report Issue Modal -->
<div class="modal fade" id="reportIssueModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report Issue</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="reportIssueForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="selectedLivestockId" name="livestock_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Issue Type</label>
                                <select class="form-control" name="issue_type" required>
                                    <option value="">Select Issue Type</option>
                                    <option value="Health">Health</option>
                                    <option value="Production">Production</option>
                                    <option value="Behavioral">Behavioral</option>
                                    <option value="Environmental">Environmental</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Priority</label>
                                <select class="form-control" name="priority" required>
                                    <option value="">Select Priority</option>
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                    <option value="Urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date Reported</label>
                                <input type="date" class="form-control" name="date_reported" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Livestock</label>
                                <input type="text" class="form-control" id="selectedLivestockInfo" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" rows="3" required placeholder="Describe the issue in detail..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Notes (Optional)</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="Additional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Report Issue</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #4e73df 0%, #3c5aa6 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }
    
    .farmer-link {
        color: #4e73df;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
    }
    
    .farmer-link:hover {
        color: #3c5aa6;
        text-decoration: underline;
    }
    
    .livestock-link {
        color: #4e73df;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
    }
    
    .livestock-link:hover {
        color: #3c5aa6;
        text-decoration: underline;
    }
    
    .gap-2 { gap: 0.5rem !important; }
    
    .issue-type-health { color: #e74a3b; font-weight: bold; }
    .issue-type-production { color: #f6c23e; font-weight: bold; }
    .issue-type-behavioral { color: #36b9cc; font-weight: bold; }
    .issue-type-environmental { color: #1cc88a; font-weight: bold; }
    .issue-type-other { color: #6c757d; font-weight: bold; }
</style>
@endpush

@push('scripts')
<script>
    let selectedFarmerId = null;
    let selectedFarmerName = '';
    let selectedLivestockId = null;

    $(document).ready(function() {
        console.log('Document ready, loading farmers...');
        loadFarmers();
        
        // Search functionality for farmers
        $('#farmerSearch').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('#farmersTable tbody tr').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(searchTerm) > -1);
            });
        });
        
        // Search functionality for issues
        $('#issueSearch').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('#issuesTable tbody tr').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(searchTerm) > -1);
            });
        });
    });

    function loadFarmers() {
        $('#farmersTableBody').html('<tr><td colspan="7" class="text-center">Loading farmers...</td></tr>');
        
        $.ajax({
            url: '{{ route("admin.issues.farmers") }}',
            method: 'GET',
            success: function(response) {
                console.log('Farmers response:', response);
                if (response.success) {
                    let html = '';
                    if (response.data.length === 0) {
                        html = '<tr><td colspan="7" class="text-center">No farmers found</td></tr>';
                    } else {
                        response.data.forEach(farmer => {
                            const displayName = farmer.first_name && farmer.last_name 
                                ? `${farmer.first_name} ${farmer.last_name}` 
                                : farmer.name || 'N/A';
                            
                            html += `
                                <tr>
                                    <td>${farmer.id}</td>
                                    <td><a href="#" class="farmer-link" onclick="selectFarmer('${farmer.id}', '${displayName}')">${displayName}</a></td>
                                    <td>${farmer.email}</td>
                                    <td>${farmer.contact_number || 'N/A'}</td>
                                    <td>${farmer.livestock_count || 0}</td>
                                    <td><span class="badge badge-${getStatusBadgeClass(farmer.status)}">${farmer.status}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" onclick="selectFarmer('${farmer.id}', '${displayName}')">
                                            <i class="fas fa-exclamation-triangle"></i> Report Issue
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    $('#farmersTableBody').html(html);
                } else {
                    $('#farmersTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading farmers: ' + (response.message || 'Unknown error') + '</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr, status, error);
                console.log('Response Text:', xhr.responseText);
                $('#farmersTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading farmers. Check console for details.</td></tr>');
            }
        });
    }

    function selectFarmer(farmerId, farmerName) {
        selectedFarmerId = farmerId;
        selectedFarmerName = farmerName;
        
        $('#selectedFarmerName').text(farmerName);
        
        $('#farmerSelectionCard').hide();
        $('#livestockCard').show();
        
        loadFarmerLivestock(farmerId);
    }

    function backToFarmers() {
        selectedFarmerId = null;
        selectedFarmerName = '';
        
        $('#farmerSelectionCard').show();
        $('#livestockCard').hide();
        
        $('#livestockTableBody').empty();
    }

    function loadFarmerLivestock(farmerId) {
        $('#livestockTableBody').html('<tr><td colspan="7" class="text-center">Loading livestock...</td></tr>');
        
        $.ajax({
            url: `{{ route("admin.issues.farmer-livestock", ":id") }}`.replace(':id', farmerId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let html = '';
                    if (response.data.livestock.length === 0) {
                        html = '<tr><td colspan="7" class="text-center">No livestock found for this farmer</td></tr>';
                    } else {
                        response.data.livestock.forEach(animal => {
                            html += `
                                <tr>
                                    <td><a href="#" class="livestock-link" onclick="selectLivestock('${animal.id}', '${animal.tag_number}')">${animal.tag_number}</a></td>
                                    <td>${animal.type}</td>
                                    <td>${animal.breed}</td>
                                    <td>${animal.gender}</td>
                                    <td>${animal.farm ? animal.farm.name : 'N/A'}</td>
                                    <td><span class="badge badge-${animal.status === 'active' ? 'success' : 'secondary'}">${animal.status}</span></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="selectLivestock('${animal.id}', '${animal.tag_number}')">
                                            <i class="fas fa-exclamation-triangle"></i> Report Issue
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    $('#livestockTableBody').html(html);
                } else {
                    $('#livestockTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading livestock</td></tr>');
                }
            },
            error: function() {
                $('#livestockTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading livestock</td></tr>');
            }
        });
    }

    function selectLivestock(livestockId, livestockInfo) {
        selectedLivestockId = livestockId;
        
        $('#selectedLivestockId').val(livestockId);
        $('#selectedLivestockInfo').val(livestockInfo);
        
        $('#reportIssueModal').modal('show');
    }

    function refreshData() {
        if (selectedFarmerId) {
            loadFarmerLivestock(selectedFarmerId);
        } else {
            loadFarmers();
        }
    }

    function refreshIssues() {
        location.reload();
    }

    function getStatusBadgeClass(status) {
        switch(status) {
            case 'approved': return 'success';
            case 'pending': return 'warning';
            case 'suspended': return 'danger';
            default: return 'secondary';
        }
    }

    // Handle report issue form submission
    $('#reportIssueForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("admin.issues.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#reportIssueModal').modal('hide');
                $('#reportIssueForm')[0].reset();
                showNotification('Issue reported successfully', 'success');
                refreshIssues();
            },
            error: function(xhr) {
                showNotification('Failed to report issue', 'error');
            }
        });
    });

    function showNotification(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        
        const notification = document.createElement('div');
        notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 3000);
    }

    // Placeholder functions for existing issue management
    function viewIssue(issueId) {
        alert('View issue functionality coming soon');
    }

    function editIssue(issueId) {
        alert('Edit issue functionality coming soon');
    }

    function deleteIssue(issueId) {
        if (confirm('Are you sure you want to delete this issue?')) {
            // Implementation for deleting issue
            alert('Delete issue functionality coming soon');
        }
    }
</script>
@endpush
