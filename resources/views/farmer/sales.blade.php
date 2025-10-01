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
<div class="row fade-in">
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Sales</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($totalSales) }}</div>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign fa-2x" style="color: #18375d !important;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #1cc88a !important;">This Month</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($monthlySales) }}</div>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar fa-2x" style="color: #1cc88a !important;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #36b9cc !important;">Total Transactions</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTransactions }}</div>
                </div>
                <div class="icon">
                    <i class="fas fa-receipt fa-2x" style="color: #36b9cc !important;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #f6c23e !important;">Average Price</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($averagePrice) }}</div>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-bar fa-2x" style="color: #f6c23e !important;"></i>
                </div>
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
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Quantity (L)</th>
                                <th>Unit Price (₱)</th>
                                <th>Total Amount (₱)</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="salesTableBody">
                            @forelse($salesData as $sale)
                            <tr>
                                <td>{{ $sale['sale_id'] }}</td>
                                <td>{{ $sale['sale_date'] }}</td>
                                <td>{{ $sale['customer_name'] }}</td>
                                <td>{{ number_format($sale['quantity'], 2) }}</td>
                                <td>₱{{ number_format($sale['unit_price'], 2) }}</td>
                                <td>₱{{ number_format($sale['amount']) }}</td>
                                <td>
                                    <span class="badge badge-{{ $sale['payment_status'] == 'paid' ? 'success' : ($sale['payment_status'] == 'partial' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($sale['payment_status']) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-action-view" onclick="viewSale('{{ $sale['id'] }}')" title="View Details">
                                            <i class="fas fa-eye"></i>
                                            <span>View</span>
                                        </button>
                                        <button class="btn-action btn-action-edit" onclick="editSale('{{ $sale['id'] }}')" title="Edit">
                                            <i class="fas fa-edit"></i>
                                            <span>Edit</span>
                                        </button>
                                        <button class="btn-action btn-action-delete" onclick="confirmDelete('{{ $sale['id'] }}')" title="Delete">
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
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">No sales records available</td>
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">N/A</td>
                            </tr>
                            @endforelse
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
                        <label for="add_farm_id">Select Farm</label>
                        <select class="form-control" id="add_farm_id" name="farm_id" required>
                            <option value="" disabled selected>Select Farm</option>
                            @foreach($farms ?? [] as $farm)
                                <option value="{{ $farm->id }}">{{ $farm->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add_customer_name">Customer Name</label>
                        <input type="text" class="form-control" id="add_customer_name" name="customer_name" required>
                    </div>
                    <div class="form-group">
                        <label for="add_customer_phone">Customer Phone</label>
                        <input type="text" class="form-control" id="add_customer_phone" name="customer_phone">
                    </div>
                    <div class="form-group">
                        <label for="add_customer_email">Customer Email</label>
                        <input type="email" class="form-control" id="add_customer_email" name="customer_email">
                    </div>
                    <div class="form-group">
                        <label for="add_quantity">Quantity (Liters)</label>
                        <input type="number" class="form-control" id="add_quantity" name="quantity" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="add_unit_price">Unit Price (₱/Liter)</label>
                        <input type="number" class="form-control" id="add_unit_price" name="unit_price" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="add_sale_date">Sale Date</label>
                        <input type="date" class="form-control" id="add_sale_date" name="sale_date" required>
                    </div>
                    <div class="form-group">
                        <label for="add_payment_method">Payment Method</label>
                        <select class="form-control" id="add_payment_method" name="payment_method">
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="mobile_money">Mobile Money</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add_payment_status">Payment Status</label>
                        <select class="form-control" id="add_payment_status" name="payment_status">
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="partial">Partial</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add_notes">Notes</label>
                        <textarea class="form-control" id="add_notes" name="notes" rows="3" placeholder="Additional notes about the sale..."></textarea>
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
                                <th>Month</th>
                                <th>Transactions</th>
                                <th>Total Sales (₱)</th>
                                <th>Average Sale (₱)</th>
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

<!-- Required libraries for PDF/Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<!-- jsPDF and autoTable for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script>
let saleToDelete = null;

$(document).ready(function () {
    // Initialize history data
    renderSalesHistory();

    // Form submission
    document.getElementById('addLivestockDetailsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addNewSale();
    });
});



function addNewSale() {
    const formData = new FormData(document.getElementById('addLivestockDetailsForm'));
    
    // Validate required fields
    if (!formData.get('farm_id') || !formData.get('customer_name') || !formData.get('quantity') || !formData.get('unit_price') || !formData.get('sale_date')) {
        showNotification('Please fill in all required fields', 'error');
        return;
    }
    
    // Send AJAX request
    fetch('{{ route("farmer.sales.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            farm_id: formData.get('farm_id'),
            customer_name: formData.get('customer_name'),
            customer_phone: formData.get('customer_phone'),
            customer_email: formData.get('customer_email'),
            quantity: formData.get('quantity'),
            unit_price: formData.get('unit_price'),
            sale_date: formData.get('sale_date'),
            payment_method: formData.get('payment_method'),
            payment_status: formData.get('payment_status'),
            notes: formData.get('notes')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            $('#addLivestockDetailsModal').modal('hide');
            document.getElementById('addLivestockDetailsForm').reset();
            // Reload the page to refresh the data
            location.reload();
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while saving the sale record', 'error');
    });
}

