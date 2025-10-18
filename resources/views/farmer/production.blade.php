@extends('layouts.app')
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
/* Consistent table alignment and header styling */
#productionTable,
#topProducersTable {
    width: 100% !important;
    min-width: 1280px;
}

#productionTable th,
#productionTable td,
#topProducersTable th,
#topProducersTable td {
    text-align: center;
    vertical-align: middle;
    padding: 0.75rem;
    border: 1px solid #dee2e6;
    white-space: normal;
}

#productionTable thead th,
#topProducersTable thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: bold;
    color: #495057;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
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
    #viewRecordModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #viewRecordModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #viewRecordModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #viewRecordModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #viewRecordModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #viewRecordModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #viewRecordModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
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
#addProductionModal form {
  text-align: left;
}

#addProductionModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#addProductionModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#addProductionModal .form-control,
#addProductionModal select.form-control,
#addProductionModal textarea.form-control {
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
#addProductionModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#addProductionModal .form-control:focus {
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
    .btn-action-refresh {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-refresh:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    .btn-action-history {
        background-color: #5a6268;
        border-color: #5a6268;
        color: white;
    }
    
    .btn-action-history:hover {
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
#productionTable th,
#productionTable td,
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
#productionTable thead th,
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
#productionTable thead th.sorting,
#productionTable thead th.sorting_asc,
#productionTable thead th.sorting_desc,
#activeFarmersTable thead th.sorting,
#activeFarmersTable thead th.sorting_asc,
#activeFarmersTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#productionTable thead th::after,
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
#productionTable thead th.sorting::after,
#productionTable thead th.sorting_asc::after,
#productionTable thead th.sorting_desc::after,
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
#productionTable td, 
#productionTable th {
    white-space: normal !important;  /* allow wrapping */
    vertical-align: middle;
}

/* Make sure action buttons don’t overflow */
#productionTable td .btn-group {
    display: flex;
    flex-wrap: wrap; /* buttons wrap if not enough space */
    gap: 0.25rem;    /* small gap between buttons */
}

#productionTable td .btn-action {
    flex: 1 1 auto; /* allow buttons to shrink/grow */
    min-width: 90px; /* prevent too tiny buttons */
    text-align: center;
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
@section('content')
<!-- Page Header -->
<div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
    <h1>
        <i class="fas fa-tasks"></i>
        Production Management
    </h1>
    <p>Track and manage your dairy production records</p>
</div>

<!-- Summary Cards -->
<div class="row fade-in mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Production (L)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalProduction) }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-users fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">This Month (L)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($monthlyProduction) }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-users fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Average Daily (L)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($averageDaily, 1) }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-users fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Quality Score</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($qualityScore, 1) }}/10</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-users fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
</div>

