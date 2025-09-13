@extends('layouts.app')

@section('title', 'Livestock Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-horse" style="color: white; margin-right: 10px;"></i>
            Livestock Management
        </h1>
        <p>Manage your livestock inventory, health records, and productivity data</p>
    </div>

    <!-- Statistics Grid -->
    <div class="row fade-in">
        <!-- Total Livestock -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Livestock</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeCount">{{ $totalLivestock }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-users fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Healthy Livestock -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Healthy</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="healthyCount">{{ $healthyLivestock }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-heart fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Needs Attention -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Needs Attention</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="attentionCount">{{ $attentionNeeded }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-exclamation-triangle fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Production Ready -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Production Ready</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="productionCount">{{ $productionReady }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-tint fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <!-- Livestock Table -->
            <div class="card shadow mb-4 fade-in">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-list"></i>
                        Livestock Inventory
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
                            <input type="text" class="form-control" placeholder="Search livestock..." id="livestockSearch">
                        </div>
                        <div class="d-flex flex-column flex-sm-row align-items-center">
                            @if($farms->count() > 0)
                                <button class="btn-action btn-action-add" onclick="openAddLivestockModal()">
                                    <i class="fas fa-plus"></i> Add Livestock
                                </button>
                            @else
                                <button class="btn-action btn-action-add" disabled title="Create a farm first">
                                    <i class="fas fa-plus"></i> Add Livestock
                                </button>
                            @endif
                            <button class="btn-action btn-action-print" onclick="printTable()">
                                <i class="fas fa-print"></i> Print
                            </button>
                            <button class="btn-action btn-action-refresh" onclick="location.reload()">
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
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" onclick="document.getElementById('csvInput').click()">
                                        <i class="fas fa-file-import"></i> Import CSV
                                    </a>
                                </div>
                            </div>
                            <input type="file" id="csvInput" accept=".csv" style="display: none;" onchange="importCSV(event)">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="livestockTable" width="100%" cellspacing="0" data-farm-count="{{ $farms->count() }}">
                            <thead class="thead-light">
                                <tr>
                                    <th>Livestock ID</th>
                                    <th>Type</th>
                                    <th>Breed</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($livestock as $animal)
                                <tr>
                                    <td>
                                        <a href="#" class="livestock-id-link" onclick="openLivestockDetails('{{ $animal->id }}')">{{ $animal->tag_number }}</a>
                                    </td>
                                    <td>{{ ucfirst($animal->type) }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $animal->breed)) }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action btn-action-edit" onclick="openEditLivestockModal('{{ $animal->id }}')" title="Edit">
                                                <i class="fas fa-edit"></i>
                                                <span>Edit</span>
                                            </button>
                                            <button class="btn-action btn-action-delete" onclick="confirmDelete('{{ $animal->id }}')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                                <span>Delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-center text-muted py-4" colspan="4">
                                        @if($farms->count() > 0)
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>No livestock records found. Add your first livestock to get started.</p>
                                            <button class="btn btn-primary" onclick="openAddLivestockModal()">
                                                <i class="fas fa-plus"></i> Add First Livestock
                                            </button>
                                        @else
                                            <i class="fas fa-home fa-3x mb-3"></i>
                                            <p>No farms found. You need to create a farm first before adding livestock.</p>
                                            <a href="{{ route('farmer.farms') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Create Your First Farm
                                            </a>
                                        @endif
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tag_number">Tag Number *</label>
                                <input type="text" class="form-control" id="tag_number" name="tag_number" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Type *</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="cow">Cow</option>
                                    <option value="buffalo">Buffalo</option>
                                    <option value="goat">Goat</option>
                                    <option value="sheep">Sheep</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="breed">Breed *</label>
                                <select class="form-control" id="breed" name="breed" required>
                                    <option value="">Select Breed</option>
                                    <option value="holstein">Holstein</option>
                                    <option value="jersey">Jersey</option>
                                    <option value="guernsey">Guernsey</option>
                                    <option value="ayrshire">Ayrshire</option>
                                    <option value="brown_swiss">Brown Swiss</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="birth_date">Date of Birth *</label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender">Gender *</label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="weight">Weight (kg)</label>
                                <input type="number" class="form-control" id="weight" name="weight" min="0" step="0.1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="health_status">Health Status *</label>
                                <select class="form-control" id="health_status" name="health_status" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="sick">Sick</option>
                                    <option value="recovering">Recovering</option>
                                    <option value="under_treatment">Under Treatment</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status *</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
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
                <button type="button" class="btn btn-info" onclick="printLivestockRecord()" id="printLivestockBtn">
                    <i class="fas fa-print"></i> Print Record
                </button>
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
<!-- DataTables CSS and JS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
let currentLivestockId = null;

