@extends('layouts.app')

@section('title', 'Inventory Management - LBDairy')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-boxes"></i>
            Inventory Management
        </h1>
        <p>Track and manage your farm inventory including feed, medicine, and equipment</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container fade-in">
        <div class="stat-card primary">
            <div class="card-body">
                <div class="stat-info">
                    <h6>Total Inventory Items</h6>
                    <div class="stat-number">{{ $totalItems ?? 0 }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
        </div>
        <div class="stat-card success">
            <div class="card-body">
                <div class="stat-info">
                    <h6>Feed Stock</h6>
                    <div class="stat-number">{{ $feedStock ?? 0 }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-seedling"></i>
                </div>
            </div>
        </div>
        <div class="stat-card info">
            <div class="card-body">
                <div class="stat-info">
                    <h6>Medicine Stock</h6>
                    <div class="stat-number">{{ $medicineStock ?? 0 }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-prescription-bottle-alt"></i>
                </div>
            </div>
        </div>
        <div class="stat-card warning">
            <div class="card-body">
                <div class="stat-info">
                    <h6>Equipment Stock</h6>
                    <div class="stat-number">{{ $equipmentStock ?? 0 }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Management Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header">
            <h6>
                <i class="fas fa-list"></i>
                Inventory List
            </h6>
            <div class="table-controls">
                <button class="btn btn-info btn-sm" onclick="addInventoryItem()">
                    <i class="fas fa-plus mr-1"></i> Add New Item
                </button>
                <div class="search-container">
                    <input type="text" class="form-control custom-search" placeholder="Search inventory..." id="customSearch">
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
                    <button class="btn btn-secondary btn-sm" onclick="printInventory()">
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
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="inventoryTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Inventory ID</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Inventory Name</th>
                            <th>Quantity</th>
                            <th>Farm ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="inventoryTableBody">
                        @forelse($inventory ?? [] as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->date ?? 'N/A' }}</td>
                            <td>
                                <span class="category-badge category-{{ strtolower($item->category ?? 'feed') }}">
                                    {{ $item->category ?? 'Feed' }}
                                </span>
                            </td>
                            <td>{{ $item->name ?? 'N/A' }}</td>
                            <td>{{ $item->quantity ?? 'N/A' }}</td>
                            <td>{{ $item->farm_id ?? 'N/A' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-primary btn-sm" onclick="editInventoryItem('{{ $item->id }}')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(this)" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-boxes"></i>
                                    <h5>No inventory items found</h5>
                                    <p>Add your first inventory item to start tracking.</p>
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
                Are you sure you want to delete this inventory item? This action cannot be undone.
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

<!-- Add/Edit Inventory Modal -->
<div class="modal fade" id="inventoryModal" tabindex="-1" role="dialog" aria-labelledby="inventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inventoryModalLabel">
                    <i class="fas fa-plus"></i>
                    Add New Inventory Item
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="inventoryForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inventoryId">Inventory ID</label>
                                <input type="text" class="form-control" id="inventoryId" name="inventoryId" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inventoryDate">Date</label>
                                <input type="date" class="form-control" id="inventoryDate" name="inventoryDate" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inventoryCategory">Category</label>
                                <select class="form-control" id="inventoryCategory" name="inventoryCategory" required>
                                    <option value="">Select Category</option>
                                    <option value="Feed">Feed</option>
                                    <option value="Medicine">Medicine</option>
                                    <option value="Equipment">Equipment</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inventoryName">Inventory Name</label>
                                <input type="text" class="form-control" id="inventoryName" name="inventoryName" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inventoryQuantity">Quantity</label>
                                <input type="text" class="form-control" id="inventoryQuantity" name="inventoryQuantity" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inventoryFarmId">Farm ID</label>
                                <input type="text" class="form-control" id="inventoryFarmId" name="inventoryFarmId" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Inventory
                    </button>
                </div>
            </form>
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
                    Inventory History
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
                        </select>
                    </div>
                </div>
                <div id="historyContent">
                    <!-- History content will be loaded here -->
                    <div class="text-center text-muted">
                        <i class="fas fa-history fa-3x mb-3"></i>
                        <p>Inventory history will be displayed here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
let inventoryTable;
let currentEditId = null;

$(document).ready(function() {
    initializeDataTable();
    setupEventListeners();
});

function initializeDataTable() {
    inventoryTable = $('#inventoryTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[1, 'desc']], // Sort by date descending
        language: {
            search: "Search inventory:",
            lengthMenu: "Show _MENU_ items per page",
            info: "Showing _START_ to _END_ of _TOTAL_ items"
        }
    });
}

function setupEventListeners() {
    // Custom search functionality
    $('#customSearch').on('keyup', function() {
        inventoryTable.search(this.value).draw();
    });

    // Inventory form submission
    $('#inventoryForm').on('submit', function(e) {
        e.preventDefault();
        saveInventoryItem();
    });

    // History filter changes
    $('#sortHistory, #filterHistory').on('change', function() {
        loadInventoryHistory();
    });
}

function addInventoryItem() {
    currentEditId = null;
    $('#inventoryModalLabel').html('<i class="fas fa-plus"></i> Add New Inventory Item');
    $('#inventoryForm')[0].reset();
    $('#inventoryModal').modal('show');
}

function editInventoryItem(itemId) {
    currentEditId = itemId;
    $('#inventoryModalLabel').html('<i class="fas fa-edit"></i> Edit Inventory Item');
    
    // In real app, this would load the item data via AJAX
    // For now, we'll populate with sample data
    $('#inventoryId').val(itemId);
    $('#inventoryDate').val('2024-06-15');
    $('#inventoryCategory').val('Feed');
    $('#inventoryName').val('Sample Item');
    $('#inventoryQuantity').val('100');
    $('#inventoryFarmId').val('FARM01');
    
    $('#inventoryModal').modal('show');
}

function confirmDelete(button) {
    const row = $(button).closest('tr');
    const itemId = row.find('td:first').text();
    const itemName = row.find('td:nth-child(4)').text();
    
    $('#confirmDeleteModal').modal('show');
    
    $('#confirmDeleteBtn').off('click').on('click', function() {
        // In real app, this would be an AJAX call to delete the item
        row.fadeOut(400, function() {
            row.remove();
            $('#confirmDeleteModal').modal('hide');
        });
    });
}

function saveInventoryItem() {
    const formData = new FormData($('#inventoryForm')[0]);
    
    // In real app, this would be an AJAX call to save the item
    console.log('Saving inventory item:', Object.fromEntries(formData));
    
    // Show success message
    alert('Inventory item saved successfully!');
    $('#inventoryModal').modal('hide');
    
    // Refresh the table (in real app, this would reload from server)
    location.reload();
}

function loadInventoryHistory() {
    const sortOrder = $('#sortHistory').val();
    const filterCategory = $('#filterHistory').val();
    
    // In real app, this would be an AJAX call to load history
    const historyHtml = `
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Action</th>
                        <th>Item</th>
                        <th>Category</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2024-06-15</td>
                        <td><span class="badge badge-success">Added</span></td>
                        <td>Corn Feed</td>
                        <td>Feed</td>
                        <td>150 bags</td>
                    </tr>
                    <tr>
                        <td>2024-06-14</td>
                        <td><span class="badge badge-warning">Updated</span></td>
                        <td>Antibiotic</td>
                        <td>Medicine</td>
                        <td>75 units</td>
                    </tr>
                    <tr>
                        <td>2024-06-13</td>
                        <td><span class="badge badge-danger">Removed</span></td>
                        <td>Old Equipment</td>
                        <td>Equipment</td>
                        <td>2 units</td>
                    </tr>
                </tbody>
            </table>
        </div>
    `;
    
    $('#historyContent').html(historyHtml);
}

// Export functions
function exportCSV() {
    const table = document.getElementById('inventoryTable');
    let csv = 'Inventory ID,Date,Category,Name,Quantity,Farm ID\n';
    
    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length > 0) {
            const rowData = Array.from(cells).map(cell => {
                let text = cell.textContent.trim();
                // Remove action buttons text
                if (cell.querySelector('.btn-group')) {
                    text = '';
                }
                return `"${text}"`;
            }).filter(text => text !== '""');
            csv += rowData.join(',') + '\n';
        }
    });
    
    downloadCSV(csv, 'inventory_export.csv');
}

function exportPDF() {
    // PDF export functionality would be implemented here
    alert('PDF export functionality coming soon!');
}

function exportPNG() {
    const tableElement = document.getElementById('inventoryTable');
    html2canvas(tableElement, {
        scale: 2,
        useCORS: true,
        allowTaint: true
    }).then(canvas => {
        const link = document.createElement('a');
        link.href = canvas.toDataURL('image/png');
        link.download = 'inventory_table.png';
        link.click();
    });
}

function downloadCSV(csv, filename) {
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

function printInventory() {
    window.print();
}

function importCSV(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const csv = e.target.result;
            // Process CSV import (in real app, this would parse and validate the data)
            alert('CSV import functionality coming soon!');
        };
        reader.readAsText(file);
    }
}
</script>
@endpush

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
    position: relative;
    overflow: hidden;
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

