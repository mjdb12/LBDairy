@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers - Livestock Analysis')

@push('styles')
<style>
/* Cache buster: {{ time() }} */
    /* Table layout styling to match superadmin */
    /* Ensure the table container and scroll area are correctly styled */
    .table-responsive {
        overflow-x: auto !important;
        overflow-y: visible !important;
        min-width: 100%;
        position: relative;
        border-radius: 8px;
        clear: both; /* Ensure it clears properly */
        margin-top: 0.5rem; /* Add space above the scroll area */
    }
    
    /* Make the DataTables scroll body very prominent to indicate the scrollable area */
    .dataTables_scrollBody {
        overflow-x: auto !important;
        overflow-y: visible !important; /* Allow vertical overflow if needed */
        clear: both !important;
        position: relative !important;
        margin-top: 0.5rem !important;
        /* Padding */
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
        background-color: transparent !important;
    }
    /* Style the horizontal scrollbar itself */
    .dataTables_scrollBody::-webkit-scrollbar {
        height: 16px !important; /* Make it thick enough to see */
    }
    
    .dataTables_scrollBody::-webkit-scrollbar-track {
        background: #f1f1f1 !important;
        border-radius: 8px !important;
    }
    
    .dataTables_scrollBody::-webkit-scrollbar-thumb {
        background: #18375d !important; /* Dark blue thumb */
        border-radius: 8px !important;
    }
    
    .dataTables_scrollBody::-webkit-scrollbar-thumb:hover {
        background: #0f2a4a !important; /* Darker blue on hover */
    }
    
    
    .table-responsive::-webkit-scrollbar {
        height: 12px; /* Make scrollbar visible but not too prominent */
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: #c1c1c1; /* Gray thumb */
        border-radius: 4px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8; /* Darker gray on hover */
    }
    
    /* Also style DataTables scroll body scrollbar */
    .dataTables_scrollBody::-webkit-scrollbar {
        height: 12px;
    }
    
    .dataTables_scrollBody::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .dataTables_scrollBody::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    .dataTables_scrollBody::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    
    /* Style the scroll body */
    #livestockTable {
        border-collapse: collapse;
        margin-bottom: 0;
        table-layout: fixed !important;
        width: auto !important;           /* allow natural width for horizontal scroll */
        min-width: 1400px !important;     /* ensure overflow beyond viewport */
    }

    
    /* Ensure DataTables scroll body has proper overflow */
    .dataTables_scrollBody {
        overflow-x: auto !important;
        clear: both;
        margin-top: 1rem; /* Add space below pagination */
    }
    
    /* Fix for DataTables wrapper */
    .dataTables_wrapper {
        clear: both;
        width: 100% !important;
    }
    
    /* Ensure proper stacking order */
    .dataTables_wrapper::after {
        content: "";
        display: table;
        clear: both;
    }
    
    /* Force scroll container to be below pagination */
    .dataTables_scroll {
        clear: both !important;
        margin-top: 1rem !important; /* Space below pagination */
        position: relative !important;
    }
    
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.075);
    }
    
   
    
    /* Badge styling - matching Inventory Table */
    /* User ID link styling - superadmin theme */
    .user-id-link {
        color: #18375d;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        background-color: rgba(24, 55, 93, 0.1);
        border: 1px solid rgba(24, 55, 93, 0.2);
        display: inline-block;
        text-align: center;
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
        text-decoration: none;
    }
    #livestockTable .badge {
        border-radius: 20px !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        padding: 0.4rem 0.8rem !important;
        text-align: center !important;
        margin: 0 auto !important;
        display: inline-block !important;
        width: fit-content !important;
    }
    
    #livestockTable .badge-primary {
        background: linear-gradient(135deg, #4e73df, #3c5aa6) !important;
        color: white !important;
    }
    
    #livestockTable .badge-success {
        background: linear-gradient(135deg, #1cc88a, #17a673) !important;
        color: white !important;
    }
    
    #livestockTable .badge-warning {
        background: linear-gradient(135deg, #f6c23e, #f4b619) !important;
        color: white !important;
    }
    
    #livestockTable .badge-info {
        background: linear-gradient(135deg, #36b9cc, #2a96a5) !important;
        color: white !important;
    }
    
    #livestockTable .badge-danger {
        background: linear-gradient(135deg, #e74a3b, #d52a1a) !important;
        color: white !important;
    }
    
    #livestockTable .badge-secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268) !important;
        color: white !important;
    }
    
    .btn-group .btn {
        margin-right: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    /* Search and button group alignment - matching Super Admin */
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
    

    /* Custom styles for our specific DataTables info and pagination containers */
    .dataTables_customInfo,
    .dataTables_customPagination {
        text-align: left !important;
        padding-top: 0.75rem !important;
        clear: both !important; /* Ensure they are below the table */
        width: 100% !important; /* Take full width */
    }
    
    /* Add a top border to visually separate the controls from the table */
    .dataTables_customInfo {
        border-top: 2px solid #18375d !important; /* More prominent border */
        margin-top: 1rem !important;
        padding-top: 0.75rem !important;
        background-color: #f8f9fa !important; /* Light background for visibility */
    }
    
    .dataTables_customPagination {
        padding-bottom: 1rem !important; /* Add some space below pagination */
    }
    
    /* Style the actual info and pagination elements inside our custom containers */
    .dataTables_customInfo div.dataTables_info,
    .dataTables_customPagination div.dataTables_paginate {
        text-align: left !important;
        display: block !important;
    }
    
    /* Ensure the entire DataTables wrapper is clear */
    .dataTables_wrapper {
        clear: both !important;
        width: 100% !important;
    }
    
    /* Ensure table layout is fixed for better control */
    #livestockTable {
        table-layout: fixed !important;
    }
    
    .dataTables_wrapper .dataTables_length {
        float: left !important;
        margin-bottom: 1rem;
    }
    
    .dataTables_wrapper .dataTables_filter {
        float: left !important;
        margin-bottom: 1rem;
        margin-left: 1rem;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        margin-left: 0.5rem;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        padding: 0.375rem 0.75rem;
    }
    
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        padding: 0.25rem 0.5rem;
        margin: 0 0.5rem;
    }
    
    .dataTables_wrapper .dataTables_info {
        padding-top: 0.75rem !important;
    }
    
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 0.75rem !important;
    }
    
    .dataTables_wrapper::after {
        content: "";
        display: table;
        clear: both;
    }
    
    .dataTables_wrapper {
        clear: both;
    }
    
    .dataTables_wrapper .dataTables_scroll {
        clear: both;
    }
    
    /* Simple table responsive styling like inventory table */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-top: 1rem; /* Space below pagination */
    }
    
    /* Ensure table has minimum width for horizontal scrolling */
    #livestockTable {
        min-width: 1750px;
        width: 1750px; /* Extra wide for Actions column */
    }
    
    /* Style the horizontal scrollbar - Clean version */
    .table-responsive::-webkit-scrollbar {
        height: 12px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 6px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 6px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Style the horizontal scrollbar itself */
    .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar {
        height: 12px; /* Make scrollbar visible */
    }
    
    .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 6px;
    }
    
    .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar-thumb {
        background: #18375d; /* Dark blue thumb */
        border-radius: 6px;
    }
    
    .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar-thumb:hover {
        background: #0f2a4a; /* Darker blue on hover */
    }
    
    /* DataTables Pagination Styling - FIXED (matching Super Admin) */
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
    
    .dataTables_wrapper .dataTables_scroll {
        margin-top: 0.5rem;
    }

.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

 /* Apply consistent styling for Pending Farmers and Active Farmers tables */
#livestockTable th,
#livestockTable td,
#livestockTable th,
#livestockTable td {
    vertical-align: middle;
    padding: 0.75rem;
    text-align: center;
    border: 1px solid #dee2e6;
    white-space: nowrap;
    overflow: visible;
}

