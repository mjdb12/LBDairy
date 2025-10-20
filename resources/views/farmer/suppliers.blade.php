@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers-Suppliers')
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
#addSupplierLedgerEntryModal form {
  text-align: left;
}

#addSupplierLedgerEntryModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#addSupplierLedgerEntryModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#addSupplierLedgerEntryModal .form-control,
#addSupplierLedgerEntryModal select.form-control,
#addSupplierLedgerEntryModal textarea.form-control {
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
#addSupplierLedgerEntryModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#addSupplierLedgerEntryModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}


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
#supplierLaddLivestockDetailsModaledgerEntryForm textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#addLivestockDetailsModal .form-control:focus {
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

  #supplierLedgerEntryForm .form-control {
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
    max-height: 120vh; /* taller for longer content */
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
        max-height: 105vh;
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
        max-height: 120vh;
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


/* Footer */
#historyModal .modal-footer {
    text-align: center;
    border-top: 1px solid #e5e7eb;
    padding-top: 1.25rem;
    margin-top: 1.5rem;
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
#addClientModal form {
  text-align: left;
}

#addClientModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#addClientModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#addClientModal .form-control,
#addClientModal select.form-control,
#addClientModal textarea.form-control {
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
#addClientModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#addClientModal .form-control:focus {
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

  #addClientModal .form-control {
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
/* Apply consistent styling for Pending Farmers and Active Farmers tables */
#suppliersTable th,
#suppliersTable td,
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
#suppliersTable thead th,
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
#suppliersTable thead th.sorting,
#suppliersTable thead th.sorting_asc,
#suppliersTable thead th.sorting_desc,
#activeFarmersTable thead th.sorting,
#activeFarmersTable thead th.sorting_asc,
#activeFarmersTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#suppliersTable thead th::after,
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
#suppliersTable thead th.sorting::after,
#suppliersTable thead th.sorting_asc::after,
#suppliersTable thead th.sorting_desc::after,
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
#suppliersTable td, 
#suppliersTable th {
    white-space: normal !important;  /* allow wrapping */
    vertical-align: middle;
}

/* Make sure action buttons don’t overflow */
#suppliersTable td .btn-group {
    display: flex;
    flex-wrap: wrap; /* buttons wrap if not enough space */
    gap: 0.25rem;    /* small gap between buttons */
}

#suppliersTable td .btn-action {
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
    .client-info-card {
    background: #ffffff;
    border-radius: 18px;
    transition: all 0.3s ease;
}

.client-info-card:hover {
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.icon-circle-sm {
    width: 45px;
    height: 45px;
    background-color: #f1f5f9;
    border-radius: 50%;
}

.btn-modern.btn-ok.btn-sm {
    font-size: 0.85rem;
    padding: 0.35rem 0.75rem;
}
/* User Details Modal Styling */
    #supplierDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #supplierDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #supplierDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #supplierDetailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #supplierDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #supplierDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #supplierDetailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
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
.client-info-card.hover-shadow {
    transition: all 0.3s ease;
    cursor: pointer;
}
.client-info-card.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    transform: translateY(-2px);
}
.icon-circle-sm {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    font-size: 1.1rem;
    margin-right: 1.1rem;
}
</style>
@endpush
@section('content')
<!-- Page Header -->
<div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
    <h1>
        <i class="fas fa-truck"></i>
        Suppliers Management
    </h1>
    <p>Manage your farm suppliers, track purchases, and maintain supplier ledgers</p>
