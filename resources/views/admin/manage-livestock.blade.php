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
#livestockTable td, 
#livestockTable th {
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
        background-color: #18375d;
        border-color: #18375d;
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

 /* ===== Edit Button ===== */
.btn-action-edit {
    background-color: white;
    border: 1px solid #387057 !important;
    color: #387057; /* blue text */
}

.btn-action-edit:hover {
    background-color: #387057; /* yellow on hover */
    border: 1px solid #387057 !important;
    color: white;
}

.btn-action-issue {
    background-color: white;
    border: 1px solid #fca700 !important;
    color: #fca700; /* blue text */
}

.btn-action-issue:hover {
    background-color: #fca700; /* yellow on hover */
    border: 1px solid #fca700 !important;
    color: white;
}
    
.btn-action-qr {
    background-color: white;
    border: 1px solid #4466ca !important;
    color: #4466ca; /* blue text */
}

.btn-action-qr:hover {
    background-color: #4466ca; /* yellow on hover */
    border: 1px solid #4466ca !important;
    color: white;
}

.btn-action-ok {
    background-color: white;
    border: 1px solid #18375d !important;
    color: #18375d; /* blue text */
}

.btn-action-ok:hover {
    background-color: #18375d; /* yellow on hover */
    border: 1px solid #18375d !important;
    color: white;
}

.btn-action-deletes {
    background-color: white !important;
    border: 1px solid #dc3545 !important;
    color: #dc3545 !important; /* blue text */
}

.btn-action-deletes:hover {
    background-color: #dc3545 !important; /* yellow on hover */
    border: 1px solid #dc3545 !important;
    color: white !important;
}

.btn-action-refresh-farmers, .btn-action-refresh-admins {
    background-color: white !important;
    border: 1px solid #fca700 !important;
    color: #fca700 !important; /* blue text */
}
    
.btn-action-refresh-farmers:hover, .btn-action-refresh-admins:hover {
    background-color: #fca700 !important; /* yellow on hover */
    border: 1px solid #fca700 !important;
    color: white !important;
}

.btn-action-back {
    background-color: white !important;
    border: 1px solid #495057 !important;
    color: #495057 !important;
}

.btn-action-back:hover {
    background-color: #495057 !important; /* yellow on hover */
    border: 1px solid #495057 !important;
    color: white !important;
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
#addLivestockModal form {
  text-align: left;
}

#addLivestockModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#addLivestockModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#addLivestockModal .form-control,
#addLivestockModal select.form-control,
#addLivestockModal textarea.form-control {
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
#addLivestockModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#addLivestockModal .form-control:focus {
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
#issueAlertModal form {
  text-align: left;
}

#issueAlertModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#issueAlertModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#issueAlertModal .form-control,
#issueAlertModal select.form-control,
#issueAlertModal textarea.form-control {
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
#issueAlertModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#issueAlertModal .form-control:focus {
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
#addLivestockModal .modal-footer {
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
   #issueAlertModal .form-control {
    font-size: 14px;
  }

  .btn-ok,
  .btn-delete,
  .btn-approves {
    width: 100%;
    margin-top: 0.5rem;
  }
}

/* ============================
   FORM ELEMENT STYLES
   ============================ */
#adminBreedingRecordModal form {
  text-align: left;
}

#adminBreedingRecordModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#adminBreedingRecordModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#adminBreedingRecordModal .form-control,
#adminBreedingRecordModal select.form-control,
#adminBreedingRecordModal textarea.form-control {
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
#adminBreedingRecordModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#adminBreedingRecordModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}

/* Match Growth modal styling to Breeding modal */
#adminGrowthRecordModal form {
  text-align: left;
}

#adminGrowthRecordModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#adminGrowthRecordModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#adminGrowthRecordModal .form-control,
#adminGrowthRecordModal select.form-control,
#adminGrowthRecordModal textarea.form-control {
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
#adminGrowthRecordModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#adminGrowthRecordModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}

#adminHealthRecordModal form {
  text-align: left;
}

#adminHealthRecordModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#adminHealthRecordModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#adminHealthRecordModal .form-control,
#adminHealthRecordModal select.form-control,
#adminHealthRecordModal textarea.form-control {
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
#adminHealthRecordModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#adminHealthRecordModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}
/* ============================
   FORM ELEMENT STYLES
   ============================ */
#adminProductionRecordModal form {
  text-align: left;
}

#adminProductionRecordModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#adminProductionRecordModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#adminProductionRecordModal .form-control,
#adminProductionRecordModal select.form-control,
#adminProductionRecordModal textarea.form-control {
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
#adminProductionRecordModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#adminProductionRecordModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}


/* MODAL BODY */
.smart-detail .modal-body {
    background: #ffffff;
    padding: 5rem 5rem; /* More spacious layout */
    border-radius: 1rem;
    max-height: 130vh; /* Extended vertical height */
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

/* Wider modal container */
.smart-detail .modal-dialog {
    max-width: 98%; /* Extended width for large screens */
    width: 100%;
    margin: 2.5rem auto;
}

/* Detail Section */
.smart-detail .detail-wrapper {
    background: #f9fafb;
    border-radius: 1.25rem;
    padding: 3.5rem; /* Increased inner spacing */
    font-size: 1.05rem;
    line-height: 1.8;
}

/* Detail Rows */
.smart-detail .detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px dashed #ddd;
    padding: 1rem 0; /* Taller row spacing */
}

.smart-detail .detail-row:last-child {
    border-bottom: none;
}

.smart-detail .detail-label {
    font-weight: 600;
    color: #1b3043;
    flex: 1;
}

.smart-detail .detail-value {
    color: #333;
    text-align: right;
    flex: 1;
}

/* Footer */
#livestockDetailsModal .modal-footer {
    text-align: center;
    border-top: 1px solid #e5e7eb;
    padding-top: 2rem;
    margin-top: 2.5rem;
}

/* RESPONSIVE ADJUSTMENTS */
@media (max-width: 1200px) {
    .smart-detail .modal-dialog {
        max-width: 97%;
    }

    .smart-detail .modal-body {
        padding: 4rem;
        max-height: 125vh;
    }

    .smart-detail .detail-wrapper {
        padding: 2.5rem;
        font-size: 1rem;
    }
}

@media (max-width: 992px) {
    .smart-detail .modal-body {
        padding: 3rem;
        max-height: 200vh;
    }

    .smart-detail .detail-wrapper {
        padding: 2rem;
        font-size: 0.95rem;
    }
}

@media (max-width: 768px) {
    .smart-detail .modal-dialog {
        max-width: 95%;
    }

    .smart-detail .modal-body {
        padding: 2rem;
        max-height: 200vh;
    }

    .smart-detail .detail-wrapper {
        padding: 1.75rem;
        line-height: 1.7;
    }

    .smart-detail p {
        text-align: left;
        font-size: 0.95rem;
    }
}

@media (max-width: 576px) {
    .smart-detail .modal-body {
        padding: 1.5rem;
        max-height: 200vh;
    }

    .smart-detail .detail-wrapper {
        padding: 1.25rem;
    }

    .smart-detail .detail-row {
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
        gap: 0.4rem;
    }

    .smart-detail .detail-value {
        text-align: left;
        width: 100%;
    }
}
/* SMART DETAIL MODAL TEMPLATE */
.smart-detail .modal-content {
    border-radius: 1.5rem;
    border: none;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    background-color: #ffffff;
    transition: all 0.3s ease-in-out;
}

/* LIVESTOCK DETAILS MODAL (Specific Styling) */
#livestockDetailsModal .modal-dialog {
    max-width: 80%; /* Wider for large screens */
    width: 100%;
    margin: 2.5rem auto;
}

#livestockDetailsModal .modal-content {
    border-radius: 1.5rem;
    border: none;
    background: #fefefe;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.18);
}

#livestockDetailsModal .modal-header {
    background: linear-gradient(135deg, #18375d, #1e4976);
    color: #fff;
    border-top-left-radius: 1.5rem;
    border-top-right-radius: 1.5rem;
    text-align: center;
    justify-content: center;
    padding: 1.5rem;
}

#livestockDetailsModal .modal-header h5 {
    font-weight: 700;
    font-size: 1.25rem;
    margin: 0;
    letter-spacing: 0.5px;
}

#livestockDetailsModal .modal-body {
    background: #ffffff;
    padding: 5rem 5rem; /* Spacious layout */
    border-radius: 1rem;
    max-height: 130vh; /* Extended vertical space */
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

/* Scrollbar styling */
#livestockDetailsModal .modal-body::-webkit-scrollbar {
    width: 8px;
}
#livestockDetailsModal .modal-body::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 5px;
}

/* Section Wrapper */
#livestockDetailsModal .detail-wrapper {
    background: #f9fafb;
    border-radius: 1.25rem;
    padding: 3.5rem;
    font-size: 1.05rem;
    line-height: 1.8;
}

/* Detail Rows */
#livestockDetailsModal .detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px dashed #d1d5db;
    padding: 1rem 0;
}

#livestockDetailsModal .detail-row:last-child {
    border-bottom: none;
}

#livestockDetailsModal .detail-label {
    font-weight: 600;
    color: #1b3043;
    flex: 1;
}

#livestockDetailsModal .detail-value {
    color: #333;
    text-align: right;
    flex: 1;
}

/* QR Section */
#livestockDetailsModal .qr-section {
    text-align: center;
    margin-top: 2rem;
}

#livestockDetailsModal .qr-section img {
    max-width: 130px;
    margin-bottom: 1rem;
    border-radius: 0.5rem;
}

#livestockDetailsModal .qr-section .btn-action {
    margin-top: 0.5rem;
}

/* Footer */
#livestockDetailsModal .modal-footer {
    text-align: center;
    border-top: 1px solid #e5e7eb;
    padding-top: 2rem;
    margin-top: 2.5rem;
}

/* RESPONSIVE DESIGN */
@media (max-width: 1200px) {
    #livestockDetailsModal .modal-dialog {
        max-width: 97%;
    }

    #livestockDetailsModal .modal-body {
        padding: 4rem;
        max-height: 125vh;
    }

    #livestockDetailsModal .detail-wrapper {
        padding: 2.75rem;
        font-size: 1rem;
    }
}

@media (max-width: 992px) {
    #livestockDetailsModal .modal-body {
        padding: 3rem;
        max-height: 120vh;
    }

    #livestockDetailsModal .detail-wrapper {
        padding: 2rem;
        font-size: 0.95rem;
    }
}

@media (max-width: 768px) {
    #livestockDetailsModal .modal-dialog {
        max-width: 95%;
    }

    #livestockDetailsModal .modal-body {
        padding: 2rem;
        max-height: 115vh;
    }

    #livestockDetailsModal .detail-wrapper {
        padding: 1.75rem;
        line-height: 1.7;
    }

    #livestockDetailsModal p {
        text-align: left;
        font-size: 0.95rem;
    }
}

@media (max-width: 576px) {
    #livestockDetailsModal .modal-body {
        padding: 1.5rem;
        max-height: 115vh;
    }

    #livestockDetailsModal .detail-wrapper {
        padding: 1.25rem;
    }

    #livestockDetailsModal .detail-row {
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
        gap: 0.4rem;
    }

    #livestockDetailsModal .detail-value {
        text-align: left;
        width: 100%;
    }

    #livestockDetailsModal .qr-section img {
        max-width: 110px;
    }
}

/* QR Code Modal Specific Adjustments */
#qrCodeModal .modal-body {
    background: #ffffff;
    padding: 2rem 2.5rem;   /* slightly smaller padding than main modal */
    border-radius: 1rem;
    max-height: 70vh;       /* smaller than main modal for QR code */
    overflow-y: auto;
    text-align: center;     /* center QR code and text */
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

/* QR Code Image */
#qrCodeModal.smart-detail #qrCodeContent img {
    max-width: 150px;       /* restrict QR code size */
    width: 100%;
    height: auto;
    margin: 0 auto 1rem;    /* spacing below QR code */
}

/* QR Code Text */
#qrCodeModal.smart-detail #qrCodeText {
    font-size: 0.9rem;
    color: #6b7280;
    margin-bottom: 1rem;
    text-align: center;
}
/* Allow table to scroll horizontally if too wide */
.table-responsive {
    overflow-x: auto;
}

/* Make table cells wrap instead of forcing them all inline */
#issuesTable td, 
#issuesTable th {
    white-space: normal !important;  /* allow wrapping */
    vertical-align: middle;
}

/* Make sure action buttons don’t overflow */
#issuesTable td .btn-group {
    display: flex;
    flex-wrap: wrap; /* buttons wrap if not enough space */
    gap: 0.25rem;    /* small gap between buttons */
}

