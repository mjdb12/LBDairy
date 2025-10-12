<style>
      /* SMART DETAIL MODAL TEMPLATE */
.smart-detail .modal-content {
    border-radius: 1.5rem;
    border: none;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12);
    background-color: #fff;
    transition: all 0.3s ease-in-out;
}

/* Center alignment for header section */
.smart-detail .modal-header,
.smart-detail .modal-footer {
    text-align: center;
}

/* Icon Header */
.smart-detail .icon-circle {
    width: 60px;
    height: 60px;
    background-color: #e8f0fe;
    color: #18375d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

/* Titles & Paragraphs */
.smart-detail h5 {
    color: #18375d;
    font-weight: 700;
    margin-bottom: 0.4rem;
    letter-spacing: 0.5px;
}

.smart-detail p {
    color: #6b7280;
    font-size: 1rem;
    margin-bottom: 1.8rem;
    line-height: 1.6;
    text-align: left; /* ensures proper centering */
}

/* MODAL BODY */
.smart-detail .modal-body {
    background: #ffffff;
    padding: 3rem 3.5rem; /* more spacious layout */
    border-radius: 1rem;
    max-height: 88vh; /* taller for longer content */
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

/* Wider modal container */
.smart-detail .modal-dialog {
    max-width: 92%; /* slightly wider modal */
    width: 100%;
    margin: 1.75rem auto;
}

/* Detail Section */
.smart-detail .detail-wrapper {
    background: #f9fafb;
    border-radius: 1.25rem;
    padding: 2.25rem; /* more inner padding */
    font-size: 1rem;
    line-height: 1.65;
}

/* Detail Rows */
.smart-detail .detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px dashed #ddd;
    padding: 0.6rem 0;
}

.smart-detail .detail-row:last-child {
    border-bottom: none;
}

.smart-detail .detail-label {
    font-weight: 600;
    color: #1b3043;
}

.smart-detail .detail-value {
    color: #333;
    text-align: right;
}

/* Footer */
#livestockDetailsModal .modal-footer {
    text-align: center;
    border-top: 1px solid #e5e7eb;
    padding-top: 1.5rem;
    margin-top: 2rem;
}

/* RESPONSIVE ADJUSTMENTS */
@media (max-width: 992px) {
    .smart-detail .modal-dialog {
        max-width: 95%;
    }

    .smart-detail .modal-body {
        padding: 2rem;
        max-height: 82vh;
    }

    .smart-detail .detail-wrapper {
        padding: 1.5rem;
        font-size: 0.95rem;
    }

    .smart-detail p {
        text-align: center;
        font-size: 0.95rem;
    }
}

