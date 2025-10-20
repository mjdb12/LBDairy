@extends('layouts.app')

@section('title', 'LBDAIRY: SuperAdmin - Manage Farms')

@push('styles')
<style>
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
    /* Custom styles for farm management */
    .border-left-primary {
        border-left: 0.25rem solid #18375d !important;
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
    
    .btn-group .btn {
        margin-right: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .gap-2 {
        gap: 0.5rem !important;
    }
    
    /* Farm ID link styling - match manage farmers */
    .farm-id-link {
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
    
    .farm-id-link:hover {
        color: #fff;
        background-color: #18375d;
        border-color: #18375d;
        text-decoration: none;
    }

    .farm-id-link:active {
        color: #fff;
        background-color: #122a4e;
        border-color: #122a4e;
    }
    
    /* Header action buttons styling to match User Management */
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
    
    /* Align Farm Directory table styling with User Management */
    #dataTable {
        width: 100% !important;
        min-width: 1280px;
        border-collapse: collapse;
    }

    /* Consistent table styling */
    .table {
        margin-bottom: 0;
    }

    .table-bordered {
        border: 1px solid #dee2e6;
    }

    #dataTable th,
    #dataTable td {
        vertical-align: middle;
        padding: 0.75rem;
        text-align: center;
        border: 1px solid #dee2e6;
        white-space: nowrap;
        overflow: visible;
    }

    /* Table headers styling */
    #dataTable thead th {
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
    #dataTable thead th.sorting,
    #dataTable thead th.sorting_asc,
    #dataTable thead th.sorting_desc {
        padding-right: 2rem !important;
    }

    /* Remove default DataTables sort indicators to prevent overlap */
    #dataTable thead th.sorting::after,
    #dataTable thead th.sorting_asc::after,
    #dataTable thead th.sorting_desc::after {
        display: none;
    }

    /* DataTables Pagination Styling */
    .dataTables_wrapper .dataTables_paginate {
        text-align: left !important;
        margin-top: 1rem;
        margin-bottom: 0.75rem !important; /* Match farmers directory gap */
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

    /* Ensure table has enough space for actions column */
    .table th:last-child,
    .table td:last-child {
        min-width: 200px;
        width: auto;
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

    .btn-action-view {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }

    .btn-action-view:hover {
        background-color: #2d5a47;
        border-color: #2d5a47;
        color: white;
    }

    .btn-action-toggle {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }

    .btn-action-toggle:hover {
        background-color: #e69500;
        border-color: #e69500;
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

    /* Edit button (dark green) */
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

    /* Farm Details Modal Styling */
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

    /* Prevent dark blue bars on headings inside details modal */
    #detailsModal .text-primary {
        background-color: transparent !important;
        color: #18375d !important;
    }

    /* Extra safety: ensure headings never inherit dark backgrounds */
    #detailsModal h6,
    #detailsModal h6.mb-3,
    #detailsModal h6.text-primary {
        background-color: transparent !important;
        background: transparent !important;
    }
    #detailsModal h6.mb-3::before,
    #detailsModal h6.mb-3::after {
        background: transparent !important;
        content: none !important;
    }

    #detailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }

    #detailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Match badge colors for status */
    #detailsModal .badge-success {
        background-color: #387057 !important;
        color: white !important;
    }
    #detailsModal .badge-danger {
        background-color: #dc3545 !important;
        color: white !important;
    }

    /* Enhanced Farm Details Modal Styling - Fix for dark blue background covering text */
    /* Force reset all backgrounds in modal body */
    #detailsModal .modal-body,
    #detailsModal .modal-body *,
    #detailsModal .modal-body div,
    #detailsModal .modal-body p,
    #detailsModal .modal-body strong,
    #detailsModal .modal-body h6 {
        background: transparent !important;
        background-color: transparent !important;
    }

    /* Modal content structure */
    #detailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        overflow: hidden;
    }

    /* Modal header - only this should have dark blue */
    #detailsModal .modal-header {
        background: #18375d !important;
        background-color: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
        padding: 1.5rem;
    }

    #detailsModal .modal-header .modal-title,
    #detailsModal .modal-header .modal-title * {
        color: white !important;
        background: transparent !important;
        background-color: transparent !important;
        font-weight: 600;
    }

    #detailsModal .modal-header .close {
        color: white !important;
        opacity: 0.8;
        background: transparent !important;
    }

    #detailsModal .modal-header .close:hover {
        opacity: 1;
        background: transparent !important;
    }

    /* Modal body - force white background and dark text */
    #detailsModal .modal-body {
        padding: 2rem !important;
        background: white !important;
        background-color: white !important;
        color: #333 !important;
    }

    /* Section headings in modal body */
    #detailsModal .modal-body h6,
    #detailsModal .modal-body h6.mb-3,
    #detailsModal .modal-body h6.text-primary {
        color: #18375d !important;
        background: transparent !important;
        background-color: transparent !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0 !important;
        padding-bottom: 0.5rem !important;
        margin-bottom: 1rem !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        padding-top: 0 !important;
    }

    /* Ensure no pseudo-elements add background */
    #detailsModal .modal-body h6::before,
    #detailsModal .modal-body h6::after,
    #detailsModal .modal-body h6.mb-3::before,
    #detailsModal .modal-body h6.mb-3::after {
        display: none !important;
        background: transparent !important;
        content: none !important;
    }

    /* Text content styling */
    #detailsModal .modal-body p {
        margin-bottom: 0.75rem !important;
        color: #333 !important;
        background: transparent !important;
        background-color: transparent !important;
        line-height: 1.5;
    }

    #detailsModal .modal-body strong {
        color: #5a5c69 !important;
        background: transparent !important;
        background-color: transparent !important;
        font-weight: 600;
    }

    /* Row and column containers */
    #detailsModal .modal-body .row,
    #detailsModal .modal-body .col-md-6,
    #detailsModal .modal-body .col-md-12,
    #detailsModal .modal-body .col-12 {
        background: transparent !important;
        background-color: transparent !important;
    }

    /* Badge styling */
    #detailsModal .badge {
        font-size: 0.75em;
        padding: 0.375em 0.75em;
        border-radius: 0.25rem;
        display: inline-block;
    }

    #detailsModal .badge-success {
        background-color: #387057 !important;
        color: white !important;
        border: none;
    }

    #detailsModal .badge-danger {
        background-color: #dc3545 !important;
        color: white !important;
        border: none;
    }

    /* Modal footer */
    #detailsModal .modal-footer {
        background: #f8f9fa !important;
        background-color: #f8f9fa !important;
        border-top: 1px solid #dee2e6 !important;
        padding: 1rem 1.5rem;
    }

    #detailsModal .modal-footer .btn {
        background: #6c757d !important;
        border-color: #6c757d !important;
        color: white !important;
    }

    #detailsModal .modal-footer .btn:hover {
        background: #5a6268 !important;
        border-color: #5a6268 !important;
    }

    /* Additional safety measures */
    #detailsModal .text-primary {
        background: transparent !important;
        background-color: transparent !important;
        color: #18375d !important;
    }

    #detailsModal .bg-primary,
    #detailsModal .bg-primary * {
        background: transparent !important;
        background-color: transparent !important;
    }

    /* Ensure all divs in modal body are transparent */
    #detailsModal .modal-body div {
        background: transparent !important;
        background-color: transparent !important;
    }

    /* Force override any Bootstrap or custom classes that might interfere */
    #detailsModal .modal-body .card,
    #detailsModal .modal-body .card-body,
    #detailsModal .modal-body .card-header {
        background: transparent !important;
        background-color: transparent !important;
        border: none !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #detailsModal .modal-body {
            padding: 1.5rem !important;
        }
        #detailsModal .modal-body h6 {
            font-size: 1rem;
        }
        #detailsModal .modal-body p {
            font-size: 0.9rem;
        }
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
#farmModal form {
  text-align: left;
}

#farmModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#farmModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#farmModal .form-control,
#farmModal select.form-control,
#farmModal textarea.form-control {
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
#farmModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#farmModal .form-control:focus {
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
#farmModal .modal-footer {
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

  #farmModal .form-control {
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
#detailsModal .modal-footer {
    text-align: center;
    border-top: 1px solid #e5e7eb;
    padding-top: 1.25rem;
    margin-top: 1.5rem;
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
            <i class="fas fa-university"></i>
            Farm Management
        </h1>
        <p>Monitor and manage registered farms, owners, and operational status</p>
    </div>

    <!-- Stats Cards -->
    <div class="row fade-in">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Active Farms</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeFarmsCount">0</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle fa-2x" style="color: #18375d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Inactive Farms</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="inactiveFarmsCount">0</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle fa-2x" style="color: #18375d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Farms</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalFarmsCount">0</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-university fa-2x" style="color: #18375d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Barangays Covered</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="barangayCount">0</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-map-marker-alt fa-2x" style="color: #18375d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Farm Management Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
            <h6 class="mb-0">
                <i class="fas fa-list"></i>
                Farm Directory
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
                    <input type="text" class="form-control" placeholder="Search farms..." id="farmSearch">
                </div>
                <div class="d-flex align-items-center justify-content-center flex-nowrap gap-2 action-toolbar">
                    <button class="btn-action btn-action-refresh" title="Refresh" onclick="refreshData()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" title="Tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="printTable()">
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
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Farm ID</th>
                            <th>Farm Name</th>
                            <th>Owner Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Barangay</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="farmsTableBody">
                        <!-- Farms will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modern Delete Task Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content smart-modal text-center p-4">
            <!-- Icon -->
            <div class="icon-wrapper mx-auto mb-4 text-danger">
                <i class="fas fa-times-circle fa-2x"></i>
            </div>
            <!-- Title -->
            <h5>Confirm Delete</h5>
            <!-- Description -->
            <p class="text-muted mb-4 px-3">
                Are you sure you want to delete this farm? This action <strong>cannot be undone</strong>.
            </p>

            <!-- Buttons -->
            <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="btn-modern btn-delete">
                    Yes, Delete
                </button>
            </div>
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
                    <i class="fas fa-university fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">Farm Details</h5>
                <p class="text-muted text-center mb-0 small">Below are the complete details of the selected farm.</p>
            </div>

      <!-- Body -->
      <div class="modal-body">
        <div id="farmDetails">
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

