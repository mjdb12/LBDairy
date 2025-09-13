@extends('layouts.app')

@section('title', 'Clients Management - LBDairy')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-users"></i>
            Clients Management Dashboard
        </h1>
        <p>Manage client relationships, track payments, and monitor business performance</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-container">
        <div class="stat-card primary">
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-number">{{ $totalClients ?? 0 }}</div>
            <div class="stat-label">Total Clients</div>
            <div class="footer">
                <span>View all clients</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>
        <div class="stat-card success">
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-number">{{ $activeClients ?? 0 }}</div>
            <div class="stat-label">Active Clients</div>
            <div class="footer">
                <span>Currently active</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>
        <div class="stat-card danger">
            <div class="icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-number">{{ $outstandingBalance ?? 0 }}</div>
            <div class="stat-label">Outstanding Balance</div>
            <div class="footer">
                <span>Requires attention</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>
        <div class="stat-card info">
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="stat-number">{{ $newThisMonth ?? 0 }}</div>
            <div class="stat-label">New This Month</div>
            <div class="footer">
                <span>{{ now()->format('F Y') }}</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>
    </div>

    <!-- Clients Table Card -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header">
            <h6>
                <i class="fas fa-users"></i>
                Client Directory
            </h6>
            <div class="table-controls">
                <div class="search-container">
                    <input type="text" class="form-control custom-search" id="customSearch" placeholder="Search clients...">
                </div>
                <div class="export-controls">
                    <button class="btn btn-primary btn-sm" onclick="addNewClient()">
                        <i class="fas fa-plus mr-2"></i>Add Client
                    </button>
                    <div class="btn-group">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fas fa-download mr-2"></i>Export
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="exportCSV()">
                                <i class="fas fa-file-csv mr-2"></i>Download CSV
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPDF()">
                                <i class="fas fa-file-pdf mr-2"></i>Download PDF
                            </a>
                            <a class="dropdown-item" href="#" onclick="exportPNG()">
                                <i class="fas fa-file-image mr-2"></i>Download PNG
                            </a>
                        </div>
                    </div>
                    <button class="btn btn-secondary btn-sm" onclick="printTable()">
                        <i class="fas fa-print mr-2"></i>Print
                    </button>
                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#historyModal">
                        <i class="fas fa-history mr-2"></i>History
                    </button>
                    <button class="btn btn-success btn-sm" onclick="document.getElementById('csvInput').click()">
                        <i class="fas fa-file-import mr-2"></i>Import
                    </button>
                    <input type="file" id="csvInput" accept=".csv" style="display: none;" onchange="importCSV(event)">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="clientsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Client ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients ?? [] as $client)
                        <tr>
                            <td><code class="small">{{ $client->id }}</code></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $client->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($client->name) . '&color=4e73df&background=f8f9fc' }}" 
                                         class="rounded-circle mr-2" width="32" height="32" alt="{{ $client->name }}">
                                    <span class="font-weight-bold">{{ $client->name }}</span>
                                </div>
                            </td>
                            <td class="text-muted">{{ $client->address ?? 'N/A' }}</td>
                            <td class="text-muted">{{ $client->phone ?? 'N/A' }}</td>
                            <td>
                                <span class="status-badge status-{{ $client->status ?? 'active' }}">
                                    {{ ucfirst($client->status ?? 'active') }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-action-ledger" onclick="viewLedger(this)" title="View Ledger">
                                        <i class="fas fa-book"></i>
                                        <span>Ledger</span>
                                    </button>
                                    <button class="btn-action btn-action-view" onclick="viewDetails(this)" title="View Details">
                                        <i class="fas fa-eye"></i>
                                        <span>View</span>
                                    </button>
                                    <button class="btn-action btn-action-delete" onclick="confirmDelete(this)" title="Delete">
                                        <i class="fas fa-trash"></i>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <h5>No clients found</h5>
                                    <p>Add your first client to start managing relationships.</p>
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

<!-- Add/Edit Client Modal -->
<div class="modal fade" id="clientModal" tabindex="-1" aria-labelledby="clientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clientModalLabel">
                    <i class="fas fa-user-plus mr-2"></i>Add New Client
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="clientForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clientName">Full Name *</label>
                                <input type="text" class="form-control" id="clientName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clientEmail">Email</label>
                                <input type="email" class="form-control" id="clientEmail" name="email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clientPhone">Phone *</label>
                                <input type="tel" class="form-control" id="clientPhone" name="phone" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clientStatus">Status</label>
                                <select class="form-control" id="clientStatus" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="clientAddress">Address</label>
                        <textarea class="form-control" id="clientAddress" name="address" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="clientNotes">Notes</label>
                        <textarea class="form-control" id="clientNotes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Client</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Client Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">
                    <i class="fas fa-user mr-2"></i>Client Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="clientDetailsContent">
                    <!-- Client details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel">
                    <i class="fas fa-history mr-2"></i>Client History
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="historyContent">
                    <!-- History content will be loaded here -->
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
let clientsTable;

$(document).ready(function() {
    initializeDataTable();
    setupEventListeners();
});

function initializeDataTable() {
    clientsTable = $('#clientsTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']],
        language: {
            search: "Search clients:",
            lengthMenu: "Show _MENU_ clients per page",
            info: "Showing _START_ to _END_ of _TOTAL_ clients"
        }
    });
}

function setupEventListeners() {
    // Custom search functionality
    $('#customSearch').on('keyup', function() {
        clientsTable.search(this.value).draw();
    });

    // Client form submission
    $('#clientForm').on('submit', function(e) {
        e.preventDefault();
        saveClient();
    });
}

function addNewClient() {
    $('#clientModalLabel').text('Add New Client');
    $('#clientForm')[0].reset();
    $('#clientModal').modal('show');
}

