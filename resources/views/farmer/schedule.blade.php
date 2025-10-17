@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers - Calendar & Schedule')
@push('styles')
<style>
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
#addEventModal form {
  text-align: left;
}

#addEventModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#addEventModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#addEventModal .form-control,
#addEventModal select.form-control,
#addEventModal textarea.form-control {
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
#addEventModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#addEventModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}


#eventDetailsModal form {
  text-align: left;
}

#eventDetailsModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#eventDetailsModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#eventDetailsModal .form-control,
#eventDetailsModal select.form-control,
#eventDetailsModal textarea.form-control {
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
#eventDetailsModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#eventDetailsModal .form-control:focus {
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

/* To-Do (Blue) */
.btn-to-do {
  background: #fff;
  color: #007bff;
  border: 2px solid #007bff;
  transition: all 0.3s ease;
}
.btn-to-do:hover {
  background: #007bff;
  color: #fff;
}

/* In Progress (Green) */
.btn-progress {
  background: #fff;
  color: #28a745;
  border: 2px solid #28a745;
  transition: all 0.3s ease;
}
.btn-progress:hover {
  background: #28a745;
  color: #fff;
}

/* Resolved / Completed (Yellow) */
.btn-resolved {
  background: #fff;
  color: #f9b208;
  border: 2px solid #f9b208;
  transition: all 0.3s ease;
}
.btn-resolved:hover {
  background: #f9b208;
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

  #addEventModal .form-control {
    font-size: 14px;
  }

  #editIssueModal .form-control {
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
        padding: 0.5rem;
        max-height: 95vh;
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
    
    .btn-action-delete {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .btn-action-delete:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
/* ============================
   TABLE LAYOUT
============================ */
    /* Apply consistent styling for Farmers, Livestock, and Issues tables */
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
#livestockTable thead th.sorting,
#livestockTable thead th.sorting_asc,
#livestockTable thead th.sorting_desc,
#issuesTable thead th.sorting,
#issuesTable thead th.sorting_asc,
#issuesTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
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
#livestockTable thead th.sorting::after,
#livestockTable thead th.sorting_asc::after,
#livestockTable thead th.sorting_desc::after,
#issuesTable thead th.sorting::after,
#issuesTable thead th.sorting_asc::after,
#issuesTable thead th.sorting_desc::after {
    display: none;
}
/* Make table cells wrap instead of forcing them all inline */
#livestockTable td, 
#issuesTable th {
    white-space: normal !important;  /* allow wrapping */
    vertical-align: middle;
}

/* Make sure action buttons don’t overflow */
#livestockTable td .btn-group {
    display: flex;
    flex-wrap: wrap; /* buttons wrap if not enough space */
    gap: 0.25rem;    /* small gap between buttons */
}

#livestockTable td .btn-action {
    flex: 1 1 auto; /* allow buttons to shrink/grow */
    min-width: 90px; /* prevent too tiny buttons */
    text-align: center;
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
    #addEventModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #addEventModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #addEventModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #addEventModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #addEventModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #addEventModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #addEventModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #addEventModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }

    /* ========== CALENDAR CONTAINER ========== */
#calendar {
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  padding: 15px;
  transition: all 0.3s ease;
}

#calendar:hover {
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
}

/* ========== TOOLBAR ========== */
.fc-toolbar {
  background: #ffff;
  border-radius: 8px;
  padding: 10px 15px;
  color: #ffff;
}

.fc-toolbar-title {
  font-weight: 600;
  font-size: 1.2rem;
  color: #18375d;
}

.fc-button {
  background: #ffffff;
  border: 2px solid #18375d;
  color: #18375d;
  font-weight: 500;
  border-radius: 6px;
  transition: all 0.2s ease;
}

.fc-button:hover {
  background: #18375d;
  color: #fff;
}

/* Active view button */
.fc-button-active {
  background: #18375d !important;
  color: #fff !important;
  border-color: #18375d !important;
}

/* ========== DAYS GRID ========== */
.fc-daygrid-day {
  background: #ffffff;
  transition: background-color 0.2s ease;
}