<!-- Smart Form Modal - Edit Farm -->
<div class="modal fade admin-modal" id="farmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content smart-form text-center p-4">

      <!-- Header -->
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle mb-3">
          <i class="fas fa-university fa-2x"></i>
        </div>
        <h5 class="fw-bold mb-1">Edit Farm</h5>
        <p class="text-muted mb-0 small">
          Update the farm details below.
        </p>
      </div>

      <!-- Form -->
      <form id="farmForm" onsubmit="saveFarm(event)">
        @csrf
        <input type="hidden" id="farmId" name="id">

        <div class="form-wrapper text-start mx-auto">
          <div class="row g-3">

            <!-- Farm Name -->
            <div class="col-md-6">
              <label for="farmName" class="fw-semibold">Farm Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="farmName" name="name" placeholder="Enter farm name" required>
            </div>

            <!-- Barangay -->
            <div class="col-md-6">
              <label for="farmBarangay" class="fw-semibold">Barangay</label>
              <select class="form-control" id="farmBarangay" name="barangay">
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

            <!-- Owner Name -->
            <div class="col-md-6">
              <label for="ownerName" class="fw-semibold">Owner Name</label>
              <input type="text" class="form-control" id="ownerName" name="owner_name" placeholder="Enter owner name">
            </div>

            <!-- Owner Email -->
            <div class="col-md-6">
              <label for="ownerEmail" class="fw-semibold">Owner Email</label>
              <input type="email" class="form-control" id="ownerEmail" name="owner_email" placeholder="Enter owner email">
            </div>

            <!-- Owner Phone -->
            <div class="col-md-6">
              <label for="ownerPhone" class="fw-semibold">Owner Phone</label>
              <input type="text" class="form-control" id="ownerPhone" name="owner_phone" placeholder="Enter owner phone">
            </div>

            <!-- Farm Status -->
            <div class="col-md-6">
              <label for="farmStatus" class="fw-semibold">Status <span class="text-danger">*</span></label>
              <select class="form-control" id="farmStatus" name="status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>

          </div>

          <!-- Notification -->
          <div id="farmFormNotification" class="mt-3 text-center" style="display: none;"></div>
        </div>

        <!-- Footer -->
        <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
          <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-modern btn-ok">
            <i class="fas fa-save"></i> Update Farm
          </button>
        </div>
      </form>

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
let farmsTable;
let farmToDelete = null;

