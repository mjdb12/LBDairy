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
                <div class="icon">
                    <i class="fas fa-cow fa-2x" style="color: #18375d !important;"></i>
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
                <div class="icon">
                    <i class="fas fa-chart-line fa-2x" style="color: #18375d !important;"></i>
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
                <div class="icon">
                    <i class="fas fa-dollar-sign fa-2x" style="color: #18375d !important;"></i>
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
                <div class="icon">
                    <i class="fas fa-receipt fa-2x" style="color: #18375d !important;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Tasks -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Active Tasks</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $tasks->where('status', '!=', 'done')->count() }}</div>
                </div>
                <div class="icon">
                    <i class="fas fa-tasks fa-2x" style="color: #18375d !important;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Net Profit -->
    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Net Profit</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalSales - $totalExpenses, 2) }}</div>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie fa-2x" style="color: #18375d !important;"></i>
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
                            <span class="font-weight-bold">{{ $production->milk_quantity }}L</span>
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
        fetch(`{{ route('farmer.tasks.destroy', ':id') }}`.replace(':id', id), {
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
});
</script>
@endpush
