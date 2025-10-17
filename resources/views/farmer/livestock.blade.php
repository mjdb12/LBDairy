@extends('layouts.app')

@section('title', 'Livestock Management')

@section('content')
    <!-- Page Header -->
    <div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
        <h1>
            <i class="fas fa-horse"></i>
            Livestock Management
        </h1>
        <p>Manage your livestock inventory, health records, and productivity data</p>
    </div>

    <!-- Statistics Grid -->
    <div class="row fade-in">
        <!-- Total Livestock -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Livestock</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeCount">{{ $totalLivestock }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-users fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Healthy Livestock -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Healthy</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="healthyCount">{{ $healthyLivestock }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-heart fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Needs Attention -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Needs Attention</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="attentionCount">{{ $attentionNeeded }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-exclamation-triangle fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Production Ready -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Production Ready</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="productionCount">{{ $productionReady }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-tint fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

            <!-- Livestock Table -->
    <div class="card shadow mb-4 fade-in" id="farmerSelectionCard">
        <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start">
            <h6 class="mb-0">
                <i class="fas fa-list"></i>
                Livestock Inventory
            </h6>
        </div>
        <div class="card-body">
            <div class="search-controls mb-3">
                <div class="input-group" style="max-width: 300px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search livestock..." id="livestockSearch">
                </div>
                <div class="d-flex align-items-center justify-content-center flex-nowrap gap-2 action-toolbar">
                    @if($farms->count() > 0)
                        <button class="btn-action btn-action-ok" onclick="openAddLivestockModal()">
                            <i class="fas fa-plus mr-2"></i> Add Livestock
                        </button>
                    @else
                        <button class="btn-action btn-action-ok" disabled title="Create a farm first">
                            <i class="fas fa-plus"></i> Add Livestock
                        </button>
                    @endif
                    <button class="btn-action btn-action-refresh" onclick="refreshLivestockTable('livestockTable')">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" type="button" data-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="printTable()">
                                <i class="fas fa-print"></i> Print Table
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportToCSV()">
                                <i class="fas fa-file-csv"></i> Download CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportToPNG()">
                                <i class="fas fa-image"></i> Download PNG
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="livestockTable">
                    <thead>
                        <tr>
                            <th>Livestock ID</th>
                            <th>Type</th>
                            <th>Breed</th>
                            <th>Age</th>
                            <th>Weight (kg)</th>
                            <th>Health Status</th>
                            <th>Registration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($livestock as $animal)
                        @php
                            $age = $animal->birth_date ? \Carbon\Carbon::parse($animal->birth_date)->age : 'N/A';
                            $registrationDate = $animal->created_at ? $animal->created_at->format('M d, Y') : 'N/A';
                        @endphp
                        <tr>
                            <td>
                                <a href="javascript:void(0)" class="livestock-id-link" data-toggle="modal" data-target="#livestockDetailsModal" onclick="openLivestockDetails('{{ $animal->id }}')">{{ $animal->tag_number }}</a>
                            </td>
                            <td>{{ ucfirst($animal->type) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $animal->breed)) }}</td>
                            <td>{{ $age }}</td>
                            <td>{{ $animal->weight ? $animal->weight . ' kg' : 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $animal->health_status === 'healthy' ? 'success' : ($animal->health_status === 'sick' ? 'danger' : 'warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $animal->health_status)) }}
                                </span>
                            </td>
                            <td>{{ $registrationDate }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn-action btn-action-ok" onclick="openEditLivestockModal('{{ $animal->id }}')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                        <span>Edit</span>
                                    </button>
                                    <button class="btn-action btn-action-deletes" data-toggle="modal" data-target="#confirmDeleteModal" onclick="confirmDelete('{{ $animal->id }}')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">N/A</td>
                            <td class="text-center text-muted">No livestock records found</td>
                            <td class="text-center text-muted">N/A</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Add/Edit Livestock Modal -->
<div class="modal fade" id="livestockModal" tabindex="-1" role="dialog" aria-labelledby="livestockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content smart-form text-center p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-paw fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1" id="livestockModalLabel">Add New Livestock</h5>
                <p class="text-muted mb-0 small">
                    Fill out the details below to register livestock information.
                </p>
            </div>
            <!-- Form -->
            <form id="livestockForm" method="POST" action="{{ route('farmer.livestock.store') }}">
                @csrf
                <div class="form-wrapper text-start mx-auto">

                    <div class="row g-3">
                        <!-- Tag Number -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="tag_number" class="fw-semibold">Tag Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control mt-1" id="tag_number" name="tag_number" required>
                        </div>

                        <!-- Name -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="name" class="fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control mt-1" id="name" name="name" required>
                        </div>

                        <!-- Type -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="type" class="fw-semibold">Type <span class="text-danger">*</span></label>
                            <select class="form-control mt-1" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="cow">Cow</option>
                                <option value="buffalo">Buffalo</option>
                                <option value="goat">Goat</option>
                                <option value="sheep">Sheep</option>
                            </select>
                        </div>

                        <!-- Breed -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="breed" class="fw-semibold">Breed <span class="text-danger">*</span></label>
                            <select class="form-control mt-1" id="breed" name="breed" required>
                                <option value="">Select Breed</option>
                                <option value="holstein">Holstein</option>
                                <option value="jersey">Jersey</option>
                                <option value="guernsey">Guernsey</option>
                                <option value="ayrshire">Ayrshire</option>
                                <option value="brown_swiss">Brown Swiss</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Date of Birth -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="birth_date" class="fw-semibold">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" class="form-control mt-1" id="birth_date" name="birth_date" required>
                        </div>

                        <!-- Gender -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="gender" class="fw-semibold">Gender <span class="text-danger">*</span></label>
                            <select class="form-control mt-1" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <!-- Weight -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="weight" class="fw-semibold">Weight (kg)</label>
                            <input type="number" class="form-control mt-1" id="weight" name="weight" min="0" step="0.1">
                        </div>

                        <!-- Health Status -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="health_status" class="fw-semibold">Health Status <span class="text-danger">*</span></label>
                            <select class="form-control mt-1" id="health_status" name="health_status" required>
                                <option value="healthy">Healthy</option>
                                <option value="sick">Sick</option>
                                <option value="recovering">Recovering</option>
                                <option value="under_treatment">Under Treatment</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="status" class="fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-control mt-1" id="status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <!-- Registry ID -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="registry_id" class="fw-semibold">Registry ID</label>
                            <input type="text" class="form-control mt-1" id="registry_id" name="registry_id">
                        </div>

                        <!-- Natural Marks -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="natural_marks" class="fw-semibold">Natural Marks</label>
                            <input type="text" class="form-control mt-1" id="natural_marks" name="natural_marks">
                        </div>

                        <!-- Property Number -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="property_no" class="fw-semibold">Property Number</label>
                            <input type="text" class="form-control mt-1" id="property_no" name="property_no">
                        </div>

                        <!-- Acquisition Date -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="acquisition_date" class="fw-semibold">Acquisition Date</label>
                            <input type="date" class="form-control mt-1" id="acquisition_date" name="acquisition_date">
                        </div>

                        <!-- Acquisition Cost -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="acquisition_cost" class="fw-semibold">Acquisition Cost (₱)</label>
                            <input type="number" class="form-control mt-1" id="acquisition_cost" name="acquisition_cost" min="0" step="0.01">
                        </div>

                        <!-- Sire ID -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="sire_id" class="fw-semibold">Sire ID</label>
                            <input type="text" class="form-control mt-1" id="sire_id" name="sire_id">
                        </div>

                        <!-- Sire Name -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="sire_name" class="fw-semibold">Sire Name</label>
                            <input type="text" class="form-control mt-1" id="sire_name" name="sire_name">
                        </div>

                        <!-- Dam ID -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="dam_id" class="fw-semibold">Dam ID</label>
                            <input type="text" class="form-control mt-1" id="dam_id" name="dam_id">
                        </div>

                        <!-- Dam Name -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="dam_name" class="fw-semibold">Dam Name</label>
                            <input type="text" class="form-control mt-1" id="dam_name" name="dam_name">
                        </div>

                        <!-- Dispersal From -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="dispersal_from" class="fw-semibold">Dispersal From</label>
                            <input type="text" class="form-control mt-1" id="dispersal_from" name="dispersal_from">
                        </div>

                        <!-- Owned By -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="owned_by" class="fw-semibold">Owned By</label>
                            <input type="text" class="form-control mt-1" id="owned_by" name="owned_by">
                        </div>

                        <!-- Remarks -->
                        <div class="col-12 mb-3">
                            <label for="remarks" class="fw-semibold">Remarks</label>
                            <textarea class="form-control mt-1" id="remarks" name="remarks" rows="3" style="resize: none;"></textarea>
                        </div>
                    </div>


                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-ok">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>


<!-- Smart Detail Modal -->
<div class="modal fade livestock-modal" id="livestockDetailsModal" tabindex="-1" role="dialog" aria-labelledby="livestockDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-eye fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">Livestock Details</h5>
                <p class="text-muted mb-0 small text-center">
                    Below are the complete details of the selected livestock.
                </p>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div id="livestockDetailsContent" class="detail-wrapper">
                    <!-- Dynamic details will be injected here -->
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap mt-4">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn-modern btn-edit" onclick="printLivestockRecord()" id="printLivestockBtn">
                    <i class="fas fa-print"></i> Print
                </button>
                <button type="button" class="btn-modern btn-ok" onclick="editCurrentLivestock()">
                    <i class="fas fa-edit"></i> Edit
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Modern Delete Task Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content smart-modal text-center p-4">
            <!-- Icon -->
            <div class="icon-wrapper mx-auto mb-4 text-danger">
                <i class="fas fa-times-circle fa-2x"></i>
            </div>
            <!-- Title -->
            <h5>Confirm Delete</h5>
            <!-- Description -->
            <p class="text-muted mb-4 px-3">
                Are you sure you want to delete this livestock? This action <strong>cannot be undone</strong>.
            </p>

            <!-- Buttons -->
            <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="btn-modern btn-delete">
                    <i class="fas fa-trash"></i> Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- DataTables CSS and JS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>


<!-- DataTables Buttons (CSV, PDF, Print) -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- Required libraries for PDF/Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<!-- jsPDF and autoTable for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
const CURRENT_FARMER_NAME = @json(Auth::user()->name);
function formatDateForInput(dateVal) {
    if (!dateVal) return '';
    try {
        const d = new Date(dateVal);
        if (isNaN(d.getTime())) {
            return String(dateVal).slice(0, 10);
        }
        const yyyy = d.getFullYear();
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd}`;
    } catch (e) {
        return String(dateVal).slice(0, 10);
    }
}

let currentLivestockId = null;
let downloadCounter = 1;
let livestockTable;


$(document).ready(function() {
    // Initialize DataTable only if the table exists
    if ($('#livestockTable').length > 0) {
        try {
            livestockTable = $('#livestockTable').DataTable({
                dom: 'Bfrtip',
                searching: true,
                paging: true,
                info: true,
                ordering: true,
                lengthChange: false,
                pageLength: 10,
                autoWidth: false,
                scrollX: true,
                order: [[0, 'asc']],
                buttons: [
                    {
                        extend: 'csvHtml5',
                        title: 'Livestock_Report',
                        className: 'd-none',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6],
                            modifier: { page: 'all' }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Livestock_Report',
                        orientation: 'landscape',
                        pageSize: 'Letter',
                        className: 'd-none',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6],
                            modifier: { page: 'all' }
                        }
                    },
                    {
                        extend: 'print',
                        title: 'Livestock Report',
                        className: 'd-none',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6],
                            modifier: { page: 'all' }
                        }
                    }
                ],
                language: {
                    search: "",
                    emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No livestock available</h5><p>There are no livestock records to display at this time.</p></div>',
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
            
            // Hide default DataTables elements
            $('.dataTables_filter').hide();
            $('.dt-buttons').hide();
            
            // Force pagination to left side after initialization
            setTimeout(() => {
                forcePaginationLeft();
            }, 100);
            
            // Add event listeners to force pagination positioning on table updates
            livestockTable.on('draw.dt', function() {
                setTimeout(forcePaginationLeft, 50);
            });
            
            // Multiple attempts to ensure pagination stays left
            setTimeout(forcePaginationLeft, 200);
            setTimeout(forcePaginationLeft, 500);
            setTimeout(forcePaginationLeft, 1000);
            
            // Connect custom search to DataTables
            $('#livestockSearch').on('keyup', function() {
                livestockTable.search(this.value).draw();
            });
        } catch (error) {
            console.error('DataTables initialization error:', error);
        }
    }
    
    // Handle form submission
    $('#livestockForm').on('submit', function(e) {
        e.preventDefault();
        submitLivestockForm();
    });
    
});

// Refresh Admins Table


// Refresh Admins Table
function refreshLivestockTable() {
    const refreshBtn = document.querySelector('.btn-action-refresh');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    refreshBtn.disabled = true;

    // Use unique flag for admins
    sessionStorage.setItem('showRefreshNotificationLivestock', 'true');

    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Check notifications after reload
$(document).ready(function() {
    if (sessionStorage.getItem('showRefreshNotificationLivestock') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationLivestock');
        setTimeout(() => {
            showNotification('Livestock data refreshed successfully!', 'success');
        }, 500);
    }

    if (sessionStorage.getItem('showRefreshNotificationAdmins') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationAdmins');
        setTimeout(() => {
            showNotification('Active Farmers data refreshed successfully!', 'success');
        }, 500);
    }
});

function openAddLivestockModal() {
    // Check if Bootstrap modal is available
    if (typeof $.fn.modal === 'undefined') {
        showToast('Modal functionality is not available. Please refresh the page.', 'error');
        return;
    }
    
    $('#livestockModalLabel').html('<i class="fas fa-plus"></i> Add New Livestock');
    $('#livestockForm')[0].reset();
    $('#livestockForm').attr('action', '{{ route("farmer.livestock.store") }}');
    $('#livestockForm').attr('method', 'POST');
    const $m = $('#livestockModal');
    if (!$m.parent().is('body')) { $m.appendTo('body'); }
    $m.modal({ backdrop: 'static', keyboard: true });
    $m.modal('show');
}

function openEditLivestockModal(livestockId) {
    // Check if Bootstrap modal is available
    if (typeof $.fn.modal === 'undefined') {
        showToast('Modal functionality is not available. Please refresh the page.', 'error');
        return;
    }
    
    currentLivestockId = livestockId;
    $('#livestockModalLabel').html('<i class="fas fa-edit"></i> Edit Livestock');
    $('#livestockForm').attr('action', `/farmer/livestock/${livestockId}`);
    $('#livestockForm').attr('method', 'POST');
    
    // Remove existing method override if any
    $('#livestockForm').find('input[name="_method"]').remove();
    $('#livestockForm').append('<input type="hidden" name="_method" value="PUT">');
    
    // Load livestock data
    loadLivestockData(livestockId);
    const $m = $('#livestockModal');
    if (!$m.parent().is('body')) { $m.appendTo('body'); }
    $m.modal({ backdrop: 'static', keyboard: true });
    $m.modal('show');
}

function openLivestockDetails(livestockId) {
    // Check if Bootstrap modal is available
    if (typeof $.fn.modal === 'undefined') {
        showToast('Modal functionality is not available. Please refresh the page.', 'error');
        return;
    }
    
    currentLivestockId = livestockId;
    loadLivestockDetails(livestockId);
    const $m = $('#livestockDetailsModal');
    if (!$m.parent().is('body')) { $m.appendTo('body'); }
    $m.modal({ backdrop: true, keyboard: true });
    $m.modal('show');
}

function loadLivestockData(livestockId) {
    $.ajax({
        url: `/farmer/livestock/${livestockId}/edit`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const livestock = response.livestock;
                $('#tag_number').val(livestock.tag_number);
                $('#name').val(livestock.name);
                $('#type').val(livestock.type);
                $('#breed').val(livestock.breed);
                $('#birth_date').val(formatDateForInput(livestock.birth_date));
                $('#weight').val(livestock.weight);
                $('#gender').val(livestock.gender);
                $('#health_status').val(livestock.health_status);
                $('#status').val(livestock.status);
                $('#registry_id').val(livestock.registry_id);
                $('#natural_marks').val(livestock.natural_marks);
                $('#property_no').val(livestock.property_no);
                $('#acquisition_date').val(formatDateForInput(livestock.acquisition_date));
                $('#acquisition_cost').val(livestock.acquisition_cost);
                $('#sire_id').val(livestock.sire_id);
                $('#sire_name').val(livestock.sire_name);
                $('#dam_id').val(livestock.dam_id);
                $('#dam_name').val(livestock.dam_name);
                $('#dispersal_from').val(livestock.dispersal_from);
                $('#owned_by').val(livestock.owned_by && String(livestock.owned_by).trim() !== '' ? livestock.owned_by : CURRENT_FARMER_NAME);
                $('#remarks').val(livestock.remarks);
            }
        },
        error: function() {
            showToast('Error loading livestock data', 'error');
        }
    });
}

function loadLivestockDetails(livestockId) {
    $.ajax({
        url: `/farmer/livestock/${livestockId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const livestock = response.livestock;
                
                // Calculate age from birth date
                const age = livestock.birth_date ? calculateAge(livestock.birth_date) : 'Unknown';
                
                // Format dates properly
                const birthDate = livestock.birth_date ? new Date(livestock.birth_date).toLocaleDateString() : 'Not recorded';
                const createdDate = livestock.created_at ? new Date(livestock.created_at).toLocaleDateString() : 'Not recorded';
                const updatedDate = livestock.updated_at ? new Date(livestock.updated_at).toLocaleDateString() : 'Not recorded';
                
                $('#livestockDetailsContent').html(`
            <!-- Smart Detail Body Content -->

<!-- QR Code & Quick Info Section -->
<div class="row mb-4">
    <!-- QR Code Status -->
    <div class="col-md-6 mb-3">
        <div class="smart-card shadow-sm h-100">
            <div class="smart-card-header text-#18375d d-flex align-items-center">
                <i class="fas fa-qrcode mr-2"></i>
                <h6 class="mb-0 fw-bold">QR Code Status</h6>
            </div>
            <div class="smart-card-body text-center p-3">
                <div id="qrCodeContainer" class="mb-3">
                    <p class="text-muted mb-0">Checking QR code status...</p>
                </div>
                <button type="button" 
                        class="btn-action btn-action-ok" 
                        onclick="checkQRCodeStatus('${livestock.id}')">
                    Check QR Code Status
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Info -->
    <div class="col-md-6 mb-3">
        <div class="smart-card shadow-sm h-100">
            <div class="smart-card-header text-#18375d d-flex align-items-center">
                <i class="fas fa-info-circle mr-2"></i>
                <h6 class="mb-0 fw-bold">Quick Info</h6>
            </div>
            <div class="smart-card-body p-3">
                <div class="row">
                    <div class="col-6 mb-2">
                        <small class="text-muted">Tag Number</small>
                        <p class="fw-bold mb-0">${livestock.tag_number || 'N/A'}</p>
                    </div>

                    <div class="col-6 mb-2">
                        <small class="text-muted">Name</small>
                        <p class="fw-bold mb-0">${livestock.name || 'N/A'}</p>
                    </div>

                    <div class="col-6 mb-2">
                        <small class="text-muted">Type</small>
                        <p class="fw-bold mb-0">
                            ${livestock.type 
                                ? livestock.type.charAt(0).toUpperCase() + livestock.type.slice(1) 
                                : 'N/A'}
                        </p>
                    </div>

                    <div class="col-6 mb-2">
                        <small class="text-muted">Breed</small>
                        <p class="fw-bold mb-0">
                            ${livestock.breed 
                                ? livestock.breed.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) 
                                : 'N/A'}
                        </p>
                    </div>

                    <div class="col-6 mb-2">
                        <small class="text-muted">Age</small>
                        <p class="fw-bold mb-0">${age}</p>
                    </div>

                    <div class="col-6 mb-2">
                        <small class="text-muted">Status</small>
                        <p class="fw-bold mb-0">
                            <span class="badge badge-${livestock.status === 'active' ? 'success' : 'secondary'}">
                                ${livestock.status 
                                    ? livestock.status.charAt(0).toUpperCase() + livestock.status.slice(1) 
                                    : 'N/A'}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Navigation Tabs -->
<ul class="nav nav-tabs smart-tabs mb-3" id="livestockTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basicForm" role="tab">
            Basic Info
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="production-tab" data-toggle="tab" href="#productionForm" role="tab">
            Production
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="health-tab" data-toggle="tab" href="#healthForm" role="tab">
            Health
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="breeding-tab" data-toggle="tab" href="#breedingForm" role="tab">
            Breeding
        </a>
    </li>
</ul>

<!-- Tabs Content -->
<div class="tab-content" id="livestockTabContent">

    <!-- Basic Info Tab -->
    <div class="tab-pane fade show active" id="basicForm" role="tabpanel">
        <div class="smart-table table-responsive">
            <table class="table table-sm table-bordered align-middle mb-0">
                <tbody>
                    <tr><th>Tag Number</th><td>${livestock.tag_number || 'Not assigned'}</td></tr>
                    <tr><th>Name</th><td>${livestock.name || 'Not assigned'}</td></tr>
                    <tr><th>Type</th><td>${livestock.type ? livestock.type.charAt(0).toUpperCase() + livestock.type.slice(1) : 'Not recorded'}</td></tr>
                    <tr><th>Breed</th><td>${livestock.breed ? livestock.breed.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'Not recorded'}</td></tr>
                    <tr><th>Date of Birth</th><td>${birthDate}</td></tr>
                    <tr><th>Age</th><td>${age}</td></tr>
                    <tr><th>Gender</th><td>${livestock.gender ? livestock.gender.charAt(0).toUpperCase() + livestock.gender.slice(1) : 'Not recorded'}</td></tr>
                    <tr><th>Weight</th><td>${livestock.weight ? livestock.weight + ' kg' : 'Not recorded'}</td></tr>
                    <tr>
                        <th>Health Status</th>
                        <td>
                            <span class="badge badge-${livestock.health_status === 'healthy' ? 'success' : livestock.health_status === 'sick' ? 'danger' : 'warning'}">
                                ${livestock.health_status ? livestock.health_status.charAt(0).toUpperCase() + livestock.health_status.slice(1) : 'Not recorded'}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge badge-${livestock.status === 'active' ? 'success' : 'secondary'}">
                                ${livestock.status ? livestock.status.charAt(0).toUpperCase() + livestock.status.slice(1) : 'Not recorded'}
                            </span>
                        </td>
                    </tr>
                    <tr><th>Farm</th><td>${livestock.farm ? livestock.farm.name : 'Not assigned'}</td></tr>
                    <tr><th>Registry ID</th><td>${livestock.registry_id || 'Not assigned'}</td></tr>
                    <tr><th>Natural Marks</th><td>${livestock.natural_marks || 'None recorded'}</td></tr>
                    <tr><th>Property Number</th><td>${livestock.property_no || 'Not assigned'}</td></tr>
                    <tr><th>Acquisition Date</th><td>${livestock.acquisition_date ? new Date(livestock.acquisition_date).toLocaleDateString() : 'Not recorded'}</td></tr>
                    <tr><th>Acquisition Cost</th><td>${livestock.acquisition_cost ? '₱' + parseFloat(livestock.acquisition_cost).toFixed(2) : 'Not recorded'}</td></tr>
                    <tr><th>Remarks</th><td>${livestock.remarks || 'None'}</td></tr>
                    <tr><th>Created</th><td>${createdDate}</td></tr>
                    <tr><th>Last Updated</th><td>${updatedDate}</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Production Tab -->
    <div class="tab-pane fade" id="productionForm" role="tabpanel">
        <div class="smart-table table-responsive">
            <table class="table table-sm table-bordered align-middle mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Date</th>
                        <th>Production Type</th>
                        <th>Quantity</th>
                        <th>Quality</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody id="productionRecordsTable">
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">
                            <i class="fas fa-info-circle"></i>
                            No production records found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-right mt-3">
            <button class="btn-action btn-action-ok" onclick="addProductionRecord('${livestock.id}')">
                <i class="fas fa-plus"></i> Add Production Record
            </button>
        </div>
    </div>

    <!-- Health Tab -->
    <div class="tab-pane fade" id="healthForm" role="tabpanel">
        <div class="smart-table table-responsive">
            <table class="table table-sm table-bordered align-middle mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Date</th>
                        <th>Health Status</th>
                        <th>Treatment</th>
                        <th>Veterinarian</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody id="healthRecordsTable">
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">
                            <i class="fas fa-info-circle"></i>
                            No health records found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-right mt-3">
            <button class="btn-action btn-action-ok" onclick="addHealthRecord('${livestock.id}')">
                <i class="fas fa-plus"></i> Add Health Record
            </button>
        </div>
    </div>

    <!-- Breeding Tab -->
    <div class="tab-pane fade" id="breedingForm" role="tabpanel">
        <div class="smart-table table-responsive">
            <table class="table table-sm table-bordered align-middle mb-0">
                <tbody>
                    <tr><th>Sire ID</th><td>${livestock.sire_id || 'Not recorded'}</td></tr>
                    <tr><th>Sire Name</th><td>${livestock.sire_name || 'Not recorded'}</td></tr>
                    <tr><th>Dam ID</th><td>${livestock.dam_id || 'Not recorded'}</td></tr>
                    <tr><th>Dam Name</th><td>${livestock.dam_name || 'Not recorded'}</td></tr>
                    <tr><th>Dispersal From</th><td>${livestock.dispersal_from || 'Not recorded'}</td></tr>
                </tbody>
            </table>
        </div>
        <div class="text-right mt-3">
            <button class="btn-action btn-action-ok" onclick="addBreedingRecord('${livestock.id}')">
                <i class="fas fa-plus"></i> Add Breeding Record
            </button>
        </div>
    </div>
</div>


                `);
                
                // Load production records
                loadProductionRecords(livestockId);
                
                // Automatically check QR code status
                setTimeout(() => {
                    checkQRCodeStatus(livestockId);
                }, 500);
            }
        },
        error: function() {
            showToast('Error loading livestock details', 'error');
        }
    });
}

function submitLivestockForm() {
    const formData = new FormData($('#livestockForm')[0]);
    const method = $('#livestockForm').attr('method');
    
    // Debug: Log form data
    console.log('Form action:', $('#livestockForm').attr('action'));
    console.log('Form method:', method);
    console.log('CSRF token:', $('meta[name="csrf-token"]').attr('content'));
    
    // Log form data entries
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    
    // Add method override for PUT requests
    if (method === 'POST' && $('#livestockForm').find('input[name="_method"]').length > 0) {
        formData.append('_method', 'PUT');
    }
    
    $.ajax({
        url: $('#livestockForm').attr('action'),
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Success response:', response);
            if (response.success) {
                showToast(response.message, 'success');
                $('#livestockModal').modal('hide');
                location.reload();
            } else {
                showToast(response.message || 'Error saving livestock', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.log('Error response:', xhr.responseText);
            console.log('Status:', status);
            console.log('Error:', error);
            
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(field => {
                    showToast(errors[field][0], 'error');
                });
            } else {
                showToast('Error saving livestock: ' + (xhr.responseJSON?.message || error), 'error');
            }
        }
    });
}

function confirmDelete(livestockId) {
    // Check if Bootstrap modal is available
    if (typeof $.fn.modal === 'undefined') {
        showToast('Modal functionality is not available. Please refresh the page.', 'error');
        return;
    }
    
    currentLivestockId = livestockId;
    const $m = $('#confirmDeleteModal');
    if (!$m.parent().is('body')) { $m.appendTo('body'); }
    $m.modal({ backdrop: 'static', keyboard: true });
    $m.modal('show');
}

$('#confirmDeleteBtn').on('click', function() {
    if (currentLivestockId) {
        deleteLivestock(currentLivestockId);
    }
});

function deleteLivestock(livestockId) {
    $.ajax({
        url: `/farmer/livestock/${livestockId}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                $('#confirmDeleteModal').modal('hide');
                location.reload();
            } else {
                showToast(response.message || 'Error deleting livestock', 'error');
            }
        },
        error: function() {
            showToast('Error deleting livestock', 'error');
        }
    });
}

function editCurrentLivestock() {
    if (currentLivestockId) {
        $('#livestockDetailsModal')
            .one('hidden.bs.modal', function() {
                openEditLivestockModal(currentLivestockId);
            })
            .modal('hide');
    }
}

function printLivestockRecord() {
    if (currentLivestockId) {
        const contentEl = document.getElementById('livestockDetailsContent');
        if (!contentEl) {
            showToast('Nothing to print. Open the livestock details first.', 'warning');
            return;
        }
        window.printElement('#livestockDetailsContent');
    }
}

function checkQRCodeStatus(livestockId) {
    // Show loading state
    const qrContainer = document.getElementById('qrCodeContainer');
    qrContainer.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking QR Code Status...';
    
    $.ajax({
        url: `/farmer/livestock/${livestockId}/qr-code`,
        method: 'GET',
        success: function(response) {
            if (response.success && response.qr_code_exists) {
                // Display the QR code
                const generatedDate = response.generated_at ? new Date(response.generated_at).toLocaleDateString() : 'Unknown';
                qrContainer.innerHTML = `
                    <div class="text-center">
                        <img src="${response.qr_code}" alt="QR Code for ${response.livestock_id}" class="img-fluid mb-2" style="max-width: 200px;">
                        <p class="text-muted small">QR Code for ${response.livestock_id}</p>
                        <p class="text-muted small">Generated by: ${response.generated_by}</p>
                        <p class="text-muted small">Generated on: ${generatedDate}</p>
                        <button type="button" class="btn-action btn-action-ok" onclick="downloadQRCode('${response.qr_code}', '${response.livestock_id}')">
                            Download QR Code
                        </button>
                    </div>
                `;
                showToast('&nbsp;QR Code is available for download!', 'success');
            } else {
                // QR code not generated yet
                qrContainer.innerHTML = `
                    <div class="text-center ">
                        <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                        <h6 class="text-warning">QR Code Not Generated</h6>
                        <p class="text-muted">This livestock does not have a QR code yet.</p>
                        <p class="text-muted small">Please contact an administrator to generate the QR code.</p>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                           <strong>Note: </strong>&nbsp;Only administrators can generate QR codes for livestock.
                        </div>
                    </div>
                    
                `;
                showToast('&nbsp;QR Code has not been generated yet. Contact an administrator.', 'warning');
            }
        },
        error: function() {
            qrContainer.innerHTML = '<p class="text-danger">Error checking QR Code status</p>';
            showToast('Error checking QR Code status', 'error');
        }
    });
}

function downloadQRCode(qrCodeUrl, livestockId) {
    // Create a temporary link to download the QR code
    const link = document.createElement('a');
    link.href = qrCodeUrl;
    link.download = `QR_Code_${livestockId}.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    showToast('QR Code downloaded successfully!', 'success');
}

function calculateAge(birthDate) {
    const birth = new Date(birthDate);
    const today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    const monthDiff = today.getMonth() - birth.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
        age--;
    }
    
    if (age === 0) {
        const monthAge = today.getMonth() - birth.getMonth();
        if (monthAge <= 0) {
            const dayAge = today.getDate() - birth.getDate();
            return dayAge <= 0 ? 'Less than 1 day' : `${dayAge} day${dayAge > 1 ? 's' : ''}`;
        }
        return `${monthAge} month${monthAge > 1 ? 's' : ''}`;
    }
    
    return `${age} year${age > 1 ? 's' : ''}`;
}

function loadProductionRecords(livestockId) {
    $.ajax({
        url: `/farmer/livestock/${livestockId}/production-records`,
        method: 'GET',
        success: function(response) {
            if (response.success && response.data.length > 0) {
                const tbody = document.getElementById('productionRecordsTable');
                tbody.innerHTML = '';
                
                response.data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${new Date(record.production_date).toLocaleDateString()}</td>
                        <td>${record.production_type || 'N/A'}</td>
                        <td>${record.quantity || 'N/A'}</td>
                        <td>${record.quality || 'N/A'}</td>
                        <td>${record.notes || 'N/A'}</td>
                    `;
                    tbody.appendChild(row);
                });
            }
        },
        error: function() {
            // Keep the default "no records" message
        }
    });
}

function addProductionRecord(livestockId) {
    // Check if Bootstrap modal is available
    if (typeof $.fn.modal === 'undefined') {
        showNotification('Modal functionality is not available. Please refresh the page.', 'danger');
        return;
    }
    
    // Create production record modal
    const modalHtml = `
        <div class="modal fade" id="productionRecordModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-chart-line"></i>
                            Add Production Record
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="productionRecordForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="production_date">Production Date *</label>
                                        <input type="date" class="form-control" id="production_date" name="production_date" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="production_type">Production Type *</label>
                                        <select class="form-control" id="production_type" name="production_type" required>
                                            <option value="milk">Milk</option>
                                            <option value="eggs">Eggs</option>
                                            <option value="meat">Meat</option>
                                            <option value="wool">Wool</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quantity">Quantity *</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" min="0" step="0.1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quality">Quality</label>
                                        <select class="form-control" id="quality" name="quality">
                                            <option value="excellent">Excellent</option>
                                            <option value="good">Good</option>
                                            <option value="fair">Fair</option>
                                            <option value="poor">Poor</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveProductionRecord(${livestockId})">
                            <i class="fas fa-save"></i> Save Record
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    $('#productionRecordModal').remove();
    
    // Add modal to body
    $('body').append(modalHtml);
    
    // Show modal
    $('#productionRecordModal').modal('show');
}

function saveProductionRecord(livestockId) {
    const form = document.getElementById('productionRecordForm');
    const formData = new FormData(form);
    
    // Add livestock ID to form data
    formData.append('livestock_id', livestockId);
    
    $.ajax({
        url: '/farmer/production-records',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showNotification('Production record added successfully!', 'success');
                $('#productionRecordModal').modal('hide');
                // Refresh production records if modal is open
                if ($('#livestockDetailsModal').hasClass('show')) {
                    loadProductionRecords(livestockId);
                }
            } else {
                showNotification('Error adding production record: ' + (response.message || 'Unknown error'), 'danger');
            }
        },
        error: function(xhr) {
            const errorMessage = xhr.responseJSON?.message || 'Error adding production record';
            showNotification(errorMessage, 'danger');
        }
    });
}

function addHealthRecord(livestockId) {
    // Check if Bootstrap modal is available
    if (typeof $.fn.modal === 'undefined') {
        showNotification('Modal functionality is not available. Please refresh the page.', 'danger');
        return;
    }
    
    // Create health record modal
    const modalHtml = `
        <div class="modal fade" id="healthRecordModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-heartbeat"></i>
                            Add Health Record
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="healthRecordForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="health_date">Health Check Date *</label>
                                        <input type="date" class="form-control" id="health_date" name="health_date" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="health_status">Health Status *</label>
                                        <select class="form-control" id="health_status" name="health_status" required>
                                            <option value="healthy">Healthy</option>
                                            <option value="sick">Sick</option>
                                            <option value="recovering">Recovering</option>
                                            <option value="under_treatment">Under Treatment</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="weight">Weight (kg)</label>
                                        <input type="number" class="form-control" id="weight" name="weight" min="0" step="0.1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="temperature">Temperature (°C)</label>
                                        <input type="number" class="form-control" id="temperature" name="temperature" min="0" step="0.1">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="symptoms">Symptoms/Observations</label>
                                        <textarea class="form-control" id="symptoms" name="symptoms" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="treatment">Treatment Given</label>
                                        <textarea class="form-control" id="treatment" name="treatment" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveHealthRecord(${livestockId})">
                            <i class="fas fa-save"></i> Save Record
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    $('#healthRecordModal').remove();
    
    // Add modal to body
    $('body').append(modalHtml);
    
    // Show modal
    $('#healthRecordModal').modal('show');
}

function saveHealthRecord(livestockId) {
    const form = document.getElementById('healthRecordForm');
    const formData = new FormData(form);
    
    // Add livestock ID to form data
    formData.append('livestock_id', livestockId);
    
    $.ajax({
        url: '/farmer/health-records',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showNotification('Health record added successfully!', 'success');
                $('#healthRecordModal').modal('hide');
                // Refresh livestock data to update health status
                location.reload();
            } else {
                showNotification('Error adding health record: ' + (response.message || 'Unknown error'), 'danger');
            }
        },
        error: function(xhr) {
            const errorMessage = xhr.responseJSON?.message || 'Error adding health record';
            showNotification(errorMessage, 'danger');
        }
    });
}

function addBreedingRecord(livestockId) {
    // Check if Bootstrap modal is available
    if (typeof $.fn.modal === 'undefined') {
        showNotification('Modal functionality is not available. Please refresh the page.', 'danger');
        return;
    }
    
    // Create breeding record modal
    const modalHtml = `
        <div class="modal fade" id="breedingRecordModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-heart"></i>
                            Add Breeding Record
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="breedingRecordForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="breeding_date">Breeding Date *</label>
                                        <input type="date" class="form-control" id="breeding_date" name="breeding_date" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="breeding_type">Breeding Type *</label>
                                        <select class="form-control" id="breeding_type" name="breeding_type" required>
                                            <option value="natural">Natural Breeding</option>
                                            <option value="artificial">Artificial Insemination</option>
                                            <option value="embryo_transfer">Embryo Transfer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="partner_livestock_id">Partner Livestock ID</label>
                                        <input type="text" class="form-control" id="partner_livestock_id" name="partner_livestock_id">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="expected_birth_date">Expected Birth Date</label>
                                        <input type="date" class="form-control" id="expected_birth_date" name="expected_birth_date">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pregnancy_status">Pregnancy Status</label>
                                        <select class="form-control" id="pregnancy_status" name="pregnancy_status">
                                            <option value="unknown">Unknown</option>
                                            <option value="pregnant">Pregnant</option>
                                            <option value="not_pregnant">Not Pregnant</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="breeding_success">Breeding Success</label>
                                        <select class="form-control" id="breeding_success" name="breeding_success">
                                            <option value="unknown">Unknown</option>
                                            <option value="successful">Successful</option>
                                            <option value="unsuccessful">Unsuccessful</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-action btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn-action btn-action-ok" onclick="saveBreedingRecord(${livestockId})">
                            Save Record
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    $('#breedingRecordModal').remove();
    
    // Add modal to body
    $('body').append(modalHtml);
    
    // Show modal
    $('#breedingRecordModal').modal('show');
}

function saveBreedingRecord(livestockId) {
    const form = document.getElementById('breedingRecordForm');
    const formData = new FormData(form);
    
    // Add livestock ID to form data
    formData.append('livestock_id', livestockId);
    
    $.ajax({
        url: '/farmer/breeding-records',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showNotification('Breeding record added successfully!', 'success');
                $('#breedingRecordModal').modal('hide');
            } else {
                showNotification('Error adding breeding record: ' + (response.message || 'Unknown error'), 'danger');
            }
        },
        error: function(xhr) {
            const errorMessage = xhr.responseJSON?.message || 'Error adding breeding record';
            showNotification(errorMessage, 'danger');
        }
    });
}

function exportToCSV() {
    try {
        if (!livestockTable) {
            showNotification('Table not initialized. Please refresh the page.', 'danger');
            return;
        }
        livestockTable.button('.buttons-csv').trigger();
    } catch (error) {
        console.error('CSV export error:', error);
        showNotification('Error exporting CSV. Please try again.', 'danger');
    }
}

function exportToPDF() {
    try {
        if (!livestockTable) {
            showNotification('Table not initialized. Please refresh the page.', 'danger');
            return;
        }
        livestockTable.button('.buttons-pdf').trigger();
    } catch (error) {
        console.error('PDF export error:', error);
        showNotification('Error exporting PDF. Please try again.', 'danger');
    }
}

function printTable() {
    try {
        window.printElement('#livestockTable');
    } catch (error) {
        console.error('Print error:', error);
        try { window.print(); } catch (_) {}
    }
}

function exportToPNG() {
    try {
        // Check if DataTable is initialized
        if (!livestockTable) {
            showNotification('Table not initialized. Please refresh the page.', 'danger');
            return;
        }
        
        // Create a temporary table without the Actions column for export
        const originalTable = document.getElementById('livestockTable');
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
        
        // Temporarily add the temp table to the DOM (hidden)
        tempTable.style.position = 'absolute';
        tempTable.style.left = '-9999px';
        tempTable.style.top = '-9999px';
        document.body.appendChild(tempTable);
        
        // Generate PNG using html2canvas
        html2canvas(tempTable, {
            scale: 2, // Higher quality
            backgroundColor: '#ffffff',
            width: tempTable.offsetWidth,
            height: tempTable.offsetHeight
        }).then(canvas => {
            // Create download link
            const link = document.createElement('a');
            link.download = `Farmer_LivestockReport_${downloadCounter}.png`;
            link.href = canvas.toDataURL("image/png");
            link.click();
            
            // Increment download counter
            downloadCounter++;
            
            // Clean up - remove temporary table
            document.body.removeChild(tempTable);
            
            showNotification('PNG exported successfully!', 'success');
        }).catch(error => {
            console.error('Error generating PNG:', error);
            // Clean up on error
            if (document.body.contains(tempTable)) {
                document.body.removeChild(tempTable);
            }
            showNotification('Error generating PNG export', 'danger');
        });
    } catch (error) {
        console.error('Error generating PNG:', error);
        showNotification('Error generating PNG. Please try again.', 'danger');
    }
}


function updateActivity(selectElement, livestockId) {
    const newStatus = selectElement.value;
    
    // Make AJAX call to update the database
    $.ajax({
        url: `/farmer/livestock/${livestockId}/status`,
        method: 'POST',
        data: {
            status: newStatus,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showToast(`Status updated to ${newStatus} for livestock ${livestockId}`, 'success');
                // Update the stats if needed
                updateStats();
                // Update the original value
                selectElement.setAttribute('data-original-value', newStatus);
            } else {
                showToast(response.message || 'Error updating status', 'error');
                // Revert the select to previous value
                selectElement.value = selectElement.getAttribute('data-original-value') || 'active';
            }
        },
        error: function() {
            showToast('Error updating status', 'error');
            // Revert the select to previous value
            selectElement.value = selectElement.getAttribute('data-original-value') || 'active';
        }
    });
}

function updateStats() {
    // This function can be called to refresh the statistics
    // You can implement AJAX call to get updated stats
}

// Initialize tooltips if Bootstrap is available
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.tooltip) {
        $('[data-toggle="tooltip"]').tooltip();
    }
});




