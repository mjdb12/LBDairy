@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers - Calendar & Schedule')
@push('styles')
<style>
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
    
    
    /* Ensure table has enough space for actions column */
    .table th:last-child,
    .table td:last-child {
        min-width: 200px;
        width: auto;
        text-align: center;
        vertical-align: middle;
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
    /* Apply consistent styling for Pending Farmers and Active Farmers tables */
#salesTable th,
#salesTable td,
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
#salesTable thead th,
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
#salesTable thead th.sorting,
#salesTable thead th.sorting_asc,
#salesTable thead th.sorting_desc,
#activeFarmersTable thead th.sorting,
#activeFarmersTable thead th.sorting_asc,
#activeFarmersTable thead th.sorting_desc {
    padding-right: 2rem !important;
}

/* Ensure proper spacing for sort indicators */
#salesTable thead th::after,
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
#salesTable thead th.sorting::after,
#salesTable thead th.sorting_asc::after,
#salesTable thead th.sorting_desc::after,
#activeFarmersTable thead th.sorting::after,
#activeFarmersTable thead th.sorting_asc::after,
#activeFarmersTable thead th.sorting_desc::after {
    display: none;
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

    .btn-action-history {
        background-color: #5a6268;
        border-color: #5a6268;
        color: white;
    }
    
    .btn-action-history:hover {
        background-color: #e69500;
        border-color: #e69500;
        color: white;
    }

    .btn-action-import {
        background-color: #1b3043;
        border-color: #1b3043;
        color: white;
    }
    
    .btn-action-import:hover {
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
/* CRITICAL FIX FOR DROPDOWN TEXT CUTTING */
    .farmer-modal select.form-control,
    .modal.farmer-modal select.form-control,
    .farmer-modal .modal-body select.form-control {
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
    .farmer-modal .col-md-6 {
        min-width: 280px !important;
        overflow: visible !important;
    }

     #addLivestockDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #addLivestockDetailsModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #addLivestockDetailsModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #addLivestockDetailsModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #addLivestockDetailsModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #addLivestockDetailsModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #addLivestockDetailsModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #addLivestockDetailsModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }
    /* */
    #addEventModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #addEventModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #addEventModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #addEventModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #addEventModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #addEventModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #addEventModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #addEventModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }
</style>
@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-calendar-alt"></i>
        Farm Activity Calendar
    </h1>
    <p>Manage your farm schedule, track activities, and organize daily operations</p>
</div>

<div class="row">
    <!-- Quick Stats & Notes Sidebar -->
    <div class="col-md-3">
        <!-- Quick Stats Card -->
        <div class="card shadow mb-4 fade-in">
            <div class="card-header">
                <h6>
                    <i class="fas fa-chart-pie"></i>
                    Quick Stats
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="h4  mb-0" id="totalEvents">0</div>
                    <small class="text-muted">Total Events</small>
                </div>
                <div class="text-center mb-3">
                    <div class="h4 text-success mb-0" id="completedEvents">0</div>
                    <small class="text-muted">Completed</small>
                </div>
                <div class="text-center mb-3">
                    <div class="h4 text-warning mb-0" id="pendingEvents">0</div>
                    <small class="text-muted">Pending</small>
                </div>
                <div class="text-center">
                    <div class="h4 text-info mb-0" id="todayEvents">0</div>
                    <small class="text-muted">Today</small>
                </div>
            </div>
        </div>

        <!-- Quick Notes Card -->
        <div class="card shadow mb-4 fade-in">
            <div class="card-header">
                <h6>
                    <i class="fas fa-sticky-note"></i>
                    Quick Notes
                </h6>
            </div>
            <div class="card-body">
                <form id="quickNoteForm">
                    <div class="form-group">
                        <textarea class="form-control" id="quickNoteText" rows="3" placeholder="Add a quick note..." maxlength="200"></textarea>
                    </div>
                    <button type="submit" class="btn-action btn-action-ok btn-sm btn-block">
                        <i class="fas fa-plus mr-1"></i> Add Note
                    </button>
                </form>
                <hr>
                <ul class="list-group list-group-flush" id="quickNotesList">
                    <li class="list-group-item text-center text-muted">No notes yet</li>
                </ul>
            </div>
        </div>


    </div>

    <!-- Calendar Section -->
    <div class="col-md-9">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header">
                <h6>
                    <i class="fas fa-calendar-week"></i>
                    Farm Activity Calendar
                </h6>
                <div class="d-flex gap-2">
                    <button class="btn-action btn-action-edits " data-toggle="modal" data-target="#addEventModal" type="button">
                        <i class="fas fa-plus"></i> Add Event
                    </button>
                    <button class="btn-action btn-action-history " onclick="exportCalendar()" type="button">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <!-- THE CALENDAR -->
                <div id="calendar" style="min-height: 600px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" role="dialog" aria-labelledby="addEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="eventForm" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventModalLabel">
                        <i class="fas fa-calendar-plus"></i>
                        Add New Event
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="event_title">Event Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="event_title" name="title" required maxlength="100" placeholder="Enter event title">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="event_start">Start Date & Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="event_start" name="start" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="event_end">End Date & Time</label>
                                <input type="datetime-local" class="form-control" id="event_end" name="end">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="event_category">Category</label>
                                <select class="form-control" id="event_category">
                                    <option value="feeding">Feeding</option>
                                    <option value="health">Health & Medical</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="milking">Milking</option>
                                    <option value="breeding">Breeding</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="event_priority">Priority</label>
                                <select class="form-control" id="event_priority">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="event_desc">Description</label>
                        <textarea class="form-control" id="event_desc" name="description" rows="3" maxlength="500" placeholder="Optional event description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-action btn-secondary" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn-action btn-action-edits">
                        Save Event
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
let calendar;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize calendar
    initializeCalendar();
    
    // Initialize quick notes
    initializeQuickNotes();
    

    
    // Update stats
    updateStats();
    
    // Initialize event form
    initializeEventForm();
});