/* Ensure all table headers have consistent styling */
#livestockTable thead th,
#activeFarmersTable thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: bold;
    color: #495057;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    text-align: center;
    vertical-align: middle;
    position: relative;
    white-space: nowrap;
}

/* Fix DataTables sorting button overlap */
#livestockTable thead th.sorting,
#livestockTable thead th.sorting_asc,
#livestockTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Remove default DataTables sort indicators to prevent mismatch with superadmin */
#livestockTable thead th.sorting::before,
#livestockTable thead th.sorting_asc::before,
#livestockTable thead th.sorting_desc::before,
#livestockTable thead th.sorting::after,
#livestockTable thead th.sorting_asc::after,
#livestockTable thead th.sorting_desc::after,
.dataTables_wrapper table.dataTable thead th.sorting:before,
.dataTables_wrapper table.dataTable thead th.sorting_asc:before,
.dataTables_wrapper table.dataTable thead th.sorting_desc:before,
.dataTables_wrapper table.dataTable thead th.sorting:after,
.dataTables_wrapper table.dataTable thead th.sorting_asc:after,
.dataTables_wrapper table.dataTable thead th.sorting_desc:after {
    display: none !important;
}

/* Allow table to scroll horizontally if too wide */
.table-responsive {
    overflow-x: auto;
}

/* Make table cells wrap instead of forcing them all inline */
#livestockTable td, 
#livestockTable th {
    white-space: normal !important;  /* allow wrapping */
    vertical-align: middle;
}

/* Make sure action buttons don’t overflow - standard action-buttons wrapper */
#livestockTable td .action-buttons {
    display: flex;
    flex-wrap: nowrap; /* keep them in one line */
    gap: 0.5rem;    /* standard spacing */
    justify-content: center;
    min-width: 260px;
    flex-shrink: 0;
}

#livestockTable td .btn-action {
    flex: 1 1 auto; /* allow buttons to shrink/grow */
    min-width: 90px; /* prevent too tiny buttons */
    text-align: center;
}

/* Enforce last column min width like other tables */
#livestockTable th:last-child,
#livestockTable td:last-child {
    min-width: 260px;
    text-align: center;
    vertical-align: middle;
}

/* Center the Name column contents even with internal flex markup */
#livestockTable td:nth-child(2) .d-flex {
    justify-content: center;
}
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
    <h1>
        <i class="fas fa-chart-line"></i>
        Livestock Analysis Dashboard
    </h1>
    <p>Comprehensive analysis of livestock performance and health metrics</p>
</div>

<!-- Statistics Grid -->
<div class="row fade-in">
    <!-- Total Livestock -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Livestock</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLivestock }}</div>
                </div>
                <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                    <i class="fas fa-leaf fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Healthy Animals -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Healthy Animals</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $healthyAnimals }}</div>
                </div>
                <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                    <i class="fas fa-heart fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Breeding Age -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Breeding Age</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $breedingAge }}</div>
                </div>
                <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                    <i class="fas fa-dna fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Under Treatment -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Under Treatment</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $underTreatment }}</div>
                </div>
                <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                    <i class="fas fa-stethoscope fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-xl-8 col-lg-7 mb-3">
        <div class="card shadow-sm">
            <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-chart-line"></i>
                    Performance Trends
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="position: relative; height: 320px;">
                    <canvas id="livestockPerformanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5 mb-3">
        <div class="card shadow-sm">
            <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-chart-pie"></i>
                    Health Distribution
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-pie" style="position: relative; height: 280px;">
                    <canvas id="healthStatusChart"></canvas>
                </div>
                <div class="mt-3 text-center small">
                    <span class="mr-3">
                        <i class="fas fa-circle text-success"></i> Healthy
                    </span>
                    <span class="mr-3">
                        <i class="fas fa-circle text-warning"></i> Under Treatment
                    </span>
                    <span class="mr-3">
                        <i class="fas fa-circle text-danger"></i> Critical
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Livestock Analysis Table -->
<div class="card shadow mb-4">
    <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
        <h6 class="m-0 font-weight-bold">
            <i class="fas fa-list-alt"></i>
            Livestock Analysis Overview
        </h6>
    </div>
    <div class="card-body">
        <div class="search-controls mb-3 d-flex flex-column flex-md-row justify-content-between align-items-md-end">
            <div class="input-group mb-2 mb-md-0" style="max-width: 300px;">  
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
                <input type="text" class="form-control" placeholder="Search livestock..." id="livestockSearch">
            </div>
            <div class="d-flex flex-column flex-sm-row align-items-center">
                <button class="btn-action btn-action-refresh" onclick="refreshLivestockData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <div class="dropdown">
                    <button class="btn-action btn-action-tool" type="button" data-toggle="dropdown">
                        <i class="fas fa-tools"></i> Tools
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" onclick="printLivestockTable()">
                            <i class="fas fa-print"></i> Print Table
                        </a>
                        <a class="dropdown-item" href="#" onclick="exportCSV('livestockTable')">
                            <i class="fas fa-file-csv"></i> Download CSV
                        </a>
                        <a class="dropdown-item" href="#" onclick="exportPDF('livestockTable')">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </a>
                        <a class="dropdown-item" href="#" onclick="exportPNG('livestockTable')">
                            <i class="fas fa-image"></i> Download PNG
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="livestockTable" cellspacing="0" style="min-width: 1280px; margin-bottom: 0; background-color: white;">
                <thead class="thead-light">
                    <tr>
                        <th>Livestock ID</th>
                        <th>Name</th>
                        <th>Breed</th>
                        <th>Age (months)</th>
                        <th>Health Score</th>
                        <th>Avg. Production (L/day)</th>
                        <th>Breeding Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($livestockData ?? [] as $animal)
                    <tr class="livestock-row" data-status="{{ $animal['health_status'] ?? 'healthy' }}" data-type="{{ $animal['breed'] ?? 'unknown' }}">
                        <td><a href="#" class="user-id-link" onclick="viewLivestockAnalysis('{{ $animal['id'] ?? $loop->iteration }}')">{{ $animal['livestock_id'] ?? 'LS' . str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}</a></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center mr-2">
                                    <i class="fas fa-cow text-white"></i>
                                </div>
                                {{ $animal['type'] ?? 'Livestock ' . $loop->iteration }}
                            </div>
                        </td>
                        <td><span class="badge badge-info">{{ $animal['breed'] ?? 'Holstein' }}</span></td>
                        <td>{{ $animal['age'] ?? rand(12, 60) }}</td>
                        <td>
                            @php
                                $healthScore = $animal['health_score'] ?? rand(85, 100);
                                $healthClass = $healthScore >= 90 ? 'success' : ($healthScore >= 80 ? 'warning' : 'danger');
                            @endphp
                            <span class="badge badge-{{ $healthClass }}">{{ $healthScore }}%</span>
                        </td>
                        <td>{{ $animal['avg_production'] ?? rand(15, 25) }}L</td>
                        <td>
                            @php
                                $breedingStatus = $animal['health_status'] === 'healthy' ? 'Active' : ($animal['health_status'] === 'under_treatment' ? 'Under Treatment' : 'Inactive');
                                $statusClass = $breedingStatus === 'Active' ? 'success' : ($breedingStatus === 'Under Treatment' ? 'warning' : 'secondary');
                            @endphp
                            <span class="badge badge-{{ $statusClass }}">{{ $breedingStatus }}</span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn-action btn-action-ok" onclick="viewLivestockAnalysis('{{ $animal['id'] ?? $loop->iteration }}')" title="Analysis">
                                    <i class="fas fa-chart-line"></i> Analysis
                                </button>
                                <button class="btn-action btn-action-edit" onclick="viewLivestockHistory('{{ $animal['id'] ?? $loop->iteration }}')" title="History">
                                    <i class="fas fa-history"></i> History
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center text-muted">N/A</td>
                        <td class="text-center text-muted">No livestock data available</td>
                        <td class="text-center text-muted">N/A</td>
                        <td class="text-center text-muted">N/A</td>
                        <td class="text-center text-muted">N/A</td>
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

<!-- Individual Livestock Analysis Modal -->
<div class="modal fade admin-modal" id="livestockAnalysisModal" tabindex="-1" role="dialog" aria-labelledby="livestockAnalysisLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

        <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-chart-line fa-2x "></i>
                </div>
                <h5 class="fw-bold mb-1">Individual Livestock Analysis </h5>
                <p class="text-muted mb-0 small text-center">Below are the complete analysis of the selected livestock.</p>
            </div>

      <!-- Body -->
      <div class="modal-body">
        <div id="livestockAnalysisContent" >
          <!-- Dynamic details injected here -->
        </div>
      </div>

      <!-- Footer -->

        <div class="modal-footer justify-content-center mt-4">
            <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
            <button type="button" class="btn-modern btn-approves" onclick="printLivestockAnalysis()">
                <i class="fas fa-print"></i> Print Analysis
            </button>
        </div>

    </div>
  </div>
</div>

<!-- Livestock History Modal -->
<div class="modal fade livestock-modal" id="livestockHistoryModal" tabindex="-1" role="dialog" aria-labelledby="livestockHistoryLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content smart-detail p-4">

      <!-- Icon + Header -->
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle">
          <i class="fas fa-history fa-2x"></i>
        </div>
        <h5 class="fw-bold mb-1">Livestock History</h5>
        <p class="text-muted mb-0 small text-center">Below is the complete history record of the selected livestock.</p>
      </div>

        <div class="modal-body">
        <div id="livestockHistoryContent" >
          <!-- Dynamic details injected here -->
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer justify-content-center mt-4">
        <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
        <button type="button" class="btn-modern btn-approves" onclick="printLivestockHistory()">
          <i class="fas fa-print"></i> Print History
        </button>
      </div>

    </div>
  </div>
</div>


<!-- Health Record Modal -->
<div class="modal fade livestock-modal" id="healthRecordModal" tabindex="-1" role="dialog" aria-labelledby="healthRecordLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content smart-detail p-4">

      <!-- Icon + Header -->
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle">
          <i class="fas fa-notes-medical fa-2x"></i>
        </div>
        <h5 class="fw-bold mb-1">Health Record Details</h5>
        <p class="text-muted mb-0 small text-center">Below is the complete health record of the selected livestock.</p>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <div id="healthRecordModalBody">
          <!-- Dynamic health record details injected here -->
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer justify-content-center mt-4">
        <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>


<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 right-0 p-3" style="z-index: 1050;">
</div>
@endsection
@push('styles')
<style>
    /* Custom Blue-Green Button for Task Submit - NO GLASS EFFECTS */
html body #viewbtn.btn-primary,
#viewbtn.btn-primary,
#viewbtn.btn,
#viewbtn {
    background-color: #18375d !important;
    background: #18375d !important;
    border-color: #18375d !important;
    color: #fff !important;
}

