@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers - Inventory')

@section('content')
<!-- Page Header -->
<div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
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
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">
                            Total Items</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalItems }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-boxes fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 fade-in">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">
                            In Stock</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $inStock }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 fade-in">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">
                            Low Stock</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStock }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 fade-in">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">
                            Out of Stock</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $outOfStock }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inventory List -->
<div class="card shadow mb-4 fade-in">
    <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
        <h6 class="m-0 font-weight-bold ">Inventory Items</h6>
    </div>
    <div class="card-body">
        <div class="search-controls mb-3">
                <div class="input-group" style="max-width: 300px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <input type="text" id="inventorySearch" class="form-control" placeholder="Search inventory...">
                </div>
               <div class="d-flex align-items-center justify-content-center flex-nowrap gap-2 action-toolbar">
                    <button class="btn-action btn-action-ok" data-toggle="modal" data-target="#addItemModal">
                    <i class="fas fa-plus mr-1"></i> Add Item
                    </button>
                    <button class="btn-action btn-action-refresh" onclick="refreshInventory()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tool" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#inventoryHistoryModal">
                                <i class="fas fa-history"></i> History
                            </a>
                            <a class="dropdown-item" href="#" onclick="printInventory()">
                                <i class="fas fa-print"></i> Print Table
                            </a>
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
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-light">
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
                    <tr data-item-id="{{ $item->id }}">
                        <td>{{ $item->code }}</td>
                        <td>{{ optional($item->date)->format('Y-m-d') }}</td>
                        <td>{{ $item->category }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->quantity_text }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn-action btn-action-ok" data-id="{{ $item->id }}" title="View">
                                    <i class="fas fa-eye"></i>
                                    <span>View</span>
                                </button>
                                <button type="button" class="btn-action btn-action-edit" data-id="{{ $item->id }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                    <span>Edit</span>
                                </button>
                                <button type="button" class="btn-action btn-action-deletes" data-id="{{ $item->id }}" title="Delete">
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

<!-- Modern Approve Farmer Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content smart-modal text-center p-4">
            <!-- Icon -->
            <div class="icon-wrapper mx-auto mb-4 text-danger">
                <i class="fas fa-check-circle fa-2x"></i>
            </div>
            <!-- Title -->
            <h5>Confirm Delete </h5>
            <!-- Description -->
            <p class="text-muted mb-4 px-3">
                Are you sure you want to <strong>delete</strong> this inventory record?
            </p>
                <!-- Buttons -->
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-delete" id="confirmDeleteBtn">
                        <i class="fas fa-trash"></i> Yes, Delete
                    </button>
                </div>
            
        </div>
    </div>
</div>

<!-- Low Stock Alerts -->
<div class="card shadow mb-4 fade-in">
    <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
        <h6 class="m-0 font-weight-bold">
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

<!-- Smart Form - Add Inventory Item Modal -->
<div class="modal fade admin-modal" id="addItemModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content smart-form text-center p-4">

      <!-- Icon + Header -->
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle">
          <i class="fas fa-plus-circle fa-2x"></i>
        </div>
        <h5 class="fw-bold mb-1">Inventory Entry</h5>
        <p class="text-muted mb-0 small">
          Enter the item details below to record a new inventory entry.
        </p>
      </div>

      <!-- Form -->
      <form id="addItemForm" onsubmit="submitItem(event)">
        <div class="form-wrapper text-start mx-auto">
          <div class="row g-3">

            <!-- Item Name -->
            <div class="col-md-6">
              <label for="itemName" class="fw-semibold">Item Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="itemName" name="item_name" required>
            </div>

            <!-- Category -->
            <div class="col-md-6">
              <label for="itemCategory" class="fw-semibold">Category <span class="text-danger">*</span></label>
              <select class="form-control" id="itemCategory" name="item_category" required>
                <option value="" disabled selected>Select Category</option>
                <option value="feed">Feed</option>
                <option value="medicine">Medicine</option>
                <option value="equipment">Equipment</option>
                <option value="tools">Tools</option>
                <option value="others">Others</option>
              </select>
            </div>

            <!-- Quantity -->
            <div class="col-md-6">
              <label for="itemQuantity" class="fw-semibold">Quantity <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="itemQuantity" name="item_quantity" min="0" required>
            </div>

            <!-- Unit -->
            <div class="col-md-6">
              <label for="itemUnit" class="fw-semibold">Unit <span class="text-danger">*</span></label>
              <select class="form-control" id="itemUnit" name="item_unit" required>
                <option value="" disabled selected>Select Unit</option>
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

            <!-- Price -->
            <div class="col-md-6">
              <label for="itemPrice" class="fw-semibold">Unit Price (₱)</label>
              <input type="number" class="form-control" id="itemPrice" name="item_price" step="0.01" min="0">
            </div>

            <!-- Supplier -->
            <div class="col-md-6">
              <label for="itemSupplier" class="fw-semibold">Supplier</label>
              <input type="text" class="form-control" id="itemSupplier" name="item_supplier">
            </div>

            <!-- Min Stock -->
            <div class="col-md-6">
              <label for="itemMinStock" class="fw-semibold">Minimum Stock Level</label>
              <input type="number" class="form-control" id="itemMinStock" name="item_min_stock" min="0">
            </div>

            <!-- Location -->
            <div class="col-md-6">
              <label for="itemLocation" class="fw-semibold">Storage Location</label>
              <input type="text" class="form-control" id="itemLocation" name="item_location">
            </div>

            <!-- Description -->
            <div class="col-md-12">
              <label for="itemDescription" class="fw-semibold">Description</label>
              <textarea class="form-control mt-1" id="itemDescription" name="item_description" rows="3" placeholder="Enter item details..." style="resize: none;"></textarea>
            </div>

            <div id="formNotification" class="mt-2" style="display: none;"></div>
          </div>
        </div>

        <!-- Footer Buttons -->
        <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
          <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-modern btn-ok" title="Save Item">
            <i class="fas fa-save"></i> Save
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

    <!-- Smart Detail Modal -->