$(document).ready(function() {
    // Initialize DataTable only if the table exists
    if ($('#livestockTable').length > 0) {
        try {
            const livestockTable = $('#livestockTable').DataTable({
                dom: 'Bfrtip',
                searching: true,
                paging: true,
                info: true,
                ordering: true,
                lengthChange: false,
                pageLength: 10,
                order: [[0, 'asc']],
                columnDefs: [
                    { width: '120px', targets: 0 }, // Livestock ID
                    { width: '100px', targets: 1 }, // Type
                    { width: '150px', targets: 2 }, // Breed
                    { width: '180px', targets: 3, className: 'text-left', orderable: false, searchable: false } // Actions
                ],
                buttons: [
                    {
                        extend: 'csvHtml5',
                        title: 'Livestock_Report',
                        className: 'd-none'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Livestock_Report',
                        orientation: 'landscape',
                        pageSize: 'Letter',
                        className: 'd-none'
                    },
                    {
                        extend: 'print',
                        title: 'Livestock Report',
                        className: 'd-none'
                    }
                ],
                language: {
                    search: "",
                    emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No livestock available</h5><p>There are no livestock records to display at this time.</p></div>',
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
            
            // Hide default DataTables elements
            $('.dataTables_filter').hide();
            $('.dt-buttons').hide();
            
            // Force pagination to left side after initialization
            setTimeout(() => {
                forcePaginationLeft();
            }, 100);
            
            // Add event listeners to force pagination positioning on table updates
            livestockTable.on('draw.dt', function() {
                setTimeout(forcePaginationLeft, 50);
            });
            
            // Multiple attempts to ensure pagination stays left
            setTimeout(forcePaginationLeft, 200);
            setTimeout(forcePaginationLeft, 500);
            setTimeout(forcePaginationLeft, 1000);
            
            // Connect custom search to DataTables
            $('#livestockSearch').on('keyup', function() {
                livestockTable.search(this.value).draw();
            });
        } catch (error) {
            console.error('DataTables initialization error:', error);
        }
    }
    
    // Handle form submission
    $('#livestockForm').on('submit', function(e) {
        e.preventDefault();
        submitLivestockForm();
    });
    

});

function openAddLivestockModal() {
    // Check if Bootstrap modal is available
    if (typeof $.fn.modal === 'undefined') {
        showToast('Modal functionality is not available. Please refresh the page.', 'error');
        return;
    }
    
    // Check if user has farms - get the count from a data attribute
    const farmCount = parseInt($('#livestockTable').data('farm-count') || 0);
    if (farmCount === 0) {
        showToast('You need to create a farm first before adding livestock.', 'error');
        return;
    }
    
    $('#livestockModalLabel').html('<i class="fas fa-plus"></i> Add New Livestock');
    $('#livestockForm')[0].reset();
    $('#livestockForm').attr('action', '{{ route("farmer.livestock.store") }}');
    $('#livestockForm').attr('method', 'POST');
    $('#livestockModal').modal('show');
}

function openEditLivestockModal(livestockId) {
    // Check if Bootstrap modal is available
    if (typeof $.fn.modal === 'undefined') {
        showToast('Modal functionality is not available. Please refresh the page.', 'error');
        return;
    }
    
    currentLivestockId = livestockId;
    $('#livestockModalLabel').html('<i class="fas fa-edit"></i> Edit Livestock');
    $('#livestockForm').attr('action', `/farmer/livestock/${livestockId}`);
    $('#livestockForm').attr('method', 'POST');
    
    // Remove existing method override if any
    $('#livestockForm').find('input[name="_method"]').remove();
    $('#livestockForm').append('<input type="hidden" name="_method" value="PUT">');
    
    // Load livestock data
    loadLivestockData(livestockId);
    $('#livestockModal').modal('show');
}

function openLivestockDetails(livestockId) {
    // Check if Bootstrap modal is available
    if (typeof $.fn.modal === 'undefined') {
        showToast('Modal functionality is not available. Please refresh the page.', 'error');
        return;
    }
    
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
                $('#tag_number').val(livestock.tag_number);
                $('#name').val(livestock.name);
                $('#type').val(livestock.type);
                $('#breed').val(livestock.breed);
                $('#birth_date').val(livestock.birth_date);
                $('#weight').val(livestock.weight);
                $('#gender').val(livestock.gender);
                $('#health_status').val(livestock.health_status);
                $('#status').val(livestock.status);
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
                
                // Calculate age from birth date
                const age = livestock.birth_date ? calculateAge(livestock.birth_date) : 'Unknown';
                
                // Format dates properly
                const birthDate = livestock.birth_date ? new Date(livestock.birth_date).toLocaleDateString() : 'Not recorded';
                const createdDate = livestock.created_at ? new Date(livestock.created_at).toLocaleDateString() : 'Not recorded';
                const updatedDate = livestock.updated_at ? new Date(livestock.updated_at).toLocaleDateString() : 'Not recorded';
                
                $('#livestockDetailsContent').html(`
                    <!-- QR Code Section at the top -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-qrcode"></i>
                                        QR Code Status
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    <div id="qrCodeContainer" class="mb-3">
                                        <p class="text-muted">Checking QR code status...</p>
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="checkQRCodeStatus('${livestock.id}')">
                                        <i class="fas fa-search"></i> Check QR Code Status
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-info-circle"></i>
                                        Quick Info
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Tag Number:</small>
                                            <p class="font-weight-bold">${livestock.tag_number || 'N/A'}</p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Name:</small>
                                            <p class="font-weight-bold">${livestock.name || 'N/A'}</p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Type:</small>
                                            <p class="font-weight-bold">${livestock.type ? livestock.type.charAt(0).toUpperCase() + livestock.type.slice(1) : 'N/A'}</p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Breed:</small>
                                            <p class="font-weight-bold">${livestock.breed ? livestock.breed.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'N/A'}</p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Age:</small>
                                            <p class="font-weight-bold">${age}</p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Status:</small>
                                            <p class="font-weight-bold"><span class="badge badge-${livestock.status === 'active' ? 'success' : 'secondary'}">${livestock.status ? livestock.status.charAt(0).toUpperCase() + livestock.status.slice(1) : 'N/A'}</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Option Bar -->
                    <ul class="nav nav-tabs mb-3" id="livestockTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basicForm" role="tab">Basic Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="production-tab" data-toggle="tab" href="#productionForm" role="tab">Production</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="health-tab" data-toggle="tab" href="#healthForm" role="tab">Health</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="breeding-tab" data-toggle="tab" href="#breedingForm" role="tab">Breeding</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="livestockTabContent">
                        <!-- Basic Info Tab -->
                        <div class="tab-pane fade show active" id="basicForm" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><th>Tag Number</th><td>${livestock.tag_number || 'Not assigned'}</td></tr>
                                        <tr><th>Name</th><td>${livestock.name || 'Not assigned'}</td></tr>
                                        <tr><th>Type</th><td>${livestock.type ? livestock.type.charAt(0).toUpperCase() + livestock.type.slice(1) : 'Not recorded'}</td></tr>
                                        <tr><th>Breed</th><td>${livestock.breed ? livestock.breed.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'Not recorded'}</td></tr>
                                        <tr><th>Date of Birth</th><td>${birthDate}</td></tr>
                                        <tr><th>Age</th><td>${age}</td></tr>
                                        <tr><th>Gender</th><td>${livestock.gender ? livestock.gender.charAt(0).toUpperCase() + livestock.gender.slice(1) : 'Not recorded'}</td></tr>
                                        <tr><th>Weight</th><td>${livestock.weight ? livestock.weight + ' kg' : 'Not recorded'}</td></tr>
                                        <tr><th>Health Status</th><td><span class="badge badge-${livestock.health_status === 'healthy' ? 'success' : livestock.health_status === 'sick' ? 'danger' : 'warning'}">${livestock.health_status ? livestock.health_status.charAt(0).toUpperCase() + livestock.health_status.slice(1) : 'Not recorded'}</span></td></tr>
                                        <tr><th>Status</th><td><span class="badge badge-${livestock.status === 'active' ? 'success' : 'secondary'}">${livestock.status ? livestock.status.charAt(0).toUpperCase() + livestock.status.slice(1) : 'Not recorded'}</span></td></tr>
                                        <tr><th>Farm</th><td>${livestock.farm ? livestock.farm.name : 'Not assigned'}</td></tr>
                                        <tr><th>Registry ID</th><td>${livestock.registry_id || 'Not assigned'}</td></tr>
                                        <tr><th>Natural Marks</th><td>${livestock.natural_marks || 'None recorded'}</td></tr>
                                        <tr><th>Property Number</th><td>${livestock.property_no || 'Not assigned'}</td></tr>
                                        <tr><th>Acquisition Date</th><td>${livestock.acquisition_date ? new Date(livestock.acquisition_date).toLocaleDateString() : 'Not recorded'}</td></tr>
                                        <tr><th>Acquisition Cost</th><td>${livestock.acquisition_cost ? '₱' + parseFloat(livestock.acquisition_cost).toFixed(2) : 'Not recorded'}</td></tr>
                                        <tr><th>Remarks</th><td>${livestock.remarks || 'None'}</td></tr>
                                        <tr><th>Created</th><td>${createdDate}</td></tr>
                                        <tr><th>Last Updated</th><td>${updatedDate}</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Production Tab -->
                        <div class="tab-pane fade" id="productionForm" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Production Type</th>
                                            <th>Quantity</th>
                                            <th>Quality</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productionRecordsTable">
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                <i class="fas fa-info-circle"></i>
                                                No production records found. Production data will be displayed here when available.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right mt-3">
                                <button class="btn btn-primary" onclick="addProductionRecord('${livestock.id}')">
                                    <i class="fas fa-plus"></i> Add Production Record
                                </button>
                            </div>
                        </div>
                        
                        <!-- Health Tab -->
                        <div class="tab-pane fade" id="healthForm" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Health Status</th>
                                            <th>Treatment</th>
                                            <th>Veterinarian</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody id="healthRecordsTable">
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                <i class="fas fa-info-circle"></i>
                                                No health records found. Health data will be displayed here when available.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right mt-3">
                                <button class="btn btn-primary" onclick="addHealthRecord('${livestock.id}')">
                                    <i class="fas fa-plus"></i> Add Health Record
                                </button>
                            </div>
                        </div>
                        
                        <!-- Breeding Tab -->
                        <div class="tab-pane fade" id="breedingForm" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><th>Sire ID</th><td>${livestock.sire_id || 'Not recorded'}</td></tr>
                                        <tr><th>Sire Name</th><td>${livestock.sire_name || 'Not recorded'}</td></tr>
                                        <tr><th>Dam ID</th><td>${livestock.dam_id || 'Not recorded'}</td></tr>
                                        <tr><th>Dam Name</th><td>${livestock.dam_name || 'Not recorded'}</td></tr>
                                        <tr><th>Dispersal From</th><td>${livestock.dispersal_from || 'Not recorded'}</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right mt-3">
                                <button class="btn btn-primary" onclick="addBreedingRecord('${livestock.id}')">
                                    <i class="fas fa-plus"></i> Add Breeding Record
                                </button>
                            </div>
                        </div>
                    </div>
                `);
                
                // Load production records
                loadProductionRecords(livestockId);
                
                // Automatically check QR code status
                setTimeout(() => {
                    checkQRCodeStatus(livestockId);
                }, 500);
            }
        },
        error: function() {
            showToast('Error loading livestock details', 'error');
        }
    });
}