function showNotification(message, type = 'info') {
    const notification = $(`
        <div class="alert alert-${type} alert-dismissible fade show refresh-notification">
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

function showToast(message, type = 'info') {
    // Create a simple alert instead of Bootstrap 5 toast for Bootstrap 4 compatibility
    const alertClass = type === 'error' ? 'alert-danger' : type === 'success' ? 'alert-success' : 'alert-info';
    const alert = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <strong>${type.charAt(0).toUpperCase() + type.slice(1)}:</strong> ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    
    // Add alert to page
    const alertContainer = document.createElement('div');
    alertContainer.innerHTML = alert;
    document.body.appendChild(alertContainer);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alertContainer.parentNode) {
            alertContainer.parentNode.removeChild(alertContainer);
        }
    }, 5000);
}
</script>
@endpush

@push('styles')
<style>
     /* Make nav tabs responsive and scrollable on small screens */
@media (max-width: 768px) {
  #livestockTab {
    display: flex;
    flex-wrap: nowrap;          /* prevent wrapping */
    overflow-x: auto;           /* allow horizontal scroll */
    white-space: nowrap;        /* keep text inline */
    -webkit-overflow-scrolling: touch; /* smooth scroll on iOS */
    gap: 4px;
  }

  #livestockTab::-webkit-scrollbar {
    height: 6px; /* small horizontal scrollbar */
  }

  #livestockTab::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 10px;
  }

  #livestockTab .nav-item {
    flex: 0 0 auto; /* don't shrink items */
  }

  #livestockTab .nav-link {
    font-size: 0.9rem;
    padding: 8px 10px;
  }

  #livestockTab .nav-link i {
    margin-right: 4px;
    font-size: 0.9rem;
  }
}
     .action-toolbar {
    flex-wrap: nowrap !important;
    gap: 0.5rem;
}

/* Prevent buttons from stretching */
.action-toolbar .btn-action {
    flex: 0 0 auto !important;
    white-space: nowrap !important;
    width: auto !important;
}

/* Adjust spacing for mobile without stretching */
@media (max-width: 576px) {
    .action-toolbar {
        justify-content: center;
        gap: 0.6rem;
    }

    .action-toolbar .btn-action {
        font-size: 0.9rem;
        padding: 0.4rem 0.8rem;
        width: auto !important;
    }
}
    .smart-card {
  border-radius: 15px;
  background: #fff;
  transition: all 0.3s ease;
}
.smart-card:hover {
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}
.smart-card-header {
  border-radius: 15px 15px 0 0;
  padding: 0.75rem 1rem;
}
.smart-card-body {
  padding: 1.25rem;
}
/* Titles & Paragraphs */
.smart-card h6 {
    color: #18375d;
    font-weight: 700;
    margin-bottom: 0.4rem;
    letter-spacing: 0.5px;
}
/* Titles & Paragraphs */
.smart-card i{
    color: #18375d;
}
.smart-tabs .nav-link {
  border: none;
  color: #555;
  font-weight: 500;
}
.smart-tabs .nav-link.active {
  border-bottom: 3px solid #28a745;
  color: #28a745;
}
.smart-table th {
  width: 40%;
  background: #f8f9fa;
  font-weight: 600;
}
.smart-table td {
  background: #fff;
}
/* SMART DETAIL MODAL TEMPLATE */
.smart-detail .modal-content {
    border-radius: 1.5rem;
    border: none;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12);
    background-color: #fff;
    transition: all 0.3s ease-in-out;
    max-width: 90vw; /* make modal a bit wider */
    margin: auto;
}

/* Icon Header */
.smart-detail .icon-circle {
    width: 70px;
    height: 70px;
    background-color: #e8f0fe;
    color: #18375d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0rem;
}

/* Titles & Paragraphs */
.smart-detail h5 {
    color: #18375d;
    font-weight: 700;
    margin-bottom: 0.75rem;
    letter-spacing: 0.5px;
}

.smart-detail p {
    color: #6b7280;
    font-size: 0.96rem;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

/* MODAL BODY */
.smart-detail .modal-body {
    background: #ffffff;
    padding: 2.5rem 1rem; /* increased horizontal and vertical padding */
    border-radius: 1.25rem;
    max-height: 80vh; /* more vertical stretch before scrolling */
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

/* Detail Section */
.smart-detail .detail-wrapper {
    background: #f9fafb;
    border-radius: 1.25rem;
    padding: 0rem; /* more internal spacing */
    font-size: 0.97rem;
}

.smart-detail .detail-row {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px dashed #ddd;
    padding: 0.75rem 0;
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
#userDetailsModal .modal-footer {
    text-align: center;
    border-top: 1px solid #e5e7eb;
    padding-top: 1.25rem;
    margin-top: 1.5rem;
}

/* ============================
   TABLE LAYOUT
============================ */
    /* Apply consistent styling for Farmers, Livestock, and Issues tables */
#livestockTable th,
#livestockTable td,
#issuesTable th,
#issuesTable td {
    vertical-align: middle;
    padding: 0.75rem;
    text-align: center;
    border: 1px solid #dee2e6;
    white-space: nowrap;
    overflow: visible;
}

/* Ensure all table headers have consistent styling */
#livestockTable thead th,
#issuesTable thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: bold;
    color: #495057;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    text-align: center;
    vertical-align: middle;
    position: relative;
    white-space: nowrap;
}

/* Fix DataTables sorting button overlap */
#livestockTable thead th.sorting,
#livestockTable thead th.sorting_asc,
#livestockTable thead th.sorting_desc,
#issuesTable thead th.sorting,
#issuesTable thead th.sorting_asc,
#issuesTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#livestockTable thead th::after,
#issuesTable thead th::after {
    content: '';
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
}

/* Remove default DataTables sort indicators to prevent overlap */
#livestockTable thead th.sorting::after,
#livestockTable thead th.sorting_asc::after,
#livestockTable thead th.sorting_desc::after,
#issuesTable thead th.sorting::after,
#issuesTable thead th.sorting_asc::after,
#issuesTable thead th.sorting_desc::after {
    display: none;
}
/* Make table cells wrap instead of forcing them all inline */
#livestockTable td, 
#issuesTable th {
    white-space: normal !important;  /* allow wrapping */
    vertical-align: middle;
}

/* Make sure action buttons don’t overflow */
#livestockTable td .btn-group {
    display: flex;
    flex-wrap: wrap; /* buttons wrap if not enough space */
    gap: 0.25rem;    /* small gap between buttons */
}

#livestockTable td .btn-action {
    flex: 1 1 auto; /* allow buttons to shrink/grow */
    min-width: 90px; /* prevent too tiny buttons */
    text-align: center;
}

        /* ============================
   SMART FORM - Enhanced Version
   ============================ */
.smart-form {
  border: none;
  border-radius: 22px; /* slightly more rounded */
  box-shadow: 0 15px 45px rgba(0, 0, 0, 0.15);
  background-color: #ffffff;
  padding: 3rem 3.5rem; /* bigger spacing */
  transition: all 0.3s ease;
  max-width: 900px; /* slightly wider form container */
  margin: 0; /* remove outer margins inside modal to maximize usable height */
  /* Let Bootstrap's .modal-body handle scrolling */
  max-height: none;
  overflow: visible;
}

.smart-form:hover {
  box-shadow: 0 18px 55px rgba(0, 0, 0, 0.18);
}

/* Header Icon */
.smart-form .icon-circle {
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
.smart-form h5 {
  color: #18375d;
  font-weight: 700;
  margin-bottom: 0.4rem;
  letter-spacing: 0.5px;
}

.smart-form p {
  color: #6b7280;
  font-size: 0.96rem;
  margin-bottom: 1.8rem;
  line-height: 1.5;
}

/* Form Container */
.smart-form .form-wrapper {
  max-width: 720px;
  margin: 0 auto;
}

/* ============================
   FORM ELEMENT STYLES
   ============================ */
#livestockModal form {
  text-align: left;
}

#livestockModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#livestockModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#livestockModal .form-control,
#livestockModal select.form-control,
#livestockModal textarea.form-control {
  border-radius: 12px;
  border: 1px solid #d1d5db;
  padding: 12px 15px;          /* consistent padding */
  font-size: 15px;             /* consistent font */
  line-height: 1.5;
  transition: all 0.2s ease;
  width: 100%;
  height: 46px;                /* unified height */
  box-sizing: border-box;
  margin-top: 0.5rem;
  margin-bottom: 1rem;
  background-color: #fff;
}

/* Keep textarea resizable but visually aligned */
#livestockModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#livestockModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}


#editLivestockModal form {
  text-align: left;
}

#editLivestockModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#editLivestockModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#editLivestockModal .form-control,
#editLivestockModal select.form-control,
#editLivestockModal textarea.form-control {
  border-radius: 12px;
  border: 1px solid #d1d5db;
  padding: 12px 15px;          /* consistent padding */
  font-size: 15px;             /* consistent font */
  line-height: 1.5;
  transition: all 0.2s ease;
  width: 100%;
  height: 46px;                /* unified height */
  box-sizing: border-box;
  margin-top: 0.5rem;
  margin-bottom: 1rem;
  background-color: #fff;
}

/* Keep textarea resizable but visually aligned */
#editLivestockModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#editLivestockModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}
/* ============================
   FORM ELEMENT STYLES
   ============================ */
#issueAlertModal form {
  text-align: left;
}

#issueAlertModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#issueAlertModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#issueAlertModal .form-control,
#issueAlertModal select.form-control,
#issueAlertModal textarea.form-control {
  border-radius: 12px;
  border: 1px solid #d1d5db;
  padding: 12px 15px;          /* consistent padding */
  font-size: 15px;             /* consistent font */
  line-height: 1.5;
  transition: all 0.2s ease;
  width: 100%;
  height: 46px;                /* unified height */
  box-sizing: border-box;
  margin-top: 0.5rem;
  margin-bottom: 1rem;
  background-color: #fff;
}

/* Keep textarea resizable but visually aligned */
#issueAlertModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#issueAlertModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}

/* ============================
   CRITICAL FIX FOR DROPDOWN TEXT CUTTING
   ============================ */
.admin-modal select.form-control,
.modal.admin-modal select.form-control,
.admin-modal .modal-body select.form-control {
  min-width: 250px !important;
  width: 100% !important;
  max-width: 100% !important;
  box-sizing: border-box !important;
  padding: 12px 15px !important;  /* match input padding */
  white-space: nowrap !important;
  text-overflow: clip !important;
  overflow: visible !important;
  font-size: 15px !important;     /* match input font */
  line-height: 1.5 !important;
  height: 46px !important;        /* same height as input */
  background-color: #fff !important;
}

/* Ensure columns don't constrain dropdowns */
.admin-modal .col-md-6 {
  min-width: 280px !important;
  overflow: visible !important;
}

/* Prevent modal body from clipping dropdowns */
.admin-modal .modal-body {
  overflow: visible !important;
}

/* ============================
   BUTTONS
   ============================ */
.btn-approve,
.btn-delete,
.btn-ok {
  font-weight: 600;
  border: none;
  border-radius: 10px;
  padding: 10px 24px;
  transition: all 0.2s ease-in-out;
}

.btn-approves {
  background: #387057;
  color: #fff;
}
.btn-approves:hover {
  background: #fca700;
  color: #fff;
}
.btn-cancel {
  background: #387057;
  color: #fff;
}
.btn-cancel:hover {
  background: #fca700;
  color: #fff;
}

.btn-delete {
  background: #dc3545;
  color: #fff;
}
.btn-delete:hover {
  background: #fca700;
  color: #fff;
}

.btn-ok {
  background: #18375d;
  color: #fff;
}
.btn-ok:hover {
  background: #fca700;
  color: #fff;
}
.btn-edit {
  background: #387057;
  color: #fff;
}
.btn-edit:hover {
  background: #fca700;
  color: #fff;
}

/* ============================
   FOOTER & ALIGNMENT
   ============================ */
#livestockModal .modal-footer {
  text-align: center;
  border-top: 1px solid #e5e7eb;
  padding-top: 1.25rem;
  margin-top: 1.5rem;
}

/* ============================
   RESPONSIVE DESIGN
   ============================ */
@media (max-width: 768px) {
  .smart-form {
    padding: 1.5rem;
  }

  .smart-form .form-wrapper {
    max-width: 100%;
  }

  #livestockModal .form-control {
    font-size: 14px;
  }

  #editLivestockModal .form-control {
    font-size: 14px;
  }
   #issueAlertModal .form-control {
    font-size: 14px;
  }

  .btn-ok,
  .btn-edit,
  .btn-delete,
  .btn-approves {
    width: 100%;
    margin-top: 0.5rem;
  }
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
}s
.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
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
/* Search and button group alignment - Match SuperAdmin */
.search-controls {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

@media (min-width: 768px) {
    .search-controls {
        flex-direction: row;
        justify-content: space-between;
        align-items: flex-end; /* Align to bottom for perfect leveling */
    }
}

.search-controls .input-group {
    flex-shrink: 0;
    align-self: flex-end; /* Ensure input group aligns to bottom */
}

.search-controls .btn-group {
    flex-shrink: 0;
    align-self: flex-end; /* Ensure button group aligns to bottom */
    display: flex;
    align-items: center;
}

/* Ensure buttons have consistent height with input */
.search-controls .btn-action {
    height: 38px; /* Match Bootstrap input height */
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
}

/* Ensure dropdown button is perfectly aligned */
.search-controls .dropdown .btn-action {
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Ensure all buttons in the group have the same baseline */
.search-controls .d-flex {
    align-items: center;
    gap: 0.75rem; /* Increased gap between buttons */
}

@media (max-width: 767px) {
    .search-controls {
        align-items: stretch;
    }
    
    .search-controls .btn-group {
        margin-top: 0.5rem;
        justify-content: center;
        align-self: center;
    }
    
    .search-controls .input-group {
        max-width: 100% !important;
    }
}

/* Enhanced Card Styling */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    overflow: hidden;
}

/* Override card header to avoid duplicate blue backgrounds */
.card-header.bg-primary {
    background: #18375d !important;
    color: white !important;
    border-bottom: none !important;
}

.card-header.bg-primary h6 {
    color: white !important;
    font-weight: 600;
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

/* Enhanced Button Styling */
.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
    border: none;
    font-size: 0.85rem;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-sm {
    padding: 0.4rem 0.8rem;
    font-size: 0.8rem;
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

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 1;
}

.stat-label {
    color: var(--dark-color);
    margin: 0;
    font-weight: 500;
    position: relative;
    z-index: 1;
}


/* Make cow icon visible */
.page-header h1 i.fas.fa-horse {
    color: white !important;
    display: inline-block !important;
    visibility: visible !important;
    font-family: "Font Awesome 6 Free" !important;
    font-weight: 900 !important;
}

/* Action buttons styling - Match Super Admin Exactly */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    justify-content: center;
    min-width: 160px;
}

/* Force delete button to be red */
.btn-action-delete,
.action-buttons .btn-action-delete{
    background-color: #c82333 !important;
    border-color: #c82333 !important;
    color: white !important;
}

.btn-action-delete:hover,
.action-buttons .btn-action-delete:hover {
    background-color: #a71e2a !important;
    border-color: #a71e2a !important;
    color: white !important;
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
    background-color: #2d5a47;
    border-color: #2d5a47;
    color: white;
}


/* Header action buttons styling to match superadmin */
.btn-action-add {
    background-color: #387057;
    border-color: #387057;
    color: white;
}

.btn-action-add:hover {
    background-color: #2d5a47;
    border-color: #2d5a47;
    color: white;
}

.btn-action-print {
    background-color: #6c757d !important;
    border-color: #6c757d !important;
    color: white !important;
}

.btn-action-print:hover {
    background-color: #5a6268 !important;
    border-color: #5a6268 !important;
    color: white !important;
}

.btn-action-refresh {
    background-color: #fca700;
    border-color: #fca700;
    color: white;
}

.btn-action-refresh:hover {
    background-color: #e69500;
    border-color: #e69500;
    color: white;
}

.btn-action-tools {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #495057;
}

.btn-action-tools:hover {
    background-color: #e2e6ea;
    border-color: #cbd3da;
    color: #495057;
}



/* Ensure table has enough space for actions column */
    .table th:last-child,
    .table td:last-child {
        min-width: 200px;
        width: auto;
    }
    
    /* Table responsiveness and spacing */
    .table-responsive {
        overflow-x: auto;
        min-width: 100%;
        position: relative;
    }
    
    /* Ensure DataTables controls are properly positioned */
    .table-responsive + .dataTables_wrapper,
    .table-responsive .dataTables_wrapper {
        width: 100%;
        position: relative;
    }
    /* Fix pagination positioning for wide tables - match active admins spacing */
    .table-responsive .dataTables_wrapper .dataTables_paginate {
        position: relative;
        width: 100%;
        text-align: left;
        margin: 1rem 0;
        left: 0;
        right: 0;
    }
    
    /* Fix pagination positioning for wide tables */
    .table-responsive .dataTables_wrapper .dataTables_paginate {
        position: relative;
        width: 100%;
        text-align: left;
        margin: 1rem 0;
        left: 0;
        right: 0;
    }
    
    #usersTable {
        width: 100% !important;
        min-width: 1280px;
        border-collapse: collapse;
    }
    
    /* Ensure consistent table styling */
    .table {
        margin-bottom: 0;
    }
    
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.075);
    }
    
    /* Ensure consistent table styling */
    .table {
        margin-bottom: 0;
    }
    
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.075);
    }
/* Table responsiveness and spacing - Super Admin Style */
.table-responsive {
    overflow-x: auto;
    min-width: 100%;
    position: relative;
    border-radius: 8px;
    overflow: hidden;
}

/* Ensure DataTables controls are properly positioned */
.table-responsive + .dataTables_wrapper,
.table-responsive .dataTables_wrapper {
    width: 100%;
    position: relative;
}

/* Fix pagination positioning for wide tables - Match SuperAdmin */
.table-responsive .dataTables_wrapper .dataTables_paginate {
    position: relative;
    width: 100%;
    text-align: left;
    margin: 1rem 0;
    left: 0;
    right: 0;
}

/* Hide only DataTables search and length controls - show pagination and info */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    display: none !important;
}

/* DataTables Pagination Styling - Match SuperAdmin User Directory Exactly */
.dataTables_wrapper .dataTables_paginate {
    text-align: left !important;
    margin-top: 1rem;
    clear: both;
    width: 100%;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    display: inline-block;
    min-width: 2.5rem;
    padding: 0.5rem 0.75rem;
    margin: 0 0.125rem;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    color: #495057;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    background-color: #fff;
    transition: all 0.15s ease-in-out;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    color: #18375d;
    background-color: #e9ecef;
    border-color: #adb5bd;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    color: #fff;
    background-color: #18375d;
    border-color: #18375d;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
    cursor: not-allowed;
    opacity: 0.5;
}

.dataTables_wrapper .dataTables_info {
    margin-top: 1rem;
    margin-bottom: 0.5rem;
    color: #495057;
    font-size: 0.875rem;
}

/* Ensure pagination container is properly positioned - Match SuperAdmin */
.dataTables_wrapper {
    width: 100%;
    margin: 0 auto;
}

.dataTables_wrapper .row {
    display: flex;
    flex-wrap: wrap;
    margin: 0;
}

.dataTables_wrapper .row > div {
    padding: 0;
}

/* Empty State Styling - Match Super Admin */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    color: #dee2e6;
}

.empty-state h5 {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #495057;
}

.empty-state p {
    margin-bottom: 0;
    font-size: 0.9rem;
}


/* Livestock ID link styling - match superadmin theme */
.livestock-id-link {
    color: #18375d;
    text-decoration: none;
    font-weight: 600;
    cursor: pointer;
    transition: color 0.2s ease;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    background-color: rgba(24, 55, 93, 0.1);
    border: 1px solid rgba(24, 55, 93, 0.2);
}

.livestock-id-link:hover {
    color: #fff;
    background-color: #18375d;
    border-color: #18375d;
    text-decoration: none;
}

.livestock-id-link:active {
    color: #fff;
    background-color: #122a4e;
    border-color: #122a4e;
}

/* Search Container */
.custom-search {
    border-radius: 8px;
    border: 2px solid var(--border-color);
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
}

.custom-search:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}



/* Status Badge Styling */
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
    color: #e74a3b;
    border: 1px solid rgba(231, 74, 59, 0.3);
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

/* Form Styling */
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 0.5rem;
    display: block;
}

.form-control {
    border-radius: 8px;
    border: 2px solid var(--border-color);
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.form-control[readonly] {
    background-color: #f8f9fc;
    border-color: #e3e6f0;
}

/* Fix for dropdown select elements to prevent text cutoff */
select.form-control {
    padding-right: 2.5rem !important; /* Extra space for dropdown arrow */
    overflow: visible;
    text-overflow: ellipsis;
    white-space: normal;
    height: auto;
    min-height: calc(1.5em + 0.75rem + 4px);
}

select.form-control option {
    padding: 0.5rem;
    white-space: normal;
    overflow: visible;
    text-overflow: clip;
}

/* Modal Enhancement */
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
}

.modal-header .close {
    color: white;
    opacity: 0.8;
}

.modal-header .close:hover {
    opacity: 1;
}

.modal-title {
    color: white;
    font-weight: 600;
}

/* Tab Enhancement */
.nav-tabs {
    border-bottom: 2px solid var(--border-color);
    margin-bottom: 1.5rem;
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: var(--dark-color);
    font-weight: 500;
    padding: 1rem 1.5rem;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
}

.nav-tabs .nav-link.active {
    background: none;
    border-color: var(--primary-color);
    color: var(--primary-color);
    font-weight: 600;
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
        margin-left: 0;
        margin-top: 1rem;
    }

    .stats-container {
        grid-template-columns: 1fr;
    }

    .container-fluid {
        padding: 1rem;
    }
    
    .modal-dialog {
        margin: 1rem;
    }
}

.toast-container {
    z-index: 9999;
}
</style>
@endpush