#issuesTable td .btn-action {
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
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        display: inline-block;
        min-width: 2.5rem;
        padding: 0.5rem 0.75rem;
        margin: 0 0.125rem;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        background-color: #ffffff;
        color: #18375d;
        cursor: pointer;
        font-size: 0.875rem;
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
        cursor: default;
    }

    /* Ensure pagination container stays left inside responsive tables */
    .table-responsive .dataTables_wrapper .dataTables_paginate {
        position: relative;
        width: 100%;
        text-align: left;
    }

    .dataTables_wrapper .dataTables_info {
        text-align: left !important;
        float: left !important;
        margin-top: 0.5rem;
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

    <div class="row fade-in mb-3">
        <!-- Active Farms -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Active Farms</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeFarmsCount ?? 0 }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-tractor fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avg Daily Production -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Avg Daily Production</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($avgProductivity ?? 0, 1) }}L</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-chart-bar fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Producer -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Top Producer</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $topProducer ?? 'N/A' }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-trophy fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Farmers -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Farmers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalFarmers ?? 0 }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-users fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4 fade-in" id="globalStatusCard">
        <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
            <h6 class="mb-0">
                <i class="fas fa-chart-bar"></i>
                Overall Livestock Status
            </h6>
        </div>
        <div class="card-body">
            <div class="chart-container" style="height: 260px;">
                <canvas id="globalLivestockStatusChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4 fade-in" id="farmerStatusWrapper" style="display:none;">
        <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
            <h6 class="mb-0">
                <i class="fas fa-user"></i>
                Selected Farmer Livestock Status
            </h6>
            <span id="selectedFarmerNameHeader" class="small text-muted"></span>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-6 col-md-3 mb-2"><strong>Total:</strong> <span id="farmerTotalLivestock">0</span></div>
                <div class="col-6 col-md-3 mb-2"><strong>Active:</strong> <span id="farmerActiveLivestock">0</span></div>
                <div class="col-6 col-md-3 mb-2"><strong>Inactive:</strong> <span id="farmerInactiveLivestock">0</span></div>
                <div class="col-6 col-md-3 mb-2"><strong>Farms:</strong> <span id="farmerTotalFarms">0</span></div>
            </div>
            <div class="chart-container" style="height: 260px;">
                <canvas id="farmerLivestockStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Farmer Selection Section -->
     <div class="card shadow mb-4 fade-in" id="farmerSelectionCard">
        <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start">
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
                    <button class="btn-action btn-action-refresh-farmers" title="Refresh" onclick="refreshPendingFarmersTable('pendingFarmersTable')">
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
        <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start">
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
                 <div class="d-flex align-items-center justify-content-center flex-nowrap gap-2 action-toolbar">
                    <button class="btn-action btn-action-back btn-sm" title="Back to Farmers" onclick="backToFarmers()">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button class="btn-action btn-action-ok" title="Add Livestock" data-toggle="modal" data-target="#addLivestockModal">
                        <i class="fas fa-plus"></i> Add Livestock
                    </button>
                    <button class="btn-action btn-action-refresh-admins" title="Refresh" onclick="refreshAdminsTable('activeFarmersTable')">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
            
            <!-- Livestock Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="livestockTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
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

<!-- Smart Form Modal - Add Livestock -->
<div class="modal fade smart-form-modal" id="addLivestockModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content smart-form text-center p-4">

      <!-- Header -->
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle mb-3">
          <i class="fas fa-list fa-2x"></i>
        </div>
        <h5 class="fw-bold mb-1">Add New Livestock</h5>
        <p class="text-muted mb-0 small">
          Fill out the details below to register a new livestock record.
        </p>
      </div>

      <!-- Form -->
      <form id="addLivestockForm">
        @csrf
        <div class="form-wrapper text-start mx-auto">
          <input type="hidden" id="selectedFarmerId" name="farmer_id">

          <div class="row g-3">
            <!-- Tag Number -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Tag ID Number <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="tag_number" required>
            </div>

            <!-- Name -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="name" required>
            </div>

            <!-- Type -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Type <span class="text-danger">*</span></label>
              <select class="form-control" name="type" required>
                <option value="">Select Type</option>
                <option value="cow">Cow</option>
                <option value="buffalo">Buffalo</option>
                <option value="goat">Goat</option>
                <option value="sheep">Sheep</option>
              </select>
            </div>

            <!-- Breed -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Breed</label>
              <input type="text" class="form-control" name="breed_name" list="breedSuggestions" placeholder="Enter breed">
              <datalist id="breedSuggestions">
                <option value="Holstein"></option>
                <option value="Jersey"></option>
                <option value="Guernsey"></option>
                <option value="Ayrshire"></option>
                <option value="Brown Swiss"></option>
              </datalist>
              <input type="hidden" name="breed" value="other">
            </div>

            <!-- Farm -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Farm <span class="text-danger">*</span></label>
              <select class="form-control" name="farm_id" required>
                <option value="">Select Farm</option>
              </select>
            </div>

            <!-- Registry ID -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Registry ID Number</label>
              <input type="text" class="form-control" name="registry_id">
            </div>

            <!-- Birth Date -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Birth Date <span class="text-danger">*</span></label>
              <input type="date" class="form-control" name="birth_date" required max="{{ date('Y-m-d') }}">
            </div>

            <!-- Gender -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Sex <span class="text-danger">*</span></label>
              <select class="form-control" name="gender" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
              </select>
            </div>

            <!-- Weight -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Weight (kg)</label>
              <input type="number" class="form-control" name="weight" min="0" step="0.1">
            </div>

            <!-- Health Status -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Health Status <span class="text-danger">*</span></label>
              <select class="form-control" name="health_status" required>
                <option value="healthy">Healthy</option>
                <option value="sick">Sick</option>
                <option value="recovering">Recovering</option>
                <option value="under_treatment">Under Treatment</option>
              </select>
            </div>

            <!-- Status -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Status <span class="text-danger">*</span></label>
              <select class="form-control" name="status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="deceased">Deceased</option>
                <option value="transferred">Transferred</option>
                <option value="sold">Sold</option>
              </select>
            </div>

            <!-- Natural Marks -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Natural marks</label>
              <textarea class="form-control" name="natural_marks" rows="2" style="resize: none;"></textarea>
            </div>

            <!-- Property Number -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Property No.</label>
              <input type="text" class="form-control" name="property_no">
            </div>

            <!-- Acquisition Date -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Date acquired</label>
              <input type="date" class="form-control" name="acquisition_date" max="{{ date('Y-m-d') }}">
            </div>

            <!-- Acquisition Cost -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Acquisition Cost</label>
              <input type="number" class="form-control" name="acquisition_cost" min="0" step="0.01">
            </div>

            <!-- Sire ID -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Sire - Registry ID Number</label>
              <input type="text" class="form-control" name="sire_id">
            </div>

            <!-- Sire Name -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Sire Name</label>
              <input type="text" class="form-control" name="sire_name">
            </div>

            <!-- Sire Breed -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Sire Breed</label>
              <input type="text" class="form-control" name="sire_breed">
            </div>

            <!-- Dam ID -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Dam - Registry ID Number</label>
              <input type="text" class="form-control" name="dam_id">
            </div>

            <!-- Dam Name -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Dam Name</label>
              <input type="text" class="form-control" name="dam_name">
            </div>

            <!-- Dam Breed -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Dam Breed</label>
              <input type="text" class="form-control" name="dam_breed">
            </div>

            <!-- Dispersal From -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Dispersal From</label>
              <input type="text" class="form-control" name="dispersal_from">
            </div>

            <!-- Owned By -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Owned By</label>
              <input type="text" class="form-control" name="owned_by">
            </div>

            <!-- Distinct Characteristics -->
            <div class="col-md-12">
              <label class="fw-semibold">Distinct Characteristics</label>
              <textarea class="form-control" name="distinct_characteristics" rows="3" style="resize: none;"></textarea>
            </div>

            <!-- Source / Origin -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Source / Origin</label>
              <input type="text" class="form-control" name="source_origin">
            </div>

            <!-- Name of Cooperator -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Name of Cooperator</label>
              <input type="text" class="form-control" name="cooperator_name">
            </div>

            <!-- Date Released -->
            <div class="col-lg-4 col-md-6 col-12">
              <label class="fw-semibold">Date Released</label>
              <input type="date" class="form-control" name="date_released" max="{{ date('Y-m-d') }}">
            </div>

            <!-- Cooperative Name -->
            <div class="col-lg-6 col-md-6 col-12">
              <label class="fw-semibold">Cooperative</label>
              <input type="text" class="form-control" name="cooperative_name">
            </div>

            <!-- Cooperative Address -->
            <div class="col-lg-6 col-md-6 col-12">
              <label class="fw-semibold">Cooperative Address</label>
              <input type="text" class="form-control" name="cooperative_address">
            </div>

            <!-- Contact No. -->
            <div class="col-lg-6 col-md-6 col-12">
              <label class="fw-semibold">Contact No.</label>
              <input type="text" class="form-control" name="cooperative_contact_no">
            </div>

            <!-- In-Charge -->
            <div class="col-lg-6 col-md-6 col-12">
              <label class="fw-semibold">In-Charge</label>
              <input type="text" class="form-control" name="in_charge">
            </div>

            <!-- Remarks -->
            <div class="col-md-12">
                <label for="addRemarks" class="fw-semibold">Remarks </label>
                <textarea class="form-control mt-1" id="addRemarks" name="remarks" rows="4" placeholder="Additional notes about the livestock..." style="resize: none;"></textarea>
            </div>

            <!-- Description -->
            <div class="col-md-12">
                <label for="addDescription" class="fw-semibold">Description</label>
                <textarea class="form-control mt-1" id="addDescription" name="description" rows="4" placeholder="Additional notes about the livestock..." style="resize: none;"></textarea>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
          <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-modern btn-ok">
            <i class="fas fa-save"></i> Add Livestock
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Smart Detail Modal -->
<div class="modal fade admin-modal livestock-modal" id="livestockDetailsModal" tabindex="-1" role="dialog" aria-labelledby="livestockDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

        <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-eye fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">Livestock Details</h5>
                <p class="text-muted mb-0 small text-center">Below are the complete details of the selected livestock.</p>
            </div>

      <!-- Body -->
      <div class="modal-body" style="overflow-x: auto;">
        <div id="livestockDetailsContent">
          <!-- Dynamic details injected here -->
        </div>
      </div>

      <!-- Footer -->

        <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
            <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
            <button type="button" class="btn-modern btn-approve" onclick="printAdminLivestockRecord()">
                <i class="fas fa-print"></i> Print Record
            </button>
            <button type="button" class="btn-modern btn-ok" onclick="openLivestockEditModal()">
                <i class="fas fa-edit"></i> Edit 
            </button>
        </div>

    </div>
  </div>
</div>


<!-- Smart Detail Modal -->
<div class="modal fade" id="qrCodeModal" tabindex="-1" role="dialog" aria-labelledby="qrCodeLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

        <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle ">
                    <i class="fas fa-qrcode fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">QR Code </h5>
                <p class="text-muted mb-0 small">Below are the generated qr code of the selected livestock.</p>
            </div>

      <!-- Body -->
      <div class="modal-body">
        <div id="qrCodeContent" >
          <p class="mt-3" id="qrCodeText"></p>
        </div>
      </div>

      <!-- Footer -->

        <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
            <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
            <button type="button" class="btn-modern btn-ok" onclick="downloadQRCode()">
                <i class="fas fa-download"></i> Download
            </button>
        </div>

    </div>
  </div>
</div>