</div>
<!-- Statistics Grid -->
    <div class="row fade-in">
        <!-- Total Livestock -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Suppliers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSuppliers }}</div>
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
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Active Suppliers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeSuppliers }}</div>
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
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Spent</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($totalSpent) }}</div>
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
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Pending Payments</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($pendingPayments) }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-tint fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


        <div class="card shadow">
            <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-list mr-2"></i> Suppliers List
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
                    <input type="text" id="supplierSearch" class="form-control" placeholder="Search suppliers...">
                </div>
                <div class="d-flex align-items-center justify-content-center flex-nowrap gap-2 action-toolbar">
                    <button class="btn-action btn-action-ok" id="addSupplierBtn" title="Add Supplier" data-toggle="modal" data-target="#addLivestockDetailsModal">
                        <i class="fas fa-plus"></i> Add Supplier
                    </button>
                    <button class="btn-action btn-action-refresh" title="Refresh" onclick="refreshSuppliersTable('suppliersTable')">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" title="Tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="printSuppliersTable('suppliersTable')">
                                <i class="fas fa-print"></i> Print Table
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
            </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="suppliersTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>Supplier ID</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="suppliersTableBody">
                            @forelse($suppliersData as $supplier)
                            <tr data-expense-type="{{ $supplier['expense_type'] }}">
                                <td>{{ $supplier['supplier_id'] }}</td>
                                <td>{{ $supplier['name'] }}</td>
                                <td>{{ $supplier['address'] }}</td>
                                <td>{{ $supplier['contact'] }}</td>
                                <td><span class="status-badge badge badge-success">Active</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn-action btn-action-ok" onclick="viewDetails('{{ $supplier['name'] }}')" title="View Details">
                                            <i class="fas fa-eye"></i>
                                            <span>View</span>
                                        </button>
                                        <button class="btn-action btn-action-edit" onclick="viewLedger('{{ $supplier['name'] }}')" title="View Ledger">
                                            <i class="fas fa-book"></i>
                                            <span>Ledger</span>
                                        </button>
                                        <button class="btn-action btn-action-deletes" onclick="confirmDelete('{{ $supplier['name'] }}','{{ $supplier['expense_type'] }}')" title="Delete">
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
                                <td class="text-center text-muted">No suppliers found</td>
                                <td class="text-center text-muted">N/A</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

<!-- SUPPLIER LEDGER MODAL -->
<div class="modal fade admin-modal" id="supplierLedgerModal" tabindex="-1" role="dialog" aria-labelledby="supplierLedgerLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

            <!-- Header -->
            <div class="d-flex flex-column align-items-center mb-4 text-center">
                <div class="icon-circle mb-2">
                    <i class="fas fa-book fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">Supplier Ledger</h5>
                <p class="text-muted small mb-0">
                    View, manage, and record all supplier transactions and balances.
                </p>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div class="form-wrapper">

                    <!-- Supplier Info Card -->
                    <div class="client-info-card smart-detail p-3 mb-4 rounded-3 shadow-sm hover-shadow">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                            
                            <!-- Supplier Info -->
                            <div class="d-flex align-items-start align-items-md-center gap-7 flex-grow-1">
                        <div class="icon-circle-sm d-flex align-items-center justify-content-center bg-success text-white me-3">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="flex-grow-1 text-start text-md-left">
                            <h6 class="fw-bold mb-1" id="ledgerSupplierName">Supplier Name</h6>
                            <div class="row small mb-0">
                                <div class="col-md-4 col-sm-6 mb-1 mb-md-0">
                                    <span class="text-muted d-block">Supplier ID:</span>
                                    <span id="supplierInfoId" class="fw-medium text-dark">SP001</span>
                                </div>
                                <div class="col-md-8 col-sm-6">
                                    <span class="text-muted d-block">Address:</span>
                                    <span id="supplierInfoAddress" class="fw-medium text-dark">Supplier Address</span>
                                </div>
                            </div>
                        </div>
                    </div>


        <!-- Add Entry Button -->
        <button class="btn-action btn-action-ok btn-sm mt-2 mt-md-0" data-toggle="modal" data-target="#addSupplierLedgerEntryModal">
            <i class="fas fa-plus me-2"></i> Add Entry
        </button>
    </div>
</div>

                    <!-- Ledger Table -->
                    <div class="table-responsive mb-4">
                        <table class="table table-hover table-bordered align-middle" id="supplierLedgerTable" width="100%">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Payable (₱)</th>
                                    <th>Paid (₱)</th>
                                    <th>Due (₱)</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Ledger entries dynamically populated -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer justify-content-center mt-3">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ADD SUPPLIER LEDGER ENTRY MODAL -->
