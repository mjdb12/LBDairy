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
        <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addItemModal">
                <i class="fas fa-plus"></i> Add New Item
            </button>
            <button class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-download"></i> Export
            </button>
            <button class="btn btn-outline-info btn-sm">
                <i class="fas fa-bell"></i> Set Alerts
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventoryData as $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <i class="fas fa-{{ $item['icon'] }} fa-2x text-{{ $item['color'] }}"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold">{{ $item['name'] }}</div>
                                    <small class="text-muted">{{ $item['description'] }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge badge-{{ $item['color'] }}">{{ $item['category'] }}</span></td>
                        <td>{{ number_format($item['quantity']) }}</td>
                        <td>{{ $item['unit'] }}</td>
                        <td>
                            <span class="badge badge-{{ $item['status'] == 'In Stock' ? 'success' : ($item['status'] == 'Low Stock' ? 'warning' : 'danger') }}">
                                {{ $item['status'] }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="viewItem('{{ $item['id'] }}')">View</button>
                            <button class="btn btn-sm btn-outline-info" onclick="editItem('{{ $item['id'] }}')">Edit</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-boxes fa-3x mb-3 text-muted"></i>
                            <p>No inventory items available.</p>
                        </td>
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
            @forelse($inventoryData->whereIn('status', ['Low Stock', 'Out of Stock']) as $item)
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
@endsection

@push('styles')
<style>
.page-header {
    background: linear-gradient(135deg, #4e73df 0%, #3c5aa6 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form submission
    document.getElementById('addItemForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show success message
        const notification = document.createElement('div');
        notification.className = 'alert alert-success alert-dismissible fade show position-fixed';
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="fas fa-check-circle"></i>
            Inventory item added successfully!
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;
        
        document.body.appendChild(notification);
        
        // Close modal
        $('#addItemModal').modal('hide');
        
        // Reset form
        this.reset();
        
        // Remove notification after 5 seconds
        setTimeout(() => {
            notification.remove();
        }, 5000);
    });
});
</script>
@endpush
