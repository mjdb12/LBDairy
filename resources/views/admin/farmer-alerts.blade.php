@extends('layouts.app')

@section('title', 'Farmer Alerts')

@section('content')
    <div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
        <h1>
            <i class="fas fa-bell"></i>
            Farmer Alerts
        </h1>
        <p>Monitor and respond to alerts issued by farmers about livestock issues</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Total Alerts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAlerts }}</div>
                    </div>
                    <div class="icon" style="color: #18375d !important;">
                        <i class="fas fa-bell fa-2x "></i>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Active Alerts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeAlerts }}</div>
                    </div>
                    <div class="icon" style="color: #18375d !important;">
                        <i class="fas fa-circle-exclamation fa-2x "></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Critical Alerts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $criticalAlerts }}</div>
                    </div>
                    <div class="icon" style="color: #18375d !important;">
                        <i class="fas fa-fire fa-2x "></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Resolved Alerts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $resolvedAlerts }}</div>
                    </div>
                    <div class="icon" style="color: #18375d !important;">
                        <i class="fas fa-check-circle fa-2x "></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

@if($criticalAlerts > 0)
    <div class="alert alert-danger alert-dismissible fade show refresh-notification" role="alert">
        <div class="alert-content">
            <i class="fas fa-times-circle me-2"></i>
            <span>You have <strong>{{ $criticalAlerts }}</strong> critical alerts that require immediate attention.</span>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span>&times;</span>
        </button>
    </div>
@endif

@if($activeAlerts > 0)
    <div class="alert alert-warning alert-dismissible fade show refresh-notification" role="alert">
        <div class="alert-content">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <span>You have <strong>{{ $activeAlerts }}</strong> active alerts awaiting your response.</span>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span>&times;</span>
        </button>
    </div>
@endif



  
    <!-- Main Content -->
     <div class="card shadow mb-4 fade-in">
        <div class="card-body d-flex flex-column flex-sm-row  justify-content-between gap-2 text-center text-sm-start">
            <h6 class="mb-0">
                <i class="fas fa-list"></i>
                        All Farmer Alerts
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
                    <input type="text" class="form-control" placeholder="Search active farmers..." id="farmerSearch">
                </div>
                <div class="d-flex align-items-center justify-content-center flex-nowrap gap-2 action-toolbar">
                    <button class="btn-action btn-action-refresh-alerts" title="Refresh" onclick="refreshAlertsData()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn-action btn-action-tools" title="Tools" ype="button" data-toggle="dropdown">
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
                <table class="table table-bordered table-hover" id="alertsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Farmer</th>
                                    <th>Livestock ID</th>
                                    <th>Topic</th>
                                    <th>Description</th>
                                    <th>Severity</th>
                                    <th>Date Created</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
    </div>


<!-- Smart Detail Modal -->
<div class="modal fade" id="alertDetailsModal" tabindex="-1" role="dialog" aria-labelledby="alertDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

        <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-user fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">Alert Details </h5>
                <p class="text-muted mb-0 small">Below are the complete details of the selected alert.</p>
            </div>

      <!-- Body -->
      <div class="modal-body">
        <div id="alertDetailsContent" >
          <!-- Dynamic details injected here -->
        </div>
      </div>

      <!-- Footer -->

        <div class="modal-footer justify-content-center mt-4">
            <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
        </div>

    </div>
  </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade admin-modal" id="statusUpdateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content smart-form text-center p-4">

            <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-edit fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">Update Alert Status</h5>
                <p class="text-muted mb-0 small">
                    Add your response and update the current alert status.
                </p>
            </div>

            <!-- Form -->
            <form id="statusUpdateForm" method="POST">
                @csrf
                @method('PATCH')

                <div class="form-wrapper text-start mx-auto">
                    
                    <!-- Status Selection -->
                    <div class="col-md-12 mt-3">
                        <label for="status" class="fw-semibold">
                            New Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-control mt-1" id="status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="resolved">Resolved</option>
                            <option value="dismissed">Dismissed</option>
                        </select>
                    </div>
                    <!-- Response Notes -->
                    <div class="col-md-12">
                        <label for="resolution_notes" class="fw-semibold">
                            Response Notes <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control mt-1" id="resolution_notes" name="resolution_notes" rows="4" placeholder="Add your response or resolution notes for the farmer..." style="resize: none;"></textarea>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
                    <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern btn-ok">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                </div>
            </form>
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
let currentAlertId = null;
let currentAction = null;
let alertsTable;
let downloadCounter = 1;

$(document).ready(function () {
    // ✅ Initialize all DataTables
    initializeDataTables();
    
    // ✅ Load data
    // loadAlertsTable();   // removed: function not defined and not needed for static table
    updateStats();

    // ✅ Custom search functionality

    $('#farmerSearch').on('keyup', function() {
        alertsTable.search(this.value).draw();
    });
});