<div class="modal fade admin-modal" id="addSupplierLedgerEntryModal" tabindex="-1" role="dialog" aria-labelledby="addSupplierLedgerEntryLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content smart-form p-4">

            <!-- Header -->
            <div class="d-flex flex-column align-items-center mb-4 text-center">
                <div class="icon-circle mb-2">
                    <i class="fas fa-plus-circle fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">Add New Ledger Entry</h5>
                <p class="text-muted small mb-0">
                    Fill out the form below to record a new supplier transaction.
                </p>
            </div>

            <!-- Body -->
            <form id="addLedgerEntryFormInner" onsubmit="supplierLedgerEntryForm(event)">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="purchaseDate" class="fw-semibold">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control mt-1" id="purchaseDate" required>
                    </div>

                    <div class="col-md-6">
                        <label for="purchaseType" class="fw-semibold">Type <span class="text-danger">*</span></label>
                        <select class="form-control mt-1" id="purchaseType" required>
                            <option value="" disabled selected>Select Type</option>
                            <option value="Feed">Feed</option>
                            <option value="Medicine">Medicine</option>
                            <option value="Equipment">Equipment</option>
                            <option value="Livestock">Livestock</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="payableAmount" class="fw-semibold">Payable (₱)</label>
                        <input type="number" class="form-control mt-1" id="payableAmount" min="0" required>
                    </div>

                    <div class="col-md-6">
                        <label for="paidAmount" class="fw-semibold">Paid (₱)</label>
                        <input type="number" class="form-control mt-1" id="paidAmount" min="0" required>
                    </div>

                    <div class="col-12">
                        <label for="paymentStatus" class="fw-semibold">Payment Status</label>
                        <select class="form-control mt-1" id="paymentStatus" required>
                            <option value="Unpaid">Unpaid</option>
                            <option value="Partial">Partial</option>
                            <option value="Paid">Paid</option>
                        </select>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn-modern btn-ok">
                        <i class="fas fa-save me-1"></i> Save Entry
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content smart-modal text-center p-4">

            <!-- Icon -->
            <div class="icon-wrapper mx-auto mb-4 text-danger">
                <i class="fas fa-times-circle fa-2x"></i>
            </div>

            <!-- Title -->
            <h5 id="confirmDeleteTitle">Confirm Delete</h5>

            <!-- Description -->
            <p id="confirmDeleteDesc" class="text-muted mb-4 px-3">
                Are you sure you want to delete this entry? This action <strong>cannot be undone</strong>.
            </p>

            <!-- Buttons -->
            <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn-modern btn-delete" id="confirmDeleteBtn"><i class="fas fa-trash"></i> Yes, Delete</button>
            </div>

        </div>
    </div>
</div>
<!-- History Modal (Smart Detail) -->
<div class="modal fade admin-modal" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-history fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1" id="historyModalLabel">Supplier Purchase History</h5>
                <p class="text-muted mb-0 small text-center">Review, filter, and export supplier purchase history below.</p>
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
                                <option value="feed">Feed</option>
                                <option value="medicine">Medicine</option>
                                <option value="equipment">Equipment</option>
                                <option value="livestock">Livestock</option>
                            </select>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive rounded shadow-sm">
                        <table class="table table-hover table-bordered align-middle mb-0">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Date</th>
                                    <th>Supplier ID</th>
                                    <th>Type</th>
                                    <th>Amount Payable (₱)</th>
                                </tr>
                            </thead>
                            <tbody id="historyTableBody">
                                <!-- Supplier history will be dynamically populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
                <button type="button" class="btn-modern btn-ok" onclick="exportHistory()">Export History</button>
            </div>
        </div>
    </div>
</div>

<!-- ADD SUPPLIER MODAL -->
<div class="modal fade admin-modal" id="addLivestockDetailsModal" tabindex="-1" role="dialog" aria-labelledby="addLivestockDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content smart-form text-center p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-user-plus fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">Add New Supplier</h5>
                <p class="text-muted mb-0 small">
                    Fill out the details below to register a new supplier.
                </p>
            </div>

            <!-- Form -->
            <form id="addLivestockDetailsForm">
                <div class="form-wrapper text-start mx-auto">
                        <!-- Supplier ID -->
                        <div class="col-md-12">
                            <label for="add_supplierId" class="fw-semibold">Supplier ID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_supplierId" name="supplierId" required>
                        </div>

                        <!-- Name -->
                        <div class="col-md-12">
                            <label for="add_supplierName" class="fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_supplierName" name="supplierName" required>
                        </div>

                        <!-- Address -->
                        <div class="col-md-12">
                            <label for="add_supplierAddress" class="fw-semibold">Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_supplierAddress" name="supplierAddress" required>
                        </div>

                        <!-- Contact Number -->
                        <div class="col-md-12">
                            <label for="add_supplierContact" class="fw-semibold">Contact Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_supplierContact" name="supplierContact" required>
                        </div>

                        <div id="formNotification" class="mt-2" style="display: none;"></div>
                
                </div>

                <!-- Footer Buttons -->
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveSupplierBtn" class="btn-modern btn-ok" title="Save Supplier">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Supplier Details Modal (Smart Detail) -->
<div class="modal fade admin-modal" id="supplierDetailsModal" tabindex="-1" role="dialog" aria-labelledby="supplierDetailsLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content smart-detail p-4">

      <!-- Icon + Header -->
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle">
          <i class="fas fa-info-circle fa-2x "></i>
        </div>
        <h5 class="fw-bold mb-1">Supplier Details</h5>
        <p class="text-muted mb-0 small text-center">Below are the complete details of the selected supplier.</p>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div id="supplierDetailsContainer" >
          <!-- Dynamic supplier details will be injected here -->
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer justify-content-center mt-4">
        <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
