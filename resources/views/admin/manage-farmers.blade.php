@extends('layouts.app')

@section('title', 'LBDAIRY: Admin - Manage Farmers')

@push('styles')
<style>
      /* Make table cells wrap instead of forcing them all inline */
#activeFarmersTable td, 
#activeFarmersTable th {
    white-space: normal !important;  /* allow wrapping */
    vertical-align: middle;
}

/* Make sure action buttons don’t overflow */
#activeFarmersTable td .btn-group {
    display: flex;
    flex-wrap: wrap; /* buttons wrap if not enough space */
    gap: 0.25rem;    /* small gap between buttons */
}

#activeFarmersTable td .btn-action {
    flex: 1 1 auto; /* allow buttons to shrink/grow */
    min-width: 90px; /* prevent too tiny buttons */
    text-align: center;
}

#pendingFarmersTable td, 
#pendingFarmersTable th {
    white-space: normal !important;  /* allow wrapping */
    vertical-align: middle;
}

/* Make sure action buttons don’t overflow */
#pendingFarmersTable td .btn-group {
    display: flex;
    flex-wrap: wrap; /* buttons wrap if not enough space */
    gap: 0.25rem;    /* small gap between buttons */
}

#pendingFarmersTable td .btn-action {
    flex: 1 1 auto; /* allow buttons to shrink/grow */
    min-width: 90px; /* prevent too tiny buttons */
    text-align: center;
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

     #approveModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #approveModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #approveModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #approveModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #approveModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #approveModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #approveModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #approveModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
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
    
   
    
 /* ===== Edit Button ===== */
  .btn-action-edit {
    background-color: white;
    border: 1px solid #387057 !important;
    color: #387057; /* blue text */
    }
    
    .btn-action-edit:hover {
        background-color: #387057; /* yellow on hover */
    border: 1px solid #387057 !important;
    color: white;
    }
.btn-action-ok {
    background-color: white;
    border: 1px solid #18375d !important;
    color: #18375d; /* blue text */
}

.btn-action-ok:hover {
    background-color: #18375d; /* yellow on hover */
    border: 1px solid #18375d !important;
    color: white;
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

 .btn-action-refresh-admins, .btn-action-refresh-farmers {
    background-color: white !important;
    border: 1px solid #fca700 !important;
    color: #fca700 !important; /* blue text */
}
    
.btn-action-refresh-admins:hover, .btn-action-refresh-farmers:hover {
    background-color: #fca700 !important; /* yellow on hover */
    border: 1px solid #fca700 !important;
    color: white !important;
}

.btn-action-tools {
    background-color: white !important;
    border: 1px solid #495057 !important;
    color: #495057 !important;
}

.btn-action-tools:hover {
    background-color: #495057 !important; /* yellow on hover */
    border: 1px solid #495057 !important;
    color: white !important;
}
    
    .btn-action-print {
        background-color: #387057 ;
        border-color: #387057 ;
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
    
    
    /* Apply consistent styling for Pending Farmers and Active Farmers tables */
#pendingFarmersTable th,
#pendingFarmersTable td,
#activeFarmersTable th,
#activeFarmersTable td {
    vertical-align: middle;
    padding: 0.75rem;
    text-align: center;
    border: 1px solid #dee2e6;
    white-space: nowrap;
    overflow: visible;
}

/* Ensure all table headers have consistent styling */
#pendingFarmersTable thead th,
#activeFarmersTable thead th {
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
#pendingFarmersTable thead th.sorting,
#pendingFarmersTable thead th.sorting_asc,
#pendingFarmersTable thead th.sorting_desc,
#activeFarmersTable thead th.sorting,
#activeFarmersTable thead th.sorting_asc,
#activeFarmersTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#pendingFarmersTable thead th::after,
#activeFarmersTable thead th::after {
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
#pendingFarmersTable thead th.sorting::after,
#pendingFarmersTable thead th.sorting_asc::after,
#pendingFarmersTable thead th.sorting_desc::after,
#activeFarmersTable thead th.sorting::after,
#activeFarmersTable thead th.sorting_asc::after,
#activeFarmersTable thead th.sorting_desc::after {
    display: none;
}

