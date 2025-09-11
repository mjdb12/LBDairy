@extends('layouts.app')

@section('title', 'LBDAIRY: Admin - Manage Farmers')

@push('styles')
<style>
/* CRITICAL FIX FOR DROPDOWN TEXT CUTTING */
.admin-modal select.form-control,
.admin-modal-modal select.form-control,
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

/* Custom styles for user management */
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
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
    
    /* Header action buttons styling to match Edit/Delete buttons */
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
    
/* Refresh buttons */
.btn-action-refresh,
.btn-action-refresh-admins,
.btn-action-refresh-farmers {
    background-color: #fca700;
    border-color: #fca700;
    color: white;
}

.btn-action-refresh:hover,
.btn-action-refresh-admin:hover,
.btn-action-refresh-farmers:hover {
    background-color: #e69500;
    border-color: #e69500;
    color: white;
}

/* Reject button */
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
    
#farmersTable th,
#farmersTable td,
#usersTable th,
#usersTable td {
    vertical-align: middle;
    padding: 0.75rem;
    text-align: center;
    border: 1px solid #dee2e6;
    white-space: nowrap;
    overflow: visible;
}
    #usersTable td {
        vertical-align: middle;
        padding: 0.75rem;
        text-align: center;
        border: 1px solid #dee2e6;
        white-space: nowrap;
        overflow: visible;
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

#farmersTable th,
#farmersTable td {
    vertical-align: middle;
    padding: 0.75rem;
    text-align: center;
    border: 1px solid #dee2e6;
    white-space: nowrap;
    overflow: visible;
}