let suppliersDT = null;
const CSRF_TOKEN = "{{ csrf_token() }}";
// In-memory store for supplier ledger entries keyed by supplier name
const supplierLedgers = {};
// Initialize DataTable
$(document).ready(function() {
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
            { extend: 'csvHtml5', title: 'Farmer_Suppliers_Report', className: 'd-none', exportOptions: { columns: [0,1,2,3,4], modifier: { page: 'all' } } },
            { extend: 'pdfHtml5', title: 'Farmer_Suppliers_Report', orientation: 'landscape', pageSize: 'Letter', className: 'd-none', exportOptions: { columns: [0,1,2,3,4], modifier: { page: 'all' } } },
            { extend: 'print', title: 'Farmer Suppliers Report', className: 'd-none', exportOptions: { columns: [0,1,2,3,4], modifier: { page: 'all' } } }
        ],
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No data available</h5><p>There are no records to display at this time.</p></div>'
        }
    };

    if ($('#suppliersTable').length) {
        try {
            suppliersDT = $('#suppliersTable').DataTable({
                ...commonConfig,
                order: [[0, 'asc']],
                columnDefs: [
                    { width: '140px', targets: 0 }, // Supplier ID
                    { width: '200px', targets: 1 }, // Name
                    { width: '240px', targets: 2 }, // Address
                    { width: '160px', targets: 3 }, // Contact
                    { width: '120px', targets: 4 }, // Status
                    { width: '220px', targets: 5, orderable: false }, // Actions
                    { targets: '_all', className: 'text-center align-middle' }
                ]
            });
            suppliersDT.columns.adjust();
        } catch (e) {
            console.error('Failed to initialize suppliers DataTable:', e);
        }
    }

    // Hide default DataTables search and buttons
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();

    // Wire up custom search
    $('#supplierSearch').on('keyup', function(){
        if (suppliersDT) suppliersDT.search(this.value).draw();
    });
});
function hideAddSupplierLedgerEntryForm() {
    const c = document.getElementById('supplierLedgerEntryForm');
    if (c) c.style.display = 'none';
}
// To sh

