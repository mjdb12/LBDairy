<div class="livestock-history-container">
    <!-- Livestock Header Information -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="text-info">
                <i class="fas fa-history"></i>
                {{ $livestock->name ?? $livestock->tag_number }} - History Records
            </h4>
            <p class="text-muted mb-0">
                <strong>Tag ID:</strong> {{ $livestock->tag_number }} | 
                <strong>Type:</strong> {{ ucfirst($livestock->type) }} | 
                <strong>Total Records:</strong> {{ $productionHistory->count() }}
            </p>
        </div>
        <div class="col-md-4 text-right">
            <div class="status-display">
                <span class="badge badge-{{ $livestock->status === 'active' ? 'success' : 'secondary' }} badge-lg">
                    {{ ucfirst($livestock->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- History Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Records</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $productionHistory->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-database fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Avg Production</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $productionHistory->avg('milk_quantity') ? round($productionHistory->avg('milk_quantity'), 1) : 0 }} L
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tint fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                First Record</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $productionHistory->last() ? $productionHistory->last()->production_date->format('M Y') : 'N/A' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Last Record</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $productionHistory->first() ? $productionHistory->first()->production_date->format('M Y') : 'N/A' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Production History Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line"></i>
                        Production History Trend
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="productionHistoryChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs for Different History Types -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="historyTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="production-tab" data-toggle="tab" href="#productionHistory" role="tab">
                                <i class="fas fa-tint"></i> Production History
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="health-tab" data-toggle="tab" href="#healthHistory" role="tab">
                                <i class="fas fa-heartbeat"></i> Health History
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="historyTabContent">
                        <!-- Production History Tab -->
                        <div class="tab-pane fade show active" id="productionHistory" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="productionHistoryTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Milk Quantity (L)</th>
                                            <th>Quality Score</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($productionHistory as $record)
                                        <tr>
                                            <td>{{ $record->production_date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="font-weight-bold text-primary">
                                                    {{ $record->milk_quantity }} L
                                                </span>
                                            </td>
                                            <td>
                                                @if($record->milk_quantity >= 20)
                                                    <span class="badge badge-success">Excellent</span>
                                                @elseif($record->milk_quantity >= 15)
                                                    <span class="badge badge-info">Good</span>
                                                @elseif($record->milk_quantity >= 10)
                                                    <span class="badge badge-warning">Average</span>
                                                @else
                                                    <span class="badge badge-danger">Low</span>
                                                @endif
                                            </td>
                                            <td>{{ $record->notes ?? 'No notes' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fas fa-tint fa-3x mb-3 text-muted"></i>
                                                <p>No production records found for this livestock.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Health History Tab -->
                        <div class="tab-pane fade" id="healthHistory" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="healthHistoryTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Health Status</th>
                                            <th>Notes</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($healthHistory as $record)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($record['date'])->format('M d, Y') }}</td>
                                            <td>
                                                @if($record['status'] === 'healthy')
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle"></i> Healthy
                                                    </span>
                                                @elseif($record['status'] === 'under_treatment')
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-stethoscope"></i> Under Treatment
                                                    </span>
                                                @elseif($record['status'] === 'critical')
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-exclamation-triangle"></i> Critical
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">{{ ucfirst($record['status']) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $record['notes'] }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fas fa-heartbeat fa-3x mb-3 text-muted"></i>
                                                <p>No health records found for this livestock.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar"></i>
                        Summary Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Production Statistics</h6>
                            <ul class="list-unstyled">
                                <li><strong>Total Production:</strong> {{ $productionHistory->sum('milk_quantity') }} L</li>
                                <li><strong>Average Daily Production:</strong> {{ $productionHistory->avg('milk_quantity') ? round($productionHistory->avg('milk_quantity'), 1) : 0 }} L</li>
                                <li><strong>Highest Daily Production:</strong> {{ $productionHistory->max('milk_quantity') }} L</li>
                                <li><strong>Lowest Daily Production:</strong> {{ $productionHistory->min('milk_quantity') }} L</li>
                                <li><strong>Records Span:</strong> {{ $productionHistory->count() > 0 ? $productionHistory->first()->production_date->diffInDays($productionHistory->last()->production_date) + 1 : 0 }} days</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Health Summary</h6>
                            <ul class="list-unstyled">
                                <li><strong>Current Status:</strong> 
                                    <span class="badge badge-{{ $livestock->health_status === 'healthy' ? 'success' : ($livestock->health_status === 'under_treatment' ? 'warning' : 'danger') }}">
                                        {{ ucfirst(str_replace('_', ' ', $livestock->health_status)) }}
                                    </span>
                                </li>
                                <li><strong>Health Records:</strong> {{ $healthHistory->count() }}</li>
                                <li><strong>Last Health Check:</strong> {{ $healthHistory->first() ? \Carbon\Carbon::parse($healthHistory->first()['date'])->format('M d, Y') : 'N/A' }}</li>
                                <li><strong>Farm Location:</strong> {{ $livestock->farm->name ?? 'Not assigned' }}</li>
                                <li><strong>Age:</strong> {{ \Carbon\Carbon::parse($livestock->birth_date)->age }} years</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Production History Chart
    const historyCtx = document.getElementById('productionHistoryChart').getContext('2d');
    const historyChart = new Chart(historyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($productionHistory->pluck('production_date')->map(function($date) { return $date->format('M d'); })->toArray()) !!},
            datasets: [{
                label: 'Daily Milk Production (L)',
                data: {!! json_encode($productionHistory->pluck('milk_quantity')->toArray()) !!},
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

    // Initialize DataTables
    $('#productionHistoryTable').DataTable({
        pageLength: 10,
        order: [[0, 'desc']],
        responsive: true,
        language: {
            search: "Search records:",
            lengthMenu: "Show _MENU_ records per page",
            info: "Showing _START_ to _END_ of _TOTAL_ records"
        }
    });

    $('#healthHistoryTable').DataTable({
        pageLength: 10,
        order: [[0, 'desc']],
        responsive: true,
        language: {
            search: "Search records:",
            lengthMenu: "Show _MENU_ records per page",
            info: "Showing _START_ to _END_ of _TOTAL_ records"
        }
    });
});
</script>
