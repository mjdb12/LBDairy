<div class="livestock-analysis-container">
    <!-- Livestock Header Information -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="text-primary">
                <i class="fas fa-cow"></i>
                {{ $livestock->name ?? $livestock->tag_number }} - Detailed Analysis
            </h4>
            <p class="text-muted mb-0">
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
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
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
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
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
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
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
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
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
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
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
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
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
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
                            <p>No specific insights available for this livestock at the moment.</p>
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
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-table"></i>
                        Detailed Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="30%">Basic Information</th>
                                    <td>
                                        <strong>Tag Number:</strong> {{ $livestock->tag_number }}<br>
                                        <strong>Name:</strong> {{ $livestock->name ?? 'Not specified' }}<br>
                                        <strong>Type:</strong> {{ ucfirst($livestock->type) }}<br>
                                        <strong>Breed:</strong> {{ ucfirst(str_replace('_', ' ', $livestock->breed)) }}<br>
                                        <strong>Gender:</strong> {{ ucfirst($livestock->gender) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Health & Status</th>
                                    <td>
                                        <strong>Health Status:</strong> 
                                        <span class="badge badge-{{ $livestock->health_status === 'healthy' ? 'success' : ($livestock->health_status === 'under_treatment' ? 'warning' : 'danger') }}">
                                            {{ ucfirst(str_replace('_', ' ', $livestock->health_status)) }}
                                        </span><br>
                                        <strong>Health Score:</strong> {{ $healthScore }}%<br>
                                        <strong>Status:</strong> 
                                        <span class="badge badge-{{ $livestock->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($livestock->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Physical Details</th>
                                    <td>
                                        <strong>Birth Date:</strong> {{ $livestock->birth_date ? \Carbon\Carbon::parse($livestock->birth_date)->format('M d, Y') : 'Not recorded' }}<br>
                                        <strong>Age:</strong> {{ $age }} years<br>
                                        <strong>Weight:</strong> {{ $livestock->weight ?? 'Not recorded' }} kg<br>
                                        <strong>Farm:</strong> {{ $livestock->farm->name ?? 'Not assigned' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Production Summary</th>
                                    <td>
                                        <strong>Total Production Records:</strong> {{ $livestock->productionRecords->count() }}<br>
                                        <strong>Average Daily Production:</strong> {{ $productionData->avg() ? round($productionData->avg(), 1) : 0 }} L<br>
                                        <strong>Maximum Daily Production:</strong> {{ $productionData->max() ? round($productionData->max(), 1) : 0 }} L<br>
                                        <strong>Minimum Daily Production:</strong> {{ $productionData->min() ? round($productionData->min(), 1) : 0 }} L
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