// Add Supplier form submission -> add a new row to the DataTable
$(document).ready(function(){
    // Ensure Save button reliably triggers the form submit
    $('#saveSupplierBtn').on('click', function(e){
        e.preventDefault();
        $('#addLivestockDetailsForm').trigger('submit');
    });

    $('#addLivestockDetailsForm').on('submit', function(e){
        e.preventDefault();
        const id = $('#add_supplierId').val().trim();
        const name = $('#add_supplierName').val().trim();
        const address = $('#add_supplierAddress').val().trim();
        const contact = $('#add_supplierContact').val().trim();

        if (!id || !name || !address || !contact){
            showNotification('Please fill in all required fields.', 'error');
            return;
        }

        const esc = s => s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        const safeId = esc(id);
        const safeName = esc(name);
        const safeAddress = esc(address);
        const safeContact = esc(contact);

        const statusCell = '<span class="status-badge badge badge-success">Active</span>';
        const actionCell = `
            <div class="btn-group">
                <button class="btn-action btn-action-ok" onclick="viewDetails('{{ $supplier['name'] }}')" title="View Details">
                    <i class="fas fa-eye"></i>
                    <span>View</span>
                </button>
                <button class="btn-action btn-action-edit" onclick="viewLedger('{{ $supplier['name'] }}')" title="View Ledger">
                    <i class="fas fa-book"></i>
                    <span>Ledger</span>
                </button>
                <button class="btn-action btn-action-deletes" onclick="confirmDelete('{{ $supplier['name'] }}','{{ $supplier['expense_type'] }}')" title="Delete">
                    <i class="fas fa-trash"></i>
                    <span>Delete</span>
                </button>
            </div>`;

        if (suppliersDT){
            // Remove placeholder row if present
            suppliersDT.rows().every(function(){
                const d = this.data();
                if (Array.isArray(d) && d[4] && (d[4]+"").toLowerCase().includes('no suppliers')){
                    this.remove();
                }
            });

            const rowNode = suppliersDT
                .row.add([safeId, safeName, safeAddress, safeContact, statusCell, actionCell])
                .draw(false)
                .node();

            // Ensure it's visible: clear search and go to last page
            if (suppliersDT.search()) suppliersDT.search('');
            suppliersDT.page('last').draw('page');

            if (rowNode){
                rowNode.classList.add('table-success');
                rowNode.scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(()=> rowNode.classList.remove('table-success'), 2000);
            }
        } else {
            // Fallback if DataTables not initialized
            $('#suppliersTableBody').append(`
                <tr>
                    <td>${safeId}</td>
                    <td>${safeName}</td>
                    <td>${safeAddress}</td>
                    <td>${safeContact}</td>
                    <td>${statusCell}</td>
                    <td>${actionCell}</td>
                </tr>`);
            const last = $('#suppliersTableBody tr:last')[0];
            if (last){
                last.classList.add('table-success');
                last.scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(()=> last.classList.remove('table-success'), 2000);
            }
        }

        // Close modal, reset form, and notify
        $('#addLivestockDetailsModal').modal('hide');
        this.reset();
        showNotification('Supplier added successfully!', 'success');
    });
});

// View Ledger function
function viewLedger(supplierName) {
    document.getElementById('ledgerSupplierName').textContent = supplierName;
    const tr = getSupplierRowByName(supplierName);
    if (tr) {
        const tds = tr.querySelectorAll('td');
        const id = tds[0] ? (tds[0].innerText||'').trim() : '';
        const address = tds[2] ? (tds[2].innerText||'').trim() : '';
        const idEl = document.getElementById('supplierInfoId');
        const addrEl = document.getElementById('supplierInfoAddress');
        if (idEl) idEl.textContent = id || 'N/A';
        if (addrEl) addrEl.textContent = address || 'N/A';
    }
    // Set current supplier context for the ledger modal and render existing entries
    const $modal = $('#supplierLedgerModal');
    $modal.data('currentSupplier', supplierName);
    if (!supplierLedgers[supplierName]) supplierLedgers[supplierName] = [];
    renderSupplierLedgerTable(supplierName);
    // Reset and hide the Add Entry form by default
    hideAddSupplierLedgerEntryForm();
    const form = document.getElementById('addLedgerEntryFormInner');
    if (form) form.reset();
    // In a real implementation, you would fetch supplier details and ledger data from backend
    $('#supplierLedgerModal').modal('show');
}

