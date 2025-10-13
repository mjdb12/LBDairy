@extends('layouts.app')

@section('title', 'Productivity Analysis')

@section('content')
    <div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
        <h1>
            <i class="fas fa-chart-line"></i>
            Productivity Analysis
        </h1>
        <p>Monitor and analyze farm productivity metrics and performance trends</p>
    </div>

    <!-- Stats Cards -->
    <div class="row fade-in">
        <!-- Active Farms -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Active Farms</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeFarmsCount }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-tractor fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avg Daily Production -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Avg Daily Production</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($avgProductivity, 1) }}L</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-chart-bar fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Producer -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Top Producer</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $topProducer ?? 'N/A' }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-trophy fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Farmers -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Farmers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalFarmers }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-users fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4 fade-in">
        <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
            <h6 class="mb-0">
                <i class="fas fa-list"></i>
                Farmer Productivity Overview
            </h6>
        </div>
        <div class="card-body">
            <div class="search-controls mb-3">
                <div class="input-group" style="max-width: 300px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search farmer..." id="customSearch">
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-center">
                    <button class="btn-action btn-action-edit" title="Print" onclick="printTable('pendingFarmersTable')">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button class="btn-action btn-action-refresh-farmers" title="Refresh" onclick="refreshPendingFarmersTable('pendingFarmersTable')">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" title="Tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportCSV('pendingFarmersTable')">
                                <i class="fas fa-file-csv"></i> Download CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPNG('pendingFarmersTable')">
                                <i class="fas fa-image"></i> Download PNG
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPDF('pendingFarmersTable')">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="farmersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Farmer ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($farmers as $farmer)
                        <tr>
                            <td>
                                <a href="#" class="farmer-id-link" onclick="viewFarmerDetails('{{ $farmer->id }}')" title="Click to view farmer details">
                                    {{ $farmer->farmer_id ?? 'F' . str_pad($farmer->id, 3, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <td>{{ $farmer->name }}</td>
                            <td>{{ $farmer->email }}</td>
                            <td>{{ $farmer->phone ?? 'N/A' }}</td>
                            <td>{{ $farmer->location ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $farmer->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($farmer->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn-action btn-action-view-live" onclick="viewFarmerDetails('{{ $farmer->id }}')" title="View Details">
                                        <i class="fas fa-eye"></i>
                                        <span>View</span>
                                    </button>
                                    <button class="btn-action btn-action-deletes" onclick="deleteFarmer('{{ $farmer->id }}')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-chart-line"></i>
                                    <h5>No productivity data available</h5>
                                    <p>There are no farms to analyze at this time.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    

<!-- Productivity Analysis Modal -->
<div class="modal fade" id="productivityModal" tabindex="-1" aria-labelledby="productivityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productivityModalLabel">
                    <i class="fas fa-chart-line"></i>
                    Farm Productivity Analysis
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h6 class="mb-1"><strong>Farm ID:</strong> <span id="modalFarmId" class="text-primary">F001</span></h6>
                        <p class="text-muted mb-0">Detailed productivity metrics and trends</p>
                    </div>
                    <div class="export-controls">
                        <div class="btn-group">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="fas fa-download"></i> Export Report
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="exportModalCSV()">
                                    <i class="fas fa-file-csv"></i> CSV
                                </a>
                                <a class="dropdown-item" href="#" onclick="exportModalPNG()">
                                    <i class="fas fa-image"></i> PNG
                                </a>
                                <a class="dropdown-item" href="#" onclick="exportModalPDF()">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </div>
                        <button class="btn btn-secondary btn-sm ml-2" onclick="printProductivity()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
                
                <div class="chart-container">
                    <canvas id="lineChart"></canvas>
                </div>
                
                <div class="analysis-text" id="analysisText">
                    <strong><i class="fas fa-lightbulb text-warning"></i> Analysis:</strong>
                    <span id="analysisContent">Loading analysis...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Smart Detail Modal - Farmer Details -->
<div class="modal fade" id="farmerDetailsModal" tabindex="-1" role="dialog" aria-labelledby="farmerDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-user fa-2x "></i>
                </div>
                <h5 class="fw-bold mb-1">Farmer Details</h5>
                <p class="text-muted mb-0 small">Below are the complete details of the selected farmer.</p>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div id="farmerDetailsContent" class="detail-wrapper">
                    <!-- Personal & Farm Info -->
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer justify-content-center mt-4">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Smart Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content smart-modal text-center p-4">

      <!-- Icon -->
      <div class="icon-wrapper mx-auto mb-3 text-danger">
        <i class="fas fa-times-circle fa-2x"></i>
      </div>

      <!-- Title -->
      <h5 id="confirmDeleteModalLabel" class="fw-bold mb-2">Confirm Delete</h5>

      <!-- Message -->
      <p class="text-muted mb-3">
        Are you sure you want to delete this farmer? <br>
        This action <strong>cannot be undone</strong>.
      </p>

      <!-- Farmer ID -->
      <p class="fw-semibold">
        <strong>Farmer ID:</strong> <span id="deleteFarmerId">-</span>
      </p>

      <!-- Footer -->
      <div class="modal-footer d-flex justify-content-center gap-2 flex-wrap ">
        <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
        <form id="deleteFarmerForm" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn-modern btn-delete">Yes, Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>


    <!-- Modern Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content smart-modal text-center p-4">

      <!-- Icon -->
      <div class="icon-wrapper mx-auto mb-4 text-danger">
        <i class="fas fa-exclamation-triangle fa-2x"></i>
      </div>

      <!-- Title -->
      <h5>Confirm Delete</h5>

      <!-- Description -->
      <p class="text-muted mb-4 px-3">
        Are you sure you want to delete this farmer? This action <strong>cannot be undone</strong>.
      </p>

      <!-- Buttons -->
      <div class="modal-footer d-flex gap-2 justify-content-center flex-wrap">
        <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
        <form id="deleteFarmerForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-modern btn-delete" id="confirmDeleteBtn">
                Yes, Delete
            </button>
        </form>
      </div>

    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
    /* SMART DETAIL MODAL TEMPLATE */
.smart-detail .modal-content {
    border-radius: 1.5rem;
    border: none;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12);
    background-color: #fff;
    transition: all 0.3s ease-in-out;
}

/* Center alignment for header section */
.smart-detail .modal-header,
.smart-detail .modal-footer {
    text-align: center;
}

/* Icon Header */
.smart-detail .icon-circle {
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
.smart-detail h5 {
    color: #18375d;
    font-weight: 700;
    margin-bottom: 0.4rem;
    letter-spacing: 0.5px;
}

.smart-detail p {
    color: #6b7280;
    font-size: 1rem;
    margin-bottom: 1.8rem;
    line-height: 1.6;
    text-align: left; /* ensures proper centering */
}

/* MODAL BODY */
.smart-detail .modal-body {
    background: #ffffff;
    padding: 3rem 3.5rem; /* more spacious layout */
    border-radius: 1rem;
    max-height: 88vh; /* taller for longer content */
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

/* Wider modal container */
.smart-detail .modal-dialog {
    max-width: 92%; /* slightly wider modal */
    width: 100%;
    margin: 1.75rem auto;
}

/* Detail Section */
.smart-detail .detail-wrapper {
    background: #f9fafb;
    border-radius: 1.25rem;
    padding: 2.25rem; /* more inner padding */
    font-size: 1rem;
    line-height: 1.65;
}

/* Detail Rows */
.smart-detail .detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px dashed #ddd;
    padding: 0.6rem 0;
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

/* Footer */
#livestockDetailsModal .modal-footer {
    text-align: center;
    border-top: 1px solid #e5e7eb;
    padding-top: 1.5rem;
    margin-top: 2rem;
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
        padding: 1.5rem;
        max-height: 80vh;
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

    .smart-modal {
  border: none;
  border-radius: 16px;
  background: #fff;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  max-width: 500px;
  margin: auto;
  transition: all 0.3s ease;
}

.smart-modal .icon-wrapper {
  background-color: #ffffffff;
  color: #18375d;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
}

.smart-modal h5 {
  color: #18375d;
  font-weight: 600;
}

.smart-modal p {
  color: #6b7280;
  font-size: 0.95rem;
}
.btn-delete {
  background: #dc3545;
  color: #fff;
  border: none;
}

.btn-delete:hover {
  background: #fca700;
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
    /* User Details Modal Styling */
    #farmerDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #farmerDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #farmerDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #farmerDetailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #farmerDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #farmerDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #farmerDetailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }
    
    #farmerDetailsModal .modal-body hr {
        border-color: #e3e6f0;
        margin: 1.5rem 0;
    }
    
    #farmerDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600;
        margin-bottom: 1rem;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
    }
    
    #farmerDetailsModal .modal-body ul {
        margin-top: 0.5rem;
        padding-left: 1.5rem;
    }
    
    #farmerDetailsModal .modal-body li {
        margin-bottom: 0.25rem;
        color: #5a5c69;
    }
    
    #farmerDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333;
    }
    
    #farmerDetailsModal .modal-body span {
        color: #18375d;
        font-weight: 500;
    }

    /* User Details Modal Styling */
    #confirmDeleteModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #confirmDeleteModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #confirmDeleteModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #confirmDeleteModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #confirmDeleteModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #confirmDeleteModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #confirmDeleteModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Custom styles for farmer management */
    .border-left-primary {
        border-left: 0.25rem solid #18375d !important;
    }
    
    .fade-in {
        animation: fadeIn 0.6s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Search and button group alignment */
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

    /* Action buttons styling to match admin management */
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        border: 1px solid transparent;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        white-space: nowrap;
    }
    
    .btn-action-add {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
    .btn-action-add:hover {
        background-color: #2d5a47;
        border-color: #2d5a47;
        color: white;
    }
    
    .btn-action-print {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: white !important;
    }
    
    .btn-action-print:hover {
        background-color: #5a6268 !important;
        border-color: #5a6268 !important;
        color: white !important;
    }
    
    .btn-action-refresh {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-refresh:hover {
        background-color: #e69500;
        border-color: #e69500;
        color: white;
    }

    .btn-action-deletes {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .btn-action-deletes:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-tools {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #495057;
    }
    
    .btn-action-tools:hover {
        background-color: #e2e6ea;
        border-color: #cbd3da;
        color: #495057;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .d-flex.flex-column.flex-sm-row {
            flex-direction: column !important;
        }
        
        .gap-2 {
            gap: 0.5rem !important;
        }
    }
    
    /* Dashboard-style stat card styling */
    .dashboard-card {
        transition: transform 0.2s ease-in-out;
        background: #fff !important;
    }
    
    .dashboard-card:hover {
        transform: translateY(-2px);
    }
    
    /* Force override any blue styling on stat cards */
    .card.stat-card,
    .card.dashboard-card {
        background: #fff !important;
        background-color: #fff !important;
    }
    
    .card.stat-card .card-body,
    .card.dashboard-card .card-body {
        background: #fff !important;
        background-color: #fff !important;
        color: inherit !important;
    }
    
    .stat-card {
        border-radius: 10px;
        overflow: hidden;
        background: #fff !important;
    }
    
    .stat-card .card-body {
        padding: 1.5rem;
        background: #fff !important;
    }
    
    .stat-card .icon {
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }
    
    .stat-card:hover .icon {
        opacity: 1;
    }
    
    /* Ensure consistent table styling */
    .table {
        margin-bottom: 0;
    }
    
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.075);
    }
    
   /* Apply consistent styling for Farmers, Livestock, and Issues tables */
#farmersTable th,
#farmersTable td,
#livestockTable th,
#livestockTable td,
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
#farmersTable thead th,
#livestockTable thead th,
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
#farmersTable thead th.sorting,
#farmersTable thead th.sorting_asc,
#farmersTable thead th.sorting_desc,
#livestockTable thead th.sorting,
#livestockTable thead th.sorting_asc,
#livestockTable thead th.sorting_desc,
#issuesTable thead th.sorting,
#issuesTable thead th.sorting_asc,
#issuesTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#farmersTable thead th::after,
#livestockTable thead th::after,
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
#farmersTable thead th.sorting::after,
#farmersTable thead th.sorting_asc::after,
#farmersTable thead th.sorting_desc::after,
#livestockTable thead th.sorting::after,
#livestockTable thead th.sorting_asc::after,
#livestockTable thead th.sorting_desc::after,
#issuesTable thead th.sorting::after,
#issuesTable thead th.sorting_asc::after,
#issuesTable thead th.sorting_desc::after {
    display: none;
}

    
    /* DataTables Pagination Styling - FIXED */
    .dataTables_wrapper .dataTables_paginate {
        text-align: left !important;
        margin-top: 1rem;
        clear: both;
        width: 100%;
        float: left !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        display: inline-block;
        min-width: 2.5rem;
        padding: 0.5rem 0.75rem;
        margin: 0 0.125rem;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        color: #18375d !important; /* Darker blue color for numbers */
        border: 1px solid #18375d !important;
        border-radius: 0.25rem;
        background-color: #fff;
        transition: all 0.15s ease-in-out;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        color: #fff !important;
        background-color: #18375d !important;
        border-color: #18375d !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        color: #fff !important;
        background-color: #18375d !important;
        border-color: #18375d !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        color: #6c757d !important;
        background-color: #fff !important;
        border-color: #dee2e6 !important;
        cursor: not-allowed;
        opacity: 0.5;
    }
    
    .dataTables_wrapper .dataTables_info {
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        color: #495057;
        font-size: 0.875rem;
        text-align: left !important;
        float: left !important;
        clear: both;
    }
    
    .dataTables_wrapper .dataTables_length {
        margin-bottom: 1rem;
    }
    
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
    }
    
    /* Force DataTables wrapper to have proper layout */
    .dataTables_wrapper .row {
        display: block !important;
        width: 100% !important;
        margin: 0 !important;
    }
    
    .dataTables_wrapper .row > div {
        padding: 0 !important;
        width: 100% !important;
        float: left !important;
        clear: both !important;
    }
    .btn-action-refresh-admins, .btn-action-refresh-farmers {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-refresh-admin:hover, .btn-action-refresh-farmers:hover {
        background-color: #e69500;
        border-color: #e69500;
        color: white;
    }

    /* Ensure pagination container stays left */
    .dataTables_wrapper .dataTables_paginate,
    .dataTables_wrapper .dataTables_info {
        text-align: left !important;
        float: left !important;
        clear: both !important;
        display: block !important;
        width: auto !important;
        margin-right: 1rem !important;
    }
    
    /* Override any Bootstrap or other framework styles that might interfere */
    .dataTables_wrapper .col-sm-12.col-md-7,
    .dataTables_wrapper .col-sm-12.col-md-5 {
        width: 100% !important;
        padding: 0 !important;
    }
    
    /* Additional override to ensure left positioning */
    .dataTables_wrapper .dataTables_paginate.paging_simple_numbers {
        text-align: left !important;
        float: left !important;
    }
    
    .dataTables_wrapper .dataTables_paginate.paging_simple_numbers .paginate_button {
        color: #18375d !important;
        border-color: #18375d !important;
    }
    
    /* Table-responsive wrapper positioning - match active admins spacing */
    .table-responsive {
        overflow-x: auto;
        min-width: 100%;
        position: relative;
        border-radius: 8px;
        overflow: hidden;
    }
    
    /* Ensure DataTables controls are properly positioned */
    .table-responsive + .dataTables_wrapper,
    .table-responsive .dataTables_wrapper {
        width: 100%;
        position: relative;
    }
    
    /* Fix pagination positioning for wide tables - match active admins spacing */
    .table-responsive .dataTables_wrapper .dataTables_paginate {
        position: relative;
        width: 100%;
        text-align: left;
        margin: 1rem 0;
        left: 0;
        right: 0;
    }
    
    /* User ID link styling - superadmin theme */
    .user-id-link {
        color: #18375d;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
        transition: color 0.2s ease;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        background-color: rgba(24, 55, 93, 0.1);
        border: 1px solid rgba(24, 55, 93, 0.2);
    }
    
    .user-id-link:hover {
        color: #fff;
        background-color: #18375d;
        border-color: #18375d;
        text-decoration: none;
    }
    
    .user-id-link:active {
        color: #fff;
        background-color: #122a4e;
        border-color: #122a4e;
    }
    /* Style all labels inside form Modal */
    #confirmDeleteModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
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
        background-color: #2d5a47;
        border-color: #2d5a47;
        color: white;
    }
    
    .btn-action-view-live, .btn-action-report-livestock {
        background-color: #18375d;
        border-color: #18375d;
        color: white;
    }
    
    .btn-action-view-live:hover, .btn-action-report-livestock:hover {
        background-color: #e69500;
        border-color: #e69500;
        color: white;
    }

    /* Header action buttons styling to match Edit/Delete buttons */
    .btn-action-add {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
    .btn-action-ok:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-deletes {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .btn-action-deletes:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-print {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: white !important;
    }
    
    .btn-action-print:hover {
        background-color: #5a6268 !important;
        border-color: #5a6268 !important;
        color: white !important;
    }
    
    .btn-action-cancel {
        background-color: #6c757d ;
        border-color: #6c757d ;
        color: white ;
    }
    
    .btn-action-refresh-, .btn-action-refresh- {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-refresh-:hover, .btn-action-refresh-:hover {
        background-color: #e69500;
        border-color: #e69500;
        color: white;
    }

    .btn-action-tools {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #495057;
    }
    
    .btn-action-tools:hover {
        background-color: #e2e6ea;
        border-color: #cbd3da;
        color: #495057;
    }
    
    /* Ensure table has enough space for actions column */
    .table th:last-child,
    .table td:last-child {
        min-width: 200px;
        width: auto;
        text-align: center;
        vertical-align: middle;
    }
    
    /* Responsive action buttons */
    @media (max-width: 1200px) {
        .action-buttons {
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .btn-action {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
    }

    /* Chart Container */
    .chart-container {
        position: relative;
        height: 400px;
        margin-bottom: 2rem;
    }

    /* Analysis Text */
    .analysis-text {
        background: #f8f9fc;
        padding: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid #f6c23e;
        line-height: 1.6;
    }

    /* Animations */
    .fade-in {
        animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #5a5c69;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Farmer ID Link Styling */
    .farmer-id-link {
        color: #18375d;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-block;
        background-color: rgba(24, 55, 93, 0.1);
        border: 1px solid rgba(24, 55, 93, 0.2);
    }

    .farmer-id-link:hover {
        color: #fff;
        background-color: #18375d;
        border-color: #18375d;
        text-decoration: none;
        transform: translateY(-1px);
    }

    .farmer-id-link:focus {
        outline: 2px solid #18375d;
        outline-offset: 2px;
    }
</style>
@endpush

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
let farmersTable;
let chartInstance;
let downloadCounter = 1;

$(document).ready(function () {
    // DataTable is initialized below; keep this block minimal to avoid duplicate init
});
$(document).ready(function () {
    // Initialize DataTable
    farmersTable = $('#farmersTable').DataTable({
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
                title: 'Farmers_Productivity_Report',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Farmers_Productivity_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Farmers Productivity Report',
                className: 'd-none'
            }
        ],
        language: {
            search: "",
            emptyTable: `
                <div class="empty-state">
                    <i class="fas fa-chart-line"></i>
                    <h5>No productivity data available</h5>
                    <p>There are no farms to analyze at this time.</p>
                </div>`
        }
    });

    // 🔍 Custom search input
    $('#customSearch').on('keyup', function() {
        farmersTable.search(this.value).draw();
    });

    // Hide default DataTables elements
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
});


// Refresh Pending Farmers Table
function refreshPendingFarmersTable() {
    const refreshBtn = document.querySelector('.btn-action-refresh-farmers');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    refreshBtn.disabled = true;

    // Use unique flag for farmers
    sessionStorage.setItem('showRefreshNotificationFarmers', 'true');

    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Check notifications after reload
$(document).ready(function() {
    if (sessionStorage.getItem('showRefreshNotificationFarmers') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationFarmers');
        setTimeout(() => {
            showNotification('Farmers data refreshed successfully!', 'success');
        }, 500);
    }

    if (sessionStorage.getItem('showRefreshNotificationAdmins') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationAdmins');
        setTimeout(() => {
            showNotification('Admins data refreshed successfully!', 'success');
        }, 500);
    }
});


function openDetailsModal(farmerId) {
    // Fetch farmer productivity data
    fetch(`{{ route('admin.analysis.farmer-data', ':id') }}`.replace(':id', farmerId))
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update modal farm ID text
            document.getElementById('modalFarmId').innerText = data.farmer.farmer_id || 'F' + String(data.farmer.id).padStart(3, '0');

            // Update analysis text
            document.getElementById('analysisContent').innerHTML = data.analysis || 'No analysis available for this farmer.';

            // Destroy previous chart instance if exists
            if (chartInstance) {
                chartInstance.destroy();
            }

            // Create new chart with farmer data
            const ctx = document.getElementById('lineChart').getContext('2d');
            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.chartData.labels || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Milk Production (liters)',
                        data: data.chartData.data || [0, 0, 0, 0, 0, 0],
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    size: 14,
                                    weight: '500'
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    elements: {
                        point: {
                            hoverBackgroundColor: 'rgba(78, 115, 223, 1)'
                        }
                    }
                }
            });

            // Show the modal
            $('#productivityModal').modal('show');
        } else {
            showNotification('Failed to load farmer data', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while loading farmer data', 'error');
    });
}

function viewFarmerDetails(farmerId) {
    console.log('viewFarmerDetails called with ID:', farmerId);
    
    // Show loading state without replacing modal structure
    const modal = $('#farmerDetailsModal');
    const contentWrap = modal.find('#farmerDetailsContent');
    if (contentWrap && contentWrap.length) {
        contentWrap.html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Loading farmer details...</p></div>');
    }
    modal.modal('show');
    
    // Build the URL
    const url = `{{ route('admin.analysis.farmer-details', ':id') }}`.replace(':id', farmerId);
    console.log('Fetching URL:', url);
    
    // Fetch farmer details
    fetch(url)
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            const farmer = data.farmer;
            const stats = data.stats;
            
            console.log('Farmer data:', farmer);
            console.log('Stats data:', stats);
            
            $('#farmerDetailsContent').html(`
            <div class="row">
                <div class="col-md-8 mb-3">
                    <div class="row">
                        <div class="col-sm-6 col-lg-3 mb-3">
                            <div class="p-3 bg-light rounded text-center">
                                <div class="small text-muted">Total Production</div>
                                <div class="h5 mb-0">${(stats?.total_production ?? farmer.total_production ?? 0)} L</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3 mb-3">
                            <div class="p-3 bg-light rounded text-center">
                                <div class="small text-muted">Avg Daily</div>
                                <div class="h5 mb-0">${(stats?.avg_daily_production ?? farmer.avg_daily_production ?? 0)} L</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3 mb-3">
                            <div class="p-3 bg-light rounded text-center">
                                <div class="small text-muted">Active Livestock</div>
                                <div class="h5 mb-0">${(stats?.active_livestock ?? farmer.active_livestock ?? 0)}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3 mb-3">
                            <div class="p-3 bg-light rounded text-center">
                                <div class="small text-muted">Efficiency</div>
                                <div class="h5 mb-0">${(stats?.efficiency ?? farmer.efficiency ?? 0)}%</div>
                            </div>
                        </div>
                    </div>
                    <div class="chart-container" style="height:260px;">
                        <canvas id="farmerProdTrend"></canvas>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="p-3 bg-light rounded h-100">
                        <h6 class="mb-2" style="color:#18375d; font-weight:600;"><i class="fas fa-user-circle mr-2"></i>Profile</h6>
                        <p class="mb-1"><strong>Name:</strong> ${farmer.name || 'N/A'}</p>
                        <p class="mb-1"><strong>Email:</strong> ${farmer.email || 'N/A'}</p>
                        <p class="mb-1"><strong>Phone:</strong> ${farmer.phone || 'N/A'}</p>
                        <p class="mb-1"><strong>Location:</strong> ${farmer.location || 'N/A'}</p>
                        <p class="mb-1"><strong>Status:</strong> <span class="badge badge-${farmer.status === 'approved' ? 'success' : (farmer.status === 'pending' ? 'warning' : 'danger')}">${farmer.status || 'N/A'}</span></p>
                        <hr>
                        <h6 class="mb-2" style="color:#18375d; font-weight:600;"><i class="fas fa-lightbulb mr-2 text-warning"></i>Analysis</h6>
                        <div id="analysisSummaryText" class="small text-muted">Loading analysis...</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-3" style="color:#18375d; font-weight:600;"><i class="fas fa-paw mr-2"></i>Livestock</h6>
                    <p class="mb-1"><strong>Total:</strong> ${stats?.total_livestock ?? farmer.total_livestock ?? '0'}</p>
                    <p class="mb-1"><strong>Active:</strong> ${stats?.active_livestock ?? farmer.active_livestock ?? '0'}</p>
                    <p class="mb-1"><strong>Inactive:</strong> ${stats?.inactive_livestock ?? farmer.inactive_livestock ?? '0'}</p>
                    <div class="mt-2"><strong>Types:</strong>
                        <ul id="livestockTypeList" class="mt-1 list-unstyled text-muted small">${farmer.livestock_types ? farmer.livestock_types.map(type => `<li>${type}</li>`).join('') : '<li>N/A</li>'}</ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="mb-3" style="color:#18375d; font-weight:600;"><i class="fas fa-chart-line mr-2"></i>Production Stats</h6>
                    <p class="mb-1"><strong>Recent (30 days):</strong> ${stats?.recent_production ?? farmer.recent_production ?? '0'} L</p>
                    <p class="mb-1"><strong>Peak Day:</strong> ${stats?.peak_day ?? 'N/A'}</p>
                    <p class="mb-1"><strong>Peak Output:</strong> ${stats?.peak_output ?? '0'} L</p>
                </div>
            </div>
            `);
            
            // Livestock by Type
            const livestockTypeList = document.getElementById('livestockTypeList');
            if (livestockTypeList) {
                livestockTypeList.innerHTML = '';
                if (stats.livestock_by_type && Object.keys(stats.livestock_by_type).length > 0) {
                    Object.entries(stats.livestock_by_type).forEach(([type, count]) => {
                        const li = document.createElement('li');
                        li.textContent = `${type}: ${count}`;
                        livestockTypeList.appendChild(li);
                    });
                } else {
                    const li = document.createElement('li');
                    li.textContent = 'No livestock data available';
                    livestockTypeList.appendChild(li);
                }
            } else {
                console.warn('Element with ID "livestockTypeList" not found');
            }
            
            // Build trend chart
            try {
                const trendLabels = (data.chartData && data.chartData.labels) || (stats.trend && stats.trend.labels) || [];
                const trendValues = (data.chartData && data.chartData.data) || (stats.trend && stats.trend.values) || [];
                const ctxTrend = document.getElementById('farmerProdTrend').getContext('2d');
                new Chart(ctxTrend, {
                    type: 'line',
                    data: {
                        labels: trendLabels,
                        datasets: [{
                            label: 'Milk Production (L)',
                            data: trendValues,
                            backgroundColor: 'rgba(24, 55, 93, 0.1)',
                            borderColor: '#18375d',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.35,
                            pointRadius: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: true, position: 'top' } },
                        scales: { y: { beginAtZero: true } }
                    }
                });

                const vals = Array.isArray(trendValues) ? trendValues.filter(v => typeof v === 'number') : [];
                const last = vals.length ? vals[vals.length - 1] : 0;
                const avg = vals.length ? (vals.reduce((a,b)=>a+b,0) / vals.length) : 0;
                const prevAvg = vals.length > 6 ? (vals.slice(0, Math.max(1, vals.length - 6)).reduce((a,b)=>a+b,0) / Math.max(1, vals.length - 6)) : avg;
                const delta = prevAvg ? ((avg - prevAvg) / prevAvg) * 100 : 0;
                const summary = `Current average is ${avg.toFixed(1)}L/day (last point: ${last.toFixed ? last.toFixed(1) : last}L). ${delta >= 0 ? 'Up' : 'Down'} ${Math.abs(delta).toFixed(1)}% vs prior period.`;
                const summaryEl = document.getElementById('analysisSummaryText');
                if (summaryEl) summaryEl.textContent = summary;
            } catch (e) {
                console.warn('Trend chart build failed:', e);
            }
            
            console.log('Modal populated successfully');
            
        } else {
            console.error('API returned error:', data.message);
            modal.find('.modal-body').html('<div class="text-center text-danger"><i class="fas fa-exclamation-triangle fa-2x"></i><p class="mt-2">Failed to load farmer details</p><p class="small">' + (data.message || 'Unknown error') + '</p></div>');
            showNotification(data.message || 'Failed to load farmer details', 'error');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        modal.find('.modal-body').html('<div class="text-center text-danger"><i class="fas fa-exclamation-triangle fa-2x"></i><p class="mt-2">An error occurred while loading farmer details</p><p class="small">' + error.message + '</p></div>');
        showNotification('An error occurred while loading farmer details', 'error');
    });
}