function initializeDataTables() {
    const commonConfig = {
        dom: 'Bfrtip',
        searching: true,
        paging: true,
        info: true,
        ordering: true,
        lengthChange: false,
        pageLength: 10,
        processing: true,
        serverSide: false,
        buttons: [
            {
                extend: 'csvHtml5',
                className: 'buttons-csv d-none'
            },
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'buttons-pdf d-none'
            },
            {
                extend: 'print',
                className: 'buttons-print d-none'
            }
        ],
        language: {
            search: "",
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>No data available</h5><p>There are no records to display at this time.</p></div>',
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        },
        columnDefs: [
            {
                targets: -1,
                orderable: false,
                searchable: false
            }
        ]
    };

    // ✅ Initialize alerts table via Ajax (data-driven)
    alertsTable = $('#alertsTable').DataTable({
        ...commonConfig,
        ajax: {
            url: '/admin/farmer-alerts-data',
            dataSrc: function(json) {
                if (Array.isArray(json)) return json;
                if (json && Array.isArray(json.data)) return json.data;
                if (json && Array.isArray(json.records)) return json.records;
                return [];
            }
        },
        columns: [
            {
                data: null,
                render: function(data) {
                    const name = data.farmer_name || 'Unknown';
                    const email = data.farmer_email || '';
                    return `<strong>${name}</strong><br><small class="text-muted">${email}</small>`;
                }
            },
            {
                data: null,
                render: function(data) {
                    const id = data.livestock_id || 'N/A';
                    const type = data.livestock_type || '';
                    const breed = data.livestock_breed || '';
                    const detail = [type, breed].filter(Boolean).join(' ');
                    return `<strong>${id}</strong><br><small class="text-muted">${detail}</small>`;
                }
            },
            { data: 'topic' },
            {
                data: 'description',
                render: function(text) {
                    if (!text) return '';
                    const tmp = document.createElement('div');
                    tmp.textContent = text;
                    const clean = tmp.textContent || '';
                    return clean.length > 50 ? clean.substring(0, 50) + '…' : clean;
                }
            },
            {
                data: null,
                render: function(data) {
                    const sev = (data.severity || '').toString();
                    const cls = data.severity_badge_class || 'secondary';
                    const label = sev ? sev.charAt(0).toUpperCase() + sev.slice(1) : '';
                    return `<span class="badge badges-${cls}">${label}</span>`;
                }
            },
            { data: 'alert_date' },
            {
                data: null,
                render: function(data) {
                    const st = (data.status || '').toString();
                    const cls = data.status_badge_class || 'secondary';
                    const label = st ? st.charAt(0).toUpperCase() + st.slice(1) : '';
                    return `<span class="badge badge-${cls}">${label}</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data) {
                    const id = data.id;
                    const isActive = (data.status || '') === 'active';
                    return `
                        <div class="action-buttons">
                            <button class="btn-action btn-action-ok" onclick="viewAlertDetails('${id}')" title="View Details">
                                <i class="fas fa-eye"></i>
                                <span>View</span>
                            </button>
                            ${isActive ? `
                            <button class="btn-action btn-action-edit" onclick="markAsResolved('${id}')" title="Mark as Resolved">
                                <i class="fas fa-check"></i>
                                <span>Resolve</span>
                            </button>
                            <button class="btn-action btn-action-deletes" onclick="dismissAlert('${id}')" title="Dismiss Alert">
                                <i class="fas fa-times"></i>
                                <span>Dismiss</span>
                            </button>` : ''}
                        </div>`;
                }
            }
        ],
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Alerts_Report',
                className: 'buttons-csv d-none'
            },
            {
                extend: 'pdfHtml5',
                title: 'Alerts_Report',
                orientation: 'landscape',
                pageSize: 'Letter',
                className: 'buttons-pdf d-none'
            },
            {
                extend: 'print',
                title: 'Alerts_Report',
                className: 'buttons-print d-none'
            }
        ]
    });

    $('.dataTables_filter').hide();
    $('.dt-buttons').hide();

    $('#statusUpdateForm').on('submit', function(e) {
        e.preventDefault();
        submitStatusUpdate();
    });

    $('#farmerSearch').on('keyup', function() {
        alertsTable.search(this.value).draw();
    });
}

// Optional stats refresher (safe stub)
function updateStats() {
    try {
        $.get('/admin/farmer-alerts-stats')
            .done(function(resp) {
                // If you later add IDs to the stat cards, update their text here.
                // Example: $('#totalAlerts').text(resp.data.total);
            })
            .fail(function() {
                // no-op; keep UI static if endpoint fails
            });
    } catch (e) {
        // swallow to avoid blocking other features
    }
}

// ✅ Export functions now work with alertsTable
function exportToCSV() {
    const data = alertsTable.rows({ search: 'applied' }).data().toArray();
    const csvData = [];
    const headers = ['Farmer', 'Livestock ID', 'Topic', 'Description', 'Severity', 'Date Created', 'Status'];
    csvData.push(headers.join(','));

    data.forEach(obj => {
        const farmer = [obj.farmer_name || '', obj.farmer_email ? `(${obj.farmer_email})` : ''].filter(Boolean).join(' ').trim();
        const livestock = [obj.livestock_id || 'N/A', [obj.livestock_type, obj.livestock_breed].filter(Boolean).join(' ')].filter(Boolean).join(' ').trim();
        const topic = (obj.topic || '').toString();
        let description = (obj.description || '').toString().replace(/\s+/g, ' ').trim();
        const severity = (obj.severity || '').toString();
        const dateCreated = (obj.alert_date || '').toString();
        const status = (obj.status || '').toString();

        const row = [farmer, livestock, topic, description, severity, dateCreated, status].map(val => {
            let cell = val || '';
            if (/,|"|\n/.test(cell)) cell = '"' + cell.replace(/"/g, '""') + '"';
            return cell;
        });
        csvData.push(row.join(','));
    });

    const csvContent = csvData.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', `FarmerAlerts_Report_${downloadCounter}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    downloadCounter++;
}

function exportToPDF() {
    try {
        const data = alertsTable.rows({ search: 'applied' }).data().toArray();
        const pdfData = [];
        const headers = ['Farmer', 'Livestock ID', 'Topic', 'Description', 'Severity', 'Date Created', 'Status'];

        data.forEach(obj => {
            const farmer = [obj.farmer_name || '', obj.farmer_email ? `(${obj.farmer_email})` : ''].filter(Boolean).join(' ').trim();
            const livestock = [obj.livestock_id || 'N/A', [obj.livestock_type, obj.livestock_breed].filter(Boolean).join(' ')].filter(Boolean).join(' ').trim();
            const topic = (obj.topic || '').toString();
            let description = (obj.description || '').toString().replace(/\s+/g, ' ').trim();
            const severity = (obj.severity || '').toString();
            const dateCreated = (obj.alert_date || '').toString();
            const status = (obj.status || '').toString();
            pdfData.push([farmer, livestock, topic, description, severity, dateCreated, status]);
        });

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape', 'mm', 'a4');
        doc.setFontSize(18);
        doc.text('Admin Farmer Alerts Report', 14, 22);
        doc.setFontSize(12);
        doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 14, 32);

        doc.autoTable({
            head: [headers],
            body: pdfData,
            startY: 40,
            styles: { fontSize: 8, cellPadding: 2 },
            headStyles: { fillColor: [24, 55, 93], textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [245, 245, 245] }
        });

        doc.save(`Admin_FarmerAlertsReport_${downloadCounter}.pdf`);
        downloadCounter++;
        showNotification('PDF exported successfully!', 'success');
    } catch (error) {
        console.error('Error generating PDF:', error);
        showNotification('Error generating PDF. Please try again.', 'danger');
    }
}

function exportToPNG() {
    const originalTable = document.getElementById('alertsTable');
    const tempTable = originalTable.cloneNode(true);
    
    const headerRow = tempTable.querySelector('thead tr');
    if (headerRow) {
        const lastHeaderCell = headerRow.lastElementChild;
        if (lastHeaderCell) {
            lastHeaderCell.remove();
        }
    }
    
    const dataRows = tempTable.querySelectorAll('tbody tr');
    dataRows.forEach(row => {
        const lastDataCell = row.lastElementChild;
        if (lastDataCell) {
            lastDataCell.remove();
        }
    });
    
    tempTable.style.position = 'absolute';
    tempTable.style.left = '-9999px';
    tempTable.style.top = '-9999px';
    document.body.appendChild(tempTable);
    
    html2canvas(tempTable, {
        scale: 2,
        backgroundColor: '#ffffff',
        width: tempTable.offsetWidth,
        height: tempTable.offsetHeight
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = `FarmerAlerts_Report_${downloadCounter}.png`;
        link.href = canvas.toDataURL("image/png");
        link.click();
        
        downloadCounter++;
        document.body.removeChild(tempTable);
    }).catch(error => {
        console.error('Error generating PNG:', error);
        if (document.body.contains(tempTable)) {
            document.body.removeChild(tempTable);
        }
        showNotification('Error generating PNG export', 'danger');
    });
}

function printTable() {
    try {
        const data = alertsTable.rows({ search: 'applied' }).data().toArray();
        if (!data || data.length === 0) {
            showNotification('No data available to print', 'warning');
            return;
        }

        let printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #18375d; margin-bottom: 5px;">Farmer Alerts Report</h1>
                    <p style="color: #666; margin: 0;">Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                <table border="3" style="border-collapse: collapse; width: 100%; border: 3px solid #000;">
                    <thead>
                        <tr>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Farmer</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Livestock ID</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Topic</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Description</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Severity</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Date Created</th>
                            <th style="border: 3px solid #000; padding: 10px; background-color: #f2f2f2; text-align: left;">Status</th>
                        </tr>
                    </thead>
                    <tbody>`;

        data.forEach(obj => {
            const farmer = [obj.farmer_name || '', obj.farmer_email ? `(${obj.farmer_email})` : ''].filter(Boolean).join(' ').trim();
            const livestock = [obj.livestock_id || 'N/A', [obj.livestock_type, obj.livestock_breed].filter(Boolean).join(' ')].filter(Boolean).join(' ').trim();
            const topic = (obj.topic || '').toString();
            const desc = ((obj.description || '').toString().replace(/\s+/g, ' ').trim());
            const severity = (obj.severity || '').toString();
            const dateCreated = (obj.alert_date || '').toString();
            const status = (obj.status || '').toString();
            printContent += `
                <tr>
                    <td style="border: 3px solid #000; padding: 10px; text-align: left;">${farmer}</td>
                    <td style="border: 3px solid #000; padding: 10px; text-align: left;">${livestock}</td>
                    <td style="border: 3px solid #000; padding: 10px; text-align: left;">${topic}</td>
                    <td style="border: 3px solid #000; padding: 10px; text-align: left;">${desc}</td>
                    <td style="border: 3px solid #000; padding: 10px; text-align: left;">${severity}</td>
                    <td style="border: 3px solid #000; padding: 10px; text-align: left;">${dateCreated}</td>
                    <td style="border: 3px solid #000; padding: 10px; text-align: left;">${status}</td>
                </tr>`;
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
            window.openPrintWindow(printContent, 'Farmer Alerts Report');
        } else {
            window.print();
        }
    } catch (error) {
        console.error('Error in print function:', error);
        showNotification('Error generating print. Please try again.', 'danger');
        try {
            alertsTable.button('.buttons-print').trigger();
        } catch (fallbackError) {
            console.error('Fallback print also failed:', fallbackError);
            showNotification('Print failed. Please try again.', 'danger');
        }
    }
}


function viewAlertDetails(alertId) {
    currentAlertId = alertId;
    loadAlertDetails(alertId);
    $('#alertDetailsModal').modal('show');
}

function loadAlertDetails(alertId) {
    $.ajax({
        url: `/admin/farmer-alerts/${alertId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const alert = response.alert;

                $('#alertDetailsContent').html(`
                    <div class="row">
                        <!-- 🔹 Alert Information -->
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d !important; font-weight: 600;"><i class="fas fa-id-card me-2"></i> Alert Information</h6>
                            <p class="text-left"><strong>Topic:</strong> ${alert.topic || 'N/A'}</p>
                            <p class="text-left"><strong>Status:</strong> 
                                <span class="badge badge-${alert.status_badge_class || 'secondary'}">
                                    ${alert.status || 'N/A'}
                                </span>
                            </p>
                            <p class="text-left"><strong>Severity:</strong> 
                                <span class="badge badge-${alert.severity_badge_class || 'secondary'}">
                                    ${alert.severity || 'N/A'}
                                </span>
                            </p>
                            <p class="text-left"><strong>Created:</strong> 
                                ${alert.alert_date ? new Date(alert.alert_date).toLocaleString() : 'N/A'}
                            </p>
                            ${alert.resolved_at ? `
                            <p class="text-left"><strong>Resolved:</strong> 
                                ${new Date(alert.resolved_at).toLocaleString()}
                            </p>` : ''}
                        </div>

                        <!-- 🔹 Farmer Information -->
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d !important; font-weight: 600;"><i class="fas fa-info-circle me-2"></i> Farmer Information</h6>
                            <p class="text-left"><strong>Name:</strong> ${alert.issued_by ? alert.issued_by.name : 'N/A'}</p>
                            <p class="text-left"><strong>Email:</strong> ${alert.issued_by ? alert.issued_by.email : 'N/A'}</p>
                            <p class="text-left"><strong>Phone:</strong> ${alert.issued_by ? alert.issued_by.phone : 'N/A'}</p>
                            <p class="text-left"><strong>Farm:</strong> 
                                ${alert.livestock && alert.livestock.farm ? alert.livestock.farm.name : 'N/A'}
                            </p>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <!-- 🔹 Livestock Information -->
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d !important; font-weight: 600;"><i class="fas fa-tractor me-2"></i> Livestock Information</h6>
                            <p class="text-left"><strong>ID:</strong> ${alert.livestock ? alert.livestock.livestock_id : 'N/A'}</p>
                            <p class="text-left"><strong>Type:</strong> ${alert.livestock ? alert.livestock.type : 'N/A'}</p>
                            <p class="text-left"><strong>Breed:</strong> ${alert.livestock ? alert.livestock.breed : 'N/A'}</p>
                            <p class="text-left"><strong>Age:</strong> 
                                ${alert.livestock && alert.livestock.age ? alert.livestock.age + ' years' : 'N/A'}
                            </p>
                            <p class="text-left"><strong>Health Status:</strong> 
                                ${alert.livestock ? alert.livestock.health_status : 'N/A'}
                            </p>
                        </div>

                        <!-- 🔹 Alert Details -->
                        <div class="col-md-6">
                            <h6 class="mb-3" style="color: #18375d !important; font-weight: 600;"><i class="fas fa-clipboard-list me-2"></i> Alert Details</h6>
                            <p class="text-left"><strong>Description:</strong> ${alert.description || 'No description provided.'}</p>
                            ${alert.resolution_notes ? `
                            <p class="text-left"><strong>Resolution Notes:</strong> ${alert.resolution_notes}</p>
                            ` : ''}
                        </div>
                    </div>
                `);

                $('#alertDetailsModal').modal('show');
            } else {
                showNotification('Error loading alert details', 'danger');
            }
        },
        error: function() {
            showNotification('Error loading alert details', 'danger');
        }
    });
}



function markAsResolved(alertId) {
    currentAlertId = alertId;
    currentAction = 'resolved';
    $('#statusUpdateForm').attr('action', `/admin/farmer-alerts/${alertId}/status`);
    $('#status').val('resolved');
    $('#statusUpdateModal').modal('show');
}

function dismissAlert(alertId) {
    currentAlertId = alertId;
    currentAction = 'dismissed';
    $('#statusUpdateForm').attr('action', `/admin/farmer-alerts/${alertId}/status`);
    $('#status').val('dismissed');
    $('#statusUpdateModal').modal('show');
}

function submitStatusUpdate() {
    const payload = {
        _method: 'PATCH',
        status: $('#statusUpdateForm select[name="status"]').val(),
        resolution_notes: $('#statusUpdateForm textarea[name="resolution_notes"]').val(),
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    $.ajax({
        url: $('#statusUpdateForm').attr('action'),
        method: 'POST',
        data: payload,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function(response) {
            showNotification('Alert status updated successfully!', 'success');
            $('#statusUpdateModal').modal('hide');
            location.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors || {};
                if (!Object.keys(errors).length) {
                    showNotification('Validation failed. Please check required fields.', 'danger');
                    return;
                }
                Object.keys(errors).forEach(field => {
                    showNotification(errors[field][0], 'danger');
                });
            } else {
                showNotification('Error updating alert status', 'danger');
            }
        }
    });
}

// Refresh Admins Table
function refreshAlertsData() {
    const refreshBtn = document.querySelector('.btn-action-refresh-alerts');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    refreshBtn.disabled = true;

    // Use unique flag for admins
    sessionStorage.setItem('showRefreshNotificationAlerts', 'true');

    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Check notifications after reload
$(document).ready(function() {
    if (sessionStorage.getItem('showRefreshNotificationAlerts') === 'true') {
        sessionStorage.removeItem('showRefreshNotificationAlerts');
        setTimeout(() => {
            showNotification('Farmer Alerts data refreshed successfully!', 'success');
        }, 500);
    }
});

function showNotification(message, type) {
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


</script>
@endpush

@push('styles')
<style>
       /* ===== Badge Colors ===== */
.badges-danger {
    background-color: #dc3545; /* red for urgent */
    color: #ffffffff; /* better contrast on yellow */
}

.badges-warning {
    background-color: #fca700; /* yellow for high */
    color: #ffffffff; /* better contrast on yellow */
}

.badges-info {
    background-color: #17a2b8; /* blue for medium */
    color: #ffffffff; /* better contrast on yellow */
}

.badges-success {
    background-color: #28a745; /* green for low */
    color: #ffffffff; /* better contrast on yellow */
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
#statusUpdateModal form {
  text-align: left;
}

#statusUpdateModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#statusUpdateModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#statusUpdateModal .form-control,
#statusUpdateModal select.form-control,
#statusUpdateModal textarea.form-control {
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
#statusUpdateModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#statusUpdateModal .form-control:focus {
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

/* ============================
   FOOTER & ALIGNMENT
   ============================ */
#addLivestockModal .modal-footer {
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

  #addLivestockModal .form-control {
    font-size: 14px;
  }

  #editLivestockModal .form-control {
    font-size: 14px;
  }
   #issueAlertModal .form-control {
    font-size: 14px;
  }

  .btn-ok,
  .btn-delete,
  .btn-approves {
    width: 100%;
    margin-top: 0.5rem;
  }
}
    /* User Details Modal Styling */
    #alertDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #alertDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #alertDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #alertDetailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #alertDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #alertDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #alertDetailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }
    /* Style all labels inside form Modal */
    #alertDetailsModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }
    /* SMART DETAIL MODAL TEMPLATE */
