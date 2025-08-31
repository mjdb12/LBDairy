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
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-list"></i>
                        Expenses List
                    </h6>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-primary btn-sm" onclick="openAddExpenseModal()">
                            <i class="fas fa-plus"></i> Add New Expense
                        </button>
                        <div class="export-controls ml-3">
                            <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" onclick="exportToCSV()">
                                    <i class="fas fa-file-csv"></i> CSV
                                </a>
                                <a class="dropdown-item" href="#" onclick="exportToPDF()">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                                <a class="dropdown-item" href="#" onclick="exportToPNG()">
                                    <i class="fas fa-file-image"></i> PNG
                                </a>
                            </div>
                        </div>
                        <button class="btn btn-info btn-sm ml-2" onclick="openHistoryModal()">
                            <i class="fas fa-history"></i> History
                        </button>
                        <button class="btn btn-secondary btn-sm ml-2" onclick="printExpenses()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
                <div class="card-body">
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
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-receipt fa-3x mb-3 text-muted"></i>
                                        <p>No expenses recorded yet. Start tracking your farm expenses!</p>
                                        <button class="btn btn-primary" onclick="openAddExpenseModal()">
                                            <i class="fas fa-plus"></i> Add First Expense
                                        </button>
                                    </td>
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
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel">
                    <i class="fas fa-history"></i>
                    Expense History & Analytics
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-chart-pie"></i> Monthly Breakdown</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="monthlyChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Category Distribution</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="categoryChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let currentExpenseId = null;

$(document).ready(function() {
    // DataTable initialization disabled to prevent column count warnings
    // The table will function as a standard HTML table with Bootstrap styling
    console.log('Expenses table loaded successfully');

    // Handle form submission
    $('#expenseForm').on('submit', function(e) {
        e.preventDefault();
        submitExpenseForm();
    });

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
    $('#expenseForm').append('<input type="hidden" name="_method" value="PUT">');
    
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
        url: `/farmer/expenses/${expenseId}/edit`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const expense = response.expense;
                $('#expense_id').val(expense.expense_id);
                $('#expense_date').val(expense.expense_date);
                $('#expense_name').val(expense.expense_name);
                $('#category').val(expense.category);
                $('#amount').val(expense.amount);
                $('#payment_status').val(expense.payment_status);
                $('#payment_method').val(expense.payment_method);
                $('#due_date').val(expense.due_date);
                $('#description').val(expense.description);
                $('#notes').val(expense.notes);
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

function openHistoryModal() {
    $('#historyModal').modal('show');
    // Load charts after modal is shown
    $('#historyModal').on('shown.bs.modal', function() {
        loadCharts();
    });
}

function loadCharts() {
    // Sample data - replace with actual data from backend
    const monthlyData = [12000, 15000, 18000, 14000, 16000, 19000];
    const categoryData = [45, 25, 20, 10, 0];
    
    // Monthly expenses chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Monthly Expenses',
                data: monthlyData,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Category distribution chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Feed', 'Veterinary', 'Maintenance', 'Utilities', 'Other'],
            datasets: [{
                data: categoryData,
                backgroundColor: [
                    '#1cc88a',
                    '#36b9cc',
                    '#f6c23e',
                    '#e74a3b',
                    '#6c757d'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

function exportToCSV() {
    const table = $('#expensesTable').DataTable();
    const data = table.data().toArray();
    
    let csv = 'Expense ID,Date,Expense Name,Amount,Payment Status,Payment Method\n';
    data.forEach(row => {
        csv += `${row[0]},${row[1]},${row[2]},${row[3]},${row[4]},${row[5]}\n`;
    });
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'expenses_report.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

function exportToPDF() {
    showToast('PDF export feature coming soon!', 'info');
}

function exportToPNG() {
    showToast('PNG export feature coming soon!', 'info');
}

function printExpenses() {
    window.print();
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

