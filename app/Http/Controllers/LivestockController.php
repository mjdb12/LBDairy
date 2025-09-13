<?php

namespace App\Http\Controllers;

use App\Models\Livestock;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LivestockController extends Controller
{
    /**
     * Display a listing of the livestock.
     */
    public function index()
    {
        $livestock = Livestock::with('farm')->get();
        $farms = Farm::all(); // Add this line to get all farms
        $totalLivestock = $livestock->count();
        $activeLivestock = $livestock->where('status', 'active')->count();
        $inactiveLivestock = $livestock->where('status', 'inactive')->count();
        $totalFarms = Farm::count();

        return view('admin.manage-livestock', compact(
            'livestock',
            'farms', // Add this to the compact array
            'totalLivestock',
            'activeLivestock',
            'inactiveLivestock',
            'totalFarms'
        ));
    }

    /**
     * Show the form for creating a new livestock.
     */
    public function create()
    {
        $farms = Farm::all();
        return view('admin.livestock.create', compact('farms'));
    }

    /**
     * Store a newly created livestock in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'livestock_id' => 'required|string|max:255|unique:livestock,tag_number',
            'type' => 'required|string|max:255',
            'breed' => 'required|string|max:255',
            'farm_id' => 'required|exists:farms,id',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'weight' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Livestock::create([
                'tag_number' => $request->livestock_id,
                'type' => $request->type,
                'breed' => $request->breed,
                'farm_id' => $request->farm_id,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'weight' => $request->weight,
                'health_status' => $request->notes ?? 'healthy',
                'status' => 'active',
                'owner_id' => $request->farmer_id ?? Auth::user()->id,
            ]);

            return redirect()->route('admin.livestock.index')
                ->with('success', 'Livestock added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add livestock. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified livestock.
     */
    public function show($id)
    {
        try {
            $livestock = Livestock::with('farm')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'livestock' => $livestock
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Livestock not found'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified livestock.
     */
    public function edit($id)
    {
        $livestock = Livestock::findOrFail($id);
        $farms = Farm::all();
        
        return view('admin.livestock.edit', compact('livestock', 'farms'));
    }

    /**
     * Update the specified livestock in storage.
     */
    public function update(Request $request, $id)
    {
        $livestock = Livestock::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'livestock_id' => 'required|string|max:255|unique:livestock,tag_number,' . $id,
            'type' => 'required|string|max:255',
            'breed' => 'required|string|max:255',
            'farm_id' => 'required|exists:farms,id',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'weight' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $livestock->update([
                'tag_number' => $request->livestock_id,
                'type' => $request->type,
                'breed' => $request->breed,
                'farm_id' => $request->farm_id,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'weight' => $request->weight,
                'health_status' => $request->notes ?? 'healthy',
            ]);

            return redirect()->route('admin.livestock.index')
                ->with('success', 'Livestock updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update livestock. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified livestock from storage.
     */
    public function destroy($id)
    {
        try {
            $livestock = Livestock::findOrFail($id);
            $livestock->delete();

            return redirect()->route('admin.livestock.index')
                ->with('success', 'Livestock deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete livestock. Please try again.');
        }
    }

    /**
     * Update livestock status.
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $livestock = Livestock::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:active,inactive'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status value'
                ], 400);
            }

            $livestock->update(['status' => $request->status]);

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
     * Get livestock statistics for dashboard.
     */
    public function getStats()
    {
        try {
            $stats = [
                'total' => Livestock::count(),
                'active' => Livestock::where('status', 'active')->count(),
                'inactive' => Livestock::where('status', 'inactive')->count(),
                'by_type' => Livestock::select('type', DB::raw('count(*) as count'))
                    ->groupBy('type')
                    ->get(),
                'by_farm' => Livestock::select('farms.name', DB::raw('count(*) as count'))
                    ->join('farms', 'livestock.farm_id', '=', 'farms.id')
                    ->groupBy('farms.id', 'farms.name')
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
     * Get all farmers with their livestock count.
     */
    public function getFarmers()
    {
        try {
            Log::info('getFarmers method called');
            
            // Check if user is authenticated
            if (!Auth::check()) {
                Log::error('User not authenticated');
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            $user = Auth::user();
            Log::info('Authenticated user: ' . $user->name . ' with role: ' . $user->role);
            
            if ($user->role !== 'admin') {
                Log::error('User does not have admin role');
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
            
            // First, let's check if there are any users with farmer role
            $farmerCount = \App\Models\User::where('role', 'farmer')->count();
            Log::info('Farmer count: ' . $farmerCount);
            
            if ($farmerCount === 0) {
                Log::info('No farmers found');
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No farmers found'
                ]);
            }
            
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
                        'contact_number' => $farmer->contact_number,
                        'barangay' => $farmer->barangay,
                        'status' => $farmer->status,
                        'livestock_count' => $farmer->livestock_count
                    ];
                });

            Log::info('Farmers data prepared: ' . count($farmers) . ' farmers');
            return response()->json([
                'success' => true,
                'data' => $farmers
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getFarmers: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch farmers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get livestock for a specific farmer.
     */
    public function getFarmerLivestock($farmerId)
    {
        try {
            $farmer = \App\Models\User::findOrFail($farmerId);
            $livestock = Livestock::where('owner_id', $farmerId)
                ->with('farm')
                ->get();

            $stats = [
                'total' => $livestock->count(),
                'active' => $livestock->where('status', 'active')->count(),
                'inactive' => $livestock->where('status', 'inactive')->count(),
                'farms' => $livestock->pluck('farm_id')->unique()->count()
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'farmer' => $farmer,
                    'livestock' => $livestock,
                    'stats' => $stats
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
     * Get farms for a specific farmer.
     */
    public function getFarmerFarms($farmerId)
    {
        try {
            $farms = Farm::where('owner_id', $farmerId)->get();
            
            return response()->json([
                'success' => true,
                'data' => $farms
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch farmer farms'
            ], 500);
        }
    }

    /**
     * Export livestock data.
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $livestock = Livestock::with('farm')->get();

        switch ($format) {
            case 'csv':
                return $this->exportToCSV($livestock);
            case 'pdf':
                return $this->exportToPDF($livestock);
            default:
                return response()->json(['error' => 'Unsupported format'], 400);
        }
    }

    /**
     * Export to CSV.
     */
    private function exportToCSV($livestock)
    {
        $filename = 'livestock_report_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($livestock) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['ID', 'Livestock ID', 'Type', 'Breed', 'Farm', 'Status', 'Birth Date', 'Weight', 'Notes']);
            
            // Add data
            foreach ($livestock as $animal) {
                fputcsv($file, [
                    $animal->id,
                    $animal->livestock_id,
                    $animal->type,
                    $animal->breed,
                    $animal->farm->name ?? 'N/A',
                    $animal->status,
                    $animal->birth_date,
                    $animal->weight,
                    $animal->notes
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to PDF.
     */
    private function exportToPDF($livestock)
    {
        // This would require a PDF library like DomPDF
        // For now, return a message
        return response()->json(['message' => 'PDF export not implemented yet']);
    }

    /**
     * Get detailed livestock information.
     */
    public function details($id)
    {
        try {
            $livestock = Livestock::with('farm')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $livestock
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Livestock not found'
            ], 404);
        }
    }

    /**
     * Generate QR code for livestock (admin only).
     */
    public function generateQRCode($id)
    {
        try {
            $livestock = Livestock::with(['farm', 'owner'])->findOrFail($id);
            
            // Generate QR code data
            $qrData = json_encode([
                'livestock_id' => $livestock->tag_number,
                'livestock_name' => $livestock->name,
                'type' => $livestock->type,
                'breed' => $livestock->breed,
                'farm_id' => $livestock->farm_id,
                'farm_name' => $livestock->farm ? $livestock->farm->name : 'Unknown',
                'owner_id' => $livestock->owner_id,
                'owner_name' => $livestock->owner ? $livestock->owner->name : 'Unknown',
                'generated_at' => now()->toISOString(),
                'generated_by' => Auth::user()->name
            ]);
            
            // Generate QR code URL using QR Server API
            $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($qrData);
            
            // Save QR code information to database
            $livestock->update([
                'qr_code_generated' => true,
                'qr_code_url' => $qrCodeUrl,
                'qr_code_generated_at' => now(),
                'qr_code_generated_by' => Auth::id()
            ]);
            
            return response()->json([
                'success' => true,
                'qr_code' => $qrCodeUrl,
                'livestock_id' => $livestock->tag_number,
                'generated_at' => $livestock->qr_code_generated_at,
                'generated_by' => Auth::user()->name
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate QR code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create issue alert for livestock.
     */
    public function issueAlert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'livestock_id' => 'required|exists:livestock,id',
            'issue_type' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'description' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $livestock = Livestock::findOrFail($request->livestock_id);
            
            // Create issue record
            $issue = \App\Models\Issue::create([
                'livestock_id' => $request->livestock_id,
                'issue_type' => $request->issue_type,
                'priority' => $request->priority,
                'description' => $request->description,
                'status' => 'pending',
                'reported_by' => Auth::id(),
                'date_reported' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Issue alert created successfully',
                'issue' => $issue
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create issue alert'
            ], 500);
        }
    }
}
