@extends('layouts.app')

@section('title', 'LBDAIRY: SuperAdmin - User Management')

@push('styles')
<style>
    /* Ensure modal is displayed correctly */
    .modal {
        z-index: 9999 !important;
        display: none !important;
    }
    .modal.show {
        display: block !important;
        opacity: 1 !important;
    }
    .modal-backdrop {
        z-index: 1040 !important;
        background-color: rgba(0, 0, 0, 0.5) !important;
    }
    .modal-dialog {
        z-index: 10000 !important;
        margin: 1.75rem auto;
    }
    .modal-content {
        position: relative;
        z-index: 10001 !important;
    }
    /* Make sure the modal is visible */
    .modal.show .modal-dialog {
        transform: none !important;
    }
    /* Ensure modal is not transparent */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
        transform: translate(0, -25%);
    }
    .modal.show .modal-dialog {
        transform: translate(0, 0);
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

    /* Style all labels inside form Modal */
    #confirmDeleteModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }
    /* CRITICAL FIX FOR DROPDOWN TEXT CUTTING */
    .superadmin-modal select.form-control,
    .modal.superadmin-modal select.form-control,
    .superadmin-modal .modal-body select.form-control {
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
        height: auto;
    }
    
    /* Ensure columns don't constrain dropdowns */
    .superadmin-modal .col-md-6 {
        min-width: 280px !important;
        overflow: visible !important;
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
    
    #usersTable th,
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
        text-align: center;
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

    /* User Details Modal Styling */
    #rejectionModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #rejectionModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #rejectionModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #rejectionModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #rejectionModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #rejectionModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #000000ff !important;
    }
    
    #rejectionModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #rejectionModal .form-group label {
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
.smart-modal .form-control {
    border-radius: 0.5rem;
    border: 1px solid #ced4da;
    padding: 0.6rem 0.75rem;
    font-size: 0.9rem;
}

.smart-modal .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25);
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
  background: #eef3f8;
  color: #18375d;
  border-radius: 50%;
  width: 58px;
  height: 58px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  margin: 0 auto 10px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
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
#userModal form {
  text-align: left;
}

#userModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#userModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#userModal .form-control,
#userModal select.form-control,
#userModal textarea.form-control {
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
#userModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#userModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}

/* ============================
   FORM ELEMENT STYLES
   ============================ */
#editAdminModal form {
  text-align: left;
}

#editAdminModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#editAdminModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#editAdminModal .form-control,
#editAdminModal select.form-control,
#editAdminModal textarea.form-control {
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
#editAdminModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#editAdminModal .form-control:focus {
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

