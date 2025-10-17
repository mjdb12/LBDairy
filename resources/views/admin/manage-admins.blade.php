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
                                <div class="action-buttons">
                                    <button class="btn-action btn-action-view" onclick="viewAdminDetails('{{ $admin->id }}')" title="View Details">
                                        <i class="fas fa-eye"></i>
                                        <span>View</span>
                                    </button>
                                    <button class="btn-action btn-action-edit" onclick="editAdmin('{{ $admin->id }}')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                        <span>Edit</span>
                                    </button>
                                    <button class="btn-action btn-action-toggle" onclick="toggleAdminStatus('{{ $admin->id }}')" title="Toggle Status">
                                        <i class="fas fa-toggle-on"></i>
                                        <span>Toggle</span>
                                    </button>
                                    <button class="btn-action btn-action-reset" onclick="resetPassword('{{ $admin->id }}')" title="Reset Password">
                                        <i class="fas fa-key"></i>
                                        <span>Reset</span>
                                    </button>
                                    <button class="btn-action btn-action-delete" onclick="deleteAdmin('{{ $admin->id }}')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                        <span>Delete</span>
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
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">No admin data available</td>
                            <td class="text-center text-muted">N/A</td>
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
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">No recent activity</td>
                            <td class="text-center text-muted">N/A</td>
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
<!-- DataTables Core -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
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
    // Get current table data without actions column
    const tableData = adminDataTable.data().toArray();
    const csvData = [];
    
    // Add headers (excluding Actions column)
    const headers = ['Admin ID', 'Name', 'Email', 'Role', 'Status', 'Registration Date', 'Last Login'];
    csvData.push(headers.join(','));
    
    // Add data rows (excluding Actions column)
    tableData.forEach(row => {
        // Extract text content from each cell, excluding the last column (Actions)
        const rowData = [];
        for (let i = 0; i < row.length - 1; i++) {
            let cellText = '';
            if (row[i]) {
                // Remove HTML tags and get clean text
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = row[i];
                cellText = tempDiv.textContent || tempDiv.innerText || '';
                // Clean up the text (remove extra spaces, newlines)
                cellText = cellText.replace(/\s+/g, ' ').trim();
            }
            // Escape commas and quotes for CSV
            if (cellText.includes(',') || cellText.includes('"') || cellText.includes('\n')) {
                cellText = '"' + cellText.replace(/"/g, '""') + '"';
            }
            rowData.push(cellText);
        }
        csvData.push(rowData.join(','));
    });
    
    // Create and download CSV file
    const csvContent = csvData.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', `Admin_ManageAdminsReport_${downloadCounter}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Increment download counter
    downloadCounter++;
    
    showNotification('CSV exported successfully!', 'success');
}

function exportPNG() {
    // Create a temporary table without the Actions column for export
    const originalTable = document.getElementById('dataTable');
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
        link.download = `Admin_ManageAdminsReport_${downloadCounter}.png`;
        link.href = canvas.toDataURL("image/png");
        link.click();
        
        // Increment download counter
        downloadCounter++;
        
        // Clean up - remove temporary table
        document.body.removeChild(tempTable);
        
        showNotification('PNG exported successfully!', 'success');
    }).catch(error => {
        console.error('Error generating PNG:', error);
        // Clean up on error
        if (document.body.contains(tempTable)) {
            document.body.removeChild(tempTable);
        }
        showNotification('Error generating PNG export', 'danger');
    });
}

function exportPDF() {
    try {
        // Force custom PDF generation to match superadmin styling
        // Don't fall back to DataTables PDF export as it has different styling
        
        const tableData = dataTable.data().toArray();
        const pdfData = [];
        
        const headers = ['Admin ID', 'Name', 'Email', 'Username', 'Role', 'Status', 'Created At'];
        
        tableData.forEach(row => {
            const rowData = [
                row[0] || '', // Admin ID
                row[1] || '', // Name
                row[2] || '', // Email
                row[3] || '', // Username
                row[4] || '', // Role
                row[5] || '',  // Status
                row[6] || ''  // Created At
            ];
            pdfData.push(rowData);
        });
        
        // Create PDF using jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape', 'mm', 'a4');
        
        // Set title
        doc.setFontSize(18);
        doc.text('Admin Manage Admins Report', 14, 22);
        doc.setFontSize(12);
        doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 14, 32);
        
        // Create table
        doc.autoTable({
            head: [headers],
            body: pdfData,
            startY: 40,
            styles: { fontSize: 8, cellPadding: 2 },
            headStyles: { fillColor: [24, 55, 93], textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [245, 245, 245] }
        });
        
        // Save the PDF
        doc.save(`Admin_ManageAdminsReport_${downloadCounter}.pdf`);
        
        // Increment download counter
        downloadCounter++;
        
        showNotification('PDF exported successfully!', 'success');
        
    } catch (error) {
        console.error('Error generating PDF:', error);
        showNotification('Error generating PDF. Please try again.', 'danger');
    }
}

function printTable() {
    try {
        // Prefer DataTables data when available
        var dt = ($.fn.DataTable && $.fn.DataTable.isDataTable('#adminDataTable')) ? $('#adminDataTable').DataTable() : null;
        var tableData = dt ? dt.data().toArray() : null;

        // Build printable HTML
        var printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #18375d; margin-bottom: 5px;">Admin Users Overview Report</h1>
                    <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                <table border="1" style="border-collapse: collapse; width: 100%; border: 1px solid #000;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2; text-align: left;">Admin ID</th>
                            <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2; text-align: left;">Name</th>
                            <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2; text-align: left;">Email</th>
                            <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2; text-align: left;">Role</th>
                            <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2; text-align: left;">Status</th>
                            <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2; text-align: left;">Last Login</th>
                            <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2; text-align: left;">Created Date</th>
                        </tr>
                    </thead>
                    <tbody>`;

        if (tableData && tableData.length) {
            // DataTables path: exclude Actions column (last)
            tableData.forEach(function(row){
                printContent += '<tr>';
                for (var i=1; i < row.length - 1; i++) { // skip first checkbox column and last Actions
                    var cellText = '';
                    if (row[i]) {
                        var tempDiv = document.createElement('div');
                        tempDiv.innerHTML = row[i];
                        cellText = (tempDiv.textContent || tempDiv.innerText || '').replace(/\s+/g, ' ').trim();
                    }
                    printContent += `<td style="border: 1px solid #000; padding: 8px; text-align: left;">${cellText}</td>`;
                }
                printContent += '</tr>';
            });
        } else {
            // Fallback: parse DOM table rows
            var rows = document.querySelectorAll('#adminDataTable tbody tr');
            rows.forEach(function(tr){
                var tds = tr.querySelectorAll('td');
                if (!tds || tds.length < 2) return;
                printContent += '<tr>';
                // skip first checkbox cell and last actions cell
                for (var i=1; i<tds.length-1; i++) {
                    var text = (tds[i].innerText || '').replace(/\s+/g,' ').trim();
                    printContent += `<td style="border: 1px solid #000; padding: 8px; text-align: left;">${text}</td>`;
                }
                printContent += '</tr>';
            });
        }

        printContent += `
                    </tbody>
                </table>
            </div>`;

        if (typeof window.printElement === 'function') {
            var container = document.createElement('div');
            container.innerHTML = printContent;
            window.printElement(container);
        } else if (typeof window.openPrintWindow === 'function') {
            window.openPrintWindow(printContent, 'Admin Users Overview Report');
        } else {
            window.print();
        }
    } catch (e) {
        window.print();
    }
}
</script>
@endsection
