@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-tasks"></i>
        Production Management
    </h1>
    <p>Track and manage your dairy production records</p>
</div>

<!-- Summary Cards -->
<div class="row fade-in mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="summary-card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Production (L)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalProduction) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="summary-card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">This Month (L)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($monthlyProduction) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="summary-card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Average Daily (L)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($averageDaily, 1) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tachometer-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="summary-card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Quality Score</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($qualityScore, 1) }}/10</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-star fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Production Charts -->
<div class="row mb-4">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Monthly Production Trend</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="productionTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Quality Distribution</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="qualityDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Producers -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Top Producing Livestock</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Livestock</th>
                                <th>Total Production (L)</th>
                                <th>Average Daily (L)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productionStats['top_producers'] as $index => $producer)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $producer['livestock_name'] }}</td>
                                <td>{{ number_format($producer['total_production'], 1) }}</td>
                                <td>{{ number_format($producer['total_production'] / 30, 1) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No production data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Production Table -->
<div class="card shadow fade-in-up">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-white">
            <i class="fas fa-table"></i>
            Production Records
        </h6>
        <div class="d-flex align-items-center">
            <div class="dropdown mr-2">
                <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-download"></i> Export
                </button>
                <div class="dropdown-menu" aria-labelledby="exportDropdown">
                    <a class="dropdown-item" href="#" onclick="exportCSV()">
                        <i class="fas fa-file-csv"></i> CSV
                    </a>
                    <a class="dropdown-item" href="#" onclick="exportPDF()">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    <a class="dropdown-item" href="#" onclick="exportPNG()">
                        <i class="fas fa-image"></i> PNG
                    </a>
                </div>
            </div>
            <button class="btn btn-secondary btn-sm mr-2" onclick="printProductivity()">
                <i class="fas fa-print"></i>
            </button>
            <button class="btn btn-info btn-sm mr-2" data-toggle="modal" data-target="#historyModal">
                <i class="fas fa-history"></i> History
            </button>
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addProductionModal">
                <i class="fas fa-plus"></i> Add Record
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="productionTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Livestock</th>
                        <th>Milk Quantity (L)</th>
                        <th>Quality Score</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productionData as $record)
                    <tr>
                        <td>{{ $record['production_date'] }}</td>
                        <td>{{ $record['livestock_name'] }} ({{ $record['livestock_tag'] }})</td>
                        <td>{{ number_format($record['milk_quantity'], 1) }}</td>
                        <td>
                            <span class="badge badge-{{ $record['milk_quality_score'] >= 8 ? 'success' : ($record['milk_quality_score'] >= 6 ? 'warning' : 'danger') }}">
                                {{ $record['milk_quality_score'] ?? 'N/A' }}/10
                            </span>
                        </td>
                        <td>{{ Str::limit($record['notes'] ?? 'No notes', 30) }}</td>
                        <td>
                            <button class="btn btn-info btn-sm mr-1" onclick="viewRecord({{ $record['id'] }})" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-warning btn-sm mr-1" onclick="editRecord({{ $record['id'] }})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $record['id'] }})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-chart-line fa-3x mb-3 text-muted"></i>
                            <p>No production records available.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Production Record Modal -->
