@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers-Sales')
@push('styles')
<style>
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
#salesTable th,
#salesTable td,
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
#salesTable thead th,
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
#salesTable thead th.sorting,
#salesTable thead th.sorting_asc,
#salesTable thead th.sorting_desc,
#activeFarmersTable thead th.sorting,
#activeFarmersTable thead th.sorting_asc,
#activeFarmersTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#salesTable thead th::after,
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
#salesTable thead th.sorting::after,
#salesTable thead th.sorting_asc::after,
#salesTable thead th.sorting_desc::after,
#activeFarmersTable thead th.sorting::after,
#activeFarmersTable thead th.sorting_asc::after,
#activeFarmersTable thead th.sorting_desc::after {
    display: none;
}
/* Make table cells wrap instead of forcing them all inline */
#salesTable td, 
#salesTable th {
    white-space: normal !important;  /* allow wrapping */
    vertical-align: middle;
}

/* Make sure action buttons don’t overflow */
#salesTable td .btn-group {
    display: flex;
    flex-wrap: wrap; /* buttons wrap if not enough space */
    gap: 0.25rem;    /* small gap between buttons */
}

#salesTable td .btn-action {
    flex: 1 1 auto; /* allow buttons to shrink/grow */
    min-width: 90px; /* prevent too tiny buttons */
    text-align: center;
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
    
    .btn-action-edits {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
    .btn-action-edits:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
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

    .btn-action-import {
        background-color: #1b3043;
        border-color: #1b3043;
        color: white;
    }
    
    .btn-action-import:hover {
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
/* CRITICAL FIX FOR DROPDOWN TEXT CUTTING */
    .farmer-modal select.form-control,
    .modal.farmer-modal select.form-control,
    .farmer-modal .modal-body select.form-control {
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
    .farmer-modal .col-md-6 {
        min-width: 280px !important;
        overflow: visible !important;
    }

     #addLivestockDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #addLivestockDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #addLivestockDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #addLivestockDetailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #addLivestockDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #addLivestockDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #addLivestockDetailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #addLivestockDetailsModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }
    /* */
    #historyModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #historyModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #historyModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #historyModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #historyModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #historyModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #historyModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #historyModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
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
#addLivestockDetailsModal form {
  text-align: left;
}

#addLivestockDetailsModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#addLivestockDetailsModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#addLivestockDetailsModal .form-control,
#addLivestockDetailsModal select.form-control,
#addLivestockDetailsModal textarea.form-control {
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
#addLivestockDetailsModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#addLivestockDetailsModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}


#editLivestockModal form {
  text-align: left;
}

#editLivestockModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#editLivestockModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
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

  #editLivestockModal .form-control {
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
#historyModal .modal-footer {
    text-align: center;
    border-top: 1px solid #e5e7eb;
    padding-top: 1.25rem;
    margin-top: 1.5rem;
}
/* User Details Modal Styling */
    #saleDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #saleDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #saleDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #saleDetailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #saleDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #saleDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #saleDetailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }
</style>
@section('content')
<!-- Page Header -->
<div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
    <h1>
        <i class="fas fa-chart-line"></i>
        Sales Management
    </h1>
    <p>Track your livestock sales, analyze performance, and manage revenue</p>
</div>

