@extends('layouts.app')
@push('styles')
<style>

.stat-card {
    border-radius: 10px;
    overflow: hidden;
}

.stat-card .card-body {
    padding: 1.5rem;
}

.stat-card .icon {
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.stat-card:hover .icon {
    opacity: 1;
}

    /* User Details Modal Styling */
    #taskModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #taskModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #taskModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #taskModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #taskModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #taskModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #taskModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #taskModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }

.dashboard-card {
    transition: transform 0.2s ease-in-out;
    background: #fff !important;
}

/* Recent System Activity Table Badge Colors */
html body .card .table .badge-danger,
html body .card .table .badge-warning,
html body .card .table .badge-info,
.card .table .badge-danger,
.card .table .badge-warning,
.card .table .badge-info {
    background-color: #fca700 !important;
    color: #fff !important;
    border-radius: 0.35rem !important;
    border: none !important;
}

html body .card .table .badge-success,
.card .table .badge-success {
    background-color: #387057 !important;
    color: #fff !important;
    border-radius: 0.35rem !important;
    border: none !important;
}

/* Custom Green Button for New Task - NO GLASS EFFECTS */
html body .card .card-header #addTaskBtn.btn-primary,
html body #addTaskBtn.btn-primary,
#addTaskBtn.btn-primary,
#addTaskBtn,
#addTaskBtn.btn {
    background-color: #387057 !important;
    background: #387057 !important;
    border-color: #387057 !important;
    color: #fff !important;
    border: 2px solid #387057 !important;
    transition: all 0.2s ease;
    box-shadow: none !important;
    filter: none !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    opacity: 1 !important;
    text-shadow: none !important;
}

html body .card .card-header #addTaskBtn.btn-primary:hover,
html body .card .card-header #addTaskBtn.btn-primary:focus,
html body #addTaskBtn.btn-primary:hover,
html body #addTaskBtn.btn-primary:focus,
#addTaskBtn.btn-primary:hover,
#addTaskBtn.btn-primary:focus,
#addTaskBtn:hover,
#addTaskBtn:focus,
#addTaskBtn.btn:hover,
#addTaskBtn.btn:focus {
    background-color: #2d5a47 !important;
    background: #2d5a47 !important;
    border-color: #2d5a47 !important;
    color: #fff !important;
    border: 2px solid #2d5a47 !important;
    transform: translateY(-1px);
    box-shadow: none !important;
    filter: none !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    opacity: 1 !important;
    text-shadow: none !important;
}

/* NEW: Complete override for no-glass-effect class */
.no-glass-effect,
.no-glass-effect.btn,
.no-glass-effect.btn-primary,
.no-glass-effect.btn-sm {
    box-shadow: none !important;
    filter: none !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    text-shadow: none !important;
    background-image: none !important;
    background: #387057 !important;
    background-color: #387057 !important;
    border: 2px solid #387057 !important;
    border-color: #387057 !important;
    color: #fff !important;
    transition: all 0.2s ease;
}

.no-glass-effect:hover,
.no-glass-effect.btn:hover,
.no-glass-effect.btn-primary:hover,
.no-glass-effect.btn-sm:hover {
    box-shadow: none !important;
    filter: none !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    text-shadow: none !important;
    background-image: none !important;
    background: #2d5a47 !important;
    background-color: #2d5a47 !important;
    border: 2px solid #2d5a47 !important;
    border-color: #2d5a47 !important;
    color: #fff !important;
    transform: translateY(-1px);
}

/* COMPLETELY CUSTOM BUTTON - NO BOOTSTRAP INHERITANCE */
.custom-task-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    background-color: #387057;
    color: #fff;
    border: 2px solid #387057;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    min-width: 80px;
    height: 36px;
    box-shadow: none;
    filter: none;
    backdrop-filter: none;
    -webkit-backdrop-filter: none;
    opacity: 1;
    text-shadow: none;
    background-image: none;
    font-family: inherit;
    line-height: 1.5;
    vertical-align: middle;
    user-select: none;
}