.fc-day-today {
  background-color: #e6f0ff !important;
  border-left: 3px solid #007bff !important;
}

.fc-daygrid-day:hover {
  background: #f8f9fa;
}

/* ========== EVENTS ========== */
.fc-event {
  border-radius: 6px !important;
  border: none !important;
  font-size: 0.85rem;
  font-weight: 500;
  padding: 3px 5px;
  transition: transform 0.1s ease;
}

.fc-event:hover {
  transform: scale(1.03);
  opacity: 0.9;
}

/* Category-based color samples (you can change these as needed) */
.fc-event.feeding {
  background-color: #28a745 !important;
  color: #fff !important;
}
.fc-event.health {
  background-color: #007bff !important;
  color: #fff !important;
}
.fc-event.maintenance {
  background-color: #ffc107 !important;
  color: #000 !important;
}
.fc-event.milking {
  background-color: #6f42c1 !important;
  color: #fff !important;
}
.fc-event.breeding {
  background-color: #20c997 !important;
  color: #fff !important;
}
.fc-event.other {
  background-color: #6c757d !important;
  color: #fff !important;
}

/* ========== LIST VIEW ========== */
.fc-list {
  border-radius: 8px;
}

.fc-list-event:hover {
  background: #f1f4f9;
}

/* ========== RESPONSIVE ADJUSTMENTS ========== */
@media (max-width: 768px) {
  #calendar {
    padding: 10px;
  }
  .fc-toolbar {
    flex-direction: column;
    text-align: center;
  }
}
/* Navigation arrows (prev/next) */
.fc-prev-button,
.fc-next-button {
  border-radius: 20% !important;
  width: 36px;
  height: 36px;
  display: flex !important;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  border: 2px solid #ffffff !important;
  background: #ffffff !important;
  color: #18375d !important;
  transition: all 0.2s ease;
}

.fc-prev-button:hover,
.fc-next-button:hover {
  background: #18375d !important;
  color: #ffffff !important;
}
/* ======== RIGHT TOOLBAR BUTTONS ======== */
.fc-toolbar-chunk:last-child .fc-button-group .fc-button {
  background: #ffffff;
  border: 2px solid #fff;
  color: #18375d;
  border-radius: 6px;
  font-weight: 500;
  padding: 6px 12px;
  margin-left: 4px;
  transition: all 0.2s ease;
}

/* Hover effect */
.fc-toolbar-chunk:last-child .fc-button-group .fc-button:hover {
  background: #18375d;
  color: #fff;
}

/* Active view (selected button) */
.fc-toolbar-chunk:last-child .fc-button-group .fc-button-active {
  background: #fff !important;
  color: #18375d !important;
  border-color: #18375d !important;
  box-shadow: 0 0 0 3px rgba(24, 55, 93, 0.15);
}

/* Remove weird focus outline */
.fc-toolbar-chunk:last-child .fc-button-group .fc-button:focus {
  outline: none !important;
  box-shadow: none !important;
}
/* ======== LEFT TOOLBAR - TODAY BUTTON ======== */
.fc-toolbar-chunk:first-child .fc-today-button {
  background: #ffffff;
  border: 2px solid #18375d; /* Green accent */
  color: #18375d;
  font-weight: 600;
  border-radius: 6px;
  padding: 6px 14px;
  transition: all 0.2s ease;
}

/* Hover effect */
.fc-toolbar-chunk:first-child .fc-today-button:hover {
  background: #18375d;
  color: #ffffff;
}

/* Disabled state (when already on today) */
.fc-toolbar-chunk:first-child .fc-today-button:disabled {
  background: #fff !important;
  color: #18375d !important;
  border-color: #18375d !important;
  box-shadow: 0 0 0 3px rgba(24, 55, 93, 0.15);
}

/* Remove unwanted focus outline */
.fc-toolbar-chunk:first-child .fc-today-button:focus {
  outline: none !important;
  box-shadow: none !important;
}

</style>
@section('content')
<!-- Page Header -->
<div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
    <h1>
        <i class="fas fa-calendar-alt"></i>
        Farm Activity Calendar
    </h1>
    <p>Manage your farm schedule, track activities, and organize daily operations</p>
