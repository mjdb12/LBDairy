@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers-Sales')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-chart-line"></i>
        Sales Management
    </h1>
    <p>Track your livestock sales, analyze performance, and manage revenue</p>
</div>

<!-- Stats Cards -->
<div class="stats-container fade-in">
    <div class="stat-card border-left-primary">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Sales</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">₱171,200</div>
            </div>
            <div class="icon text-primary">
                <i class="fas fa-dollar-sign fa-2x"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card border-left-success">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">This Month</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">₱45,800</div>
            </div>
            <div class="icon text-success">
                <i class="fas fa-calendar fa-2x"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card border-left-info">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Transactions</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">24</div>
            </div>
            <div class="icon text-info">
                <i class="fas fa-receipt fa-2x"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card border-left-warning">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Average Price</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">₱7,133</div>
            </div>
            <div class="icon text-warning">
                <i class="fas fa-chart-bar fa-2x"></i>
            </div>
        </div>
    </div>
</div>

<!-- Sales Table -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-list mr-2"></i> Sales Records
                </h6>
                <div class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addLivestockDetailsModal">
                        <i class="fas fa-plus mr-1"></i> Add Sale
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <div class="dropdown-menu">
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
                    <button class="btn btn-success btn-sm" onclick="document.getElementById('csvInput').click()">
                        <i class="fas fa-file-import"></i> Import
                    </button>
                    <input type="file" id="csvInput" accept=".csv" style="display: none;" onchange="importCSV(event)">
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
                        <tbody id="salesTableBody">
                            <tr>
                                <td>SL001</td>
                                <td>2024-06-01</td>
                                <td>Goat</td>
                                <td>8,500</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(this)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>SL002</td>
                                <td>2024-06-03</td>
                                <td>Cow</td>
                                <td>35,000</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(this)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>SL003</td>
                                <td>2024-06-05</td>
                                <td>Carabao</td>
                                <td>42,000</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(this)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>SL004</td>
                                <td>2024-06-07</td>
                                <td>Goat</td>
                                <td>9,200</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(this)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>SL005</td>
                                <td>2024-06-10</td>
                                <td>Cow</td>
                                <td>36,500</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(this)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>SL006</td>
                                <td>2024-06-12</td>
                                <td>Carabao</td>
                                <td>41,000</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(this)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
<div class="modal fade" id="addLivestockDetailsModal" tabindex="-1" role="dialog" aria-labelledby="addLivestockDetailsLabel" aria-hidden="true">
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
                <form id="addLivestockDetailsForm">
                    <div class="form-group">
                        <label for="add_saleId">Sale ID</label>
                        <input type="text" class="form-control" id="add_saleId" name="saleId" required>
                    </div>
                    <div class="form-group">
                        <label for="add_dateSold">Date Sold</label>
                        <input type="date" class="form-control" id="add_dateSold" name="dateSold" required>
                    </div>
                    <div class="form-group">
                        <label for="add_type">Type</label>
                        <select class="form-control" id="add_type" name="type" required>
                            <option value="" disabled selected>Select Type</option>
                            <option value="Goat">Goat</option>
                            <option value="Cow">Cow</option>
                            <option value="Carabao">Carabao</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add_amountSold">Amount Sold (₱)</label>
                        <input type="number" class="form-control" id="add_amountSold" name="amountSold" min="0" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" form="addLivestockDetailsForm" class="btn btn-primary">
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
                                <th>Amount Sold (₱)</th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody">
                            <!-- Sales history will be dynamically populated here -->
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
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<style>
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

.stat-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, var(--primary-color), var(--primary-dark));
}

.stat-card.border-left-success::before {
    background: linear-gradient(90deg, var(--success-color), var(--success-color));
}

.stat-card.border-left-info::before {
    background: linear-gradient(90deg, var(--info-color), var(--info-color));
}