/* Hover and Focus State */
html body #viewbtn.btn-primary:hover,
html body #viewbtn.btn-primary:focus,
#viewbtn.btn-primary:hover,
#viewbtn.btn-primary:focus,
#viewbtn:hover,
#viewbtn:focus,
#viewbtn.btn:hover,
#viewbtn.btn:focus {
    background-color: #fca700 !important;
    background: #fca700 !important;
    border-color: #fca700 !important;
    color: #fff !important;
    border: 2px solid #fca700 !important;
    transform: translateY(-1px);
    box-shadow: none !important;
    filter: none !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    opacity: 1 !important;
    text-shadow: none !important;
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
    background-color: #e69500 !important;
    border-color: #e69500 !important;
    color: white !important;
}
  /* ===== Edit Button ===== */
.btn-action-ok {
    background-color: white !important;
    border: 1px solid #18375d !important;
    color: #18375d !important; /* blue text */
}

.btn-action-ok:hover {
    background-color: #18375d !important; /* yellow on hover */
    border: 1px solid #18375d !important;
    color: white !important;
}

.btn-action-edit {
    background-color: white !important;
    border: 1px solid #387057 !important;
    color: #387057 !important; 
}

.btn-action-edit:hover {
    background-color: #387057 !important; /* yellow on hover */
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
</style>
@push('scripts')
<!-- DataTables Core -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js?v={{ time() }}&ver=3.0"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js?v={{ time() }}&ver=3.0"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js?v={{ time() }}&ver=3.0"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js?v={{ time() }}&ver=3.0"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js?v={{ time() }}&ver=3.0"></script>

<!-- Required libraries for PDF/Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js?v={{ time() }}&ver=3.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js?v={{ time() }}&ver=3.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js?v={{ time() }}&ver=3.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js?v={{ time() }}&ver=3.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js?v={{ time() }}&ver=3.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js?v={{ time() }}&ver=3.0"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js?v={{ time() }}&ver=3.0"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Livestock Analysis - Simple horizontal scrollbar implementation');
    
    // Initialize DataTable with simple configuration like inventory table
    const table = $('#livestockTable').DataTable({
        dom: 'Bfrtip',
        searching: true,
        paging: true,
        info: true,
        ordering: true,
        lengthChange: false,
        pageLength: 10,
        autoWidth: false,
        scrollX: false,
        columnDefs: [
            { width: '100px', targets: 0 }, // Livestock ID
            { width: '180px', targets: 1 }, // Name
            { width: '140px', targets: 2 }, // Breed
            { width: '140px', targets: 3 }, // Age (months)
            { width: '160px', targets: 4 }, // Health Score
            { width: '200px', targets: 5 }, // Avg. Production
            { width: '160px', targets: 6 }, // Breeding Status
            { width: '260px', targets: 7, orderable: false }, // Actions
            { targets: '_all', className: 'text-center align-middle' }
        ],
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Livestock_Analysis_Report',
                className: 'd-none',
                exportOptions: { columns: [0,1,2,3,4,5,6], modifier: { page: 'all' } }
            },
            {
                extend: 'pdfHtml5',
                title: 'Livestock_Analysis_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none',
                exportOptions: { columns: [0,1,2,3,4,5,6], modifier: { page: 'all' } }
            },
            {
                extend: 'print',
                title: 'Livestock Analysis Report',
                className: 'd-none',
                exportOptions: { columns: [0,1,2,3,4,5,6], modifier: { page: 'all' } }
            }
        ],
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No data available</h5><p>There are no records to display at this time.</p></div>',
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        drawCallback: function(settings) {
            setTimeout(forcePaginationLeft, 10);
            setTimeout(forceCenterAlignment, 10);
        },
        initComplete: function(settings, json) {
            setTimeout(forcePaginationLeft, 100);
            setTimeout(forceCenterAlignment, 100);
        }
    });
    table.columns.adjust();

    // The custom DOM in commonConfig should handle layout correctly.
    // Hide any default elements that might interfere.
    $('.dataTables_filter').hide(); // This should already be hidden by dom: 't...'
    $('.dt-buttons').hide(); // This should already be hidden by className: 'd-none'

    // Custom search functionality
    $('#livestockSearch').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Hide default DataTables search bar
    $('.dataTables_filter').hide();
    
    // Multiple attempts to ensure pagination stays left and content is centered
    setTimeout(forcePaginationLeft, 200);
    setTimeout(forcePaginationLeft, 500);
    setTimeout(forcePaginationLeft, 1000);
    
    // Multiple attempts to ensure center alignment
    setTimeout(forceCenterAlignment, 200);
    setTimeout(forceCenterAlignment, 500);
    setTimeout(forceCenterAlignment, 1000);
    setTimeout(forceCenterAlignment, 1500);
    
    // Custom search functionality
    $('#livestockSearch').on('keyup', function() {
        table.search(this.value).draw();
    });
    
    // Monitor for any DOM changes that might affect pagination and alignment
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' && 
                mutation.target.className && 
                mutation.target.className.includes('dataTables')) {
                setTimeout(forcePaginationLeft, 50);
                setTimeout(forceCenterAlignment, 50);
            }
        });
    });
    
    // Observe the DataTables wrapper
    const wrapper = document.querySelector('.dataTables_wrapper');
    if (wrapper) {
        observer.observe(wrapper, {
            childList: true,
            subtree: true
        });
    }
    
    console.log('DataTables initialized with custom layout. Info and Pagination should be below the table.');

    // Initialize Charts with a small delay to ensure DOM is fully loaded
    setTimeout(function() {
        initializeCharts();
    }, 100);
});