.btn-approve {
  background: #387057;
  color: #fff;
}
.btn-approve:hover {
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
#userModal .modal-footer {
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

  #userModal .form-control {
    font-size: 14px;
  }

  #editAdminModal .form-control {
    font-size: 14px;
  }

  .btn-ok,
  .btn-delete,
  .btn-approve {
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
    color: #1a73e8;
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
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
    <h1>
        <i class="fas fa-users"></i>
        User Management
    </h1>
    <p>Manage all system users, roles, and permissions</p>
</div>

    <!-- Stats Cards -->
    <div class="row fade-in">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalUsersCount">0</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users fa-2x" style="color: #18375d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Approved Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeUsersCount">0</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-check fa-2x" style="color: #18375d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Pending Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingUsersCount">0</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-clock fa-2x" style="color: #18375d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Rejected Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="suspendedUsersCount">0</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-times fa-2x" style="color: #18375d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- User Management Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-body d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start">
            <h6 class="mb-0">
                <i class="fas fa-list"></i>
                User Directory
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
                    <input type="text" class="form-control" placeholder="Search users..." id="userSearch">
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-center">
                    <button class="btn-action btn-action-edit" onclick="showAddUserModal()">
                        <i class="fas fa-user-plus"></i> Add User
                    </button>
                    <button class="btn-action btn-action-print" onclick="printTable()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button class="btn-action btn-action-refresh" onclick="refreshData()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
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
                <table class="table table-bordered table-hover" id="usersTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Registration Date</th>
                            <th title="Shows relative time (e.g., '2 hours ago')">Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <!-- Users will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Smart Form Modal -->
<div class="modal fade admin-modal" id="userModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content smart-form text-center p-4">

      <!-- Header -->
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle mb-3">
          <i class="fas fa-user-plus fa-lg"></i>
        </div>
        <h5 class="fw-bold mb-1">Add New User</h5>
        <p class="text-muted mb-0 small">
          Fill out the form below to add or update a user’s information.
        </p>
      </div>

      <!-- Form -->
      <form id="userForm" onsubmit="saveUser(event)">
        @csrf
        <input type="hidden" id="userId" name="user_id">

        <div class="form-wrapper text-start mx-auto">
          <div class="row g-3">
            <!-- First Name -->
            <div class="col-md-6">
              <label for="firstName" class="fw-semibold text-#18375d">First Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="firstName" name="first_name" placeholder="Enter first name" required>
            </div>

            <!-- Last Name -->
            <div class="col-md-6">
              <label for="lastName" class="fw-semibold">Last Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="lastName" name="last_name" placeholder="Enter last name" required>
            </div>

            <!-- Email -->
            <div class="col-md-6">
              <label for="email" class="fw-semibold">Email <span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" required>
            </div>

            <!-- Username -->
            <div class="col-md-6">
              <label for="username" class="fw-semibold">Username <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
            </div>

            <!-- Contact -->
            <div class="col-md-6">
              <label for="phone" class="fw-semibold">Contact Number</label>
              <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter contact number">
            </div>

            <!-- Barangay -->
            <div class="col-md-6">
              <label for="barangay" class="fw-semibold">Barangay</label>
              <select class="form-control" id="barangay" name="barangay">
                <option value="">Select Barangay</option>
                    <option value="Abang">Abang</option>
                    <option value="Aliliw">Aliliw</option>
                    <option value="Atulinao">Atulinao</option>
                    <option value="Ayuti (Poblacion)">Ayuti (Poblacion)</option>
                    <option value="Barangay 1 (Poblacion)">Barangay 1 (Poblacion)</option>
                    <option value="Barangay 2 (Poblacion)">Barangay 2 (Poblacion)</option>
                    <option value="Barangay 3 (Poblacion)">Barangay 3 (Poblacion)</option>
                    <option value="Barangay 4 (Poblacion)">Barangay 4 (Poblacion)</option>
                    <option value="Barangay 5 (Poblacion)">Barangay 5 (Poblacion)</option>
                    <option value="Barangay 6 (Poblacion)">Barangay 6 (Poblacion)</option>
                    <option value="Barangay 7 (Poblacion)">Barangay 7 (Poblacion)</option>
                    <option value="Barangay 8 (Poblacion)">Barangay 8 (Poblacion)</option>
                    <option value="Barangay 9 (Poblacion)">Barangay 9 (Poblacion)</option>
                    <option value="Barangay 10 (Poblacion)">Barangay 10 (Poblacion)</option>
                    <option value="Igang">Igang</option>
                    <option value="Kabatete">Kabatete</option>
                    <option value="Kakawit">Kakawit</option>
                    <option value="Kalangay">Kalangay</option>
                    <option value="Kalyaat">Kalyaat</option>
                    <option value="Kilib">Kilib</option>
                    <option value="Kulapi">Kulapi</option>
                    <option value="Mahabang Parang">Mahabang Parang</option>
                    <option value="Malupak">Malupak</option>
                    <option value="Manasa">Manasa</option>
                    <option value="May-It">May-It</option>
                    <option value="Nagsinamo">Nagsinamo</option>
                    <option value="Nalunao">Nalunao</option>
                    <option value="Palola">Palola</option>
                    <option value="Piis">Piis</option>
                    <option value="Samil">Samil</option>
                    <option value="Tiawe">Tiawe</option>
                    <option value="Tinamnan">Tinamnan</option>
                <!-- Add more -->
              </select>
            </div>

            <!-- Role -->
            <div class="col-md-6">
              <label for="userRole" class="fw-semibold">Role <span class="text-danger">*</span></label>
              <select class="form-control" id="userRole" name="role" required>
                <option value="">Select Role</option>
                <option value="farmer">Farmer</option>
                <option value="admin">Admin</option>
                <option value="superadmin">Super Admin</option>
              </select>
            </div>

            <!-- Status -->
            <div class="col-md-6">
              <label for="userStatus" class="fw-semibold">Status <span class="text-danger">*</span></label>
              <select class="form-control" id="userStatus" name="status" required>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
              </select>
            </div>

            <!-- Password -->
            <div class="col-md-6">
              <label for="password" class="fw-semibold">Password <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
              <small class="text-muted">Leave blank to keep existing password when editing.</small>
            </div>

            <!-- Confirm Password -->
            <div class="col-md-6">
              <label for="passwordConfirmation" class="fw-semibold">Confirm Password <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="passwordConfirmation" name="password_confirmation" placeholder="Confirm password">
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer justify-content-center mt-4">
          <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-modern btn-approve">Save User</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Smart Form Modal - Edit User -->
<div class="modal fade admin-modal" id="editAdminModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content smart-form text-center p-4">

      <!-- Header -->
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle mb-3">
          <i class="fas fa-user-edit fa-lg"></i>
        </div>
        <h5 class="fw-bold mb-1">Edit User</h5>
        <p class="text-muted mb-0 small">
          Modify the details below to update the selected user’s information.
        </p>
      </div>

      <!-- Form -->
      <form id="editAdminForm" onsubmit="updateAdmin(event)">
        @csrf
        <input type="hidden" id="editAdminId" name="user_id">

        <div class="form-wrapper text-start mx-auto">
          <div class="row g-3">
            
            <!-- First Name -->
            <div class="col-md-6">
              <label for="editAdminFirstName" class="fw-semibold">First Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="editAdminFirstName" name="first_name" placeholder="Enter first name" required>
            </div>

            <!-- Last Name -->
            <div class="col-md-6">
              <label for="editAdminLastName" class="fw-semibold">Last Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="editAdminLastName" name="last_name" placeholder="Enter last name" required>
            </div>

            <!-- Email -->
            <div class="col-md-6">
              <label for="editAdminEmail" class="fw-semibold">Email <span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="editAdminEmail" name="email" placeholder="Enter email address" required>
            </div>

            <!-- Username -->
            <div class="col-md-6">
              <label for="editAdminUsername" class="fw-semibold">Username <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="editAdminUsername" name="username" placeholder="Enter username" required>
            </div>

            <!-- Contact -->
            <div class="col-md-6">
              <label for="editAdminPhone" class="fw-semibold">Contact Number</label>
              <input type="text" class="form-control" id="editAdminPhone" name="phone" placeholder="Enter contact number">
            </div>

            <!-- Barangay -->
            <div class="col-md-6">
              <label for="editAdminBarangay" class="fw-semibold">Barangay <span class="text-danger">*</span></label>
              <select class="form-control" id="editAdminBarangay" name="barangay" required>
                <option value="">Select Barangay</option>
                <option value="Abang">Abang</option>
                <option value="Aliliw">Aliliw</option>
                <option value="Atulinao">Atulinao</option>
                <option value="Ayuti (Poblacion)">Ayuti (Poblacion)</option>
                <option value="Barangay 1 (Poblacion)">Barangay 1 (Poblacion)</option>
                <option value="Barangay 2 (Poblacion)">Barangay 2 (Poblacion)</option>
                <option value="Barangay 3 (Poblacion)">Barangay 3 (Poblacion)</option>
                <option value="Barangay 4 (Poblacion)">Barangay 4 (Poblacion)</option>
                <option value="Barangay 5 (Poblacion)">Barangay 5 (Poblacion)</option>
                <option value="Barangay 6 (Poblacion)">Barangay 6 (Poblacion)</option>
                <option value="Barangay 7 (Poblacion)">Barangay 7 (Poblacion)</option>
                <option value="Barangay 8 (Poblacion)">Barangay 8 (Poblacion)</option>
                <option value="Barangay 9 (Poblacion)">Barangay 9 (Poblacion)</option>
                <option value="Barangay 10 (Poblacion)">Barangay 10 (Poblacion)</option>
                <option value="Igang">Igang</option>
                <option value="Kabatete">Kabatete</option>
                <option value="Kakawit">Kakawit</option>
                <option value="Kalangay">Kalangay</option>
                <option value="Kalyaat">Kalyaat</option>
                <option value="Kilib">Kilib</option>
                <option value="Kulapi">Kulapi</option>
                <option value="Mahabang Parang">Mahabang Parang</option>
                <option value="Malupak">Malupak</option>
                <option value="Manasa">Manasa</option>
                <option value="May-It">May-It</option>
                <option value="Nagsinamo">Nagsinamo</option>
                <option value="Nalunao">Nalunao</option>
                <option value="Palola">Palola</option>
                <option value="Piis">Piis</option>
                <option value="Samil">Samil</option>
                <option value="Tiawe">Tiawe</option>
                <option value="Tinamnan">Tinamnan</option>
              </select>
            </div>

            <!-- Role -->
            <div class="col-md-6">
              <label for="editAdminRole" class="fw-semibold">Role <span class="text-danger">*</span></label>
              <select class="form-control" id="editAdminRole" name="role" required>
                <option value="">Select Role</option>
                <option value="farmer">Farmer</option>
                <option value="admin">Admin</option>
                <option value="superadmin">Super Admin</option>
              </select>
            </div>

            <!-- Status -->
            <div class="col-md-6">
              <label for="editAdminStatus" class="fw-semibold">Status <span class="text-danger">*</span></label>
              <select class="form-control" id="editAdminStatus" name="status" required>
                <option value="active">Active</option> 
                    <option value="inactive">Inactive</option> 
              </select>
            </div>

            <!-- New Password -->
            <div class="col-md-6">
              <label for="editAdminPassword" class="fw-semibold">New Password</label>
              <input type="password" class="form-control" id="editAdminPassword" name="password" placeholder="Leave blank to keep current">
              <small class="text-muted">Leave blank to retain existing password.</small>
            </div>

          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer justify-content-center mt-4">
          <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
          <button type="submit" id="updateAdminBtn" class="btn-modern btn-approve">Update User</button>
        </div>
      </form>

    </div>
  </div>
</div>


<!-- Modern Delete Task Confirmation Modal -->
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
                Are you sure you want to delete this user? This action <strong>cannot be undone</strong>.
            </p>

            <!-- Buttons -->
            <div class="modal-footer d-flex gap-2 justify-content-center flex-wrap">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="btn-modern btn-delete">
                    Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Smart Detail Modal -->
<div class="modal fade admin-modal" id="userDetailsModal" tabindex="-1" role="dialog" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle mb-3">
                    <i class="fas fa-user fa-lg"></i>
                </div>
                <h5 class="fw-bold mb-1">User Details</h5>
                <p class="text-muted mb-0 small">Below are the complete details of the selected user.</p>
            </div>

            <!-- Body -->
             <div class="modal-body">
                <div id="userDetails" class="detail-wrapper text-start">
                    <!-- Example user detail layout -->
                    <div class="detail-row">
                        <span class="detail-label">Full Name:</span>
                        <span class="detail-value" id="detailName">John Doe</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value" id="detailEmail">john@example.com</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Role:</span>
                        <span class="detail-value" id="detailRole">Admin</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value" id="detailStatus">Active</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer d-flex gap-2 justify-content-center flex-wrap mt-4">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
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

<!-- jsPDF and autoTable for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
let usersTable;
let userToDelete = null;
let downloadCounter = 1;

$(document).ready(function () {
    // Initialize DataTables
    initializeDataTables();
    
    // Load data
    loadUsers();
    updateStats();

    // Custom search functionality
    $('#userSearch').on('keyup', function() {
        usersTable.search(this.value).draw();
    });

    // Auto-refresh data every 30 seconds to ensure real-time data
    setInterval(function() {
        loadUsers();
        updateStats();
    }, 30000); // 30 seconds

    // Ensure modals are at the end of <body> to avoid stacking/z-index issues
    $('#editAdminModal, #userModal, #userDetailsModal, #confirmDeleteModal').appendTo('body');

    // Debug: confirm modals exist
    console.log('[Users] Modals present:', {
        editAdminModal: $('#editAdminModal').length,
        userModal: $('#userModal').length,
        userDetailsModal: $('#userDetailsModal').length,
        confirmDeleteModal: $('#confirmDeleteModal').length
    });
});

function initializeDataTables() {
    usersTable = $('#usersTable').DataTable({
        dom: 'Bfrtip',
        searching: true,
        paging: true,
        info: true,
        ordering: true,
        lengthChange: false,
        pageLength: 10,
        columnDefs: [
            { width: '80px', targets: 0 }, // User ID
            { width: '180px', targets: 1 }, // Name
            { width: '220px', targets: 2 }, // Email
            { width: '120px', targets: 3 }, // Role
            { width: '120px', targets: 4 }, // Status
            { width: '220px', targets: 5 }, // Registration Date
            { width: '160px', targets: 6 }, // Last Login
            { width: '220px', targets: 7, className: 'text-left' }  // Actions
        ],
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Users_Report',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Users_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Users Report',
                className: 'd-none'
            }
        ],
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No users available</h5><p>There are no users to display at this time.</p></div>'
        }
    });

    // Hide default DataTables elements
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
}