.stat-card.border-left-warning::before {
    background: linear-gradient(90deg, var(--warning-color), var(--warning-color));
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script>
let dataTable;
let saleToDelete = null;

$(document).ready(function () {
    // Initialize DataTable
    initializeDataTable();
    
    // Initialize history data
    renderSalesHistory();

    // Form submission
    document.getElementById('addLivestockDetailsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addNewSale();
    });
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

function addNewSale() {
    const saleId = document.getElementById('add_saleId').value;
    const dateSold = document.getElementById('add_dateSold').value;
    const type = document.getElementById('add_type').value;
    const amountSold = document.getElementById('add_amountSold').value;
    
    if (!saleId || !dateSold || !type || !amountSold) {
        showNotification('Please fill in all fields', 'error');
        return;
    }
    
    // Add to table
    const newRow = dataTable.row.add([
        saleId,
        dateSold,
        type,
        '₱' + parseInt(amountSold).toLocaleString(),
        `<div class="btn-group" role="group">
            <button class="btn btn-danger btn-sm" onclick="confirmDelete(this)" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </div>`
    ]).draw().node();
    
    // Close modal and reset form
    $('#addLivestockDetailsModal').modal('hide');
    document.getElementById('addLivestockDetailsForm').reset();
    
    showNotification('Sale record added successfully!', 'success');
    updateStats();
}

function confirmDelete(button) {
    const row = button.closest('tr');
    const saleId = row.cells[0].textContent;
    saleToDelete = row;
    
    $('#confirmDeleteModal').modal('show');
    
    // Set up delete button
    document.getElementById('confirmDeleteBtn').onclick = function() {
        deleteSale();
    };
}

function deleteSale() {
    if (saleToDelete) {
        dataTable.row(saleToDelete).remove().draw();
        saleToDelete = null;
        $('#confirmDeleteModal').modal('hide');
        showNotification('Sale record deleted successfully!', 'success');
        updateStats();
    }
}

function renderSalesHistory() {
    const historyData = [
        { date: '2024-06-01', saleId: 'SL001', type: 'Goat', amount: '8,500' },
        { date: '2024-06-03', saleId: 'SL002', type: 'Cow', amount: '35,000' },
        { date: '2024-06-05', saleId: 'SL003', type: 'Carabao', amount: '42,000' },
        { date: '2024-06-07', saleId: 'SL004', type: 'Goat', amount: '9,200' },
        { date: '2024-06-10', saleId: 'SL005', type: 'Cow', amount: '36,500' },
        { date: '2024-06-12', saleId: 'SL006', type: 'Carabao', amount: '41,000' }
    ];
    
    const tbody = document.getElementById('historyTableBody');
    tbody.innerHTML = '';
    
    historyData.forEach(sale => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${sale.date}</td>
            <td>${sale.saleId}</td>
            <td>${sale.type}</td>
            <td>₱${sale.amount}</td>
        `;
        tbody.appendChild(row);
    });
}

function updateStats() {
    // Update stats based on current table data
    const rows = dataTable.rows().data();
    let totalAmount = 0;
    let totalTransactions = 0;
    
    rows.each(function(value, index) {
        const amount = parseInt(value[3].replace(/[₱,]/g, ''));
        if (!isNaN(amount)) {
            totalAmount += amount;
            totalTransactions++;
        }
    });
    
    // Update display (you can add elements to show these values)
    console.log('Total Amount:', totalAmount, 'Total Transactions:', totalTransactions);
}

function exportCSV() {
    dataTable.button('.buttons-csv').trigger();
}

function exportPDF() {
    dataTable.button('.buttons-pdf').trigger();
}

function exportPNG() {
    // Implement PNG export
    showNotification('PNG export functionality coming soon!', 'info');
}

function printProductivity() {
    dataTable.button('.buttons-print').trigger();
}

function importCSV(event) {
    const file = event.target.files[0];
    if (file) {
        // Handle CSV import logic here
        showNotification('CSV import functionality coming soon!', 'info');
    }
}

function exportHistory() {
    // Export history data
    showNotification('History exported successfully!', 'success');
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
</script>
@endpush