function initializeCharts() {
    try {
        // Livestock Performance Chart
        const performanceCtx = document.getElementById('livestockPerformanceChart');
        if (performanceCtx) {
            const performanceChart = new Chart(performanceCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_keys($performanceMetrics['production'] ?? [])) !!},
                    datasets: [{
                        label: 'Average Milk Production (L/day)',
                        data: {!! json_encode(array_values($performanceMetrics['production'] ?? [])) !!},
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }, {
                        label: 'Health Score (%)',
                        data: {!! json_encode(array_values($performanceMetrics['health_score'] ?? [])) !!},
                        borderColor: '#1cc88a',
                        backgroundColor: 'rgba(28, 200, 138, 0.05)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Health Status Chart
        const healthCtx = document.getElementById('healthStatusChart');
        if (healthCtx) {
            const healthChart = new Chart(healthCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_keys($healthDistribution)) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($healthDistribution)) !!},
                        backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b', '#36b9cc'],
                        hoverBackgroundColor: ['#17a673', '#f4b619', '#e02424', '#2c9faf'],
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
        }
    } catch (error) {
        console.error('Error initializing charts:', error);
    }
}

// Individual livestock analysis functions
function viewLivestockAnalysis(livestockId) {
    // Load individual livestock analysis
    $.ajax({
        url: `/farmer/livestock/${livestockId}/analysis`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $('#livestockAnalysisContent').html(response.html);
                // Ensure modal is attached to body and show it
                const $m = $('#livestockAnalysisModal');
                if (!$m.parent().is('body')) { $m.appendTo('body'); }
                $m.modal({ backdrop: true, keyboard: true });
                $m.modal('show');
                // Initialize charts using response data
                setTimeout(function(){
                    initializeIndividualAnalysisChartsFromResponse(response.data || {});
                }, 0);
            } else {
                showToast('Failed to load livestock analysis', 'error');
            }
        },
        error: function() {
            showToast('Error loading livestock analysis', 'error');
        }
    });
}

// Keep references to destroy/recreate between openings
let individualProductionChartInstance = null;
let individualProductionPieInstance = null;
let livestockHistoryChartInstance = null;

