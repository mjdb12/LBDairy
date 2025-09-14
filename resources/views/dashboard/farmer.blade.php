@extends('layouts.app')

@section('title', 'Farmer Dashboard - LBDAIRY')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-tachometer-alt"></i>
        Farmer Dashboard
    </h1>
    <p>Welcome back! Here's what's happening with your dairy farm today.</p>
</div>

<!-- Statistics Grid -->
<div class="row fade-in">
    <!-- Total Livestock -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Livestock</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLivestock }}</div>
                </div>
                <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                    <i class="fas fa-users fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Production -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Production (L)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalProduction, 2) }}</div>
                </div>
                <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                    <i class="fas fa-chart-line fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Sales -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Sales</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalSales, 2) }}</div>
                </div>
                <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                    <i class="fas fa-dollar-sign fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Expenses -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Expenses</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalExpenses, 2) }}</div>
                </div>
                <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                    <i class="fas fa-receipt fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Alerts Section -->
@if(isset($livestockAlerts) && $livestockAlerts->count() > 0)
<div class="row fade-in">
    <div class="col-12 mb-4">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0">
                    <i class="fas fa-exclamation-triangle"></i>
                    Livestock Alerts
                </h6>
            </div>
            <div class="card-body">
                @foreach($livestockAlerts as $alert)
                <div class="alert alert-{{ $alert->severity === 'critical' ? 'danger' : ($alert->severity === 'high' ? 'warning' : ($alert->severity === 'medium' ? 'info' : 'secondary')) }} alert-dismissible fade show" role="alert">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="alert-heading mb-1">
                                <i class="fas fa-{{ $alert->severity === 'critical' ? 'exclamation-triangle' : ($alert->severity === 'high' ? 'exclamation-circle' : 'info-circle') }}"></i>
                                {{ $alert->topic }} - {{ $alert->livestock->tag_number }}
                            </h6>
                            <p class="mb-1">{{ $alert->description }}</p>
                            <small class="text-muted">
                                <i class="fas fa-calendar"></i> {{ $alert->alert_date->format('M d, Y') }}
                                <span class="ml-3">
                                    <span class="badge badge-{{ $alert->severity_badge_class }}">{{ ucfirst($alert->severity) }}</span>
                                </span>
                            </small>
                        </div>
                        <div class="ml-3">
                            <button type="button" class="btn btn-sm btn-outline-{{ $alert->severity === 'critical' ? 'danger' : ($alert->severity === 'high' ? 'warning' : 'info') }}" onclick="markAlertAsRead({{ $alert->id }})">
                                <i class="fas fa-check"></i> Mark as Read
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<!-- Task Board Row -->
<div class="row fade-in">
    <!-- Task Board -->
    <div class="col-12 mb-4">
        <div class="card shadow">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0">
                    <i class="fas fa-tasks"></i>
                    Task Board
                </h6>
                <button class="custom-task-btn" id="addTaskBtn">
                    <i class="fas fa-plus"></i> New Task
                </button>
            </div>
            <div class="card-body">
                <ul class="list-group" id="taskList"></ul>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Row -->
<div class="row fade-in">
    <!-- Recent Production -->
    <div class="col-xl-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-line"></i>
                    Recent Production
                </h6>
            </div>
            <div class="card-body">
                @if($recentProduction->count() > 0)
                    @foreach($recentProduction as $production)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-1">{{ $production->livestock->name ?? 'Unknown' }}</h6>
                                <small class="text-muted">{{ $production->production_date->format('M d, Y') }}</small>
                            </div>
                            <span class="font-weight-bold">{{ number_format($production->milk_quantity, 1) }}L</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No production records yet.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Sales -->
    <div class="col-xl-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-dollar-sign"></i>
                    Recent Sales
                </h6>
            </div>
            <div class="card-body">
                @if($recentSales->count() > 0)
                    @foreach($recentSales as $sale)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-1">{{ $sale->customer_name }}</h6>
                                <small class="text-muted">{{ $sale->sale_date->format('M d, Y') }}</small>
                            </div>
                            <span class="font-weight-bold">${{ number_format($sale->total_amount, 2) }}</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No sales records yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Task Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" id="taskForm">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalTitle">New Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="taskId">
                <div class="form-group">
                    <label for="taskTitle">Title</label>
                    <input type="text" class="form-control" id="taskTitle" required maxlength="255">
                </div>
                <div class="form-group">
                    <label for="taskDescription">Description</label>
                    <textarea class="form-control" id="taskDescription" rows="3"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="taskPriority">Priority</label>
                        <select class="form-control" id="taskPriority">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="taskDueDate">Due Date</label>
                        <input type="date" class="form-control" id="taskDueDate">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="taskSubmitBtn">Add Task</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Task Confirmation Modal -->