function loadUsers() {
    // Show loading state
    const tableBody = $('#usersTableBody');
                    tableBody.html('<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading users...</td></tr>');
    
    // Load users from the database via AJAX
    $.ajax({
        url: '{{ route("superadmin.users.list") }}',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                usersTable.clear();
                
                if (response.data.length === 0) {
                    usersTable.row.add([
                        '<td colspan="8" class="text-center">No users found</td>'
                    ]).draw(false);
                    return;
                }
                
                response.data.forEach(user => {
                    const displayName = user.first_name && user.last_name 
                        ? `${user.first_name} ${user.last_name}` 
                        : user.name || 'N/A';
                    
                    const rowData = [
                        `<a href="#" class="user-id-link" onclick="showUserDetails('${user.id}')">${user.id}</a>`,
                        displayName,
                        user.email,
                        `<span class="badge badge-${getRoleBadgeClass(user.role)}">${user.role}</span>`,
                        `<span class="badge badge-${getStatusBadgeClass(user.status)}">${user.status}</span>`,
                        new Date(user.created_at).toLocaleDateString(),
                        getLastLoginDisplay(user),
                        `<div class="action-buttons">
                            <button type="button" class="btn-action btn-action-edit" data-toggle="modal" data-target="#editAdminModal" data-user-id="${user.id}" onclick="editUser('${user.id}')" title="Edit">
                                <i class="fas fa-edit"></i>
                                <span>Edit</span>
                            </button>

                            <button class="btn-action btn-action-deletes" onclick="confirmDelete('${user.id}')" title="Delete">
                                <i class="fas fa-trash"></i>
                                <span>Delete</span>
                            </button>
                        </div>`
                    ];
                    
                    usersTable.row.add(rowData).draw(false);
                });
            } else {
                showNotification('Failed to load users: ' + (response.message || 'Unknown error'), 'danger');
            }
        },
        error: function(xhr) {
            console.error('Error loading users:', xhr);
            showNotification('Error loading users. Please try again.', 'danger');
            tableBody.html('<tr><td colspan="8" class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Error loading users</td></tr>');
        }
    });
}