<div class="modal fade admin-modal" id="inventoryDetailsModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

        <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-info-circle fa-2x "></i>
                </div>
                <h5 class="fw-bold mb-1">Item Details </h5>
                <p class="text-muted mb-0 small text-center">Below are the complete details of the selected entry.</p>
            </div>

      <!-- Body -->
      <div class="modal-body">
        <div id="inventoryDetailsContent" >
          <!-- Dynamic details injected here -->
        </div>
      </div>

      <!-- Footer -->

         <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
            <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
            <button type="button" class="btn-modern btn-ok" onclick="beginEditCurrentItem()">
                <i class="fas fa-edit"></i> Edit
            </button>
        </div>

    </div>
  </div>
</div>

<!-- Smart Detail - Inventory History Modal -->
<div class="modal fade admin-modal" id="inventoryHistoryModal" tabindex="-1" role="dialog" aria-labelledby="inventoryHistoryLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content smart-detail p-4">

      <!-- Icon + Header -->
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle">
          <i class="fas fa-boxes fa-2x"></i>
        </div>
        <h5 class="fw-bold mb-1">Inventory History (Quarterly)</h5>
        <p class="text-muted mb-0 small text-center">
          View, compare, and export quarterly inventory performance data.
        </p>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div class="form-wrapper text-start mx-auto">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="inventoryYear" class="fw-semibold">Year:</label>
              <select id="inventoryYear" class="form-control" onchange="loadInventoryHistory()">
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

          <!-- Table -->
          <div class="table-responsive rounded shadow-sm">
            <table class="table table-hover table-bordered align-middle mb-0">
              <thead class="table-light text-center">
                <tr>
                  <th>Quarter</th>
                  <th>Total Items</th>
                  <th>Total Cost (₱)</th>
                </tr>
              </thead>
              <tbody id="inventoryHistoryTableBody">
                <!-- Quarterly inventory data dynamically loaded here -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Footer -->
       <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
        <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">
          Close
        </button>
        <button type="button" class="btn-modern btn-ok" onclick="exportInventoryHistory()">
          <i class="fas fa-file-export"></i> Export History
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
/* Cancel Button */
.action-toolbar .btn-action-ok {
  border: none;
}

/* Delete Button */
.action-toolbar .btn-action-refresh {
  color: #fff;
  border: none;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}
.action-toolbar .btn-action-tools {
  color: #fff;
  border: none;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

/* Mobile Behavior: stay side-by-side */
@media (max-width: 576px) {
  .action-toolbar {
    flex-direction: row !important;
    justify-content: center;
    gap: 8px;
  }

  .action-toolbar .btn-modern {
    flex: 1 1 auto;
    min-width: 40%;
    text-align: center;
  }
}

    /* Modal Footer Layout */
.modal-footer {
  display: flex !important;
  justify-content: center;
  align-items: center;
  gap: 10px;
  padding: 1rem;
  border-top: none;
  flex-wrap: nowrap !important;
}

/* Cancel Button */
.btn-cancel {
  background-color: #e9ecef;
  color: #333;
  border: none;
  padding: 0.6rem 1.2rem;
  border-radius: 8px;
  transition: all 0.3s ease;
  white-space: nowrap;
}

.btn-cancel:hover {
  background-color: #d6d8db;
}

/* Confirm Delete Button */
#confirmDeleteBtn {
  background-color: #dc3545;
  color: #fff;
  border: none;
  padding: 0.6rem 1.2rem;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: all 0.3s ease;
  white-space: nowrap;
}