<div class="modal fade" id="confirmDeleteTaskModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteTaskLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteTaskLabel">
                    <i class="fas fa-exclamation-triangle"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this task? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteTaskBtn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.dashboard-card {
    transition: transform 0.2s ease-in-out;
}

.dashboard-card:hover {
    transform: translateY(-2px);
}

.stat-card {
    border-radius: 10px;
    overflow: hidden;
}

.stat-card .card-body {
    padding: 1.5rem;
}

.stat-card .icon {
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.stat-card:hover .icon {
    opacity: 1;
}

.table-responsive {
    border-radius: 8px;
    overflow: hidden;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.fade-in {
    animation: fadeIn 0.6s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.font-size-4 {
    font-size: 0.6em;
}

/* Custom Green Button for New Task - NO GLASS EFFECTS */
html body .card .card-header #addTaskBtn.btn-primary,
html body #addTaskBtn.btn-primary,
#addTaskBtn.btn-primary,
#addTaskBtn,
#addTaskBtn.btn {
    background-color: #387057 !important;
    background: #387057 !important;
    border-color: #387057 !important;
    color: #fff !important;
    border: 2px solid #387057 !important;
    transition: all 0.2s ease;
    box-shadow: none !important;
    filter: none !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    opacity: 1 !important;
    text-shadow: none !important;
}

html body .card .card-header #addTaskBtn.btn-primary:hover,
html body .card .card-header #addTaskBtn.btn-primary:focus,
html body #addTaskBtn.btn-primary:hover,
html body #addTaskBtn.btn-primary:focus,
#addTaskBtn.btn-primary:hover,
#addTaskBtn.btn-primary:focus,
#addTaskBtn:hover,
#addTaskBtn:focus,
#addTaskBtn.btn:hover,
#addTaskBtn.btn:focus {
    background-color: #2d5a47 !important;
    background: #2d5a47 !important;
    border-color: #2d5a47 !important;
    color: #fff !important;
    border: 2px solid #2d5a47 !important;
    transform: translateY(-1px);
    box-shadow: none !important;
    filter: none !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    opacity: 1 !important;
    text-shadow: none !important;
}

/* COMPLETELY CUSTOM BUTTON - NO BOOTSTRAP INHERITANCE */
.custom-task-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    background-color: #387057;
    color: #fff;
    border: 2px solid #387057;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    min-width: 80px;
    height: 36px;
    box-shadow: none;
    filter: none;
    backdrop-filter: none;
    -webkit-backdrop-filter: none;
    opacity: 1;
    text-shadow: none;
    background-image: none;
    font-family: inherit;
    line-height: 1.5;
    vertical-align: middle;
    user-select: none;
}

.custom-task-btn:hover,
.custom-task-btn:focus {
    background-color: #2d5a47;
    border-color: #2d5a47;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: none;
    filter: none;
    backdrop-filter: none;
    -webkit-backdrop-filter: none;
    opacity: 1;
    text-shadow: none;
    background-image: none;
    text-decoration: none;
}

.custom-task-btn:active {
    transform: translateY(0);
}

.custom-task-btn:focus {
    outline: 0;
}

/* Task Board Styling */
.task-checkbox {
    width: 18px;
    height: 18px;
    accent-color: #18375d !important;
    cursor: pointer;
    margin-top: 2px;
}

/* Task board container styling */
#taskList {
    border-radius: 0.5rem;
    overflow: hidden;
}

#taskList .list-group-item:first-child {
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
}

#taskList .list-group-item:last-child {
    border-bottom-left-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
}

.task-checkbox:checked {
    background-color: #18375d !important;
    border-color: #18375d !important;
}

.task-checkbox:focus {
    outline: 2px solid #18375d;
    outline-offset: 2px;
}

.task-checkbox:focus:not(:focus-visible) {
    outline: none;
}

/* Task action buttons spacing */
.action-buttons {
    display: inline-flex;
    gap: 0.5rem;
    align-items: center;
}

/* Ensure proper alignment of task items */
.list-group-item {
    padding: 1rem;
    border-left: none;
    border-right: none;
    border-top: 1px solid #e3e6f0;
    border-bottom: 1px solid #e3e6f0;
    transition: all 0.2s ease;
    background-color: #fff;
}

.list-group-item:hover {
    background-color: #f8f9fc;
    transform: translateX(2px);
}

.list-group-item:first-child {
    border-top: none;
}

