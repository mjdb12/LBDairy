@extends('layouts.app')

@section('title', 'Livestock Management')
@push('styles')
<style>
    /* CRITICAL FIX FOR DROPDOWN TEXT CUTTING */
    .admin-modal select.form-control,
    .modal.admin-modal select.form-control,
    .admin-modal .modal-body select.form-control {
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
    .admin-modal .col-md-6 {
        min-width: 280px !important;
        overflow: visible !important;
    }

    /* Farmer Details Modal Styling (ensure header doesn't cover content) */
    #farmerDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        overflow: hidden;
    }

    #farmerDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
    }

    #farmerDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }

    #farmerDetailsModal .modal-body {
        padding: 2rem;
        background: #ffffff;
        position: relative;
        z-index: 1;
    }

    #farmerDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }

    /* Prevent dark blue bars behind headings in farmer details */
    #farmerDetailsModal .text-primary {
        background-color: transparent !important;
        color: #18375d !important;
    }

    #farmerDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }

    #farmerDetailsModal .modal-footer {
        background: #ffffff;
    }
    /* Custom styles for farmer management */
    .border-left-primary {
        border-left: 0.25rem solid #18375d !important;
    }
    
    .fade-in {
        animation: fadeIn 0.6s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
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

    /* Action buttons styling to match admin management */
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        border: 1px solid transparent;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        white-space: nowrap;
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
    
    .btn-action-refresh-admins, .btn-action-refresh-farmers {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-refresh-admin:hover, .btn-action-refresh-farmers:hover {
        background-color: #e69500;
        border-color: #e69500;
        color: white;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .d-flex.flex-column.flex-sm-row {
            flex-direction: column !important;
        }
        
        .gap-2 {
            gap: 0.5rem !important;
        }
    }
    
    /* Dashboard-style stat card styling */
    .dashboard-card {
        transition: transform 0.2s ease-in-out;
        background: #fff !important;
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
    
    /* Apply consistent styling for tables */
#livestockTable th,
#livestockTable td,
#farmersTable th,
#farmersTable td {
    vertical-align: middle;
    padding: 0.75rem;
    text-align: center;
    border: 1px solid #dee2e6;
    white-space: nowrap;
    overflow: visible;
}

/* Ensure all table headers have consistent styling */
#livestockTable thead th,
#farmersTable thead th {
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
#farmersTable thead th.sorting,
#farmersTable thead th.sorting_asc,
#farmersTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#livestockTable thead th::after,
#farmersTable thead th::after {
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
#farmersTable thead th.sorting::after,
#farmersTable thead th.sorting_asc::after,
#farmersTable thead th.sorting_desc::after {
    display: none;
}

.table-responsive {
    overflow-x: auto;
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

    
    /* DataTables Pagination Styling - FIXED */
    .dataTables_wrapper .dataTables_paginate {
        text-align: left !important;
        margin-top: 1rem;
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
        color: #18375d !important; /* Darker blue color for numbers */
        border: 1px solid #18375d !important;
        border-radius: 0.25rem;
        background-color: #fff;
        transition: all 0.15s ease-in-out;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        color: #fff !important;
        background-color: #18375d !important;
        border-color: #18375d !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        color: #fff !important;
        background-color: #18375d !important;
        border-color: #18375d !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        color: #6c757d !important;
        background-color: #fff !important;
        border-color: #dee2e6 !important;
        cursor: not-allowed;
        opacity: 0.5;
    }
    
    .dataTables_wrapper .dataTables_info {
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        color: #495057;
        font-size: 0.875rem;
        text-align: left !important;
        float: left !important;
        clear: both;
    }
    
    .dataTables_wrapper .dataTables_length {
        margin-bottom: 1rem;
    }
    
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
    }
    
    /* Force DataTables wrapper to have proper layout */
    .dataTables_wrapper .row {
        display: block !important;
        width: 100% !important;
        margin: 0 !important;
    }
    
    .dataTables_wrapper .row > div {
        padding: 0 !important;
        width: 100% !important;
        float: left !important;
        clear: both !important;
    }
    
    /* Ensure pagination container stays left */
    .dataTables_wrapper .dataTables_paginate,
    .dataTables_wrapper .dataTables_info {
        text-align: left !important;
        float: left !important;
        clear: both !important;
        display: block !important;
        width: auto !important;
        margin-right: 1rem !important;
    }
    
    /* Override any Bootstrap or other framework styles that might interfere */
    .dataTables_wrapper .col-sm-12.col-md-7,
    .dataTables_wrapper .col-sm-12.col-md-5 {
        width: 100% !important;
        padding: 0 !important;
    }
    
    /* Additional override to ensure left positioning */
    .dataTables_wrapper .dataTables_paginate.paging_simple_numbers {
        text-align: left !important;
        float: left !important;
    }
    
    .dataTables_wrapper .dataTables_paginate.paging_simple_numbers .paginate_button {
        color: #18375d !important;
        border-color: #18375d !important;
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
    
    .btn-action-add-live {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }

    .btn-action-add-live:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }

    .btn-action-edit:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-edit:hover {
        background-color: #2d5a47;
        border-color: #2d5a47;
        color: white;
    }
    
    .btn-action-view-livestock, .btn-action-report-livestock {
        background-color: #18375d;
        border-color: #18375d;
        color: white;
    }
    
    .btn-action-view-livestock:hover, .btn-action-report-livestock:hover {
        background-color: #e69500;
        border-color: #e69500;
        color: white;
    }

    /* Header action buttons styling to match Edit/Delete buttons */
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

    .btn-action-view-live {
        background-color: #18375d;
        border-color: #18375d;
        color: white;
    }

    .btn-action-view-live:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }

    .btn-action-qr {
        background-color: #4466ca;
        border-color: #4466ca;
        color: white;
    }

    .btn-action-qr:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }

    .btn-action-issue {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }

    .btn-action-issue:hover {
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
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: white !important;
    }
    
    .btn-action-print:hover {
        background-color: #5a6268 !important;
        border-color: #5a6268 !important;
        color: white !important;
    }
    
    .btn-action-cancel {
        background-color: #6c757d ;
        border-color: #6c757d ;
        color: white ;
    }
    
    .btn-action-refresh-, .btn-action-refresh- {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-refresh-:hover, .btn-action-refresh-:hover {
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
    
    /* Add Livestock Modal Styling */
#addLivestockModal .modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
}

#addLivestockModal .modal-header {
    background: #18375d !important;
    color: white !important;
    border-bottom: none !important;
    border-radius: 12px 12px 0 0 !important;
}