/* Ensure all table headers have consistent styling */
#farmersTable thead th {
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
#farmersTable thead th.sorting,
#farmersTable thead th.sorting_asc,
#farmersTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#farmersTable thead th::after {
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
#farmersTable thead th.sorting_desc::after {
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


    
    /* DataTables Pagination Styling */
    .dataTables_wrapper .dataTables_paginate {
        text-align: left !important;
        margin-top: 1rem;
        clear: both;
        width: 100%;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        display: inline-block;
        min-width: 2.5rem;
        padding: 0.5rem 0.75rem;
        margin: 0 0.125rem;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        color: #495057;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        background-color: #fff;
        transition: all 0.15s ease-in-out;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        color: #18375d;
        background-color: #e9ecef;
        border-color: #adb5bd;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        color: #fff;
        background-color: #18375d;
        border-color: #18375d;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        color: #6c757d;
        background-color: #fff;
        border-color: #dee2e6;
        cursor: not-allowed;
        opacity: 0.5;
    }
    
    .dataTables_wrapper .dataTables_info {
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        color: #495057;
        font-size: 0.875rem;
    }
    
    /* Ensure pagination container is properly positioned */
    .dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    
    .dataTables_wrapper .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0;
    }
    
    .dataTables_wrapper .row > div {
        padding: 0;
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
        color: #18375d !important; /* Admin theme color for numbers */
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
    
    
</style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-users"></i>
            Farmer Management
        </h1>
        <p>Manage farmer registrations, approvals, and account status</p>
    </div>

    <!-- Stats Cards -->
    <div class="row fade-in">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Pending Approvals</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingCount">0</div>
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
                        <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Active Farmer</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeCount">0</div>
                    </div>
                    <div class="icon" style="color: #18375d !important;">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Total Farmer</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalCount">0</div>
                    </div>
                    <div class="icon" style="color: #18375d !important;">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Rejected Farmer</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="rejectedCount">0</div>
                    </div>
                    <div class="icon" style="color: #18375d !important;">
                        <i class="fas fa-user-times fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Farmers Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">
                <i class="fas fa-list"></i>
                Pending Farmer Registrations
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
<input type="text" class="form-control" placeholder="Search pending farmers..." id="pendingSearch">
</div>
<div class="d-flex flex-column flex-sm-row align-items-center">
    <button class="btn-action btn-action-print" onclick="printTable('pendingFarmersTable')" title="Print">
        <i class="fas fa-print"></i> Print
    </button>
    <button class="btn-action btn-action-refresh-farmers" onclick="refreshPendingFarmersTable('pendingFarmersTable')" title="Refresh">
        <i class="fas fa-sync-alt"></i> Refresh
    </button>
</div>

                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" type="button" data-toggle="dropdown">
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
<div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="pendingFarmersTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Farm Name</th>
                            <th>Barangay</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Registration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="pendingFarmersBody">
                        <!-- Pending farmers will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>


    <!-- Active Farmers Card -->
     <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">
                <i class="fas fa-list"></i>
                Active Farmers
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
                    <input type="text" class="form-control" placeholder="Search active farmers..." id="activeSearch">
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-center">
                    <button class="btn-action btn-action-print" onclick="printTable('activeFarmersTable')">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button class="btn-action btn-action-refresh-admins" onclick="refreshAdminsTable('activeFarmersTable')">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportCSV('activeFarmersTable')">
                                <i class="fas fa-file-csv"></i> Download CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPNG('activeFarmersTable')">
                                <i class="fas fa-image"></i> Download PNG
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPDF('activeFarmersTable')">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="activeFarmersTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Farm Name</th>
                            <th>Barangay</th>
                            <th>Contact</th>
                            <th>Username</th>
                            <th>Registration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="activeFarmersBody">
                        <!-- Active farmers will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>


</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">
                    <i class="fas fa-user"></i>
                    Farmer Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="farmerDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="openContactModal()">
                    <i class="fas fa-envelope"></i> Contact Farmer
                </button>
                
            </div>
        </div>
    </div>
</div>

<!-- Contact Farmer Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">
                    <i class="fas fa-paper-plane"></i>
                    Send Message to Farmer
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form onsubmit="sendMessage(event)">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="farmerNameHidden">
                    <div class="form-group">
                        <label for="messageSubject">Subject</label>
                        <input type="text" class="form-control" id="messageSubject" required>
                    </div>
                    <div class="form-group">
                        <label for="messageBody">Message</label>
                        <textarea class="form-control" id="messageBody" rows="4" required></textarea>
                    </div>
                    <div id="messageNotification" class="mt-2" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rejection Reason Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1" role="dialog" aria-labelledby="rejectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectionModalLabel">
                    <i class="fas fa-times-circle"></i>
                    Reject Farmer Registration
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form onsubmit="submitRejection(event)">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="farmerIdHidden">
                    <div class="form-group">
                        <label for="rejectionReason">Reason for Rejection</label>
                        <textarea class="form-control" id="rejectionReason" rows="4" required placeholder="Please provide a reason for rejecting this farmer registration..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Reject Registration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Approve Farmer Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">
                    <i class="fas fa-check-circle"></i>
                    Approve Farmer Registration
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form onsubmit="submitApproval(event)">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="farmerIdHiddenApprove">
                    <p>Are you sure you want to <strong>approve</strong> this farmer’s registration?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Approve Registration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Schedule Inspection Modal -->
<div class="modal fade" id="scheduleInspectionModal" tabindex="-1" role="dialog" aria-labelledby="scheduleInspectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleInspectionModalLabel">
                    <i class="fas fa-calendar-check"></i>
                    Schedule Farm Inspection
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form onsubmit="submitInspectionSchedule(event)">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="inspectionFarmerIdHidden">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inspectionFarmerName">Farmer Name</label>
                                <input type="text" class="form-control" id="inspectionFarmerName" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inspectionDate">Inspection Date</label>
                                <input type="date" class="form-control" id="inspectionDate" required min="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inspectionTime">Inspection Time</label>
                                <input type="time" class="form-control" id="inspectionTime" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inspectionPriority">Priority Level</label>
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
                        <label for="inspectionNotes">Inspection Notes</label>
                        <textarea class="form-control" id="inspectionNotes" rows="3" placeholder="Enter any specific notes or instructions for the inspection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-calendar-check"></i> Schedule Inspection
                    </button>
                </div>
            </form>
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
let pendingFarmersTable;
let activeFarmersTable;

$(document).ready(function () {
    // Initialize DataTables
    initializeDataTables();
    
    // Load data
    loadPendingFarmers();
    loadActiveFarmers();
    updateStats();

    // Custom search functionality
    $('#pendingSearch').on('keyup', function() {
        pendingFarmersTable.search(this.value).draw();
    });
    
    $('#activeSearch').on('keyup', function() {
        activeFarmersTable.search(this.value).draw();
    });
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

// Refresh Admins Table
function refreshAdminsTable() {
    const refreshBtn = document.querySelector('.btn-action-refresh-admins');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    refreshBtn.disabled = true;

    // Use unique flag for admins
    sessionStorage.setItem('showRefreshNotificationAdmins', 'true');

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
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No data available</h5><p>There are no records to display at this time.</p></div>',
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        },
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
        columnDefs: [
            {
                targets: -1, // Last column (Actions)
                orderable: false,
                searchable: false
            }
        ]
    };

    pendingFarmersTable = $('#pendingFarmersTable').DataTable({
        ...commonConfig,
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Pending_Farmers_Report',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Pending_Farmers_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Pending Farmers Report',
                className: 'd-none'
            }
        ]
    });

    activeFarmersTable = $('#activeFarmersTable').DataTable({
        ...commonConfig,
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Active_Farmers_Report',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Active_Farmers_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Active Farmers Report',
                className: 'd-none'
            }
        ]
    });
    
    // Add event listeners to force pagination positioning on table updates
    pendingFarmersTable.on('draw.dt', function() {
        setTimeout(forcePaginationLeft, 50);
    });
    
    activeFarmersTable.on('draw.dt', function() {
        setTimeout(forcePaginationLeft, 50);
    });
    
    // Force initial positioning
    setTimeout(forcePaginationLeft, 200);



    // Hide default DataTables elements
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
    
    // Force pagination to left side after initialization
    setTimeout(() => {
        forcePaginationLeft();
    }, 100);
}

    // Function to force pagination positioning to the left - ENHANCED VERSION
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
        'margin-top': '1rem'
    });
    
    $('.dataTables_wrapper .dataTables_info').css({
        'text-align': 'left',
        'float': 'left',
        'clear': 'both',
        'display': 'block',
        'width': 'auto',
        'margin-right': '1rem',
        'margin-top': '1rem'
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

function loadPendingFarmers() {
    // Show loading state
    const tableBody = $('#pendingFarmersBody');
    tableBody.html('<tr><td colspan="9" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading pending farmers...</td></tr>');
    
    // Load pending farmers from the database via AJAX
    $.ajax({
        url: '{{ route("admin.farmers.pending") }}',
        method: 'GET',
        success: function(response) {
            pendingFarmersTable.clear();
            
            if (response.success && response.data && response.data.length > 0) {
                response.data.forEach((farmer) => {
                    const rowData = [
                        `<a href="#" class="user-id-link" onclick="showFarmerDetails(${farmer.id})" title="Click to view details">${farmer.id}</a>`,
                        `${farmer.first_name || ''} ${farmer.last_name || ''}`,
                        farmer.farm_name || 'N/A',
                        farmer.barangay || 'N/A',
                        farmer.phone || 'N/A',
                        farmer.email || 'N/A',
                        farmer.username || 'N/A',
                        farmer.created_at ? new Date(farmer.created_at).toLocaleDateString() : 'N/A',
                        `<div class="action-buttons">
                            <button class="btn-action btn-action-approve" onclick="approveFarmer('${farmer.id}')" title="Approve">
                                <i class="fas fa-check"></i>
                                <span>Approve</span>
                            </button>
                            <button class="btn-action btn-action-reject" onclick="showRejectionModal('${farmer.id}')" title="Reject">
                                <i class="fas fa-times"></i>
                                <span>Reject</span>
                            </button>
                        </div>`
                    ];
                    
                    pendingFarmersTable.row.add(rowData);
                });
                pendingFarmersTable.draw();
            } else {
                // Handle empty data properly
                pendingFarmersTable.clear().draw();
                $('#pendingFarmersBody').html('<tr><td colspan="9" class="text-center">No pending farmers found</td></tr>');
            }
        },
        error: function(xhr) {
            console.error('Error loading pending farmers:', xhr);
            pendingFarmersTable.clear().draw();
            $('#pendingFarmersBody').html('<tr><td colspan="9" class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Error loading pending farmers</td></tr>');
        }
    });
}

function loadActiveFarmers() {
    // Show loading state
    const tableBody = $('#activeFarmersBody');
    tableBody.html('<tr><td colspan="9" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading active farmers...</td></tr>');
    
    // Load active farmers from the database via AJAX
    $.ajax({
        url: '{{ route("admin.farmers.active") }}',
        method: 'GET',
        success: function(response) {
            activeFarmersTable.clear();
            
            if (response.success && response.data && response.data.length > 0) {
                response.data.forEach((farmer) => {
                    const rowData = [
                        `<a href="#" class="user-id-link" onclick="showFarmerDetails(${farmer.id})" title="Click to view details">${farmer.id}</a>`,
                        `${farmer.first_name || ''} ${farmer.last_name || ''}`,
                        farmer.farm_name || 'N/A',
                        farmer.barangay || 'N/A',
                        farmer.phone || 'N/A',
                        farmer.email || 'N/A',
                        farmer.username || 'N/A',
                        farmer.created_at ? new Date(farmer.created_at).toLocaleDateString() : 'N/A',
                        `<div class="action-buttons">
                            <button class="btn-action btn-action-view" onclick="showFarmerDetails(${farmer.id})" title="View Details">
                                <i class="fas fa-eye"></i>
                                <span>View</span>
                            </button>
                            <button class="btn-action btn-action-view" onclick="openContactModal()" title="Contact">
                                <i class="fas fa-envelope"></i>
                                <span>Contact</span>
                            </button>
                            <button class="btn-action btn-action-toggle" onclick="deactivateFarmer('${farmer.id}')" title="Deactivate">
                                <i class="fas fa-user-slash"></i>
                                <span>Deactivate</span>
                            </button>
                        </div>`
                    ];
                    
                    activeFarmersTable.row.add(rowData);
                });
                activeFarmersTable.draw();
            } else {
                // Handle empty data properly
                activeFarmersTable.clear().draw();
                $('#activeFarmersBody').html('<tr><td colspan="9" class="text-center">No active farmers found</td></tr>');
            }
        },
        error: function(xhr) {
            console.error('Error loading active farmers:', xhr);
            activeFarmersTable.clear().draw();
            $('#activeFarmersBody').html('<tr><td colspan="9" class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Error loading active farmers</td></tr>');
        }
    });
}

function updateStats() {
    // Update stats via AJAX
    $.ajax({
        url: '{{ route("admin.farmers.stats") }}',
        method: 'GET',
        success: function(response) {
            if (response.success && response.data) {
                document.getElementById('pendingCount').textContent = response.data.pending || 0;
                document.getElementById('activeCount').textContent = response.data.active || 0;
                document.getElementById('totalCount').textContent = response.data.total || 0;
                document.getElementById('rejectedCount').textContent = response.data.rejected || 0;
            }
        },
        error: function(xhr) {
            console.error('Error loading stats:', xhr);
        }
    });
}

function showFarmerDetails(farmerId) {
    // Load farmer details via AJAX
    $.ajax({
        url: `/admin/farmers/${farmerId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const farmer = response.farmer;
                const details = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Personal Information</h6>
                            <p><strong>Full Name:</strong> ${farmer.first_name || ''} ${farmer.last_name || ''}</p>
                            <p><strong>Email:</strong> ${farmer.email || 'N/A'}</p>
                            <p><strong>Username:</strong> ${farmer.username || 'N/A'}</p>
                            <p><strong>Contact Number:</strong> ${farmer.phone || 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Account Information</h6>
                            
                            <p><strong>Role:</strong> <span class="badge badge-success">Farmer</span></p>
                            <p><strong>Status:</strong> <span class="badge badge-${farmer.status === 'active' || farmer.status === 'approved' ? 'success' : 'warning'}">${farmer.status}</span></p>
                            <p><strong>Barangay:</strong> ${farmer.barangay || 'N/A'}</p>
                            <p><strong>Registration Date:</strong> ${farmer.created_at ? new Date(farmer.created_at).toLocaleDateString() : 'N/A'}</p>
                            <p><strong>Last Updated:</strong> ${farmer.updated_at ? new Date(farmer.updated_at).toLocaleDateString() : 'N/A'}</p>
                        </div>
                    </div>
                `;

                document.getElementById('farmerDetails').innerHTML = details;
                document.getElementById('farmerNameHidden').value = `${farmer.first_name || ''} ${farmer.last_name || ''}`;
                $('#detailsModal').modal('show');
            } else {
                alert('Error loading farmer details');
            }
        },
        error: function() {
            alert('Error loading farmer details');
        }
    });
}

