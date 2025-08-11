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

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.profile') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.farms') }}">
                    <i class="fas fa-fw fa-home"></i>
                    <span>My Farms</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.livestock') }}">
                    <i class="fas fa-fw fa-cow"></i>
                    <span>Livestock</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.production') }}">
                    <i class="fas fa-fw fa-chart-line"></i>
                    <span>Production</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.sales') }}">
                    <i class="fas fa-fw fa-dollar-sign"></i>
                    <span>Sales</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.expenses') }}">
                    <i class="fas fa-fw fa-receipt"></i>
                    <span>Expenses</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('farmer.issues') }}">
                    <i class="fas fa-fw fa-exclamation-triangle"></i>
                    <span>Issues</span>
                </a>
            </li>

        @elseif(auth()->user()->isAdmin())
            <!-- Admin Navigation -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.profile') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.farms') }}">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Farms</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.farmers') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Farmers</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.livestock.index') }}">
                    <i class="fas fa-fw fa-cow"></i>
                    <span>Manage Livestock</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.farms.index') }}">
                    <i class="fas fa-fw fa-university"></i>
                    <span>Manage Farms</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.production') }}">
                    <i class="fas fa-fw fa-chart-line"></i>
                    <span>Production</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.sales') }}">
                    <i class="fas fa-fw fa-dollar-sign"></i>
                    <span>Sales</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.expenses') }}">
                    <i class="fas fa-fw fa-receipt"></i>
                    <span>Expenses</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.issues.index') }}">
                    <i class="fas fa-fw fa-exclamation-triangle"></i>
                    <span>Manage Issues</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.analysis.index') }}">
                    <i class="fas fa-fw fa-chart-bar"></i>
                    <span>Productivity Analysis</span>
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

            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.profile') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.users') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.admins') }}">
                    <i class="fas fa-fw fa-user-shield"></i>
                    <span>Admins</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.farms') }}">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Farms</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.audit-logs') }}">
                    <i class="fas fa-fw fa-history"></i>
                    <span>Audit Logs</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.settings') }}">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
        @endif
    @endauth

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