<!-- Production Charts -->
<div class="row mb-4">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
                <h6 class="m-0 font-weight-bold ">Monthly Production Trend</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="productionTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
                <h6 class="m-0 font-weight-bold  ">Quality Distribution</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="qualityDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Producers -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
                <h6 class="m-0 font-weight-bold  ">Top Producing Livestock</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="topProducersTable">
                        <thead class="thead-light">
                            <tr>
                                <th>Rank</th>
                                <th>Livestock</th>
                                <th>Total Production (L)</th>
                                <th>Average Daily (L)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productionStats['top_producers'] as $index => $producer)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $producer['livestock_name'] }}</td>
                                <td>{{ number_format($producer['total_production'], 1) }}</td>
                                <td>{{ number_format($producer['total_production'] / 30, 1) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">No production data available</td>
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

<!-- Production Table -->
<div class="card shadow fade-in-up">
    <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
        <h6 class="m-0 font-weight-bold">
            <i class="fas fa-table"></i>
            Production Records
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
                    <input type="text" id="productionSearch" class="form-control" placeholder="Search production records...">
                </div>
                <div class="d-flex align-items-center justify-content-center flex-nowrap gap-2 action-toolbar">
                    <button class="btn-action btn-action-ok" id="supplierSearch" title="Add Product" data-toggle="modal" data-target="#addProductionModal">
                        <i class="fas fa-plus"></i> Add Product
                    </button>
                    <button class="btn-action btn-action-refresh" title="Refresh" onclick="refreshProductionTable()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" title="Tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#historyModal">
                                <i class="fas fa-history"></i> History 
                            </a>
                            <a class="dropdown-item" href="#" onclick="printProductionTable()">
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
            <table class="table table-bordered table-hover" id="productionTable">
                <thead class="thead-light">
                    <tr>
                        <th>Date</th>
                        <th>Livestock</th>
                        <th>Milk Quantity (L)</th>
                        <th>Quality Score</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productionData as $record)
                    <tr data-record-id="{{ $record['id'] }}">
                        <td>{{ $record['production_date'] }}</td>
                        <td>{{ $record['livestock_name'] }} ({{ $record['livestock_tag'] }})</td>
                        <td>{{ number_format($record['milk_quantity'], 1) }}</td>
                        <td>
                            <span class="badge badge-{{ $record['milk_quality_score'] >= 8 ? 'success' : ($record['milk_quality_score'] >= 6 ? 'warning' : 'danger') }}">
                                {{ $record['milk_quality_score'] ?? 'N/A' }}/10
                            </span>
                        </td>
                        <td>{{ Str::limit($record['notes'] ?? 'No notes', 30) }}</td>
                        <td>
                            <div class="btn-group">
                            <button class="btn-action btn-action-ok" onclick="viewRecord({{ $record['id'] }})" title="View Details">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn-action btn-action-edits" onclick="editRecord({{ $record['id'] }})" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn-action btn-action-deletes" onclick="confirmDelete({{ $record['id'] }})" title="Delete">
                                <i class="fas fa-trash"></i> Delete
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
                        <td class="text-center text-muted">No production records available</td>
                        <td class="text-center text-muted">N/A</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ADD PRODUCTION RECORD MODAL -->
<div class="modal fade admin-modal" id="addProductionModal" tabindex="-1" role="dialog" aria-labelledby="addProductionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content smart-form text-center p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-plus fa-2x"></i>
                </div>
                <h5 id="addProductionModalLabel" class="fw-bold mb-1">Add Production Record</h5>
                <p id="addProductionModalDesc" class="text-muted mb-0 small">
                    Fill out the details below to record a new milk production entry.
                </p>
            </div>

            <!-- Form -->
            <form id="addProductionForm" action="{{ route('farmer.production.store') }}" method="POST">
                @csrf
                <div class="form-wrapper text-start mx-auto">
                    <div class="row">
                    <!-- Production Date -->
                    <div class="col-md-6 ">
                        <label for="production_date" class="fw-semibold">Production Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="production_date" name="production_date" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <!-- Livestock -->
                    <div class="col-md-6 ">
                        <label for="livestock_id" class="fw-semibold">Livestock <span class="text-danger">*</span></label>
                        <select class="form-control" id="livestock_id" name="livestock_id" required>
                            <option value="">Select Livestock</option>
                            @foreach($livestockList as $livestock)
                                <option value="{{ $livestock->id }}">{{ $livestock->name }} ({{ $livestock->tag_number }})</option>
                            @endforeach
                        </select>
                    </div>
                    

                    <!-- Milk Quantity -->
                    <div class="col-md-6 ">
                        <label for="milk_quantity" class="fw-semibold">Milk Quantity (L) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" class="form-control" id="milk_quantity" name="milk_quantity" required>
                    </div>

                    <!-- Quality Score -->
                    <div class="col-md-6 ">
                        <label for="milk_quality_score" class="fw-semibold">Quality Score (1-10)</label>
                        <select class="form-control" id="milk_quality_score" name="milk_quality_score">
                            <option value="">Select Quality</option>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Notes -->
                    <div class="col-md-12">
                        <label for="notes" class="fw-semibold">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional notes about this production record..."></textarea>
                    </div>

                    <div id="formNotification" class="mt-2" style="display: none;"></div>
                </div>
                </div>

                <!-- Footer Buttons -->
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveProductionBtn" class="btn-modern btn-ok" title="Save Record">
                        <i class="fas fa-save"></i> Save
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
            <h5>Confirm Delete</h5>

            <!-- Description -->
            <p class="text-muted mb-4 px-3">
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

<!-- PRODUCTION HISTORY MODAL (Smart Detail) -->
<div class="modal fade admin-modal" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-history fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1" id="historyModalLabel">Production History</h5>
                <p class="text-muted mb-0 small text-center">
                    View and export quarterly milk production records by year.
                </p>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div class="form-wrapper text-start mx-auto">
                    <div class="row g-3 mb-3 align-items-end">
                        <div class="col-md-6">
                            <label for="yearHistory" class="fw-semibold">Select Year:</label>
                            <select id="yearHistory" class="form-control" onchange="loadHistory()">
                                @php($currentYear = (int)date('Y'))
                                @for($y = $currentYear; $y >= $currentYear - 5; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6 text-md-end text-muted small">
                            Showing quarterly aggregates
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive rounded shadow-sm">
                        <table class="table table-hover table-bordered align-middle mb-0" id="historyQuarterTable">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Quarter</th>
                                    <th>Total Production (L)</th>
                                    <th>Average Quality</th>
                                    <th>Records</th>
                                </tr>
                            </thead>
                            <tbody id="historyTableBody">
                                <!-- Quarterly history will be dynamically populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
                <button type="button" class="btn-modern btn-ok" onclick="exportHistory()">
                    <i class="fas fa-file-export"></i> Export History
                </button>
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

<!-- Required libraries for PDF/Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- jsPDF and autoTable for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
let productionDT = null;
const CSRF_TOKEN = "{{ csrf_token() }}";
$(document).ready(function() {
    const productionStoreAction = $('#addProductionForm').attr('action');
    // Initialize DataTable for Production Records
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
            { extend: 'csvHtml5', title: 'Farmer_Production_Report', className: 'd-none', exportOptions: { columns: [0,1,2,3,4], modifier: { page: 'all' } } },
            { extend: 'pdfHtml5', title: 'Farmer_Production_Report', orientation: 'landscape', pageSize: 'Letter', className: 'd-none', exportOptions: { columns: [0,1,2,3,4], modifier: { page: 'all' } } },
            { extend: 'print', title: 'Farmer Production Report', className: 'd-none', exportOptions: { columns: [0,1,2,3,4], modifier: { page: 'all' } } }
        ],
        language: { search: "", emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No data available</h5><p>There are no records to display at this time.</p></div>' }
    };

    if ($('#productionTable').length) {
        try {
            productionDT = $('#productionTable').DataTable({
                ...commonConfig,
                order: [[0, 'desc']],
                columnDefs: [
                    { width: '120px', targets: 0 },
                    { width: '220px', targets: 1 },
                    { width: '140px', targets: 2 },
                    { width: '140px', targets: 3 },
                    { width: '260px', targets: 4 },
                    { width: '220px', targets: 5, orderable: false },
                    { targets: '_all', className: 'text-center align-middle' }
                ]
            });
            productionDT.columns.adjust();
        } catch (e) {
            console.error('Failed to initialize Production DataTable:', e);
        }
    }

    // Hide default DataTables search and buttons; wire custom search
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
    $('#productionSearch').on('keyup', function(){
        if (productionDT) productionDT.search(this.value).draw();
    });

    // AJAX submit for Add/Update Production form
    $('#addProductionForm').on('submit', function(e){
        try {
            e.preventDefault();
            const form = this;
            const btn = document.getElementById('saveProductionBtn');
            if (btn){ btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...'; }
            const actionUrl = form.getAttribute('action');
            const fd = new FormData(form);
            fetch(actionUrl, {
                method: 'POST', // Laravel will honor _method for PUT
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: fd
            }).then(async (r)=>{
                let data = null; try { data = await r.json(); } catch(_){}
                if (!r.ok || !data || data.success !== true){
                    throw new Error((data && data.message) || `HTTP ${r.status}`);
                }
                if (data.record){ upsertProductionRow(data.record); }
                $('#addProductionModal').modal('hide');
                showAlert('success', data.message || 'Record saved successfully!');
                // Reset for add mode
                form.reset();
                $('#production_date').val('{{ date('Y-m-d') }}');
            }).catch(err=>{
                console.error('save production error:', err);
                showInlineFormError('Failed to save record. Please check your inputs and try again.');
            }).finally(()=>{
                if (btn){ btn.disabled = false; btn.innerHTML = 'Save Record'; }
            });
        } catch(e){ console.error('submit handler error:', e); }
    });

    // Reset form to create mode when modal closes
    $('#addProductionModal').on('hidden.bs.modal', function(){
        resetProductionFormMode();
        $('#addProductionForm')[0].reset();
        $('#production_date').val('{{ date('Y-m-d') }}');
    });

    // Load history whenever the modal opens
    $('#historyModal').on('shown.bs.modal', function(){
        try { loadHistory(); } catch(e){ console.error('loadHistory error:', e); }
    });

    function resetProductionFormMode(){
        $('#addProductionForm').attr('action', productionStoreAction);
        $('#addProductionForm').find('input[name="_method"]').remove();
        $('#addProductionModalLabel').text('Add Production Record');
        $('#addProductionModalDesc').text('Fill out the details below to record a new milk production entry.');
        $('#saveProductionBtn').html('<i class="fas fa-save"></i> Save Record');
    }

    // Production Trend Chart
    const productionTrendCtx = document.getElementById('productionTrendChart').getContext('2d');
    new Chart(productionTrendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(($productionStats['monthly_trend'] ?? collect())->pluck('month')) !!},
            datasets: [{
                label: 'Production (L)',
                data: {!! json_encode(($productionStats['monthly_trend'] ?? collect())->pluck('production')) !!},
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
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
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + ' L';
                        }
                    }
                }
            }
        }
    });

    // Quality Distribution Chart
    const qualityDistributionCtx = document.getElementById('qualityDistributionChart').getContext('2d');
    new Chart(qualityDistributionCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(($productionStats['quality_distribution'] ?? collect())->pluck('score')->map(function($score) { return 'Score ' . $score; })) !!},
            datasets: [{
                data: {!! json_encode(($productionStats['quality_distribution'] ?? collect())->pluck('count')) !!},
                backgroundColor: [
                    '#e74a3b', '#f6c23e', '#1cc88a', '#36b9cc', '#4e73df',
                    '#6f42c1', '#fd7e14', '#20c9a6', '#5a5c69', '#858796'
                ],
                hoverBackgroundColor: [
                    '#e74a3b', '#f6c23e', '#1cc88a', '#36b9cc', '#4e73df',
                    '#6f42c1', '#fd7e14', '#20c9a6', '#5a5c69', '#858796'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});

// Helpers
function showInlineFormError(message){
    const n = document.getElementById('formNotification');
    if (!n) return;
    n.className = 'alert alert-danger';
    n.textContent = message || 'An error occurred.';
    n.style.display = 'block';
}

function htmlEscape(s){
    return (s==null? '': String(s)).replace(/[&<>"']/g, function(c){
        return {"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#39;"}[c];
    });
}

function truncateText(s, n){
    s = s || '';
    return s.length > n ? s.slice(0, n-1) + '…' : s;
}

function getProductionRowByRecordId(id){
    // Prefer data attribute if present
    let tr = document.querySelector(`#productionTable tbody tr[data-record-id="${id}"]`);
    if (tr) return tr;
    // Fallback: find by edit button onclick
    const btns = document.querySelectorAll('#productionTable tbody button');
    for (const b of btns){
        const on = (b.getAttribute('onclick')||'').replace(/\s+/g,'');
        if (on === `editRecord(${id})` || on.indexOf(`editRecord(${id})`) !== -1){
            const row = b.closest('tr');
            if (row) return row;
        }
    }
    return null;
}

function buildProductionRowCells(record){
    const date = htmlEscape(record.production_date || '');
    const livestock = record.livestock_name ? `${htmlEscape(record.livestock_name)}${record.livestock_tag? ' ('+htmlEscape(record.livestock_tag)+')':''}` : (record.livestock_id? ('ID '+record.livestock_id):'');
    const qty = (record.milk_quantity!=null) ? Number(record.milk_quantity).toFixed(1) : '';
    const score = record.milk_quality_score!=null ? Number(record.milk_quality_score) : null;
    const scoreBadge = score!=null ? `<span class="badge badge-${score>=8?'success':(score>=6?'warning':'danger')}">${score}/10</span>` : 'N/A';
    const notes = truncateText(String(record.notes||'No notes'), 30);
    const actions = `
        <div class="btn-group">
            <button class="btn-action btn-action-ok" onclick="viewRecord(${record.id})" title="View Details"><i class="fas fa-eye"></i></button>
            <button class="btn-action btn-action-edits" onclick="editRecord(${record.id})" title="Edit"><i class="fas fa-edit"></i></button>
            <button class="btn-action btn-action-deletes" onclick="confirmDelete(${record.id})" title="Delete"><i class="fas fa-trash"></i></button>
        </div>`;
    return [date, livestock, qty, scoreBadge, htmlEscape(notes), actions];
}

function upsertProductionRow(record){
    try {
        const cells = buildProductionRowCells(record);
        const tr = getProductionRowByRecordId(record.id);
        if (productionDT){
            if (tr){ productionDT.row(tr).data(cells).draw(false); }
            else {
                const node = productionDT.row.add(cells).draw(false).node();
                if (node) node.setAttribute('data-record-id', record.id);
            }
        }
    } catch(e){ console.error('upsertProductionRow error:', e); }
}

function confirmDelete(recordId) {
    try {
        // Enhance modal text with context
        const tr = getProductionRowByRecordId(recordId);
        const dateText = tr && tr.cells[0] ? (tr.cells[0].innerText||'').trim() : '';
        const livestockText = tr && tr.cells[1] ? (tr.cells[1].innerText||'').trim() : '';
        const titleEl = $('#confirmDeleteModal').find('h5');
        const descEl = $('#confirmDeleteModal').find('p');
        if (titleEl.length) titleEl.text('Confirm Delete');
        if (descEl.length) descEl.html(`Are you sure you want to delete the record for <strong>${htmlEscape(livestockText) || 'this livestock'}</strong>${dateText? ' on <strong>'+htmlEscape(dateText)+'</strong>':''}? This action <strong>cannot be undone</strong>.`);
        $('#confirmDeleteModal').modal('show');
        $('#confirmDeleteBtn').off('click').on('click', function(){
            const btn = this; btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
            deleteRecord(recordId).always(function(){ btn.disabled = false; btn.innerHTML = 'Yes, Delete'; });
        });
    } catch(e){ console.error('confirmDelete error:', e); $('#confirmDeleteModal').modal('show'); }
}

function loadHistory() {
    const year = document.getElementById('yearHistory').value;
    // Fetch quarterly aggregates
    fetch(`/farmer/production/history?mode=quarterly&year=${year}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.mode === 'quarterly') {
                const tbody = document.getElementById('historyTableBody');
                tbody.innerHTML = '';
                const quarters = Array.isArray(data.quarters) ? data.quarters : [];
                if (quarters.length) {
                    quarters.forEach(q => {
                        const label = `Q${q.quarter} ${q.year}`;
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${label}</td>
                            <td>${Number(q.total_production).toLocaleString(undefined, { maximumFractionDigits: 1 })}</td>
                            <td>${q.avg_quality != null ? (Number(q.avg_quality).toFixed(1) + '/10') : 'N/A'}</td>
                            <td>${q.records}</td>
                        `;
                        tbody.appendChild(row);
                    });
                } else {
                    const row = document.createElement('tr');
                    row.innerHTML = '<td colspan="4" class="text-center text-muted">No quarterly data for the selected year.</td>';
                    tbody.appendChild(row);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Failed to load history data.');
        });
}