.custom-task-btn:hover,
.custom-task-btn:focus {
    background-color: #2d5a47;
    border-color: #2d5a47;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: none;
    filter: none;
    backdrop-filter: none;
    -webkit-backdrop-filter: none;
    opacity: 1;
    text-shadow: none;
    background-image: none;
    text-decoration: none;
}

.custom-task-btn:active {
    transform: translateY(0);
}

.custom-task-btn:focus {
    outline: 0;
}

/* Fix Status column alignment in Recent System Activity table */
.card .table th:last-child,
.card .table td:last-child {
    text-align: left !important;
}

.dashboard-card:hover {
    transform: translateY(-2px);
}

/* Force override any blue styling on stat cards */
.card.stat-card,
.card.dashboard-card {
    background: #fff !important;
    background-color: #fff !important;
}

.card.stat-card .card-body,
.card.dashboard-card .card-body {
    background: #fff !important;
    background-color: #fff !important;
    color: inherit !important;
}

.stat-card {
    border-radius: 10px;
    overflow: hidden;
    background: #fff !important;
}

.stat-card .card-body {
    padding: 1.5rem;
    background: #fff !important;
}

.stat-card .icon {
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.stat-card:hover .icon {
    opacity: 1;
}

/* Force text colors to be correct */
.text-primary {
    color: #18375d !important;
}

.text-success {
    color: #1cc88a !important;
}

.text-info {
    color: #36b9cc !important;
}

.text-warning {
    color: #f6c23e !important;
}

.text-danger {
    color: #e74a3b !important;
}

.text-secondary {
    color: #858796 !important;
}

/* Ensure no blue backgrounds anywhere in stat cards */
.card.stat-card *,
.card.dashboard-card * {
    background-color: transparent !important;
}

.card.stat-card,
.card.dashboard-card {
    background-color: #fff !important;
}

.table-responsive {
    border-radius: 8px;
    overflow: hidden;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.fade-in {
    animation: fadeIn 0.6s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Task Board Styling */
.task-checkbox {
    width: 18px;
    height: 18px;
    accent-color: #18375d !important;
    cursor: pointer;
    margin-top: 2px;
}

/* Task board container styling */
#taskList {
    border-radius: 0.5rem;
    overflow: hidden;
}

#taskList .list-group-item:first-child {
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
}

#taskList .list-group-item:last-child {
    border-bottom-left-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
}

.task-checkbox:checked {
    background-color: #18375d !important;
    border-color: #18375d !important;
}

.task-checkbox:focus {
    outline: 2px solid #18375d;
    outline-offset: 2px;
}

.task-checkbox:focus:not(:focus-visible) {
    outline: none;
}

/* Task action buttons spacing */
.action-buttons {
    display: inline-flex;
    gap: 0.5rem;
    align-items: center;
}

/* Ensure proper alignment of task items */
.list-group-item {
    padding: 1rem;
    border-left: none;
    border-right: none;
    border-top: 1px solid #e3e6f0;
    border-bottom: 1px solid #e3e6f0;
    transition: all 0.2s ease;
    background-color: #fff;
}

.list-group-item:hover {
    background-color: #f8f9fc;
    transform: translateX(2px);
}

.list-group-item:first-child {
    border-top: none;
}

.list-group-item:last-child {
    border-bottom: none;
}

.list-group-item .d-flex {
    gap: 0.5rem;
}

/* Task title and description alignment */
.list-group-item .font-weight-bold {
    color: #18375d;
    margin-bottom: 0.25rem;
}

.list-group-item .text-muted {
    font-size: 0.875rem;
    line-height: 1.4;
}


/* Priority badge styling */
.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-weight: 500;
    letter-spacing: 0.025em;
}

/* Priority badge specific colors */
.badge-danger {
    background-color: #e74a3b !important;
    color: #fff !important;
}