// Open Approve Farmer Modal
function approveFarmer(farmerId) {
    // Set hidden field in modal
    $('#farmerIdHiddenApprove').val(farmerId);
    // Show modal
    $('#approveModal').modal('show');
}

// Handle Approve Submission
function submitApproval(event) {
    event.preventDefault();

    const farmerId = $('#farmerIdHiddenApprove').val();

    $.ajax({
        url: `{{ route("admin.farmers.approve", ":id") }}`.replace(':id', farmerId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#approveModal').modal('hide');
                loadPendingFarmers();
                loadActiveFarmers();
                updateStats();
                showNotification('Farmer approved successfully!', 'success');
            } else {
                showNotification(response.message || 'Error approving farmer', 'danger');
            }
        },
        error: function() {
            $('#approveModal').modal('hide');
            showNotification('Error approving farmer', 'danger');
        }
    });
}


function showRejectionModal(farmerId) {
    document.getElementById('farmerIdHidden').value = farmerId;
    $('#rejectionModal').modal('show');
}

function submitRejection(event) {
    event.preventDefault();
    const farmerId = document.getElementById('farmerIdHidden').value;
    const reason = document.getElementById('rejectionReason').value;

    $.ajax({
        url: `{{ route("admin.farmers.reject", ":id") }}`.replace(':id', farmerId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            rejection_reason: reason
        },
        success: function(response) {
            if (response.success) {
                $('#rejectionModal').modal('hide');
                document.getElementById('rejectionReason').value = '';
                loadPendingFarmers();
                updateStats();
                showNotification('Farmer registration rejected.', 'warning');
            } else {
                showNotification(response.message || 'Error rejecting farmer', 'danger');
            }
        },
        error: function(xhr) {
            showNotification('Error rejecting farmer', 'danger');
        }
    });
}

