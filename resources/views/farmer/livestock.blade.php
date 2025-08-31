@extends('layouts.app')

@section('title', 'Livestock Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-cow"></i>
            Livestock Management
        </h1>
        <p>Manage your livestock inventory, health records, and productivity data</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container fade-in">
        <div class="stat-card">
            <div class="stat-number" style="color: var(--success-color);" id="activeCount">{{ $totalLivestock }}</div>
            <div class="stat-label">Total Livestock</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: var(--success-color);" id="healthyCount">{{ $healthyLivestock }}</div>
            <div class="stat-label">Healthy</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: var(--warning-color);" id="attentionCount">{{ $attentionNeeded }}</div>
            <div class="stat-label">Needs Attention</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: var(--info-color);" id="productionCount">{{ $productionReady }}</div>
            <div class="stat-label">Production Ready</div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <!-- Livestock Table -->
            <div class="card shadow mb-4 fade-in">
                <div class="card-header">
                    <h6>
                        <i class="fas fa-list"></i>
                        Livestock Inventory
                    </h6>
                    <div class="table-controls" style="gap: 1rem; flex-wrap: wrap; align-items: center; display: flex;">
                        <div class="search-container" style="min-width: 250px;">
                            <input type="text" class="form-control custom-search" id="customSearch" placeholder="Search livestock...">
                        </div>
                        <div class="export-controls" style="display: flex; gap: 0.5rem; align-items: center;">
                            @if($farms->count() > 0)
                                <button class="btn btn-primary btn-sm" onclick="openAddLivestockModal()">
                                    <i class="fas fa-plus"></i> Add Livestock
                                </button>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled title="Create a farm first">
                                    <i class="fas fa-plus"></i> Add Livestock
                                </button>
                            @endif
                            <div class="btn-group">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="fas fa-download"></i> Export
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#" onclick="exportToCSV()">
                                        <i class="fas fa-file-csv"></i> CSV
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="exportToPDF()">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="exportToPNG()">
                                        <i class="fas fa-image"></i> PNG
                                    </a>
                                </div>
                            </div>
                            <button class="btn btn-secondary btn-sm" onclick="printTable()">
                                <i class="fas fa-print"></i>
                            </button>
                            <button class="btn btn-success btn-sm" onclick="document.getElementById('csvInput').click()">
                                <i class="fas fa-file-import"></i> Import
                            </button>
                            <input type="file" id="csvInput" accept=".csv" style="display: none;" onchange="importCSV(event)">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="livestockTable" width="100%" cellspacing="0" data-farm-count="{{ $farms->count() }}">
                            <thead>
                                <tr>
                                    <th>Livestock ID</th>
                                    <th>Type</th>
                                    <th>Breed</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($livestock as $animal)
                                <tr>
                                    <td>
                                        <a href="#" class="livestock-link" onclick="openLivestockDetails('{{ $animal->id }}')">{{ $animal->tag_number }}</a>
                                    </td>
                                    <td>{{ ucfirst($animal->type) }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $animal->breed)) }}</td>
                                    <td>
                                        <select class="form-control" onchange="updateActivity(this, '{{ $animal->id }}')" data-original-value="{{ strtolower($animal->status) }}">
                                            <option value="active" {{ $animal->status == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $animal->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action btn-action-edit" onclick="openEditLivestockModal('{{ $animal->id }}')" title="Edit">
                                                <i class="fas fa-edit"></i>
                                                <span>Edit</span>
                                            </button>
                                            <a href="{{ route('farmer.livestock.print', $animal->id) }}" class="btn-action btn-action-print" title="Print Record" target="_blank">
                                                <i class="fas fa-print"></i>
                                                <span>Print</span>
                                            </a>
                                            <button class="btn-action btn-action-delete" onclick="confirmDelete('{{ $animal->id }}')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                                <span>Delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-center text-muted py-4">
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
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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
                    },
                    emptyTable: "No livestock records found"
                },
                columnDefs: [
                    {
                        targets: -1, // Actions column
                        orderable: false,
                        searchable: false
                    }
                ]
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
                $('#livestockDetailsContent').html(`
                    <!-- QR Code Section at the top -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-qrcode"></i>
                                        QR Code Generation
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    <div id="qrCodeContainer" class="mb-3">
                                        <p class="text-muted">Click "Generate QR" button to create QR code</p>
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="generateQRCode('${livestock.tag_number}')">
                                        <i class="fas fa-qrcode"></i> Generate QR Code
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
                                            <small class="text-muted">Livestock ID:</small>
                                            <p class="font-weight-bold">${livestock.tag_number}</p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Name:</small>
                                            <p class="font-weight-bold">${livestock.name}</p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Breed:</small>
                                            <p class="font-weight-bold">${livestock.breed}</p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Owner:</small>
                                            <p class="font-weight-bold">${livestock.owner_id || 'Not assigned'}</p>
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
                            <a class="nav-link" id="growth-tab" data-toggle="tab" href="#growthForm" role="tab">Growth</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="milk-tab" data-toggle="tab" href="#milkForm" role="tab">Milk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="breeding-tab" data-toggle="tab" href="#breedingForm" role="tab">Breeding</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="health-tab" data-toggle="tab" href="#healthForm" role="tab">Health</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="livestockTabContent">
                        <!-- Basic Info Tab -->
                        <div class="tab-pane fade show active" id="basicForm" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><th>Owned By</th><td>${livestock.owner_id || 'Not assigned'}</td></tr>
                                        <tr><th>Tag ID</th><td>${livestock.tag_number}</td></tr>
                                        <tr><th>Name</th><td>${livestock.name}</td></tr>
                                        <tr><th>Date of Birth</th><td>${livestock.birth_date || 'Not recorded'}</td></tr>
                                        <tr><th>Sex</th><td>${livestock.gender}</td></tr>
                                        <tr><th>Breed</th><td>${livestock.breed}</td></tr>
                                        <tr><th>Type</th><td>${livestock.type}</td></tr>
                                        <tr><th>Weight</th><td>${livestock.weight || 'Not recorded'} kg</td></tr>
                                        <tr><th>Health Status</th><td><span class="status-badge status-${livestock.health_status === 'healthy' ? 'active' : 'inactive'}">${livestock.health_status}</span></td></tr>
                                        <tr><th>Status</th><td><span class="status-badge status-${livestock.status === 'active' ? 'active' : 'inactive'}">${livestock.status}</span></td></tr>
                                        <tr><th>Created</th><td>${livestock.created_at || 'Not recorded'}</td></tr>
                                        <tr><th>Last Updated</th><td>${livestock.updated_at || 'Not recorded'}</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Growth Tab -->
                        <div class="tab-pane fade" id="growthForm" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><th>Date</th><td><input type="date" class="form-control" id="growthDate"></td></tr>
                                        <tr><th>Weight (kg)</th><td><input type="number" class="form-control" id="weight" step="0.1"></td></tr>
                                        <tr><th>Height (cm)</th><td><input type="number" class="form-control" id="height"></td></tr>
                                        <tr><th>Heart Girth (cm)</th><td><input type="number" step="0.1" class="form-control" id="heartGirthCm"></td></tr>
                                        <tr><th>Body Length (cm)</th><td><input type="number" step="0.1" class="form-control" id="bodyLengthCm"></td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right mt-3">
                                <button class="btn btn-primary">Save Growth Record</button>
                            </div>
                        </div>
                        
                        <!-- Milk Tab -->
                        <div class="tab-pane fade" id="milkForm" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><th>Date of Calving</th><td><input type="date" class="form-control" id="calvingDate"></td></tr>
                                        <tr><th>Calf ID Number</th><td><input type="text" class="form-control" id="calfIdNumber"></td></tr>
                                        <tr><th>Sex</th><td>
                                            <select class="form-control" id="calfSex">
                                                <option value="">Select Sex</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </td></tr>
                                        <tr><th>Breed</th><td><input type="text" class="form-control" id="calfBreed"></td></tr>
                                        <tr><th>Milk Production (liters)</th><td><input type="number" step="0.01" class="form-control" id="milkProduction"></td></tr>
                                        <tr><th>Days in Milk</th><td><input type="number" class="form-control" id="daysInMilk"></td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right mt-3">
                                <button class="btn btn-primary">Save Milk Record</button>
                            </div>
                        </div>
                        
                        <!-- Breeding Tab -->
                        <div class="tab-pane fade" id="breedingForm" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><th>Breeding Date</th><td><input type="date" class="form-control" id="breedingDate"></td></tr>
                                        <tr><th>Breeding Type</th><td>
                                            <select class="form-control" id="breedingType">
                                                <option value="Natural">Natural</option>
                                                <option value="Artificial Insemination">Artificial Insemination</option>
                                            </select>
                                        </td></tr>
                                        <tr><th>Sire Registry ID</th><td><input type="text" class="form-control" id="sireId"></td></tr>
                                        <tr><th>Dam Registry ID</th><td><input type="text" class="form-control" id="damId"></td></tr>
                                        <tr><th>Pregnancy Check Date</th><td><input type="date" class="form-control" id="pregnancyCheckDate"></td></tr>
                                        <tr><th>Pregnancy Result</th><td>
                                            <select class="form-control" id="pregnancyResult">
                                                <option value="Positive">Positive</option>
                                                <option value="Negative">Negative</option>
                                                <option value="Unknown">Unknown</option>
                                            </select>
                                        </td></tr>
                                        <tr><th>Remarks</th><td><textarea class="form-control" id="breedingRemarks" rows="3"></textarea></td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right mt-3">
                                <button class="btn btn-primary">Save Breeding Record</button>
                            </div>
                        </div>
                        
                        <!-- Health Tab -->
                        <div class="tab-pane fade" id="healthForm" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><th>Date</th><td><input type="date" class="form-control" id="healthDate"></td></tr>
                                        <tr><th>Health Status</th><td><input type="text" class="form-control" id="healthStatus"></td></tr>
                                        <tr><th>Treatment</th><td><input type="text" class="form-control" id="treatment"></td></tr>
                                        <tr><th>Remarks</th><td><textarea class="form-control" id="healthRemarks" rows="3"></textarea></td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right mt-3">
                                <button class="btn btn-primary">Save Health Record</button>
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

function generateQRCode(livestockId) {
    // Implementation for QR code generation
    showToast('QR Code generation feature coming soon!', 'info');
}

function exportToCSV() {
    const table = $('#livestockTable').DataTable();
    const data = table.data().toArray();
    
    let csv = 'Livestock ID,Type,Breed,Status\n';
    data.forEach(row => {
        // Only export the first 4 columns that match our table structure
        csv += `${row[0]},${row[1]},${row[2]},${row[3]}\n`;
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

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('customSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('livestockTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            let visibleCount = 0;
            
            for (let row of rows) {
                const cells = row.getElementsByTagName('td');
                let found = false;
                
                for (let cell of cells) {
                    if (cell.textContent.toLowerCase().includes(searchTerm)) {
                        found = true;
                        break;
                    }
                }
                
                if (found) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            }
            
            // Show message if no results found
            if (visibleCount === 0 && searchTerm !== '') {
                showToast('No livestock found matching your search', 'info');
            }
        });
        
        // Clear search on escape key
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
                this.dispatchEvent(new Event('input'));
            }
        });
    }
    
    // Initialize tooltips if Bootstrap is available
    if (typeof $ !== 'undefined' && $.fn.tooltip) {
        $('[data-toggle="tooltip"]').tooltip();
    }
});

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


/* Enhanced Card Styling */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    overflow: hidden;
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

/* Enhanced Table Styling */
.table {
    margin-bottom: 0;
    font-size: 0.9rem;
}

.table thead th {
    background-color: #f8f9fc;
    border-top: none;
    border-bottom: 2px solid var(--border-color);
    font-weight: 600;
    color: var(--dark-color);
    padding: 1rem 0.75rem;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-color: var(--border-color);
}

/* Livestock Link Styling */
.livestock-link {
    color: var(--primary-color);
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.livestock-link:hover {
    color: var(--primary-dark);
    text-decoration: underline;
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

