<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="LBDAIRY - Modern Dairy Management System">
    <meta name="author" content="LBDAIRY">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#18375d">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="LB Dairy">

    <title>@yield('title', 'LBDAIRY')</title>
    <link rel="preload" as="image" href="{{ asset('img/LBDairy.png') }}">

    <!-- Custom fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/unified-styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/button-consistency.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tooltip-override.css') }}" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #18375d;
            --primary-dark: #122a47;
            --success-color: #1cc88a;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --info-color: #36b9cc;
            --light-color: #fff;
            --dark-color: #5a5c69;
            --gray: #858796;
            --border-color: #e3e6f0;
            --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #fff;
        }

        /* Enhanced Card Styling */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            overflow: hidden;
            background-color: #fff;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        /* Override all card headers with maximum specificity */
        html body .card-header,
        html body .card .card-header,
        html body .card > .card-header,
        .card-header,
        .card .card-header,
        .card > .card-header {
            background: #18375d !important;
            background-color: #18375d !important;
            border-bottom: none !important;
            padding: 1.5rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            color: white !important;
        }
        
        /* Exclude stat cards and dashboard cards from card header styling */
        html body .card.stat-card .card-header,
        html body .card.dashboard-card .card-header,
        .card.stat-card .card-header,
        .card.dashboard-card .card-header {
            display: none !important;
            background: transparent !important;
            background-color: transparent !important;
        }
        
        /* Completely override any card styling for stat cards */
        html body .card.stat-card,
        html body .card.dashboard-card,
        .card.stat-card,
        .card.dashboard-card {
            background: #fff !important;
        }

        /* Ensure sidebar icons are visible */
        .sidebar .nav-item .nav-link i {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .sidebar .nav-item .nav-link:hover i,
        .sidebar .nav-item.active .nav-link i {
            color: white !important;
        }
        
        html body .card.stat-card .card-body,
        html body .card.dashboard-card .card-body,
        .card.stat-card .card-body,
        .card.dashboard-card .card-body {
            background: #fff !important;
            background-color: #fff !important;
            color: inherit !important;
        }
        
        /* Force text colors in stat cards */
        html body .card.stat-card .text-primary,
        html body .card.dashboard-card .text-primary,
        .card.stat-card .text-primary,
        .card.dashboard-card .text-primary {
            color: #18375d !important;
        }
        
        /* Green tooltips when sidebar is collapsed */
        body.sidebar-toggled .tooltip-inner,
        .sidebar.toggled .tooltip-inner {
            background-color: #387057 !important;
            color: #fff !important;
            opacity: 1 !important;
        }
        
        /* Hide tooltips when sidebar is expanded */
        body:not(.sidebar-toggled) .tooltip,
        .sidebar:not(.toggled) .tooltip {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }
        
        /* Make tooltip completely solid */
        .tooltip.show {
            opacity: 1 !important;
        }
        
        .tooltip {
            opacity: 1 !important;
        }
        
        /* Green arrows when sidebar is collapsed */
        body.sidebar-toggled .tooltip .arrow::before,
        .sidebar.toggled .tooltip .arrow::before {
            border-top-color: #387057 !important;
            border-bottom-color: #387057 !important;
            border-left-color: #387057 !important;
            border-right-color: #387057 !important;
        }
        
        /* Hide tooltip arrows when sidebar is expanded */
        body:not(.sidebar-toggled) .tooltip .arrow,
        .sidebar:not(.toggled) .tooltip .arrow {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }
        
        /* Override any dynamically created tooltips */
        .tooltip-inner {
            background-color: #387057 !important;
            color: #fff !important;
        }
        
        html body .card.stat-card .text-success,
        html body .card.dashboard-card .text-success,
        .card.stat-card .text-success,
        .card.dashboard-card .text-success {
            color: #1cc88a !important;
        }
        
        html body .card.stat-card .text-info,
        html body .card.dashboard-card .text-info,
        .card.stat-card .text-info,
        .card.dashboard-card .text-info {
            color: #36b9cc !important;
        }
        
        html body .card.stat-card .text-warning,
        html body .card.dashboard-card .text-warning,
        .card.stat-card .text-warning,
        .card.dashboard-card .text-warning {
            color: #f6c23e !important;
        }
        
        html body .card.stat-card .text-danger,
        html body .card.dashboard-card .text-danger,
        .card.stat-card .text-danger,
        .card.dashboard-card .text-danger {
            color: #e74a3b !important;
        }
        
        html body .card.stat-card .text-secondary,
        html body .card.dashboard-card .text-secondary,
        .card.stat-card .text-secondary,
        .card.dashboard-card .text-secondary {
            color: #858796 !important;
        }
        
        /* Ensure stat cards and dashboard cards don't have card headers */
        .stat-card .card-header,
        .stat-card > .card-header,
        .dashboard-card .card-header,
        .dashboard-card > .card-header,
        .card.stat-card .card-header,
        .card.dashboard-card .card-header {
            display: none !important;
        }
        
        /* Override any card header styling for stat and dashboard cards */
        .card.stat-card,
        .card.dashboard-card,
        .stat-card,
        .dashboard-card {
            background: #fff !important;
        }
        
        /* Ensure no blue backgrounds on stat/dashboard cards */
        .card.stat-card::before,
        .card.dashboard-card::before,
        .stat-card::before,
        .dashboard-card::before {
            display: none !important;
        }

        .card-header h6 {
            color: white !important;
            font-weight: 600;
            font-size: 1.1rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Ensure stat card text is not affected by card header text styling */
        .stat-card h6,
        .dashboard-card h6,
        .stat-card .card-header h6,
        .dashboard-card .card-header h6 {
            color: inherit !important;
        }

        .card-header h6::before {
            content: '';
            width: 4px;
            height: 20px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        /* Override Bootstrap primary colors */
        .bg-primary,
        .btn-primary,
        .text-primary {
            background-color: #18375d !important;
            color: #18375d !important;
        }

        .btn-primary {
            background-color: #18375d !important;
            border-color: #18375d !important;
        }

        .btn-primary:hover {
            background-color: #122a47 !important;
            border-color: #122a47 !important;
        }

        .btn-user {
            background: #18375d;
            border-radius: 15px;
            padding: 1.25rem 1.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: none;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-user:hover {
            background-color: #fca700;
            color: #ffffffff;
            border-color: #fca700;
            transform: translateY(-2px);
            transform: translateY(-3px);
            box-shadow: 0 1rem 2rem rgba(24, 55, 93, 0.4);
        }

        .border-primary {
            border-color: #18375d !important;
        }

        /* Sidebar Brand Styling */
        .sidebar-brand {
            background-color: #f4f2e6 !important;
            border-bottom: 1px solid rgba(24, 55, 93, 0.1) !important;
        }

        .sidebar-brand-text {
            color: #18375d !important;
            font-weight: bold !important;
            font-size: 1.1rem !important;
        }

        .sidebar-brand:hover {
            background-color: #f4f2e6 !important;
            text-decoration: none !important;
        }

        /* Enhanced Button Styling */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
            border: none;
            font-size: 0.85rem;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }

        /* Custom Dashboard Styles */
        .stat-card {
            background: #fff !important;
            border-radius: 12px !important;
            padding: 1.5rem !important;
            box-shadow: var(--shadow) !important;
            transition: all 0.3s ease !important;
            border-left: 4px solid var(--primary-color) !important;
            height: 100% !important;
        }
        
        /* Dashboard card specific styling */
        .dashboard-card {
            background: #fff !important;
            border-radius: 12px !important;
            box-shadow: var(--shadow) !important;
            transition: all 0.3s ease !important;
            height: 100% !important;
        }
        
        .dashboard-card:hover {
            box-shadow: var(--shadow-lg) !important;
            transform: translateY(-2px) !important;
        }
        
        /* Fix text colors in stat cards and dashboard cards */
        .stat-card .text-primary,
        .dashboard-card .text-primary {
            color: #18375d !important;
        }
        
        .stat-card .text-success,
        .dashboard-card .text-success {
            color: #1cc88a !important;
        }
        
        .stat-card .text-info,
        .dashboard-card .text-info {
            color: #36b9cc !important;
        }
        
        .stat-card .text-warning,
        .dashboard-card .text-warning {
            color: #f6c23e !important;
        }
        
        .stat-card .text-danger,
        .dashboard-card .text-danger {
            color: #e74a3b !important;
        }
        
        .stat-card .text-secondary,
        .dashboard-card .text-secondary {
            color: #858796 !important;
        }
        
        /* Ensure stat card text is not affected by card header styling */
        .stat-card .text-xs,
        .dashboard-card .text-xs,
        .stat-card .font-weight-bold,
        .dashboard-card .font-weight-bold,
        .stat-card .text-uppercase,
        .dashboard-card .text-uppercase {
            color: inherit !important;
        }
        
        /* Fix icon colors in stat cards */
        .stat-card .icon.text-primary,
        .dashboard-card .icon.text-primary {
            color: #18375d !important;
        }
        
        .stat-card .icon.text-success,
        .dashboard-card .icon.text-success {
            color: #1cc88a !important;
        }
        
        .stat-card .icon.text-info,
        .dashboard-card .icon.text-info {
            color: #36b9cc !important;
        }
        
        .stat-card .icon.text-warning,
        .dashboard-card .icon.text-warning {
            color: #f6c23e !important;
        }
        
        .stat-card .icon.text-danger,
        .dashboard-card .icon.text-danger {
            color: #e74a3b !important;
        }
        
        .stat-card .icon.text-secondary,
        .dashboard-card .icon.text-secondary {
            color: #858796 !important;
        }

        .stat-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .stat-card.border-left-primary {
            border-left-color: var(--primary-color);
        }

        .stat-card.border-left-success {
            border-left-color: var(--success-color);
        }

        .stat-card.border-left-warning {
            border-left-color: var(--warning-color);
        }

        .stat-card.border-left-danger {
            border-left-color: var(--danger-color);
        }

        .stat-title {
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .stat-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            opacity: 0.3;
        }

        .stat-footer {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .stat-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .stat-footer a:hover {
            color: var(--primary-dark);
        }

        /* Page Header */
        .page-header {
            background: var(--primary-color);
            color: white !important;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }



        .page-header h1 {
            color: white !important;
            margin: 0;
            font-weight: 700;
            font-size: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            z-index: 1;
        }

        /* Ensure all page header icons are visible */
        .page-header h1 i {
            color: white !important;
            font-size: 1.2em !important;
            margin-right: 0.5rem !important;
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .page-header p {
            color: white !important;
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
        }

        /* Fade In Animation */
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Row Spacing */
        .row {
            margin-bottom: 1.5rem;
        }

        .row:last-child {
            margin-bottom: 0;
        }

        /* Modern Card Styles */
        .modern-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            height: 100%;
        }

        .modern-card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modern-card-header h5 {
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white !important;
        }

        .modern-card-body {
            padding: 1.5rem;
        }

        /* Todo Item Styles */
        .todo-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .todo-item:last-child {
            border-bottom: none;
        }

        .todo-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }

        .todo-checkbox {
            margin: 0;
        }

        .todo-text {
            font-weight: 500;
            color: var(--dark-color);
        }

        .todo-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .todo-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
        }

        .action-btn {
            background: none;
            border: none;
            color: var(--gray);
            padding: 0.25rem;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            background: var(--light-color);
            color: var(--primary-color);
        }

        .action-btn.delete:hover {
            background: #fee;
            color: var(--danger-color);
        }

        /* Fade In Up Animation */
        .fade-in-up {
            animation: fadeInUp 0.6s ease-in-out;
        }

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

        /* Additional Utility Classes */
        .text-decoration-line-through {
            text-decoration: line-through;
        }

        .badge-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .badge-info {
            background-color: var(--info-color);
            color: white;
        }

        .badge-warning {
            background-color: var(--warning-color);
            color: white;
        }

        .badge-success {
            background-color: var(--success-color);
            color: white;
        }

        .text-muted {
            color: var(--gray) !important;
        }

        .text-success {
            color: var(--success-color) !important;
        }

        .text-warning {
            color: var(--warning-color) !important;
        }

        .text-danger {
            color: var(--danger-color) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        .small {
            font-size: 0.875rem !important;
        }

        .mr-1 {
            margin-right: 0.25rem !important;
        }

        .ml-1 {
            margin-left: 0.25rem !important;
        }

        .mt-2 {
            margin-top: 0.5rem !important;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        /* Custom Sidebar Toggle Override - Maximum specificity to override style.css */
        html body .sidebar.toggled {
            width: 6.5rem !important;
            overflow: visible !important;
        }
        
        html body .sidebar.toggled .sidebar-brand-text,
        html body .sidebar.toggled .nav-link span {
            display: none !important;
        }
        
        /* Show sidebar headings in collapsed state with smaller text */
        html body .sidebar.toggled .sidebar-heading {
            visibility: visible !important;
            height: auto !important;
            margin: 0.75rem 0 0.5rem 0 !important;
            padding: 0 0.5rem !important;
            font-size: 0.6rem !important;
            line-height: 1.2 !important;
            text-align: center !important;
            color: rgba(255, 255, 255, 0.6) !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }
        
        /* Ensure consistent spacing for collapsed sidebar */
        html body .sidebar.toggled hr.sidebar-divider {
            margin: 0.5rem 0 !important;
            border-top: 1px solid rgba(255, 255, 255, 0.15) !important;
        }
        
        /* Maintain consistent nav-item height */
        html body .sidebar.toggled .nav-item {
            margin-bottom: 0 !important;
        }
        
        /* Ensure consistent spacing between nav items */
        html body .sidebar.toggled .nav-item + .nav-item {
            margin-top: 0.25rem !important;
        }
        
        html body .sidebar.toggled .nav-link {
            text-align: center !important;
            padding: 0.75rem 0.5rem !important;
            height: auto !important;
            min-height: 3rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        html body .sidebar.toggled .nav-link i {
            margin-right: 0 !important;
            font-size: 1.1rem !important;
            line-height: 1 !important;
        }
        
        html body .sidebar.toggled .sidebar-brand {
            justify-content: center !important;
            height: 4.375rem !important;
            padding: 1rem 0.5rem !important;
            display: flex !important;
            align-items: center !important;
        }
        
        html body .sidebar.toggled .sidebar-brand-icon {
            margin-right: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        html body .sidebar.toggled .sidebar-brand-icon img {
            width: 50px !important;
            height: 50px !important;
            object-fit: contain !important;
        }
        
        html body .sidebar.toggled .sidebar-brand-text img {
            display: none !important;
        }
        
        /* Content wrapper adjustments (desktop) */
        html body #content-wrapper {
            margin-left: 14rem !important;
            transition: margin-left 0.3s ease !important;
        }

        /* Mobile view (screen width ≤ 768px) */
        @media (max-width: 768px) {
            html body #content-wrapper {
                margin-left: 7rem !important; /* Sidebar hidden by default */
                width: 100% !important;   /* Full width for content */
            }

            /* When sidebar is visible on mobile */
            body.sidebar-toggled #content-wrapper {
                margin-left: 14rem !important; /* Same width as sidebar */
                width: calc(100% - 14rem) !important;
            }
        }

        
        html body.sidebar-toggled #content-wrapper {
            margin-left: 6.5rem !important;
        }
        
        /* Sidebar transition */
        html body .sidebar {
            transition: width 0.3s ease !important;
            height: 100vh !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            z-index: 1000 !important;
        }
        
        /* Ensure collapsed sidebar maintains scrollability */
        html body .sidebar.toggled {
            overflow-y: auto !important;
            overflow-x: hidden !important;
            height: 100vh !important;
            max-height: 100vh !important;
        }
        
        /* Custom scrollbar for sidebar */
        html body .sidebar::-webkit-scrollbar,
        html body .sidebar.toggled::-webkit-scrollbar {
            width: 6px;
        }
        
        html body .sidebar::-webkit-scrollbar-track,
        html body .sidebar.toggled::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }
        
        html body .sidebar::-webkit-scrollbar-thumb,
        html body .sidebar.toggled::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        
        html body .sidebar::-webkit-scrollbar-thumb:hover,
        html body .sidebar.toggled::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        /* Firefox scrollbar styling */
        html body .sidebar,
        html body .sidebar.toggled {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) rgba(255, 255, 255, 0.1);
        }
        
        /* Prevent tooltips from interfering with sidebar navigation */
        .sidebar .nav-link {
            position: relative !important;
            z-index: 1 !important;
        }
        
        /* Disable all tooltips in sidebar */
        .sidebar .tooltip,
        body.sidebar-toggled .tooltip,
        .sidebar.toggled .tooltip {
            display: none !important;
        }
        
        /* Ensure sidebar toggle button stays in position */
        html body .sidebar #sidebarToggle {
            position: sticky !important;
            bottom: 1rem !important;
            margin: 1rem auto !important;
            z-index: 1000 !important;
        }
        
        /* Additional card header overrides for all variations */
        html body .card-header:first-child,
        html body .card-group > .card:not(:last-child) .card-header,
        html body .card-group > .card:not(:first-child) .card-header,
        html body .accordion > .card > .card-header {
            background: #18375d !important;
            color: white !important;
        }
        
        /* Override all elements with old blue gradient colors */
        html body .page-link,
        html body .page-item.active .page-link,
        html body .pagination .page-item.active .page-link {
            background-color: #18375d !important;
            border-color: #18375d !important;
            color: white !important;
        }
        
        html body .page-link:hover,
        html body .page-link:focus {
            background-color: #122a47 !important;
            border-color: #122a47 !important;
            color: white !important;
        }
        
        /* Override any gradient backgrounds */
        html body [class*="bg-gradient-primary"],
        html body [class*="bg-primary"],
        html body .bg-primary {
            background: #18375d !important;
        }
        
        /* Override any blue color classes */
        html body .text-primary,
        html body [class*="text-primary"] {
            color: #18375d !important;
        }
        
        /* Override any border colors */
        html body .border-primary,
        html body [class*="border-primary"] {
            border-color: #18375d !important;
        }
        
        /* Override any button colors */
        html body .btn-primary,
        html body [class*="btn-primary"] {
            background-color: #18375d !important;
            border-color: #18375d !important;
            color: white !important;
        }
        
        html body .btn-primary:hover,
        html body .btn-primary:focus,
        html body .btn-primary:active {
            background-color: #122a47 !important;
            border-color: #122a47 !important;
            color: white !important;
        }
        
        /* Override any badge colors */
        html body .badge-primary,
        html body [class*="badge-primary"] {
            background-color: #18375d !important;
            color: white !important;
        }
        
        /* Override any alert colors */
        html body .alert-primary {
            background-color: rgba(24, 55, 93, 0.1) !important;
            border-color: #18375d !important;
            color: #18375d !important;
        }
        
        /* Override any progress bar colors */
        html body .progress-bar {
            background-color: #18375d !important;
        }
        
        /* Override any list group item colors */
        html body .list-group-item-primary {
            background-color: rgba(24, 55, 93, 0.1) !important;
            color: #18375d !important;
        }
        
        /* Laravel Pagination Overrides */
        html body .pagination .page-item .page-link {
            background-color: #18375d !important;
            border-color: #18375d !important;
            color: white !important;
        }
        
        html body .pagination .page-item.active .page-link {
            background-color: #122a47 !important;
            border-color: #122a47 !important;
            color: white !important;
            font-weight: bold !important;
        }
        
        html body .pagination .page-item .page-link:hover,
        html body .pagination .page-item .page-link:focus {
            background-color: #122a47 !important;
            border-color: #122a47 !important;
            color: white !important;
        }
        
        html body .pagination .page-item.disabled .page-link {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            color: #adb5bd !important;
        }
        
        /* DataTables Pagination Overrides */
        html body .dataTables_wrapper .dataTables_paginate .paginate_button {
            background: #18375d !important;
            border: 1px solid #18375d !important;
            color: white !important;
        }
        
        html body .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #122a47 !important;
            border: 1px solid #122a47 !important;
            color: white !important;
        }
        
        html body .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #122a47 !important;
            border: 1px solid #122a47 !important;
            color: white !important;
            font-weight: bold !important;
        }
        
        html body .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            background: #6c757d !important;
            border: 1px solid #6c757d !important;
            color: #adb5bd !important;
        }
        
        /* Enhanced Active State Styling */
        html body .sidebar .nav-item.active .nav-link {
            background: rgba(255, 255, 255, 0.15) !important;
            border-left: 4px solid #fff !important;
            color: #fff !important;
            font-weight: 600 !important;
        }
        
        html body .sidebar .nav-item.active .nav-link i {
            color: #fff !important;
        }
        
        html body .sidebar .nav-item.active .nav-link span {
            color: #fff !important;
            font-weight: 600 !important;
        }
        
        /* Active state for collapsed sidebar */
        html body .sidebar.toggled .nav-item.active .nav-link {
            background: rgba(255, 255, 255, 0.2) !important;
            border-left: 4px solid #fff !important;
            border-radius: 0 6px 6px 0 !important;
        }
        
        /* Hover effect for active items */
        html body .sidebar .nav-item.active .nav-link:hover {
            background: rgba(255, 255, 255, 0.25) !important;
        }
        
        /* Active state for collapse items */
        html body .sidebar .collapse-item.active {
            background: rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
            font-weight: 600 !important;
        }

        /* Content Cards */
        .modern-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: none;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .modern-card-header {
            background: var(--primary-color);
            color: white !important;
            padding: 1.5rem 2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modern-card-header i {
            margin-right: 0.75rem;
        }

        .modern-card-body {
            padding: 2rem;
        }

        /* Todo List */
        .todo-item {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .todo-item:hover {
            box-shadow: var(--shadow);
            border-color: var(--primary-color);
        }

        .todo-content {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .todo-checkbox {
            margin-right: 1rem;
            transform: scale(1.2);
        }

        .todo-text {
            font-weight: 500;
            color: var(--dark-color);
        }

        .todo-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .todo-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .todo-badge.badge-danger {
            background: rgba(231, 74, 59, 0.1);
            color: var(--danger-color);
        }

        .todo-badge.badge-info {
            background: rgba(54, 185, 204, 0.1);
            color: var(--info-color);
        }

        .todo-badge.badge-warning {
            background: rgba(246, 194, 62, 0.1);
            color: var(--warning-color);
        }

        .todo-badge.badge-success {
            background: rgba(28, 200, 138, 0.1);
            color: var(--success-color);
        }

        .todo-badge.badge-primary {
            background: rgba(78, 115, 223, 0.1);
            color: var(--primary-color);
        }

        .action-btn {
            background: none;
            border: none;
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--dark-color);
        }

        .action-btn:hover {
            background: var(--light-color);
            color: var(--primary-color);
        }

        .action-btn.delete:hover {
            color: var(--danger-color);
        }

        /* Chart Container */
        .chart-container {
            position: relative;
            height: 300px;
            padding: 1rem;
        }

        /* Print-only container: only when body has .print-element-only */
        @media print {
            body.print-element-only * { visibility: hidden !important; }
            body.print-element-only #__print_area__,
            body.print-element-only #__print_area__ * { visibility: visible !important; }
            body.print-element-only #__print_area__ { position: absolute; left: 0; top: 0; width: 100%; padding: 0; margin: 0; }
        }

        /* Global print cleanup for all pages (incl. DataTables print view) */
        @media print {
            html, body { background: #fff !important; }
            /* Hide app chrome and interactive UI */
            .sidebar,
            #accordionSidebar,
            .topbar,
            .navbar,
            .footer,
            .page,
            .card-header,
            .container-fluid,
            .card,
            .card-body,
            .table-responsive,
            .dataTables_wrapper,
            .search-controls,
            .pagination,
            .btn,
            .dropdown,
            .alert,
            .toast,
            .toast-container,
            .modal { display: none !important; }

            /* Flatten layout spacing */
            #wrapper,
            #content-wrapper,
            #content,
            .container-fluid { padding: 0 !important; margin: 0 !important; background: #fff !important; }

            /* Hide DataTables chrome */
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate { display: none !important; }

            /* Ensure dt-print-view also hides chrome */
            body.dt-print-view .topbar,
            body.dt-print-view .sidebar,
            body.dt-print-view .navbar,
            body.dt-print-view .footer,
            body.dt-print-view .page,
            body.dt-print-view .card-header,
            body.dt-print-view .container-fluid,
            body.dt-print-view .card,
            body.dt-print-view .card-body,
            body.dt-print-view .table-responsive,
            body.dt-print-view .dataTables_wrapper,
            body.dt-print-view .btn,
            body.dt-print-view .dropdown { display: none !important; }

            body::before,
            body.dt-print-view::before {
                content: '';
                position: fixed;
                top: 0; right: 0; bottom: 0; left: 0;
                background: url('{{ asset('img/LBDairy.png') }}') center center no-repeat;
                background-size: 60% auto;
                opacity: 0.08;
                z-index: 0;
                pointer-events: none;
            }

            #__print_area__ { position: relative; z-index: 1; }
            .print-watermark-img {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 60vw;
                max-width: 900px;
                opacity: 0.08;
                z-index: 0;
                pointer-events: none;
            }

            /* Force badge text visible in print (browsers often skip backgrounds) */
            .badge,
            #auditDataTable .badge,
            .dt-print-view .badge {
                color: #000 !important;
                background: transparent !important;
                border: 1px solid #000 !important;
            }
        }
    </style>
    
    <style>
        /* Global top-right alert positioning */
        .refresh-notification {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            animation: slideInRight 0.3s ease-out;
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
    
    @stack('styles')
</head>

<body id="page-top">
    <br><br><br><br>
    <!-- Page Wrapper -->
    <div id="wrapper">
        @include('layouts.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                @include('layouts.topbar')

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <style>
                        @media (max-width: 768px) {
                            .alert {
                                margin: 30px;  /* Reduced side margins */
                                padding: 15px;
                                font-size: 14px;
                                width: calc(100% - 60px); /* Make alert wider */
                            }

                            .alert .btn-close {
                                padding: 0.5rem;
                                margin: -0.5rem -0.5rem -0.5rem auto;
                            }
                        }
                    </style>

                    <!-- Success/Error messages handled globally by top-right toast -->

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            @include('layouts.footer')
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery Easing for smooth animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <!-- SB Admin 2 JS -->
    <script src="{{ asset('js/sb-admin-2.js') }}"></script>
    
    <!-- Additional JavaScript for functionality -->
    <script>
        // Global function to update topbar profile picture
        function updateTopbarProfilePicture(imageFilename) {
            const topbarProfilePic = document.querySelector('.topbar .img-profile');
            if (topbarProfilePic && imageFilename) {
                // Add cache-busting parameter to ensure the image updates
                const timestamp = new Date().getTime();
                topbarProfilePic.src = '{{ asset("img/") }}/' + imageFilename + '?t=' + timestamp;
                console.log('Topbar profile picture updated to:', imageFilename);
            }
        }
        
        // Global print helper: prints a specific element in the same tab
        window.printElement = function(target) {
            try {
                const el = (typeof target === 'string') ? document.querySelector(target) : target;
                const printArea = document.getElementById('__print_area__') || (function(){ const d=document.createElement('div'); d.id='__print_area__'; document.body.appendChild(d); return d; })();
                printArea.innerHTML = '';
                if (el && el.tagName && el.tagName.toLowerCase() === 'canvas') {
                    const img = new Image();
                    img.src = el.toDataURL('image/png');
                    img.style.maxWidth = '100%';
                    printArea.appendChild(img);
                } else if (el) {
                    printArea.appendChild(el.cloneNode(true));
                }
                var wm = document.createElement('img');
                wm.src = '{{ asset('img/LBDairy.png') }}';
                wm.className = 'print-watermark-img';
                printArea.appendChild(wm);
                // Temporarily set a blank title so browsers don't print a header title
                const __prevTitle = document.title;
                document.title = '';
                document.body.classList.add('print-element-only');
                var done = false;
                function doPrint(){ if (done) return; done = true; try { window.print(); } catch(_){} setTimeout(function(){ printArea.innerHTML=''; document.body.classList.remove('print-element-only'); document.title = __prevTitle; }, 300); }
                if (wm.complete) { setTimeout(doPrint, 80); } else { wm.onload = function(){ setTimeout(doPrint, 80); }; setTimeout(doPrint, 900); }
            } catch (e) {
                window.print();
            }
        };

        window.openPrintWindow = function(html, title) {
            try {
                var win = window.open('', '_blank', 'noopener,noreferrer');
                if (!win) {
                    // Popup blocked: print in-place using print-element-only container
                    var printArea = document.getElementById('__print_area__') || (function(){ var d=document.createElement('div'); d.id='__print_area__'; document.body.appendChild(d); return d; })();
                    printArea.innerHTML = (typeof html === 'string') ? html : '';
                    document.body.classList.add('print-element-only');
                    window.print();
                    setTimeout(function(){
                        printArea.innerHTML='';
                        document.body.classList.remove('print-element-only');
                    }, 300);
                    return;
                }
                var doc = win.document;
                doc.open();
                doc.write('<!doctype html><html><head><meta charset="utf-8"><title>' + (typeof title === 'string' ? title : 'Print') + '</title>');
                doc.write('<style>@page{size:auto;margin:12mm;}html,body{background:#fff!important;color:#000;} .btn,.dropdown,.dataTables_wrapper,.table-responsive{display:none!important;} table{width:100%;border-collapse:collapse;} th,td{border:3px solid #000;padding:10px;text-align:left;} thead th{background:#f2f2f2;color:#18375d;} .badge{color:#000!important;background:transparent!important;border:1px solid #000!important;} @media print { body::before{content:"";position:fixed;top:0;right:0;bottom:0;left:0;background:url(\'{{ asset('img/LBDairy.png') }}\') center center no-repeat;background-size:60% auto;opacity:0.08;z-index:0;pointer-events:none;} .print-watermark-img{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);width:60vw;max-width:900px;opacity:0.08;z-index:0;pointer-events:none;} }</style>');
                doc.write('</head><body>');
                doc.write('<img src="{{ asset('img/LBDairy.png') }}" class="print-watermark-img" />');
                doc.write(html);
                doc.write('<script>(function(){var img=document.querySelector(".print-watermark-img");function go(){try{window.focus();window.print();}catch(e){} setTimeout(function(){try{window.close();}catch(_){ }},200);} if(img && !img.complete){ img.onload=function(){ setTimeout(go,80); }; setTimeout(go,900); } else { setTimeout(go,120); }})();<\/script>');
                doc.write('</body></html>');
                doc.close();
            } catch (e) {
                try {
                    var printArea2 = document.getElementById('__print_area__') || (function(){ var d=document.createElement('div'); d.id='__print_area__'; document.body.appendChild(d); return d; })();
                    printArea2.innerHTML = (typeof html === 'string') ? html : '';
                    document.body.classList.add('print-element-only');
                    window.print();
                    setTimeout(function(){
                        printArea2.innerHTML='';
                        document.body.classList.remove('print-element-only');
                    }, 300);
                } catch(ignored) {
                    window.print();
                }
            }
        };

        $(document).ready(function() {

            
            // Optimized tooltip initialization (guarded)
            try {
                if (window.jQuery && $.fn && $.fn.tooltip) {
                    // Set global defaults only if constructor/defaults exist
                    if ($.fn.tooltip.Constructor && $.fn.tooltip.Constructor.Default) {
                        $.fn.tooltip.Constructor.Default.template = '<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>';
                        $.fn.tooltip.Constructor.Default.delay = { show: 0, hide: 0 };
                        $.fn.tooltip.Constructor.Default.trigger = 'hover';
                        $.fn.tooltip.Constructor.Default.animation = false;
                    }
                    // Initialize tooltips with optimized settings
                    $('[data-toggle="tooltip"]').tooltip({
                        delay: { show: 0, hide: 0 },
                        trigger: 'hover',
                        animation: false
                    });
                }
            } catch (e) {
                console.warn('Global tooltip init failed:', e);
            }
            
            // Let SB Admin 2 handle the sidebar toggle
            // Just add localStorage support and debug logging
            
            // Restore sidebar state from localStorage
            var sidebarToggled = localStorage.getItem('sidebar-toggled') === 'true';
            if (sidebarToggled) {
                $('body').addClass('sidebar-toggled');
                $('.sidebar').addClass('toggled');
            }
            

            
            // Save state when sidebar is toggled (using SB Admin 2's event)
            $(document).on('click', '#sidebarToggle, #sidebarToggleTop', function() {
                console.log('Toggle button clicked - before toggle');
                console.log('Current sidebar classes:', $('.sidebar').attr('class'));
                console.log('Current body classes:', $('body').attr('class'));
                
                setTimeout(function() {
                    localStorage.setItem('sidebar-toggled', $('.sidebar').hasClass('toggled'));
                    console.log('Sidebar state saved:', $('.sidebar').hasClass('toggled'));
                    console.log('After toggle - sidebar classes:', $('.sidebar').attr('class'));
                    console.log('After toggle - body classes:', $('body').attr('class'));
                    console.log('Sidebar width:', $('.sidebar').css('width'));
                    

                }, 100);
            });
            
            // Ensure logout modal works
            $('[data-target="#logoutModal"]').on('click', function(e) {
                e.preventDefault();
                $('#logoutModal').modal('show');
            });
            
            // Debug logging
            console.log('jQuery loaded:', typeof $ !== 'undefined');
            console.log('Bootstrap modal:', typeof $.fn.modal !== 'undefined');
            console.log('Sidebar toggle button:', $('#sidebarToggle').length);
            console.log('Topbar sidebar toggle button:', $('#sidebarToggleTop').length);
            console.log('Logout modal:', $('#logoutModal').length);
            console.log('Initial sidebar state:', $('.sidebar').hasClass('toggled'));
            
            // Ensure sidebar scrolling works
            $('.sidebar').on('wheel', function(e) {
                e.preventDefault();
                this.scrollTop += e.originalEvent.deltaY;
            });
            
            // Additional debugging for farmer sidebar
            if ($('body').hasClass('sidebar-toggled') || $('.sidebar').hasClass('toggled')) {
                console.log('Sidebar is collapsed - checking scrollability');
                console.log('Sidebar height:', $('.sidebar').height());
                console.log('Sidebar overflow-y:', $('.sidebar').css('overflow-y'));
                console.log('Sidebar scrollHeight:', $('.sidebar')[0].scrollHeight);
                console.log('Sidebar clientHeight:', $('.sidebar')[0].clientHeight);
            }

            // Keep page position when opening modals or clicking placeholder anchors
            // Prevent hash anchors from jumping to top (except explicit scroll-to-top control)
            $(document).on('click', 'a[href^="#"]:not(.scroll-to-top)', function(e) {
                // Allow if it's a real hash navigation target and not a modal
                const href = $(this).attr('href');
                const $target = href && href.length > 1 ? $(href) : $();
                const isModalTarget = $target.hasClass('modal');
                if (href === '#' || isModalTarget || $(this).is('[data-toggle], [data-target]')) {
                    e.preventDefault();
                }
            });

            // Generic handler: anchors with data-target="#modalId" should open modal without navigation
            $(document).on('click', 'a[data-target^="#"]', function(e) {
                const target = $(this).attr('data-target');
                if (target) {
                    const $m = $(target);
                    if ($m.hasClass('modal')) {
                        e.preventDefault();
                        $m.modal('show');
                    }
                }
            });

            // Lock and restore scroll position around modal show to avoid jump-to-top
            var __PAGE_SCROLL_Y = 0;
            $(document).on('show.bs.modal', '.modal', function() {
                __PAGE_SCROLL_Y = $(window).scrollTop();
            });
            $(document).on('shown.bs.modal', '.modal', function() {
                $(window).scrollTop(__PAGE_SCROLL_Y);
            });
            $(document).on('hide.bs.modal hidden.bs.modal', '.modal', function() {
                $(window).scrollTop(__PAGE_SCROLL_Y);
            });

            // Enforce numeric-only and 11-digit limit for contact/phone inputs globally
            function enforcePhoneRules($inputs) {
                $inputs.each(function() {
                    const $el = $(this);
                    // Set helpful attributes
                    $el.attr({ inputmode: 'numeric', maxlength: 11, pattern: '^\\d{11}$' });
                    // Sanitize initial value
                    $el.val(($el.val() || '').toString().replace(/\D/g, '').slice(0, 11));
                });
            }

            function bindPhoneHandlers($inputs) {
                $inputs.off('input.enforcePhone').on('input.enforcePhone', function() {
                    const cleaned = this.value.replace(/\D/g, '').slice(0, 11);
                    if (this.value !== cleaned) this.value = cleaned;
                });
            }

            function initPhoneEnforcement() {
                // Target common phone/contact fields
                const $targets = $(
                    'input[name="phone"], ' +
                    'input[id*="Phone" i], ' +
                    'input[id*="phone" i], ' +
                    'input[name*="contact" i], ' +
                    'input[id*="contact" i]'
                ).filter('input[type="text"], input[type="tel"], input:not([type])');
                enforcePhoneRules($targets);
                bindPhoneHandlers($targets);
            }

            // Initialize now and after any dynamic DOM updates
            initPhoneEnforcement();
            // Re-apply on modal show (for dynamically injected forms)
            $(document).on('shown.bs.modal', function() { initPhoneEnforcement(); });

            // Scroll to Top Button Functionality
            $(document).on('scroll', function() {
                var scrollDistance = $(this).scrollTop();
                if (scrollDistance > 100) {
                    $('.scroll-to-top').fadeIn();
                } else {
                    $('.scroll-to-top').fadeOut();
                }
            });

            // Smooth scrolling to top when scroll-to-top button is clicked
            $(document).on('click', '.scroll-to-top', function(e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 800, 'easeInOutExpo');
            });
        });
    </script>
    
    <style id="__print_helper_css__">
    @media print {
        body.print-element-only *:not(#__print_area__):not(#__print_area__ *) {
            display: none !important;
        }
        #__print_area__ {
            display: block !important;
            width: 100% !important;
        }
        /* Hide interactive controls when printing a section (do not hide last table column globally) */
        #__print_area__ .action-buttons,
        #__print_area__ .btn-group,
        #__print_area__ .btn-action,
        #__print_area__ .btn,
        #__print_area__ [data-toggle],
        #__print_area__ [data-target] {
            display: none !important;
        }
    }
    </style>
    
    @stack('scripts')

    <script>
    (function initDtPrintWatermark(){
      function applyPatch(){
        try {
          if (!window.jQuery || !$.fn || !$.fn.dataTable || !$.fn.dataTable.ext || !$.fn.dataTable.ext.buttons || !$.fn.dataTable.ext.buttons.print) return false;
          var btn = $.fn.dataTable.ext.buttons.print;
          if (btn.__lb_watermark_patched) return true;
          var originalAction = btn.action;
          btn.action = function (e, dt, node, config) {
            config = config || {};
            var origCustomize = config.customize;
            var logo = '{{ asset('img/LBDairy.png') }}';
            // avoid auto print; we'll trigger after watermark loads
            config.autoPrint = false;
            config.customize = function (win) {
              try {
                var doc = win.document;
                var style = doc.createElement('style');
                style.textContent = '@media print { body::before{content:"";position:fixed;top:0;right:0;bottom:0;left:0;background:url(' + JSON.stringify(logo).slice(1,-1) + ') center center no-repeat;background-size:60% auto;opacity:0.08;z-index:0;pointer-events:none;} .print-watermark-img{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);width:60vw;max-width:900px;opacity:0.08;z-index:0;pointer-events:none;} }';
                doc.head.appendChild(style);
                var img = doc.createElement('img');
                img.src = logo;
                img.className = 'print-watermark-img';
                doc.body.insertBefore(img, doc.body.firstChild);
                var done=false; function go(){ if(done) return; done=true; try{ win.focus(); win.print(); }catch(_){ } setTimeout(function(){ try{ win.close(); }catch(_){ } }, 200); }
                if (img.complete) { setTimeout(go, 120); } else { img.onload = function(){ setTimeout(go, 120); }; setTimeout(go, 900); }
              } catch(_){ }
              if (typeof origCustomize === 'function') { try { origCustomize(win); } catch(_){ } }
            };
            return originalAction ? originalAction.call(this, e, dt, node, config) : $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, node, config);
          };
          btn.__lb_watermark_patched = true;
          return true;
        } catch(_) { return false; }
      }
      if (!applyPatch()) {
        var tries = 0; var timer = setInterval(function(){ if (applyPatch() || ++tries > 20) clearInterval(timer); }, 500);
      }
    })();
    </script>
    
    <script>
    // Global helper for top-right alerts (Bootstrap 4.x)
    function showTopRightAlert(message, type) {
        try {
            var icon = (type === 'success') ? 'check-circle' : (type === 'warning' ? 'exclamation-triangle' : (type === 'info' ? 'info-circle' : 'times-circle'));
            var $n = $(`
                <div class="alert alert-${type} alert-dismissible fade show refresh-notification">
                    <i class="fas fa-${icon} mr-2"></i>
                    ${message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `);
            $('body').append($n);
            setTimeout(function(){ $n.alert('close'); }, 5000);
        } catch (e) { console.warn('showTopRightAlert error', e); }
    }

    document.addEventListener('DOMContentLoaded', function(){
        // Trigger on Laravel session flashes
        @if(session('success'))
            showTopRightAlert(@json(session('success')), 'success');
        @endif
        @if(session('error'))
            showTopRightAlert(@json(session('error')), 'danger');
        @endif
    });
    </script>
    
    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <!-- Toast notifications will be dynamically added here -->
    </div>
    
    @include('layouts.logout-modal')
    
    <!-- Service Worker Registration -->
    <script>
    console.log('=== SERVICE WORKER DEBUG ===');
    console.log('Service worker script loaded!');
    console.log('Navigator object:', navigator);
    console.log('ServiceWorker in navigator:', 'serviceWorker' in navigator);
    
    if ('serviceWorker' in navigator) {
        console.log('Service Worker API is supported');
        window.addEventListener('load', () => {
            console.log('Page loaded, attempting to register service worker...');
            console.log('Current URL:', window.location.href);
            
            navigator.serviceWorker.register('/sw.js')
                .then(registration => {
                    console.log('SW registered successfully: ', registration);
                })
                .catch(registrationError => {
                    console.log('SW registration failed: ', registrationError);
                    console.error('Registration error details:', registrationError);
                });
        });
    } else {
        console.log('Service Worker API is NOT supported');
    }
    </script>
</body>
</html>
