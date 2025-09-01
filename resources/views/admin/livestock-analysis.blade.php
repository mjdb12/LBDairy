@extends('layouts.app')

@section('title', 'Livestock Analysis - LBDairy')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-chart-line"></i>
            Livestock Analysis Dashboard
        </h1>
        <p>Comprehensive analysis and insights for livestock productivity and performance</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-number" id="totalLivestock">{{ $totalLivestock ?? 0 }}</div>
            <div class="stat-label">Total Livestock</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="activeLivestock">{{ $activeLivestock ?? 0 }}</div>
            <div class="stat-label">Active Livestock</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="avgProductivity">{{ $avgProductivity ?? 0 }}%</div>
            <div class="stat-label">Avg. Productivity</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="totalProduction">{{ $totalProduction ?? 0 }}</div>
            <div class="stat-label">Total Production (L)</div>
        </div>
    </div>

    <!-- Main Analysis Section -->
    <div class="row">
        <!-- Chart Column -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6>
                        <i class="fas fa-chart-line"></i>
                        Livestock Productivity Trends
                    </h6>
                    <div class="export-controls">
                        <div class="btn-group mr-2">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" 
                                    id="exportMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-download mr-1"></i>Export Chart
                            </button>
                            <div class="dropdown-menu" aria-labelledby="exportMenuButton">
                                <a class="dropdown-item" href="#" onclick="exportChartCSV()">
                                    <i class="fas fa-file-csv mr-2"></i>Download as CSV
                                </a>
                                <a class="dropdown-item" href="#" onclick="exportChartPNG()">
                                    <i class="fas fa-file-image mr-2"></i>Download as PNG
                                </a>
                                <a class="dropdown-item" href="#" onclick="exportChartPDF()">
                                    <i class="fas fa-file-pdf mr-2"></i>Download as PDF
                                </a>
                            </div>
                        </div>
                        <button class="btn btn-secondary btn-sm" onclick="printChart()">
                            <i class="fas fa-print mr-1"></i>Print
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="mainChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Column -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6>
                        <i class="fas fa-tachometer-alt"></i>
                        Quick Performance Metrics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="quick-stats">
                        <div class="stat-item">
                            <div class="stat-icon bg-success">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value" id="topPerformer">{{ $topPerformer ?? 'N/A' }}</div>
                                <div class="stat-label">Top Performer</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon bg-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value" id="needsAttention">{{ $needsAttention ?? 0 }}</div>
                                <div class="stat-label">Needs Attention</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon bg-info">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value" id="avgAge">{{ $avgAge ?? 0 }}</div>
                                <div class="stat-label">Average Age (months)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Livestock Analysis Table -->
    <div class="card mt-4">
        <div class="card-header">
            <h6>
                <i class="fas fa-list"></i>
                Livestock Analysis Table
            </h6>
            <div class="table-controls">
                <button class="btn btn-primary btn-sm" onclick="refreshTable()">
                    <i class="fas fa-sync-alt mr-1"></i>Refresh
                </button>
                <button class="btn btn-success btn-sm" onclick="exportTableCSV()">
                    <i class="fas fa-file-csv mr-1"></i>Export CSV
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="livestockTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Breed</th>
                            <th>Age (months)</th>
                            <th>Status</th>
                            <th>Productivity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($livestock ?? [] as $animal)
                        <tr>
                            <td><strong>{{ $animal->id }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center mr-2">
                                        <i class="fas fa-cow text-white"></i>
                                    </div>
                                    {{ $animal->name }}
                                </div>
                            </td>
                            <td><span class="badge badge-info">{{ $animal->breed }}</span></td>
                            <td>{{ $animal->age_months }}</td>
                            <td>
                                <span class="status-badge status-{{ $animal->status == 'active' ? 'active' : 'inactive' }}">
                                    <i class="fas fa-{{ $animal->status == 'active' ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                    {{ ucfirst($animal->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         data-width="{{ $animal->productivity_score ?? 0 }}"
                                         aria-valuenow="{{ $animal->productivity_score ?? 0 }}" 
                                         aria-valuemin="0" aria-valuemax="100">
                                        {{ $animal->productivity_score ?? 0 }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-info btn-sm" onclick="openLivestockModal('{{ $animal->id }}')">
                                    <i class="fas fa-chart-line mr-1"></i>View Analysis
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h5>No livestock data available</h5>
                                    <p>Add some livestock to start tracking analysis.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Livestock Analysis Modal -->
<div class="modal fade" id="livestockModal" tabindex="-1" aria-labelledby="livestockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="livestockModalLabel">
                        <i class="fas fa-chart-line mr-2"></i>
                        Livestock Productivity Analysis
                    </h5>
                    <small style="opacity: 0.8;">Livestock ID: <span id="modalLivestockId"></span></small>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Analysis Tabs -->
                <ul class="nav nav-tabs" id="analysisTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="growth-tab" data-toggle="tab" href="#growth" 
                           role="tab" aria-controls="growth" aria-selected="true" onclick="switchAnalysis('growth')">
                            <i class="fas fa-chart-line mr-1"></i>Growth
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="health-tab" data-toggle="tab" href="#health" 
                           role="tab" aria-controls="health" aria-selected="false" onclick="switchAnalysis('health')">
                            <i class="fas fa-heartbeat mr-1"></i>Health
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="breeding-tab" data-toggle="tab" href="#breeding" 
                           role="tab" aria-controls="breeding" aria-selected="false" onclick="switchAnalysis('breeding')">
                            <i class="fas fa-venus-mars mr-1"></i>Breeding
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="milk-tab" data-toggle="tab" href="#milk" 
                           role="tab" aria-controls="milk" aria-selected="false" onclick="switchAnalysis('milk')">
                            <i class="fas fa-tint mr-1"></i>Milk Production
                        </a>
                    </li>
                </ul>

                <!-- Chart Container -->
                <div class="chart-container mt-3">
                    <canvas id="analysisChart"></canvas>
                </div>

                <!-- Analysis Insights -->
                <div class="alert alert-info mt-3" role="alert">
                    <h6 class="alert-heading">
                        <i class="fas fa-info-circle mr-2"></i>Analysis Insights
                    </h6>
                    <p class="mb-0" id="livestockAnalysisText">
                        Select an analysis type to view detailed insights.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
let mainChartInstance = null;
let analysisChartInstance = null;
let currentLivestockId = null;
let currentAnalysisType = 'growth';

// Initialize charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Set progress bar widths from data attributes
    document.querySelectorAll('.progress-bar[data-width]').forEach(function(progressBar) {
        const width = progressBar.getAttribute('data-width');
        progressBar.style.width = width + '%';
    });
    
    initializeMainChart();
    setupEventListeners();
});