/* Allow table to scroll horizontally if too wide */
.table-responsive {
    overflow-x: auto;
}

/* Make table cells wrap instead of forcing them all inline */
#issuesTable td, 
#issuesTable th {
    white-space: normal !important;  /* allow wrapping */
    vertical-align: middle;
}

/* Make sure action buttons don’t overflow */
#issuesTable td .btn-group {
    display: flex;
    flex-wrap: wrap; /* buttons wrap if not enough space */
    gap: 0.25rem;    /* small gap between buttons */
}

#issuesTable td .btn-action {
    flex: 1 1 auto; /* allow buttons to shrink/grow */
    min-width: 90px; /* prevent too tiny buttons */
    text-align: center;
}

    /* User Details Modal Styling */
    #detailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #detailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #detailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #detailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #detailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #detailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #detailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
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
    
    
    /* Ensure table has enough space for actions column */
    .table th:last-child,
    .table td:last-child {
        min-width: 200px;
        width: auto;
        text-align: center;
        vertical-align: middle;
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
    
        /* Style all labels inside form Modal */
    #rejectionModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }
    #contactModal .form-group label {
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

.smart-modal {
  border: none;
  border-radius: 16px;
  background: #fff;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  max-width: 500px;
  margin: auto;
  transition: all 0.3s ease;
}

.smart-modal .icon-circle {
  width: 55px;
    height: 55px;
    background-color: #e8f0fe;
    color: #18375d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.smart-modal h5 {
  color: #18375d;
  font-weight: 600;
}

.smart-modal p {
  color: #6b7280;
  font-size: 0.95rem;
}
.btn-approve {
  background: #387057;
  color: #fff;
  border: none;
}
.btn-approve:hover {
  background: #fca700;
}
.btn-delete {
  background: #dc3545;
  color: #fff;
  border: none;
}
.btn-delete:hover {
  background: #fca700;
}
.btn-ok {
  background: #18375d;
  color: #fff;
  border: none;
}
.btn-ok:hover {
  background: #fca700;
}

/* Contact Farmer Modal Alignment */
#contactModal .smart-modal {
    text-align: center; /* Keep header text centered */
}

#contactModal form {
    text-align: left; /* Align form content to the left */
}

/* Make sure labels, inputs, and textareas are properly aligned */
#contactModal .form-group {
    width: 100%;
    max-width: 700px; /* Optional: limits width for large screens */
    margin: 0 auto; /* Centers the form container */
}

/* Label styling */
#contactModal label {
    display: block;
    font-weight: 600;
    color: #333;
}

/* Inputs and Textareas */
#contactModal .form-control {
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 15px;
    box-shadow: none;
}

/* Keep modal buttons centered */
#contactModal .modal-footer {
    text-align: center;
}

/* Optional: Add smooth focus effect */
#contactModal .form-control:focus {
    border-color: #198754; /* Bootstrap green */
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}

/* Contact Farmer Modal Alignment */
#rejectionModal .smart-modal {
    text-align: center; /* Keep header text centered */
}

#rejectionModal form {
    text-align: left; /* Align form content to the left */
}

/* Make sure labels, inputs, and textareas are properly aligned */
#rejectionModal .form-group {
    width: 100%;
    max-width: 700px; /* Optional: limits width for large screens */
    margin: 0 auto; /* Centers the form container */
}

/* Label styling */
#rejectionModal label {
    display: block;
    font-weight: 600;
    color: #333;
}

/* Inputs and Textareas */
#rejectionModal .form-control {
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 15px;
    box-shadow: none;
}

/* Keep modal buttons centered */
#rejectionModal .modal-footer {
    text-align: center;
}