function deleteFarmer(farmerId) {
    document.getElementById('deleteFarmerId').textContent = 'F' + String(farmerId).padStart(3, '0');
    document.getElementById('deleteFarmerForm').action = `{{ route('admin.analysis.delete-farmer', ':id') }}`.replace(':id', farmerId);
    $('#confirmDeleteModal').modal('show');
}

// Export functions
function exportCSV() {
    const tableData = dataTable.data().toArray();
    const csvData = [];
    
    // Add headers (excluding Actions column)
    const headers = ['Farmer ID', 'Name', 'Email', 'Phone', 'Location', 'Status'];
    csvData.push(headers.join(','));
    
    // Add data rows (excluding Actions column)
    tableData.forEach(row => {
        const rowData = [];
        for (let i = 0; i < row.length - 1; i++) {
            let cellText = '';
            if (row[i]) {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = row[i];
                cellText = tempDiv.textContent || tempDiv.innerText || '';
                cellText = cellText.replace(/\s+/g, ' ').trim();
            }
            if (cellText.includes(',') || cellText.includes('"') || cellText.includes('\n')) {
                cellText = '"' + cellText.replace(/"/g, '""') + '"';
            }
            rowData.push(cellText);
        }
        csvData.push(rowData.join(','));
    });
    
    const csvContent = csvData.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', `FarmersAnalysis_Report_${downloadCounter}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    downloadCounter++;
}
function exportPDF() {
    try {
        // Force custom PDF generation to match superadmin styling
        // Don't fall back to DataTables PDF export as it has different styling
        
        const tableData = dataTable.data().toArray();
        const pdfData = [];
        
        const headers = ['Farmer ID', 'Name', 'Email', 'Phone', 'Location', 'Status'];
        
        tableData.forEach(row => {
            const rowData = [];
            for (let i = 0; i < row.length - 1; i++) {
                let cellText = '';
                if (row[i]) {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = row[i];
                    cellText = tempDiv.textContent || tempDiv.innerText || '';
                    cellText = cellText.replace(/\s+/g, ' ').trim();
                }
                rowData.push(cellText);
            }
            pdfData.push(rowData);
        });
        
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape', 'mm', 'a4');
        
        // Set title
        doc.setFontSize(18);
        doc.text('Admin Farmers Analysis Report', 14, 22);
        doc.setFontSize(12);
        doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 14, 32);
        
        doc.autoTable({
            head: [headers],
            body: pdfData,
            startY: 40,
            styles: {
                fontSize: 8,
                cellPadding: 2
            },
            headStyles: {
                fillColor: [24, 55, 93],
                textColor: 255,
                fontStyle: 'bold'
            },
            alternateRowStyles: {
                fillColor: [245, 245, 245]
            }
        });
        
        // Save PDF with counter
        doc.save(`Admin_FarmersAnalysisReport_${downloadCounter}.pdf`);
        
        // Increment download counter
        downloadCounter++;
        
        showNotification('PDF exported successfully!', 'success');
        
    } catch (error) {
        console.error('Error generating PDF:', error);
        showNotification('Error generating PDF. Falling back to DataTables export.', 'warning');
        dataTable.button('.buttons-pdf').trigger();
    }
}
function printTable() {
    try {
        const tableData = dataTable.data().toArray();
        
        if (!tableData || tableData.length === 0) {
            showNotification('No data available to print', 'warning');
            return;
        }
        
        // Create print content directly in current page
        const originalContent = document.body.innerHTML;
        
        let printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #18375d; margin-bottom: 5px;">Farmers Analysis Report</h1>
                    <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                <table border="3" style="border-collapse: collapse; width: 100%; border: 3px solid #000;">
                    <thead>
                        <tr>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Farmer ID</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Name</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Email</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Phone</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Location</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Status</th>
                        </tr>
                    </thead>
                    <tbody>`;
        
        // Add data rows (excluding Actions column)
        tableData.forEach(row => {
            printContent += '<tr>';
            for (let i = 0; i < row.length - 1; i++) { // Skip last column (Actions)
                let cellText = '';
                if (row[i]) {
                    // Remove HTML tags and get clean text
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = row[i];
                    cellText = tempDiv.textContent || tempDiv.innerText || '';
                    // Clean up the text
                    cellText = cellText.replace(/\s+/g, ' ').trim();
                }
                printContent += `<td style="border: 3px solid #000; padding: 10px; text-align: left;">${cellText}</td>`;
            }
            printContent += '</tr>';
        });
        
        printContent += `
                    </tbody>
                </table>
            </div>`;
        
        // Replace page content with print content
        document.body.innerHTML = printContent;
        
        // Print the page
        window.print();
        
        // Restore original content after print dialog closes
        setTimeout(() => {
            document.body.innerHTML = originalContent;
            // Re-initialize any JavaScript that might be needed
            location.reload(); // Reload to restore full functionality
        }, 100);
        
    } catch (error) {
        console.error('Error in print function:', error);
        showNotification('Error generating print. Please try again.', 'danger');
        
        // Fallback to DataTables print
        try {
            dataTable.button('.buttons-print').trigger();
        } catch (fallbackError) {
            console.error('Fallback print also failed:', fallbackError);
            showNotification('Print failed. Please try again.', 'danger');
        }
    }
}

function exportPNG() {
    const originalTable = document.getElementById('dataTable');
    const tempTable = originalTable.cloneNode(true);
    
    const headerRow = tempTable.querySelector('thead tr');
    if (headerRow) {
        const lastHeaderCell = headerRow.lastElementChild;
        if (lastHeaderCell) {
            lastHeaderCell.remove();
        }
    }
    
    const dataRows = tempTable.querySelectorAll('tbody tr');
    dataRows.forEach(row => {
        const lastDataCell = row.lastElementChild;
        if (lastDataCell) {
            lastDataCell.remove();
        }
    });
    
    tempTable.style.position = 'absolute';
    tempTable.style.left = '-9999px';
    tempTable.style.top = '-9999px';
    document.body.appendChild(tempTable);
    
    html2canvas(tempTable, {
        scale: 2,
        backgroundColor: '#ffffff',
        width: tempTable.offsetWidth,
        height: tempTable.offsetHeight
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = `FarmersAnalysis_Report_${downloadCounter}.png`;
        link.href = canvas.toDataURL("image/png");
        link.click();
        
        downloadCounter++;
        document.body.removeChild(tempTable);
    }).catch(error => {
        console.error('Error generating PNG:', error);
        if (document.body.contains(tempTable)) {
            document.body.removeChild(tempTable);
        }
        showNotification('Error generating PNG export', 'danger');
    });
}

function exportModalCSV() {
    const farmId = $('#modalFarmId').text();
    const chart = chartInstance;
    if (!chart) return;
    
    const labels = chart.data.labels;
    const data = chart.data.datasets[0].data;
    const headers = ['Month', 'Milk Production (liters)'];
    const rows = labels.map((month, i) => [month, data[i]]);

    let csvContent = "data:text/csv;charset=utf-8,"
        + headers.join(",") + "\n"
        + rows.map(e => e.join(",")).join("\n");

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", farmId + "_production_analysis.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function exportModalPNG() {
    const canvas = document.getElementById('lineChart');
    const link = document.createElement('a');
    link.download = 'production_analysis_chart.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
}

async function exportModalPDF() {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF();

    pdf.setFontSize(18);
    pdf.text("Farm Productivity Analysis", 14, 22);

    const farmId = $('#modalFarmId').text();
    pdf.setFontSize(14);
    pdf.text(`Farm ID: ${farmId}`, 14, 35);

    const canvas = document.getElementById('lineChart');
    const imgData = canvas.toDataURL('image/png');
    pdf.addImage(imgData, 'PNG', 15, 45, 180, 100);

    const analysisText = document.getElementById('analysisContent').textContent;
    pdf.setFontSize(12);
    const splitText = pdf.splitTextToSize(analysisText, 180);
    pdf.text(splitText, 14, 155);

    pdf.save(farmId + '_production_analysis.pdf');
}

function printProductivity() {
    const modalBody = document.querySelector('#productivityModal .modal-body').innerHTML;
    const originalContents = document.body.innerHTML;

    document.body.innerHTML = `
        <div style="padding: 20px;">
            <h2>Farm Productivity Analysis</h2>
            ${modalBody}
        </div>
    `;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}

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
</script>
@endpush
