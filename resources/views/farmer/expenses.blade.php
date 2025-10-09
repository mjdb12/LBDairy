@extends('layouts.app')

@section('title', 'Expenses Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-money-bill-wave"></i>
            Expenses Management
        </h1>
        <p>Track and manage all farm expenses efficiently</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Expenses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($totalExpenses, 2) }}</div>
                            <div class="text-xs text-danger">
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
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Feed Expenses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($feedExpenses, 2) }}</div>
                            <div class="text-xs text-success">
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
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Veterinary Expenses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($veterinaryExpenses, 2) }}</div>
                            <div class="text-xs text-info">
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
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Maintenance Expenses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($maintenanceExpenses, 2) }}</div>
                            <div class="text-xs text-warning">
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
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Expense Trend</h6>
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
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Budget Alert!</strong> Your expenses have exceeded your monthly budget by ₱{{ number_format($budgetExcess, 2) }}.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow fade-in">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-list"></i>
                        Expenses List
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
                                <input type="text" id="expensesSearch" class="form-control" placeholder="Search expenses...">
                            </div>
                            <div class="btn-group d-flex gap-2 align-items-center mt-2 mt-sm-0">
                                <button class="btn btn-primary btn-sm" onclick="openAddExpenseModal()">
                                    <i class="fas fa-plus"></i> Add Expense
                                </button>
                                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#historyModal">
                                    <i class="fas fa-history"></i> History
                                </button>
                                <button class="btn btn-secondary btn-sm" onclick="printExpenses()">
                                    <i class="fas fa-print"></i> Print
                                </button>
                                <button class="btn btn-warning btn-sm" onclick="refreshExpenses()">
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
                        <table class="table table-bordered" id="expensesTable" width="100%" cellspacing="0">
                            <thead>
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
                            <tbody>
                                @forelse($expensesData as $expense)
                                <tr>
                                    <td>
                                        <span class="badge badge-primary">{{ $expense['expense_id'] }}</span>
                                    </td>
                                    <td>{{ $expense['expense_date'] }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-receipt text-primary mr-2"></i>
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
                                        <div class="action-buttons">
                                            <button class="btn-action btn-action-view" onclick="viewExpenseDetails('{{ $expense['id'] }}')" title="View Details">
                                                <i class="fas fa-eye"></i>
                                                <span>View</span>
                                            </button>
                                            <button class="btn-action btn-action-edit" onclick="openEditExpenseModal('{{ $expense['id'] }}')" title="Edit Expense">
                                                <i class="fas fa-edit"></i>
                                                <span>Edit</span>
                                            </button>
                                            <button class="btn-action btn-action-delete" onclick="confirmDelete('{{ $expense['id'] }}')" title="Delete Expense">
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
        </div>
    </div>
</div>

<!-- Add/Edit Expense Modal -->
<div class="modal fade" id="expenseModal" tabindex="-1" role="dialog" aria-labelledby="expenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="expenseModalLabel">
                    <i class="fas fa-plus"></i>
                    Add New Expense
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="expenseForm" method="POST" action="{{ route('farmer.expenses.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="farm_id">Select Farm</label>
                                <select class="form-control" id="farm_id" name="farm_id" required>
                                    <option value="" disabled selected>Select Farm</option>
                                    @foreach($farms ?? [] as $farm)
                                        <option value="{{ $farm->id }}">{{ $farm->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expense_date">Expense Date</label>
                                <input type="date" class="form-control" id="expense_date" name="expense_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="description">Expense Description</label>
                                <input type="text" class="form-control" id="description" name="description" required placeholder="e.g., Feed Purchase, Veterinary Services">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expense_type">Category</label>
                                <select class="form-control" id="expense_type" name="expense_type" required>
                                    <option value="">Select Category</option>
                                    <option value="feed">Feed</option>
                                    <option value="medicine">Medicine/Veterinary</option>
                                    <option value="equipment">Equipment/Maintenance</option>
                                    <option value="labor">Labor</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount">Amount (₱)</label>
                                <input type="number" class="form-control" id="amount" name="amount" min="0" step="0.01" required placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_method">Payment Method</label>
                                <select class="form-control" id="payment_method" name="payment_method">
                                    <option value="cash">Cash</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="check">Check</option>
                                    <option value="credit">Credit</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="receipt_number">Receipt Number</label>
                                <input type="text" class="form-control" id="receipt_number" name="receipt_number" placeholder="Optional receipt number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Additional notes about this expense..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Expense
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Expense Details Modal -->
<div class="modal fade" id="expenseDetailsModal" tabindex="-1" role="dialog" aria-labelledby="expenseDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="expenseDetailsLabel">
                    <i class="fas fa-info-circle"></i>
                    Expense Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="expenseDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editCurrentExpense()">
                    <i class="fas fa-edit"></i> Edit
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
                    Expenses History (Quarterly)
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="expensesYear" class="font-weight-bold">Year:</label>
                        <select id="expensesYear" class="form-control form-control-sm" onchange="loadExpensesHistory()">
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
                <div class="table-responsive">
                    <table class="table table-bordered" id="expensesHistoryQuarterTable">
                        <thead>
                            <tr>
                                <th>Quarter</th>
                                <th>Total Expenses (₱)</th>
                                <th>Records</th>
                            </tr>
                        </thead>
                        <tbody id="expensesHistoryTableBody">
                            <!-- Quarterly history rows here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="exportExpensesHistory()">
                    <i class="fas fa-download"></i> Export History
                </button>
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
                Are you sure you want to delete this expense? This action cannot be undone.
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
        buttons: [
            { extend: 'csvHtml5', title: 'Farmer_Expenses_Report', className: 'd-none' },
            { extend: 'pdfHtml5', title: 'Farmer_Expenses_Report', orientation: 'landscape', pageSize: 'Letter', className: 'd-none' },
            { extend: 'print', title: 'Farmer Expenses Report', className: 'd-none' }
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

    // Handle form submission
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
            labels: {!! json_encode($expenseStats['monthly_trend']->pluck('month')) !!},
            datasets: [{
                label: 'Expenses (₱)',
                data: {!! json_encode($expenseStats['monthly_trend']->pluck('expenses')) !!},
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
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr><td><strong>ID:</strong></td><td>${expense.expense_id}</td></tr>
                                        <tr><td><strong>Name:</strong></td><td>${expense.expense_name}</td></tr>
                                        <tr><td><strong>Category:</strong></td><td><span class="badge badge-${getExpenseColor(expense.category)}">${expense.category}</span></td></tr>
                                        <tr><td><strong>Amount:</strong></td><td><strong>₱${parseFloat(expense.amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</strong></td></tr>
                                        <tr><td><strong>Date:</strong></td><td>${expense.expense_date}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-credit-card"></i> Payment Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr><td><strong>Status:</strong></td><td><span class="badge badge-${expense.payment_status === 'Paid' ? 'success' : 'warning'}">${expense.payment_status}</span></td></tr>
                                        <tr><td><strong>Method:</strong></td><td>${expense.payment_method || 'Not specified'}</td></tr>
                                        <tr><td><strong>Due Date:</strong></td><td>${expense.due_date || 'Not specified'}</td></tr>
                                        <tr><td><strong>Description:</strong></td><td>${expense.description || 'No description'}</td></tr>
                                        <tr><td><strong>Notes:</strong></td><td>${expense.notes || 'No notes'}</td></tr>
                                    </table>
                                </div>
                            </div>
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
    const formData = new FormData($('#expenseForm')[0]);
    
    $.ajax({
        url: $('#expenseForm').attr('action'),
        method: $('#expenseForm').attr('method'),
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                $('#expenseModal').modal('hide');
                location.reload();
            } else {
                showToast(response.message || 'Error saving expense', 'error');
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(field => {
                    showToast(errors[field][0], 'error');
                });
            } else {
                showToast('Error saving expense', 'error');
            }
        }
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
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                $('#confirmDeleteModal').modal('hide');
                location.reload();
            } else {
                showToast(response.message || 'Error deleting expense', 'error');
            }
        },
        error: function() {
            showToast('Error deleting expense', 'error');
        }
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
    try {
        if (!expensesDT) return showToast('Table is not ready.', 'error');
        const rows = expensesDT.data().toArray();
        const headers = ['Expense ID','Date','Expense Name','Amount','Payment Status','Payment Method'];
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
        const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = `Farmer_ExpensesReport_${Date.now()}.csv`; a.click();
        showToast('CSV exported successfully!', 'success');
    } catch(e){ console.error('CSV export error:', e); showToast('Error generating CSV.', 'error'); }
}

function exportPDF() {
    try {
        if (!expensesDT) return showToast('Table is not ready.', 'error');
        const rows = expensesDT.data().toArray();
        const data = rows.map(r => [r[0]||'', r[1]||'', r[2]||'', r[3]||'', r[4]||'', r[5]||'']);
        const headers = ['Expense ID','Date','Expense Name','Amount','Payment Status','Payment Method'];
        // Create PDF using jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape', 'mm', 'a4');
        
        // Set title
        doc.setFontSize(18);
        doc.text('Farmer Expenses Report', 14, 22);
        doc.setFontSize(12);
        doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 14, 32);
        
        // Create table
        doc.autoTable({
            head: [headers],
            body: data,
            startY: 40,
            styles: { fontSize: 8, cellPadding: 2 },
            headStyles: { fillColor: [24, 55, 93], textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [245, 245, 245] }
        });
        
        // Save the PDF
        doc.save(`Farmer_ExpensesReport_${Date.now()}.pdf`);
        
        showToast('PDF exported successfully!', 'success');
        
    } catch (error) {
        console.error('Error generating PDF:', error);
        showToast('Error generating PDF. Please try again.', 'error');
    }
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
    const btn = document.querySelector('.btn.btn-warning.btn-sm');
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