function updateStats() {
    // Update stats via AJAX
    $.ajax({
        url: '{{ route("superadmin.users.stats") }}',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                document.getElementById('totalUsersCount').textContent = response.data.total;
                document.getElementById('activeUsersCount').textContent = response.data.approved;
                document.getElementById('pendingUsersCount').textContent = response.data.pending;
                document.getElementById('suspendedUsersCount').textContent = response.data.rejected;
                
                // Update timestamp
                document.getElementById('lastUpdated').textContent = new Date().toLocaleTimeString();
            }
        },
        error: function(xhr) {
            console.error('Error loading stats:', xhr);
        }
    });
}

function getRoleBadgeClass(role) {
    switch(role) {
        case 'superadmin': return 'primary';
        case 'admin': return 'warning';
        case 'farmer': return 'success';
        default: return 'secondary badge-pill';
    }
}

function getStatusBadgeClass(status) {
    switch(status) {
        case 'approved': return 'success';
        case 'pending': return 'warning';
        case 'rejected': return 'danger';
        default: return 'secondary';
    }
}

function formatLastLogin(lastLoginAt) {
    if (!lastLoginAt) return 'Never';
    
    const loginDate = new Date(lastLoginAt);
    const now = new Date();
    const diffInHours = Math.floor((now - loginDate) / (1000 * 60 * 60));
    const diffInDays = Math.floor(diffInHours / 24);
    
    if (diffInDays > 0) {
        return `${diffInDays} day${diffInDays > 1 ? 's' : ''} ago`;
    } else if (diffInHours > 0) {
        return `${diffInHours} hour${diffInHours > 1 ? 's' : ''} ago`;
    } else {
        const diffInMinutes = Math.floor((now - loginDate) / (1000 * 60));
        if (diffInMinutes > 0) {
            return `${diffInMinutes} minute${diffInMinutes > 1 ? 's' : ''} ago`;
        } else {
            return 'Just now';
        }
    }
}

