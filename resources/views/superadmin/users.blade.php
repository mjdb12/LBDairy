@extends('layouts.app')

@section('title', 'LBDAIRY: SuperAdmin - User Management')

@section('content')
<div class="container-fluid">
    <br><br><br><br>
    
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-users"></i>
            User Management
        </h1>
        <p>Manage all system users, roles, and permissions</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container fade-in">
        <div class="stat-card">
            <div class="stat-number" style="color: var(--success-color);" id="totalUsersCount">0</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: var(--info-color);" id="activeUsersCount">0</div>
            <div class="stat-label">Active Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: var(--warning-color);" id="pendingUsersCount">0</div>
            <div class="stat-label">Pending Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: var(--danger-color);" id="suspendedUsersCount">0</div>
            <div class="stat-label">Suspended Users</div>
        </div>
    </div>

    <!-- User Management Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header">
            <h6>
                <i class="fas fa-list"></i>
                User Directory
            </h6>
            <div class="table-controls">
                <div class="search-container">
                    <input type="text" class="form-control custom-search" placeholder="Search users...">
                </div>
                <div class="export-controls">
                    <div class="btn-group">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportCSV()">
                                <i class="fas fa-file-csv"></i> Download CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPNG()">
                                <i class="fas fa-image"></i> Download PNG
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPDF()">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </div>
                    </div>
                    <button class="btn btn-secondary btn-sm" onclick="printTable()">
                        <i class="fas fa-print"></i>
                    </button>
                    <button class="btn btn-primary btn-sm" onclick="showAddUserModal()">
                        <i class="fas fa-user-plus"></i> Add User
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Registration Date</th>
                            <th>Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <!-- Users will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">
                    <i class="fas fa-user-plus"></i>
                    Add New User
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="userForm" onsubmit="saveUser(event)">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="userId" name="user_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstName">First Name *</label>
                                <input type="text" class="form-control" id="firstName" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastName">Last Name *</label>
                                <input type="text" class="form-control" id="lastName" name="last_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username">Username *</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contactNumber">Contact Number</label>
                                <input type="text" class="form-control" id="contactNumber" name="contact_number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="barangay">Barangay</label>
                                <input type="text" class="form-control" id="barangay" name="barangay">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userRole">Role *</label>
                                <select class="form-control" id="userRole" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="farmer">Farmer</option>
                                    <option value="admin">Admin</option>
                                    <option value="superadmin">Super Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userStatus">Status *</label>
                                <select class="form-control" id="userStatus" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="passwordFields">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password *</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <small class="form-text text-muted">Leave blank to keep existing password when editing</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="passwordConfirmation">Confirm Password *</label>
                                <input type="password" class="form-control" id="passwordConfirmation" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                    <div id="formNotification" class="mt-2" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save User
                    </button>
                </div>
            </form>
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
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
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

<!-- User Details Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" role="dialog" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDetailsModalLabel">
                    <i class="fas fa-user"></i>
                    User Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="userDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables Core -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<!-- Required libraries for PDF/Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
let usersTable;
let userToDelete = null;

$(document).ready(function () {
    // Initialize DataTables
    initializeDataTables();
    
    // Load data
    loadUsers();
    updateStats();

    // Custom search functionality
    $('.custom-search').on('keyup', function() {
        usersTable.search(this.value).draw();
    });
});

function initializeDataTables() {
    usersTable = $('#usersTable').DataTable({
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
                title: 'Users_Report',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Users_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Users Report',
                className: 'd-none'
            }
        ],
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No users available</h5><p>There are no users to display at this time.</p></div>'
        }
    });

    // Hide default DataTables elements
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
}

function loadUsers() {
    // Load users from the database via AJAX
    $.ajax({
        url: '{{ route("superadmin.users.index") }}',
        method: 'GET',
        success: function(response) {
            usersTable.clear();
            
            response.data.forEach(user => {
                const rowData = [
                    `<a href="#" class="user-id-link" onclick="showUserDetails('${user.id}')">${user.id}</a>`,
                    `${user.first_name} ${user.last_name}`,
                    user.email,
                    `<span class="badge badge-${getRoleBadgeClass(user.role)}">${user.role}</span>`,
                    `<span class="badge badge-${getStatusBadgeClass(user.status)}">${user.status}</span>`,
                    new Date(user.created_at).toLocaleDateString(),
                    user.last_login_at ? new Date(user.last_login_at).toLocaleDateString() : 'Never',
                    `<div class="btn-group" role="group">
                        <button class="btn btn-primary btn-sm" onclick="editUser('${user.id}')" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-warning btn-sm" onclick="toggleUserStatus('${user.id}', '${user.status}')" title="Toggle Status">
                            <i class="fas fa-${user.status === 'active' ? 'pause' : 'play'}"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete('${user.id}')" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>`
                ];
                
                usersTable.row.add(rowData).draw(false);
            });
        },
        error: function(xhr) {
            console.error('Error loading users:', xhr);
        }
    });
}

function updateStats() {
    // Update stats via AJAX
    $.ajax({
        url: '{{ route("superadmin.users.stats") }}',
        method: 'GET',
        success: function(response) {
            document.getElementById('totalUsersCount').textContent = response.total;
            document.getElementById('activeUsersCount').textContent = response.active;
            document.getElementById('pendingUsersCount').textContent = response.pending;
            document.getElementById('suspendedUsersCount').textContent = response.suspended;
        },
        error: function(xhr) {
            console.error('Error loading stats:', xhr);
        }
    });
}

function getRoleBadgeClass(role) {
    switch(role) {
        case 'superadmin': return 'danger';
        case 'admin': return 'primary';
        case 'farmer': return 'success';
        default: return 'secondary';
    }
}

