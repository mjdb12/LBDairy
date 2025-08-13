@extends('layouts.app')

@section('title', 'LBDAIRY: SuperAdmin - Manage Farms')

@section('content')
<div class="container-fluid">
    <br><br><br><br>
    
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
            <div class="stat-number" style="color: var(--success-color);" id="activeFarmsCount">0</div>
            <div class="stat-label">Active Farms</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: var(--danger-color);" id="inactiveFarmsCount">0</div>
            <div class="stat-label">Inactive Farms</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: var(--info-color);" id="totalFarmsCount">0</div>
            <div class="stat-label">Total Farms</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: var(--warning-color);" id="barangayCount">0</div>
            <div class="stat-label">Barangays Covered</div>
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
                    <tbody id="farmsTableBody">
                        <!-- Farms will be loaded here -->
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

<!-- Details Modal -->
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
                <div id="farmDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables Core -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<!-- Required libraries for PDF/Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
let farmsTable;
let farmToDelete = null;

$(document).ready(function () {
    // Initialize DataTables
    initializeDataTables();
    
    // Load data
    loadFarms();
    updateStats();

    // Custom search functionality
    $('.custom-search').on('keyup', function() {
        farmsTable.search(this.value).draw();
    });
});

function initializeDataTables() {
    farmsTable = $('#dataTable').DataTable({
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
                title: 'Farms_Report',
                className: 'd-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Farms_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'd-none'
            },
            {
                extend: 'print',
                title: 'Farms Report',
                className: 'd-none'
            }
        ],
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No farms available</h5><p>There are no farms to display at this time.</p></div>'
        }
    });

    // Hide default DataTables elements
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
}

function loadFarms() {
    // Load farms from the database via AJAX
    $.ajax({
        url: '{{ route("superadmin.farms.index") }}',
        method: 'GET',
        success: function(response) {
            farmsTable.clear();
            
            response.data.forEach(farm => {
                const rowData = [
                    `<a href="#" class="farm-id-link" onclick="openDetailsModal('${farm.id}')">${farm.farm_id}</a>`,
                    `${farm.owner.first_name} ${farm.owner.last_name}`,
                    farm.owner.email,
                    farm.owner.contact_number,
                    farm.barangay,
                    `<select class="form-control" onchange="updateActivity(this, '${farm.id}')">
                        <option value="active" ${farm.status === 'active' ? 'selected' : ''}>Active</option>
                        <option value="inactive" ${farm.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                    </select>`,
                    `<button class="btn btn-danger btn-sm" onclick="confirmDelete('${farm.id}')">
                        <i class="fas fa-trash"></i>
                    </button>`
                ];
                
                farmsTable.row.add(rowData).draw(false);
            });
        },
        error: function(xhr) {
            console.error('Error loading farms:', xhr);
        }
    });
}

function updateStats() {
    // Update stats via AJAX
    $.ajax({
        url: '{{ route("superadmin.farms.stats") }}',
        method: 'GET',
        success: function(response) {
            document.getElementById('activeFarmsCount').textContent = response.active;
            document.getElementById('inactiveFarmsCount').textContent = response.inactive;
            document.getElementById('totalFarmsCount').textContent = response.total;
            document.getElementById('barangayCount').textContent = response.barangays;
        },
        error: function(xhr) {
            console.error('Error loading stats:', xhr);
        }
    });
}

function openDetailsModal(farmId) {
    // Load farm details via AJAX
    $.ajax({
        url: `{{ route("superadmin.farms.show", ":id") }}`.replace(':id', farmId),
        method: 'GET',
        success: function(response) {
            const farm = response.data;
            const details = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3 text-primary">Farm Information</h6>
                        <p><strong>Farm ID:</strong> ${farm.farm_id}</p>
                        <p><strong>Farm Name:</strong> ${farm.name || 'N/A'}</p>
                        <p><strong>Barangay:</strong> ${farm.barangay}</p>
                        <p><strong>Status:</strong> <span class="badge badge-${farm.status === 'active' ? 'success' : 'danger'}">${farm.status}</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3 text-primary">Owner Information</h6>
                        <p><strong>Owner Name:</strong> ${farm.owner.first_name} ${farm.owner.last_name}</p>
                        <p><strong>Email:</strong> ${farm.owner.email}</p>
                        <p><strong>Contact Number:</strong> ${farm.owner.contact_number}</p>
                        <p><strong>Registration Date:</strong> ${new Date(farm.created_at).toLocaleDateString()}</p>
                    </div>
                </div>
                ${farm.description ? `
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="mb-3 text-primary">Description</h6>
                        <p>${farm.description}</p>
                    </div>
                </div>
                ` : ''}
            `;

            document.getElementById('farmDetails').innerHTML = details;
            $('#detailsModal').modal('show');
        },
        error: function(xhr) {
            console.error('Error loading farm details:', xhr);
        }
    });
}

function updateActivity(selectElement, farmId) {
    const newStatus = selectElement.value;
    
    $.ajax({
        url: `{{ route("superadmin.farms.update-status", ":id") }}`.replace(':id', farmId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            status: newStatus
        },
        success: function(response) {
            updateStats();
            showNotification(`Farm status updated to ${newStatus}`, 'success');
        },
        error: function(xhr) {
            // Revert the select element if update fails
            selectElement.value = selectElement.value === 'active' ? 'inactive' : 'active';
            showNotification('Error updating farm status', 'danger');
        }
    });
}

function confirmDelete(farmId) {
    farmToDelete = farmId;
    $('#confirmDeleteModal').modal('show');
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (farmToDelete) {
        deleteFarm(farmToDelete);
        farmToDelete = null;
        $('#confirmDeleteModal').modal('hide');
    }
});

function deleteFarm(farmId) {
    $.ajax({
        url: `{{ route("superadmin.farms.destroy", ":id") }}`.replace(':id', farmId),
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            loadFarms();
            updateStats();
            showNotification('Farm deleted successfully', 'success');
        },
        error: function(xhr) {
            showNotification('Error deleting farm', 'danger');
        }
    });
}

function importCSV(event) {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('csv_file', file);

    $.ajax({
        url: '{{ route("superadmin.farms.import") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            loadFarms();
            updateStats();
            showNotification(`Successfully imported ${response.imported} farms`, 'success');
        },
        error: function(xhr) {
            showNotification('Error importing CSV file', 'danger');
        }
    });

    // Reset file input
    event.target.value = '';
}

function exportCSV() {
    farmsTable.button('.buttons-csv').trigger();
}

function exportPDF() {
    farmsTable.button('.buttons-pdf').trigger();
}

function exportPNG() {
    const tableElement = document.getElementById('dataTable');
    html2canvas(tableElement).then(canvas => {
        let link = document.createElement('a');
        link.download = 'Farms_Report.png';
        link.href = canvas.toDataURL("image/png");
        link.click();
    });
}

function printTable() {
    farmsTable.button('.buttons-print').trigger();
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
