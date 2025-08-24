@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers - Livestock Analysis')

@section('styles')
<style>
    .avatar-sm {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
    }
    
    .table thead th {
        background-color: #f8f9fc;
        border-top: none;
        border-bottom: 2px solid #e3e6f0;
        font-weight: 600;
        color: #5a5c69;
        padding: 1rem 0.75rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .table tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
        transform: scale(1.001);
    }
    
    .table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border-top: 1px solid #f0f0f0;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.5rem 0.75rem;
        border-radius: 12px;
    }
    
    .btn-group .btn {
        margin-right: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .search-container {
        position: relative;
        min-width: 250px;
    }
    
    .search-container input {
        border-radius: 25px;
        border: 2px solid transparent;
        background: rgba(255, 255, 255, 0.9);
        padding: 0.6rem 1rem 0.6rem 2.5rem;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    
    .search-container input:focus {
        border-color: #4e73df;
        background: white;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    
    .search-container::before {
        content: '\f002';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        z-index: 2;
    }
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-line text-primary"></i>
                Livestock Analysis Dashboard
            </h1>
            <p class="text-muted mb-0">Comprehensive analysis of livestock performance and health metrics</p>
        </div>

    </div>
</div>

<!-- Statistics Cards Row -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-primary shadow-sm h-100">
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

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-success shadow-sm h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Healthy Animals</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $healthyAnimals }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-heart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-info shadow-sm h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Breeding Age</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $breedingAge }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-baby fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-warning shadow-sm h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Under Treatment</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $underTreatment }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-stethoscope fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-xl-8 col-lg-7 mb-3">
        <div class="card shadow-sm">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-line"></i>
                    Performance Trends
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="position: relative; height: 320px;">
                    <canvas id="livestockPerformanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5 mb-3">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-pie"></i>
                    Health Distribution
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-pie" style="position: relative; height: 280px;">
                    <canvas id="healthStatusChart"></canvas>
                </div>
                <div class="mt-3 text-center small">
                    <span class="mr-3">
                        <i class="fas fa-circle text-success"></i> Healthy
                    </span>
                    <span class="mr-3">
                        <i class="fas fa-circle text-warning"></i> Under Treatment
                    </span>
                    <span class="mr-3">
                        <i class="fas fa-circle text-danger"></i> Critical
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Livestock Analysis Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-list-alt"></i>
            Livestock Analysis Overview
        </h6>
        <div class="d-flex align-items-center">
            <div class="mr-3 search-container">
                <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search livestock...">
            </div>
            <div class="btn-group mr-2">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                    <i class="fas fa-download"></i> Export
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#" onclick="exportTableCSV()">
                        <i class="fas fa-file-csv"></i> CSV
                    </a>
                    <a class="dropdown-item" href="#" onclick="exportTablePDF()">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    <a class="dropdown-item" href="#" onclick="exportTablePNG()">
                        <i class="fas fa-image"></i> PNG
                    </a>
                </div>
            </div>
            <button class="btn btn-secondary btn-sm" onclick="printTable()">
                <i class="fas fa-print"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="livestockTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th><i class="fas fa-tag mr-1"></i>Livestock ID</th>
                        <th><i class="fas fa-paw mr-1"></i>Name</th>
                        <th><i class="fas fa-dna mr-1"></i>Breed</th>
                        <th><i class="fas fa-calendar mr-1"></i>Age (months)</th>
                        <th><i class="fas fa-heartbeat mr-1"></i>Health Score</th>
                        <th><i class="fas fa-tint mr-1"></i>Avg. Production (L/day)</th>
                        <th><i class="fas fa-venus-mars mr-1"></i>Breeding Status</th>
                        <th><i class="fas fa-chart-bar mr-1"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($livestockData ?? [] as $animal)
                    <tr class="livestock-row" data-status="{{ $animal['health_status'] ?? 'healthy' }}" data-type="{{ $animal['breed'] ?? 'unknown' }}">
                        <td><strong>{{ $animal['livestock_id'] ?? 'LS' . str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center mr-2">
                                    <i class="fas fa-cow text-white"></i>
                                </div>
                                {{ $animal['type'] ?? 'Livestock ' . $loop->iteration }}
                            </div>
                        </td>
                        <td><span class="badge badge-info">{{ $animal['breed'] ?? 'Holstein' }}</span></td>
                        <td>{{ $animal['age'] ?? rand(12, 60) }}</td>
                        <td>
                            @php
                                $healthScore = $animal['health_score'] ?? rand(85, 100);
                                $healthClass = $healthScore >= 90 ? 'success' : ($healthScore >= 80 ? 'warning' : 'danger');
                            @endphp
                            <span class="badge badge-{{ $healthClass }}">{{ $healthScore }}%</span>
                        </td>
                        <td>{{ $animal['avg_production'] ?? rand(15, 25) }}L</td>
                        <td>
                            @php
                                $breedingStatus = $animal['health_status'] === 'healthy' ? 'Active' : ($animal['health_status'] === 'under_treatment' ? 'Under Treatment' : 'Inactive');
                                $statusClass = $breedingStatus === 'Active' ? 'success' : ($breedingStatus === 'Under Treatment' ? 'warning' : 'secondary');
                            @endphp
                            <span class="badge badge-{{ $statusClass }}">{{ $breedingStatus }}</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-info btn-sm" onclick="viewLivestockAnalysis('{{ $animal['id'] ?? $loop->iteration }}')">
                                    <i class="fas fa-chart-line mr-1"></i>Analysis
                                </button>
                                <button class="btn btn-warning btn-sm" onclick="viewLivestockHistory('{{ $animal['id'] ?? $loop->iteration }}')">
                                    <i class="fas fa-history mr-1"></i>History
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            <div class="empty-state">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5>No livestock data available</h5>
                                <p class="text-muted">Add livestock to see analysis data here.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Individual Livestock Analysis Modal -->
<div class="modal fade" id="livestockAnalysisModal" tabindex="-1" role="dialog" aria-labelledby="livestockAnalysisLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="livestockAnalysisLabel">
                    <i class="fas fa-chart-line"></i>
                    Individual Livestock Analysis
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="livestockAnalysisContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" onclick="printLivestockAnalysis()">
                    <i class="fas fa-print"></i> Print Analysis
                </button>
                <button type="button" class="btn btn-primary" onclick="exportLivestockAnalysis()">
                    <i class="fas fa-download"></i> Export Data
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Livestock History Modal -->
<div class="modal fade" id="livestockHistoryModal" tabindex="-1" role="dialog" aria-labelledby="livestockHistoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="livestockHistoryLabel">
                    <i class="fas fa-history"></i>
                    Livestock History
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="livestockHistoryContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" onclick="printLivestockHistory()">
                    <i class="fas fa-print"></i> Print History
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 right-0 p-3" style="z-index: 1050;">
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable with improved configuration
    const table = $('#livestockTable').DataTable({
        pageLength: 10,
        order: [[0, 'asc']],
        responsive: true,
        scrollX: true,
        scrollCollapse: true,
        autoWidth: false,
        language: {
            search: "Search livestock:",
            lengthMenu: "Show _MENU_ livestock per page",
            info: "Showing _START_ to _END_ of _TOTAL_ livestock",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        columnDefs: [
            {
                targets: [7], // Actions column
                orderable: false
            }
        ]
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Initialize Charts
    initializeCharts();
});

function initializeCharts() {
    // Livestock Performance Chart
    const performanceCtx = document.getElementById('livestockPerformanceChart');
    if (performanceCtx) {
        const performanceChart = new Chart(performanceCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($performanceMetrics['production'] ?? [])) !!},
                datasets: [{
                    label: 'Average Milk Production (L/day)',
                    data: {!! json_encode(array_values($performanceMetrics['production'] ?? [])) !!},
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Health Score (%)',
                    data: {!! json_encode(array_values($performanceMetrics['health_score'] ?? [])) !!},
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
    }

    // Health Status Chart
    const healthCtx = document.getElementById('healthStatusChart');
    if (healthCtx) {
        const healthChart = new Chart(healthCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($healthDistribution)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($healthDistribution)) !!},
                    backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b', '#36b9cc'],
                    hoverBackgroundColor: ['#17a673', '#f4b619', '#e02424', '#2c9faf'],
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
    }
}

// Individual livestock analysis functions
function viewLivestockAnalysis(livestockId) {
    // Load individual livestock analysis
    $.ajax({
        url: `/farmer/livestock/${livestockId}/analysis`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $('#livestockAnalysisContent').html(response.html);
                $('#livestockAnalysisModal').modal('show');
            } else {
                showToast('Failed to load livestock analysis', 'error');
            }
        },
        error: function() {
            showToast('Error loading livestock analysis', 'error');
        }
    });
}

function viewLivestockHistory(livestockId) {
    // Load livestock history
    $.ajax({
        url: `/farmer/livestock/${livestockId}/history`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $('#livestockHistoryContent').html(response.html);
                $('#livestockHistoryModal').modal('show');
            } else {
                showToast('Failed to load livestock history', 'error');
            }
        },
        error: function() {
            showToast('Error loading livestock history', 'error');
        }
    });
}

