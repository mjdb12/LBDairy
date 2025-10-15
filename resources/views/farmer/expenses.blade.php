@extends('layouts.app')

@section('title', 'Expenses Management')

@section('content')
    <!-- Page Header -->
    <div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
        <h1>
            <i class="fas fa-money-bill-wave"></i>
            Expenses Management
        </h1>
        <p>Track and manage all farm expenses efficiently</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold  text-uppercase mb-1">
                                Total Expenses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($totalExpenses, 2) }}</div>
                            <div class="text-xs ">
                                <i class="fas fa-arrow-up"></i>
                                {{ $expenseChange }}% from last month
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold  text-uppercase mb-1">
                                Feed Expenses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($feedExpenses, 2) }}</div>
                            <div class="text-xs ">
                                <i class="fas fa-arrow-down"></i>
                                {{ $feedChange }}% from last month
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-seedling fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold  text-uppercase mb-1">
                                Veterinary Expenses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($veterinaryExpenses, 2) }}</div>
                            <div class="text-xs ">
                                <i class="fas fa-arrow-up"></i>
                                {{ $veterinaryChange }}% from last month
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-stethoscope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Maintenance Expenses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($maintenanceExpenses, 2) }}</div>
                            <div class="text-xs ">
                                <i class="fas fa-arrow-up"></i>
                                {{ $maintenanceChange }}% from last month
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expense Charts -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
                    <h6 class="m-0 font-weight-bold ">Monthly Expense Trend</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="expenseTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Budget Alert -->
    @if($budgetExceeded)
    <div class="alert alert-danger alert-dismissible fade show refresh-notification" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Budget Alert!</strong> Your expenses have exceeded your monthly budget by ₱{{ number_format($budgetExcess, 2) }}.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Main Content -->
            <div class="card shadow fade-in">
                <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2 text-center text-sm-start">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-list"></i>
                        Expenses List
                    </h6>
                </div>
                <div class="card-body"><div class="search-controls mb-3">
                <div class="input-group" style="max-width: 300px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <input type="text" id="expensesSearch" class="form-control" placeholder="Search expenses...">
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-center">
                    <button class="btn-action btn-action-ok" id="supplierSearch" title="Add Expenses" onclick="openAddExpenseModal()">
                        <i class="fas fa-plus"></i> Add Expense
                    </button>
                    <button class="btn-action btn-action-edit" title="Print" onclick="printExpenses()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button class="btn-action btn-action-refresh" title="Refresh" onclick="refreshExpenses()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <button class="btn-action btn-action-history" data-toggle="modal" data-target="#historyModal">
                        <i class="fas fa-history"></i> History
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" title="Tools" type="button" data-toggle="dropdown">
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
                    <div class="table-responsive">
                        <table class="table table-bordered" id="expensesTable" width="100%" cellspacing="0" >
                            <thead class="thead-light">
                                <tr>
                                    <th>Expense ID</th>
                                    <th>Date</th>
                                    <th>Expense Name</th>
                                    <th>Amount</th>
                                    <th>Payment Status</th>
                                    <th>Payment Method</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="salesTableBody">
                                @forelse($expensesData as $expense)
                                <tr data-expense-id="{{ $expense['id'] }}">
                                    <td>
                                        <span class="badge badge-primary">{{ $expense['expense_id'] }}</span>
                                    </td>
                                    <td>{{ $expense['expense_date'] }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-receipt  mr-2"></i>
                                            {{ $expense['expense_name'] }}
                                        </div>
                                    </td>
                                    <td><strong>₱{{ number_format($expense['amount'], 2) }}</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $expense['payment_status'] === 'Paid' ? 'success' : 'warning' }}">
                                            {{ $expense['payment_status'] }}
                                        </span>
                                    </td>
                                    <td>{{ $expense['payment_method'] }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn-action btn-action-ok" onclick="viewExpenseDetails('{{ $expense['id'] }}')" title="View Details">
                                                <i class="fas fa-eye"></i>
                                                <span>View</span>
                                            </button>
                                            <button class="btn-action btn-action-edits" onclick="openEditExpenseModal('{{ $expense['id'] }}')" title="Edit Expense">
                                                <i class="fas fa-edit"></i>
                                                <span>Edit</span>
                                            </button>
                                            <button class="btn-action btn-action-deletes" onclick="confirmDelete('{{ $expense['id'] }}')" title="Delete Expense">
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
                                    <td class="text-center text-muted">No expenses recorded yet</td>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">N/A</td>
                                    <td class="text-center text-muted">N/A</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

<!-- ADD EXPENSE MODAL -->
<div class="modal fade admin-modal" id="expenseModal" tabindex="-1" role="dialog" aria-labelledby="expenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content smart-form text-center p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle  mb-3">
                    <i class="fas fa-wallet fa-2x"></i>
                </div>
                <h5 id="expenseModalLabel" class="fw-bold mb-1">Add New Expense</h5>
                <p class="text-muted mb-0 small">
                    Fill out the details below to record a new farm expense.
                </p>
            </div>

            <!-- Form -->
            <form id="expenseForm" method="POST" action="{{ route('farmer.expenses.store') }}">
                @csrf
                <div class="form-wrapper text-start mx-auto">
                    <div class="row">
                        <!-- Farm -->
                        <div class="col-md-6">
                            <label for="farm_id" class="fw-semibold">Select Farm <span class="text-danger">*</span></label>
                            <select class="form-control" id="farm_id" name="farm_id" required>
                                <option value="" disabled selected>Select Farm</option>
                                @foreach($farms ?? [] as $farm)
                                    <option value="{{ $farm->id }}">{{ $farm->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Expense Date -->
                        <div class="col-md-6">
                            <label for="expense_date" class="fw-semibold">Expense Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="expense_date" name="expense_date" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- Description -->
                        <div class="col-md-6">
                            <label for="description" class="fw-semibold">Expense Description <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="description" name="description" required placeholder="e.g., Feed Purchase, Veterinary Services">
                        </div>

                        <!-- Category -->
                        <div class="col-md-6">
                            <label for="expense_type" class="fw-semibold">Category <span class="text-danger">*</span></label>
                            <select class="form-control" id="expense_type" name="expense_type" required>
                                <option value="">Select Category</option>
                                <option value="feed">Feed</option>
                                <option value="medicine">Medicine/Veterinary</option>
                                <option value="equipment">Equipment/Maintenance</option>
                                <option value="labor">Labor</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Amount -->
                        <div class="col-md-6">
                            <label for="amount" class="fw-semibold">Amount (₱) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="amount" name="amount" min="0" step="0.01" required placeholder="0.00">
                        </div>

                        <!-- Payment Method -->
                        <div class="col-md-6">
                            <label for="payment_method" class="fw-semibold">Payment Method</label>
                            <select class="form-control" id="payment_method" name="payment_method">
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="check">Check</option>
                                <option value="credit">Credit</option>
                            </select>
                        </div>

                        <!-- Receipt Number -->
                        <div class="col-md-6">
                            <label for="receipt_number" class="fw-semibold">Receipt Number</label>
                            <input type="text" class="form-control" id="receipt_number" name="receipt_number" placeholder="Optional receipt number">
                        </div>

                        <!-- Notes -->
                        <div class="col-md-6">
                            <label for="notes" class="fw-semibold">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Additional notes about this expense..."></textarea>
                        </div>

                        <div id="formNotification" class="mt-2" style="display: none;"></div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="modal-footer d-flex gap-2 justify-content-center flex-wrap mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-ok" title="Save Expense">
                        Save Expense
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- EXPENSE DETAILS MODAL (Smart Detail) -->
<div class="modal fade admin-modal" id="expenseDetailsModal" tabindex="-1" role="dialog" aria-labelledby="expenseDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-receipt fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1" id="expenseDetailsLabel">Expense Details</h5>
                <p class="text-muted mb-0 small text-center">
                    Review detailed information about this recorded farm expense.
                </p>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div class="detail-wrapper text-start mx-auto" id="expenseDetailsContent">
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer justify-content-center mt-4">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
                <button type="button" class="btn-modern btn-ok" onclick="editCurrentExpense()">
                    <i class="fas fa-edit"></i> Edit Expense
                </button>
            </div>
        </div>
    </div>
</div>


<!-- EXPENSES HISTORY MODAL (Smart Detail) -->
<div class="modal fade admin-modal" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-history fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1" id="historyModalLabel">Expenses History (Quarterly)</h5>
                <p class="text-muted mb-0 small text-center">
                    View and export quarterly expenses data by year.
                </p>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div class="form-wrapper text-start mx-auto">
                    <div class="row g-3 mb-3 align-items-end">
                        <div class="col-md-6">
                            <label for="expensesYear" class="fw-semibold">Select Year:</label>
                            <select id="expensesYear" class="form-control" onchange="loadExpensesHistory()">
                                @php($currentYear = (int)date('Y'))
                                @for($y = $currentYear; $y >= $currentYear - 5; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6 text-md-end text-muted small">
                            Showing quarterly aggregates
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive rounded shadow-sm">
                        <table class="table table-hover table-bordered align-middle mb-0" id="expensesHistoryQuarterTable">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Quarter</th>
                                    <th>Total Expenses (₱)</th>
                                    <th>Records</th>
                                </tr>
                            </thead>
                            <tbody id="expensesHistoryTableBody">
                                <!-- Quarterly expense history will be dynamically populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer justify-content-center mt-4">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
                <button type="button" class="btn-modern btn-ok" onclick="exportExpensesHistory()">
                    Export History
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Delete Confirmation Modal -->
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
                Are you sure you want to delete this entry? This action <strong>cannot be undone</strong>.
            </p>

            <!-- Buttons -->
            <div class="modal-footer d-flex gap-2 justify-content-center flex-wrap">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn-modern btn-delete" id="confirmDeleteBtn">Yes, Delete</button>
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

<!-- Required libraries for PDF/Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<!-- jsPDF and autoTable for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let currentExpenseId = null;
let expensesDT = null;
const CSRF_TOKEN = "{{ csrf_token() }}";

$(document).ready(function() {
    // Initialize DataTable for Expenses
    const commonConfig = {
        dom: 'Bfrtip',
        searching: true,
        paging: true,
        info: true,
        ordering: true,
        lengthChange: false,
        pageLength: 10,
        autoWidth: false,
        scrollX: true,
        buttons: [
            { extend: 'csvHtml5', title: 'Farmer_Expenses_Report', className: 'd-none', exportOptions: { columns: [0,1,2,3,4,5], modifier: { page: 'all' } } },
            { extend: 'pdfHtml5', title: 'Farmer_Expenses_Report', orientation: 'landscape', pageSize: 'Letter', className: 'd-none', exportOptions: { columns: [0,1,2,3,4,5], modifier: { page: 'all' } } },
            { extend: 'print', title: 'Farmer Expenses Report', className: 'd-none', exportOptions: { columns: [0,1,2,3,4,5], modifier: { page: 'all' } } }
        ],
        language: { search: "", emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No data available</h5><p>There are no records to display at this time.</p></div>' }
    };

    if ($('#expensesTable').length) {
        try {
            expensesDT = $('#expensesTable').DataTable({
                ...commonConfig,
                order: [[1, 'desc']],
                columnDefs: [
                    { width: '140px', targets: 0 },
                    { width: '140px', targets: 1 },
                    { width: '260px', targets: 2 },
                    { width: '140px', targets: 3 },
                    { width: '160px', targets: 4 },
                    { width: '160px', targets: 5 },
                    { width: '200px', targets: 6, orderable: false }
                ]
            });

// Helpers
function htmlEscape(s){
    return (s==null? '': String(s)).replace(/[&<>"']/g, function(c){
        return {"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#39;"}[c];
    });
}

function truncateText(s, n){
    s = s || '';
    return s.length > n ? s.slice(0, n-1) + '…' : s;
}

function getExpenseRowById(id){
    return document.querySelector(`#expensesTable tbody tr[data-expense-id="${id}"]`);
}

function buildExpenseRowCells(exp){
    const idBadge = `<span class="badge badge-primary">${htmlEscape(exp.expense_id || ('EXP'+String(exp.id).padStart(3,'0')))}</span>`;
    const date = htmlEscape(exp.expense_date || '');
    const name = `<div class="d-flex align-items-center"><i class="fas fa-receipt text-primary mr-2"></i>${htmlEscape(exp.description || exp.expense_name || '')}</div>`;
    const amount = `<strong>₱${Number(exp.amount||0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</strong>`;
    const status = `<span class="badge badge-${(exp.payment_status||'Paid')==='Paid'?'success':'warning'}">${htmlEscape(exp.payment_status || 'Paid')}</span>`;
    const method = htmlEscape(exp.payment_method || 'Cash');
    const actions = `
        <div class="btn-group">
            <button class="btn-action btn-action-ok" onclick="viewExpenseDetails('${exp.id}')" title="View Details"><i class="fas fa-eye"></i><span>View</span></button>
            <button class="btn-action btn-action-edits" onclick="openEditExpenseModal('${exp.id}')" title="Edit Expense"><i class="fas fa-edit"></i><span>Edit</span></button>
            <button class="btn-action btn-action-deletes" onclick="confirmDelete('${exp.id}')" title="Delete Expense"><i class="fas fa-trash"></i><span>Delete</span></button>
        </div>`;
    return [idBadge, date, name, amount, status, method, actions];
}

function upsertExpenseRow(exp){
    try {
        const cells = buildExpenseRowCells(exp);
        const tr = getExpenseRowById(exp.id);
        if (expensesDT){
            if (tr){ expensesDT.row(tr).data(cells).draw(false); }
            else {
                const node = expensesDT.row.add(cells).draw(false).node();
                if (node) node.setAttribute('data-expense-id', exp.id);
            }
        }
    } catch(e){ console.error('upsertExpenseRow error:', e); }
}

function fetchExpenseAndUpsert(id){
    return $.ajax({ url: `/farmer/expenses/${id}`, method: 'GET', dataType: 'json' })
        .done(function(resp){ if (resp && resp.success && resp.expense) upsertExpenseRow(resp.expense); });
}
        } catch (e) { console.error('Failed to initialize Expenses DataTable:', e); }
    }

    // Hide default DataTables search and buttons; wire custom search
    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();
    $('#expensesSearch').on('keyup', function(){
        if (expensesDT) expensesDT.search(this.value).draw();
    });

    // Load quarterly history when modal opens
    $('#historyModal').on('shown.bs.modal', function(){
        try { loadExpensesHistory(); } catch(e){ console.error('loadExpensesHistory error:', e); }
    });

    // Handle form submission (AJAX)
    $('#expenseForm').on('submit', function(e) {
        e.preventDefault();
        submitExpenseForm();
    });

    // Refresh notification after reload
    if (sessionStorage.getItem('showRefreshNotificationExpenses') === 'true'){
        sessionStorage.removeItem('showRefreshNotificationExpenses');
        setTimeout(()=>showToast('Data refreshed successfully!', 'success'), 400);
    }

    // Expense Trend Chart
    const expenseTrendCtx = document.getElementById('expenseTrendChart').getContext('2d');
    new Chart(expenseTrendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(($expenseStats['monthly_trend'] ?? collect())->pluck('month')) !!},
            datasets: [{
                label: 'Expenses (₱)',
                data: {!! json_encode(($expenseStats['monthly_trend'] ?? collect())->pluck('expenses')) !!},
                borderColor: '#e74a3b',
                backgroundColor: 'rgba(231, 74, 59, 0.1)',
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
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });


});

function openAddExpenseModal() {
    $('#expenseModalLabel').html('<i class="fas fa-plus"></i> Add New Expense');
    $('#expenseForm')[0].reset();
    $('#expenseForm').attr('action', '{{ route("farmer.expenses.store") }}');
    $('#expenseForm').attr('method', 'POST');
    // Remove _method if present
    const methodInput = $('#expenseForm').find('input[name="_method"]');
    if (methodInput.length) methodInput.remove();
    $('#expense_date').val('{{ date("Y-m-d") }}');
    $('#expenseModal').modal('show');
}

function openEditExpenseModal(expenseId) {
    currentExpenseId = expenseId;
    $('#expenseModalLabel').html('<i class="fas fa-edit"></i> Edit Expense');
    $('#expenseForm').attr('action', `/farmer/expenses/${expenseId}`);
    $('#expenseForm').attr('method', 'POST');
    if (!$('#expenseForm').find('input[name="_method"]').length) {
        $('#expenseForm').prepend('<input type="hidden" name="_method" value="PUT">');
    } else {
        $('#expenseForm').find('input[name="_method"]').val('PUT');
    }
    
    // Load expense data
    loadExpenseData(expenseId);
    $('#expenseModal').modal('show');
}

function viewExpenseDetails(expenseId) {
    currentExpenseId = expenseId;
    loadExpenseDetails(expenseId);
    $('#expenseDetailsModal').modal('show');
}

function loadExpenseData(expenseId) {
    $.ajax({
        url: `/farmer/expenses/${expenseId}`,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            const expense = response && response.success ? response.expense : response;
            if (expense) {
                $('#expense_date').val(expense.expense_date || '');
                $('#description').val(expense.description || '');
                $('#expense_type').val(expense.expense_type || '').trigger('change');
                $('#amount').val(expense.amount || '');
                $('#payment_method').val(expense.payment_method || '').trigger('change');
                $('#receipt_number').val(expense.receipt_number || '');
                $('#notes').val(expense.notes || '');
                if (expense.farm_id) $('#farm_id').val(expense.farm_id).trigger('change');
            }
        },
        error: function() {
            showToast('Error loading expense data', 'error');
        }
    });
}

function loadExpenseDetails(expenseId) {
    $.ajax({
        url: `/farmer/expenses/${expenseId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const expense = response.expense;
                $('#expenseDetailsContent').html(`
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Expense Information</h6>
                            <p><strong>Date:</strong> ${expense.expense_date}</p>
                            <p><strong>Category:</strong> ${expense.expense_type}</p>
                            <p><strong>Description:</strong> ${expense.description}</p>
                            <p><strong>Amount:</strong> ₱${expense.amount}</p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d; font-weight: 600;">Additional Details</h6>
                            <p><strong>Farm:</strong> ${expense.farm_name}</p>
                            <p><strong>Payment Method:</strong> ${expense.payment_method}</p>
                            <p><strong>Receipt No.:</strong> ${expense.receipt_number || 'N/A'}</p>
                            <p><strong>Notes:</strong> ${expense.notes || 'No notes available.'}</p>
                        </div>
                    </div>
                `);
            }
        },
        error: function() {
            showToast('Error loading expense details', 'error');
        }
    });
}

function submitExpenseForm() {
    const formEl = $('#expenseForm')[0];
    const formData = new FormData(formEl);
    const url = $('#expenseForm').attr('action');
    const method = $('#expenseForm').attr('method');
    const submitBtn = $('#expenseForm .btn-ok').get(0);
    if (submitBtn){ submitBtn.disabled = true; submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...'; }

    fetch(url, {
        method: method || 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        body: formData
    }).then(async (r)=>{
        let data = null; try { data = await r.json(); } catch(_){}
        if (!r.ok || !data){ throw new Error('Request failed'); }
        if (data.success){
            const id = (data.expense && data.expense.id) ? data.expense.id : null;
            if (id){
                // Fetch normalized expense then upsert row
                await fetchExpenseAndUpsert(id);
            }
            $('#expenseModal').modal('hide');
            showToast(data.message || 'Expense saved', 'success');
            formEl.reset();
            $('#expense_date').val('{{ date('Y-m-d') }}');
        } else {
            showToast(data.message || 'Error saving expense', 'error');
        }
    }).catch(async (err)=>{
        // Try parse validation errors
        console.error('submitExpenseForm error:', err);
        showToast('Error saving expense', 'error');
    }).finally(()=>{
        if (submitBtn){ submitBtn.disabled = false; submitBtn.innerHTML = 'Save Expense'; }
    });
}

function confirmDelete(expenseId) {
    currentExpenseId = expenseId;
    $('#confirmDeleteModal').modal('show');
}

$('#confirmDeleteBtn').on('click', function() {
    if (currentExpenseId) {
        deleteExpense(currentExpenseId);
    }
});

function deleteExpense(expenseId) {
    $.ajax({
        url: `/farmer/expenses/${expenseId}`,
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
    }).done(function(response){
        if (response && response.success){
            const tr = getExpenseRowById(expenseId);
            if (tr && expensesDT){ expensesDT.row(tr).remove().draw(false); }
            $('#confirmDeleteModal').modal('hide');
            showToast(response.message || 'Expense deleted successfully!', 'success');
        } else {
            showToast((response && response.message) || 'Error deleting expense', 'error');
        }
    }).fail(function(){
        showToast('Error deleting expense', 'error');
    });
}

function editCurrentExpense() {
    if (currentExpenseId) {
        $('#expenseDetailsModal').modal('hide');
        openEditExpenseModal(currentExpenseId);
    }
}

function loadExpensesHistory() {
    const year = document.getElementById('expensesYear').value;
    fetch(`/farmer/expenses/history?year=${year}`)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.mode === 'quarterly') {
                const tbody = document.getElementById('expensesHistoryTableBody');
                tbody.innerHTML = '';
                const quarters = Array.isArray(data.quarters) ? data.quarters : [];
                if (quarters.length) {
                    quarters.forEach(q => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>Q${q.quarter} ${q.year}</td>
                            <td>₱${Number(q.total_expenses).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            <td>${q.records}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                } else {
                    const tr = document.createElement('tr');
                    tr.innerHTML = '<td colspan="3" class="text-center text-muted">No quarterly data for the selected year.</td>';
                    tbody.appendChild(tr);
                }
            }
        })
        .catch(err => { console.error('expenses history error:', err); showToast('Failed to load history', 'error'); });
}

function exportCSV(){
    try { if (expensesDT) expensesDT.button('.buttons-csv').trigger(); else showToast('Table is not ready.', 'error'); }
    catch(e){ console.error('CSV export error:', e); showToast('Error generating CSV.', 'error'); }
}

function exportPDF() {
    try { if (expensesDT) expensesDT.button('.buttons-pdf').trigger(); else showToast('Table is not ready.', 'error'); }
    catch (error) { console.error('Error generating PDF:', error); showToast('Error generating PDF. Please try again.', 'error'); }
}

function exportPNG() {
    // Create a temporary table without the Actions column for export
    const originalTable = document.getElementById('expensesTable');
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
        link.download = `Farmer_ExpensesReport_${Date.now()}.png`;
        link.href = canvas.toDataURL("image/png");
        link.click();
        // Clean up - remove temporary container
        document.body.removeChild(offscreen);
        
        showToast('PNG exported successfully!', 'success');
    }).catch(error => {
        console.error('Error generating PNG:', error);
        // Clean up on error
        if (document.body.contains(offscreen)) {
            document.body.removeChild(offscreen);
        }
        showToast('Error generating PNG export', 'error');
    });
}

function printExpenses() {
    try { if (expensesDT) expensesDT.button('.buttons-print').trigger(); else window.print(); }
    catch(e){ console.error('printExpenses error:', e); window.print(); }
}

function refreshExpenses(){
    const btn = document.querySelector('.btn-action-refresh');
    if (btn){ btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...'; }
    sessionStorage.setItem('showRefreshNotificationExpenses','true');
    setTimeout(()=>location.reload(), 800);
}

function exportExpensesHistory() {
    try {
        const year = document.getElementById('expensesYear').value;
        const rows = [];
        rows.push(['Quarter','Total Expenses (PHP)','Records'].join(','));
        document.querySelectorAll('#expensesHistoryQuarterTable tbody tr').forEach(tr => {
            const cells = Array.from(tr.querySelectorAll('td')).map(td => (td.textContent||'').trim());
            if (cells.length === 3) rows.push(cells.join(','));
        });
        const blob = new Blob([rows.join('\n')], { type: 'text/csv;charset=utf-8;' });
        const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = `Expenses_Quarterly_${year}.csv`; a.click();
        showToast('Quarterly history exported successfully!', 'success');
    } catch(e) { console.error('exportExpensesHistory error:', e); showToast('Failed to export history.', 'error'); }
}

function getExpenseColor(category){
    const c = (category||'').toLowerCase();
    if (c.includes('feed')) return 'success';
    if (c.includes('medicine') || c.includes('veter')) return 'info';
    if (c.includes('equipment') || c.includes('maint')) return 'warning';
    if (c.includes('labor')) return 'primary';
    return 'secondary';
}

function showToast(message, type = 'info') {
    const toast = `
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} text-white">
                <strong class="me-auto">${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">${message}</div>
        </div>
    `;
    
    // Add toast to page and show it
    const toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
    toastContainer.innerHTML = toast;
    document.body.appendChild(toastContainer);
    
    const toastElement = toastContainer.querySelector('.toast');
    const bsToast = new bootstrap.Toast(toastElement);
    bsToast.show();
    
    // Remove toast after it's hidden
    toastElement.addEventListener('hidden.bs.toast', () => {
        document.body.removeChild(toastContainer);
    });
}
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<style>
    /* Make table cells wrap instead of forcing them all inline */
#expensesTable td, 
#expensesTable th {
    white-space: normal !important;  /* allow wrapping */
    vertical-align: middle;
}

/* Make sure action buttons don’t overflow */
#expensesTable td .btn-group {
    display: flex;
    flex-wrap: wrap; /* buttons wrap if not enough space */
    gap: 0.25rem;    /* small gap between buttons */
}

#expensesTable td .btn-action {
    flex: 1 1 auto; /* allow buttons to shrink/grow */
    min-width: 90px; /* prevent too tiny buttons */
    text-align: center;
}
    /* SMART DETAIL MODAL TEMPLATE */
.smart-detail .modal-content {
    border-radius: 1.5rem;
    border: none;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12);
    background-color: #fff;
    transition: all 0.3s ease-in-out;
}

/* Icon Header */
.smart-detail .icon-circle {
    width: 55px;
    height: 55px;
    background-color: #e8f0fe;
    color: #18375d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Titles & Paragraphs */
.smart-detail h5 {
    color: #18375d;
    font-weight: 700;
    margin-bottom: 0.4rem;
    letter-spacing: 0.5px;
}

.smart-detail p {
    color: #6b7280;
    font-size: 0.96rem;
    margin-bottom: 1.8rem;
    line-height: 1.5;
}

/* MODAL BODY */
.smart-detail .modal-body {
    background: #ffffff;
    padding: 1.75rem 2rem;
    border-radius: 1rem;
    max-height: 70vh; /* ensures content scrolls on smaller screens */
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

/* Detail Section */
.smart-detail .detail-wrapper {
    background: #f9fafb;
    border-radius: 1rem;
    padding: 1.5rem;
    font-size: 0.95rem;
}

.smart-detail .detail-row {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px dashed #ddd;
    padding: 0.5rem 0;
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
#historyModal .modal-footer {
    text-align: center;
    border-top: 1px solid #e5e7eb;
    padding-top: 1.25rem;
    margin-top: 1.5rem;
}

/* User Details Modal Styling */
    #expenseDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #expenseDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #expenseDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #expenseDetailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #expenseDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #expenseDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #expenseDetailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
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
  margin: 2rem auto;
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
#expenseModal form {
  text-align: left;
}

#expenseModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#expenseModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#expenseModal .form-control,
#expenseModal select.form-control,
#expenseModal textarea.form-control {
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
#expenseModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#expenseModal .form-control:focus {
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
#reportIssueModal form {
  text-align: left;
}

#reportIssueModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#reportIssueModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#reportIssueModal .form-control,
#reportIssueModal select.form-control,
#reportIssueModal textarea.form-control {
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
#reportIssueModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#reportIssueModal .form-control:focus {
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

/* ============================
   FOOTER & ALIGNMENT
   ============================ */
#reportIssueModal .modal-footer {
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

  #addClientModal .form-control {
    font-size: 14px;
  }

  #editLivestockModal .form-control {
    font-size: 14px;
  }
   #reportIssueModal .form-control {
    font-size: 14px;
  }

  .btn-ok,
  .btn-delete,
  .btn-approves {
    width: 100%;
    margin-top: 0.5rem;
  }
}
    /* Apply consistent styling for Pending Farmers and Active Farmers tables */
#expensesTable th,
#expensesTable td,
#activeFarmersTable th,
#activeFarmersTable td {
    vertical-align: middle;
    padding: 0.75rem;
    text-align: center;
    border: 1px solid #dee2e6;
    white-space: nowrap;
    overflow: visible;
}

/* Ensure all table headers have consistent styling */
#expensesTable thead th,
#activeFarmersTable thead th {
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
#expensesTable thead th.sorting,
#expensesTable thead th.sorting_asc,
#expensesTable thead th.sorting_desc,
#activeFarmersTable thead th.sorting,
#activeFarmersTable thead th.sorting_asc,
#activeFarmersTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#expensesTable thead th::after,
#activeFarmersTable thead th::after {
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
#expensesTable thead th.sorting::after,
#expensesTable thead th.sorting_asc::after,
#expensesTable thead th.sorting_desc::after,
#activeFarmersTable thead th.sorting::after,
#activeFarmersTable thead th.sorting_asc::after,
#activeFarmersTable thead th.sorting_desc::after {
    display: none;
}

/* Allow table to scroll horizontally if too wide */
.table-responsive {
    overflow-x: auto;
}

/* Make table cells wrap instead of forcing them all inline */
#expensesTable td, 
#expensesTable th {
    white-space: normal !important;  /* allow wrapping */
    vertical-align: middle;
}

/* Make sure action buttons don’t overflow */
#expensesTable td .btn-group {
    display: flex;
    flex-wrap: wrap; /* buttons wrap if not enough space */
    gap: 0.25rem;    /* small gap between buttons */
}

#expensesTable td .btn-action {
    flex: 1 1 auto; /* allow buttons to shrink/grow */
    min-width: 90px; /* prevent too tiny buttons */
    text-align: center;
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
    
    .btn-action-edits {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
    .btn-action-edits:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    .btn-action-history {
        background-color: #5a6268;
        border-color: #5a6268;
        color: white;
    }
    
    .btn-action-history:hover {
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

    .btn-action-cancel {
        background-color: #6c757d ;
        border-color: #6c757d ;
        color: white ;
    }
    
    .btn-action-refresh, .btn-action-refresh- {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-refresh:hover, .btn-action-refresh-farmers:hover {
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
    /* Search and button group alignment */
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
/* ===== DATATABLE STYLES ===== */
.dataTables_length {
    margin-bottom: 1rem;
}

.dataTables_length select {
    min-width: 80px;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background-color: #fff;
    margin: 0 0.5rem;
}

.dataTables_length label {
    display: flex;
    align-items: center;
    margin-bottom: 0;
    font-weight: 500;
    color: var(--dark-color);
}

.dataTables_info {
    padding-top: 0.5rem;
    font-weight: 500;
    color: var(--dark-color);
}

.dataTables_paginate {
    margin-top: 1rem;
}

.dataTables_paginate .paginate_button {
    padding: 0.5rem 0.75rem;
    margin: 0 0.125rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background-color: #fff;
    color: var(--dark-color);
    text-decoration: none;
    transition: var(--transition-fast);
}

.dataTables_paginate .paginate_button:hover {
    background-color: var(--light-color);
    border-color: var(--primary-light);
    color: var(--primary-color);
}

.dataTables_paginate .paginate_button.current {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

.dataTables_paginate .paginate_button.disabled {
    color: var(--text-muted);
    cursor: not-allowed;
    background-color: var(--light-color);
    border-color: var(--border-color);
}

.dataTables_filter {
    margin-bottom: 1rem;
}

.dataTables_filter input {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background-color: #fff;
    transition: var(--transition-fast);
}

.dataTables_filter input:focus {
    border-color: var(--primary-light);
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    outline: 0;
}
.export-controls {
    display: flex;
    gap: 0.5rem;
    margin-left: auto;
}

.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.toast-container {
    z-index: 9999;
}

.alert {
    border: none;
    border-radius: 8px;
    font-weight: 500;
}

.alert-danger {
    background: rgba(231, 74, 59, 0.1);
    color: #c53030;
    border-left: 4px solid #e74a3b;
}

.badge {
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.4rem 0.8rem;
}

.badge-primary {
    background: linear-gradient(135deg, #4e73df, #3c5aa6);
}

.badge-success {
    background: linear-gradient(135deg, #1cc88a, #17a673);
}

.badge-warning {
    background: linear-gradient(135deg, #f6c23e, #f4b619);
}

.badge-info {
    background: linear-gradient(135deg, #36b9cc, #2a96a5);
}

.badge-danger {
    background: linear-gradient(135deg, #e74a3b, #d52a1a);
}
</style>
@endpush

