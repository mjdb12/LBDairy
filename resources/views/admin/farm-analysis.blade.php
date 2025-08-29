@extends('layouts.app')

@section('title', 'Farm Analysis')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-chart-line"></i>
            Farm Analysis
        </h1>
        <p>Comprehensive analysis of farm performance, productivity trends, and operational metrics</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container fade-in">
        <div class="stat-card">
            <i class="fas fa-tractor stat-icon"></i>
            <h3>{{ $totalFarms ?? 0 }}</h3>
            <p>Total Farms</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-chart-bar stat-icon"></i>
            <h3>{{ number_format($avgProduction ?? 0, 1) }}L</h3>
            <p>Avg Daily Production</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-trophy stat-icon"></i>
            <h3>{{ $topPerformingFarm ?? 'N/A' }}</h3>
            <p>Top Performing Farm</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-calendar-check stat-icon"></i>
            <h3>{{ $activeFarms ?? 0 }}</h3>
            <p>Active Farms</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row fade-in">
        <!-- Production Trend Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-area"></i>
                        Production Trend (Last 30 Days)
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <a class="dropdown-item" href="#" onclick="exportChart('production')">Export Chart</a>
                            <a class="dropdown-item" href="#" onclick="printChart('production')">Print Chart</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="productionChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>

        <!-- Farm Distribution Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie"></i>
                        Farm Distribution by Region
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="regionChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Farm Performance Table -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table"></i>
                Farm Performance Overview
            </h6>
            <div class="table-controls">
                <div class="search-container">
                    <input type="text" class="form-control custom-search" placeholder="Search farms...">
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
                            <a class="dropdown-item" href="#" onclick="exportPNG()">
                                <i class="fas fa-image"></i> PNG
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPDF()">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                        </div>
                    </div>
                    <button class="btn btn-secondary btn-sm" onclick="printTable()">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="farmDataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Farm ID</th>
                            <th>Farm Name</th>
                            <th>Owner</th>
                            <th>Location</th>
                            <th>Livestock Count</th>
                            <th>Daily Production</th>
                            <th>Performance Score</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($farms ?? [] as $farm)
                        <tr>
                            <td>
                                <a href="#" onclick="openFarmDetails('{{ $farm->id }}')">
                                    {{ $farm->farm_id ?? 'F' . str_pad($farm->id, 3, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <td>{{ $farm->name ?? 'N/A' }}</td>
                            <td>{{ $farm->owner_name ?? 'N/A' }}</td>
                            <td>{{ $farm->location ?? 'N/A' }}</td>
                            <td>{{ $farm->livestock_count ?? 0 }}</td>
                            <td>{{ number_format($farm->daily_production ?? 0, 1) }}L</td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    @php
                                        $score = $farm->performance_score ?? 0;
                                        $color = $score >= 80 ? 'success' : ($score >= 60 ? 'warning' : 'danger');
                                    @endphp
                                    <div class="progress-bar bg-{{ $color }}" role="progressbar" 
                                         style="width: {{ $score }}%" aria-valuenow="{{ $score }}" 
                                         aria-valuemin="0" aria-valuemax="100">
                                        {{ $score }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-{{ $farm->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($farm->status ?? 'inactive') }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-action-view" onclick="viewFarmDetails('{{ $farm->id }}')" title="View Details">
                                        <i class="fas fa-eye"></i>
                                        <span>View</span>
                                    </button>
                                    <button class="btn-action btn-action-edit" onclick="editFarm('{{ $farm->id }}')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                        <span>Edit</span>
                                    </button>
                                    <button class="btn-action btn-action-report" onclick="generateReport('{{ $farm->id }}')" title="Generate Report">
                                        <i class="fas fa-file-alt"></i>
                                        <span>Report</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                <i class="fas fa-info-circle"></i>
                                No farm data available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Regional Performance -->
    <div class="row fade-in">
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map-marker-alt"></i>
                        Regional Performance Comparison
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="regionalChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-trending-up"></i>
                        Growth Trends
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="growthChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Farm Details Modal -->
<div class="modal fade" id="farmDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-tractor"></i>
                    Farm Details
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="farmDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="generateDetailedReport()">Generate Report</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    initializeDataTable();
});

function initializeCharts() {
    // Production Trend Chart
    const productionCtx = document.getElementById('productionChart').getContext('2d');
    new Chart(productionCtx, {
        type: 'line',
        data: {
            labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
            datasets: [{
                label: 'Daily Production (L)',
                data: [65, 59, 80, 81, 56, 55, 40],
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
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
                    beginAtZero: true
                }
            }
        }
    });

    // Region Distribution Chart
    const regionCtx = document.getElementById('regionChart').getContext('2d');
    new Chart(regionCtx, {
        type: 'doughnut',
        data: {
            labels: ['North', 'South', 'East', 'West'],
            datasets: [{
                data: [30, 25, 25, 20],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Regional Performance Chart
    const regionalCtx = document.getElementById('regionalChart').getContext('2d');
    new Chart(regionalCtx, {
        type: 'bar',
        data: {
            labels: ['North', 'South', 'East', 'West'],
            datasets: [{
                label: 'Average Production (L)',
                data: [75, 68, 82, 71],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Growth Trends Chart
    const growthCtx = document.getElementById('growthChart').getContext('2d');
    new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Production Growth (%)',
                data: [0, 5, 12, 18, 25, 32],
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28, 200, 138, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function initializeDataTable() {
    $('#farmDataTable').DataTable({
        responsive: true,
        order: [[6, 'desc']], // Sort by performance score
        pageLength: 10,
        language: {
            search: "Search farms:",
            lengthMenu: "Show _MENU_ farms per page",
            info: "Showing _START_ to _END_ of _TOTAL_ farms"
        }
    });
}

function openFarmDetails(farmId) {
    // Load farm details via AJAX
    $.get(`/admin/farms/${farmId}/details`, function(data) {
        $('#farmDetailsContent').html(data);
        $('#farmDetailsModal').modal('show');
    });
}

function viewFarmDetails(farmId) {
    openFarmDetails(farmId);
}

function editFarm(farmId) {
    window.location.href = `/admin/farms/${farmId}/edit`;
}

function generateReport(farmId) {
    window.open(`/admin/farms/${farmId}/report`, '_blank');
}

function generateDetailedReport() {
    // Generate detailed report for the farm in modal
    const farmId = $('#farmDetailsModal').data('farm-id');
    window.open(`/admin/farms/${farmId}/detailed-report`, '_blank');
}

function exportCSV() {
    // Export table data to CSV
    const table = $('#farmDataTable').DataTable();
    const data = table.data().toArray();
    // Implementation for CSV export
}

function exportPNG() {
    // Export chart as PNG
    const canvas = document.getElementById('productionChart');
    const link = document.createElement('a');
    link.download = 'farm-analysis.png';
    link.href = canvas.toDataURL();
    link.click();
}

function exportPDF() {
    // Export as PDF
    window.print();
}

function printTable() {
    window.print();
}

function exportChart(chartType) {
    const canvas = document.getElementById(chartType + 'Chart');
    const link = document.createElement('a');
    link.download = `${chartType}-chart.png`;
    link.href = canvas.toDataURL();
    link.click();
}

function printChart(chartType) {
    const canvas = document.getElementById(chartType + 'Chart');
    const win = window.open();
    win.document.write('<html><head><title>Chart</title></head><body>');
    win.document.write('<img src="' + canvas.toDataURL() + '"/>');
    win.document.write('</body></html>');
    win.document.close();
    win.print();
}
</script>
@endsection