</div>

<div class="row">
    <!-- Quick Stats & Notes Sidebar -->
    <div class="col-md-3">
        <!-- Quick Stats Card -->
        <div class="card shadow mb-4 fade-in">
            <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start">
                <h6>
                    <i class="fas fa-chart-pie"></i>
                    Quick Stats
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="h4  mb-0" id="totalEvents">0</div>
                    <small class="text-muted">Total Events</small>
                </div>
                <div class="text-center mb-3">
                    <div class="h4 text-success mb-0" id="completedEvents">0</div>
                    <small class="text-muted">Completed</small>
                </div>
                <div class="text-center mb-3">
                    <div class="h4 text-warning mb-0" id="pendingEvents">0</div>
                    <small class="text-muted">Pending</small>
                </div>
                <div class="text-center">
                    <div class="h4 text-info mb-0" id="todayEvents">0</div>
                    <small class="text-muted">Today</small>
                </div>
            </div>
        </div>

        <!-- Quick Notes Card -->
        <div class="card shadow mb-4 fade-in">
            <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start">
                <h6>
                    <i class="fas fa-sticky-note"></i>
                    Quick Notes
                </h6>
            </div>
            <div class="card-body">
                <form id="quickNoteForm">
                    <div class="form-group">
                        <textarea class="form-control" id="quickNoteText" rows="3" placeholder="Add a quick note..." maxlength="200"></textarea>
                    </div>
                    <button type="submit" class="btn-action btn-action-ok btn-sm btn-block">
                        <i class="fas fa-plus mr-1"></i> Add Note
                    </button>
                </form>
                <hr>
                <ul class="list-group list-group-flush" id="quickNotesList">
                    <li class="list-group-item text-center text-muted">No notes yet</li>
                </ul>
            </div>
        </div>


    </div>

    <!-- Calendar Section -->
    <div class="col-md-9">
        <div class="card shadow mb-4 fade-in">
            <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start">
                <h6>
                    <i class="fas fa-calendar-week"></i>
                    Farm Activity Calendar
                </h6>
                 <div class="d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button class="btn-action btn-action-ok " data-toggle="modal" data-target="#addEventModal" type="button">
                        <i class="fas fa-plus"></i> Add Event
                    </button>
                    <button class="btn-action btn-action-history " onclick="exportCalendar()" type="button">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <!-- THE CALENDAR -->
                <div id="calendar" style="min-height: 600px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Smart Form - Add Event Modal -->
