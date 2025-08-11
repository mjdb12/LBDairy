@extends('layouts.app')

@section('title', 'LBDAIRY: Farmer - My Farms')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-university"></i>
            My Farms
        </h1>
        <p>Manage your farm properties, livestock, and production records</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container fade-in">
        <div class="stat-card info">
            <div class="card-body">
                <div class="stat-info">
                    <h5>{{ $totalFarms }}</h5>
                    <div class="stat-label">Total Farms</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-university"></i>
                </div>
            </div>
            <div class="card-footer">
                <a href="#" class="text-info">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="stat-card success">
            <div class="card-body">
                <div class="stat-info">
                    <h5>{{ $activeFarms }}</h5>
                    <div class="stat-label">Active Farms</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="card-footer">
                <a href="#" class="text-success">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="stat-card warning">
            <div class="card-body">
                <div class="stat-info">
                    <h5>{{ $totalLivestock }}</h5>
                    <div class="stat-label">Total Livestock</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-cow"></i>
                </div>
            </div>
            <div class="card-footer">
                <a href="#" class="text-warning">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="stat-card danger">
            <div class="card-body">
                <div class="stat-info">
                    <h5>{{ $totalProduction }}</h5>
                    <div class="stat-label">Total Production</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="card-footer">
                <a href="#" class="text-danger">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Farms Table Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header">
            <h6>
                <i class="fas fa-list-alt"></i>
                Farm Directory
            </h6>
            <div class="table-controls">
                <button class="btn btn-success btn-sm" onclick="addFarmDetails()">
                    <i class="fas fa-plus mr-1"></i> Add New Farm
                </button>
                <div class="search-container">
                    <input type="text" class="form-control custom-search" id="customSearch" placeholder="Search farms...">
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
                            <a class="dropdown-item" href="#" onclick="exportPDF()">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPNG()">
                                <i class="fas fa-image"></i> PNG
                            </a>
                        </div>
                    </div>
                    <button class="btn btn-secondary btn-sm" onclick="printFarms()">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="farmsTable">
                    <thead>
                        <tr>
                            <th>Farm ID</th>
                            <th>Farm Name</th>
                            <th>Location</th>
                            <th>Size (hectares)</th>
                            <th>Status</th>
                            <th>Livestock Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($farms as $farm)
                        <tr>
                            <td>
                                <a href="#" class="farm-id-link" onclick="openDetailsModal('{{ $farm->id }}')">
                                    {{ $farm->farm_id ?? 'F' . str_pad($farm->id, 3, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <td>{{ $farm->name ?? 'Farm ' . $farm->id }}</td>
                            <td>{{ $farm->location ?? 'N/A' }}</td>
                            <td>{{ $farm->size ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $farm->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($farm->status) }}
                                </span>
                            </td>
                            <td>{{ $farm->livestock_count ?? 0 }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-info btn-sm" onclick="openDetailsModal('{{ $farm->id }}')" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-primary btn-sm" onclick="editFarm('{{ $farm->id }}')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $farm->id }}')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-university"></i>
                                    <h5>No farms available</h5>
                                    <p>You haven't registered any farms yet. Click "Add New Farm" to get started.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Farm Details Modal -->
<div class="modal fade" id="farmDetailsModal" tabindex="-1" role="dialog" aria-labelledby="farmDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="farmDetailsModalLabel">
                    <i class="fas fa-university"></i>
                    Farm Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="farmDetailsContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2">Loading farm details...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Farm Modal -->
<div class="modal fade" id="farmModal" tabindex="-1" role="dialog" aria-labelledby="farmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="farmModalLabel">
                    <i class="fas fa-plus-circle"></i>
                    Add New Farm
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="farmForm">
                    @csrf
                    <input type="hidden" id="farm_id" name="farm_id">
                    <div class="form-group">
                        <label for="farm_name">Farm Name</label>
                        <input type="text" class="form-control" id="farm_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="farm_location">Location</label>
                        <input type="text" class="form-control" id="farm_location" name="location" required>
                    </div>
                    <div class="form-group">
                        <label for="farm_size">Size (hectares)</label>
                        <input type="number" class="form-control" id="farm_size" name="size" min="0" step="0.1" required>
                    </div>
                    <div class="form-group">
                        <label for="farm_description">Description</label>
                        <textarea class="form-control" id="farm_description" name="description" rows="3" placeholder="Describe your farm..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" form="farmForm" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Farm
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
                <p>Are you sure you want to delete this farm? This action cannot be undone and will remove all associated data.</p>
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

    /* Enhanced Stats Cards */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-color);
    }

    .stat-card.info::before { background: var(--info-color); }
    .stat-card.success::before { background: var(--success-color); }
    .stat-card.warning::before { background: var(--warning-color); }
    .stat-card.danger::before { background: var(--danger-color); }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .stat-card .card-body {
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stat-card .stat-info h5 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--dark-color);
    }

    .stat-card .stat-info .stat-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--dark-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0;
    }

    .stat-card .stat-icon {
        font-size: 2.5rem;
        opacity: 0.7;
    }

    .stat-card.info .stat-icon { color: var(--info-color); }
    .stat-card.success .stat-icon { color: var(--success-color); }
    .stat-card.warning .stat-icon { color: var(--warning-color); }
    .stat-card.danger .stat-icon { color: var(--danger-color); }

    .stat-card .card-footer {
        background: rgba(0, 0, 0, 0.03);
        border-top: 1px solid var(--border-color);
        padding: 0.75rem 0;
        margin-top: 1rem;
        border-radius: 0;
    }

    .stat-card .card-footer a {
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s ease;
    }

    .stat-card .card-footer a:hover {
        opacity: 0.8;
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

    .table tbody tr {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .table tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
        transform: scale(1.001);
    }

    .table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border-top: 1px solid #f0f0f0;
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

    .btn-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #17a673 100%);
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger-color) 0%, #c73e1d 100%);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
    }

    .btn-info {
        background: linear-gradient(135deg, var(--info-color) 0%, #2c9faf 100%);
    }

    .btn-light {
        background: white;
        border: 1px solid var(--border-color);
        color: var(--dark-color);
    }

    .btn-light:hover {
        background: #f8f9fc;
        border-color: var(--primary-color);
    }

    /* Enhanced Search and Export Controls */
    .table-controls {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .search-container {
        position: relative;
        min-width: 250px;
    }

    .search-container input {
        border-radius: 25px;
        border: 2px solid transparent;
        background: rgba(255, 255, 255, 0.9);
        padding: 0.6rem 1rem 0.6rem 2.5rem;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .search-container input:focus {
        border-color: var(--primary-color);
        background: white;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .search-container::before {
        content: '\f002';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        z-index: 2;
    }

    .export-controls {
        display: flex;
        gap: 0.5rem;
    }

    .dropdown-toggle::after {
        margin-left: 0.5rem;
    }

    /* Enhanced Modal Styling */
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
        padding: 1.5rem;
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.2rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border-top: 1px solid var(--border-color);
        padding: 1.5rem;
    }

    /* Form Controls */
    .form-control {
        border-radius: 8px;
        border: 1px solid var(--border-color);
        padding: 0.6rem 1rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .form-group label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    /* Page Header Enhancement */
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

    /* Badge Styling */
    .badge {
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Farm ID Link Styling */
    .farm-id-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
    }

    .farm-id-link:hover {
        color: var(--primary-dark);
        text-decoration: underline;
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
            justify-content: center;
        }

        .card-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .stats-container {
            grid-template-columns: 1fr;
        }
    }

    /* Animation Classes */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper .dataTables_info {
        color: var(--dark-color);
        font-size: 0.85rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 6px;
        margin: 0 2px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
    }

    /* Empty State */
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
</style>
@endpush

@push('scripts')
<script>
    let dataTable;
    let farmToDelete = null;

    $(document).ready(function () {
        // Initialize DataTable
        initializeDataTable();
        
        // Custom search functionality
        $('#customSearch').on('keyup', function() {
            dataTable.search(this.value).draw();
        });
    });

    function initializeDataTable() {
        dataTable = $('#farmsTable').DataTable({
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
                    title: 'My_Farms_Report',
                    className: 'd-none'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'My_Farms_Report',
                    orientation: 'landscape',
                    pageSize: 'Letter',
                    className: 'd-none'
                },
                {
                    extend: 'print',
                    title: 'My Farms Report',
                    className: 'd-none'
                }
            ],
            language: {
                search: "",
                emptyTable: '<div class="empty-state"><i class="fas fa-university"></i><h5>No farms available</h5><p>You haven\'t registered any farms yet.</p></div>'
            }
        });

        // Hide default DataTables elements
        $('.dataTables_filter').hide();
        $('.dt-buttons').hide();
    }

    function openDetailsModal(farmId) {
        $('#farmDetailsModal').modal('show');
        
        // Fetch farm details via AJAX
        fetch(`/farmer/farms/${farmId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('farmDetailsContent').innerHTML = data.html;
                } else {
                    document.getElementById('farmDetailsContent').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            Error loading farm details: ${data.message}
                        </div>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('farmDetailsContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        Error loading farm details. Please try again.
                    </div>
                `;
            });
    }

    function addFarmDetails() {
        document.getElementById('farmForm').reset();
        document.getElementById('farm_id').value = '';
        document.getElementById('farmModalLabel').innerHTML = '<i class="fas fa-plus-circle"></i> Add New Farm';
        $('#farmModal').modal('show');
    }

    function editFarm(farmId) {
        // Fetch farm data and populate form
        fetch(`/farmer/farms/${farmId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('farm_id').value = data.farm.id;
                    document.getElementById('farm_name').value = data.farm.name || '';
                    document.getElementById('farm_location').value = data.farm.location || '';
                    document.getElementById('farm_size').value = data.farm.size || '';
                    document.getElementById('farm_description').value = data.farm.description || '';
                    document.getElementById('farmModalLabel').innerHTML = '<i class="fas fa-edit"></i> Edit Farm';
                    $('#farmModal').modal('show');
                } else {
                    showNotification('Error loading farm data: ' + data.message, 'danger');
                }
            })
            .catch(error => {
                showNotification('Error loading farm data. Please try again.', 'danger');
            });
    }

    // Handle form submit
    document.getElementById('farmForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const farmId = document.getElementById('farm_id').value;
        const url = farmId ? `/farmer/farms/${farmId}` : '/farmer/farms';
        const method = farmId ? 'PUT' : 'POST';
        
        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#farmModal').modal('hide');
                showNotification('Farm saved successfully!', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showNotification('Error saving farm: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            showNotification('Error saving farm. Please try again.', 'danger');
        });
    });

    function confirmDelete(farmId) {
        farmToDelete = farmId;
        $('#confirmDeleteModal').modal('show');
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (farmToDelete) {
            fetch(`/farmer/farms/${farmToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#confirmDeleteModal').modal('hide');
                    showNotification('Farm deleted successfully!', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('Error deleting farm: ' + data.message, 'danger');
                }
            })
            .catch(error => {
                showNotification('Error deleting farm. Please try again.', 'danger');
            });
            farmToDelete = null;
        }
    });

    // Export Functions
    function exportCSV() {
        dataTable.button('.buttons-csv').trigger();
    }

    function exportPDF() {
        dataTable.button('.buttons-pdf').trigger();
    }

    function exportPNG() {
        const table = document.querySelector('table.table-bordered');
        html2canvas(table).then(function(canvas) {
            const link = document.createElement('a');
            link.download = 'my_farms.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    }

    function printFarms() {
        dataTable.button('.buttons-print').trigger();
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
