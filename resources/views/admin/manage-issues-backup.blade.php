@extends('layouts.app')

@section('title', 'Issue Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-exclamation-triangle"></i>
            Issue Management
        </h1>
        <p>Track and manage livestock issues, health concerns, and production problems</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <i class="fas fa-exclamation-triangle stat-icon"></i>
            <h3>{{ $totalIssues }}</h3>
            <p>Total Issues</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-clock stat-icon"></i>
            <h3>{{ $pendingIssues }}</h3>
            <p>Pending Issues</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-exclamation-circle stat-icon"></i>
            <h3>{{ $urgentIssues }}</h3>
            <p>Urgent Issues</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-check-circle stat-icon"></i>
            <h3>{{ $resolvedIssues }}</h3>
            <p>Resolved Issues</p>
        </div>
    </div>

    <!-- Issues Table -->
    <div class="card shadow mb-4 fade-in">
        <div class="card-header">
            <h6>
                <i class="fas fa-list"></i>
                Issues Overview
            </h6>
            <div class="table-controls" style="gap: 1rem; flex-wrap: wrap; align-items: center; display: flex;">
                <div class="search-container" style="min-width: 250px;">
                    <input type="text" class="form-control custom-search" id="customSearch" placeholder="Search issues...">
                </div>
                <div class="export-controls" style="display: flex; gap: 0.5rem; align-items: center;">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addIssueModal">
                        <i class="fas fa-plus"></i> Report Issue
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
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="issuesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Livestock ID</th>
                            <th>Type</th>
                            <th>Breed</th>
                            <th>Issue Type</th>
                            <th>Description</th>
                            <th>Date Reported</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($issues as $issue)
                        <tr>
                            <td><strong>{{ $issue->livestock->tag_number ?? 'N/A' }}</strong></td>
                            <td>{{ $issue->livestock->type ?? 'N/A' }}</td>
                            <td>{{ $issue->livestock->breed ?? 'N/A' }}</td>
                            <td>
                                <span class="issue-type-{{ strtolower($issue->issue_type) }}">
                                    {{ $issue->issue_type }}
                                </span>
                            </td>
                            <td>{{ Str::limit($issue->description, 50) }}</td>
                            <td>{{ $issue->date_reported ? $issue->date_reported->format('Y-m-d') : 'N/A' }}</td>
                            <td>
                                <span class="status-badge status-{{ strtolower($issue->status) }}">
                                    {{ $issue->status }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-primary btn-sm" onclick="viewIssueDetails('{{ $issue->id }}')" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-info btn-sm" onclick="editIssue('{{ $issue->id }}')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteIssue('{{ $issue->id }}')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h5>No issues found</h5>
                                    <p>There are no issues to display at this time.</p>
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

<!-- Add Issue Modal -->
<div class="modal fade" id="addIssueModal" tabindex="-1" role="dialog" aria-labelledby="addIssueLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addIssueLabel">
                    <i class="fas fa-plus"></i> Report New Issue
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.issues.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="livestock_id">Livestock</label>
                                <select class="form-control" id="livestock_id" name="livestock_id" required>
                                    <option value="">Select Livestock</option>
                                    @foreach($livestock as $animal)
                                    <option value="{{ $animal->id }}">{{ $animal->tag_number }} - {{ $animal->type }} ({{ $animal->breed }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="issue_type">Issue Type</label>
                                <select class="form-control" id="issue_type" name="issue_type" required>
                                    <option value="">Select Issue Type</option>
                                    <option value="Health">Health</option>
                                    <option value="Injury">Injury</option>
                                    <option value="Production">Production</option>
                                    <option value="Behavioral">Behavioral</option>
                                    <option value="Environmental">Environmental</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="priority">Priority</label>
                                <select class="form-control" id="priority" name="priority" required>
                                    <option value="">Select Priority</option>
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                    <option value="Urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_reported">Date Reported</label>
                                <input type="date" class="form-control" id="date_reported" name="date_reported" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Describe the issue in detail..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="notes">Additional Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional information..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Report Issue</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Issue Modal -->
<div class="modal fade" id="editIssueModal" tabindex="-1" role="dialog" aria-labelledby="editIssueLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editIssueLabel">
                    <i class="fas fa-edit"></i> Edit Issue
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editIssueForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_livestock_id">Livestock</label>
                                <select class="form-control" id="edit_livestock_id" name="livestock_id" required>
                                    <option value="">Select Livestock</option>
                                    @foreach($livestock as $animal)
                                    <option value="{{ $animal->id }}">{{ $animal->tag_number }} - {{ $animal->type }} ({{ $animal->breed }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_issue_type">Issue Type</label>
                                <select class="form-control" id="edit_issue_type" name="issue_type" required>
                                    <option value="">Select Issue Type</option>
                                    <option value="Health">Health</option>
                                    <option value="Injury">Injury</option>
                                    <option value="Production">Production</option>
                                    <option value="Behavioral">Behavioral</option>
                                    <option value="Environmental">Environmental</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_priority">Priority</label>
                                <select class="form-control" id="edit_priority" name="priority" required>
                                    <option value="">Select Priority</option>
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                    <option value="Urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_status">Status</label>
                                <select class="form-control" id="edit_status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Resolved">Resolved</option>
                                    <option value="Closed">Closed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_notes">Additional Notes</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Issue</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Issue Details Modal -->
<div class="modal fade" id="issueModal" tabindex="-1" aria-labelledby="issueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="issueModalLabel">
                    <i class="fas fa-info-circle"></i>
                    Issue Details
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="issue-details-grid">
                    <div class="detail-item">
                        <div class="detail-label">Livestock ID</div>
                        <div class="detail-value" id="modalLivestockId">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Type</div>
                        <div class="detail-value" id="modalLivestockType">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Breed</div>
                        <div class="detail-value" id="modalLivestockBreed">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Issue Type</div>
                        <div class="detail-value" id="modalIssueType">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Priority</div>
                        <div class="detail-value" id="modalPriority">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Date Reported</div>
                        <div class="detail-value" id="modalIssueDate">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Status</div>
                        <div class="detail-value" id="modalIssueStatus">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Description</div>
                        <div class="detail-value" id="modalDescription">-</div>
                    </div>
                </div>
                <div class="mt-4">
                    <h6>Notes</h6>
                    <p id="modalNotes" class="text-muted">-</p>
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
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this issue? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteIssueForm" method="POST" style="display: inline;">
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

    .issue-type-health {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .issue-type-injury {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .issue-type-production {
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .issue-type-behavioral {
        background: rgba(102, 16, 242, 0.1);
        color: #6610f2;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .issue-type-environmental {
        background: rgba(32, 201, 151, 0.1);
        color: #20c997;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .issue-type-other {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .status-pending {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.2);
    }

    .status-urgent {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.2);
    }

    .status-resolved {
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
        border: 1px solid rgba(25, 135, 84, 0.2);
    }

    .status-in-progress {
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
        border: 1px solid rgba(13, 110, 253, 0.2);
    }

    .status-closed {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
        border: 1px solid rgba(108, 117, 125, 0.2);
    }

    .issue-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .detail-item {
        background: #f8f9fc;
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid var(--primary-color);
    }

    .detail-label {
        font-weight: 600;
        color: var(--dark-color);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .detail-value {
        font-size: 1rem;
        color: #2d3748;
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

<script>
    let dataTable;

    $(document).ready(function() {
        // Debug: Check table structure before initializing DataTable
        const table = $('#issuesTable');
        const headerRow = table.find('thead tr');
        const headerCount = headerRow.find('th').length;
        const firstDataRow = table.find('tbody tr:first');
        const dataCount = firstDataRow.find('td').length;
        
        console.log('Header columns:', headerCount);
        console.log('Data columns:', dataCount);
        
        if (headerCount !== dataCount) {
            console.error('Column count mismatch detected!');
            console.error('Headers:', headerCount, 'Data:', dataCount);
            return; // Don't initialize DataTable if there's a mismatch
        }
        
        // Initialize DataTable
        dataTable = $('#issuesTable').DataTable({
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
                    title: 'Issues_Report',
                    className: 'd-none'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Issues_Report',
                    orientation: 'landscape',
                    pageSize: 'Letter',
                    className: 'd-none'
                },
                {
                    extend: 'print',
                    title: 'Issues Report',
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
            if (dataTable) {
                dataTable.search(this.value).draw();
            }
        });
        
        // Additional debugging
        console.log('Table structure check completed');
        console.log('Issues count:', {{ count($issues ?? []) }});
    });

    function viewIssueDetails(issueId) {
        // Fetch issue data and populate the modal
        fetch(`{{ route('admin.issues.show', ':id') }}`.replace(':id', issueId))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const issue = data.issue;
                
                // Populate the modal
                document.getElementById('modalLivestockId').textContent = issue.livestock?.tag_number || 'N/A';
                document.getElementById('modalLivestockType').textContent = issue.livestock?.type || 'N/A';
                document.getElementById('modalLivestockBreed').textContent = issue.livestock?.breed || 'N/A';
                document.getElementById('modalIssueType').textContent = issue.issue_type || 'N/A';
                document.getElementById('modalPriority').textContent = issue.priority || 'N/A';
                document.getElementById('modalIssueDate').textContent = issue.date_reported || 'N/A';
                document.getElementById('modalIssueStatus').textContent = issue.status || 'N/A';
                document.getElementById('modalDescription').textContent = issue.description || 'N/A';
                document.getElementById('modalNotes').textContent = issue.notes || 'No notes available';
                
                // Show the modal
                $('#issueModal').modal('show');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to load issue data', 'error');
        });
    }

    function editIssue(issueId) {
        // Fetch issue data and populate the edit modal
        fetch(`{{ route('admin.issues.show', ':id') }}`.replace(':id', issueId))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const issue = data.issue;
                
                // Populate the edit form
                document.getElementById('edit_livestock_id').value = issue.livestock_id || '';
                document.getElementById('edit_issue_type').value = issue.issue_type || '';
                document.getElementById('edit_priority').value = issue.priority || '';
                document.getElementById('edit_status').value = issue.status || '';
                document.getElementById('edit_description').value = issue.description || '';
                document.getElementById('edit_notes').value = issue.notes || '';
                
                // Update the form action
                document.getElementById('editIssueForm').action = `{{ route('admin.issues.update', ':id') }}`.replace(':id', issueId);
                
                // Show the modal
                $('#editIssueModal').modal('show');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to load issue data', 'error');
        });
    }

    function deleteIssue(issueId) {
        // Update the delete form action
        document.getElementById('deleteIssueForm').action = `{{ route('admin.issues.destroy', ':id') }}`.replace(':id', issueId);
        
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
</script>
@endpush
