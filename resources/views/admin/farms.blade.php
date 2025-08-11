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
            <div class="stat-number" style="color: var(--success-color);" id="activeFarmsCount">{{ $activeFarmsCount }}</div>
            <div class="stat-label">Active Farms</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: var(--danger-color);" id="inactiveFarmsCount">{{ $inactiveFarmsCount }}</div>
            <div class="stat-label">Inactive Farms</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: var(--info-color);" id="totalFarmsCount">{{ $totalFarmsCount }}</div>
            <div class="stat-label">Total Farms</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: var(--warning-color);" id="barangayCount">{{ $barangayCount }}</div>
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
                    <tbody>
                        @forelse($farms as $farm)
                        <tr>
                            <td>
                                <a href="#" class="farm-id-link" onclick="openDetailsModal('{{ $farm->id }}')">
                                    {{ $farm->farm_id ?? 'F' . str_pad($farm->id, 3, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <td>{{ $farm->user->name ?? 'N/A' }}</td>
                            <td>{{ $farm->user->email ?? 'N/A' }}</td>
                            <td>{{ $farm->user->phone ?? 'N/A' }}</td>
                            <td>{{ $farm->location ?? 'N/A' }}</td>
                            <td>
                                <select class="form-control" onchange="updateActivity(this, '{{ $farm->id }}')">
                                    <option value="active" {{ $farm->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $farm->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $farm->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-university"></i>
                                    <h5>No farms available</h5>
                                    <p>There are no farms registered at this time.</p>
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

<!-- Farm Details Modal -->
<div class="modal fade" id="farmDetailsModal" tabindex="-1" role="dialog" aria-labelledby="farmDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="farmDetailsModalLabel">
                    <i class="fas fa-university"></i>
                    Farm Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="farmDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="openContactModal()">
                    <i class="fas fa-envelope"></i> Contact Farm Owner
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Contact Farm Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">
                    <i class="fas fa-paper-plane"></i>
                    Send Message to Farm Owner
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form onsubmit="sendMessage(event)">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="farmerNameHidden">
                    <div class="form-group">
                        <label for="messageSubject">Subject</label>
                        <input type="text" class="form-control" id="messageSubject" required>
                    </div>
                    <div class="form-group">
                        <label for="messageBody">Message</label>
                        <textarea class="form-control" id="messageBody" rows="4" required></textarea>
                    </div>
                    <div id="messageNotification" class="mt-2" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                    Confirm Deletion
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this farm? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="deleteFarm()">
                    <i class="fas fa-trash"></i> Delete Farm
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    :root {
        --primary-color: #4e73df;
        --primary-dark: #3c5aa6;
        --success-color: #1cc88a;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;
        --info-color: #36b9cc;
        --light-color: #f8f9fc;
        --dark-color: #5a5c69;
        --border-color: #e3e6f0;
        --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }

    /* Enhanced Card Styling */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-bottom: none;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-header h6 {
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-header h6::before {
        content: '';
        width: 4px;
        height: 20px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 2px;
    }

    /* Enhanced Table Styling */
    .table {
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .table thead th {
        background-color: #f8f9fc;
        border-top: none;
        border-bottom: 2px solid var(--border-color);
        font-weight: 600;
        color: var(--dark-color);
        padding: 1rem 0.75rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border-top: 1px solid var(--border-color);
    }

    .table tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
    }

    /* Table Controls */
    .table-controls {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .search-container {
        min-width: 250px;
    }

    .export-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .dropdown-toggle::after {
        margin-left: 0.5rem;
    }

    /* Enhanced Modal Styling */
    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: var(--shadow-lg);
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-bottom: none;
        border-radius: 12px 12px 0 0;
        padding: 1.5rem;
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.2rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border-top: 1px solid var(--border-color);
        padding: 1.5rem;
    }

    /* Status Badges */
    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: rgba(28, 200, 138, 0.1);
        color: #25855a;
        border: 1px solid rgba(28, 200, 138, 0.3);
    }

    .status-inactive {
        background: rgba(231, 74, 59, 0.1);
        color: #c73e1d;
        border: 1px solid rgba(231, 74, 59, 0.3);
    }

    /* Page Header Enhancement */
    .page-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: var(--shadow);
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
        box-shadow: var(--shadow);
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--dark-color);
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* Farm ID Links */
    .farm-id-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .farm-id-link:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    /* Status Select Styling */
    .form-control {
        border-radius: 6px;
        border: 1px solid var(--border-color);
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .table-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .search-container {
            min-width: 100%;
        }

        .export-controls {
            justify-content: center;
        }

        .card-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }
    }

    /* Animation Classes */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper .dataTables_info {
        color: var(--dark-color);
        font-size: 0.85rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 6px;
        margin: 0 2px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--dark-color);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--border-color);
    }

    .empty-state h5 {
        margin-bottom: 0.5rem;
        color: var(--dark-color);
    }

    .empty-state p {
        color: #6c757d;
        margin: 0;
    }
</style>
@endsection

@section('scripts')
<script>
let dataTable;
let farmToDelete = null;

$(document).ready(function () {
    // Initialize DataTable
    initializeDataTable();
    
    // Update stats on page load
    updateStats();

    // Custom search functionality
    $('.custom-search').on('keyup', function() {
        dataTable.search(this.value).draw();
    });
});

function initializeDataTable() {
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
            emptyTable: '<div class="empty-state"><i class="fas fa-university"></i><h5>No farms available</h5><p>There are no farms registered at this time.</p></div>'
        }
    });

    // Hide default DataTables elements
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
}

