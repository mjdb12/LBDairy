@extends('layouts.app')

@section('title', 'Livestock Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-paw"></i>
            Livestock Management
        </h1>
        <p>Manage your livestock inventory, track health, and monitor production</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Livestock</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLivestock }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-paw fa-2x text-gray-300"></i>
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
                                Healthy</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $healthyLivestock }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-heart fa-2x text-gray-300"></i>
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
                                Needs Attention</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $attentionNeeded }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Production Ready</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $productionReady }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-milk fa-2x text-gray-300"></i>
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
                        Livestock Inventory
                    </h6>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-primary btn-sm" onclick="openAddLivestockModal()">
                            <i class="fas fa-plus"></i> Add New Livestock
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
                        <table class="table table-bordered" id="livestockTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Breed</th>
                                    <th>Age</th>
                                    <th>Weight</th>
                                    <th>Health Status</th>
                                    <th>Production Status</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($livestock as $animal)
                                <tr>
                                    <td>
                                        <a href="#" onclick="openLivestockDetails('{{ $animal->id }}')" class="text-primary font-weight-bold">
                                            {{ $animal->livestock_id }}
                                        </a>
                                    </td>
                                    <td>{{ $animal->type }}</td>
                                    <td>{{ $animal->breed }}</td>
                                    <td>{{ $animal->age }} years</td>
                                    <td>{{ $animal->weight }} kg</td>
                                    <td>
                                        <span class="status-badge status-{{ $animal->health_status == 'Healthy' ? 'active' : 'inactive' }}">
                                            {{ $animal->health_status }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $animal->production_status == 'Active' ? 'active' : 'inactive' }}">
                                            {{ $animal->production_status }}
                                        </span>
                                    </td>
                                    <td>{{ $animal->updated_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-info btn-sm" onclick="openLivestockDetails('{{ $animal->id }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-warning btn-sm" onclick="openEditLivestockModal('{{ $animal->id }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $animal->id }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>No livestock records found. Add your first livestock to get started.</p>
                                        <button class="btn btn-primary" onclick="openAddLivestockModal()">
                                            <i class="fas fa-plus"></i> Add First Livestock
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

<!-- Add/Edit Livestock Modal -->
<div class="modal fade" id="livestockModal" tabindex="-1" role="dialog" aria-labelledby="livestockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="livestockModalLabel">
                    <i class="fas fa-plus"></i>
                    Add New Livestock
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="livestockForm" method="POST" action="{{ route('farmer.livestock.store') }}">
                @csrf
                <div class="modal-body">
                    <ul class="nav nav-tabs mb-3" id="livestockTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basicForm" role="tab">
                                Basic Info
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="health-tab" data-toggle="tab" href="#healthForm" role="tab">
                                Health Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="production-tab" data-toggle="tab" href="#productionForm" role="tab">
                                Production Info
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="livestockTabContent">
                        <!-- Basic Info Tab -->
                        <div class="tab-pane fade show active" id="basicForm" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="livestock_id">Livestock ID</label>
                                        <input type="text" class="form-control" id="livestock_id" name="livestock_id" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type">Type</label>
                                        <select class="form-control" id="type" name="type" required>
                                            <option value="">Select Type</option>
                                            <option value="Cow">Cow</option>
                                            <option value="Goat">Goat</option>
                                            <option value="Carabao">Carabao</option>
                                            <option value="Sheep">Sheep</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="breed">Breed</label>
                                        <input type="text" class="form-control" id="breed" name="breed" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="age">Age (years)</label>
                                        <input type="number" class="form-control" id="age" name="age" min="0" step="0.1" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="weight">Weight (kg)</label>
                                        <input type="number" class="form-control" id="weight" name="weight" min="0" step="0.1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select class="form-control" id="gender" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Health Details Tab -->
                        <div class="tab-pane fade" id="healthForm" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="health_status">Health Status</label>
                                        <select class="form-control" id="health_status" name="health_status" required>
                                            <option value="Healthy">Healthy</option>
                                            <option value="Sick">Sick</option>
                                            <option value="Recovering">Recovering</option>
                                            <option value="Under Treatment">Under Treatment</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vaccination_date">Last Vaccination Date</label>
                                        <input type="date" class="form-control" id="vaccination_date" name="vaccination_date">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="health_notes">Health Notes</label>
                                <textarea class="form-control" id="health_notes" name="health_notes" rows="3" placeholder="Any health-related notes or observations"></textarea>
                            </div>
                        </div>

                        <!-- Production Info Tab -->
                        <div class="tab-pane fade" id="productionForm" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="production_status">Production Status</label>
                                        <select class="form-control" id="production_status" name="production_status" required>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                            <option value="Pregnant">Pregnant</option>
                                            <option value="Lactating">Lactating</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_milking">Last Milking Date</label>
                                        <input type="date" class="form-control" id="last_milking" name="last_milking">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="production_notes">Production Notes</label>
                                <textarea class="form-control" id="production_notes" name="production_notes" rows="3" placeholder="Any production-related notes or observations"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Livestock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Livestock Details Modal -->
<div class="modal fade" id="livestockDetailsModal" tabindex="-1" role="dialog" aria-labelledby="livestockDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="livestockDetailsLabel">
                    <i class="fas fa-eye"></i>
                    Livestock Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="livestockDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editCurrentLivestock()">
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
                Are you sure you want to delete this livestock? This action cannot be undone.
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
let currentLivestockId = null;

$(document).ready(function() {
    // Initialize DataTable
    $('#livestockTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc']],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search livestock...",
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
    $('#livestockForm').on('submit', function(e) {
        e.preventDefault();
        submitLivestockForm();
    });
});

function openAddLivestockModal() {
    $('#livestockModalLabel').html('<i class="fas fa-plus"></i> Add New Livestock');
    $('#livestockForm')[0].reset();
    $('#livestockForm').attr('action', '{{ route("farmer.livestock.store") }}');
    $('#livestockForm').attr('method', 'POST');
    $('#livestockModal').modal('show');
}

function openEditLivestockModal(livestockId) {
    currentLivestockId = livestockId;
    $('#livestockModalLabel').html('<i class="fas fa-edit"></i> Edit Livestock');
    $('#livestockForm').attr('action', `/farmer/livestock/${livestockId}`);
    $('#livestockForm').attr('method', 'POST');
    $('#livestockForm').append('<input type="hidden" name="_method" value="PUT">');
    
    // Load livestock data
    loadLivestockData(livestockId);
    $('#livestockModal').modal('show');
}

function openLivestockDetails(livestockId) {
    currentLivestockId = livestockId;
    loadLivestockDetails(livestockId);
    $('#livestockDetailsModal').modal('show');
}

function loadLivestockData(livestockId) {
    $.ajax({
        url: `/farmer/livestock/${livestockId}/edit`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const livestock = response.livestock;
                $('#livestock_id').val(livestock.livestock_id);
                $('#type').val(livestock.type);
                $('#breed').val(livestock.breed);
                $('#age').val(livestock.age);
                $('#weight').val(livestock.weight);
                $('#gender').val(livestock.gender);
                $('#health_status').val(livestock.health_status);
                $('#vaccination_date').val(livestock.vaccination_date);
                $('#health_notes').val(livestock.health_notes);
                $('#production_status').val(livestock.production_status);
                $('#last_milking').val(livestock.last_milking);
                $('#production_notes').val(livestock.production_notes);
            }
        },
        error: function() {
            showToast('Error loading livestock data', 'error');
        }
    });
}

