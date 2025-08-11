@extends('layouts.app')

@section('title', 'Productivity Analysis')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-chart-line"></i>
            Productivity Analysis
        </h1>
        <p>Monitor and analyze farm productivity metrics and performance trends</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container fade-in">
        <div class="stat-card">
            <i class="fas fa-tractor stat-icon"></i>
            <h3>{{ $activeFarmsCount }}</h3>
            <p>Active Farms</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-chart-bar stat-icon"></i>
            <h3>{{ number_format($avgProductivity, 1) }}L</h3>
            <p>Avg Daily Production</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-trophy stat-icon"></i>
            <h3>{{ $topProducer ?? 'N/A' }}</h3>
            <p>Top Producer</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-users stat-icon"></i>
            <h3>{{ $totalFarmers }}</h3>
            <p>Total Farmers</p>
        </div>
    </div>

    <!-- Farmers List Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header">
            <h6>
                <i class="fas fa-users"></i>
                Farmer Productivity Overview
            </h6>
            <div class="table-controls">
                <div class="search-container">
                    <input type="text" class="form-control custom-search" placeholder="Search farmers...">
                </div>
                <div class="export-controls">
                    <div class="btn-group">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportCSV()">
                                <i class="fas fa-file-csv"></i> CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPNG()">
                                <i class="fas fa-image"></i> PNG
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPDF()">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                        </div>
                    </div>
                    <button class="btn btn-secondary btn-sm" onclick="printTable()">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Farmer ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($farmers as $farmer)
                        <tr>
                            <td>
                                <a href="#" onclick="openDetailsModal('{{ $farmer->id }}')">{{ $farmer->farmer_id ?? 'F' . str_pad($farmer->id, 3, '0', STR_PAD_LEFT) }}</a>
                            </td>
                            <td>{{ $farmer->name }}</td>
                            <td>{{ $farmer->email }}</td>
                            <td>{{ $farmer->phone ?? 'N/A' }}</td>
                            <td>{{ $farmer->location ?? 'N/A' }}</td>
                            <td>
                                <select class="form-control" onchange="updateActivity(this, '{{ $farmer->id }}')">
                                    <option value="active" {{ $farmer->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $farmer->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-info btn-sm" onclick="viewFarmerDetails('{{ $farmer->id }}')" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteFarmer('{{ $farmer->id }}')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-chart-line"></i>
                                    <h5>No productivity data available</h5>
                                    <p>There are no farms to analyze at this time.</p>
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

<!-- Productivity Analysis Modal -->
<div class="modal fade" id="productivityModal" tabindex="-1" aria-labelledby="productivityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productivityModalLabel">
                    <i class="fas fa-chart-line"></i>
                    Farm Productivity Analysis
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h6 class="mb-1"><strong>Farm ID:</strong> <span id="modalFarmId" class="text-primary">F001</span></h6>
                        <p class="text-muted mb-0">Detailed productivity metrics and trends</p>
                    </div>
                    <div class="export-controls">
                        <div class="btn-group">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="fas fa-download"></i> Export Report
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="exportModalCSV()">
                                    <i class="fas fa-file-csv"></i> CSV
                                </a>
                                <a class="dropdown-item" href="#" onclick="exportModalPNG()">
                                    <i class="fas fa-image"></i> PNG
                                </a>
                                <a class="dropdown-item" href="#" onclick="exportModalPDF()">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </div>
                        <button class="btn btn-secondary btn-sm ml-2" onclick="printProductivity()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
                
                <div class="chart-container">
                    <canvas id="lineChart"></canvas>
                </div>
                
                <div class="analysis-text" id="analysisText">
                    <strong><i class="fas fa-lightbulb text-warning"></i> Analysis:</strong>
                    <span id="analysisContent">Loading analysis...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Farmer Details Modal -->
<div class="modal fade" id="farmerDetailsModal" tabindex="-1" aria-labelledby="farmerDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="farmerDetailsModalLabel">
                    <i class="fas fa-user"></i>
                    Farmer Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><strong>Personal Information</strong></h6>
                        <p><strong>Name:</strong> <span id="modalFarmerName">-</span></p>
                        <p><strong>Email:</strong> <span id="modalFarmerEmail">-</span></p>
                        <p><strong>Phone:</strong> <span id="modalFarmerPhone">-</span></p>
                        <p><strong>Location:</strong> <span id="modalFarmerLocation">-</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6><strong>Farm Statistics</strong></h6>
                        <p><strong>Total Livestock:</strong> <span id="modalTotalLivestock">-</span></p>
                        <p><strong>Active Livestock:</strong> <span id="modalActiveLivestock">-</span></p>
                        <p><strong>Total Production:</strong> <span id="modalTotalProduction">-</span></p>
                        <p><strong>Status:</strong> <span id="modalFarmerStatus">-</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                    Confirm Deletion
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this farmer? This action cannot be undone.</p>
                <p><strong>Farmer ID:</strong> <span id="deleteFarmerId">-</span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteFarmerForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Stats Cards */
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
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }

    .stat-card h3 {
        font-size: 2rem;
        font-weight: 700;
        color: #4e73df;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .stat-card p {
        color: #5a5c69;
        margin: 0.5rem 0 0 0;
        font-weight: 500;
        position: relative;
        z-index: 1;
    }

    .stat-card .stat-icon {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 2rem;
        color: rgba(78, 115, 223, 0.2);
        z-index: 1;
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, #4e73df 0%, #3c5aa6 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
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

    /* Table Controls */
    .table-controls {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
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
        width: 100%;
    }

    .search-container input:focus {
        border-color: #4e73df;
        background: white;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        outline: none;
    }

    .search-container::before {
        content: '\f002';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #5a5c69;
        z-index: 1;
    }

    .export-controls {
        display: flex;
        gap: 0.5rem;
        margin-left: auto;
    }

    /* Chart Container */
    .chart-container {
        position: relative;
        height: 400px;
        margin-bottom: 2rem;
    }

    /* Analysis Text */
    .analysis-text {
        background: #f8f9fc;
        padding: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid #f6c23e;
        line-height: 1.6;
    }

    /* Animations */
    .fade-in {
        animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #5a5c69;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>
@endpush

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
let dataTable;
let chartInstance;

$(document).ready(function () {
    // Initialize DataTable
    dataTable = $('#dataTable').DataTable({
        dom: 'Bfrtip',
        searching: true,
        paging: true,
        info: true,
        ordering: true,
        lengthChange: false,
        pageLength: 10,
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Farmers_Productivity_Report',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Farmers_Productivity_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Farmers Productivity Report',
                className: 'd-none'
            }
        ],
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-chart-line"></i><h5>No productivity data available</h5><p>There are no farms to analyze at this time.</p></div>'
        }
    });

    // Custom search functionality
    $('.custom-search').on('keyup', function() {
        dataTable.search(this.value).draw();
    });

    // Hide default DataTables elements
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
});