$(document).ready(function () {
    // Initialize DataTables
    initializeDataTables();
    
    // Load data
    loadFarms();
    updateStats();

    // Custom search functionality
    $('#farmSearch').on('keyup', function() {
        farmsTable.search(this.value).draw();
    });
});

function initializeDataTables() {
    farmsTable = $('#dataTable').DataTable({
        dom: 'Bfrtip',
        searching: true,
        paging: true,
        info: true,
        ordering: true,
        lengthChange: false,
        pageLength: 10,
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Farms_Report',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Farms_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Farms Report',
                className: 'd-none'
            }
        ],
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No farms available</h5><p>There are no farms to display at this time.</p></div>'
        }
    });

    // Hide default DataTables elements
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
}

function loadFarms() {
    // Load farms from the database via AJAX
    $.ajax({
        url: '{{ route("superadmin.farms.list") }}',
        method: 'GET',
        success: function(response) {
            farmsTable.clear();
            const items = response.data || [];
            items.forEach(farm => {
                const rowData = [
                    `<a href="#" class="farm-id-link" onclick="openDetailsModal('${farm.id}')">${farm.farm_id}</a>`,
                    `${farm.name || 'N/A'}`,
                    `${farm.owner?.name || ''}`,
                    `${farm.owner?.email || ''}`,
                    `${farm.owner?.phone || ''}`,
                    `${farm.barangay || ''}`,
                    `<span class="badge badge-${farm.status === 'active' ? 'success' : 'danger'}">${farm.status}</span>`,
                    `<div class=\"btn-group\">\n\	\t\t\t\t\t<button class=\"btn-action btn-action-ok\" onclick=\"editFarm('${farm.id}')\" title=\"Edit\">\n\	\t\t\t\t\t\t<i class=\"fas fa-edit\"></i>\n\	\t\t\t\t\t\t<span>Edit</span>\n\	\t\t\t\t\t</button>\n\	\t\t\t\t\t<button class=\"btn-action btn-action-deletes\" onclick=\"confirmDelete('${farm.id}')\" title=\"Delete\">\n\	\t\t\t\t\t\t<i class=\"fas fa-trash\"></i>\n\	\t\t\t\t\t\t<span>Delete</span>\n\	\t\t\t\t\t</button>\n\	\t\t\t\t</div>`
                ];
                
                farmsTable.row.add(rowData).draw(false);
            });
        },
        error: function(xhr) {
            console.error('Error loading farms:', xhr);
        }
    });
}