<div class="modal fade admin-modal" id="addEventModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content smart-form text-center p-4">
      
      <!-- Icon + Header -->
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle">
          <i class="fas fa-calendar-plus fa-2x"></i>
        </div>
        <h5 class="fw-bold mb-1">Add New Event</h5>
        <p class="text-muted mb-0 small">
          Fill in the event details below to add it to your schedule.
        </p>
      </div>

      <!-- Form -->
      <form id="eventForm" onsubmit="submitEvent(event)" autocomplete="off">
        <div class="form-wrapper text-start mx-auto">
          <div class="row g-3">

            <!-- Event Title -->
            <div class="col-md-12">
              <label for="event_title" class="fw-semibold">Event Title <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="event_title" name="title" maxlength="100" required placeholder="Enter event title">
            </div>

            <!-- Date & Time -->
            <div class="col-md-6">
              <label for="event_start" class="fw-semibold">Start Date & Time <span class="text-danger">*</span></label>
              <input type="datetime-local" class="form-control" id="event_start" name="start" required>
            </div>

            <div class="col-md-6">
              <label for="event_end" class="fw-semibold">End Date & Time</label>
              <input type="datetime-local" class="form-control" id="event_end" name="end">
            </div>

            <!-- Category -->
            <div class="col-md-6">
              <label for="event_category" class="fw-semibold">Category</label>
              <select class="form-control" id="event_category" name="category">
                <option value="feeding">Feeding</option>
                <option value="health">Health & Medical</option>
                <option value="maintenance">Maintenance</option>
                <option value="milking">Milking</option>
                <option value="breeding">Breeding</option>
                <option value="other">Other</option>
              </select>
            </div>

            <!-- Priority -->
            <div class="col-md-6">
              <label for="event_priority" class="fw-semibold">Priority</label>
              <select class="form-control" id="event_priority" name="priority">
                <option value="low">Low</option>
                <option value="medium" selected>Medium</option>
                <option value="high">High</option>
              </select>
            </div>

            <!-- Description -->
            <div class="col-md-12">
              <label for="event_desc" class="fw-semibold">Description</label>
              <textarea class="form-control mt-1" id="event_desc" name="description" rows="3" maxlength="500" placeholder="Optional event description" style="resize: none;"></textarea>
            </div>

            <div id="formNotification" class="mt-2" style="display: none;"></div>
          </div>
        </div>

        <!-- Footer Buttons -->
       <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
          <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-modern btn-ok" title="Save Event">
            <i class="fas fa-save"></i> Save
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Smart Detail - Event Details Modal -->
<div class="modal fade admin-modal" id="eventDetailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content smart-detail p-4">

      <!-- Icon + Header -->
      <div class="d-flex flex-column align-items-center mb-4 text-center">
        <div class="icon-circle">
          <i class="fas fa-circle-info fa-2x"></i>
        </div>
        <h5 class="fw-bold mb-1" id="eventDetailsTitle">Event Details</h5>
        <p class="text-muted mb-0 small">
          View complete information and manage event progress.
        </p>
      </div>

      <!-- Body -->
      <div class="modal-body text-start" id="eventDetailsContent">
        <!-- Filled by JS -->
      </div>

      <!-- Status Actions (separate from footer) -->
      <div class="d-flex justify-content-center flex-wrap gap-2 mt-3 mb-2">
        <button type="button" id="btnMarkTodo" class="btn-modern btn-to-do">
          Mark To-Do
        </button>
        <button type="button" id="btnMarkInProgress" class="btn-modern btn-progress">
          Mark In Progress
        </button>
        <button type="button" id="btnMarkDone" class="btn-modern btn-resolved">
          Mark Completed
        </button>
      </div>

      <!-- Footer -->
      <div class="modal-footer d-flex justify-content-center border-0 pt-3">
        <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">
          Close
        </button>
      </div>

    </div>
  </div>
</div>



@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
let calendar;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize calendar
    initializeCalendar();
    
    // Initialize quick notes
    initializeQuickNotes();
    

    
    // Update stats
    updateStats();
    
    // Initialize event form
    initializeEventForm();
});

function initializeCalendar() {
    var calendarEl = document.getElementById('calendar');
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        initialView: 'dayGridMonth',
        height: 'auto',
        // Fetch events with credentials to ensure auth cookies are sent
        events: function(info, successCallback, failureCallback) {
            fetch('/calendar/events', {
                method: 'GET',
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json' }
            })
            .then(res => {
                if (!res.ok) throw new Error('Failed to load events');
                return res.json();
            })
            .then(data => {
                try { console.log('[Calendar] Loaded events count:', Array.isArray(data) ? data.length : 'n/a'); } catch (e) {}
                successCallback(data || []);
            })
            .catch(err => {
                console.error('Calendar events load error:', err);
                showNotification('Failed to load calendar events.', 'error');
                if (failureCallback) failureCallback(err);
            });
        },
        editable: true,
        droppable: false,
        eventResizableFromStart: true,
        eventDurationEditable: true,
        eventStartEditable: true,
        
        // Event interactions
        eventClick: function(info) {
            openEventDetailsModal(info.event);
        },
        
        eventDrop: function(info) {
            // Block moving admin-scheduled inspections
            if ((info.event.id && info.event.id.startsWith('insp_')) || info.event.extendedProps.category === 'inspection') {
                showNotification('Inspection events cannot be moved.', 'error');
                info.revert();
                return;
            }
            updateEvent(info.event);
        },
        
        eventResize: function(info) {
            // Block resizing admin-scheduled inspections
            if ((info.event.id && info.event.id.startsWith('insp_')) || info.event.extendedProps.category === 'inspection') {
                showNotification('Inspection events cannot be resized.', 'error');
                info.revert();
                return;
            }
            updateEvent(info.event);
        },
        
        // Date click
        dateClick: function(info) {
            openAddEventModal(info.dateStr);
        },
        
        // Event loading
        eventDidMount: function(info) {
            // Add tooltip with event details (only if Bootstrap tooltip is available)
            try {
                if (window.jQuery && jQuery.fn && typeof jQuery.fn.tooltip === 'function') {
                    jQuery(info.el).tooltip({
                        title: info.event.title + (info.event.extendedProps.description ? '<br>' + info.event.extendedProps.description : ''),
                        html: true,
                        placement: 'top',
                        trigger: 'hover'
                    });
                }
            } catch (e) {
                // Fail silently; tooltips are optional
                console.warn('Tooltip init failed:', e);
            }
        },
        
        // Refresh stats whenever events are updated in the view
        eventsSet: function(events) {
            try { console.log('[Calendar] eventsSet size:', events ? events.length : 'n/a'); } catch (e) {}
            updateStats(events);
        },
        // Also refresh stats when loading finishes (safety net)
        loading: function(isLoading) {
            if (!isLoading) {
                updateStats();
            }
        }
    });
    
    calendar.render();
}





