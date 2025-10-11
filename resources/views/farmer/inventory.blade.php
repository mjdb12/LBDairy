@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers - Inventory')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-boxes"></i>
        Inventory Management
    </h1>
    <p>Track and manage your farm supplies, feed, and equipment inventory</p>
</div>

<div class="row mb-4">
    <!-- Inventory Statistics -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 fade-in">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Items</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalItems }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-boxes fa-2x text-gray-300"></i>
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
                            In Stock</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $inStock }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            Low Stock</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStock }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2 fade-in">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Out of Stock</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $outOfStock }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inventory List -->
<div class="card shadow mb-4 fade-in">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Inventory Items</h6>
    </div>
    <div class="card-body">
        <!-- Search (left) + Actions (right) -->
        <div class="search-controls mb-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch">
                <div class="input-group" style="max-width: 380px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <input type="text" id="inventorySearch" class="form-control" placeholder="Search inventory...">
                </div>
                <div class="btn-group d-flex gap-2 align-items-center mt-2 mt-sm-0">
                    <button id="btnAddItem" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addItemModal">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
                    <button id="btnInventoryHistory" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#inventoryHistoryModal">
                        <i class="fas fa-history"></i> History
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="printInventory()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" onclick="refreshInventory()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportInventoryCSV()">
                                <i class="fas fa-file-csv"></i> Download CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportInventoryPNG()">
                                <i class="fas fa-image"></i> Download PNG
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportInventoryPDF()">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventory as $item)
                    <tr>
                        <td>{{ $item->code }}</td>
                        <td>{{ optional($item->date)->format('Y-m-d') }}</td>
                        <td>{{ $item->category }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->quantity_text }}</td>
                        <td>
                            <div class="action-buttons">
                                <button type="button" class="btn-action btn-action-view" data-id="{{ $item->id }}" title="View">
                                    <i class="fas fa-eye"></i>
                                    <span>View</span>
                                </button>
                                <button type="button" class="btn-action btn-action-edit" data-id="{{ $item->id }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                    <span>Edit</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center text-muted">N/A</td>
                        <td class="text-center text-muted">N/A</td>
                        <td class="text-center text-muted">N/A</td>
                        <td class="text-center text-muted">No inventory items available</td>
                        <td class="text-center text-muted">N/A</td>
                        <td class="text-center text-muted">N/A</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>



<!-- Low Stock Alerts -->
<div class="card shadow mb-4 fade-in">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-exclamation-triangle"></i>
            Low Stock Alerts
        </h6>
    </div>
    <div class="card-body">
        <div class="list-group list-group-flush">
            @forelse(collect($inventoryData)->whereIn('status', ['Low Stock', 'Out of Stock']) as $item)
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <div class="font-weight-bold">{{ $item['name'] }}</div>
                    <small class="text-muted">{{ $item['quantity'] }} {{ $item['unit'] }} remaining</small>
                </div>
                <span class="badge badge-{{ $item['status'] == 'Out of Stock' ? 'danger' : 'warning' }} badge-pill">
                    {{ $item['status'] == 'Out of Stock' ? 'Critical' : 'Low' }}
                </span>
            </div>
            @empty
            <div class="list-group-item text-center text-muted">
                <i class="fas fa-check-circle fa-2x mb-2"></i>
                <p>No low stock alerts</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">
                    <i class="fas fa-plus"></i>
                    Add New Inventory Item
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addItemForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="itemName">Item Name *</label>
                                <input type="text" class="form-control" id="itemName" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="itemCategory">Category *</label>
                                <select class="form-control" id="itemCategory" required>
                                    <option value="">Select Category</option>
                                    <option value="feed">Feed</option>
                                    <option value="medicine">Medicine</option>
                                    <option value="equipment">Equipment</option>
                                    <option value="tools">Tools</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="itemQuantity">Quantity *</label>
                                <input type="number" class="form-control" id="itemQuantity" required min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="itemUnit">Unit *</label>
                                <select class="form-control" id="itemUnit" required>
                                    <option value="">Select Unit</option>
                                    <option value="kg">Kilograms (kg)</option>
                                    <option value="g">Grams (g)</option>
                                    <option value="l">Liters (L)</option>
                                    <option value="ml">Milliliters (ml)</option>
                                    <option value="pcs">Pieces (pcs)</option>
                                    <option value="packs">Packs</option>
                                    <option value="units">Units</option>
                                    <option value="bales">Bales</option>
                                    <option value="vials">Vials</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="itemPrice">Unit Price (₱)</label>
                                <input type="number" class="form-control" id="itemPrice" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="itemSupplier">Supplier</label>
                                <input type="text" class="form-control" id="itemSupplier">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="itemDescription">Description</label>
                                <textarea class="form-control" id="itemDescription" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="itemMinStock">Minimum Stock Level</label>
                                <input type="number" class="form-control" id="itemMinStock" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="itemLocation">Storage Location</label>
                                <input type="text" class="form-control" id="itemLocation">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Inventory Details Modal -->