function getLastLoginDisplay(user) {
    if (!user.last_login_at) return 'Never';
    
    const lastLogin = formatLastLogin(user.last_login_at);
    const onlineIndicator = user.is_online ? ' <span class="badge badge-success badge-sm" title="Currently Online"><i class="fas fa-circle"></i></span>' : '';
    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    
    return `<span title="${new Date(user.last_login_at).toLocaleString()} (${timezone})">${lastLogin}</span>${onlineIndicator}`;
}



function showAddUserModal() {
    resetUserForm();
    $('#userModalLabel').html('<i class="fas fa-user-plus"></i> Add New User');
    $('#passwordFields').show();
    $('#password').prop('required', true);
    $('#passwordConfirmation').prop('required', true);
    $('#userStatus').val('pending'); // Set default status for new users
    
    // Ensure any previous backdrops are cleared
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();

    // Show the modal using Bootstrap 4 API
    $('#userModal').modal({
        backdrop: 'static',
        keyboard: true,
        show: true
    });
}

function editUser(userId) {
    console.log('[Users] editUser clicked with id:', userId);
    // Clear any previous notifications
    $('#editAdminFormNotification').hide().empty();
    
    // Show the modal using Bootstrap 4 syntax
    $('#editAdminModal').modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
    
    // Show loading state
    const submitBtn = $('#updateAdminBtn');
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');
    
    // Fetch user data
    $.ajax({
        url: `{{ route("superadmin.users.show", ":id") }}`.replace(':id', userId),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const user = response.data;
                
                // Populate form fields
                $('#editAdminId').val(user.id);
                $('#editAdminFirstName').val(user.first_name || '');
                $('#editAdminLastName').val(user.last_name || '');
                $('#editAdminEmail').val(user.email || '');
                $('#editAdminUsername').val(user.username || '');
                $('#editAdminPhone').val(user.phone || '');
                $('#editAdminBarangay').val(user.barangay || '');
                $('#editAdminRole').val(user.role || 'admin');
                $('#editAdminStatus').val(user.status || 'active');
            } else {
                $('#editAdminModal').modal('hide');
                showNotification('Error loading user data', 'danger');
            }
        },
        error: function(xhr) {
            $('#editAdminModal').modal('hide');
            let errorMessage = 'Error loading user data. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showNotification(errorMessage, 'danger');
        },
        complete: function() {
            submitBtn.prop('disabled', false).html('Update User');
        }
    });
}

