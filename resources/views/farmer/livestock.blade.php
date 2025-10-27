@extends('layouts.app')

@section('title', 'Livestock Management')

@section('content')
    <!-- Page Header -->
    <div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
        <h1>
            <i class="fas fa-list"></i>
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
                <table class="table table-bordered " id="livestockTable">
                    <thead class="thead-light">
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
            <div class="modal-body" style="overflow-x: auto;">
                <div id="livestockDetailsContent">
                    <!-- Dynamic details will be injected here -->
                </div>
            </div>

            <!-- Footer -->
             <div class="modal-footer justify-content-center mt-4">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn-modern btn-edit" onclick="printLivestockRecord()" id="printLivestockBtn">
                    <i class="fas fa-print"></i> Print Record
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
let currentLivestockData = null;
let currentLivestockRemarksMeta = '';
let currentLivestockPrintBasic = null;


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
                autoWidth: true,
                scrollX: false,
                order: [[0, 'asc']],
                columnDefs: [
                    { width: '100px', targets: 0 }, // Livestock ID
                    { width: '120px', targets: 1 }, // Type
                    { width: '140px', targets: 2 }, // Breed
                    { width: '100px', targets: 3 }, // Age
                    { width: '120px', targets: 4 }, // Weight
                    { width: '140px', targets: 5 }, // Health Status
                    { width: '140px', targets: 6 }, // Registration Date
                    { width: '220px', targets: 7, orderable: false }, // Actions
                    { targets: '_all', className: 'text-center align-middle' }
                ],
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
            livestockTable.columns.adjust();
            
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
            
            $('#livestockSearch').on('keyup', function() {
        if ($.fn.DataTable.isDataTable('#livestockTable')) {
            $('#livestockTable').DataTable().search(this.value).draw();
        }
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
    // Remove any previous method override (e.g., from Edit)
    $('#livestockForm').find('input[name="_method"]').remove();
    currentLivestockRemarksMeta = '';
    // Default Owned By to current farmer
    if (typeof CURRENT_FARMER_NAME !== 'undefined') {
        $('#owned_by').val(CURRENT_FARMER_NAME);
    }
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
                $('#owned_by').val(livestock.owned_by || '');
                const rawRemarksEdit = (livestock.remarks || '').toString();
                const metaLines = rawRemarksEdit
                    .split(/\r?\n/)
                    .filter(line => /^\s*\[(Health|Breeding|Calving|Growth|Production)\]/i.test(line))
                    .join('\n')
                    .trim();
                const cleanedEdit = rawRemarksEdit
                    .split(/\r?\n/)
                    .filter(line => !/^\s*\[(Health|Breeding|Calving|Growth|Production)\]/i.test(line))
                    .join('\n')
                    .trim();
                currentLivestockRemarksMeta = metaLines;
                $('#remarks').val(cleanedEdit);
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
                const livestock = response.livestock || response.data || (response.data && response.data.livestock) || response;
                currentLivestockData = livestock;
                // Build a normalized basic info snapshot for reliable printing
                currentLivestockPrintBasic = {
                    tag_number: (livestock && (livestock.tag_number || livestock.livestock_id || livestock.tagNumber)) || '',
                    name: (livestock && (livestock.name || livestock.livestock_name)) || '',
                    type: (livestock && livestock.type) || '',
                    breed: (livestock && livestock.breed) || '',
                    gender: (livestock && livestock.gender) || '',
                    birth_date: (livestock && (livestock.birth_date || livestock.birthDate)) || '',
                    status: (livestock && livestock.status) || '',
                    health_status: (livestock && (livestock.health_status || livestock.healthStatus)) || '',
                    weight: (livestock && (livestock.weight ?? '')),
                    farm_name: (livestock && livestock.farm && (livestock.farm.name || livestock.farm.farm_name)) || ''
                };
                
                // Calculate age from birth date
                const age = livestock.birth_date ? calculateAge(livestock.birth_date) : 'Unknown';
                
                // Format dates properly
                const birthDate = livestock.birth_date ? new Date(livestock.birth_date).toLocaleDateString() : 'Not recorded';
                const createdDate = livestock.created_at ? new Date(livestock.created_at).toLocaleDateString() : 'Not recorded';
                const updatedDate = livestock.updated_at ? new Date(livestock.updated_at).toLocaleDateString() : 'Not recorded';
                
                const rawRemarks = (livestock.remarks || '').toString();
                const cleanedRemarks = rawRemarks
                    .split(/\r?\n/)
                    .filter(line => !/^\s*\[(Health|Breeding|Calving|Growth|Production)\]/i.test(line))
                    .join('\n')
                    .trim();
                
                $('#livestockDetailsContent').html(`
            <!-- Smart Detail Body Content -->
<div id="livestockPrintContainer" style="display:none;"></div>

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
                        <small class="text-muted">Previous Weight</small>
                        <p class="fw-bold mb-0">${livestock.previous_weight ? livestock.previous_weight + ' kg' : 'N/A'}</p>
                    </div>

                    <div class="col-6 mb-2">
                        <small class="text-muted">Previous Weight Date</small>
                        <p class="fw-bold mb-0">${livestock.previous_weight_date ? new Date(livestock.previous_weight_date).toLocaleDateString() : 'N/A'}</p>
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
                    <tr><th>Previous Weight</th><td>${livestock.previous_weight ? livestock.previous_weight + ' kg' : 'Not recorded'}</td></tr>
                    <tr><th>Previous Weight Date</th><td>${livestock.previous_weight_date ? new Date(livestock.previous_weight_date).toLocaleDateString() : 'Not recorded'}</td></tr>
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
                    <tr><th>Remarks</th><td>${cleanedRemarks || 'None'}</td></tr>
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
                        <th style="text-align: left !important;">Notes</th>
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
                        <th style="text-align: left !important;">Notes</th>
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
        <div class="smart-table table-responsive mt-3" style="display:block; max-width:100%; overflow-x: auto !important; -webkit-overflow-scrolling: touch; padding-bottom:8px;">
            <table class="table table-sm table-bordered align-middle mb-0" style="min-width: 1200px !important; table-layout: auto; width: 100% !important;">
                <thead class="thead-light">
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Partner</th>
                        <th>Pregnancy</th>
                        <th>Success</th>
                        <th style="text-align: left !important;">Notes</th>
                    </tr>
                </thead>
                <tbody id="breedingRecordsTable">
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            <i class="fas fa-info-circle"></i>
                            No breeding records found.
                        </td>
                    </tr>
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
                
                // Load records for tabs
                loadProductionRecords(livestockId);
                loadHealthRecords(livestockId);
                loadBreedingRecords(livestockId);
                
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
    
    if (method === 'POST' && $('#livestockForm').find('input[name="_method"]').length > 0) {
        formData.append('_method', 'PUT');
    }
    if ($('#livestockForm').find('input[name="_method"]').length > 0) {
        let editedRemarks = formData.get('remarks') || '';
        if (currentLivestockRemarksMeta) {
            const merged = (editedRemarks ? editedRemarks.toString().trim() + '\n' : '') + currentLivestockRemarksMeta;
            formData.set('remarks', merged.trim());
        }
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
        if (!currentLivestockData) {
            const contentEl = document.getElementById('livestockDetailsContent');
            if (!contentEl) { showToast('Nothing to print. Open the livestock details first.', 'warning'); return; }
            window.printElement('#livestockDetailsContent');
            return;
        }
        const l = currentLivestockData;
        const fmt = (d) => {
            if (!d) return '';
            try { const x = new Date(d); if (!isNaN(x)) return x.toLocaleDateString(); } catch(e) {}
            const s = String(d); return s.length >= 10 ? s.slice(0,10) : s;
        };
        // Safe getter to handle varying API payload shapes
        const v = (obj, keys) => {
            for (const k of keys) {
                if (obj && obj[k] !== undefined && obj[k] !== null && obj[k] !== '') return obj[k];
            }
            return '';
        };
        const farmName = (l.farm && (l.farm.name || l.farm.farm_name)) ? (l.farm.name || l.farm.farm_name) : '';
        const b = currentLivestockPrintBasic || {
            tag_number: (l && (l.tag_number || l.livestock_id || l.tagNumber)) || '',
            name: (l && (l.name || l.livestock_name)) || '',
            type: (l && l.type) || '',
            breed: (l && l.breed) || '',
            gender: (l && l.gender) || '',
            birth_date: (l && (l.birth_date || l.birthDate)) || '',
            status: (l && l.status) || '',
            health_status: (l && (l.health_status || l.healthStatus)) || '',
            weight: (l && (l.weight ?? '')),
            farm_name: farmName
        };
        // Removed large section titles (H3) to avoid duplicates with table headers in print output.
        const basicTitle = ``;
        const prodTitle = ``;
        const healthTitle = ``;
        const breedingTitle = ``;

        const detailsReq = $.ajax({ url: `/farmer/livestock/${currentLivestockId}`, method: 'GET' });
        const editReq = $.ajax({ url: `/farmer/livestock/${currentLivestockId}/edit`, method: 'GET' });
        const prodReq = $.ajax({ url: `/farmer/livestock/${currentLivestockId}/production-records`, method: 'GET' });
        const healthReq = $.ajax({ url: `/farmer/livestock/${currentLivestockId}/health-records`, method: 'GET' });
        const breedReq = $.ajax({ url: `/farmer/livestock/${currentLivestockId}/breeding-records`, method: 'GET' });

        $.when(detailsReq, prodReq, healthReq, breedReq, editReq).done(function(dRes, pRes, hRes, brRes, eRes) {
            const fresh = dRes && dRes[0] ? (dRes[0].livestock || dRes[0].data || (dRes[0].data && dRes[0].data.livestock) || null) : null;
            const fallbackEdit = eRes && eRes[0] ? (eRes[0].livestock || eRes[0].data || null) : null;
            const ll = fresh || fallbackEdit || l || {};
            const farm2 = (ll.farm && (ll.farm.name || ll.farm.farm_name)) ? (ll.farm.name || ll.farm.farm_name) : farmName;
            const getDomBasicValue = (label) => {
                const norm = (s) => (s || '')
                    .toString()
                    .replace(/\s*:\s*$/, '')
                    .trim()
                    .toLowerCase();
                const synonyms = {
                    'tag number': ['tag number', 'tag id number', 'livestock id'],
                    'name': ['name', 'livestock name'],
                    'type': ['type'],
                    'breed': ['breed'],
                    'gender': ['gender', 'sex'],
                    'birth date': ['birth date', 'date of birth'],
                    'status': ['status', 'activity'],
                    'health status': ['health status'],
                    'weight': ['weight', 'weight (kg)'],
                    'farm': ['farm', 'cooperative']
                };
                const wanted = norm(label);
                const accepted = new Set(synonyms[wanted] || [wanted]);
                try {
                    const container = document.getElementById('livestockDetailsContent');
                    if (!container) return '';
                    const nodes = container.querySelectorAll('small.text-muted');
                    for (const sm of nodes) {
                        const lbl = norm(sm.textContent || '');
                        if (accepted.has(lbl)) {
                            const p = sm.parentElement ? sm.parentElement.querySelector('p') : null;
                            const val = (p && p.textContent) ? p.textContent.trim() : '';
                            if (val && val !== 'N/A') return val;
                        }
                    }
                    const basicRows = container.querySelectorAll('#basicForm table tbody tr');
                    for (const row of basicRows) {
                        const th = row.querySelector('th');
                        const td = row.querySelector('td');
                        if (!th || !td) continue;
                        const thLabel = norm(th.textContent || '');
                        if (accepted.has(thLabel)) {
                            const val = (td.textContent || '').trim();
                            if (val && !/^not\s+recorded$/i.test(val) && val !== 'N/A') return val;
                        }
                    }
                } catch (_) {}
                return '';
            };
            const bb = {
                tag_number: (ll.tag_number || ll.livestock_id || ll.tagNumber || b.tag_number || getDomBasicValue('Tag Number') || ''),
                name: (ll.name || ll.livestock_name || b.name || getDomBasicValue('Name') || ''),
                type: (ll.type || b.type || getDomBasicValue('Type') || ''),
                breed: (ll.breed || b.breed || getDomBasicValue('Breed') || ''),
                gender: (ll.gender || b.gender || getDomBasicValue('Gender') || ''),
                birth_date: (ll.birth_date || ll.birthDate || b.birth_date || getDomBasicValue('Birth Date') || getDomBasicValue('Date of Birth') || ''),
                status: (ll.status || b.status || getDomBasicValue('Status') || ''),
                health_status: (ll.health_status || ll.healthStatus || b.health_status || getDomBasicValue('Health Status') || ''),
                weight: ((ll.weight ?? b.weight) ?? getDomBasicValue('Weight') ?? getDomBasicValue('Weight (kg)') ?? ''),
                farm_name: farm2 || b.farm_name || getDomBasicValue('Farm') || ''
            };

            const ensureFilled = (primary, fallback) => {
                const out = { ...primary };
                const keys = ['tag_number','name','type','breed','gender','birth_date','status','health_status','weight','farm_name'];
                keys.forEach(k => {
                    const v = out[k];
                    if (v === undefined || v === null || (typeof v === 'string' && v.trim() === '')) {
                        const fv = fallback && fallback[k] !== undefined && fallback[k] !== null ? fallback[k] : '';
                        out[k] = fv;
                    }
                });
                return out;
            };
            const bbSafe = ensureFilled(bb, b);

            const money = (n) => {
                if (n === undefined || n === null || n === '') return '';
                const num = Number(n);
                if (isNaN(num)) return '';
                return '₱' + num.toFixed(2);
            };
            const up1 = (s) => (s || '').replace(/^\w/, m=>m.toUpperCase());
            const upWords = (s) => (s || '').replace('_',' ').replace(/\b\w/g, c=>c.toUpperCase());
            const ageStr = ll.birth_date ? (typeof calculateAge === 'function' ? calculateAge(ll.birth_date) : (function(bd){
                try {
                    if (!bd) return '';
                    const d = new Date(bd); const t = new Date();
                    let y = t.getFullYear() - d.getFullYear();
                    const m = t.getMonth() - d.getMonth();
                    if (m < 0 || (m === 0 && t.getDate() < d.getDate())) y--;
                    return y > 0 ? `${y} year${y>1?'s':''}` : '';
                } catch(_) { return ''; }
            })(ll.birth_date)) : '';
            const registry = ll.registry_id || '';
            const naturalMarks = ll.natural_marks || '';
            const propertyNo = ll.property_no || '';
            const acqDate = fmt(ll.acquisition_date || ll.acquisitionDate || '');
            const acqCost = money(ll.acquisition_cost);
            const remarksClean = (() => {
                const raw = (ll.remarks || '').toString();
                return raw.split(/\r?\n/).filter(line => !/^\s*\[(Health|Breeding|Calving|Growth|Production)\]/i.test(line)).join('\n').trim();
            })();
            const createdAt = fmt(ll.created_at || ll.createdAt || '');

            const typeDisp = upWords(bbSafe.type);
            const breedDisp = upWords(bbSafe.breed);
            const genderDisp = up1(bbSafe.gender);
            const statusDisp = up1(bbSafe.status);
            const healthDisp = up1(bbSafe.health_status);

            const weightDisp = bbSafe.weight !== undefined && bbSafe.weight !== null && bbSafe.weight !== '' ? bbSafe.weight : '';

            const basicInfo = `
                <table>
                    <thead><tr><th colspan="2">Basic Information</th></tr></thead>
                    <tbody>
                        <tr><td>Tag Number</td><td>${bbSafe.tag_number || ''}</td></tr>
                        <tr><td>Name</td><td>${bbSafe.name || ''}</td></tr>
                        <tr><td>Type</td><td>${typeDisp}</td></tr>
                        <tr><td>Breed</td><td>${breedDisp}</td></tr>
                        <tr><td>Date of Birth</td><td>${fmt(bbSafe.birth_date)}</td></tr>
                        <tr><td>Age</td><td>${ageStr}</td></tr>
                        <tr><td>Gender</td><td>${genderDisp}</td></tr>
                        <tr><td>Weight</td><td>${weightDisp}</td></tr>
                        <tr><td>Health Status</td><td>${healthDisp}</td></tr>
                        <tr><td>Status</td><td>${statusDisp}</td></tr>
                        <tr><td>Farm</td><td>${bbSafe.farm_name || ''}</td></tr>
                        <tr><td>Registry ID</td><td>${registry}</td></tr>
                        <tr><td>Natural Marks</td><td>${naturalMarks}</td></tr>
                        <tr><td>Property Number</td><td>${propertyNo}</td></tr>
                        <tr><td>Acquisition Date</td><td>${acqDate}</td></tr>
                        <tr><td>Acquisition Cost</td><td>${acqCost}</td></tr>
                        <tr><td>Remarks</td><td>${remarksClean}</td></tr>
                        <tr><td>Created</td><td>${createdAt}</td></tr>
                    </tbody>
                </table>`;

            try {
                const core = [bbSafe.tag_number, bbSafe.name, bbSafe.type, bbSafe.breed, bbSafe.gender, bbSafe.birth_date, bbSafe.status, bbSafe.health_status, bbSafe.farm_name];
                const allBlank = core.every(v => (v === undefined || v === null || String(v).trim() === ''));
                if (allBlank) {
                    const el = document.getElementById('livestockDetailsContent');
                    if (el) {
                        if (typeof window.printElement === 'function') {
                            window.printElement('#livestockDetailsContent');
                        } else {
                            const w = window.open('', '_blank');
                            if (w) { w.document.write(el.outerHTML); w.document.close(); try{w.print();}catch(_){} }
                        }
                    }
                    return;
                }
            } catch(_) {}

            const pData = (pRes && pRes[0] && pRes[0].success) ? (pRes[0].data || []) : [];
            const hData = (hRes && hRes[0] && hRes[0].success) ? (hRes[0].data || []) : [];
            const bData = (brRes && brRes[0] && brRes[0].success) ? (brRes[0].data || []) : [];

            const prodRows = pData.map(r => {
                // Derive type from notes if not provided
                let pType = r.production_type || '';
                if (!pType && r.notes) {
                    const m = r.notes.match(/\[type:\s*([^\]]+)\]/i); if (m) pType = m[1];
                }
                let noteText = r.notes || '';
                noteText = noteText.replace(/\[type:\s*[^\]]+\]\s*/i, '').trim();
                return `<tr>
                    <td>${r.production_date ? new Date(r.production_date).toLocaleDateString() : ''}</td>
                    <td>${pType || 'Milk'}</td>
                    <td>${r.quantity ?? ''}</td>
                    <td>${r.quality ?? ''}</td>
                    <td>${noteText || ''}</td>
                </tr>`;
            }).join('');
            const productionTbl = `
                <table>
                    <thead><tr><th colspan="5">Production Records</th></tr><tr><th>Date</th><th>Type</th><th>Quantity</th><th>Quality</th><th>Notes</th></tr></thead>
                    <tbody>${prodRows || '<tr><td colspan="5">No production records found.</td></tr>'}</tbody>
                </table>`;

            const healthRows = hData.map(r => `
                <tr>
                    <td>${r.date ? new Date(r.date).toLocaleDateString() : ''}</td>
                    <td>${r.status || ''}</td>
                    <td>${r.treatment || ''}</td>
                    <td>${r.veterinarian || ''}</td>
                    <td>${r.notes || ''}</td>
                </tr>
            `).join('');
            const healthTbl = `
                <table>
                    <thead><tr><th colspan="5">Health Records</th></tr><tr><th>Date</th><th>Status</th><th>Treatment</th><th>Veterinarian</th><th>Notes</th></tr></thead>
                    <tbody>${healthRows || '<tr><td colspan="5">No health records found.</td></tr>'}</tbody>
                </table>`;

            const breedingRows = bData.map(r => `
                <tr>
                    <td>${r.date ? new Date(r.date).toLocaleDateString() : ''}</td>
                    <td>${r.type || ''}</td>
                    <td>${r.partner || ''}</td>
                    <td>${r.pregnancy || ''}</td>
                    <td>${r.success || ''}</td>
                    <td>${r.notes || ''}</td>
                </tr>
            `).join('');
            const breedingTbl = `
                <table>
                    <thead><tr><th colspan="6">Breeding Records</th></tr><tr><th>Date</th><th>Type</th><th>Partner</th><th>Pregnancy</th><th>Success</th><th>Notes</th></tr></thead>
                    <tbody>${breedingRows || '<tr><td colspan="6">No breeding records found.</td></tr>'}</tbody>
                </table>`;

            const title = `
                <div style="margin-bottom:10px; text-align:center;">
                    <h3 style="margin:0 0 6px 0;color:#18375d;">Individual Animal Record</h3>
                    <div style="font-size:12px;color:#333;">Generated: ${new Date().toLocaleString()}</div>
                </div>`;

            const tableCss = `
                <style>
                @page{size:auto;margin:12mm;}
                html,body{background:#fff!important;color:#000;}
                table{width:100%;border-collapse:collapse;margin:0 0 12px 0;}
                th,td{border:3px solid #000;padding:10px;text-align:left;}
                thead th{background:#f2f2f2;color:#18375d;}
                </style>`;

            const container = document.createElement('div');
            // Exclude large H3 titles; rely on table headers only.
            container.innerHTML = tableCss + `<div>${title}${basicInfo}${productionTbl}${healthTbl}${breedingTbl}</div>`;
            if (typeof window.printElement === 'function') {
                window.printElement(container);
            } else if (typeof window.openPrintWindow === 'function') {
                window.openPrintWindow(container.innerHTML, '');
            } else {
                const w = window.open('', '_blank');
                if (w) {
                    w.document.open();
                    w.document.write(`<html><head><title></title></head><body>${container.innerHTML}</body></html>`);
                    w.document.close();
                    try { w.focus(); } catch(_){}
                    try { w.print(); } catch(_){}
                    try { w.close(); } catch(_){}
                } else {
                    window.print();
                }
            }
        }).fail(function(){
            const tableCss = `
                <style>
                @page{size:auto;margin:12mm;}
                html,body{background:#fff!important;color:#000;}
                table{width:100%;border-collapse:collapse;margin:0 0 12px 0;}
                th,td{border:3px solid #000;padding:10px;text-align:left;}
                thead th{background:#f2f2f2;color:#18375d;}
                </style>`;
            const container = document.createElement('div');
            container.innerHTML = tableCss + `<div>${basicInfo}</div>`;
            if (typeof window.printElement === 'function') {
                window.printElement(container);
            } else if (typeof window.openPrintWindow === 'function') {
                window.openPrintWindow(container.innerHTML, 'Livestock Record');
            } else {
                const w = window.open('', '_blank');
                if (w) {
                    w.document.open();
                    w.document.write(`<html><head><title>Livestock Record</title></head><body>${container.innerHTML}</body></html>`);
                    w.document.close();
                    try { w.focus(); } catch(_){}
                    try { w.print(); } catch(_){}
                    try { w.close(); } catch(_){}
                } else {
                    window.print();
                }
            }
        });
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

// Ensure pagination and info are left-aligned; guard errors
function forcePaginationLeft() {
    try {
        const wrapper = $('#livestockTable').closest('.dataTables_wrapper');
        if (!wrapper.length) return;
        wrapper.find('.dataTables_length').hide();
        wrapper.find('.dataTables_filter').hide();
        wrapper.find('.dataTables_paginate').css({ 'float': 'left', 'text-align': 'left' });
        wrapper.find('.dataTables_info').css({ 'float': 'left', 'text-align': 'left', 'margin-left': '10px' });
    } catch (e) {}
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
                    // attempt to derive type from notes if not provided
                    let pType = record.production_type || '';
                    if (!pType && record.notes) {
                        const m = record.notes.match(/\[type:\s*([^\]]+)\]/i);
                        if (m) pType = m[1];
                    }
                    // strip type tag from notes display
                    let noteText = record.notes || '';
                    noteText = noteText.replace(/\[type:\s*[^\]]+\]\s*/i, '').trim();
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${new Date(record.production_date).toLocaleDateString()}</td>
                        <td>${pType || 'Milk'}</td>
                        <td>${record.quantity || 'N/A'}</td>
                        <td>${record.quality || 'N/A'}</td>
                        <td style="text-align: left !important; white-space: normal; word-break: break-word;">${noteText || 'N/A'}</td>
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

function loadHealthRecords(livestockId) {
    $.ajax({
        url: `/farmer/livestock/${livestockId}/health-records`,
        method: 'GET',
        success: function(response) {
            if (response.success && response.data && response.data.length > 0) {
                const tbody = document.getElementById('healthRecordsTable');
                tbody.innerHTML = '';
                response.data.forEach(r => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${r.date ? new Date(r.date).toLocaleDateString() : 'N/A'}</td>
                        <td>${r.status || 'N/A'}</td>
                        <td>${r.treatment || 'N/A'}</td>
                        <td>${r.veterinarian || 'N/A'}</td>
                        <td style="text-align: left !important; white-space: normal; word-break: break-word;">${r.notes || 'N/A'}</td>
                    `;
                    tbody.appendChild(row);
                });
            }
        }
    });
}

function loadBreedingRecords(livestockId) {
    $.ajax({
        url: `/farmer/livestock/${livestockId}/breeding-records`,
        method: 'GET',
        success: function(response) {
            if (response.success && response.data && response.data.length > 0) {
                const tbody = document.getElementById('breedingRecordsTable');
                if (tbody) {
                    tbody.innerHTML = '';
                    response.data.forEach(r => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td style="white-space: nowrap;">${r.date ? new Date(r.date).toLocaleDateString() : 'N/A'}</td>
                            <td style="white-space: nowrap;">${r.type || 'N/A'}</td>
                            <td style="white-space: nowrap;">${r.partner || 'N/A'}</td>
                            <td style="white-space: nowrap;">${r.pregnancy || 'N/A'}</td>
                            <td style="white-space: nowrap;">${r.success || 'N/A'}</td>
                            <td style="white-space: normal; min-width: 320px; word-break: break-word;">${r.notes || 'N/A'}</td>
                        `;
                        tbody.appendChild(row);
                    });
                }
            }
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
        <!-- Add Production Record Modal -->
<div class="modal fade" id="productionRecordModal" tabindex="-1" role="dialog" aria-labelledby="productionRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content smart-form text-center p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-chart-line fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1" id="productionRecordModalLabel">Add Production Record</h5>
                <p class="text-muted mb-0 small">
                    Enter the production details below to record livestock output.
                </p>
            </div>

            <!-- Form -->
            <form id="productionRecordForm" class="text-start mx-auto">
                <div class="form-wrapper">
                    <div class="row g-3">
                        <!-- Production Date -->
                        <div class="col-md-6 ">
                            <label for="production_date" class="fw-semibold">Production Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control mt-1" id="production_date" name="production_date" required>
                        </div>

                        <!-- Production Type -->
                        <div class="col-md-6 ">
                            <label for="production_type" class="fw-semibold">Production Type <span class="text-danger">*</span></label>
                            <select class="form-control mt-1" id="production_type" name="production_type" required>
                                <option value="">Select Type</option>
                                <option value="milk">Milk</option>
                                <option value="eggs">Eggs</option>
                                <option value="meat">Meat</option>
                                <option value="wool">Wool</option>
                            </select>
                        </div>

                        <!-- Quantity -->
                        <div class="col-md-6 ">
                            <label for="quantity" class="fw-semibold">Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control mt-1" id="quantity" name="quantity" min="0" step="0.1" required>
                        </div>

                        <!-- Quality -->
                        <div class="col-md-6 ">
                            <label for="quality" class="fw-semibold">Quality</label>
                            <select class="form-control mt-1" id="quality" name="quality">
                                <option value="">Select Quality</option>
                                <option value="excellent">Excellent</option>
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="col-12 mb-3">
                            <label for="notes" class="fw-semibold">Notes</label>
                            <textarea class="form-control mt-1" id="notes" name="notes" rows="3" style="resize: none;"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-modern btn-ok" onclick="saveProductionRecord('${livestockId}')">
                        <i class="fas fa-save"></i> Save Record
                    </button>
                </div>
            </form>
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
    livestockId = livestockId || currentLivestockId;
    if (!livestockId) { showNotification('Missing livestock ID. Please reopen details and try again.', 'danger'); return; }
    const form = document.getElementById('productionRecordForm');
    const formData = new FormData(form);
    
    // Map fields to backend expectations
    const qualityMap = { excellent: 10, good: 8, fair: 6, poor: 4 };
    const quality = formData.get('quality');
    const qty = formData.get('quantity');
    if (qty !== null) formData.append('milk_quantity', qty);
    if (quality) formData.append('milk_quality_score', qualityMap[quality] || '');
    // Include production type as a tag in notes so we can display it later
    const pType = formData.get('production_type');
    const userNotes = (formData.get('notes') || '').toString().trim();
    const combinedNotes = `[type: ${pType || 'milk'}] ${userNotes}`.trim();
    formData.set('notes', combinedNotes);
    
    formData.append('livestock_id', livestockId);
    
    $.ajax({
        url: '/farmer/production',
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
        <!-- Add Health Record Modal -->
<div class="modal fade" id="healthRecordModal" tabindex="-1" role="dialog" aria-labelledby="healthRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content smart-form text-center p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-heartbeat fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1" id="healthRecordModalLabel">Add Health Record</h5>
                <p class="text-muted mb-0 small">
                    Provide the details below to record the livestock’s health status.
                </p>
            </div>

            <!-- Form -->
            <form id="healthRecordForm" class="text-start mx-auto">
                <div class="form-wrapper">
                    <div class="row g-3">
                        <!-- Health Date -->
                        <div class="col-md-6">
                            <label for="health_date" class="fw-semibold">Health Check Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control mt-1" id="health_date" name="health_date" required>
                        </div>

                        <!-- Health Status -->
                        <div class="col-md-6">
                            <label for="health_status" class="fw-semibold">Health Status <span class="text-danger">*</span></label>
                            <select class="form-control mt-1" id="health_status" name="health_status" required>
                                <option value="">Select Status</option>
                                <option value="healthy">Healthy</option>
                                <option value="sick">Sick</option>
                                <option value="recovering">Recovering</option>
                                <option value="under_treatment">Under Treatment</option>
                            </select>
                        </div>

                        <!-- Weight -->
                        <div class="col-md-6 ">
                            <label for="health_weight" class="fw-semibold">Weight (kg)</label>
                            <input type="number" class="form-control mt-1" id="health_weight" name="weight" min="0" step="0.1">
                        </div>

                        <!-- Temperature -->
                        <div class=" col-md-6 ">
                            <label for="temperature" class="fw-semibold">Temperature (°C)</label>
                            <input type="number" class="form-control mt-1" id="temperature" name="temperature" min="0" step="0.1">
                        </div>

                        <!-- Symptoms / Observations -->
                        <div class="col-12 mb-3">
                            <label for="symptoms" class="fw-semibold">Symptoms / Observations</label>
                            <textarea class="form-control mt-1" id="symptoms" name="symptoms" rows="3" style="resize: none;"></textarea>
                        </div>

                        <!-- Treatment -->
                        <div class="col-12 mb-3">
                            <label for="treatment" class="fw-semibold">Treatment Given</label>
                            <textarea class="form-control mt-1" id="treatment" name="treatment" rows="3" style="resize: none;"></textarea>
                        </div>

                        <!-- Veterinarian -->
                        <div class="col-lg-6 col-md-6 mb-3">
                            <label for="veterinarian_id" class="fw-semibold">Veterinarian</label>
                            <select class="form-control mt-1" id="veterinarian_id" name="veterinarian_id">
                                <option value="">Select Veterinarian</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-modern btn-ok" onclick="saveHealthRecord('${livestockId}')">
                        <i class="fas fa-save"></i> Save Record
                    </button>
                </div>
            </form>
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

    // Populate veterinarian dropdown
    $.ajax({
        url: '/farmer/admins',
        method: 'GET',
        success: function(res) {
            if (res && res.success && Array.isArray(res.data)) {
                const sel = document.querySelector('#healthRecordModal #veterinarian_id');
                if (sel) {
                    if (res.data.length === 0) {
                        const opt = document.createElement('option');
                        opt.value = '';
                        opt.textContent = 'No admins found';
                        sel.appendChild(opt);
                    } else {
                        res.data.forEach(a => {
                            const opt = document.createElement('option');
                            opt.value = a.id;
                            opt.textContent = a.name;
                            sel.appendChild(opt);
                        });
                    }
                }
            }
        },
        error: function() {
            const sel = document.querySelector('#healthRecordModal #veterinarian_id');
            if (sel && sel.children.length === 1) { // only the default option
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'Unable to load admins';
                sel.appendChild(opt);
            }
        }
    });
}

function saveHealthRecord(livestockId) {
    livestockId = livestockId || currentLivestockId;
    if (!livestockId) { showNotification('Missing livestock ID. Please reopen details and try again.', 'danger'); return; }
    const form = document.getElementById('healthRecordForm');
    const formData = new FormData(form);
    
    // Add livestock ID to form data
    formData.append('livestock_id', livestockId);
    
    $.ajax({
        url: `/farmer/livestock/${livestockId}/health`,
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
                // Refresh health records table if details modal is open
                if ($('#livestockDetailsModal').hasClass('show')) {
                    loadHealthRecords(livestockId);
                }
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
        <!-- Add Breeding Record Modal -->
<div class="modal fade" id="breedingRecordModal" tabindex="-1" role="dialog" aria-labelledby="breedingRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content smart-form text-center p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-heart fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1" id="breedingRecordModalLabel">Add Breeding Record</h5>
                <p class="text-muted mb-0 small">
                    Provide the breeding details below to update the livestock’s reproductive record.
                </p>
            </div>

            <!-- Form -->
            <form id="breedingRecordForm" class="text-start mx-auto">
                <div class="form-wrapper">
                    <div class="row g-3">
                        <!-- Breeding Date -->
                        <div class="col-md-6">
                            <label for="breeding_date" class="fw-semibold">Breeding Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control mt-1" id="breeding_date" name="breeding_date" required>
                        </div>

                        <!-- Breeding Type -->
                        <div class="col-md-6">
                            <label for="breeding_type" class="fw-semibold">Breeding Type <span class="text-danger">*</span></label>
                            <select class="form-control mt-1" id="breeding_type" name="breeding_type" required>
                                <option value="">Select Type</option>
                                <option value="natural">Natural Breeding</option>
                                <option value="artificial">Artificial Insemination</option>
                                <option value="embryo_transfer">Embryo Transfer</option>
                            </select>
                        </div>

                        <!-- Partner Livestock ID -->
                        <div class="col-md-6 ">
                            <label for="partner_livestock_id" class="fw-semibold">Partner Livestock ID</label>
                            <input type="text" class="form-control mt-1" id="partner_livestock_id" name="partner_livestock_id">
                        </div>

                        <!-- Expected Birth Date -->
                        <div class="col-md-6 ">
                            <label for="expected_birth_date" class="fw-semibold">Expected Birth Date</label>
                            <input type="date" class="form-control mt-1" id="expected_birth_date" name="expected_birth_date">
                        </div>

                        <!-- Pregnancy Status -->
                        <div class="col-md-6">
                            <label for="pregnancy_status" class="fw-semibold">Pregnancy Status</label>
                            <select class="form-control mt-1" id="pregnancy_status" name="pregnancy_status">
                                <option value="">Select Status</option>
                                <option value="unknown">Unknown</option>
                                <option value="pregnant">Pregnant</option>
                                <option value="not_pregnant">Not Pregnant</option>
                            </select>
                        </div>

                        <!-- Breeding Success -->
                        <div class="col-md-6">
                            <label for="breeding_success" class="fw-semibold">Breeding Success</label>
                            <select class="form-control mt-1" id="breeding_success" name="breeding_success">
                                <option value="">Select Result</option>
                                <option value="unknown">Unknown</option>
                                <option value="successful">Successful</option>
                                <option value="unsuccessful">Unsuccessful</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="col-12 mb-3">
                            <label for="notes" class="fw-semibold">Notes</label>
                            <textarea class="form-control mt-1" id="notes" name="notes" rows="3" style="resize: none;"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-modern btn-ok" onclick="saveBreedingRecord('${livestockId}')">
                        <i class="fas fa-save"></i> Save Record
                    </button>
                </div>
            </form>
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
    livestockId = livestockId || currentLivestockId;
    if (!livestockId) { showNotification('Missing livestock ID. Please reopen details and try again.', 'danger'); return; }
    const form = document.getElementById('breedingRecordForm');
    const formData = new FormData(form);
    
    // Add livestock ID to form data
    formData.append('livestock_id', livestockId);
    
    $.ajax({
        url: `/farmer/livestock/${livestockId}/breeding`,
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
                if ($('#livestockDetailsModal').hasClass('show')) {
                    loadBreedingRecords(livestockId);
                }
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
        const tableId = 'livestockTable';
        const tableEl = document.getElementById(tableId);
        const dt = ($.fn.DataTable && $.fn.DataTable.isDataTable('#' + tableId)) ? $('#' + tableId).DataTable() : null;

        const headerTexts = [];
        if (tableEl) {
            const ths = tableEl.querySelectorAll('thead th');
            ths.forEach((th, idx) => { if (idx < ths.length - 1) headerTexts.push((th.innerText || '').trim()); });
        }

        const rows = [];
        if (dt) {
            dt.data().toArray().forEach(row => {
                const cleaned = [];
                for (let i = 0; i < row.length - 1; i++) {
                    const div = document.createElement('div');
                    div.innerHTML = row[i];
                    cleaned.push((div.textContent || div.innerText || '').replace(/\s+/g, ' ').trim());
                }
                rows.push(cleaned);
            });
        } else if (tableEl) {
            tableEl.querySelectorAll('tbody tr').forEach(tr => {
                const tds = tr.querySelectorAll('td');
                if (tds.length) {
                    const cleaned = [];
                    for (let i = 0; i < tds.length - 1; i++) cleaned.push((tds[i].innerText || '').replace(/\s+/g, ' ').trim());
                    rows.push(cleaned);
                }
            });
        }

        if (!rows.length) { if (typeof showNotification === 'function') showNotification('No data available to print', 'warning'); return; }

        let printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #18375d; margin-bottom: 5px;">Livestock Inventory Report</h1>
                    <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                <table border="3" style="border-collapse: collapse; width: 100%; border: 3px solid #000;">
                    <thead>
                        <tr>`;
        headerTexts.forEach(h => { printContent += `<th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">${h}</th>`; });
        printContent += `</tr>
                    </thead>
                    <tbody>`;
        rows.forEach(r => {
            printContent += '<tr>';
            r.forEach(cell => { printContent += `<td style=\"border: 3px solid #000; padding: 10px; text-align: left;\">${cell}</td>`; });
            printContent += '</tr>';
        });
        printContent += `
                    </tbody>
                </table>
            </div>`;

        if (typeof window.printElement === 'function') {
            const container = document.createElement('div'); container.innerHTML = printContent; window.printElement(container);
        } else if (typeof window.openPrintWindow === 'function') {
            window.openPrintWindow(printContent, 'Livestock Inventory Report');
        } else {
            const w = window.open('', '_blank');
            if (w) { w.document.open(); w.document.write(`<html><head><title>Print</title></head><body>${printContent}</body></html>`); w.document.close(); w.focus(); w.print(); w.close(); }
            else { window.print(); }
        }
    } catch (error) {
        console.error('Print error:', error);
        try { $('#' + 'livestockTable').DataTable().button('.buttons-print').trigger(); } catch (_) {}
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
    /* ============================
   FORM ELEMENT STYLES
   ============================ */
#breedingRecordModal form {
  text-align: left;
}

#breedingRecordModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#breedingRecordModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#breedingRecordModal .form-control,
#breedingRecordModal select.form-control,
#breedingRecordModal textarea.form-control {
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
#breedingRecordModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#breedingRecordModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}


#healthRecordModal form {
  text-align: left;
}

#healthRecordModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#healthRecordModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#healthRecordModal .form-control,
#healthRecordModal select.form-control,
#healthRecordModal textarea.form-control {
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
#healthRecordModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#healthRecordModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}
/* ============================
   FORM ELEMENT STYLES
   ============================ */
#productionRecordModal form {
  text-align: left;
}

#productionRecordModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#productionRecordModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#productionRecordModal .form-control,
#productionRecordModal select.form-control,
#productionRecordModal textarea.form-control {
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
#productionRecordModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#productionRecordModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}
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

  /* Ensure breeding Notes column is left-aligned regardless of global table centering */
  #breedingForm table thead th:nth-child(6),
  #breedingForm table tbody td:nth-child(6) {
    text-align: left !important;
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
    
   /* ===== Edit Button ===== */
.btn-action-ok {
    background-color: white;
    border: 1px solid #18375d !important;
    color: #18375d; /* blue text */
}

.btn-action-ok:hover {
    background-color: #18375d; /* yellow on hover */
    border: 1px solid #18375d !important;
    color: white;
}

.btn-action-deletes {
    background-color: white !important;
    border: 1px solid #dc3545 !important;
    color: #dc3545 !important; /* blue text */
}

.btn-action-deletes:hover {
    background-color: #dc3545 !important; /* yellow on hover */
    border: 1px solid #dc3545 !important;
    color: white !important;
}

.btn-action-refresh {
    background-color: white !important;
    border: 1px solid #fca700 !important;
    color: #fca700 !important; /* blue text */
}
    
.btn-action-refresh:hover {
    background-color: #fca700 !important; /* yellow on hover */
    border: 1px solid #fca700 !important;
    color: white !important;
}

.btn-action-tools {
    background-color: white !important;
    border: 1px solid #495057 !important;
    color: #495057 !important;
}

.btn-action-tools:hover {
    background-color: #495057 !important; /* yellow on hover */
    border: 1px solid #495057 !important;
    color: white !important;
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

/* DataTables Pagination Styling */
.dataTables_wrapper .dataTables_paginate {
    text-align: left !important;
    margin-top: 1rem;
    margin-bottom: 0.75rem !important; /* Match farmers directory gap */
    clear: both;
    width: 100%;
    float: left !important;
}

.dataTables_wrapper .dataTables_paginate .pagination {
    justify-content: flex-start !important;
    margin: 0;
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

