@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers - Inventory')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fa fa-list"></i>
        Inventory Management
    </h1>
    <p>Track and manage your farm supplies, feed, and equipment inventory</p>
</div>

<div class="row">
    <!-- Inventory Statistics -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 fade-in">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Items</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">156</div>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">142</div>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">6</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Inventory List -->
    <div class="col-lg-8">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Inventory Items</h6>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addItemModal">
                    <i class="fas fa-plus"></i> Add New Item
                </button>
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
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <i class="fas fa-seedling fa-2x text-success"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">Premium Feed Mix</div>
                                            <small class="text-muted">High protein content</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-primary">Feed</span></td>
                                <td>250</td>
                                <td>kg</td>
                                <td><span class="badge badge-success">In Stock</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                    <button class="btn btn-sm btn-outline-info">Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <i class="fas fa-pills fa-2x text-info"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">Vitamin Supplements</div>
                                            <small class="text-muted">Multi-vitamin for livestock</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-info">Medicine</span></td>
                                <td>45</td>
                                <td>packs</td>
                                <td><span class="badge badge-warning">Low Stock</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                    <button class="btn btn-sm btn-outline-info">Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <i class="fas fa-tools fa-2x text-warning"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">Milking Equipment</div>
                                            <small class="text-muted">Automatic milking system</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-warning">Equipment</span></td>
                                <td>2</td>
                                <td>units</td>
                                <td><span class="badge badge-success">In Stock</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                    <button class="btn btn-sm btn-outline-info">Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <i class="fas fa-seedling fa-2x text-success"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">Hay Bales</div>
                                            <small class="text-muted">Fresh grass hay</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-primary">Feed</span></td>
                                <td>0</td>
                                <td>bales</td>
                                <td><span class="badge badge-danger">Out of Stock</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                    <button class="btn btn-sm btn-outline-info">Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <i class="fas fa-pills fa-2x text-info"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">Antibiotics</div>
                                            <small class="text-muted">Broad spectrum antibiotics</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-info">Medicine</span></td>
                                <td>12</td>
                                <td>vials</td>
                                <td><span class="badge badge-warning">Low Stock</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                    <button class="btn btn-sm btn-outline-info">Edit</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Analytics -->
    <div class="col-lg-4">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-pie"></i>
                    Inventory by Category
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="inventoryChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i> Feed
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-info"></i> Medicine
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-warning"></i> Equipment
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-secondary"></i> Others
                    </span>
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
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="font-weight-bold">Vitamin Supplements</div>
                            <small class="text-muted">45 packs remaining</small>
                        </div>
                        <span class="badge badge-warning badge-pill">Low</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="font-weight-bold">Antibiotics</div>
                            <small class="text-muted">12 vials remaining</small>
                        </div>
                        <span class="badge badge-warning badge-pill">Low</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="font-weight-bold">Hay Bales</div>
                            <small class="text-muted">Out of stock</small>
                        </div>
                        <span class="badge badge-danger badge-pill">Critical</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-tools"></i>
                    Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <button class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-plus"></i> Add New Item
                </button>
                <button class="btn btn-outline-secondary btn-block mb-2">
                    <i class="fas fa-download"></i> Export Inventory
                </button>
                <button class="btn btn-outline-info btn-block">
                    <i class="fas fa-bell"></i> Set Alerts
                </button>
            </div>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inventory Chart
    const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
    const inventoryChart = new Chart(inventoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Feed', 'Medicine', 'Equipment', 'Others'],
            datasets: [{
                data: [65, 25, 8, 2],
                backgroundColor: ['#4e73df', '#36b9cc', '#f6c23e', '#6c757d'],
                hoverBackgroundColor: ['#2e59d9', '#2c9faf', '#f4b619', '#545b62'],
                hoverBorderColor: 'rgba(234, 236, 244, 1)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });

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
