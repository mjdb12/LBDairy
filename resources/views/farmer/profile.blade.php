@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers-Profile')

@push('styles')
<style>
     /* User Details Modal Styling */
    #editProfileModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #editProfileModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #editProfileModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #editProfileModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #editProfileModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #editProfileModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #editProfileModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #editProfileModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }

    #changePasswordModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #changePasswordModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #changePasswordModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #changePasswordModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #changePasswordModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #changePasswordModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #changePasswordModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #changePasswordModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }

    /* Apply consistent buttons */
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
      .btn-action-edit-profile {
    background-color: white !important;
    border: 1px solid #18375d !important;
    color: #18375d !important;/* blue text */
}

  .btn-action-edit-profile:hover {
    background-color: #18375d !important;/* yellow on hover */
    border: 1px solid #18375d !important;
    color: white !important;
}
     .btn-action-edit-pass {
    background-color: white !important;
    border: 1px solid #fca700 !important;
    color: #fca700 !important;/* blue text */
}

  .btn-action-edit-pass:hover {
    background-color: #fca700 !important;/* yellow on hover */
    border: 1px solid #fca700 !important;
    color: white !important;
}
    

    /* Custom styles for admin profile */
    
    /* Profile Picture Enhancement */
    .profile-picture-container {
        position: relative;
        display: inline-block;
    }

    .img-profile {
        border: 6px solid white;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .img-profile:hover {
        transform: scale(1.08);
        box-shadow: 0 1.5rem 4rem rgba(24, 55, 93, 0.25);
    }

    /* Profile Card Thick Blue Border */
    .profile-card-bordered {
        background: #18375d;
        border-radius: 18px;
        padding: 18px;
        box-shadow: 0 4px 32px rgba(24, 55, 93, 0.10);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .profile-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        width: 100%;
        max-width: 420px;
        margin: 0 auto;
    }

    /* Card Headers */
    .card-header {
        background: linear-gradient(135deg, #18375d 0%, #122a47 100%);
        color: white;
        border-bottom: none;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h6 {
        margin: 0;
        font-weight: 600;
        font-size: 1rem;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    /* -------------------------
   Profile Card (Left Column)
-------------------------- */
.profile-card {
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
    border: 1px solid #e3e6f0;
}

.profile-header {
    background: #ffffff;
    padding: 1.5rem 1rem;
}

.img-profile {
    width: 160px;
    height: 160px;
    object-fit: cover;
    border: 4px solid #18375d;
    transition: transform 0.2s ease;
}

.img-profile:hover {
    transform: scale(1.05);
}

/* Profile Picture Container */
.profile-picture-container {
    display: inline-block;
}

/* Camera Button Below Profile Picture */
.btn-action-ok {
    background: #18375d;
    color: #fff;
    border-radius: 50%; /* perfect circle */
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.25);
    transition: all 0.2s ease-in-out;
    border: none;
    margin: 0 auto;
}

.btn-action-ok:hover {
    background: #fca700;
    transform: scale(1.1); /* subtle pop effect */
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}


/* Profile Actions */
.profile-actions .btn {
    margin: 0 0.25rem;
    font-size: 0.85rem;
    border-radius: 6px;
    transition: 0.2s;
}

.profile-actions .btn-outline-primary:hover {
    background: #18375d;
    color: #fff;
}

.profile-actions .btn-outline-secondary:hover {
    background: #18375d;
    color: #fff;
}

/* -------------------------
   Profile Info Table (Right Column)
-------------------------- */
.profile-info-table {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}

.profile-info-table th {
    color: #18375d;
    font-weight: 600;
    width: 200px;
    font-size: 0.9rem;
    padding: 1rem 0.75rem;
    border-bottom: 1px solid #e3e6f0;
    vertical-align: middle;
    white-space: nowrap;
}

.profile-info-table th i {
    margin-right: 0.5rem;
    width: 18px;
    text-align: center;
}

.profile-info-table td {
    color: #5a5c69;
    font-weight: 500;
    font-size: 0.95rem;
    padding: 1rem 0.75rem;
    border-bottom: 1px solid #e3e6f0;
    vertical-align: middle;
}

.profile-info-table tbody tr:hover {
    background-color: #f8f9fc;
    transition: background-color 0.2s ease;
}

.profile-info-table tbody tr:last-child td,
.profile-info-table tbody tr:last-child th {
    border-bottom: none;
}

/* Empty cell fallback */
.profile-info-table td:empty::after {
    content: "Not provided";
    color: #858796;
    font-style: italic;
}


.btn-action:hover {
    opacity: 0.85;
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
#editProfileModal form {
  text-align: left;
}

#editProfileModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#editProfileModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#editProfileModal .form-control,
#editProfileModal select.form-control,
#editProfileModal textarea.form-control {
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
#editProfileModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#editProfileModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}


#changePasswordModal form {
  text-align: left;
}

#changePasswordModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#changePasswordModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#changePasswordModal .form-control,
#changePasswordModal select.form-control,
#changePasswordModal textarea.form-control {
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
#changePasswordModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#changePasswordModal .form-control:focus {
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
</style>
@endpush

@section('content')
<!-- Success/Error messages handled by global top-right toast -->

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle mr-2"></i>
    <strong>Please fix the following errors:</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<!-- Page Header -->
<div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
    <h1>
        <i class="fas fa-user-circle"></i>
        Farmer Profile
    </h1>
    <p>Manage your personal information and farm details</p>
</div>
<!-- Statistics Grid -->
    <div class="row fade-in">
        <!-- Total Livestock -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Livestock</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ auth()->user()->livestock->count() }}</div>
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
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Milk Production</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\ProductionRecord::where('farm_id', auth()->user()->farms->first()->id ?? 0)->whereMonth('production_date', now()->month)->sum('milk_quantity') }} <span class="text-xs">L/month</span></div>
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
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Sales This Month</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format(\App\Models\Sale::where('farm_id', auth()->user()->farms->first()->id ?? 0)->whereMonth('sale_date', now()->month)->sum('total_amount'), 0) }}</div>
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
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Account Age</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \Carbon\Carbon::parse(auth()->user()->created_at)->diffForHumans() }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-tint fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Profile Section -->
<div class="row fade-in">
    <!-- Profile Card -->
    <div class="col-12 col-md-5 col-lg-4 mb-4">
        <div class="card shadow profile-card h-100">
            <!-- Profile Header -->
            <div class="profile-header text-center p-4">
                <div class="profile-picture-container d-inline-block">
                    <img id="profilePicture" 
                        src="{{ asset('img/' . (auth()->user()->profile_image ?? 'ronaldo.png')) }}?t={{ time() }}" 
                        alt="Profile Picture" 
                        class="img-profile rounded-circle shadow">
                </div>
                
                <!-- Camera Button Below Profile Picture -->
                <div class="mt-3">
                    <button class="btn-action btn-action-ok" onclick="document.getElementById('uploadProfilePicture').click()">
                        <i class="fas fa-camera"></i>
                    </button>
                    <input type="file" id="uploadProfilePicture" accept="image/*" style="display:none;" onchange="changeProfilePicture(event)">
                </div>

                <h3 class="mt-3 mb-1 font-weight-bold">{{ auth()->user()->name }}</h3>
                <p class="text-muted mb-2">{{ auth()->user()->email }}</p>
                <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn-action btn-action-edit-profile" data-toggle="modal" data-target="#editProfileModal" title="Edit Profile">
                    <i class="fas fa-edit mr-1"></i>Edit Profile
                </button>
                <button class="btn-action btn-action-edit-pass" data-toggle="modal" data-target="#changePasswordModal" title="Change Password">
                    <i class="fas fa-key mr-2"></i>Change Password
                </button>
            </div>
            </div>
        </div>
    </div>

    <!-- Profile Details -->
    <div class="col-12 col-md-7 col-lg-8 mb-4">
        <div class="card shadow h-100">
            <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start">
                <h6>
                    <i class="fas fa-user-edit"></i>
                    Profile Details
                </h6>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0 profile-info-table">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <i class="fas fa-user" style="color: #18375d;"></i>Full Name
                                </th>
                                <td>{{ auth()->user()->name ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <i class="fas fa-envelope" style="color: #18375d;"></i>Email
                                </th>
                                <td>{{ auth()->user()->email ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <i class="fas fa-phone" style="color: #18375d;"></i>Phone
                                </th>
                                <td>{{ auth()->user()->phone ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <i class="fas fa-seedling" style="color: #18375d;"></i>Farm Name
                                </th>
                                <td>{{ auth()->user()->farms->first()->name ?? 'No farm registered' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <i class="fas fa-map-marker-alt" style="color: #18375d;"></i>Address
                                </th>
                                <td>{{ auth()->user()->address ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <i class="fas fa-calendar-alt" style="color: #18375d;"></i>Member Since
                                </th>
                                <td>{{ auth()->user()->created_at->format('F Y') }}</td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <i class="fas fa-check-circle" style="color: #18375d;"></i>Status
                                </th>
                                <td><span class="badge badge-success">{{ ucfirst(auth()->user()->status ?? 'Active') }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade admin-modal" id="editProfileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content smart-form text-center p-4">
      <!-- Header -->
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle">
          <i class="fas fa-user-edit fa-2x"></i>
        </div>
        <h5 class="fw-bold mb-1">Edit Profile</h5>
        <p class="text-muted mb-0 small">
          Update your personal and farm details below.
        </p>
      </div>

      <!-- Form -->
      <form method="POST" action="{{ route('farmer.profile.update') }}" id="editProfileForm">
        @csrf
        @method('PUT')

        <div class="form-wrapper text-start mx-auto">
            <div class="row g-3">
            
            <!-- Full Name -->
            <div class="col-md-12">
              <label for="editFullName" class="fw-semibold">
                Full Name <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control @error('name') is-invalid @enderror"
                     id="editFullName" name="name"
                     value="{{ old('name', auth()->user()->name ?? '') }}"
                     required placeholder="Enter your full name">
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Email -->
            <div class="col-md-6">
              <label for="editEmail" class="fw-semibold">
                Email <span class="text-danger">*</span>
              </label>
              <input type="email" class="form-control @error('email') is-invalid @enderror"
                     id="editEmail" name="email"
                     value="{{ old('email', auth()->user()->email ?? '') }}"
                     required placeholder="Enter your email address">
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Phone -->
            <div class="col-md-6">
              <label for="editPhone" class="fw-semibold">
                Contact Number <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control @error('phone') is-invalid @enderror"
                     id="editPhone" name="phone"
                     value="{{ old('phone', auth()->user()->phone ?? '') }}"
                     placeholder="Enter your contact number">
              @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Farm Name -->
            <div class="col-md-6">
              <label for="editFarmName" class="fw-semibold">
                Farm Name <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control @error('farm_name') is-invalid @enderror"
                     id="editFarmName" name="farm_name"
                     value="{{ old('farm_name', auth()->user()->farms->first()->name ?? '') }}"
                     placeholder="Enter your farm name">
              @error('farm_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Address -->
            <div class="col-md-6">
              <label for="editAddress" class="fw-semibold">
                Address <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control @error('address') is-invalid @enderror"
                     id="editAddress" name="address"
                     value="{{ old('address', auth()->user()->address ?? '') }}"
                     placeholder="Enter your address">
              @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            </div>
        </div>

        <!-- Footer Buttons -->
        <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
          <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">
            Cancel
          </button>
          <button type="submit" class="btn-modern btn-ok">
            <i class="fas fa-save"></i> Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Change Password Modal -->
<div class="modal fade admin-modal" id="changePasswordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content smart-form text-center p-4">
      
      <!-- Header -->
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle">
          <i class="fas fa-key fa-2x"></i>
        </div>
        <h5 class="fw-bold mb-1">Change Password</h5>
        <p class="text-muted mb-0 small">
          Update your account password below.
        </p>
      </div>

      <!-- Form -->
      <form method="POST" action="{{ route('farmer.profile.password') }}" id="changePasswordForm">
        @csrf
        @method('PUT')

        <div class="form-wrapper text-start mx-auto">
            
            <!-- Current Password -->
            <div class="col-md-12">
              <label for="currentPassword" class="fw-semibold">
                Current Password <span class="text-danger">*</span>
              </label>
              <input type="password"
                     class="form-control @error('current_password') is-invalid @enderror"
                     id="currentPassword" name="current_password"
                     required placeholder="Enter your current password">
              @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- New Password -->
            <div class="col-md-12">
              <label for="newPassword" class="fw-semibold">
                New Password <span class="text-danger">*</span>
              </label>
              <input type="password"
                     class="form-control @error('password') is-invalid @enderror"
                     id="newPassword" name="password"
                     required placeholder="Enter your new password">
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Confirm New Password -->
            <div class="col-md-12">
              <label for="confirmPassword" class="fw-semibold">
                Confirm New Password <span class="text-danger">*</span>
              </label>
              <input type="password"
                     class="form-control"
                     id="confirmPassword" name="password_confirmation"
                     required placeholder="Re-enter new password">
            </div>

        </div>

        <!-- Footer Buttons -->
        <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
          <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">
            Cancel
          </button>
          <button type="submit" class="btn-modern btn-ok">
            <i class="fas fa-save"></i> Change Password
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

@endsection


@push('scripts')
<script>
    
    function changeProfilePicture(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Show loading state
        const button = event.target.previousElementSibling;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;

        // Create FormData
        const formData = new FormData();
        formData.append('profile_picture', file);
        formData.append('_token', '{{ csrf_token() }}');

        // Upload via AJAX
        fetch('{{ route("farmer.profile.picture") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the profile picture with cache busting
                document.getElementById('profilePicture').src = data.image_url + '?t=' + new Date().getTime();
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error uploading profile picture', 'danger');
        })
        .finally(() => {
            // Reset button state
            button.innerHTML = originalText;
            button.disabled = false;
            // Clear the file input
            event.target.value = '';
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

   // Handle Edit Profile form submission
document.querySelector('#editProfileForm').addEventListener('submit', function (e) {
    const fullName = document.getElementById('editFullName').value.trim();
    const email = document.getElementById('editEmail').value.trim();
    const phone = document.getElementById('editPhone').value.trim();

    // Simple front-end validation
    if (!fullName || !email || !phone) {
        e.preventDefault();
        showNotification('Please fill in all required fields before saving.', 'warning');
        return;
    }

    // Removed transient info toast to avoid intrusive alerts during submit
});

// Handle Change Password form submission
document.querySelector('#changePasswordForm').addEventListener('submit', function (e) {
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    // Basic front-end validation
    if (!currentPassword || !newPassword || !confirmPassword) {
        e.preventDefault();
        showNotification('Please fill out all password fields.', 'warning');
        return;
    }

    if (newPassword !== confirmPassword) {
        e.preventDefault();
        showNotification('Passwords do not match!', 'danger');
        return;
    }

    if (newPassword.length < 8) {
        e.preventDefault();
        showNotification('Password must be at least 8 characters long.', 'warning');
        return;
    }

    // Removed transient info toast to avoid intrusive alerts during submit
});

</script>
@endpush
