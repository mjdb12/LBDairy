@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-tachometer-alt"></i>
        Admin Dashboard
    </h1>
    <p>Welcome back! Here's what's happening with your dairy management system today.</p>
</div>

<!-- Statistics Grid -->
<div class="row fade-in">
    <!-- Total Farmers -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card border-left-primary">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title text-primary">Total Farmers</div>
                    <div class="stat-value">{{ \App\Models\User::where('role', 'farmer')->count() }}</div>
                    <div class="mt-2">
                        <span class="text-success font-weight-bold small">
                            <i class="fas fa-arrow-up mr-1"></i>+12%
                        </span>
                        <span class="text-muted small ml-1">from last month</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-footer">
                <a href="#" class="d-flex justify-content-between align-items-center">
                    <span>More info</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Total Livestock -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card border-left-success">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title text-success">Total Livestock</div>
                    <div class="stat-value">{{ \App\Models\Livestock::count() }}</div>
                    <div class="mt-2">
                        <span class="text-success font-weight-bold small">
                            <i class="fas fa-arrow-up mr-1"></i>+5%
                        </span>
                        <span class="text-muted small ml-1">from last month</span>
                    </div>
                </div>
                <div class="stat-icon">
                                                            <i class="fas fa-paw"></i>
                </div>
            </div>
            <div class="stat-footer">
                <a href="#" class="d-flex justify-content-between align-items-center">
                    <span>More info</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- New Requests -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card border-left-warning">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title text-warning">New Requests</div>
                    <div class="stat-value">{{ \App\Models\Issue::where('status', 'pending')->count() }}</div>
                    <div class="mt-2">
                        <span class="text-danger font-weight-bold small">
                            <i class="fas fa-arrow-down mr-1"></i>-8%
                        </span>
                        <span class="text-muted small ml-1">from last month</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
            </div>
            <div class="stat-footer">
                <a href="#" class="d-flex justify-content-between align-items-center">
                    <span>More info</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Service Areas -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card border-left-danger">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title text-danger">Service Areas</div>
                    <div class="stat-value">{{ \App\Models\Farm::distinct('location')->count() }}</div>
                    <div class="mt-2">
                        <span class="text-success font-weight-bold small">
                            <i class="fas fa-arrow-up mr-1"></i>+3%
                        </span>
                        <span class="text-muted small ml-1">from last month</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
            </div>
            <div class="stat-footer">
                <a href="#" class="d-flex justify-content-between align-items-center">
                    <span>More info</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row fade-in-up mb-4">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="d-flex align-items-center">
                    <i class="fas fa-bolt"></i>
                    <span>Quick Actions</span>
                </div>
            </div>
            <div class="modern-card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('admin.livestock.index') }}" class="quick-action-card">
                            <div class="quick-action-icon">
                                <i class="fas fa-cow"></i>
                            </div>
                            <div class="quick-action-text">
                                <h6>Manage Livestock</h6>
                                <p>View and manage livestock inventory</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('admin.issues.index') }}" class="quick-action-card">
                            <div class="quick-action-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="quick-action-text">
                                <h6>Manage Issues</h6>
                                <p>Track and resolve farm issues</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('admin.analysis.index') }}" class="quick-action-card">
                            <div class="quick-action-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="quick-action-text">
                                <h6>Productivity Analysis</h6>
                                <p>Analyze farm performance metrics</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('admin.farms.index') }}" class="quick-action-card">
                            <div class="quick-action-icon">
                                <i class="fas fa-university"></i>
                            </div>
                            <div class="quick-action-text">
                                <h6>Manage Farms</h6>
                                <p>Monitor and manage farm operations</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('admin.manage-farmers') }}" class="quick-action-card">
                            <div class="quick-action-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="quick-action-text">
                                <h6>Manage Farmers</h6>
                                <p>Oversee farmer accounts and farms</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="row fade-in-up">
    <!-- To-Do List -->
    <div class="col-12 col-xl-6">
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="d-flex align-items-center">
                    <i class="fas fa-tasks"></i>
                    <span>To-Do List</span>
                </div>
                <button class="btn btn-sm btn-light">
                    <i class="fas fa-plus mr-1"></i>Add Task
                </button>
            </div>
            <div class="modern-card-body">
                <!-- Todo Items -->
                <div class="todo-item">
                    <div class="todo-content">
                        <input type="checkbox" class="todo-checkbox form-check-input">
                        <span class="todo-text">Make a report about the overall operation</span>
                    </div>
                    <div class="todo-actions">
                        <span class="todo-badge badge-danger">
                            <i class="far fa-clock"></i>2 hours
                        </span>
                        <button class="action-btn">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <div class="todo-item">
                    <div class="todo-content">
                        <input type="checkbox" class="todo-checkbox form-check-input" checked>
                        <span class="todo-text text-decoration-line-through text-muted">Address Service Requests in Zone Y</span>
                    </div>
                    <div class="todo-actions">
                        <span class="todo-badge badge-info">
                            <i class="far fa-clock"></i>4 hours
                        </span>
                        <button class="action-btn">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <div class="todo-item">
                    <div class="todo-content">
                        <input type="checkbox" class="todo-checkbox form-check-input">
                        <span class="todo-text">Inspect Farm Performance Reports</span>
                    </div>
                    <div class="todo-actions">
                        <span class="todo-badge badge-warning">
                            <i class="far fa-clock"></i>1 day
                        </span>
                        <button class="action-btn">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <div class="todo-item">
                    <div class="todo-content">
                        <input type="checkbox" class="todo-checkbox form-check-input">
                        <span class="todo-text">Schedule Maintenance for Zone X</span>
                    </div>
                    <div class="todo-actions">
                        <span class="todo-badge badge-success">
                            <i class="far fa-clock"></i>3 days
                        </span>
                        <button class="action-btn">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <div class="todo-item">
                    <div class="todo-content">
                        <input type="checkbox" class="todo-checkbox form-check-input">
                        <span class="todo-text">Check your messages and notifications</span>
                    </div>
                    <div class="todo-actions">
                        <span class="todo-badge badge-primary">
                            <i class="far fa-clock"></i>1 week
                        </span>
                        <button class="action-btn">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Line Chart -->
    <div class="col-12 col-xl-6">
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="d-flex align-items-center">
                    <i class="fas fa-chart-line"></i>
                    <span>Livestock Population Trends</span>
                </div>
            </div>
            <div class="modern-card-body">
                <div class="chart-container">
                    <canvas id="lineChart"></canvas>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <div class="d-flex align-items-center mr-4">
                        <div class="rounded-circle mr-2" style="width: 12px; height: 12px; background: #007bff;"></div>
                        <span class="small text-muted">Cattle</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle mr-2" style="width: 12px; height: 12px; background: #28a745;"></div>
                        <span class="small text-muted">Goats</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row fade-in-up mt-4">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="d-flex align-items-center">
                    <i class="fas fa-history"></i>
                    <span>Recent Activity</span>
                </div>
            </div>
            <div class="modern-card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>User</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\AuditLog::latest()->take(10)->get() as $log)
                            <tr>
                                <td>{{ $log->action }}</td>
                                <td>{{ $log->user->name ?? 'System' }}</td>
                                <td>{{ $log->created_at->diffForHumans() }}</td>
                                <td><span class="badge badge-success">Completed</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .quick-action-card {
        display: block;
        padding: 1.5rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
        border: 1px solid #e3e6f0;
    }

    .quick-action-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        text-decoration: none;
        color: inherit;
    }

    .quick-action-icon {
        text-align: center;
        margin-bottom: 1rem;
    }

    .quick-action-icon i {
        font-size: 2.5rem;
        color: #4e73df;
        background: rgba(78, 115, 223, 0.1);
        width: 80px;
        height: 80px;
        line-height: 80px;
        border-radius: 50%;
        display: inline-block;
    }

    .quick-action-text {
        text-align: center;
    }

    .quick-action-text h6 {
        margin: 0 0 0.5rem 0;
        font-weight: 600;
        color: #5a5c69;
    }

    .quick-action-text p {
        margin: 0;
        font-size: 0.875rem;
        color: #858796;
        line-height: 1.4;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Line Chart
    const ctx = document.getElementById('lineChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Cattle',
                data: [65, 59, 80, 81, 56, 55],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            }, {
                label: 'Goats',
                data: [28, 48, 40, 19, 86, 27],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
