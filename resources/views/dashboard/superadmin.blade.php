@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-crown"></i>
        Super Admin Dashboard
    </h1>
    <p>System-wide overview and management controls for the entire dairy management system.</p>
</div>

<!-- Statistics Grid -->
<div class="row fade-in">
    <!-- Total Users -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Users</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers ?? 0 }}</div>
                </div>
                <div class="icon">
                    <i class="fas fa-user-friends fa-2x" style="color: #18375d !important;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Areas -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Service Areas</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $serviceAreasCount ?? 0 }}</div>
                </div>
                <div class="icon">
                    <i class="fas fa-map-marker-alt fa-2x" style="color: #18375d !important;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Admins -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Admins</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAdmins ?? 0 }}</div>
                </div>
                <div class="icon">
                    <i class="fas fa-user-shield fa-2x" style="color: #18375d !important;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Farmers -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Farmers</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalFarmers ?? 0 }}</div>
                </div>
                <div class="icon">
                    <i class="fas fa-users fa-2x" style="color: #18375d !important;"></i>
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
                <button class="btn btn-sm btn-primary" id="addTaskBtn">
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
                <h6 class="m-0 font-weight-bold" style="color: white !important;">
                    <i class="fas fa-history" style="color: white !important;"></i>
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
@endsection

@push('styles')
<style>
.dashboard-card {
    transition: transform 0.2s ease-in-out;
    background: #f6f4e8 !important;
}

/* Recent System Activity Table Badge Colors */
html body .card .table .badge-danger,
html body .card .table .badge-warning,
html body .card .table .badge-info,
.card .table .badge-danger,
.card .table .badge-warning,
.card .table .badge-info {
    background-color: #fca700 !important;
    color: #fff !important;
    border-radius: 0.35rem !important;
    border: none !important;
}

html body .card .table .badge-success,
.card .table .badge-success {
    background-color: #387057 !important;
    color: #fff !important;
    border-radius: 0.35rem !important;
    border: none !important;
}

/* Custom Green Button for New Task */
html body .card .card-header #addTaskBtn.btn-primary,
html body #addTaskBtn.btn-primary,
#addTaskBtn.btn-primary {
    background-color: #387057 !important;
    background: #387057 !important;
    border-color: #387057 !important;
    color: #fff !important;
    border: 2px solid #387057 !important;
}

html body .card .card-header #addTaskBtn.btn-primary:hover,
html body .card .card-header #addTaskBtn.btn-primary:focus,
html body #addTaskBtn.btn-primary:hover,
html body #addTaskBtn.btn-primary:focus,
#addTaskBtn.btn-primary:hover,
#addTaskBtn.btn-primary:focus {
    background-color: #2d5a47 !important;
    background: #2d5a47 !important;
    border-color: #2d5a47 !important;
    color: #fff !important;
    border: 2px solid #2d5a47 !important;
}

/* Fix Status column alignment in Recent System Activity table */
.card .table th:last-child,
.card .table td:last-child {
    text-align: left !important;
}

.dashboard-card:hover {
    transform: translateY(-2px);
}

/* Force override any blue styling on stat cards */
.card.stat-card,
.card.dashboard-card {
    background: #f6f4e8 !important;
    background-color: #f6f4e8 !important;
}

.card.stat-card .card-body,
.card.dashboard-card .card-body {
    background: #f6f4e8 !important;
    background-color: #f6f4e8 !important;
    color: inherit !important;
}

.stat-card {
    border-radius: 10px;
    overflow: hidden;
    background: #f6f4e8 !important;
}

.stat-card .card-body {
    padding: 1.5rem;
    background: #f6f4e8 !important;
}

.stat-card .icon {
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.stat-card:hover .icon {
    opacity: 1;
}

/* Force text colors to be correct */
.text-primary {
    color: #18375d !important;
}

.text-success {
    color: #1cc88a !important;
}

.text-info {
    color: #36b9cc !important;
}

.text-warning {
    color: #f6c23e !important;
}

.text-danger {
    color: #e74a3b !important;
}

.text-secondary {
    color: #858796 !important;
}

/* Ensure no blue backgrounds anywhere in stat cards */
.card.stat-card *,
.card.dashboard-card * {
    background-color: transparent !important;
}

.card.stat-card,
.card.dashboard-card {
    background-color: #f6f4e8 !important;
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
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Task board logic
    const taskList = document.getElementById('taskList');
    const addTaskBtn = document.getElementById('addTaskBtn');

    function fetchTasks() {
        fetch('/superadmin/tasks', { credentials: 'same-origin', headers: { 'Accept': 'application/json' }})
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;
                renderTasks(data.tasks);
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
                <button class="btn-action btn-action-edit btn-action-sm edit-task" title="Edit Task">
                    <i class="fas fa-edit"></i>
                    <span>Edit</span>
                </button>
                <button class="btn-action btn-action-delete btn-action-sm delete-task" title="Delete Task">
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
        fetch('/superadmin/tasks', {
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
        fetch(`/superadmin/tasks/${id}`, {
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
        fetch(`/superadmin/tasks/${id}`, {
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
    document.getElementById('taskCancelEditBtn')?.addEventListener('click', () => hideTaskForm());
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
    fetch("{{ route('superadmin.livestock-trends') }}", { credentials: 'same-origin', headers: { 'Accept': 'application/json' }})
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
                        data: [0, 0, 0, 0, 0, 0],
                        borderColor: '#fca700',
                        backgroundColor: 'rgba(252, 167, 0, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Goats',
                        data: [0, 0, 0, 0, 0, 0],
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
