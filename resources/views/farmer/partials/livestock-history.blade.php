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
.smart-detail h6 {
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
<div class="livestock-history-container">
    <!-- Livestock Header Information -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h5>
                <i class="fas fa-cow"></i>
                {{$livestock->name ?? $livestock->tag_number }} - History Records
            </h5>
            <p class="text-muted">
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
                            <div class="text-xs font-weight-bold  text-uppercase mb-1">
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
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
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
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
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
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
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
                <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2  text-sm-start">
                    <h6 class="m-0 font-weight-bold ">
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
                <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2  text-sm-start">
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
                                                <i class="text-center fas fa-tint fa-3x mb-3 text-muted"></i>
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
                                                <button class="btn-action btn-action-ok btn-sm" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="text-center fas fa-heartbeat fa-3x mb-3 text-muted"></i>
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
                <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2  text-sm-start">
                    <h6 class="m-0 font-weight-bold ">
                        <i class="fas fa-chart-bar"></i>
                        Summary Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Production Statistics</h6>
                            <ul class="list-unstyled">
                                <li><strong>Total Production:</strong> {{ $productionHistory->sum('milk_quantity') }} L</li>
                                <li><strong>Average Daily Production:</strong> {{ $productionHistory->avg('milk_quantity') ? round($productionHistory->avg('milk_quantity'), 1) : 0 }} L</li>
                                <li><strong>Highest Daily Production:</strong> {{ $productionHistory->max('milk_quantity') }} L</li>
                                <li><strong>Lowest Daily Production:</strong> {{ $productionHistory->min('milk_quantity') }} L</li>
                                <li><strong>Records Span:</strong> {{ $productionHistory->count() > 0 ? $productionHistory->first()->production_date->diffInDays($productionHistory->last()->production_date) + 1 : 0 }} days</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 >Health Summary</h6>
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
