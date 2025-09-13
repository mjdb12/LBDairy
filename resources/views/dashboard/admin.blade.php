@extends('layouts.app')
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

/* User Details Modal Styling */
    #taskModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #taskModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #taskModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #taskModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #taskModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #taskModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #taskModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #taskModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
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
    
    .btn-action-edit-profile {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
        .btn-action-edit-profile:hover {
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
</style>
@endpush
@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-tachometer-alt"></i>
        Admin Dashboard
    </h1>
    <p>Welcome back! Here's what's happening with your dairy management system today.</p>
</div>

<!-- Statistics Grid -->
<div class="row fade-in">
    <!-- Active Farms -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Active Farms</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Farm::where('status', 'active')->count() }}</div>
                </div>
                <div class="icon" style="color: #18375d !important;">
                    <i class="fas fa-building fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Farmers -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Total Farmers</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'farmer')->count() }}</div>
                </div>
                <div class="icon" style="color: #18375d !important;">
                    <i class="fas fa-users fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- New Requests -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">New Requests</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Issue::where('status', 'pending')->count() }}</div>
                </div>
                <div class="icon" style="color: #18375d !important;">
                    <i class="fas fa-user-plus fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Livestock -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold  text-uppercase mb-1" style="color: #18375d !important;">Total Livestock</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Livestock::count() }}</div>
                </div>
                <div class="icon" style="color: #18375d !important;">
                    <i class="fas fa-tractor fa-2x mr-2"></i>
                </div>
            </div>
        </div>
    </div>
</div>

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
                <button class="btn-action btn-sm btn-action-edit" id="addTaskBtn">
                    <i class="fas fa-plus"></i> New Task
                </button>
            </div>
            <div class="card-body">
                <ul class="list-group" id="taskList"></ul>
            </div>
        </div>
    </div>
</div>

<!-- Livestock Trends Chart Row -->
<div class="row fade-in">
    <!-- Livestock Trends Chart -->
    <div class="col-12 mb-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">
                    <i class="fas fa-chart-line"></i>
                    Livestock Population Trends
                </h6>
            </div>
            <div class="card-body">
                <canvas id="lineChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>



<!-- Recent System Activity -->
<div class="row fade-in">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-history"></i>
                    Recent System Activity
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>User</th>
                                <th>Details</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\AuditLog::latest()->take(10)->get() as $log)
                            <tr>
                                <td>
                                    <span class="badge badge-{{ $log->severity === 'critical' ? 'danger' : ($log->severity === 'warning' ? 'warning' : 'info') }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td>{{ $log->user->name ?? 'System' }}</td>
                                <td>{{ Str::limit($log->description ?? 'No details', 60) }}</td>
                                <td>{{ $log->created_at->diffForHumans() }}</td>
                                <td>
                                    <span class="badge badge-success">Completed</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" id="taskForm">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalTitle"><i class="fas fa-plus mr-2"></i> Add New Livestock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="taskId">
                <div class="form-group">
                    <label for="taskTitle">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="taskTitle" required maxlength="255">
                </div>
                <div class="form-group">
                    <label for="taskDescription">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="taskDescription" rows="3"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="taskPriority">Priority <span class="text-danger">*</span></label>
                        <select class="form-control" id="taskPriority">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="taskDueDate">Due Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="taskDueDate">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-action btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn-action btn-action-edit" id="taskSubmitBtn">Add Task</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Task board logic
    const taskList = document.getElementById('taskList');
    const addTaskBtn = document.getElementById('addTaskBtn');

    function fetchTasks() {
        fetch('/admin/tasks', { credentials: 'same-origin', headers: { 'Accept': 'application/json' }})
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
            <div class="d-flex align-items-start">
                <input type="checkbox" class="mr-2" ${task.status === 'done' ? 'checked' : ''}>
                <div>
                    <div class="font-weight-bold">${escapeHtml(task.title)}</div>
                    <small class="text-muted">${escapeHtml(task.description || '')}</small>
                </div>
            </div>
            <div class="mt-2 mt-md-0 d-flex align-items-center">
                <span class="badge badge-${priorityBadge(task.priority)} mr-2"><i class="far fa-clock"></i> ${formatDue(task.due_date)}</span>
                <button class="btn-action btn-action-edit" title="Edit Task">
                    <i class="fas fa-edit"></i>
                    <span>Edit</span>
                </button>
                <button class="btn-action btn-action-deletes" title="Delete Task">
                    <i class="fas fa-trash"></i>
                    <span>Delete</span>
                </button>
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
        fetch('/admin/tasks', {
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
                alert(`Failed to create task: ${msg}`);
                return;
            }
            if (data && data.success) {
                fetchTasks();
            } else {
                alert('Failed to create task');
            }
        }).catch(() => alert('Failed to create task: network error'));
    }

    function updateTask(id, payload) {
        fetch(`/admin/tasks/${id}`, {
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
                alert(`Failed to update task: ${msg}`);
                return;
            }
            if (data && data.success) {
                fetchTasks();
            } else {
                alert('Failed to update task');
            }
        }).catch(() => alert('Failed to update task: network error'));
    }

    function deleteTask(id) {
        if (!confirm('Delete this task?')) return;
        fetch(`/admin/tasks/${id}`, {
            method: 'DELETE',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(r => r.json()).then(data => {
            if (data.success) fetchTasks(); else alert('Failed to delete task');
        });
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
        if (!payload.title) { alert('Title is required'); return; }
        if (id) {
            updateTask(id, payload);
        } else {
            createTask(payload);
        }
        hideTaskForm();
    });
    fetchTasks();

    // Initialize chart with real data
    const ctxLine = document.getElementById('lineChart').getContext('2d');
    let trendsChart = null;
    fetch("{{ route('admin.livestock-trends') }}", { credentials: 'same-origin', headers: { 'Accept': 'application/json' }})
        .then(r => r.json())
        .then(payload => {
            if (!payload || !payload.success) return;
            const config = {
                type: 'line',
                data: {
                    labels: payload.labels,
                    datasets: payload.datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.1)' } },
                        x: { grid: { color: 'rgba(0,0,0,0.1)' } }
                    }
                }
            };
            trendsChart = new Chart(ctxLine, config);
        })
        .catch(() => {
            // fallback to placeholder data if request fails
            trendsChart = new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Cattle',
                        data: [65, 59, 80, 81, 56, 55],
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Goats',
                        data: [28, 48, 40, 19, 86, 27],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        });
});
</script>
@endpush