function initializeQuickNotes() {
    const form = document.getElementById('quickNoteForm');
    const noteInput = document.getElementById('quickNoteText');
    const notesList = document.getElementById('quickNotesList');

    function loadNotes() {
        notesList.innerHTML = '';
        const notes = JSON.parse(localStorage.getItem('quickNotes') || '[]');
        
        if (notes.length === 0) {
            notesList.innerHTML = '<li class="list-group-item text-center text-muted">No notes yet</li>';
            return;
        }
        
        notes.forEach((note, idx) => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-start';
            li.innerHTML = `
                <div class="flex-grow-1">
                    <small class="text-muted">${note.date}</small>
                    <div>${note.text.replace(/</g, "&lt;").replace(/>/g, "&gt;")}</div>
                </div>
                <button class="btn-action btn-sm btn-outline-danger ml-2" onclick="deleteNote(${idx})" title="Delete note">
                    <i class="fas fa-trash-alt"></i>
                </button>
            `;
            notesList.appendChild(li);
        });
    }

    window.deleteNote = function(index) {
        const notes = JSON.parse(localStorage.getItem('quickNotes') || '[]');
        notes.splice(index, 1);
        localStorage.setItem('quickNotes', JSON.stringify(notes));
        loadNotes();
        showNotification('Note deleted!', 'success');
    };

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const noteText = noteInput.value.trim();
        if (noteText) {
            const notes = JSON.parse(localStorage.getItem('quickNotes') || '[]');
            const newNote = {
                text: noteText,
                date: new Date().toLocaleDateString()
            };
            notes.unshift(newNote);
            localStorage.setItem('quickNotes', JSON.stringify(notes));
            noteInput.value = '';
            loadNotes();
            showNotification('Note added!', 'success');
        }
    });

    loadNotes();
}

function initializeEventForm() {
    document.getElementById('eventForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        var title = document.getElementById('event_title').value.trim();
        var start = document.getElementById('event_start').value;
        var end = document.getElementById('event_end').value;
        var category = document.getElementById('event_category').value;
        var priority = document.getElementById('event_priority').value;
        var description = document.getElementById('event_desc').value.trim();
        
        if (!title || !start) {
            showNotification('Please fill in all required fields!', 'error');
            return;
        }
        
        // Prepare data for API
        // Normalize to ISO timestamps to avoid validation issues
        var startIso = start ? new Date(start).toISOString() : null;
        var endIso = end ? new Date(end).toISOString() : null;
        var eventData = {
            title: title,
            start: startIso,
            end: endIso || startIso,
            priority: priority,
            description: description,
            category: category
        };
        try { console.log('[Calendar] Submitting eventData:', eventData); } catch (e) {}
        
        // Send to API
        fetch('/calendar/events', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(eventData)
        })
        .then(async response => {
            let payload = null;
            try { payload = await response.json(); } catch (e) {}
            if (!response.ok) {
                const msg = (payload && (payload.message || (payload.errors && JSON.stringify(payload.errors)))) || `HTTP ${response.status}`;
                throw new Error(msg);
            }
            return payload || { success: true };
        })
        .then(data => {
            if (data.success) {
                // Refresh calendar
                calendar.refetchEvents();
                
                // Close modal and reset form
                $('#addEventModal').modal('hide');
                document.getElementById('eventForm').reset();
                
                showNotification('Event added successfully!', 'success');
                updateStats();
            } else {
                showNotification('Error adding event: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error adding event: ' + (error && error.message ? error.message : error), 'error');
        });
    });
}