.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.stat-card.primary {
    border-left: 4px solid var(--primary-color);
}

.stat-card.success {
    border-left: 4px solid var(--success-color);
}

.stat-card.info {
    border-left: 4px solid var(--info-color);
}

.stat-card.warning {
    border-left: 4px solid var(--warning-color);
}

.stat-card .card-body {
    padding: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.stat-info h6 {
    margin: 0 0 0.5rem 0;
    color: var(--dark-color);
    font-size: 0.9rem;
    font-weight: 500;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
}

.stat-icon {
    font-size: 2.5rem;
    opacity: 0.8;
}

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
    flex-wrap: wrap;
    gap: 1rem;
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

.table-controls {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.search-container {
    min-width: 200px;
}

.custom-search {
    border-radius: 20px;
    border: none;
    padding: 0.5rem 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.export-controls {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

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

.category-badge {
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    display: inline-block;
}

.category-feed {
    background: rgba(28, 200, 138, 0.1);
    color: var(--success-color);
}

.category-medicine {
    background: rgba(54, 185, 204, 0.1);
    color: var(--info-color);
}

.category-equipment {
    background: rgba(246, 194, 62, 0.1);
    color: var(--warning-color);
}

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

.empty-state h5 {
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.empty-state p {
    opacity: 0.7;
    margin: 0;
}

.fade-in {
    animation: fadeIn 0.6s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* DataTables customization */
.dataTables_wrapper .dataTables_filter input {
    border-radius: 20px;
    border: 1px solid var(--border-color);
    padding: 0.5rem 1rem;
}

.dataTables_wrapper .dataTables_length select {
    border-radius: 8px;
    border: 1px solid var(--border-color);
    padding: 0.25rem 0.5rem;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 8px;
    margin: 0 0.25rem;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
}

@media (max-width: 768px) {
    .stats-container {
        grid-template-columns: 1fr;
    }
    
    .card-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .table-controls {
        flex-direction: column;
        align-items: stretch;
    }
    
    .export-controls {
        flex-wrap: wrap;
        justify-content: center;
    }
}
</style>
@endpush