<!-- Edit Livestock Modal -->
<div class="modal fade smart-form-modal" id="editLivestockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content smart-form text-center p-4">

            <!-- Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle mb-3">
                    <i class="fas fa-edit fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">Edit Livestock</h5>
                <p class="text-muted mb-0 small">
                    Update livestock details below and click <strong>Update Livestock</strong> to save changes.
                </p>
            </div>

            <!-- Form -->
            <form id="editLivestockForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editLivestockId" name="livestock_id">

                <div class="form-wrapper text-start mx-auto">

                    <div class="row g-3">
                        <!-- Tag Number -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Tag ID Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editTagNumber" name="tag_number" required>
                        </div>

                        <!-- Name -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>

                        <!-- Type -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="editType" name="type" required>
                                <option value="">Select Type</option>
                                <option value="cow">Cow</option>
                                <option value="buffalo">Buffalo</option>
                                <option value="goat">Goat</option>
                                <option value="sheep">Sheep</option>
                            </select>
                        </div>

                        <!-- Breed -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Breed</label>
                            <input type="text" class="form-control" id="editBreedName" name="breed_name" list="breedSuggestionsEdit" placeholder="Enter breed">
                            <datalist id="breedSuggestionsEdit">
                                <option value="Holstein"></option>
                                <option value="Jersey"></option>
                                <option value="Guernsey"></option>
                                <option value="Ayrshire"></option>
                                <option value="Brown Swiss"></option>
                            </datalist>
                            <input type="hidden" name="breed" value="other">
                        </div>

                        <!-- Farm -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Farm <span class="text-danger">*</span></label>
                            <select class="form-control" id="editFarmId" name="farm_id" required>
                                <option value="">Select Farm</option>
                            </select>
                        </div>

                        <!-- Registry ID -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Registry ID Number</label>
                            <input type="text" class="form-control" id="editRegistryId" name="registry_id">
                        </div>

                        <!-- Birth Date -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Birth Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="editBirthDate" name="birth_date" required max="{{ date('Y-m-d') }}">
                        </div>

                        <!-- Gender -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Sex <span class="text-danger">*</span></label>
                            <select class="form-control" id="editGender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <!-- Weight -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Weight (kg)</label>
                            <input type="number" class="form-control" id="editWeight" name="weight" min="0" step="0.1">
                        </div>

                        <!-- Health Status -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Health Status</label>
                            <select class="form-control" id="editHealthStatus" name="health_status">
                                <option value="healthy">Healthy</option>
                                <option value="sick">Sick</option>
                                <option value="injured">Injured</option>
                                <option value="pregnant">Pregnant</option>
                                <option value="lactating">Lactating</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-control" id="editStatus" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="deceased">Deceased</option>
                                <option value="transferred">Transferred</option>
                                <option value="sold">Sold</option>
                            </select>
                        </div>

                        <!-- Natural Marks -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Natural marks</label>
                            <textarea class="form-control" id="editNaturalMarks" name="natural_marks" rows="2" style="resize: none;"></textarea>
                        </div>

                        <!-- Property Number -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Property No.</label>
                            <input type="text" class="form-control" id="editPropertyNo" name="property_no">
                        </div>

                        <!-- Acquisition Date -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Date acquired</label>
                            <input type="date" class="form-control" id="editAcquisitionDate" name="acquisition_date" max="{{ date('Y-m-d') }}">
                        </div>

                        <!-- Acquisition Cost -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Acquisition Cost</label>
                            <input type="number" class="form-control" id="editAcquisitionCost" name="acquisition_cost" min="0" step="0.01">
                        </div>

                        <!-- Sire ID -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Sire - Registry ID Number</label>
                            <input type="text" class="form-control" id="editSireId" name="sire_id">
                        </div>

                        <!-- Sire Name -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Sire Name</label>
                            <input type="text" class="form-control" id="editSireName" name="sire_name">
                        </div>

                        <!-- Sire Breed -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Sire Breed</label>
                            <input type="text" class="form-control" id="editSireBreed" name="sire_breed">
                        </div>

                        <!-- Dam ID -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Dam - Registry ID Number</label>
                            <input type="text" class="form-control" id="editDamId" name="dam_id">
                        </div>

                        <!-- Dam Name -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Dam Name</label>
                            <input type="text" class="form-control" id="editDamName" name="dam_name">
                        </div>

                        <!-- Dam Breed -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Dam Breed</label>
                            <input type="text" class="form-control" id="editDamBreed" name="dam_breed">
                        </div>

                        <!-- Dispersal From -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Dispersal From</label>
                            <input type="text" class="form-control" id="editDispersalFrom" name="dispersal_from">
                        </div>

                        <!-- Owned By -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Owned By</label>
                            <input type="text" class="form-control" id="editOwnedBy" name="owned_by">
                        </div>

                        <!-- Distinct Characteristics -->
                        <div class="col-md-12">
                            <label class="fw-semibold">Distinct Characteristics</label>
                            <textarea class="form-control" id="editDistinctCharacteristics" name="distinct_characteristics" rows="3" style="resize: none;"></textarea>
                        </div>

                        <!-- Source / Origin -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Source / Origin</label>
                            <input type="text" class="form-control" id="editSourceOrigin" name="source_origin">
                        </div>

                        <!-- Name of Cooperator -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Name of Cooperator</label>
                            <input type="text" class="form-control" id="editCooperatorName" name="cooperator_name">
                        </div>

                        <!-- Date Released -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="fw-semibold">Date Released</label>
                            <input type="date" class="form-control" id="editDateReleased" name="date_released" max="{{ date('Y-m-d') }}">
                        </div>

                        <!-- Cooperative Name -->
                        <div class="col-lg-6 col-md-6 col-12">
                            <label class="fw-semibold">Cooperative</label>
                            <input type="text" class="form-control" id="editCooperativeName" name="cooperative_name">
                        </div>

                        <!-- Cooperative Address -->
                        <div class="col-lg-6 col-md-6 col-12">
                            <label class="fw-semibold">Cooperative Address</label>
                            <input type="text" class="form-control" id="editCooperativeAddress" name="cooperative_address">
                        </div>

                        <!-- Contact No. -->
                        <div class="col-lg-6 col-md-6 col-12">
                            <label class="fw-semibold">Contact No.</label>
                            <input type="text" class="form-control" id="editCooperativeContactNo" name="cooperative_contact_no">
                        </div>

                        <!-- In-Charge -->
                        <div class="col-lg-6 col-md-6 col-12">
                            <label class="fw-semibold">In-Charge</label>
                            <input type="text" class="form-control" id="editInCharge" name="in_charge">
                        </div>

                        <!-- Remarks -->
                        <div class="col-md-12">
                            <label for="editRemarks" class="fw-semibold">Remarks </label>
                            <textarea class="form-control mt-1" id="editRemarks" name="remarks" rows="4" placeholder="Additional notes about the livestock..." style="resize: none;"></textarea>
                        </div>

                        <!-- Description/Notes -->
                        <div class="col-md-12">
                            <label for="editDescription" class="fw-semibold">Description</label>
                            <textarea class="form-control mt-1" id="editDescription" name="description" rows="4" placeholder="Additional notes about the livestock..." style="resize: none;"></textarea>
                        </div>

                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-ok"><i class="fas fa-save"></i> Update</button>
                </div>

            </form>
        </div>
    </div>
</div>


<!-- Modern Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
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
        Are you sure you want to delete this livestock? This action <strong>cannot be undone</strong>.
      </p>

      <!-- Buttons -->
      <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
        <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn-modern btn-delete" id="confirmDeleteBtn">
          <i class="fas fa-trash"></i> Yes, Delete
        </button>
      </div>

    </div>
  </div>
</div>

<!-- Modern Approve Farmer Modal -->
<div class="modal fade" id="approveFarmerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content smart-modal text-center p-4">
            <!-- Icon -->
            <div class="icon-wrapper mx-auto mb-4 text-success">
                <i class="fas fa-times-circle fa-2x"></i>
            </div>
            <!-- Title -->
            <h5>Approve Farmer Registration</h5>
            <!-- Description -->
            <p class="text-muted mb-4 px-3">
                Are you sure you want to <strong>approve</strong> this farmer’s registration?
            </p>

            <!-- Form -->
            <form onsubmit="submitApproval(event)">
                @csrf
                <input type="hidden" id="farmerIdHiddenApprove">

                <!-- Buttons -->
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-approve">
                        <i class="fas fa-check-circle"></i> Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Issue Alert Modal -->
