<style>
/* SMART DETAIL MODAL TEMPLATE (COMPRESSED VERSION) */
.smart-detail .modal-content {
    border-radius: 1rem;
    border: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    background-color: #fff;
    transition: all 0.3s ease-in-out;
    max-width: 85vw;
    margin: auto;
}

/* Icon Header */
.smart-detail .icon-circle {
    width: 55px;
    height: 55px;
    background-color: #e8f0fe;
    color: #18375d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    font-size: 1.2rem;
}

/* Titles & Paragraphs */
.smart-detail h5 {
    color: #18375d;
    font-weight: 700;
    margin-bottom: 0.5rem;
    font-size: 1.05rem;
}

.smart-detail p {
    color: #6b7280;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    line-height: 1.4;
}

/* MODAL BODY */
.smart-detail .modal-body {
    background: #ffffff;
    padding: 1.5rem 1rem;
    border-radius: 1rem;
    max-height: 75vh;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

/* Detail Section */
.smart-detail .detail-wrapper {
    background: #f9fafb;
    border-radius: 0.75rem;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
}

.smart-detail .detail-row {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px dashed #ddd;
    padding: 0.5rem 0;
    gap: 0.5rem;
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
    padding-top: 1rem;
    margin-top: 1rem;
}

/* RESPONSIVE ADJUSTMENTS */
@media (max-width: 992px) {
    .smart-detail .modal-dialog {
        max-width: 95%;
    }

    .smart-detail .modal-body {
        padding: 1.25rem;
        max-height: 78vh;
    }

    .smart-detail .detail-wrapper {
        padding: 0.75rem 0.9rem;
        font-size: 0.88rem;
    }

    .smart-detail p {
        font-size: 0.88rem;
    }
}

@media (max-width: 576px) {
    .smart-detail .modal-body {
        padding: 1rem;
        max-height: 75vh;
    }

    .smart-detail .detail-wrapper {
        padding: 0.75rem;
    }

    .smart-detail .detail-row {
        flex-direction: column;
        text-align: left;
        gap: 0.25rem;
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
/* SMART DETAIL HEADER */
.smart-detail-header {
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 1rem;
}

.smart-detail-header .icon-circle {
    width: 55px;
    height: 55px;
    font-size: 1.3rem;
}

.smart-detail-header h5 {
    font-size: 1.25rem;
    margin-bottom: 0.3rem;
}

.smart-detail-header .progress {
    background: #eef2f7;
}

.smart-detail-header .progress-bar strong {
    font-size: 0.9rem;
    color: #fff;
    text-shadow: 0 1px 2px rgba(0,0,0,0.15);
}

/* RESPONSIVE OPTIMIZATION */
@media (max-width: 767.98px) {
    .smart-detail-header {
        text-align: center;
    }
    .smart-detail-header .icon-circle {
        margin: 0 auto 0.75rem;
    }
    .smart-detail-header h5 {
        font-size: 1.1rem;
    }
    .smart-detail-header .progress {
        height: 22px;
    }
}

/* SMART METRICS SECTION */
.smart-metrics {
    margin-top: 0.5rem;
}

.metric-card {
    background: #ffffff;
    border-radius: 1rem;
    border: 1px solid #e5e7eb;
    transition: all 0.25s ease-in-out;
    padding: 1rem 1.25rem;
}

.metric-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
}

/* Label & Value */
.metric-label {
    color: #6b7280;
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.metric-value {
    color: #18375d;
    font-weight: 700;
    font-size: 1.25rem;
}

.metric-value small {
    font-size: 0.8rem;
    color: #6b7280;
}

/* Icon Styling */
.metric-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 46px;
    border-radius: 50%;
    font-size: 1.2rem;
}

/* Subtle background variants */
.bg-blue-subtle { background: #e8f0fe; }
.bg-success-subtle { background: #e9fbe7; }
.bg-warning-subtle { background: #fff8e1; }
.bg-info-subtle { background: #e7f7fb; }

/* ---------- Responsive Tweaks ---------- */
@media (max-width: 992px) {
    .metric-card {
        padding: 0.9rem 1rem;
    }
    .metric-label {
        font-size: 0.8rem;
    }
    .metric-value {
        font-size: 1.1rem;
    }
    .metric-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
}

@media (max-width: 768px) {
    .metric-card {
        padding: 0.8rem 0.9rem;
        text-align: center;
    }
    .metric-label {
        font-size: 0.75rem;
    }
    .metric-value {
        font-size: 1rem;
    }
    .metric-icon {
        width: 38px;
        height: 38px;
        margin-top: 0.5rem;
        font-size: 0.95rem;
    }
}

@media (max-width: 576px) {
    .metric-card {
        padding: 0.7rem 0.8rem;
    }
    .metric-value {
        font-size: 0.95rem;
    }
    .metric-icon {
        width: 34px;
        height: 34px;
        font-size: 0.9rem;
    }
}

.detail-divider {
    border: none;
    border-top: 1px dashed #d1d5db;
    margin: 1.5rem 0;
}

.detail-section .section-title i {
    font-size: 1rem;
    opacity: 0.85;
}

.smart-detail {
    background: #f9fafb;
}

.smart-detail strong {
    color: #1b3043;
    font-weight: 600;
}

.smart-detail .badge {
    font-size: 0.8rem;
    padding: 0.4em 0.6em;
    border-radius: 0.4rem;
}

@media (max-width: 768px) {
    .smart-detail .row > div {
        font-size: 0.9rem;
    }
    .detail-section .section-title {
        font-size: 1rem;
    }
}
/* === Insights Section Styles === */
.card-body .alert {
    border-radius: 0.75rem;
    border: none;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
    display: flex;
    align-items: flex-start;
    padding: 1rem 1.25rem;
    transition: all 0.3s ease;
}

/* Left border indicator */
.border-left-success {
    border-left: 5px solid #28a745 !important;
}
.border-left-warning {
    border-left: 5px solid #ffc107 !important;
}
.border-left-danger {
    border-left: 5px solid #dc3545 !important;
}
.border-left-info {
    border-left: 5px solid #17a2b8 !important;
}

/* Icon spacing and size */
.alert i {
    font-size: 1.6rem;
    margin-right: 0.75rem;
    color: #ffff; /* Neutral default */
    flex-shrink: 0;
    transition: transform 0.2s ease;
}
.alert:hover i {
    transform: scale(1.1);
}

/* Text styles inside alert */
.alert .alert-heading {
    font-size: 1rem;
    font-weight: 600;
    color: #ffff;
}
.alert p {
    font-size: 0.9rem;
    margin: 0;
    color: #ffff;
    text-align: left;
}

/* Type-specific icon tints */
.alert-success i {
    color: #ade2c9ff;
}
.alert-warning i {
    color: #e19033ff;
}
.alert-danger i {
    color: #e5b3b3ff;
}
.alert-info i {
    color: #9fc6d8ff;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-body .alert {
        padding: 0.9rem 1rem;
        flex-direction: row;
    }
    .alert i {
        font-size: 1.4rem;
        margin-right: 0.6rem;
    }
    .alert .alert-heading {
        font-size: 0.95rem;
    }
    .alert p {
        font-size: 0.85rem;
    }
}

</style>
    <!-- Livestock Header Information -->
    <div class="smart-detail-header row align-items-center mb-4">
        <!-- Livestock Info -->
        <div class="col-lg-8 col-md-7 col-sm-12 mb-3 mb-md-0">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-1" style="font-weight: 700; color: #18375d;">
                        {{ $livestock->name ?? $livestock->tag_number }} 
                        <span class="text-muted" style="font-size: 0.9rem;">– Detailed Analysis</span>
                    </h5>
                    <p class="text-muted mb-0" style="font-size: 0.95rem;">
                        <strong>Tag ID:</strong> {{ $livestock->tag_number }} &nbsp; | &nbsp;
                        <strong>Type:</strong> {{ ucfirst($livestock->type) }} &nbsp; | &nbsp;
                        <strong>Breed:</strong> {{ ucfirst(str_replace('_', ' ', $livestock->breed)) }} &nbsp; | &nbsp;
                        <strong>Age:</strong> {{ $age }} years
                    </p>
                </div>
            </div>
        </div>

        <!-- Health Score -->
        <div class="col-lg-4 col-md-5 col-sm-12 text-md-end text-center">
            <div class="health-score-display">
                <label class="fw-semibold text-muted small mb-1 d-block">Health Score</label>
                <div class="progress" style="height: 26px; border-radius: 0.75rem; background: #f1f5f9;">
                    <div class="progress-bar {{ $healthScore >= 80 ? 'bg-success' : ($healthScore >= 60 ? 'bg-warning' : 'bg-danger') }}"
                        role="progressbar"
                        style="width: {{ $healthScore }}%; transition: width 0.5s ease;">
                        <strong>{{ $healthScore }}%</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Key Metrics Section -->
        <div class="smart-metrics row g-3 mb-4">
            <!-- Average Production -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="metric-card h-100 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="metric-label">Avg Production</div>
                        <div class="metric-value">
                            {{ $productionData->avg() ? round($productionData->avg(), 1) : 0 }}
                            <small>L/day</small>
                        </div>
                    </div>
                    <div class="metric-icon bg-blue-subtle text-#blue">
                        <i class="fas fa-tint"></i>
                    </div>
                </div>
            </div>

            <!-- Max Production -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="metric-card h-100 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="metric-label">Max Production</div>
                        <div class="metric-value">
                            {{ $productionData->max() ? round($productionData->max(), 1) : 0 }}
                            <small>L/day</small>
                        </div>
                    </div>
                    <div class="metric-icon bg-success-subtle text-success">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>

            <!-- Total Records -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="metric-card h-100 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="metric-label">Total Records</div>
                        <div class="metric-value">
                            {{ $livestock->productionRecords->count() }}
                        </div>
                    </div>
                    <div class="metric-icon bg-warning-subtle text-warning">
                        <i class="fas fa-database"></i>
                    </div>
                </div>
            </div>

            <!-- Current Weight -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="metric-card h-100 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="metric-label">Current Weight</div>
                        <div class="metric-value">
                            {{ $livestock->weight ?? 'N/A' }}
                            <small>kg</small>
                        </div>
                    </div>
                    <div class="metric-icon bg-info-subtle text-info">
                        <i class="fas fa-weight"></i>
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
                                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                    <div class="alert alert-{{ $insight['type'] }} border-left-{{ $insight['type'] }} d-flex flex-column flex-sm-row align-items-start">
                                        <i class="{{ $insight['icon'] }} fa-2x mr-0 mr-sm-3 mb-2 mb-sm-0"></i>
                                        <div>
                                            <h6 class="alert-heading mb-1">{{ $insight['title'] }}</h6>
                                            <p class="mb-0">{{ $insight['message'] }}</p>
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

    <!-- Detailed Information Section -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                
                <!-- Header -->
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3">
                    <h5 class="fw-bold m-0 d-flex align-items-center">
                        <i class="fas fa-table me-2"></i> Detailed Information
                    </h5>
                </div>
                <hr>
                <!-- Smart Detail Wrapper -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="mb-3" style="color: #18375d; font-weight: 600;">
                            <i class="fas fa-info-circle me-1"></i> Basic Information
                        </h6>
                        <p class="text-left"><strong>Tag Number:</strong> {{ $livestock->tag_number }}</p>
                        <p class="text-left"><strong>Name:</strong> {{ $livestock->name ?? 'Not specified' }}</p>
                        <p class="text-left"><strong>Type:</strong> {{ ucfirst($livestock->type) }}</p>
                        <p class="text-left"><strong>Breed:</strong> {{ ucfirst(str_replace('_', ' ', $livestock->breed)) }}</p>
                        <p class="text-left"><strong>Gender:</strong> {{ ucfirst($livestock->gender) }}</p>
                    </div>

                    <div class="col-md-6">
                        <h6 class="mb-3 text-success" style="font-weight: 600;">
                            <i class="fas fa-heartbeat me-1"></i> Health & Status
                        </h6>
                        <p class="text-left">
                            <strong>Health Status:</strong>
                            <span class="badge badge-{{ $livestock->health_status === 'healthy' ? 'success' : ($livestock->health_status === 'under_treatment' ? 'warning' : 'danger') }}">
                                {{ ucfirst(str_replace('_', ' ', $livestock->health_status)) }}
                            </span>
                        </p>
                        <p class="text-left"><strong>Health Score:</strong> {{ $healthScore }}%</p>
                        <p class="text-left">
                            <strong>Status:</strong>
                            <span class="badge badge-{{ $livestock->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($livestock->status) }}
                            </span>
                        </p>
                    </div>
                </div>

<hr class="detail-divider">

<!-- Physical & Production Details -->
<div class="row">
    <div class="col-md-6">
        <h6 class="mb-3 text-warning" style="font-weight: 600;">
            <i class="fas fa-dumbbell me-1"></i> Physical Details
        </h6>
        <p class="text-left"><strong>Birth Date:</strong> {{ $livestock->birth_date ? \Carbon\Carbon::parse($livestock->birth_date)->format('M d, Y') : 'Not recorded' }}</p>
        <p class="text-left"><strong>Age:</strong> {{ $age }} years</p>
        <p class="text-left"><strong>Weight:</strong> {{ $livestock->weight ?? 'Not recorded' }} kg</p>
        <p class="text-left"><strong>Farm:</strong> {{ $livestock->farm->name ?? 'Not assigned' }}</p>
    </div>

    <div class="col-md-6">
        <h6 class="mb-3 text-info" style="font-weight: 600;">
            <i class="fas fa-industry me-1"></i> Production Summary
        </h6>
        <p class="text-left"><strong>Total Records:</strong> {{ $livestock->productionRecords->count() }}</p>
        <p class="text-left"><strong>Average Daily Production:</strong> {{ $productionData->avg() ? round($productionData->avg(), 1) : 0 }} L</p>
        <p class="text-left"><strong>Maximum Daily Production:</strong> {{ $productionData->max() ? round($productionData->max(), 1) : 0 }} L</p>
        <p class="text-left"><strong>Minimum Daily Production:</strong> {{ $productionData->min() ? round($productionData->min(), 1) : 0 }} L</p>
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
                    backgroundColor: ['#b2f0d9ff', '#f7e2b0ff', '#fdc6c1ff'],
                    hoverBackgroundColor: ['#b2f0d9ff', '#f7e2b0ff', '#fdc6c1ff'],
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