@media (max-width: 576px) {
    .smart-detail .modal-body {
        padding: 1.5rem;
        max-height: 80vh;
    }

    .smart-detail .detail-wrapper {
        padding: 1.25rem;
    }

    .smart-detail .detail-row {
        flex-direction: column;
        text-align: left;
        gap: 0.3rem;
    }

    .smart-detail .detail-value {
        text-align: left;
    }
}
    /* Action buttons styling */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        justify-content: center;
        min-width: 200px;
    }
    
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.25rem;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        white-space: nowrap;
    }
    
    .btn-action-edit {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
    .btn-action-edit:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-ok {
        background-color: #18375d;
        border-color: #18375d;
        color: white;
    }
    
    .btn-action-ok:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    .btn-action-deletes {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .btn-action-deletes:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-print {
        background-color: #387057 ;
        border-color: #387057 ;
        color: white !important;
    }
    
    .btn-action-print:hover {
        background-color: #5a6268 !important;
        border-color: #5a6268 !important;
        color: white !important;
    }
/* 🌟 Page Header Styling */
.page {
    background-color: #18375d;
    border-radius: 12px;
    padding: 1.5rem 2rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease-in-out;
    animation: fadeIn 0.6s ease-in-out;
}

/* Hover lift effect for interactivity */
.page:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
}

/* 🧭 Header Title */
.page h1 {
    color: #18375d;
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Icon style */
.page i {
    color: #18375d; /* Bootstrap primary color */
}

/* 💬 Subtitle text */
.page p {
    color: #18375d;
    font-size: 1rem;
    margin: 0;
}

/* ✨ Fade-in Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
/* Base Card Style */
.card {
    background-color: #ffffff !important;
    border: none;
    border-radius: 0.75rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
}

/* Top Section (Header inside card-body) */
.card-body:first-of-type {
    background-color: #ffffff;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    border-top-left-radius: 0.75rem;
    border-top-right-radius: 0.75rem;
    padding: 1rem 1.5rem;
}

/* Title (h6) */
.card-body:first-of-type h6 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 700;
    color: #18375d !important;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Second Card Body (Main Content) */
.card-body:last-of-type {
    background-color: #ffffff;
    padding: 1.25rem 1.5rem;
    border-bottom-left-radius: 0.75rem;
    border-bottom-right-radius: 0.75rem;
}
.smart-detail {
  background: #ffffff;
  border-radius: 18px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
}

.smart-detail:hover {
  box-shadow: 0 14px 35px rgba(0, 0, 0, 0.12);
}

.section-title {
  font-size: 1rem;
  display: flex;
  align-items: center;
}

.badge {
  font-size: 0.8rem;
  padding: 0.35rem 0.6rem;
  border-radius: 8px;
}

</style>
<div class="livestock-analysis-container">
    <!-- Livestock Header Information -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h5>
                <i class="fas fa-cow"></i>
                {{$livestock->name ?? $livestock->tag_number }} - Detailed Analysis
            </h5>
            <p class="text-muted">
                <strong>Tag ID:</strong> {{ $livestock->tag_number }} | 
                <strong>Type:</strong> {{ ucfirst($livestock->type) }} | 
                <strong>Breed:</strong> {{ ucfirst(str_replace('_', ' ', $livestock->breed)) }} | 
                <strong>Age:</strong> {{ $age }} years
            </p>
        </div>
        <div class="col-md-4 text-right">
            <div class="health-score-display">
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar {{ $healthScore >= 80 ? 'bg-success' : ($healthScore >= 60 ? 'bg-warning' : 'bg-danger') }}" 
                         role="progressbar" 
                         style="width: {{ $healthScore }}%">
                        <strong>{{ $healthScore }}%</strong>
                    </div>
                </div>
                <small class="text-muted">Health Score</small>
            </div>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold  text-uppercase mb-1">
                                Avg Production</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $productionData->avg() ? round($productionData->avg(), 1) : 0 }} L/day
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tint fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold  text-uppercase mb-1">
                                Max Production</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $productionData->max() ? round($productionData->max(), 1) : 0 }} L/day
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold  text-uppercase mb-1">
                                Total Records</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $livestock->productionRecords->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-database fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Current Weight</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $livestock->weight ?? 'N/A' }} kg
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-weight fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-3">
            <div class="card shadow">
                <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2  text-sm-start">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-line"></i>
                        Production Trend (Last 12 Months)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="position: relative; height: 300px;">
                        <canvas id="individualProductionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="card shadow">
                <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2  text-sm-start">
                    <h6 class="m-0 font-weight-bold ">
                        <i class="fas fa-chart-pie"></i>
                        Production Distribution
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie" style="position: relative; height: 250px;">
                        <canvas id="individualProductionPie"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Insights Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2  text-sm-start">
                    <h6 class="m-0 font-weight-bold ">
                        <i class="fas fa-lightbulb"></i>
                        Analysis Insights
                    </h6>
                </div>
                <div class="card-body">
                    @if(count($insights) > 0)
                        <div class="row">
                            @foreach($insights as $insight)
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <div class="alert alert-{{ $insight['type'] }} border-left-{{ $insight['type'] }}">
                                        <div class="d-flex align-items-center">
                                            <i class="{{ $insight['icon'] }} fa-2x mr-3"></i>
                                            <div>
                                                <h6 class="alert-heading mb-1">{{ $insight['title'] }}</h6>
                                                <p class="mb-0">{{ $insight['message'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <p class="text-center">No specific insights available for this livestock at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Information Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2  text-sm-start">
                    <h6 class="m-0 font-weight-bold ">
                        <i class="fas fa-table"></i>
                        Detailed Information
                    </h6>
                </div>
               <div class="card smart-detail border-0 shadow-sm rounded-4 p-3">
    <div class="card-body">
        <div class="detail-section mb-4">
            <h6 class="section-title  fw-bold mb-3">
                <i class="fas fa-info-circle me-2"></i> Basic Information
            </h6>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Tag Number:</strong> {{ $livestock->tag_number }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Name:</strong> {{ $livestock->name ?? 'Not specified' }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Type:</strong> {{ ucfirst($livestock->type) }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Breed:</strong> {{ ucfirst(str_replace('_', ' ', $livestock->breed)) }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Gender:</strong> {{ ucfirst($livestock->gender) }}
                </div>
            </div>
        </div>

        <hr class="my-3">

        <div class="detail-section mb-4">
            <h6 class="section-title text-success fw-bold mb-3">
                <i class="fas fa-heartbeat me-2"></i> Health & Status
            </h6>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Health Status:</strong>
                    <span class="badge badge-{{ $livestock->health_status === 'healthy' ? 'success' : ($livestock->health_status === 'under_treatment' ? 'warning' : 'danger') }}">
                        {{ ucfirst(str_replace('_', ' ', $livestock->health_status)) }}
                    </span>
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Health Score:</strong> {{ $healthScore }}%
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Status:</strong>
                    <span class="badge badge-{{ $livestock->status === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($livestock->status) }}
                    </span>
                </div>
            </div>
        </div>

        <hr class="my-3">

        <div class="detail-section mb-4">
            <h6 class="section-title text-warning fw-bold mb-3">
                <i class="fas fa-heartbeat me-2"></i> Physical Details
            </h6>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Birth Date:</strong> 
                    {{ $livestock->birth_date ? \Carbon\Carbon::parse($livestock->birth_date)->format('M d, Y') : 'Not recorded' }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Age:</strong> {{ $age }} years
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Weight:</strong> {{ $livestock->weight ?? 'Not recorded' }} kg
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Farm:</strong> {{ $livestock->farm->name ?? 'Not assigned' }}
                </div>
            </div>
        </div>

        <hr class="my-3">

        <div class="detail-section">
            <h6 class="section-title text-info fw-bold mb-3">
                <i class="fas fa-industry me-2"></i> Production Summary
            </h6>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Total Production Records:</strong> {{ $livestock->productionRecords->count() }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Average Daily Production:</strong> {{ $productionData->avg() ? round($productionData->avg(), 1) : 0 }} L
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Maximum Daily Production:</strong> {{ $productionData->max() ? round($productionData->max(), 1) : 0 }} L
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Minimum Daily Production:</strong> {{ $productionData->min() ? round($productionData->min(), 1) : 0 }} L
                </div>
            </div>
        </div>
    </div>
</div>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Individual Production Chart
    const productionCtx = document.getElementById('individualProductionChart');
    if (productionCtx) {
        const productionChart = new Chart(productionCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($productionData->toArray())) !!},
                datasets: [{
                    label: 'Monthly Average Production (L/day)',
                    data: {!! json_encode(array_values($productionData->toArray())) !!},
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

    // Production Distribution Pie Chart
    const pieCtx = document.getElementById('individualProductionPie');
    if (pieCtx) {
        const pieChart = new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ['High Production', 'Medium Production', 'Low Production'],
                datasets: [{
                    data: [
                        {{ $productionData->filter(function($value) { return $value > 20; })->count() }},
                        {{ $productionData->filter(function($value) { return $value >= 10 && $value <= 20; })->count() }},
                        {{ $productionData->filter(function($value) { return $value < 10; })->count() }}
                    ],
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
                        display: true,
                        position: 'bottom'
                    }
                },
                cutout: '60%'
            }
        });
    }
});
</script>