function submitLivestockForm() {
    const formData = new FormData($('#livestockForm')[0]);
    const method = $('#livestockForm').attr('method');
    
    // Debug: Log form data
    console.log('Form action:', $('#livestockForm').attr('action'));
    console.log('Form method:', method);
    console.log('CSRF token:', $('meta[name="csrf-token"]').attr('content'));
    
    // Log form data entries
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    
    // Add method override for PUT requests
    if (method === 'POST' && $('#livestockForm').find('input[name="_method"]').length > 0) {
        formData.append('_method', 'PUT');
    }
    
    $.ajax({
        url: $('#livestockForm').attr('action'),
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Success response:', response);
            if (response.success) {
                showToast(response.message, 'success');
                $('#livestockModal').modal('hide');
                location.reload();
            } else {
                showToast(response.message || 'Error saving livestock', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.log('Error response:', xhr.responseText);
            console.log('Status:', status);
            console.log('Error:', error);
            
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(field => {
                    showToast(errors[field][0], 'error');
                });
            } else {
                showToast('Error saving livestock: ' + (xhr.responseJSON?.message || error), 'error');
            }
        }
    });
}

function confirmDelete(livestockId) {
    // Check if Bootstrap modal is available
    if (typeof $.fn.modal === 'undefined') {
        showToast('Modal functionality is not available. Please refresh the page.', 'error');
        return;
    }
    
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

function printLivestockRecord() {
    if (currentLivestockId) {
        // Open the print page in a new window
        const printUrl = `/farmer/livestock/${currentLivestockId}/print`;
        window.open(printUrl, '_blank');
    }
}

function checkQRCodeStatus(livestockId) {
    // Show loading state
    const qrContainer = document.getElementById('qrCodeContainer');
    qrContainer.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking QR Code Status...';
    
    $.ajax({
        url: `/farmer/livestock/${livestockId}/qr-code`,
        method: 'GET',
        success: function(response) {
            if (response.success && response.qr_code_exists) {
                // Display the QR code
                const generatedDate = response.generated_at ? new Date(response.generated_at).toLocaleDateString() : 'Unknown';
                qrContainer.innerHTML = `
                    <div class="text-center">
                        <img src="${response.qr_code}" alt="QR Code for ${response.livestock_id}" class="img-fluid mb-2" style="max-width: 200px;">
                        <p class="text-muted small">QR Code for ${response.livestock_id}</p>
                        <p class="text-muted small">Generated by: ${response.generated_by}</p>
                        <p class="text-muted small">Generated on: ${generatedDate}</p>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="downloadQRCode('${response.qr_code}', '${response.livestock_id}')">
                            <i class="fas fa-download"></i> Download QR Code
                        </button>
                    </div>
                `;
                showToast('QR Code is available for download!', 'success');
            } else {
                // QR code not generated yet
                qrContainer.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                        <h6 class="text-warning">QR Code Not Generated</h6>
                        <p class="text-muted">This livestock does not have a QR code yet.</p>
                        <p class="text-muted small">Please contact an administrator to generate the QR code.</p>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> Only administrators can generate QR codes for livestock.
                        </div>
                    </div>
                `;
                showToast('QR Code has not been generated yet. Contact an administrator.', 'warning');
            }
        },
        error: function() {
            qrContainer.innerHTML = '<p class="text-danger">Error checking QR Code status</p>';
            showToast('Error checking QR Code status', 'error');
        }
    });
}

function downloadQRCode(qrCodeUrl, livestockId) {
    // Create a temporary link to download the QR code
    const link = document.createElement('a');
    link.href = qrCodeUrl;
    link.download = `QR_Code_${livestockId}.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    showToast('QR Code downloaded successfully!', 'success');
}

function calculateAge(birthDate) {
    const birth = new Date(birthDate);
    const today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    const monthDiff = today.getMonth() - birth.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
        age--;
    }
    
    if (age === 0) {
        const monthAge = today.getMonth() - birth.getMonth();
        if (monthAge <= 0) {
            const dayAge = today.getDate() - birth.getDate();
            return dayAge <= 0 ? 'Less than 1 day' : `${dayAge} day${dayAge > 1 ? 's' : ''}`;
        }
        return `${monthAge} month${monthAge > 1 ? 's' : ''}`;
    }
    
    return `${age} year${age > 1 ? 's' : ''}`;
}

function loadProductionRecords(livestockId) {
    $.ajax({
        url: `/farmer/livestock/${livestockId}/production-records`,
        method: 'GET',
        success: function(response) {
            if (response.success && response.data.length > 0) {
                const tbody = document.getElementById('productionRecordsTable');
                tbody.innerHTML = '';
                
                response.data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${new Date(record.production_date).toLocaleDateString()}</td>
                        <td>${record.production_type || 'N/A'}</td>
                        <td>${record.quantity || 'N/A'}</td>
                        <td>${record.quality || 'N/A'}</td>
                        <td>${record.notes || 'N/A'}</td>
                    `;
                    tbody.appendChild(row);
                });
            }
        },
        error: function() {
            // Keep the default "no records" message
        }
    });
}

function addProductionRecord(livestockId) {
    showToast('Production record functionality coming soon!', 'info');
}

function addHealthRecord(livestockId) {
    showToast('Health record functionality coming soon!', 'info');
}

function addBreedingRecord(livestockId) {
    showToast('Breeding record functionality coming soon!', 'info');
}

function exportToCSV() {
    const table = $('#livestockTable').DataTable();
    const data = table.data().toArray();
    
    let csv = 'Livestock ID,Type,Breed\n';
    data.forEach(row => {
        // Only export the first 3 columns that match our table structure
        csv += `${row[0]},${row[1]},${row[2]}\n`;
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

function exportToPNG() {
    showToast('PNG export functionality will be implemented soon', 'info');
}

function importCSV(event) {
    const file = event.target.files[0];
    if (file) {
        showToast('CSV import functionality will be implemented soon', 'info');
        // Reset the file input
        event.target.value = '';
    }
}

function updateActivity(selectElement, livestockId) {
    const newStatus = selectElement.value;
    
    // Make AJAX call to update the database
    $.ajax({
        url: `/farmer/livestock/${livestockId}/status`,
        method: 'POST',
        data: {
            status: newStatus,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showToast(`Status updated to ${newStatus} for livestock ${livestockId}`, 'success');
                // Update the stats if needed
                updateStats();
                // Update the original value
                selectElement.setAttribute('data-original-value', newStatus);
            } else {
                showToast(response.message || 'Error updating status', 'error');
                // Revert the select to previous value
                selectElement.value = selectElement.getAttribute('data-original-value') || 'active';
            }
        },
        error: function() {
            showToast('Error updating status', 'error');
            // Revert the select to previous value
            selectElement.value = selectElement.getAttribute('data-original-value') || 'active';
        }
    });
}

function updateStats() {
    // This function can be called to refresh the statistics
    // You can implement AJAX call to get updated stats
}

// Initialize tooltips if Bootstrap is available
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.tooltip) {
        $('[data-toggle="tooltip"]').tooltip();
    }
});

// Function to force pagination positioning to the left - Match SuperAdmin User Directory
function forcePaginationLeft() {
    console.log('Forcing pagination to left - livestock inventory...');
    
    // Force wrapper layout
    $('.dataTables_wrapper .row').css({
        'display': 'block',
        'width': '100%',
        'margin': '0',
        'padding': '0'
    });
    
    $('.dataTables_wrapper .row > div').css({
        'width': '100%',
        'float': 'left',
        'clear': 'both',
        'padding': '0',
        'margin': '0'
    });
    
    // Force pagination and info to left
    $('.dataTables_wrapper .dataTables_paginate').css({
        'text-align': 'left',
        'float': 'left',
        'clear': 'both',
        'display': 'block',
        'width': 'auto',
        'margin-right': '1rem',
        'margin-top': '1rem'
    });
    
    $('.dataTables_wrapper .dataTables_info').css({
        'text-align': 'left',
        'float': 'left',
        'clear': 'both',
        'display': 'block',
        'width': 'auto',
        'margin-right': '1rem',
        'margin-top': '1rem'
    });
    
    
    console.log('Pagination positioning applied to livestock inventory');
}

function showToast(message, type = 'info') {
    // Create a simple alert instead of Bootstrap 5 toast for Bootstrap 4 compatibility
    const alertClass = type === 'error' ? 'alert-danger' : type === 'success' ? 'alert-success' : 'alert-info';
    const alert = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <strong>${type.charAt(0).toUpperCase() + type.slice(1)}:</strong> ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    
    // Add alert to page
    const alertContainer = document.createElement('div');
    alertContainer.innerHTML = alert;
    document.body.appendChild(alertContainer);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alertContainer.parentNode) {
            alertContainer.parentNode.removeChild(alertContainer);
        }
    }, 5000);
}
</script>
@endpush

@push('styles')
<style>

/* Search and button group alignment - Match SuperAdmin */
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

/* Enhanced Card Styling */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    overflow: hidden;
}

/* Override card header to avoid duplicate blue backgrounds */
.card-header.bg-primary {
    background: #18375d !important;
    color: white !important;
    border-bottom: none !important;
}

.card-header.bg-primary h6 {
    color: white !important;
    font-weight: 600;
}

.card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

.card-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    border-bottom: none;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.card-header h6 {
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-header h6::before {
    content: '';
    width: 4px;
    height: 20px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 2px;
}

/* Enhanced Button Styling */
.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
    border: none;
    font-size: 0.85rem;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-sm {
    padding: 0.4rem 0.8rem;
    font-size: 0.8rem;
}

/* Stats Cards */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: var(--shadow);
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, rgba(78, 115, 223, 0.1) 0%, rgba(78, 115, 223, 0.05) 100%);
    border-radius: 50%;
    transform: translate(30px, -30px);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 1;
}

.stat-label {
    color: var(--dark-color);
    margin: 0;
    font-weight: 500;
    position: relative;
    z-index: 1;
}


/* Make cow icon visible */
.page-header h1 i.fas.fa-horse {
    color: white !important;
    display: inline-block !important;
    visibility: visible !important;
    font-family: "Font Awesome 6 Free" !important;
    font-weight: 900 !important;
}

/* Action buttons styling - Match Super Admin Exactly */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    justify-content: center;
    min-width: 160px;
}

/* Force delete button to be red */
.btn-action-delete,
.action-buttons .btn-action-delete,
#livestockTable .btn-action-delete {
    background-color: #c82333 !important;
    border-color: #c82333 !important;
    color: white !important;
}

.btn-action-delete:hover,
.action-buttons .btn-action-delete:hover,
#livestockTable .btn-action-delete:hover {
    background-color: #a71e2a !important;
    border-color: #a71e2a !important;
    color: white !important;
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
    background-color: #2d5a47;
    border-color: #2d5a47;
    color: white;
}


/* Header action buttons styling to match superadmin */
.btn-action-add {
    background-color: #387057;
    border-color: #387057;
    color: white;
}

.btn-action-add:hover {
    background-color: #2d5a47;
    border-color: #2d5a47;
    color: white;
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
    background-color: #fca700;
    border-color: #fca700;
    color: white;
}

.btn-action-refresh:hover {
    background-color: #e69500;
    border-color: #e69500;
    color: white;
}

.btn-action-tools {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #495057;
}

.btn-action-tools:hover {
    background-color: #e2e6ea;
    border-color: #cbd3da;
    color: #495057;
}


/* Enhanced Table Styling - Matching Super Admin Style */
#livestockTable {
    width: 100% !important;
    min-width: 1280px;
    border-collapse: collapse;
}

.table {
    margin-bottom: 0;
}

.table-bordered {
    border: 1px solid #dee2e6;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.075);
}

#livestockTable th,
#livestockTable td {
    vertical-align: middle;
    padding: 0.75rem;
    text-align: center;
    border: 1px solid #dee2e6;
    white-space: nowrap;
    overflow: visible;
}

/* Ensure Actions column has enough space and proper alignment */
#livestockTable th:last-child,
#livestockTable td:last-child {
    min-width: 180px !important;
    width: 180px !important;
    text-align: left !important;
    white-space: nowrap;
    overflow: visible;
    text-overflow: initial;
}

/* Ensure all table headers have consistent styling - Super Admin Style */
#livestockTable thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: bold;
    color: #495057;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    text-align: left;
    vertical-align: middle;
    position: relative;
    white-space: nowrap;
}

/* Fix DataTables sorting button overlap */
#livestockTable thead th.sorting,
#livestockTable thead th.sorting_asc,
#livestockTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#livestockTable thead th::after {
    content: '';
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
}

/* Remove default DataTables sort indicators to prevent overlap */
#livestockTable thead th.sorting::after,
#livestockTable thead th.sorting_asc::after,
#livestockTable thead th.sorting_desc::after {
    display: none;
}

/* Table responsiveness and spacing - Super Admin Style */
.table-responsive {
    overflow-x: auto;
    min-width: 100%;
    position: relative;
    border-radius: 8px;
    overflow: hidden;
}

/* Ensure DataTables controls are properly positioned */
.table-responsive + .dataTables_wrapper,
.table-responsive .dataTables_wrapper {
    width: 100%;
    position: relative;
}

/* Fix pagination positioning for wide tables - Match SuperAdmin */
.table-responsive .dataTables_wrapper .dataTables_paginate {
    position: relative;
    width: 100%;
    text-align: left;
    margin: 1rem 0;
    left: 0;
    right: 0;
}


/* Hide only DataTables search and length controls - show pagination and info */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    display: none !important;
}

/* DataTables Pagination Styling - Match SuperAdmin User Directory Exactly */
.dataTables_wrapper .dataTables_paginate {
    text-align: left !important;
    margin-top: 1rem;
    clear: both;
    width: 100%;
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


/* Ensure pagination container is properly positioned - Match SuperAdmin */
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

/* Empty State Styling - Match Super Admin */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    color: #dee2e6;
}

.empty-state h5 {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #495057;
}

.empty-state p {
    margin-bottom: 0;
    font-size: 0.9rem;
}


/* Livestock ID link styling - match superadmin theme */
.livestock-id-link {
    color: #18375d;
    text-decoration: none;
    font-weight: 600;
    cursor: pointer;
    transition: color 0.2s ease;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    background-color: rgba(24, 55, 93, 0.1);
    border: 1px solid rgba(24, 55, 93, 0.2);
}

.livestock-id-link:hover {
    color: #fff;
    background-color: #18375d;
    border-color: #18375d;
    text-decoration: none;
}

.livestock-id-link:active {
    color: #fff;
    background-color: #122a4e;
    border-color: #122a4e;
}

/* Search Container */
.custom-search {
    border-radius: 8px;
    border: 2px solid var(--border-color);
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
}

.custom-search:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}



/* Status Badge Styling */
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

/* Animations */
.fade-in {
    animation: fadeIn 0.6s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Form Styling */
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 0.5rem;
    display: block;
}

.form-control {
    border-radius: 8px;
    border: 2px solid var(--border-color);
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.form-control[readonly] {
    background-color: #f8f9fc;
    border-color: #e3e6f0;
}

/* Modal Enhancement */
.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: var(--shadow-lg);
}

.modal-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    border-bottom: none;
    border-radius: 12px 12px 0 0;
}

.modal-header .close {
    color: white;
    opacity: 0.8;
}

.modal-header .close:hover {
    opacity: 1;
}

.modal-title {
    color: white;
    font-weight: 600;
}

/* Tab Enhancement */
.nav-tabs {
    border-bottom: 2px solid var(--border-color);
    margin-bottom: 1.5rem;
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: var(--dark-color);
    font-weight: 500;
    padding: 1rem 1.5rem;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
}

.nav-tabs .nav-link.active {
    background: none;
    border-color: var(--primary-color);
    color: var(--primary-color);
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
    .table-controls {
        flex-direction: column;
        align-items: stretch;
    }

    .search-container {
        min-width: 100%;
    }

    .export-controls {
        margin-left: 0;
        margin-top: 1rem;
    }

    .stats-container {
        grid-template-columns: 1fr;
    }

    .container-fluid {
        padding: 1rem;
    }
    
    .modal-dialog {
        margin: 1rem;
    }
}

.toast-container {
    z-index: 9999;
}
</style>
@endpush

