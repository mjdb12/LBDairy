<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="LBDAIRY - Admin Dashboard">
    <meta name="author" content="LBDAIRY">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'LBDAIRY - Admin Dashboard')</title>

    <!-- Custom fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/unified-styles.css') }}" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
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

        /* Table Styling */
        .table {
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead th {
            background-color: var(--light-color);
            border-bottom: 2px solid var(--border-color);
            font-weight: 600;
            color: var(--dark-color);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        .table tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.05);
        }

        /* Badge Styling */
        .badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.4rem 0.6rem;
            border-radius: 6px;
        }

        /* Avatar Styling */
        .avatar-sm {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
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

        /* Sidebar Toggle Button */
        #sidebarToggle {
            background: var(--primary-color) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 0.5rem !important;
            transition: all 0.2s ease !important;
            box-shadow: var(--shadow) !important;
        }

        #sidebarToggle:hover {
            background: var(--primary-dark) !important;
            transform: translateY(-1px) !important;
            box-shadow: var(--shadow-lg) !important;
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
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
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
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- SB Admin 2 JS -->
    <script src="{{ asset('js/sb-admin-2.js') }}"></script>
    
    <!-- Additional JavaScript for functionality -->
    <script>
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
                    // Use Bootstrap 5 collapse
                    const collapseElements = document.querySelectorAll('.sidebar .collapse');
                    collapseElements.forEach(element => {
                        const bsCollapse = new bootstrap.Collapse(element, { hide: true });
                    });
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
            
            // Ensure logout modal works (Bootstrap 5 syntax)
            $('[data-bs-toggle="modal"][data-bs-target="#logoutModal"]').on('click', function(e) {
                e.preventDefault();
                const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
                logoutModal.show();
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
    
    @include('layouts.logout-modal')
</body>
</html>