#addLivestockModal .modal-title {
    color: white !important;
    font-weight: 600;
}

#addLivestockModal .modal-body {
    padding: 2rem;
    background: white;
}

#addLivestockModal .modal-body h6 {
    color: #18375d !important;
    font-weight: 600 !important;
    border-bottom: 2px solid #e3e6f0;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem !important;
}

#addLivestockModal .modal-body p {
    margin-bottom: 0.75rem;
    color: #333 !important;
}

#addLivestockModal .modal-body strong {
    color: #5a5c69 !important;
    font-weight: 600;
}

/* Style all labels inside Add Livestock form */
#addLivestockModal .form-group label {
    font-weight: 600;
    color: #18375d;
    display: inline-block;
    margin-bottom: 0.5rem;
}

/* Edit Livestock Modal Styling */
#editLivestockModal .modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
}

#editLivestockModal .modal-header {
    background: #18375d !important;
    color: white !important;
    border-bottom: none !important;
    border-radius: 12px 12px 0 0 !important;
}

#editLivestockModal .modal-title {
    color: white !important;
    font-weight: 600;
}

#editLivestockModal .modal-body {
    padding: 2rem;
    background: white;
}

#editLivestockModal .modal-body h6 {
    color: #18375d !important;
    font-weight: 600 !important;
    border-bottom: 2px solid #e3e6f0;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem !important;
}

#editLivestockModal .modal-body p {
    margin-bottom: 0.75rem;
    color: #333 !important;
}

#editLivestockModal .modal-body strong {
    color: #5a5c69 !important;
    font-weight: 600;
}

/* Style all labels inside Edit Livestock form */
#editLivestockModal .form-group label {
    font-weight: 600;
    color: #18375d;
    display: inline-block;
    margin-bottom: 0.5rem;
}

/* Delete Confirmation Modal Styling */
#deleteConfirmationModal .modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
}

#deleteConfirmationModal .modal-header {
    background: #18375d !important;
    color: white !important;
    border-bottom: none !important;
    border-radius: 12px 12px 0 0 !important;
}

#deleteConfirmationModal .modal-title {
    color: white !important;
    font-weight: 600;
}

#deleteConfirmationModal .modal-body {
    padding: 2rem;
    background: white;
}

#deleteConfirmationModal .modal-body p {
    margin-bottom: 0.75rem;
    color: #333 !important;
}

#deleteConfirmationModal .modal-footer {
    background: white;
    border-top: 1px solid #e3e6f0;
}

/* User Details Modal Styling */
    #livestockDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #livestockDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #livestockDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #livestockDetailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #livestockDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #livestockDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #livestockDetailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }
    /* Style all labels inside form Modal */
    #livestockDetailsModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }

    /* User Details Modal Styling */
    #issueAlertModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #issueAlertModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #issueAlertModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #issueAlertModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #issueAlertModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #issueAlertModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #issueAlertModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }
    /* Style all labels inside form Modal */
    #issueAlertModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }


    /* Ensure table has enough space for actions column */
    .table th:last-child,
    .table td:last-child {
        min-width: 200px;
        width: auto;
        text-align: center;
        vertical-align: middle;
    }
    
    /* Responsive action buttons */
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
</style>
@endpush

