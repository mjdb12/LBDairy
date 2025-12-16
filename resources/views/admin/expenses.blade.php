@extends('layouts.app')

@section('title', 'Expenses Management - LBDairy')

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

    <!-- Stats Cards -->
    <div class="stats-container fade-in">
        <div class="stat-card border-left-danger">
            <div class="card-body">
                <div>
                    <div class="stat-label">Total Expenses</div>
                    <div class="stat-number text-danger">₱{{ number_format($totalExpenses ?? 0, 0) }}</div>
                    <div class="stat-change text-danger">
                        <i class="fas fa-arrow-up"></i>
                        {{ $expenseChange ?? 0 }}% from last month
                    </div>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card border-left-success">
            <div class="card-body">
                <div>
                    <div class="stat-label">Feed Expenses</div>
                    <div class="stat-number text-success">₱{{ number_format($feedExpenses ?? 0, 0) }}</div>
                    <div class="stat-change text-success">
                        <i class="fas fa-arrow-down"></i>
                        {{ $feedChange ?? 0 }}% from last month
                    </div>
                </div>
                <div class="icon">
                    <i class="fas fa-seedling"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card border-left-info">
            <div class="card-body">
                <div>
                    <div class="stat-label">Veterinary Expenses</div>
                    <div class="stat-number text-info">₱{{ number_format($veterinaryExpenses ?? 0, 0) }}</div>
                    <div class="stat-change text-info">
                        <i class="fas fa-arrow-up"></i>
                        {{ $veterinaryChange ?? 0 }}% from last month
                    </div>
                </div>
                <div class="icon">
                    <i class="fas fa-stethoscope"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card border-left-warning">
            <div class="card-body">
                <div>
                    <div class="stat-label">Maintenance Expenses</div>
                    <div class="stat-number text-warning">₱{{ number_format($maintenanceExpenses ?? 0, 0) }}</div>
                    <div class="stat-change text-warning">
                        <i class="fas fa-arrow-up"></i>
                        {{ $maintenanceChange ?? 0 }}% from last month
                    </div>
                </div>
                <div class="icon">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Expenses Table Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header">
            <h6>
                <i class="fas fa-list"></i>
                Expenses List
            </h6>
            <div class="table-controls">
                <button class="btn btn-info btn-sm" onclick="addExpense()">
                    <i class="fas fa-plus mr-1"></i> Add New Expense
                </button>
                <div class="search-container">
                    <input type="text" class="form-control custom-search" placeholder="Search expenses..." id="customSearch">
                </div>
                <div class="export-controls">
                    <div class="btn-group">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportCSV()">
                                <i class="fas fa-file-csv mr-2"></i>CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPDF()">
                                <i class="fas fa-file-pdf mr-2"></i>PDF
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPNG()">
                                <i class="fas fa-file-image mr-2"></i>PNG
                            </a>
                        </div>
                    </div>
                    <button class="btn btn-secondary btn-sm" onclick="printExpenses()">
                        <i class="fas fa-print"></i>
                    </button>
                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#historyModal">
                        <i class="fas fa-history"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0" id="expensesTable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag mr-1"></i>Expense ID</th>
                            <th><i class="fas fa-calendar mr-1"></i>Date</th>
                            <th><i class="fas fa-tag mr-1"></i>Expense Name</th>
                            <th><i class="fas fa-peso-sign mr-1"></i>Expense Amount</th>
                            <th><i class="fas fa-check-circle mr-1"></i>Paid Amount</th>
                            <th><i class="fas fa-cogs mr-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="expensesTableBody">
                        @forelse($expenses ?? [] as $expense)
                        <tr>
                            <td><span class="badge badge-primary">{{ $expense->id }}</span></td>
                            <td>{{ $expense->date ?? 'N/A' }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-{{ $expense->icon ?? 'receipt' }} text-{{ $expense->color ?? 'primary' }} mr-2"></i>
                                    {{ $expense->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td><strong>₱{{ number_format($expense->amount ?? 0, 0) }}</strong></td>
                            <td><span class="badge badge-success">₱{{ number_format($expense->paid_amount ?? 0, 0) }}</span></td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete(this)">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-receipt"></i>
                                    <h5>No expenses found</h5>
                                    <p>Add your first expense to start tracking.</p>
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
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <p>Are you sure you want to delete this expense record?</p>
                    <p class="text-muted small">This action cannot be undone.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Cancel
                </button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger">
                    <i class="fas fa-trash mr-1"></i>Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" role="dialog" aria-labelledby="addExpenseLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Add New Expense
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addExpenseForm">
                    <div class="form-group">
                        <label for="expenseId">
                            <i class="fas fa-hashtag mr-1"></i>
                            Expense ID
                        </label>
                        <input type="text" class="form-control" id="expenseId" name="expenseId" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="expenseDate">
                            <i class="fas fa-calendar mr-1"></i>
                            Date
                        </label>
                        <input type="date" class="form-control" id="expenseDate" name="expenseDate" required max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label for="expenseName">
                            <i class="fas fa-tag mr-1"></i>
                            Expense Name
                        </label>
                        <input type="text" class="form-control" id="expenseName" name="expenseName" required>
                    </div>
                    <div class="form-group">
                        <label for="expenseCategory">
                            <i class="fas fa-list mr-1"></i>
                            Category
                        </label>
                        <select class="form-control" id="expenseCategory" name="expenseCategory" required>
                            <option value="">Select Category</option>
                            <option value="Feed">Feed</option>
                            <option value="Veterinary">Veterinary</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Utilities">Utilities</option>
                            <option value="Medicine">Medicine</option>
                            <option value="Equipment">Equipment</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="expenseAmount">
                            <i class="fas fa-peso-sign mr-1"></i>
                            Amount
                        </label>
                        <input type="number" class="form-control" id="expenseAmount" name="expenseAmount" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="expenseDescription">
                            <i class="fas fa-align-left mr-1"></i>
                            Description
                        </label>
                        <textarea class="form-control" id="expenseDescription" name="expenseDescription" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="saveExpense()">
                    <i class="fas fa-save mr-1"></i>Save Expense
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
                    <i class="fas fa-history mr-2"></i>
                    Expense History
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="historyContent">
                    <!-- History content will be loaded here -->
                    <div class="text-center text-muted">
                        <i class="fas fa-history fa-3x mb-3"></i>
                        <p>Expense history will be displayed here</p>
                    </div>
                </div>
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

<script>
let expensesTable;

$(document).ready(function() {
    initializeDataTable();
    setupEventListeners();
    generateExpenseId();
});

function initializeDataTable() {
    expensesTable = $('#expensesTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[1, 'desc']], // Sort by date descending
        language: {
            search: "Search expenses:",
            lengthMenu: "Show _MENU_ expenses per page",
            info: "Showing _START_ to _END_ of _TOTAL_ expenses"
        }
    });
}

function setupEventListeners() {
    // Custom search functionality
    $('#customSearch').on('keyup', function() {
        expensesTable.search(this.value).draw();
    });

    // Category change handler
    $('#expenseCategory').on('change', function() {
        updateExpenseIcon();
    });
}

function generateExpenseId() {
    const timestamp = Date.now();
    const random = Math.floor(Math.random() * 1000);
    const expenseId = `EX${timestamp.toString().slice(-6)}${random.toString().padStart(3, '0')}`;
    $('#expenseId').val(expenseId);
}

function updateExpenseIcon() {
    const category = $('#expenseCategory').val();
    const iconMap = {
        'Feed': 'seedling',
        'Veterinary': 'stethoscope',
        'Maintenance': 'tools',
        'Utilities': 'bolt',
        'Medicine': 'pills',
        'Equipment': 'wrench',
        'Other': 'receipt'
    };
    
    const icon = iconMap[category] || 'receipt';
    $('#expenseName').attr('placeholder', `Enter ${category} expense name`);
}

function addExpense() {
    generateExpenseId();
    $('#expenseDate').val(new Date().toISOString().split('T')[0]);
    $('#addExpenseForm')[0].reset();
    $('#addExpenseModal').modal('show');
}

function confirmDelete(button) {
    const row = $(button).closest('tr');
    const expenseId = row.find('td:first .badge').text();
    const expenseName = row.find('td:nth-child(3)').text().trim();
    
    $('#confirmDeleteModal').modal('show');
    
    $('#confirmDeleteBtn').off('click').on('click', function() {
        $.ajax({
            url: `/admin/expenses/${expenseId}`,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(resp) {
                if (resp && resp.success) {
                    $('#confirmDeleteModal').modal('hide');
                    location.reload();
                } else {
                    alert('Failed to delete expense.');
                }
            },
            error: function() {
                alert('Failed to delete expense.');
            }
        });
    });
}

function saveExpense() {
    const formData = new FormData($('#addExpenseForm')[0]);
    const payload = Object.fromEntries(formData);
    $.ajax({
        url: '/admin/expenses',
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        data: {
            expenseDate: payload.expenseDate,
            expenseName: payload.expenseName,
            expenseCategory: payload.expenseCategory,
            expenseAmount: payload.expenseAmount,
            expenseDescription: payload.expenseDescription
        },
        success: function(resp) {
            if (resp && resp.success) {
                alert('Expense saved successfully!');
                $('#addExpenseModal').modal('hide');
                setTimeout(() => location.reload(), 500);
            } else {
                alert('Failed to save expense.');
            }
        },
        error: function() {
            alert('Failed to save expense.');
        }
    });
}

// Export functions
function exportCSV() {
    // Get current table data without actions column
    const tableData = expensesTable.data().toArray();
    const csvData = [];
    
    // Add headers (excluding Actions column)
    const headers = ['Expense ID', 'Date', 'Expense Name', 'Amount', 'Paid Amount', 'Status', 'Farm ID'];
    csvData.push(headers.join(','));
    
    // Add data rows (excluding Actions column)
    tableData.forEach(row => {
        // Extract text content from each cell, excluding the last column (Actions)
        const rowData = [];
        for (let i = 0; i < row.length - 1; i++) {
            let cellText = '';
            if (row[i]) {
                // Remove HTML tags and get clean text
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = row[i];
                cellText = tempDiv.textContent || tempDiv.innerText || '';
                // Clean up the text (remove extra spaces, newlines)
                cellText = cellText.replace(/\s+/g, ' ').trim();
            }
            // Escape commas and quotes for CSV
            if (cellText.includes(',') || cellText.includes('"') || cellText.includes('\n')) {
                cellText = '"' + cellText.replace(/"/g, '""') + '"';
            }
            rowData.push(cellText);
        }
        csvData.push(rowData.join(','));
    });
    
    // Create and download CSV file
    const csvContent = csvData.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', `Admin_ExpensesReport_${downloadCounter}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Increment download counter
    downloadCounter++;
    
    showNotification('CSV exported successfully!', 'success');
}

function exportPDF() {
    try {
        // Force custom PDF generation to match superadmin styling
        // Don't fall back to DataTables PDF export as it has different styling
        
        const tableData = expensesTable.data().toArray();
        const pdfData = [];
        
        const headers = ['Expense ID', 'Category', 'Description', 'Amount', 'Date', 'Status', 'Created By'];
        
        tableData.forEach(row => {
            const rowData = [
                row[0] || '', // Expense ID
                row[1] || '', // Category
                row[2] || '', // Description
                row[3] || '', // Amount
                row[4] || '', // Date
                row[5] || '',  // Status
                row[6] || ''   // Created By
            ];
            pdfData.push(rowData);
        });
        
        // Create PDF using jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape', 'mm', 'a4');
        
        // Set title
        doc.setFontSize(18);
        doc.text('Admin Expenses Report', 14, 22);
        doc.setFontSize(12);
        doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 14, 32);
        
        // Create table
        doc.autoTable({
            head: [headers],
            body: pdfData,
            startY: 40,
            styles: { fontSize: 8, cellPadding: 2 },
            headStyles: { fillColor: [24, 55, 93], textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [245, 245, 245] }
        });
        
        // Save the PDF
        doc.save(`Admin_ExpensesReport_${downloadCounter}.pdf`);
        
        // Increment download counter
        downloadCounter++;
        
        showNotification('PDF exported successfully!', 'success');
        
    } catch (error) {
        console.error('Error generating PDF:', error);
        showNotification('Error generating PDF. Please try again.', 'danger');
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
        link.download = `Admin_ExpensesReport_${downloadCounter}.png`;
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
}

function downloadCSV(csv, filename) {
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

function printExpenses() {
    try {
        const tableData = expensesTable && expensesTable.data ? expensesTable.data().toArray() : [];
        if (!tableData || tableData.length === 0) {
            showNotification('No data available to print', 'warning');
            return;
        }

        let printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #18375d; margin-bottom: 5px;">Expenses Report</h1>
                    <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                <table border="1" style="border-collapse: collapse; width: 100%; border: 1px solid #000;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2; text-align: left;">Expense ID</th>
                            <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2; text-align: left;">Date</th>
                            <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2; text-align: left;">Expense Name</th>
                            <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2; text-align: left;">Expense Amount</th>
                            <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2; text-align: left;">Paid Amount</th>
                        </tr>
                    </thead>
                    <tbody>`;

        tableData.forEach(row => {
            printContent += '<tr>';
            for (let i = 0; i < row.length - 1; i++) { // Exclude Actions column
                let cellText = '';
                if (row[i]) {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = row[i];
                    cellText = (tempDiv.textContent || tempDiv.innerText || '').replace(/\s+/g, ' ').trim();
                }
                printContent += `<td style=\"border: 1px solid #000; padding: 8px; text-align: left;\">${cellText}</td>`;
            }
            printContent += '</tr>';
        });

        printContent += `
                    </tbody>
                </table>
            </div>`;

        if (typeof window.printElement === 'function') {
            const container = document.createElement('div');
            container.innerHTML = printContent;
            window.printElement(container);
        } else if (typeof window.openPrintWindow === 'function') {
            window.openPrintWindow(printContent, 'Expenses Report');
        } else {
            window.print();
        }
    } catch (e) {
        window.print();
    }
}
</script>
@endpush

@push('styles')
<style>
:root {
    --primary-color: #18375d;
    --primary-dark: #122a47;
    --success-color: #1cc88a;
    --warning-color: #f6c23e;
    --danger-color: #e74a3b;
    --info-color: #36b9cc;
    --light-color: #fff;
    --dark-color: #5a5c69;
    --border-color: #e3e6f0;
    --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    --border-radius: 12px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.page-header {
    background: var(--primary-color);
    color: white;
    padding: 2rem;
    border-radius: var(--border-radius);
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
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    transition: var(--transition);
    overflow: hidden;
    position: relative;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.stat-card.border-left-danger {
    border-left: 4px solid var(--danger-color);
}

.stat-card.border-left-success {
    border-left: 4px solid var(--success-color);
}

.stat-card.border-left-info {
    border-left: 4px solid var(--info-color);
}

.stat-card.border-left-warning {
    border-left: 4px solid var(--warning-color);
}

.stat-card .card-body {
    padding: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.stat-label {
    font-size: 0.9rem;
    color: var(--dark-color);
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-change {
    font-size: 0.8rem;
    font-weight: 500;
}

.stat-card .icon {
    font-size: 2.5rem;
    opacity: 0.8;
}

.card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    transition: var(--transition);
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
    flex-wrap: wrap;
    gap: 1rem;
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

.table-controls {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.search-container {
    min-width: 200px;
}

.custom-search {
    border-radius: 20px;
    border: none;
    padding: 0.5rem 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.export-controls {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

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

.table tbody tr {
    transition: var(--transition);
}

.table tbody tr:hover {
    background-color: rgba(78, 115, 223, 0.05);
    transform: scale(1.001);
}

.table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-top: 1px solid #f0f0f0;
}

.badge {
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    padding: 0.5rem 0.75rem;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: var(--transition);
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

.empty-state h5 {
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.empty-state p {
    opacity: 0.7;
    margin: 0;
}

.fade-in {
    animation: fadeIn 0.6s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* DataTables customization */
.dataTables_wrapper .dataTables_filter input {
    border-radius: 20px;
    border: 1px solid var(--border-color);
    padding: 0.5rem 1rem;
}

.dataTables_wrapper .dataTables_length select {
    border-radius: 8px;
    border: 1px solid var(--border-color);
    padding: 0.25rem 0.5rem;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 8px;
    margin: 0 0.25rem;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
}

@media (max-width: 768px) {
    .stats-container {
        grid-template-columns: 1fr;
    }
    
    .card-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .table-controls {
        flex-direction: column;
        align-items: stretch;
    }
    
    .export-controls {
        flex-wrap: wrap;
        justify-content: center;
    }
}
</style>
@endpush