function updateStats() {
    // Update stats via AJAX
    $.ajax({
        url: '{{ route("superadmin.farms.stats") }}',
        method: 'GET',
        success: function(response) {
            document.getElementById('activeFarmsCount').textContent = response.active;
            document.getElementById('inactiveFarmsCount').textContent = response.inactive;
            document.getElementById('totalFarmsCount').textContent = response.total;
            document.getElementById('barangayCount').textContent = response.barangays;
        },
        error: function(xhr) {
            console.error('Error loading stats:', xhr);
        }
    });
}

function openDetailsModal(farmId) {
    $.ajax({
        url: `{{ route("superadmin.farms.show", ":id") }}`.replace(':id', farmId),
        method: 'GET',
        success: function(response) {
            const farm = response.data;
            const details = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3" style="color: #18375d; font-weight: 600;"><i class="fas fa-tractor me-2"></i> Farm Information</h6>
                        <p><strong>Farm ID:</strong> ${farm.farm_id}</p>
                        <p><strong>Farm Name:</strong> ${farm.name || 'N/A'}</p>
                        <p><strong>Barangay:</strong> ${farm.barangay || ''}</p>
                        <p><strong>Status:</strong> <span class="badge badge-${farm.status === 'active' ? 'success' : 'danger'}">${farm.status}</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3" style="color: #18375d; font-weight: 600;"><i class="fas fa-id-card me-2"></i> Owner Information</h6>
                        <p><strong>Owner Name:</strong> ${farm.owner?.name || ''}</p>
                        <p><strong>Email:</strong> ${farm.owner?.email || ''}</p>
                        <p><strong>Contact Number:</strong> ${farm.owner?.phone || ''}</p>
                        <p><strong>Registration Date:</strong> ${new Date(farm.created_at).toLocaleDateString()}</p>
                    </div>
                </div>
                ${farm.description ? `
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Description</h6>
                        <p>${farm.description}</p>
                    </div>
                </div>
                ` : ''}
            `;

            document.getElementById('farmDetails').innerHTML = details;
            $('#detailsModal').modal('show');
        },
        error: function(xhr) {
            console.error('Error loading farm details:', xhr);
        }
    });
}

function toggleFarmStatus(farmId, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    
    $.ajax({
        url: `{{ route("superadmin.farms.update-status", ":id") }}`.replace(':id', farmId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            status: newStatus
        },
        success: function(response) {
            loadFarms();
            updateStats();
            showNotification(`Farm status updated to ${newStatus}`, 'success');
        },
        error: function(xhr) {
            showNotification('Error updating farm status', 'danger');
        }
    });
}

function editFarm(farmId) {
    $.ajax({
        url: `{{ route('superadmin.farms.show', ':id') }}`.replace(':id', farmId),
        method: 'GET',
        success: function(response) {
            const farm = response.data;
            // Populate Add Farm modal as Edit Farm
            $('#farmModalLabel').html('<i class="fas fa-university"></i> Edit Farm');
            $('#farmId').val(farm.id);
            $('#farmName').val(farm.name || '');
            $('#farmBarangay').val(farm.barangay || '');
            $('#ownerName').val(farm.owner?.name || '');
            $('#ownerEmail').val(farm.owner?.email || '');
            $('#ownerPhone').val(farm.owner?.phone || '');
            $('#farmStatus').val(farm.status || 'active');
            $('#farmModal').modal('show');
        },
        error: function() {
            showNotification('Error loading farm data', 'danger');
        }
    });
}

function updateActivity(selectElement, farmId) {
    const newStatus = selectElement.value;
    
    $.ajax({
        url: `{{ route("superadmin.farms.update-status", ":id") }}`.replace(':id', farmId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            status: newStatus
        },
        success: function(response) {
            updateStats();
            showNotification(`Farm status updated to ${newStatus}`, 'success');
        },
        error: function(xhr) {
            // Revert the select element if update fails
            selectElement.value = selectElement.value === 'active' ? 'inactive' : 'active';
            showNotification('Error updating farm status', 'danger');
        }
    });
}

function confirmDelete(farmId) {
    farmToDelete = farmId;
    $('#confirmDeleteModal').modal('show');
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (farmToDelete) {
        deleteFarm(farmToDelete);
        farmToDelete = null;
        $('#confirmDeleteModal').modal('hide');
    }
});

function deleteFarm(farmId) {
    $.ajax({
        url: `{{ route("superadmin.farms.destroy", ":id") }}`.replace(':id', farmId),
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            loadFarms();
            updateStats();
            showNotification('Farm deleted successfully!', 'success');
        },
        error: function(xhr) {
            showNotification('Error deleting farm', 'danger');
        }
    });
}

function importCSV(event) {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('csv_file', file);

    $.ajax({
        url: '{{ route("superadmin.farms.import") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            loadFarms();
            updateStats();
            showNotification(`Successfully imported ${response.imported} farms`, 'success');
        },
        error: function(xhr) {
            showNotification('Error importing CSV file', 'danger');
        }
    });

    // Reset file input
    event.target.value = '';
}

function exportCSV() {
    const tableData = farmsTable.data().toArray();
    const csvData = [];
    const headers = ['Farm ID', 'Farm Name', 'Owner Name', 'Email', 'Phone', 'Barangay', 'Status'];
    csvData.push(headers.join(','));
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
    link.setAttribute('download', 'Farms_Report.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function exportPDF() {
    try {
        if (typeof window.jspdf === 'undefined') {
    farmsTable.button('.buttons-pdf').trigger();
            return;
        }
        const tableData = farmsTable.data().toArray();
        const pdfData = [];
        const headers = ['Farm ID', 'Farm Name', 'Owner Name', 'Email', 'Phone', 'Barangay', 'Status'];
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
        doc.setFontSize(18);
        doc.text('SuperAdmin Farms Report', 14, 22);
        doc.setFontSize(12);
        doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 14, 32);
        doc.autoTable({
            head: [headers],
            body: pdfData,
            startY: 40,
            styles: { fontSize: 8, cellPadding: 2 },
            headStyles: { fillColor: [24, 55, 93], textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [245, 245, 245] }
        });
        doc.save('Farms_Report.pdf');
        showNotification('PDF exported successfully!', 'success');
    } catch (error) {
        console.error('Error generating PDF:', error);
        showNotification('Error generating PDF. Falling back to DataTables export.', 'warning');
        try { farmsTable.button('.buttons-pdf').trigger(); } catch {}
    }
}

function exportPNG() {
    const originalTable = document.getElementById('dataTable');
    const tempTable = originalTable.cloneNode(true);
    const headerRow = tempTable.querySelector('thead tr');
    if (headerRow) {
        const lastHeaderCell = headerRow.lastElementChild;
        if (lastHeaderCell) lastHeaderCell.remove();
    }
    const dataRows = tempTable.querySelectorAll('tbody tr');
    dataRows.forEach(row => {
        const lastDataCell = row.lastElementChild;
        if (lastDataCell) lastDataCell.remove();
    });
    tempTable.style.position = 'absolute';
    tempTable.style.left = '-9999px';
    tempTable.style.top = '-9999px';
    document.body.appendChild(tempTable);
    html2canvas(tempTable, { scale: 2, backgroundColor: '#ffffff', width: tempTable.offsetWidth, height: tempTable.offsetHeight })
        .then(canvas => {
            const link = document.createElement('a');
        link.download = 'Farms_Report.png';
            link.href = canvas.toDataURL('image/png');
        link.click();
            document.body.removeChild(tempTable);
        })
        .catch(error => {
            console.error('Error generating PNG:', error);
            if (document.body.contains(tempTable)) document.body.removeChild(tempTable);
            showNotification('Error generating PNG export', 'danger');
    });
}

function printTable() {
    try {
        const tableData = farmsTable.data().toArray();
        if (!tableData || tableData.length === 0) {
            showNotification('No data available to print', 'warning');
            return;
        }
        const originalContent = document.body.innerHTML;
        let printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #18375d; margin-bottom: 5px;">SuperAdmin Farms Report</h1>
                    <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                <table border="3" style="border-collapse: collapse; width: 100%; border: 3px solid #000;">
                    <thead>
                        <tr>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Farm ID</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Farm Name</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Owner Name</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Email</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Phone</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Barangay</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Status</th>
                        </tr>
                    </thead>
                    <tbody>`;
        tableData.forEach(row => {
            printContent += '<tr>';
            for (let i = 0; i < row.length - 1; i++) {
                let cellText = '';
                if (row[i]) {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = row[i];
                    cellText = tempDiv.textContent || tempDiv.innerText || '';
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
        if (typeof window.printElement === 'function') {
            const container = document.createElement('div');
            container.innerHTML = printContent;
            window.printElement(container);
        } else if (typeof window.openPrintWindow === 'function') {
            window.openPrintWindow(printContent, 'SuperAdmin Farms Report');
        } else {
            window.print();
        }
    } catch (error) {
        console.error('Error in print function:', error);
        showNotification('Error generating print. Please try again.', 'danger');
        try { farmsTable.button('.buttons-print').trigger(); } catch {}
    }
}

function refreshData() {
    const refreshBtn = $('button[onclick="refreshData()"]');
    const originalIcon = refreshBtn.html();
    refreshBtn.html('<i class="fas fa-spinner fa-spin"></i>Refreshing...');
    refreshBtn.prop('disabled', true);

    loadFarms();
    updateStats();

    setTimeout(() => {
        refreshBtn.html(originalIcon);
        refreshBtn.prop('disabled', false);
        showNotification('Farms data refreshed successfully', 'success');
    }, 1000);
}

function saveFarm(event) {
    event.preventDefault();

    const farmId = $('#farmId').val();
    // Creation disabled: require an existing farmId to proceed
    if (!farmId) {
        const container = document.getElementById('farmFormNotification');
        if (container) {
            container.innerHTML = `
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i>
                    Creating new farms via Manage Farms has been disabled. Please register the user first.
                </div>
            `;
            container.style.display = 'block';
        } else {
            if (typeof showNotification === 'function') {
                showNotification('Creating new farms via Manage Farms has been disabled.', 'warning');
            } else {
                alert('Creating new farms via Manage Farms has been disabled.');
            }
        }
        return false;
    }
    const url = farmId ? `{{ route("superadmin.farms.update", ":id") }}`.replace(':id', farmId) : '{{ route("superadmin.farms.store") }}';
    const formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        name: $('#farmName').val(),
        barangay: $('#farmBarangay').val(),
        owner_name: $('#ownerName').val(),
        owner_email: $('#ownerEmail').val(),
        owner_phone: $('#ownerPhone').val(),
        status: $('#farmStatus').val()
    };

    // Clear previous notifications
    const notif = document.getElementById('farmFormNotification');
    if (notif) { notif.style.display = 'none'; notif.innerHTML = ''; }

    $.ajax({
        url: url,
        method: farmId ? 'PUT' : 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        success: function(response) {
            $('#farmModal').modal('hide');
            loadFarms();
            updateStats();
            showNotification(farmId ? 'Farm updated successfully' : 'Farm created successfully', 'success');
        },
        error: function(xhr) {
            let message = 'Error saving farm. Please try again.';
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                // Build detailed validation list
                let list = '';
                Object.keys(errors).forEach(field => {
                    const first = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                    list += `\n• ${field}: ${first}`;
                });
                message = 'Please fix the following errors:' + list;
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }

            const container = document.getElementById('farmFormNotification');
            if (container) {
                container.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        ${message.replace(/\n/g, '<br>')}
                    </div>
                `;
                container.style.display = 'block';
            }
        }
    });
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