<div class="modal fade admin-modal" id="issueAlertModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content smart-form text-center p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle ">
                    <i class="fas fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1" id="issueModalTitle">Issue Alert</h5>
                <p class="text-muted mb-0 small">
                    Fill out the details below to create an issue alert.
                </p>
            </div>

            <!-- Form -->
            <form id="issueAlertForm">
                @csrf
                <input type="hidden" id="alertLivestockId">

                <div class="form-wrapper text-start mx-auto">

                        <!-- Issue Type -->
                        <div class="col-md-12">
                            <label for="issueType" class="fw-semibold">Issue Type <span class="text-danger">*</span></label>
                            <select class="form-control mt-1" id="issueType" required>
                                <option value="">Select Issue Type</option>
                                <option value="health">Health Issue</option>
                                <option value="injury">Injury</option>
                                <option value="production">Production Issue</option>
                                <option value="behavioral">Behavioral Issue</option>
                                <option value="environmental">Environmental Issue</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Priority -->
                        <div class="col-md-12">
                            <label for="issuePriority" class="fw-semibold">Priority <span class="text-danger">*</span></label>
                            <select class="form-control mt-1" id="issuePriority" required>
                                <option value="">Select Priority</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="col-md-12">
                            <label for="issueDescription" class="fw-semibold">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control mt-1" id="issueDescription" rows="4" required placeholder="Describe the issue in detail..." style="resize: none;"></textarea>
                        </div>
                </div>

                <!-- Footer Buttons -->
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-ok">
                        <i class="fas fa-save"></i> Issue Alert
                    </button>
                </div>
            </form>
        </div>
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

    .smart-card { border-radius: 15px; background: #fff; transition: all 0.3s ease; }
    .smart-card:hover { box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1); }
    .smart-card-header { border-radius: 15px 15px 0 0; padding: 0.75rem 1rem; }
    .smart-card-body { padding: 1.25rem; }
    .smart-card h6 { color: #18375d; font-weight: 700; margin-bottom: 0.4rem; letter-spacing: 0.5px; }
    .smart-card i { color: #18375d; }

    .smart-tabs .nav-link { border: none; color: #555; font-weight: 500; }
    .smart-tabs .nav-link.active { border-bottom: 3px solid #28a745; color: #28a745; }

    .smart-table th { width: 40%; background: #f8f9fa; font-weight: 600; }
    .smart-table td { background: #fff; }

    .smart-detail .modal-content, .modal-content.smart-detail { border-radius: 1.5rem; border: none; box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12); background-color: #fff; transition: all 0.3s ease-in-out; max-width: 90vw; margin: auto; }
    .smart-detail .icon-circle { width: 70px; height: 70px; background-color: #e8f0fe; color: #18375d; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0rem; }
    .smart-detail h5 { color: #18375d; font-weight: 700; margin-bottom: 0.75rem; letter-spacing: 0.5px; }
    .smart-detail p { color: #6b7280; font-size: 0.96rem; margin-bottom: 1.5rem; line-height: 1.6; }
    .smart-detail .modal-body { background: #ffffff; padding: 2.5rem 1rem; border-radius: 1.25rem; max-height: 80vh; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }

    #breedingForm table thead th:nth-child(6),
    #breedingForm table tbody td:nth-child(6) { text-align: left !important; }

    @media (max-width: 768px) {
      #livestockTab { display: flex; flex-wrap: nowrap; overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch; gap: 4px; }
      #livestockTab::-webkit-scrollbar { height: 6px; }
      #livestockTab::-webkit-scrollbar-thumb { background: rgba(0, 0, 0, 0.2); border-radius: 10px; }
      #livestockTab .nav-item { flex: 0 0 auto; }
      #livestockTab .nav-link { font-size: 0.9rem; padding: 8px 10px; }
      #livestockTab .nav-link i { margin-right: 4px; font-size: 0.9rem; }
    }

    /* Ensure details modal uses scrolling (override broader admin-modal rule) */
    #livestockDetailsModal .modal-body { 
      overflow-y: auto !important; 
      overflow-x: auto !important; 
      max-height: 80vh !important; 
      padding: 2.5rem 1rem !important;
    }
    /* Left align content inside details tables regardless of global table alignment */
    #livestockDetailsModal table thead th, 
    #livestockDetailsModal table tbody td { 
      text-align: left !important; 
      vertical-align: middle !important; 
    }
    #livestockDetailsModal .badge { font-weight: 600; }
    #livestockDetailsModal .smart-card-header { background: transparent !important; }

    /* DataTables Pagination Styling - match Admin Farmers tables */
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
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        background-color: #ffffff;
        color: #18375d;
        cursor: pointer;
        font-size: 0.875rem;
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
        cursor: default;
    }

    /* Ensure pagination container stays left inside responsive tables */
    .table-responsive .dataTables_wrapper .dataTables_paginate {
        position: relative;
        width: 100%;
        text-align: left;
    }

    .dataTables_wrapper .dataTables_info {
        text-align: left !important;
        float: left !important;
        margin-top: 0.5rem;
    }

</style>
@endpush

@push('scripts')
<!-- DataTables Core -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- Required libraries for PDF/Excel (same stack as Admin Farmers) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<!-- jsPDF and autoTable for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let globalStatusChartInstance = null;
    let farmerStatusChartInstance = null;

    function initializeGlobalStatusChart() {
        const canvas = document.getElementById('globalLivestockStatusChart');
        if (!canvas || typeof Chart === 'undefined') {
            return;
        }

        const ctx = canvas.getContext('2d');
        const data = {
            labels: ['Active', 'Inactive', 'Deceased', 'Transferred', 'Sold'],
            datasets: [{
                label: 'Livestock Count',
                data: [
                    {{ $activeLivestock ?? 0 }},
                    {{ $inactiveLivestock ?? 0 }},
                    {{ $deceasedLivestock ?? 0 }},
                    {{ $transferredLivestock ?? 0 }},
                    {{ $soldLivestock ?? 0 }}
                ],
                backgroundColor: [
                    '#1cc88a', // active
                    '#858796', // inactive
                    '#4b5563', // deceased
                    '#36b9cc', // transferred
                    '#f6c23e'  // sold
                ],
                hoverBackgroundColor: [
                    '#17a673',
                    '#6c757d',
                    '#374151',
                    '#2c9faf',
                    '#dda20a'
                ],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        };

        const options = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    stacked: false
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        };

        if (globalStatusChartInstance) {
            globalStatusChartInstance.destroy();
        }

        globalStatusChartInstance = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        });
    }

    function updateFarmerStatusChart(payload) {
        const canvas = document.getElementById('farmerLivestockStatusChart');
        if (!canvas || typeof Chart === 'undefined') {
            return;
        }

        const ctx = canvas.getContext('2d');
        const data = {
            labels: ['Active', 'Inactive', 'Deceased', 'Transferred', 'Sold'],
            datasets: [{
                label: 'Livestock Count',
                data: [
                    payload.active || 0,
                    payload.inactive || 0,
                    payload.deceased || 0,
                    payload.transferred || 0,
                    payload.sold || 0
                ],
                backgroundColor: [
                    '#1cc88a',
                    '#858796',
                    '#4b5563',
                    '#36b9cc',
                    '#f6c23e'
                ],
                hoverBackgroundColor: [
                    '#17a673',
                    '#6c757d',
                    '#374151',
                    '#2c9faf',
                    '#dda20a'
                ],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        };

        const options = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    stacked: false
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        };

        if (farmerStatusChartInstance) {
            farmerStatusChartInstance.destroy();
        }

        farmerStatusChartInstance = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        });
    }

    function formatDateForInput(dateVal) {
        if (!dateVal) return '';
        try {
            const d = new Date(dateVal);
            if (isNaN(d.getTime())) {
                return String(dateVal).slice(0, 10);
            }
            const yyyy = d.getFullYear();
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        } catch (e) {
            return String(dateVal).slice(0, 10);
        }
    }
    let selectedFarmerId = null;
    let selectedFarmerName = '';
    let selectedLivestockId = null;
    let currentAdminLivestockData = null;
    let currentAdminLivestockPrintBasic = null;

   $(document).ready(function() {
        console.log('Document ready, initializing livestock farmers page...');
        initializeGlobalStatusChart();
        
        // 🔍 Search functionality for Farmers Table
        $('#farmerSearch').on('keyup', function() {
            const value = $(this).val();
            if (typeof farmersTable !== 'undefined' && farmersTable) {
                // Use DataTables global search when available
                farmersTable.search(value).draw();
            } else {
                // Fallback manual filtering before DataTables initialization
                const searchTerm = value.toLowerCase();
                $('#farmersTable tbody tr').each(function() {
                    const text = $(this).text().toLowerCase();
                    $(this).toggle(text.indexOf(searchTerm) > -1);
                });
            }
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
    let livestockTable;

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

// Safe DataTables initializer for this page
function initializeDataTables() {
    try {
        if (!$.fn || !$.fn.DataTable) {
            console.warn('DataTables plugin not loaded; skipping initialization');
            return;
        }
        // Initialize farmers table if present
        if ($('#farmersTable').length && !$.fn.DataTable.isDataTable('#farmersTable')) {
            farmersTable = $('#farmersTable').DataTable({
                dom: 'Bfrtip',
                searching: true,
                paging: true,
                info: true,
                lengthChange: false,
                pageLength: 10,
                buttons: [
                    { extend: 'csvHtml5', className: 'd-none' },
                    { extend: 'pdfHtml5', className: 'd-none' },
                    { extend: 'print', className: 'd-none' }
                ],
                columnDefs: [
                    { targets: -1, orderable: false, searchable: false }
                ]
            });
        }
        // Initialize livestock table if present
        if ($('#livestockTable').length && !$.fn.DataTable.isDataTable('#livestockTable')) {
            livestockTable = $('#livestockTable').DataTable({
                dom: 'Bfrtip',
                searching: true,
                paging: true,
                info: true,
                lengthChange: false,
                pageLength: 10,
                buttons: [
                    { extend: 'csvHtml5', className: 'd-none' },
                    { extend: 'pdfHtml5', className: 'd-none' },
                    { extend: 'print', className: 'd-none' }
                ],
                columnDefs: [
                    { targets: -1, orderable: false, searchable: false }
                ]
            });
        }
        // Hide default elements we don't use in UI
        $('.dataTables_filter').hide();
        $('.dt-buttons').hide();
    } catch (e) {
        console.warn('initializeDataTables error:', e);
    }
}

// Backwards-compat shim: some code calls loadfarmersTable(), but our function is loadFarmers()
function loadfarmersTable() {
    try { if (typeof loadFarmers === 'function') { loadFarmers(); } } catch (_) {}
}

// No-op if updateStats is not yet implemented on this page
if (typeof updateStats !== 'function') {
    function updateStats() { /* intentionally empty */ }
}

    function loadFarmers() {
        // Only inject a manual loading row if DataTables has NOT been initialized yet.
        // Once DataTables is managing the table, rows must be added via its API to avoid column count warnings.
        if (typeof farmersTable === 'undefined' || !farmersTable) {
            $('#farmersTableBody').html('<tr><td colspan="7" class="text-center">Loading farmers...</td></tr>');
        }

        $.ajax({
            url: '{{ route("admin.livestock.farmers") }}',
            method: 'GET',
            success: function(response) {
                console.log('Farmers response:', response);
                if (!response.success) {
                    $('#farmersTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading farmers: ' + (response.message || 'Unknown error') + '</td></tr>');
                    return;
                }

                const hasData = Array.isArray(response.data) && response.data.length > 0;

                // No farmers
                if (!hasData) {
                    if (typeof farmersTable !== 'undefined' && farmersTable) {
                        farmersTable.clear().draw();
                        $('#farmersTableBody').html('<tr><td colspan="7" class="text-center">No farmers found</td></tr>');
                    } else {
                        $('#farmersTableBody').html('<tr><td colspan="7" class="text-center">No farmers found</td></tr>');
                    }
                    return;
                }

                // We have farmers: feed them into DataTables if available, otherwise fall back to raw HTML rows.
                if (typeof farmersTable !== 'undefined' && farmersTable) {
                    farmersTable.clear();

                    response.data.forEach(farmer => {
                        const displayName = farmer.first_name && farmer.last_name
                            ? `${farmer.first_name} ${farmer.last_name}`
                            : (farmer.name || 'N/A');

                        const nameLink = `<a href="#" class="farmer-link" onclick="selectFarmer('${farmer.id}', '${displayName}')">${displayName}</a>`;
                        const statusBadge = `<span class="badge badge-${getStatusBadgeClass(farmer.status)}">${farmer.status}</span>`;
                        const actionsHtml = `
                            <button class="btn-action btn-action-ok" title="View Livestock" onclick="selectFarmer('${farmer.id}', '${displayName}')">
                                <i class="fas fa-eye"></i> View Livestock
                            </button>`;

                        const rowData = [
                            farmer.id,
                            nameLink,
                            farmer.email || 'N/A',
                            farmer.contact_number || 'N/A',
                            farmer.livestock_count || 0,
                            statusBadge,
                            actionsHtml
                        ];

                        farmersTable.row.add(rowData);
                    });

                    farmersTable.draw();
                } else {
                    // Fallback when DataTables is not initialized for some reason
                    let html = '';
                    response.data.forEach(farmer => {
                        const displayName = farmer.first_name && farmer.last_name
                            ? `${farmer.first_name} ${farmer.last_name}`
                            : (farmer.name || 'N/A');

                        html += `
                            <tr>
                                <td>${farmer.id}</td>
                                <td><a href="#" class="farmer-link" onclick="selectFarmer('${farmer.id}', '${displayName}')">${displayName}</a></td>
                                <td>${farmer.email || 'N/A'}</td>
                                <td>${farmer.contact_number || 'N/A'}</td>
                                <td>${farmer.livestock_count || 0}</td>
                                <td><span class="badge badge-${getStatusBadgeClass(farmer.status)}">${farmer.status}</span></td>
                                <td>
                                    <button class="btn-action btn-action-ok" title="View Livestock" onclick="selectFarmer('${farmer.id}', '${displayName}')">
                                        <i class="fas fa-eye"></i> View Livestock
                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                    $('#farmersTableBody').html(html);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr, status, error);
                console.log('Response Text:', xhr.responseText);
                $('#farmersTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading farmers. Check console for details.</td></tr>');
            }
        });
    }

    function loadAdminGrowthRecords(livestockId) {
        $.ajax({
            url: `{{ route("admin.livestock.growth-records", ["id" => "__ID__"]) }}`.replace('__ID__', livestockId),
            method: 'GET',
            success: function(response) {
                if (response.success && response.data && response.data.length > 0) {
                    const tbody = document.getElementById('growthRecordsTable');
                    if (!tbody) return;
                    tbody.innerHTML = '';
                    response.data.forEach(r => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td style="white-space: nowrap;">${r.date ? new Date(r.date).toLocaleDateString() : 'N/A'}</td>
                            <td style="white-space: nowrap;">${r.weight_kg ?? 'N/A'}</td>
                            <td style="white-space: nowrap;">${r.height_cm ?? 'N/A'}</td>
                            <td style="white-space: nowrap;">${r.heart_girth_cm ?? 'N/A'}</td>
                            <td style="white-space: nowrap;">${r.body_length_cm ?? 'N/A'}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            }
        });
    }

    function selectFarmer(farmerId, farmerName) {
        selectedFarmerId = farmerId;
        selectedFarmerName = farmerName;
        
        $('#selectedFarmerName').text(farmerName);
        $('#selectedFarmerNameHeader').text(farmerName);
        $('#farmerStatusWrapper').show();
        $('#globalStatusCard').hide();
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
        $('#farmerStatusWrapper').hide();
        $('#globalStatusCard').show();
        
        $('#livestockTableBody').empty();
    }

    function loadFarmerLivestock(farmerId) {
        // Same pattern as farmers table: avoid injecting a single-colspan row once DataTables is active.
        if (typeof livestockTable === 'undefined' || !livestockTable) {
            $('#livestockTableBody').html('<tr><td colspan="7" class="text-center">Loading livestock...</td></tr>');
        }

        $.ajax({
            url: `{{ route("admin.livestock.farmer-livestock", ["id" => "__ID__"]) }}`.replace('__ID__', farmerId),
            method: 'GET',
            success: function(response) {
                console.log('Farmers response:', response);
                if (!response.success) {
                    $('#livestockTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading livestock</td></tr>');
                    return;
                }

                const livestock = (response.data && Array.isArray(response.data.livestock)) ? response.data.livestock : [];
                const hasLivestock = livestock.length > 0;

                if (!hasLivestock) {
                    if (typeof livestockTable !== 'undefined' && livestockTable) {
                        livestockTable.clear().draw();
                        $('#livestockTableBody').html('<tr><td colspan="7" class="text-center">No livestock found for this farmer</td></tr>');
                    } else {
                        $('#livestockTableBody').html('<tr><td colspan="7" class="text-center">No livestock found for this farmer</td></tr>');
                    }
                    if (response.data && response.data.stats) {
                        updateFarmerStats(response.data.stats);
                    }
                    return;
                }

                if (typeof livestockTable !== 'undefined' && livestockTable) {
                    livestockTable.clear();

                    livestock.forEach(animal => {
                        const breedText = (animal.breed_name && animal.breed_name.trim() !== '')
                            ? animal.breed_name
                            : (animal.breed && animal.breed !== 'other'
                                ? animal.breed.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())
                                : 'N/A');

                        const statusMap = {
                            'active':   { class: 'success',   label: 'Active' },
                            'inactive': { class: 'secondary', label: 'Inactive' },
                            'deceased': { class: 'dark',      label: 'Deceased' },
                            'transferred': { class: 'info',   label: 'Transferred' },
                            'sold':     { class: 'warning',   label: 'Sold' }
                        };
                        const rawStatus = (animal.status || '').toString().toLowerCase();
                        const mapped = statusMap[rawStatus] || {
                            class: 'secondary',
                            label: rawStatus ? rawStatus.charAt(0).toUpperCase() + rawStatus.slice(1) : 'Unknown'
                        };
                        const statusBadge = `
                            <span class="badge badge-${mapped.class}">
                                ${mapped.label}
                            </span>`;

                        const actionsHtml = `
                            <div class="btn-group">
                                <button class="btn-action btn-action-ok" onclick="viewLivestockDetails('${animal.id}')" title="View Details">
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
                            </div>`;

                        const rowData = [
                            animal.tag_number,
                            animal.type,
                            breedText,
                            animal.gender,
                            animal.farm ? animal.farm.name : 'N/A',
                            statusBadge,
                            actionsHtml
                        ];

                        livestockTable.row.add(rowData);
                    });

                    livestockTable.draw();
                } else {
                    // Fallback when DataTables is not initialized
                    let html = '';
                    livestock.forEach(animal => {
                        const breedText = (animal.breed_name && animal.breed_name.trim() !== '')
                            ? animal.breed_name
                            : (animal.breed && animal.breed !== 'other'
                                ? animal.breed.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())
                                : 'N/A');

                        html += `
                            <tr>
                                <td>${animal.tag_number}</td>
                                <td>${animal.type}</td>
                                <td>${breedText}</td>
                                <td>${animal.gender}</td>
                                <td>${animal.farm ? animal.farm.name : 'N/A'}</td>
                                <td>
                                    <span class="badge badge-${animal.status === 'active' ? 'success' : 'danger'}">
                                        ${animal.status === 'active' ? 'Active' : 'Inactive'}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn-action btn-action-ok" onclick="viewLivestockDetails('${animal.id}')" title="View Details">
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

                    $('#livestockTableBody').html(html);
                }

                if (response.data && response.data.stats) {
                    updateFarmerStats(response.data.stats);
                }
            },
            error: function() {
                $('#livestockTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading livestock</td></tr>');
            }
        });
    }

    function loadFarmerFarms(farmerId) {
        $.ajax({
            url: `{{ route("admin.livestock.farmer-farms", ["id" => "__ID__"]) }}`.replace('__ID__', farmerId),
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
            url: `{{ route("admin.livestock.farmer-farms", ["id" => "__ID__"]) }}`.replace('__ID__', farmerId),
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

        const payload = {
            active: stats.active || 0,
            inactive: stats.inactive || 0,
            deceased: stats.deceased || 0,
            transferred: stats.transferred || 0,
            sold: stats.sold || 0
        };
        updateFarmerStatusChart(payload);
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
            url: `{{ route("admin.livestock.details", ["id" => "__ID__"]) }}`.replace('__ID__', livestockId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const livestock = response.livestock || response.data;
                    
                    // Populate the edit form
                    $('#editLivestockId').val(livestock.id);
                    $('#editTagNumber').val(livestock.tag_number);
                    $('#editName').val(livestock.name);
                    // Normalize enums to match select option values
                    const norm = (v) => (v || '').toString().trim().toLowerCase();
                    const normOpt = (v) => norm(v).replace(/[\s-]+/g, '_');
                    const allowedTypes = ['cow','buffalo','goat','sheep'];
                    const allowedBreeds = ['holstein','jersey','guernsey','ayrshire','brown_swiss','other'];
                    const allowedGenders = ['male','female'];
                    const allowedStatuses = ['active','inactive','deceased','transferred','sold'];
                    const allowedHealth = ['healthy','sick','recovering','under_treatment','injured','pregnant','lactating'];

                    let typeVal = norm(livestock.type);
                    let breedVal = normOpt(livestock.breed);
                    let genderVal = norm(livestock.gender);
                    let statusVal = norm(livestock.status);
                    let healthVal = norm(livestock.health_status);

                    if (!allowedTypes.includes(typeVal)) typeVal = '';
                    if (!allowedBreeds.includes(breedVal)) breedVal = 'other';
                    if (!allowedGenders.includes(genderVal)) genderVal = '';
                    if (!allowedStatuses.includes(statusVal)) statusVal = '';
                    if (!allowedHealth.includes(healthVal)) healthVal = 'healthy';

                    $('#editType').val(typeVal);
                    // Populate breed text (prefer stored breed_name)
                    const prettyBreed = (s) => {
                        if (!s) return '';
                        return s.toString().replace(/_/g,' ').replace(/\b\w/g, t => t.toUpperCase());
                    };
                    $('#editBreedName').val(livestock.breed_name ? livestock.breed_name : (breedVal && breedVal !== 'other' ? prettyBreed(breedVal) : ''));
                    $('#editBirthDate').val(formatDateForInput(livestock.birth_date));
                    $('#editGender').val(genderVal);
                    $('#editWeight').val(livestock.weight);
                    $('#editHealthStatus').val(healthVal);
                    $('#editStatus').val(statusVal);
                    $('#editRegistryId').val(livestock.registry_id);
                    $('#editNaturalMarks').val(livestock.natural_marks);
                    $('#editPropertyNo').val(livestock.property_no);
                    $('#editAcquisitionDate').val(formatDateForInput(livestock.acquisition_date));
                    $('#editAcquisitionCost').val(livestock.acquisition_cost);
                    $('#editSireId').val(livestock.sire_id);
                    $('#editSireName').val(livestock.sire_name);
                    $('#editSireBreed').val(livestock.sire_breed);
                    $('#editDamId').val(livestock.dam_id);
                    $('#editDamName').val(livestock.dam_name);
                    $('#editDamBreed').val(livestock.dam_breed);
                    $('#editDispersalFrom').val(livestock.dispersal_from);
                    $('#editOwnedBy').val(livestock.owned_by && String(livestock.owned_by).trim() !== '' ? livestock.owned_by : selectedFarmerName);
                    $('#editDistinctCharacteristics').val(livestock.distinct_characteristics || '');
                    $('#editSourceOrigin').val(livestock.source_origin || '');
                    $('#editCooperatorName').val(livestock.cooperator_name || '');
                    $('#editDateReleased').val(formatDateForInput(livestock.date_released));
                    $('#editCooperativeName').val(livestock.cooperative_name || '');
                    $('#editCooperativeAddress').val(livestock.cooperative_address || '');
                    $('#editCooperativeContactNo').val(livestock.cooperative_contact_no || '');
                    $('#editInCharge').val(livestock.in_charge || '');
                    $('#editRemarks').val(livestock.remarks || '');
                    $('#editDescription').val(livestock.description || '');
                    
                    // Pre-seed current farm to ensure a valid value exists even before async load
                    (function(){
                        const farmSelect = $('#editFarmId');
                        const farmName = (livestock.farm && (livestock.farm.name || livestock.farm.farm_name)) ? (livestock.farm.name || livestock.farm.farm_name) : 'Current Farm';
                        const farmId = (livestock.farm_id != null && livestock.farm_id !== '') ? livestock.farm_id : (livestock.farm && livestock.farm.id) ? livestock.farm.id : '';
                        farmSelect.empty().append(`<option value="${farmId}" selected>${farmName}</option>`);
                    })();
                    // Load farms for the farmer (fallback to owner from payload if needed)
                    const ownerId = selectedFarmerId || (livestock.farm && livestock.farm.owner_id) || livestock.owner_id || null;
                    if (ownerId) {
                        const farmId = (livestock.farm_id != null && livestock.farm_id !== '') ? livestock.farm_id : (livestock.farm && livestock.farm.id) ? livestock.farm.id : '';
                        loadFarmerFarmsForEdit(ownerId, farmId);
                    }
                    
                    // Show the edit modal
                    const $m = $('#editLivestockModal');
                    if (!$m.parent().is('body')) { $m.appendTo('body'); }
                    $m.modal({ backdrop: 'static', keyboard: true });
                    $m.modal('show');
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
        if (!formData.get('_method')) { formData.append('_method', 'PUT'); }
        if (!formData.get('_token')) { formData.append('_token', '{{ csrf_token() }}'); }
        
        $.ajax({
            url: `{{ route("admin.livestock.update", ["id" => "__ID__"]) }}`.replace('__ID__', livestockId),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-HTTP-Method-Override': 'PUT',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#editLivestockModal').modal('hide');
                $('#editLivestockForm')[0].reset();
                loadFarmerLivestock(selectedFarmerId);
                showNotification('Livestock updated successfully!', 'success');
            },
            error: function(xhr) {
                try { console.warn('Admin livestock update error:', xhr.status, xhr.responseText); } catch(_) {}
                const errors = xhr.responseJSON?.errors || {};
                let errorMessage = 'Failed to update livestock. ';
                if (Object.keys(errors).length) {
                    Object.values(errors).forEach(error => { errorMessage += error[0] + ' '; });
                } else if (xhr.responseJSON?.message) {
                    errorMessage += xhr.responseJSON.message;
                } else if (xhr.status) {
                    errorMessage += `(status ${xhr.status})`;
                }
                showNotification(errorMessage, 'danger');
            }
        });
    });

    // Handle delete confirmation
    $('#confirmDeleteBtn').on('click', function() {
        const livestockId = $(this).data('livestock-id');
        
        $.ajax({
            url: `{{ route('admin.livestock.destroy', ["id" => "__ID__"]) }}`.replace('__ID__', livestockId),
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
            url: `{{ route("admin.livestock.details", ["id" => "__ID__"]) }}`.replace('__ID__', livestockId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const livestock = (response.livestock || response.data) || {};
                    currentAdminLivestockData = livestock;
                    currentAdminLivestockPrintBasic = {
                        tag_number: livestock.tag_number || '',
                        name: livestock.name || '',
                        type: livestock.type || '',
                        breed: livestock.breed || '',
                        gender: livestock.gender || '',
                        birth_date: livestock.birth_date || '',
                        status: livestock.status || '',
                        health_status: livestock.health_status || '',
                        weight: (livestock.weight ?? ''),
                        farm_name: (livestock.farm && livestock.farm.name) ? livestock.farm.name : ''
                    };

                    // Calculate age and dates
                    const age = livestock.birth_date ? calculateAge(livestock.birth_date) : 'Unknown';
                    const birthDate = livestock.birth_date ? new Date(livestock.birth_date).toLocaleDateString() : 'Not recorded';
                    const createdDate = livestock.created_at ? new Date(livestock.created_at).toLocaleDateString() : 'Not recorded';
                    const updatedDate = livestock.updated_at ? new Date(livestock.updated_at).toLocaleDateString() : 'Not recorded';

                    const rawRemarks = (livestock.remarks || '').toString();
                    const cleanedRemarks = rawRemarks
                        .split(/\r?\n/)
                        .filter(line => !/^\s*\[(Health|Breeding|Calving|Growth|Production)\]/i.test(line))
                        .join('\n')
                        .trim();

                    // Build UI similar to farmer view
                    $('#livestockDetailsContent').html(`
                        <div id="livestockPrintContainer" style="display:none;"></div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="smart-card shadow-sm h-100">
                                    <div class="smart-card-header text-#18375d d-flex align-items-center">
                                        <i class="fas fa-qrcode mr-2"></i>
                                        <h6 class="mb-0 fw-bold">QR Code Status</h6>
                                    </div>
                                    <div class="smart-card-body text-center p-3">
                                        <div id="qrCodeContainer" class="mb-3">
                                            ${livestock.qr_code_generated && livestock.qr_code_url ? `
                                                <img src="${livestock.qr_code_url}" alt="QR Code" class="img-fluid mb-3" style="max-width: 150px;">
                                                <p class="text-center text-muted small mb-2">Generated by: ${livestock.qr_code_generator ? livestock.qr_code_generator.name : 'Unknown'}</p>
                                                <p class="text-center text-muted small mb-3">Generated on: ${livestock.qr_code_generated_at ? new Date(livestock.qr_code_generated_at).toLocaleDateString() : 'Unknown'}</p>
                                                <button class="btn-action btn-action-ok btn-sm" onclick="downloadQRCodeFromDetails('${livestock.qr_code_url}', '${livestock.tag_number}')">
                                                    <i class="fas fa-download"></i> Download QR Code
                                                </button>
                                            ` : `
                                                <p class="text-center text-muted">No QR code generated yet</p>
                                                <button class="btn-action btn-action-ok btn-sm" onclick="generateQRCodeFromDetails('${livestock.id}')">
                                                    <i class="fas fa-qrcode"></i> Generate QR Code
                                                </button>
                                            `}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="smart-card shadow-sm h-100">
                                    <div class="smart-card-header text-#18375d d-flex align-items-center">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <h6 class="mb-0 fw-bold">Quick Info</h6>
                                    </div>
                                    <div class="smart-card-body p-3">
                                        <div class="row">
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">Tag Number</small>
                                                <p class="fw-bold mb-0">${livestock.tag_number || 'N/A'}</p>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">Name</small>
                                                <p class="fw-bold mb-0">${livestock.name || 'N/A'}</p>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">Type</small>
                                                <p class="fw-bold mb-0">${livestock.type ? livestock.type.charAt(0).toUpperCase() + livestock.type.slice(1) : 'N/A'}</p>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">Breed</small>
                                                <p class="fw-bold mb-0">${(livestock.breed_name && livestock.breed_name.trim() !== '')
                                                    ? livestock.breed_name
                                                    : (livestock.breed && livestock.breed !== 'other'
                                                        ? livestock.breed.replace('_',' ').replace(/\b\w/g, l => l.toUpperCase())
                                                        : 'N/A')}</p>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">Age</small>
                                                <p class="fw-bold mb-0">${age}</p>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">Previous Weight</small>
                                                <p class="fw-bold mb-0">${livestock.previous_weight ? livestock.previous_weight + ' kg' : 'N/A'}</p>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">Previous Weight Date</small>
                                                <p class="fw-bold mb-0">${livestock.previous_weight_date ? new Date(livestock.previous_weight_date).toLocaleDateString() : 'N/A'}</p>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">Status</small>
                                                <p class="fw-bold mb-0"><span class="badge badge-${livestock.status === 'active' ? 'success' : 'secondary'}">${livestock.status ? livestock.status.charAt(0).toUpperCase() + livestock.status.slice(1) : 'N/A'}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <ul class="nav nav-tabs smart-tabs mb-3" id="livestockTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basicForm" role="tab">Basic Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="production-tab" data-toggle="tab" href="#productionForm" role="tab">Production</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="health-tab" data-toggle="tab" href="#healthForm" role="tab">Health</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="breeding-tab" data-toggle="tab" href="#breedingForm" role="tab">Breeding</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="growth-tab" data-toggle="tab" href="#growthForm" role="tab">Growth</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="livestockTabContent">
                            <div class="tab-pane fade show active" id="basicForm" role="tabpanel">
                                <div class="smart-table table-responsive">
                                    <table class="table table-sm table-bordered align-middle mb-0">
                                        <tbody>
                                            <tr><th>Owned By</th><td>${livestock.owned_by || 'Not recorded'}</td></tr>
                                            <tr><th>Dispersal From</th><td>${livestock.dispersal_from || 'Not recorded'}</td></tr>
                                            <tr><th>Registry ID Number</th><td>${livestock.registry_id || 'Not assigned'}</td></tr>
                                            <tr><th>Tag ID Number</th><td>${livestock.tag_number || 'Not assigned'}</td></tr>
                                            <tr><th>Name</th><td>${livestock.name || 'Not assigned'}</td></tr>
                                            <tr><th>Date of Birth</th><td>${birthDate}</td></tr>
                                            <tr><th>Sex</th><td>${livestock.gender ? livestock.gender.charAt(0).toUpperCase() + livestock.gender.slice(1) : 'Not recorded'}</td></tr>
                                            <tr><th>Breed</th><td>${livestock.breed_name && livestock.breed_name.trim() !== '' 
                                                ? livestock.breed_name 
                                                : (livestock.breed && livestock.breed !== 'other' 
                                                    ? livestock.breed.replace('_',' ').replace(/\b\w/g, l => l.toUpperCase()) 
                                                    : 'Not recorded')}</td></tr>
                                            <tr><th>Sire Registry ID</th><td>${livestock.sire_id || 'Not recorded'}</td></tr>
                                            <tr><th>Sire Name</th><td>${livestock.sire_name || 'Not recorded'}</td></tr>
                                            <tr><th>Sire Breed</th><td>${livestock.sire_breed || 'Not recorded'}</td></tr>
                                            <tr><th>Dam Registry ID</th><td>${livestock.dam_id || 'Not recorded'}</td></tr>
                                            <tr><th>Dam Name</th><td>${livestock.dam_name || 'Not recorded'}</td></tr>
                                            <tr><th>Dam Breed</th><td>${livestock.dam_breed || 'Not recorded'}</td></tr>
                                            <tr><th>Distinct Characteristics</th><td>${livestock.distinct_characteristics || 'None recorded'}</td></tr>
                                            <tr><th>Natural Marks</th><td>${livestock.natural_marks || 'None recorded'}</td></tr>
                                            <tr><th>Property No.</th><td>${livestock.property_no || 'Not assigned'}</td></tr>
                                            <tr><th>Date Acquired</th><td>${livestock.acquisition_date ? new Date(livestock.acquisition_date).toLocaleDateString() : 'Not recorded'}</td></tr>
                                            <tr><th>Acquisition Cost</th><td>${livestock.acquisition_cost ? '₱' + parseFloat(livestock.acquisition_cost).toFixed(2) : 'Not recorded'}</td></tr>
                                            <tr><th>Source / Origin</th><td>${livestock.source_origin || 'Not recorded'}</td></tr>
                                            <tr><th>Remarks</th><td>${cleanedRemarks || 'None'}</td></tr>
                                            <tr><th>Name of Cooperator</th><td>${livestock.cooperator_name || 'Not recorded'}</td></tr>
                                            <tr><th>Date Released</th><td>${livestock.date_released ? new Date(livestock.date_released).toLocaleDateString() : 'Not recorded'}</td></tr>
                                            <tr><th>Cooperative</th><td>${livestock.cooperative_name || 'Not recorded'}</td></tr>
                                            <tr><th>Address</th><td>${livestock.cooperative_address || 'Not recorded'}</td></tr>
                                            <tr><th>Contact No.</th><td>${livestock.cooperative_contact_no || 'Not recorded'}</td></tr>
                                            <tr><th>In-Charge</th><td>${livestock.in_charge || 'Not recorded'}</td></tr>
                                            <tr><th>Farm</th><td>${livestock.farm ? livestock.farm.name : 'Not assigned'}</td></tr>
                                            <tr><th>Created</th><td>${createdDate}</td></tr>
                                            <tr><th>Last Updated</th><td>${updatedDate}</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="productionForm" role="tabpanel">
                                <div class="smart-table table-responsive">
                                    <table class="table table-sm table-bordered align-middle mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Quantity</th>
                                                <th>Quality</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody id="productionRecordsTable">
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-3">
                                                    <i class="fas fa-info-circle"></i>
                                                    No production records found.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-right mt-3">
                                    <button class="btn-action btn-action-ok" onclick="adminAddProductionRecord('${livestock.id}')">
                                        <i class="fas fa-plus"></i> Add Production Record
                                    </button>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="healthForm" role="tabpanel">
                                <div class="smart-table table-responsive">
                                    <table class="table table-sm table-bordered align-middle mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Observations</th>
                                                <th>Test Performed</th>
                                                <th>Diagnosis/ Remarks</th>
                                                <th>Drugs/ Biologicals Given</th>
                                                <th>Signature</th>
                                            </tr>
                                        </thead>
                                        <tbody id="healthRecordsTable">
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-3">
                                                    <i class="fas fa-info-circle"></i>
                                                    No health records found.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-right mt-3">
                                    <button class="btn-action btn-action-ok" onclick="adminAddHealthRecord('${livestock.id}')">
                                        <i class="fas fa-plus"></i> Add Health Record
                                    </button>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="breedingForm" role="tabpanel">
                                <div class="smart-table table-responsive mt-3" style="display:block; max-width:100%; overflow-x: auto !important; -webkit-overflow-scrolling: touch; padding-bottom:8px;">
                                    <table class="table table-sm table-bordered align-middle mb-0" style="min-width: 1200px !important; table-layout: auto; width: 100% !important;">
                                        <thead class="thead-light">
                                            <tr>
                                                <th rowspan="2">Date of Service</th>
                                                <th rowspan="2">BCS</th>
                                                <th colspan="3" class="text-center">Signs of Estrus</th>
                                                <th colspan="2" class="text-center">Bull Used</th>
                                                <th colspan="2" class="text-center">PD</th>
                                                <th rowspan="2">AI Tech's Signature</th>
                                            </tr>
                                            <tr>
                                                <th>VO</th>
                                                <th>UT</th>
                                                <th>MD</th>
                                                <th>ID No.</th>
                                                <th>Name</th>
                                                <th>Date</th>
                                                <th>Result</th>
                                            </tr>
                                        </thead>
                                        <tbody id="breedingRecordsTable">
                                            <tr>
                                                <td colspan="10" class="text-center text-muted py-3">
                                                    <i class="fas fa-info-circle"></i>
                                                    No breeding records found.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-muted small mt-2">
                                    <strong>Signs of Estrus Legend:</strong>
                                    MD - Mucus discharge (1, 2, 3); UT - Uterine tone (1, 2, 3); VO - Vaginal opening (1, 2, 3)
                                </div>
                                <div class="text-right mt-3">
                                    <button class="btn-action btn-action-ok" onclick="adminAddBreedingRecord('${livestock.id}')">
                                        <i class="fas fa-plus"></i> Add Breeding Record
                                    </button>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="growthForm" role="tabpanel">
                                <div class="smart-table table-responsive">
                                    <table class="table table-sm table-bordered align-middle mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Weight (kg)</th>
                                                <th>Height (cm)</th>
                                                <th>Heart girth (cm)</th>
                                                <th>Body length (cm)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="growthRecordsTable">
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-3">
                                                    <i class="fas fa-info-circle"></i>
                                                    No growth records found.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-right mt-3">
                                    <button class="btn-action btn-action-ok" onclick="adminAddGrowthRecord('${livestock.id}')">
                                        <i class="fas fa-plus"></i> Add Growth Record
                                    </button>
                                </div>
                            </div>
                        </div>
                    `);

                    // Load records for tabs
                    loadAdminProductionRecords(livestockId);
                    loadAdminHealthRecords(livestockId);
                    loadAdminBreedingRecords(livestockId);
                    loadAdminGrowthRecords(livestockId);

                    const $m = $('#livestockDetailsModal');
                    if (!$m.parent().is('body')) { $m.appendTo('body'); }
                    $m.modal({ backdrop: true, keyboard: true });
                    $m.modal('show');
                } else {
                    showNotification('Error loading livestock details', 'danger');
                }
            },
            error: function() {
                showNotification('Error loading livestock details', 'danger');
            }
        });
    }

    // Helpers mirrored from farmer view
    function calculateAge(birthDate) {
        try {
            const birth = new Date(birthDate);
            const today = new Date();
            let age = today.getFullYear() - birth.getFullYear();
            const monthDiff = today.getMonth() - birth.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                age--;
            }
            if (age === 0) {
                const monthAge = today.getMonth() - birth.getMonth();
                if (monthAge <= 0) {
                    const dayAge = today.getDate() - birth.getDate();
                    return dayAge <= 0 ? 'Less than 1 day' : `${dayAge} day${dayAge > 1 ? 's' : ''}`;
                }
                return `${monthAge} month${monthAge > 1 ? 's' : ''}`;
            }
            return `${age} year${age > 1 ? 's' : ''}`;
        } catch (_) { return 'Unknown'; }
    }

    function loadAdminProductionRecords(livestockId) {
        $.ajax({
            url: `{{ route("admin.livestock.production-records", ["id" => "__ID__"]) }}`.replace('__ID__', livestockId),
            method: 'GET',
            success: function(response) {
                const tbody = document.getElementById('productionRecordsTable');
                if (!tbody) return;
                tbody.innerHTML = '';
                if (response.success && Array.isArray(response.data) && response.data.length > 0) {
                    response.data.forEach(r => {
                        const tr = document.createElement('tr');
                        const dateStr = r.production_date ? new Date(r.production_date).toLocaleDateString() : '';
                        const ptype = r.production_type || 'Milk';
                        const qty = (r.quantity !== undefined && r.quantity !== null && r.quantity !== '') ? r.quantity : '';
                        const qual = (r.quality !== undefined && r.quality !== null && r.quality !== '') ? r.quality : '';
                        const notes = (r.notes || '').replace(/\[type:\s*[^\]]+\]\s*/i, '').trim();
                        tr.innerHTML = `
                            <td style="white-space: nowrap;">${dateStr || 'N/A'}</td>
                            <td style="white-space: nowrap;">${ptype}</td>
                            <td style="white-space: nowrap;">${qty !== '' ? qty : 'N/A'}</td>
                            <td style="white-space: nowrap;">${qual !== '' ? qual : 'N/A'}</td>
                            <td style="white-space: nowrap;">${notes || 'N/A'}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                } else {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td colspan="5" class="text-center text-muted py-3"><i class=\"fas fa-info-circle\"></i> No production records found.</td>`;
                    tbody.appendChild(tr);
                }
            }
        });
    }

    function loadAdminHealthRecords(livestockId) {
        $.ajax({
            url: `{{ route("admin.livestock.health-records", ["id" => "__ID__"]) }}`.replace('__ID__', livestockId),
            method: 'GET',
            success: function(response) {
                if (response.success && response.data && response.data.length > 0) {
                    const tbody = document.getElementById('healthRecordsTable');
                    if (!tbody) return;
                    tbody.innerHTML = '';
                    response.data.forEach(r => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${r.date ? new Date(r.date).toLocaleDateString() : 'N/A'}</td>
                            <td>${r.observations || 'N/A'}</td>
                            <td>${r.test || 'N/A'}</td>
                            <td style="text-align:left; white-space: normal; word-break: break-word;">${r.diagnosis || 'N/A'}</td>
                            <td>${r.drugs || 'N/A'}</td>
                            <td>${r.signature || 'N/A'}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            }
        });
    }

    function loadAdminBreedingRecords(livestockId) {
        $.ajax({
            url: `{{ route("admin.livestock.breeding-records", ["id" => "__ID__"]) }}`.replace('__ID__', livestockId),
            method: 'GET',
            success: function(response) {
                if (response.success && response.data && response.data.length > 0) {
                    const tbody = document.getElementById('breedingRecordsTable');
                    if (!tbody) return;
                    tbody.innerHTML = '';
                    response.data.forEach(r => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td style="white-space: nowrap;">${r.date_of_service ? new Date(r.date_of_service).toLocaleDateString() : 'N/A'}</td>
                            <td style="white-space: nowrap;">${r.bcs || 'N/A'}</td>
                            <td style="white-space: nowrap;">${r.vo || 'N/A'}</td>
                            <td style="white-space: nowrap;">${r.ut || 'N/A'}</td>
                            <td style="white-space: nowrap;">${r.md || 'N/A'}</td>
                            <td style="white-space: nowrap;">${r.bull_id_no || 'N/A'}</td>
                            <td style="white-space: nowrap;">${r.bull_name || 'N/A'}</td>
                            <td style="white-space: nowrap;">${r.pd_date || 'N/A'}</td>
                            <td style="white-space: nowrap;">${r.pd_result || 'N/A'}</td>
                            <td style="white-space: nowrap;">${r.ai_signature || 'N/A'}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            }
        });
    }

    function adminAddProductionRecord(livestockId) {
        if (typeof $.fn.modal === 'undefined') { showNotification('Modal is unavailable.', 'danger'); return; }
        const modalHtml = `
<div class="modal fade" id="adminProductionRecordModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content smart-form text-center p-4">
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle"><i class="fas fa-chart-line fa-2x"></i></div>
        <h5 class="fw-bold mb-1">Add Production Record</h5>
        <p class="text-muted mb-0 small">Enter production details.</p>
      </div>
      <form id="adminProductionRecordForm" class="text-start mx-auto">
        <div class="form-wrapper">
          <div class="row g-3">
            <div class="col-md-6"><label class="fw-semibold">Production Date <span class="text-danger">*</span></label><input type="date" class="form-control mt-1" name="production_date" required max="{{ date('Y-m-d') }}"></div>
            <div class="col-md-6"><label class="fw-semibold">Production Type <span class="text-danger">*</span></label><select class="form-control mt-1" name="production_type" required><option value="">Select Type</option><option value="milk">Milk</option><option value="eggs">Eggs</option><option value="meat">Meat</option><option value="wool">Wool</option></select></div>
            <div class="col-md-6"><label class="fw-semibold">Quantity <span class="text-danger">*</span></label><input type="number" class="form-control mt-1" name="quantity" min="0" step="0.1" required></div>
            <div class="col-md-6"><label class="fw-semibold">Quality</label><select class="form-control mt-1" name="quality"><option value="">Select Quality</option><option value="excellent">Excellent</option><option value="good">Good</option><option value="fair">Fair</option><option value="poor">Poor</option></select></div>
            <div class="col-12 mb-3"><label class="fw-semibold">Notes</label><textarea class="form-control mt-1" name="notes" rows="3" style="resize:none;"></textarea></div>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
          <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn-modern btn-ok" onclick="adminSaveProductionRecord('${livestockId}')"><i class="fas fa-save"></i> Save Record</button>
        </div>
      </form>
    </div>
  </div>
</div>`;
        $('#adminProductionRecordModal').remove();
        $('body').append(modalHtml);
        $('#adminProductionRecordModal').modal('show');
    }

    function adminSaveProductionRecord(livestockId) {
        livestockId = livestockId || selectedLivestockId;
        if (!livestockId) { showNotification('Missing livestock ID.', 'danger'); return; }
        const form = document.getElementById('adminProductionRecordForm');
        const fd = new FormData(form);
        const btn = document.querySelector('#adminProductionRecordModal .btn-ok');
        if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...'; }
        const qualityMap = { excellent: 10, good: 8, fair: 6, poor: 4 };
        const quality = fd.get('quality');
        const qty = fd.get('quantity');
        if (qty !== null) fd.append('milk_quantity', qty);
        if (quality) fd.append('milk_quality_score', qualityMap[quality] || '');
        const pType = fd.get('production_type');
        const userNotes = (fd.get('notes') || '').toString().trim();
        const combinedNotes = `[type: ${pType || 'milk'}] ${userNotes}`.trim();
        fd.set('notes', combinedNotes);
        $.ajax({
            url: `{{ route('admin.livestock.production.store', ["id" => "__ID__"]) }}`.replace('__ID__', livestockId),
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            success: function(res){
                if (res && res.success) {
                    $('#adminProductionRecordModal').modal('hide');
                    loadAdminProductionRecords(livestockId);
                    showNotification('Production record added successfully!', 'success');
                } else { showNotification('Error adding production record', 'danger'); }
            },
            error: function(xhr){ showNotification(xhr.responseJSON?.message || 'Error adding production record', 'danger'); },
            complete: function(){ if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Save Record'; } }
        });
    }

    function adminAddHealthRecord(livestockId) {
        if (typeof $.fn.modal === 'undefined') { showNotification('Modal is unavailable.', 'danger'); return; }
        const modalHtml = `
<div class="modal fade" id="adminHealthRecordModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content smart-form text-center p-4">
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle"><i class="fas fa-heartbeat fa-2x"></i></div>
        <h5 class="fw-bold mb-1">Add Health Record</h5>
        <p class="text-muted mb-0 small">Provide health details.</p>
      </div>
      <form id="adminHealthRecordForm" class="text-start mx-auto">
        <div class="form-wrapper">
          <div class="row g-3">
            <div class="col-md-6"><label class="fw-semibold">Health Check Date <span class="text-danger">*</span></label><input type="date" class="form-control mt-1" name="health_date" required max="{{ date('Y-m-d') }}"></div>
            <div class="col-md-6"><label class="fw-semibold">Health Status <span class="text-danger">*</span></label><select class="form-control mt-1" name="health_status" required><option value="">Select Status</option><option value="healthy">Healthy</option><option value="sick">Sick</option><option value="recovering">Recovering</option><option value="under_treatment">Under Treatment</option></select></div>
            <div class="col-md-6"><label class="fw-semibold">Weight (kg)</label><input type="number" class="form-control mt-1" name="weight" min="0" step="0.1"></div>
            <div class="col-md-6"><label class="fw-semibold">Temperature (°C)</label><input type="number" class="form-control mt-1" name="temperature" min="0" step="0.1"></div>
            <div class="col-12 mb-3"><label class="fw-semibold">Symptoms / Observations</label><textarea class="form-control mt-1" name="symptoms" rows="3" style="resize:none;"></textarea></div>
            <div class="col-12 mb-3"><label class="fw-semibold">Treatment Given</label><textarea class="form-control mt-1" name="treatment" rows="3" style="resize:none;"></textarea></div>
            <div class="col-lg-6 col-md-6 mb-3"><label class="fw-semibold">Veterinarian</label><select class="form-control mt-1" name="veterinarian_id"><option value="">Select Veterinarian</option></select></div>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
          <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn-modern btn-ok" onclick="adminSaveHealthRecord('${livestockId}')"><i class="fas fa-save"></i> Save Record</button>
        </div>
      </form>
    </div>
  </div>
</div>`;
        $('#adminHealthRecordModal').remove();
        $('body').append(modalHtml);
        $('#adminHealthRecordModal').modal('show');
        $.ajax({ url: '{{ route("admin.veterinarians.list") }}', method: 'GET', success: function(res){ if (res && res.success) { const sel = document.querySelector('#adminHealthRecordModal select[name="veterinarian_id"]'); if (sel) { if ((res.data||[]).length === 0) { const opt=document.createElement('option'); opt.value=''; opt.textContent='No veterinarians found'; sel.appendChild(opt);} else { res.data.forEach(a=>{ const opt=document.createElement('option'); opt.value=a.id; opt.textContent=a.name; sel.appendChild(opt); }); } } } }});
    }

    function adminSaveHealthRecord(livestockId) {
        livestockId = livestockId || selectedLivestockId;
        if (!livestockId) { showNotification('Missing livestock ID.', 'danger'); return; }
        const fd = new FormData(document.getElementById('adminHealthRecordForm'));
        $.ajax({
            url: `{{ route('admin.livestock.health.store', ["id" => "__ID__"]) }}`.replace('__ID__', livestockId),
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                if (res && res.success) {
                    $('#adminHealthRecordModal').modal('hide');
                    loadAdminHealthRecords(livestockId);
                    showNotification('Health record added successfully!', 'success');
                } else { showNotification('Error adding health record', 'danger'); }
            },
            error: function(xhr){ showNotification(xhr.responseJSON?.message || 'Error adding health record', 'danger'); }
        });
    }

    function adminAddBreedingRecord(livestockId) {
        if (typeof $.fn.modal === 'undefined') { showNotification('Modal is unavailable.', 'danger'); return; }
        const modalHtml = `
<div class="modal fade" id="adminBreedingRecordModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content smart-form text-center p-4">
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle"><i class="fas fa-heart fa-2x"></i></div>
        <h5 class="fw-bold mb-1">Add Breeding Record</h5>
        <p class="text-muted mb-0 small">Provide breeding details.</p>
      </div>
      <form id="adminBreedingRecordForm" class="text-start mx-auto">
        <div class="form-wrapper">
          <div class="row g-3">
            <div class="col-md-6"><label class="fw-semibold">Breeding Date <span class="text-danger">*</span></label><input type="date" class="form-control mt-1" name="breeding_date" required max="{{ date('Y-m-d') }}"></div>
            <div class="col-md-6"><label class="fw-semibold">Breeding Type <span class="text-danger">*</span></label><select class="form-control mt-1" name="breeding_type" required><option value="">Select Type</option><option value="natural">Natural Breeding</option><option value="artificial">Artificial Insemination</option><option value="embryo_transfer">Embryo Transfer</option></select></div>
            <div class="col-md-6"><label class="fw-semibold">Partner Livestock ID</label><input type="text" class="form-control mt-1" name="partner_livestock_id"></div>
            <div class="col-md-6"><label class="fw-semibold">Expected Birth Date</label><input type="date" class="form-control mt-1" name="expected_birth_date" max="{{ date('Y-m-d') }}"></div>
            <div class="col-md-6"><label class="fw-semibold">Pregnancy Status</label><select class="form-control mt-1" name="pregnancy_status"><option value="">Select Status</option><option value="unknown">Unknown</option><option value="pregnant">Pregnant</option><option value="not_pregnant">Not Pregnant</option></select></div>
            <div class="col-md-6"><label class="fw-semibold">Breeding Success</label><select class="form-control mt-1" name="breeding_success"><option value="">Select Result</option><option value="unknown">Unknown</option><option value="successful">Successful</option><option value="unsuccessful">Unsuccessful</option></select></div>
            <div class="col-12 mb-3"><label class="fw-semibold">Notes</label><textarea class="form-control mt-1" name="notes" rows="3" style="resize:none;"></textarea></div>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
          <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn-modern btn-ok" onclick="adminSaveBreedingRecord('${livestockId}')"><i class="fas fa-save"></i> Save Record</button>
        </div>
      </form>
    </div>
  </div>
</div>`;
        $('#adminBreedingRecordModal').remove();
        $('body').append(modalHtml);
        $('#adminBreedingRecordModal').modal('show');
    }

    function adminSaveBreedingRecord(livestockId) {
        livestockId = livestockId || selectedLivestockId;
        if (!livestockId) { showNotification('Missing livestock ID.', 'danger'); return; }
        const fd = new FormData(document.getElementById('adminBreedingRecordForm'));
        $.ajax({
            url: `{{ route('admin.livestock.breeding.store', ["id" => "__ID__"]) }}`.replace('__ID__', livestockId),
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                if (res && res.success) {
                    $('#adminBreedingRecordModal').modal('hide');
                    loadAdminBreedingRecords(livestockId);
                    showNotification('Breeding record added successfully!', 'success');
                } else { showNotification('Error adding breeding record', 'danger'); }
            },
            error: function(xhr){ showNotification(xhr.responseJSON?.message || 'Error adding breeding record', 'danger'); }
        });
    }

    function adminAddGrowthRecord(livestockId) {
        if (typeof $.fn.modal === 'undefined') { showNotification('Modal is unavailable.', 'danger'); return; }
        const modalHtml = `
<div class="modal fade" id="adminGrowthRecordModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content smart-form text-center p-4">
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle"><i class="fas fa-seedling fa-2x"></i></div>
        <h5 class="fw-bold mb-1">Add Growth Record</h5>
        <p class="text-muted mb-0 small">Record measurements for growth monitoring.</p>
      </div>
      <form id="adminGrowthRecordForm" class="text-start mx-auto">
        <div class="form-wrapper">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="fw-semibold">Date</label>
              <input type="date" class="form-control mt-1" name="growth_date" max="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-6">
              <label class="fw-semibold">Weight (kg)</label>
              <input type="number" step="0.01" min="0" class="form-control mt-1" name="weight_kg">
            </div>
            <div class="col-md-6">
              <label class="fw-semibold">Height (cm)</label>
              <input type="number" step="0.1" min="0" class="form-control mt-1" name="height_cm">
            </div>
            <div class="col-md-6">
              <label class="fw-semibold">Heart girth (cm)</label>
              <input type="number" step="0.1" min="0" class="form-control mt-1" name="heart_girth_cm">
            </div>
            <div class="col-md-6">
              <label class="fw-semibold">Body length (cm)</label>
              <input type="number" step="0.1" min="0" class="form-control mt-1" name="body_length_cm">
            </div>
          </div>
        </div>
      <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
        <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn-modern btn-ok" onclick="adminSaveGrowthRecord('${livestockId}')"><i class="fas fa-save"></i> Save Record</button>
      </div>
      </form>
    </div>
  </div>
</div>`;
        $('#adminGrowthRecordModal').remove();
        $('body').append(modalHtml);
        $('#adminGrowthRecordModal').modal('show');
    }

    function adminSaveGrowthRecord(livestockId) {
        livestockId = livestockId || selectedLivestockId;
        if (!livestockId) { showNotification('Missing livestock ID.', 'danger'); return; }
        const fd = new FormData(document.getElementById('adminGrowthRecordForm'));
        $.ajax({
            url: `{{ route('admin.livestock.growth.store', ["id" => "__ID__"]) }}`.replace('__ID__', livestockId),
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                if (res && res.success) {
                    $('#adminGrowthRecordModal').modal('hide');
                    loadAdminGrowthRecords(livestockId);
                    showNotification('Growth record added successfully!', 'success');
                } else { showNotification(res.message || 'Error adding growth record', 'danger'); }
            },
            error: function(xhr){ showNotification(xhr.responseJSON?.message || 'Error adding growth record', 'danger'); }
        });
    }

    function printAdminLivestockRecord() {
        if (!selectedLivestockId) { showNotification('No livestock selected.', 'warning'); return; }
        const l = currentAdminLivestockData || {};
        const fmt = (d) => {
            if (!d) return '';
            try { const x = new Date(d); if (!isNaN(x)) return x.toLocaleDateString(); } catch(e) {}
            const s = String(d); return s.length >= 10 ? s.slice(0,10) : s;
        };
        const farmName = (l.farm && (l.farm.name)) ? l.farm.name : '';
        const b = currentAdminLivestockPrintBasic || {
            tag_number: (l.tag_number || ''),
            name: (l.name || ''),
            type: (l.type || ''),
            breed: (l.breed || ''),
            gender: (l.gender || ''),
            birth_date: (l.birth_date || ''),
            status: (l.status || ''),
            health_status: (l.health_status || ''),
            weight: (l.weight ?? ''),
            farm_name: farmName
        };

        const detailsReq = $.ajax({ url: `{{ route('admin.livestock.details', ["id" => "__ID__"]) }}`.replace('__ID__', selectedLivestockId), method: 'GET' });
        const prodReq = $.ajax({ url: `{{ route('admin.livestock.production-records', ["id" => "__ID__"]) }}`.replace('__ID__', selectedLivestockId), method: 'GET' });
        const healthReq = $.ajax({ url: `{{ route('admin.livestock.health-records', ["id" => "__ID__"]) }}`.replace('__ID__', selectedLivestockId), method: 'GET' });
        const breedReq = $.ajax({ url: `{{ route('admin.livestock.breeding-records', ["id" => "__ID__"]) }}`.replace('__ID__', selectedLivestockId), method: 'GET' });

        $.when(detailsReq, prodReq, healthReq, breedReq).done(function(dRes, pRes, hRes, brRes){
            const fresh = dRes && dRes[0] ? (dRes[0].data || null) : null;
            const ll = fresh || l || {};
            const farm2 = (ll.farm && ll.farm.name) ? ll.farm.name : farmName;

            const ensureFilled = (primary, fallback) => {
                const out = { ...primary };
                const keys = ['tag_number','name','type','breed','breed_name','gender','birth_date','status','health_status','weight','farm_name'];
                keys.forEach(k => {
                    const v = out[k];
                    if (v === undefined || v === null || (typeof v === 'string' && v.trim() === '')) {
                        const fv = fallback && fallback[k] !== undefined && fallback[k] !== null ? fallback[k] : '';
                        out[k] = fv;
                    }
                });
                return out;
            };
            const bbSafe = ensureFilled({
                tag_number: (ll.tag_number || b.tag_number || ''),
                name: (ll.name || b.name || ''),
                type: (ll.type || b.type || ''),
                breed: (ll.breed || b.breed || ''),
                breed_name: (ll.breed_name || b.breed_name || ''),
                gender: (ll.gender || b.gender || ''),
                birth_date: (ll.birth_date || b.birth_date || ''),
                status: (ll.status || b.status || ''),
                health_status: (ll.health_status || b.health_status || ''),
                weight: ((ll.weight ?? b.weight) ?? ''),
                farm_name: (farm2 || b.farm_name || '')
            }, b);

            const money = (n) => {
                if (n === undefined || n === null || n === '') return '';
                const num = Number(n);
                if (isNaN(num)) return '';
                return '₱' + num.toFixed(2);
            };
            const up1 = (s) => (s || '').replace(/^\w/, m=>m.toUpperCase());
            const upWords = (s) => (s || '').replace('_',' ').replace(/\b\w/g, c=>c.toUpperCase());
            const ageStr = ll.birth_date ? calculateAge(ll.birth_date) : '';
            const registry = ll.registry_id || '';
            const naturalMarks = ll.natural_marks || '';
            const propertyNo = ll.property_no || '';
            const acqDate = fmt(ll.acquisition_date || '');
            const acqCost = money(ll.acquisition_cost);
            const remarksClean = (() => {
                const raw = (ll.remarks || '').toString();
                return raw.split(/\r?\n/).filter(line => !/^\s*\[(Health|Breeding|Calving|Growth|Production)\]/i.test(line)).join('\n').trim();
            })();
            const createdAt = fmt(ll.created_at || '');

            const typeDisp = upWords(bbSafe.type);
            const breedDisp = (bbSafe.breed_name && bbSafe.breed_name.trim() !== '')
                ? bbSafe.breed_name
                : (bbSafe.breed && bbSafe.breed !== 'other' ? upWords(bbSafe.breed) : '');
            const genderDisp = up1(bbSafe.gender);
            const statusDisp = up1(bbSafe.status);
            const healthDisp = up1(bbSafe.health_status);
            const weightDisp = bbSafe.weight !== undefined && bbSafe.weight !== null && bbSafe.weight !== '' ? bbSafe.weight : '';

            const basicInfo = `
                <table>
                    <thead><tr><th colspan="2">Basic Information</th></tr></thead>
                    <tbody>
                        <tr><td>Tag Number</td><td>${bbSafe.tag_number || ''}</td></tr>
                        <tr><td>Name</td><td>${bbSafe.name || ''}</td></tr>
                        <tr><td>Type</td><td>${typeDisp}</td></tr>
                        <tr><td>Breed</td><td>${breedDisp}</td></tr>
                        <tr><td>Date of Birth</td><td>${fmt(bbSafe.birth_date)}</td></tr>
                        <tr><td>Age</td><td>${ageStr}</td></tr>
                        <tr><td>Gender</td><td>${genderDisp}</td></tr>
                        <tr><td>Weight</td><td>${weightDisp}</td></tr>
                        <tr><td>Health Status</td><td>${healthDisp}</td></tr>
                        <tr><td>Status</td><td>${statusDisp}</td></tr>
                        <tr><td>Farm</td><td>${bbSafe.farm_name || ''}</td></tr>
                        <tr><td>Registry ID</td><td>${registry}</td></tr>
                        <tr><td>Natural Marks</td><td>${naturalMarks}</td></tr>
                        <tr><td>Property Number</td><td>${propertyNo}</td></tr>
                        <tr><td>Acquisition Date</td><td>${acqDate}</td></tr>
                        <tr><td>Acquisition Cost</td><td>${acqCost}</td></tr>
                        <tr><td>Remarks</td><td>${remarksClean}</td></tr>
                        <tr><td>Created</td><td>${createdAt}</td></tr>
                    </tbody>
                </table>`;

            const pData = (pRes && pRes[0] && pRes[0].success) ? (pRes[0].data || []) : [];
            const hData = (hRes && hRes[0] && hRes[0].success) ? (hRes[0].data || []) : [];
            const bData = (brRes && brRes[0] && brRes[0].success) ? (brRes[0].data || []) : [];

            const prodRows = pData.map(r => `
                <tr>
                    <td>${r.date_of_calving ? new Date(r.date_of_calving).toLocaleDateString() : ''}</td>
                    <td>${r.calf_id || ''}</td>
                    <td>${r.sex || ''}</td>
                    <td>${r.breed || ''}</td>
                    <td>${r.sire_id || ''}</td>
                    <td>${(r.milk_production ?? '')}</td>
                    <td>${(r.dim ?? '')}</td>
                </tr>
            `).join('');
            const productionTbl = `
                <table>
                    <thead><tr><th colspan="7">Calving and Milk Production Record</th></tr><tr><th>Date of Calving</th><th>Calf ID No.</th><th>Sex</th><th>Breed</th><th>Sire ID No.</th><th>Milk Prod'n</th><th>Days in Milk (DIM)</th></tr></thead>
                    <tbody>${prodRows || '<tr><td colspan="7">No production records found.</td></tr>'}</tbody>
                </table>`;

            const healthRows = hData.map(r => `
                <tr>
                    <td>${r.date ? new Date(r.date).toLocaleDateString() : ''}</td>
                    <td>${r.observations || ''}</td>
                    <td>${r.test || ''}</td>
                    <td>${r.diagnosis || ''}</td>
                    <td>${r.drugs || ''}</td>
                    <td>${r.signature || ''}</td>
                </tr>
            `).join('');
            const healthTbl = `
                <table>
                    <thead><tr><th colspan="6">Health Record</th></tr><tr><th>Date</th><th>Observations</th><th>Test Performed</th><th>Diagnosis/ Remarks</th><th>Drugs/ Biologicals Given</th><th>Signature</th></tr></thead>
                    <tbody>${healthRows || '<tr><td colspan="6">No health records found.</td></tr>'}</tbody>
                </table>`;

            const breedingRows = bData.map(r => `
                <tr>
                    <td>${r.date_of_service ? new Date(r.date_of_service).toLocaleDateString() : ''}</td>
                    <td>${r.bcs || ''}</td>
                    <td>${r.vo || ''}</td>
                    <td>${r.ut || ''}</td>
                    <td>${r.md || ''}</td>
                    <td>${r.bull_id_no || ''}</td>
                    <td>${r.bull_name || ''}</td>
                    <td>${r.pd_date || ''}</td>
                    <td>${r.pd_result || ''}</td>
                    <td>${r.ai_signature || ''}</td>
                </tr>
            `).join('');
            const breedingTbl = `
                <table>
                    <thead>
                        <tr><th colspan="10">Breeding/AI Activity Record</th></tr>
                        <tr>
                            <th rowspan="2">Date of Service</th>
                            <th rowspan="2">BCS</th>
                            <th colspan="3">Signs of Estrus</th>
                            <th colspan="2">Bull Used</th>
                            <th colspan="2">PD</th>
                            <th rowspan="2">AI Tech's Signature</th>
                        </tr>
                        <tr>
                            <th>VO</th>
                            <th>UT</th>
                            <th>MD</th>
                            <th>ID No.</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tbody>${breedingRows || '<tr><td colspan="10">No breeding records found.</td></tr>'}</tbody>
                </table>`;
            const breedingLegend = `<div style="font-size:12px;margin:6px 0 0 0;">Signs of Estrus Legend: MD - Mucus discharge (1, 2, 3); UT - Uterine tone (1, 2, 3); VO - Vaginal opening (1, 2, 3)</div>`;

            const title = `
                <div style="margin-bottom:10px; text-align:center;">
                    <h3 style="margin:0 0 6px 0;color:#18375d;">Individual Animal Record</h3>
                    <div style="font-size:12px;color:#333;">Generated: ${new Date().toLocaleString()}</div>
                </div>`;

            const tableCss = `
                <style>
                @page{size:auto;margin:12mm;}
                html,body{background:#fff!important;color:#000;}
                table{width:100%;border-collapse:collapse;margin:0 0 12px 0;}
                th,td{border:3px solid #000;padding:10px;text-align:left;}
                thead th{background:#f2f2f2;color:#18375d;}
                </style>`;

            const container = document.createElement('div');
            container.innerHTML = tableCss + `<div>${title}${basicInfo}${productionTbl}${healthTbl}${breedingTbl}${breedingLegend}</div>`;
            if (typeof window.printElement === 'function') {
                window.printElement(container);
            } else if (typeof window.openPrintWindow === 'function') {
                window.openPrintWindow(container.innerHTML, '');
            } else {
                const w = window.open('', '_blank');
                if (w) {
                    w.document.open();
                    w.document.write(`<html><head><title></title></head><body>${container.innerHTML}</body></html>`);
                    w.document.close();
                    try { w.focus(); } catch(_){ }
                    try { w.print(); } catch(_){ }
                    try { w.close(); } catch(_){ }
                } else {
                    window.print();
                }
            }
        }).fail(function(){
            const tableCss = `
                <style>
                @page{size:auto;margin:12mm;}
                html,body{background:#fff!important;color:#000;}
                table{width:100%;border-collapse:collapse;margin:0 0 12px 0;}
                th,td{border:3px solid #000;padding:10px;text-align:left;}
                thead th{background:#f2f2f2;color:#18375d;}
                </style>`;
            const container = document.createElement('div');
            container.innerHTML = tableCss + `<div></div>`;
            if (typeof window.printElement === 'function') {
                window.printElement(container);
            } else if (typeof window.openPrintWindow === 'function') {
                window.openPrintWindow(container.innerHTML, 'Livestock Record');
            } else {
                const w = window.open('', '_blank');
                if (w) {
                    w.document.open();
                    w.document.write(`<html><head><title>Livestock Record</title></head><body>${container.innerHTML}</body></html>`);
                    w.document.close();
                    try { w.focus(); } catch(_){ }
                    try { w.print(); } catch(_){ }
                    try { w.close(); } catch(_){ }
                } else {
                    window.print();
                }
            }
        });
    }

    function generateQRCode(livestockId) {
        // Show loading state
        showNotification('Generating QR Code...', 'info');
        
        $.ajax({
            url: `{{ route("admin.livestock.qr-code", ["id" => "__ID__"]) }}`.replace('__ID__', livestockId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const generatedDate = response.generated_at ? new Date(response.generated_at).toLocaleDateString() : 'Just now';
                    $('#qrCodeContent').html(`
                        <div class="text-center">
                            <img src="${response.qr_code}" alt="QR Code" class="img-fluid mb-3" style="max-width: 150px;">
                            <p class="text-center text-muted small mb-2">Generated by: ${response.generated_by}</p>
                            <p class="text-center text-muted small mb-3">Generated on: ${generatedDate}</p>
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
            url: `{{ route("admin.livestock.qr-code", ["id" => "__ID__"]) }}`.replace('__ID__', livestockId),
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