function initializeIndividualAnalysisChartsFromResponse(data) {
    try {
        if (typeof Chart === 'undefined') {
            console.warn('Chart.js not loaded');
            return;
        }
        // Normalize production data to labels and values
        const prodObj = (data && data.production_data) ? data.production_data : {};
        const labels = Array.isArray(prodObj)
            ? prodObj.map((_, idx) => String(idx + 1))
            : Object.keys(prodObj || {});
        // Sort labels if they look like YYYY-MM
        const sortedLabels = labels.slice().sort();
        const values = sortedLabels.map(k => {
            const v = Array.isArray(prodObj) ? prodObj[Number(k)] : prodObj[k];
            return typeof v === 'number' ? v : parseFloat(v) || 0;
        });
        const hasLineData = values.some(v => v > 0);

        // Destroy previous instances
        if (individualProductionChartInstance) { individualProductionChartInstance.destroy(); individualProductionChartInstance = null; }
        if (individualProductionPieInstance) { individualProductionPieInstance.destroy(); individualProductionPieInstance = null; }

        // Line chart
        const lineCanvas = document.getElementById('individualProductionChart');
        if (lineCanvas) {
            const container = lineCanvas.parentElement;
            const msgId = 'individualProductionChartEmptyMsg';
            const ensureMsg = (show) => {
                let m = document.getElementById(msgId);
                if (show) {
                    if (!m) {
                        m = document.createElement('div');
                        m.id = msgId;
                        m.className = 'text-center text-muted small mt-2';
                        m.textContent = 'No production data available';
                        container.appendChild(m);
                    }
                } else if (m) {
                    m.remove();
                }
            };
            if (!hasLineData) {
                lineCanvas.style.display = 'none';
                ensureMsg(true);
            } else {
                lineCanvas.style.display = '';
                ensureMsg(false);
                const ctx = lineCanvas.getContext('2d');
                individualProductionChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: sortedLabels,
                        datasets: [{
                            label: 'Monthly Average Production (L/day)',
                            data: values,
                            borderColor: '#4e73df',
                            backgroundColor: 'rgba(78, 115, 223, 0.05)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: true } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.1)' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        }

        // Pie chart buckets
        const high = values.filter(v => v > 20).length;
        const mid = values.filter(v => v >= 10 && v <= 20).length;
        const low = values.filter(v => v < 10).length;
        const pieCanvas = document.getElementById('individualProductionPie');
        if (pieCanvas) {
            const container = pieCanvas.parentElement;
            const msgId = 'individualProductionPieEmptyMsg';
            const ensureMsg = (show) => {
                let m = document.getElementById(msgId);
                if (show) {
                    if (!m) {
                        m = document.createElement('div');
                        m.id = msgId;
                        m.className = 'text-center text-muted small mt-2';
                        m.textContent = 'No production distribution data available';
                        container.appendChild(m);
                    }
                } else if (m) {
                    m.remove();
                }
            };
            if ((high + mid + low) === 0) {
                pieCanvas.style.display = 'none';
                ensureMsg(true);
            } else {
                pieCanvas.style.display = '';
                ensureMsg(false);
                const ctx2 = pieCanvas.getContext('2d');
                individualProductionPieInstance = new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: ['High Production', 'Medium Production', 'Low Production'],
                        datasets: [{
                            data: [high, mid, low],
                            backgroundColor: ['#b2f0d9ff', '#f7e2b0ff', '#fdc6c1ff'],
                            hoverBackgroundColor: ['#b2f0d9ff', '#f7e2b0ff', '#fdc6c1ff'],
                            hoverBorderColor: 'rgba(234, 236, 244, 1)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: true, position: 'bottom' } },
                        cutout: '60%'
                    }
                });
            }
        }
    } catch (e) {
        console.error('initializeIndividualAnalysisChartsFromResponse error:', e);
    }
}

// Cleanup on modal hide to avoid memory leaks
$(document).on('hidden.bs.modal', '#livestockAnalysisModal', function(){
    try {
        if (individualProductionChartInstance) { individualProductionChartInstance.destroy(); individualProductionChartInstance = null; }
        if (individualProductionPieInstance) { individualProductionPieInstance.destroy(); individualProductionPieInstance = null; }
    } catch (_) {}
});

$(document).on('hidden.bs.modal', '#livestockHistoryModal', function(){
    try {
        if (livestockHistoryChartInstance) { livestockHistoryChartInstance.destroy(); livestockHistoryChartInstance = null; }
    } catch (_) {}
});

// Ensure body scroll state remains correct when closing nested modals (e.g., healthRecordModal)
$(document).on('hidden.bs.modal', '#healthRecordModal', function(){
    if ($('#livestockHistoryModal').hasClass('show')) {
        $('body').addClass('modal-open');
    }
});
$(document).on('shown.bs.modal', '#healthRecordModal', function(){
    if ($('#livestockHistoryModal').hasClass('show')) {
        $('body').addClass('modal-open');
    }
});

function viewLivestockHistory(livestockId) {
    // Load livestock history
    $.ajax({
        url: `/farmer/livestock/${livestockId}/history`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $('#livestockHistoryContent').html(response.html);
                const $m = $('#livestockHistoryModal');
                if (!$m.parent().is('body')) { $m.appendTo('body'); }
                $m.modal({ backdrop: true, keyboard: true });
                $m.modal('show');
                setTimeout(function(){
                    try {
                        if (typeof Chart !== 'undefined') {
                            if (typeof livestockHistoryChartInstance !== 'undefined' && livestockHistoryChartInstance) { livestockHistoryChartInstance.destroy(); livestockHistoryChartInstance = null; }
                            const canvas = document.getElementById('productionHistoryChart');
                            if (canvas) {
                                const ctx = canvas.getContext('2d');
                                const arr = (response.data && response.data.production_history) ? response.data.production_history : [];
                                const entries = Array.isArray(arr) ? arr.map(function(it){ var d = it.production_date || it.date || ''; var dt = new Date(d); var key = isNaN(dt) ? String(d) : dt.toISOString(); var label = isNaN(dt) ? String(d) : dt.toLocaleDateString(undefined, { month:'short', day:'2-digit' }); var qty = parseFloat(it.milk_quantity) || 0; return { key:key, label:label, qty:qty }; }) : [];
                                entries.sort(function(a,b){ return a.key.localeCompare(b.key); });
                                const labels = entries.map(function(e){ return e.label; });
                                const values = entries.map(function(e){ return e.qty; });
                                livestockHistoryChartInstance = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: labels,
                                        datasets: [{
                                            label: 'Daily Milk Production (L)',
                                            data: values,
                                            borderColor: '#4e73df',
                                            backgroundColor: 'rgba(78, 115, 223, 0.05)',
                                            borderWidth: 2,
                                            fill: true,
                                            tension: 0.4
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: { legend: { display: true } },
                                        scales: {
                                            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.1)' } },
                                            x: { grid: { display: false } }
                                        }
                                    }
                                });
                            }
                        }
                    } catch (_) {}
                    try {
                        if ($ && $.fn && $.fn.DataTable) {
                            if (!$.fn.DataTable.isDataTable('#productionHistoryTable')) {
                                $('#productionHistoryTable').DataTable({
                                    pageLength: 10,
                                    order: [[0,'desc']],
                                    responsive: true,
                                    language: {
                                        emptyTable: '<div class="py-4 text-center text-muted"><i class="fas fa-tint fa-3x mb-3"></i><p>No production records found for this livestock.</p></div>',
                                        search: 'Search records:',
                                        lengthMenu: 'Show _MENU_ records per page',
                                        info: 'Showing _START_ to _END_ of _TOTAL_ records'
                                    }
                                });
                            }
                            if (!$.fn.DataTable.isDataTable('#healthHistoryTable')) {
                                $('#healthHistoryTable').DataTable({
                                    pageLength: 10,
                                    order: [[0,'desc']],
                                    responsive: true,
                                    columnDefs: [ { targets: -1, className: 'text-left' } ],
                                    language: {
                                        emptyTable: '<div class="py-4 text-center text-muted"><i class="fas fa-heartbeat fa-3x mb-3"></i><p>No health records found for this livestock.</p></div>',
                                        search: 'Search records:',
                                        lengthMenu: 'Show _MENU_ records per page',
                                        info: 'Showing _START_ to _END_ of _TOTAL_ records'
                                    }
                                });
                            } else {
                                // Ensure last column stays left-aligned on re-init
                                $('#healthHistoryTable').find('th:last-child, td:last-child').addClass('text-left');
                            }
                        }
                    } catch (_) {}
                    try {
                        // Delegated handler for Health tab View buttons
                        $(document).off('click', '#healthHistoryTable .btn-action-ok').on('click', '#healthHistoryTable .btn-action-ok', function(e){
                            e.preventDefault();
                            const toText = function(html){ var div = document.createElement('div'); div.innerHTML = html || ''; return (div.textContent || div.innerText || '').replace(/\s+/g,' ').trim(); };
                            let date = '', status = '', notes = '';
                            try {
                                if ($ && $.fn && $.fn.DataTable && $.fn.DataTable.isDataTable('#healthHistoryTable')) {
                                    const dt = $('#healthHistoryTable').DataTable();
                                    const $tr = $(this).closest('tr');
                                    const row = dt.row($tr.hasClass('child') ? $tr.prev() : $tr);
                                    const data = row.data();
                                    if (Array.isArray(data)) {
                                        date = toText(data[0] || '');
                                        status = toText(data[1] || '');
                                        notes = toText(data[2] || '');
                                    } else if (data) {
                                        date = toText(data.date || '');
                                        status = toText(data.status || '');
                                        notes = toText(data.notes || '');
                                    }
                                } else {
                                    const $cells = $(this).closest('tr').find('td');
                                    date = ($cells.eq(0).text() || '').trim();
                                    status = ($cells.eq(1).text() || '').trim();
                                    notes = ($cells.eq(2).text() || '').trim();
                                }
                            } catch(_) {}

                            const bodyHtml = `
                                <table class="table table-sm table-bordered mb-0">
                                    <tbody>
                                        <tr><th>Date</th><td>${date}</td></tr>
                                        <tr><th>Status</th><td>${status}</td></tr>
                                        <tr><th>Notes</th><td>${notes}</td></tr>
                                    </tbody>
                                </table>`;
                            $('#healthRecordModalBody').html(bodyHtml);
                            const $hm = $('#healthRecordModal');
                            if (!$hm.parent().is('body')) { $hm.appendTo('body'); }
                            $hm.modal({ backdrop: true, keyboard: true });
                            $hm.modal('show');
                        });
                    } catch (_) {}
                }, 0);
            } else {
                showToast('Failed to load livestock history', 'error');
            }
        },
        error: function() {
            showToast('Error loading livestock history', 'error');
        }
    });
}

