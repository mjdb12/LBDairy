<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $this->authorizeSuperAdmin();

        $tasks = Task::orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'tasks' => $tasks]);
    }

    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();

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

        return response()->json(['success' => true, 'task' => $task]);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeSuperAdmin();

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'sometimes|required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'sometimes|required|in:todo,in_progress,done',
        ]);

        $task->update($data);

        return response()->json(['success' => true, 'task' => $task]);
    }

    public function destroy(Task $task)
    {
        $this->authorizeSuperAdmin();
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

    private function authorizeSuperAdmin(): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'superadmin') {
            abort(403);
        }
    }
}