// Handle edit admin form submission
$(document).on('submit', '#editAdminForm', function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = $('#updateAdminBtn');
    const notification = $('#editAdminFormNotification');
    
    // Disable submit button
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
    
    // Clear previous notifications
    notification.hide().empty();
    
    // Get form data
    const formData = new FormData(form);
    const userId = formData.get('user_id');
    
    // Convert FormData to JSON
    const jsonData = {};
    formData.forEach((value, key) => {
        // Don't include empty password fields
        if (key === 'password' && !value) return;
        jsonData[key] = value;
    });
    
    // Submit via AJAX
    $.ajax({
        url: `/superadmin/users/${userId}`,
        method: 'PUT',
        data: jsonData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Close modal using Bootstrap 4 syntax
                $('#editAdminModal').modal('hide');
                
                // Show success message
                showNotification('User updated successfully', 'success');
                
                // Refresh the users table
                loadUsers();
                
                // Update stats if needed
                if (typeof updateStats === 'function') {
                    updateStats();
                }
            } else {
                // Show error message
                let errorMessage = response.message || 'Error updating admin. Please try again.';
                if (response.errors) {
                    errorMessage = Object.values(response.errors).flat().join('<br>');
                }
                
                notification.html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        ${errorMessage}
                    </div>
                `).show();
            }
        },
        error: function(xhr) {
            let errorMessage = 'Error updating admin. Please try again.';
            
            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
            }
            
            notification.html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    ${errorMessage}
                </div>
            `).show();
        },
        complete: function() {
            // Re-enable submit button
            submitBtn.prop('disabled', false).html('Update User');
            
            // Scroll to notification if there's an error
            if (notification.is(':visible')) {
                $('html, body').animate({
                    scrollTop: notification.offset().top - 100
                }, 500);
            }
        }
    });
});

// Expose functions globally for inline onclick handlers
window.editUser = editUser;
window.showAddUserModal = showAddUserModal;
window.showUserDetails = showUserDetails;
window.toggleUserStatus = toggleUserStatus;