/* Optional: Add smooth focus effect */
#rejectionModal .form-control:focus {
    border-color: #198754; /* Bootstrap green */
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}
/* Ensure internal content aligns properly */
.smart-modal .details-content {
    text-align: left;
}
/* Custom modal width */
.custom-width {
  max-width: 1000px !important; /* adjust as needed */
  width: 90%; /* keeps it responsive */
}

/* Optional: make sure it scales well on smaller screens */
@media (max-width: 992px) {
  .custom-width {
    max-width: 95% !important;
    width: 95%;
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

/* Icon Header */
.smart-detail .icon-circle {
    width: 55px;
    height: 55px;
    background-color: #e8f0fe;
    color: #18375d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
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
    font-size: 0.96rem;
    margin-bottom: 1.8rem;
    line-height: 1.5;
}

/* MODAL BODY */
.smart-detail .modal-body {
    background: #ffffff;
    padding: 1.75rem 2rem;
    border-radius: 1rem;
    max-height: 70vh; /* ensures content scrolls on smaller screens */
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

/* Detail Section */
.smart-detail .detail-wrapper {
    background: #f9fafb;
    border-radius: 1rem;
    padding: 1.5rem;
    font-size: 0.95rem;
}

.smart-detail .detail-row {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px dashed #ddd;
    padding: 0.5rem 0;
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
#userDetailsModal .modal-footer {
    text-align: center;
    border-top: 1px solid #e5e7eb;
    padding-top: 1.25rem;
    margin-top: 1.5rem;
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
    <!-- Page Header -->
    <div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
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
        <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start">
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
                 <div class="d-flex align-items-center justify-content-center flex-nowrap gap-2 action-toolbar">
                    <button class="btn-action btn-action-refresh-farmers" title="Refresh" onclick="refreshPendingFarmersTable('pendingFarmersTable')">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" title="Tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="printTable('pendingFarmersTable')">
                                <i class="fas fa-print"></i> Print Table
                            </a>
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


    <!-- Active Farmers Card -->
     <div class="card shadow mb-4 fade-in">
        <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start">
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
                 <div class="d-flex align-items-center justify-content-center flex-nowrap gap-2 action-toolbar">
                    <button class="btn-action btn-action-refresh-admins" title="Refresh" onclick="refreshAdminsTable('activeFarmersTable')">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" title="Tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="printTable('activeFarmersTable')">
                                <i class="fas fa-print"></i> Print Table
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportCSV('activeFarmersTable')">
                                <i class="fas fa-file-csv"></i> Download CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPNG('activeFarmersTable')">
                                <i class="fas fa-image"></i> Download PNG
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPDF('activeFarmersTable')">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                            <a class="dropdown-item" href="#" onclick="document.getElementById('activeFarmersCsvInput').click()">
                                <i class="fas fa-file-csv"></i> Import CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="activeFarmersTable" width="100%" cellspacing="0">
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
                    <tbody id="activeFarmersBody">
                        <!-- Active farmers will be loaded here -->
                    </tbody>
                </table>
            </div>
            <input type="file" id="activeFarmersCsvInput" accept=".csv" style="display:none" onchange="importFarmersCSV('active', event)">
        </div>
    </div>


</div>


<!-- Smart Detail Modal -->
<div class="modal fade admin-modal" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

        <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-user fa-2x "></i>
                </div>
                <h5 class="fw-bold mb-1">Farmer Details </h5>
                <p class="text-muted mb-0 small text-center">Below are the complete details of the selected user.</p>
            </div>

      <!-- Body -->
      <div class="modal-body">
        <div id="farmerDetails" >
          <!-- Dynamic details injected here -->
        </div>
      </div>

      <!-- Footer -->

        <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
            <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
            <button type="button" class="btn-modern btn-ok" onclick="openContactModal()">
                <i class="fas fa-envelope"></i> Contact Farmer
            </button>
        </div>

    </div>
  </div>
</div>


<!-- Modern Contact Farmer Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content smart-modal text-center p-4">
            <!-- Icon -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                <i class="fas fa-paper-plane fa-2x"></i>
            </div>
                <h5 class="fw-bold mb-1">Send Message to Farmer </h5>
                <p class="text-muted mb-0 small">Please fill out the form below to send a message to the farmer. Ensure your message is clear and concise.</p>
            </div>

            <!-- Form -->
            <form onsubmit="sendMessage(event)">
                @csrf
                <input type="hidden" id="farmerNameHidden">
                <input type="hidden" id="farmerIdHidden">

                <!-- Subject Field -->
                <div class="form-group px-3 text-start">
                    <label for="messageSubject" class="fw-semibold text-start">
                        Subject <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        class="form-control mt-1" 
                        id="messageSubject" 
                        required 
                        placeholder="Enter a subject here...">
                </div>

                <!-- Message Field -->
                <div class="form-group px-3 text-start mt-3">
                    <label for="messageBody" class="fw-semibold text-start">
                        Message <span class="text-danger">*</span>
                    </label>
                    <textarea 
                        class="form-control mt-1" 
                        id="messageBody" 
                        rows="5" 
                        required 
                        placeholder="Write your message here..." 
                        style="resize: none;"></textarea>
                </div>

                <!-- Notification -->
                <div id="messageNotification" class="mt-3 text-center" style="display: none;"></div>

                <!-- Buttons -->
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-ok">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modern Rejection Reason Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content smart-modal text-center p-4">
            <!-- Icon -->
            <div class="icon-wrapper mx-auto mb-4 text-danger">
                <i class="fas fa-times-circle fa-2x"></i>
            </div>
            <!-- Title -->
            <h5>Reject Farmer Registration</h5>
            <!-- Description -->
            <p class="text-muted mb-4 px-3">
                Please provide a reason for rejecting this farmer’s registration.
            </p>

            <!-- Form -->
            <form onsubmit="submitRejection(event)">
                @csrf
                <input type="hidden" id="farmerIdHidden">

                <!-- Reason Field -->
                <div class="form-group px-3 text-start">
                    <label for="rejectionReason" class="fw-semibold  text-start">
                        Reason for Rejection <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control mt-1" id="rejectionReason" rows="4" required placeholder="Enter reason for rejection..."></textarea>
                </div>


                <!-- Buttons -->
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-delete">
                        <i class="fas fa-times"></i> Reject 
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Deactivate Farmer Modal -->
<div class="modal fade" id="deactivateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content smart-modal text-center p-4">
            <!-- Icon -->
            <div class="icon-wrapper mx-auto mb-4 text-danger">
                <i class="fas fa-check-circle fa-2x"></i>
            </div>
            <!-- Title -->
            <h5>Deactivate Farmer </h5>
            <!-- Description -->
            <p class="text-muted mb-4 px-3">
                Are you sure you want to <strong>deactivate</strong> this farmer?
            </p>

            <!-- Form -->
            <form onsubmit="submitDeactivation(event)">
                @csrf
                <input type="hidden" id="deactivateFarmerIdHidden">

                <!-- Buttons -->
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-delete">
                        <i class="fas fa-user-slash"></i> Deactivate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modern Approve Farmer Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content smart-modal text-center p-4">
            <!-- Icon -->
            <div class="icon-wrapper mx-auto mb-4 text-success">
                <i class="fas fa-check-circle fa-2x"></i>
            </div>
            <!-- Title -->
            <h5>Approve Farmer Registration</h5>
            <!-- Description -->
            <p class="text-muted mb-4 px-3">
                Are you sure you want to <strong>approve</strong> this farmer’s registration?
            </p>

            <!-- Form -->
            <form onsubmit="submitApproval(event)">
                @csrf
                <input type="hidden" id="farmerIdHiddenApprove">

                <!-- Buttons -->
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-approve">
                        <i class="fas fa-check-circle"></i> Approve
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
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Schedule Inspection
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

<!-- Required libraries for PDF/Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<!-- jsPDF and autoTable for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<script>
let pendingFarmersTable;
let activeFarmersTable;
let downloadCounter = 1;

function escapeHtml(value) {
    if (value === null || value === undefined) return '';
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;')
        .replace(/\//g, '&#x2F;');
}

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
            showNotification('Pending Farmers data refreshed successfully!', 'success');
        }, 500);
    }

    if (sessionStorage.getItem('showRefreshNotificationAdmins') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationAdmins');
        setTimeout(() => {
            showNotification('Active Farmers data refreshed successfully!', 'success');
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
                        `<div class="btn-group">
                            <button class="btn-action btn-action-ok" onclick="approveFarmer('${farmer.id}')" title="Approve">
                                <i class="fas fa-check"></i>
                                <span>Approve</span>
                            </button>
                            <button class="btn-action btn-action-deletes" onclick="showRejectionModal('${farmer.id}')" title="Reject">
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
                            <button class="btn-action btn-action-ok" onclick="showFarmerDetails(${farmer.id})" title="View Details">
                                <i class="fas fa-eye"></i>
                                <span>View</span>
                            </button>
                            <button class="btn-action btn-action-edit" onclick="openContactModal(${farmer.id})" title="Contact">
                                <i class="fas fa-envelope"></i>
                                <span>Contact</span>
                            </button>
                            <button class="btn-action btn-action-deletes" onclick="deactivateFarmer('${farmer.id}')" title="Deactivate">
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
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;"><i class="fas fa-user-circle me-2"></i> Personal Information</h6>
                            <p><strong>Full Name:</strong> ${escapeHtml(farmer.first_name || '')} ${escapeHtml(farmer.last_name || '')}</p>
                            <p><strong>Email:</strong> ${escapeHtml(farmer.email || 'N/A')}</p>
                            <p><strong>Username:</strong> ${escapeHtml(farmer.username || 'N/A')}</p>
                            <p><strong>Contact Number:</strong> ${escapeHtml(farmer.phone || 'N/A')}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;"><i class="fas fa-id-card me-2"></i> Account Information</h6>
                            <p><strong>Role:</strong> <span class="badge badge-success">Farmer</span></p>
                            <p><strong>Status:</strong> <span class="badge badge-${farmer.status === 'active' || farmer.status === 'approved' ? 'success' : 'warning'}">${escapeHtml(farmer.status)}</span></p>
                            <p><strong>Barangay:</strong> ${escapeHtml(farmer.barangay || 'N/A')}</p>
                            <p><strong>Registration Date:</strong> ${farmer.created_at ? new Date(farmer.created_at).toLocaleDateString() : 'N/A'}</p>
                            <p><strong>Last Updated:</strong> ${farmer.updated_at ? new Date(farmer.updated_at).toLocaleDateString() : 'N/A'}</p>
                        </div>
                    </div>
                `;

                document.getElementById('farmerDetails').innerHTML = details;
                document.getElementById('farmerNameHidden').value = `${farmer.first_name || ''} ${farmer.last_name || ''}`;
                document.getElementById('farmerIdHidden').value = farmer.id;
                
                // Hide contact farmer button for all farmers when accessed via user ID click
                const contactButton = $('#detailsModal .modal-footer button[onclick="openContactModal()"]');
                contactButton.hide();
                
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
    $('#deactivateFarmerIdHidden').val(farmerId);
    $('#deactivateModal').modal('show');
}

function submitDeactivation(event) {
    event.preventDefault();
    const farmerId = $('#deactivateFarmerIdHidden').val();
    if (!farmerId) {
        showNotification('Missing farmer ID for deactivation', 'danger');
        return;
    }
    $.ajax({
        url: `{{ route('admin.farmers.deactivate', ':id') }}`.replace(':id', farmerId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#deactivateModal').modal('hide');
            if (response.success) {
                loadActiveFarmers();
                updateStats();
                showNotification('Farmer deactivated successfully.', 'warning');
            } else {
                showNotification(response.message || 'Error deactivating farmer', 'danger');
            }
        },
        error: function() {
            $('#deactivateModal').modal('hide');
            showNotification('Error deactivating farmer', 'danger');
        }
    });
}




function openContactModal(farmerId, farmerName) {
    if (typeof farmerId !== 'undefined' && farmerId !== null) {
        const idInput = document.getElementById('farmerIdHidden');
        if (idInput) idInput.value = farmerId;
    }
    if (typeof farmerName !== 'undefined' && farmerName !== null) {
        const nameInput = document.getElementById('farmerNameHidden');
        if (nameInput) nameInput.value = farmerName;
    }
    $('#detailsModal').modal('hide');
    $('#contactModal').modal('show');
}

function sendMessage(event) {
    event.preventDefault();
    const name = document.getElementById('farmerNameHidden').value;
    const farmerId = document.getElementById('farmerIdHidden').value;
    const subject = document.getElementById('messageSubject').value;
    const message = document.getElementById('messageBody').value;

    if (!farmerId) {
        document.getElementById('messageNotification').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle mr-2"></i>
                Please select a farmer before sending a message.
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;
        document.getElementById('messageNotification').style.display = 'block';
        return;
    }

    // Send message via AJAX
    $.ajax({
        url: '{{ route("admin.farmers.contact") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            farmer_id: farmerId,
            subject: subject,
            message: message
        },
        success: function(response) {
            var nEl = document.getElementById('farmerNameHidden');
            var displayName = nEl && nEl.value ? nEl.value : 'Farmer';
            showNotification(`Message sent to ${displayName} successfully!`, 'success');

            document.getElementById('messageSubject').value = '';
            document.getElementById('messageBody').value = '';
        },

error: function(xhr) {
    // Show error notification
    showNotification(
        `<i class="fas fa-exclamation-circle"></i> Error sending message. Please try again.`,
        'danger'
    );
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
            ${escapeHtml(message)}
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
    const tableData = table.data().toArray();
    const csvData = [];
    
    // Determine headers based on table type
    let headers = [];
    let reportTitle = '';
    
    if (tableId === 'pendingFarmersTable') {
        headers = ['User ID', 'Name', 'Farm Name', 'Barangay', 'Contact', 'Email', 'Username', 'Registration Date'];
        reportTitle = 'PendingFarmers';
    } else {
        headers = ['User ID', 'Name', 'Farm Name', 'Barangay', 'Contact', 'Username', 'Registration Date'];
        reportTitle = 'ActiveFarmers';
    }
    
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
    link.setAttribute('download', `${reportTitle}_Report_${downloadCounter}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Increment download counter
    downloadCounter++;
}

function exportPDF(tableId) {
    try {
        // Force custom PDF generation to match superadmin styling
        // Don't fall back to DataTables PDF export as it has different styling
        
        const table = tableId === 'pendingFarmersTable' ? pendingFarmersTable : activeFarmersTable;
        const tableData = table.data().toArray();
        const pdfData = [];
        
        // Determine headers and title based on table type
        let headers = [];
        let reportTitle = '';
        
        if (tableId === 'pendingFarmersTable') {
            headers = ['User ID', 'Name', 'Farm Name', 'Barangay', 'Contact', 'Email', 'Username', 'Registration Date'];
            reportTitle = 'Admin Pending Registrations Report';
        } else {
            headers = ['User ID', 'Name', 'Farm Name', 'Barangay', 'Contact', 'Email', 'Username', 'Registration Date'];
            reportTitle = 'Admin Active Farmers Report';
        }
        
        // Add data rows (excluding Actions column)
        tableData.forEach(row => {
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
                rowData.push(cellText);
            }
            pdfData.push(rowData);
        });
        
        // Create PDF using jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape', 'mm', 'a4');
        
        // Set title
        doc.setFontSize(18);
        doc.text(reportTitle, 14, 22);
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
        const fileName = tableId === 'pendingFarmersTable' ? 'Admin_PendingRegistrationsReport' : 'Admin_ActiveFarmersReport';
        doc.save(`${fileName}_${downloadCounter}.pdf`);
        
        // Increment download counter
        downloadCounter++;
        
        showNotification('PDF exported successfully!', 'success');
        
    } catch (error) {
        console.error('Error generating PDF:', error);
        showNotification('Error generating PDF. Please try again.', 'danger');
    }
}

function printTable(tableId) {
    try {
        const table = tableId === 'pendingFarmersTable' ? pendingFarmersTable : activeFarmersTable;
        const tableData = table.data().toArray();
        
        if (!tableData || tableData.length === 0) {
            showNotification('No data available to print', 'warning');
            return;
        }
        
        // Determine report title based on table type
        const reportTitle = tableId === 'pendingFarmersTable' ? 'Pending Farmers Report' : 'Active Farmers Report';
        
        // Create print content directly in current page
        const originalContent = document.body.innerHTML;
        
        // Determine column headers based on table type
        let columnHeaders = '';
        if (tableId === 'pendingFarmersTable') {
            columnHeaders = `
                            <th style="border: 2px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">User ID</th>
                            <th style="border: 2px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Name</th>
                            <th style="border: 2px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Farm Name</th>
                            <th style="border: 2px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Barangay</th>
                            <th style="border: 2px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Contact</th>
                            <th style="border: 2px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Email</th>
                            <th style="border: 2px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Username</th>
                            <th style="border: 2px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Registration Date</th>`;
        } else {
            // Active Farmers Table (no Email column)
            columnHeaders = `
                            <th style="border: 2px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">User ID</th>
                            <th style="border: 2px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Name</th>
                            <th style="border: 2px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Farm Name</th>
                            <th style="border: 2px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Barangay</th>
                            <th style="border: 2px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Contact</th>
                            <th style="border: 2px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Email</th>
                            <th style="border: 2px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Username</th>
                            <th style="border: 2px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Registration Date</th>`;
        }

        let printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #18375d; margin-bottom: 5px;">${reportTitle}</h1>
                    <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                <table border="3" style="border-collapse: collapse; width: 100%; border: 3px solid #000;">
                    <thead>
                        <tr>
                            ${columnHeaders}
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
            window.openPrintWindow(printContent, reportTitle);
        } else {
            window.print();
        }
        
    } catch (error) {
        console.error('Error in print function:', error);
        showNotification('Error generating print. Please try again.', 'danger');
        
        // Fallback to DataTables print
        try {
            const table = tableId === 'pendingFarmersTable' ? pendingFarmersTable : activeFarmersTable;
            table.button('.buttons-print').trigger();
        } catch (fallbackError) {
            console.error('Fallback print also failed:', fallbackError);
            showNotification('Print failed. Please try again.', 'danger');
        }
    }
}

function exportPNG(tableId) {
    // Create a temporary table without the Actions column for export
    const originalTable = document.getElementById(tableId);
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
        const fileName = tableId === 'pendingFarmersTable' ? 'PendingFarmers' : 'ActiveFarmers';
        link.download = `${fileName}_Report_${downloadCounter}.png`;
        link.href = canvas.toDataURL("image/png");
        link.click();
        
        // Increment download counter
        downloadCounter++;
        
        // Clean up - remove temporary table
        document.body.removeChild(tempTable);
    }).catch(error => {
        console.error('Error generating PNG:', error);
        // Clean up on error
        if (document.body.contains(tempTable)) {
            document.body.removeChild(tempTable);
        }
        showNotification('Error generating PNG export', 'danger');
    });
}
</script>
@endpush