function getStatusBadgeClass(status) {
    switch(status) {
        case 'active': return 'success';
        case 'inactive': return 'warning';
        case 'suspended': return 'danger';
        default: return 'secondary';
    }
}

function showAddUserModal() {
    resetUserForm();
    $('#userModalLabel').html('<i class="fas fa-user-plus"></i> Add New User');
    $('#passwordFields').show();
    $('#password').prop('required', true);
    $('#passwordConfirmation').prop('required', true);
    $('#userModal').modal('show');
}

function editUser(userId) {
    // Load user data via AJAX
    $.ajax({
        url: `{{ route("superadmin.users.show", ":id") }}`.replace(':id', userId),
        method: 'GET',
        success: function(response) {
            const user = response.data;
            populateUserForm(user);
            $('#userModalLabel').html('<i class="fas fa-edit"></i> Edit User');
            $('#passwordFields').hide();
            $('#password').prop('required', false);
            $('#passwordConfirmation').prop('required', false);
            $('#userModal').modal('show');
        },
        error: function(xhr) {
            showNotification('Error loading user data', 'danger');
        }
    });
}

function populateUserForm(user) {
    $('#userId').val(user.id);
    $('#firstName').val(user.first_name);
    $('#lastName').val(user.last_name);
    $('#email').val(user.email);
    $('#username').val(user.username);
    $('#contactNumber').val(user.contact_number);
    $('#barangay').val(user.barangay);
    $('#userRole').val(user.role);
    $('#userStatus').val(user.status);
}

function resetUserForm() {
    $('#userForm')[0].reset();
    $('#userId').val('');
    $('#formNotification').hide();
}

function saveUser(event) {
    event.preventDefault();
    
    const formData = new FormData($('#userForm')[0]);
    const userId = $('#userId').val();
            const url = userId ? `{{ route("superadmin.users.update", ":id") }}`.replace(':id', userId) : '{{ route("superadmin.users.store") }}';
    const method = userId ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        method: method,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#userModal').modal('hide');
            loadUsers();
            updateStats();
            showNotification(userId ? 'User updated successfully' : 'User created successfully', 'success');
        },
        error: function(xhr) {
            const errors = xhr.responseJSON?.errors || {};
            let errorMessage = 'Please fix the following errors:';
            
            Object.keys(errors).forEach(field => {
                errorMessage += `\n• ${errors[field][0]}`;
            });
            
            document.getElementById('formNotification').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    ${errorMessage}
                </div>
            `;
            document.getElementById('formNotification').style.display = 'block';
        }
    });
}

function toggleUserStatus(userId, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    
    $.ajax({
        url: `{{ route("superadmin.users.toggle-status", ":id") }}`.replace(':id', userId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            status: newStatus
        },
        success: function(response) {
            loadUsers();
            updateStats();
            showNotification(`User status updated to ${newStatus}`, 'success');
        },
        error: function(xhr) {
            showNotification('Error updating user status', 'danger');
        }
    });
}

function showUserDetails(userId) {
    // Load user details via AJAX
    $.ajax({
        url: `{{ route("superadmin.users.show", ":id") }}`.replace(':id', userId),
        method: 'GET',
        success: function(response) {
            const user = response.data;
            const details = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3 text-primary">Personal Information</h6>
                        <p><strong>Full Name:</strong> ${user.first_name} ${user.last_name}</p>
                        <p><strong>Email:</strong> ${user.email}</p>
                        <p><strong>Username:</strong> ${user.username}</p>
                        <p><strong>Contact Number:</strong> ${user.contact_number || 'N/A'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3 text-primary">Account Information</h6>
                        <p><strong>Role:</strong> <span class="badge badge-${getRoleBadgeClass(user.role)}">${user.role}</span></p>
                        <p><strong>Status:</strong> <span class="badge badge-${getStatusBadgeClass(user.status)}">${user.status}</span></p>
                        <p><strong>Barangay:</strong> ${user.barangay || 'N/A'}</p>
                        <p><strong>Registration Date:</strong> ${new Date(user.created_at).toLocaleDateString()}</p>
                        <p><strong>Last Login:</strong> ${user.last_login_at ? new Date(user.last_login_at).toLocaleDateString() : 'Never'}</p>
                    </div>
                </div>
            `;

            document.getElementById('userDetails').innerHTML = details;
            $('#userDetailsModal').modal('show');
        },
        error: function(xhr) {
            console.error('Error loading user details:', xhr);
        }
    });
}

function confirmDelete(userId) {
    userToDelete = userId;
    $('#confirmDeleteModal').modal('show');
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (userToDelete) {
        deleteUser(userToDelete);
        userToDelete = null;
        $('#confirmDeleteModal').modal('hide');
    }
});

function deleteUser(userId) {
    $.ajax({
        url: `{{ route("superadmin.users.destroy", ":id") }}`.replace(':id', userId),
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            loadUsers();
            updateStats();
            showNotification('User deleted successfully', 'success');
        },
        error: function(xhr) {
            showNotification('Error deleting user', 'danger');
        }
    });
}

function exportCSV() {
    usersTable.button('.buttons-csv').trigger();
}

function exportPDF() {
    usersTable.button('.buttons-pdf').trigger();
}

function exportPNG() {
    const tableElement = document.getElementById('usersTable');
    html2canvas(tableElement).then(canvas => {
        let link = document.createElement('a');
        link.download = 'Users_Report.png';
        link.href = canvas.toDataURL("image/png");
        link.click();
    });
}

function printTable() {
    usersTable.button('.buttons-print').trigger();
}

function showNotification(message, type) {
    const notification = $(`
        <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
             style="top: 100px; right: 20px; z-index: 9999; min-width: 300px;">
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
</script>
@endpush