<!-- Statistics Grid -->
    <div class="row fade-in">
        <!-- Total Livestock -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Sales</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($totalSales) }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-users fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Healthy Livestock -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">This Month</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($monthlySales) }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-heart fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Needs Attention -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Transaction</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTransactions }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-exclamation-triangle fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Production Ready -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Average Price</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($averagePrice) }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-tint fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Sales Table -->
        <div class="card shadow mb-4 fade-in">
            <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
                <h6 class="mb-0">
                    <i class="fas fa-list mr-2"></i> Sales Records
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-end align-items-center gap-2">
                        <button class="btn-action btn-action-ok" data-toggle="modal" data-target="#addLivestockDetailsModal">
                        <i class="fas fa-plus mr-1"></i> Add Sale
                        </button>
                        <button class="btn-action btn-action-edit" onclick="printProductivity()">
                        <i class="fas fa-print mr-1"></i> Print
                        </button>
                        <button class="btn-action btn-action-refresh" onclick="refreshSalesTable('salesTable')">
                        <i class="fas fa-sync-alt mr-1"></i> Refresh
                        </button>
                        <button class="btn-action btn-action-history" data-toggle="modal" data-target="#historyModal">
                        <i class="fas fa-history mr-1"></i> History
                        </button>
                        <div class="dropdown">
                            <button class="btn-action btn-action-tools" type="button" data-toggle="dropdown">
                                <i class="fas fa-tools"></i> Tools
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="document.getElementById('csvInput').click()">
                                    <i class="fas fa-history"></i> Import Table
                                </a>
                                <a class="dropdown-item" href="#" onclick="exportCSV()">
                                    <i class="fas fa-file-csv"></i> Download CSV
                                </a>
                                <a class="dropdown-item" href="#" onclick="exportPNG()">
                                    <i class="fas fa-image"></i> Download PNG
                                </a>
                                <a class="dropdown-item" href="#" onclick="exportPDF()">
                                    <i class="fas fa-file-pdf"></i> Download PDF
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Hidden CSV input for Import -->
                    <input id="csvInput" type="file" accept=".csv" style="display:none" onchange="importCSV(event)">
<br>
                <div class="table-responsive">
                <table class="table table-bordered table-hover" id="salesTable" width="100%" cellspacing="0">
                        <thead >
                            <tr>
                                <th>Sale ID</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Quantity (L)</th>
                                <th>Unit Price (₱)</th>
                                <th>Total Amount (₱)</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="salesTableBody">
                            @forelse($salesData as $sale)
                            <tr>
                                <td>{{ $sale['sale_id'] }}</td>
                                <td>{{ $sale['sale_date'] }}</td>
                                <td>{{ $sale['customer_name'] }}</td>
                                <td>{{ number_format($sale['quantity'], 2) }}</td>
                                <td>₱{{ number_format($sale['unit_price'], 2) }}</td>
                                <td>₱{{ number_format($sale['amount']) }}</td>
                                <td>
                                    <span class="badge badge-{{ $sale['payment_status'] == 'paid' ? 'success' : ($sale['payment_status'] == 'partial' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($sale['payment_status']) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn-action btn-action-ok" onclick="viewSale('{{ $sale['id'] }}')" title="View Details">
                                            <i class="fas fa-eye"></i>
                                            <span>View</span>
                                        </button>
                                        <button class="btn-action btn-action-edits" onclick="editSale('{{ $sale['id'] }}')" title="Edit">
                                            <i class="fas fa-edit"></i>
                                            <span>Edit</span>
                                        </button>
                                        <button class="btn-action btn-action-deletes" onclick="confirmDelete('{{ $sale['id'] }}')" title="Delete">
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
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">No sales records available</td>
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
                Are you sure you want to <strong>delete</strong> this sale record?
            </p>
                <!-- Buttons -->
                <div class="modal-footer d-flex gap-2 justify-content-center flex-wrap">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-delete" id="confirmDeleteBtn">
                        Delete
                    </button>
                </div>
            
        </div>
    </div>
</div>

