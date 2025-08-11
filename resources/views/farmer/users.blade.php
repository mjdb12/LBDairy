@extends('layouts.app')

@section('title', 'LBDAIRY: Farm Dashboard')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-tachometer-alt"></i>
        Farm Dashboard
    </h1>
    <p>Monitor your farm operations, livestock, and productivity at a glance</p>
</div>

<section class="content">
    <div class="row">
        <div class="col-12">
            <!-- Enhanced Stat Boxes Row -->
            <div class="row fade-in">
                <!-- Active Farms -->
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card stat-card border-left-info shadow h-100 py-2">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Active Farms</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">23</div>
                            </div>
                            <div class="icon text-info">
                                <i class="fas fa-university fa-2x"></i>
                            </div>
                        </div>
                        <a href="#" class="card-footer text-info small d-flex justify-content-between align-items-center">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- Efficiency Rate -->
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card stat-card border-left-success shadow h-100 py-2">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Efficiency Rate</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">53<sup>%</sup></div>
                            </div>
                            <div class="icon text-success">
                                <i class="fas fa-chart-line fa-2x"></i>
                            </div>
                        </div>
                        <a href="#" class="card-footer text-success small d-flex justify-content-between align-items-center">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- New Requests -->
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card stat-card border-left-warning shadow h-100 py-2">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">New Requests</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">19</div>
                            </div>
                            <div class="icon text-warning">
                                <i class="fas fa-clipboard-list fa-2x"></i>
                            </div>
                        </div>
                        <a href="#" class="card-footer text-warning small d-flex justify-content-between align-items-center">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- Service Areas Covered -->
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card stat-card border-left-danger shadow h-100 py-2">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Service Areas Covered</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">25</div>
                            </div>
                            <div class="icon text-danger">
                                <i class="fas fa-map-marker-alt fa-2x"></i>
                            </div>
                        </div>
                        <a href="#" class="card-footer text-danger small d-flex justify-content-between align-items-center">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- End Enhanced Stat Boxes Row -->

            <!-- Row 2: Enhanced To-Do List and Line Chart Side by Side -->
            <div class="row fade-in">
                <!-- Enhanced To-Do List -->
                <div class="col-12 col-lg-6 mb-4">
                    <div class="card todo-card shadow-sm">
                        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <h6 class="m-0 font-weight-bold mb-2 mb-md-0">
                                <i class="fas fa-tasks mr-2"></i> Today's Tasks
                            </h6>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
                                <li class="page-item"><a href="#" class="page-link">1</a></li>
                                <li class="page-item"><a href="#" class="page-link">2</a></li>
                                <li class="page-item"><a href="#" class="page-link">3</a></li>
                                <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" id="todo1" class="mr-2">
                                        <label for="todo1" class="mb-0">Make a report about the overall operation</label>
                                    </div>
                                    <div class="mt-2 mt-md-0 d-flex align-items-center">
                                        <span class="badge badge-danger mr-2"><i class="far fa-clock"></i> 2 hours</span>
                                        <i class="fas fa-edit text-primary mr-2" role="button" title="Edit"></i>
                                        <i class="fas fa-trash text-danger" role="button" title="Delete"></i>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" id="todo2" class="mr-2" checked>
                                        <label for="todo2" class="mb-0">Address Service Requests in Zone Y</label>
                                    </div>
                                    <div class="mt-2 mt-md-0 d-flex align-items-center">
                                        <span class="badge badge-info mr-2"><i class="far fa-clock"></i> 4 hours</span>
                                        <i class="fas fa-edit text-primary mr-2" role="button" title="Edit"></i>
                                        <i class="fas fa-trash text-danger" role="button" title="Delete"></i>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" id="todo3" class="mr-2">
                                        <label for="todo3" class="mb-0">Inspect Farm Performance Reports</label>
                                    </div>
                                    <div class="mt-2 mt-md-0 d-flex align-items-center">
                                        <span class="badge badge-warning mr-2"><i class="far fa-clock"></i> 1 day</span>
                                        <i class="fas fa-edit text-primary mr-2" role="button" title="Edit"></i>
                                        <i class="fas fa-trash text-danger" role="button" title="Delete"></i>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" id="todo4" class="mr-2">
                                        <label for="todo4" class="mb-0">Schedule Maintenance for Zone X</label>
                                    </div>
                                    <div class="mt-2 mt-md-0 d-flex align-items-center">
                                        <span class="badge badge-success mr-2"><i class="far fa-clock"></i> 3 days</span>
                                        <i class="fas fa-edit text-primary mr-2" role="button" title="Edit"></i>
                                        <i class="fas fa-trash text-danger" role="button" title="Delete"></i>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" id="todo5" class="mr-2">
                                        <label for="todo5" class="mb-0">Check your messages and notifications</label>
                                    </div>
                                    <div class="mt-2 mt-md-0 d-flex align-items-center">
                                        <span class="badge badge-primary mr-2"><i class="far fa-clock"></i> 1 week</span>
                                        <i class="fas fa-edit text-primary mr-2" role="button" title="Edit"></i>
                                        <i class="fas fa-trash text-danger" role="button" title="Delete"></i>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Enhanced Line Chart -->
                <div class="col-12 col-lg-6 mb-4">
                    <div class="card chart-card shadow mb-4">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-chart-line mr-2"></i> Livestock Population Trends
                            </h6>
                        </div>
                        <div class="card-body">
                            <canvas id="lineChart" height="280"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

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

