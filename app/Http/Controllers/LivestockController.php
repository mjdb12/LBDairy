<?php

namespace App\Http\Controllers;

use App\Models\Livestock;
use App\Models\Farm;
use App\Models\ProductionRecord;
use App\Models\HealthRecord;
use App\Models\BreedingRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

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

    public function storeLivestockProductionRecord(Request $request, $id)
    {
        $request->validate([
            'production_date' => 'required|date',
            'milk_quantity' => 'required|numeric|min:0',
            'milk_quality_score' => 'nullable|integer|min:1|max:10',
            'notes' => 'nullable|string',
        ]);

        $livestock = Livestock::findOrFail($id);

        $record = ProductionRecord::create([
            'farm_id' => $livestock->farm_id,
            'livestock_id' => $livestock->id,
            'production_date' => $request->production_date,
            'milk_quantity' => $request->milk_quantity,
            'milk_quality_score' => $request->milk_quality_score,
            'notes' => $request->notes,
            'recorded_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Production record saved.',
            'record' => $record,
        ]);
    }

    public function storeLivestockHealthRecord(Request $request, $id)
    {
        $request->validate([
            'health_date' => 'nullable|date',
            'health_status' => 'required|in:healthy,sick,recovering,under_treatment',
            'weight' => 'nullable|numeric|min:0',
            'temperature' => 'nullable|numeric',
            'symptoms' => 'nullable|string',
            'treatment' => 'nullable|string',
            'veterinarian_id' => 'nullable|exists:users,id',
        ]);

        $livestock = Livestock::findOrFail($id);

        // Persist to dedicated table
        try {
            HealthRecord::create([
                'livestock_id' => $livestock->id,
                'health_date' => $request->health_date ?: now()->toDateString(),
                'health_status' => $request->health_status,
                'weight' => $request->weight,
                'temperature' => $request->temperature,
                'symptoms' => $request->symptoms,
                'treatment' => $request->treatment,
                'veterinarian_id' => $request->veterinarian_id,
                'notes' => $request->symptoms,
                'recorded_by' => Auth::id(),
            ]);
        } catch (\Exception $e) {
            Log::warning('Admin HealthRecord insert failed: ' . $e->getMessage());
        }

        // Append a simple note into remarks for legacy display
        $noteParts = [];
        $noteParts[] = 'Status: ' . $request->health_status;
        if ($request->health_date) $noteParts[] = 'Date: ' . $request->health_date;
        if ($request->temperature !== null) $noteParts[] = 'Temp: ' . $request->temperature . '°C';
        if ($request->symptoms) $noteParts[] = 'Symptoms: ' . $request->symptoms;
        if ($request->treatment) $noteParts[] = 'Treatment: ' . $request->treatment;
        if ($request->filled('veterinarian_id')) {
            $vet = User::find($request->veterinarian_id);
            if ($vet) {
                $noteParts[] = 'Veterinarian: ' . ($vet->name ?: $vet->email);
            }
        }
        $appended = trim(($livestock->remarks ? $livestock->remarks . "\n" : '') . '[Health] ' . implode(' | ', $noteParts));

        $livestock->update([
            'health_status' => $request->health_status,
            'weight' => $request->weight !== null ? $request->weight : $livestock->weight,
            'remarks' => $appended,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Health record saved.',
        ]);
    }

    public function storeLivestockBreedingRecord(Request $request, $id)
    {
        $request->validate([
            'breeding_date' => 'nullable|date',
            'breeding_type' => 'nullable|string|max:50',
            'partner_livestock_id' => 'nullable|string|max:255',
            'expected_birth_date' => 'nullable|date',
            'pregnancy_status' => 'nullable|in:unknown,pregnant,not_pregnant',
            'breeding_success' => 'nullable|in:unknown,successful,unsuccessful',
            'notes' => 'nullable|string',
        ]);

        $livestock = Livestock::findOrFail($id);

        try {
            BreedingRecord::create([
                'livestock_id' => $livestock->id,
                'breeding_date' => $request->breeding_date ?: now()->toDateString(),
                'breeding_type' => $request->breeding_type,
                'partner_livestock_id' => $request->partner_livestock_id,
                'expected_birth_date' => $request->expected_birth_date,
                'pregnancy_status' => $request->pregnancy_status,
                'breeding_success' => $request->breeding_success,
                'notes' => $request->notes,
                'recorded_by' => Auth::id(),
            ]);
        } catch (\Exception $e) {
            Log::warning('Admin BreedingRecord insert failed: ' . $e->getMessage());
        }

        $noteParts = [];
        if ($request->breeding_date) $noteParts[] = 'Date: ' . $request->breeding_date;
        if ($request->breeding_type) $noteParts[] = 'Type: ' . ucfirst(str_replace('_',' ', $request->breeding_type));
        if ($request->partner_livestock_id) $noteParts[] = 'Partner: ' . $request->partner_livestock_id;
        if ($request->expected_birth_date) $noteParts[] = 'Expected Birth: ' . $request->expected_birth_date;
        if ($request->pregnancy_status) $noteParts[] = 'Pregnancy: ' . str_replace('_',' ', $request->pregnancy_status);
        if ($request->breeding_success) $noteParts[] = 'Success: ' . $request->breeding_success;
        if ($request->notes) $noteParts[] = 'Notes: ' . $request->notes;
        $appended = trim(($livestock->remarks ? $livestock->remarks . "\n" : '') . '[Breeding] ' . implode(' | ', $noteParts));

        $livestock->update([
            'remarks' => $appended,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Breeding record saved.',
        ]);
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
            'tag_number' => 'required|string|max:255|unique:livestock,tag_number',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'breed' => 'required|string|max:255',
            'farm_id' => 'required|exists:farms,id',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'weight' => 'nullable|numeric|min:0',
            'health_status' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'registry_id' => 'nullable|string|max:255',
            'natural_marks' => 'nullable|string|max:255',
            'property_no' => 'nullable|string|max:255',
            'acquisition_date' => 'nullable|date',
            'acquisition_cost' => 'nullable|numeric|min:0',
            'sire_id' => 'nullable|string|max:255',
            'sire_name' => 'nullable|string|max:255',
            'dam_id' => 'nullable|string|max:255',
            'dam_name' => 'nullable|string|max:255',
            'dispersal_from' => 'nullable|string|max:255',
            'owned_by' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:1000',
            'description' => 'nullable|string|max:1000',
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
            Livestock::create([
                'tag_number' => $request->tag_number,
                'name' => $request->name,
                'type' => $request->type,
                'breed' => $request->breed,
                'farm_id' => $request->farm_id,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'weight' => $request->weight,
                'health_status' => $request->health_status,
                'status' => $request->status,
                'registry_id' => $request->registry_id,
                'natural_marks' => $request->natural_marks,
                'property_no' => $request->property_no,
                'acquisition_date' => $request->acquisition_date,
                'acquisition_cost' => $request->acquisition_cost,
                'sire_id' => $request->sire_id,
                'sire_name' => $request->sire_name,
                'dam_id' => $request->dam_id,
                'dam_name' => $request->dam_name,
                'dispersal_from' => $request->dispersal_from,
                'owned_by' => $request->owned_by,
                'remarks' => $request->remarks,
                'description' => $request->description,
                'owner_id' => $request->farmer_id ?? Auth::user()->id,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Livestock added successfully!'
                ]);
            }
            return redirect()->route('admin.livestock.index')
                ->with('success', 'Livestock added successfully!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add livestock. Please try again.'
                ], 500);
            }
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
            'tag_number' => 'required|string|max:255|unique:livestock,tag_number,' . $id,
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'breed' => 'required|string|max:255',
            'farm_id' => 'required|exists:farms,id',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'weight' => 'nullable|numeric|min:0',
            'health_status' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'registry_id' => 'nullable|string|max:255',
            'natural_marks' => 'nullable|string|max:255',
            'property_no' => 'nullable|string|max:255',
            'acquisition_date' => 'nullable|date',
            'acquisition_cost' => 'nullable|numeric|min:0',
            'sire_id' => 'nullable|string|max:255',
            'sire_name' => 'nullable|string|max:255',
            'dam_id' => 'nullable|string|max:255',
            'dam_name' => 'nullable|string|max:255',
            'dispersal_from' => 'nullable|string|max:255',
            'owned_by' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:1000',
            'description' => 'nullable|string|max:1000',
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
            $livestock->update([
                'tag_number' => $request->tag_number,
                'name' => $request->name,
                'type' => $request->type,
                'breed' => $request->breed,
                'farm_id' => $request->farm_id,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'weight' => $request->weight,
                'health_status' => $request->health_status ?? 'healthy',
                'status' => $request->status,
                'registry_id' => $request->registry_id,
                'natural_marks' => $request->natural_marks,
                'property_no' => $request->property_no,
                'acquisition_date' => $request->acquisition_date,
                'acquisition_cost' => $request->acquisition_cost,
                'sire_id' => $request->sire_id,
                'sire_name' => $request->sire_name,
                'dam_id' => $request->dam_id,
                'dam_name' => $request->dam_name,
                'dispersal_from' => $request->dispersal_from,
                'owned_by' => $request->owned_by,
                'remarks' => $request->remarks,
                'description' => $request->description,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Livestock updated successfully!'
                ]);
            }

            return redirect()->route('admin.livestock.index')
                ->with('success', 'Livestock updated successfully!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update livestock. Please try again.'
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Failed to update livestock. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified livestock from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $livestock = Livestock::findOrFail($id);
            $livestock->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Livestock deleted successfully!'
                ]);
            }

            return redirect()->route('admin.livestock.index')
                ->with('success', 'Livestock deleted successfully!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete livestock. Please try again.'
                ], 500);
            }
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
            $livestock = Livestock::with(['farm', 'qrCodeGenerator'])->findOrFail($id);
            // Attach previous weight info from latest health record that has weight
            $prev = HealthRecord::where('livestock_id', $livestock->id)
                ->whereNotNull('weight')
                ->orderByDesc('health_date')
                ->orderByDesc('id')
                ->first();
            $payload = $livestock->toArray();
            $payload['previous_weight'] = $prev ? (string) $prev->weight : null;
            $payload['previous_weight_date'] = ($prev && $prev->health_date) ? optional($prev->health_date)->format('Y-m-d') : null;

            return response()->json([
                'success' => true,
                'data' => $payload
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Livestock not found'
            ], 404);
        }
    }

    /**
     * Get production records for a specific livestock (admin).
     */
    public function getLivestockProductionRecords($id)
    {
        try {
            $livestock = Livestock::findOrFail($id);

            $records = ProductionRecord::where('livestock_id', $livestock->id)
                ->orderBy('production_date', 'desc')
                ->take(25)
                ->get()
                ->map(function ($r) {
                    $type = null;
                    if (!empty($r->notes) && preg_match('/\[type:\s*([^\]]+)\]/i', $r->notes, $m)) {
                        $type = ucfirst(strtolower(trim($m[1])));
                    }
                    return [
                        'production_date' => optional($r->production_date)->format('Y-m-d'),
                        'production_type' => $type ?: 'Milk',
                        'quantity' => (string) $r->milk_quantity,
                        'quality' => $r->milk_quality_score,
                        'notes' => $r->notes,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $records,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch production records'
            ], 500);
        }
    }

    /**
     * Get health records for a specific livestock (admin).
     */
    public function getLivestockHealthRecords($id)
    {
        try {
            $livestock = Livestock::findOrFail($id);

            $dbRecords = HealthRecord::with('veterinarian')
                ->where('livestock_id', $livestock->id)
                ->orderByDesc('health_date')
                ->orderByDesc('id')
                ->take(25)
                ->get()
                ->map(function($r){
                    return [
                        'date' => optional($r->health_date)->format('Y-m-d'),
                        'status' => $r->health_status,
                        'treatment' => $r->treatment,
                        'veterinarian' => $r->veterinarian ? ($r->veterinarian->name ?: $r->veterinarian->email) : null,
                        'notes' => $r->notes ?: $r->symptoms,
                    ];
                });

            if ($dbRecords->count() > 0) {
                return response()->json(['success' => true, 'data' => $dbRecords]);
            }

            $remarks = (string)($livestock->remarks ?? '');
            $lines = preg_split('/\r?\n/', $remarks);
            $records = [];
            foreach ($lines as $line) {
                if (strpos($line, '[Health]') === 0) {
                    $entry = ['date' => null, 'status' => null, 'treatment' => null, 'veterinarian' => null, 'notes' => null];
                    $parts = array_map('trim', explode('|', substr($line, 8)));
                    foreach ($parts as $p) {
                        if (stripos($p, 'Date:') === 0) $entry['date'] = trim(substr($p, 5));
                        if (stripos($p, 'Status:') === 0) $entry['status'] = trim(substr($p, 7));
                        if (stripos($p, 'Treatment:') === 0) $entry['treatment'] = trim(substr($p, 10));
                        if (stripos($p, 'Veterinarian:') === 0) $entry['veterinarian'] = trim(substr($p, 13));
                        if (stripos($p, 'Symptoms:') === 0) {
                            $entry['notes'] = isset($entry['notes']) && $entry['notes']
                                ? ($entry['notes'] . '; ' . trim(substr($p, 9)))
                                : trim(substr($p, 9));
                        }
                    }
                    if (!$entry['status'] && isset($livestock->health_status)) {
                        $entry['status'] = $livestock->health_status;
                    }
                    $records[] = $entry;
                }
            }

            return response()->json(['success' => true, 'data' => $records]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch health records'
            ], 500);
        }
    }

    /**
     * Get breeding records for a specific livestock (admin).
     */
    public function getLivestockBreedingRecords($id)
    {
        try {
            $livestock = Livestock::findOrFail($id);

            $dbRecords = BreedingRecord::where('livestock_id', $livestock->id)
                ->orderByDesc('breeding_date')
                ->orderByDesc('id')
                ->take(25)
                ->get()
                ->map(function($r){
                    return [
                        'date' => optional($r->breeding_date)->format('Y-m-d'),
                        'type' => $r->breeding_type ? ucfirst(str_replace('_',' ', $r->breeding_type)) : null,
                        'partner' => $r->partner_livestock_id,
                        'pregnancy' => $r->pregnancy_status ? str_replace('_',' ', $r->pregnancy_status) : null,
                        'success' => $r->breeding_success,
                        'notes' => $r->notes,
                    ];
                });

            if ($dbRecords->count() > 0) {
                return response()->json(['success' => true, 'data' => $dbRecords]);
            }

            $remarks = (string)($livestock->remarks ?? '');
            $lines = preg_split('/\r?\n/', $remarks);
            $records = [];
            foreach ($lines as $line) {
                if (strpos($line, '[Breeding]') === 0) {
                    $entry = ['date' => null, 'type' => null, 'partner' => null, 'pregnancy' => null, 'success' => null, 'notes' => null];
                    $parts = array_map('trim', explode('|', substr($line, 10)));
                    foreach ($parts as $p) {
                        if (stripos($p, 'Date:') === 0) $entry['date'] = trim(substr($p, 5));
                        if (stripos($p, 'Type:') === 0) $entry['type'] = trim(substr($p, 5));
                        if (stripos($p, 'Partner:') === 0) $entry['partner'] = trim(substr($p, 8));
                        if (stripos($p, 'Expected Birth:') === 0) {
                            $val = trim(substr($p, 15));
                            $entry['notes'] = isset($entry['notes']) && $entry['notes']
                                ? ($entry['notes'] . '; Expected: ' . $val)
                                : ('Expected: ' . $val);
                        }
                        if (stripos($p, 'Pregnancy:') === 0) $entry['pregnancy'] = trim(substr($p, 10));
                        if (stripos($p, 'Success:') === 0) $entry['success'] = trim(substr($p, 8));
                        if (stripos($p, 'Notes:') === 0) {
                            $val = trim(substr($p, 6));
                            $entry['notes'] = isset($entry['notes']) && $entry['notes']
                                ? ($entry['notes'] . '; ' . $val)
                                : $val;
                        }
                    }
                    $records[] = $entry;
                }
            }

            return response()->json(['success' => true, 'data' => $records]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch breeding records'
            ], 500);
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
     * Create livestock alert for farmer.
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
            $livestock = Livestock::with('farm')->findOrFail($request->livestock_id);
            
            // Map priority to severity for LivestockAlert
            $severityMap = [
                'low' => 'low',
                'medium' => 'medium', 
                'high' => 'high',
                'urgent' => 'critical'
            ];
            
            // Create livestock alert record
            $alert = \App\Models\LivestockAlert::create([
                'livestock_id' => $request->livestock_id,
                'issued_by' => Auth::id(), // Admin who issued the alert
                'alert_date' => now()->toDateString(),
                'topic' => $request->issue_type,
                'description' => $request->description,
                'severity' => $severityMap[$request->priority],
                'status' => 'active',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Livestock alert created successfully',
                'alert' => $alert
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create livestock alert: ' . $e->getMessage()
            ], 500);
        }
    }
}