.badge-warning {
    background-color: #f6c23e !important;
    color: #fff !important;
}

.badge-secondary {
    background-color: #858796 !important;
    color: #fff !important;
}

/* User Details Modal Styling */
    #confirmDeleteTaskModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #confirmDeleteTaskModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #confirmDeleteTaskModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #confirmDeleteTaskModal .modal-body {
        padding: 2rem;
        background: white;
    }
    #confirmDeleteTaskModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #confirmDeleteTaskModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #confirmDeleteTaskModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #confirmDeleteTaskModal .form-group label {
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

/* List Group Styling */
.list-group {
    border: none;
}

.list-group-item {
    border: none;
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
    background-color: #f8f9fc;
    transition: all 0.3s ease;
}

.list-group-item:hover {
    background-color: #e9edf5;
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
.smart-form .icon-wrapper {
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
#taskModal form {
  text-align: left;
}

#taskModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#taskModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#taskModal .form-control,
#taskModal select.form-control,
#taskModal textarea.form-control {
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
#taskModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#taskModal .form-control:focus {
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
#taskModal .modal-footer {
  text-align: center;
  border-top: 1px solid #e5e7eb;
  padding-top: 1.25rem;
  margin-top: 1.5rem;
}
#changePasswordModal .modal-footer {
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

    #taskModal .form-control {
        font-size: 14px;
    }
    #changePasswordModal .form-control {
        font-size: 14px;
    }

  .btn-ok,
  .btn-delete,
  .btn-approves {
    width: 100%;
    margin-top: 0.5rem;
  }
}

/* Apply consistent styling for Pending Farmers and Active Farmers tables */
#recenttable th,
#recenttable td,
#activeFarmersTable th,
#activeFarmersTable td {
    vertical-align: middle;
    padding: 0.75rem;
    text-align: left;
    border: 1px solid #dee2e6;
    white-space: nowrap;
    overflow: visible;
}

/* Ensure all table headers have consistent styling */
#recenttable thead th,
#activeFarmersTable thead th {
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
#recenttable thead th.sorting,
#recenttable thead th.sorting_asc,
#recenttable thead th.sorting_desc,
#activeFarmersTable thead th.sorting,
#activeFarmersTable thead th.sorting_asc,
#activeFarmersTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#recenttable thead th::after,
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
#recenttable thead th.sorting::after,
#recenttable thead th.sorting_asc::after,
#recenttable thead th.sorting_desc::after,
#activeFarmersTable thead th.sorting::after,
#activeFarmersTable thead th.sorting_asc::after,
#activeFarmersTable thead th.sorting_desc::after {
    display: none;
}

#recenttable td, 
#recenttable th {
    white-space: normal !important;  /* allow wrapping */
    vertical-align: middle;
}

/* Make sure action buttons don’t overflow */
#recenttable td .btn-group {
    display: flex;
    flex-wrap: wrap; /* buttons wrap if not enough space */
    gap: 0.25rem;    /* small gap between buttons */
}

#recenttable td .btn-action {
    flex: 1 1 auto; /* allow buttons to shrink/grow */
    min-width: 90px; /* prevent too tiny buttons */
    text-align: center;
}
</style>
@endpush
@section('content')
    <!-- Page Header -->
<div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
    <h1>
        <i class="fas fa-tachometer-alt me-2"></i> Admin Dashboard
    </h1>
    <p >
        Welcome back! Here’s an overview of your dairy management system’s performance today.
    </p>
</div>
    <!-- Statistics Grid -->
