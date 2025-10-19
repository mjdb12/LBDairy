@extends('layouts.app')

@section('title', 'LBDAIRY: Admin - Profile')

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
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
        .btn-action-edit-profile:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }

    .btn-action-edit-pass {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
        .btn-action-edit-pass:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }

    .btn-action-oks {
        background-color: #18375d;
        border-color: #18375d;
        color: white;
    }
    .btn-action-oks:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }

       
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
    
    /* Custom button colors */
    .btn-edit-profile {
        background-color: #387057 !important;
        border-color: #387057 !important;
        color: white !important;
    }
    .btn-action-edit-profile {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
        .btn-action-edit-profile:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }

    .btn-action-edit-pass {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
        .btn-action-edit-pass:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-edit-profile:hover,
    .btn-edit-profile:focus {
        background-color: #2d5a47 !important;
        border-color: #2d5a47 !important;
        color: white !important;
        box-shadow: 0 0 0 0.2rem rgba(56, 112, 87, 0.25) !important;
    }
    
    .btn-change-password {
        background-color: #fca700 !important;
        border-color: #fca700 !important;
        color: white !important;
    }
    
    .btn-change-password:hover,
    .btn-change-password:focus {
        background-color: #e69500 !important;
        border-color: #e69500 !important;
        color: white !important;
        box-shadow: 0 0 0 0.2rem rgba(252, 167, 0, 0.25) !important;
    }

     /* Profile Info Table */
    .profile-info-table {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .profile-info-table td {
        color: #5a5c69 !important;
        padding: 1rem 0;
        border-bottom: 1px solid #e3e6f0;
        font-size: 0.95rem;
        vertical-align: middle;
    }
    
    .profile-info-table th {
        color: #18375d !important;
        padding: 1rem 0;
        border-bottom: 1px solid #e3e6f0;
        font-weight: 600;
        width: 200px;
        font-size: 0.9rem;
        vertical-align: middle;
    }
    
    .profile-info-table th i {
        margin-right: 0.5rem;
        width: 16px;
        text-align: center;
    }
    
    .profile-info-table tbody tr:hover {
        background-color: #f8f9fc;
        transition: background-color 0.2s ease;
    }
    
    .profile-info-table tbody tr:last-child td,
    .profile-info-table tbody tr:last-child th {
        border-bottom: none;
    }
    
    /* Profile detail values styling */
    .profile-info-table td {
        font-weight: 500;
    }
    
    .profile-info-table td:empty::after {
        content: "Not provided";
        color: #858796;
        font-style: italic;
    }
    /* Stagger Animation */
    .stagger-animation .col-12 {
        animation: fadeInUp 0.6s ease-out;
    }
    
    .stagger-animation .col-12:nth-child(1) { animation-delay: 0.1s; }
    .stagger-animation .col-12:nth-child(2) { animation-delay: 0.2s; }
    .stagger-animation .col-12:nth-child(3) { animation-delay: 0.3s; }
    .stagger-animation .col-12:nth-child(4) { animation-delay: 0.4s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Notification Styles */
    .notification {
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideInRight 0.3s ease-out;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    

/* Responsive Design */
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .card-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .profile-info-table th {
            width: 150px;
            font-size: 0.85rem;
        }
        
        .profile-info-table td {
            font-size: 0.9rem;
        }
        
        .profile-info-table th i {
            width: 14px;
            margin-right: 0.4rem;
        }
        
        .profile-card-bordered {
            margin-bottom: 1.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .profile-info-table th {
            width: 120px;
            font-size: 0.8rem;
            padding: 0.75rem 0;
        }
        
        .profile-info-table td {
            font-size: 0.85rem;
            padding: 0.75rem 0;
        }
        
        .profile-info-table th i {
            width: 12px;
            margin-right: 0.3rem;
        }
    }
/* -------------------------
   Custom Action Buttons (Details Header)
-------------------------- */
    .btn-action {
        font-size: 0.85rem;
        padding: 0.35rem 0.75rem;
        border-radius: 0.5rem;
        transition: 0.2s;
    }

    .btn-action-edit-profile {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
    .btn-action-edit-profile:hover {
    background-color: #fca700;
    border-color: #fca700;
    color: white;
    }

    .btn-action-edit-pass {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }

    .btn-action-edit-pass:hover {
    background-color: #fca700;
    border-color: #fca700;
    color: white;
    }

.btn-action:hover {
    opacity: 0.85;
}


    /* Stagger Animation */
    .stagger-animation .col-12 {
        animation: fadeInUp 0.6s ease-out;
    }
    
    .stagger-animation .col-12:nth-child(1) { animation-delay: 0.1s; }
    .stagger-animation .col-12:nth-child(2) { animation-delay: 0.2s; }
    .stagger-animation .col-12:nth-child(3) { animation-delay: 0.3s; }
    .stagger-animation .col-12:nth-child(4) { animation-delay: 0.4s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Notification Styles */
    .notification {
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideInRight 0.3s ease-out;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .card-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
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
.smart-form .icon-wrapper {
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
#editProfileModal .modal-footer {
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

    #editProfileModal .form-control {
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
/* Reset table layout */
.profile-info-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
}

/* Basic cell styling */
.profile-info-table th,
.profile-info-table td {
    padding: 10px 12px;
    text-align: left;
    vertical-align: middle;
    border: none;
    white-space: nowrap;
}

/* Header column (labels) */
.profile-info-table th {
    font-weight: 600;
    color: #18375d;
}

/* Data column */
.profile-info-table td {
    color: #333;
}

/* Icons inside headers */
.profile-info-table th i {
    color: #18375d;
    margin-right: 6px;
}

/* Badge colors */
.badge-primary {
    background-color: #18375d !important;
    color: #fff !important;
}

.badge-success {
    background-color: #28a745 !important;
    color: #fff !important;
}

/* Make table scrollable on smaller screens */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* Optional: improve mobile readability */
@media (max-width: 768px) {
    .profile-info-table th,
    .profile-info-table td {
        font-size: 0.9rem;
        white-space: nowrap;
    }
}
/* Make table scrollable horizontally */
.profile-scroll {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* Prevent layout break on scroll */
.profile-info-table {
    min-width: 370px; /* or adjust if you want */
    width: 100%;
    border-collapse: collapse;
}

.profile-info-table th,
.profile-info-table td {
    padding: 10px 12px;
    white-space: nowrap; /* prevents wrapping */
}

/* Optional: improve mobile view */
@media (max-width: 768px) {
    .profile-info-table {
        font-size: 0.9rem;
    }
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
        <i class="fas fa-user-shield"></i>
        Admin Profile
    </h1>
    <p>Manage your administrative profile and account settings</p>
</div>

<!-- Success/Error messages handled by global top-right toast -->

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle mr-2"></i>
    <strong>Please fix the following errors:</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
</div>
@endif

<!-- Stats Cards -->
<div class="row fade-in">
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
    <!-- Active Farmers -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Active Farmers</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'farmer')->where('is_active', true)->count() }}</div>
                </div>
                <div class="icon" style="color: #18375d !important;">
                    <i class="fas fa-user-check fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pending Issues -->
     <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Pending Issues</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Issue::whereIn('status', ['Pending','In Progress'])->count() }}</div>
                </div>
                <div class="icon" style="color: #18375d !important;">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Days as Admin -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Days as Admin</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format(\Carbon\Carbon::parse(auth()->user()->created_at)->diffInDays(now()), 2) }}</div>
                </div>
                <div class="icon" style="color: #18375d !important;">
                    <i class="fas fa-user-shield fa-2x"></i>
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
                    <button class="btn-action btn-action-ok" title="Upload Profile Picture" onclick="document.getElementById('uploadProfilePicture').click()">
                        <i class="fas fa-camera"></i>
                    </button>
                    <input type="file" id="uploadProfilePicture" accept="image/*" style="display:none;" onchange="changeProfilePicture(event)">
                </div>

                <h3 class="mt-3 mb-1 font-weight-bold">{{ auth()->user()->name }}</h3>
                <p class="text-muted mb-2">{{ auth()->user()->email }}</p>
                <!-- Action Buttons -->
                <div class="btn-group d-flex align-items-center justify-content-center flex-nowrap gap-2 action-toolbar">
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
            <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
                <h6>
                    <i class="fas fa-user-edit"></i>
                    Profile Details
                </h6>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0 profile-info-table profile-scroll">
                        <tbody>
                            <tr>
                                <th scope="row" >
                                    <i class="fas fa-user"></i> Full Name
                                </th>
                                <td>{{ auth()->user()->name ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th scope="row"><i class="fas fa-envelope  "></i> Email</th>
                                <td>{{ auth()->user()->email ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th scope="row"><i class="fas fa-phone  "></i> Phone</th>
                                <td>{{ auth()->user()->phone ?? 'Not provided' }}</td>
                            </tr>
                           <tr>
                                <th scope="row"><i class="fas fa-user-shield  "></i> Position</th>
                                <td>{{ auth()->user()->position ?? 'Super Admin' }}</td>
                            </tr>
                            <tr>
                                <th scope="row"><i class="fas fa-map-marker-alt  "></i> Barangay</th>
                                <td>{{ auth()->user()->barangay ?? 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <th scope="row"><i class="fas fa-home  "></i> Address</th>
                                <td>{{ auth()->user()->address ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th scope="row"><i class="fas fa-calendar-alt  "></i> Member Since</th>
                                <td>{{ auth()->user()->created_at ? auth()->user()->created_at->format('F j, Y') : 'Unknown' }}</td>
                            </tr>
                            <tr>
                                <th scope="row"><i class="fas fa-clock "></i> Last Updated</th>
                                <td>{{ auth()->user()->updated_at ? auth()->user()->updated_at->format('F j, Y g:i A') : 'Never' }}</td>
                            </tr>
                            <tr>
                                <th scope="row"><i class="fas fa-user-tag "></i> Role</th>
                                <td><span class="badge badge-primary">{{ ucfirst(auth()->user()->role ?? 'Unknown') }}</span></td>
                            </tr>
                            <tr>
                                <th scope="row"><i class="fas fa-check-circle  "></i> Status</th>
                                <td><span class="badge badge-success">{{ ucfirst(auth()->user()->status ?? 'Active') }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Edit Profile Modal -->
<div class="modal fade admin-modal" id="editProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content smart-form text-center p-4">
            
            
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-wrapper mb-3">
                    <i class="fas fa-user-edit fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">Edit Profile</h5>
                <p class="text-muted mb-0 small">
                    Update your profile information below. Make sure all fields are correct before saving.
                </p>
            </div>

            <!-- Form -->
            <form id="editProfileForm" method="POST" action="{{ route('admin.profile.update') }}" >
                @csrf
                @method('PUT')

                <div class="form-wrapper text-start mx-auto">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="editFullName">
                                Full Name
                            <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="editFullName" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="editEmail">
                                Email
                            <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="editEmail" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="editPhone">
                                Contact Number
                            <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="editPhone" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Barangay -->
                        <div class="col-md-6">
                            <label for="editBarangay">
                                Barangay
                            <span class="text-danger">*</span></label>
                            <select class="form-control @error('barangay') is-invalid @enderror" id="editBarangay" name="barangay" required>
                                <option value="">Select Barangay</option>
                                <option value="Abang" {{ old('barangay', auth()->user()->barangay) == 'Abang' ? 'selected' : '' }}>Abang</option>
                                <option value="Aliliw" {{ old('barangay', auth()->user()->barangay) == 'Aliliw' ? 'selected' : '' }}>Aliliw</option>
                                <option value="Atulinao" {{ old('barangay', auth()->user()->barangay) == 'Atulinao' ? 'selected' : '' }}>Atulinao</option>
                                <option value="Ayuti (Poblacion)" {{ old('barangay', auth()->user()->barangay) == 'Ayuti (Poblacion)' ? 'selected' : '' }}>Ayuti (Poblacion)</option>
                                <option value="Barangay 1 (Poblacion)" {{ old('barangay', auth()->user()->barangay) == 'Barangay 1 (Poblacion)' ? 'selected' : '' }}>Barangay 1 (Poblacion)</option>
                                <option value="Barangay 2 (Poblacion)" {{ old('barangay', auth()->user()->barangay) == 'Barangay 2 (Poblacion)' ? 'selected' : '' }}>Barangay 2 (Poblacion)</option>
                                <option value="Barangay 3 (Poblacion)" {{ old('barangay', auth()->user()->barangay) == 'Barangay 3 (Poblacion)' ? 'selected' : '' }}>Barangay 3 (Poblacion)</option>
                                <option value="Barangay 4 (Poblacion)" {{ old('barangay', auth()->user()->barangay) == 'Barangay 4 (Poblacion)' ? 'selected' : '' }}>Barangay 4 (Poblacion)</option>
                                <option value="Barangay 5 (Poblacion)" {{ old('barangay', auth()->user()->barangay) == 'Barangay 5 (Poblacion)' ? 'selected' : '' }}>Barangay 5 (Poblacion)</option>
                                <option value="Barangay 6 (Poblacion)" {{ old('barangay', auth()->user()->barangay) == 'Barangay 6 (Poblacion)' ? 'selected' : '' }}>Barangay 6 (Poblacion)</option>
                                <option value="Barangay 7 (Poblacion)" {{ old('barangay', auth()->user()->barangay) == 'Barangay 7 (Poblacion)' ? 'selected' : '' }}>Barangay 7 (Poblacion)</option>
                                <option value="Barangay 8 (Poblacion)" {{ old('barangay', auth()->user()->barangay) == 'Barangay 8 (Poblacion)' ? 'selected' : '' }}>Barangay 8 (Poblacion)</option>
                                <option value="Barangay 9 (Poblacion)" {{ old('barangay', auth()->user()->barangay) == 'Barangay 9 (Poblacion)' ? 'selected' : '' }}>Barangay 9 (Poblacion)</option>
                                <option value="Barangay 10 (Poblacion)" {{ old('barangay', auth()->user()->barangay) == 'Barangay 10 (Poblacion)' ? 'selected' : '' }}>Barangay 10 (Poblacion)</option>
                                <option value="Igang" {{ old('barangay', auth()->user()->barangay) == 'Igang' ? 'selected' : '' }}>Igang</option>
                                <option value="Kabatete" {{ old('barangay', auth()->user()->barangay) == 'Kabatete' ? 'selected' : '' }}>Kabatete</option>
                                <option value="Kakawit" {{ old('barangay', auth()->user()->barangay) == 'Kakawit' ? 'selected' : '' }}>Kakawit</option>
                                <option value="Kalangay" {{ old('barangay', auth()->user()->barangay) == 'Kalangay' ? 'selected' : '' }}>Kalangay</option>
                                <option value="Kalyaat" {{ old('barangay', auth()->user()->barangay) == 'Kalyaat' ? 'selected' : '' }}>Kalyaat</option>
                                <option value="Kilib" {{ old('barangay', auth()->user()->barangay) == 'Kilib' ? 'selected' : '' }}>Kilib</option>
                                <option value="Kulapi" {{ old('barangay', auth()->user()->barangay) == 'Kulapi' ? 'selected' : '' }}>Kulapi</option>
                                <option value="Mahabang Parang" {{ old('barangay', auth()->user()->barangay) == 'Mahabang Parang' ? 'selected' : '' }}>Mahabang Parang</option>
                                <option value="Malupak" {{ old('barangay', auth()->user()->barangay) == 'Malupak' ? 'selected' : '' }}>Malupak</option>
                                <option value="Manasa" {{ old('barangay', auth()->user()->barangay) == 'Manasa' ? 'selected' : '' }}>Manasa</option>
                                <option value="May-It" {{ old('barangay', auth()->user()->barangay) == 'May-It' ? 'selected' : '' }}>May-It</option>
                                <option value="Nagsinamo" {{ old('barangay', auth()->user()->barangay) == 'Nagsinamo' ? 'selected' : '' }}>Nagsinamo</option>
                                <option value="Nalunao" {{ old('barangay', auth()->user()->barangay) == 'Nalunao' ? 'selected' : '' }}>Nalunao</option>
                                <option value="Palola" {{ old('barangay', auth()->user()->barangay) == 'Palola' ? 'selected' : '' }}>Palola</option>
                                <option value="Piis" {{ old('barangay', auth()->user()->barangay) == 'Piis' ? 'selected' : '' }}>Piis</option>
                                <option value="Samil" {{ old('barangay', auth()->user()->barangay) == 'Samil' ? 'selected' : '' }}>Samil</option>
                                <option value="Tiawe" {{ old('barangay', auth()->user()->barangay) == 'Tiawe' ? 'selected' : '' }}>Tiawe</option>
                                <option value="Tinamnan" {{ old('barangay', auth()->user()->barangay) == 'Tinamnan' ? 'selected' : '' }}>Tinamnan</option>
                            </select>
                            @error('barangay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Position -->
                        <div class="col-md-6">
                            <label for="editPosition">
                                Position
                            <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror" id="editPosition" name="position" value="{{ old('position', auth()->user()->position ?? 'Admin') }}">
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="col-md-6">
                            <label for="editAddress">
                                Address
                            <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="editAddress" name="address" value="{{ old('address', auth()->user()->address) }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-ok"><i class="fas fa-save"></i> Save Changes</button>
                </div>
            </form>
            
        </div>
    </div>
</div>

<!-- Modern Change Password Modal -->
<div class="modal fade admin-modal" id="changePasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content smart-form text-center p-4">
            
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-wrapper mb-3">
                    <i class="fas fa-key fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">Change Password</h5>
                <p class="text-muted mb-0 small">
                    Please fill in your current and new password below.</p>
                </p>
            </div>

      <form id="changePasswordForm" method="POST" action="{{ route('admin.profile.password') }}">
        @csrf
        @method('PUT')
        <div class="form-wrapper text-start mx-auto">
                <div class="col-md-12">
                    <label for="currentPassword">
                        Current Password
                    <span class="text-danger">*</span></label>
                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="currentPassword" name="current_password" required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="newPassword">
                        New Password
                    <span class="text-danger">*</span></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="newPassword" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="newPassword">
                        New Password
                    <span class="text-danger">*</span></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="newPassword" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
        </div>

        <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
          <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-modern btn-ok"><i class="fas fa-save"></i> Change Password</button>
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
        if (file) {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                showNotification('Please select a valid image file!', 'danger');
                return;
            }
            
            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                showNotification('Image size should be less than 5MB!', 'danger');
                return;
            }
            
            // Create FormData for file upload
            const formData = new FormData();
            formData.append('profile_picture', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            // Show loading state
            const profilePicture = document.getElementById('profilePicture');
            const originalSrc = profilePicture.src;
            profilePicture.style.opacity = '0.5';
            
            // Upload file to server
            fetch('{{ route("admin.profile.picture") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the image source with the new filename (with cache-busting)
                    const timestamp = new Date().getTime();
                    profilePicture.src = '{{ asset("img/") }}/' + data.filename + '?t=' + timestamp;
                    
                    // Update the topbar profile picture using the global function
                    if (typeof updateTopbarProfilePicture === 'function') {
                        updateTopbarProfilePicture(data.filename);
                        console.log('Topbar profile picture update called with:', data.filename);
                    } else {
                        console.warn('updateTopbarProfilePicture function not found');
                    }
                    
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message || 'Failed to upload profile picture!', 'danger');
                    profilePicture.src = originalSrc;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to upload profile picture!', 'danger');
                profilePicture.src = originalSrc;
            })
            .finally(() => {
                profilePicture.style.opacity = '1';
            });
        }
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show notification`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'times-circle'} mr-2"></i>
            ${message}
            <button type="button" class="close" onclick="this.parentElement.remove()">
                <span>&times;</span>
            </button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    // Form validation
    document.getElementById('editProfileForm').addEventListener('submit', function(e) {
        const name = document.getElementById('editFullName').value.trim();
        const email = document.getElementById('editEmail').value.trim();
        const barangay = document.getElementById('editBarangay').value;
        
        if (!name) {
            e.preventDefault();
            showNotification('Full name is required!', 'danger');
            return;
        }
        
        if (!email) {
            e.preventDefault();
            showNotification('Email is required!', 'danger');
            return;
        }
        
        if (!barangay) {
            e.preventDefault();
            showNotification('Please select a barangay!', 'danger');
            return;
        }
        
        // Show loading state
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        submitBtn.disabled = true;
        
        // Re-enable after a delay in case of validation errors
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });

    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        const currentPassword = document.getElementById('currentPassword').value;
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        if (!currentPassword) {
            e.preventDefault();
            showNotification('Current password is required!', 'danger');
            return;
        }
        
        if (!newPassword) {
            e.preventDefault();
            showNotification('New password is required!', 'danger');
            return;
        }
        
        if (newPassword.length < 8) {
            e.preventDefault();
            showNotification('New password must be at least 8 characters long!', 'danger');
            return;
        }
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            showNotification('Passwords do not match!', 'danger');
            return;
        }
        
        // Show loading state
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Changing...';
        submitBtn.disabled = true;
        
        // Re-enable after a delay in case of validation errors
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            if (alert.classList.contains('alert-dismissible')) {
                const closeButton = alert.querySelector('.close');
                if (closeButton) {
                    closeButton.click();
                }
            }
        });
    }, 5000);
</script>
@endpush
