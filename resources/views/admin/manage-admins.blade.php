@extends('layouts.app')

@section('title', 'Manage Admins')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-user-shield"></i>
            Manage Admins
        </h1>
        <p>Manage admin users, permissions, and access controls</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container fade-in">
        <div class="stat-card">
            <i class="fas fa-users stat-icon"></i>
            <h3>{{ $totalAdmins ?? 0 }}</h3>
            <p>Total Admins</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-user-check stat-icon"></i>
            <h3>{{ $activeAdmins ?? 0 }}</h3>
            <p>Active Admins</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-user-clock stat-icon"></i>
            <h3>{{ $pendingAdmins ?? 0 }}</h3>
            <p>Pending Approval</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-shield-alt stat-icon"></i>
            <h3>{{ $superAdmins ?? 0 }}</h3>
            <p>Super Admins</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4 fade-in">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <button class="btn btn-primary btn-block" onclick="openAddAdminModal()">
                                <i class="fas fa-plus"></i>
                                Add New Admin
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success btn-block" onclick="bulkApprove()">
                                <i class="fas fa-check"></i>
                                Bulk Approve
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info btn-block" onclick="exportAdminList()">
                                <i class="fas fa-download"></i>
                                Export List
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-warning btn-block" onclick="openPermissionsModal()">
                                <i class="fas fa-key"></i>
                                Manage Permissions
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admins Table -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table"></i>
                Admin Users Overview
            </h6>
            <div class="table-controls">
                <div class="search-container">
                    <input type="text" class="form-control custom-search" placeholder="Search admins...">
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
                <table class="table table-bordered" id="adminDataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th>Admin ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admins ?? [] as $admin)
                        <tr>
                            <td>
                                <input type="checkbox" class="admin-select" value="{{ $admin->id }}">
                            </td>
                            <td>
                                <a href="#" onclick="openAdminDetails('{{ $admin->id }}')">
                                    {{ $admin->admin_id ?? 'A' . str_pad($admin->id, 3, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <td>{{ $admin->name ?? 'N/A' }}</td>
                            <td>{{ $admin->email ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $role = $admin->role ?? 'admin';
                                    $badgeClass = $role === 'super_admin' ? 'danger' : 
                                                 ($role === 'admin' ? 'primary' : 'secondary');
                                @endphp
                                <span class="badge badge-{{ $badgeClass }}">
                                    {{ ucwords(str_replace('_', ' ', $role)) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $status = $admin->status ?? 'inactive';
                                    $statusClass = $status === 'active' ? 'success' : 
                                                  ($status === 'pending' ? 'warning' : 'secondary');
                                @endphp
                                <span class="badge badge-{{ $statusClass }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td>{{ $admin->last_login ?? 'Never' }}</td>
                            <td>{{ $admin->created_at ?? 'N/A' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-info" onclick="viewAdminDetails('{{ $admin->id }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary" onclick="editAdmin('{{ $admin->id }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success" onclick="toggleAdminStatus('{{ $admin->id }}')">
                                        <i class="fas fa-toggle-on"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="resetPassword('{{ $admin->id }}')">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteAdmin('{{ $admin->id }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                <i class="fas fa-info-circle"></i>
                                No admin data available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Admin Activity Log -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-history"></i>
                Recent Admin Activity
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Action</th>
                            <th>Details</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivity ?? [] as $activity)
                        <tr>
                            <td>{{ $activity->admin_name ?? 'N/A' }}</td>
                            <td>{{ $activity->action ?? 'N/A' }}</td>
                            <td>{{ $activity->details ?? 'N/A' }}</td>
                            <td>{{ $activity->timestamp ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                <i class="fas fa-info-circle"></i>
                                No recent activity
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Admin Modal -->
<div class="modal fade" id="adminModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminModalTitle">
                    <i class="fas fa-user-plus"></i>
                    Add New Admin
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="adminForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adminName">Full Name *</label>
                                <input type="text" class="form-control" id="adminName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adminEmail">Email *</label>
                                <input type="email" class="form-control" id="adminEmail" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adminPhone">Phone</label>
                                <input type="tel" class="form-control" id="adminPhone" name="phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adminRole">Role *</label>
                                <select class="form-control" id="adminRole" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="super_admin">Super Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adminPassword">Password *</label>
                                <input type="password" class="form-control" id="adminPassword" name="password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adminPasswordConfirm">Confirm Password *</label>
                                <input type="password" class="form-control" id="adminPasswordConfirm" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="adminPermissions">Permissions</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="permUsers" name="permissions[]" value="manage_users">
                                    <label class="custom-control-label" for="permUsers">Manage Users</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="permFarms" name="permissions[]" value="manage_farms">
                                    <label class="custom-control-label" for="permFarms">Manage Farms</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="permLivestock" name="permissions[]" value="manage_livestock">
                                    <label class="custom-control-label" for="permLivestock">Manage Livestock</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Admin Details Modal -->
<div class="modal fade" id="adminDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-shield"></i>
                    Admin Details
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="adminDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editAdminFromModal()">Edit Admin</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Initialize DataTable when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeDataTable();
});

function initializeDataTable() {
    $('#adminDataTable').DataTable({
        responsive: true,
        order: [[7, 'desc']], // Sort by created date
        pageLength: 10,
        language: {
            search: "Search admins:",
            lengthMenu: "Show _MENU_ admins per page",
            info: "Showing _START_ to _END_ of _TOTAL_ admins"
        }
    });
}

function openAddAdminModal() {
    $('#adminModalTitle').html('<i class="fas fa-user-plus"></i> Add New Admin');
    $('#adminForm')[0].reset();
    $('#adminModal').modal('show');
}

function openAdminDetails(adminId) {
    // Load admin details via AJAX
    $.get(`/admin/admins/${adminId}/details`, function(data) {
        $('#adminDetailsContent').html(data);
        $('#adminDetailsModal').modal('show');
    });
}

function viewAdminDetails(adminId) {
    openAdminDetails(adminId);
}

function editAdmin(adminId) {
    // Load admin data and open edit modal
    $.get(`/admin/admins/${adminId}/edit`, function(data) {
        $('#adminModalTitle').html('<i class="fas fa-user-edit"></i> Edit Admin');
        // Populate form fields with data
        $('#adminModal').modal('show');
    });
}

function editAdminFromModal() {
    // Get admin ID from modal and open edit form
    const adminId = $('#adminDetailsModal').data('admin-id');
    editAdmin(adminId);
}

function toggleAdminStatus(adminId) {
    if (confirm('Are you sure you want to change this admin\'s status?')) {
        $.post(`/admin/admins/${adminId}/toggle-status`, {
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Error updating admin status');
            }
        });
    }
}

function resetPassword(adminId) {
    if (confirm('Are you sure you want to reset this admin\'s password?')) {
        $.post(`/admin/admins/${adminId}/reset-password`, {
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.success) {
                alert('Password reset successfully. New password: ' + response.newPassword);
            } else {
                alert('Error resetting password');
            }
        });
    }
}

function deleteAdmin(adminId) {
    if (confirm('Are you sure you want to delete this admin? This action cannot be undone.')) {
        $.post(`/admin/admins/${adminId}/delete`, {
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Error deleting admin');
            }
        });
    }
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.admin-select');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function bulkApprove() {
    const selectedAdmins = Array.from(document.querySelectorAll('.admin-select:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedAdmins.length === 0) {
        alert('Please select admins to approve');
        return;
    }
    
    if (confirm(`Are you sure you want to approve ${selectedAdmins.length} admin(s)?`)) {
        $.post('/admin/admins/bulk-approve', {
            _token: '{{ csrf_token() }}',
            admin_ids: selectedAdmins
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Error approving admins');
            }
        });
    }
}

function exportAdminList() {
    window.location.href = '/admin/admins/export';
}

function openPermissionsModal() {
    // Open permissions management modal
    alert('Permissions management feature coming soon!');
}

// Form submission
$('#adminForm').on('submit', function(e) {
    e.preventDefault();
    
    // Validate password confirmation
    const password = $('#adminPassword').val();
    const confirmPassword = $('#adminPasswordConfirm').val();
    
    if (password !== confirmPassword) {
        alert('Passwords do not match');
        return;
    }
    
    // Submit form
    $.post('/admin/admins/store', $(this).serialize(), function(response) {
        if (response.success) {
            $('#adminModal').modal('hide');
            location.reload();
        } else {
            alert('Error saving admin: ' + response.message);
        }
    });
});

function exportCSV() {
    // Export table data to CSV
    const table = $('#adminDataTable').DataTable();
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
</script>
@endsection