function exportCSV() {
    try {
        if (productionDT) { productionDT.button('.buttons-csv').trigger(); }
        else { showAlert('danger', 'Table is not ready.'); }
    } catch (e) { console.error('CSV export error:', e); showAlert('danger', 'Error generating CSV.'); }
}

function exportPDF() {
    try {
        if (productionDT) { productionDT.button('.buttons-pdf').trigger(); }
        else { showAlert('danger', 'Table is not ready.'); }
    } catch (error) { console.error('Error generating PDF:', error); showAlert('danger', 'Error generating PDF.'); }
}

function viewRecord(recordId) {
    // Load and display production record details
    $.ajax({
        url: `/farmer/production/${recordId}`,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            const record = response && response.success ? response.record : response;
            if (record) {
                const modalHtml = `
                    <!-- PRODUCTION RECORD DETAILS MODAL (Smart Detail) -->
<div class="modal fade admin-modal" id="viewRecordModal" tabindex="-1" role="dialog" aria-labelledby="viewRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-eye fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1" id="viewRecordModalLabel">Production Record Details</h5>
                <p class="text-muted mb-0 small text-center">
                    Review detailed information about this milk production record.
                </p>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div class="text-start mx-auto">
                    <div class="row">
                        <div class="col-md-6">
    <h6 class="mb-3" style="color: #18375d; font-weight: 600;">
        <i class="fas fa-tint me-2"></i> Production Information
    </h6>
    <p><strong>Date:</strong> ${record.production_date || ''}</p>
    <p><strong>Livestock:</strong> ${record.livestock_name ? `${record.livestock_name} (${record.livestock_tag || ''})` : `ID ${record.livestock_id || ''}`}</p>
    <p><strong>Quantity:</strong> ${record.milk_quantity} L</p>
    <p><strong>Quality Score:</strong> ${record.milk_quality_score}/10</p>
</div>

<div class="col-md-6">
    <h6 class="mb-3" style="color: #18375d; font-weight: 600;">
        <i class="fas fa-sticky-note me-2"></i> Additional Details
    </h6>
    <p><strong>Farm:</strong> ${record.farm_name || ''}</p>
    <p><strong>Notes:</strong> ${record.notes || 'No notes available.'}</p>
</div>

                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer justify-content-center mt-4">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

                `;
                
                $('#viewRecordModal').remove();
                $('body').append(modalHtml);
                $('#viewRecordModal').modal('show');
            }
        },
        error: function() {
            showAlert('danger', 'Failed to load record details.');
        }
    });
}