function openEventDetailsModal(event) {
    try {
        // Title
        document.getElementById('eventDetailsTitle').textContent = event.title || 'Event Details';

        // Dates
        const startStr = event.start ? event.start.toLocaleString() : '';
        const endStr = event.end ? event.end.toLocaleString() : '';
        let whenHtml = startStr;
        if (endStr && endStr !== startStr) {
            whenHtml += ' - ' + endStr;
        }

        // Extended properties
        const ext = event.extendedProps || {};
        const category = ext.category || (event.id && event.id.startsWith('insp_') ? 'inspection' : 'general');
        const priority = ext.priority || 'medium';
        const status = ext.status || 'todo';
        const description = ext.description || '';

        const content = `
            <div class="mb-2">
                <strong>When:</strong><br>
                <span>${whenHtml}</span>
            </div>
            <div class="mb-2">
                <strong>Category:</strong><br>
                <span class="badge badge-info">${category}</span>
            </div>
            <div class="mb-2">
                <strong>Priority / Status:</strong><br>
                <span class="badge badge-${getPriorityBadgeClass(priority)} mr-1">${priority}</span>
                <span class="badge badge-${getStatusBadgeClass(status)}">${status}</span>
            </div>
            ${description ? `<div class=\"mb-2\"><strong>Description:</strong><br><div>${description.replace(/</g,'&lt;').replace(/>/g,'&gt;')}</div></div>` : ''}
            ${(category === 'inspection' || (event.id && event.id.startsWith('insp_'))) ? `<div class=\"text-muted small\"><i class=\"fas fa-lock mr-1\"></i>Admin-scheduled inspection (read-only)</div>` : ''}
        `;

        const container = document.getElementById('eventDetailsContent');
        container.innerHTML = content;
        // Wire status buttons
        const isInspection = (category === 'inspection' || (event.id && event.id.startsWith('insp_')));
        const btnTodo = document.getElementById('btnMarkTodo');
        const btnInProg = document.getElementById('btnMarkInProgress');
        const btnDone = document.getElementById('btnMarkDone');

        if (btnTodo && btnInProg && btnDone) {
            // Reset previous handlers
            btnTodo.onclick = null; btnInProg.onclick = null; btnDone.onclick = null;
            if (isInspection) {
                [btnTodo, btnInProg, btnDone].forEach(b => { b.disabled = true; b.title = 'Inspection events are read-only'; });
            } else {
                [btnTodo, btnInProg, btnDone].forEach(b => { b.disabled = false; b.title = ''; });
                btnTodo.onclick = () => setEventStatus(event, 'todo');
                btnInProg.onclick = () => setEventStatus(event, 'in_progress');
                btnDone.onclick = () => setEventStatus(event, 'done');
            }
        }
        $('#eventDetailsModal').modal('show');
    } catch (e) {
        console.error('Failed to open event details modal:', e);
        showNotification('Could not open event details.', 'error');
    }
}

function updateEvent(event) {
    var eventData = {
        title: event.title,
        start: event.start.toISOString(),
        end: event.end ? event.end.toISOString() : null,
        priority: event.extendedProps.priority,
        description: event.extendedProps.description,
        status: event.extendedProps.status
    };
    
    return fetch(`/calendar/events/${event.id}`, {
        method: 'PUT',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(eventData)
    })
    .then(async response => {
        let payload = null;
        try { payload = await response.json(); } catch (e) {}
        if (!response.ok) {
            const msg = (payload && (payload.message || (payload.errors && JSON.stringify(payload.errors)))) || `HTTP ${response.status}`;
            throw new Error(msg);
        }
        return payload || { success: true };
    })
    .then(data => {
        if (data.success) {
            showNotification('Event updated successfully!', 'success');
            updateStats();
        } else {
            showNotification('Error updating event: ' + (data.message || 'Unknown error'), 'error');
            calendar.refetchEvents(); // Refresh to revert changes
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating event: ' + (error && error.message ? error.message : error), 'error');
        calendar.refetchEvents(); // Refresh to revert changes
    });
}

function setEventStatus(event, newStatus) {
    try {
        if ((event.id && event.id.startsWith('insp_')) || event.extendedProps.category === 'inspection') {
            showNotification('Inspection events cannot be edited here.', 'error');
            return;
        }
        // Update local event first for responsive UI
        if (typeof event.setExtendedProp === 'function') {
            event.setExtendedProp('status', newStatus);
        } else {
            event.extendedProps.status = newStatus;
        }
        // Persist to server
        updateEvent(event).then(() => {
            // Refresh details badge without closing modal
            try {
                const contentEl = document.getElementById('eventDetailsContent');
                if (contentEl) {
                    // Replace only the status badge area by reopening details content
                    openEventDetailsModal(event);
                }
            } catch (e) {}
        });
    } catch (e) {
        console.error('Failed to set status:', e);
        showNotification('Failed to change status.', 'error');
    }
}

function getPriorityBadgeClass(priority) {
    const classes = {
        'low': 'success',
        'medium': 'warning',
        'high': 'danger'
    };
    return classes[priority] || 'secondary';
}

function getStatusBadgeClass(status) {
    const classes = {
        'todo': 'secondary',
        'in_progress': 'warning',
        'done': 'success'
    };
    return classes[status] || 'secondary';
}

function openAddEventModal(dateStr) {
    document.getElementById('event_start').value = dateStr + 'T09:00';
    $('#addEventModal').modal('show');
}

function updateStats(eventsArg) {
    var events = Array.isArray(eventsArg) && eventsArg.length !== undefined ? eventsArg : calendar.getEvents();
    var today = new Date();
    today.setHours(0, 0, 0, 0);
    
    var total = events.length;
    var completed = events.filter(e => ['done','completed'].includes(e.extendedProps.status)).length;
    var pending = events.filter(e => ['todo','in_progress','scheduled'].includes(e.extendedProps.status)).length;
    var todayCount = events.filter(e => {
        var eventDate = new Date(e.start);
        eventDate.setHours(0, 0, 0, 0);
        return eventDate.getTime() === today.getTime();
    }).length;
    
    document.getElementById('totalEvents').textContent = total;
    document.getElementById('completedEvents').textContent = completed;
    document.getElementById('pendingEvents').textContent = pending;
    document.getElementById('todayEvents').textContent = todayCount;
}

function exportCalendar() {
    // Export calendar data
    var events = calendar.getEvents();
    var data = events.map(e => ({
        title: e.title,
        start: e.start.toLocaleString(),
        end: e.end ? e.end.toLocaleString() : '',
        description: e.extendedProps.description || '',
        priority: e.extendedProps.priority,
        status: e.extendedProps.status,
        assigned_to: e.extendedProps.assigned_to || '',
        created_by: e.extendedProps.created_by || ''
    }));
    
    var csv = 'Title,Start,End,Description,Priority,Status,Assigned To,Created By\n';
    data.forEach(row => {
        csv += `"${row.title}","${row.start}","${row.end}","${row.description}","${row.priority}","${row.status}","${row.assigned_to}","${row.created_by}"\n`;
    });
    
    var blob = new Blob([csv], { type: 'text/csv' });
    var url = window.URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = 'farm_calendar.csv';
    a.click();
    window.URL.revokeObjectURL(url);
    
    showNotification('Calendar exported successfully!', 'success');
}

function showNotification(message, type) {
    // Simple notification system
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
</script>
@endpush