#confirmDeleteBtn:hover {
  background-color: #c82333;
  transform: translateY(-1px);
}

/* Keep in one line even on mobile */
@media (max-width: 576px) {
  .modal-footer {
    flex-direction: row !important;
    justify-content: space-evenly;
    gap: 8px;
  }

  #confirmDeleteBtn,
  .btn-cancel {
    flex: 1 1 auto;
    min-width: 40%;
  }
}

    .action-toolbar {
    flex-wrap: nowrap !important;
    gap: 0.5rem;
}

/* Prevent buttons from stretching */
.action-toolbar .btn-action {
    flex: 0 0 auto !important;
    white-space: nowrap !important;
    width: auto !important;
}

/* Adjust spacing for mobile without stretching */
@media (max-width: 576px) {
    .action-toolbar {
        justify-content: center;
        gap: 0.6rem;
    }

    .action-toolbar .btn-action {
        font-size: 0.9rem;
        padding: 0.4rem 0.8rem;
        width: auto !important;
    }
}
    /* Search and button group alignment - EXACT COPY FROM SUPERADMIN */
.search-controls {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

@media (min-width: 768px) {
    .search-controls {
        flex-direction: row;
        justify-content: space-between;
        align-items: flex-end; /* Align to bottom for perfect leveling */
    }
}

.search-controls .input-group {
    flex-shrink: 0;
    align-self: flex-end; /* Ensure input group aligns to bottom */
}

.search-controls .btn-group {
    flex-shrink: 0;
    align-self: flex-end; /* Ensure button group aligns to bottom */
    display: flex;
    align-items: center;
}

/* Ensure buttons have consistent height with input */
.search-controls .btn-action {
    height: 38px; /* Match Bootstrap input height */
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
}

/* Ensure dropdown button is perfectly aligned */
.search-controls .dropdown .btn-action {
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Ensure all buttons in the group have the same baseline */
.search-controls .d-flex {
    align-items: center;
    gap: 0.75rem; /* Increased gap between buttons */
}

@media (max-width: 767px) {
    .search-controls {
        align-items: stretch;
    }
    
    .search-controls .btn-group {
        margin-top: 0.5rem;
        justify-content: center;
        align-self: center;
    }
    
    .search-controls .input-group {
        max-width: 100% !important;
    }
}
/* 🌟 Page Header Styling */
.page {
    background-color: #18375d;
    border-radius: 12px;
    padding: 1.5rem 2rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease-in-out;
    animation: fadeIn 0.6s ease-in-out;
}

/* Hover lift effect for interactivity */
.page:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
}

/* 🧭 Header Title */
.page h1 {
    color: #18375d;
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Icon style */
.page i {
    color: #18375d; /* Bootstrap primary color */
}

/* 💬 Subtitle text */
.page p {
    color: #18375d;
    font-size: 1rem;
    margin: 0;
}

/* ✨ Fade-in Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
/* Base Card Style */
.card {
    background-color: #ffffff !important;
    border: none;
    border-radius: 0.75rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
}

/* Top Section (Header inside card-body) */
.card-body:first-of-type {
    background-color: #ffffff;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    border-top-left-radius: 0.75rem;
    border-top-right-radius: 0.75rem;
    padding: 1rem 1.5rem;
}

/* Title (h6) */
.card-body:first-of-type h6 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 700;
    color: #18375d !important;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Second Card Body (Main Content) */
.card-body:last-of-type {
    background-color: #ffffff;
    padding: 1.25rem 1.5rem;
    border-bottom-left-radius: 0.75rem;
    border-bottom-right-radius: 0.75rem;
}

/* ============================
SMART FORM - Enhanced Version
============================ */
.smart-form {
  border: none;
  border-radius: 22px; /* slightly more rounded */
  box-shadow: 0 15px 45px rgba(0, 0, 0, 0.15);
  background-color: #ffffff;
  padding: 3rem 3.5rem; /* bigger spacing */
  transition: all 0.3s ease;
  max-width: 900px; /* slightly wider form container */
  margin: 2rem auto;
}

.smart-form:hover {
  box-shadow: 0 18px 55px rgba(0, 0, 0, 0.18);
}

/* Header Icon */
.smart-form .icon-circle {
  width: 60px;
    height: 60px;
    background-color: #e8f0fe;
    color: #18375d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

/* Titles & Paragraphs */
.smart-form h5 {
  color: #18375d;
  font-weight: 700;
  margin-bottom: 0.4rem;
  letter-spacing: 0.5px;
}

.smart-form p {
  color: #6b7280;
  font-size: 0.96rem;
  margin-bottom: 1.8rem;
  line-height: 1.5;
}

/* Form Container */
.smart-form .form-wrapper {
  max-width: 720px;
  margin: 0 auto;
}

/* ============================
   FORM ELEMENT STYLES
   ============================ */
#addItemModal form {
  text-align: left;
}

#addItemModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#addItemModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#addItemModal .form-control,
#addItemModal select.form-control,
#addItemModal textarea.form-control {
  border-radius: 12px;
  border: 1px solid #d1d5db;
  padding: 12px 15px;          /* consistent padding */
  font-size: 15px;             /* consistent font */
  line-height: 1.5;
  transition: all 0.2s ease;
  width: 100%;
  height: 46px;                /* unified height */
  box-sizing: border-box;
  margin-top: 0.5rem;
  margin-bottom: 1rem;
  background-color: #fff;
}

/* Keep textarea resizable but visually aligned */
#addItemModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#addItemModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}


#editIssueModal form {
  text-align: left;
}

#editIssueModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#editIssueModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#editIssueModal .form-control,
#editIssueModal select.form-control,
#editIssueModal textarea.form-control {
  border-radius: 12px;
  border: 1px solid #d1d5db;
  padding: 12px 15px;          /* consistent padding */
  font-size: 15px;             /* consistent font */
  line-height: 1.5;
  transition: all 0.2s ease;
  width: 100%;
  height: 46px;                /* unified height */
  box-sizing: border-box;
  margin-top: 0.5rem;
  margin-bottom: 1rem;
  background-color: #fff;
}

/* Keep textarea resizable but visually aligned */
#editIssueModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#editIssueModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}
/* ============================
   FORM ELEMENT STYLES
   ============================ */
#reportIssueModal form {
  text-align: left;
}

#reportIssueModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#reportIssueModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#reportIssueModal .form-control,
#reportIssueModal select.form-control,
#reportIssueModal textarea.form-control {
  border-radius: 12px;
  border: 1px solid #d1d5db;
  padding: 12px 15px;          /* consistent padding */
  font-size: 15px;             /* consistent font */
  line-height: 1.5;
  transition: all 0.2s ease;
  width: 100%;
  height: 46px;                /* unified height */
  box-sizing: border-box;
  margin-top: 0.5rem;
  margin-bottom: 1rem;
  background-color: #fff;
}

/* Keep textarea resizable but visually aligned */
#reportIssueModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#reportIssueModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}

/* ============================
   CRITICAL FIX FOR DROPDOWN TEXT CUTTING
   ============================ */
.admin-modal select.form-control,
.modal.admin-modal select.form-control,
.admin-modal .modal-body select.form-control {
  min-width: 250px !important;
  width: 100% !important;
  max-width: 100% !important;
  box-sizing: border-box !important;
  padding: 12px 15px !important;  /* match input padding */
  white-space: nowrap !important;
  text-overflow: clip !important;
  overflow: visible !important;
  font-size: 15px !important;     /* match input font */
  line-height: 1.5 !important;
  height: 46px !important;        /* same height as input */
  background-color: #fff !important;
}

/* Ensure columns don't constrain dropdowns */
.admin-modal .col-md-6 {
  min-width: 280px !important;
  overflow: visible !important;
}

/* Prevent modal body from clipping dropdowns */
.admin-modal .modal-body {
  overflow: visible !important;
}

/* ============================
   BUTTONS
   ============================ */
.btn-approve,
.btn-delete,
.btn-ok {
  font-weight: 600;
  border: none;
  border-radius: 10px;
  padding: 10px 24px;
  transition: all 0.2s ease-in-out;
}

.btn-approves {
  background: #387057;
  color: #fff;
}
.btn-approves:hover {
  background: #fca700;
  color: #fff;
}
.btn-cancel {
  background: #387057;
  color: #fff;
}
.btn-cancel:hover {
  background: #fca700;
  color: #fff;
}

.btn-delete {
  background: #dc3545;
  color: #fff;
}
.btn-delete:hover {
  background: #fca700;
  color: #fff;
}

.btn-ok {
  background: #18375d;
  color: #fff;
}
.btn-ok:hover {
  background: #fca700;
  color: #fff;
}

/* ============================
   FOOTER & ALIGNMENT
   ============================ */
#reportIssueModal .modal-footer {
  text-align: center;
  border-top: 1px solid #e5e7eb;
  padding-top: 1.25rem;
  margin-top: 1.5rem;
}

/* ============================
   RESPONSIVE DESIGN
   ============================ */
@media (max-width: 768px) {
  .smart-form {
    padding: 1.5rem;
  }

  .smart-form .form-wrapper {
    max-width: 100%;
  }

  #addLivestockModal .form-control {
    font-size: 14px;
  }

  #editIssueModal .form-control {
    font-size: 14px;
  }
   #reportIssueModal .form-control {
    font-size: 14px;
  }

  .btn-ok,
  .btn-delete,
  .btn-approves {
    width: 100%;
    margin-top: 0.5rem;
  }
}