function updateStats() {
    const table = $('#dataTable').DataTable();
    const totalRows = table.rows().count();
    
    let activeFarms = 0;
    let inactiveFarms = 0;
    const barangays = new Set();

    // Count active/inactive farms and unique barangays
    table.rows().every(function() {
        const data = this.data();
        const select = $(this.node()).find('select');
        const status = select.val();
        const barangay = data[4]; // Barangay column
        
        if (status === 'active') {
            activeFarms++;
        } else {
            inactiveFarms++;
        }
        
        if (barangay && barangay !== 'N/A') {
            barangays.add(barangay);
        }
    });

    document.getElementById('activeFarmsCount').textContent = activeFarms;
    document.getElementById('inactiveFarmsCount').textContent = inactiveFarms;
    document.getElementById('totalFarmsCount').textContent = totalRows;
    document.getElementById('barangayCount').textContent = barangays.size;
}

function openDetailsModal(farmId) {
    // Load farm details via AJAX
    $.get(`/admin/farms/${farmId}`, function(response) {
        if (response.success) {
            const farm = response.farm;
            const content = `
                <div class="row">
                    <div class="col-md-6">
                        <h6><strong>Farm Information</strong></h6>
                        <p><strong>Farm ID:</strong> ${farm.farm_id || 'F' + String(farm.id).padStart(3, '0')}</p>
                        <p><strong>Name:</strong> ${farm.name || 'N/A'}</p>
                        <p><strong>Location:</strong> ${farm.location || 'N/A'}</p>
                        <p><strong>Size:</strong> ${farm.size_hectares || 'N/A'} hectares</p>
                        <p><strong>Status:</strong> <span class="status-badge status-${farm.status}">${farm.status}</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6><strong>Owner Information</strong></h6>
                        <p><strong>Name:</strong> ${farm.user ? farm.user.name : 'N/A'}</p>
                        <p><strong>Email:</strong> ${farm.user ? farm.user.email : 'N/A'}</p>
                        <p><strong>Phone:</strong> ${farm.user ? farm.user.phone : 'N/A'}</p>
                        <p><strong>Address:</strong> ${farm.user ? farm.user.address : 'N/A'}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6><strong>Description</strong></h6>
                        <p>${farm.description || 'No description available.'}</p>
                    </div>
                </div>
            `;
            $('#farmDetailsContent').html(content);
            $('#farmDetailsModal').modal('show');
        } else {
            alert('Failed to load farm details');
        }
    }).fail(function() {
        alert('Failed to load farm details');
    });
}

function openContactModal() {
    $('#farmDetailsModal').modal('hide');
    $('#contactModal').modal('show');
}

function updateActivity(select, farmId) {
    const status = select.value;
    
    $.post(`/admin/farms/${farmId}/status`, {
        status: status,
        _token: '{{ csrf_token() }}'
    }, function(response) {
        if (response.success) {
            updateStats();
            showNotification('Farm status updated successfully', 'success');
        } else {
            showNotification('Failed to update farm status', 'error');
            // Revert the select
            select.value = select.getAttribute('data-original-value');
        }
    }).fail(function() {
        showNotification('Failed to update farm status', 'error');
        // Revert the select
        select.value = select.getAttribute('data-original-value');
    });
}

function confirmDelete(farmId) {
    farmToDelete = farmId;
    $('#deleteModal').modal('show');
}

function deleteFarm() {
    if (!farmToDelete) return;
    
    $.ajax({
        url: `/admin/farms/${farmToDelete}`,
        type: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                $('#deleteModal').modal('hide');
                location.reload();
            } else {
                showNotification('Failed to delete farm', 'error');
            }
        },
        error: function() {
            showNotification('Failed to delete farm', 'error');
        }
    });
}

function sendMessage(event) {
    event.preventDefault();
    
    const subject = document.getElementById('messageSubject').value;
    const body = document.getElementById('messageBody').value;
    
    // Here you would typically send the message via AJAX
    // For now, just show a success message
    showNotification('Message sent successfully!', 'success');
    $('#contactModal').modal('hide');
    
    // Clear the form
    document.getElementById('messageSubject').value = '';
    document.getElementById('messageBody').value = '';
}

function exportCSV() {
    dataTable.button('.buttons-csv').trigger();
}

function exportPNG() {
    // Implementation for PNG export
    showNotification('PNG export functionality coming soon!', 'info');
}

function exportPDF() {
    dataTable.button('.buttons-pdf').trigger();
}

function printTable() {
    dataTable.button('.buttons-print').trigger();
}

function importCSV(event) {
    const file = event.target.files[0];
    if (file) {
        // Implementation for CSV import
        showNotification('CSV import functionality coming soon!', 'info');
    }
}

function showNotification(message, type) {
    const notification = document.getElementById('messageNotification');
    notification.textContent = message;
    notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'}`;
    notification.style.display = 'block';
    
    setTimeout(() => {
        notification.style.display = 'none';
    }, 3000);
}

// Store original values for status selects
$(document).ready(function() {
    $('select').each(function() {
        $(this).attr('data-original-value', $(this).val());
    });
});
</script>
@endsection