<div class="row fade-in">
    <!-- Active Farms -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Active Farms</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Farm::where('status', 'active')->count() }}</div>
                </div>
                <div class="icon" style="color: #18375d !important;">
                    <i class="fas fa-building fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Farmers -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Total Farmers</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'farmer')->count() }}</div>
                </div>
                <div class="icon" style="color: #18375d !important;">
                    <i class="fas fa-users fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- New Requests -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">New Requests</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Issue::where('status', 'pending')->count() }}</div>
                </div>
                <div class="icon" style="color: #18375d !important;">
                    <i class="fas fa-user-plus fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Livestock -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Total Livestock</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Livestock::count() }}</div>
                </div>
                <div class="icon" style="color: #18375d !important;">
                    <i class="fas fa-tractor fa-2x mr-2"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Task Board Row --> 
 <div class="row fade-in"> <!-- Task Board --> 
    <div class="col-12 mb-4"> 
        <div class="card shadow"> 
            <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start"> 
                <h6 class="mb-0"> 
                    <i class="fas fa-tasks"></i> Task Board 
                </h6> 
                <button class="btn-action btn-action-ok" title="New Task" id="addTaskBtn"> 
                    <i class="fas fa-plus mr-2"></i> New Task 
                </button> 
            </div> 
            <div class="card-body"> 
                <ul class="list-group" id="taskList"></ul> 
            </div> 
        </div> 
    </div> 
</div>

<!-- Livestock Trends Chart Row -->
<div class="row fade-in">
    <!-- Livestock Trends Chart -->
    <div class="col-12 mb-4">
        <div class="card shadow">
            <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2  text-sm-start">
                <h6 class="mb-0">
                    <i class="fas fa-chart-line"></i>
                    Livestock Population Trends
                </h6>
            </div>
            <div class="card-body">
                <canvas id="lineChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>


<!-- Recent System Activity -->
<div class="row fade-in">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2  text-sm-start">
                <h6 class="mb-0">
                    <i class="fas fa-history"></i>
                    Recent System Activity
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="recenttable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>User</th>
                                <th>Details</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\AuditLog::latest()->take(10)->get() as $log)
                            <tr>
                                <td>
                                    <span class="badge badge-{{ $log->severity === 'critical' ? 'danger' : ($log->severity === 'warning' ? 'warning' : 'info') }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td>{{ $log->user->name ?? 'System' }}</td>
                                <td>{{ Str::limit($log->description ?? 'No details', 60) }}</td>
                                <td>{{ $log->created_at->diffForHumans() }}</td>
                                <td>
                                    <span class="badge badge-success">Completed</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modern Task Modal -->
<div class="modal fade admin-modal" id="taskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content smart-form text-center p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-wrapper">
                    <i class="fas fa-tasks fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1" id="taskModalTitle">New Task</h5>
                <p class="text-muted mb-0 small">
                    Create or update your task details below, then click <strong>“Add Task”</strong> to save changes.
                </p>
            </div>

            <!-- Form -->
            <form id="taskForm">
                <input type="hidden" id="taskId">

                <div class="form-wrapper text-start mx-auto">
                    
                        
                        <!-- Title -->
                        <div class="col-md-12">
                            <label for="taskTitle" class="fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control mt-1" id="taskTitle" required maxlength="255" placeholder="Enter task title">
                        </div>

                        <!-- Description -->
                        <div class="col-md-12">
                            <label for="taskDescription" class="fw-semibold">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control mt-1" id="taskDescription" rows="3" placeholder="Enter task details" style="resize: none;"></textarea>
                        </div>
                            <!-- Priority -->
                        <div class="col-md-12">
                            <label for="taskPriority" class="fw-semibold">Priority <span class="text-danger">*</span></label>
                            <select class="form-control mt-1" id="taskPriority">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>

                        <!-- Due Date -->
                        <div class="col-md-12">
                            <label for="taskDueDate" class="fw-semibold">Due Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control mt-1" id="taskDueDate">
                        </div>

                    <!-- Notification -->
                    <div id="taskNotification" class="mt-3 text-center" style="display: none;"></div>
                </div>

                <!-- Footer Buttons -->
                <div class="modal-footer d-flex gap-2 justify-content-center flex-wrap mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-ok" id="taskSubmitBtn">Add Task</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modern Delete Task Confirmation Modal -->
