<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inspection;
use App\Models\Task;

class CalendarController extends Controller
{
    /**
     * List calendar events for current user.
     *
     * Returns both user-created farm activity events (backed by the tasks table)
     * and admin-scheduled inspections (read-only).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $events = [];

        if ($user) {
            // Persistent user-created events are stored as tasks with a due_date
            $query = Task::with(['assignedUser', 'creator'])
                ->whereNotNull('due_date')
                ->orderBy('due_date');

            // Non-superadmins only see their own tasks
            if ($user->role !== 'superadmin') {
                $query->where('assigned_to', $user->id);
            }

            $tasks = $query->get();

            foreach ($tasks as $task) {
                $start = $task->due_date; // casted to Carbon via $casts
                if (!$start) {
                    continue;
                }

                // Default 1-hour duration
                $end = (clone $start)->addHour();
                $color = $this->getPriorityColor($task->priority);

                $events[] = [
                    'id' => 'task_' . $task->id,
                    'title' => $task->title,
                    'start' => $start->format('c'),
                    'end' => $end->format('c'),
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'textColor' => '#ffffff',
                    // Extra fields become event.extendedProps in FullCalendar
                    'priority' => $task->priority,
                    'description' => $task->description,
                    'status' => $task->status,
                    'category' => 'task',
                    'assigned_to' => optional($task->assignedUser)->name,
                    'created_by' => optional($task->creator)->name,
                ];
            }
        }

        // Admin-scheduled inspections for the logged-in farmer remain read-only
        $userId = Auth::id();
        if ($userId) {
            $inspections = Inspection::where('farmer_id', $userId)
                ->orderBy('inspection_date')
                ->orderBy('inspection_time')
                ->get();

            foreach ($inspections as $insp) {
                $date = optional($insp->inspection_date)->format('Y-m-d');
                $time = is_object($insp->inspection_time)
                    ? $insp->inspection_time->format('H:i:s')
                    : (string) $insp->inspection_time;
                $start = trim($date . 'T' . ($time ?: '08:00:00'));

                $events[] = [
                    'id' => 'insp_' . $insp->id,
                    'title' => 'Scheduled Inspection',
                    'start' => $start,
                    'end' => null,
                    'priority' => $insp->priority ?? 'medium',
                    'description' => $insp->notes ?? null,
                    'status' => $insp->status ?? 'scheduled',
                    'category' => 'inspection',
                    'editable' => false,
                    'startEditable' => false,
                    'durationEditable' => false,
                    'backgroundColor' => '#18375d',
                    'borderColor' => '#18375d',
                    'textColor' => '#ffffff',
                ];
            }
        }

        return response()->json($events);
    }

    /**
     * Store a new calendar event as a Task-backed farm activity.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'priority' => 'nullable|string|in:low,medium,high',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|string|in:todo,in_progress,done',
            'category' => 'nullable|string|max:50',
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'] ?? 'medium',
            'due_date' => $validated['start'],
            'assigned_to' => $user ? $user->id : null,
            'created_by' => $user ? $user->id : null,
            'status' => $validated['status'] ?? 'todo',
            'sort_order' => Task::max('sort_order') + 1,
        ]);

        $start = $task->due_date;
        $end = $start ? (clone $start)->addHour() : null;
        $color = $this->getPriorityColor($task->priority);

        $event = [
            'id' => 'task_' . $task->id,
            'title' => $task->title,
            'start' => $start ? $start->format('c') : null,
            'end' => $end ? $end->format('c') : null,
            'backgroundColor' => $color,
            'borderColor' => $color,
            'textColor' => '#ffffff',
            'priority' => $task->priority,
            'description' => $task->description,
            'status' => $task->status,
            'category' => $validated['category'] ?? 'task',
            'assigned_to' => optional($task->assignedUser)->name,
            'created_by' => optional($task->creator)->name,
        ];

        return response()->json(['success' => true, 'event' => $event]);
    }

    /**
     * Update an existing calendar event.
     *
     * task_123 IDs map to Task records; insp_123 remain read-only.
     */
    public function update(Request $request, string $id)
    {
        // Disallow editing admin-scheduled inspection events from farmer view
        if (substr($id, 0, 5) === 'insp_') {
            return response()->json(['success' => false, 'message' => 'Inspection events cannot be edited here.'], 403);
        }

        if (strpos($id, 'task_') !== 0) {
            return response()->json(['success' => false, 'message' => 'Unsupported event type'], 400);
        }

        $taskId = (int) substr($id, 5);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'start' => 'sometimes|required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'priority' => 'nullable|string|in:low,medium,high',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|string|in:todo,in_progress,done',
            'category' => 'nullable|string|max:50',
        ]);

        $user = Auth::user();
        $query = Task::query();

        // Non-superadmins can only update their own events
        if (!$user || $user->role !== 'superadmin') {
            $query->where('assigned_to', $user ? $user->id : null);
        }

        $task = $query->find($taskId);
        if (!$task) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        // Map calendar fields to task columns
        if (isset($validated['start'])) {
            $validated['due_date'] = $validated['start'];
            unset($validated['start']);
        }
        // We ignore "end" and "category" at the Task level
        unset($validated['end'], $validated['category']);

        $task->update($validated);

        return response()->json(['success' => true]);
    }

    /**
     * Map priority to a consistent color for calendar events.
     */
    private function getPriorityColor($priority): string
    {
        $colors = [
            'low' => '#28a745',
            'medium' => '#ffc107',
            'high' => '#dc3545',
        ];

        return $colors[$priority] ?? '#6c757d';
    }
}