function loadLivestockDetails(livestockId) {
    $.ajax({
        url: `/farmer/livestock/${livestockId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const livestock = response.livestock;
                $('#livestockDetailsContent').html(`
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr><td><strong>ID:</strong></td><td>${livestock.livestock_id}</td></tr>
                                        <tr><td><strong>Type:</strong></td><td>${livestock.type}</td></tr>
                                        <tr><td><strong>Breed:</strong></td><td>${livestock.breed}</td></tr>
                                        <tr><td><strong>Age:</strong></td><td>${livestock.age} years</td></tr>
                                        <tr><td><strong>Weight:</strong></td><td>${livestock.weight} kg</td></tr>
                                        <tr><td><strong>Gender:</strong></td><td>${livestock.gender}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-heart"></i> Health Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr><td><strong>Status:</strong></td><td><span class="status-badge status-${livestock.health_status === 'Healthy' ? 'active' : 'inactive'}">${livestock.health_status}</span></td></tr>
                                        <tr><td><strong>Vaccination:</strong></td><td>${livestock.vaccination_date || 'Not recorded'}</td></tr>
                                        <tr><td><strong>Notes:</strong></td><td>${livestock.health_notes || 'No notes'}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-milk"></i> Production Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr><td><strong>Status:</strong></td><td><span class="status-badge status-${livestock.production_status === 'Active' ? 'active' : 'inactive'}">${livestock.production_status}</span></td></tr>
                                        <tr><td><strong>Last Milking:</strong></td><td>${livestock.last_milking || 'Not recorded'}</td></tr>
                                        <tr><td><strong>Notes:</strong></td><td>${livestock.production_notes || 'No notes'}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    <h6 class="mb-0"><i class="fas fa-qrcode"></i> QR Code</h6>
                                </div>
                                <div class="card-body text-center">
                                    <div id="qrCodeContainer" class="mb-3">
                                        <p class="text-muted">QR Code for ${livestock.livestock_id}</p>
                                    </div>
                                    <button type="button" class="btn btn-warning" onclick="generateQRCode('${livestock.livestock_id}')">
                                        <i class="fas fa-qrcode"></i> Generate QR Code
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
        },
        error: function() {
            showToast('Error loading livestock details', 'error');
        }
    });
}