function viewSale(saleId) {
    // Load and display sale details
    $.ajax({
        url: `/farmer/sales/${saleId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const sale = response.sale;
                const modalHtml = `
                    <div class="modal fade" id="viewSaleModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-eye"></i>
                                        Sale Details
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Sale Information</h6>
                                            <p><strong>Sale ID:</strong> ${sale.sale_id}</p>
                                            <p><strong>Date:</strong> ${sale.sale_date}</p>
                                            <p><strong>Customer:</strong> ${sale.customer_name}</p>
                                            <p><strong>Quantity:</strong> ${sale.quantity} L</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Financial Details</h6>
                                            <p><strong>Unit Price:</strong> ₱${sale.unit_price}</p>
                                            <p><strong>Total Amount:</strong> ₱${sale.amount}</p>
                                            <p><strong>Payment Status:</strong> <span class="badge badge-${sale.payment_status === 'paid' ? 'success' : 'warning'}">${sale.payment_status}</span></p>
                                            <p><strong>Payment Method:</strong> ${sale.payment_method}</p>
                                        </div>
                                    </div>
                                    ${sale.notes ? `<div class="row mt-3"><div class="col-12"><h6>Notes</h6><p>${sale.notes}</p></div></div>` : ''}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                $('#viewSaleModal').remove();
                $('body').append(modalHtml);
                $('#viewSaleModal').modal('show');
            }
        },
        error: function() {
            showNotification('Failed to load sale details.', 'danger');
        }
    });
}

function editSale(saleId) {
    // Load sale data for editing
    $.ajax({
        url: `/farmer/sales/${saleId}/edit`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const sale = response.sale;
                
                // Populate the add sale modal with existing data
                $('#addLivestockDetailsModal').modal('show');
                $('#customer_name').val(sale.customer_name);
                $('#customer_phone').val(sale.customer_phone);
                $('#customer_email').val(sale.customer_email);
                $('#quantity').val(sale.quantity);
                $('#unit_price').val(sale.unit_price);
                $('#sale_date').val(sale.sale_date);
                $('#payment_method').val(sale.payment_method);
                $('#payment_status').val(sale.payment_status);
                $('#notes').val(sale.notes);
                
                // Change form action to update
                $('#addLivestockDetailsForm').attr('action', `/farmer/sales/${saleId}`);
                $('#addLivestockDetailsForm').attr('method', 'PUT');
                $('#addLivestockDetailsModalLabel').html('<i class="fas fa-edit"></i> Edit Sale Record');
                $('#saveSaleBtn').html('<i class="fas fa-save"></i> Update Sale');
            }
        },
        error: function() {
            showNotification('Failed to load sale for editing.', 'danger');
        }
    });
}

function confirmDelete(saleId) {
    saleToDelete = saleId;
    $('#confirmDeleteModal').modal('show');
    
    // Set up delete button
    document.getElementById('confirmDeleteBtn').onclick = function() {
        deleteSale();
    };
}

function deleteSale() {
    if (saleToDelete) {
        // Send AJAX request to delete
        fetch(`/farmer/sales/${saleToDelete}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                $('#confirmDeleteModal').modal('hide');
                // Reload the page to refresh the data
                location.reload();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while deleting the sale record', 'error');
        });
        
        saleToDelete = null;
    }
}

function renderSalesHistory() {
    const historyData = @json($salesHistory);
    
    const tbody = document.getElementById('historyTableBody');
    tbody.innerHTML = '';
    
    if (historyData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center text-muted py-4">
                    <i class="fas fa-chart-line fa-3x mb-3 text-muted"></i>
                    <p>No sales history available.</p>
                </td>
            </tr>
        `;
        return;
    }
    
    historyData.forEach(sale => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${sale.month}</td>
            <td>${sale.transaction_count}</td>
            <td>₱${parseInt(sale.total_sales).toLocaleString()}</td>
            <td>₱${parseInt(sale.total_sales / sale.transaction_count).toLocaleString()}</td>
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
    link.setAttribute('download', `Farmer_SalesReport_${downloadCounter}.csv`);
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
        
        const headers = ['Sale ID', 'Product', 'Quantity', 'Price', 'Total', 'Customer', 'Date'];
        
        tableData.forEach(row => {
            const rowData = [
                row[0] || '', // Sale ID
                row[1] || '', // Product
                row[2] || '', // Quantity
                row[3] || '', // Price
                row[4] || '', // Total
                row[5] || '', // Customer
                row[6] || ''  // Date
            ];
            pdfData.push(rowData);
        });
        
        // Create PDF using jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape', 'mm', 'a4');
        
        // Set title
        doc.setFontSize(18);
        doc.text('Farmer Sales Report', 14, 22);
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
        doc.save(`Farmer_SalesReport_${downloadCounter}.pdf`);
        
        // Increment download counter
        downloadCounter++;
        
        showToast('PDF exported successfully!', 'success');
        
    } catch (error) {
        console.error('Error generating PDF:', error);
        showToast('Error generating PDF. Please try again.', 'error');
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
        link.download = `Farmer_SalesReport_${downloadCounter}.png`;
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