.list-group-item:last-child {
    border-bottom: none;
}

.list-group-item .d-flex {
    gap: 0.5rem;
}

/* Task title and description alignment */
.list-group-item .font-weight-bold {
    color: #18375d;
    margin-bottom: 0.25rem;
}

.list-group-item .text-muted {
    font-size: 0.875rem;
    line-height: 1.4;
}

/* Priority badge specific colors */
.badge-danger {
    background-color: #e74a3b !important;
    color: #fff !important;
}

.badge-warning {
    background-color: #f6c23e !important;
    color: #fff !important;
}

.badge-secondary {
    background-color: #858796 !important;
    color: #fff !important;
}

/* Recent Production and Sales Section Styling - Specific to these sections only */
.row .col-xl-6 .card .card-header h6.text-primary {
    color: #ffffff !important;
}

.row .col-xl-6 .card .card-header h6.text-primary i {
    color: #ffffff !important;
}

/* Ensure recent sections card headers are visible */
.row .col-xl-6 .card .card-header {
    background-color: #18375d !important;
    border-bottom: 1px solid #e3e6f0 !important;
}

.row .col-xl-6 .card .card-header h6 {
    color: #ffffff !important;
    font-weight: 600 !important;
}

.row .col-xl-6 .card .card-header h6 i {
    color: #ffffff !important;
    margin-right: 0.5rem;
}

/* Ensure recent sections card body content is visible */
.row .col-xl-6 .card .card-body {
    background-color: #fff !important;
    color: #5a5c69 !important;
}

.row .col-xl-6 .card .card-body h6 {
    color: #18375d !important;
    font-weight: 600 !important;
}

.row .col-xl-6 .card .card-body .text-muted {
    color: #858796 !important;
}

.row .col-xl-6 .card .card-body .font-weight-bold {
    color: #18375d !important;
    font-weight: 600 !important;
}

