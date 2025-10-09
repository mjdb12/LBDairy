<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inspection;

class CalendarController extends Controller
{
    private function sessionKey(): string
    {
        $userId = Auth::id() ?: 'guest';
        return "calendar_events_{$userId}";
    }

    /**
     * List calendar events for current user.
     */
    public function index(Request $request)
    {
        // User-created events (session-based)
        $events = session($this->sessionKey(), []);

        // Admin-scheduled inspections for the logged-in farmer
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
                    // Extended props consumed by FullCalendar as extendedProps
                    'priority' => $insp->priority ?? 'medium',
                    'description' => $insp->notes ?? null,
                    'status' => $insp->status ?? 'scheduled',
                    'category' => 'inspection',
                    // Prevent farmer from moving admin-scheduled events
                    'editable' => false,
                    'startEditable' => false,
                    'durationEditable' => false,
                    // Visual hint
                    'backgroundColor' => '#18375d',
                    'borderColor' => '#18375d',
                    'textColor' => '#ffffff',
                ];
            }
        }

        return response()->json($events);
    }

    /**
     * Store a new calendar event.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required', // accept ISO strings
            'end' => 'nullable',   // accept ISO strings
            'priority' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:50',
        ]);

        $events = session($this->sessionKey(), []);
        $id = uniqid('evt_', true);

        $event = [
            'id' => $id,
            'title' => $validated['title'],
            'start' => $validated['start'],
            'end' => $validated['end'] ?? $validated['start'],
            // FullCalendar supports extendedProps; we return flat keys, it will make them available under extendedProps when consumed via AJAX
            'priority' => $validated['priority'] ?? 'medium',
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? 'todo',
            'category' => $validated['category'] ?? null,
        ];

        $events[] = $event;
        session([$this->sessionKey() => $events]);

        return response()->json(['success' => true, 'event' => $event]);
    }

    /**
     * Update an existing calendar event.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'start' => 'sometimes|required', // accept ISO strings
            'end' => 'nullable',              // accept ISO strings
            'priority' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:50',
        ]);

        // Disallow editing admin-scheduled inspection events from farmer view
        if (substr($id, 0, 5) === 'insp_') {
            return response()->json(['success' => false, 'message' => 'Inspection events cannot be edited here.'], 403);
        }

        $events = session($this->sessionKey(), []);
        $found = false;
        foreach ($events as &$evt) {
            if (($evt['id'] ?? null) === $id) {
                $found = true;
                foreach ($validated as $k => $v) {
                    // if end is null and provided explicitly, keep it null
                    $evt[$k] = $v;
                }
                if (!isset($evt['end'])) {
                    $evt['end'] = $evt['start'] ?? $evt['end'] ?? null;
                }
                break;
            }
        }
        unset($evt);

        if (!$found) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        session([$this->sessionKey() => $events]);
        return response()->json(['success' => true]);
    }
}