@section('content')
    <div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
        <h1>
            <i class="fas fa-clipboard-list"></i>Livestock Management
        </h1>
        <p>Select a farmer to view and manage their livestock</p>
    </div>

    <!-- Farmer Selection Section -->
     <div class="card shadow mb-4 fade-in" id="farmerSelectionCard">
        <div class="card-body d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start">
            <h6 class="mb-0">
                <i class="fas fa-users"></i>
                Select Farmer
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
                    <input type="text" class="form-control" placeholder="Search farmers..." id="farmerSearch">
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-center">
                    <button class="btn-action btn-action-refresh-farmers" onclick="refreshPendingFarmersTable('pendingFarmersTable')">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
                <div class="table-responsive">
                <table class="table table-bordered table-hover" id="farmersTable">
                    <thead>
                        <tr>
                            <th>Farmer ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Total Livestock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="farmersTableBody">
                        <!-- Farmers will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    

    <!-- Livestock Section (Initially Hidden) -->
    <div class="card shadow mb-4" id="livestockCard" style="display: none;" id="selectedLivestockId">
        <div class="card-body d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start">
            <h6 class="mb-0">
                <i class="fas fa-list"></i>
                List of Livestock
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
                    <input type="text" class="form-control" placeholder="Search livestock..." id="activeSearch">
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-center">
                    <button class="btn-action btn-secondary btn-sm" onclick="backToFarmers()">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button class="btn-action btn-action-add-live" data-toggle="modal" data-target="#addLivestockModal">
                        <i class="fas fa-plus"></i> Add Livestock
                    </button>
                    <button class="btn-action btn-action-refresh-admins" onclick="refreshAdminsTable('activeFarmersTable')">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
            
            <!-- Livestock Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="livestockTable">
                    <thead>
                        <tr>
                            <th>Tag Number</th>
                            <th>Type</th>
                            <th>Breed</th>
                            <th>Gender</th>
                            <th>Farm</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="livestockTableBody">
                        <!-- Livestock will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Add Livestock Modal -->
<div class="modal fade" id="addLivestockModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus mr-2"></i> Add New Livestock</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="addLivestockForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="selectedFarmerId" name="farmer_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tag Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="tag_number" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type <span class="text-danger">*</span></label>
                                <select class="form-control" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="cow">Cow</option>
                                    <option value="buffalo">Buffalo</option>
                                    <option value="goat">Goat</option>
                                    <option value="sheep">Sheep</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Breed <span class="text-danger">*</span></label>
                                <select class="form-control" name="breed" required>
                                    <option value="">Select Breed</option>
                                    <option value="holstein">Holstein</option>
                                    <option value="jersey">Jersey</option>
                                    <option value="guernsey">Guernsey</option>
                                    <option value="ayrshire">Ayrshire</option>
                                    <option value="brown_swiss">Brown Swiss</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Farm <span class="text-danger">*</span></label>
                                <select class="form-control" name="farm_id" required>
                                    <option value="">Select Farm</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Registry ID</label>
                                <input type="text" class="form-control" name="registry_id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Birth Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="birth_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gender <span class="text-danger">*</span></label>
                                <select class="form-control" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Weight (kg)</label>
                                <input type="number" class="form-control" name="weight" min="0" step="0.1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Health Status <span class="text-danger">*</span></label>
                                <select class="form-control" name="health_status" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="sick">Sick</option>
                                    <option value="recovering">Recovering</option>
                                    <option value="under_treatment">Under Treatment</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select class="form-control" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Natural Marks</label>
                                <input type="text" class="form-control" name="natural_marks">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Property Number</label>
                                <input type="text" class="form-control" name="property_no">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Acquisition Date</label>
                                <input type="date" class="form-control" name="acquisition_date">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Acquisition Cost (₱)</label>
                                <input type="number" class="form-control" name="acquisition_cost" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sire ID</label>
                                <input type="text" class="form-control" name="sire_id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sire Name</label>
                                <input type="text" class="form-control" name="sire_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Dam ID</label>
                                <input type="text" class="form-control" name="dam_id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Dam Name</label>
                                <input type="text" class="form-control" name="dam_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Dispersal From</label>
                                <input type="text" class="form-control" name="dispersal_from">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Owned By</label>
                                <input type="text" class="form-control" name="owned_by">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea class="form-control" name="remarks" rows="3" placeholder="Additional remarks about the livestock..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Description/Notes</label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Additional notes about the livestock..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-action btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-action btn-action-view-live">Add Livestock</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Livestock Details Modal -->
<div class="modal fade" id="livestockDetailsModal" tabindex="-1" role="dialog" aria-labelledby="livestockDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="livestockDetailsLabel">
                    <i class="fas fa-cow mr-2"></i>
                    Livestock Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <div id="livestockDetailsContent"></div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-action btn-secondary" data-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn-action btn-action-ok" onclick="openLivestockEditModal()">
                    Edit Livestock
                </button>
            </div>
        </div>
    </div>
</div>