function viewDetails(button) {
    const row = $(button).closest('tr');
    const clientId = row.find('td:first code').text();
    const clientName = row.find('td:nth-child(2) .font-weight-bold').text();
    
    $('#detailsModalLabel').text(`Client Details - ${clientName}`);
    
    // Load client details (in real app, this would be an AJAX call)
    const detailsHtml = `
        <div class="row">
            <div class="col-md-6">
                <h6>Basic Information</h6>
                <p><strong>ID:</strong> ${clientId}</p>
                <p><strong>Name:</strong> ${clientName}</p>
                <p><strong>Status:</strong> <span class="badge badge-success">Active</span></p>
            </div>
            <div class="col-md-6">
                <h6>Contact Information</h6>
                <p><strong>Phone:</strong> +63 912 345 6789</p>
                <p><strong>Email:</strong> client@example.com</p>
                <p><strong>Address:</strong> 123 Main St., City</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <h6>Recent Activity</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-shopping-cart text-success"></i> Order placed - 2 days ago</li>
                    <li><i class="fas fa-credit-card text-info"></i> Payment received - 1 week ago</li>
                    <li><i class="fas fa-phone text-warning"></i> Support call - 2 weeks ago</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>Statistics</h6>
                <p><strong>Total Orders:</strong> 15</p>
                <p><strong>Total Spent:</strong> ₱45,000</p>
                <p><strong>Member Since:</strong> January 2024</p>
            </div>
        </div>
    `;
    
    $('#clientDetailsContent').html(detailsHtml);
    $('#detailsModal').modal('show');
}

function viewLedger(button) {
    const row = $(button).closest('tr');
    const clientName = row.find('td:nth-child(2) .font-weight-bold').text();
    
    alert(`Viewing ledger for ${clientName}. This feature will show payment history and outstanding balances.`);
}

function confirmDelete(button) {
    const row = $(button).closest('tr');
    const clientName = row.find('td:nth-child(2) .font-weight-bold').text();
    
    if (confirm(`Are you sure you want to delete ${clientName}? This action cannot be undone.`)) {
        // In real app, this would be an AJAX call to delete the client
        row.fadeOut(400, function() {
            row.remove();
        });
    }
}

function saveClient() {
    const formData = new FormData($('#clientForm')[0]);
    
    // In real app, this would be an AJAX call to save the client
    console.log('Saving client:', Object.fromEntries(formData));
    
    // Show success message
    alert('Client saved successfully!');
    $('#clientModal').modal('hide');
    
    // Refresh the table (in real app, this would reload from server)
    location.reload();
}

// Export functions
function exportCSV() {
    // Get current table data without actions column
    const tableData = clientsTable.data().toArray();
    const csvData = [];
    
    // Add headers (excluding Actions column)
    const headers = ['Client ID', 'Name', 'Address', 'Contact', 'Status', 'Registration Date'];
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
    link.setAttribute('download', `Admin_ClientsReport_${downloadCounter}.csv`);
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
        
        const tableData = clientsTable.data().toArray();
        const pdfData = [];
        
        const headers = ['Client ID', 'Name', 'Email', 'Phone', 'Address', 'Status', 'Created At'];
        
        tableData.forEach(row => {
            const rowData = [
                row[0] || '', // Client ID
                row[1] || '', // Name
                row[2] || '', // Email
                row[3] || '', // Phone
                row[4] || '', // Address
                row[5] || '',  // Status
                row[6] || ''   // Created At
            ];
            pdfData.push(rowData);
        });
        
        // Create PDF using jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape', 'mm', 'a4');
        
        // Set title
        doc.setFontSize(18);
        doc.text('Admin Clients Report', 14, 22);
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
        doc.save(`Admin_ClientsReport_${downloadCounter}.pdf`);
        
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
    const originalTable = document.getElementById('clientsTable');
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
        link.download = `Admin_ClientsReport_${downloadCounter}.png`;
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

function printTable() {
    window.print();
}

function importCSV(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const csv = e.target.result;
            // Process CSV import (in real app, this would parse and validate the data)
            alert('CSV import functionality coming soon!');
        };
        reader.readAsText(file);
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
}

.page-header {
    background: var(--primary-color);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: var(--shadow);
    position: relative;
    overflow: hidden;
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
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.stat-card.primary {
    border-left: 4px solid var(--primary-color);
}

.stat-card.success {
    border-left: 4px solid var(--success-color);
}

.stat-card.danger {
    border-left: 4px solid var(--danger-color);
}

.stat-card.info {
    border-left: 4px solid var(--info-color);
}

.stat-card .icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
    margin-bottom: 1rem;
}

.stat-card.primary .icon {
    background: var(--primary-color);
}

.stat-card.success .icon {
    background: var(--success-color);
}

.stat-card.danger .icon {
    background: var(--danger-color);
}

.stat-card.info .icon {
    background: var(--info-color);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark-color);
    margin-bottom: 0.5rem;
}

.stat-label {
    color: var(--dark-color);
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 1rem;
}

.stat-card .footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.8rem;
    color: var(--dark-color);
    opacity: 0.7;
}

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
    transition: all 0.2s ease;
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

.status-badge {
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    display: inline-block;
}

.status-active, .status-paid {
    background: rgba(28, 200, 138, 0.1);
    color: var(--success-color);
}

.status-partial {
    background: rgba(246, 194, 62, 0.1);
    color: var(--warning-color);
}

.status-inactive, .status-unpaid, .status-overdue {
    background: rgba(231, 74, 59, 0.1);
    color: var(--danger-color);
}

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

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--dark-color);
}

.empty-state i {
    font-size: 3rem;
    opacity: 0.5;
    margin-bottom: 1rem;
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