function editRecord(recordId) {
    // Load record data for editing
    $.ajax({
        url: `/farmer/production/${recordId}`,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            const record = response && response.success ? response.record : response;
            if (record) {
                // Populate the add production modal with existing data
                $('#addProductionModal').modal('show');
                // Set date string directly to avoid timezone shifts
                $('#production_date').val(record.production_date || '');
                // Ensure option exists in select even if livestock inactive
                if (record.livestock_id) {
                    const sel = $('#livestock_id');
                    if (!sel.find(`option[value="${record.livestock_id}"]`).length) {
                        const label = (record.livestock_name || 'Livestock') + (record.livestock_tag ? ` (${record.livestock_tag})` : '');
                        sel.append(`<option value="${record.livestock_id}">${label}</option>`);
                    }
                    sel.val(record.livestock_id).trigger('change');
                } else {
                    $('#livestock_id').val('').trigger('change');
                }
                $('#milk_quantity').val(record.milk_quantity || '');
                $('#milk_quality_score').val(record.milk_quality_score || '').trigger('change');
                $('#notes').val(record.notes || '');

                // Change form action to update with method spoofing
                $('#addProductionForm').attr('action', `/farmer/production/${recordId}`);
                const methodInput = $('#addProductionForm').find('input[name="_method"]');
                if (methodInput.length) { methodInput.val('PUT'); }
                else { $('#addProductionForm').prepend('<input type="hidden" name="_method" value="PUT">'); }
                $('#addProductionModalLabel').text('Edit Production Record');
                $('#addProductionModalDesc').text('Fill out the details below to record a new milk production entry.');
                $('#saveProductionBtn').html('<i class="fas fa-save"></i> Save');
            }
        },
        error: function() {
            showAlert('danger', 'Failed to load record for editing.');
        }
    });
}