<!-- QR Code Modal -->
<div class="modal fade" id="qrCodeModal" tabindex="-1" role="dialog" aria-labelledby="qrCodeLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-qrcode mr-2"></i>
                    QR Code
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div id="qrCodeContent"></div>
                <p class="mt-3" id="qrCodeText"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-action btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn-action btn-action-ok" onclick="downloadQRCode()">
                    Download
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Livestock Modal -->
<div class="modal fade" id="editLivestockModal" tabindex="-1" role="dialog" aria-labelledby="editLivestockLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLivestockLabel">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Livestock
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editLivestockForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="editLivestockId" name="livestock_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tag Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editTagNumber" name="tag_number" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editName" name="name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="editType" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="cow">Cow</option>
                                    <option value="buffalo">Buffalo</option>
                                    <option value="goat">Goat</option>
                                    <option value="sheep">Sheep</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Breed <span class="text-danger">*</span></label>
                                <select class="form-control" id="editBreed" name="breed" required>
                                    <option value="">Select Breed</option>
                                    <option value="holstein">Holstein</option>
                                    <option value="jersey">Jersey</option>
                                    <option value="guernsey">Guernsey</option>
                                    <option value="ayrshire">Ayrshire</option>
                                    <option value="brown_swiss">Brown Swiss</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Farm <span class="text-danger">*</span></label>
                                <select class="form-control" id="editFarmId" name="farm_id" required>
                                    <option value="">Select Farm</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Registry ID</label>
                                <input type="text" class="form-control" id="editRegistryId" name="registry_id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Birth Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="editBirthDate" name="birth_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gender <span class="text-danger">*</span></label>
                                <select class="form-control" id="editGender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Weight (kg)</label>
                                <input type="number" class="form-control" id="editWeight" name="weight" min="0" step="0.1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Health Status</label>
                                <select class="form-control" id="editHealthStatus" name="health_status">
                                    <option value="healthy">Healthy</option>
                                    <option value="sick">Sick</option>
                                    <option value="injured">Injured</option>
                                    <option value="pregnant">Pregnant</option>
                                    <option value="lactating">Lactating</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="editStatus" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Natural Marks</label>
                                <input type="text" class="form-control" id="editNaturalMarks" name="natural_marks">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Property Number</label>
                                <input type="text" class="form-control" id="editPropertyNo" name="property_no">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Acquisition Date</label>
                                <input type="date" class="form-control" id="editAcquisitionDate" name="acquisition_date">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Acquisition Cost (₱)</label>
                                <input type="number" class="form-control" id="editAcquisitionCost" name="acquisition_cost" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sire ID</label>
                                <input type="text" class="form-control" id="editSireId" name="sire_id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sire Name</label>
                                <input type="text" class="form-control" id="editSireName" name="sire_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Dam ID</label>
                                <input type="text" class="form-control" id="editDamId" name="dam_id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Dam Name</label>
                                <input type="text" class="form-control" id="editDamName" name="dam_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Dispersal From</label>
                                <input type="text" class="form-control" id="editDispersalFrom" name="dispersal_from">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Owned By</label>
                                <input type="text" class="form-control" id="editOwnedBy" name="owned_by">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea class="form-control" id="editRemarks" name="remarks" rows="3" placeholder="Additional remarks about the livestock..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Description/Notes</label>
                                <textarea class="form-control" id="editDescription" name="description" rows="3" placeholder="Additional notes about the livestock..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-action btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-action btn-action-ok">
                        Update Livestock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationLabel">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this livestock? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-action btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn-action btn-action-deletes" id="confirmDeleteBtn">
                    Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Issue Alert Modal -->
<div class="modal fade" id="issueAlertModal" tabindex="-1" role="dialog" aria-labelledby="issueAlertLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Issue Alert
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="issueAlertForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="alertLivestockId">
                    <div class="form-group">
                        <label for="issueType">Issue Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="issueType" required>
                            <option value="">Select Issue Type</option>
                            <option value="health">Health Issue</option>
                            <option value="injury">Injury</option>
                            <option value="production">Production Issue</option>
                            <option value="behavioral">Behavioral Issue</option>
                            <option value="environmental">Environmental Issue</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="issuePriority">Priority <span class="text-danger">*</span></label>
                        <select class="form-control" id="issuePriority" required>
                            <option value="">Select Priority</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="issueDescription">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="issueDescription" rows="4" required placeholder="Describe the issue in detail..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-action btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-action btn-action-ok">
                        Issue Alert
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .page-header {
        background: #18375d;
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }
    
    .farmer-link {
        color: #18375d;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
    }
    
    .farmer-link:hover {
        color: #122a47;
        text-decoration: underline;
    }
    
    .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
    .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
    .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
    .border-left-primary { border-left: 0.25rem solid #18375d !important; }
    
    .gap-2 { gap: 0.5rem !important; }
</style>
@endpush

@push('scripts')
<script>
    let selectedFarmerId = null;
    let selectedFarmerName = '';
    let selectedLivestockId = null;

   $(document).ready(function() {
        console.log('Document ready, loading farmers...');
        loadFarmers();
        
        // 🔍 Search functionality for Farmers Table
        $('#farmerSearch').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('#farmersTable tbody tr').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(searchTerm) > -1);
            });
        });
        // Search functionality
        $('#activeSearch').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('#livestockTable tbody tr').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(searchTerm) > -1);
            });
        });
        // 🔍 Search functionality for Livestock Table
        $('#livestockSearch').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('#livestockTable tbody tr').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(searchTerm) > -1);
            });
        });
    });


