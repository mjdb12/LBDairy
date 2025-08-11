@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers - Calendar & Schedule')

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
                    <div class="h4 text-primary mb-0" id="totalEvents">0</div>
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
                    <button type="submit" class="btn btn-primary btn-sm btn-block">
                        <i class="fas fa-plus mr-1"></i> Add Note
                    </button>
                </form>
                <hr>
                <ul class="list-group list-group-flush" id="quickNotesList">
                    <li class="list-group-item text-center text-muted">No notes yet</li>
                </ul>
            </div>
        </div>

        <!-- External Events Card -->
        <div class="card shadow mb-4 fade-in">
            <div class="card-header">
                <h6>
                    <i class="fas fa-drag-handle"></i>
                    Quick Activities
                </h6>
            </div>
            <div class="card-body">
                <div id="external-events">
                    <div class="external-event bg-primary text-white mb-2 p-2 rounded" style="cursor: move;">
                        <i class="fas fa-milk mr-2"></i> Milk Collection
                    </div>
                    <div class="external-event bg-success text-white mb-2 p-2 rounded" style="cursor: move;">
                        <i class="fas fa-seedling mr-2"></i> Feed Livestock
                    </div>
                    <div class="external-event bg-warning text-white mb-2 p-2 rounded" style="cursor: move;">
                        <i class="fas fa-broom mr-2"></i> Clean Barn
                    </div>
                    <div class="external-event bg-info text-white mb-2 p-2 rounded" style="cursor: move;">
                        <i class="fas fa-heartbeat mr-2"></i> Health Check
                    </div>
                    <div class="external-event bg-danger text-white mb-2 p-2 rounded" style="cursor: move;">
                        <i class="fas fa-syringe mr-2"></i> Vaccination
                    </div>
                </div>
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="drop-remove">
                    <label class="form-check-label" for="drop-remove">
                        Remove after drop
                    </label>
                </div>
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
                    <button class="btn btn-sm btn-outline-light" data-toggle="modal" data-target="#addEventModal" type="button">
                        <i class="fas fa-plus"></i> Add Event
                    </button>
                    <button class="btn btn-sm btn-outline-light" onclick="exportCalendar()" type="button">
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
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
                                    <option value="urgent">Urgent</option>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Event
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
    
    // Initialize external events
    initializeExternalEvents();
    
    // Update stats
    updateStats();
    
    // Initialize event form
    initializeEventForm();
});

function initializeCalendar() {
    var calendarEl = document.getElementById('calendar');
    
    // Sample events data
    var calendarEvents = generateSampleEvents();
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        initialView: 'dayGridMonth',
        height: 'auto',
        events: calendarEvents,
        editable: true,
        droppable: true,
        eventResizableFromStart: true,
        eventDurationEditable: true,
        eventStartEditable: true,
        
        // Event interactions
        eventClick: function(info) {
            handleEventClick(info);
        },
        
        eventDrop: function(info) {
            showNotification('Event moved successfully!', 'success');
        },
        
        eventResize: function(info) {
            showNotification('Event duration updated!', 'info');
        },
        
        // Drop external events
        drop: function(info) {
            var checkbox = document.getElementById('drop-remove');
            if (checkbox.checked) {
                info.draggedEl.parentNode.removeChild(info.draggedEl);
            }
            showNotification('Event added to calendar!', 'success');
        },
        
        // Date click
        dateClick: function(info) {
            openAddEventModal(info.dateStr);
        }
    });
    
    calendar.render();
}