// Confirm Delete function (duplicate guard)
function confirmDelete(recordId) {
    try {
        const tr = getProductionRowByRecordId(recordId);
        const dateText = tr && tr.cells[0] ? (tr.cells[0].innerText||'').trim() : '';
        const livestockText = tr && tr.cells[1] ? (tr.cells[1].innerText||'').trim() : '';
        const titleEl = $('#confirmDeleteModal').find('h5');
        const descEl = $('#confirmDeleteModal').find('p');
        if (titleEl.length) titleEl.text('Confirm Delete');
        if (descEl.length) descEl.html(`Are you sure you want to delete the record for <strong>${htmlEscape(livestockText) || 'this livestock'}</strong>${dateText? ' on <strong>'+htmlEscape(dateText)+'</strong>':''}? This action <strong>cannot be undone</strong>.`);
        $('#confirmDeleteModal').modal('show');
        $('#confirmDeleteBtn').off('click').on('click', function(){
            const btn = this; btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
            deleteRecord(recordId).always(function(){ btn.disabled = false; btn.innerHTML = 'Yes, Delete'; });
        });
    } catch(e){ console.error('confirmDelete error:', e); $('#confirmDeleteModal').modal('show'); }
}

function deleteRecord(recordId) {
    return $.ajax({
        url: `/farmer/production/${recordId}`,
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
    }).done(function(response){
        if (response && response.success){
            const tr = getProductionRowByRecordId(recordId);
            if (tr && productionDT){ productionDT.row(tr).remove().draw(false); }
            $('#confirmDeleteModal').modal('hide');
            showAlert('success', 'Production record deleted successfully!');
        } else {
            showAlert('danger', 'Failed to delete record.');
        }
    }).fail(function(){
        showAlert('danger', 'Failed to delete record.');
    });
}