// Render ledger entries for the current supplier into the table
function renderSupplierLedgerTable(supplierName) {
    const tbody = document.querySelector('#supplierLedgerTable tbody');
    if (!tbody) return;
    const entries = supplierLedgers[supplierName] || [];
    tbody.innerHTML = '';
    if (entries.length === 0) {
        const tr = document.createElement('tr');
        tr.innerHTML = '<td colspan="7" class="text-center text-muted">No ledger entries</td>'; 
        tbody.appendChild(tr);
        return;
    }
    entries.forEach((e, idx) => {
        const tr = document.createElement('tr');
        const badge = statusBadge(e.status);
        tr.innerHTML = `
            <td>${escapeHtml(e.date)}</td>
            <td>${escapeHtml(e.type)}</td>
            <td>${formatCurrency(e.payable)}</td>
            <td>${formatCurrency(e.paid)}</td>
            <td>${formatCurrency(e.due)}</td>
            <td>${badge}</td>
            <td class="text-center">
                <button class="btn-modern btn-delete btn-sm" onclick="deleteLedgerEntry(${idx})"><i class="fas fa-trash"></i> Delete</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function deleteLedgerEntry(index) {
    const supplierName = $('#supplierLedgerModal').data('currentSupplier');
    if (!supplierName) return;
    const entries = supplierLedgers[supplierName] || [];
    if (index >= 0 && index < entries.length) {
        entries.splice(index, 1);
        renderSupplierLedgerTable(supplierName);
        showNotification('Ledger entry deleted.', 'success');
    }
}

// Handle Add Ledger Entry form submission
$(document).on('submit', '#addLedgerEntryFormInner', function(e){
    e.preventDefault();

    const supplierName = $('#supplierLedgerModal').data('currentSupplier') || 
                         document.getElementById('ledgerSupplierName')?.textContent?.trim();

    if (!supplierName) {
        showNotification('No supplier selected.', 'error');
        return;
    }

    const dateVal = ($('#purchaseDate').val() || '').trim();
    const typeVal = ($('#purchaseType').val() || '').trim();
    const payableVal = parseFloat($('#payableAmount').val());
    const paidVal = parseFloat($('#paidAmount').val());
    const statusVal = ($('#paymentStatus').val() || '').trim();

    if (!dateVal || !typeVal || isNaN(payableVal) || isNaN(paidVal) || !statusVal) {
        showNotification('Please fill all required fields.', 'error');
        return;
    }

    const dueVal = Math.max(0, payableVal - paidVal);
    const entry = { date: dateVal, type: typeVal, payable: payableVal, paid: paidVal, due: dueVal, status: statusVal };

    if (!supplierLedgers[supplierName]) supplierLedgers[supplierName] = [];
    supplierLedgers[supplierName].push(entry);

    // Re-render ledger table
    renderSupplierLedgerTable(supplierName);

    // Hide the **new modal**
    $('#addSupplierLedgerEntryModal').modal('hide');

    // Reset the form
    this.reset();

    showNotification('Ledger entry saved.', 'success');
});


// Utilities
function formatCurrency(n){
    const num = Number(n || 0);
    return '₱' + num.toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2});
}
function statusBadge(status){
    const s = (status||'').toLowerCase();
    if (s === 'paid') return '<span class="badge badge-success">Paid</span>';
    if (s === 'partial') return '<span class="badge badge-warning">Partial</span>';
    return '<span class="badge badge-secondary">Unpaid</span>';
}
function escapeHtml(str){
    return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function viewDetails(supplier) {
    var data = {};
    if (typeof supplier === 'string') {
        const tr = getSupplierRowByName(supplier);
        const tds = tr ? tr.querySelectorAll('td') : [];
        data.id = tds[0] ? (tds[0].innerText||'').trim() : '';
        data.name = tds[1] ? (tds[1].innerText||'').trim() : '';
        data.address = tds[2] ? (tds[2].innerText||'').trim() : '';
        data.contact = tds[3] ? (tds[3].innerText||'').trim() : '';
        data.status = tds[4] ? (tds[4].innerText||'').trim() : '';
    } else {
        data = supplier || {};
    }

    const container = document.getElementById('supplierDetailsContainer');
    if (!container) { $('#supplierDetailsModal').modal('show'); return; }
    container.innerHTML = (
        '<div class="row">'
        + '<div class="col-md-6">'
        + '<h6 class="mb-3" style="color: #18375d; font-weight: 600;"><i class="fas fa-truck mr-2"></i>Supplier Information</h6>'
        + '<p class="text-left"><strong>Supplier ID:</strong> ' + (data.id || 'N/A') + '</p>'
        + '<p class="text-left"><strong>Name:</strong> ' + (data.name || 'N/A') + '</p>'
        + '<p class="text-left"><strong>Address:</strong> ' + (data.address || 'N/A') + '</p>'
        + '<p class="text-left"><strong>Contact:</strong> ' + (data.contact || 'N/A') + '</p>'
        + '</div>'
        + '<div class="col-md-6">'
        + '<h6 class="mb-3" style="color: #18375d; font-weight: 600;"><i class="fas fa-info-circle mr-2"></i>Status</h6>'
        + '<p class="text-left"><strong>Current Status:</strong> <span class="badge badge-' + ((data.status||'').toLowerCase()==='active'?'success':'secondary') + '">' + (data.status || 'N/A') + '</span></p>'
        + '</div>'
        + '</div>'
    );
    $('#supplierDetailsModal').modal('show');
}


// Track supplier pending deletion
let supplierToDelete = null;
let supplierExpenseTypeToDelete = null;

// Confirm Delete function
function confirmDelete(supplierName, expenseType) {
    supplierToDelete = supplierName;
    supplierExpenseTypeToDelete = expenseType || null;
    const btn = document.getElementById('confirmDeleteBtn');
    if (btn) {
        btn.onclick = function(){ deleteSupplier(); };
    }
    $('#confirmDeleteModal').modal('show');
}

function getSupplierRowByName(name){
    const rows = document.querySelectorAll('#suppliersTable tbody tr');
    for (const tr of rows){
        const tds = tr.querySelectorAll('td');
        const n = tds[1] ? (tds[1].innerText||'').trim() : '';
        if (n === name) return tr;
    }
    return null;
}

function deleteSupplier(){
    try {
        const btn = document.getElementById('confirmDeleteBtn');
        if (btn){ btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...'; }
        if (!supplierToDelete) { $('#confirmDeleteModal').modal('hide'); return; }
        const tr = getSupplierRowByName(supplierToDelete);

        // If we have an expense type, also delete from backend
        if (supplierExpenseTypeToDelete) {
            fetch(`/farmer/suppliers/${encodeURIComponent(supplierExpenseTypeToDelete)}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }).then(async (r)=>{
                let data = null; try { data = await r.json(); } catch(_){}
                if (!r.ok || !data || data.success !== true){
                    throw new Error((data && data.message) || `HTTP ${r.status}`);
                }
                if (tr && suppliersDT){ suppliersDT.row(tr).remove().draw(false); }
                $('#confirmDeleteModal').modal('hide');
                showNotification(`Supplier deleted. Removed ${data.deleted_count||0} related expenses.`, 'success');
            }).catch(err=>{
                console.error('deleteSupplier backend error:', err);
                showNotification('Failed to delete supplier from server','error');
            }).finally(()=>{
                if (btn){ btn.disabled = false; btn.innerHTML = 'Yes, Delete'; }
                supplierToDelete = null; supplierExpenseTypeToDelete = null;
            });
        } else {
            // Frontend-only removal
            if (tr && suppliersDT){ suppliersDT.row(tr).remove().draw(false); }
            $('#confirmDeleteModal').modal('hide');
            showNotification('Supplier removed from the list.', 'success');
            if (btn){ btn.disabled = false; btn.innerHTML = 'Yes, Delete'; }
            supplierToDelete = null; supplierExpenseTypeToDelete = null;
        }
    } catch(e){
        console.error('deleteSupplier error:', e);
        showNotification('Failed to remove supplier','error');
        const btn = document.getElementById('confirmDeleteBtn');
        if (btn){ btn.disabled = false; btn.innerHTML = 'Yes, Delete'; }
        supplierToDelete = null; supplierExpenseTypeToDelete = null;
    }
}

