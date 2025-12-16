@extends('layouts.app')

@section('title', 'LBDAIRY: Admin Sales')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-chart-line"></i>
            Sales Management
        </h1>
        <p>Track livestock sales, manage transactions, and monitor revenue performance</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container fade-in">
        <div class="stat-card info">
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
                <a href="#" class="text-info">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="stat-card success">
            <div class="card-body">
                <div class="stat-info">
                    <h5>{{ $totalSoldQuantity }}</h5>
                    <div class="stat-label">Total Sold Quantity</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-truck-loading"></i>
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
                    <h5>{{ $stockInShed }}</h5>
                    <div class="stat-label">Stock in Shed</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-warehouse"></i>
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
                    <h5>₱{{ number_format($totalSoldAmount) }}</h5>
                    <div class="stat-label">Total Sold Amount</div>
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

    <!-- Sales Table Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header">
            <h6>
                <i class="fas fa-list-alt"></i>
                Sales Records
            </h6>
            <div class="table-controls">
                <button class="btn btn-success btn-sm" onclick="addSaleDetails()">
                    <i class="fas fa-plus mr-1"></i> Add New Sale
                </button>
                <div class="search-container">
                    <input type="text" class="form-control custom-search" id="customSearch" placeholder="Search sales...">
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
                    <button class="btn btn-secondary btn-sm" onclick="printSales()">
                        <i class="fas fa-print"></i>
                    </button>
                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#historyModal">
                        <i class="fas fa-history"></i> History
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
                <table class="table table-bordered" id="salesTable">
                    <thead>
                        <tr>
                            <th>Sale ID</th>
                            <th>Date Sold</th>
                            <th>Type</th>
                            <th>Amount Sold (₱)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr>
                            <td>{{ $sale->id }}</td>
                            <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                            <td>{{ $sale->type ?? 'Livestock' }}</td>
                            <td>{{ number_format($sale->amount) }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-action-delete" onclick="confirmDelete('{{ $sale->id }}')" title="Delete">
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
                            <td class="text-center text-muted">No sales records</td>
                            <td class="text-center text-muted">N/A</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
                    <p>Are you sure you want to delete this sale record? This action cannot be undone.</p>
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

    <!-- ADD SALE DETAILS MODAL -->
    <div class="modal fade" id="addSaleDetailsModal" tabindex="-1" role="dialog" aria-labelledby="addSaleDetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle"></i>
                        Add New Sale
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addSaleDetailsForm">
                        @csrf
                        <div class="form-group">
                            <label for="add_dateSold">Date Sold</label>
                            <input type="date" class="form-control" id="add_dateSold" name="dateSold" required max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                            <label for="add_type">Type</label>
                            <select class="form-control" id="add_type" name="type" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="Goat">Goat</option>
                                <option value="Cow">Cow</option>
                                <option value="Carabao">Carabao</option>
                                <option value="Pig">Pig</option>
                                <option value="Chicken">Chicken</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="add_amountSold">Amount Sold (₱)</label>
                            <input type="number" class="form-control" id="add_amountSold" name="amountSold" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="add_quantity">Quantity</label>
                            <input type="number" class="form-control" id="add_quantity" name="quantity" min="1" value="1" required>
                        </div>
                        <div class="form-group">
                            <label for="add_notes">Notes (Optional)</label>
                            <textarea class="form-control" id="add_notes" name="notes" rows="3" placeholder="Additional notes about the sale..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" form="addSaleDetailsForm" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Sale
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- History Modal -->
    <div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">
                        <i class="fas fa-history"></i>
                        Sales History
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="sortHistory" class="font-weight-bold">Sort By:</label>
                            <select id="sortHistory" class="form-control form-control-sm">
                                <option value="newest">Newest First</option>
                                <option value="oldest">Oldest First</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="filterHistory" class="font-weight-bold">Filter By:</label>
                            <select id="filterHistory" class="form-control form-control-sm">
                                <option value="all">All</option>
                                <option value="goat">Goat</option>
                                <option value="cow">Cow</option>
                                <option value="carabao">Carabao</option>
                                <option value="pig">Pig</option>
                                <option value="chicken">Chicken</option>
                            </select>
                        </div>
                    </div>
                    <div id="historyContent" class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Sale ID</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Amount Sold (₱)</th>
                                </tr>
                            </thead>
                            <tbody id="historyTableBody">
                                @foreach($salesHistory as $sale)
                                <tr>
                                    <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $sale->id }}</td>
                                    <td>{{ $sale->type ?? 'Livestock' }}</td>
                                    <td>{{ $sale->quantity ?? 1 }}</td>
                                    <td>{{ number_format($sale->amount) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="exportHistory()">
                        <i class="fas fa-download"></i> Export History
                    </button>
                </div>
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
    let dataTable;
    let saleToDelete = null;

    $(document).ready(function () {
        // Initialize DataTable
        initializeDataTable();
        
        // Custom search functionality
        $('#customSearch').on('keyup', function() {
            dataTable.search(this.value).draw();
        });

        // Set default date to today
        document.getElementById('add_dateSold').value = new Date().toISOString().split('T')[0];
    });

    function initializeDataTable() {
        dataTable = $('#salesTable').DataTable({
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
                    title: 'Sales_Report',
                    className: 'd-none'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Sales_Report',
                    orientation: 'landscape',
                    pageSize: 'Letter',
                    className: 'd-none'
                },
                {
                    extend: 'print',
                    title: 'Sales Report',
                    className: 'd-none'
                }
            ],
            language: {
                search: "",
                emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No sales records</h5><p>There are no sales records to display at this time.</p></div>'
            }
        });

        // Hide default DataTables elements
        $('.dataTables_filter').hide();
        $('.dt-buttons').hide();
    }

    // Show modal for adding new sale
    function addSaleDetails() {
        document.getElementById("addSaleDetailsForm").reset();
        document.getElementById('add_dateSold').value = new Date().toISOString().split('T')[0];
        $('#addSaleDetailsModal').modal('show');
    }

    // Handle form submit: add new sale via AJAX
    document.getElementById('addSaleDetailsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("admin.sales.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addSaleDetailsModal').modal('hide');
                    showNotification('Sale record added successfully!', 'success');
                    // Reload page to show new data
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('Error adding sale record: ' + response.message, 'danger');
                }
            },
            error: function(xhr) {
                showNotification('Error adding sale record. Please try again.', 'danger');
            }
        });
    });

    function confirmDelete(saleId) {
        saleToDelete = saleId;
        $('#confirmDeleteModal').modal('show');
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (saleToDelete) {
            $.ajax({
                url: '{{ route("admin.sales.destroy", "") }}/' + saleToDelete,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#confirmDeleteModal').modal('hide');
                        showNotification('Sale record deleted successfully!', 'success');
                        // Reload page to reflect changes
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        showNotification('Error deleting sale record: ' + response.message, 'danger');
                    }
                },
                error: function(xhr) {
                    showNotification('Error deleting sale record. Please try again.', 'danger');
                }
            });
            saleToDelete = null;
        }
    });

    // Export Functions
    function exportCSV() {
        // Get current table data without actions column
        const tableData = dataTable.data().toArray();
        const csvData = [];
        
        // Add headers (excluding Actions column)
        const headers = ['Sale ID', 'Client Name', 'Product', 'Quantity', 'Price', 'Total', 'Date', 'Status'];
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
        link.setAttribute('download', `Admin_SalesReport_${downloadCounter}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Increment download counter
        downloadCounter++;
        
        showNotification('CSV exported successfully!', 'success');
    }

    function exportPDF() {
        try {
            // Force custom PDF generation to match superadmin styling
            // Don't fall back to DataTables PDF export as it has different styling
            
            const tableData = dataTable.data().toArray();
            const pdfData = [];
            
            const headers = ['Sale ID', 'Farmer', 'Product', 'Quantity', 'Price', 'Total', 'Date'];
            
            tableData.forEach(row => {
                const rowData = [
                    row[0] || '', // Sale ID
                    row[1] || '', // Farmer
                    row[2] || '', // Product
                    row[3] || '', // Quantity
                    row[4] || '', // Price
                    row[5] || '',  // Total
                    row[6] || ''   // Date
                ];
                pdfData.push(rowData);
            });
            
            // Create PDF using jsPDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('landscape', 'mm', 'a4');
            
            // Set title
            doc.setFontSize(18);
            doc.text('Admin Sales Report', 14, 22);
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
            doc.save(`Admin_SalesReport_${downloadCounter}.pdf`);
            
            // Increment download counter
            downloadCounter++;
            
            showNotification('PDF exported successfully!', 'success');
            
        } catch (error) {
            console.error('Error generating PDF:', error);
            showNotification('Error generating PDF. Please try again.', 'danger');
        }
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
            link.download = `Admin_SalesReport_${downloadCounter}.png`;
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

    function printSales() {
        dataTable.button('.buttons-print').trigger();
    }

    function exportHistory() {
        let csv = 'Date,Sale ID,Type,Quantity,Amount Sold (₱)\n';
        const historyRows = document.querySelectorAll('#historyTableBody tr');
        
        historyRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 5) {
                csv += `${cells[0].textContent},${cells[1].textContent},${cells[2].textContent},${cells[3].textContent},${cells[4].textContent}\n`;
            }
        });
        
        const blob = new Blob([csv], { type: 'text/csv' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'sales_history.csv';
        link.click();
    }

    function importCSV(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const csv = e.target.result;
                const lines = csv.split('\n');
                const headers = lines[0].split(',');
                
                // Process CSV data and send to server
                const csvData = [];
                for (let i = 1; i < lines.length; i++) {
                    if (lines[i].trim()) {
                        const data = lines[i].split(',');
                        if (data.length >= 4) {
                            csvData.push({
                                dateSold: data[0],
                                type: data[1],
                                quantity: data[2],
                                amountSold: data[3]
                            });
                        }
                    }
                }
                
                // Send CSV data to server for processing
                $.ajax({
                    url: '{{ route("admin.sales.import") }}',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        csv_data: csvData
                    },
                    success: function(response) {
                        if (response.success) {
                            showNotification('CSV imported successfully!', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            showNotification('Error importing CSV: ' + response.message, 'danger');
                        }
                    },
                    error: function(xhr) {
                        showNotification('Error importing CSV. Please try again.', 'danger');
                    }
                });
            };
            reader.readAsText(file);
        }
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

    // History filtering and sorting
    document.getElementById('sortHistory').addEventListener('change', (event) => {
        filterAndSortHistory();
    });

    document.getElementById('filterHistory').addEventListener('change', (event) => {
        filterAndSortHistory();
    });

    function filterAndSortHistory() {
        const sortOrder = document.getElementById('sortHistory').value;
        const filter = document.getElementById('filterHistory').value;
        
        let rows = Array.from(document.querySelectorAll('#historyTableBody tr'));
        
        // Filter rows
        if (filter !== 'all') {
            rows = rows.filter(row => {
                const typeCell = row.querySelector('td:nth-child(3)');
                return typeCell && typeCell.textContent.toLowerCase().includes(filter.toLowerCase());
            });
        }
        
        // Sort rows
        rows.sort((a, b) => {
            const dateA = new Date(a.querySelector('td:nth-child(1)').textContent);
            const dateB = new Date(b.querySelector('td:nth-child(1)').textContent);
            return sortOrder === 'newest' ? dateB - dateA : dateA - dateB;
        });
        
        // Reorder table
        const tbody = document.getElementById('historyTableBody');
        rows.forEach(row => tbody.appendChild(row));
    }
</script>
@endpush
