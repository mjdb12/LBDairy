@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers - Clients')
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
#dataTable th,
#dataTable td,
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
#dataTable thead th,
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
#dataTable thead th.sorting,
#dataTable thead th.sorting_asc,
#dataTable thead th.sorting_desc,
#activeFarmersTable thead th.sorting,
#activeFarmersTable thead th.sorting_asc,
#activeFarmersTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#dataTable thead th::after,
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
#dataTable thead th.sorting::after,
#dataTable thead th.sorting_asc::after,
#dataTable thead th.sorting_desc::after,
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

     #addClientModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #addClientModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #addClientModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #addClientModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #addClientModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #addClientModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #addClientModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #addClientModal .form-group label {
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
</style>
@section('content')
<!-- Page Header -->
<div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
    <h1>
        <i class="fas fa-users"></i>
        Clients Management
    </h1>
    <p>Manage your client relationships and track sales performance</p>
</div>

<!-- Statistics Grid -->
    <div class="row fade-in">
        <!-- Total Livestock -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Clients</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalClients }}</div>
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
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Active Clients</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeClients }}</div>
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
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Monthly Revenue</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($monthlyRevenue) }}</div>
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
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">New This Month</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $newThisMonth }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-tint fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- 1. Client Directory -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 fade-in">
            <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
                <h6 class="m-0 font-weight-bold">Client Directory</h6>
            </div>
            <div class="card-body">
                <!-- Search + Actions controls -->
                <div class="search-controls mb-3">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch">
                        <div class="input-group" style="max-width: 380px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" id="clientSearch" class="form-control" placeholder="Search clients...">
                        </div>
                        <div class="btn-group d-flex gap-2 align-items-center mt-2 mt-sm-0">
                            <button class="btn-action btn-action-ok" data-toggle="modal" data-target="#addClientModal">
                            <i class="fas fa-plus mr-1"></i> Add Client
                            </button>
                            <button class="btn-action btn-action-edits" onclick="printClientsTable()">
                                <i class="fas fa-print"></i> Print
                            </button>
                            <button class="btn-action btn-action-refresh" onclick="refreshClientsTable()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                            <div class="dropdown">
                                <button class="btn-action btn-action-tools" type="button" data-toggle="dropdown">
                                    <i class="fas fa-tools"></i> Tools
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#" onclick="exportClientsCSV()">
                                        <i class="fas fa-file-csv"></i> Download CSV
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="exportClientsPNG()">
                                        <i class="fas fa-image"></i> Download PNG
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="exportClientsPDF()">
                                        <i class="fas fa-file-pdf"></i> Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Contact</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Total Orders</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clientsData as $client)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img class="img-profile rounded-circle mr-3" src="{{ asset('img/ronaldo.png') }}" width="40">
                                        <div>
                                            <div class="font-weight-bold">{{ $client['name'] }}</div>
                                            <small class="text-muted">{{ $client['type_label'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $client['phone'] ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $client['email'] ?? 'N/A' }}</small>
                                </td>
                                <td><span class="badge {{ $client['type_badge'] }}">{{ $client['type_label'] }}</span></td>
                                <td><span class="badge {{ $client['status_badge'] }}">{{ $client['status_label'] }}</span></td>
                                <td>{{ $client['total_orders'] }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn-action btn-action-ok" onclick="viewClient('{{ $client['name'] }}')" title="View Details">
                                            <i class="fas fa-eye"></i>
                                            <span>View</span>
                                        </button>
                                        <button class="btn-action btn-action-edits" onclick="editClient('{{ $client['name'] }}')" title="Edit">
                                            <i class="fas fa-edit"></i>
                                            <span>Edit</span>
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
                                <td class="text-center text-muted">No clients found</td>
                                <td class="text-center text-muted">N/A</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. Client Distribution -->
<div class="row">
  <div class="col-12">
    <div class="card shadow mb-4 fade-in">
      <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
        <h6 class="mb-0">
          <i class="fas fa-chart-pie mr-2"></i>
          Client Distribution
        </h6>
      </div>

      <div class="card-body">
        <div class="row align-items-center">
          <!-- Chart Section -->
          <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="chart-pie pt-3 pb-2">
              <canvas id="clientDistributionChart"></canvas>
            </div>
          </div>

          <!-- Legend Section -->
          <div class="col-lg-4">
            <div class="text-center small mt-2">
              <div class="mb-3">
                <span class="mr-2">
                  <i class="fas fa-circle text-success"></i> Retail
                </span>
                <div class="font-weight-bold text-success">
                  {{ $clientDistribution['retail'] ?? 0 }} clients
                </div>
              </div>

              <div class="mb-3">
                <span class="mr-2">
                  <i class="fas fa-circle text-info"></i> Wholesale
                </span>
                <div class="font-weight-bold text-info">
                  {{ $clientDistribution['wholesale'] ?? 0 }} clients
                </div>
              </div>

              <div class="mb-3">
                <span class="mr-2">
                  <i class="fas fa-circle text-warning"></i> Business
                </span>
                <div class="font-weight-bold text-warning">
                  {{ $clientDistribution['business'] ?? 0 }} clients
                </div>
              </div>

              <div class="mb-3">
                <span class="mr-2">
                  <i class="fas fa-circle text-secondary"></i> Market
                </span>
                <div class="font-weight-bold text-secondary">
                  {{ $clientDistribution['market'] ?? 0 }} clients
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- 3. Top Clients -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 fade-in">
            <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-trophy"></i>
                    Top Clients
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($topClients as $client)
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="card border-left-primary h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <img class="img-profile rounded-circle mr-3" src="{{ asset('img/ronaldo.png') }}" width="50">
                                    <div class="flex-grow-1">
                                        <div class="font-weight-bold ">{{ $client['name'] }}</div>
                                        <small class="text-muted">{{ $client['type'] }}</small>
                                        <div class="mt-2">
                                            <span class="badge badge-primary badge-pill">₱{{ number_format($client['total_spent']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-trophy fa-3x mb-3 text-muted"></i>
                            <p>No top clients data available yet.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ADD CLIENT MODAL -->
<div class="modal fade admin-modal" id="addClientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content smart-form text-center p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-user-plus fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">Add New Client</h5>
                <p class="text-muted mb-0 small">
                    Fill out the details below to register a new client.
                </p>
            </div>

            <!-- Form -->
            <form id="addClientForm" onsubmit="submitClient(event)">
                @csrf
                <div class="form-wrapper text-start mx-auto">
                    <div class="row g-3">

                        <!-- Full Name -->
                        <div class="col-md-6">
                            <label for="clientName" class="fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="clientName" name="client_name" required>
                        </div>

                        <!-- Client Type -->
                        <div class="col-md-6">
                            <label for="clientType" class="fw-semibold">Client Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="clientType" name="client_type" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="retail">Retail</option>
                                <option value="wholesale">Wholesale</option>
                                <option value="business">Business</option>
                                <option value="market">Market</option>
                            </select>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <label for="clientPhone" class="fw-semibold">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="clientPhone" name="client_phone" required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="clientEmail" class="fw-semibold">Email Address</label>
                            <input type="email" class="form-control" id="clientEmail" name="client_email">
                        </div>

                        <!-- Address -->
                        <div class="col-md-6">
                            <label for="clientAddress" class="fw-semibold">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control mt-1" id="clientAddress" name="client_address" rows="3" placeholder="Enter full address..." style="resize: none;"></textarea>
                        </div>
                        
                        <!-- Status -->
                        <div class="col-md-6">
                            <label for="clientStatus" class="fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-control" id="clientStatus" name="client_status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="col-md-12">
                            <label for="clientNotes" class="fw-semibold">Notes</label>
                            <textarea class="form-control mt-1" id="clientNotes" name="client_notes" rows="3" placeholder="Additional client information..." style="resize: none;"></textarea>
                        </div>



                        <div id="formNotification" class="mt-2" style="display: none;"></div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="modal-footer d-flex gap-2 justify-content-center flex-wrap mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-ok" title="Save Client">
                        Save Client
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- DataTables + Buttons -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<!-- PDF/PNG helpers -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let clientsDT = null;
document.addEventListener('DOMContentLoaded', function() {
    // Client Distribution Chart
    const clientCtx = document.getElementById('clientDistributionChart').getContext('2d');
    window.clientChart = new Chart(clientCtx, {
        type: 'doughnut',
        data: {
            labels: ['Retail', 'Wholesale', 'Business', 'Market'],
            datasets: [{
                data: [
                    {{ $clientDistribution['retail'] ?? 0 }},
                    {{ $clientDistribution['wholesale'] ?? 0 }},
                    {{ $clientDistribution['business'] ?? 0 }},
                    {{ $clientDistribution['market'] ?? 0 }}
                ],
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

    // Initialize DataTable for Client Directory
    const commonConfig = {
        dom: 'Bfrtip',
        searching: true,
        paging: true,
        info: true,
        ordering: true,
        lengthChange: false,
        pageLength: 10,
        buttons: [
            { extend: 'csvHtml5', title: 'Farmer_Clients_Report', className: 'd-none' },
            { extend: 'pdfHtml5', title: 'Farmer_Clients_Report', orientation: 'landscape', pageSize: 'Letter', className: 'd-none' },
            { extend: 'print', title: 'Farmer Clients Report', className: 'd-none' }
        ],
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No data available</h5><p>There are no records to display at this time.</p></div>'
        }
    };

    if ($('#dataTable').length) {
        try {
            clientsDT = $('#dataTable').DataTable({
                ...commonConfig,
                order: [[0, 'asc']],
                columnDefs: [
                    { width: '260px', targets: 0 }, // Client Name
                    { width: '180px', targets: 1 }, // Contact
                    { width: '120px', targets: 2 }, // Type
                    { width: '120px', targets: 3 }, // Status
                    { width: '140px', targets: 4 }, // Total Orders
                    { width: '180px', targets: 5, orderable: false } // Action
                ]
            });
        } catch (e) {
            console.error('Failed to initialize Client Directory table:', e);
        }
    }

    // Hide default DataTables search and buttons; use custom controls
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();

    // Wire custom search
    $('#clientSearch').on('keyup', function(){
        if (clientsDT) clientsDT.search(this.value).draw();
    });

    // Form submission
    document.getElementById('addClientForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const name = document.getElementById('clientName').value.trim();
        const type = document.getElementById('clientType').value;
        const phone = document.getElementById('clientPhone').value.trim();
        const email = document.getElementById('clientEmail').value.trim();
        const address = document.getElementById('clientAddress').value.trim();
        const notes = document.getElementById('clientNotes').value.trim();
        const status = document.getElementById('clientStatus').value;

        if (!name || !type || !phone) {
            showNotification('Please fill in the required fields.', 'error');
            return;
        }

        // Map labels and badges
        const typeLabelMap = { retail: 'Retail', wholesale: 'Wholesale', business: 'Business', market: 'Market' };
        const typeBadgeMap = { retail: 'badge badge-success', wholesale: 'badge badge-info', business: 'badge badge-warning', market: 'badge badge-secondary' };
        const statusLabelMap = { active: 'Active', inactive: 'Inactive', pending: 'Pending' };
        const statusBadgeMap = { active: 'badge badge-success', inactive: 'badge badge-secondary', pending: 'badge badge-warning' };

        const nameCell = `
            <div class="d-flex align-items-center">
                <img class="img-profile rounded-circle mr-3" src="{{ asset('img/ronaldo.png') }}" width="40">
                <div>
                    <div class="font-weight-bold">${name}</div>
                    <small class="text-muted">${typeLabelMap[type] || 'N/A'}</small>
                </div>
            </div>`;
        const contactCell = `<div>${phone || 'N/A'}</div><small class="text-muted">${email || 'N/A'}</small>`;
        const typeCell = `<span class="${typeBadgeMap[type] || 'badge badge-secondary'}">${typeLabelMap[type] || 'N/A'}</span>`;
        const statusCell = `<span class="${statusBadgeMap[status] || 'badge badge-secondary'}">${statusLabelMap[status] || 'N/A'}</span>`;
        const totalOrdersCell = `0`;
        const actionCell = `
            <button class="btn btn-sm btn-outline-primary" onclick="viewClient('${name.replace(/'/g, "&#39;")}')">View</button>
            <button class="btn btn-sm btn-outline-info" onclick="editClient('${name.replace(/'/g, "&#39;")}')">Edit</button>`;

        if (clientsDT) {
            clientsDT.row.add([nameCell, contactCell, typeCell, statusCell, totalOrdersCell, actionCell]).draw(false);
        }

        // Update Chart counts
        try {
            const idxMap = { retail: 0, wholesale: 1, business: 2, market: 3 };
            const idx = idxMap[type];
            if (typeof idx !== 'undefined' && window.clientChart) {
                window.clientChart.data.datasets[0].data[idx] = (window.clientChart.data.datasets[0].data[idx] || 0) + 1;
                window.clientChart.update();
            }
        } catch (err) { console.warn('Chart update failed:', err); }

        showNotification('Client added successfully!', 'success');
        $('#addClientModal').modal('hide');
        this.reset();
    });
});

// Print using DataTables button
function printClientsTable(){
    try { if (clientsDT) clientsDT.button('.buttons-print').trigger(); else window.print(); }
    catch(e){ console.error('printClientsTable error:', e); window.print(); }
}

function refreshClientsTable(){
    const btn = document.querySelector('.btn-action-refresh');
    if (btn){ btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...'; }
    sessionStorage.setItem('showRefreshNotificationClients','true');
    setTimeout(()=>location.reload(), 800);
}

// After reload, show notification
$(document).ready(function(){
    if (sessionStorage.getItem('showRefreshNotificationClients') === 'true'){
        sessionStorage.removeItem('showRefreshNotificationClients');
        setTimeout(()=>showNotification('Client data refreshed successfully!','success'), 400);
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

function exportClientsCSV(){
    if (!clientsDT) return showNotification('Table not ready', 'error');
    const rows = clientsDT.data().toArray();
    const headers = ['Client Name','Contact','Type','Status','Total Orders'];
    const csv = [headers.join(',')];
    rows.forEach(r=>{
        const arr = [];
        for (let i=0;i<r.length-1;i++){
            let t=''; const tmp=document.createElement('div'); tmp.innerHTML=r[i]; t=tmp.textContent||tmp.innerText||''; t=t.replace(/\s+/g,' ').trim(); if (t.includes(',')||t.includes('"')||t.includes('\n')) t='"'+t.replace(/"/g,'""')+'"'; arr.push(t);
        }
        csv.push(arr.join(','));
    });
    const blob=new Blob([csv.join('\n')],{type:'text/csv;charset=utf-8;'});
    const a=document.createElement('a'); a.href=URL.createObjectURL(blob); a.download=`Farmer_ClientsReport_${Date.now()}.csv`; a.click();
}

function exportClientsPDF(){
    try{
        if (!clientsDT) return showNotification('Table not ready','error');
        const rows = clientsDT.data().toArray();
        const data = rows.map(r=>[r[0]||'',r[1]||'',r[2]||'',r[3]||'',r[4]||'']);
        const { jsPDF } = window.jspdf; const doc = new jsPDF('landscape','mm','a4');
        doc.setFontSize(18); doc.text('Farmer Clients Report',14,22);
        doc.setFontSize(12); doc.text(`Generated on: ${new Date().toLocaleDateString()}`,14,32);
        doc.autoTable({ head: [['Client Name','Contact','Type','Status','Total Orders']], body: data, startY: 40, styles:{fontSize:8, cellPadding:2}, headStyles:{ fillColor:[24,55,93], textColor:255, fontStyle:'bold' }, alternateRowStyles:{ fillColor:[245,245,245] } });
        doc.save(`Farmer_ClientsReport_${Date.now()}.pdf`);
    }catch(e){ console.error('PDF export error:', e); showNotification('Error generating PDF','error'); }
}

function exportClientsPNG(){
    const tbl = document.getElementById('dataTable'); if (!tbl) return;
    const clone = tbl.cloneNode(true);
    const headRow = clone.querySelector('thead tr'); if (headRow) headRow.lastElementChild && headRow.lastElementChild.remove();
    clone.querySelectorAll('tbody tr').forEach(tr=>{ tr.lastElementChild && tr.lastElementChild.remove(); });
    clone.style.position='absolute'; clone.style.left='-9999px'; document.body.appendChild(clone);
    html2canvas(clone,{scale:2, backgroundColor:'#ffffff', width:clone.offsetWidth, height:clone.offsetHeight}).then(canvas=>{
        const a=document.createElement('a'); a.download=`Farmer_ClientsReport_${Date.now()}.png`; a.href=canvas.toDataURL('image/png'); a.click(); document.body.removeChild(clone);
    }).catch(err=>{ console.error('PNG export error:', err); document.body.contains(clone)&&document.body.removeChild(clone); showNotification('Error generating PNG','error'); });
}

function showNotification(message, type){
    const t = document.createElement('div');
    t.className = `alert alert-${type==='error'?'danger':type} alert-dismissible fade show position-fixed`;
    t.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    t.innerHTML = `${message}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>`;
    document.body.appendChild(t); setTimeout(()=>t.remove(), 5000);
}

function viewClient(name){ showNotification(`View client: ${name}`,'info'); }
function editClient(name){ showNotification(`Edit client: ${name}`,'info'); }
</script>
@endpush