<!-- ADD SALE DETAILS MODAL -->
<div class="modal fade admin-modal" id="addLivestockDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content smart-form text-center p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-plus-circle fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">Sale Entry</h5>
                <p class="text-muted mb-0 small">
                    Enter the details below to record a new sale transaction.
                </p>
            </div>

            <!-- Form -->
            <form id="addLivestockDetailsForm" onsubmit="submitSale(event)">
                @csrf

                <div class="form-wrapper text-start mx-auto">
                    <div class="row g-3">

                        <!-- Farm -->
                        <div class="col-md-12">
                            <label for="add_farm_id" class="fw-semibold">Select Farm <span class="text-danger">*</span></label>
                            <select class="form-control" id="add_farm_id" name="farm_id" required>
                                <option value="" disabled selected>Select Farm</option>
                                @foreach($farms ?? [] as $farm)
                                    <option value="{{ $farm->id }}">{{ $farm->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Customer Info -->
                        <div class="col-md-6">
                            <label for="add_customer_name" class="fw-semibold">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_customer_name" name="customer_name" required>
                        </div>

                        <div class="col-md-6">
                            <label for="add_customer_phone" class="fw-semibold">Customer Phone</label>
                            <input type="text" class="form-control" id="add_customer_phone" name="customer_phone">
                        </div>

                        <div class="col-md-6">
                            <label for="add_customer_email" class="fw-semibold">Customer Email</label>
                            <input type="email" class="form-control" id="add_customer_email" name="customer_email">
                        </div>

                        <!-- Sale Details -->
                        <div class="col-md-6">
                            <label for="add_quantity" class="fw-semibold">Quantity (Liters) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="add_quantity" name="quantity" min="0" step="0.01" required>
                        </div>

                        <div class="col-md-6">
                            <label for="add_unit_price" class="fw-semibold">Unit Price (₱/Liter) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="add_unit_price" name="unit_price" min="0" step="0.01" required>
                        </div>

                        <div class="col-md-6">
                            <label for="add_sale_date" class="fw-semibold">Sale Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="add_sale_date" name="sale_date" required value="{{ date('Y-m-d') }}">
                        </div>

                        <!-- Payment Details -->
                        <div class="col-md-6">
                            <label for="add_payment_method" class="fw-semibold">Payment Method <span class="text-danger">*</span></label>
                            <select class="form-control" id="add_payment_method" name="payment_method" required>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="mobile_money">Mobile Money</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="add_payment_status" class="fw-semibold">Payment Status <span class="text-danger">*</span></label>
                            <select class="form-control" id="add_payment_status" name="payment_status" required>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="partial">Partial</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="add_notes" class="fw-semibold">Notes</label>
                            <textarea class="form-control mt-1" id="add_notes" name="notes" rows="4" placeholder="Additional notes about the sale..." style="resize: none;"></textarea>
                        </div>

                        <div id="formNotification" class="mt-2" style="display: none;"></div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="modal-footer d-flex gap-2 justify-content-center flex-wrap mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-ok" title="Save Sale">
                        Save Sale
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Smart Detail Modal -->
<div class="modal fade admin-modal" id="saleDetailsModal" tabindex="-1" role="dialog" aria-labelledby="saleDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

        <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-eye fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">Sale Details </h5>
                <p class="text-muted mb-0 small">Below are the complete details of the selected entry.</p>
            </div>

      <!-- Body -->
      <div class="modal-body">
        <div id="saleDetailsContent" class="detail-wrapper">
          <!-- Dynamic details injected here -->
        </div>
      </div>

      <!-- Footer -->

        <div class="modal-footer justify-content-center mt-4">
            <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
        </div>

    </div>
  </div>
</div>

<!-- Smart Detail - Sales History Modal -->
<div class="modal fade admin-modal" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content smart-detail p-4">

      <!-- Icon + Header -->
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle ">
          <i class="fas fa-history fa-2x"></i>
        </div>
        <h5 class="fw-bold mb-1">Sales History</h5>
        <p class="text-muted mb-0 small text-center">
          Review, filter, and export livestock sales history below.
        </p>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div class="form-wrapper text-start mx-auto">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="sortHistory" class="fw-semibold">Sort By:</label>
              <select id="sortHistory" class="form-control">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="filterHistory" class="fw-semibold">Filter By:</label>
              <select id="filterHistory" class="form-control">
                <option value="all">All</option>
                <option value="goat">Goat</option>
                <option value="cow">Cow</option>
                <option value="carabao">Carabao</option>
              </select>
            </div>
          </div>

          <!-- Table -->
          <div class="table-responsive rounded shadow-sm">
            <table class="table table-hover table-bordered align-middle mb-0">
              <thead class="table-light text-center">
                <tr>
                  <th>Month</th>
                  <th>Transactions</th>
                  <th>Total Sales (₱)</th>
                  <th>Average Sale (₱)</th>
                </tr>
              </thead>
              <tbody id="historyTableBody">
                <!-- Sales history dynamically inserted here -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer justify-content-center mt-4">
        <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">
          Close
        </button>
        <button type="button" class="btn-modern btn-ok" onclick="exportHistory()">
          Export History
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
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, var(--primary-color), var(--primary-dark));
}