function exportPNG() {
    // Create a temporary table without the Actions column for export
    const originalTable = document.getElementById('productionTable');
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
    
    // Place temp table inside an offscreen container so layout computes size
    const offscreen = document.createElement('div');
    offscreen.style.position = 'absolute';
    offscreen.style.left = '-9999px';
    offscreen.style.top = '0';
    offscreen.style.background = '#ffffff';
    offscreen.appendChild(tempTable);
    document.body.appendChild(offscreen);
    // Match width to original table for proper rendering
    tempTable.style.width = originalTable.offsetWidth + 'px';
    
    // Generate PNG using html2canvas
    html2canvas(tempTable, {
        scale: 2,
        backgroundColor: '#ffffff',
        useCORS: true,
        logging: false,
        windowWidth: tempTable.scrollWidth,
        windowHeight: tempTable.scrollHeight
    }).then(canvas => {
        // Create download link
        const link = document.createElement('a');
        link.download = `Farmer_ProductionReport_${Date.now()}.png`;
        link.href = canvas.toDataURL("image/png");
        link.click();
        
        // Clean up - remove temporary container
        document.body.removeChild(offscreen);
        
        showAlert('success', 'PNG exported successfully!');
    }).catch(error => {
        console.error('Error generating PNG:', error);
        // Clean up on error
        if (document.body.contains(offscreen)) {
            document.body.removeChild(offscreen);
        }
        showAlert('danger', 'Error generating PNG export');
    });
}

