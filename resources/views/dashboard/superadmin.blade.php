@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-crown"></i>
        Super Admin Dashboard
    </h1>
    <p>System-wide overview and management controls for the entire dairy management system.</p>
</div>

<!-- Statistics Grid -->
<div class="row fade-in">
    <!-- Total Admins -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Admins</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'admin')->count() }}</div>
                </div>
                <div class="icon text-primary">
                    <i class="fas fa-user-shield fa-2x"></i>
                </div>
            </div>
            <a href="#" class="card-footer text-primary small d-flex justify-content-between align-items-center">
                View Details
                <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Total Farmers -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Farmers</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'farmer')->count() }}</div>
                </div>
                <div class="icon text-success">
                    <i class="fas fa-users fa-2x"></i>
                </div>
            </div>
            <a href="#" class="card-footer text-success small d-flex justify-content-between align-items-center">
                View Details
                <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- New Requests -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">New Requests</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">19</div>
                </div>
                <div class="icon text-warning">
                    <i class="fas fa-user-plus fa-2x"></i>
                </div>
            </div>
            <a href="#" class="card-footer text-warning small d-flex justify-content-between align-items-center">
                Review Requests
                <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Service Areas Covered -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Service Areas</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">25</div>
                </div>
                <div class="icon text-danger">
                    <i class="fas fa-map-marker-alt fa-2x"></i>
                </div>
            </div>
            <a href="#" class="card-footer text-danger small d-flex justify-content-between align-items-center">
                View Coverage
                <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Row 2: To-Do List and Line Chart Side by Side -->
<div class="row fade-in">
    <!-- To-Do List -->
    <div class="col-12 col-lg-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <h6>
                    <i class="fas fa-tasks"></i>
                    Priority Tasks
                </h6>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
                    <li class="page-item active"><a href="#" class="page-link">1</a></li>
                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                    <li class="page-item"><a href="#" class="page-link">3</a></li>
                    <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
                </ul>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                        <div>
                            <input type="checkbox" id="todo1" class="mr-2">
                            <label for="todo1" class="mb-0">Make a report about the overall operation</label>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <span class="badge badge-danger mr-2"><i class="far fa-clock"></i> 2 hours</span>
                            <i class="fas fa-edit text-primary mr-2" role="button" title="Edit task"></i>
                            <i class="fas fa-trash text-danger" role="button" title="Delete task"></i>
                        </div>
                    </li>
                    <li class="list-group-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                        <div>
                            <input type="checkbox" id="todo2" class="mr-2" checked>
                            <label for="todo2" class="mb-0">Address Service Requests in Zone Y</label>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <span class="badge badge-info mr-2"><i class="far fa-clock"></i> 4 hours</span>
                            <i class="fas fa-edit text-primary mr-2" role="button" title="Edit task"></i>
                            <i class="fas fa-trash text-danger" role="button" title="Delete task"></i>
                        </div>
                    </li>
                    <li class="list-group-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                        <div>
                            <input type="checkbox" id="todo3" class="mr-2">
                            <label for="todo3" class="mb-0">Inspect Farm Performance Reports</label>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <span class="badge badge-warning mr-2"><i class="far fa-clock"></i> 1 day</span>
                            <i class="fas fa-edit text-primary mr-2" role="button" title="Edit task"></i>
                            <i class="fas fa-trash text-danger" role="button" title="Delete task"></i>
                        </div>
                    </li>
                    <li class="list-group-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                        <div>
                            <input type="checkbox" id="todo4" class="mr-2">
                            <label for="todo4" class="mb-0">Schedule Maintenance for Zone X</label>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <span class="badge badge-success mr-2"><i class="far fa-clock"></i> 3 days</span>
                            <i class="fas fa-edit text-primary mr-2" role="button" title="Edit task"></i>
                            <i class="fas fa-trash text-danger" role="button" title="Delete task"></i>
                        </div>
                    </li>
                    <li class="list-group-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                        <div>
                            <input type="checkbox" id="todo5" class="mr-2">
                            <label for="todo5" class="mb-0">Check your messages and notifications</label>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <span class="badge badge-primary mr-2"><i class="far fa-clock"></i> 1 week</span>
                            <i class="fas fa-edit text-primary mr-2" role="button" title="Edit task"></i>
                            <i class="fas fa-trash text-danger" role="button" title="Delete task"></i>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Line Chart -->
    <div class="col-12 col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h6>
                    <i class="fas fa-chart-line"></i>
                    Livestock Population Trends
                </h6>
            </div>
            <div class="card-body">
                <canvas id="lineChart" height="135"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- System Overview -->
<div class="row fade-in-up mt-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-database"></i>
                    System Overview
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-server fa-3x text-primary mb-2"></i>
                            <h5>{{ \App\Models\Farm::count() }}</h5>
                            <small class="text-muted">Total Farms</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-cow fa-3x text-success mb-2"></i>
                            <h5>{{ \App\Models\Livestock::count() }}</h5>
                            <small class="text-muted">Total Livestock</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-chart-bar fa-3x text-info mb-2"></i>
                            <h5>{{ \App\Models\ProductionRecord::count() }}</h5>
                            <small class="text-muted">Production Records</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-2"></i>
                            <h5>{{ \App\Models\Issue::where('status', 'open')->count() }}</h5>
                            <small class="text-muted">Open Issues</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent System Activity -->
<div class="row fade-in-up mt-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history"></i>
                    Recent System Activity
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>User</th>
                                <th>Details</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\AuditLog::latest()->take(15)->get() as $log)
                            <tr>
                                <td>{{ $log->action }}</td>
                                <td>{{ $log->user->name ?? 'System' }}</td>
                                <td>{{ Str::limit($log->details ?? 'No details', 50) }}</td>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize chart with enhanced styling
    const ctxLine = document.getElementById('lineChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Cattle',
                data: [65, 59, 80, 81, 56, 55],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Goats',
                data: [28, 48, 40, 19, 86, 27],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4,
                fill: true
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
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            }
        }
    });
});
</script>
@endpush
