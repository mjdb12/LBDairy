@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-clipboard-list"></i>
        Audit Logs
    </h1>
    <p>Monitor and track all system activities</p>
</div>

<!-- Stats Cards -->
<div class="stats-container slide-in">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <h3>{{ $totalLogs ?? 0 }}</h3>
        <p>Total Logs</p>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-calendar-day"></i>
        </div>
        <h3>{{ $todayLogs ?? 0 }}</h3>
        <p>Today's Activity</p>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3>{{ $criticalEvents ?? 0 }}</h3>
        <p>Critical Actions</p>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <h3>{{ \App\Models\User::where('is_active', true)->count() }}</h3>
        <p>Active Users</p>
    </div>
</div>

<!-- Audit Logs Table Card -->
<div class="card shadow mb-4 fade-in">
    <div class="card-header">
        <h6>
            <i class="fas fa-clipboard-list"></i>
            System Activity Logs
        </h6>
        <div class="table-controls">
            <div class="search-container">
                <input type="text" class="form-control custom-search" placeholder="Search logs...">
            </div>
            <div class="export-controls">
                <div class="btn-group">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                        <i class="fas fa-download"></i> Export
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" onclick="exportCSV()">
                            <i class="fas fa-file-csv"></i> CSV
                        </a>
                        <a class="dropdown-item" href="#" onclick="exportPDF()">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        <a class="dropdown-item" href="#" onclick="exportPNG()">
                            <i class="fas fa-image"></i> PNG
                        </a>
                    </div>
                </div>
                <button class="btn btn-secondary btn-sm" onclick="printTable()">
                    <i class="fas fa-print"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Filter Controls -->
    <div class="filter-controls">
        <div class="filter-group">
            <label for="roleFilter">Role</label>
            <select id="roleFilter">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="farmer">Farmer</option>
                <option value="superadmin">Super Admin</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="actionFilter">Action</label>
            <select id="actionFilter">
                <option value="">All Actions</option>
                <option value="login">Login</option>
                <option value="update">Update</option>
                <option value="delete">Delete</option>
                <option value="create">Create</option>
                <option value="export">Export</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="dateFrom">From Date</label>
            <input type="date" id="dateFrom">
        </div>
        <div class="filter-group">
            <label for="dateTo">To Date</label>
            <input type="date" id="dateTo">
        </div>
        <div class="filter-group">
            <label>&nbsp;</label>
            <div style="display: flex; gap: 0.5rem;">
                <button class="btn-filter" onclick="applyFilters()">
                    <i class="fas fa-filter"></i> Apply
                </button>
                <button class="btn-clear" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="auditLogsTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Log ID</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditLogs as $log)
                    <tr>
                        <td><code class="small">LOG{{ str_pad($log->id, 3, '0', STR_PAD_LEFT) }}</code></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('img/ronaldo.png') }}" class="rounded-circle mr-2" width="32" height="32" alt="User">
                                <span class="font-weight-bold">{{ $log->user->name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="role-badge role-{{ $log->user->role ?? 'system' }}">
                                {{ ucfirst($log->user->role ?? 'System') }}
                            </span>
                        </td>
                        <td>
                            <span class="action-badge action-{{ strtolower($log->action) }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td>{{ $log->description ?? 'No details available' }}</td>
                        <td>{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h5>No audit logs found</h5>
                                <p>There are no audit logs to display at this time.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($auditLogs->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $auditLogs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
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
        --border-color: #e3e6f0;
        --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
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

    /* Stats Cards */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: var(--shadow);
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(78, 115, 223, 0.1) 0%, rgba(78, 115, 223, 0.05) 100%);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .stat-card h3 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .stat-card p {
        color: var(--dark-color);
        margin: 0.5rem 0 0 0;
        font-weight: 500;
        position: relative;
        z-index: 1;
    }

    .stat-card .stat-icon {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 2rem;
        color: rgba(78, 115, 223, 0.2);
        z-index: 1;
    }

    /* Table Controls */
    .table-controls {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .search-container {
        min-width: 250px;
    }

    .custom-search {
        border-radius: 8px;
        border: 1px solid var(--border-color);
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }

    .export-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Filter Controls */
    .filter-controls {
        display: flex;
        align-items: end;
        gap: 1rem;
        padding: 1.5rem;
        background: #f8f9fc;
        border-bottom: 1px solid var(--border-color);
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        min-width: 150px;
    }

    .filter-group label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-group select,
    .filter-group input {
        border-radius: 6px;
        border: 1px solid var(--border-color);
        padding: 0.5rem;
        font-size: 0.9rem;
        background: white;
    }

    .btn-filter,
    .btn-clear {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-filter {
        background: var(--primary-color);
        color: white;
    }

    .btn-filter:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    .btn-clear {
        background: #6c757d;
        color: white;
    }

    .btn-clear:hover {
        background: #5a6268;
        transform: translateY(-1px);
    }

    /* Role and Action Badges */
    .role-badge,
    .action-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .role-admin {
        background: rgba(78, 115, 223, 0.1);
        color: var(--primary-color);
    }

    .role-farmer {
        background: rgba(28, 200, 138, 0.1);
        color: var(--success-color);
    }

    .role-superadmin {
        background: rgba(231, 74, 59, 0.1);
        color: var(--danger-color);
    }

    .role-system {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }

    .action-login {
        background: rgba(28, 200, 138, 0.1);
        color: var(--success-color);
    }

    .action-update {
        background: rgba(54, 185, 204, 0.1);
        color: var(--info-color);
    }

    .action-delete {
        background: rgba(231, 74, 59, 0.1);
        color: var(--danger-color);
    }

    .action-create {
        background: rgba(28, 200, 138, 0.1);
        color: var(--success-color);
    }

    .action-export {
        background: rgba(246, 194, 62, 0.1);
        color: var(--warning-color);
    }

    /* Animation Classes */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    .slide-in {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .search-container {
            min-width: 100%;
        }

        .filter-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group {
            min-width: 100%;
        }

        .export-controls {
            margin-left: 0;
            margin-top: 1rem;
        }

        .stats-container {
            grid-template-columns: 1fr;
        }

        .table-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .card-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function applyFilters() {
    const roleFilter = document.getElementById('roleFilter').value;
    const actionFilter = document.getElementById('actionFilter').value;
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    
    // Here you would implement the actual filtering logic
    console.log('Applying filters:', { roleFilter, actionFilter, dateFrom, dateTo });
    alert('Filter functionality would be implemented here');
}

function clearFilters() {
    document.getElementById('roleFilter').value = '';
    document.getElementById('actionFilter').value = '';
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
}

function exportCSV() {
    alert('CSV export functionality would be implemented here');
}

function exportPDF() {
    alert('PDF export functionality would be implemented here');
}

function exportPNG() {
    alert('PNG export functionality would be implemented here');
}

function printTable() {
    window.print();
}
</script>
@endpush