<div class="modal fade" id="confirmDeleteTaskModal" tabindex="-1" aria-hidden="true">
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
                Are you sure you want to delete this task? This action <strong>cannot be undone</strong>.
            </p>

            <!-- Buttons -->
            <div class="modal-footer d-flex gap-2 justify-content-center flex-wrap">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteTaskBtn" class="btn-modern btn-delete">
                    Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Task board logic
    const taskList = document.getElementById('taskList');
    const addTaskBtn = document.getElementById('addTaskBtn');

    function fetchTasks() {
        fetch('/admin/tasks', { credentials: 'same-origin', headers: { 'Accept': 'application/json' }})
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;
                renderTasks(data.tasks);
            });
    }

    function renderTasks(tasks) {
        taskList.innerHTML = '';
        if (!tasks || tasks.length === 0) {
            const li = document.createElement('li');
            li.className = 'list-group-item text-muted';
            li.textContent = 'No tasks yet';
            taskList.appendChild(li);
            return;
        }
        tasks.forEach(task => taskList.appendChild(taskItem(task)));
    }

    function taskItem(task) {
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between';
        li.dataset.id = task.id;
        li.innerHTML = `
            <div class="d-flex align-items-center">
                <input type="checkbox" class="task-checkbox mr-3" ${task.status === 'done' ? 'checked' : ''}>
                <div>
                    <div class="font-weight-bold">${escapeHtml(task.title)}</div>
                    <small class="text-muted">${escapeHtml(task.description || '')}</small>
                </div>
            </div>
            <div class="mt-2 mt-md-0 d-flex align-items-center">
                <span class="badge badge-${priorityBadge(task.priority)} mr-2"><i class="far fa-clock"></i> ${formatDue(task.due_date)}</span>
                <div class="btn-group">
                    <button class="btn-action btn-action-ok edit-task" title="Edit Task">
                        <i class="fas fa-edit"></i>
                        <span>Edit</span>
                    </button>
                    <button class="btn-action btn-action-deletes delete-task" title="Delete Task">
                        <i class="fas fa-trash"></i>
                        <span>Delete</span>
                    </button>
                </div>
            </div>
        `;

        li.querySelector('input[type="checkbox"]').addEventListener('change', (e) => {
            updateTask(task.id, { status: e.target.checked ? 'done' : 'todo' });
        });
        li.querySelector('.edit-task').addEventListener('click', () => startEditTask(task));
        li.querySelector('.delete-task').addEventListener('click', () => deleteTask(task.id));
        return li;
    }

    function escapeHtml(s) {
        return (s || '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[c]));
    }

    function priorityBadge(p) {
        if (p === 'high') return 'danger';
        if (p === 'low') return 'secondary';
        return 'warning';
    }

    function formatDue(dateStr) {
        if (!dateStr) return 'No due';
        try { return new Date(dateStr).toLocaleDateString(); } catch { return 'No due'; }
    }

    function startNewTask() {
        document.getElementById('taskId').value = '';
        document.getElementById('taskTitle').value = '';
        document.getElementById('taskDescription').value = '';
        document.getElementById('taskPriority').value = 'medium';
        document.getElementById('taskDueDate').value = '';
        document.getElementById('taskSubmitBtn').textContent = 'Add Task';
        document.getElementById('taskModalTitle').textContent = 'New Task';
        $('#taskModal').modal('show');
        
        // Focus the title field after modal is shown
        $('#taskModal').on('shown.bs.modal', function () {
            setTimeout(() => {
                const titleField = document.getElementById('taskTitle');
                if (titleField) {
                    titleField.focus();
                    titleField.select(); // Select any existing text
                }
            }, 100);
        });
    }

    function startEditTask(task) {
        document.getElementById('taskId').value = task.id;
        document.getElementById('taskTitle').value = task.title || '';
        document.getElementById('taskDescription').value = task.description || '';
        document.getElementById('taskPriority').value = task.priority || 'medium';
        document.getElementById('taskDueDate').value = task.due_date ? task.due_date.substring(0,10) : '';
        document.getElementById('taskSubmitBtn').textContent = 'Update Task';
        document.getElementById('taskModalTitle').textContent = 'Edit Task';
        document.getElementById('taskTitle').focus();
        $('#taskModal').modal('show');
    }

    function hideTaskForm() {
        $('#taskModal').modal('hide');
    }

    function createTask(payload) {
        fetch('/admin/tasks', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
        }).then(async r => {
            let data = null;
            try { data = await r.json(); } catch (e) {}
            if (!r.ok) {
                const msg = (data && (data.message || (data.errors && Object.values(data.errors)[0][0]))) || `Request failed (${r.status})`;
                alert(`Failed to create task: ${msg}`);
                return;
            }
            if (data && data.success) {
                fetchTasks();
            } else {
                alert('Failed to create task');
            }
        }).catch(() => alert('Failed to create task: network error'));
    }

    function updateTask(id, payload) {
        fetch(`/admin/tasks/${id}`, {
            method: 'PUT',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
        }).then(async r => {
            let data = null;
            try { data = await r.json(); } catch (e) {}
            if (!r.ok) {
                const msg = (data && (data.message || (data.errors && Object.values(data.errors)[0][0]))) || `Request failed (${r.status})`;
                alert(`Failed to update task: ${msg}`);
                return;
            }
            if (data && data.success) {
                fetchTasks();
            } else {
                alert('Failed to update task');
            }
        }).catch(() => alert('Failed to update task: network error'));
    }

    let taskToDelete = null;

    function deleteTask(id) {
        taskToDelete = id;
        $('#confirmDeleteTaskModal').modal('show');
    }

    document.getElementById('confirmDeleteTaskBtn').addEventListener('click', function() {
        if (taskToDelete) {
            performTaskDeletion(taskToDelete);
            taskToDelete = null;
            $('#confirmDeleteTaskModal').modal('hide');
        }
    });

    function performTaskDeletion(id) {
        fetch(`/admin/tasks/${id}`, {
            method: 'DELETE',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(r => r.json()).then(data => {
            if (data.success) {
                fetchTasks();
                showNotification('Task deleted successfully', 'success');
            } else {
                showNotification('Failed to delete task', 'danger');
            }
        }).catch(() => {
            showNotification('Failed to delete task: network error', 'danger');
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

    addTaskBtn?.addEventListener('click', () => startNewTask());
    document.getElementById('taskCancelEditBtn')?.addEventListener('click', () => hideTaskForm());
    document.getElementById('taskForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('taskId').value;
        const payload = {
            title: document.getElementById('taskTitle').value.trim(),
            description: document.getElementById('taskDescription').value.trim(),
            priority: document.getElementById('taskPriority').value,
            due_date: document.getElementById('taskDueDate').value || null
        };
        if (!payload.title) { alert('Title is required'); return; }
        if (id) {
            updateTask(id, payload);
        } else {
            createTask(payload);
        }
        hideTaskForm();
    });
    fetchTasks();

    // Initialize chart with real data
    const ctxLine = document.getElementById('lineChart').getContext('2d');
    let trendsChart = null;
    fetch("{{ route('admin.livestock-trends') }}", { credentials: 'same-origin', headers: { 'Accept': 'application/json' }})
        .then(r => r.json())
        .then(payload => {
            if (!payload || !payload.success) return;
            const config = {
                type: 'line',
                data: {
                    labels: payload.labels,
                    datasets: payload.datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.1)' } },
                        x: { grid: { color: 'rgba(0,0,0,0.1)' } }
                    }
                }
            };
            trendsChart = new Chart(ctxLine, config);
        })
        .catch(() => {
            // fallback to placeholder data if request fails
            trendsChart = new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Cattle',
                        data: [65, 59, 80, 81, 56, 55],
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Goats',
                        data: [28, 48, 40, 19, 86, 27],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        });
});
</script>
@endpush
