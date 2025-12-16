<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Livestock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IssueController extends Controller
{
    /**
     * Display a listing of the issues.
     */
    public function index()
    {
        $issues = Issue::with('livestock.farm')->get();
        $totalIssues = $issues->count();
        $pendingIssues = $issues->where('status', 'Pending')->count();
        $urgentIssues = $issues->where('priority', 'Urgent')->count();
        $resolvedIssues = $issues->where('status', 'Resolved')->count();
        $livestock = Livestock::all();

        return view('admin.manage-issues', compact(
            'issues',
            'totalIssues',
            'pendingIssues',
            'urgentIssues',
            'resolvedIssues',
            'livestock'
        ));
    }

    /**
     * Show the form for creating a new issue.
     */
    public function create()
    {
        $livestock = Livestock::all();
        return view('admin.issues.create', compact('livestock'));
    }

    /**
     * Get all farmers for issue reporting.
     */
    public function getFarmers()
    {
        try {
            Log::info('getFarmers method called for issues');
            
            // Check if user is authenticated
            if (!\Illuminate\Support\Facades\Auth::check()) {
                Log::error('User not authenticated');
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            $user = \Illuminate\Support\Facades\Auth::user();
            Log::info('Authenticated user: ' . $user->name . ' with role: ' . $user->role);
            
            if ($user->role !== 'admin') {
                Log::error('User does not have admin role');
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
            
            // Get farmers with their livestock counts
            $farmers = \App\Models\User::where('role', 'farmer')
                ->withCount('livestock')
                ->get()
                ->map(function ($farmer) {
                    return [
                        'id' => $farmer->id,
                        'first_name' => $farmer->first_name,
                        'last_name' => $farmer->last_name,
                        'name' => $farmer->name,
                        'email' => $farmer->email,
                        // Prefer phone, fall back to legacy contact_number so frontend contact column shows a real value
                        'contact_number' => $farmer->phone ?? $farmer->contact_number,
                        'barangay' => $farmer->barangay,
                        'status' => $farmer->status,
                        'livestock_count' => $farmer->livestock_count
                    ];
                });

            Log::info('Farmers data prepared for issues: ' . count($farmers) . ' farmers');
            return response()->json([
                'success' => true,
                'data' => $farmers
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getFarmers for issues: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch farmers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get livestock for a specific farmer for issue reporting.
     */
    public function getFarmerLivestock($farmerId)
    {
        try {
            $farmer = \App\Models\User::findOrFail($farmerId);
            $livestock = Livestock::where('owner_id', $farmerId)
                ->with('farm')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'farmer' => $farmer,
                    'livestock' => $livestock
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch farmer livestock'
            ], 500);
        }
    }

    /**
     * Store a newly created issue in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'livestock_id' => 'required|exists:livestock,id',
            'issue_type' => 'required|string|max:255',
            'priority' => 'required|string|in:Low,Medium,High,Urgent',
            'date_reported' => 'required|date|after_or_equal:today',
            'description' => 'required|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = \Illuminate\Support\Facades\Auth::user();
            $livestock = Livestock::findOrFail($request->livestock_id);
            
            Issue::create([
                'livestock_id' => $request->livestock_id,
                'farm_id' => $livestock->farm_id,
                'issue_type' => $request->issue_type,
                'priority' => $request->priority,
                'date_reported' => $request->date_reported,
                'description' => $request->description,
                'notes' => $request->notes,
                'status' => 'Pending',
                'reported_by' => $user->id,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Issue reported successfully!'
                ]);
            }
            return redirect()->route('admin.issues.index')
                ->with('success', 'Issue reported successfully!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to report issue. Please try again.'
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Failed to report issue. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified issue.
     */
    public function show($id)
    {
        try {
            $issue = Issue::with('livestock.farm')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'issue' => $issue
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Issue not found'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified issue.
     */
    public function edit($id)
    {
        $issue = Issue::findOrFail($id);
        $livestock = Livestock::all();
        
        return view('admin.issues.edit', compact('issue', 'livestock'));
    }

    /**
     * Update the specified issue in storage.
     */
    public function update(Request $request, $id)
    {
        $issue = Issue::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'issue_type' => 'required|string|max:255',
            'priority' => 'required|string|in:Low,Medium,High,Urgent',
            'status' => 'required|string|in:Pending,In Progress,Resolved,Closed',
            'description' => 'required|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $issue->update([
                'issue_type' => $request->issue_type,
                'priority' => $request->priority,
                'status' => $request->status,
                'description' => $request->description,
                'notes' => $request->notes,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Issue updated successfully!'
                ]);
            }
            return redirect()->route('admin.issues.index')
                ->with('success', 'Issue updated successfully!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update issue. Please try again.'
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Failed to update issue. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified issue from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $issue = Issue::findOrFail($id);
            $issue->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Issue deleted successfully!'
                ]);
            }
            return redirect()->route('admin.issues.index')
                ->with('success', 'Issue deleted successfully!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete issue. Please try again.'
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Failed to delete issue. Please try again.');
        }
    }

    /**
     * Update issue status.
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $issue = Issue::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:Pending,In Progress,Resolved,Closed'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status value'
                ], 400);
            }

            $issue->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status'
            ], 500);
        }
    }

    /**
     * Get issue statistics for dashboard.
     */
    public function getStats()
    {
        try {
            $stats = [
                'total' => Issue::count(),
                'pending' => Issue::where('status', 'Pending')->count(),
                'in_progress' => Issue::where('status', 'In Progress')->count(),
                'resolved' => Issue::where('status', 'Resolved')->count(),
                'closed' => Issue::where('status', 'Closed')->count(),
                'by_type' => Issue::select('issue_type', DB::raw('count(*) as count'))
                    ->groupBy('issue_type')
                    ->get(),
                'by_priority' => Issue::select('priority', DB::raw('count(*) as count'))
                    ->groupBy('priority')
                    ->get(),
                'by_status' => Issue::select('status', DB::raw('count(*) as count'))
                    ->groupBy('status')
                    ->get()
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics'
            ], 500);
        }
    }

    /**
     * Export issues data.
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $issues = Issue::with('livestock.farm')->get();

        switch ($format) {
            case 'csv':
                return $this->exportToCSV($issues);
            case 'pdf':
                return $this->exportToPDF($issues);
            default:
                return response()->json(['error' => 'Unsupported format'], 400);
        }
    }

    /**
     * Export to CSV.
     */
    private function exportToCSV($issues)
    {
        $filename = 'issues_report_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($issues) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['ID', 'Livestock ID', 'Type', 'Breed', 'Issue Type', 'Priority', 'Status', 'Date Reported', 'Description', 'Notes']);
            
            // Add data
            foreach ($issues as $issue) {
                fputcsv($file, [
                    $issue->id,
                    $issue->livestock->livestock_id ?? 'N/A',
                    $issue->livestock->type ?? 'N/A',
                    $issue->livestock->breed ?? 'N/A',
                    $issue->issue_type,
                    $issue->priority,
                    $issue->status,
                    $issue->date_reported,
                    $issue->description,
                    $issue->notes
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to PDF.
     */
    private function exportToPDF($issues)
    {
        // This would require a PDF library like DomPDF
        // For now, return a message
        return response()->json(['message' => 'PDF export not implemented yet']);
    }

    /**
     * Get urgent issues for alerts.
     */
    public function getUrgentIssues()
    {
        try {
            $urgentIssues = Issue::with('livestock.farm')
                ->where('priority', 'Urgent')
                ->where('status', '!=', 'Resolved')
                ->orderBy('date_reported', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'urgent_issues' => $urgentIssues
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch urgent issues'
            ], 500);
        }
    }
}
