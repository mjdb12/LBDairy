@extends('layouts.app')

@section('title', 'Livestock Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-cow"></i>
            Livestock Management
        </h1>
        <p>Comprehensive livestock tracking and management system for your dairy operations</p>
    </div>

    <!-- Farmer Selection Section -->
    <div class="card shadow mb-4 fade-in" id="farmerSelectionCard">
        <div class="card-header">
            <h6>
                <i class="fas fa-users"></i>
                Select Farmer
            </h6>
            <div class="table-controls" style="gap: 1rem; flex-wrap: wrap; align-items: center; display: flex;">
                <div class="search-container" style="min-width: 250px;">
                    <input type="text" class="form-control custom-search" id="farmerSearch" placeholder="Search farmers...">
                </div>
                <button class="btn btn-info btn-sm" onclick="refreshFarmerData()" title="Refresh Data">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="farmersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Farmer ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Barangay</th>
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

    <!-- Livestock Section (Initially Hidden) -->
    <div class="card shadow mb-4 fade-in" id="livestockCard" style="display: none;">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>
                        <i class="fas fa-cow"></i>
                        Livestock for: <span id="selectedFarmerName" class="text-primary font-weight-bold"></span>
                    </h6>
                    <small class="text-muted">Showing livestock for the selected farmer</small>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-secondary btn-sm" onclick="backToFarmers()">
                        <i class="fas fa-arrow-left"></i> Back to Farmers
                    </button>
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addLivestockModal">
                        <i class="fas fa-plus"></i> Add Livestock
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Statistics Cards for Selected Farmer -->
            <div class="row mb-3" id="farmerStats">
                <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Livestock</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="farmerTotalLivestock">0</div>
                            </div>
                            <div class="icon text-success">
                                <i class="fas fa-cow fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Active Livestock</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="farmerActiveLivestock">0</div>
                            </div>
                            <div class="icon text-info">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Inactive Livestock</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="farmerInactiveLivestock">0</div>
                            </div>
                            <div class="icon text-warning">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Farms</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="farmerTotalFarms">0</div>
                            </div>
                            <div class="icon text-primary">
                                <i class="fas fa-home fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Livestock Table -->
            <div class="table-responsive">
                <table class="table table-bordered" id="livestockTable" width="100%" cellspacing="0">
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
</div>

<!-- Add Livestock Modal -->
<div class="modal fade" id="addLivestockModal" tabindex="-1" role="dialog" aria-labelledby="addLivestockLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLivestockLabel">
                    <i class="fas fa-plus"></i> Add New Livestock
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addLivestockForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="selectedFarmerId" name="farmer_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="livestock_id">Tag Number</label>
                                <input type="text" class="form-control" id="livestock_id" name="livestock_id" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="cow">Cow</option>
                                    <option value="buffalo">Buffalo</option>
                                    <option value="goat">Goat</option>
                                    <option value="sheep">Sheep</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="breed">Breed</label>
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
                                <label for="farm_id">Farm</label>
                                <select class="form-control" id="farm_id" name="farm_id" required>
                                    <option value="">Select Farm</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="birth_date">Birth Date</label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date" required max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="weight">Weight (kg)</label>
                                <input type="number" step="0.1" class="form-control" id="weight" name="weight">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Livestock</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Livestock Modal -->
<div class="modal fade" id="editLivestockModal" tabindex="-1" role="dialog" aria-labelledby="editLivestockLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLivestockLabel">
                    <i class="fas fa-edit"></i> Edit Livestock
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editLivestockForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_livestock_id">Tag Number</label>
                                <input type="text" class="form-control" id="edit_livestock_id" name="livestock_id" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_type">Type</label>
                                <select class="form-control" id="edit_type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="cow">Cow</option>
                                    <option value="buffalo">Buffalo</option>
                                    <option value="goat">Goat</option>
                                    <option value="sheep">Sheep</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_breed">Breed</label>
                                <select class="form-control" id="edit_breed" name="breed" required>
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
                                <label for="edit_farm_id">Farm</label>
                                <select class="form-control" id="edit_farm_id" name="farm_id" required>
                                    <option value="">Select Farm</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_birth_date">Birth Date</label>
                                <input type="date" class="form-control" id="edit_birth_date" name="birth_date" required max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_weight">Weight (kg)</label>
                                <input type="number" step="0.1" class="form-control" id="edit_weight" name="weight">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_gender">Gender</label>
                                <select class="form-control" id="edit_gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_notes">Notes</label>
                                <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Livestock</button>
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
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
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
                <form id="deleteLivestockForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --primary-color: #4e73df;
        --primary-dark: #3c5aa6;
        --success-color: #1cc88a;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;
        --info-color: #36b9cc;
        --light-color: #f8f9fc;
        --dark-color: #5a5c69;
        --border-color: #e3e6f0;
        --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: var(--shadow);
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

    .search-container {
        position: relative;
    }

    .search-container input {
        padding-left: 2.5rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .search-container input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        outline: none;
    }

    .search-container::before {
        content: '\f002';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--dark-color);
        z-index: 1;
    }

    .farmer-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
    }

    .farmer-link:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    .livestock-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
    }

    .livestock-link:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--dark-color);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .gap-2 {
        gap: 0.5rem !important;
    }

    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    let farmersTable;
    let livestockTable;
    let selectedFarmerId = null;
    let selectedFarmerName = '';

    $(document).ready(function() {
        console.log('Livestock management page loaded');
        
        // Initialize DataTables
        initializeDataTables();
        
        // Load farmers data
        loadFarmers();

        // Custom search for farmers
        $('#farmerSearch').on('keyup', function() {
            farmersTable.search(this.value).draw();
        });

        // Auto-refresh data every 30 seconds
        setInterval(function() {
            if (selectedFarmerId) {
                loadFarmerLivestock(selectedFarmerId);
            } else {
                loadFarmers();
            }
        }, 30000);
    });

    function initializeDataTables() {
        // Initialize Farmers DataTable
        farmersTable = $('#farmersTable').DataTable({
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
                    title: 'Farmers_Report',
                    className: 'd-none'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Farmers_Report',
                    orientation: 'landscape',
                    pageSize: 'Letter',
                    className: 'd-none'
                },
                {
                    extend: 'print',
                    title: 'Farmers Report',
                    className: 'd-none'
                }
            ],
            language: {
                search: "",
                paginate: {
                    previous: '<i class="fas fa-chevron-left"></i>',
                    next: '<i class="fas fa-chevron-right"></i>'
                }
            }
        });

        // Initialize Livestock DataTable
        livestockTable = $('#livestockTable').DataTable({
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
                paginate: {
                    previous: '<i class="fas fa-chevron-left"></i>',
                    next: '<i class="fas fa-chevron-right"></i>'
                }
            }
        });

        // Hide default DataTables elements
        $('.dataTables_filter').hide();
        $('.dt-buttons').hide();
    }

    function loadFarmers() {
        const tableBody = $('#farmersTableBody');
        tableBody.html('<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading farmers...</td></tr>');
        
        $.ajax({
            url: '{{ route("admin.livestock.farmers") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    farmersTable.clear();
                    
                    if (response.data.length === 0) {
                        farmersTable.row.add([
                            '<td colspan="8" class="text-center">No farmers found</td>'
                        ]).draw(false);
                        return;
                    }
                    
                    response.data.forEach(farmer => {
                        const displayName = farmer.first_name && farmer.last_name 
                            ? `${farmer.first_name} ${farmer.last_name}` 
                            : farmer.name || 'N/A';
                        
                        const rowData = [
                            farmer.id,
                            `<a href="#" class="farmer-link" onclick="selectFarmer('${farmer.id}', '${displayName}')">${displayName}</a>`,
                            farmer.email,
                            farmer.contact_number || 'N/A',
                            farmer.barangay || 'N/A',
                            farmer.livestock_count || 0,
                            `<span class="badge badge-${getStatusBadgeClass(farmer.status)}">${farmer.status}</span>`,
                            `<div class="btn-group" role="group">
                                <button class="btn btn-primary btn-sm" onclick="selectFarmer('${farmer.id}', '${displayName}')" title="View Livestock">
                                    <i class="fas fa-cow"></i> View Livestock
                                </button>
                                <button class="btn btn-info btn-sm" onclick="viewFarmerDetails('${farmer.id}')" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>`
                        ];
                        
                        farmersTable.row.add(rowData).draw(false);
                    });
                } else {
                    showNotification('Failed to load farmers: ' + (response.message || 'Unknown error'), 'danger');
                }
            },
            error: function(xhr) {
                console.error('Error loading farmers:', xhr);
                showNotification('Error loading farmers. Please try again.', 'danger');
                tableBody.html('<tr><td colspan="8" class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Error loading farmers</td></tr>');
            }
        });
    }

    function selectFarmer(farmerId, farmerName) {
        selectedFarmerId = farmerId;
        selectedFarmerName = farmerName;
        
        // Update the UI
        $('#selectedFarmerName').text(farmerName);
        $('#selectedFarmerId').val(farmerId);
        
        // Hide farmer selection, show livestock section
        $('#farmerSelectionCard').hide();
        $('#livestockCard').show();
        
        // Load farmer's livestock
        loadFarmerLivestock(farmerId);
        
        // Load farmer's farms for the add livestock modal
        loadFarmerFarms(farmerId);
    }

    function backToFarmers() {
        selectedFarmerId = null;
        selectedFarmerName = '';
        
        // Show farmer selection, hide livestock section
        $('#farmerSelectionCard').show();
        $('#livestockCard').hide();
        
        // Clear livestock table
        livestockTable.clear().draw();
    }

    function loadFarmerLivestock(farmerId) {
        const tableBody = $('#livestockTableBody');
        tableBody.html('<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading livestock...</td></tr>');
        
        $.ajax({
            url: `{{ route("admin.livestock.farmer-livestock", ":id") }}`.replace(':id', farmerId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    livestockTable.clear();
                    
                    if (response.data.livestock.length === 0) {
                        livestockTable.row.add([
                            '<td colspan="7" class="text-center">No livestock found for this farmer</td>'
                        ]).draw(false);
                    } else {
                        response.data.livestock.forEach(animal => {
                            const rowData = [
                                `<a href="#" class="livestock-link" onclick="openDetailsModal('${animal.id}')">${animal.tag_number}</a>`,
                                animal.type,
                                animal.breed,
                                animal.gender,
                                animal.farm ? animal.farm.name : 'N/A',
                                `<select class="form-control" onchange="updateStatus(this, '${animal.id}')">
                                    <option value="active" ${animal.status === 'active' ? 'selected' : ''}>Active</option>
                                    <option value="inactive" ${animal.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                                </select>`,
                                `<div class="btn-group" role="group">
                                    <button class="btn btn-info btn-sm" onclick="editLivestock('${animal.id}')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteLivestock('${animal.id}')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>`
                            ];
                            
                            livestockTable.row.add(rowData).draw(false);
                        });
                    }
                    
                    // Update stats
                    updateFarmerStats(response.data.stats);
                } else {
                    showNotification('Failed to load livestock: ' + (response.message || 'Unknown error'), 'danger');
                }
            },
            error: function(xhr) {
                console.error('Error loading livestock:', xhr);
                showNotification('Error loading livestock. Please try again.', 'danger');
                tableBody.html('<tr><td colspan="7" class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Error loading livestock</td></tr>');
            }
        });
    }

    function loadFarmerFarms(farmerId) {
        $.ajax({
            url: `{{ route("admin.livestock.farmer-farms", ":id") }}`.replace(':id', farmerId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const farmSelect = $('#farm_id');
                    const editFarmSelect = $('#edit_farm_id');
                    
                    // Clear existing options
                    farmSelect.empty().append('<option value="">Select Farm</option>');
                    editFarmSelect.empty().append('<option value="">Select Farm</option>');
                    
                    // Add new options
                    response.data.forEach(farm => {
                        farmSelect.append(`<option value="${farm.id}">${farm.name}</option>`);
                        editFarmSelect.append(`<option value="${farm.id}">${farm.name}</option>`);
                    });
                }
            },
            error: function(xhr) {
                console.error('Error loading farms:', xhr);
            }
        });
    }

    function updateFarmerStats(stats) {
        document.getElementById('farmerTotalLivestock').textContent = stats.total || 0;
        document.getElementById('farmerActiveLivestock').textContent = stats.active || 0;
        document.getElementById('farmerInactiveLivestock').textContent = stats.inactive || 0;
        document.getElementById('farmerTotalFarms').textContent = stats.farms || 0;
    }

    function refreshFarmerData() {
        const refreshBtn = $('button[onclick="refreshFarmerData()"]');
        const originalIcon = refreshBtn.html();
        refreshBtn.html('<i class="fas fa-spinner fa-spin"></i>');
        refreshBtn.prop('disabled', true);
        
        if (selectedFarmerId) {
            loadFarmerLivestock(selectedFarmerId);
        } else {
            loadFarmers();
        }
        
        setTimeout(() => {
            refreshBtn.html(originalIcon);
            refreshBtn.prop('disabled', false);
            showNotification('Data refreshed successfully', 'success');
        }, 1000);
    }

    function getStatusBadgeClass(status) {
        switch(status) {
            case 'approved': return 'success';
            case 'pending': return 'warning';
            case 'suspended': return 'danger';
            case 'rejected': return 'danger';
            default: return 'secondary';
        }
    }

    function viewFarmerDetails(farmerId) {
        // Implementation for viewing farmer details
        showNotification('Farmer details feature coming soon', 'info');
    }

    function updateStatus(select, livestockId) {
        console.log('Updating status for livestock:', livestockId, 'to:', select.value);
        const status = select.value;
        const url = `{{ route('admin.livestock.update-status', ':id') }}`.replace(':id', livestockId);
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Status updated successfully', 'success');
                // Refresh livestock data
                if (selectedFarmerId) {
                    loadFarmerLivestock(selectedFarmerId);
                }
            } else {
                showNotification('Failed to update status', 'error');
                // Revert the select value
                select.value = select.getAttribute('data-original-value');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
            // Revert the select value
            select.value = select.getAttribute('data-original-value');
        });
    }

    function editLivestock(livestockId) {
        console.log('Editing livestock:', livestockId);
        // Fetch livestock data and populate the edit modal
        fetch(`{{ route('admin.livestock.show', ':id') }}`.replace(':id', livestockId))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const livestock = data.livestock;
                console.log('Livestock data:', livestock);
                
                // Populate the edit form
                document.getElementById('edit_livestock_id').value = livestock.tag_number;
                document.getElementById('edit_type').value = livestock.type;
                document.getElementById('edit_breed').value = livestock.breed;
                document.getElementById('edit_farm_id').value = livestock.farm_id;
                document.getElementById('edit_birth_date').value = livestock.birth_date;
                document.getElementById('edit_gender').value = livestock.gender;
                document.getElementById('edit_weight').value = livestock.weight;
                document.getElementById('edit_notes').value = livestock.health_status;
                
                // Update the form action
                document.getElementById('editLivestockForm').action = `{{ route('admin.livestock.update', ':id') }}`.replace(':id', livestockId);
                
                // Show the modal
                $('#editLivestockModal').modal('show');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to load livestock data', 'error');
        });
    }

    function deleteLivestock(livestockId) {
        console.log('Deleting livestock:', livestockId);
        // Update the delete form action
        document.getElementById('deleteLivestockForm').action = `{{ route('admin.livestock.destroy', ':id') }}`.replace(':id', livestockId);
        
        // Show the confirmation modal
        $('#confirmDeleteModal').modal('show');
    }

    function openDetailsModal(livestockId) {
        console.log('Opening details modal for livestock:', livestockId);
        // Fetch livestock details and show in a modal
        fetch(`{{ route('admin.livestock.show', ':id') }}`.replace(':id', livestockId))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const livestock = data.livestock;
                console.log('Livestock details:', livestock);
                
                // Create and show a details modal
                const modalHtml = `
                    <div class="modal fade" id="livestockDetailsModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-cow"></i> Livestock Details
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Tag Number:</strong> ${livestock.tag_number}</p>
                                            <p><strong>Type:</strong> ${livestock.type}</p>
                                            <p><strong>Breed:</strong> ${livestock.breed}</p>
                                            <p><strong>Gender:</strong> ${livestock.gender || 'N/A'}</p>
                                            <p><strong>Farm:</strong> ${livestock.farm ? livestock.farm.name : 'N/A'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Birth Date:</strong> ${livestock.birth_date || 'N/A'}</p>
                                            <p><strong>Weight:</strong> ${livestock.weight ? livestock.weight + ' kg' : 'N/A'}</p>
                                            <p><strong>Status:</strong> <span class="badge badge-${livestock.status === 'active' ? 'success' : 'secondary'}">${livestock.status}</span></p>
                                            <p><strong>Health Status:</strong> ${livestock.health_status || 'N/A'}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="editLivestock('${livestock.id}')">Edit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Remove existing modal if any
                const existingModal = document.getElementById('livestockDetailsModal');
                if (existingModal) {
                    existingModal.remove();
                }
                
                // Add new modal to body
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                
                // Show the modal
                $('#livestockDetailsModal').modal('show');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to load livestock details', 'error');
        });
    }

    // Handle form submissions
    $('#addLivestockForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('farmer_id', selectedFarmerId);
        
        $.ajax({
            url: '{{ route("admin.livestock.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#addLivestockModal').modal('hide');
                $('#addLivestockForm')[0].reset();
                loadFarmerLivestock(selectedFarmerId);
                showNotification('Livestock added successfully', 'success');
            },
            error: function(xhr) {
                showNotification('Failed to add livestock', 'error');
            }
        });
    });

    $('#editLivestockForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: this.action,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#editLivestockModal').modal('hide');
                loadFarmerLivestock(selectedFarmerId);
                showNotification('Livestock updated successfully', 'success');
            },
            error: function(xhr) {
                showNotification('Failed to update livestock', 'error');
            }
        });
    });

    $('#deleteLivestockForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: this.action,
            method: 'POST',
            success: function(response) {
                $('#confirmDeleteModal').modal('hide');
                loadFarmerLivestock(selectedFarmerId);
                showNotification('Livestock deleted successfully', 'success');
            },
            error: function(xhr) {
                showNotification('Failed to delete livestock', 'error');
            }
        });
    });

    function showNotification(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 'alert-info';
        
        const notification = document.createElement('div');
        notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
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
</script>
@endpush