function openDetailsModal(farmerId) {
    // Fetch farmer productivity data
    fetch(`{{ route('admin.analysis.farmer-data', ':id') }}`.replace(':id', farmerId))
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update modal farm ID text
            document.getElementById('modalFarmId').innerText = data.farmer.farmer_id || 'F' + String(data.farmer.id).padStart(3, '0');

            // Update analysis text
            document.getElementById('analysisContent').innerHTML = data.analysis || 'No analysis available for this farmer.';

            // Destroy previous chart instance if exists
            if (chartInstance) {
                chartInstance.destroy();
            }

            // Create new chart with farmer data
            const ctx = document.getElementById('lineChart').getContext('2d');
            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.chartData.labels || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Milk Production (liters)',
                        data: data.chartData.data || [0, 0, 0, 0, 0, 0],
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    size: 14,
                                    weight: '500'
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    elements: {
                        point: {
                            hoverBackgroundColor: 'rgba(78, 115, 223, 1)'
                        }
                    }
                }
            });

            // Show the modal
            $('#productivityModal').modal('show');
        } else {
            showNotification('Failed to load farmer data', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while loading farmer data', 'error');
    });
}

function viewFarmerDetails(farmerId) {
    // Fetch farmer details
    fetch(`{{ route('admin.analysis.farmer-details', ':id') }}`.replace(':id', farmerId))
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const farmer = data.farmer;
            document.getElementById('modalFarmerName').textContent = farmer.name || 'N/A';
            document.getElementById('modalFarmerEmail').textContent = farmer.email || 'N/A';
            document.getElementById('modalFarmerPhone').textContent = farmer.phone || 'N/A';
            document.getElementById('modalFarmerLocation').textContent = farmer.location || 'N/A';
            document.getElementById('modalTotalLivestock').textContent = data.stats.total_livestock || '0';
            document.getElementById('modalActiveLivestock').textContent = data.stats.active_livestock || '0';
            document.getElementById('modalTotalProduction').textContent = (data.stats.total_production || '0') + 'L';
            document.getElementById('modalFarmerStatus').textContent = farmer.status || 'N/A';
            
            $('#farmerDetailsModal').modal('show');
        } else {
            showNotification('Failed to load farmer details', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while loading farmer details', 'error');
    });
}

