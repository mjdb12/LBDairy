@extends('layouts.app')

@section('title', 'LBDAIRY: Admin - Schedule Inspections')

@push('styles')
<style>
    /* CRITICAL FIX FOR DROPDOWN TEXT CUTTING */
    .admin-modal select.form-control,
    .modal.admin-modal select.form-control,
    .admin-modal .modal-body select.form-control {
        min-width: 250px !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding: 0.75rem 2rem 0.75rem 0.75rem !important;
        white-space: nowrap !important;
        text-overflow: clip !important;
        overflow: visible !important;
        font-size: 0.875rem !important;
        line-height: 1.5 !important;
    }
    
    /* Ensure columns don't constrain dropdowns */
    .admin-modal .col-md-6 {
        min-width: 280px !important;
        overflow: visible !important;
    }

    /* Apply consistent styling for Farmers, Livestock, and Issues tables */
#farmersTable th,
#farmersTable td,
#inspectionsTable th,
#inspectionsTable td,
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
#inspectionsTable thead th,
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
#inspectionsTable thead th.sorting,
#inspectionsTable thead th.sorting_asc,
#inspectionsTable thead th.sorting_desc,
#issuesTable thead th.sorting,
#issuesTable thead th.sorting_asc,
#issuesTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#farmersTable thead th::after,
#inspectionsTable thead th::after,
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
#inspectionsTable thead th.sorting::after,
#inspectionsTable thead th.sorting_asc::after,
#inspectionsTable thead th.sorting_desc::after,
#issuesTable thead th.sorting::after,
#issuesTable thead th.sorting_asc::after,
#issuesTable thead th.sorting_desc::after {
    display: none;
}

    

    /* Custom styles for user management */
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    
    .border-left-danger {
        border-left: 0.25rem solid #e74a3b !important;
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
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.075);
    }
    
    .badge {
        font-size: 0.75em;
        padding: 0.375em 0.75em;
    }
    
    .badge-pill {
        border-radius: 50rem;
        padding-left: 0.75em;
        padding-right: 0.75em;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Ensure all role badges have identical pill shape */
    .badge-danger.badge-pill,
    .badge-primary.badge-pill,
    .badge-success.badge-pill,
    .badge-secondary.badge-pill {
        border-radius: 50rem !important;
        padding: 0.5em 1em !important;
        font-size: 0.7em;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        line-height: 1.2;
        display: inline-block;
        white-space: nowrap;
        vertical-align: baseline;
    }
    
    /* Force pill shape override for any conflicting styles */
    .badge.badge-pill {
        border-radius: 50rem !important;
        padding: 0.5em 1em !important;
    }
    
    /* Make admin badge (primary) look identical to superadmin pill shape */
    .badge-primary {
        border-radius: 50rem !important;
        padding: 0.5em 1em !important;
        font-size: 0.7em;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        line-height: 1.2;
        display: inline-block;
        white-space: nowrap;
        vertical-align: baseline;
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
    
    .btn-group .btn {
        margin-right: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .gap-2 {
        gap: 0.5rem !important;
    }
    
    .badge-sm {
        font-size: 0.6em;
        padding: 0.2em 0.4em;
    }
    
    .badge-success .fas.fa-circle {
        animation: pulse 2s infinite;
    }
    
    /* Override badge colors for status column to ensure proper colors */
    #usersTable .badge-danger {
        background-color: #dc3545 !important;
        color: white !important;
    }
    
    #usersTable .badge-warning {
        background-color: #ffc107 !important;
        color: #212529 !important;
    }
    
    #usersTable .badge-success {
        background-color: #387057 !important;
        color: white !important;
    }
    
    /* Fix admin role badge text color */
    #usersTable .badge-warning {
        background-color: #fca700 !important;
        color: white !important;
    }
    
    /* Ensure superadmin stays dark blue */
    #usersTable .badge-primary {
        background-color: #18375d !important;
        color: white !important;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
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

    .btn-action-ok {
        background-color: #18375d;
        border-color: #18375d;
        color: white;
    }
    .btn-action-ok:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-edit:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-delete {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .btn-action-delete:hover {
        background-color: #c82333;
        border-color: #c82333;
        color: white;
    }
    
    /* Header action buttons styling to match Edit/Delete buttons */
    .btn-action-add {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
    .btn-action-add:hover {
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

    .btn-action-reject {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-reject:hover {
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
    }
    
    /* Table responsiveness and spacing */
    .table-responsive {
        overflow-x: auto;
        min-width: 100%;
        position: relative;
    }
    
    /* Ensure DataTables controls are properly positioned */
    .table-responsive + .dataTables_wrapper,
    .table-responsive .dataTables_wrapper {
        width: 100%;
        position: relative;
    }
    
    /* Fix pagination positioning for wide tables */
    .table-responsive .dataTables_wrapper .dataTables_paginate {
        position: relative;
        width: 100%;
        text-align: left;
        margin: 1rem 0;
        left: 0;
        right: 0;
    }
    
    #usersTable {
        width: 100% !important;
        min-width: 1280px;
        border-collapse: collapse;
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
    
    
    /* Ensure Registration Date column has enough space */
    #usersTable th:nth-child(6),
    #usersTable td:nth-child(6) {
        min-width: 220px !important;
        width: 220px !important;
        white-space: nowrap;
        overflow: visible;
        text-overflow: initial;
    }
    
    /* Ensure all table headers have consistent styling */
    #usersTable thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: bold;
        color: #495057;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 0.75rem;
        text-align: left;
        vertical-align: middle;
        position: relative;
        white-space: nowrap;
    }
    
    /* Fix DataTables sorting button overlap */
    #usersTable thead th.sorting,
    #usersTable thead th.sorting_asc,
    #usersTable thead th.sorting_desc {
        padding-right: 2rem !important;
    }
    
    /* Ensure proper spacing for sort indicators */
    #usersTable thead th::after {
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
    #usersTable thead th.sorting::after,
    #usersTable thead th.sorting_asc::after,
    #usersTable thead th.sorting_desc::after {
        display: none;
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
    
    /* Action buttons styling to match active admins table */
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
        font-weight: 500;
        border: 1px solid transparent;
        border-radius: 0.375rem;
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
    
    .btn-action-delete {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .btn-action-delete:hover {
        background-color: #c82333;
        border-color: #c82333;
        color: white;
    }
    
    /* Ensure table has enough space for actions column */
    .table th:last-child,
    .table td:last-child {
        min-width: 200px;
        width: auto;
        text-align: center;
        vertical-align: middle;
    }


    /* User Details Modal Styling */
    #userDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #userDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #userDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #userDetailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #userDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #userDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #userDetailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }
    
    /* Responsive adjustments */
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
    /* Custom styles for farmer management */
    
    .card-header .btn-group {
        margin-left: 0.5rem;
    }
    
    .card-header .input-group {
        margin-bottom: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .card-header .d-flex {
            flex-direction: column !important;
        }
        
        .card-header .btn-group {
            margin-left: 0;
            margin-top: 0.5rem;
        }
        
        .card-header .input-group {
            margin-bottom: 0.5rem;
            max-width: 100% !important;
        }
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.075);
    }
    
    .badge {
        font-size: 0.75em;
        padding: 0.375em 0.75em;
    }
    
    .btn-group .btn {
        margin-right: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .gap-2 {
        gap: 0.5rem !important;
    }
    
    /* Status badges */
    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status-approved {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .status-active {
        background-color: #d1ecf1;
        color: #0c5460;
    }
    
    /* Table-responsive wrapper positioning */
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
    
    **************
    /* User Details Modal Styling */
    #scheduleInspectionModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #scheduleInspectionModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #scheduleInspectionModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #scheduleInspectionModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #scheduleInspectionModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #scheduleInspectionModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #scheduleInspectionModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }


    /* Style all labels inside form Modal */
    #scheduleInspectionModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }


    /* ===== DATATABLE STYLES ===== */
.dataTables_length {
    margin-bottom: 1rem;
}

.dataTables_length select {
    min-width: 80px;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background-color: #fff;
    margin: 0 0.5rem;
}

.dataTables_length label {
    display: flex;
    align-items: center;
    margin-bottom: 0;
    font-weight: 500;
    color: var(--dark-color);
}

.dataTables_info {
    padding-top: 0.5rem;
    font-weight: 500;
    color: var(--dark-color);
}

.dataTables_paginate {
    margin-top: 1rem;
}

.dataTables_paginate .paginate_button {
    padding: 0.5rem 0.75rem;
    margin: 0 0.125rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background-color: #fff;
    color: var(--dark-color);
    text-decoration: none;
    transition: var(--transition-fast);
}

.dataTables_paginate .paginate_button:hover {
    background-color: var(--light-color);
    border-color: var(--primary-light);
    color: var(--primary-color);
}

.dataTables_paginate .paginate_button.current {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

.dataTables_paginate .paginate_button.disabled {
    color: var(--text-muted);
    cursor: not-allowed;
    background-color: var(--light-color);
    border-color: var(--border-color);
}

.dataTables_filter {
    margin-bottom: 1rem;
}

.dataTables_filter input {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background-color: #fff;
    transition: var(--transition-fast);
}

.dataTables_filter input:focus {
    border-color: var(--primary-light);
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    outline: 0;
}
  
</style>
@endpush

@section('content')
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-calendar-check"></i>
            Schedule Inspections
        </h1>
        <p>Manage farm inspections, schedule new inspections, and track inspection status</p>
    </div>

    <!-- Stats Cards -->
    <div class="row fade-in">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Total Inspections</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalInspections">0</div>
                    </div>
                    <div class="icon" style="color: #18375d !important;">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Scheduled</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="scheduledInspections">0</div>
                    </div>
                    <div class="icon" style="color: #18375d !important;">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Completed</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="completedInspections">0</div>
                    </div>
                    <div class="icon" style="color: #18375d !important;">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Urgent</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="urgentInspections">0</div>
                    </div>
                    <div class="icon" style="color: #18375d !important;">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <!-- Farmers Table -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">
                <i class="fas fa-users"></i>
                    Select Farmer for Inspection
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
                    <input type="text" class="form-control" placeholder="Search active farmers..." id="farmerSearch">
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-center">
                    <button class="btn-action btn-action-print" onclick="printFarmersTable()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button class="btn-action btn-action-refresh-admins" onclick="refreshFarmersData()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportFarmersCSV()">
                                <i class="fas fa-file-csv"></i> Download CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportFarmersPNG()">
                                <i class="fas fa-image"></i> Download PNG
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportFarmersPDF()">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="farmersTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Farmer Name</th>
                            <th>Farm Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Barangay</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="farmersTableBody">
                        <!-- Farmers will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    

    <!-- Inspections Table -->
     <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">
                <i class="fas fa-list"></i>
                    All Inspections
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
                    <input type="text" class="form-control" placeholder="Search active farmers..." id="inspectionSearch">
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-center">
                    <button class="btn-action btn-action-print" onclick="printInspectionsTable()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button class="btn-action btn-action-refresh-admins" onclick="refreshInspectionsData()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportInspectionsCSV()">
                                <i class="fas fa-file-csv"></i> Download CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportInspectionsPNG()">
                                <i class="fas fa-image"></i> Download PNG
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportInspectionsPDF()">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="inspectionsTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Farmer Name</th>
                            <th>Farm Name</th>
                            <th>Inspection Date</th>
                            <th>Time</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Scheduled By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="inspectionsTableBody">
                        <!-- Inspections will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Schedule Inspection Modal -->
<div class="modal fade" id="scheduleInspectionModal" tabindex="-1" role="dialog" aria-labelledby="scheduleInspectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleInspectionModalLabel">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Schedule Farm Inspection
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form onsubmit="submitInspectionSchedule(event)">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inspectionFarmer">Select Farmer <span class="text-danger">*</span></label>
                                <select class="form-control" id="inspectionFarmer" required>
                                    <option value="">Select Farmer</option>
                                    <!-- Farmers will be loaded here -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inspectionDate">Inspection Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="inspectionDate" required min="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inspectionTime">Inspection Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="inspectionTime" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inspectionPriority">Priority Level <span class="text-danger">*</span></label>
                                <select class="form-control" id="inspectionPriority" required>
                                    <option value="">Select Priority</option>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inspectionNotes">Inspection Notes <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="inspectionNotes" rows="3" placeholder="Enter any specific notes or instructions for the inspection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-action btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-action btn-action-ok">
                        <i class="fas fa-calendar-check"></i> Schedule Inspection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Inspection Details Modal -->
<div class="modal fade" id="inspectionDetailsModal" tabindex="-1" role="dialog" aria-labelledby="inspectionDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inspectionDetailsModalLabel">
                    <i class="fas fa-eye"></i>
                    Inspection Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="inspectionDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Farmer Details Modal -->
<div class="modal fade" id="farmerDetailsModal" tabindex="-1" role="dialog" aria-labelledby="farmerDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="farmerDetailsModalLabel">
                    <i class="fas fa-user"></i>
                    Farmer Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="farmerDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<!-- Required libraries for PDF/Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
let inspectionsTable;
let farmersTable;

$(document).ready(function () {
    // Initialize DataTables
    initializeDataTables();
    
    // Load data
    loadInspections();
    loadFarmersTable();
    updateStats();

    // Custom search functionality
    $('#inspectionSearch').on('keyup', function() {
        inspectionsTable.search(this.value).draw();
    });

    $('#farmerSearch').on('keyup', function() {
        farmersTable.search(this.value).draw();
    });
});

function initializeDataTables() {
    const commonConfig = {
        dom: 'Bfrtip',
        searching: true,
        paging: true,
        info: true,
        ordering: true,
        lengthChange: false,
        pageLength: 10,
        processing: true,
        serverSide: false,
        buttons: [
            {
                extend: 'csvHtml5',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                className: 'd-none'
            }
        ],
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No data available</h5><p>There are no records to display at this time.</p></div>',
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        },
        columnDefs: [
            {
                targets: -1, // Last column (Actions)
                orderable: false,
                searchable: false
            }
        ]
    };

    inspectionsTable = $('#inspectionsTable').DataTable({
        ...commonConfig,
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Inspections_Report',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Inspections_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Inspections Report',
                className: 'd-none'
            }
        ]
    });

    farmersTable = $('#farmersTable').DataTable({
        ...commonConfig,
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Farmers_List',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Farmers_List',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Farmers List',
                className: 'd-none'
            }
        ]
    });

    // Hide default DataTables elements
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
}