function printLivestockAnalysis() {
    try {
        const container = document.getElementById('livestockAnalysisContent');
        if (!container || !container.innerHTML.trim()) {
            showToast('Nothing to print. Open the analysis first.', 'warning');
            return;
        }

        // Extract header info
        const headerTitleEl = container.querySelector('.smart-detail-header h5');
        const headerTitle = headerTitleEl ? (headerTitleEl.textContent || '').replace(/\s*–.*$/, '').trim() : 'Individual Livestock Analysis';
        const infoLineEl = container.querySelector('.smart-detail-header p');
        const infoLine = infoLineEl ? (infoLineEl.textContent || '').replace(/\s+/g, ' ').trim() : '';
        const parseField = (label) => {
            const re = new RegExp(label + '\\s*:\\s*([^|]+)');
            const m = infoLine.match(re);
            return m ? m[1].trim() : '';
        };
        const tagId = parseField('Tag ID');
        const type = parseField('Type');
        const breed = parseField('Breed');
        const age = parseField('Age');
        const healthScoreText = (container.querySelector('.smart-detail-header .progress strong') || {}).textContent || '';

        // Extract key metrics
        const metrics = [];
        container.querySelectorAll('.smart-metrics .metric-card').forEach(card => {
            const label = (card.querySelector('.metric-label') || {}).textContent || '';
            const value = (card.querySelector('.metric-value') || {}).textContent || '';
            if (label && value) metrics.push([label.trim(), value.replace(/\s+/g, ' ').trim()]);
        });

        // Extract insights
        const insights = [];
        container.querySelectorAll('.card-body .alert').forEach(alert => {
            const title = (alert.querySelector('.alert-heading') || {}).textContent || '';
            const msg = (alert.querySelector('p') || {}).textContent || '';
            if (title || msg) insights.push([title.trim(), msg.trim()]);
        });

        // Extract detailed info rows (any p with leading strong label)
        const detailRows = [];
        container.querySelectorAll('.card-body p').forEach(p => {
            const strong = p.querySelector('strong');
            if (strong) {
                const label = (strong.textContent || '').replace(/:$/, '').trim();
                const val = (p.textContent || '').replace(strong.textContent, '').replace(/^\s*[:\-]?\s*/, '').trim();
                if (label && val) detailRows.push([label, val]);
            }
        });

        // Build print CSS
        const css = `
            <style>
            @page { size: auto; margin: 12mm; }
            html, body { background: #fff !important; color: #000; font-family: Arial, sans-serif; }
            h2, h3 { color: #18375d; margin: 0 0 6px 0; }
            .meta { font-size: 12px; color: #333; margin-bottom: 12px; }
            .section { margin-bottom: 14px; }
            table { width: 100%; border-collapse: collapse; margin: 0 0 12px 0; }
            th, td { border: 3px solid #000; padding: 10px; text-align: left; vertical-align: top; }
            thead th { background: #f2f2f2; color: #18375d; }
            .noborder td { border: 0; padding: 2px 0; }
            .two-col { width: 50%; }
            .w-30 { width: 30%; } .w-70 { width: 70%; }
            .small { font-size: 12px; }
            </style>`;

        // Helpers
        const row = (k, v) => `<tr><td class="w-30">${k}</td><td class="w-70">${v}</td></tr>`;
        const rowsHtml = (arr) => arr.map(x => `<tr><td>${x[0]}</td><td>${x[1]}</td></tr>`).join('');

        // Sections
        const titleHtml = `
            <div style="text-align:center;margin-bottom:10px;">
                <h2>Individual Livestock Analysis</h2>
                <div class="meta">Generated: ${new Date().toLocaleString()}</div>
            </div>`;

        const basicInfoTable = `
            <table>
                <thead><tr><th colspan="2">Basic Information</th></tr></thead>
                <tbody>
                    ${row('Name', headerTitle || '')}
                    ${row('Tag ID', tagId)}
                    ${row('Type', type)}
                    ${row('Breed', breed)}
                    ${row('Age', age)}
                    ${row('Health Score', healthScoreText)}
                </tbody>
            </table>`;

        const metricsTable = metrics.length ? `
            <table>
                <thead><tr><th colspan="2">Key Metrics</th></tr><tr><th>Metric</th><th>Value</th></tr></thead>
                <tbody>${rowsHtml(metrics)}</tbody>
            </table>` : '';

        const insightsTable = insights.length ? `
            <table>
                <thead><tr><th colspan="2">Insights</th></tr><tr><th>Title</th><th>Details</th></tr></thead>
                <tbody>${rowsHtml(insights)}</tbody>
            </table>` : '';

        // Consolidated details from the info section
        const detailsTable = detailRows.length ? `
            <table>
                <thead><tr><th colspan="2">Detailed Information</th></tr><tr><th>Field</th><th>Value</th></tr></thead>
                <tbody>${rowsHtml(detailRows)}</tbody>
            </table>` : '';

        const html = css + `<div>${titleHtml}${basicInfoTable}${metricsTable}${insightsTable}${detailsTable}</div>`;

        if (typeof window.printElement === 'function') {
            const wrap = document.createElement('div');
            wrap.innerHTML = html;
            window.printElement(wrap);
        } else if (typeof window.openPrintWindow === 'function') {
            window.openPrintWindow(html, 'Livestock Analysis');
        } else {
            const w = window.open('', '_blank');
            if (w) {
                w.document.open();
                w.document.write(`<html><head><title>Livestock Analysis</title></head><body>${html}</body></html>`);
                w.document.close();
                try { w.focus(); } catch(_){}
                try { w.print(); } catch(_){}
                try { w.close(); } catch(_){}
            } else {
                window.print();
            }
        }
    } catch (e) {
        console.error('printLivestockAnalysis error:', e);
        if (typeof showToast === 'function') showToast('Failed to generate print view', 'danger');
    }
}

function exportLivestockHistory() {
    try {
        const container = document.getElementById('livestockHistoryContent');
        if (!container || !container.innerHTML.trim()) {
            showToast('No history data to export', 'warning');
            return;
        }
        const JsPDFCtor = (window.jspdf && window.jspdf.jsPDF) ? window.jspdf.jsPDF : (window.jsPDF ? window.jsPDF.jsPDF : null);
        if (!JsPDFCtor) {
            showToast('PDF library unavailable. Please try again.', 'danger');
            return;
        }
        const doc = new JsPDFCtor('p', 'pt', 'a4');
        const margin = 36;
        doc.html(container, {
            x: margin,
            y: margin,
            html2canvas: { scale: 0.8, useCORS: true, backgroundColor: '#ffffff' },
            callback: function (docInstance) {
                docInstance.save('Livestock_History.pdf');
                showToast('History exported as PDF', 'success');
            }
        });
    } catch (e) {
        console.error('exportLivestockHistory error:', e);
        showToast('Failed to export history', 'danger');
    }
}

function exportLivestockAnalysis() {
    try {
        const container = document.getElementById('livestockAnalysisContent');
        if (!container || !container.innerHTML.trim()) {
            showToast('No analysis data to export', 'warning');
            return;
        }
        const JsPDFCtor = (window.jspdf && window.jspdf.jsPDF) ? window.jspdf.jsPDF : (window.jsPDF ? window.jsPDF.jsPDF : null);
        if (!JsPDFCtor) {
            showToast('PDF library unavailable. Please try again.', 'danger');
            return;
        }
        const doc = new JsPDFCtor('p', 'pt', 'a4');
        const margin = 36; // 0.5 inch
        doc.html(container, {
            x: margin,
            y: margin,
            html2canvas: { scale: 0.8, useCORS: true, backgroundColor: '#ffffff' },
            callback: function (docInstance) {
                docInstance.save('Livestock_Analysis.pdf');
                showToast('Analysis exported as PDF', 'success');
            }
        });
    } catch (e) {
        console.error('exportLivestockAnalysis error:', e);
        showToast('Failed to export analysis', 'danger');
    }
}

// Function to force pagination positioning to the left - ENHANCED VERSION (from Super Admin)
function forcePaginationLeft() {
    console.log('Forcing pagination to left - enhanced version...');
    
    // Force wrapper layout
    $('.dataTables_wrapper .row').css({
        'display': 'block',
        'width': '100%',
        'margin': '0',
        'padding': '0'
    });
    
    $('.dataTables_wrapper .row > div').css({
        'width': '100%',
        'float': 'left',
        'clear': 'both',
        'padding': '0',
        'margin': '0'
    });
    
    // Force pagination and info to left
    $('.dataTables_wrapper .dataTables_paginate').css({
        'text-align': 'left',
        'float': 'left',
        'clear': 'both',
        'display': 'block',
        'width': 'auto',
        'margin-right': '1rem',
        'margin-top': '1rem',
        'margin-bottom': '0.5rem' // Add bottom margin to create space
    });
    
    $('.dataTables_wrapper .dataTables_info').css({
        'text-align': 'left',
        'float': 'left',
        'clear': 'both',
        'display': 'block',
        'width': 'auto',
        'margin-right': '1rem',
        'margin-top': '1rem',
        'margin-bottom': '0.5rem' // Add bottom margin to create space
    });
    
    // Apply darker blue color to pagination buttons
    $('.dataTables_wrapper .dataTables_paginate .paginate_button').css({
        'color': '#18375d',
        'border-color': '#18375d'
    });
    
    $('.dataTables_wrapper .dataTables_paginate .paginate_button.current').css({
        'color': '#fff',
        'background-color': '#18375d',
        'border-color': '#18375d'
    });
    
    console.log('Enhanced pagination positioning applied');
}

