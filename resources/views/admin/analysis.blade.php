@extends('layouts.app')

@section('title', 'LBDAIRY: Admin - Productivity Analysis')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-chart-line"></i>
            Productivity Analysis
        </h1>
        <p>Monitor farmer performance, track productivity metrics, and analyze system-wide data</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container fade-in">
        <div class="stat-card info">
            <div class="card-body">
                <div class="stat-info">
                    <h5>{{ $totalFarmers }}</h5>
                    <div class="stat-label">Total Farmers</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
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
                    <h5>{{ $activeFarmers }}</h5>
                    <div class="stat-label">Active Farmers</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
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
                    <h5>{{ $avgProductivity }}</h5>
                    <div class="stat-label">Avg Productivity</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-chart-bar"></i>
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
                    <h5>{{ $totalRevenue }}</h5>
                    <div class="stat-label">Total Revenue</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
            <div class="card-footer">
                <a href="#" class="text-danger">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Analysis Table Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header">
            <h6>
                <i class="fas fa-list-alt"></i>
                Farmer Performance Analysis
            </h6>
            <div class="table-controls">
                <div class="search-container">
                    <input type="text" class="form-control custom-search" id="customSearch" placeholder="Search farmers...">
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
                    <button class="btn btn-secondary btn-sm" onclick="printAnalysis()">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="analysisTable">
                    <thead>
                        <tr>
                            <th>Farmer ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Productivity Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($farmers as $farmer)
                        <tr>
                            <td>{{ $farmer->id }}</td>
                            <td>{{ $farmer->name }}</td>
                            <td>{{ $farmer->email }}</td>
                            <td>
                                <span class="badge badge-{{ $farmer->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($farmer->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-{{ $farmer->productivity_score >= 80 ? 'success' : ($farmer->productivity_score >= 60 ? 'warning' : 'danger') }}" 
                                         role="progressbar" 
                                         data-width="{{ $farmer->productivity_score }}"
                                         aria-valuenow="{{ $farmer->productivity_score }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ number_format($farmer->productivity_score, 1) }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-action-view" onclick="viewFarmerData('{{ $farmer->id }}')" title="View Data">
                                        <i class="fas fa-eye"></i>
                                        <span>View</span>
                                    </button>
                                    <button class="btn-action btn-action-details" onclick="viewFarmerDetails('{{ $farmer->id }}')" title="Details">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Details</span>
                                    </button>
                                    <button class="btn-action btn-action-toggle" 
                                            data-farmer-id="{{ $farmer->id }}" 
                                            data-current-status="{{ $farmer->status }}"
                                            onclick="toggleFarmerStatus(this)"
                                            title="{{ $farmer->status == 'active' ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas fa-{{ $farmer->status == 'active' ? 'pause' : 'play' }}"></i>
                                        <span>{{ $farmer->status == 'active' ? 'Deactivate' : 'Activate' }}</span>
                                    </button>
                                    <button class="btn-action btn-action-delete" onclick="confirmDeleteFarmer('{{ $farmer->id }}')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <h5>No farmers available</h5>
                                    <p>There are no farmers registered at this time.</p>
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

<!-- Farmer Data Modal -->
<div class="modal fade" id="farmerDataModal" tabindex="-1" role="dialog" aria-labelledby="farmerDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="farmerDataModalLabel">
                    <i class="fas fa-chart-bar"></i>
                    Farmer Performance Data
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="farmerDataContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2">Loading farmer data...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Farmer Details Modal -->
<div class="modal fade" id="farmerDetailsModal" tabindex="-1" role="dialog" aria-labelledby="farmerDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="farmerDetailsModalLabel">
                    <i class="fas fa-user"></i>
                    Farmer Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="farmerDetailsContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2">Loading farmer details...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteFarmerModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteFarmerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteFarmerLabel">
                    <i class="fas fa-exclamation-triangle"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this farmer? This action cannot be undone and will remove all associated data.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteFarmerBtn" class="btn btn-danger">
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
        --primary-color: #18375d;
        --primary-dark: #122a47;
        --success-color: #1cc88a;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;
        --info-color: #36b9cc;
        --light-color: #fff;
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

    /* Page Header Enhancement */
    .page-header {
        background: var(--primary-color);
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

    /* Progress Bar Styling */
    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }

    .progress-bar {
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 20px;
    }

    /* Badge Styling */
    .badge {
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
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
    let farmerToDelete = null;

    $(document).ready(function () {
        // Set progress bar widths from data attributes
        $('.progress-bar[data-width]').each(function() {
            const width = $(this).data('width');
            $(this).css('width', width + '%');
        });
        
        // Initialize DataTable
        initializeDataTable();
        
        // Custom search functionality
        $('#customSearch').on('keyup', function() {
            dataTable.search(this.value).draw();
        });
    });

    function initializeDataTable() {
        dataTable = $('#analysisTable').DataTable({
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
                    title: 'Farmer_Analysis_Report',
                    className: 'd-none'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Farmer_Analysis_Report',
                    orientation: 'landscape',
                    pageSize: 'Letter',
                    className: 'd-none'
                },
                {
                    extend: 'print',
                    title: 'Farmer Analysis Report',
                    className: 'd-none'
                }
            ],
            language: {
                search: "",
                emptyTable: '<div class="empty-state"><i class="fas fa-users"></i><h5>No farmers available</h5><p>There are no farmers registered at this time.</p></div>'
            }
        });

        // Hide default DataTables elements
        $('.dataTables_filter').hide();
        $('.dt-buttons').hide();
    }

    function viewFarmerData(farmerId) {
        $('#farmerDataModal').modal('show');
        
        // Fetch farmer data via AJAX
        fetch(`{{ route('admin.analysis.farmer-data', ':id') }}`.replace(':id', farmerId))
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('farmerDataContent').innerHTML = data.html;
                } else {
                    document.getElementById('farmerDataContent').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            Error loading farmer data: ${data.message}
                        </div>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('farmerDataContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        Error loading farmer data. Please try again.
                    </div>
                `;
            });
    }

    function viewFarmerDetails(farmerId) {
        $('#farmerDetailsModal').modal('show');
        
        // Fetch farmer details via AJAX
        fetch(`{{ route('admin.analysis.farmer-details', ':id') }}`.replace(':id', farmerId))
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('farmerDetailsContent').innerHTML = data.html;
                } else {
                    document.getElementById('farmerDetailsContent').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            Error loading farmer details: ${data.message}
                        </div>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('farmerDetailsContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        Error loading farmer details. Please try again.
                    </div>
                `;
            });
    }

    function toggleFarmerStatus(button) {
        const farmerId = button.getAttribute('data-farmer-id');
        const currentStatus = button.getAttribute('data-current-status');
        const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
        updateFarmerStatus(farmerId, newStatus);
    }

    function updateFarmerStatus(farmerId, newStatus) {
        fetch(`{{ route('admin.analysis.update-status', ':id') }}`.replace(':id', farmerId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(`Farmer status updated to ${newStatus} successfully!`, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showNotification('Error updating farmer status: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            showNotification('Error updating farmer status. Please try again.', 'danger');
        });
    }

    function confirmDeleteFarmer(farmerId) {
        farmerToDelete = farmerId;
        $('#confirmDeleteFarmerModal').modal('show');
    }

    document.getElementById('confirmDeleteFarmerBtn').addEventListener('click', function() {
        if (farmerToDelete) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('admin.analysis.delete-farmer', ':id') }}`.replace(':id', farmerToDelete);
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = $('meta[name="csrf-token"]').attr('content');
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
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
            link.download = 'farmer_analysis.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    }

    function printAnalysis() {
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