function populateUserForm(user) {
    $('#userId').val(user.id);
    $('#firstName').val(user.first_name);
    $('#lastName').val(user.last_name);
    $('#email').val(user.email);
    $('#username').val(user.username);
    $('#phone').val(user.phone || '');
    $('#barangay').val(user.barangay || '');
    $('#userRole').val(user.role);
    $('#userStatus').val(user.status || 'pending');
    
    // Ensure required fields are properly set
    if (user.first_name) $('#firstName').prop('required', true);
    if (user.last_name) $('#lastName').prop('required', true);
    if (user.email) $('#email').prop('required', true);
    if (user.username) $('#username').prop('required', true);
    if (user.role) $('#userRole').prop('required', true);
    if (user.status) $('#userStatus').prop('required', true);
}

function resetUserForm() {
    $('#userForm')[0].reset();
    $('#userId').val('');
    $('#formNotification').hide();
}

function saveUser(event) {
    event.preventDefault();
    
    const userId = $('#userId').val();
    const url = userId ? `{{ route("superadmin.users.update", ":id") }}`.replace(':id', userId) : '{{ route("superadmin.users.store") }}';
    
    // Get form data manually to ensure proper serialization
    const formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        first_name: $('#firstName').val(),
        last_name: $('#lastName').val(),
        email: $('#email').val(),
        username: $('#username').val(),
        phone: $('#phone').val(),
        barangay: $('#barangay').val(),
        role: $('#userRole').val(),
        status: $('#userStatus').val()
    };
    
    // Add password only if provided (for new users or password changes)
    if ($('#password').val()) {
        formData.password = $('#password').val();
        formData.password_confirmation = $('#passwordConfirmation').val();
    }
    
    // Debug: Log form data
    console.log('Form data being sent:', formData);
    console.log('Required field values:');
    console.log('firstName:', $('#firstName').val());
    console.log('lastName:', $('#lastName').val());
    console.log('email:', $('#email').val());
    console.log('username:', $('#username').val());
    console.log('role:', $('#userRole').val());
    console.log('status:', $('#userStatus').val());
    console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
    
    const method = userId ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        method: method,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        success: function(response) {
            $('#userModal').modal('hide');
            loadUsers();
            updateStats();
            showNotification(userId ? 'User updated successfully' : 'User created successfully', 'success');
        },
        error: function(xhr) {
            console.log('Error response:', xhr.responseJSON);
            const errors = xhr.responseJSON?.errors || {};
            let errorMessage = 'Please fix the following errors:';
            
            Object.keys(errors).forEach(field => {
                errorMessage += `\n• ${field}: ${errors[field][0]}`;
            });
            
            document.getElementById('formNotification').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    ${errorMessage}
                </div>
            `;
            document.getElementById('formNotification').style.display = 'block';
        }
    });
}

function toggleUserStatus(userId, currentStatus) {
    $.ajax({
        url: `{{ route("superadmin.users.toggle-status", ":id") }}`.replace(':id', userId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                loadUsers();
                updateStats();
                showNotification(`User status updated to ${response.status}`, 'success');
            } else {
                showNotification('Error updating user status', 'danger');
            }
        },
        error: function(xhr) {
            showNotification('Error updating user status', 'danger');
        }
    });
}

function showUserDetails(userId) {
    // Load user details via AJAX
    $.ajax({
        url: `{{ route("superadmin.users.show", ":id") }}`.replace(':id', userId),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const user = response.data;
                const displayName = user.first_name && user.last_name 
                    ? `${user.first_name} ${user.last_name}` 
                    : user.name || 'N/A';
                
                const details = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Personal Information</h6>
                            <p><strong>Full Name:</strong> ${displayName}</p>
                            <p><strong>Email:</strong> ${user.email}</p>
                            <p><strong>Username:</strong> ${user.username}</p>
                            <p><strong>Contact Number:</strong> ${user.phone || 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Account Information</h6>
                            <p><strong>Role:</strong> <span class="badge badge-${getRoleBadgeClass(user.role)}">${user.role}</span></p>
                            <p><strong>Status:</strong> <span class="badge badge-${getStatusBadgeClass(user.status)}">${user.status}</span></p>
                            <p><strong>Barangay:</strong> ${user.barangay || 'N/A'}</p>
                            <p><strong>Registration Date:</strong> ${new Date(user.created_at).toLocaleDateString()}</p>
                            <p><strong>Last Login:</strong> ${user.last_login_at ? formatLastLogin(user.last_login_at) : 'Never'}${user.is_online ? ' <span class="badge badge-success badge-sm" title="Currently Online"><i class="fas fa-circle"></i> Online</span>' : ''}</p>
                        </div>
                    </div>
                `;

                document.getElementById('userDetails').innerHTML = details;
                $('#userDetailsModal').modal('show');
            } else {
                showNotification('Error loading user details', 'danger');
            }
        },
        error: function(xhr) {
            console.error('Error loading user details:', xhr);
            showNotification('Error loading user details', 'danger');
        }
    });
}