// Show Add Entry Form
function showAddSupplierLedgerEntryForm() {
    document.getElementById('supplierLedgerEntryForm').style.display = 'block';
}

// Import CSV function
function importCSV(event) {
    const file = event.target.files[0];
    if (file) {
        // Handle CSV import logic here
        console.log('Importing CSV:', file.name);
    }
}

// Export History function
function exportHistory() {
    // Handle export logic here
    console.log('Exporting history...');
}

// Print suppliers table using DataTables
function printSuppliersTable(){
    try {
        const tableId = 'suppliersTable';
        const tableEl = document.getElementById(tableId);
        const dt = ($.fn.DataTable && $.fn.DataTable.isDataTable('#' + tableId)) ? $('#' + tableId).DataTable() : null;

        // Headers excluding last column (Actions)
        const headers = [];
        if (tableEl) {
            const ths = tableEl.querySelectorAll('thead th');
            ths.forEach((th, i) => { if (i < ths.length - 1) headers.push((th.innerText||'').trim()); });
        }

        // Rows excluding last column
        const rows = [];
        if (dt) {
            dt.data().toArray().forEach(r => {
                const arr = [];
                for (let i = 0; i < r.length - 1; i++) { const d = document.createElement('div'); d.innerHTML = r[i]; arr.push((d.textContent||d.innerText||'').replace(/\s+/g,' ').trim()); }
                rows.push(arr);
            });
        } else if (tableEl) {
            tableEl.querySelectorAll('tbody tr').forEach(tr => {
                const tds = tr.querySelectorAll('td'); if (!tds.length) return;
                const arr = []; for (let i = 0; i < tds.length - 1; i++) arr.push((tds[i].innerText||'').replace(/\s+/g,' ').trim());
                rows.push(arr);
            });
        }

        if (!rows.length) return;

        // Build print html
        let html = `
            <div style=\"font-family: Arial, sans-serif; margin: 20px;\">
                <div style=\"text-align: center; margin-bottom: 20px;\">
                    <h1 style=\"color:#18375d; margin-bottom:5px;\">Suppliers Report</h1>
                    <p style=\"color:#666; margin:0;\">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                <table border=\"3\" style=\"border-collapse: collapse; width:100%; border:3px solid #000;\">
                    <thead><tr>`;
        headers.forEach(h => { html += `<th style=\"border:3px solid #000; padding:10px; background:#f2f2f2; text-align:left;\">${h}</th>`; });
        html += `</tr></thead><tbody>`;
        rows.forEach(r => { html += '<tr>'; r.forEach(c => { html += `<td style=\"border:3px solid #000; padding:10px; text-align:left;\">${c}</td>`; }); html += '</tr>'; });
        html += `</tbody></table></div>`;

        if (typeof window.printElement === 'function') { const container = document.createElement('div'); container.innerHTML = html; window.printElement(container); }
        else if (typeof window.openPrintWindow === 'function') { window.openPrintWindow(html, 'Suppliers Report'); }
        else { const w = window.open('', '_blank'); if (w) { w.document.open(); w.document.write(`<html><head><title>Print</title></head><body>${html}</body></html>`); w.document.close(); w.focus(); w.print(); w.close(); } else { window.print(); } }
    } catch(e){ console.error('printSuppliersTable error:', e); try { $('#' + 'suppliersTable').DataTable().button('.buttons-print').trigger(); } catch(_){} }
}