.smart-detail .modal-content {
    border-radius: 1.5rem;
    border: none;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12);
    background-color: #fff;
    transition: all 0.3s ease-in-out;
}

/* Center alignment for header section */
.smart-detail .modal-header,
.smart-detail .modal-footer {
    text-align: center;
}

/* Icon Header */
.smart-detail .icon-circle {
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
.smart-detail h5 {
    color: #18375d;
    font-weight: 700;
    margin-bottom: 0.4rem;
    letter-spacing: 0.5px;
}

.smart-detail p {
    color: #6b7280;
    font-size: 1rem;
    margin-bottom: 1.8rem;
    line-height: 1.6;
    text-align: left; /* ensures proper centering */
}

/* MODAL BODY */
.smart-detail .modal-body {
    background: #ffffff;
    padding: 3rem 3.5rem; /* more spacious layout */
    border-radius: 1rem;
    max-height: 88vh; /* taller for longer content */
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

/* Wider modal container */
.smart-detail .modal-dialog {
    max-width: 92%; /* slightly wider modal */
    width: 100%;
    margin: 1.75rem auto;
}

/* Detail Section */
.smart-detail .detail-wrapper {
    background: #f9fafb;
    border-radius: 1.25rem;
    padding: 2.25rem; /* more inner padding */
    font-size: 1rem;
    line-height: 1.65;
}

/* Detail Rows */
.smart-detail .detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px dashed #ddd;
    padding: 0.6rem 0;
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
#livestockDetailsModal .modal-footer {
    text-align: center;
    border-top: 1px solid #e5e7eb;
    padding-top: 1.5rem;
    margin-top: 2rem;
}

/* RESPONSIVE ADJUSTMENTS */
@media (max-width: 992px) {
    .smart-detail .modal-dialog {
        max-width: 95%;
    }

    .smart-detail .modal-body {
        padding: 2rem;
        max-height: 82vh;
    }

    .smart-detail .detail-wrapper {
        padding: 1.5rem;
        font-size: 0.95rem;
    }

    .smart-detail p {
        text-align: center;
        font-size: 0.95rem;
    }
}

@media (max-width: 576px) {
    .smart-detail .modal-body {
        padding: 0.5rem;
        max-height: 95vh;
    }

    .smart-detail .detail-wrapper {
        padding: 1.25rem;
    }

    .smart-detail .detail-row {
        flex-direction: column;
        text-align: left;
        gap: 0.3rem;
    }

    .smart-detail .detail-value {
        text-align: left;
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
    /* CRITICAL FIX FOR DROPDOWN TEXT CUTTING */
    .admin-modal select.form-control,
    .modal.admin-modal select.form-control,
    .admin-modal .modal-body select.form-control {
        min-width: 250px !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding: 0.75rem 2rem 0.75rem 0.75rem !important;
        white-space: nowrap !important;
        text-overflow: clip !important;
        overflow: visible !important;
        font-size: 0.875rem !important;
        line-height: 1.5 !important;
    }
    
    /* Ensure columns don't constrain dropdowns */
    .admin-modal .col-md-6 {
        min-width: 280px !important;
        overflow: visible !important;
    }

    /* User Details Modal Styling */
    #statusUpdateModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #statusUpdateModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #statusUpdateModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #statusUpdateModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #statusUpdateModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #statusUpdateModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #statusUpdateModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }


    /* Style all labels inside form Modal */
    #statusUpdateModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }

        /* Farmer Info Card */
    .farmer-info p {
        margin: 0.5rem 0;
        font-size: 0.95rem;
        color: #495057;
        display: flex;
        align-items: center;
    }

    /* Labels */
    .farmer-info .label {
        font-weight: 600;
        color: #18375d;
        min-width: 90px; /* keeps labels aligned */
        display: inline-block;
    }

    /* Card adjustments */
    .card .card-header {
        border-radius: 0.5rem 0.5rem 0 0;
        font-weight: 600;
    }

    .card .card-body {
        background: #fdfdfd;
        border-radius: 0 0 0.5rem 0.5rem;
    }

        /* Alert Info Card */
    .alert-info-card p {
        margin: 0.5rem 0;
        font-size: 0.95rem;
        color: #495057;
        display: flex;
        align-items: center;
    }

    .alert-info-card .label {
        font-weight: 600;
        color: #18375d;
        min-width: 90px;
        display: inline-block;
    }

    /* Custom Badges */
    .badge {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.35em 0.65em;
        border-radius: 0.5rem;
    }

    .badge-success {
        background-color: #39a400;
        color: #fff;
    }

    .badge-danger {
        background-color: #dc3545;
        color: #fff;
    }

    .badge-warning {
        background-color: #f39c12;
        color: #fff;
    }

    .badge-info {
        background-color: #17a2b8;
        color: #fff;
    }

    /* Livestock & Alert Details Cards */
    .livestock-info-card p,
    .alert-details-card p {
        margin: 0.5rem 0;
        font-size: 0.95rem;
        color: #495057;
        display: flex;
        align-items: center;
    }

    .livestock-info-card .label,
    .alert-details-card .label {
        font-weight: 600;
        color: #18375d;
        min-width: 120px;
        display: inline-block;
    }

    /* Add spacing and readability for Alert Details */
    .alert-details-card {
        line-height: 1.5;
    }

    .alert-details-card .label {
        display: block;
        margin-bottom: 0.25rem;
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
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.075);
    }
    
    .badge {
        font-size: 0.75em;
        padding: 0.375em 0.75em;
    }
    
    .badge-pill {
        border-radius: 50rem;
        padding-left: 0.75em;
        padding-right: 0.75em;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Ensure all role badges have identical pill shape */
    .badge-danger.badge-pill,
    .badge-primary.badge-pill,
    .badge-success.badge-pill,
    .badge-secondary.badge-pill {
        border-radius: 50rem !important;
        padding: 0.5em 1em !important;
        font-size: 0.7em;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        line-height: 1.2;
        display: inline-block;
        white-space: nowrap;
        vertical-align: baseline;
    }
    
    /* Force pill shape override for any conflicting styles */
    .badge.badge-pill {
        border-radius: 50rem !important;
        padding: 0.5em 1em !important;
    }
    
    /* Make admin badge (primary) look identical to superadmin pill shape */
    .badge-primary {
        border-radius: 50rem !important;
        padding: 0.5em 1em !important;
        font-size: 0.7em;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        line-height: 1.2;
        display: inline-block;
        white-space: nowrap;
        vertical-align: baseline;
    }
    
    .btn-group .btn {
        margin-right: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .gap-2 {
        gap: 0.5rem !important;
    }
    
    .badge-sm {
        font-size: 0.6em;
        padding: 0.2em 0.4em;
    }
    
    .badge-success .fas.fa-circle {
        animation: pulse 2s infinite;
    }
    
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    /* Apply consistent buttons */
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

 .btn-action-ok {
    background-color: white;
    border: 1px solid #18375d !important;
    color: #18375d; /* blue text */
}

.btn-action-ok:hover {
    background-color: #18375d !important;/* yellow on hover */
    border: 1px solid #18375d !important;
    color: white !important;
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

.btn-action-refresh-alerts, .btn-action-refresh-admins {
    background-color: white !important;
    border: 1px solid #fca700 !important;
    color: #fca700 !important; /* blue text */
}
    
.btn-action-refresh-alerts:hover, .btn-action-refresh-admins:hover {
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
 .btn-action-edit {
    background-color: white !important;
    border: 1px solid #387057 !important;
    color: #387057 !important;/* blue text */
}

.btn-action-edit:hover {
    background-color: #387057 !important;/* yellow on hover */
    border: 1px solid #387057 !important;
    color: white !important;
}
    
    .btn-action-edit:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-view-livestock, .btn-action-report-livestock {
        background-color: #18375d;
        border-color: #18375d;
        color: white;
    }
    
    .btn-action-view-livestock:hover, .btn-action-report-livestock:hover {
        background-color: #e69500;
        border-color: #e69500;
        color: white;
    }

    /* Header action buttons styling to match Edit/Delete buttons */
    .btn-action-add {
        background-color: #387057;
        border-color: #387057;
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
        background-color: #6c757d !important;
        border-color: #6c757d !important;
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
    
    .btn-action-refresh-, .btn-action-refresh- {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-refresh-:hover, .btn-action-refresh-:hover {
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

    .btn-action-refresh-alerts, .btn-action-refresh-farmers {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-refresh-alerts:hover, .btn-action-refresh-farmers:hover {
        background-color: #e69500;
        border-color: #e69500;
        color: white;
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
    
    /* Apply consistent styling for Alerts table */
#alertsTable th,
#alertsTable td {
    vertical-align: middle;
    padding: 0.75rem;
    text-align: center;
    border: 1px solid #dee2e6;
    white-space: nowrap;
    overflow: visible;
}

/* Ensure table headers have consistent styling */
#alertsTable thead th {
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
#alertsTable thead th.sorting,
#alertsTable thead th.sorting_asc,
#alertsTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#alertsTable thead th::after {
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
#alertsTable thead th.sorting::after,
#alertsTable thead th.sorting_asc::after,
#alertsTable thead th.sorting_desc::after {
    display: none;
}

/* Allow table to scroll horizontally if too wide */
.table-responsive {
    overflow-x: auto;
}

/* Make table cells wrap instead of forcing them all inline */
#alertsTable td, 
#alertsTable th {
    white-space: normal !important;  /* allow wrapping */
    vertical-align: middle;
}

/* Make sure action buttons don’t overflow */
#alertsTable td .btn-group {
    display: flex;
    flex-wrap: wrap; /* buttons wrap if not enough space */
    gap: 0.25rem;    /* small gap between buttons */
}

#alertsTable td .btn-action {
    flex: 1 1 auto; /* allow buttons to shrink/grow */
    min-width: 90px; /* prevent too tiny buttons */
    text-align: center;
}


     /* User Details Modal Styling */
    #alertDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #alertDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #alertDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #alertDetailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #alertDetailsModal .modal-body h6 {
        color: #ffffffff !important;
        font-weight: 600 !important;
    }
    
    #alertDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #alertDetailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }


    /* Style all labels inside form Modal */
    #alertDetailsModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }

/* DataTables Pagination Styling */
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
    
    /* Ensure pagination container is properly positioned */
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
    
    #farmersTable th,
    #farmersTable td {
        vertical-align: middle;
        padding: 0.75rem;
        text-align: center;
        border: 1px solid #dee2e6;
        white-space: nowrap;
        overflow: visible;
    }
    
    /* Ensure all table headers have consistent styling */
    #farmersTable thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: bold;
        color: #495057;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 0.75rem;
        text-align: left;
        vertical-align: middle;
        position: relative;
        white-space: nowrap;
    }
    
    /* Fix DataTables sorting button overlap */
    #farmersTable thead th.sorting,
    #farmersTable thead th.sorting_asc,
    #farmersTable thead th.sorting_desc {
        padding-right: 2rem !important;
    }
    
    /* Ensure proper spacing for sort indicators */
    #farmersTable thead th::after {
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
    #farmersTable thead th.sorting::after,
    #farmersTable thead th.sorting_asc::after,
    #farmersTable thead th.sorting_desc::after {
        display: none;
    }
    
    
    /* Table-responsive wrapper positioning - match active admins spacing */
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
    
    /* Fix pagination positioning for wide tables - match active admins spacing */
    .table-responsive .dataTables_wrapper .dataTables_paginate {
        position: relative;
        width: 100%;
        text-align: left;
        margin: 1rem 0;
        left: 0;
        right: 0;
    }
    
    /* User ID link styling - superadmin theme */
    .user-id-link {
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
    
    .user-id-link:hover {
        color: #fff;
        background-color: #18375d;
        border-color: #18375d;
        text-decoration: none;
    }
    
    .user-id-link:active {
        color: #fff;
        background-color: #122a4e;
        border-color: #122a4e;
    }
    
    /* Action buttons styling to match active admins table */
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
        font-weight: 500;
        border: 1px solid transparent;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        white-space: nowrap;
    }
    
    .btn-action-delete {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .btn-action-delete:hover {
        background-color: #c82333;
        border-color: #c82333;
        color: white;
    }
    
    /* Ensure table has enough space for actions column */
    .table th:last-child,
    .table td:last-child {
        min-width: 200px;
        width: auto;
        text-align: center;
        vertical-align: middle;
    }


    /* User Details Modal Styling */
    #userDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #userDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #userDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #userDetailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #userDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #userDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #userDetailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }
    
    /* Responsive adjustments */
    @media (max-width: 1200px) {
        .action-buttons {
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .btn-action {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
    }
    /* Custom styles for farmer management */
    
    .card-header .btn-group {
        margin-left: 0.5rem;
    }
    
    .card-header .input-group {
        margin-bottom: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .card-header .d-flex {
            flex-direction: column !important;
        }
        
        .card-header .btn-group {
            margin-left: 0;
            margin-top: 0.5rem;
        }
        
        .card-header .input-group {
            margin-bottom: 0.5rem;
            max-width: 100% !important;
        }
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.075);
    }
    
    .badge {
        font-size: 0.75em;
        padding: 0.375em 0.75em;
    }
    
    .btn-group .btn {
        margin-right: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .gap-2 {
        gap: 0.5rem !important;
    }
    
    /* Status badges */
    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status-approved {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .status-active {
        background-color: #d1ecf1;
        color: #0c5460;
    }
    
    /* Table-responsive wrapper positioning */
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
    
    /* Fix pagination positioning for wide tables - match active admins spacing */
    .table-responsive .dataTables_wrapper .dataTables_paginate {
        position: relative;
        width: 100%;
        text-align: left;
        margin: 1rem 0;
        left: 0;
        right: 0;
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
    
</style>
@endpush