// Force center alignment for all table content
function forceCenterAlignment() {
    console.log('Forcing center alignment for all table content...');
    
    // Force center alignment on all table cells
    $('#livestockTable td, #livestockTable th').each(function() {
        $(this).css({
            'text-align': 'center',
            'vertical-align': 'middle'
        });
    });
    
    // Force center alignment on all nested elements
    $('#livestockTable td *, #livestockTable th *').each(function() {
        $(this).css({
            'text-align': 'center',
            'margin-left': 'auto',
            'margin-right': 'auto'
        });
    });
    
    // Special handling for flex containers
    $('#livestockTable .d-flex').each(function() {
        $(this).css({
            'justify-content': 'center',
            'align-items': 'center',
            'text-align': 'center',
            'margin': '0 auto',
            'width': '100%'
        });
    });
    
    // Force center alignment on badges and spans
    $('#livestockTable .badge, #livestockTable span, #livestockTable div:not(.action-buttons), #livestockTable a').each(function() {
        if (!$(this).hasClass('btn-action') && !$(this).hasClass('action-buttons')) {
            $(this).css({
                'text-align': 'center',
                'margin': '0 auto',
                'display': 'block',
                'width': 'fit-content'
            });
        }
    });
    
    // Center align action buttons container
    $('#livestockTable .action-buttons').each(function() {
        $(this).css({
            'display': 'flex',
            'justify-content': 'center',
            'align-items': 'center',
            'text-align': 'center',
            'width': '100%',
            'gap': '0.5rem'
        });
    });
    
    // Force center alignment on avatar containers
    $('#livestockTable .avatar-sm').each(function() {
        $(this).css({
            'margin': '0 auto',
            'display': 'block'
        });
    });
    
    // FORCE livestock ID link styling - EXACT MATCH from manage livestock
    $('#livestockTable .user-id-link').each(function() {
        $(this).css({
            'color': '#18375d',
            'text-decoration': 'none',
            'font-weight': '600',
            'cursor': 'pointer',
            'transition': 'all 0.2s ease',
            'padding': '0.25rem 0.5rem',
            'border-radius': '0.25rem',
            'background-color': 'rgba(24, 55, 93, 0.1)',
            'border': '1px solid rgba(24, 55, 93, 0.2)',
            'display': 'inline-block',
            'margin': '0 auto',
            'text-align': 'center'
        });
        
        // Add hover effects
        $(this).on('mouseenter', function() {
            $(this).css({
                'color': '#fff',
                'background-color': '#18375d',
                'border-color': '#18375d'
            });
        });
        
        $(this).on('mouseleave', function() {
            $(this).css({
                'color': '#18375d',
                'background-color': 'rgba(24, 55, 93, 0.1)',
                'border-color': 'rgba(24, 55, 93, 0.2)'
            });
        });
    });
    
    // FORCE Analysis button styling to standard dark blue with orange hover
    $('#livestockTable .btn-action-ok').each(function() {
        $(this).css({
            'background-color': '#18375d',
            'border-color': '#18375d',
            'color': 'white'
        });
        
        // Add hover effects for analysis button
        $(this).on('mouseenter', function() {
            $(this).css({
                'background-color': '#fca700',
                'border-color': '#fca700',
                'color': 'white'
            });
        });
        
        $(this).on('mouseleave', function() {
            $(this).css({
                'background-color': '#18375d',
                'border-color': '#18375d',
                'color': 'white'
            });
        });
    });
    
    console.log('Center alignment, livestock ID styling, and analysis button styling forced on all table elements');
}

function printLivestockHistory() {
    try {
        const container = document.getElementById('livestockHistoryContent');
        if (!container || !container.innerHTML.trim()) {
            showToast('Nothing to print. Open the history first.', 'warning');
            return;
        }

        const headerTitleEl = container.querySelector('.smart-detail-header h5');
        const headerTitle = headerTitleEl ? (headerTitleEl.textContent || '').replace(/\s*–.*$/, '').trim() : 'Livestock History';
        const infoLineEl = container.querySelector('.smart-detail-header p');
        let tagId = '', type = '', total = '';
        if (infoLineEl) {
            const t = (infoLineEl.textContent || '').replace(/\s+/g, ' ').trim();
            const parseField = (label) => {
                const re = new RegExp(label + '\\s*:\\s*([^|]+)');
                const m = t.match(re);
                return m ? m[1].trim() : '';
            };
            tagId = parseField('Tag ID');
            type = parseField('Type');
            total = parseField('Total Records');
        }
        const statusEl = container.querySelector('.status-display .badge');
        const status = statusEl ? (statusEl.textContent || '').replace(/\s+/g, ' ').trim() : '';

        const prodRows = [];
        container.querySelectorAll('#productionHistoryTable tbody tr').forEach(function(tr){
            const cells = tr.querySelectorAll('td');
            if (cells && cells.length) {
                const a = Array.from(cells).map(function(td){ return (td.textContent || '').replace(/\s+/g, ' ').trim(); });
                if (a.length >= 4) prodRows.push([a[0], a[1], a[2], a[3]]);
            }
        });

        const healthRows = [];
        container.querySelectorAll('#healthHistoryTable tbody tr').forEach(function(tr){
            const cells = tr.querySelectorAll('td');
            if (cells && cells.length) {
                const a = Array.from(cells).map(function(td){ return (td.textContent || '').replace(/\s+/g, ' ').trim(); });
                if (a.length >= 3) healthRows.push([a[0], a[1], a[2]]);
            }
        });

        const css = `
            <style>
            @page { size: auto; margin: 12mm; }
            html, body { background: #fff !important; color: #000; font-family: Arial, sans-serif; }
            h2, h3 { color: #18375d; margin: 0 0 6px 0; }
            .meta { font-size: 12px; color: #333; margin-bottom: 12px; }
            .section { margin-bottom: 14px; }
            table { width: 100%; border-collapse: collapse; margin: 0 0 12px 0; }
            th, td { border: 3px solid #000; padding: 10px; text-align: left; vertical-align: top; }
            thead th { background: #f2f2f2; color: #18375d; }
            .w-30 { width: 30%; } .w-70 { width: 70%; }
            </style>`;

        const row = (k, v) => `<tr><td class="w-30">${k}</td><td class="w-70">${v}</td></tr>`;
        const rows4 = (arr) => arr.map((x) => `<tr><td>${x[0]}</td><td>${x[1]}</td><td>${x[2]}</td><td>${x[3]}</td></tr>`).join('');
        const rows3 = (arr) => arr.map((x) => `<tr><td>${x[0]}</td><td>${x[1]}</td><td>${x[2]}</td></tr>`).join('');

        const titleHtml = `
            <div style="text-align:center;margin-bottom:10px;">
                <h2>Livestock History</h2>
                <div class="meta">Generated: ${new Date().toLocaleString()}</div>
            </div>`;

        const basicInfo = `
            <table>
                <thead><tr><th colspan="2">Basic Information</th></tr></thead>
                <tbody>
                    ${row('Name', headerTitle || '')}
                    ${row('Tag ID', tagId)}
                    ${row('Type', type)}
                    ${row('Status', status)}
                    ${row('Total Records', total)}
                </tbody>
            </table>`;

        const prodTable = `
            <table>
                <thead><tr><th colspan="4">Production History</th></tr><tr><th>Date</th><th>Milk Quantity (L)</th><th>Quality</th><th>Notes</th></tr></thead>
                <tbody>${rows4(prodRows)}</tbody>
            </table>`;

        const healthTable = `
            <table>
                <thead><tr><th colspan="3">Health History</th></tr><tr><th>Date</th><th>Status</th><th>Notes</th></tr></thead>
                <tbody>${rows3(healthRows)}</tbody>
            </table>`;

        const html = css + `<div>${titleHtml}${basicInfo}${prodTable}${healthTable}</div>`;

        if (typeof window.printElement === 'function') {
            const wrap = document.createElement('div');
            wrap.innerHTML = html;
            window.printElement(wrap);
        } else if (typeof window.openPrintWindow === 'function') {
            window.openPrintWindow(html, 'Livestock History');
        } else {
            const w = window.open('', '_blank');
            if (w) {
                w.document.open();
                w.document.write(`<html><head><title>Livestock History</title></head><body>${html}</body></html>`);
                w.document.close();
                try { w.focus(); } catch(_){}
                try { w.print(); } catch(_){}
                try { w.close(); } catch(_){}
            } else {
                window.print();
            }
        }
    } catch (e) {
        console.error('printLivestockHistory error:', e);
        if (typeof showToast === 'function') showToast('Failed to generate print view', 'danger');
    }
}