</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Task board logic
    const taskList = document.getElementById('taskList');
    const addTaskBtn = document.getElementById('addTaskBtn');

    function fetchTasks() {
        fetch('{{ route("farmer.tasks.index") }}', { credentials: 'same-origin', headers: { 'Accept': 'application/json' }})
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;
                renderTasks(data.tasks);
            })
            .catch(() => {
                // Fallback to empty task list if endpoint doesn't exist
                renderTasks([]);
            });
    }

    function renderTasks(tasks) {
        taskList.innerHTML = '';
        if (!tasks || tasks.length === 0) {
            const li = document.createElement('li');
            li.className = 'list-group-item text-muted';
            li.textContent = 'No tasks yet';
            taskList.appendChild(li);
            return;
        }
        tasks.forEach(task => taskList.appendChild(taskItem(task)));
    }

    function taskItem(task) {
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between';
        li.dataset.id = task.id;
        li.innerHTML = `
            <div class="d-flex align-items-center">
                <input type="checkbox" class="task-checkbox mr-3" ${task.status === 'done' ? 'checked' : ''}>
                <div>
                    <div class="font-weight-bold">${escapeHtml(task.title)}</div>
                    <small class="text-muted">${escapeHtml(task.description || '')}</small>
                </div>
            </div>
            <div class="mt-2 mt-md-0 d-flex align-items-center">
                <span class="badge badge-${priorityBadge(task.priority)} mr-4"><i class="far fa-clock"></i> ${formatDue(task.due_date)}</span>
                <div class="action-buttons">
                    <button class="btn-action btn-action-edit btn-action-sm edit-task" title="Edit Task">
                        <i class="fas fa-edit"></i>
                        <span>Edit</span>
                    </button>
                    <button class="btn-action btn-action-delete btn-action-sm delete-task" title="Delete Task">
                        <i class="fas fa-trash"></i>
                        <span>Delete</span>
                    </button>
                </div>
            </div>
        `;

        li.querySelector('input[type="checkbox"]').addEventListener('change', (e) => {
            updateTask(task.id, { status: e.target.checked ? 'done' : 'todo' });
        });
        li.querySelector('.edit-task').addEventListener('click', () => startEditTask(task));
        li.querySelector('.delete-task').addEventListener('click', () => deleteTask(task.id));
        return li;
    }

    function escapeHtml(s) {
        return (s || '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[c]));
    }

    function priorityBadge(p) {
        if (p === 'high') return 'danger';
        if (p === 'low') return 'secondary';
        return 'warning';
    }

    function formatDue(dateStr) {
        if (!dateStr) return 'No due';
        try { return new Date(dateStr).toLocaleDateString(); } catch { return 'No due'; }
    }

    function startNewTask() {
        document.getElementById('taskId').value = '';
        document.getElementById('taskTitle').value = '';
        document.getElementById('taskDescription').value = '';
        document.getElementById('taskPriority').value = 'medium';
        document.getElementById('taskDueDate').value = '';
        document.getElementById('taskSubmitBtn').textContent = 'Add Task';
        document.getElementById('taskModalTitle').textContent = 'New Task';
        $('#taskModal').modal('show');
    }

    function startEditTask(task) {
        document.getElementById('taskId').value = task.id;
        document.getElementById('taskTitle').value = task.title || '';
        document.getElementById('taskDescription').value = task.description || '';
        document.getElementById('taskPriority').value = task.priority || 'medium';
        document.getElementById('taskDueDate').value = task.due_date ? task.due_date.substring(0,10) : '';
        document.getElementById('taskSubmitBtn').textContent = 'Update Task';
        document.getElementById('taskModalTitle').textContent = 'Edit Task';
        document.getElementById('taskTitle').focus();
        $('#taskModal').modal('show');
    }

    function hideTaskForm() {
        $('#taskModal').modal('hide');
    }

    function createTask(payload) {
        fetch('{{ route("farmer.tasks.store") }}', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
        }).then(async r => {
            let data = null;
            try { data = await r.json(); } catch (e) {}
            if (!r.ok) {
                const msg = (data && (data.message || (data.errors && Object.values(data.errors)[0][0]))) || `Request failed (${r.status})`;
                showNotification(`Failed to create task: ${msg}`, 'danger');
                return;
            }
            if (data && data.success) {
                fetchTasks();
                showNotification('Task created successfully', 'success');
            } else {
                showNotification('Failed to create task', 'danger');
            }
        }).catch(() => showNotification('Failed to create task: network error', 'danger'));
    }

    function updateTask(id, payload) {
        fetch(`{{ route('farmer.tasks.update', ':id') }}`.replace(':id', id), {
            method: 'PUT',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
        }).then(async r => {
            let data = null;
            try { data = await r.json(); } catch (e) {}
            if (!r.ok) {
                const msg = (data && (data.message || (data.errors && Object.values(data.errors)[0][0]))) || `Request failed (${r.status})`;
                showNotification(`Failed to update task: ${msg}`, 'danger');
                return;
            }
            if (data && data.success) {
                fetchTasks();
                showNotification('Task updated successfully', 'success');
            } else {
                showNotification('Failed to update task', 'danger');
            }
        }).catch(() => showNotification('Failed to update task: network error', 'danger'));
    }

    let taskToDelete = null;

    function deleteTask(id) {
        taskToDelete = id;
        $('#confirmDeleteTaskModal').modal('show');
    }

    document.getElementById('confirmDeleteTaskBtn').addEventListener('click', function() {
        if (taskToDelete) {
            performTaskDeletion(taskToDelete);
            taskToDelete = null;
            $('#confirmDeleteTaskModal').modal('hide');
        }
    });

    function performTaskDeletion(id) {
        fetch(`{{ route('farmer.tasks.destroy', ':id') }}`.replace(':id', id), {
            method: 'DELETE',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(r => r.json()).then(data => {
            if (data.success) {
                fetchTasks();
                showNotification('Task deleted successfully', 'success');
            } else {
                showNotification('Failed to delete task', 'danger');
            }
        }).catch(() => showNotification('Failed to delete task: network error', 'danger'));
    }

    function markAlertAsRead(alertId) {
        $.ajax({
            url: `{{ route('farmer.alerts.mark-read', ':id') }}`.replace(':id', alertId),
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Hide the alert
                    $(`[onclick="markAlertAsRead(${alertId})"]`).closest('.alert').fadeOut();
                    showNotification('Alert marked as read', 'success');
                } else {
                    showNotification('Failed to mark alert as read', 'danger');
                }
            },
            error: function() {
                showNotification('Failed to mark alert as read', 'danger');
            }
        });
    }

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

    addTaskBtn?.addEventListener('click', () => startNewTask());
    document.getElementById('taskForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('taskId').value;
        const payload = {
            title: document.getElementById('taskTitle').value.trim(),
            description: document.getElementById('taskDescription').value.trim(),
            priority: document.getElementById('taskPriority').value,
            due_date: document.getElementById('taskDueDate').value || null
        };
        if (!payload.title) { showNotification('Title is required', 'warning'); return; }
        if (id) {
            updateTask(id, payload);
        } else {
            createTask(payload);
        }
        hideTaskForm();
    });
    fetchTasks();
});
</script>
@endpush
