@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers - Livestock Analysis')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-database"></i>
        Livestock Analysis
    </h1>
    <p>Detailed analysis of livestock performance, health, and productivity metrics</p>
</div>

<div class="row">
    <!-- Livestock Overview Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 fade-in">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Livestock</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">156</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-cow fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2 fade-in">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Healthy Animals</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">142</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-heart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2 fade-in">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Breeding Age</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">89</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-heart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 fade-in">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Under Treatment</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">5</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-stethoscope fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Livestock Performance Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Livestock Performance Trends</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Chart Options:</div>
                        <a class="dropdown-item" href="#">View Details</a>
                        <a class="dropdown-item" href="#">Export Data</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Print Chart</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="livestockPerformanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Livestock Health Distribution -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Health Status Distribution</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Chart Options:</div>
                        <a class="dropdown-item" href="#">View Details</a>
                        <a class="dropdown-item" href="#">Export Data</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Print Chart</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="healthStatusChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> Healthy
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-warning"></i> Under Treatment
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-danger"></i> Critical
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Livestock Details Table -->
    <div class="col-lg-12">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Livestock Performance Summary</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Livestock ID</th>
                                <th>Type</th>
                                <th>Age</th>
                                <th>Health Score</th>
                                <th>Production (L/day)</th>
                                <th>Weight (kg)</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>LS001</td>
                                <td>Dairy Cow</td>
                                <td>3 years</td>
                                <td>95%</td>
                                <td>25.5</td>
                                <td>450</td>
                                <td><span class="badge badge-success">Healthy</span></td>
                                <td><button class="btn btn-sm btn-outline-primary">View Details</button></td>
                            </tr>
                            <tr>
                                <td>LS002</td>
                                <td>Dairy Cow</td>
                                <td>4 years</td>
                                <td>88%</td>
                                <td>22.0</td>
                                <td>480</td>
                                <td><span class="badge badge-warning">Under Treatment</span></td>
                                <td><button class="btn btn-sm btn-outline-warning">View Details</button></td>
                            </tr>
                            <tr>
                                <td>LS003</td>
                                <td>Goat</td>
                                <td>2 years</td>
                                <td>92%</td>
                                <td>3.2</td>
                                <td>45</td>
                                <td><span class="badge badge-success">Healthy</span></td>
                                <td><button class="btn btn-sm btn-outline-primary">View Details</button></td>
                            </tr>
                            <tr>
                                <td>LS004</td>
                                <td>Carabao</td>
                                <td>5 years</td>
                                <td>78%</td>
                                <td>8.5</td>
                                <td>650</td>
                                <td><span class="badge badge-info">Recovering</span></td>
                                <td><button class="btn btn-sm btn-outline-info">View Details</button></td>
                            </tr>
                            <tr>
                                <td>LS005</td>
                                <td>Dairy Cow</td>
                                <td>2 years</td>
                                <td>96%</td>
                                <td>28.0</td>
                                <td>420</td>
                                <td><span class="badge badge-success">Healthy</span></td>
                                <td><button class="btn btn-sm btn-outline-primary">View Details</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Breeding Analysis Section -->
<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-heart"></i>
                    Breeding Analysis
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="breedingChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> Pregnant
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-info"></i> Ready to Breed
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-warning"></i> Under Observation
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-line"></i>
                    Growth Trends
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Health Recommendations -->
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-lightbulb"></i>
                    Health & Performance Recommendations
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-heart"></i> Breeding Optimization</h6>
                            <p class="mb-0">89 animals are at breeding age. Consider implementing a structured breeding program to maximize genetic potential.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-stethoscope"></i> Health Monitoring</h6>
                            <p class="mb-0">5 animals are under treatment. Schedule follow-up examinations and maintain treatment protocols.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-success">
                            <h6><i class="fas fa-chart-line"></i> Performance Improvement</h6>
                            <p class="mb-0">Overall health score is 91%. Continue current vaccination and nutrition programs for optimal performance.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-primary">
                            <h6><i class="fas fa-weight"></i> Weight Management</h6>
                            <p class="mb-0">Monitor weight trends and adjust feeding programs to maintain optimal body condition scores.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Livestock Performance Chart
    const performanceCtx = document.getElementById('livestockPerformanceChart').getContext('2d');
    const performanceChart = new Chart(performanceCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Average Milk Production (L/day)',
                data: [22.5, 23.1, 24.2, 23.8, 24.5, 25.1, 24.8, 25.3, 25.7, 25.2, 24.9, 25.5],
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }, {
                label: 'Health Score (%)',
                data: [88, 89, 90, 89, 91, 92, 91, 93, 92, 94, 93, 95],
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28, 200, 138, 0.05)',
                borderWidth: 2,
                fill: false,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Health Status Chart
    const healthCtx = document.getElementById('healthStatusChart').getContext('2d');
    const healthChart = new Chart(healthCtx, {
        type: 'doughnut',
        data: {
            labels: ['Healthy', 'Under Treatment', 'Critical'],
            datasets: [{
                data: [142, 5, 2],
                backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
                hoverBackgroundColor: ['#17a673', '#f4b619', '#e02424'],
                hoverBorderColor: 'rgba(234, 236, 244, 1)',
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
            cutout: '70%'
        }
    });

    // Breeding Chart
    const breedingCtx = document.getElementById('breedingChart').getContext('2d');
    const breedingChart = new Chart(breedingCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pregnant', 'Ready to Breed', 'Under Observation'],
            datasets: [{
                data: [45, 32, 12],
                backgroundColor: ['#1cc88a', '#36b9cc', '#f6c23e'],
                hoverBackgroundColor: ['#17a673', '#2c9faf', '#f4b619'],
                hoverBorderColor: 'rgba(234, 236, 244, 1)',
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
            cutout: '70%'
        }
    });

    // Growth Chart
    const growthCtx = document.getElementById('growthChart').getContext('2d');
    const growthChart = new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: ['Month 1', 'Month 2', 'Month 3', 'Month 4', 'Month 5', 'Month 6'],
            datasets: [{
                label: 'Average Weight (kg)',
                data: [350, 380, 410, 440, 470, 500],
                borderColor: '#f6c23e',
                backgroundColor: 'rgba(246, 194, 62, 0.05)',
                borderWidth: 2,
                fill: true,
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
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
@endpush