function loadInspections() {
    // Show loading state
    const tableBody = $('#inspectionsTableBody');
    tableBody.html('<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading inspections...</td></tr>');
    
    // Load inspections from the database via AJAX
    $.ajax({
        url: '{{ route("admin.inspections.list") }}',
        method: 'GET',
        success: function(response) {
            inspectionsTable.clear();
            
            if (response.success && response.data && response.data.length > 0) {
                response.data.forEach((inspection) => {
                    const rowData = [
                        `${inspection.farmer?.first_name || ''} ${inspection.farmer?.last_name || ''}`,
                        inspection.farmer?.farm_name || 'N/A',
                        inspection.inspection_date ? new Date(inspection.inspection_date).toLocaleDateString() : 'N/A',
                        inspection.inspection_time ? new Date(`2000-01-01T${inspection.inspection_time}`).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : 'N/A',
                        `<span class="badge badge-${inspection.priority === 'urgent' ? 'danger' : (inspection.priority === 'high' ? 'warning' : (inspection.priority === 'medium' ? 'info' : 'success'))}">${inspection.priority}</span>`,
                        `<span class="badge badge-${inspection.status === 'scheduled' ? 'primary' : (inspection.status === 'completed' ? 'success' : (inspection.status === 'cancelled' ? 'danger' : 'warning'))}">${inspection.status}</span>`,
                        inspection.scheduled_by?.name || 'Admin',
                        `<div class="action-buttons">
                            <button class="btn-action btn-action-view" onclick="viewInspectionDetails('${inspection.id}')" title="View Details">
                                <i class="fas fa-eye"></i>
                                <span>View</span>
                            </button>
                            <button class="btn-action btn-action-edit" onclick="editInspection('${inspection.id}')" title="Edit">
                                <i class="fas fa-edit"></i>
                                <span>Edit</span>
                            </button>
                            <button class="btn-action btn-action-reject" onclick="cancelInspection('${inspection.id}')" title="Cancel">
                                <i class="fas fa-times"></i>
                                <span>Cancel</span>
                            </button>
                        </div>`
                    ];
                    
                    inspectionsTable.row.add(rowData);
                });
                inspectionsTable.draw();
            } else {
                // Handle empty data properly
                inspectionsTable.clear().draw();
                $('#inspectionsTableBody').html('<tr><td colspan="8" class="text-center">No inspections found</td></tr>');
            }
        },
        error: function(xhr) {
            console.error('Error loading inspections:', xhr);
            inspectionsTable.clear().draw();
            $('#inspectionsTableBody').html('<tr><td colspan="8" class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Error loading inspections</td></tr>');
        }
    });
}

