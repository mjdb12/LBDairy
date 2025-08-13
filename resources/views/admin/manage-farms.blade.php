@extends('layouts.app')

@section('title', 'Farm Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-university"></i>
            Farm Management
        </h1>
        <p>Monitor and manage registered farms, owners, and operational status</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container fade-in">
        <div class="stat-card">
            <i class="fas fa-check-circle stat-icon"></i>
            <h3>{{ $activeFarmsCount }}</h3>
            <p>Active Farms</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-times-circle stat-icon"></i>
            <h3>{{ $inactiveFarmsCount }}</h3>
            <p>Inactive Farms</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-university stat-icon"></i>
            <h3>{{ $totalFarmsCount }}</h3>
            <p>Total Farms</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-map-marker-alt stat-icon"></i>
            <h3>{{ $barangayCount }}</h3>
            <p>Barangays Covered</p>
        </div>
    </div>

    <!-- Farm Management Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header">
            <h6>
                <i class="fas fa-list"></i>
                Farm Directory
            </h6>
            <div class="table-controls">
                <div class="search-container">
                    <input type="text" class="form-control custom-search" placeholder="Search farms...">
                </div>
                <div class="export-controls">
                    <div class="btn-group">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
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
                    <button class="btn btn-secondary btn-sm" onclick="printTable()">
                        <i class="fas fa-print"></i>
                    </button>
                    <button class="btn btn-primary btn-sm" onclick="document.getElementById('csvInput').click()">
                        <i class="fas fa-file-import"></i> Import CSV
                    </button>
                    <input type="file" id="csvInput" accept=".csv" style="display: none;" onchange="importCSV(event)">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Farm ID</th>
                            <th>Owner Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Barangay</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($farms as $farm)
                        <tr>
                            <td>
                                <a href="#" class="farm-id-link" onclick="openDetailsModal('{{ $farm->id }}')">
                                    {{ $farm->farm_id ?? 'FS' . str_pad($farm->id, 3, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                                                            <td>{{ $farm->owner->name ?? 'N/A' }}</td>
                                <td>{{ $farm->owner->email ?? 'N/A' }}</td>
                            <td>{{ $farm->phone ?? 'N/A' }}</td>
                            <td>{{ $farm->location ?? 'N/A' }}</td>
                            <td>
                                <select class="form-control" onchange="updateActivity(this, '{{ $farm->id }}')">
                                    <option value="active" {{ $farm->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $farm->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-info btn-sm" onclick="viewFarmDetails('{{ $farm->id }}')" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $farm->id }}')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-university"></i>
                                    <h5>No farms found</h5>
                                    <p>There are no farms to display at this time.</p>
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
                <p>Are you sure you want to delete this farm? This action cannot be undone.</p>
                <p><strong>Farm ID:</strong> <span id="deleteFarmId">-</span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteFarmForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Yes, Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Farm Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">
                    <i class="fas fa-university"></i>
                    Farm Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="farmDetails">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><strong>Farm Information</strong></h6>
                            <p><strong>Farm ID:</strong> <span id="modalFarmId">-</span></p>
                            <p><strong>Farm Name:</strong> <span id="modalFarmName">-</span></p>
                            <p><strong>Location:</strong> <span id="modalFarmLocation">-</span></p>
                            <p><strong>Status:</strong> <span id="modalFarmStatus">-</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Owner Information</strong></h6>
                            <p><strong>Owner Name:</strong> <span id="modalOwnerName">-</span></p>
                            <p><strong>Email:</strong> <span id="modalOwnerEmail">-</span></p>
                            <p><strong>Phone:</strong> <span id="modalOwnerPhone">-</span></p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6><strong>Farm Statistics</strong></h6>
                            <p><strong>Total Livestock:</strong> <span id="modalTotalLivestock">-</span></p>
                            <p><strong>Active Livestock:</strong> <span id="modalActiveLivestock">-</span></p>
                            <p><strong>Total Production:</strong> <span id="modalTotalProduction">-</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Additional Details</strong></h6>
                            <p><strong>Registration Date:</strong> <span id="modalRegistrationDate">-</span></p>
                            <p><strong>Last Updated:</strong> <span id="modalLastUpdated">-</span></p>
                        </div>
                    </div>
                </div>
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

    /* Farm ID Link */
    .farm-id-link {
        color: #4e73df;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .farm-id-link:hover {
        color: #3c5aa6;
        text-decoration: underline;
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
                title: 'Farm_Management_Report',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Farm_Management_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Farm Management Report',
                className: 'd-none'
            }
        ],
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-university"></i><h5>No farms found</h5><p>There are no farms to display at this time.</p></div>'
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

function openDetailsModal(farmId) {
    viewFarmDetails(farmId);
}

function viewFarmDetails(farmId) {
    // Fetch farm details
    fetch(`{{ route('admin.farms.show', ':id') }}`.replace(':id', farmId))
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const farm = data.farm;
            document.getElementById('modalFarmId').textContent = farm.farm_id || 'FS' + String(farm.id).padStart(3, '0', STR_PAD_LEFT);
            document.getElementById('modalFarmName').textContent = farm.name || 'N/A';
            document.getElementById('modalFarmLocation').textContent = farm.location || 'N/A';
            document.getElementById('modalFarmStatus').textContent = farm.status || 'N/A';
            document.getElementById('modalOwnerName').textContent = farm.owner_name || farm.user?.name || 'N/A';
            document.getElementById('modalOwnerEmail').textContent = farm.user?.email || 'N/A';
            document.getElementById('modalOwnerPhone').textContent = farm.phone || 'N/A';
            document.getElementById('modalTotalLivestock').textContent = data.stats.total_livestock || '0';
            document.getElementById('modalActiveLivestock').textContent = data.stats.active_livestock || '0';
            document.getElementById('modalTotalProduction').textContent = (data.stats.total_production || '0') + 'L';
            document.getElementById('modalRegistrationDate').textContent = farm.created_at ? new Date(farm.created_at).toLocaleDateString() : 'N/A';
            document.getElementById('modalLastUpdated').textContent = farm.updated_at ? new Date(farm.updated_at).toLocaleDateString() : 'N/A';
            
            $('#detailsModal').modal('show');
        } else {
            showNotification('Failed to load farm details', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while loading farm details', 'error');
    });
}

function updateActivity(selectElement, farmId) {
    const status = selectElement.value;
    const originalValue = selectElement.getAttribute('data-original-value') || selectElement.value;
    
    fetch(`{{ route('admin.farms.update-status', ':id') }}`.replace(':id', farmId), {
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
            showNotification(`Farm status updated to ${status}`, 'success');
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

function confirmDelete(farmId) {
    document.getElementById('deleteFarmId').textContent = 'FS' + String(farmId).padStart(3, '0', STR_PAD_LEFT);
    document.getElementById('deleteFarmForm').action = `{{ route('admin.farms.destroy', ':id') }}`.replace(':id', farmId);
    $('#confirmDeleteModal').modal('show');
}

// Export functions
function exportCSV() {
    dataTable.button('.buttons-csv').trigger();
}

function exportPDF() {
    dataTable.button('.buttons-pdf').trigger();
}

function printTable() {
    dataTable.button('.buttons-print').trigger();
}

function exportPNG() {
    html2canvas(document.querySelector("#dataTable")).then(canvas => {
        let link = document.createElement('a');
        link.download = 'Farm_Management_Report.png';
        link.href = canvas.toDataURL("image/png");
        link.click();
    });
}

function importCSV(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const csv = e.target.result;
        const lines = csv.split('\n');
        const headers = lines[0].split(',');
        
        // Process CSV data
        const farms = [];
        for (let i = 1; i < lines.length; i++) {
            if (lines[i].trim()) {
                const values = lines[i].split(',');
                const farm = {};
                headers.forEach((header, index) => {
                    farm[header.trim()] = values[index] ? values[index].trim() : '';
                });
                farms.push(farm);
            }
        }
        
        // Send to server for import
        importFarmsToServer(farms);
    };
    reader.readAsText(file);
}

function importFarmsToServer(farms) {
    fetch('{{ route("admin.farms.import") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ farms: farms })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(`Successfully imported ${data.imported_count} farms`, 'success');
            location.reload();
        } else {
            showNotification('Failed to import farms', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred during import', 'error');
    });
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
