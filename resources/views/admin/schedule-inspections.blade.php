@extends('layouts.app')

@section('title', 'LBDAIRY: Admin - Schedule Inspections')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
<style>
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

    /* Cancel Confirmation Modal Styling */
#cancelInspectionModal .modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
}

#cancelInspectionModal .modal-header {
    background: #18375d !important;
    color: white !important;
    border-bottom: none !important;
    border-radius: 12px 12px 0 0 !important;
}

#cancelInspectionModal .modal-title {
    color: white !important;
    font-weight: 600;
}

#cancelInspectionModal .modal-body {
    padding: 2rem;
    background: white;
}

#cancelInspectionModal .modal-body p {
    margin-bottom: 0.75rem;
    color: #333 !important;
}

#cancelInspectionModal .modal-footer {
    background: white;
    border-top: 1px solid #e3e6f0;
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
    .btn-action-edit:hover {
        background-color: #fca700;
        border-color: #fca700;
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

    /* Make table cells wrap instead of forcing them all inline */
#inspectionsTableBody td, 
#inspectionsTableBody th {
    white-space: normal !important;  /* allow wrapping */
    vertical-align: middle;
}

/* Make sure action buttons don’t overflow */
#inspectionsTableBody td .btn-group {
    display: flex;
    flex-wrap: wrap; /* buttons wrap if not enough space */
    gap: 0.25rem;    /* small gap between buttons */
}

#inspectionsTableBody td .btn-action {
    flex: 1 1 auto; /* allow buttons to shrink/grow */
    min-width: 90px; /* prevent too tiny buttons */
    text-align: center;
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
        background-color: #f6c23e;
        border-color: #f6c23e;
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
    
    /* Inspection scheduling modal styling */
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
    #editInspectionModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }

    /* User Details Modal Styling */
    #editInspectionModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #editInspectionModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #editInspectionModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #editInspectionModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #editInspectionModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #editInspectionModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #editInspectionModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }


    /* Style all labels inside form Modal */
    #editInspectionModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }

    /* User Details Modal Styling */
    #inspectionDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #inspectionDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #inspectionDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #inspectionDetailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #inspectionDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #inspectionDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #inspectionDetailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Ensure Notes/Findings headings are visible and not covered */
    #inspectionDetailsModal .modal-body h6.text-primary {
        background: transparent !important;
        position: relative;
        z-index: 1;
    }


    /* Style all labels inside form Modal */
    #inspectionDetailsModal .form-group label {
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
.smart-form .icon-wrapper {
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
#editInspectionModal form {
  text-align: left;
}

#editInspectionModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#editInspectionModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#editInspectionModal .form-control,
#editInspectionModal select.form-control,
#editInspectionModal textarea.form-control {
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
#editInspectionModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#editInspectionModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}


#editInspectionModal form {
  text-align: left;
}

#editInspectionModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#editInspectionModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}
/* Style all labels inside form Modal */
    #editInspectionModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }

    /* User Details Modal Styling */
    #cancelInspectionModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #cancelInspectionModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #cancelInspectionModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #cancelInspectionModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #cancelInspectionModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #cancelInspectionModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #cancelInspectionModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }


/* Unified input + select + textarea styles */
#editLivestockModal .form-control,
#editLivestockModal select.form-control,
#editLivestockModal textarea.form-control {
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
#editLivestockModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#editLivestockModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}
/* ============================
   FORM ELEMENT STYLES
   ============================ */
#scheduleInspectionModal form {
  text-align: left;
}

#scheduleInspectionModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#scheduleInspectionModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#scheduleInspectionModal .form-control,
#scheduleInspectionModal select.form-control,
#scheduleInspectionModal textarea.form-control {
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
#scheduleInspectionModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#scheduleInspectionModal .form-control:focus {
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
#addLivestockModal .modal-footer {
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

  #editLivestockModal .form-control {
    font-size: 14px;
  }
   #issueAlertModal .form-control {
    font-size: 14px;
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
</style>
@endpush

