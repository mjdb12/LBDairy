@php
    // Helper function to determine if current route matches
    function isCurrentRoute($routeName) {
        return request()->routeIs($routeName);
    }
    
    // Helper function to determine if current route starts with a pattern
    function isCurrentRoutePattern($pattern) {
        return request()->routeIs($pattern . '*');
    }
@endphp

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <br><br><br>
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}" style="background-color: #ffffffff !important; border-bottom: 2px solid #18375d !important;">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/LBDairy.png') }}" alt="LBDAIRY" width="50" height="50">
        </div>
        <div class="sidebar-brand-text mx-2">
            <img src="{{ asset('img/LBDairyText.png') }}" alt="LBDAIRY" style="height: 70px; width: auto; object-fit: contain;">
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    @auth
        @if(auth()->user()->isFarmer())
            <!-- Farmer Navigation -->
            <li class="nav-item {{ isCurrentRoute('farmer.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.dashboard') }}" data-toggle="tooltip" data-placement="right" data-original-title="Dashboard">
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

            <li class="nav-item {{ isCurrentRoute('farmer.scan') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.scan') }}" data-toggle="tooltip" data-placement="right" data-original-title="Scan Livestock">
                    <i class="fas fa-qrcode"></i>
                    <span>Scan Livestock</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.livestock') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.livestock') }}" data-toggle="tooltip" data-placement="right" data-original-title="Manage Livestock">
                    <i class="fa fa-list"></i>
                    <span>Manage Livestock</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.issues') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.issues') }}" data-toggle="tooltip" data-placement="right" data-original-title="Manage Issues">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span>Manage Issues</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.issue-alerts') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.issue-alerts') }}" data-toggle="tooltip" data-placement="right" data-original-title="Issues Alerts">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span>Issues Alerts</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.farm-analysis') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.farm-analysis') }}" data-toggle="tooltip" data-placement="right" data-original-title="Farm Analysis">
                    <i class="fas fa-fw fa-database"></i>
                    <span>Farm Analysis</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.livestock-analysis') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.livestock-analysis') }}" data-toggle="tooltip" data-placement="right" data-original-title="Livestock Analysis">
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

            <li class="nav-item {{ isCurrentRoute('farmer.sales') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.sales') }}" data-toggle="tooltip" data-placement="right" data-original-title="Sales">
                    <i class="fas fa-fw fa-donate"></i>
                    <span>Sales</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.clients') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.clients') }}" data-toggle="tooltip" data-placement="right" data-original-title="Clients">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Clients</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.suppliers') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.suppliers') }}" data-toggle="tooltip" data-placement="right" data-original-title="Suppliers">
                    <i class="fa fa-users"></i>
                    <span>Suppliers</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.production') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.production') }}" data-toggle="tooltip" data-placement="right" data-original-title="Production">
                    <i class="fas fa-fw fa-tasks"></i>
                    <span>Production</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.expenses') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.expenses') }}" data-toggle="tooltip" data-placement="right" data-original-title="Expenses">
                    <i class="fas fa-fw fa-donate"></i>
                    <span>Expenses</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.inventory') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.inventory') }}" data-toggle="tooltip" data-placement="right" data-original-title="Inventory">
                    <i class="fa fa-list"></i>
                    <span>Inventory</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.schedule') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.schedule') }}" data-toggle="tooltip" data-placement="right" data-original-title="Calendar">
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

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Manage Profile
            </div>

            <li class="nav-item {{ isCurrentRoute('farmer.profile') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.profile') }}" data-toggle="tooltip" data-placement="right" data-original-title="Profile">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-target="#logoutModal" data-toggle="tooltip" data-placement="right" data-original-title="Logout">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>

        @elseif(auth()->user()->isAdmin())
            <!-- Admin Navigation -->
            <li class="nav-item {{ isCurrentRoute('admin.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}" data-toggle="tooltip" data-placement="right" data-original-title="Dashboard">
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
            <li class="nav-item {{ isCurrentRoute('admin.manage-farmers') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.manage-farmers') }}" data-toggle="tooltip" data-placement="right" data-original-title="Farmers">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Farmers</span>
                </a>
            </li>

            <!-- Nav Item - Livestock -->
            <li class="nav-item {{ isCurrentRoute('admin.livestock.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.livestock.index') }}" data-toggle="tooltip" data-placement="right" data-original-title="Livestock">
                    <i class="fas fa-fw fa-list"></i>
                    <span>Livestock</span>
                </a>
            </li>   

            <!-- Nav Item - Analysis -->
            <li class="nav-item {{ isCurrentRoute('admin.analysis.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.analysis.index') }}" data-toggle="tooltip" data-placement="right" data-original-title="Productivity Analysis">
                    <i class="fas fa-fw fa-chart-bar"></i>
                    <span>Productivity Analysis</span>
                </a>
            </li>

            <!-- Nav Item - Issues -->
            <li class="nav-item {{ isCurrentRoute('admin.issues.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.issues.index') }}" data-toggle="tooltip" data-placement="right" data-original-title="Issues Alerts">
                    <i class="fas fa-fw fa-exclamation-triangle"></i>
                    <span>Issues Alerts</span>
                </a>
            </li>

            <!-- Nav Item - Schedule Inspections -->
            <li class="nav-item {{ isCurrentRoute('admin.schedule-inspections') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.schedule-inspections') }}" data-toggle="tooltip" data-placement="right" data-original-title="Schedule Inspections">
                    <i class="fas fa-fw fa-calendar-check"></i>
                    <span>Schedule Inspections</span>
                </a>
            </li>

            <!-- Nav Item - Farmer Alerts -->
            <li class="nav-item {{ isCurrentRoute('admin.farmer-alerts') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.farmer-alerts') }}" data-toggle="tooltip" data-placement="right" data-original-title="Farmer Alerts">
                    <i class="fas fa-fw fa-exclamation-circle"></i>
                    <span>Farmer Alerts</span>
                </a>
            </li>

            <!-- Nav Item - User Approvals -->
            <li class="nav-item {{ isCurrentRoute('admin.approvals') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.approvals') }}" data-toggle="tooltip" data-placement="right" data-original-title="User Approvals">
                    <i class="fas fa-fw fa-user-check"></i>
                    <span>User Approvals</span>
                </a>
            </li>

            <!-- Nav Item - Logs -->
            <li class="nav-item {{ isCurrentRoute('admin.audit-logs') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.audit-logs') }}" data-toggle="tooltip" data-placement="right" data-original-title="Logs">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Logs</span>
                </a>
            </li>

            <!-- Nav Item - Profile -->
            <li class="nav-item {{ isCurrentRoute('admin.profile') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.profile') }}" data-toggle="tooltip" data-placement="right" data-original-title="Profile">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>

            <!-- Nav Item - Logout -->
            <li class="nav-item">
                <a class="nav-link" href="#" data-target="#logoutModal" data-toggle="tooltip" data-placement="right" data-original-title="Logout">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>

        @elseif(auth()->user()->isSuperAdmin())
            <!-- Super Admin Navigation -->
             
            <li class="nav-item {{ isCurrentRoute('superadmin.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('superadmin.dashboard') }}" data-toggle="tooltip" data-placement="right" data-original-title="Dashboard">
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

            <li class="nav-item {{ isCurrentRoute('superadmin.users') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('superadmin.users') }}" data-toggle="tooltip" data-placement="right" data-original-title="User Management">
                    <i class="fas fa-fw fa-users-cog"></i>
                    <span>User Management</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('superadmin.admins') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('superadmin.admins') }}" data-toggle="tooltip" data-placement="right" data-original-title="Manage Admins">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Manage Admins</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('superadmin.manage-farmers') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('superadmin.manage-farmers') }}" data-toggle="tooltip" data-placement="right" data-original-title="Manage Farmers">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Manage Farmers</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('superadmin.farms.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('superadmin.farms.index') }}" data-toggle="tooltip" data-placement="right" data-original-title="Manage Farms">
                    <i class="fas fa-fw fa-university"></i>
                    <span>Manage Farms</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('superadmin.manage-analysis') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('superadmin.manage-analysis') }}" data-toggle="tooltip" data-placement="right" data-original-title="Productivity Analysis">
                    <i class="fas fa-fw fa-database"></i>
                    <span>Productivity Analysis</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('superadmin.audit-logs') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('superadmin.audit-logs') }}" data-toggle="tooltip" data-placement="right" data-original-title="Audit Logs">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Audit Logs</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('superadmin.profile') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('superadmin.profile') }}" data-toggle="tooltip" data-placement="right" data-original-title="Profile">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-target="#logoutModal" data-toggle="tooltip" data-placement="right" data-original-title="Logout">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        @endif
    @endauth

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggle Button -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle" data-toggle="tooltip" data-placement="right" data-original-title="Toggle Sidebar">
        </button>
    </div>

</ul>
<!-- End of Sidebar -->
