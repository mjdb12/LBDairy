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
    <meta name="theme-color" content="#4e73df">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="LB Dairy">

    <title>@yield('title', 'LBDAIRY')</title>

    <!-- Custom fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/unified-styles.css') }}" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4e73df;
            --primary-dark: #3c5aa6;
            --success-color: #1cc88a;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --info-color: #36b9cc;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
            --gray: #858796;
            --border-color: #e3e6f0;
            --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fc;
        }

        /* Enhanced Card Styling */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-bottom: none;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h6 {
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-header h6::before {
            content: '';
            width: 4px;
            height: 20px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
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
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-color);
            height: 100%;
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
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .page-header h1 {
            color: var(--dark-color);
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-header p {
            color: var(--gray);
            font-size: 1.125rem;
            margin: 0;
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
            background: white;
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
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
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

        /* Page Header Enhancement */
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }

        .page-header h1 {
            margin: 0;
            font-weight: 700;
            font-size: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        /* Ensure sidebar scrolling works properly */
        #wrapper {
            position: relative;
        }
        
        #content-wrapper {
            margin-left: 6.5rem;
        }
        
        @media (min-width: 768px) {
            #content-wrapper {
                margin-left: 14rem;
            }
        }
        
        body.sidebar-toggled #content-wrapper {
            margin-left: 6.5rem;
        }
        
        /* Enhanced Sidebar Toggle Button Styling */
        #sidebarToggle {
            width: 40px !important;
            height: 40px !important;
            background-color: #4e73df !important;
            color: white !important;
            border: none !important;
            cursor: pointer !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
        }
        
        #sidebarToggle:hover {
            background-color: #3c5aa6 !important;
            transform: scale(1.1) !important;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important;
        }
        
        #sidebarToggle:active {
            transform: scale(0.95) !important;
        }
        
        #sidebarToggle i {
            font-size: 14px !important;
            font-weight: bold !important;
        }
        
        /* Ensure sidebar toggle container is visible */
        .sidebar #sidebarToggle {
            position: relative !important;
            z-index: 1000 !important;
        }
    </style>
    
    @stack('styles')
</head>

<body id="page-top">
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
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

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
        
        $(document).ready(function() {
            // Enhanced sidebar toggle functionality
            $('#sidebarToggle').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                $('body').toggleClass('sidebar-toggled');
                $('.sidebar').toggleClass('toggled');
                
                // Update button icon and text
                var icon = $(this).find('i');
                if ($('.sidebar').hasClass('toggled')) {
                    icon.removeClass('fa-chevron-left').addClass('fa-chevron-right');
                    $('.sidebar .collapse').collapse('hide');
                } else {
                    icon.removeClass('fa-chevron-right').addClass('fa-chevron-left');
                }
                
                console.log('Sidebar toggled:', $('.sidebar').hasClass('toggled'));
            });
            
            // Topbar sidebar toggle for mobile
            $('#sidebarToggleTop').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                $('body').toggleClass('sidebar-toggled');
                $('.sidebar').toggleClass('toggled');
                
                console.log('Topbar sidebar toggle clicked');
            });
            
            // Ensure logout modal works
            $('[data-toggle="modal"][data-target="#logoutModal"]').on('click', function(e) {
                e.preventDefault();
                $('#logoutModal').modal('show');
            });
            
            // Debug logging
            console.log('jQuery loaded:', typeof $ !== 'undefined');
            console.log('Bootstrap modal:', typeof $.fn.modal !== 'undefined');
            console.log('Sidebar toggle button:', $('#sidebarToggle').length);
            console.log('Topbar sidebar toggle button:', $('#sidebarToggleTop').length);
            console.log('Logout modal:', $('#logoutModal').length);
            
            // Test click events
            $('#sidebarToggle').on('click', function() {
                console.log('Sidebar toggle button clicked!');
            });
        });
    </script>
    
    @stack('scripts')
    
    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <!-- Toast notifications will be dynamically added here -->
    </div>
    
    @include('layouts.logout-modal')
    
    <!-- Service Worker Registration -->
    <script>
    console.log('Service worker script loaded!');
    if ('serviceWorker' in navigator) {
        console.log('Service Worker API is supported');
        window.addEventListener('load', () => {
            console.log('Page loaded, attempting to register service worker...');
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