function initializeCalendar() {
    var calendarEl = document.getElementById('calendar');
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        initialView: 'dayGridMonth',
        height: 'auto',
        events: '/calendar/events',
        editable: true,
        droppable: false,
        eventResizableFromStart: true,
        eventDurationEditable: true,
        eventStartEditable: true,
        
        // Event interactions
        eventClick: function(info) {
            handleEventClick(info);
        },
        
        eventDrop: function(info) {
            updateEvent(info.event);
        },
        
        eventResize: function(info) {
            updateEvent(info.event);
        },
        
        // Date click
        dateClick: function(info) {
            openAddEventModal(info.dateStr);
        },
        
        // Event loading
        eventDidMount: function(info) {
            // Add tooltip with event details
            $(info.el).tooltip({
                title: info.event.title + (info.event.extendedProps.description ? '<br>' + info.event.extendedProps.description : ''),
                html: true,
                placement: 'top',
                trigger: 'hover'
            });
        }
    });
    
    calendar.render();
}





function initializeQuickNotes() {
    const form = document.getElementById('quickNoteForm');
    const noteInput = document.getElementById('quickNoteText');
    const notesList = document.getElementById('quickNotesList');

    function loadNotes() {
        notesList.innerHTML = '';
        const notes = JSON.parse(localStorage.getItem('quickNotes') || '[]');
        
        if (notes.length === 0) {
            notesList.innerHTML = '<li class="list-group-item text-center text-muted">No notes yet</li>';
            return;
        }
        
        notes.forEach((note, idx) => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-start';
            li.innerHTML = `
                <div class="flex-grow-1">
                    <small class="text-muted">${note.date}</small>
                    <div>${note.text.replace(/</g, "&lt;").replace(/>/g, "&gt;")}</div>
                </div>
                <button class="btn-action btn-sm btn-outline-danger ml-2" onclick="deleteNote(${idx})" title="Delete note">
                    <i class="fas fa-trash-alt"></i>
                </button>
            `;
            notesList.appendChild(li);
        });
    }

    window.deleteNote = function(index) {
        const notes = JSON.parse(localStorage.getItem('quickNotes') || '[]');
        notes.splice(index, 1);
        localStorage.setItem('quickNotes', JSON.stringify(notes));
        loadNotes();
        showNotification('Note deleted!', 'success');
    };

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const noteText = noteInput.value.trim();
        if (noteText) {
            const notes = JSON.parse(localStorage.getItem('quickNotes') || '[]');
            const newNote = {
                text: noteText,
                date: new Date().toLocaleDateString()
            };
            notes.unshift(newNote);
            localStorage.setItem('quickNotes', JSON.stringify(notes));
            noteInput.value = '';
            loadNotes();
            showNotification('Note added!', 'success');
        }
    });

    loadNotes();
}