function loadFarmersTable() {
    // Show loading state
    const tableBody = $('#farmersTableBody');
    tableBody.html('<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading farmers...</td></tr>');
    
    // Load farmers from the database via AJAX
    $.ajax({
        url: '{{ route("admin.farmers.active") }}',
        method: 'GET',
        success: function(response) {
            farmersTable.clear();
            
            if (response.success && response.data && response.data.length > 0) {
                response.data.forEach((farmer) => {
                    const rowData = [
                        `${farmer.first_name || ''} ${farmer.last_name || ''}`,
                        farmer.farm_name || 'N/A',
                        farmer.email || 'N/A',
                        farmer.phone || 'N/A',
                        farmer.barangay || 'N/A',
                        `<span class="badge badge-success">Active</span>`,
                        `<div class="action-buttons">
                            <button class="btn-action btn-action-add" onclick="scheduleInspectionForFarmer('${farmer.id}', '${farmer.first_name || ''} ${farmer.last_name || ''}', '${farmer.farm_name || 'N/A'}')" title="Schedule Inspection">
                                <i class="fas fa-calendar-check"></i>
                                <span>Schedule</span>
                            </button>
                            <button class="btn-action btn-action-ok" onclick="viewFarmerDetails('${farmer.id}')" title="View Details">
                                <i class="fas fa-eye"></i>
                                <span>View</span>
                            </button>
                        </div>`
                    ];
                    
                    farmersTable.row.add(rowData);
                });
                farmersTable.draw();
            } else {
                // Handle empty data properly
                farmersTable.clear().draw();
                $('#farmersTableBody').html('<tr><td colspan="7" class="text-center">No active farmers found</td></tr>');
            }
        },
        error: function(xhr) {
            console.error('Error loading farmers:', xhr);
            farmersTable.clear().draw();
            $('#farmersTableBody').html('<tr><td colspan="7" class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Error loading farmers</td></tr>');
        }
    });
}