// Refresh Pending Farmers Table
function refreshPendingFarmersTable() {
    const refreshBtn = document.querySelector('.btn-action-refresh-farmers');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    refreshBtn.disabled = true;

    // Use unique flag for farmers
    sessionStorage.setItem('showRefreshNotificationFarmers', 'true');

    setTimeout(() => {
        location.reload();
    }, 1000);
}
// Check notifications after reload
$(document).ready(function() {
    if (sessionStorage.getItem('showRefreshNotificationFarmers') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationFarmers');
        setTimeout(() => {
            showNotification('Farmers data refreshed successfully!', 'success');
        }, 500);
    }
});
// Refresh Admins Table
function refreshAdminsTable() {
    const refreshBtn = document.querySelector('.btn-action-refresh-admins');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    refreshBtn.disabled = true;

    // Save which tab is active
    const activeTab = $('.nav-tabs .nav-link.active').attr('id'); 
    sessionStorage.setItem('activeTab', activeTab);

    // Use unique flag for admins refresh
    sessionStorage.setItem('showRefreshNotificationAdmins', 'true');

    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Restore tab + check notifications after reload
$(document).ready(function () {
    // Restore active tab
    const activeTab = sessionStorage.getItem('activeTab');
    if (activeTab) {
        $(`#${activeTab}`).tab('show'); 
        sessionStorage.removeItem('activeTab');
    }

    // Show notification if refresh happened
    if (sessionStorage.getItem('showRefreshNotificationAdmins') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationAdmins');
        setTimeout(() => {
            showNotification('Livestock data refreshed successfully!', 'success');
        }, 500);
    }
});


    let farmersTable;

$(document).ready(function () {
    // Initialize DataTables
    initializeDataTables();
    
    // Load data
    loadfarmersTable();
    updateStats();

    // Custom search functionality
    $('#pendingSearch').on('keyup', function() {
        pendingFarmersTable.search(this.value).draw();
    });
    
});

    function loadFarmers() {
        $('#farmersTableBody').html('<tr><td colspan="7" class="text-center">Loading farmers...</td></tr>');
        
        $.ajax({
            url: '{{ route("admin.livestock.farmers") }}',
            method: 'GET',
            success: function(response) {
                console.log('Farmers response:', response);
                if (response.success) {
                    let html = '';
                    if (response.data.length === 0) {
                        html = '<tr><td colspan="7" class="text-center">No farmers found</td></tr>';
                    } else {
                        response.data.forEach(farmer => {
                            const displayName = farmer.first_name && farmer.last_name 
                                ? `${farmer.first_name} ${farmer.last_name}` 
                                : farmer.name || 'N/A';
                            
                            html += `
                                <tr>
                                    <td>${farmer.id}</td>
                                    <td><a href="#" class="farmer-link" onclick="selectFarmer('${farmer.id}', '${displayName}')">${displayName}</a></td>
                                    <td>${farmer.email}</td>
                                    <td>${farmer.contact_number || 'N/A'}</td>
                                    <td>${farmer.livestock_count || 0}</td>
                                    <td><span class="badge badge-${getStatusBadgeClass(farmer.status)}">${farmer.status}</span></td>
                                    <td>
                                        <button class="btn-action btn-action-view-livestock" onclick="selectFarmer('${farmer.id}', '${displayName}')">
                                            <i class="fas fa-eye"></i> View Livestock
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    $('#farmersTableBody').html(html);
                } else {
                    $('#farmersTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading farmers: ' + (response.message || 'Unknown error') + '</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr, status, error);
                console.log('Response Text:', xhr.responseText);
                $('#farmersTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading farmers. Check console for details.</td></tr>');
            }
        });
    }

    function selectFarmer(farmerId, farmerName) {
        selectedFarmerId = farmerId;
        selectedFarmerName = farmerName;
        
        $('#selectedFarmerName').text(farmerName);
        $('#selectedFarmerId').val(farmerId);
        
        $('#farmerSelectionCard').hide();
        $('#livestockCard').show();
        
        loadFarmerLivestock(farmerId);
        loadFarmerFarms(farmerId);
    }

    function backToFarmers() {
        selectedFarmerId = null;
        selectedFarmerName = '';
        
        $('#farmerSelectionCard').show();
        $('#livestockCard').hide();
        
        $('#livestockTableBody').empty();
    }

    function loadFarmerLivestock(farmerId) {
        $('#livestockTableBody').html('<tr><td colspan="7" class="text-center">Loading livestock...</td></tr>');
        
        $.ajax({
            url: `{{ route("admin.livestock.farmer-livestock", ":id") }}`.replace(':id', farmerId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let html = '';
                    if (response.data.livestock.length === 0) {
                        html = '<tr><td colspan="7" class="text-center">No livestock found for this farmer</td></tr>';
                    } else {
                        response.data.livestock.forEach(animal => {
                            html += `
                                <tr>
                                    <td>${animal.tag_number}</td>
                                    <td>${animal.type}</td>
                                    <td>${animal.breed}</td>
                                    <td>${animal.gender}</td>
                                    <td>${animal.farm ? animal.farm.name : 'N/A'}</td>
                                    <td>
                                        <span class="badge badge-${animal.status === 'active' ? 'success' : 'danger'}">
                                            ${animal.status === 'active' ? 'Active' : 'Inactive'}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action btn-action-view-live" onclick="viewLivestockDetails('${animal.id}')" title="View Details">
                                                <i class="fas fa-eye"></i>
                                                <span>View</span>
                                            </button>
                                            <button class="btn-action btn-action-qr" onclick="generateQRCode('${animal.id}')" title="Generate QR Code">
                                                <i class="fas fa-qrcode"></i>
                                                <span>QR Code</span>
                                            </button>
                                            <button class="btn-action btn-action-issue" onclick="issueAlert('${animal.id}')" title="Issue Alert">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <span>Alert</span>
                                            </button>
                                            <button class="btn-action btn-action-edit" onclick="editLivestock('${animal.id}')" title="Edit">
                                                <i class="fas fa-edit"></i>
                                                <span>Edit</span>
                                            </button>
                                            <button class="btn-action btn-action-deletes" onclick="deleteLivestock('${animal.id}')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                                <span>Delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    $('#livestockTableBody').html(html);
                    updateFarmerStats(response.data.stats);
                } else {
                    $('#livestockTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading livestock</td></tr>');
                }
            },
            error: function() {
                $('#livestockTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading livestock</td></tr>');
            }
        });
    }

    function loadFarmerFarms(farmerId) {
        $.ajax({
            url: `{{ route("admin.livestock.farmer-farms", ":id") }}`.replace(':id', farmerId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const farmSelect = $('select[name="farm_id"]');
                    farmSelect.empty().append('<option value="">Select Farm</option>');
                    
                    response.data.forEach(farm => {
                        farmSelect.append(`<option value="${farm.id}">${farm.name}</option>`);
                    });
                }
            }
        });
    }

    function loadFarmerFarmsForEdit(farmerId, selectedFarmId) {
        $.ajax({
            url: `{{ route("admin.livestock.farmer-farms", ":id") }}`.replace(':id', farmerId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const farmSelect = $('#editFarmId');
                    farmSelect.empty().append('<option value="">Select Farm</option>');
                    
                    response.data.forEach(farm => {
                        const selected = farm.id == selectedFarmId ? 'selected' : '';
                        farmSelect.append(`<option value="${farm.id}" ${selected}>${farm.name}</option>`);
                    });
                }
            }
        });
    }

    function updateFarmerStats(stats) {
        $('#farmerTotalLivestock').text(stats.total || 0);
        $('#farmerActiveLivestock').text(stats.active || 0);
        $('#farmerInactiveLivestock').text(stats.inactive || 0);
        $('#farmerTotalFarms').text(stats.farms || 0);
    }

    function refreshData() {
        if (selectedFarmerId) {
            loadFarmerLivestock(selectedFarmerId);
        } else {
            loadFarmers();
        }
    }

    function getStatusBadgeClass(status) {
        switch(status) {
            case 'approved': return 'success';
            case 'pending': return 'warning';
            case 'suspended': return 'danger';
            default: return 'secondary';
        }
    }


    function editLivestock(livestockId) {
        // Load livestock data and show edit modal
        $.ajax({
            url: `{{ route("admin.livestock.details", ":id") }}`.replace(':id', livestockId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const livestock = response.data;
                    
                    // Populate the edit form
                    $('#editLivestockId').val(livestock.id);
                    $('#editTagNumber').val(livestock.tag_number);
                    $('#editName').val(livestock.name);
                    $('#editType').val(livestock.type);
                    $('#editBreed').val(livestock.breed);
                    $('#editBirthDate').val(livestock.birth_date);
                    $('#editGender').val(livestock.gender);
                    $('#editWeight').val(livestock.weight);
                    $('#editHealthStatus').val(livestock.health_status);
                    $('#editStatus').val(livestock.status);
                    $('#editRegistryId').val(livestock.registry_id);
                    $('#editNaturalMarks').val(livestock.natural_marks);
                    $('#editPropertyNo').val(livestock.property_no);
                    $('#editAcquisitionDate').val(livestock.acquisition_date);
                    $('#editAcquisitionCost').val(livestock.acquisition_cost);
                    $('#editSireId').val(livestock.sire_id);
                    $('#editSireName').val(livestock.sire_name);
                    $('#editDamId').val(livestock.dam_id);
                    $('#editDamName').val(livestock.dam_name);
                    $('#editDispersalFrom').val(livestock.dispersal_from);
                    $('#editOwnedBy').val(livestock.owned_by);
                    $('#editRemarks').val(livestock.remarks);
                    $('#editDescription').val(livestock.description);
                    
                    // Load farms for the farmer
                    loadFarmerFarmsForEdit(selectedFarmerId, livestock.farm_id);
                    
                    // Show the edit modal
                    $('#editLivestockModal').modal('show');
                } else {
                    showNotification('Error loading livestock data', 'danger');
                }
            },
            error: function() {
                showNotification('Error loading livestock data', 'danger');
            }
        });
    }

    function deleteLivestock(livestockId) {
        // Store the livestock ID for the confirmation
        $('#confirmDeleteBtn').data('livestock-id', livestockId);
        
        // Show the confirmation modal
        $('#deleteConfirmationModal').modal('show');
    }

    // Handle add livestock form submission
    $('#addLivestockForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('farmer_id', selectedFarmerId);
        
        $.ajax({
            url: '{{ route("admin.livestock.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#addLivestockModal').modal('hide');
                $('#addLivestockForm')[0].reset();
                loadFarmerLivestock(selectedFarmerId);
                showNotification('Livestock added successfully!', 'success');
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                let errorMessage = 'Failed to add livestock. ';
                Object.values(errors).forEach(error => {
                    errorMessage += error[0] + ' ';
                });
                showNotification(errorMessage, 'danger');
            }
        });
    });

    // Handle edit livestock form submission
    $('#editLivestockForm').on('submit', function(e) {
        e.preventDefault();
        
        const livestockId = $('#editLivestockId').val();
        const formData = new FormData(this);
        
        $.ajax({
            url: `{{ route("admin.livestock.update", ":id") }}`.replace(':id', livestockId),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-HTTP-Method-Override': 'PUT'
            },
            success: function(response) {
                $('#editLivestockModal').modal('hide');
                $('#editLivestockForm')[0].reset();
                loadFarmerLivestock(selectedFarmerId);
                showNotification('Livestock updated successfully!', 'success');
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                let errorMessage = 'Failed to update livestock. ';
                Object.values(errors).forEach(error => {
                    errorMessage += error[0] + ' ';
                });
                showNotification(errorMessage, 'danger');
            }
        });
    });

    // Handle delete confirmation
    $('#confirmDeleteBtn').on('click', function() {
        const livestockId = $(this).data('livestock-id');
        
        $.ajax({
            url: `{{ route('admin.livestock.destroy', ':id') }}`.replace(':id', livestockId),
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteConfirmationModal').modal('hide');
                showNotification('Livestock deleted successfully!', 'success');
                
                // Reload the livestock list
                if (selectedFarmerId) {
                    loadFarmerLivestock(selectedFarmerId);
                }
            },
            error: function(xhr) {
                $('#deleteConfirmationModal').modal('hide');
                showNotification('Failed to delete livestock', 'danger');
            }
        });
    });

    function viewLivestockDetails(livestockId) {
        // Store the livestock ID for editing
        selectedLivestockId = livestockId;
        
        $.ajax({
            url: `{{ route("admin.livestock.details", ":id") }}`.replace(':id', livestockId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const livestock = response.data;
                    $('#livestockDetailsContent').html(`
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Basic Information</h6>
                                <p><strong>Tag Number:</strong> ${livestock.tag_number || 'N/A'}</p>
                                <p><strong>Type:</strong> ${livestock.type || 'N/A'}</p>
                                <p><strong>Breed:</strong> ${livestock.breed || 'N/A'}</p>
                                <p><strong>Gender:</strong> ${livestock.gender || 'N/A'}</p>
                                <p><strong>Birth Date:</strong> ${livestock.birth_date ? new Date(livestock.birth_date).toLocaleDateString() : 'N/A'}</p>
                            </div>

                            <div class="col-md-6">
                                <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Farm Information</h6>
                                <p><strong>Farm:</strong> ${livestock.farm ? livestock.farm.name : 'N/A'}</p>
                                <p><strong>Status:</strong> 
                                    <span class="badge badge-${livestock.status === 'active' ? 'success' : 'danger'}">
                                        ${livestock.status || 'N/A'}
                                    </span>
                                </p>
                                <p><strong>Health Status:</strong> ${livestock.health_status || 'N/A'}</p>
                                <p><strong>Weight:</strong> ${livestock.weight ? livestock.weight + ' kg' : 'N/A'}</p>
                                <p><strong>Registration Date:</strong> ${livestock.created_at ? new Date(livestock.created_at).toLocaleDateString() : 'N/A'}</p>
                            </div>
                        </div>

                        ${livestock.description ? `
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="text-primary">Description</h6>
                                <p>${livestock.description}</p>
                            </div>
                        </div>
                        ` : ''}

                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="mb-3" style="color: #18375d; font-weight: 600;">QR Code</h6>
                                ${livestock.qr_code_generated ? `
                                    <div class="text-center">
                                        <img src="${livestock.qr_code_url}" alt="QR Code" class="img-fluid mb-3" style="max-width: 150px;">
                                        <p class="text-muted small mb-2">Generated by: ${livestock.qr_code_generator ? livestock.qr_code_generator.name : 'Unknown'}</p>
                                        <p class="text-muted small mb-3">Generated on: ${livestock.qr_code_generated_at ? new Date(livestock.qr_code_generated_at).toLocaleDateString() : 'Unknown'}</p>
                                        <button class="btn-action btn-action-ok btn-sm" onclick="downloadQRCodeFromDetails('${livestock.qr_code_url}', '${livestock.tag_number}')">
                                            <i class="fas fa-download"></i> Download QR Code
                                        </button>
                                    </div>
                                ` : `
                                    <div class="text-center">
                                        <p class="text-muted">No QR code generated yet</p>
                                        <button class="btn-action btn-action-qr btn-sm" onclick="generateQRCodeFromDetails('${livestock.id}')">
                                            <i class="fas fa-qrcode"></i> Generate QR Code
                                        </button>
                                    </div>
                                `}
                            </div>
                        </div>
                    `);
                    $('#livestockDetailsModal').modal('show');
                } else {
                    showNotification('Error loading livestock details', 'danger');
                }
            },
            error: function() {
                showNotification('Error loading livestock details', 'danger');
            }
        });
    }

    function generateQRCode(livestockId) {
        // Show loading state
        showNotification('Generating QR Code...', 'info');
        
        $.ajax({
            url: `{{ route("admin.livestock.qr-code", ":id") }}`.replace(':id', livestockId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const generatedDate = response.generated_at ? new Date(response.generated_at).toLocaleDateString() : 'Just now';
                    $('#qrCodeContent').html(`
                        <div class="text-center">
                            <img src="${response.qr_code}" alt="QR Code" class="img-fluid mb-3" style="max-width: 200px;">
                            <p class="text-muted small">Generated by: ${response.generated_by}</p>
                            <p class="text-muted small">Generated on: ${generatedDate}</p>
                        </div>
                    `);
                    $('#qrCodeText').text(`QR Code for ${response.livestock_id}`);
                    $('#qrCodeModal').modal('show');
                    showNotification('QR Code generated successfully!', 'success');
                } else {
                    showNotification('Error generating QR code: ' + (response.message || 'Unknown error'), 'danger');
                }
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON?.message || 'Unknown error';
                showNotification('Error generating QR code: ' + errorMessage, 'danger');
            }
        });
    }

    function downloadQRCode() {
        const img = $('#qrCodeContent img');
        if (img.length > 0) {
            const livestockId = $('#qrCodeText').text().replace('QR Code for ', '');
            const link = document.createElement('a');
            link.download = `QR_Code_${livestockId}.png`;
            link.href = img.attr('src');
            link.click();
            showNotification('QR Code downloaded successfully!', 'success');
        } else {
            showNotification('No QR code available to download', 'warning');
        }
    }

    function downloadQRCodeFromDetails(qrCodeUrl, tagNumber) {
        const link = document.createElement('a');
        link.download = `QR_Code_${tagNumber}.png`;
        link.href = qrCodeUrl;
        link.click();
        showNotification('QR Code downloaded successfully!', 'success');
    }

    function generateQRCodeFromDetails(livestockId) {
        // Show loading state
        showNotification('Generating QR Code...', 'info');
        
        $.ajax({
            url: `{{ route("admin.livestock.qr-code", ":id") }}`.replace(':id', livestockId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    showNotification('QR Code generated successfully!', 'success');
                    // Refresh the livestock details to show the new QR code
                    viewLivestockDetails(livestockId);
                } else {
                    showNotification('Error generating QR code: ' + (response.message || 'Unknown error'), 'danger');
                }
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON?.message || 'Unknown error';
                showNotification('Error generating QR code: ' + errorMessage, 'danger');
            }
        });
    }

    function issueAlert(livestockId) {
        $('#alertLivestockId').val(livestockId);
        $('#issueAlertModal').modal('show');
    }

    $('#issueAlertForm').on('submit', function(e) {
        e.preventDefault();
        
        const livestockId = $('#alertLivestockId').val();
        const issueType = $('#issueType').val();
        const priority = $('#issuePriority').val();
        const description = $('#issueDescription').val();

        $.ajax({
            url: '{{ route("admin.livestock.issue-alert") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                livestock_id: livestockId,
                issue_type: issueType,
                priority: priority,
                description: description
            },
            success: function(response) {
                if (response.success) {
                    $('#issueAlertModal').modal('hide');
                    // Reset form
                    $('#issueType').val('');
                    $('#issuePriority').val('');
                    $('#issueDescription').val('');
                    
                    showNotification('Issue alert created successfully!', 'success');
                } else {
                    showNotification(response.message || 'Error creating issue alert', 'danger');
                }
            },
            error: function() {
                showNotification('Error creating issue alert', 'danger');
            }
        });
    });

    function openLivestockEditModal() {
        // Get the livestock ID from the current livestock details
        // We need to store the current livestock ID when viewing details
        if (selectedLivestockId) {
            // Close the details modal
            $('#livestockDetailsModal').modal('hide');
            
            // Open the edit modal with the livestock data
            editLivestock(selectedLivestockId);
        } else {
            showNotification('No livestock selected for editing', 'warning');
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
