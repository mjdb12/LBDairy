@extends('layouts.app')

@section('title', 'LBDAIRY: SuperAdmin - Audit Logs')

@push('styles')
<style>
    /* Custom styles for audit logs */
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
    
    .btn-group .btn {
        margin-right: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .gap-2 {
        gap: 0.5rem !important;
    }
    
    /* Custom status badge colors for User Activity Summary */
    .badge-success[style*="background-color: #387057"] {
        background-color: #387057 !important;
        color: white !important;
    }
    
    .badge-danger[style*="background-color: #c82333"] {
        background-color: #c82333 !important;
        color: white !important;
    }
    
    /* Specific styling for user status badges */
    .user-status-badge {
        font-weight: bold !important;
    }
    
    .user-status-badge[style*="#387057"] {
        background-color: #387057 !important;
        color: white !important;
        border: none !important;
    }
    
    .user-status-badge[style*="#c82333"] {
        background-color: #c82333 !important;
        color: white !important;
        border: none !important;
    }
    
    /* Custom severity badge colors for System Activity Logs */
    #auditDataTable .badge-info,
    .badge-info {
        background-color: #387057 !important;
        color: white !important;
        border: none !important;
    }
    
    #auditDataTable .badge-warning,
    .badge-warning {
        background-color: #ffc107 !important;
        color: #212529 !important;
        border: none !important;
    }
    
    #auditDataTable .badge-danger,
    .badge-danger {
        background-color: #c82333 !important;
        color: white !important;
        border: none !important;
    }
    
    /* More specific targeting for audit logs table */
    table#auditDataTable .badge {
        font-weight: bold !important;
    }
    
    table#auditDataTable .badge-info {
        background-color: #387057 !important;
        color: white !important;
    }
    
    table#auditDataTable .badge-warning {
        background-color: #ffc107 !important;
        color: #212529 !important;
    }
    
    table#auditDataTable .badge-danger {
        background-color: #c82333 !important;
        color: white !important;
    }
    
    .log-id-link {
        color: #4e73df;
        text-decoration: none;
        font-weight: 600;
    }
    
    .log-id-link:hover {
        color: #2e59d9;
        text-decoration: underline;
    }
    
    /* Fix DataTable length menu overlapping */
    .dataTables_length {
        margin-bottom: 1rem;
    }
    
    .dataTables_length select {
        min-width: 80px;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        background-color: #fff;
        margin: 0 0.5rem;
    }
    
    .dataTables_length label {
        display: flex;
        align-items: center;
        margin-bottom: 0;
        font-weight: 500;
        color: #5a5c69;
    }
    
    /* Fix DataTable info overlapping */
    .dataTables_info {
        padding-top: 0.5rem;
        font-weight: 500;
        color: #5a5c69;
    }
    
    /* Fix DataTable pagination */
    .dataTables_paginate {
        margin-top: 1rem;
    }
    
    .dataTables_paginate .paginate_button {
        padding: 0.5rem 0.75rem;
        margin: 0 0.125rem;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        background-color: #fff;
        color: #5a5c69;
        text-decoration: none;
        transition: all 0.15s ease-in-out;
    }
    
    .dataTables_paginate .paginate_button:hover {
        background-color: #e3e6f0;
        border-color: #bac8f3;
        color: #4e73df;
    }
    
    .dataTables_paginate .paginate_button.current {
        background-color: #4e73df;
        border-color: #4e73df;
        color: #fff;
    }
    
    .dataTables_paginate .paginate_button.disabled {
        color: #858796;
        cursor: not-allowed;
        background-color: #f8f9fc;
        border-color: #e3e6f0;
    }
    
    /* Fix table header text wrapping */
    .table thead th {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
        padding: 0.75rem 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        vertical-align: middle;
        background-color: #f8f9fc;
        border-bottom: 2px solid #e3e6f0;
        color: #5a5c69;
    }
    
    /* Specific column width adjustments */
    .table thead th:nth-child(1) { /* Log ID */
        min-width: 80px;
        max-width: 80px;
    }
    
    .table thead th:nth-child(2) { /* Timestamp */
        min-width: 120px;
        max-width: 120px;
    }
    
    .table thead th:nth-child(3) { /* User */
        min-width: 120px;
        max-width: 120px;
    }
    
    .table thead th:nth-child(4) { /* Action */
        min-width: 80px;
        max-width: 80px;
    }
    
    .table thead th:nth-child(5) { /* Module */
        min-width: 100px;
        max-width: 100px;
    }
    
    .table thead th:nth-child(6) { /* Details */
        min-width: 150px;
        max-width: 200px;
    }
    
    .table thead th:nth-child(7) { /* IP Address */
        min-width: 100px;
        max-width: 100px;
    }
    
    .table thead th:nth-child(8) { /* Severity */
        min-width: 80px;
        max-width: 80px;
    }
    
    .table thead th:nth-child(9) { /* Actions */
        min-width: 100px;
        max-width: 100px;
    }
    
    /* Chart styling improvements */
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(45deg, #4e73df, #224abe) !important;
    }
    
    .bg-gradient-warning {
        background: linear-gradient(45deg, #f6c23e, #d39e00) !important;
    }
    
    
    /* Statistics styling */
    .text-xs {
        font-size: 0.7rem;
    }
    
    .h6 {
        font-size: 1rem;
    }
    
    /* User Management Style Button Actions */
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        margin: 0.25rem;
        border: 1px solid transparent;
        border-radius: 0.35rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.15s ease-in-out;
        cursor: pointer;
        white-space: nowrap;
    }
    
    .btn-action i {
        font-size: 0.875rem;
    }
    
    .btn-action span {
        font-size: 0.875rem;
    }
    
    .btn-action-view {
        background-color: #4e73df;
        border-color: #4e73df;
        color: white;
    }
    
    .btn-action-view:hover {
        background-color: #2e59d9;
        border-color: #2e59d9;
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
    
    .btn-action-print {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
    }
    
    .btn-action-print:hover {
        background-color: #5a6268;
        border-color: #5a6268;
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

    /* Log ID link styling - superadmin theme */
    .log-id-link {
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

    .log-id-link:hover {
        color: #fff;
        background-color: #18375d;
        border-color: #18375d;
        text-decoration: none;
    }

    .log-id-link:active {
        color: #fff;
        background-color: #122a4e;
        border-color: #122a4e;
    }
    
    /* Badge pill styling */
    .badge-pill {
        border-radius: 50rem;
        padding: 0.5em 1em;
        font-size: 0.7em;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        line-height: 1.2;
        display: inline-block;
        white-space: nowrap;
        vertical-align: baseline;
    }
    
    /* Security Alerts Table Styling - Match User Management */
    #securityAlertsTable {
        width: 100% !important;
        min-width: 1000px;
        border-collapse: collapse;
    }
    
    #securityAlertsTable th,
    #securityAlertsTable td {
        vertical-align: middle;
        padding: 0.75rem;
        text-align: center;
        border: 1px solid #dee2e6;
        white-space: nowrap;
        overflow: visible;
    }
    
    #securityAlertsTable thead th {
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
    
    /* Fix DataTables sorting button overlap for Security Alerts */
    #securityAlertsTable thead th.sorting,
    #securityAlertsTable thead th.sorting_asc,
    #securityAlertsTable thead th.sorting_desc {
        padding-right: 2rem !important;
    }
    
    /* Ensure proper spacing for sort indicators - Security Alerts */
    #securityAlertsTable thead th::after {
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
    
    /* DataTables sorting arrows - Security Alerts */
    #securityAlertsTable thead th.sorting,
    #securityAlertsTable thead th.sorting_asc,
    #securityAlertsTable thead th.sorting_desc {
        cursor: pointer !important;
        position: relative !important;
        padding-right: 2rem !important;
    }
    
    #securityAlertsTable thead th.sorting::after,
    #securityAlertsTable thead th.sorting_asc::after,
    #securityAlertsTable thead th.sorting_desc::after {
        content: '' !important;
        position: absolute !important;
        right: 0.5rem !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        width: 0 !important;
        height: 0 !important;
        border-left: 4px solid transparent !important;
        border-right: 4px solid transparent !important;
        display: block !important;
    }
    
    #securityAlertsTable thead th.sorting::after {
        border-top: 4px solid #6c757d !important;
        border-bottom: 4px solid #6c757d !important;
    }
    
    #securityAlertsTable thead th.sorting_asc::after {
        border-bottom: 4px solid #495057 !important;
    }
    
    #securityAlertsTable thead th.sorting_desc::after {
        border-top: 4px solid #495057 !important;
    }
    
    /* User Activity Table Styling - Match User Management */
    #userActivityTable {
        width: 100% !important;
        min-width: 800px;
        border-collapse: collapse;
    }
    
    #userActivityTable th,
    #userActivityTable td {
        vertical-align: middle;
        padding: 0.75rem;
        text-align: center;
        border: 1px solid #dee2e6;
        white-space: nowrap;
        overflow: visible;
    }
    
    #userActivityTable thead th {
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
    
    /* Fix DataTables sorting button overlap for User Activity */
    #userActivityTable thead th.sorting,
    #userActivityTable thead th.sorting_asc,
    #userActivityTable thead th.sorting_desc {
        padding-right: 2rem !important;
    }
    
    /* Ensure proper spacing for sort indicators - User Activity */
    #userActivityTable thead th::after {
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
    
    /* DataTables sorting arrows - User Activity */
    #userActivityTable thead th.sorting,
    #userActivityTable thead th.sorting_asc,
    #userActivityTable thead th.sorting_desc {
        cursor: pointer !important;
        position: relative !important;
        padding-right: 2rem !important;
    }
    
    #userActivityTable thead th.sorting::after,
    #userActivityTable thead th.sorting_asc::after,
    #userActivityTable thead th.sorting_desc::after {
        content: '' !important;
        position: absolute !important;
        right: 0.5rem !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        width: 0 !important;
        height: 0 !important;
        border-left: 4px solid transparent !important;
        border-right: 4px solid transparent !important;
        display: block !important;
    }
    
    #userActivityTable thead th.sorting::after {
        border-top: 4px solid #6c757d !important;
        border-bottom: 4px solid #6c757d !important;
    }
    
    #userActivityTable thead th.sorting_asc::after {
        border-bottom: 4px solid #495057 !important;
    }
    
    #userActivityTable thead th.sorting_desc::after {
        border-top: 4px solid #495057 !important;
    }
    
    /* Ensure table has enough space for actions column */
    .table th:last-child,
    .table td:last-child {
        min-width: 200px;
        width: auto;
    }

    /* System Activity Logs Table Styling - Match User Directory */
    #auditDataTable {
        width: 100% !important;
        min-width: 1200px;
        border-collapse: collapse;
    }
    
    /* Ensure horizontal scrolling for audit logs table */
    .table-responsive {
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
    }

    #auditDataTable th,
    #auditDataTable td {
        vertical-align: middle;
        padding: 0.75rem;
        text-align: center;
        border: 1px solid #dee2e6;
        white-space: nowrap;
        overflow: visible;
    }

    /* Ensure all table headers have consistent styling */
    #auditDataTable thead th {
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

    /* Fix DataTables sorting button overlap for Audit Data */
    #auditDataTable thead th.sorting,
    #auditDataTable thead th.sorting_asc,
    #auditDataTable thead th.sorting_desc {
        padding-right: 2rem !important;
    }

    /* Ensure proper spacing for sort indicators - Audit Data */
    #auditDataTable thead th::after {
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

    /* DataTables sorting arrows - Audit Data */
    #auditDataTable thead th.sorting,
    #auditDataTable thead th.sorting_asc,
    #auditDataTable thead th.sorting_desc {
        cursor: pointer !important;
        position: relative !important;
        padding-right: 2rem !important;
    }

    #auditDataTable thead th.sorting::after,
    #auditDataTable thead th.sorting_asc::after,
    #auditDataTable thead th.sorting_desc::after {
        content: '' !important;
        position: absolute !important;
        right: 0.5rem !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        width: 0 !important;
        height: 0 !important;
        border-left: 4px solid transparent !important;
        border-right: 4px solid transparent !important;
        display: block !important;
    }

    #auditDataTable thead th.sorting::after {
        border-top: 4px solid #6c757d !important;
        border-bottom: 4px solid #6c757d !important;
    }

    #auditDataTable thead th.sorting_asc::after {
        border-bottom: 4px solid #495057 !important;
    }

    #auditDataTable thead th.sorting_desc::after {
        border-top: 4px solid #495057 !important;
    }
    
    /* Table responsiveness and spacing */
    .table-responsive {
        overflow-x: auto;
        min-width: 100%;
        position: relative;
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
    
    /* Responsive fixes for DataTable */
    @media (max-width: 768px) {
        .dataTables_length,
        .dataTables_filter,
        .dataTables_info,
        .dataTables_paginate {
            text-align: center;
            margin-bottom: 0.5rem;
        }
        
        .dataTables_length label {
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        
        .dataTables_length select {
            margin: 0;
        }
        
        /* Mobile table header adjustments */
        .table thead th {
            font-size: 0.7rem;
            padding: 0.5rem 0.25rem;
            min-width: 60px;
            max-width: 80px;
        }
        
        /* Mobile chart adjustments */
        .chart-container {
            height: 250px;
        }
    }
    
    @media (max-width: 576px) {
        .chart-container {
            height: 200px;
        }
        
        .row.text-center .col-3,
        .row.text-center .col-6 {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-history"></i>
            Audit Logs
        </h1>
        <p>Monitor system activities, user actions, and security events</p>
    </div>

    <!-- Stats Cards -->
    <div class="row fade-in">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left-color: #1a365d !important;">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #1a365d !important;">Total Logs</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLogs ?? 0 }}</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-list fa-2x" style="color: #1a365d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left-color: #1a365d !important;">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #1a365d !important;">Today's Logs</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayLogs ?? 0 }}</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar fa-2x" style="color: #1a365d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left-color: #1a365d !important;">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #1a365d !important;">Critical Events</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $criticalEvents ?? 0 }}</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle fa-2x" style="color: #1a365d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left-color: #1a365d !important;">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #1a365d !important;">Active Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeUsers ?? 0 }}</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users fa-2x" style="color: #1a365d !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Analytics -->
    <div class="row fade-in">
        <!-- Activity Timeline Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-line"></i>
                        System Activity Timeline (Last 24 Hours)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="activityTimelineChart"></canvas>
                </div>
                    <div class="mt-3">
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #5a5c69;">Peak Hour</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800" id="peakHour">--:--</div>
                            </div>
                            <div class="col-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #5a5c69;">Total Events</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800" id="totalEvents">0</div>
                            </div>
                            <div class="col-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #5a5c69;">Avg/Hour</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800" id="avgPerHour">0</div>
                            </div>
                            <div class="col-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #5a5c69;">Status</div>
                                <div class="h6 mb-0 font-weight-bold text-success" id="activityStatus">Normal</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Severity Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header bg-gradient-warning text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie"></i>
                        Events by Severity
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="severityChart"></canvas>
                </div>
                    <div class="mt-3">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #5a5c69;">Critical Events</div>
                                <div class="h6 mb-0 font-weight-bold text-danger" id="criticalCount">0</div>
                            </div>
                            <div class="col-6">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #5a5c69;">System Health</div>
                                <div class="h6 mb-0 font-weight-bold text-success" id="systemHealth">Healthy</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Alerts -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-danger text-white">
            <h6 class="mb-0">
                <i class="fas fa-shield-alt"></i>
                Security Alerts & Critical Events
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
                    <input type="text" class="form-control" placeholder="Search alerts..." id="securitySearch">
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-center">
                    <button class="btn-action btn-action-refresh" onclick="refreshSecurityAlerts()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <button class="btn-action btn-action-print" onclick="printSecurityTable()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportSecurityCSV()">
                                <i class="fas fa-file-csv"></i> Download CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportSecurityPNG()">
                                <i class="fas fa-image"></i> Download PNG
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportSecurityPDF()">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="securityAlertsTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Timestamp</th>
                            <th>User</th>
                            <th>Event</th>
                            <th>Severity</th>
                            <th>Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($securityAlerts ?? [] as $alert)
                        <tr>
                            <td>{{ $alert->timestamp ?? 'N/A' }}</td>
                            <td>{{ $alert->user_name ?? 'N/A' }}</td>
                            <td>{{ $alert->event ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $alert->severity_class ?? 'warning' }} badge-pill">{{ $alert->severity_label ?? 'Warning' }}</span>
                            </td>
                            <td>{{ $alert->details ?? 'N/A' }}</td>
                            <td>
                                <button class="btn-action btn-action-view" onclick="investigateAlert('{{ $alert->id }}')" title="Investigate">
                                    <i class="fas fa-search"></i>
                                    <span>Investigate</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">No security alerts at this time</td>
                            <td class="text-center text-muted">N/A</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- User Activity Summary -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-success text-white">
            <h6 class="mb-0">
                <i class="fas fa-users"></i>
                User Activity Summary
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
                    <input type="text" class="form-control" placeholder="Search users..." id="userActivitySearch">
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-center">
                    <button class="btn-action btn-action-refresh" onclick="refreshUserActivity()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <button class="btn-action btn-action-print" onclick="printUserActivityTable()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportUserActivityCSV()">
                                <i class="fas fa-file-csv"></i> Download CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportUserActivityPNG()">
                                <i class="fas fa-image"></i> Download PNG
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportUserActivityPDF()">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="userActivityTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>User</th>
                            <th>Last Activity</th>
                            <th>Total Actions</th>
                            <th>Critical Events</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($userActivity ?? [] as $user)
                        <tr>
                            <td>{{ $user->name ?? 'N/A' }}</td>
                            <td>{{ $user->last_activity ?? 'N/A' }}</td>
                            <td>{{ $user->total_actions ?? 0 }}</td>
                            <td>{{ $user->critical_events ?? 0 }}</td>
                            <td>
                                @php
                                    $status = $user->status ?? 'Active';
                                    $statusClass = $status === 'Active' ? 'success' : 
                                                  ($status === 'Suspended' ? 'warning' : 'danger');
                                @endphp
                                <span class="badge badge-{{ $statusClass }} badge-pill user-status-badge" 
                                      style="@if($status === 'Active') background-color: #387057 !important; color: white !important; @elseif($status === 'Inactive') background-color: #c82333 !important; color: white !important; @endif">
                                    {{ $status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">No user activity data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- System Activity Logs Table (Moved to bottom) -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header bg-secondary text-white">
            <h6 class="mb-0">
                <i class="fas fa-table"></i>
                System Activity Logs (Latest 30 Entries)
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
                    <input type="text" class="form-control" placeholder="Search logs..." id="logSearch">
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-center">
                    <button class="btn-action btn-action-refresh" onclick="refreshSystemLogs()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <button class="btn-action btn-action-print" onclick="printSystemLogsTable()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportSystemLogsCSV()">
                                <i class="fas fa-file-csv"></i> Download CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportSystemLogsPNG()">
                                <i class="fas fa-image"></i> Download PNG
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportSystemLogsPDF()">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive" style="overflow-x: auto; max-width: 100%;">
                <table class="table table-bordered table-hover" id="auditDataTable" width="100%" cellspacing="0" style="min-width: 1200px;">
                    <thead class="thead-light">
                        <tr>
                            <th>Log ID</th>
                            <th>Timestamp</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Module</th>
                            <th>Details</th>
                            <th>IP Address</th>
                            <th>Severity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($auditLogs ?? [])->take(30) as $log)
                        <tr class="{{ $log->severity === 'critical' ? 'table-danger' : ($log->severity === 'warning' ? 'table-warning' : '') }}">
                            <td>
                                <a href="#" class="log-id-link" onclick="openLogDetails('{{ $log->id }}')" data-log-id="{{ $log->id }}">
                                    {{ $log->log_id ?? 'L' . str_pad($log->id, 3, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <td>{{ $log->timestamp ?? 'N/A' }}</td>
                            <td>
                                @if($log->user)
                                    <span class="font-weight-bold">{{ $log->user->name ?? 'N/A' }}</span>
                                    <br><small class="text-muted">{{ $log->user->email ?? 'N/A' }}</small>
                                @else
                                    <span class="text-muted">System</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $action = $log->action ?? 'unknown';
                                    $actionClass = $action === 'delete' ? 'danger' : 
                                                  ($action === 'update' ? 'warning' : 
                                                  ($action === 'create' ? 'success' : 'info'));
                                @endphp
                                <span class="badge badge-{{ $actionClass }}">
                                    {{ ucfirst($action) }}
                                </span>
                            </td>
                            <td>{{ $log->module ?? 'N/A' }}</td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $log->details ?? 'N/A' }}">
                                    {{ $log->details ?? 'N/A' }}
                                </span>
                            </td>
                            <td>{{ $log->ip_address ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $severity = $log->severity ?? 'info';
                                    
                                    // Map severity to appropriate class and color
                                    if ($severity === 'critical' || $severity === 'error') {
                                        $severityClass = 'danger';
                                        $severityColor = '#c82333';
                                        $textColor = 'white';
                                    } elseif ($severity === 'warning') {
                                        $severityClass = 'warning';
                                        $severityColor = '#ffc107';
                                        $textColor = '#212529';
                                    } elseif ($severity === 'danger') {
                                        $severityClass = 'danger';
                                        $severityColor = '#c82333';
                                        $textColor = 'white';
                                    } elseif ($severity === 'low') {
                                        $severityClass = 'info';
                                        $severityColor = '#387057';
                                        $textColor = 'white';
                                    } else {
                                        // Default for 'info' and any other
                                        $severityClass = 'info';
                                        $severityColor = '#387057';
                                        $textColor = 'white';
                                    }
                                @endphp
                                <span class="badge badge-{{ $severityClass }}" 
                                      style="background-color: {{ $severityColor }} !important; color: {{ $textColor }} !important;">
                                    {{ ucfirst($severity) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                <i class="fas fa-info-circle"></i>
                                No audit log data available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-history"></i>
                    Audit Log Details
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="logDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-filter"></i>
                    Advanced Filter Options
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="filterForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filterDateFrom">Date From</label>
                                <input type="date" class="form-control" id="filterDateFrom" name="date_from">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filterDateTo">Date To</label>
                                <input type="date" class="form-control" id="filterDateTo" name="date_to">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filterUser">User</label>
                                <select class="form-control" id="filterUser" name="user_id">
                                    <option value="">All Users</option>
                                    @foreach($users ?? [] as $user)
                                    <option value="{{ $user->id }}">{{ $user->name ?? 'N/A' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filterSeverity">Severity</label>
                                <select class="form-control" id="filterSeverity" name="severity">
                                    <option value="">All Severities</option>
                                    <option value="info">Info</option>
                                    <option value="warning">Warning</option>
                                    <option value="error">Error</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filterModule">Module</label>
                                <select class="form-control" id="filterModule" name="module">
                                    <option value="">All Modules</option>
                                    <option value="auth">Authentication</option>
                                    <option value="users">User Management</option>
                                    <option value="farms">Farm Management</option>
                                    <option value="livestock">Livestock Management</option>
                                    <option value="system">System</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filterAction">Action</label>
                                <select class="form-control" id="filterAction" name="action">
                                    <option value="">All Actions</option>
                                    <option value="create">Create</option>
                                    <option value="read">Read</option>
                                    <option value="update">Update</option>
                                    <option value="delete">Delete</option>
                                    <option value="login">Login</option>
                                    <option value="logout">Logout</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Required libraries for PDF/Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<!-- jsPDF and autoTable for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
// Initialize charts and DataTable when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    initializeDataTable();
    
    // Check for refresh notification flag after page loads
    if (sessionStorage.getItem('showRefreshNotification') === 'true') {
        // Clear the flag
        sessionStorage.removeItem('showRefreshNotification');
        // Show notification after a short delay to ensure page is fully loaded
        setTimeout(() => {
            showNotification('Data refreshed successfully!', 'success');
        }, 500);
    }
});

function initializeCharts() {
    // Load real data from server
    $.get('{{ route("superadmin.audit-logs.chart-data") }}', function(response) {
        if (response.success) {
            initializeTimelineChart(response.timeline);
            initializeSeverityChart(response.severity);
            
            // Update statistics with server-calculated data
            if (response.statistics) {
                updateStatisticsFromServer(response.statistics);
            }
            
            // Display debug information in console for data verification
            if (response.debug) {
                console.log('Chart Data Debug Info:', response.debug);
                console.log('Timeline Data:', response.timeline);
                console.log('Severity Data:', response.severity);
                console.log('Statistics:', response.statistics);
            }
        } else {
            console.error('Failed to load chart data:', response.message);
            initializeDefaultCharts();
        }
    }).fail(function() {
        console.error('Failed to fetch chart data');
        initializeDefaultCharts();
    });
}

function initializeTimelineChart(timelineData) {
    const timelineCtx = document.getElementById('activityTimelineChart').getContext('2d');
    const chart = new Chart(timelineCtx, {
        type: 'line',
        data: {
            labels: timelineData.labels,
            datasets: [{
                label: 'System Events (Last 24 Hours)',
                data: timelineData.data,
                borderColor: '#fca700',
                backgroundColor: 'rgba(252, 167, 0, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fca700',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#fca700',
                    borderWidth: 1,
                    cornerRadius: 6,
                    displayColors: false,
                    callbacks: {
                        title: function(context) {
                            return 'Hour: ' + context[0].label;
                        },
                        label: function(context) {
                            return 'Events: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Time (24 Hours)',
                        color: '#5a5c69',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        color: '#5a5c69',
                        maxTicksLimit: 12
                    }
                },
                y: {
                    beginAtZero: true,
                    display: true,
                    title: {
                        display: true,
                        text: 'Number of Events',
                        color: '#5a5c69',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        color: '#5a5c69',
                        stepSize: 1
                    }
                }
            }
        }
    });
    
    // Update timeline statistics
    updateTimelineStats(timelineData);
    
    return chart;
}

function initializeSeverityChart(severityData) {
    const severityCtx = document.getElementById('severityChart').getContext('2d');
    const chart = new Chart(severityCtx, {
        type: 'doughnut',
        data: {
            labels: severityData.labels,
            datasets: [{
                data: severityData.data,
                backgroundColor: severityData.colors,
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverBorderWidth: 3,
                hoverBorderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12,
                            weight: 'bold'
                        },
                        color: '#5a5c69'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#4e73df',
                    borderWidth: 1,
                    cornerRadius: 6,
                    displayColors: true,
                    callbacks: {
                        title: function(context) {
                            return 'Severity: ' + context[0].label;
                        },
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            elements: {
                arc: {
                    borderWidth: 2
                }
            }
        }
    });
    
    // Update severity statistics
    updateSeverityStats(severityData);
    
    return chart;
}

function updateTimelineStats(timelineData) {
    const totalEvents = timelineData.data.reduce((a, b) => a + b, 0);
    const maxIndex = timelineData.data.indexOf(Math.max(...timelineData.data));
    const peakHour = timelineData.labels[maxIndex];
    const avgPerHour = totalEvents > 0 ? (totalEvents / 24).toFixed(1) : 0;
    
    // Determine activity status
    let activityStatus = 'Normal';
    let statusClass = 'text-success';
    if (totalEvents > 100) {
        activityStatus = 'High';
        statusClass = 'text-warning';
    } else if (totalEvents > 200) {
        activityStatus = 'Critical';
        statusClass = 'text-danger';
    }
    
    document.getElementById('peakHour').textContent = peakHour;
    document.getElementById('totalEvents').textContent = totalEvents;
    document.getElementById('avgPerHour').textContent = avgPerHour;
    document.getElementById('activityStatus').textContent = activityStatus;
    document.getElementById('activityStatus').className = 'h6 mb-0 font-weight-bold ' + statusClass;
}

function updateSeverityStats(severityData) {
    // With our 3-category system: [Low, Medium, High]
    // High severity (index 2) includes both 'error' and 'critical' events
    const highSeverityCount = severityData.data[2] || 0; // High severity events
    const mediumSeverityCount = severityData.data[1] || 0; // Medium severity events
    const lowSeverityCount = severityData.data[0] || 0; // Low severity events
    const totalEvents = severityData.data.reduce((a, b) => a + b, 0);
    
    // Determine system health based on severity distribution
    let systemHealth = 'Healthy';
    let healthClass = 'text-success';
    
    if (highSeverityCount > 0) {
        systemHealth = 'Warning';
        healthClass = 'text-warning';
    }
    if (highSeverityCount > 5 || (highSeverityCount > 0 && mediumSeverityCount > 10)) {
        systemHealth = 'Critical';
        healthClass = 'text-danger';
    }
    
    // Update the critical count to show high severity events
    document.getElementById('criticalCount').textContent = highSeverityCount;
    document.getElementById('systemHealth').textContent = systemHealth;
    document.getElementById('systemHealth').className = 'h6 mb-0 font-weight-bold ' + healthClass;
}

function updateStatisticsFromServer(stats) {
    // Update timeline statistics
    document.getElementById('peakHour').textContent = stats.peak_hour || '--:--';
    document.getElementById('totalEvents').textContent = stats.total_events_24h || 0;
    document.getElementById('avgPerHour').textContent = stats.avg_per_hour || 0;
    
    // Determine activity status based on server data
    let activityStatus = 'Normal';
    let statusClass = 'text-success';
    if (stats.total_events_24h > 100) {
        activityStatus = 'High';
        statusClass = 'text-warning';
    } else if (stats.total_events_24h > 200) {
        activityStatus = 'Critical';
        statusClass = 'text-danger';
    }
    
    document.getElementById('activityStatus').textContent = activityStatus;
    document.getElementById('activityStatus').className = 'h6 mb-0 font-weight-bold ' + statusClass;
    
    // Update severity statistics
    document.getElementById('criticalCount').textContent = stats.high_severity_count || 0;
    
    // Determine system health based on server data
    let systemHealth = 'Healthy';
    let healthClass = 'text-success';
    
    if (stats.high_severity_count > 0) {
        systemHealth = 'Warning';
        healthClass = 'text-warning';
    }
    if (stats.high_severity_count > 5 || (stats.high_severity_count > 0 && stats.medium_severity_count > 10)) {
        systemHealth = 'Critical';
        healthClass = 'text-danger';
    }
    
    document.getElementById('systemHealth').textContent = systemHealth;
    document.getElementById('systemHealth').className = 'h6 mb-0 font-weight-bold ' + healthClass;
}

function refreshCharts() {
    // Show loading state
    const refreshButtons = document.querySelectorAll('[onclick="refreshCharts()"]');
    refreshButtons.forEach(btn => {
        const icon = btn.querySelector('i');
        icon.className = 'fas fa-spinner fa-spin';
        btn.disabled = true;
    });
    
    // Reload chart data
    $.get('{{ route("superadmin.audit-logs.chart-data") }}', function(response) {
        if (response.success) {
            // Destroy existing charts
            Chart.helpers.each(Chart.instances, function(chart) {
                chart.destroy();
            });
            
            // Reinitialize charts with new data
            initializeTimelineChart(response.timeline);
            initializeSeverityChart(response.severity);
            
            // Update statistics with server-calculated data
            if (response.statistics) {
                updateStatisticsFromServer(response.statistics);
            }
            
            // Show success notification
            showNotification('Charts refreshed successfully!', 'success');
        } else {
            console.error('Failed to refresh chart data:', response.message);
            showNotification('Failed to refresh chart data', 'danger');
        }
    }).fail(function() {
        console.error('Failed to refresh chart data');
        showNotification('Failed to refresh chart data', 'danger');
    }).always(function() {
        // Restore button state
        refreshButtons.forEach(btn => {
            const icon = btn.querySelector('i');
            icon.className = 'fas fa-sync-alt';
            btn.disabled = false;
        });
    });
}

function initializeDefaultCharts() {
    // Fallback charts with default data
    const timelineCtx = document.getElementById('activityTimelineChart').getContext('2d');
    new Chart(timelineCtx, {
        type: 'line',
        data: {
            labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '24:00'],
            datasets: [{
                label: 'System Events',
                data: [0, 0, 0, 0, 0, 0, 0],
                borderColor: '#fca700',
                backgroundColor: 'rgba(252, 167, 0, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const severityCtx = document.getElementById('severityChart').getContext('2d');
    new Chart(severityCtx, {
        type: 'doughnut',
        data: {
            labels: ['Info', 'Warning', 'Critical/Error'],
            datasets: [{
                data: [0, 0, 0],
                backgroundColor: ['#387057', '#fca700', '#c82333']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

function initializeDataTable() {
    console.log('Initializing DataTables...');
    
    $('#auditDataTable').DataTable({
        responsive: true,
        ordering: true,
        paging: false, // Disable pagination
        order: [[1, 'desc']], // Sort by timestamp
        searching: false, // Disable built-in search to remove duplicate
        info: false, // Hide info text
        columnDefs: [
            { width: '80px', targets: 0 }, // Log ID
            { width: '150px', targets: 1 }, // Timestamp
            { width: '120px', targets: 2 }, // User
            { width: '150px', targets: 3 }, // Action
            { width: '120px', targets: 4 }, // Module
            { width: '200px', targets: 5 }, // Details
            { width: '120px', targets: 6 }, // IP Address
            { width: '100px', targets: 7 }  // Severity
        ]
    });

    // Initialize Security Alerts DataTable
    console.log('Initializing Security Alerts DataTable...');
    const securityTable = $('#securityAlertsTable');
    if (securityTable.length > 0) {
        try {
            securityTable.DataTable({
                responsive: true,
                ordering: true,
                paging: false,
                searching: false,
                info: false,
                order: [[0, 'desc']], // Sort by timestamp by default
                columnDefs: [
                    { width: '150px', targets: 0 }, // Timestamp
                    { width: '120px', targets: 1 }, // User
                    { width: '150px', targets: 2 }, // Event
                    { width: '100px', targets: 3 }, // Severity
                    { width: '200px', targets: 4 }, // Details
                    { width: '150px', targets: 5, orderable: false }  // Actions - not sortable
                ],
        language: {
                    emptyTable: '<div class="empty-state"><i class="fas fa-check-circle"></i><h5>No security alerts</h5><p>No security alerts at this time</p></div>'
                }
            });
            console.log('Security Alerts DataTable initialized successfully');
        } catch (error) {
            console.error('Error initializing Security Alerts DataTable:', error);
        }
    } else {
        console.log('Security Alerts table not found');
    }

    // Initialize User Activity DataTable
    console.log('Initializing User Activity DataTable...');
    const userActivityTable = $('#userActivityTable');
    if (userActivityTable.length > 0) {
        try {
            userActivityTable.DataTable({
                responsive: true,
                ordering: true,
                paging: false,
                searching: false,
                info: false,
                order: [[1, 'desc']], // Sort by last activity by default
                columnDefs: [
                    { width: '200px', targets: 0 }, // User
                    { width: '150px', targets: 1 }, // Last Activity
                    { width: '120px', targets: 2 }, // Total Actions
                    { width: '120px', targets: 3 }, // Critical Events
                    { width: '100px', targets: 4 }  // Status
                ],
                language: {
                    emptyTable: '<div class="empty-state"><i class="fas fa-info-circle"></i><h5>No user activity</h5><p>No user activity data available</p></div>'
                }
            });
            console.log('User Activity DataTable initialized successfully');
        } catch (error) {
            console.error('Error initializing User Activity DataTable:', error);
        }
    } else {
        console.log('User Activity table not found');
    }
    
    console.log('DataTables initialization complete');

    // Custom search functionality
    $('#logSearch').on('keyup', function() {
        $('#auditDataTable').DataTable().search(this.value).draw();
    });

    // Security alerts search functionality
    $('#securitySearch').on('keyup', function() {
        $('#securityAlertsTable').DataTable().search(this.value).draw();
    });

    // User activity search functionality
    $('#userActivitySearch').on('keyup', function() {
        $('#userActivityTable').DataTable().search(this.value).draw();
    });

}

function openLogDetails(logId) {
    console.log('openLogDetails called with logId:', logId);
    
    // Try to find the log data directly from the DOM first
    const logLink = document.querySelector(`a[data-log-id="${logId}"]`);
    if (!logLink) {
        console.log('Log link not found in DOM');
        alert('Log entry not found');
        return;
    }
    
    // Find the table row containing this link
    const tableRow = logLink.closest('tr');
    if (!tableRow) {
        console.log('Table row not found');
        alert('Log entry not found');
        return;
    }
    
    // Extract data from the table row
    const cells = tableRow.querySelectorAll('td');
    if (cells.length < 8) {
        console.log('Not enough cells in row:', cells.length);
        alert('Log entry data incomplete');
        return;
    }
    
    // Extract clean text from each cell
    const cleanData = Array.from(cells).map(cell => {
        return cell.textContent || cell.innerText || '';
    });
    
    console.log('Extracted data:', cleanData);
    
    // Create compact content similar to other modals
    const logDetailsHtml = `
        <div class="table-responsive">
            <table class="table table-sm table-borderless">
                <tbody>
                    <tr>
                        <td class="font-weight-bold text-muted" style="width: 30%;">Log ID:</td>
                        <td>${cleanData[0]}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold text-muted">Timestamp:</td>
                        <td>${cleanData[1]}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold text-muted">User:</td>
                        <td>${cleanData[2]}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold text-muted">Action:</td>
                        <td>${cleanData[3]}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold text-muted">Severity:</td>
                        <td><span class="badge badge-${getSeverityBadgeClass(cleanData[7])}">${cleanData[7]}</span></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold text-muted">IP Address:</td>
                        <td>${cleanData[6]}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold text-muted">Details:</td>
                        <td>${cleanData[5] || 'No additional details available'}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    `;
    
    // Set the content and show modal
    $('#logDetailsContent').html(logDetailsHtml);
    $('#logDetailsModal').data('log-id', logId);
    $('#logDetailsModal').modal('show');
}

function getSeverityBadgeClass(severity) {
    switch(severity.toLowerCase()) {
        case 'critical': return 'danger';
        case 'warning': return 'warning';
        case 'error': return 'danger';
        case 'info': return 'info';
        default: return 'secondary';
    }
}

function viewLogDetails(logId) {
    openLogDetails(logId);
}

function investigateLog(logId) {
    // Open investigation view
    window.open(`{{ route("superadmin.audit-logs.details", ":id") }}`.replace(':id', logId), '_blank');
}

function investigateFromModal() {
    // Get log ID from modal and investigate
    const logId = $('#logDetailsModal').data('log-id');
    investigateLog(logId);
}

function flagLog(logId) {
    if (confirm('Flag this log entry for further investigation?')) {
        $.post(`{{ route("superadmin.audit-logs.details", ":id") }}`.replace(':id', logId), {
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.success) {
                alert('Log entry flagged successfully');
            } else {
                alert('Error flagging log entry');
            }
        });
    }
}

function investigateAlert(alertId) {
    // Open investigation view for security alert
    window.open(`/superadmin/security-alerts/${alertId}/investigate`, '_blank');
}

function exportLogs() {
    window.location.href = '{{ route("superadmin.audit-logs.export") }}';
}

function generateReport() {
    window.open('{{ route("superadmin.audit-logs.export") }}', '_blank');
}

function openFilterModal() {
    $('#filterModal').modal('show');
}

function clearOldLogs() {
    if (!confirm('Are you absolutely sure you want to CLEAR ALL audit logs? This cannot be undone.')) return;

    const $btn = $('.btn-warning[onclick="clearOldLogs()"]');
    const originalHtml = $btn.html();
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Clearing...');

    $.post('{{ route("superadmin.audit-logs.clear") }}', {
        _token: '{{ csrf_token() }}',
        days: 0
    }, function(response) {
        if (response.success) {
            alert(response.message || 'Logs cleared successfully');
            location.reload();
        } else {
            alert(response.message || 'Error clearing logs');
            $btn.prop('disabled', false).html(originalHtml);
        }
    }).fail(function(xhr) {
        const msg = (xhr && xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Request failed while clearing logs';
        alert(msg);
        $btn.prop('disabled', false).html(originalHtml);
    });
}

// Form submission
$('#filterForm').on('submit', function(e) {
    e.preventDefault();
    
    // Apply filter and reload table
    const formData = $(this).serialize();
    $.get('{{ route("superadmin.audit-logs.export") }}?' + formData, function(response) {
        if (response.success) {
            $('#filterModal').modal('hide');
            location.reload();
        } else {
            alert('Error applying filter');
        }
    });
});

function exportCSV() {
    // Export table data to CSV
    const table = $('#auditDataTable').DataTable();
    const data = table.data().toArray();
    // Implementation for CSV export
}

function exportPNG() {
    // Export table as PNG
    window.print();
}

function exportPDF() {
    // Export as PDF
    window.print();
}

function printTable() {
    window.print();
}

function exportChart(chartType) {
    const canvas = document.getElementById(chartType + 'Chart');
    const link = document.createElement('a');
    link.download = `${chartType}-chart.png`;
    link.href = canvas.toDataURL();
    link.click();
}

function printChart(chartType) {
    const canvas = document.getElementById(chartType + 'Chart');
    const win = window.open();
    win.document.write('<html><head><title>Chart</title></head><body>');
    win.document.write('<img src="' + canvas.toDataURL() + '"/>');
    win.document.write('</body></html>');
    win.document.close();
    win.print();
}

// Security Alerts Functions
function refreshSecurityAlerts() {
    // Show loading indicator - target the specific security alerts refresh button
    const refreshBtn = document.querySelector('[onclick="refreshSecurityAlerts()"]');
    if (refreshBtn) {
        const originalText = refreshBtn.innerHTML;
        refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
        refreshBtn.disabled = true;
    }
    
    // Since the table is server-side rendered (not AJAX), we'll reload the page
    // Store a flag to show notification after reload
    sessionStorage.setItem('showRefreshNotification', 'true');
    
    // Reload the page after a short delay
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function printSecurityTable() {
    try {
        // Get current table data without actions column
        const table = $('#securityAlertsTable').DataTable();
        const tableData = table.data().toArray();
        
        if (!tableData || tableData.length === 0) {
            showNotification('No data available to print', 'warning');
            return;
        }
        
        // Create print content directly in current page
        const originalContent = document.body.innerHTML;
        
        let printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #18375d; margin-bottom: 5px;">Security Alerts & Critical Events Report</h1>
                    <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                
                <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                    <thead>
                        <tr style="background-color: #f8f9fa;">
                            <th style="border: 3px solid #000; padding: 15px; text-align: left; font-weight: bold; color: #18375d;">Timestamp</th>
                            <th style="border: 3px solid #000; padding: 15px; text-align: left; font-weight: bold; color: #18375d;">User</th>
                            <th style="border: 3px solid #000; padding: 15px; text-align: left; font-weight: bold; color: #18375d;">Event</th>
                            <th style="border: 3px solid #000; padding: 15px; text-align: left; font-weight: bold; color: #18375d;">Severity</th>
                            <th style="border: 3px solid #000; padding: 15px; text-align: left; font-weight: bold; color: #18375d;">Details</th>
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
    }
}

// User Activity Functions
function refreshUserActivity() {
    // Show loading indicator - target the specific user activity refresh button
    const refreshBtn = document.querySelector('[onclick="refreshUserActivity()"]');
    if (refreshBtn) {
        const originalText = refreshBtn.innerHTML;
        refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
        refreshBtn.disabled = true;
    }
    
    // Since the table is server-side rendered (not AJAX), we'll reload the page
    // Store a flag to show notification after reload
    sessionStorage.setItem('showRefreshNotification', 'true');
    
    // Reload the page after a short delay
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function printUserActivityTable() {
    try {
        // Get current table data (all columns - no Actions column in this table)
        const table = $('#userActivityTable').DataTable();
        const tableData = table.data().toArray();
        
        if (!tableData || tableData.length === 0) {
            showNotification('No data available to print', 'warning');
            return;
        }
        
        // Create print content directly in current page
        const originalContent = document.body.innerHTML;
        
        let printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #18375d; margin-bottom: 5px;">User Activity Summary Report</h1>
                    <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                
                <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                    <thead>
                        <tr style="background-color: #f8f9fa;">
                            <th style="border: 3px solid #000; padding: 15px; text-align: left; font-weight: bold; color: #18375d;">User</th>
                            <th style="border: 3px solid #000; padding: 15px; text-align: left; font-weight: bold; color: #18375d;">Last Activity</th>
                            <th style="border: 3px solid #000; padding: 15px; text-align: left; font-weight: bold; color: #18375d;">Total Actions</th>
                            <th style="border: 3px solid #000; padding: 15px; text-align: left; font-weight: bold; color: #18375d;">Critical Events</th>
                            <th style="border: 3px solid #000; padding: 15px; text-align: left; font-weight: bold; color: #18375d;">Status</th>
                        </tr>
                    </thead>
                    <tbody>`;
        
        // Add data rows (all columns - no Actions column in this table)
        tableData.forEach(row => {
            printContent += '<tr>';
            for (let i = 0; i < row.length; i++) { // Include all columns
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
    }
}

// Security Alerts Export Functions
function exportSecurityCSV() {
    try {
        const table = $('#securityAlertsTable').DataTable();
        const data = table.data().toArray();
        
        if (!data || data.length === 0) {
            showNotification('No data available to export', 'warning');
            return;
        }
        
        // Create CSV content
        let csvContent = 'Timestamp,User,Event,Severity,Details\n';
        data.forEach(row => {
            const csvRow = row.slice(0, -1).map(cell => {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = cell;
                return '"' + (tempDiv.textContent || tempDiv.innerText || '').replace(/"/g, '""') + '"';
            }).join(',');
            csvContent += csvRow + '\n';
        });
        
        // Download CSV
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'security-alerts-' + new Date().toISOString().split('T')[0] + '.csv';
        a.click();
        window.URL.revokeObjectURL(url);
        
        showNotification('Security alerts CSV exported successfully', 'success');
    } catch (error) {
        console.error('Error exporting CSV:', error);
        showNotification('Error exporting CSV. Please try again.', 'danger');
    }
}

function exportSecurityPNG() {
    try {
        const table = $('#securityAlertsTable')[0];
        
        // Create a temporary table with better styling for export
        const tempTable = table.cloneNode(true);
        tempTable.style.position = 'absolute';
        tempTable.style.left = '-9999px';
        tempTable.style.top = '-9999px';
        tempTable.style.backgroundColor = '#ffffff';
        tempTable.style.border = '1px solid #000';
        document.body.appendChild(tempTable);
        
        // Generate PNG using html2canvas
        html2canvas(tempTable, {
            scale: 2, // Higher quality
            backgroundColor: '#ffffff',
            width: tempTable.offsetWidth,
            height: tempTable.offsetHeight
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'security-alerts-' + new Date().toISOString().split('T')[0] + '.png';
            link.href = canvas.toDataURL();
            link.click();
            
            // Clean up
            document.body.removeChild(tempTable);
            showNotification('Security alerts PNG exported successfully', 'success');
        }).catch(error => {
            document.body.removeChild(tempTable);
            throw error;
        });
        
    } catch (error) {
        console.error('Error exporting PNG:', error);
        showNotification('Error exporting PNG. Please try again.', 'danger');
    }
}

function exportSecurityPDF() {
    try {
        // Check if jsPDF is available
        if (typeof window.jspdf === 'undefined') {
            console.warn('jsPDF not available, falling back to html2canvas PDF export');
            // Fallback to html2canvas PDF export
            const table = $('#securityAlertsTable')[0];
            html2canvas(table).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF();
                const imgWidth = 210;
                const pageHeight = 295;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                let heightLeft = imgHeight;
                
                let position = 0;
                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
                
                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }
                
                pdf.save('security-alerts-' + new Date().toISOString().split('T')[0] + '.pdf');
                showNotification('Security alerts PDF exported successfully', 'success');
            });
            return;
        }
        
        const table = $('#securityAlertsTable').DataTable();
        const data = table.data().toArray();
        
        if (!data || data.length === 0) {
            showNotification('No data available to export', 'warning');
            return;
        }
        
        // Prepare data for PDF
        const pdfData = [];
        data.forEach(row => {
            const rowData = [];
            for (let i = 0; i < row.length - 1; i++) { // Skip Actions column
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
        
        // Create PDF using jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape', 'mm', 'a4');
        
        // Set title
        doc.setFontSize(18);
        doc.text('Security Alerts & Critical Events Report', 14, 22);
        doc.setFontSize(12);
        doc.text('Generated on: ' + new Date().toLocaleDateString(), 14, 30);
        
        // Add table using autoTable
        doc.autoTable({
            head: [['Timestamp', 'User', 'Event', 'Severity', 'Details']],
            body: pdfData,
            startY: 40,
            styles: {
                fontSize: 8,
                cellPadding: 3
            },
            headStyles: {
                fillColor: [248, 249, 250],
                textColor: [73, 80, 87],
                fontStyle: 'bold'
            },
            alternateRowStyles: {
                fillColor: [248, 249, 250]
            }
        });
        
        doc.save('security-alerts-' + new Date().toISOString().split('T')[0] + '.pdf');
        showNotification('Security alerts PDF exported successfully', 'success');
        
    } catch (error) {
        console.error('Error exporting PDF:', error);
        showNotification('Error exporting PDF. Please try again.', 'danger');
    }
}

// User Activity Export Functions
function exportUserActivityCSV() {
    try {
        const table = $('#userActivityTable').DataTable();
        const data = table.data().toArray();
        
        if (!data || data.length === 0) {
            showNotification('No data available to export', 'warning');
            return;
        }
        
        // Create CSV content
        let csvContent = 'User,Last Activity,Total Actions,Critical Events,Status\n';
        data.forEach(row => {
            const csvRow = row.map(cell => {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = cell;
                return '"' + (tempDiv.textContent || tempDiv.innerText || '').replace(/"/g, '""') + '"';
            }).join(',');
            csvContent += csvRow + '\n';
        });
        
        // Download CSV
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'user-activity-' + new Date().toISOString().split('T')[0] + '.csv';
        a.click();
        window.URL.revokeObjectURL(url);
        
        showNotification('User activity CSV exported successfully', 'success');
    } catch (error) {
        console.error('Error exporting CSV:', error);
        showNotification('Error exporting CSV. Please try again.', 'danger');
    }
}

function exportUserActivityPNG() {
    try {
        const table = $('#userActivityTable')[0];
        
        // Create a temporary table with better styling for export
        const tempTable = table.cloneNode(true);
        tempTable.style.position = 'absolute';
        tempTable.style.left = '-9999px';
        tempTable.style.top = '-9999px';
        tempTable.style.backgroundColor = '#ffffff';
        tempTable.style.border = '1px solid #000';
        document.body.appendChild(tempTable);
        
        // Generate PNG using html2canvas
        html2canvas(tempTable, {
            scale: 2, // Higher quality
            backgroundColor: '#ffffff',
            width: tempTable.offsetWidth,
            height: tempTable.offsetHeight
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'user-activity-' + new Date().toISOString().split('T')[0] + '.png';
            link.href = canvas.toDataURL();
            link.click();
            
            // Clean up
            document.body.removeChild(tempTable);
            showNotification('User activity PNG exported successfully', 'success');
        }).catch(error => {
            document.body.removeChild(tempTable);
            throw error;
        });
        
    } catch (error) {
        console.error('Error exporting PNG:', error);
        showNotification('Error exporting PNG. Please try again.', 'danger');
    }
}

function exportUserActivityPDF() {
    try {
        // Check if jsPDF is available
        if (typeof window.jspdf === 'undefined') {
            console.warn('jsPDF not available, falling back to html2canvas PDF export');
            // Fallback to html2canvas PDF export
            const table = $('#userActivityTable')[0];
            html2canvas(table).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF();
                const imgWidth = 210;
                const pageHeight = 295;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                let heightLeft = imgHeight;
                
                let position = 0;
                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
                
                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }
                
                pdf.save('user-activity-' + new Date().toISOString().split('T')[0] + '.pdf');
                showNotification('User activity PDF exported successfully', 'success');
            });
            return;
        }
        
        const table = $('#userActivityTable').DataTable();
        const data = table.data().toArray();
        
        if (!data || data.length === 0) {
            showNotification('No data available to export', 'warning');
            return;
        }
        
        // Prepare data for PDF
        const pdfData = [];
        data.forEach(row => {
            const rowData = [];
            for (let i = 0; i < row.length; i++) { // Include all columns
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
        
        // Create PDF using jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape', 'mm', 'a4');
        
        // Set title
        doc.setFontSize(18);
        doc.text('User Activity Summary Report', 14, 22);
        doc.setFontSize(12);
        doc.text('Generated on: ' + new Date().toLocaleDateString(), 14, 30);
        
        // Add table using autoTable
        doc.autoTable({
            head: [['User', 'Last Activity', 'Total Actions', 'Critical Events', 'Status']],
            body: pdfData,
            startY: 40,
            styles: {
                fontSize: 8,
                cellPadding: 3
            },
            headStyles: {
                fillColor: [248, 249, 250],
                textColor: [73, 80, 87],
                fontStyle: 'bold'
            },
            alternateRowStyles: {
                fillColor: [248, 249, 250]
            }
        });
        
        doc.save('user-activity-' + new Date().toISOString().split('T')[0] + '.pdf');
        showNotification('User activity PDF exported successfully', 'success');
        
    } catch (error) {
        console.error('Error exporting PDF:', error);
        showNotification('Error exporting PDF. Please try again.', 'danger');
    }
}

// System Activity Logs Functions
function refreshSystemLogs() {
    // Show loading indicator - target the specific system logs refresh button
    const refreshBtn = document.querySelector('[onclick="refreshSystemLogs()"]');
    if (refreshBtn) {
        const originalText = refreshBtn.innerHTML;
        refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
        refreshBtn.disabled = true;
    }
    
    // Since the table is server-side rendered (not AJAX), we'll reload the page
    // Store a flag to show notification after reload
    sessionStorage.setItem('showRefreshNotification', 'true');
    
    // Reload the page after a short delay
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function printSystemLogsTable() {
    try {
        // Get current table data (all columns - no Actions column in this table)
        const table = $('#auditDataTable').DataTable();
        const tableData = table.data().toArray();
        
        if (!tableData || tableData.length === 0) {
            showNotification('No data available to print', 'warning');
            return;
        }
        
        // Create print content directly in current page
        const originalContent = document.body.innerHTML;
        
        let printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #18375d; margin-bottom: 5px;">System Activity Logs Report</h1>
                    <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                
                <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                    <thead>
                        <tr style="background-color: #f8f9fa;">
                            <th style="border: 3px solid #000; padding: 15px; text-align: left; font-weight: bold; color: #18375d;">Log ID</th>
                            <th style="border: 3px solid #000; padding: 15px; text-align: left; font-weight: bold; color: #18375d;">Timestamp</th>
                            <th style="border: 3px solid #000; padding: 15px; text-align: left; font-weight: bold; color: #18375d;">User</th>
                            <th style="border: 3px solid #000; padding: 15px; text-align: left; font-weight: bold; color: #18375d;">Action</th>
                            <th style="border: 3px solid #000; padding: 15px; text-align: left; font-weight: bold; color: #18375d;">Module</th>
                            <th style="border: 3px solid #000; padding: 15px; text-align: left; font-weight: bold; color: #18375d;">Details</th>
                            <th style="border: 3px solid #000; padding: 15px; text-align: left; font-weight: bold; color: #18375d;">IP Address</th>
                            <th style="border: 3px solid #000; padding: 15px; text-align: left; font-weight: bold; color: #18375d;">Severity</th>
                        </tr>
                    </thead>
                    <tbody>`;
        
        // Add data rows (excluding Actions column)
        tableData.forEach(row => {
            printContent += '<tr>';
            for (let i = 0; i < row.length - 1; i++) { // Skip Actions column
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
    }
}

// System Activity Logs Export Functions
function exportSystemLogsCSV() {
    try {
        const table = $('#auditDataTable').DataTable();
        const data = table.data().toArray();
        
        if (!data || data.length === 0) {
            showNotification('No data available to export', 'warning');
            return;
        }
        
        // Create CSV content
        let csvContent = 'Log ID,Timestamp,User,Action,Module,Details,IP Address,Severity\n';
        data.forEach(row => {
            const csvRow = row.slice(0, -1).map(cell => {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = cell;
                return '"' + (tempDiv.textContent || tempDiv.innerText || '').replace(/"/g, '""') + '"';
            }).join(',');
            csvContent += csvRow + '\n';
        });
        
        // Download CSV
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'system-activity-logs-' + new Date().toISOString().split('T')[0] + '.csv';
        a.click();
        window.URL.revokeObjectURL(url);
        
        showNotification('System activity logs CSV exported successfully', 'success');
    } catch (error) {
        console.error('Error exporting CSV:', error);
        showNotification('Error exporting CSV. Please try again.', 'danger');
    }
}

function exportSystemLogsPNG() {
    try {
        const table = $('#auditDataTable')[0];
        
        // Create a temporary table with better styling for export
        const tempTable = table.cloneNode(true);
        tempTable.style.position = 'absolute';
        tempTable.style.left = '-9999px';
        tempTable.style.top = '-9999px';
        tempTable.style.backgroundColor = '#ffffff';
        tempTable.style.border = '1px solid #000';
        document.body.appendChild(tempTable);
        
        // Generate PNG using html2canvas
        html2canvas(tempTable, {
            scale: 2, // Higher quality
            backgroundColor: '#ffffff',
            width: tempTable.offsetWidth,
            height: tempTable.offsetHeight
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'system-activity-logs-' + new Date().toISOString().split('T')[0] + '.png';
            link.href = canvas.toDataURL();
            link.click();
            
            // Clean up
            document.body.removeChild(tempTable);
            showNotification('System activity logs PNG exported successfully', 'success');
        }).catch(error => {
            document.body.removeChild(tempTable);
            throw error;
        });
        
    } catch (error) {
        console.error('Error exporting PNG:', error);
        showNotification('Error exporting PNG. Please try again.', 'danger');
    }
}

function exportSystemLogsPDF() {
    try {
        // Check if jsPDF is available
        if (typeof window.jspdf === 'undefined') {
            console.warn('jsPDF not available, falling back to html2canvas PDF export');
            // Fallback to html2canvas PDF export
            const table = $('#auditDataTable')[0];
            html2canvas(table).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF();
                const imgWidth = 210;
                const pageHeight = 295;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                let heightLeft = imgHeight;
                
                let position = 0;
                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
                
                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }
                
                pdf.save('system-activity-logs-' + new Date().toISOString().split('T')[0] + '.pdf');
                showNotification('System activity logs PDF exported successfully', 'success');
            });
            return;
        }
        
        const table = $('#auditDataTable').DataTable();
        const data = table.data().toArray();
        
        if (!data || data.length === 0) {
            showNotification('No data available to export', 'warning');
            return;
        }
        
        // Prepare data for PDF
        const pdfData = [];
        data.forEach(row => {
            const rowData = [];
            for (let i = 0; i < row.length - 1; i++) { // Skip Actions column
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
        
        // Create PDF using jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape', 'mm', 'a4');
        
        // Set title
        doc.setFontSize(18);
        doc.text('System Activity Logs Report', 14, 22);
        doc.setFontSize(12);
        doc.text('Generated on: ' + new Date().toLocaleDateString(), 14, 30);
        
        // Add table using autoTable
        doc.autoTable({
            head: [['Log ID', 'Timestamp', 'User', 'Action', 'Module', 'Details', 'IP Address', 'Severity']],
            body: pdfData,
            startY: 40,
            styles: {
                fontSize: 8,
                cellPadding: 3
            },
            headStyles: {
                fillColor: [248, 249, 250],
                textColor: [73, 80, 87],
                fontStyle: 'bold'
            },
            alternateRowStyles: {
                fillColor: [248, 249, 250]
            }
        });
        
        doc.save('system-activity-logs-' + new Date().toISOString().split('T')[0] + '.pdf');
        showNotification('System activity logs PDF exported successfully', 'success');
        
    } catch (error) {
        console.error('Error exporting PDF:', error);
        showNotification('Error exporting PDF. Please try again.', 'danger');
    }
}

// Notification function (matching other super admin pages)
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