.stat-card.border-left-success::before {
    background: linear-gradient(90deg, var(--success-color), var(--success-color));
}

.stat-card.border-left-info::before {
    background: linear-gradient(90deg, var(--info-color), var(--info-color));
}

.stat-card.border-left-warning::before {
    background: linear-gradient(90deg, var(--warning-color), var(--warning-color));
}
</style>
@endpush

@push('scripts')
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
let saleToDelete = null;
let salesDT = null;
let downloadCounter = 1;

$(document).ready(function () {
    // Initialize history data
    renderSalesHistory();

    // Initialize DataTable for Sales Records with hidden export buttons
    const commonConfig = {
        dom: 'Bfrtip',
        searching: true,
        paging: true,
        info: true,
        ordering: true,
        lengthChange: false,
        pageLength: 10,
        autoWidth: false,
        scrollX: true,
        buttons: [
            { extend: 'csvHtml5', title: 'Farmer_Sales_Report', className: 'd-none', exportOptions: { columns: [0,1,2,3,4,5,6], modifier: { page: 'all' } } },
            { extend: 'pdfHtml5', title: 'Farmer_Sales_Report', orientation: 'landscape', pageSize: 'Letter', className: 'd-none', exportOptions: { columns: [0,1,2,3,4,5,6], modifier: { page: 'all' } } },
            { extend: 'print', title: 'Farmer Sales Report', className: 'd-none', exportOptions: { columns: [0,1,2,3,4,5,6], modifier: { page: 'all' } } }
        ],
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No data available</h5><p>There are no records to display at this time.</p></div>'
        }
    };

    if ($('#salesTable').length) {
        try {
            salesDT = $('#salesTable').DataTable({
                ...commonConfig,
                order: [[1, 'desc']], // Sort by Date
                columnDefs: [
                    { width: '120px', targets: 0 }, // Sale ID
                    { width: '120px', targets: 1 }, // Date
                    { width: '200px', targets: 2 }, // Customer
                    { width: '140px', targets: 3 }, // Quantity
                    { width: '160px', targets: 4 }, // Unit Price
                    { width: '180px', targets: 5 }, // Total Amount
                    { width: '120px', targets: 6 }, // Status
                    { width: '220px', targets: 7, orderable: false } // Actions
                ]
            });
        } catch (e) {
            console.error('Failed to initialize sales DataTable:', e);
        }
    }

    // Hide default DataTables UI elements we replace with custom controls
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();

    // Form submission (create or update)
    document.getElementById('addLivestockDetailsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        submitSale();
    });
});

let editSaleId = null;

function submitSale() {
    const formData = new FormData(document.getElementById('addLivestockDetailsForm'));

    if (!formData.get('farm_id') || !formData.get('customer_name') || !formData.get('quantity') || !formData.get('unit_price') || !formData.get('sale_date')) {
        showNotification('Please fill in all required fields', 'danger');
        return;
    }

    const payload = {
        farm_id: formData.get('farm_id'),
        customer_name: formData.get('customer_name'),
        customer_phone: formData.get('customer_phone'),
        customer_email: formData.get('customer_email'),
        quantity: formData.get('quantity'),
        unit_price: formData.get('unit_price'),
        sale_date: formData.get('sale_date'),
        payment_method: formData.get('payment_method'),
        payment_status: formData.get('payment_status'),
        notes: formData.get('notes')
    };

    const url = editSaleId ? `/farmer/sales/${editSaleId}` : '{{ route("farmer.sales.store") }}';
    const method = editSaleId ? 'PUT' : 'POST';

    fetch(url, {
        method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            $('#addLivestockDetailsModal').modal('hide');
            document.getElementById('addLivestockDetailsForm').reset();
            editSaleId = null;
            location.reload();
        } else {
            showNotification(data.message || 'Failed to save sale', 'danger');
        }
    })
    .catch(err => {
        console.error('submitSale error:', err);
        showNotification('An error occurred while saving the sale record', 'danger');
    });
}