@section('content')
    <div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
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
        <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start">
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
                 <div class="d-flex align-items-center justify-content-center flex-nowrap gap-2 action-toolbar">
                    <button class="btn-action btn-action-refresh-admins" title="Refresh" onclick="refreshFarmersData()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" title="Tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="printFarmersTable()">
                                <i class="fas fa-print"></i> Print Table
                            </a>
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
        <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start">
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
                 <div class="d-flex align-items-center justify-content-center flex-nowrap gap-2 action-toolbar">
                    <button class="btn-action btn-action-refresh-admins" title="Refresh" onclick="refreshInspectionsData()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" title="Tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="printInspectionsTable()">
                                <i class="fas fa-print"></i> Print Table
                            </a>
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
<div class="modal fade admin-modal" id="scheduleInspectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content smart-form text-center p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-wrapper ">
                    <i class="fas fa-calendar-check fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1" id="scheduleInspectionModalTitle">Schedule Farm Inspection</h5>
                <p class="text-muted mb-0 small">
                    Fill in the inspection details below, then click <strong>“Schedule Inspection”</strong> to save.
                </p>
            </div>

            <!-- Form -->
            <form id="inspectionForm" onsubmit="submitInspectionSchedule(event)">
                <input type="hidden" id="inspectionFarmer" name="farmer_id">

                <div class="form-wrapper text-start mx-auto">
                    <div class="row g-3">
                        <!-- Farmer & Farm Info -->
                        <div class="col-md-6">
                            <label for="inspectionFarmerName" class="fw-semibold">Farmer Name</label>
                            <input type="text" class="form-control mt-1" id="inspectionFarmerName" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="inspectionFarmName" class="fw-semibold">Farm Name</label>
                            <input type="text" class="form-control mt-1" id="inspectionFarmName" readonly>
                        </div>

                        <!-- Date & Time -->
                        <div class="col-md-6">
                            <label for="inspectionDate" class="fw-semibold">Inspection Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control mt-1" id="inspectionDate" name="inspection_date" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="inspectionTime" class="fw-semibold">Inspection Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control mt-1" id="inspectionTime" name="inspection_time" required>
                        </div>

                        <!-- Priority -->
                        <div class="col-md-6">
                            <label for="inspectionPriority" class="fw-semibold">Priority Level <span class="text-danger">*</span></label>
                            <select class="form-control mt-1" id="inspectionPriority" name="priority" required>
                                <option value="">Select Priority</option>
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="col-md-6">
                            <label for="inspectionNotes" class="fw-semibold">Inspection Notes <span class="text-danger">*</span></label>
                            <textarea class="form-control mt-1" id="inspectionNotes" name="notes" rows="3" placeholder="Enter any specific notes or instructions for the inspection..." style="resize: none;"></textarea>
                        </div>
                    </div>

                    <!-- Notification -->
                    <div id="inspectionNotification" class="mt-3 text-center" style="display: none;"></div>
                </div>

                <!-- Footer Buttons -->
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-ok" id="inspectionSubmitBtn" title="Schedule Inspection" >Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Smart Detail Modal - Farmer Details -->
<div class="modal fade" id="inspectionDetailsModal" tabindex="-1" role="dialog" aria-labelledby="inspectionDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-user fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">Inspection Details</h5>
                <p class="text-muted mb-0 small">Below are the complete details of the selected inspection.</p>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div id="inspectionDetailsContent" class="detail-wrapper">
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


<!-- Smart Detail Modal - Farmer Details -->
<div class="modal fade" id="farmerDetailsModal" tabindex="-1" role="dialog" aria-labelledby="farmerDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-user fa-lg"></i>
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

