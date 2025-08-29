@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers-Suppliers')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-truck"></i>
        Suppliers Management
    </h1>
    <p>Manage your farm suppliers, track purchases, and maintain supplier ledgers</p>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 fade-in">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Suppliers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSuppliers }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-truck fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2 fade-in">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Active Suppliers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeSuppliers }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2 fade-in">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Spent</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($totalSpent) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 fade-in">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending Payments</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($pendingPayments) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-list mr-2"></i> Suppliers List
                </h6>
                <div class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addLivestockDetailsModal">
                        <i class="fas fa-plus mr-1"></i> Add Supplier
                    </button>
                    <button class="btn btn-secondary btn-sm" onclick="window.print()">
                        <i class="fas fa-print"></i>
                    </button>
                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#historyModal">
                        <i class="fas fa-history"></i> History
                    </button>
                    <button class="btn btn-success btn-sm" onclick="document.getElementById('csvInput').click()">
                        <i class="fas fa-file-import mr-1"></i> Import
                    </button>
                    <input type="file" id="csvInput" accept=".csv" style="display: none;" onchange="importCSV(event)">
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="suppliersTable">
                        <thead>
                            <tr>
                                <th>Supplier ID</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="suppliersTableBody">
                            @forelse($suppliersData as $supplier)
                            <tr>
                                <td>{{ $supplier['supplier_id'] }}</td>
                                <td>{{ $supplier['name'] }}</td>
                                <td>{{ $supplier['address'] }}</td>
                                <td>{{ $supplier['contact'] }}</td>
                                <td><span class="status-badge {{ $supplier['status_badge'] }}">{{ $supplier['status_label'] }}</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-action-ledger" onclick="viewLedger('{{ $supplier['name'] }}')" title="View Ledger">
                                            <i class="fas fa-book"></i>
                                            <span>Ledger</span>
                                        </button>
                                        <button class="btn-action btn-action-view" onclick="viewDetails('{{ $supplier['name'] }}')" title="View Details">
                                            <i class="fas fa-eye"></i>
                                            <span>View</span>
                                        </button>
                                        <button class="btn-action btn-action-delete" onclick="confirmDelete('{{ $supplier['name'] }}')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-truck fa-3x mb-3 text-muted"></i>
                                    <p>No suppliers found. Add expenses with supplier information to see suppliers here.</p>
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

<!-- SUPPLIER LEDGER MODAL -->
<div class="modal fade" id="supplierLedgerModal" tabindex="-1" role="dialog" aria-labelledby="supplierLedgerLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-book"></i>
                    Supplier Ledger
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Supplier Info Card -->
                <div class="client-info-card">
                    <div class="d-flex align-items-center">
                        <div class="icon mr-3">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1" id="ledgerSupplierName">Supplier Name</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="opacity-75">Supplier ID:</small>
                                    <div id="supplierInfoId">SP001</div>
                                </div>
                                <div class="col-md-8">
                                    <small class="opacity-75">Address:</small>
                                    <div id="supplierInfoAddress">Supplier Address</div>
                                </div>
                            </div>
                            <button class="btn btn-light btn-sm" onclick="showAddSupplierLedgerEntryForm()">
                                <i class="fas fa-plus mr-2"></i>Add Entry
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Ledger Table -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="supplierLedgerTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Payable (₱)</th>
                                <th>Paid (₱)</th>
                                <th>Due (₱)</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Ledger entries will be populated here -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Add Entry Form -->
                <form id="supplierLedgerEntryForm" class="mt-4" style="display:none;">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Add New Ledger Entry</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="purchaseDate" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="purchaseDate" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="purchaseType" class="form-label">Type</label>
                                    <select class="form-control" id="purchaseType" required>
                                        <option value="" disabled selected>Select</option>
                                        <option value="Feed">Feed</option>
                                        <option value="Medicine">Medicine</option>
                                        <option value="Equipment">Equipment</option>
                                        <option value="Livestock">Livestock</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="payableAmount" class="form-label">Payable (₱)</label>
                                    <input type="number" class="form-control" id="payableAmount" min="0" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="paidAmount" class="form-label">Paid (₱)</label>
                                    <input type="number" class="form-control" id="paidAmount" min="0" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="paymentStatus" class="form-label">Status</label>
                                    <select class="form-control" id="paymentStatus" required>
                                        <option value="Unpaid">Unpaid</option>
                                        <option value="Partial">Partial</option>
                                        <option value="Paid">Paid</option>
                                    </select>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
                Are you sure you want to delete this entry? This action cannot be undone.
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
                    Supplier Purchase History
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
                            <option value="feed">Feed</option>
                            <option value="medicine">Medicine</option>
                            <option value="equipment">Equipment</option>
                            <option value="livestock">Livestock</option>
                        </select>
                    </div>
                </div>
                <div id="historyContent" class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Supplier ID</th>
                                <th>Type</th>
                                <th>Amount Payable (₱)</th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody">
                            <!-- Supplier history will be dynamically populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="exportHistory()">
                    <i class="fas fa-download mr-1"></i> Export History
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ADD SUPPLIER MODAL -->
<div class="modal fade" id="addLivestockDetailsModal" tabindex="-1" role="dialog" aria-labelledby="addLivestockDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i>
                    Add New Supplier
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addLivestockDetailsForm">
                    <div class="form-group">
                        <label for="add_supplierId">Supplier ID</label>
                        <input type="text" class="form-control" id="add_supplierId" name="supplierId" required>
                    </div>
                    <div class="form-group">
                        <label for="add_supplierName">Name</label>
                        <input type="text" class="form-control" id="add_supplierName" name="supplierName" required>
                    </div>
                    <div class="form-group">
                        <label for="add_supplierAddress">Address</label>
                        <input type="text" class="form-control" id="add_supplierAddress" name="supplierAddress" required>
                    </div>
                    <div class="form-group">
                        <label for="add_supplierContact">Contact Number</label>
                        <input type="text" class="form-control" id="add_supplierContact" name="supplierContact" required>
                    </div>
                    <div class="modal-footer px-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Save Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Supplier Details Modal -->