function initializeMainChart() {
    const ctx = document.getElementById('mainChart').getContext('2d');
    
    mainChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Average Productivity',
                data: [65, 72, 68, 75, 80, 78],
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderColor: '#4e73df',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
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
}

function setupEventListeners() {
    // Add any additional event listeners here
}

function openLivestockModal(livestockId) {
    currentLivestockId = livestockId;
    document.getElementById('modalLivestockId').textContent = livestockId;
    $('#livestockModal').modal('show');
    
    // Load livestock data and initialize analysis chart
    loadLivestockAnalysis(livestockId, 'growth');
}

function switchAnalysis(type) {
    currentAnalysisType = type;
    loadLivestockAnalysis(currentLivestockId, type);
}

function loadLivestockAnalysis(livestockId, type) {
    // Simulate loading data - in real app, this would be an AJAX call
    const analysisData = getAnalysisData(type);
    updateAnalysisChart(analysisData);
    updateAnalysisText(type);
}

function getAnalysisData(type) {
    const data = {
        growth: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            data: [45, 52, 58, 65, 72, 78],
            label: 'Growth Rate'
        },
        health: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            data: [85, 88, 92, 89, 94, 91],
            label: 'Health Score'
        },
        breeding: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            data: [60, 65, 70, 75, 80, 85],
            label: 'Breeding Success'
        },
        milk: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            data: [20, 22, 25, 28, 30, 32],
            label: 'Milk Production (L)'
        }
    };
    
    return data[type] || data.growth;
}

function updateAnalysisChart(data) {
    if (analysisChartInstance) {
        analysisChartInstance.destroy();
    }
    
    const ctx = document.getElementById('analysisChart').getContext('2d');
    
    analysisChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: data.label,
                data: data.data,
                backgroundColor: 'rgba(28, 200, 138, 0.1)',
                borderColor: '#1cc88a',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
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
}

function updateAnalysisText(type) {
    const texts = {
        growth: 'Growth analysis shows steady improvement in livestock development over the past 6 months.',
        health: 'Health metrics indicate excellent overall wellness with minor seasonal variations.',
        breeding: 'Breeding success rates have improved significantly with proper management practices.',
        milk: 'Milk production shows consistent growth with peak performance in recent months.'
    };
    
    document.getElementById('livestockAnalysisText').textContent = texts[type] || texts.growth;
}