function loadFarmers() {
    $.ajax({
        url: '{{ route("admin.farmers.active") }}',
        method: 'GET',
        success: function(response) {
            const farmerSelect = $('#inspectionFarmer');
            farmerSelect.empty().append('<option value="">Select Farmer</option>');
            
            if (response.success && response.data) {
                response.data.forEach(farmer => {
                    const farmerName = `${farmer.first_name || ''} ${farmer.last_name || ''}`.trim();
                    farmerSelect.append(`<option value="${farmer.id}">${farmerName} - ${farmer.farm_name || 'N/A'}</option>`);
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading farmers:', xhr);
        }
    });
}

function updateStats() {
    // Update stats via AJAX
    $.ajax({
        url: '{{ route("admin.inspections.stats") }}',
        method: 'GET',
        success: function(response) {
            if (response.success && response.data) {
                document.getElementById('totalInspections').textContent = response.data.total || 0;
                document.getElementById('scheduledInspections').textContent = response.data.scheduled || 0;
                document.getElementById('completedInspections').textContent = response.data.completed || 0;
                document.getElementById('urgentInspections').textContent = response.data.urgent || 0;
            }
        },
        error: function(xhr) {
            console.error('Error loading stats:', xhr);
        }
    });
}

function scheduleInspectionForFarmer(farmerId, farmerName, farmName) {
    // Pre-populate the modal with farmer information
    document.getElementById('inspectionFarmer').value = farmerId;
    
    // Show the modal
    $('#scheduleInspectionModal').modal('show');
    
    // Show a notification that farmer is selected
    showNotification(`Selected farmer: ${farmerName} (${farmName})`, 'info');
}

function openScheduleModal() {
    $('#scheduleInspectionModal').modal('show');
}

function viewFarmerDetails(farmerId) {
    $.ajax({
        url: `{{ url('admin/farmers') }}/${farmerId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const farmer = response.data;
                $('#farmerDetailsContent').html(`
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Personal Information</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Name:</strong></td><td>${farmer.first_name || ''} ${farmer.last_name || ''}</td></tr>
                                <tr><td><strong>Email:</strong></td><td>${farmer.email || 'N/A'}</td></tr>
                                <tr><td><strong>Phone:</strong></td><td>${farmer.phone || 'N/A'}</td></tr>
                                <tr><td><strong>Username:</strong></td><td>${farmer.username || 'N/A'}</td></tr>
                                <tr><td><strong>Registration Date:</strong></td><td>${farmer.created_at || 'N/A'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Farm Information</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Farm Name:</strong></td><td>${farmer.farm_name || 'N/A'}</td></tr>
                                <tr><td><strong>Barangay:</strong></td><td>${farmer.barangay || 'N/A'}</td></tr>
                                <tr><td><strong>Status:</strong></td><td><span class="badge badge-${farmer.status === 'active' ? 'success' : 'warning'}">${farmer.status}</span></td></tr>
                            </table>
                        </div>
                    </div>
                `);
                $('#farmerDetailsModal').modal('show');
            } else {
                showNotification('Error loading farmer details', 'danger');
            }
        },
        error: function() {
            showNotification('Error loading farmer details', 'danger');
        }
    });
}

function submitInspectionSchedule(event) {
    event.preventDefault();
    
    const farmerId = document.getElementById('inspectionFarmer').value;
    const inspectionDate = document.getElementById('inspectionDate').value;
    const inspectionTime = document.getElementById('inspectionTime').value;
    const priority = document.getElementById('inspectionPriority').value;
    const notes = document.getElementById('inspectionNotes').value;

    $.ajax({
        url: '{{ route("admin.inspections.schedule") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            farmer_id: farmerId,
            inspection_date: inspectionDate,
            inspection_time: inspectionTime,
            priority: priority,
            notes: notes
        },
        success: function(response) {
            if (response.success) {
                $('#scheduleInspectionModal').modal('hide');
                // Reset form
                document.getElementById('inspectionFarmer').value = '';
                document.getElementById('inspectionDate').value = '';
                document.getElementById('inspectionTime').value = '';
                document.getElementById('inspectionPriority').value = '';
                document.getElementById('inspectionNotes').value = '';
                
                loadInspections();
                updateStats();
                showNotification('Inspection scheduled successfully!', 'success');
            } else {
                showNotification(response.message || 'Error scheduling inspection', 'danger');
            }
        },
        error: function(xhr) {
            showNotification('Error scheduling inspection', 'danger');
        }
    });
}

function viewInspectionDetails(inspectionId) {
    $.ajax({
        url: `{{ route("admin.inspections.show", ":id") }}`.replace(':id', inspectionId),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const inspection = response.data;
                $('#inspectionDetailsContent').html(`
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Inspection Information</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Date:</strong></td><td>${inspection.inspection_date}</td></tr>
                                <tr><td><strong>Time:</strong></td><td>${inspection.inspection_time}</td></tr>
                                <tr><td><strong>Status:</strong></td><td><span class="badge badge-${inspection.status === 'scheduled' ? 'primary' : (inspection.status === 'completed' ? 'success' : (inspection.status === 'cancelled' ? 'danger' : 'warning'))}">${inspection.status}</span></td></tr>
                                <tr><td><strong>Priority:</strong></td><td><span class="badge badge-${inspection.priority === 'urgent' ? 'danger' : (inspection.priority === 'high' ? 'warning' : (inspection.priority === 'medium' ? 'info' : 'success'))}">${inspection.priority}</span></td></tr>
                                <tr><td><strong>Scheduled By:</strong></td><td>${inspection.scheduled_by?.name || 'Admin'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Farmer Information</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Name:</strong></td><td>${inspection.farmer?.first_name || ''} ${inspection.farmer?.last_name || ''}</td></tr>
                                <tr><td><strong>Email:</strong></td><td>${inspection.farmer?.email || 'N/A'}</td></tr>
                                <tr><td><strong>Contact:</strong></td><td>${inspection.farmer?.phone || 'N/A'}</td></tr>
                                <tr><td><strong>Farm Name:</strong></td><td>${inspection.farmer?.farm_name || 'N/A'}</td></tr>
                                <tr><td><strong>Barangay:</strong></td><td>${inspection.farmer?.barangay || 'N/A'}</td></tr>
                            </table>
                        </div>
                    </div>
                    ${inspection.notes ? `
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-primary">Notes</h6>
                            <p>${inspection.notes}</p>
                        </div>
                    </div>
                    ` : ''}
                    ${inspection.findings ? `
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-primary">Findings</h6>
                            <p>${inspection.findings}</p>
                        </div>
                    </div>
                    ` : ''}
                `);
                $('#inspectionDetailsModal').modal('show');
            } else {
                showNotification('Error loading inspection details', 'danger');
            }
        },
        error: function() {
            showNotification('Error loading inspection details', 'danger');
        }
    });
}

function editInspection(inspectionId) {
    // Implementation for editing inspection
    showNotification('Edit functionality coming soon!', 'info');
}

function cancelInspection(inspectionId) {
    if (confirm('Are you sure you want to cancel this inspection?')) {
        $.ajax({
            url: `{{ route("admin.inspections.cancel", ":id") }}`.replace(':id', inspectionId),
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    loadInspections();
                    updateStats();
                    showNotification('Inspection cancelled successfully!', 'warning');
                } else {
                    showNotification(response.message || 'Error cancelling inspection', 'danger');
                }
            },
            error: function() {
                showNotification('Error cancelling inspection', 'danger');
            }
        });
    }
}

function refreshInspectionsData() {
    const refreshBtn = $('button[onclick="refreshInspectionsData()"]');
    const originalIcon = refreshBtn.html();
    refreshBtn.html('<i class="fas fa-spinner fa-spin"></i>Refreshing...');
    refreshBtn.prop('disabled', true);
    
    loadInspections();
    updateStats();
    
    setTimeout(() => {
        refreshBtn.html(originalIcon);
        refreshBtn.prop('disabled', false);
        showNotification('Inspections data refreshed', 'success');
    }, 1000);
}

function refreshFarmersData() {
    const refreshBtn = $('button[onclick="refreshFarmersData()"]');
    const originalIcon = refreshBtn.html();
   refreshBtn.html('<i class="fas fa-spinner fa-spin"></i>Refreshing...');
    refreshBtn.prop('disabled', true);
    
    loadFarmersTable();
    
    setTimeout(() => {
        refreshBtn.html(originalIcon);
        refreshBtn.prop('disabled', false);
        showNotification('Farmers data refreshed', 'success');
    }, 1000);
}

function exportInspectionsCSV() {
    inspectionsTable.button('.buttons-csv').trigger();
}

function exportInspectionsPDF() {
    inspectionsTable.button('.buttons-pdf').trigger();
}

function exportInspectionsPNG() {
    const tableElement = document.getElementById('inspectionsTable');
    html2canvas(tableElement).then(canvas => {
        let link = document.createElement('a');
        link.download = 'Inspections_Report.png';
        link.href = canvas.toDataURL("image/png");
        link.click();
    });
}

function printInspectionsTable() {
    inspectionsTable.button('.buttons-print').trigger();
}

function exportFarmersCSV() {
    farmersTable.button('.buttons-csv').trigger();
}

function exportFarmersPDF() {
    farmersTable.button('.buttons-pdf').trigger();
}

function exportFarmersPNG() {
    const tableElement = document.getElementById('farmersTable');
    html2canvas(tableElement).then(canvas => {
        let link = document.createElement('a');
        link.download = 'Farmers_List.png';
        link.href = canvas.toDataURL("image/png");
        link.click();
    });
}

function printFarmersTable() {
    farmersTable.button('.buttons-print').trigger();
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
