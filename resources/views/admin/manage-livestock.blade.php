@extends('layouts.app')

@section('title', 'Livestock Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-cow"></i>
            Livestock Management
        </h1>
        <p>Comprehensive livestock tracking and management system for your dairy operations</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <i class="fas fa-cow stat-icon"></i>
            <h3>{{ $totalLivestock }}</h3>
            <p>Total Livestock</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-check-circle stat-icon"></i>
            <h3>{{ $activeLivestock }}</h3>
            <p>Active Livestock</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-exclamation-triangle stat-icon"></i>
            <h3>{{ $inactiveLivestock }}</h3>
            <p>Inactive Livestock</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-chart-line stat-icon"></i>
            <h3>{{ $totalFarms }}</h3>
            <p>Total Farms</p>
        </div>
    </div>

    <!-- Livestock Table -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header">
            <h6>
                <i class="fas fa-list"></i>
                Livestock Inventory
            </h6>
            <div class="table-controls" style="gap: 1rem; flex-wrap: wrap; align-items: center; display: flex;">
                <div class="search-container" style="min-width: 250px;">
                    <input type="text" class="form-control custom-search" id="customSearch" placeholder="Search livestock...">
                </div>
                <div class="export-controls" style="display: flex; gap: 0.5rem; align-items: center;">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addLivestockModal">
                        <i class="fas fa-plus"></i> Add Livestock
                    </button>
                    <div class="btn-group">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
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
                    <button class="btn btn-secondary btn-sm" onclick="printTable()">
                        <i class="fas fa-print"></i>
                    </button>
                    <button class="btn btn-success btn-sm" onclick="document.getElementById('csvInput').click()">
                        <i class="fas fa-file-import"></i> Import
                    </button>
                    <input type="file" id="csvInput" accept=".csv" style="display: none;" onchange="importCSV(event)">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="livestockTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Livestock ID</th>
                            <th>Type</th>
                            <th>Breed</th>
                            <th>Farm</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($livestock as $animal)
                        <tr>
                            <td>
                                <a href="#" class="livestock-link" onclick="openDetailsModal('{{ $animal->id }}')">{{ $animal->livestock_id }}</a>
                            </td>
                            <td>{{ $animal->type }}</td>
                            <td>{{ $animal->breed }}</td>
                            <td>{{ $animal->farm->name ?? 'N/A' }}</td>
                            <td>
                                <select class="form-control" onchange="updateStatus(this, '{{ $animal->id }}')">
                                    <option value="active" {{ $animal->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $animal->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-info btn-sm" onclick="editLivestock('{{ $animal->id }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteLivestock('{{ $animal->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h5>No livestock found</h5>
                                    <p>There are no livestock records to display at this time.</p>
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

<!-- Add Livestock Modal -->
<div class="modal fade" id="addLivestockModal" tabindex="-1" role="dialog" aria-labelledby="addLivestockLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLivestockLabel">
                    <i class="fas fa-plus"></i> Add New Livestock
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.livestock.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="livestock_id">Livestock ID</label>
                                <input type="text" class="form-control" id="livestock_id" name="livestock_id" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="Cow">Cow</option>
                                    <option value="Goat">Goat</option>
                                    <option value="Carabao">Carabao</option>
                                    <option value="Buffalo">Buffalo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="breed">Breed</label>
                                <input type="text" class="form-control" id="breed" name="breed" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="farm_id">Farm</label>
                                <select class="form-control" id="farm_id" name="farm_id" required>
                                    <option value="">Select Farm</option>
                                    @foreach($farms as $farm)
                                    <option value="{{ $farm->id }}">{{ $farm->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="birth_date">Birth Date</label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="weight">Weight (kg)</label>
                                <input type="number" step="0.1" class="form-control" id="weight" name="weight">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Livestock</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Livestock Modal -->
<div class="modal fade" id="editLivestockModal" tabindex="-1" role="dialog" aria-labelledby="editLivestockLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLivestockLabel">
                    <i class="fas fa-edit"></i> Edit Livestock
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editLivestockForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_livestock_id">Livestock ID</label>
                                <input type="text" class="form-control" id="edit_livestock_id" name="livestock_id" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_type">Type</label>
                                <select class="form-control" id="edit_type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="Cow">Cow</option>
                                    <option value="Goat">Goat</option>
                                    <option value="Carabao">Carabao</option>
                                    <option value="Buffalo">Buffalo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_breed">Breed</label>
                                <input type="text" class="form-control" id="edit_breed" name="breed" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_farm_id">Farm</label>
                                <select class="form-control" id="edit_farm_id" name="farm_id" required>
                                    <option value="">Select Farm</option>
                                    @foreach($farms as $farm)
                                    <option value="{{ $farm->id }}">{{ $farm->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_birth_date">Birth Date</label>
                                <input type="date" class="form-control" id="edit_birth_date" name="birth_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_weight">Weight (kg)</label>
                                <input type="number" step="0.1" class="form-control" id="edit_weight" name="weight">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_notes">Notes</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Livestock</button>
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
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this livestock? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteLivestockForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
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
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(78, 115, 223, 0.1) 0%, rgba(78, 115, 223, 0.05) 100%);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .stat-card h3 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .stat-card p {
        color: var(--dark-color);
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

    .search-container {
        position: relative;
    }

    .search-container input {
        padding-left: 2.5rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .search-container input:focus {
        border-color: var(--primary-color);
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
        color: var(--dark-color);
        z-index: 1;
    }

    .livestock-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
    }

    .livestock-link:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--dark-color);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    let dataTable;

    $(document).ready(function() {
        // Initialize DataTable
        dataTable = $('#livestockTable').DataTable({
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
                    title: 'Livestock_Report',
                    className: 'd-none'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Livestock_Report',
                    orientation: 'landscape',
                    pageSize: 'Letter',
                    className: 'd-none'
                },
                {
                    extend: 'print',
                    title: 'Livestock Report',
                    className: 'd-none'
                }
            ],
            language: {
                search: "",
                paginate: {
                    previous: '<i class="fas fa-chevron-left"></i>',
                    next: '<i class="fas fa-chevron-right"></i>'
                }
            }
        });

        // Hide default DataTables elements
        $('.dataTables_filter').hide();
        $('.dt-buttons').hide();

        // Custom search
        $('.custom-search').on('keyup', function() {
            dataTable.search(this.value).draw();
        });
    });

    function updateStatus(select, livestockId) {
        const status = select.value;
        const url = `{{ route('admin.livestock.update-status', ':id') }}`.replace(':id', livestockId);
        
        fetch(url, {
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
                showNotification('Status updated successfully', 'success');
            } else {
                showNotification('Failed to update status', 'error');
                // Revert the select value
                select.value = select.getAttribute('data-original-value');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
            // Revert the select value
            select.value = select.getAttribute('data-original-value');
        });
    }

    function editLivestock(livestockId) {
        // Fetch livestock data and populate the edit modal
        fetch(`{{ route('admin.livestock.show', ':id') }}`.replace(':id', livestockId))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const livestock = data.livestock;
                
                // Populate the edit form
                document.getElementById('edit_livestock_id').value = livestock.livestock_id;
                document.getElementById('edit_type').value = livestock.type;
                document.getElementById('edit_breed').value = livestock.breed;
                document.getElementById('edit_farm_id').value = livestock.farm_id;
                document.getElementById('edit_birth_date').value = livestock.birth_date;
                document.getElementById('edit_weight').value = livestock.weight;
                document.getElementById('edit_notes').value = livestock.notes;
                
                // Update the form action
                document.getElementById('editLivestockForm').action = `{{ route('admin.livestock.update', ':id') }}`.replace(':id', livestockId);
                
                // Show the modal
                $('#editLivestockModal').modal('show');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to load livestock data', 'error');
        });
    }

    function deleteLivestock(livestockId) {
        // Update the delete form action
        document.getElementById('deleteLivestockForm').action = `{{ route('admin.livestock.destroy', ':id') }}`.replace(':id', livestockId);
        
        // Show the confirmation modal
        $('#confirmDeleteModal').modal('show');
    }

    function exportCSV() {
        dataTable.button('.buttons-csv').trigger();
    }

    function exportPDF() {
        dataTable.button('.buttons-pdf').trigger();
    }

    function exportPNG() {
        // Implementation for PNG export
        showNotification('PNG export feature coming soon', 'info');
    }

    function printTable() {
        dataTable.button('.buttons-print').trigger();
    }

    function importCSV(event) {
        const file = event.target.files[0];
        if (file) {
            // Implementation for CSV import
            showNotification('CSV import feature coming soon', 'info');
        }
    }

    function showNotification(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 'alert-info';
        
        const notification = document.createElement('div');
        notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 3000);
    }

    // Store original values for status updates
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelects = document.querySelectorAll('select[onchange*="updateStatus"]');
        statusSelects.forEach(select => {
            select.setAttribute('data-original-value', select.value);
        });
    });
</script>
@endpush