function deactivateFarmer(farmerId) {
    if (!confirm('Are you sure you want to deactivate this farmer?')) return;

    $.ajax({
        url: `{{ route("admin.farmers.deactivate", ":id") }}`.replace(':id', farmerId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                loadActiveFarmers();
                updateStats();
                showNotification('Farmer deactivated successfully.', 'warning');
            } else {
                showNotification(response.message || 'Error deactivating farmer', 'danger');
            }
        },
        error: function(xhr) {
            showNotification('Error deactivating farmer', 'danger');
        }
    });
}



function openContactModal() {
    $('#detailsModal').modal('hide');
    $('#contactModal').modal('show');
}

function sendMessage(event) {
    event.preventDefault();
    const name = document.getElementById('farmerNameHidden').value;
    const subject = document.getElementById('messageSubject').value;
    const message = document.getElementById('messageBody').value;

    // Send message via AJAX
    $.ajax({
        url: '{{ route("admin.farmers.contact") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            name: name,
            subject: subject,
            message: message
        },
        success: function(response) {
            if (response.success) {
                document.getElementById('messageNotification').innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Message sent to <strong>${name}</strong> successfully!
                    </div>
                `;
                document.getElementById('messageNotification').style.display = 'block';

                document.getElementById('messageSubject').value = '';
                document.getElementById('messageBody').value = '';
                
                setTimeout(() => {
                    $('#contactModal').modal('hide');
                    document.getElementById('messageNotification').style.display = 'none';
                }, 2000);
            } else {
                document.getElementById('messageNotification').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        ${response.message || 'Error sending message. Please try again.'}
                    </div>
                `;
                document.getElementById('messageNotification').style.display = 'block';
            }
        },
        error: function(xhr) {
            document.getElementById('messageNotification').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    Error sending message. Please try again.
                </div>
            `;
            document.getElementById('messageNotification').style.display = 'block';
        }
    });
}

function refreshPendingData() {
    const refreshBtn = $('button[onclick="refreshPendingData()"]');
    const originalIcon = refreshBtn.html();
    refreshBtn.html('<i class="fas fa-spinner fa-spin"></i>');
    refreshBtn.prop('disabled', true);
    
    loadPendingFarmers();
    updateStats();
    
    setTimeout(() => {
        refreshBtn.html(originalIcon);
        refreshBtn.prop('disabled', false);
        showNotification('Pending farmers data refreshed', 'success');
    }, 1000);
}

function refreshActiveData() {
    const refreshBtn = $('button[onclick="refreshActiveData()"]');
    const originalIcon = refreshBtn.html();
    refreshBtn.html('<i class="fas fa-spinner fa-spin"></i>');
    refreshBtn.prop('disabled', true);
    
    loadActiveFarmers();
    updateStats();
    
    setTimeout(() => {
        refreshBtn.html(originalIcon);
        refreshBtn.prop('disabled', false);
        showNotification('Active farmers data refreshed', 'success');
    }, 1000);
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

// Export functions
function exportCSV(tableId) {
    const table = tableId === 'pendingFarmersTable' ? pendingFarmersTable : activeFarmersTable;
    table.button('.buttons-csv').trigger();
}

function exportPDF(tableId) {
    const table = tableId === 'pendingFarmersTable' ? pendingFarmersTable : activeFarmersTable;
    table.button('.buttons-pdf').trigger();
}

function printTable(tableId) {
    const table = tableId === 'pendingFarmersTable' ? pendingFarmersTable : activeFarmersTable;
    table.button('.buttons-print').trigger();
}

function exportPNG(tableId) {
    const tableElement = document.getElementById(tableId);
    html2canvas(tableElement).then(canvas => {
        let link = document.createElement('a');
        link.download = `${tableId}_Report.png`;
        link.href = canvas.toDataURL("image/png");
        link.click();
    });
}
</script>
@endpush
