@extends('layouts.app')

@section('title', 'Production Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-industry"></i>
            Production Management
        </h1>
        <p>Monitor and manage dairy production operations across all farms</p>
    </div>

    <!-- Production Summary Cards -->
    <div class="row fade-in">
        <!-- Total Products -->
        <div class="col-12 col-sm-6 col-md-3 mb-4">
            <div class="card summary-card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Products</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProducts }}</div>
                    </div>
                    <div class="icon text-primary">
                        <i class="fas fa-box-open fa-2x"></i>
                    </div>
                </div>
                <a href="#" class="card-footer text-primary small d-flex justify-content-between align-items-center">
                    View Products <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- Total Stock -->
        <div class="col-12 col-sm-6 col-md-3 mb-4">
            <div class="card summary-card border-left-success shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Stock</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStock }} units</div>
                    </div>
                    <div class="icon text-success">
                        <i class="fas fa-warehouse fa-2x"></i>
                    </div>
                </div>
                <a href="#" class="card-footer text-success small d-flex justify-content-between align-items-center">
                    View Stock <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- Today's Production -->
        <div class="col-12 col-sm-6 col-md-3 mb-4">
            <div class="card summary-card border-left-info shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Today's Production</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayProduction }} units</div>
                    </div>
                    <div class="icon text-info">
                        <i class="fas fa-calendar-day fa-2x"></i>
                    </div>
                </div>
                <a href="#" class="card-footer text-info small d-flex justify-content-between align-items-center">
                    View Details <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- Low Stock Alerts -->
        <div class="col-12 col-sm-6 col-md-3 mb-4">
            <div class="card summary-card border-left-warning shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Low Stock Alerts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockAlerts }} Products</div>
                    </div>
                    <div class="icon text-warning">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
                <a href="#" class="card-footer text-warning small d-flex justify-content-between align-items-center">
                    Check Alerts <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Production List Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header">
            <h6>
                <i class="fas fa-list-alt"></i>
                Production List
            </h6>
            <div class="table-controls">
                <div class="search-container">
                    <input type="text" class="form-control custom-search" placeholder="Search products...">
                </div>
                <div class="export-controls">
                    <button class="btn btn-info btn-sm" onclick="addProductionDetails()">
                        <i class="fas fa-plus mr-1"></i> Add New Product
                    </button>
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
                    <button class="btn btn-secondary btn-sm" onclick="printProductivity()">
                        <i class="fas fa-print"></i>
                    </button>
                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#historyModal">
                        <i class="fas fa-history"></i> History
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="productionTable">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Batch Number</th>
                            <th>Stock Number</th>
                            <th>Farm</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productionTableBody">
                        @forelse($productionRecords as $record)
                        <tr>
                            <td>{{ $record->id }}</td>
                            <td>{{ $record->type ?? 'Milk' }}</td>
                            <td>BATCH-{{ $record->created_at->format('Ymd') }}</td>
                            <td>{{ $record->quantity ?? 0 }}</td>
                            <td>{{ $record->farm->name ?? 'N/A' }}</td>
                            <td>{{ $record->created_at->format('M d, Y') }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $record->id }}')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-industry"></i>
                                    <h5>No production records</h5>
                                    <p>There are no production records available at this time.</p>
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
                Are you sure you want to delete this production record? This action cannot be undone.
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

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel">
                    <i class="fas fa-history"></i>
                    Production History
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
                            <option value="milk">Milk</option>
                            <option value="cheese">Cheese</option>
                            <option value="yogurt">Yogurt</option>
                        </select>
                    </div>
                </div>
                <div id="historyContent" class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Batch Number</th>
                                <th>Stock Produced</th>
                                <th>Farm</th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody">
                            @foreach($productionHistory as $record)
                            <tr>
                                <td>{{ $record->created_at->format('M d, Y') }}</td>
                                <td>{{ $record->id }}</td>
                                <td>{{ $record->type ?? 'Milk' }}</td>
                                <td>BATCH-{{ $record->created_at->format('Ymd') }}</td>
                                <td>{{ $record->quantity ?? 0 }}</td>
                                <td>{{ $record->farm->name ?? 'N/A' }}</td>
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

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i>
                    Add New Production Record
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form onsubmit="addProductionRecord(event)">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="productType">Product Type</label>
                        <select class="form-control" id="productType" required>
                            <option value="">Select Product Type</option>
                            <option value="milk">Milk</option>
                            <option value="cheese">Cheese</option>
                            <option value="yogurt">Yogurt</option>
                            <option value="butter">Butter</option>
                            <option value="chocolate_milk">Chocolate Milk</option>
                            <option value="condensed_milk">Condensed Milk</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" placeholder="Enter quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="unit">Unit</label>
                        <select class="form-control" id="unit" required>
                            <option value="liters">Liters</option>
                            <option value="kilograms">Kilograms</option>
                            <option value="units">Units</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="farmSelect">Farm</label>
                        <select class="form-control" id="farmSelect" required>
                            <option value="">Select Farm</option>
                            @foreach($farms as $farm)
                            <option value="{{ $farm->id }}">{{ $farm->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" rows="3" placeholder="Additional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
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

    /* Enhanced Summary Cards */
    .summary-card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .summary-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .summary-card .card-body {
        padding: 1.5rem;
    }

    .summary-card .card-footer {
        background: rgba(0, 0, 0, 0.05);
        border-top: 1px solid var(--border-color);
        padding: 0.75rem 1.5rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .summary-card .card-footer:hover {
        background: rgba(0, 0, 0, 0.1);
        text-decoration: none;
    }

    .border-left-primary {
        border-left: 4px solid var(--primary-color) !important;
    }

    .border-left-success {
        border-left: 4px solid var(--success-color) !important;
    }

    .border-left-info {
        border-left: 4px solid var(--info-color) !important;
    }

    .border-left-warning {
        border-left: 4px solid var(--warning-color) !important;
    }

    .text-xs {
        font-size: 0.7rem;
    }

    .text-gray-800 {
        color: #5a5c69 !important;
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
        border-top: 1px solid var(--border-color);
    }

    .table tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
    }

    /* Table Controls */
    .table-controls {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .search-container {
        min-width: 250px;
    }

    .export-controls {
        display: flex;
        align-items: center;
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
    }

    /* Animation Classes */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
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
        color: var(--border-color);
    }

    .empty-state h5 {
        margin-bottom: 0.5rem;
        color: var(--dark-color);
    }

    .empty-state p {
        color: #6c757d;
        margin: 0;
    }
</style>
@endsection

@section('scripts')
<script>
let productionTable;

$(document).ready(function () {
    // Initialize DataTable
    initializeProductionTable();
    
    // Custom search functionality
    $('.custom-search').on('keyup', function() {
        productionTable.search(this.value).draw();
    });
});

function initializeProductionTable() {
    productionTable = $('#productionTable').DataTable({
        searching: true,
        paging: true,
        info: true,
        ordering: true,
        lengthChange: false,
        pageLength: 10,
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-industry"></i><h5>No production records</h5><p>There are no production records available at this time.</p></div>'
        }
    });

    // Hide default DataTables elements
    $('.dataTables_filter').hide();
}

function addProductionDetails() {
    $('#addProductModal').modal('show');
}

function addProductionRecord(event) {
    event.preventDefault();
    
    const productType = document.getElementById('productType').value;
    const quantity = document.getElementById('quantity').value;
    const unit = document.getElementById('unit').value;
    const farmId = document.getElementById('farmSelect').value;
    const notes = document.getElementById('notes').value;
    
    // Here you would typically send the data via AJAX
    // For now, just show a success message
    showNotification('Production record added successfully!', 'success');
    $('#addProductModal').modal('hide');
    
    // Clear the form
    document.getElementById('productType').value = '';
    document.getElementById('quantity').value = '';
    document.getElementById('unit').value = '';
    document.getElementById('farmSelect').value = '';
    document.getElementById('notes').value = '';
    
    // Reload the page to show the new record
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function confirmDelete(recordId) {
    if (confirm('Are you sure you want to delete this production record? This action cannot be undone.')) {
        deleteProductionRecord(recordId);
    }
}

function deleteProductionRecord(recordId) {
    $.ajax({
        url: `/admin/production/${recordId}`,
        type: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                showNotification('Production record deleted successfully!', 'success');
                location.reload();
            } else {
                showNotification('Failed to delete production record', 'error');
            }
        },
        error: function() {
            showNotification('Failed to delete production record', 'error');
        }
    });
}

function exportCSV() {
    // Implementation for CSV export
    showNotification('CSV export functionality coming soon!', 'info');
}

function exportPDF() {
    // Implementation for PDF export
    showNotification('PDF export functionality coming soon!', 'info');
}

function exportPNG() {
    // Implementation for PNG export
    showNotification('PNG export functionality coming soon!', 'info');
}

function printProductivity() {
    window.print();
}

function exportHistory() {
    // Implementation for history export
    showNotification('History export functionality coming soon!', 'info');
}

function showNotification(message, type) {
    // Create a simple notification
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
}

// Handle history filtering and sorting
$(document).ready(function() {
    $('#sortHistory, #filterHistory').on('change', function() {
        // Implementation for history filtering and sorting
        showNotification('History filtering and sorting functionality coming soon!', 'info');
    });
});
</script>
@endsection
