<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/logo.png') }}" alt="LBDAIRY" width="40" height="40">
        </div>
        <div class="sidebar-brand-text mx-3">LBDAIRY</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    @auth
        @if(auth()->user()->isFarmer())
            <!-- Farmer Navigation -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Tools
            </div>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.scan') }}">
                    <i class="fas fa-qrcode"></i>
                    <span>Scan Livestock</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.livestock') }}">
                    <i class="fa fa-list"></i>
                    <span>Manage Livestock</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.issues') }}">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span>Manage Issues</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.issue-alerts') }}">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span>Issues Alerts</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.farm-analysis') }}">
                    <i class="fas fa-fw fa-database"></i>
                    <span>Farm Analysis</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.livestock-analysis') }}">
                    <i class="fas fa-fw fa-database"></i>
                    <span>Livestock Analysis</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Operation
            </div>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.sales') }}">
                    <i class="fas fa-fw fa-donate"></i>
                    <span>Sales</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.clients') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Clients</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.suppliers') }}">
                    <i class="fa fa-users"></i>
                    <span>Suppliers</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.production') }}">
                    <i class="fas fa-fw fa-tasks"></i>
                    <span>Production</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.expenses') }}">
                    <i class="fas fa-fw fa-donate"></i>
                    <span>Expenses</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.inventory') }}">
                    <i class="fa fa-list"></i>
                    <span>Inventory</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.schedule') }}">
                    <i class="fas fa-fw fa-clock"></i>
                    <span>Calendar</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                System
            </div>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.audit-logs') }}">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Audit Logs</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Manage Profile
            </div>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.profile') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>

        @elseif(auth()->user()->isAdmin())
            <!-- Admin Navigation -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Tools
            </div>

            <!-- Nav Item - Farmers -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.manage-farmers') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Farmers</span>
                </a>
            </li>

            <!-- Nav Item - Livestock -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCharts"
                    aria-expanded="false" aria-controls="collapseCharts">
                    <i class="fas fa-fw fa-list"></i>
                    <span>Livestock</span>
                </a>
                <div id="collapseCharts" class="collapse" aria-labelledby="headingCharts" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manage:</h6>
                        <a class="collapse-item" href="{{ route('admin.livestock.index') }}">List of Farmers</a>
                        <div class="collapse-divider"></div>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Analysis -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.analysis.index') }}">
                    <i class="fas fa-fw fa-chart-bar"></i>
                    <span>Productivity Analysis</span>
                </a>
            </li>

            <!-- Nav Item - Issues -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.issues.index') }}">
                    <i class="fas fa-fw fa-exclamation-triangle"></i>
                    <span>Issues Alerts</span>
                </a>
            </li>

            <!-- Nav Item - User Approvals -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.approvals') }}">
                    <i class="fas fa-fw fa-user-check"></i>
                    <span>User Approvals</span>
                </a>
            </li>

            <!-- Nav Item - Logs -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.audit-logs') }}">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Logs</span>
                </a>
            </li>

            <!-- Nav Item - Profile -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.profile') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>

            <!-- Nav Item - Logout -->
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>

        @elseif(auth()->user()->isSuperAdmin())
            <!-- Super Admin Navigation -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Tools
            </div>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.users') }}">
                    <i class="fas fa-fw fa-users-cog"></i>
                    <span>User Management</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.admins') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Manage Admins</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.manage-farmers') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Manage Farmers</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.farms.index') }}">
                    <i class="fas fa-fw fa-university"></i>
                    <span>Manage Farms</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.manage-analysis') }}">
                    <i class="fas fa-fw fa-database"></i>
                    <span>Productivity Analysis</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.audit-logs') }}">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Audit Logs</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.profile') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        @endif
    @endauth

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">



</ul>
<!-- End of Sidebar -->