// Export functions
function exportChartCSV() {
    if (!mainChartInstance) return;
    
    const data = mainChartInstance.data;
    let csv = 'Month,Productivity\n';
    data.labels.forEach((label, i) => {
        csv += `"${label}",${data.datasets[0].data[i]}\n`;
    });
    
    downloadCSV(csv, 'livestock_productivity_trends.csv');
}

function exportChartPNG() {
    if (!mainChartInstance) return;
    
    const canvas = document.getElementById('mainChart');
    const url = canvas.toDataURL('image/png');
    downloadFile(url, 'livestock_productivity_trends.png');
}

function exportChartPDF() {
    // PDF export functionality would be implemented here
    alert('PDF export functionality coming soon!');
}

function exportTableCSV() {
    const table = document.getElementById('livestockTable');
    let csv = 'ID,Name,Breed,Age,Status,Productivity\n';
    
    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length > 0) {
            const rowData = Array.from(cells).map(cell => {
                let text = cell.textContent.trim();
                // Extract productivity percentage from progress bar
                if (cell.querySelector('.progress-bar')) {
                    text = cell.querySelector('.progress-bar').style.width;
                }
                return `"${text}"`;
            });
            csv += rowData.join(',') + '\n';
        }
    });
    
    downloadCSV(csv, 'livestock_analysis_table.csv');
}

function downloadCSV(csv, filename) {
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

function downloadFile(url, filename) {
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

function printChart() {
    window.print();
}

function refreshTable() {
    location.reload();
}
</script>
@endpush

@push('styles')
<style>
:root {
    --primary-color: #18375d;
    --primary-dark: #122a47;
    --success-color: #1cc88a;
    --warning-color: #f6c23e;
    --danger-color: #e74a3b;
    --info-color: #36b9cc;
    --light-color: #f8f9fc;
    --dark-color: #5a5c69;
    --border-color: #e3e6f0;
    --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    --gradient-primary: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    --gradient-success: linear-gradient(135deg, var(--success-color) 0%, #17a673 100%);
    --gradient-info: linear-gradient(135deg, var(--info-color) 0%, #2c9faf 100%);
    --gradient-warning: linear-gradient(135deg, var(--warning-color) 0%, #d69e2e 100%);
}

.page-header {
    background: var(--gradient-primary);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: var(--shadow);
    position: relative;
    overflow: hidden;
}



.page-header h1 {
    margin: 0;
    font-weight: 700;
    font-size: 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
    z-index: 1;
}

.page-header p {
    margin: 0.5rem 0 0 0;
    opacity: 0.9;
    font-size: 1.1rem;
    position: relative;
    z-index: 1;
}

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
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-primary);
    border-radius: 12px 12px 0 0;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--primary-color);
}

.stat-label {
    color: var(--dark-color);
    font-size: 0.9rem;
    font-weight: 500;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    overflow: hidden;
}

.card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

.card-header {
    background: var(--gradient-primary);
    border-bottom: none;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.card-header h6 {
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-header h6::before {
    content: '';
    width: 4px;
    height: 20px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 2px;
}

.chart-container {
    position: relative;
    height: 400px;
    margin: 1rem 0;
}

.chart-container canvas {
    max-height: 100%;
}

.quick-stats {
    display: grid;
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--light-color);
    border-radius: 8px;
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: translateX(5px);
    box-shadow: var(--shadow);
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.85rem;
    color: var(--dark-color);
    opacity: 0.8;
}

.table {
    margin-bottom: 0;
    font-size: 0.9rem;
}

.table thead th {
    background-color: #f8f9fc;
    border-top: none;
    border-bottom: 2px solid var(--border-color);
    font-weight: 600;
    color: var(--dark-color);
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

.avatar-sm {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

.status-badge {
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.status-active {
    background: rgba(28, 200, 138, 0.1);
    color: var(--success-color);
}

.status-inactive {
    background: rgba(231, 74, 59, 0.1);
    color: var(--danger-color);
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
    border: none;
    font-size: 0.85rem;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-sm {
    padding: 0.4rem 0.8rem;
    font-size: 0.8rem;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--dark-color);
}

.empty-state i {
    font-size: 3rem;
    opacity: 0.5;
    margin-bottom: 1rem;
}

.empty-state h5 {
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.empty-state p {
    opacity: 0.7;
    margin: 0;
}

.export-controls {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.table-controls {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

@media (max-width: 768px) {
    .stats-container {
        grid-template-columns: 1fr;
    }
    
    .card-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .export-controls,
    .table-controls {
        flex-wrap: wrap;
    }
    
    .chart-container {
        height: 300px;
    }
}
</style>
@endpush