/* SMART DETAIL MODAL TEMPLATE */
.smart-detail .modal-content {
    border-radius: 1.5rem;
    border: none;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12);
    background-color: #fff;
    transition: all 0.3s ease-in-out;
    max-width: 90vw; /* make modal a bit wider */
    margin: auto;
}

/* Icon Header */
.smart-detail .icon-circle {
    width: 70px;
    height: 70px;
    background-color: #e8f0fe;
    color: #18375d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0rem;
}

/* Titles & Paragraphs */
.smart-detail h5 {
    color: #18375d;
    font-weight: 700;
    margin-bottom: 0.75rem;
    letter-spacing: 0.5px;
}

.smart-detail p {
    color: #6b7280;
    font-size: 0.96rem;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

/* MODAL BODY */
.smart-detail .modal-body {
    background: #ffffff;
    padding: 2.5rem 1rem; /* increased horizontal and vertical padding */
    border-radius: 1.25rem;
    max-height: 80vh; /* more vertical stretch before scrolling */
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

/* Detail Section */
.smart-detail .detail-wrapper {
    background: #f9fafb;
    border-radius: 1.25rem;
    padding: 0rem; /* more internal spacing */
    font-size: 0.97rem;
}

.smart-detail .detail-row {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px dashed #ddd;
    padding: 0.75rem 0;
}

.smart-detail .detail-row:last-child {
    border-bottom: none;
}

.smart-detail .detail-label {
    font-weight: 600;
    color: #1b3043;
}

.smart-detail .detail-value {
    color: #333;
    text-align: right;
}

/* RESPONSIVE ADJUSTMENTS */
@media (max-width: 992px) {
    .smart-detail .modal-dialog {
        max-width: 95%;
    }

    .smart-detail .modal-body {
        padding: 2rem;
        max-height: 82vh;
    }

    .smart-detail .detail-wrapper {
        padding: 1.5rem;
        font-size: 0.95rem;
    }

    .smart-detail p {
        text-align: center;
        font-size: 0.95rem;
    }
}

@media (max-width: 576px) {
    .smart-detail .modal-body {
        padding: 0.5rem;
        max-height: 95vh;
    }

    .smart-detail .detail-wrapper {
        padding: 1.25rem;
    }

    .smart-detail .detail-row {
        flex-direction: column;
        text-align: left;
        gap: 0.3rem;
    }

    .smart-detail .detail-value {
        text-align: left;
    }
}

/* Action buttons styling */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        justify-content: center;
        min-width: 200px;
    }
    
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.25rem;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        white-space: nowrap;
    }
    
    .btn-action-edit {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
    .btn-action-edit:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }

    .btn-action-refresh {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-refresh:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    .btn-action-history {
        background-color: #5a6268;
        border-color: #5a6268;
        color: white;
    }
    
    .btn-action-history:hover {
        background-color: #e69500;
        border-color: #e69500;
        color: white;
    }

/* ===== Edit Button ===== */
.btn-action-ok {
    background-color: white !important;
    border: 1px solid #18375d !important;
    color: #18375d !important;/* blue text */
}

.btn-action-ok:hover {
    background-color: #18375d !important;/* yellow on hover */
    border: 1px solid #18375d !important;
    color: white !important;
}

.btn-action-edit {
    background-color: white !important;
    border: 1px solid #387057 !important;
    color: #387057 !important;/* blue text */
}

.btn-action-edit:hover {
    background-color: #387057 !important;/* yellow on hover */
    border: 1px solid #387057 !important;
    color: white !important;
}

.btn-action-deletes {
    background-color: white !important;
    border: 1px solid #dc3545 !important;
    color: #dc3545 !important; /* blue text */
}

.btn-action-deletes:hover {
    background-color: #dc3545 !important; /* yellow on hover */
    border: 1px solid #dc3545 !important;
    color: white !important;
}

.btn-action-refresh-alerts {
    background-color: white !important;
    border: 1px solid #fca700 !important;
    color: #fca700 !important; /* blue text */
}

.btn-action-refresh-alerts:hover {
    background-color: #fca700 !important; /* yellow on hover */
    border: 1px solid #fca700 !important;
    color: white !important;
}
.btn-action-refresh-inspection {
    background-color: white !important;
    border: 1px solid #fca700 !important;
    color: #fca700 !important; /* blue text */
}

.btn-action-refresh-inspection:hover {
    background-color: #fca700 !important; /* yellow on hover */
    border: 1px solid #fca700 !important;
    color: white !important;
}

.btn-action-refresh {
    background-color: white !important;
    border: 1px solid #fca700 !important;
    color: #fca700 !important; /* blue text */
}
    
.btn-action-refresh:hover {
    background-color: #fca700 !important; /* yellow on hover */
    border: 1px solid #fca700 !important;
    color: white !important;
}

.btn-action-tool {
    background-color: white !important;
    border: 1px solid #495057 !important;
    color: #495057 !important;
}

.btn-action-tool:hover {
    background-color: #495057 !important; /* yellow on hover */
    border: 1px solid #495057 !important;
    color: white !important;
}
    
/* ============================
   TABLE LAYOUT
============================ */
    /* Apply consistent styling for Farmers, Livestock, and Issues tables */
#dataTable th,
#dataTable td,
#issuesTable th,
#issuesTable td {
    vertical-align: middle;
    padding: 0.75rem;
    text-align: center;
    border: 1px solid #dee2e6;
    white-space: nowrap;
    overflow: visible;
}