// Export functions matching superadmin pattern
function exportCSV(tableId) {
    if (tableId === 'livestockTable') {
        try {
            const table = $('#livestockTable').DataTable();
            table.button('.buttons-csv').trigger();
            showToast('CSV export started', 'success');
        } catch (error) {
            console.error('Error triggering CSV export:', error);
            showToast('CSV export unavailable', 'danger');
        }
    }
}

function exportPDF(tableId) {
    if (tableId === 'livestockTable') {
        try {
            const table = $('#livestockTable').DataTable();
            table.button('.buttons-pdf').trigger();
            showToast('PDF export started', 'success');
        } catch (error) {
            console.error('Error triggering PDF export:', error);
            showToast('PDF export unavailable', 'danger');
        }
    }
}

function exportPNG(tableId) {
    if (tableId === 'livestockTable') {
        try {
            // Create a temporary table without actions column for PNG export
            const table = $('#livestockTable').DataTable();
            const tableData = table.data().toArray();
            
            if (!tableData || tableData.length === 0) {
                showToast('No data available to export', 'warning');
                return;
            }
            
            // Create temporary container
            const tempContainer = document.createElement('div');
            tempContainer.style.position = 'absolute';
            tempContainer.style.left = '-9999px';
            tempContainer.style.top = '-9999px';
            tempContainer.style.background = 'white';
            tempContainer.style.padding = '20px';
            
            // Create content for PNG
            let pngContent = `
                <div style="font-family: Arial, sans-serif;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h1 style="color: #18375d; margin-bottom: 5px;">Livestock Analysis Report</h1>
                        <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                    </div>
                    <table style="border-collapse: collapse; width: 100%; border: 2px solid #000;">
                        <thead>
                            <tr style="background-color: #f2f2f2;">
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">Livestock ID</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">Name</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">Breed</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">Age (months)</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">Health Score</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">Avg. Production (L/day)</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">Breeding Status</th>
                            </tr>
                        </thead>
                        <tbody>`;
            
            // Add data rows (excluding Actions column)
            tableData.forEach(row => {
                pngContent += '<tr>';
                for (let i = 0; i < row.length - 1; i++) { // Skip last column (Actions)
                    let cellText = '';
                    if (row[i]) {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = row[i];
                        cellText = tempDiv.textContent || tempDiv.innerText || '';
                        cellText = cellText.replace(/\s+/g, ' ').trim();
                    }
                    pngContent += `<td style="border: 1px solid #000; padding: 8px;">${cellText}</td>`;
                }
                pngContent += '</tr>';
            });
            
            pngContent += `
                        </tbody>
                    </table>
                </div>`;
            
            tempContainer.innerHTML = pngContent;
            document.body.appendChild(tempContainer);
            
            // Use html2canvas to capture the content
            html2canvas(tempContainer, {
                backgroundColor: 'white',
                scale: 2,
                useCORS: true
            }).then(canvas => {
                // Create download link
                const link = document.createElement('a');
                link.download = `Livestock_Analysis_Report.png`;
                link.href = canvas.toDataURL("image/png");
                link.click();
                
                // Clean up
                document.body.removeChild(tempContainer);
                showToast('PNG export completed successfully', 'success');
            }).catch(error => {
                console.error('Error generating PNG:', error);
                document.body.removeChild(tempContainer);
                showToast('Error exporting PNG. Please try again.', 'danger');
            });
            
        } catch (error) {
            console.error('Error exporting PNG:', error);
            showToast('Error exporting PNG. Please try again.', 'danger');
        }
    }
}

function printLivestockTable() {
    try {
        const tableEl = document.getElementById('livestockTable');
        const dt = ($.fn.DataTable && $.fn.DataTable.isDataTable('#livestockTable')) ? $('#livestockTable').DataTable() : null;

        // Build headers (exclude last column: Actions)
        const headerTexts = [];
        if (tableEl) {
            const ths = tableEl.querySelectorAll('thead th');
            ths.forEach((th, idx) => {
                if (idx < ths.length - 1) headerTexts.push((th.innerText || '').trim());
            });
        }

        // Build rows (exclude last column)
        const rows = [];
        if (dt) {
            const data = dt.data().toArray();
            data.forEach(row => {
                const cleaned = [];
                for (let i = 0; i < row.length - 1; i++) {
                    const div = document.createElement('div');
                    div.innerHTML = row[i];
                    const text = (div.textContent || div.innerText || '').replace(/\s+/g, ' ').trim();
                    cleaned.push(text);
                }
                rows.push(cleaned);
            });
        } else if (tableEl) {
            tableEl.querySelectorAll('tbody tr').forEach(tr => {
                const tds = tr.querySelectorAll('td');
                if (tds.length) {
                    const cleaned = [];
                    for (let i = 0; i < tds.length - 1; i++) {
                        cleaned.push((tds[i].innerText || '').replace(/\s+/g, ' ').trim());
                    }
                    rows.push(cleaned);
                }
            });
        }

        if (!rows.length) {
            if (typeof showToast === 'function') showToast('No data available to print', 'warning');
            return;
        }

        // Build print content (match superadmin style)
        let printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #18375d; margin-bottom: 5px;">Livestock Analysis Report</h1>
                    <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                <table border="3" style="border-collapse: collapse; width: 100%; border: 3px solid #000;">
                    <thead>
                        <tr>`;
        headerTexts.forEach(h => {
            printContent += `<th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">${h}</th>`;
        });
        printContent += `</tr>
                    </thead>
                    <tbody>`;
        rows.forEach(r => {
            printContent += '<tr>';
            r.forEach(cell => {
                printContent += `<td style="border: 3px solid #000; padding: 10px; text-align: left;">${cell}</td>`;
            });
            printContent += '</tr>';
        });
        printContent += `
                    </tbody>
                </table>
            </div>`;

        if (typeof window.printElement === 'function') {
            const container = document.createElement('div');
            container.innerHTML = printContent;
            window.printElement(container);
        } else if (typeof window.openPrintWindow === 'function') {
            window.openPrintWindow(printContent, 'Livestock Analysis Report');
        } else {
            const w = window.open('', '_blank');
            if (w) {
                w.document.open();
                w.document.write(`<html><head><title>Print</title></head><body>${printContent}</body></html>`);
                w.document.close();
                w.focus();
                w.print();
                w.close();
            } else {
                window.print();
            }
        }
    } catch (error) {
        console.error('Error generating print:', error);
        try { $('#livestockTable').DataTable().button('.buttons-print').trigger(); } catch (_) {}
    }
}

// Refresh Pending Farmers Table
function refreshLivestockData() {
    const refreshBtn = document.querySelector('.btn-action-refresh');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    refreshBtn.disabled = true;

    // Use unique flag for farmers
    sessionStorage.setItem('showRefreshNotificationAlerts', 'true');

    setTimeout(() => {
        location.reload();
    }, 1000);
}
// Check notifications after reload
$(document).ready(function() {
    if (sessionStorage.getItem('showRefreshNotificationAlerts') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationAlerts');
        setTimeout(() => {
            showNotification('Livestock data refreshed successfully!', 'success');
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

function showToast(message, type = 'info') {
    // Simple toast notification
    const toast = $(`
        <div class="toast" role="alert">
            <div class="toast-header">
                <strong class="mr-auto">Notification</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">
                    <span>&times;</span>
                </button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `);
    
    $('.toast-container').append(toast);
    toast.toast('show');
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>
@endpush
