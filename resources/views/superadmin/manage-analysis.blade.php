@extends('layouts.app')

@section('title', 'LBDAIRY: SuperAdmin - Productivity Analysis')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-database"></i>
        Productivity Analysis
    </h1>
        <p>Comprehensive farm and livestock productivity insights and analytics</p>
</div>



<!-- Stats Cards -->
<div class="row fade-in stagger-animation">
    <!-- Total Farms -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Farms</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalFarmsStat">0</div>
                </div>
                <div class="icon">
                    <i class="fas fa-university fa-2x" style="color: #18375d !important;"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Total Livestock -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Livestock</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalLivestockStat">0</div>
                </div>
                <div class="icon">
                    <i class="fas fa-cow fa-2x" style="color: #18375d !important;"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Monthly Production -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Monthly Production</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><span id="monthlyProductionStat">0</span> L</div>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line fa-2x" style="color: #18375d !important;"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Efficiency Rate -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Efficiency Rate</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><span id="efficiencyStat">0</span><sup>%</sup></div>
                </div>
                <div class="icon">
                    <i class="fas fa-percentage fa-2x" style="color: #18375d !important;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row fade-in">
    <!-- Farm Performance Chart -->
    <div class="col-12 col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h6>
                    <i class="fas fa-chart-bar"></i>
                    Farm Performance Overview
                </h6>
                <div class="action-buttons">
                    <select class="form-control form-control-sm" id="chartPeriod">
                        <option value="7">Last 7 Days</option>
                        <option value="30" selected>Last 30 Days</option>
                        <option value="90">Last 3 Months</option>
                        <option value="365">Last Year</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <canvas id="farmPerformanceChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Performing Farms -->
    <div class="col-12 col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h6>
                    <i class="fas fa-trophy"></i>
                    Top Performing Farms
                </h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach(\App\Models\Farm::take(5)->get() as $index => $farm)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="badge badge-primary mr-2">{{ $index + 1 }}</span>
                            <div>
                                <div class="font-weight-bold">{{ $farm->name }}</div>
                                <small class="text-muted">{{ $farm->location }}</small>
                            </div>
                        </div>
                        <span class="badge badge-success">{{ rand(85, 98) }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Analysis -->
<div class="row fade-in side-by-side-row">
    <!-- Livestock Analysis -->
    <div class="col-12 col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h6>
                    <i class="fas fa-cow"></i>
                    Livestock Distribution
                </h6>
            </div>
            <div class="card-body">
                <canvas id="livestockChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Production Trends -->
    <div class="col-12 col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h6>
                    <i class="fas fa-chart-line"></i>
                    Production Trends
                </h6>
            </div>
            <div class="card-body">
                <canvas id="productionChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Data Tables -->
<div class="row fade-in">
    <!-- Farm Analysis Table -->
    <div class="col-12 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h6>
                    <i class="fas fa-table"></i>
                    Farm Analysis Data
                </h6>
                <div class="action-buttons">
                    <button class="btn btn-success btn-sm">
                        <i class="fas fa-download mr-2"></i>Export Report
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="farmAnalysisTable">
                        <thead>
                            <tr>
                                <th>Farm ID</th>
                                <th>Farm Name</th>
                                <th>Owner</th>
                                <th>Livestock Count</th>
                                <th>Monthly Production</th>
                                <th>Efficiency</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Farm::take(10)->get() as $farm)
                            <tr>
                                <td>{{ $farm->id }}</td>
                                <td>{{ $farm->name }}</td>
                                <td>{{ $farm->owner->name ?? 'Unknown' }}</td>
                                <td>{{ \App\Models\Livestock::where('farm_id', $farm->id)->count() }}</td>
                                <td>{{ rand(100, 500) }} L</td>
                                <td>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: {{ rand(70, 95) }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ rand(70, 95) }}%</small>
                                </td>
                                <td>
                                    @if($farm->status === 'active')
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-warning">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-action-view" onclick="viewFarmDetails('{{ $farm->id }}')" title="View Details">
                                            <i class="fas fa-eye"></i>
                                            <span>View</span>
                                        </button>
                                        <button class="btn-action btn-action-report" onclick="generateFarmReport('{{ $farm->id }}')" title="Generate Report">
                                            <i class="fas fa-file-alt"></i>
                                            <span>Report</span>
                                        </button>
                                    </div>
                                </td>
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

    /* Enhanced Stat Cards */
    .border-left-info {
        border-left: 4px solid var(--info-color) !important;
    }

    .border-left-success {
        border-left: 4px solid var(--success-color) !important;
    }

    .border-left-warning {
        border-left: 4px solid var(--warning-color) !important;
    }

    .border-left-primary {
        border-left: 4px solid var(--primary-color) !important;
    }

    .card-footer {
        background: rgba(0, 0, 0, 0.03);
        border-top: 1px solid var(--border-color);
        padding: 0.75rem 1.5rem;
        transition: all 0.2s ease;
    }

    .card-footer:hover {
        background: rgba(0, 0, 0, 0.05);
    }

    /* Enhanced Card Header */
    .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
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

    /* Stagger Animation */
    .stagger-animation .col-12 {
        animation: fadeInUp 0.6s ease-out;
    }
    
    .stagger-animation .col-12:nth-child(1) { animation-delay: 0.1s; }
    .stagger-animation .col-12:nth-child(2) { animation-delay: 0.2s; }
    .stagger-animation .col-12:nth-child(3) { animation-delay: 0.3s; }
    .stagger-animation .col-12:nth-child(4) { animation-delay: 0.4s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Enhanced Table */
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

    .table tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
    }

    /* Enhanced Badges */
    .badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #17a673 100%);
    }

    .badge-warning {
        background: linear-gradient(135deg, var(--warning-color) 0%, #d69e2e 100%);
    }

    .badge-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    }

    .badge-info {
        background: linear-gradient(135deg, var(--info-color) 0%, #2c9faf 100%);
    }

    /* Progress Bar Enhancement */
    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }

    .progress-bar {
        border-radius: 10px;
    }

    /* List Group Enhancement */
    .list-group-item {
        border: none;
        border-bottom: 1px solid #f0f0f0;
        padding: 1rem;
        transition: all 0.2s ease;
    }

    .list-group-item:hover {
        background-color: rgba(78, 115, 223, 0.05);
        transform: translateX(2px);
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    /* Side-by-side layout enhancements */
    .side-by-side-row .col-lg-6 {
        display: flex;
        flex-direction: column;
    }

    .side-by-side-row .col-lg-6 .card {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .side-by-side-row .col-lg-6 .card .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* Ensure charts and lists take full height */
    #livestockChart {
        flex: 1;
        min-height: 200px;
    }

    #topFarmsList {
        flex: 1;
        min-height: 200px;
    }

    /* Force side-by-side layout */
    .side-by-side-row {
        display: flex !important;
        flex-wrap: nowrap !important;
        margin: 0 !important;
    }

    .side-by-side-row .col-lg-6 {
        flex: 0 0 50% !important;
        max-width: 50% !important;
        padding-left: 15px !important;
        padding-right: 15px !important;
    }

    .side-by-side-row .col-lg-6:first-child {
        padding-left: 0 !important;
        padding-right: 7.5px !important;
    }

    .side-by-side-row .col-lg-6:last-child {
        padding-left: 7.5px !important;
        padding-right: 0 !important;
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .side-by-side-row {
            flex-wrap: wrap !important;
        }
        
        .side-by-side-row .col-lg-6 {
            flex: 0 0 100% !important;
            max-width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#farmAnalysisTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'desc']],
            language: {
                search: "Search farms:",
                lengthMenu: "Show _MENU_ farms per page",
                info: "Showing _START_ to _END_ of _TOTAL_ farms"
            }
        });

        // Farm Performance Chart
        const farmCtx = document.getElementById('farmPerformanceChart').getContext('2d');
    fetch("{{ url('superadmin/analysis/farm-performance') }}?days=' + document.getElementById('chartPeriod').value)")
            .then(r => r.json())
            .then(payload => {
                if (!payload?.success) return;
                new Chart(farmCtx, {
                    type: 'line',
                    data: {
                        labels: payload.labels,
                        datasets: [payload.dataset]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: { y: { beginAtZero: true } },
                        plugins: { legend: { position: 'top' } }
                    }
                });
            });

        // Livestock Distribution Chart
        const livestockCtx = document.getElementById('livestockChart').getContext('2d');
    fetch("{{ url('superadmin/analysis/livestock-distribution') }}")
            .then(r => r.json())
            .then(payload => {
                if (!payload?.success) return;
                new Chart(livestockCtx, {
                    type: 'doughnut',
                    data: {
                        labels: payload.labels,
                        datasets: [{
                            data: payload.data,
                            backgroundColor: ['#dd1d10ff','#2d4a6b','#3e5a7a','#4f6a89','#607a98','#718aa7'],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { 
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            } 
                        },
                        layout: {
                            padding: {
                                top: 10,
                                bottom: 10
                            }
                        }
                    }
                });
            });

        // Production Trends Chart
        const productionCtx = document.getElementById('productionChart').getContext('2d');
    fetch("{{ url('superadmin/analysis/production-trends') }}")
            .then(r => r.json())
            .then(payload => {
                if (!payload?.success) return;
                
                // Create enhanced chart with multiple datasets
                new Chart(productionCtx, {
                    type: 'bar',
                    data: {
                        labels: payload.labels,
                        datasets: [
                            {
                                label: 'Total Production (L)',
                                backgroundColor: 'rgba(78, 115, 223, 0.8)',
                                data: payload.data,
                                borderRadius: 8,
                                borderSkipped: false,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Active Farms',
                                type: 'line',
                                backgroundColor: 'rgba(28, 200, 138, 0.2)',
                                borderColor: 'rgba(28, 200, 138, 1)',
                                borderWidth: 3,
                                fill: false,
                                data: payload.activeFarms || [],
                                yAxisID: 'y1',
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    afterLabel: function(context) {
                                        if (context.datasetIndex === 0) {
                                            const weekIndex = context.dataIndex;
                                            const avgProduction = payload.avgProduction ? payload.avgProduction[weekIndex] : 0;
                                            const activeLivestock = payload.activeLivestock ? payload.activeLivestock[weekIndex] : 0;
                                            return [
                                                `Avg Daily: ${avgProduction.toFixed(1)}L`,
                                                `Active Livestock: ${activeLivestock}`
                                            ];
                                        }
                                        return '';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Production (Liters)'
                                }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Active Farms'
                                },
                                grid: {
                                    drawOnChartArea: false,
                                },
                            }
                        }
                    }
                });

                // Update summary information if available
                if (payload.summary) {
                    updateProductionSummary(payload.summary);
                }
            })
            .catch(error => {
                console.error('Error loading production trends:', error);
            });

        // Function to update production summary
        function updateProductionSummary(summary) {
            // You can add summary display elements here if needed
            console.log('Production Summary:', summary);
        }

        // Add fade-in animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });

        // Load top KPI stats
    fetch("{{ url('superadmin/analysis/summary') }}")
            .then(r => r.json())
            .then(payload => {
                if (!payload?.success) return;
                const t = payload.totals || {};
                document.getElementById('totalFarmsStat').textContent = t.farms ?? 0;
                document.getElementById('totalLivestockStat').textContent = t.livestock ?? 0;
                document.getElementById('monthlyProductionStat').textContent = (t.monthly_production_liters ?? 0);
                document.getElementById('efficiencyStat').textContent = (t.efficiency_percent ?? 0);
            });

        // Load top performing farms
    fetch("{{ url('superadmin/analysis/top-performing-farms') }}")
            .then(r => r.json())
            .then(payload => {
                const topFarmsList = document.getElementById('topFarmsList');
                if (!payload?.success) {
                    topFarmsList.innerHTML = '<div class="text-center py-4"><p class="text-muted">Failed to load top performing farms</p></div>';
                    return;
                }
                
                const farms = payload.farms || [];
                if (farms.length === 0) {
                    topFarmsList.innerHTML = '<div class="text-center py-4"><p class="text-muted">No farm data available</p></div>';
                    return;
                }
                
                topFarmsList.innerHTML = farms.map((farm, index) => `
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="badge badge-primary mr-2">${index + 1}</span>
                            <div>
                                <div class="font-weight-bold">${farm.name}</div>
                                <small class="text-muted">${farm.location} • ${farm.livestock_count} livestock</small>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-success">${farm.performance_score}%</span>
                            <div class="small text-muted">${farm.monthly_production}L</div>
                        </div>
                    </div>
                `).join('');
            })
            .catch(error => {
                console.error('Error loading top performing farms:', error);
                document.getElementById('topFarmsList').innerHTML = '<div class="text-center py-4"><p class="text-muted">Error loading data</p></div>';
            });

        // Chart period change handler
        $('#chartPeriod').change(function() {
            const days = $(this).val();
            fetch('{{ route('superadmin.analysis.farm-performance') }}?days=' + days)
                .then(r => r.json())
                .then(payload => {
                    if (!payload?.success) return;
                    // simple refresh by re-rendering
                    new Chart(farmCtx, {
                        type: 'line',
                        data: { labels: payload.labels, datasets: [payload.dataset] },
                        options: { responsive: true, maintainAspectRatio: false }
                    });
                });
        });
    });
</script>
@endpush