function viewSale(saleId) {
    $.ajax({
        url: `/farmer/sales/${saleId}`,
        method: 'GET',
        success: function (response) {
            if (response.success) {
                const sale = response.sale;

                $('#saleDetailsContent').html(`
                    <div class="row">
                        <!-- Sale Information -->
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">
                                Sale Information
                            </h6>
                            <p><strong>Sale ID:</strong> ${sale.sale_id || 'N/A'}</p>
                            <p><strong>Date:</strong> ${sale.sale_date ? new Date(sale.sale_date).toLocaleDateString() : 'N/A'}</p>
                            <p><strong>Customer:</strong> ${sale.customer_name || 'N/A'}</p>
                            <p><strong>Quantity:</strong> ${sale.quantity ? sale.quantity + ' L' : 'N/A'}</p>
                        </div>

                        <!-- Financial Details -->
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">
                                Financial Details
                            </h6>
                            <p><strong>Unit Price:</strong> ₱${sale.unit_price ? sale.unit_price.toFixed(2) : '0.00'}</p>
                            <p><strong>Total Amount:</strong> ₱${sale.amount ? sale.amount.toFixed(2) : '0.00'}</p>
                            <p><strong>Payment Status:</strong> 
                                <span class="badge badge-${sale.payment_status === 'paid' ? 'success' : sale.payment_status === 'pending' ? 'warning' : 'secondary'}">
                                    ${sale.payment_status ? sale.payment_status.charAt(0).toUpperCase() + sale.payment_status.slice(1) : 'N/A'}
                                </span>
                            </p>
                            <p><strong>Payment Method:</strong> ${sale.payment_method || 'N/A'}</p>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">
                                Additional Details
                            </h6>
                            <p><strong>Description:</strong></p>
                            <p>${sale.description || 'No description provided.'}</p>

                            ${sale.notes ? `
                                <p><strong>Notes:</strong></p>
                                <p>${sale.notes}</p>
                            ` : ''}
                        </div>
                    </div>
                `);

                $('#saleDetailsModal').modal('show');
            } else {
                showToast('Failed to load sale details', 'error');
            }
        },
        error: function () {
            showToast('Error loading sale details', 'error');
        }
    });
}

function editSale(saleId) {
    // Load sale data for editing
    $.ajax({
        url: `/farmer/sales/${saleId}/edit`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const sale = response.sale;
                
                // Populate the add sale modal with existing data
                $('#addLivestockDetailsModal').modal('show');
                $('#add_farm_id').val(sale.farm_id);
                $('#add_customer_name').val(sale.customer_name);
                $('#add_customer_phone').val(sale.customer_phone);
                $('#add_customer_email').val(sale.customer_email);
                $('#add_quantity').val(sale.quantity);
                $('#add_unit_price').val(sale.unit_price);
                $('#add_sale_date').val(sale.sale_date);
                $('#add_payment_method').val(sale.payment_method);
                $('#add_payment_status').val(sale.payment_status);
                $('#add_notes').val(sale.notes);
                editSaleId = saleId;
            }
        },
        error: function() {
            showNotification('Failed to load sale for editing.', 'danger');
        }
    });
}

function confirmDelete(saleId) {
    saleToDelete = saleId;
    $('#confirmDeleteModal').modal('show');
    
    // Set up delete button
    document.getElementById('confirmDeleteBtn').onclick = function() {
        deleteSale();
    };
}

