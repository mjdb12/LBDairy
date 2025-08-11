@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-users"></i>
        Farmer Management
    </h1>
    <p>Manage farmer accounts, status, and communication</p>
</div>

<!-- Statistics Cards -->
<div class="row fade-in mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Farmers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'farmer')->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Farmers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'farmer')->where('is_active', true)->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Inactive Farmers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'farmer')->where('is_active', false)->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-times fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">New This Month</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'farmer')->whereMonth('created_at', now()->month)->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Farmers Table -->
<div class="card shadow fade-in-up">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-white">
            <i class="fas fa-table"></i>
            Farmers List
        </h6>
        <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#addFarmerModal">
            <i class="fas fa-plus"></i> Add New Farmer
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="farmersTable">
                <thead>
                    <tr>
                        <th>Farmer ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\User::where('role', 'farmer')->get() as $farmer)
                    <tr>
                        <td>
                            <a href="#" class="farmer-id-link" onclick="openDetailsModal('{{ $farmer->id }}')">
                                {{ $farmer->username ?? 'F' . str_pad($farmer->id, 3, '0', STR_PAD_LEFT) }}
                            </a>
                        </td>
                        <td>{{ $farmer->name }}</td>
                        <td>{{ $farmer->email }}</td>
                        <td>{{ $farmer->phone ?? 'N/A' }}</td>
                        <td>{{ $farmer->address ?? 'N/A' }}</td>
                        <td>
                            <select class="form-control form-control-sm" onchange="updateActivity(this, {{ $farmer->id }})">
                                <option value="1" {{ $farmer->is_active ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$farmer->is_active ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm mr-1" onclick="openDetailsModal('{{ $farmer->id }}')" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-warning btn-sm mr-1" onclick="editFarmer({{ $farmer->id }})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $farmer->id }})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Farmer Modal -->
<div class="modal fade" id="addFarmerModal" tabindex="-1" role="dialog" aria-labelledby="addFarmerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFarmerModalLabel">
                    <i class="fas fa-user-plus"></i>
                    Add New Farmer
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.farmers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
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
                                <label for="email">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password *</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Farmer
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
                <p>Are you sure you want to delete this farmer? This action cannot be undone.</p>
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

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">
                    <i class="fas fa-user"></i>
                    Farmer Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="farmerDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="openContactModal()">
                    <i class="fas fa-envelope"></i> Contact Farmer
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Contact Farmer Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">
                    <i class="fas fa-paper-plane"></i>
                    Send Message to Farmer
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form onsubmit="sendMessage(event)">
                <div class="modal-body">
                    <input type="hidden" id="farmerNameHidden">
                    <div class="form-group">
                        <label for="messageSubject">Subject</label>
                        <input type="text" class="form-control" id="messageSubject" required>
                    </div>
                    <div class="form-group">
                        <label for="messageBody">Message</label>
                        <textarea class="form-control" id="messageBody" rows="4" required></textarea>
                    </div>
                    <div id="messageNotification" class="mt-2" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script>
$(document).ready(function() {
    $('#farmersTable').DataTable({
        responsive: true,
        order: [[1, 'asc']],
        pageLength: 25,
        language: {
            search: "Search farmers:",
            lengthMenu: "Show _MENU_ farmers per page",
            info: "Showing _START_ to _END_ of _TOTAL_ farmers"
        }
    });
});

function updateActivity(select, farmerId) {
    const isActive = select.value === '1';
    const status = isActive ? 'active' : 'inactive';
    
    // Send AJAX request to update farmer status
    fetch(`/admin/farmers/${farmerId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ is_active: isActive })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showAlert('success', `Farmer status updated to ${status} successfully!`);
        } else {
            // Show error message and revert selection
            showAlert('danger', 'Failed to update farmer status. Please try again.');
            select.value = isActive ? '0' : '1';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'An error occurred. Please try again.');
        select.value = isActive ? '0' : '1';
    });
}

function confirmDelete(farmerId) {
    $('#confirmDeleteModal').modal('show');
    $('#confirmDeleteBtn').off('click').on('click', function() {
        // Send delete request
        fetch(`/admin/farmers/${farmerId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Farmer deleted successfully!');
                location.reload();
            } else {
                showAlert('danger', 'Failed to delete farmer. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred. Please try again.');
        });
        
        $('#confirmDeleteModal').modal('hide');
    });
}

function openDetailsModal(farmerId) {
    // Fetch farmer details and populate modal
    fetch(`/admin/farmers/${farmerId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const farmer = data.farmer;
                document.getElementById('farmerDetails').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> ${farmer.name}</p>
                            <p><strong>Username:</strong> ${farmer.username}</p>
                            <p><strong>Email:</strong> ${farmer.email}</p>
                            <p><strong>Phone:</strong> ${farmer.phone || 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Address:</strong> ${farmer.address || 'N/A'}</p>
                            <p><strong>Status:</strong> <span class="badge badge-${farmer.is_active ? 'success' : 'danger'}">${farmer.is_active ? 'Active' : 'Inactive'}</span></p>
                            <p><strong>Joined:</strong> ${new Date(farmer.created_at).toLocaleDateString()}</p>
                            <p><strong>Last Updated:</strong> ${new Date(farmer.updated_at).toLocaleDateString()}</p>
                        </div>
                    </div>
                `;
                document.getElementById('farmerNameHidden').value = farmer.name;
                $('#detailsModal').modal('show');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Failed to load farmer details.');
        });
}

function openContactModal() {
    $('#detailsModal').modal('hide');
    $('#contactModal').modal('show');
}

function sendMessage(event) {
    event.preventDefault();
    
    const subject = document.getElementById('messageSubject').value;
    const body = document.getElementById('messageBody').value;
    const farmerName = document.getElementById('farmerNameHidden').value;
    
    // Here you would typically send the message via email or save to database
    // For now, we'll just show a success message
    
    document.getElementById('messageNotification').innerHTML = `
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            Message sent successfully to ${farmerName}!
        </div>
    `;
    document.getElementById('messageNotification').style.display = 'block';
    
    // Clear form
    document.getElementById('messageSubject').value = '';
    document.getElementById('messageBody').value = '';
    
    // Hide notification after 3 seconds
    setTimeout(() => {
        document.getElementById('messageNotification').style.display = 'none';
    }, 3000);
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
        </button>
    `;
    
    document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.page-header'));
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
@endpush