function printProductionTable() {
    try { window.printElement('#productionTable'); }
    catch(e){ console.error('printProductionTable error:', e); window.print(); }
}

function refreshProductionTable(){
    const btn = document.querySelector('.btn-action-refresh');
    if (btn){ btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...'; }
    sessionStorage.setItem('showRefreshNotificationProduction','true');
    setTimeout(()=>location.reload(), 800);
}

$(document).ready(function(){
    if (sessionStorage.getItem('showRefreshNotificationProduction') === 'true'){
        sessionStorage.removeItem('showRefreshNotificationProduction');
        setTimeout(()=>showAlert('success', 'Data refreshed successfully!'), 400);
    }
});

function exportHistory() {
    // Export quarterly table to CSV
    try {
        const year = document.getElementById('yearHistory').value;
        const rows = [];
        rows.push(['Quarter','Total Production (L)','Average Quality','Records'].join(','));
        document.querySelectorAll('#historyQuarterTable tbody tr').forEach(tr => {
            const cells = Array.from(tr.querySelectorAll('td')).map(td => (td.textContent||'').trim());
            if (cells.length === 4) rows.push(cells.join(','));
        });
        const blob = new Blob([rows.join('\n')], { type: 'text/csv;charset=utf-8;' });
        const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = `Production_Quarterly_${year}.csv`; a.click();
        showAlert('success', 'Quarterly history exported successfully!');
    } catch(e) { console.error('exportHistory error:', e); showAlert('danger', 'Failed to export history.'); }
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
        </button>
    `;
    
    document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.page-header'));
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
@endpush