<div class="modal fade" id="inventoryDetailsModal" tabindex="-1" role="dialog" aria-labelledby="inventoryDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inventoryDetailsLabel">
                    <i class="fas fa-info-circle"></i>
                    Item Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="inventoryDetailsContent"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="beginEditCurrentItem()">
                    <i class="fas fa-edit"></i> Edit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Inventory History Modal -->
<div class="modal fade" id="inventoryHistoryModal" tabindex="-1" role="dialog" aria-labelledby="inventoryHistoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inventoryHistoryLabel">
                    <i class="fas fa-history"></i>
                    Inventory History (Quarterly)
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="inventoryYear" class="font-weight-bold">Year:</label>
                        <select id="inventoryYear" class="form-control form-control-sm" onchange="loadInventoryHistory()">
                            @php($currentYear = (int)date('Y'))
                            @for($y = $currentYear; $y >= $currentYear - 5; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="text-muted small">Showing quarterly aggregates</div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="inventoryHistoryQuarterTable">
                        <thead>
                            <tr>
                                <th>Quarter</th>
                                <th>Items</th>
                                <th>Total Cost (₱)</th>
                            </tr>
                        </thead>
                        <tbody id="inventoryHistoryTableBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="exportInventoryHistory()">
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


.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
}

.card-header {
    background: linear-gradient(135deg, #f8f9fc 0%, #eaecf4 100%);
    border-bottom: 1px solid #e3e6f0;
    border-radius: 12px 12px 0 0 !important;
}

.table {
    margin-bottom: 0;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #5a5c69;
    background-color: #f8f9fc;
    padding: 1rem 0.75rem;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
    border-color: #e3e6f0;
    padding: 1rem 0.75rem;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(78, 115, 223, 0.05);
    transform: scale(1.001);
}

.badge {
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.4rem 0.8rem;
}

.badge-primary {
    background: linear-gradient(135deg, #4e73df, #3c5aa6);
}

.badge-success {
    background: linear-gradient(135deg, #1cc88a, #17a673);
}

.badge-warning {
    background: linear-gradient(135deg, #f6c23e, #f4b619);
}

.badge-info {
    background: linear-gradient(135deg, #36b9cc, #2a96a5);
}

.badge-danger {
    background: linear-gradient(135deg, #e74a3b, #d52a1a);
}

.list-group-item {
    border: none;
    border-bottom: 1px solid #e3e6f0;
    padding: 1rem;
}

.list-group-item:last-child {
    border-bottom: none;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
    border: none;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-sm {
    padding: 0.25rem 0.75rem;
    font-size: 0.875rem;
}

.gap-2 {
    gap: 0.5rem;
}

.card-header .d-flex.gap-2 {
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .card-header .d-flex.gap-2 {
        margin-top: 1rem;
        width: 100%;
        justify-content: center;
    }
    
    .card-header .d-flex.gap-2 .btn {
        flex: 1;
        min-width: 120px;
    }
}

.chart-pie {
    position: relative;
    height: 300px;
    margin: 0 auto;
}

.chart-area {
    position: relative;
    height: 300px;
}

.list-group-item {
    border: none;
    border-bottom: 1px solid #e3e6f0;
    padding: 1rem;
    transition: all 0.2s ease;
}

.list-group-item:hover {
    background-color: rgba(78, 115, 223, 0.05);
}

.list-group-item:last-child {
    border-bottom: none;
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
    color: #5a5c69;
    margin-bottom: 0.5rem;
}

.modal-header {
    background: linear-gradient(135deg, #4e73df 0%, #3c5aa6 100%);
    color: white;
    border-radius: 12px 12px 0 0;
}

.modal-header .close {
    color: white;
    opacity: 0.8;
}

.modal-header .close:hover {
    opacity: 1;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #d1d3e2;
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    transform: translateY(-1px);
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #858796;
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

@media (max-width: 768px) {
    .page-header h1 {
        font-size: 1.5rem;
    }
    
    .page-header p {
        font-size: 1rem;
    }
    
    .card-header {
        padding: 1rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .col-xl-3 {
        margin-bottom: 1rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
    
    .d-flex.align-items-center {
        flex-direction: column;
        text-align: center;
    }
    
    .d-flex.align-items-center .mr-3 {
        margin-right: 0 !important;
        margin-bottom: 0.5rem;
    }
    
    .chart-pie {
        height: 250px;
    }
    
    .col-lg-6 {
        margin-bottom: 1rem;
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
    
    .badge {
        font-size: 0.7rem;
        padding: 0.3rem 0.6rem;
    }
}
</style>
@endpush

@push('scripts')
<!-- DataTables & exports -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
let inventoryDT = null;
let CURRENT_ITEM_ID = null;
let CURRENT_ITEM = null;

$(document).ready(function(){
    // Init DataTable
    const commonConfig = {
        dom: 'Bfrtip',
        searching: true,
        paging: true,
        info: true,
        ordering: true,
        lengthChange: false,
        pageLength: 10,
        buttons: [
            { extend: 'csvHtml5', title: 'Farmer_Inventory', className: 'd-none' },
            { extend: 'pdfHtml5', title: 'Farmer_Inventory', orientation: 'landscape', pageSize: 'Letter', className: 'd-none' },
            { extend: 'print', title: 'Farmer Inventory', className: 'd-none' }
        ],
        language: { search: "", emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No data available</h5><p>There are no records to display at this time.</p></div>' }
    };
    try {
        inventoryDT = $('#dataTable').DataTable({
            ...commonConfig,
            columnDefs: [
                { width: '320px', targets: 0 },
                { width: '140px', targets: 1 },
                { width: '120px', targets: 2 },
                { width: '120px', targets: 3 },
                { width: '140px', targets: 4 },
                { width: '200px', targets: 5, orderable: false }
            ]
        });
    } catch(e){ console.error('Inventory DataTable init error:', e); }

    // Hide default search/buttons; wire custom search
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
    $('#inventorySearch').on('keyup', function(){ if (inventoryDT) inventoryDT.search(this.value).draw(); });

    // Load quarterly history on modal open
    $('#inventoryHistoryModal').on('shown.bs.modal', function(){ loadInventoryHistory(); });

    // Add/Edit item form (AJAX to backend)
    document.getElementById('addItemForm').addEventListener('submit', function(e){
        e.preventDefault();
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const isEdit = !!CURRENT_ITEM_ID;
        const payload = {
            name: $('#itemName').val(),
            category: $('#itemCategory').val(),
            quantity: $('#itemQuantity').val(),
            unit: $('#itemUnit').val()
        };
        const url = isEdit ? `/farmer/inventory/${CURRENT_ITEM_ID}` : '/farmer/inventory';
        const method = isEdit ? 'PUT' : 'POST';
        $.ajax({
            url: url,
            type: method,
            headers: { 'X-CSRF-TOKEN': token },
            data: payload,
            success: function(resp){
                if (resp && resp.success){
                    $('#addItemModal').modal('hide');
                    showToast(isEdit ? 'Inventory item updated.' : 'Inventory item added.', 'success');
                    setTimeout(() => location.reload(), 600);
                } else {
                    showToast('Failed to save item.', 'error');
                }
            },
            error: function(){ showToast('Failed to save item.', 'error'); }
        });
    });

    // Delegated handlers to ensure reliability
    $(document).on('click', '#btnAddItem', function(e){
        e.preventDefault();
        $('#addItemModal').modal('show');
    });

    $(document).on('click', '#btnInventoryHistory', function(e){
        e.preventDefault();
        $('#inventoryHistoryModal').modal('show');
        try { loadInventoryHistory(); } catch(err){ console.error('loadInventoryHistory error:', err); }
    });

    $(document).on('click', '.btn-action-view', function(e){
        e.preventDefault();
        const id = $(this).data('id');
        if (typeof id !== 'undefined') viewItem(id);
    });

    $(document).on('click', '.btn-action-edit', function(e){
        e.preventDefault();
        const id = $(this).data('id');
        if (typeof id !== 'undefined') editItem(id);
    });
});

function viewItem(id){
    CURRENT_ITEM_ID = Number(id);
    $.get(`/farmer/inventory/${CURRENT_ITEM_ID}`, function(resp){
        if (resp && resp.success && resp.item){
            CURRENT_ITEM = resp.item;
            const item = resp.item;
            $('#inventoryDetailsContent').html(`
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr><td><strong>Code:</strong></td><td>${item.code || ''}</td></tr>
                            <tr><td><strong>Name:</strong></td><td>${item.name || ''}</td></tr>
                            <tr><td><strong>Category:</strong></td><td>${item.category || ''}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr><td><strong>Date:</strong></td><td>${item.date || ''}</td></tr>
                            <tr><td><strong>Quantity:</strong></td><td>${item.quantity_text || ''}</td></tr>
                            <tr><td><strong>Farm ID:</strong></td><td>${item.farm_id || ''}</td></tr>
                        </table>
                    </div>
                </div>
            `);
            $('#inventoryDetailsModal').modal('show');
        } else {
            showToast('Item not found', 'error');
        }
    }).fail(function(){ showToast('Failed to load item.', 'error'); });
}

function beginEditCurrentItem(){
    if (!CURRENT_ITEM || !CURRENT_ITEM_ID) return;
    $('#inventoryDetailsModal').modal('hide');
    fillFormFromItem(CURRENT_ITEM);
    $('#addItemModal').modal('show');
}

function editItem(id){
    CURRENT_ITEM_ID = Number(id);
    $.get(`/farmer/inventory/${CURRENT_ITEM_ID}`, function(resp){
        if (resp && resp.success && resp.item){
            CURRENT_ITEM = resp.item;
            fillFormFromItem(resp.item);
            $('#addItemModal').modal('show');
        } else {
            showToast('Item not found', 'error');
        }
    }).fail(function(){ showToast('Failed to load item.', 'error'); });
}

function fillFormFromItem(item){
    $('#itemName').val(item.name || '');
    $('#itemCategory').val(String(item.category || '').toLowerCase());
    const parsed = parseQuantityText(item.quantity_text || '');
    $('#itemQuantity').val(parsed.quantity || '');
    $('#itemUnit').val(parsed.unit || '');
    // Keep CURRENT_ITEM_ID for submit to use PUT
}

function parseQuantityText(qt){
    if (!qt) return { quantity: '', unit: '' };
    const parts = String(qt).trim().split(/\s+/);
    if (parts.length === 0) return { quantity: '', unit: '' };
    const qty = parseFloat(parts[0]);
    if (!isNaN(qty)){
        return { quantity: qty, unit: parts.slice(1).join(' ') };
    }
    return { quantity: '', unit: qt };
}

function printInventory(){
    try { if (inventoryDT) inventoryDT.button('.buttons-print').trigger(); else window.print(); }
    catch(e){ console.error('printInventory error:', e); window.print(); }
}

function refreshInventory(){
    const btn = document.querySelector('.btn.btn-warning.btn-sm');
    if (btn){ btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...'; }
    sessionStorage.setItem('showRefreshNotificationInventory','true');
    setTimeout(()=>location.reload(), 800);
}

function exportInventoryCSV(){
    try {
        if (!inventoryDT) return showToast('Table is not ready.', 'error');
        const rows = inventoryDT.data().toArray();
        const headers = ['Item Name','Category','Quantity','Unit','Status'];
        const csv = [headers.join(',')];
        rows.forEach(r => {
            const arr = [];
            for (let i = 0; i < r.length - 1; i++) {
                const tmp = document.createElement('div'); tmp.innerHTML = r[i];
                let t = tmp.textContent || tmp.innerText || '';
                t = t.replace(/\s+/g, ' ').trim();
                if (t.includes(',') || t.includes('"') || t.includes('\n')) t = '"' + t.replace(/"/g, '""') + '"';
                arr.push(t);
            }
            csv.push(arr.join(','));
        });
        const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
        const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = `Farmer_Inventory_${Date.now()}.csv`; a.click();
        showToast('CSV exported successfully!', 'success');
    } catch(e){ console.error('CSV export error:', e); showToast('Error generating CSV.', 'error'); }
}

function exportInventoryPDF(){
    try {
        if (!inventoryDT) return showToast('Table is not ready.', 'error');
        const rows = inventoryDT.data().toArray();
        const data = rows.map(r => [r[0]||'', r[1]||'', r[2]||'', r[3]||'', r[4]||'']);
        const headers = ['Item Name','Category','Quantity','Unit','Status'];
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape', 'mm', 'a4');
        doc.setFontSize(18);
        doc.text('Farmer Inventory', 14, 22);
        doc.setFontSize(12);
        doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 14, 32);
        doc.autoTable({ head: [headers], body: data, startY: 40, styles: { fontSize: 8, cellPadding: 2 }, headStyles: { fillColor: [24,55,93], textColor: 255, fontStyle: 'bold' }, alternateRowStyles: { fillColor: [245,245,245] } });
        doc.save(`Farmer_Inventory_${Date.now()}.pdf`);
        showToast('PDF exported successfully!', 'success');
    } catch(e){ console.error('PDF export error:', e); showToast('Error generating PDF.', 'error'); }
}

function exportInventoryPNG(){
    const originalTable = document.getElementById('dataTable');
    const tempTable = originalTable.cloneNode(true);
    // Remove Actions column
    const headerRow = tempTable.querySelector('thead tr'); if (headerRow && headerRow.lastElementChild) headerRow.lastElementChild.remove();
    tempTable.querySelectorAll('tbody tr').forEach(tr => { if (tr.lastElementChild) tr.lastElementChild.remove(); });
    const offscreen = document.createElement('div'); offscreen.style.position = 'absolute'; offscreen.style.left = '-9999px'; offscreen.style.top = '0'; offscreen.style.background = '#fff'; offscreen.appendChild(tempTable); document.body.appendChild(offscreen);
    html2canvas(tempTable, { scale: 2, backgroundColor: '#fff', useCORS: true, logging: false }).then(canvas => {
        const a = document.createElement('a'); a.download = `Farmer_Inventory_${Date.now()}.png`; a.href = canvas.toDataURL('image/png'); a.click(); document.body.removeChild(offscreen); showToast('PNG exported successfully!', 'success');
    }).catch(err => { console.error('PNG export error:', err); if (document.body.contains(offscreen)) document.body.removeChild(offscreen); showToast('Error generating PNG export', 'error'); });
}

function loadInventoryHistory(){
    const year = document.getElementById('inventoryYear').value;
    fetch(`/farmer/inventory/history?year=${year}`)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.mode === 'quarterly'){
                const tbody = document.getElementById('inventoryHistoryTableBody');
                tbody.innerHTML = '';
                const quarters = Array.isArray(data.quarters) ? data.quarters : [];
                if (quarters.length){
                    quarters.forEach(q => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `<td>Q${q.quarter} ${q.year}</td><td>${q.items}</td><td>₱${Number(q.total_cost).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>`;
                        tbody.appendChild(tr);
                    });
                } else {
                    const tr = document.createElement('tr'); tr.innerHTML = '<td colspan="3" class="text-center text-muted">No quarterly data for the selected year.</td>'; tbody.appendChild(tr);
                }
            }
        })
        .catch(err => { console.error('inventory history error:', err); showToast('Failed to load history', 'error'); });
}

function exportInventoryHistory(){
    try {
        const year = document.getElementById('inventoryYear').value;
        const rows = [];
        rows.push(['Quarter','Items','Total Cost (PHP)'].join(','));
        document.querySelectorAll('#inventoryHistoryQuarterTable tbody tr').forEach(tr => {
            const cells = Array.from(tr.querySelectorAll('td')).map(td => (td.textContent||'').trim());
            if (cells.length === 3) rows.push(cells.join(','));
        });
        const blob = new Blob([rows.join('\n')], { type: 'text/csv;charset=utf-8;' });
        const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = `Inventory_Quarterly_${year}.csv`; a.click();
        showToast('Quarterly history exported successfully!', 'success');
    } catch(e) { console.error('exportInventoryHistory error:', e); showToast('Failed to export history.', 'error'); }
}

function showToast(message, type = 'info'){
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `${message}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>`;
    document.body.appendChild(alertDiv);
    setTimeout(()=>{ if (alertDiv && alertDiv.parentNode) alertDiv.remove(); }, 5000);
}
</script>
@endpush