function generateSampleEvents() {
    var events = [];
    var date = new Date();
    var communities = [
        { name: 'Tinamnan', color: '#8e44ad' },
        { name: 'Dapdap', color: '#3498db' },
        { name: 'Ayuti', color: '#f39c12' },
        { name: 'Kulapi', color: '#e74c3c' },
        { name: 'Kamias', color: '#2ecc71' },
        { name: 'May-It', color: '#95a5a6' }
    ];
    
    var activities = [
        { name: "Milk Collection", icon: "🥛", color: "#3498db" },
        { name: "Feed Livestock", icon: "🌾", color: "#27ae60" },
        { name: "Clean Barn", icon: "🧹", color: "#f4ce14" },
        { name: "Health Checkup", icon: "🩺", color: "#8e44ad" },
        { name: "Vaccination", icon: "💉", color: "#e74c3c" },
        { name: "Grooming", icon: "✂️", color: "#f39c12" }
    ];
    
    // Generate events for the next 30 days
    for (var i = 0; i < 30; i++) {
        var eventDate = new Date(date);
        eventDate.setDate(date.getDate() + i);
        
        // Add 1-3 random events per day
        var numEvents = Math.floor(Math.random() * 3) + 1;
        
        for (var j = 0; j < numEvents; j++) {
            var activity = activities[Math.floor(Math.random() * activities.length)];
            var community = communities[Math.floor(Math.random() * communities.length)];
            
            var startHour = Math.floor(Math.random() * 8) + 6; // 6 AM to 2 PM
            var startTime = new Date(eventDate);
            startTime.setHours(startHour, Math.floor(Math.random() * 60));
            
            var endTime = new Date(startTime);
            endTime.setHours(startTime.getHours() + Math.floor(Math.random() * 3) + 1);
            
            events.push({
                title: `${activity.name} - ${community.name}`,
                start: startTime,
                end: endTime,
                backgroundColor: activity.color,
                borderColor: activity.color,
                extendedProps: {
                    community: community.name,
                    activity: activity.name,
                    icon: activity.icon,
                    priority: ['low', 'medium', 'high'][Math.floor(Math.random() * 3)],
                    status: Math.random() > 0.7 ? 'completed' : 'pending'
                }
            });
        }
    }
    
    return events;
}

function initializeExternalEvents() {
    var containerEl = document.getElementById('external-events');
    
    new FullCalendar.Draggable(containerEl, {
        itemSelector: '.external-event',
        eventData: function(eventEl) {
            return {
                id: 'ext-' + Date.now() + '-' + Math.floor(Math.random() * 10000), // Unique ID for deletion
                title: eventEl.innerText.trim(),
                backgroundColor: eventEl.style.backgroundColor,
                borderColor: eventEl.style.backgroundColor,
                textColor: eventEl.style.color,
                extendedProps: {
                    priority: 'medium',
                    status: 'pending'
                }
            };
        }
    });
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
                <button class="btn btn-sm btn-outline-danger ml-2" onclick="deleteNote(${idx})" title="Delete note">
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
        showNotification('Note deleted!', 'info');
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
        
        // Add event to calendar
        var newEvent = {
            title: title,
            start: start,
            end: end || start,
            backgroundColor: getCategoryColor(category),
            borderColor: getCategoryColor(category),
            extendedProps: {
                category: category,
                priority: priority,
                description: description,
                status: 'pending'
            }
        };
        
        calendar.addEvent(newEvent);
        
        // Close modal and reset form
        $('#addEventModal').modal('hide');
        document.getElementById('eventForm').reset();
        
        showNotification('Event added successfully!', 'success');
        updateStats();
    });
}

function getCategoryColor(category) {
    const colors = {
        'feeding': '#27ae60',
        'health': '#8e44ad',
        'maintenance': '#f39c12',
        'milking': '#3498db',
        'breeding': '#e74c3c',
        'other': '#95a5a6'
    };
    return colors[category] || '#95a5a6';
}

function handleEventClick(info) {
    // Handle event click - could open edit modal
    console.log('Event clicked:', info.event.title);
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
    var completed = events.filter(e => e.extendedProps.status === 'completed').length;
    var pending = events.filter(e => e.extendedProps.status === 'pending').length;
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
        start: e.start,
        end: e.end,
        category: e.extendedProps.category,
        priority: e.extendedProps.priority,
        status: e.extendedProps.status
    }));
    
    var csv = 'Title,Start,End,Category,Priority,Status\n';
    data.forEach(row => {
        csv += `"${row.title}","${row.start}","${row.end}","${row.category}","${row.priority}","${row.status}"\n`;
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