<div class="modal fade" id="addProductionModal" tabindex="-1" role="dialog" aria-labelledby="addProductionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductionModalLabel">
                    <i class="fas fa-plus"></i>
                    Add Production Record
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('farmer.production.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="production_date">Production Date *</label>
                                <input type="date" class="form-control" id="production_date" name="production_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="livestock_id">Livestock *</label>
                                <select class="form-control" id="livestock_id" name="livestock_id" required>
                                    <option value="">Select Livestock</option>
                                    @foreach($livestockList as $livestock)
                                        <option value="{{ $livestock->id }}">{{ $livestock->name }} ({{ $livestock->tag_number }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="milk_quantity">Milk Quantity (L) *</label>
                                <input type="number" step="0.1" class="form-control" id="milk_quantity" name="milk_quantity" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="milk_quality_score">Quality Score (1-10)</label>
                                <select class="form-control" id="milk_quality_score" name="milk_quality_score">
                                    <option value="">Select Quality</option>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional notes about this production record..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteLabel">
                    <i class="fas fa-exclamation-triangle"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this production record? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel">
                    <i class="fas fa-history"></i>
                    Production History
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="sortHistory" class="font-weight-bold">Sort By:</label>
                        <select id="sortHistory" class="form-control form-control-sm" onchange="loadHistory()">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="filterHistory" class="font-weight-bold">Filter By:</label>
                        <select id="filterHistory" class="form-control form-control-sm" onchange="loadHistory()">
                            <option value="all">All</option>
                            <option value="high_quality">High Quality (8-10)</option>
                            <option value="medium_quality">Medium Quality (5-7)</option>
                            <option value="low_quality">Low Quality (1-4)</option>
                        </select>
                    </div>
                </div>
                <div id="historyContent" class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Livestock</th>
                                <th>Milk Quantity (L)</th>
                                <th>Quality Score</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody">
                            <!-- Production history will be dynamically populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="exportHistory()">
                    <i class="fas fa-download"></i> Export History
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    // DataTable initialization disabled to prevent column count warnings
    // The table will function as a standard HTML table with Bootstrap styling
    console.log('Production table loaded successfully');

    // Production Trend Chart
    const productionTrendCtx = document.getElementById('productionTrendChart').getContext('2d');
    new Chart(productionTrendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($productionStats['monthly_trend']->pluck('month')) !!},
            datasets: [{
                label: 'Production (L)',
                data: {!! json_encode($productionStats['monthly_trend']->pluck('production')) !!},
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
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
                    ticks: {
                        callback: function(value) {
                            return value + ' L';
                        }
                    }
                }
            }
        }
    });

    // Quality Distribution Chart
    const qualityDistributionCtx = document.getElementById('qualityDistributionChart').getContext('2d');
    new Chart(qualityDistributionCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($productionStats['quality_distribution']->pluck('score')->map(function($score) { return 'Score ' . $score; })) !!},
            datasets: [{
                data: {!! json_encode($productionStats['quality_distribution']->pluck('count')) !!},
                backgroundColor: [
                    '#e74a3b', '#f6c23e', '#1cc88a', '#36b9cc', '#4e73df',
                    '#6f42c1', '#fd7e14', '#20c9a6', '#5a5c69', '#858796'
                ],
                hoverBackgroundColor: [
                    '#e74a3b', '#f6c23e', '#1cc88a', '#36b9cc', '#4e73df',
                    '#6f42c1', '#fd7e14', '#20c9a6', '#5a5c69', '#858796'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});

function confirmDelete(recordId) {
    $('#confirmDeleteModal').modal('show');
    $('#confirmDeleteBtn').off('click').on('click', function() {
        // Send delete request
        fetch(`/farmer/production/${recordId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Production record deleted successfully!');
                location.reload();
            } else {
                showAlert('danger', 'Failed to delete record. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred. Please try again.');
        });
        
        $('#confirmDeleteModal').modal('hide');
    });
}

function loadHistory() {
    const sortBy = document.getElementById('sortHistory').value;
    const filterBy = document.getElementById('filterHistory').value;
    
    // Fetch filtered history data
    fetch(`/farmer/production/history?sort=${sortBy}&filter=${filterBy}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tbody = document.getElementById('historyTableBody');
                tbody.innerHTML = '';
                
                data.records.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${new Date(record.production_date).toLocaleDateString()}</td>
                        <td>${record.livestock?.name || 'Unknown'}</td>
                        <td>${record.milk_quantity}</td>
                        <td>
                            <span class="badge badge-${record.milk_quality_score >= 8 ? 'success' : (record.milk_quality_score >= 6 ? 'warning' : 'danger')}">
                                ${record.milk_quality_score || 'N/A'}/10
                            </span>
                        </td>
                        <td>${record.notes || 'No notes'}</td>
                    `;
                    tbody.appendChild(row);
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Failed to load history data.');
        });
}

function exportCSV() {
    // Implement CSV export functionality
    showAlert('info', 'CSV export functionality will be implemented soon.');
}

function exportPDF() {
    // Implement PDF export functionality
    showAlert('info', 'PDF export functionality will be implemented soon.');
}

function exportPNG() {
    // Implement PNG export functionality
    showAlert('info', 'PNG export functionality will be implemented soon.');
}

function printProductivity() {
    window.print();
}

function exportHistory() {
    // Implement history export functionality
    showAlert('info', 'History export functionality will be implemented soon.');
}

function viewRecord(recordId) {
    // Implement view record functionality
    showAlert('info', 'View record functionality will be implemented soon.');
}

function editRecord(recordId) {
    // Implement edit record functionality
    showAlert('info', 'Edit record functionality will be implemented soon.');
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
        </button>
    `;
    
    document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.page-header'));
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
@endpush