function confirmDelete(userId) {
    userToDelete = userId;
    $('#confirmDeleteModal').modal('show');
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (userToDelete) {
        deleteUser(userToDelete);
        userToDelete = null;
        $('#confirmDeleteModal').modal('hide');
    }
});

function deleteUser(userId) {
    $.ajax({
        url: `{{ route("superadmin.users.destroy", ":id") }}`.replace(':id', userId),
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            loadUsers();
            updateStats();
            showNotification('User deleted successfully', 'success');
        },
        error: function(xhr) {
            showNotification('Error deleting user', 'danger');
        }
    });
}

function exportCSV() {
    // Get current table data without actions column
    const tableData = usersTable.data().toArray();
    const csvData = [];
    
    // Add headers (excluding Actions column)
    const headers = ['User ID', 'Name', 'Email', 'Role', 'Status', 'Registration Date', 'Last Login'];
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
    link.setAttribute('download', `SuperAdmin_UserReport_${downloadCounter}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Increment download counter
    downloadCounter++;
}

function exportPDF() {
    try {
        // Check if jsPDF is available
        if (typeof window.jspdf === 'undefined') {
            console.warn('jsPDF not available, falling back to DataTables PDF export');
            // Fallback to DataTables PDF export
            usersTable.button('.buttons-pdf').trigger();
            return;
        }
        
        // Get current table data without actions column
        const tableData = usersTable.data().toArray();
        const pdfData = [];
        
        // Add headers (excluding Actions column)
        const headers = ['User ID', 'Name', 'Email', 'Role', 'Status', 'Registration Date', 'Last Login'];
        
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
        doc.text('SuperAdmin User Report', 14, 22);
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
        doc.save(`SuperAdmin_UserReport_${downloadCounter}.pdf`);
        
        // Increment download counter
        downloadCounter++;
        
        showNotification('PDF exported successfully!', 'success');
        
    } catch (error) {
        console.error('Error generating PDF:', error);
        showNotification('Error generating PDF. Falling back to DataTables export.', 'warning');
        
        // Fallback to DataTables PDF export
        try {
            usersTable.button('.buttons-pdf').trigger();
        } catch (fallbackError) {
            console.error('Fallback PDF export also failed:', fallbackError);
            showNotification('PDF export failed. Please try again.', 'danger');
        }
    }
}

function exportPNG() {
    // Create a temporary table without the Actions column for export
    const originalTable = document.getElementById('usersTable');
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
        link.download = `SuperAdmin_UserReport_${downloadCounter}.png`;
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

function printTable() {
    try {
        // Get current table data without actions column
        const tableData = usersTable.data().toArray();
        
        if (!tableData || tableData.length === 0) {
            showNotification('No data available to print', 'warning');
            return;
        }
        
        // Create print content directly in current page
        const originalContent = document.body.innerHTML;
        
        let printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #18375d; margin-bottom: 5px;">SuperAdmin User Report</h1>
                    <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                <table border="3" style="border-collapse: collapse; width: 100%; border: 3px solid #000;">
                    <thead>
                        <tr>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">User ID</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Name</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Email</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Role</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Status</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Registration Date</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Last Login</th>
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
            usersTable.button('.buttons-print').trigger();
        } catch (fallbackError) {
            console.error('Fallback print also failed:', fallbackError);
            showNotification('Print failed. Please try again.', 'danger');
        }
    }
}

function refreshData() {
    // Show loading indicator
    const refreshBtn = $('button[onclick="refreshData()"]');
    const originalIcon = refreshBtn.html();
    refreshBtn.html('<i class="fas fa-spinner fa-spin"></i>Refreshing...');
    refreshBtn.prop('disabled', true);
    
    // Refresh data
    loadUsers();
    updateStats();
    
    // Reset button after a short delay
    setTimeout(() => {
        refreshBtn.html(originalIcon);
        refreshBtn.prop('disabled', false);
        showNotification('Data refreshed successfully', 'success');
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

// Handle modal hide events for Bootstrap 4
$('#editAdminModal').on('hidden.bs.modal', function () {
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});

// Force modal cleanup on page load
$(document).ready(function() {
    // Clean up any leftover modal states
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});
</script>
@endpush