/* Ensure all table headers have consistent styling */
#dataTable thead th,
#issuesTable thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: bold;
    color: #495057;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    text-align: center;
    vertical-align: middle;
    position: relative;
    white-space: nowrap;
}

/* Fix DataTables sorting button overlap */
#dataTable thead th.sorting,
#dataTable thead th.sorting_asc,
#dataTable thead th.sorting_desc,
#issuesTable thead th.sorting,
#issuesTable thead th.sorting_asc,
#issuesTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#dataTable thead th::after,
#issuesTable thead th::after {
    content: '';
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
}

/* Remove default DataTables sort indicators to prevent overlap */
#dataTable thead th.sorting::after,
#dataTable thead th.sorting_asc::after,
#dataTable thead th.sorting_desc::after,
#issuesTable thead th.sorting::after,
#issuesTable thead th.sorting_asc::after,
#issuesTable thead th.sorting_desc::after {
    display: none;
}
/* Make table cells wrap instead of forcing them all inline */
#dataTable td, 
#dataTable th {
    white-space: normal !important;  /* allow wrapping */
    vertical-align: middle;
}

/* Make sure action buttons don’t overflow */
#dataTable td .btn-group {
    display: flex;
    flex-wrap: wrap; /* buttons wrap if not enough space */
    gap: 0.25rem;    /* small gap between buttons */
}

#dataTable td .btn-action {
    flex: 1 1 auto; /* allow buttons to shrink/grow */
    min-width: 90px; /* prevent too tiny buttons */
    text-align: center;
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
/* User Details Modal Styling */
    #inventoryDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #inventoryDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #inventoryDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #inventoryDetailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #inventoryDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #inventoryDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #inventoryDetailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<!-- DataTables & exports -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- Required libraries for PDF/Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- jsPDF and autoTable for PDF generation -->
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
        autoWidth: true,
        scrollX: false,
        buttons: [
            { extend: 'csvHtml5', title: 'Farmer_Inventory', className: 'd-none', exportOptions: { columns: [0,1,2,3,4], modifier: { page: 'all' } } },
            { extend: 'pdfHtml5', title: 'Farmer_Inventory', orientation: 'landscape', pageSize: 'Letter', className: 'd-none', exportOptions: { columns: [0,1,2,3,4], modifier: { page: 'all' } } },
            { extend: 'print', title: 'Farmer Inventory', className: 'd-none', exportOptions: { columns: [0,1,2,3,4], modifier: { page: 'all' } } }
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
                { width: '220px', targets: 5, orderable: false },
                { targets: '_all', className: 'text-center align-middle' }
            ]
        });
        inventoryDT.columns.adjust();
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
                if (resp && resp.success && resp.item){
                    upsertInventoryRow(resp.item);
                    $('#addItemModal').modal('hide');
                    showToast(isEdit ? 'Inventory item updated.' : 'Inventory item added.', 'success');
                    // Reset form for next add
                    if (!isEdit){
                        $('#addItemForm')[0].reset();
                    }
                    CURRENT_ITEM_ID = null;
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

    $(document).on('click', '.btn-action-ok', function(e){
        e.preventDefault();
        const id = $(this).data('id');
        if (typeof id !== 'undefined') viewItem(id);
    });

    $(document).on('click', '.btn-action-edit', function(e){
        e.preventDefault();
        const id = $(this).data('id');
        if (typeof id !== 'undefined') editItem(id);
    });

    // Delete button -> open confirm modal
    $(document).on('click', '.btn-action-deletes', function(e){
        e.preventDefault();
        const id = $(this).data('id');
        if (typeof id === 'undefined') return;
        CURRENT_ITEM_ID = Number(id);
        $.get(`/farmer/inventory/${CURRENT_ITEM_ID}`, function(resp){
            if (resp && resp.success && resp.item){
                $('#confirmDeleteItemName').text(resp.item.name || '');
                $('#confirmDeleteItemCode').text(resp.item.code || '');
                $('#confirmDeleteModal').modal('show');
            } else {
                showToast('Item not found', 'error');
            }
        }).fail(function(){ showToast('Failed to load item.', 'error'); });
    });

    // Confirm delete
    $('#confirmDeleteBtn').on('click', function(){
        if (!CURRENT_ITEM_ID) return;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        $.ajax({
            url: `/farmer/inventory/${CURRENT_ITEM_ID}`,
            type: 'DELETE',
            headers: { 'X-CSRF-TOKEN': token },
            success: function(resp){
                if (resp && resp.success){
                    const $row = $(`tr[data-item-id="${CURRENT_ITEM_ID}"]`);
                    if (inventoryDT && $row.length){ inventoryDT.row($row).remove().draw(false); }
                    else { $row.remove(); }
                    showToast('Inventory item deleted.', 'success');
                    $('#confirmDeleteModal').modal('hide');
                    CURRENT_ITEM_ID = null;
                } else {
                    showToast('Failed to delete item.', 'error');
                }
            },
            error: function(){ showToast('Failed to delete item.', 'error'); }
        });
    });
});