<!-- Smart Form Modal - Edit Farmer -->
<div class="modal fade admin-modal" id="editInspectionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content smart-form text-center p-4">

      <!-- Header -->
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-wrapper">
          <i class="fas fa-edit fa-2x"></i>
        </div>
        <h5 class="fw-bold mb-1">Edit Inspection</h5>
        <p class="text-muted mb-0 small">
          Update inspection details below and click <strong>Update Inspection</strong> to save changes.
        </p>
      </div>

      <!-- Form -->
      <form id="editInspectionForm">
        <input type="hidden" id="editInspectionId" name="inspection_id">

        <div class="form-wrapper text-start mx-auto">
          <div class="row g-3">

            <!-- Full Name -->
            <div class="col-md-6">
                <label for="editInspectionDate">Inspection Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="editInspectionDate" name="inspection_date" required>
            </div>

            <!-- Email -->
            <div class="col-md-6">
                <label for="editInspectionTime">Inspection Time <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="editInspectionTime" name="inspection_time" required>
            </div>

            <!-- Contact -->
            <div class="col-md-6">
                <label for="editInspectionPriority">Priority Level <span class="text-danger">*</span></label>
                <select class="form-control" id="editInspectionPriority" name="priority" required>
                    <option value="">Select Priority</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>

            <!-- Username -->
            <div class="col-md-6">
                <label for="editInspectionStatus">Status <span class="text-danger">*</span></label>
                <select class="form-control" id="editInspectionStatus" name="status" required>
                    <option value="">Select Status</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="rescheduled">Rescheduled</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="editInspectionNotes">Inspection Notes</label>
                <textarea class="form-control" id="editInspectionNotes" name="notes" rows="3" placeholder="Enter inspection notes..."></textarea>
            </div>
            <div class="col-md-6">
                <label for="editInspectionFindings">Findings</label>
                <textarea class="form-control" id="editInspectionFindings" name="findings" rows="3" placeholder="Enter inspection findings..."></textarea>
            </div>

          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
          <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-modern btn-ok">
            Update Inspection
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- Smart Cancel Inspection Modal -->
<div class="modal fade admin-modal" id="cancelInspectionModal" tabindex="-1" aria-labelledby="cancelInspectionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content smart-form text-center p-4">

      <!-- Icon -->
      <div class="icon-wrapper mx-auto mb-3 text-danger">
        <i class="fas fa-times-circle fa-2x"></i>
      </div>

      <!-- Title -->
      <h5 id="cancelInspectionModalLabel" class="fw-bold mb-2">
        Confirm Cancellation
      </h5>

      <!-- Message -->
      <p class="text-muted mb-3">
        Are you sure you want to cancel this inspection?<br>
        This action <strong>cannot be undone</strong>.
      </p>

      <!-- Footer -->
      <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
        <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">
          Cancel
        </button>
        <button type="button" class="btn-modern btn-delete" id="confirmCancelInspectionBtn">
          Yes, Cancel
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<!-- FullCalendar -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
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