function updateActivity(selectElement, farmerId) {
    const status = selectElement.value;
    const originalValue = selectElement.getAttribute('data-original-value') || selectElement.value;
    
    fetch(`{{ route('admin.analysis.update-status', ':id') }}`.replace(':id', farmerId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(`Farmer status updated to ${status}`, 'success');
            selectElement.setAttribute('data-original-value', status);
        } else {
            showNotification('Failed to update status', 'error');
            selectElement.value = originalValue;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
        selectElement.value = originalValue;
    });
}

function deleteFarmer(farmerId) {
    document.getElementById('deleteFarmerId').textContent = 'F' + String(farmerId).padStart(3, '0');
    document.getElementById('deleteFarmerForm').action = `{{ route('admin.analysis.delete-farmer', ':id') }}`.replace(':id', farmerId);
    $('#confirmDeleteModal').modal('show');
}

// Export functions
function exportCSV() { dataTable.button('.buttons-csv').trigger(); }
function exportPDF() { dataTable.button('.buttons-pdf').trigger(); }
function printTable() { dataTable.button('.buttons-print').trigger(); }

function exportPNG() {
    html2canvas(document.querySelector("#dataTable")).then(canvas => {
        let link = document.createElement('a');
        link.download = 'Farmers_Productivity_Report.png';
        link.href = canvas.toDataURL("image/png");
        link.click();
    });
}

function exportModalCSV() {
    const farmId = $('#modalFarmId').text();
    const chart = chartInstance;
    if (!chart) return;
    
    const labels = chart.data.labels;
    const data = chart.data.datasets[0].data;
    const headers = ['Month', 'Milk Production (liters)'];
    const rows = labels.map((month, i) => [month, data[i]]);

    let csvContent = "data:text/csv;charset=utf-8,"
        + headers.join(",") + "\n"
        + rows.map(e => e.join(",")).join("\n");

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", farmId + "_production_analysis.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function exportModalPNG() {
    const canvas = document.getElementById('lineChart');
    const link = document.createElement('a');
    link.download = 'production_analysis_chart.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
}

async function exportModalPDF() {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF();

    pdf.setFontSize(18);
    pdf.text("Farm Productivity Analysis", 14, 22);

    const farmId = $('#modalFarmId').text();
    pdf.setFontSize(14);
    pdf.text(`Farm ID: ${farmId}`, 14, 35);

    const canvas = document.getElementById('lineChart');
    const imgData = canvas.toDataURL('image/png');
    pdf.addImage(imgData, 'PNG', 15, 45, 180, 100);

    const analysisText = document.getElementById('analysisContent').textContent;
    pdf.setFontSize(12);
    const splitText = pdf.splitTextToSize(analysisText, 180);
    pdf.text(splitText, 14, 155);

    pdf.save(farmId + '_production_analysis.pdf');
}

function printProductivity() {
    const modalBody = document.querySelector('#productivityModal .modal-body').innerHTML;
    const originalContents = document.body.innerHTML;

    document.body.innerHTML = `
        <div style="padding: 20px;">
            <h2>Farm Productivity Analysis</h2>
            ${modalBody}
        </div>
    `;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}

function showNotification(message, type) {
    const notification = $(`
        <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
             style="top: 100px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'times-circle'}"></i>
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `);
    
    $('body').append(notification);
    
    setTimeout(() => {
        notification.alert('close');
    }, 5000);
}
</script>
@endpush
