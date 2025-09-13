<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'superadmin' || $user->role === 'admin') {
            $tasks = Task::orderBy('sort_order')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Farmers can only see their own tasks
            $tasks = Task::where('assigned_to', $user->id)
                ->orderBy('sort_order')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return response()->json(['success' => true, 'tasks' => $tasks]);
    }

    public function show(Task $task)
    {
        $user = Auth::user();
        
        // Check if user can view this task
        if ($user->role !== 'superadmin' && $user->role !== 'admin' && $task->assigned_to !== $user->id) {
            abort(403);
        }

        return response()->json(['success' => true, 'task' => $task]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Allow superadmin and admin to create tasks for anyone, farmers can only create tasks for themselves
        if ($user->role !== 'superadmin' && $user->role !== 'admin') {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'priority' => 'required|in:low,medium,high',
                'due_date' => 'nullable|date',
            ]);

            $task = Task::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'priority' => $data['priority'],
                'due_date' => $data['due_date'] ?? null,
                'assigned_to' => $user->id, // Farmers can only assign tasks to themselves
                'created_by' => $user->id,
                'status' => 'todo',
                'sort_order' => Task::max('sort_order') + 1,
            ]);
        } else {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'priority' => 'required|in:low,medium,high',
                'due_date' => 'nullable|date',
                'assigned_to' => 'nullable|exists:users,id',
            ]);

            $task = Task::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'priority' => $data['priority'],
                'due_date' => $data['due_date'] ?? null,
                'assigned_to' => $data['assigned_to'] ?? null,
                'created_by' => Auth::id(),
                'status' => 'todo',
                'sort_order' => Task::max('sort_order') + 1,
            ]);
        }

        return response()->json(['success' => true, 'task' => $task]);
    }

    public function update(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Check if user can update this task
        if ($user->role !== 'superadmin' && $user->role !== 'admin' && $task->assigned_to !== $user->id) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'sometimes|required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'status' => 'sometimes|required|in:todo,in_progress,done',
        ]);

        // Only superadmin and admin can change assigned_to
        if ($user->role === 'superadmin' || $user->role === 'admin') {
            $data['assigned_to'] = $request->input('assigned_to');
        }

        $task->update($data);

        return response()->json(['success' => true, 'task' => $task]);
    }

    public function destroy(Task $task)
    {
        $user = Auth::user();
        
        // Check if user can delete this task
        if ($user->role !== 'superadmin' && $user->role !== 'admin' && $task->assigned_to !== $user->id) {
            abort(403);
        }
        
        $task->delete();
        return response()->json(['success' => true]);
    }

    public function reorder(Request $request)
    {
        $this->authorizeSuperAdmin();

        $data = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:tasks,id',
            'orders.*.sort_order' => 'required|integer',
        ]);

        foreach ($data['orders'] as $item) {
            Task::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    public function move(Request $request, Task $task)
    {
        $this->authorizeSuperAdmin();

        $data = $request->validate([
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $task->update(['status' => $data['status']]);

        return response()->json(['success' => true, 'task' => $task]);
    }

    public function calendarEvents()
    {
        $user = Auth::user();
        
        // Get tasks for the current user or all tasks if superadmin
        $query = Task::with(['assignedUser', 'creator']);
        
        if ($user->role !== 'superadmin') {
            $query->where('assigned_to', $user->id);
        }
        
        $tasks = $query->whereNotNull('due_date')
            ->orderBy('due_date')
            ->get();

        $events = $tasks->map(function ($task) {
            $color = $this->getPriorityColor($task->priority);
            $status = $task->status;
            
            return [
                'id' => $task->id,
                'title' => $task->title,
                'start' => $task->due_date->format('Y-m-d H:i:s'),
                'end' => $task->due_date->addHours(1)->format('Y-m-d H:i:s'), // Default 1 hour duration
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'description' => $task->description,
                    'priority' => $task->priority,
                    'status' => $status,
                    'assigned_to' => $task->assignedUser ? $task->assignedUser->name : null,
                    'created_by' => $task->creator ? $task->creator->name : null,
                    'type' => 'task'
                ]
            ];
        });

        return response()->json($events);
    }

    public function storeCalendarEvent(Request $request)
    {
        $user = Auth::user();
        
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'start' => 'required|date',
            'end' => 'nullable|date|after:start',
            'category' => 'nullable|string',
        ]);

        $task = Task::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'priority' => $data['priority'],
            'due_date' => $data['start'],
            'assigned_to' => $user->id,
            'created_by' => $user->id,
            'status' => 'todo',
            'sort_order' => Task::max('sort_order') + 1,
        ]);

        return response()->json(['success' => true, 'task' => $task]);
    }

    public function updateCalendarEvent(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Check if user can update this task
        if ($user->role !== 'superadmin' && $task->assigned_to !== $user->id) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'sometimes|required|in:low,medium,high',
            'start' => 'sometimes|required|date',
            'end' => 'nullable|date|after:start',
            'status' => 'sometimes|required|in:todo,in_progress,done',
        ]);

        $task->update($data);

        return response()->json(['success' => true, 'task' => $task]);
    }

    private function getPriorityColor($priority)
    {
        $colors = [
            'low' => '#28a745',
            'medium' => '#ffc107',
            'high' => '#dc3545'
        ];
        
        return $colors[$priority] ?? '#6c757d';
    }

    private function authorizeSuperAdmin(): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'superadmin') {
            abort(403);
        }
    }
}