<div class="modal fade" id="supplierDetailsModal" tabindex="-1" role="dialog" aria-labelledby="supplierDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="supplierDetailsLabel">
                    <i class="fas fa-info-circle"></i>
                    Supplier Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Supplier details will be populated here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script>
// Initialize DataTable
$(document).ready(function() {
    // DataTable initialization disabled to prevent column count warnings
    // The table will function as a standard HTML table with Bootstrap styling
    console.log('Suppliers table loaded successfully');
});

// View Ledger function
function viewLedger(supplierName) {
    // Update modal with supplier information
    document.getElementById('ledgerSupplierName').textContent = supplierName;
    
    // In a real implementation, you would fetch supplier details and ledger data
    // For now, we'll show the modal with placeholder data
    $('#supplierLedgerModal').modal('show');
}

// View Details function
function viewDetails(supplierName) {
    // Update modal with supplier information
    const modalBody = document.querySelector('#supplierDetailsModal .modal-body');
    modalBody.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h6>Supplier Information</h6>
                <p><strong>Name:</strong> ${supplierName}</p>
                <p><strong>Status:</strong> Active</p>
                <p><strong>Total Transactions:</strong> ${Math.floor(Math.random() * 20) + 5}</p>
                <p><strong>Total Spent:</strong> ₱${(Math.random() * 50000 + 10000).toLocaleString()}</p>
            </div>
            <div class="col-md-6">
                <h6>Recent Activity</h6>
                <p><strong>Last Transaction:</strong> ${new Date().toLocaleDateString()}</p>
                <p><strong>Payment Status:</strong> Good Standing</p>
            </div>
        </div>
    `;
    
    $('#supplierDetailsModal').modal('show');
}

// Confirm Delete function
function confirmDelete(supplierName) {
    // Update modal message
    document.querySelector('#confirmDeleteModal .modal-body').textContent = 
        `Are you sure you want to delete supplier "${supplierName}"? This action cannot be undone.`;
    
    $('#confirmDeleteModal').modal('show');
}

// Show Add Entry Form
function showAddSupplierLedgerEntryForm() {
    document.getElementById('supplierLedgerEntryForm').style.display = 'block';
}

// Import CSV function
function importCSV(event) {
    const file = event.target.files[0];
    if (file) {
        // Handle CSV import logic here
        console.log('Importing CSV:', file.name);
    }
}

// Export History function
function exportHistory() {
    // Handle export logic here
    console.log('Exporting history...');
}
</script>
@endpush