<!-- jsPDF and autoTable for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
let inspectionsTable;
let farmersTable;
let downloadCounter = 1;

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
                        `<div class="btn-group">
                            <button class="btn-action btn-action-ok" onclick="viewInspectionDetails('${inspection.id}')" title="View Details">
                                <i class="fas fa-eye"></i>
                                <span>View</span>
                            </button>
                            <button class="btn-action btn-action-edit" onclick="editInspection('${inspection.id}')" title="Edit">
                                <i class="fas fa-edit"></i>
                                <span>Edit</span>
                            </button>
                            <button class="btn-action btn-action-deletes" onclick="cancelInspection('${inspection.id}')" title="Cancel">
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
                            <button class="btn-action btn-action-ok" onclick="viewFarmerDetails('${farmer.id}')" title="View Details">
                                <i class="fas fa-eye"></i>
                                <span>View</span>
                            </button>
                            <button class="btn-action btn-action-add" onclick="scheduleInspectionForFarmer('${farmer.id}', '${farmer.first_name || ''} ${farmer.last_name || ''}', '${farmer.farm_name || 'N/A'}')" title="Schedule Inspection">
                                <i class="fas fa-calendar-check"></i>
                                <span>Schedule</span>
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
    document.getElementById('inspectionFarmerName').value = farmerName;
    document.getElementById('inspectionFarmName').value = farmName;
    
    // Clear other fields
    document.getElementById('inspectionDate').value = '';
    document.getElementById('inspectionTime').value = '';
    document.getElementById('inspectionPriority').value = '';
    document.getElementById('inspectionNotes').value = '';
    
    // Show the modal
    $('#scheduleInspectionModal').modal('show');
    
    // Show a notification that farmer is selected
    showNotification(`Selected farmer: ${farmerName}`, 'info');
}

function openScheduleModal() {
    $('#scheduleInspectionModal').modal('show');
}

function viewFarmerDetails(farmerId) {
    console.log('viewFarmerDetails called with ID:', farmerId);
    
    // Show loading state
    $('#farmerDetailsContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Loading farmer details...</p></div>');
    $('#farmerDetailsModal').modal('show');
    
    // Load farmer details and their scheduled inspections
    $.ajax({
        url: `{{ route('admin.farmers.show', ':id') }}`.replace(':id', farmerId),
        method: 'GET',
        success: function(response) {
            console.log('Farmer response:', response);
            if (response.success) {
                const farmer = response.farmer;
                
                // Load scheduled inspections for this farmer
                $.ajax({
                    url: `{{ route('admin.inspections.farmer', ':id') }}`.replace(':id', farmerId),
                    method: 'GET',
                    success: function(inspectionsResponse) {
                        console.log('Inspections response:', inspectionsResponse);
                        if (inspectionsResponse.success) {
                            const inspections = inspectionsResponse.data;
                            console.log('Inspections data:', inspections);
                            
                            // Create calendar HTML with inspections
                            const calendarHtml = `
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Farmer Information</h6>
                                    <p class="text-left"><strong>Name:</strong> ${farmer.first_name || ''} ${farmer.last_name || ''}</p>
                                    <p class="text-left"><strong>Email:</strong> ${farmer.email || 'N/A'}</p>
                                    <p class="text-left"><strong>Phone:</strong> ${farmer.phone || 'N/A'}</p>
                                    <p class="text-left"><strong>Farm:</strong> ${farmer.farm_name || 'N/A'}</p>
                                    <p class="text-left"><strong>Status:</strong> 
                                        <span class="badge badge-${farmer.status === 'active' ? 'success' : 'warning'} badge-pill">
                                            <i class="fas fa-${farmer.status === 'active' ? 'check-circle' : 'clock'} mr-1"></i>
                                            ${farmer.status ? farmer.status.charAt(0).toUpperCase() + farmer.status.slice(1) : 'N/A'}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Scheduled Inspections Calendar</h6>
                                    <div id="farmerCalendar" class="border rounded" style="height: 400px;">
                                    </div>
                                </div>
                            </div>
                                <div class="col-md-12">
                                    <h6 class="mb-3" style="color: #18375d; font-weight: 600;">
                                        Upcoming Inspections
                                    </h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Priority</th>
                                                    <th>Status</th>
                                                    <th>Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${inspections.length > 0 ? inspections.map(inspection => `
                                                    <tr class="${inspection.priority === 'urgent' ? 'table-danger' : (inspection.priority === 'high' ? 'table-warning' : '')}">
                                                        <td>${inspection.inspection_date ? new Date(inspection.inspection_date).toLocaleDateString() : 'N/A'}</td>
                                                        <td>${inspection.inspection_time ? new Date('1970-01-01T' + inspection.inspection_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : 'N/A'}</td>
                                                        <td><span class="badge badge-${getPriorityBadgeClass(inspection.priority)}">${inspection.priority || 'N/A'}</span></td>
                                                        <td><span class="badge badge-${getStatusBadgeClass(inspection.status)}">${inspection.status || 'N/A'}</span></td>
                                                        <td class="text-muted">${inspection.notes ? inspection.notes.substring(0, 60) + (inspection.notes.length > 60 ? '...' : '') : 'N/A'}</td>
                                                    </tr>
                                                `).join('') : `
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted py-3">
                                                            <i class="fas fa-clipboard-list fa-lg mb-2 d-block"></i>
                                                            No scheduled inspections found
                                                        </td>
                                                    </tr>
                                                `}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            


                            `;
                            
                            $('#farmerDetailsContent').html(calendarHtml);
                            
                            // Initialize calendar with inspections
                            console.log('About to initialize calendar with inspections:', inspections);
                            
                            // Wait for DOM to be ready and FullCalendar to be loaded
                            setTimeout(() => {
                                // Check if FullCalendar is loaded
                                if (typeof FullCalendar === 'undefined') {
                                    console.error('FullCalendar not loaded yet, retrying...');
                                    setTimeout(() => {
                                        if (inspections && inspections.length > 0) {
                                            initializeFarmerCalendar(inspections);
                                        } else {
                                            console.log('No inspections found for this farmer');
                                            $('#farmerCalendar').html(`
                                                <div class="text-center text-muted p-4">
                                                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                                                    <h5>No Scheduled Inspections</h5>
                                                    <p>This farmer has no scheduled inspections yet.</p>
                                                </div>
                                            `);
                                        }
                                    }, 500);
                                } else {
                                    if (inspections && inspections.length > 0) {
                                        initializeFarmerCalendar(inspections);
                                    } else {
                                        console.log('No inspections found for this farmer');
                                        $('#farmerCalendar').html(`
                                            <div class="text-center text-muted p-4">
                                                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                                                <h5>No Scheduled Inspections</h5>
                                                <p>This farmer has no scheduled inspections yet.</p>
                                            </div>
                                        `);
                                    }
                                }
                            }, 100);
                            
                            $('#farmerDetailsModal').modal('show');
                        } else {
                            showNotification('Error loading farmer inspections', 'danger');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading farmer inspections:', xhr, status, error);
                        $('#farmerDetailsContent').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                Error loading farmer inspections: ${error}
                            </div>
                        `);
                        showNotification('Error loading farmer inspections', 'danger');
                    }
                });
            } else {
                showNotification('Error loading farmer details', 'danger');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading farmer details:', xhr, status, error);
            $('#farmerDetailsContent').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Error loading farmer details: ${error}
                </div>
            `);
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
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Inspection Information</h6>
                            <p class="text-left"><strong>Date:</strong> ${inspection.inspection_date || 'N/A'}</p>
                            <p class="text-left"><strong>Time:</strong> ${inspection.inspection_time || 'N/A'}</p>
                            <p class="text-left"><strong>Status:</strong> 
                                <span class="badge badge-${inspection.status === 'scheduled' ? 'primary' : 
                                    (inspection.status === 'completed' ? 'success' : 
                                    (inspection.status === 'cancelled' ? 'danger' : 'warning'))}">
                                    ${inspection.status}
                                </span>
                            </p>
                            <p class="text-left"><strong>Priority:</strong> 
                                <span class="badge badge-${inspection.priority === 'urgent' ? 'danger' : 
                                    (inspection.priority === 'high' ? 'warning' : 
                                    (inspection.priority === 'medium' ? 'info' : 'success'))}">
                                    ${inspection.priority}
                                </span>
                            </p>
                            <p class="text-left"><strong>Scheduled By:</strong> ${inspection.scheduled_by?.name || 'Admin'}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Farmer Information</h6>
                            <p class="text-left"><strong>Name:</strong> ${inspection.farmer?.first_name || ''} ${inspection.farmer?.last_name || ''}</p>
                            <p class="text-left"><strong>Email:</strong> ${inspection.farmer?.email || 'N/A'}</p>
                            <p class="text-left"><strong>Contact:</strong> ${inspection.farmer?.phone || 'N/A'}</p>
                            <p class="text-left"><strong>Farm Name:</strong> ${inspection.farmer?.farm_name || 'N/A'}</p>
                            <p class="text-left"><strong>Barangay:</strong> ${inspection.farmer?.barangay || 'N/A'}</p>
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
    console.log('Editing inspection:', inspectionId);
    
    // Show loading state
    $('#editInspectionModal').modal('show');
    $('#editInspectionForm')[0].reset();
    
    // Fetch inspection details
    $.ajax({
        url: `{{ route("admin.inspections.show", ":id") }}`.replace(':id', inspectionId),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const inspection = response.data;
                
                // Populate form fields
                $('#editInspectionId').val(inspection.id);
                $('#editInspectionDate').val(inspection.inspection_date);
                $('#editInspectionTime').val(inspection.inspection_time);
                $('#editInspectionPriority').val(inspection.priority);
                $('#editInspectionStatus').val(inspection.status);
                $('#editInspectionNotes').val(inspection.notes || '');
                $('#editInspectionFindings').val(inspection.findings || '');
                
                console.log('Inspection data loaded:', inspection);
            } else {
                showNotification('Error loading inspection details', 'danger');
                $('#editInspectionModal').modal('hide');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading inspection:', xhr, status, error);
            showNotification('Error loading inspection details', 'danger');
            $('#editInspectionModal').modal('hide');
        }
    });
}

let currentInspectionId = null;

function cancelInspection(inspectionId) {
    currentInspectionId = inspectionId;
    $('#cancelInspectionModal').modal('show');
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
        showNotification('Inspections data refreshed successfully!', 'success');
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
        showNotification('Farmers data refreshed successfully!', 'success');
    }, 1000);
}

function exportInspectionsCSV() {
    const tableData = inspectionsTable.data().toArray();
    const csvData = [];
    
    // Add headers (excluding Actions column)
    const headers = ['Farmer Name', 'Farm Name', 'Inspection Date', 'Time', 'Priority', 'Status', 'Scheduled By'];
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
    link.setAttribute('download', `ScheduledInspections_Report_${downloadCounter}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    downloadCounter++;
}

