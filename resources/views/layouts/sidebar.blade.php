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
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background: #18375d !important;">

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
            <li class="nav-item {{ isCurrentRoute('farmer.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.dashboard') }}" title="Dashboard">
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
                <a class="nav-link" href="{{ route('farmer.scan') }}" title="Scan Livestock">
                    <i class="fas fa-qrcode"></i>
                    <span>Scan Livestock</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.livestock') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.livestock') }}" title="Manage Livestock">
                    <i class="fa fa-list"></i>
                    <span>Manage Livestock</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.issues') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.issues') }}" title="Manage Issues">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span>Manage Issues</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.issue-alerts') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.issue-alerts') }}" title="Issues Alerts">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span>Issues Alerts</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.farm-analysis') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.farm-analysis') }}" title="Farm Analysis">
                    <i class="fas fa-fw fa-database"></i>
                    <span>Farm Analysis</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.livestock-analysis') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.livestock-analysis') }}" title="Livestock Analysis">
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
                <a class="nav-link" href="{{ route('farmer.sales') }}" title="Sales">
                    <i class="fas fa-fw fa-donate"></i>
                    <span>Sales</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.clients') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.clients') }}" title="Clients">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Clients</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.suppliers') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.suppliers') }}" title="Suppliers">
                    <i class="fa fa-users"></i>
                    <span>Suppliers</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.production') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.production') }}" title="Production">
                    <i class="fas fa-fw fa-tasks"></i>
                    <span>Production</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.expenses') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.expenses') }}" title="Expenses">
                    <i class="fas fa-fw fa-donate"></i>
                    <span>Expenses</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.inventory') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.inventory') }}" title="Inventory">
                    <i class="fa fa-list"></i>
                    <span>Inventory</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('farmer.schedule') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.schedule') }}" title="Calendar">
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

            <li class="nav-item {{ isCurrentRoute('farmer.audit-logs') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.audit-logs') }}" title="Audit Logs">
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

            <li class="nav-item {{ isCurrentRoute('farmer.profile') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.profile') }}" title="Profile">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal" title="Logout">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>

        @elseif(auth()->user()->isAdmin())
            <!-- Admin Navigation -->
            <li class="nav-item {{ isCurrentRoute('admin.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}" title="Dashboard">
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
                <a class="nav-link" href="{{ route('admin.manage-farmers') }}" title="Farmers">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Farmers</span>
                </a>
            </li>

            <!-- Nav Item - Livestock -->
            <li class="nav-item {{ isCurrentRoutePattern('admin.livestock.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCharts"
                    aria-expanded="false" aria-controls="collapseCharts" title="Livestock">
                    <i class="fas fa-fw fa-list"></i>
                    <span>Livestock</span>
                </a>
                <div id="collapseCharts" class="collapse" aria-labelledby="headingCharts" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manage:</h6>
                        <a class="collapse-item {{ isCurrentRoute('admin.livestock.index') ? 'active' : '' }}" href="{{ route('admin.livestock.index') }}">List of Farmers</a>
                        <div class="collapse-divider"></div>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Analysis -->
            <li class="nav-item {{ isCurrentRoute('admin.analysis.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.analysis.index') }}" title="Productivity Analysis">
                    <i class="fas fa-fw fa-chart-bar"></i>
                    <span>Productivity Analysis</span>
                </a>
            </li>

            <!-- Nav Item - Issues -->
            <li class="nav-item {{ isCurrentRoute('admin.issues.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.issues.index') }}" title="Issues Alerts">
                    <i class="fas fa-fw fa-exclamation-triangle"></i>
                    <span>Issues Alerts</span>
                </a>
            </li>

            <!-- Nav Item - Schedule Inspections -->
            <li class="nav-item {{ isCurrentRoute('admin.schedule-inspections') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.schedule-inspections') }}" title="Schedule Inspections">
                    <i class="fas fa-fw fa-calendar-check"></i>
                    <span>Schedule Inspections</span>
                </a>
            </li>

            <!-- Nav Item - Farmer Alerts -->
            <li class="nav-item {{ isCurrentRoute('admin.farmer-alerts') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.farmer-alerts') }}" title="Farmer Alerts">
                    <i class="fas fa-fw fa-bell"></i>
                    <span>Farmer Alerts</span>
                </a>
            </li>

            <!-- Nav Item - User Approvals -->
            <li class="nav-item {{ isCurrentRoute('admin.approvals') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.approvals') }}" title="User Approvals">
                    <i class="fas fa-fw fa-user-check"></i>
                    <span>User Approvals</span>
                </a>
            </li>

            <!-- Nav Item - Logs -->
            <li class="nav-item {{ isCurrentRoute('admin.audit-logs') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.audit-logs') }}" title="Logs">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Logs</span>
                </a>
            </li>

            <!-- Nav Item - Profile -->
            <li class="nav-item {{ isCurrentRoute('admin.profile') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.profile') }}" title="Profile">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>

            <!-- Nav Item - Logout -->
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal" title="Logout">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>

        @elseif(auth()->user()->isSuperAdmin())
            <!-- Super Admin Navigation -->
            <li class="nav-item {{ isCurrentRoute('superadmin.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('superadmin.dashboard') }}" title="Dashboard">
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
                <a class="nav-link" href="{{ route('superadmin.users') }}" title="User Management">
                    <i class="fas fa-fw fa-users-cog"></i>
                    <span>User Management</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('superadmin.admins') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('superadmin.admins') }}" title="Manage Admins">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Manage Admins</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('superadmin.manage-farmers') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('superadmin.manage-farmers') }}" title="Manage Farmers">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Manage Farmers</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('superadmin.farms.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('superadmin.farms.index') }}" title="Manage Farms">
                    <i class="fas fa-fw fa-university"></i>
                    <span>Manage Farms</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('superadmin.manage-analysis') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('superadmin.manage-analysis') }}" title="Productivity Analysis">
                    <i class="fas fa-fw fa-database"></i>
                    <span>Productivity Analysis</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('superadmin.audit-logs') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('superadmin.audit-logs') }}" title="Audit Logs">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Audit Logs</span>
                </a>
            </li>

            <li class="nav-item {{ isCurrentRoute('superadmin.profile') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('superadmin.profile') }}" title="Profile">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal" title="Logout">
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
        <button class="rounded-circle border-0" id="sidebarToggle" title="Toggle Sidebar">
        </button>
    </div>

</ul>
<!-- End of Sidebar -->
