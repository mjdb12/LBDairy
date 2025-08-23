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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLivestock }}</div>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($monthlyProduction) }} L</div>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($monthlyRevenue) }}</div>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeIssues }}</div>
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
    <div class="col-12">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Farm Performance Summary</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width: 25%;">Metric</th>
                                <th style="width: 20%;">Current Month</th>
                                <th style="width: 20%;">Previous Month</th>
                                <th style="width: 20%;">Change</th>
                                <th style="width: 15%;">Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Milk Production (L)</td>
                                <td>{{ number_format($performanceMetrics['milk_production']['current']) }}</td>
                                <td>{{ number_format($performanceMetrics['milk_production']['previous']) }}</td>
                                <td class="{{ $performanceMetrics['milk_production']['change'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $performanceMetrics['milk_production']['change'] >= 0 ? '+' : '' }}{{ $performanceMetrics['milk_production']['change'] }}%
                                </td>
                                <td class="text-center">
                                    @if($performanceMetrics['milk_production']['change'] >= 0)
                                        <i class="fas fa-arrow-up text-success"></i>
                                    @else
                                        <i class="fas fa-arrow-down text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Feed Consumption (₱)</td>
                                <td>₱{{ number_format($performanceMetrics['feed_consumption']['current']) }}</td>
                                <td>₱{{ number_format($performanceMetrics['feed_consumption']['previous']) }}</td>
                                <td class="{{ $performanceMetrics['feed_consumption']['change'] <= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $performanceMetrics['feed_consumption']['change'] >= 0 ? '+' : '' }}{{ $performanceMetrics['feed_consumption']['change'] }}%
                                </td>
                                <td class="text-center">
                                    @if($performanceMetrics['feed_consumption']['change'] <= 0)
                                        <i class="fas fa-arrow-down text-success"></i>
                                    @else
                                        <i class="fas fa-arrow-up text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Veterinary Costs (₱)</td>
                                <td>₱{{ number_format($performanceMetrics['veterinary_costs']['current']) }}</td>
                                <td>₱{{ number_format($performanceMetrics['veterinary_costs']['previous']) }}</td>
                                <td class="{{ $performanceMetrics['veterinary_costs']['change'] <= 0 ? 'text-success' : 'text-warning' }}">
                                    {{ $performanceMetrics['veterinary_costs']['change'] >= 0 ? '+' : '' }}{{ $performanceMetrics['veterinary_costs']['change'] }}%
                                </td>
                                <td class="text-center">
                                    @if($performanceMetrics['veterinary_costs']['change'] <= 0)
                                        <i class="fas fa-arrow-down text-success"></i>
                                    @else
                                        <i class="fas fa-arrow-up text-warning"></i>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Livestock Health Score</td>
                                <td>{{ $performanceMetrics['health_score']['current'] }}%</td>
                                <td>{{ $performanceMetrics['health_score']['previous'] }}%</td>
                                <td class="{{ $performanceMetrics['health_score']['change'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $performanceMetrics['health_score']['change'] >= 0 ? '+' : '' }}{{ $performanceMetrics['health_score']['change'] }}%
                                </td>
                                <td class="text-center">
                                    @if($performanceMetrics['health_score']['change'] >= 0)
                                        <i class="fas fa-arrow-up text-success"></i>
                                    @else
                                        <i class="fas fa-arrow-down text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Breeding Success Rate</td>
                                <td>{{ $performanceMetrics['breeding_success']['current'] }}%</td>
                                <td>{{ $performanceMetrics['breeding_success']['previous'] }}%</td>
                                <td class="{{ $performanceMetrics['breeding_success']['change'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $performanceMetrics['breeding_success']['change'] >= 0 ? '+' : '' }}{{ $performanceMetrics['breeding_success']['change'] }}%
                                </td>
                                <td class="text-center">
                                    @if($performanceMetrics['breeding_success']['change'] >= 0)
                                        <i class="fas fa-arrow-up text-success"></i>
                                    @else
                                        <i class="fas fa-arrow-down text-danger"></i>
                                    @endif
                                </td>
                            </tr>
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
document.addEventListener('DOMContentLoaded', function() {
    // Production Chart
    const productionCtx = document.getElementById('productionChart').getContext('2d');
    const productionChart = new Chart(productionCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($productionData)) !!},
            datasets: [{
                label: 'Milk Production (L)',
                data: {!! json_encode(array_values($productionData)) !!},
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
            labels: {!! json_encode(array_keys($livestockDistribution)) !!},
            datasets: [{
                data: {!! json_encode(array_values($livestockDistribution)) !!},
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#f4b619', '#e74a3b'],
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
