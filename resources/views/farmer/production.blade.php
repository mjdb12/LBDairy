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
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">No production data available</td>
                                <td class="text-center text-muted">N/A</td>
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
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold">
            <i class="fas fa-table"></i>
            Production Records
        </h6>
    </div>
    <div class="card-body">
        <!-- Search (left) + Actions (right) -->
        <div class="search-controls mb-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch">
                <div class="input-group" style="max-width: 380px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <input type="text" id="productionSearch" class="form-control" placeholder="Search production records...">
                </div>
                <div class="btn-group d-flex gap-2 align-items-center mt-2 mt-sm-0">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addProductionModal">
                        <i class="fas fa-plus"></i> Add Record
                    </button>
                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#historyModal">
                        <i class="fas fa-history"></i> History
                    </button>
                    <button class="btn btn-secondary btn-sm" onclick="printProductionTable()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button class="btn btn-warning btn-sm" onclick="refreshProductionTable()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportCSV()">
                                <i class="fas fa-file-csv"></i> Download CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPNG()">
                                <i class="fas fa-image"></i> Download PNG
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPDF()">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                        <td class="text-center text-muted">N/A</td>
                        <td class="text-center text-muted">N/A</td>
                        <td class="text-center text-muted">N/A</td>
                        <td class="text-center text-muted">N/A</td>
                        <td class="text-center text-muted">No production records available</td>
                        <td class="text-center text-muted">N/A</td>
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
            <form id="addProductionForm" action="{{ route('farmer.production.store') }}" method="POST">
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
                    <button type="submit" id="saveProductionBtn" class="btn btn-primary">
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
                        <label for="yearHistory" class="font-weight-bold">Year:</label>
                        <select id="yearHistory" class="form-control form-control-sm" onchange="loadHistory()">
                            @php($currentYear = (int)date('Y'))
                            @for($y = $currentYear; $y >= $currentYear - 5; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="text-muted small">Showing quarterly aggregates</div>
                    </div>
                </div>
                <div id="historyContent" class="table-responsive">
                    <table class="table table-bordered" id="historyQuarterTable">
                        <thead>
                            <tr>
                                <th>Quarter</th>
                                <th>Total Production (L)</th>
                                <th>Average Quality</th>
                                <th>Records</th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody">
                            <!-- Quarterly history will be dynamically populated here -->
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
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- Required libraries for PDF/Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- jsPDF and autoTable for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
let productionDT = null;
$(document).ready(function() {
    const productionStoreAction = $('#addProductionForm').attr('action');
    // Initialize DataTable for Production Records
    const commonConfig = {
        dom: 'Bfrtip',
        searching: true,
        paging: true,
        info: true,
        ordering: true,
        lengthChange: false,
        pageLength: 10,
        buttons: [
            { extend: 'csvHtml5', title: 'Farmer_Production_Report', className: 'd-none' },
            { extend: 'pdfHtml5', title: 'Farmer_Production_Report', orientation: 'landscape', pageSize: 'Letter', className: 'd-none' },
            { extend: 'print', title: 'Farmer Production Report', className: 'd-none' }
        ],
        language: { search: "", emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No data available</h5><p>There are no records to display at this time.</p></div>' }
    };

    if ($('#productionTable').length) {
        try {
            productionDT = $('#productionTable').DataTable({
                ...commonConfig,
                order: [[0, 'desc']],
                columnDefs: [
                    { width: '120px', targets: 0 },
                    { width: '220px', targets: 1 },
                    { width: '140px', targets: 2 },
                    { width: '140px', targets: 3 },
                    { width: '260px', targets: 4 },
                    { width: '160px', targets: 5, orderable: false }
                ]
            });
        } catch (e) {
            console.error('Failed to initialize Production DataTable:', e);
        }
    }

    // Hide default DataTables search and buttons; wire custom search
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
    $('#productionSearch').on('keyup', function(){
        if (productionDT) productionDT.search(this.value).draw();
    });

    // Reset form to create mode when modal closes
    $('#addProductionModal').on('hidden.bs.modal', function(){
        resetProductionFormMode();
        $('#addProductionForm')[0].reset();
        $('#production_date').val('{{ date('Y-m-d') }}');
    });

    // Load history whenever the modal opens
    $('#historyModal').on('shown.bs.modal', function(){
        try { loadHistory(); } catch(e){ console.error('loadHistory error:', e); }
    });

    function resetProductionFormMode(){
        $('#addProductionForm').attr('action', productionStoreAction);
        $('#addProductionForm').find('input[name="_method"]').remove();
        $('#addProductionModalLabel').html('<i class="fas fa-plus"></i> Add Production Record');
        $('#saveProductionBtn').html('<i class="fas fa-save"></i> Save Record');
    }

    // Production Trend Chart
    const productionTrendCtx = document.getElementById('productionTrendChart').getContext('2d');
    new Chart(productionTrendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(($productionStats['monthly_trend'] ?? collect())->pluck('month')) !!},
            datasets: [{
                label: 'Production (L)',
                data: {!! json_encode(($productionStats['monthly_trend'] ?? collect())->pluck('production')) !!},
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
            labels: {!! json_encode(($productionStats['quality_distribution'] ?? collect())->pluck('score')->map(function($score) { return 'Score ' . $score; })) !!},
            datasets: [{
                data: {!! json_encode(($productionStats['quality_distribution'] ?? collect())->pluck('count')) !!},
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
    const year = document.getElementById('yearHistory').value;
    // Fetch quarterly aggregates
    fetch(`/farmer/production/history?mode=quarterly&year=${year}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.mode === 'quarterly') {
                const tbody = document.getElementById('historyTableBody');
                tbody.innerHTML = '';
                const quarters = Array.isArray(data.quarters) ? data.quarters : [];
                if (quarters.length) {
                    quarters.forEach(q => {
                        const label = `Q${q.quarter} ${q.year}`;
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${label}</td>
                            <td>${Number(q.total_production).toLocaleString(undefined, { maximumFractionDigits: 1 })}</td>
                            <td>${q.avg_quality != null ? (Number(q.avg_quality).toFixed(1) + '/10') : 'N/A'}</td>
                            <td>${q.records}</td>
                        `;
                        tbody.appendChild(row);
                    });
                } else {
                    const row = document.createElement('tr');
                    row.innerHTML = '<td colspan="4" class="text-center text-muted">No quarterly data for the selected year.</td>';
                    tbody.appendChild(row);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Failed to load history data.');
        });
}

function exportCSV() {
    try {
        if (!productionDT) return showAlert('danger', 'Table is not ready.');
        const rows = productionDT.data().toArray();
        const headers = ['Date','Livestock','Milk Quantity (L)','Quality Score','Notes'];
        const csv = [headers.join(',')];
        rows.forEach(r => {
            const arr = [];
            for (let i = 0; i < r.length - 1; i++) { // exclude Actions
                const tmp = document.createElement('div'); tmp.innerHTML = r[i];
                let t = tmp.textContent || tmp.innerText || '';
                t = t.replace(/\s+/g, ' ').trim();
                if (t.includes(',') || t.includes('"') || t.includes('\n')) t = '"' + t.replace(/"/g, '""') + '"';
                arr.push(t);
            }
            csv.push(arr.join(','));
        });
        const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
        const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = `Farmer_ProductionReport_${Date.now()}.csv`; a.click();
        showAlert('success', 'CSV exported successfully!');
    } catch (e) { console.error('CSV export error:', e); showAlert('danger', 'Error generating CSV.'); }
}

function exportPDF() {
    try {
        if (!productionDT) return showAlert('danger', 'Table is not ready.');
        const rows = productionDT.data().toArray();
        const data = rows.map(r => [r[0]||'', r[1]||'', r[2]||'', r[3]||'', r[4]||'']);
        const headers = ['Date','Livestock','Milk Quantity (L)','Quality Score','Notes'];
        const { jsPDF } = window.jspdf; const doc = new jsPDF('landscape','mm','a4');
        doc.setFontSize(18); doc.text('Farmer Production Report', 14, 22);
        doc.setFontSize(12); doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 14, 32);
        doc.autoTable({ head: [headers], body: data, startY: 40, styles: { fontSize: 8, cellPadding: 2 }, headStyles: { fillColor: [24,55,93], textColor: 255, fontStyle: 'bold' }, alternateRowStyles: { fillColor: [245,245,245] } });
        doc.save(`Farmer_ProductionReport_${Date.now()}.pdf`);
        showAlert('success', 'PDF exported successfully!');
    } catch (error) { console.error('Error generating PDF:', error); showAlert('danger', 'Error generating PDF.'); }
}

function viewRecord(recordId) {
    // Load and display production record details
    $.ajax({
        url: `/farmer/production/${recordId}`,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            const record = response && response.success ? response.record : response;
            if (record) {
                const modalHtml = `
                    <div class="modal fade" id="viewRecordModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-eye"></i>
                                        Production Record Details
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Production Information</h6>
                                            <p><strong>Date:</strong> ${record.production_date || ''}</p>
                                            <p><strong>Livestock:</strong> ${record.livestock_name ? `${record.livestock_name} (${record.livestock_tag||''})` : `ID ${record.livestock_id||''}`}</p>
                                            <p><strong>Quantity:</strong> ${record.milk_quantity} L</p>
                                            <p><strong>Quality Score:</strong> ${record.milk_quality_score}/10</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Additional Details</h6>
                                            <p><strong>Farm:</strong> ${record.farm_name || ''}</p>
                                            <p><strong>Notes:</strong> ${record.notes || 'No notes'}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                $('#viewRecordModal').remove();
                $('body').append(modalHtml);
                $('#viewRecordModal').modal('show');
            }
        },
        error: function() {
            showAlert('error', 'Failed to load record details.');
        }
    });
}

function editRecord(recordId) {
    // Load record data for editing
    $.ajax({
        url: `/farmer/production/${recordId}`,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            const record = response && response.success ? response.record : response;
            if (record) {
                // Populate the add production modal with existing data
                $('#addProductionModal').modal('show');
                // Set date string directly to avoid timezone shifts
                $('#production_date').val(record.production_date || '');
                // Ensure option exists in select even if livestock inactive
                if (record.livestock_id) {
                    const sel = $('#livestock_id');
                    if (!sel.find(`option[value="${record.livestock_id}"]`).length) {
                        const label = (record.livestock_name || 'Livestock') + (record.livestock_tag ? ` (${record.livestock_tag})` : '');
                        sel.append(`<option value="${record.livestock_id}">${label}</option>`);
                    }
                    sel.val(record.livestock_id).trigger('change');
                } else {
                    $('#livestock_id').val('').trigger('change');
                }
                $('#milk_quantity').val(record.milk_quantity || '');
                $('#milk_quality_score').val(record.milk_quality_score || '').trigger('change');
                $('#notes').val(record.notes || '');

                // Change form action to update with method spoofing
                $('#addProductionForm').attr('action', `/farmer/production/${recordId}`);
                const methodInput = $('#addProductionForm').find('input[name="_method"]');
                if (methodInput.length) { methodInput.val('PUT'); }
                else { $('#addProductionForm').prepend('<input type="hidden" name="_method" value="PUT">'); }
                $('#addProductionModalLabel').html('<i class="fas fa-edit"></i> Edit Production Record');
                $('#saveProductionBtn').html('<i class="fas fa-save"></i> Update Record');
            }
        },
        error: function() {
            showAlert('error', 'Failed to load record for editing.');
        }
    });
}

function confirmDelete(recordId) {
    if (confirm('Are you sure you want to delete this production record? This action cannot be undone.')) {
        deleteRecord(recordId);
    }
}

function deleteRecord(recordId) {
    $.ajax({
        url: `/farmer/production/${recordId}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showAlert('success', 'Production record deleted successfully!');
                location.reload();
            } else {
                showAlert('error', 'Failed to delete record.');
            }
        },
        error: function() {
            showAlert('error', 'Failed to delete record.');
        }
    });
}

function exportPNG() {
    // Create a temporary table without the Actions column for export
    const originalTable = document.getElementById('productionTable');
    const tempTable = originalTable.cloneNode(true);
    
    // Remove the Actions column header
    const headerRow = tempTable.querySelector('thead tr');
    if (headerRow) {
        const lastHeaderCell = headerRow.lastElementChild;
        if (lastHeaderCell) {
            lastHeaderCell.remove();
        }
    }
    
    // Remove the Actions column from all data rows
    const dataRows = tempTable.querySelectorAll('tbody tr');
    dataRows.forEach(row => {
        const lastDataCell = row.lastElementChild;
        if (lastDataCell) {
            lastDataCell.remove();
        }
    });
    
    // Place temp table inside an offscreen container so layout computes size
    const offscreen = document.createElement('div');
    offscreen.style.position = 'absolute';
    offscreen.style.left = '-9999px';
    offscreen.style.top = '0';
    offscreen.style.background = '#ffffff';
    offscreen.appendChild(tempTable);
    document.body.appendChild(offscreen);
    // Match width to original table for proper rendering
    tempTable.style.width = originalTable.offsetWidth + 'px';
    
    // Generate PNG using html2canvas
    html2canvas(tempTable, {
        scale: 2,
        backgroundColor: '#ffffff',
        useCORS: true,
        logging: false,
        windowWidth: tempTable.scrollWidth,
        windowHeight: tempTable.scrollHeight
    }).then(canvas => {
        // Create download link
        const link = document.createElement('a');
        link.download = `Farmer_ProductionReport_${Date.now()}.png`;
        link.href = canvas.toDataURL("image/png");
        link.click();
        
        // Clean up - remove temporary container
        document.body.removeChild(offscreen);
        
        showAlert('success', 'PNG exported successfully!');
    }).catch(error => {
        console.error('Error generating PNG:', error);
        // Clean up on error
        if (document.body.contains(offscreen)) {
            document.body.removeChild(offscreen);
        }
        showAlert('error', 'Error generating PNG export');
    });
}

function printProductionTable() {
    try { if (productionDT) productionDT.button('.buttons-print').trigger(); else window.print(); }
    catch(e){ console.error('printProductionTable error:', e); window.print(); }
}

function refreshProductionTable(){
    const btn = document.querySelector('.btn.btn-warning.btn-sm');
    if (btn){ btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...'; }
    sessionStorage.setItem('showRefreshNotificationProduction','true');
    setTimeout(()=>location.reload(), 800);
}

$(document).ready(function(){
    if (sessionStorage.getItem('showRefreshNotificationProduction') === 'true'){
        sessionStorage.removeItem('showRefreshNotificationProduction');
        setTimeout(()=>showAlert('success', 'Data refreshed successfully!'), 400);
    }
});

function exportHistory() {
    // Export quarterly table to CSV
    try {
        const year = document.getElementById('yearHistory').value;
        const rows = [];
        rows.push(['Quarter','Total Production (L)','Average Quality','Records'].join(','));
        document.querySelectorAll('#historyQuarterTable tbody tr').forEach(tr => {
            const cells = Array.from(tr.querySelectorAll('td')).map(td => (td.textContent||'').trim());
            if (cells.length === 4) rows.push(cells.join(','));
        });
        const blob = new Blob([rows.join('\n')], { type: 'text/csv;charset=utf-8;' });
        const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = `Production_Quarterly_${year}.csv`; a.click();
        showAlert('success', 'Quarterly history exported successfully!');
    } catch(e) { console.error('exportHistory error:', e); showAlert('danger', 'Failed to export history.'); }
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