function printLivestockAnalysis() {
    const printContent = $('#livestockAnalysisContent').html();
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Livestock Analysis Report</title>
                <link rel="stylesheet" href="/css/app.css">
            </head>
            <body>
                <div class="container">
                    <h2>Livestock Analysis Report</h2>
                    ${printContent}
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

function exportLivestockAnalysis() {
    // Implementation for exporting individual livestock analysis
    showToast('Export functionality will be implemented soon', 'info');
}

function printLivestockHistory() {
    const printContent = $('#livestockHistoryContent').html();
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Livestock History Report</title>
                <link rel="stylesheet" href="/css/app.css">
            </head>
            <body>
                <div class="container">
                    <h2>Livestock History Report</h2>
                    ${printContent}
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

// Table export functions
function exportTableCSV() {
    const table = document.getElementById('livestockTable');
    let csv = '';
    
    // Get headers
    const headers = table.querySelectorAll('thead th');
    headers.forEach((header, index) => {
        csv += '"' + header.textContent.trim() + '"';
        if (index < headers.length - 1) csv += ',';
    });
    csv += '\n';
    
    // Get data rows
    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        cells.forEach((cell, index) => {
            csv += '"' + cell.textContent.trim() + '"';
            if (index < cells.length - 1) csv += ',';
        });
        csv += '\n';
    });
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'livestock_analysis.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

function exportTablePDF() {
    // Simple PDF export implementation
    showToast('PDF export functionality will be implemented soon', 'info');
}

function exportTablePNG() {
    // PNG export implementation
    showToast('PNG export functionality will be implemented soon', 'info');
}

function printTable() {
    window.print();
}

function showToast(message, type = 'info') {
    // Simple toast notification
    const toast = $(`
        <div class="toast" role="alert">
            <div class="toast-header">
                <strong class="mr-auto">Notification</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">
                    <span>&times;</span>
                </button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `);
    
    $('.toast-container').append(toast);
    toast.toast('show');
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>
@endpush