function exportInspectionsPDF() {
    try {
        // Force custom PDF generation to match superadmin styling
        // Don't fall back to DataTables PDF export as it has different styling
        
        const tableData = inspectionsTable.data().toArray();
        const pdfData = [];
        
        const headers = ['Farmer Name', 'Farm Name', 'Inspection Date', 'Time', 'Priority', 'Status', 'Scheduled By'];
        
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
        doc.text('Admin Scheduled Inspections Report', 14, 22);
        doc.setFontSize(12);
        doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 14, 32);
        
        // Add table
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
        doc.save(`Admin_ScheduledInspectionsReport_${downloadCounter}.pdf`);
        
        // Increment download counter
        downloadCounter++;
        
        showNotification('PDF exported successfully!', 'success');
        
    } catch (error) {
        console.error('Error generating PDF:', error);
        showNotification('Error generating PDF. Please try again.', 'danger');
    }
}

function exportInspectionsPNG() {
    const originalTable = document.getElementById('inspectionsTable');
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
        link.download = `ScheduledInspections_Report_${downloadCounter}.png`;
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

function printInspectionsTable() {
    try {
        const tableData = inspectionsTable.data().toArray();
        
        if (!tableData || tableData.length === 0) {
            showNotification('No data available to print', 'warning');
            return;
        }
        
        // Create print content directly in current page
        const originalContent = document.body.innerHTML;
        
        let printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #18375d; margin-bottom: 5px;">Scheduled Inspections Report</h1>
                    <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                <table border="3" style="border-collapse: collapse; width: 100%; border: 3px solid #000;">
                    <thead>
                        <tr>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Farmer Name</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Farm Name</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Inspection Date</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Time</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Priority</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Status</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Scheduled By</th>
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
        
        // Print in same tab without background using printElement (no new tab)
        if (typeof window.printElement === 'function') {
            const container = document.createElement('div');
            container.innerHTML = printContent;
            window.printElement(container);
        } else if (typeof window.openPrintWindow === 'function') {
            window.openPrintWindow(printContent, 'Scheduled Inspections Report');
        } else {
            window.print();
        }
        
    } catch (error) {
        console.error('Error in print function:', error);
        showNotification('Error generating print. Please try again.', 'danger');
        
        // Fallback to DataTables print
        try {
            inspectionsTable.button('.buttons-print').trigger();
        } catch (fallbackError) {
            console.error('Fallback print also failed:', fallbackError);
            showNotification('Print failed. Please try again.', 'danger');
        }
    }
}

function exportFarmersCSV() {
    const tableData = farmersTable.data().toArray();
    const csvData = [];
    
    // Add headers (excluding Actions column)
    const headers = ['Farmer Name', 'Farm Name', 'Email', 'Contact', 'Barangay', 'Status'];
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
    link.setAttribute('download', `ActiveFarmers_Report_${downloadCounter}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Increment download counter
    downloadCounter++;
}

function exportFarmersPDF() {
    try {
        // Force custom PDF generation to match superadmin styling
        // Don't fall back to DataTables PDF export as it has different styling
        
        const tableData = farmersTable.data().toArray();
        const pdfData = [];
        
        // Add headers (excluding Actions column)
        const headers = ['Farmer Name', 'Farm Name', 'Email', 'Contact', 'Barangay', 'Status'];
        
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
                rowData.push(cellText);
            }
            pdfData.push(rowData);
        });
        
        // Create PDF using jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape', 'mm', 'a4');
        
        // Set title
        doc.setFontSize(18);
        doc.text('Active Farmers Report', 14, 22);
        doc.setFontSize(12);
        doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 14, 32);
        
        // Add table
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
        doc.save(`ActiveFarmers_Report_${downloadCounter}.pdf`);
        
        // Increment download counter
        downloadCounter++;
        
        showNotification('PDF exported successfully!', 'success');
        
    } catch (error) {
        console.error('Error generating PDF:', error);
        showNotification('Error generating PDF. Please try again.', 'danger');
    }
}

function exportFarmersPNG() {
    // Create a temporary table without the Actions column for export
    const originalTable = document.getElementById('farmersTable');
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
        scale: 2,
        backgroundColor: '#ffffff',
        width: tempTable.offsetWidth,
        height: tempTable.offsetHeight
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = `ActiveFarmers_Report_${downloadCounter}.png`;
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

function printFarmersTable() {
    try {
        const tableData = farmersTable.data().toArray();
        
        if (!tableData || tableData.length === 0) {
            showNotification('No data available to print', 'warning');
            return;
        }
        
        // Create print content directly in current page
        const originalContent = document.body.innerHTML;
        
        let printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #18375d; margin-bottom: 5px;">Active Farmers Report</h1>
                    <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                <table border="3" style="border-collapse: collapse; width: 100%; border: 3px solid #000;">
                    <thead>
                        <tr>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Farmer Name</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Farm Name</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Email</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Contact</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Barangay</th>
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
        
        // Print in same tab without background using printElement (no new tab)
        if (typeof window.printElement === 'function') {
            const container = document.createElement('div');
            container.innerHTML = printContent;
            window.printElement(container);
        } else if (typeof window.openPrintWindow === 'function') {
            window.openPrintWindow(printContent, 'Active Farmers Report');
        } else {
            window.print();
        }
        
    } catch (error) {
        console.error('Error in print function:', error);
        showNotification('Error generating print. Please try again.', 'danger');
        
        // Fallback to DataTables print
        try {
            farmersTable.button('.buttons-print').trigger();
        } catch (fallbackError) {
            console.error('Fallback print also failed:', fallbackError);
            showNotification('Print failed. Please try again.', 'danger');
        }
    }
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

function getPriorityBadgeClass(priority) {
    switch(priority) {
        case 'low': return 'info';
        case 'medium': return 'warning';
        case 'high': return 'danger';
        case 'urgent': return 'dark';
        default: return 'secondary';
    }
}

function getStatusBadgeClass(status) {
    switch(status) {
        case 'scheduled': return 'primary';
        case 'completed': return 'success';
        case 'cancelled': return 'danger';
        case 'rescheduled': return 'warning';
        default: return 'secondary';
    }
}

function initializeFarmerCalendar(inspections) {
    try {
        // Check if FullCalendar is available
        if (typeof FullCalendar === 'undefined') {
            console.error('FullCalendar is not loaded');
            showNotification('Calendar library not loaded. Please refresh the page.', 'error');
            return;
        }
        
        // Destroy existing calendar if it exists
        if (window.farmerCalendar) {
            try {
                if (typeof window.farmerCalendar.destroy === 'function') {
                    window.farmerCalendar.destroy();
                }
            } catch (destroyError) {
                console.warn('Error destroying existing calendar:', destroyError);
            }
            window.farmerCalendar = null;
        }
        
        const calendarEl = document.getElementById('farmerCalendar');
        if (!calendarEl) {
            console.error('Calendar element not found');
            showNotification('Calendar element not found', 'error');
            return;
        }
        
        // Clear the calendar element content
        calendarEl.innerHTML = '';
        
        // Convert inspections to FullCalendar events
        console.log('Converting inspections to calendar events:', inspections);
        
        const events = inspections.map(inspection => {
            // Use the calendar_start attribute if available, otherwise construct it
            let eventStart;
            
            if (inspection.calendar_start) {
                eventStart = inspection.calendar_start;
            } else if (inspection.inspection_date && inspection.inspection_time) {
                // Fallback: construct the datetime
                let startDate, startTime;
                
                // If inspection_time is a full datetime, extract just the time part
                if (inspection.inspection_time.includes(' ')) {
                    startTime = inspection.inspection_time.split(' ')[1].substring(0, 8); // Extract HH:MM:SS
                } else {
                    startTime = inspection.inspection_time;
                }
                
                // Ensure inspection_date is just the date part
                if (inspection.inspection_date.includes(' ')) {
                    startDate = inspection.inspection_date.split(' ')[0]; // Extract YYYY-MM-DD
                } else {
                    startDate = inspection.inspection_date;
                }
                
                eventStart = `${startDate}T${startTime}`;
            } else {
                console.warn(`Invalid date/time for inspection ${inspection.id}:`, inspection.inspection_date, inspection.inspection_time);
                return null;
            }
            
            console.log(`Creating event for inspection ${inspection.id}: ${eventStart}`);
            
            return {
                id: inspection.id,
                title: `Inspection - ${inspection.priority}`,
                start: eventStart,
                backgroundColor: getPriorityColor(inspection.priority),
                borderColor: getPriorityColor(inspection.priority),
                textColor: 'white',
                extendedProps: {
                    priority: inspection.priority,
                    status: inspection.status,
                    notes: inspection.notes,
                    scheduledBy: inspection.scheduled_by?.name || 'Admin'
                }
            };
        }).filter(event => event !== null);
        
        console.log('Final calendar events:', events);
        
        // Create new calendar instance
        if (typeof FullCalendar.Calendar === 'undefined') {
            console.error('FullCalendar.Calendar is not available');
            showNotification('Calendar component not available. Please refresh the page.', 'error');
            return;
        }
        
        window.farmerCalendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            initialView: 'dayGridMonth',
            height: 'auto',
            events: events,
            eventClick: function(info) {
                const inspection = info.event;
                const details = `
                    <strong>Farm Inspection</strong><br>
                    <strong>Priority:</strong> ${inspection.extendedProps.priority}<br>
                    <strong>Status:</strong> ${inspection.extendedProps.status}<br>
                    <strong>Scheduled By:</strong> ${inspection.extendedProps.scheduledBy}<br>
                    ${inspection.extendedProps.notes ? `<strong>Notes:</strong> ${inspection.extendedProps.notes}` : ''}
                `;
                showNotification(details, 'info');
            },
            eventDidMount: function(info) {
                // Add tooltip
                $(info.el).tooltip({
                    title: `Inspection - ${info.event.extendedProps.priority} priority`,
                    placement: 'top',
                    trigger: 'hover'
                });
            }
        });
        
        window.farmerCalendar.render();
        console.log('Farmer calendar initialized successfully');
    } catch (error) {
        console.error('Error initializing farmer calendar:', error);
        showNotification('Error initializing calendar: ' + error.message, 'error');
        
        // Fallback: show a simple message instead of calendar
        const calendarEl = document.getElementById('farmerCalendar');
        if (calendarEl) {
            calendarEl.innerHTML = `
                <div class="text-center text-muted p-4">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                    <h5>Calendar Error</h5>
                    <p>Unable to load calendar. Please refresh the page and try again.</p>
                    <p class="small">Error: ${error.message}</p>
                </div>
            `;
        }
    }
}

function getPriorityColor(priority) {
    switch(priority) {
        case 'urgent': return '#dc3545';
        case 'high': return '#fd7e14';
        case 'medium': return '#ffc107';
        case 'low': return '#28a745';
        default: return '#6c757d';
    }
}

// Event handlers for modals
$(document).ready(function() {
    // Edit inspection form submission
    $('#editInspectionForm').on('submit', function(e) {
        e.preventDefault();
        
        const inspectionId = $('#editInspectionId').val();
        const formData = new FormData(this);
        formData.append('_method', 'PUT');
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Updating...').prop('disabled', true);
        
        $.ajax({
            url: `{{ route("admin.inspections.update", ":id") }}`.replace(':id', inspectionId),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-HTTP-Method-Override': 'PUT',
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    $('#editInspectionModal').modal('hide');
                    loadInspections();
                    updateStats();
                    showNotification('Inspection updated successfully!', 'success');
                } else {
                    showNotification(response.message || 'Error updating inspection', 'danger');
                }
            },
            error: function(xhr) {
                console.error('Error updating inspection:', xhr);
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Handle validation errors
                    let errorMessage = 'Validation errors:\n';
                    Object.keys(xhr.responseJSON.errors).forEach(key => {
                        errorMessage += `• ${xhr.responseJSON.errors[key].join(', ')}\n`;
                    });
                    showNotification(errorMessage, 'danger');
                } else {
                    showNotification('Error updating inspection', 'danger');
                }
            },
            complete: function() {
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });
    
    // Cancel inspection confirmation
    $('#confirmCancelInspectionBtn').on('click', function() {
        if (currentInspectionId) {
            const btn = $(this);
            const originalText = btn.html();
            btn.html('<i class="fas fa-spinner fa-spin"></i> Cancelling...').prop('disabled', true);
            
            $.ajax({
                url: `{{ route("admin.inspections.cancel", ":id") }}`.replace(':id', currentInspectionId),
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#cancelInspectionModal').modal('hide');
                        loadInspections();
                        updateStats();
                        showNotification('Inspection cancelled successfully!', 'warning');
                    } else {
                        showNotification(response.message || 'Error cancelling inspection', 'danger');
                    }
                },
                error: function(xhr) {
                    console.error('Error cancelling inspection:', xhr);
                    showNotification('Error cancelling inspection', 'danger');
                },
                complete: function() {
                    btn.html(originalText).prop('disabled', false);
                    currentInspectionId = null;
                }
            });
        }
    });
});
</script>
@endpush
