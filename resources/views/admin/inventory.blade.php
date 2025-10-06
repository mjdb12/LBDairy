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
                                <div class="action-buttons">
                                    <button class="btn-action btn-action-edit" onclick="editInventoryItem('{{ $item->id }}')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                        <span>Edit</span>
                                    </button>
                                    <button class="btn-action btn-action-delete" onclick="confirmDelete(this)" title="Delete">
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
                            <td class="text-center text-muted">No inventory items found</td>
                            <td class="text-center text-muted">N/A</td>
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
    
    $.get(`/admin/inventory/${itemId}`, function(response) {
        if (response && response.success && response.item) {
            const item = response.item;
            $('#inventoryId').val(item.code || itemId);
            $('#inventoryDate').val(item.date || '');
            $('#inventoryCategory').val(item.category || '');
            $('#inventoryName').val(item.name || '');
            $('#inventoryQuantity').val(item.quantity_text || item.quantity || '');
            $('#inventoryFarmId').val(item.farm_id || '');
            $('#inventoryModal').modal('show');
        } else {
            showNotification('Failed to load inventory item', 'error');
        }
    }).fail(function() {
        showNotification('Failed to load inventory item', 'error');
    });
}

function confirmDelete(button) {
    const row = $(button).closest('tr');
    const itemId = row.find('td:first').text();
    const itemName = row.find('td:nth-child(4)').text();
    
    $('#confirmDeleteModal').modal('show');
    
    $('#confirmDeleteBtn').off('click').on('click', function() {
        $.ajax({
            url: `/admin/inventory/${itemId}`,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(resp) {
                if (resp && resp.success) {
                    $('#confirmDeleteModal').modal('hide');
                    location.reload();
                } else {
                    showNotification('Failed to delete inventory item', 'error');
                }
            },
            error: function() {
                showNotification('Failed to delete inventory item', 'error');
            }
        });
    });
}

function saveInventoryItem() {
    const formData = new FormData($('#inventoryForm')[0]);
    const payload = Object.fromEntries(formData);
    const isEdit = !!currentEditId;
    const url = isEdit ? `/admin/inventory/${currentEditId}` : '/admin/inventory';
    const method = isEdit ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        type: method,
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        data: {
            inventoryId: payload.inventoryId,
            inventoryDate: payload.inventoryDate,
            inventoryCategory: payload.inventoryCategory,
            inventoryName: payload.inventoryName,
            inventoryQuantity: payload.inventoryQuantity,
            inventoryFarmId: payload.inventoryFarmId
        },
        success: function(resp) {
            if (resp && resp.success) {
                showNotification('Inventory item saved successfully!', 'success');
                $('#inventoryModal').modal('hide');
                setTimeout(() => location.reload(), 500);
            } else {
                showNotification('Failed to save inventory item', 'error');
            }
        },
        error: function() {
            showNotification('Failed to save inventory item', 'error');
        }
    });
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
    // Get current table data without actions column
    const tableData = inventoryTable.data().toArray();
    const csvData = [];
    
    // Add headers (excluding Actions column)
    const headers = ['Inventory ID', 'Date', 'Category', 'Name', 'Quantity', 'Farm ID', 'Status'];
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
    link.setAttribute('download', `Admin_InventoryReport_${downloadCounter}.csv`);
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
        
        const tableData = inventoryTable.data().toArray();
        const pdfData = [];
        
        const headers = ['Item ID', 'Name', 'Category', 'Quantity', 'Unit Price', 'Total Value', 'Status'];
        
        tableData.forEach(row => {
            const rowData = [
                row[0] || '', // Item ID
                row[1] || '', // Name
                row[2] || '', // Category
                row[3] || '', // Quantity
                row[4] || '', // Unit Price
                row[5] || '',  // Total Value
                row[6] || ''   // Status
            ];
            pdfData.push(rowData);
        });
        
        // Create PDF using jsPDF - Match superadmin styling exactly
        const { jsPDF } = window.jsPDF;
        const doc = new jsPDF('landscape');
        
        // Add title - Match superadmin styling exactly
        doc.setFontSize(18);
        doc.setFont(undefined, 'bold');
        doc.text('Admin Inventory Report', 148, 20, { align: 'center' });
        
        doc.setFontSize(12);
        doc.setFont(undefined, 'normal');
        doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 148, 30, { align: 'center' });
        
        // Add table - Match superadmin styling exactly
        doc.autoTable({
            head: [headers],
            body: pdfData,
            startY: 40,
            styles: { fontSize: 8, cellPadding: 2 },
            headStyles: { fillColor: [24, 55, 93], textColor: 255 },
            columnStyles: {
                0: { cellWidth: 25 }, // Item ID
                1: { cellWidth: 30 }, // Name
                2: { cellWidth: 25 }, // Category
                3: { cellWidth: 20 }, // Quantity
                4: { cellWidth: 25 }, // Unit Price
                5: { cellWidth: 25 }, // Total Value
                6: { cellWidth: 20 }  // Status
            }
        });
        
        // Save the PDF
        doc.save(`Admin_InventoryReport_${downloadCounter}.pdf`);
        
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
    const originalTable = document.getElementById('inventoryTable');
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
        link.download = `Admin_InventoryReport_${downloadCounter}.png`;
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

.page-header {
    background: var(--primary-color);
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

.table tbody tr {
    transition: all 0.2s ease;
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

.btn-group .btn {
    margin-right: 0.25rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
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

.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    overflow: hidden;
}

.modal-body {
    padding: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 0.5rem;
}

.form-control {
    border-radius: 8px;
    border: 1px solid var(--border-color);
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    transform: translateY(-1px);
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
    
    .page-header h1 {
        font-size: 1.5rem;
    }
    
    .page-header p {
        font-size: 1rem;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .stat-icon {
        font-size: 2rem;
    }
}

@media (max-width: 576px) {
    .page-header {
        padding: 1.5rem;
    }
    
    .page-header h1 {
        font-size: 1.25rem;
        flex-direction: column;
        text-align: center;
    }
    
    .table th,
    .table td {
        padding: 0.5rem 0.25rem;
        font-size: 0.8rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .category-badge {
        font-size: 0.7rem;
        padding: 0.3rem 0.6rem;
    }
}
</style>
@endpush