function submitLivestockForm() {
    const formData = new FormData($('#livestockForm')[0]);
    
    $.ajax({
        url: $('#livestockForm').attr('action'),
        method: $('#livestockForm').attr('method'),
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                $('#livestockModal').modal('hide');
                location.reload();
            } else {
                showToast(response.message || 'Error saving livestock', 'error');
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(field => {
                    showToast(errors[field][0], 'error');
                });
            } else {
                showToast('Error saving livestock', 'error');
            }
        }
    });
}

function confirmDelete(livestockId) {
    currentLivestockId = livestockId;
    $('#confirmDeleteModal').modal('show');
}

$('#confirmDeleteBtn').on('click', function() {
    if (currentLivestockId) {
        deleteLivestock(currentLivestockId);
    }
});

function deleteLivestock(livestockId) {
    $.ajax({
        url: `/farmer/livestock/${livestockId}`,
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
                showToast(response.message || 'Error deleting livestock', 'error');
            }
        },
        error: function() {
            showToast('Error deleting livestock', 'error');
        }
    });
}

function editCurrentLivestock() {
    if (currentLivestockId) {
        $('#livestockDetailsModal').modal('hide');
        openEditLivestockModal(currentLivestockId);
    }
}

function generateQRCode(livestockId) {
    // Implementation for QR code generation
    showToast('QR Code generation feature coming soon!', 'info');
}

function exportToCSV() {
    const table = $('#livestockTable').DataTable();
    const data = table.data().toArray();
    
    let csv = 'Livestock ID,Type,Breed,Age,Weight,Health Status,Production Status,Last Updated\n';
    data.forEach(row => {
        csv += `${row[0]},${row[1]},${row[2]},${row[3]},${row[4]},${row[5]},${row[6]},${row[7]}\n`;
    });
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'livestock_inventory.csv';
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

.status-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-active {
    background: rgba(28, 200, 138, 0.1);
    color: #25855a;
    border: 1px solid rgba(28, 200, 138, 0.3);
}

.status-inactive {
    background: rgba(231, 74, 59, 0.1);
    color: #e74a3b;
    border: 1px solid rgba(231, 74, 59, 0.3);
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
</style>
@endpush