// Refresh suppliers table
function refreshSuppliersTable(){
    const btn = document.querySelector('.btn-action-refresh');
    if (btn){ btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...'; }
    sessionStorage.setItem('showRefreshNotificationSuppliers','true');
    setTimeout(()=>location.reload(), 800);
}

// After reload, show notification
$(document).ready(function(){
    if (sessionStorage.getItem('showRefreshNotificationSuppliers') === 'true'){
        sessionStorage.removeItem('showRefreshNotificationSuppliers');
        setTimeout(()=>showNotification('Suppliers data refreshed successfully!','success'), 400);
    }
});

// CSV export (exclude Actions column)
function exportCSV(){
    try {
        if (suppliersDT) { suppliersDT.button('.buttons-csv').trigger(); }
        else { showNotification('Table not ready','error'); }
    } catch(e) { console.error('CSV export error:', e); showNotification('Error generating CSV','error'); }
}

// PDF export via jsPDF
function exportPDF(){
    try {
        if (suppliersDT) { suppliersDT.button('.buttons-pdf').trigger(); }
        else { showNotification('Table not ready','error'); }
    } catch(e) { console.error('PDF export error:', e); showNotification('Error generating PDF','error'); }
}

// PNG export via html2canvas
function exportPNG(){
    const tbl = document.getElementById('suppliersTable'); if (!tbl) return;
    const clone = tbl.cloneNode(true);
    const headRow = clone.querySelector('thead tr'); if (headRow) headRow.lastElementChild && headRow.lastElementChild.remove();
    clone.querySelectorAll('tbody tr').forEach(tr=>{ tr.lastElementChild && tr.lastElementChild.remove(); });
    clone.style.position='absolute'; clone.style.left='-9999px'; document.body.appendChild(clone);
    html2canvas(clone,{scale:2, backgroundColor:'#ffffff', width:clone.offsetWidth, height:clone.offsetHeight}).then(canvas=>{
        const a=document.createElement('a'); a.download=`Farmer_SuppliersReport_${Date.now()}.png`; a.href=canvas.toDataURL('image/png'); a.click(); document.body.removeChild(clone);
    }).catch(err=>{ console.error('PNG export error:', err); document.body.contains(clone)&&document.body.removeChild(clone); showNotification('Error generating PNG','error'); });
}

function showNotification(message, type){
    const t=document.createElement('div');
    t.className = `alert alert-${type==='error'?'danger':type} alert-dismissible fade show position-fixed`;
    t.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    t.innerHTML = `${message}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>`;
    document.body.appendChild(t); setTimeout(()=>t.remove(), 5000);
}
</script>
@endpush