function deleteSale() {
    if (saleToDelete) {
        // Send AJAX request to delete
        fetch(`/farmer/sales/${saleToDelete}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                $('#confirmDeleteModal').modal('hide');
                // Reload the page to refresh the data
                location.reload();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while deleting the sale record', 'error');
        });
        
        saleToDelete = null;
    }
}

function renderSalesHistory() {
    const historyData = @json($salesHistory);
    
    const tbody = document.getElementById('historyTableBody');
    tbody.innerHTML = '';
    
    if (historyData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center text-muted py-4">
                    <i class="fas fa-chart-line fa-3x mb-3 text-muted"></i>
                    <p>No sales history available.</p>
                </td>
            </tr>
        `;
        return;
    }
    
    historyData.forEach(sale => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${sale.month}</td>
            <td>${sale.transaction_count}</td>
            <td>₱${parseInt(sale.total_sales).toLocaleString()}</td>
            <td>₱${parseInt(sale.total_sales / sale.transaction_count).toLocaleString()}</td>
        `;
        tbody.appendChild(row);
    });
}

function updateStats() {
    // Update stats based on current table data
    const rows = dataTable.rows().data();
    let totalAmount = 0;
    let totalTransactions = 0;
    
    rows.each(function(value, index) {
        const amount = parseInt(value[3].replace(/[₱,]/g, ''));
        if (!isNaN(amount)) {
            totalAmount += amount;
            totalTransactions++;
        }
    });
    
    // Update display (you can add elements to show these values)
    console.log('Total Amount:', totalAmount, 'Total Transactions:', totalTransactions);
}

function exportCSV() {
    try {
        if (salesDT) salesDT.button('.buttons-csv').trigger();
    } catch (e) { console.error('exportCSV error:', e); }
}

function exportPDF() {
    try { if (salesDT) salesDT.button('.buttons-pdf').trigger(); } catch (e) { console.error('exportPDF error:', e); }
}

function exportPNG() {
    // Create a temporary table without the Actions column for export
    const originalTable = document.getElementById('salesTable');
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
        link.download = `Farmer_SalesReport_${downloadCounter}.png`;
        link.href = canvas.toDataURL("image/png");
        link.click();
        
        // Increment download counter
        downloadCounter++;
        
        // Clean up - remove temporary table
        document.body.removeChild(tempTable);
        
        showNotification('PNG exported successfully!', 'success');
    }).catch(error => {
        console.error('Error generating PNG:', error);
        // Clean up on error
        if (document.body.contains(tempTable)) {
            document.body.removeChild(tempTable);
        }
        showNotification('Error generating PNG export', 'danger');
    });
}

function printProductivity() {
    try { window.printElement('#salesTable'); }
    catch (e) { console.error('printProductivity error:', e); window.print(); }
}

function refreshSalesTable() {
    const btn = document.querySelector('.btn-action-refresh');
    if (btn) {
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
        btn.disabled = true;
    }
    sessionStorage.setItem('showRefreshNotificationSales', 'true');
    setTimeout(() => { location.reload(); }, 800);
}

// After reload, show notification
$(document).ready(function() {
    if (sessionStorage.getItem('showRefreshNotificationSales') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationSales');
        setTimeout(() => { showNotification('Sales data refreshed successfully!', 'success'); }, 400);
    }
});

function importCSV(event) {
    const file = event.target.files[0];
    if (file) {
        // Handle CSV import logic here
        showNotification('CSV import functionality coming soon!', 'info');
    }
}

function exportHistory() {
    try {
        const rows = Array.from(document.querySelectorAll('#historyTableBody tr'));
        if (rows.length === 0) {
            showNotification('No history data to export', 'warning');
            return;
        }
        const headers = ['Month', 'Transactions', 'Total Sales (₱)', 'Average Sale (₱)'];
        const csv = [headers.join(',')];
        rows.forEach(tr => {
            const cols = Array.from(tr.querySelectorAll('td')).map(td => {
                let text = (td.textContent || '').trim();
                if (text.includes(',') || text.includes('"') || text.includes('\n')) {
                    text = '"' + text.replace(/"/g, '""') + '"';
                }
                return text;
            });
            if (cols.length) csv.push(cols.join(','));
        });
        const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.href = url;
        link.download = 'Farmer_Sales_History.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
        showNotification('History exported successfully!', 'success');
    } catch (e) {
        console.error('exportHistory error:', e);
        showNotification('Failed to export history', 'danger');
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
</script>
@endpush