function initializeEventForm() {
    document.getElementById('eventForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        var title = document.getElementById('event_title').value.trim();
        var start = document.getElementById('event_start').value;
        var end = document.getElementById('event_end').value;
        var category = document.getElementById('event_category').value;
        var priority = document.getElementById('event_priority').value;
        var description = document.getElementById('event_desc').value.trim();
        
        if (!title || !start) {
            showNotification('Please fill in all required fields!', 'error');
            return;
        }
        
        // Prepare data for API
        var eventData = {
            title: title,
            start: start,
            end: end || start,
            priority: priority,
            description: description,
            category: category
        };
        
        // Send to API
        fetch('/calendar/events', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(eventData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh calendar
                calendar.refetchEvents();
                
                // Close modal and reset form
                $('#addEventModal').modal('hide');
                document.getElementById('eventForm').reset();
                
                showNotification('Event added successfully!', 'success');
                updateStats();
            } else {
                showNotification('Error adding event!', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error adding event!', 'error');
        });
    });
}



function handleEventClick(info) {
    // Show event details in a modal or tooltip
    var event = info.event;
    var details = `
        <strong>${event.title}</strong><br>
        <small class="text-muted">${event.start.toLocaleString()}</small><br>
        ${event.extendedProps.description ? '<br>' + event.extendedProps.description : ''}<br>
        <span class="badge badge-${getPriorityBadgeClass(event.extendedProps.priority)}">${event.extendedProps.priority}</span>
        <span class="badge badge-${getStatusBadgeClass(event.extendedProps.status)}">${event.extendedProps.status}</span>
    `;
    
    showNotification(details, 'info');
}

function updateEvent(event) {
    var eventData = {
        title: event.title,
        start: event.start.toISOString(),
        end: event.end ? event.end.toISOString() : null,
        priority: event.extendedProps.priority,
        description: event.extendedProps.description,
        status: event.extendedProps.status
    };
    
    fetch(`/calendar/events/${event.id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(eventData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Event updated successfully!', 'success');
            updateStats();
        } else {
            showNotification('Error updating event!', 'error');
            calendar.refetchEvents(); // Refresh to revert changes
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating event!', 'error');
        calendar.refetchEvents(); // Refresh to revert changes
    });
}

function getPriorityBadgeClass(priority) {
    const classes = {
        'low': 'success',
        'medium': 'warning',
        'high': 'danger'
    };
    return classes[priority] || 'secondary';
}

function getStatusBadgeClass(status) {
    const classes = {
        'todo': 'secondary',
        'in_progress': 'warning',
        'done': 'success'
    };
    return classes[status] || 'secondary';
}

function openAddEventModal(dateStr) {
    document.getElementById('event_start').value = dateStr + 'T09:00';
    $('#addEventModal').modal('show');
}

function updateStats() {
    var events = calendar.getEvents();
    var today = new Date();
    today.setHours(0, 0, 0, 0);
    
    var total = events.length;
    var completed = events.filter(e => e.extendedProps.status === 'done').length;
    var pending = events.filter(e => e.extendedProps.status === 'todo' || e.extendedProps.status === 'in_progress').length;
    var todayCount = events.filter(e => {
        var eventDate = new Date(e.start);
        eventDate.setHours(0, 0, 0, 0);
        return eventDate.getTime() === today.getTime();
    }).length;
    
    document.getElementById('totalEvents').textContent = total;
    document.getElementById('completedEvents').textContent = completed;
    document.getElementById('pendingEvents').textContent = pending;
    document.getElementById('todayEvents').textContent = todayCount;
}

function exportCalendar() {
    // Export calendar data
    var events = calendar.getEvents();
    var data = events.map(e => ({
        title: e.title,
        start: e.start.toLocaleString(),
        end: e.end ? e.end.toLocaleString() : '',
        description: e.extendedProps.description || '',
        priority: e.extendedProps.priority,
        status: e.extendedProps.status,
        assigned_to: e.extendedProps.assigned_to || '',
        created_by: e.extendedProps.created_by || ''
    }));
    
    var csv = 'Title,Start,End,Description,Priority,Status,Assigned To,Created By\n';
    data.forEach(row => {
        csv += `"${row.title}","${row.start}","${row.end}","${row.description}","${row.priority}","${row.status}","${row.assigned_to}","${row.created_by}"\n`;
    });
    
    var blob = new Blob([csv], { type: 'text/csv' });
    var url = window.URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = 'farm_calendar.csv';
    a.click();
    window.URL.revokeObjectURL(url);
    
    showNotification('Calendar exported successfully!', 'success');
}

function showNotification(message, type) {
    // Simple notification system
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
</script>
@endpush