function viewItem(id){
    CURRENT_ITEM_ID = Number(id);
    $.get(`/farmer/inventory/${CURRENT_ITEM_ID}`, function(resp){
        if (resp && resp.success && resp.item){
            CURRENT_ITEM = resp.item;
            const item = resp.item;
            $('#inventoryDetailsContent').html(`
                <div class="row text-left">
        <!-- Item Information -->
        <div class="col-md-6">
           <h6 class="mb-3" style="color: #18375d; font-weight: 600;">
    <i class="fas fa-box me-2"></i> Item Information
</h6>

            <p class="text-left"><strong>Code:</strong> ${item.code || 'N/A'}</p>
            <p class="text-left"><strong>Name:</strong> ${item.name || 'N/A'}</p>
            <p class="text-left"><strong>Category:</strong> ${item.category || 'N/A'}</p>
            <p class="text-left"><strong>Date:</strong> ${item.date || 'N/A'}</p>
        </div>

        <!-- Additional Details -->
        <div class="col-md-6">
            <h6 class="mb-3" style="color: #18375d; font-weight: 600;"><i class="fas fa-sticky-note me-2"></i> Additional Details</h6>
            <p class="text-left"><strong>Quantity:</strong> ${item.quantity_text || 'N/A'}</p>
            <p class="text-left"><strong>Farm ID:</strong> ${item.farm_id || 'N/A'}</p>
            <p class="text-left"><strong>Status:</strong> 
                <span class="badge badge-${item.status === 'available' ? 'success' : item.status === 'low stock' ? 'warning' : 'secondary'}">
                    ${item.status || 'N/A'}
                </span>
            </p>
            <p class="text-left"><strong>Last Updated:</strong> ${item.updated_at ? new Date(item.updated_at).toLocaleDateString() : 'N/A'}</p>
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
    try {
        const tableId = 'dataTable';
        const tableEl = document.getElementById(tableId);
        const dt = ($.fn.DataTable && $.fn.DataTable.isDataTable('#' + tableId)) ? $('#' + tableId).DataTable() : null;

        const headerTexts = [];
        if (tableEl) {
            const ths = tableEl.querySelectorAll('thead th');
            ths.forEach((th, idx) => { if (idx < ths.length - 1) headerTexts.push((th.innerText || '').trim()); });
        }

        const rows = [];
        if (dt) {
            dt.data().toArray().forEach(row => {
                const cleaned = [];
                for (let i = 0; i < row.length - 1; i++) {
                    const div = document.createElement('div');
                    div.innerHTML = row[i];
                    cleaned.push((div.textContent || div.innerText || '').replace(/\s+/g, ' ').trim());
                }
                rows.push(cleaned);
            });
        } else if (tableEl) {
            tableEl.querySelectorAll('tbody tr').forEach(tr => {
                const tds = tr.querySelectorAll('td');
                if (tds.length) {
                    const cleaned = [];
                    for (let i = 0; i < tds.length - 1; i++) cleaned.push((tds[i].innerText || '').replace(/\s+/g, ' ').trim());
                    rows.push(cleaned);
                }
            });
        }

        if (!rows.length) { showNotification && showNotification('No data available to print', 'warning'); return; }

        let html = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style=\"color:#18375d; margin-bottom:5px;\">Inventory Report</h1>
                    <p style=\"color:#666; margin:0;\">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                <table border=\"3\" style=\"border-collapse: collapse; width:100%; border:3px solid #000;\">
                    <thead><tr>`;
        headerTexts.forEach(h => { html += `<th style=\"border:3px solid #000; padding:10px; background:#f2f2f2; text-align:left;\">${h}</th>`; });
        html += `</tr></thead><tbody>`;
        rows.forEach(r => {
            html += '<tr>';
            r.forEach(c => { html += `<td style=\"border:3px solid #000; padding:10px; text-align:left;\">${c}</td>`; });
            html += '</tr>';
        });
        html += `</tbody></table></div>`;

        if (typeof window.printElement === 'function') {
            const container = document.createElement('div'); container.innerHTML = html; window.printElement(container);
        } else if (typeof window.openPrintWindow === 'function') {
            window.openPrintWindow(html, 'Inventory Report');
        } else {
            const w = window.open('', '_blank');
            if (w) { w.document.open(); w.document.write(`<html><head><title>Print</title></head><body>${html}</body></html>`); w.document.close(); w.focus(); w.print(); w.close(); }
            else { window.print(); }
        }
    } catch(e){
        console.error('printInventory error:', e);
        try { $('#' + 'dataTable').DataTable().button('.buttons-print').trigger(); } catch (_) {}
    }
}

// Refresh Admins Table
function refreshInventory() {
    const refreshBtn = document.querySelector('.btn-action-refresh');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    refreshBtn.disabled = true;

    // Use unique flag for admins
    sessionStorage.setItem('showRefreshNotificationInventory', 'true');

    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Check notifications after reload
$(document).ready(function() {
    if (sessionStorage.getItem('showRefreshNotificationInventory') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationInventory');
        setTimeout(() => {
            showNotification('Inventory data refreshed successfully!', 'success');
        }, 500);
    }
});

 function showNotification(message, type) {
    const notification = $(`
        <div class="alert alert-${type} alert-dismissible fade show refresh-notification">
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

function exportInventoryCSV(){
    try { if (inventoryDT) inventoryDT.button('.buttons-csv').trigger(); else showToast('Table is not ready.', 'error'); }
    catch(e){ console.error('CSV export error:', e); showToast('Error generating CSV.', 'error'); }
}

function exportInventoryPDF(){
    try { if (inventoryDT) inventoryDT.button('.buttons-pdf').trigger(); else showToast('Table is not ready.', 'error'); }
    catch(e){ console.error('PDF export error:', e); showToast('Error generating PDF.', 'error'); }
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

// Helpers: upsert row into DataTable and HTML escape
function upsertInventoryRow(item){
    const data = [
        htmlEscape(item.code || ''),
        htmlEscape(item.date || ''),
        htmlEscape(item.category || ''),
        htmlEscape(item.name || ''),
        htmlEscape(item.quantity_text || ''),
        `\n            <div class="action-buttons">\n                <button type="button" class="btn-action btn-action-ok" data-id="${item.id}" title="View">\n                    <i class="fas fa-eye"></i><span>View</span>\n                </button>\n                <button type="button" class="btn-action btn-action-edit" data-id="${item.id}" title="Edit">\n                    <i class="fas fa-edit"></i><span>Edit</span>\n                </button>\n                <button type="button" class="btn-action btn-action-deletes" data-id="${item.id}" title="Delete">\n                    <i class="fas fa-trash"></i><span>Delete</span>\n                </button>\n            </div>\n        `
    ];
    const $row = $(`tr[data-item-id="${item.id}"]`);
    if (inventoryDT){
        if ($row.length){
            inventoryDT.row($row).data(data).draw(false);
        } else {
            const node = inventoryDT.row.add(data).draw(false).node();
            $(node).setAttribute?.('data-item-id', item.id);
            $(node).attr('data-item-id', item.id);
        }
    } else {
        // Fallback when DataTables is unavailable
        if ($row.length){
            const cells = $row.find('td');
            $(cells[0]).text(item.code || '');
            $(cells[1]).text(item.date || '');
            $(cells[2]).text(item.category || '');
            $(cells[3]).text(item.name || '');
            $(cells[4]).text(item.quantity_text || '');
        } else {
            const tbody = document.querySelector('#dataTable tbody');
            if (tbody){
                const tr = document.createElement('tr');
                tr.setAttribute('data-item-id', item.id);
                tr.innerHTML = `
                    <td>${htmlEscape(item.code || '')}</td>
                    <td>${htmlEscape(item.date || '')}</td>
                    <td>${htmlEscape(item.category || '')}</td>
                    <td>${htmlEscape(item.name || '')}</td>
                    <td>${htmlEscape(item.quantity_text || '')}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn-action btn-action-ok" data-id="${item.id}" title="View"><i class="fas fa-eye"></i><span>View</span></button>
                            <button type="button" class="btn-action btn-action-edit" data-id="${item.id}" title="Edit"><i class="fas fa-edit"></i><span>Edit</span></button>
                            <button type="button" class="btn-action btn-action-deletes" data-id="${item.id}" title="Delete"><i class="fas fa-trash"></i><span>Delete</span></button>
                        </div>
                    </td>`;
                tbody.prepend(tr);
            }
        }
    }
}

function htmlEscape(s){
    return (s==null? '': String(s)).replace(/[&<>"']/g, function(c){
        return {"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#39;"}[c];
    });
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
