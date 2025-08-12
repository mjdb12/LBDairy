@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers - Farm Analysis')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-database"></i>
        Farm Analysis
    </h1>
    <p>Comprehensive analysis of farm performance and productivity metrics</p>
</div>

<div class="row">
    <!-- Key Performance Indicators -->
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
                            Monthly Production</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">2,450 L</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tint fa-2x text-gray-300"></i>
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
                            Revenue This Month</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₱45,200</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                            Active Issues</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Production Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Monthly Production Trend</h6>
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
                    <canvas id="productionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Livestock Distribution -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Livestock Distribution</h6>
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
                    <canvas id="livestockChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i> Dairy Cows
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> Goats
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-info"></i> Carabaos
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Farm Performance Table -->
    <div class="col-lg-12">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Farm Performance Summary</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Metric</th>
                                <th>Current Month</th>
                                <th>Previous Month</th>
                                <th>Change</th>
                                <th>Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Milk Production (L)</td>
                                <td>2,450</td>
                                <td>2,180</td>
                                <td class="text-success">+12.4%</td>
                                <td><i class="fas fa-arrow-up text-success"></i></td>
                            </tr>
                            <tr>
                                <td>Feed Consumption (kg)</td>
                                <td>1,850</td>
                                <td>1,920</td>
                                <td class="text-success">-3.6%</td>
                                <td><i class="fas fa-arrow-down text-success"></i></td>
                            </tr>
                            <tr>
                                <td>Veterinary Costs (₱)</td>
                                <td>8,500</td>
                                <td>7,200</td>
                                <td class="text-warning">+18.1%</td>
                                <td><i class="fas fa-arrow-up text-warning"></i></td>
                            </tr>
                            <tr>
                                <td>Livestock Health Score</td>
                                <td>92%</td>
                                <td>89%</td>
                                <td class="text-success">+3.4%</td>
                                <td><i class="fas fa-arrow-up text-success"></i></td>
                            </tr>
                            <tr>
                                <td>Breeding Success Rate</td>
                                <td>78%</td>
                                <td>75%</td>
                                <td class="text-success">+4.0%</td>
                                <td><i class="fas fa-arrow-up text-success"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recommendations Section -->
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-lightbulb"></i>
                    Recommendations
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-chart-line"></i> Production Optimization</h6>
                            <p class="mb-0">Consider adjusting feeding schedules during peak production periods to maximize milk yield.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle"></i> Cost Management</h6>
                            <p class="mb-0">Veterinary costs have increased. Review preventive care strategies to reduce emergency treatments.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-success">
                            <h6><i class="fas fa-heart"></i> Health Improvement</h6>
                            <p class="mb-0">Livestock health score is improving. Continue current vaccination and nutrition programs.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-primary">
                            <h6><i class="fas fa-seedling"></i> Feed Efficiency</h6>
                            <p class="mb-0">Feed consumption has decreased while production increased. This indicates improved feed efficiency.</p>
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
    // Production Chart
    const productionCtx = document.getElementById('productionChart').getContext('2d');
    const productionChart = new Chart(productionCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Milk Production (L)',
                data: [2100, 1950, 2200, 2350, 2180, 2450, 2300, 2400, 2250, 2350, 2180, 2450],
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
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

    // Livestock Distribution Chart
    const livestockCtx = document.getElementById('livestockChart').getContext('2d');
    const livestockChart = new Chart(livestockCtx, {
        type: 'doughnut',
        data: {
            labels: ['Dairy Cows', 'Goats', 'Carabaos'],
            datasets: [{
                data: [65, 25, 10],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
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
});
</script>
@endpush
