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

    /**
     * Lightweight livestock search for dropdowns (admin sees all, farmer sees own).
     */
    public function search(Request $request)
    {
        try {
            $q = trim((string)$request->get('q', ''));
            $gender = $request->get('gender'); // optional: male|female
            $type = $request->get('type');     // optional: cow|buffalo|goat|sheep
            $limit = (int) $request->get('limit', 20);
            if ($limit < 1) $limit = 20; if ($limit > 50) $limit = 50;

            $query = Livestock::query()->select('id', 'tag_number', 'name', 'type', 'breed', 'gender');

            // Scope by role
            if (Auth::check() && Auth::user()->role === 'farmer') {
                $query->where('owner_id', Auth::id());
            }

            if ($q !== '') {
                $query->where(function($w) use ($q) {
                    $like = '%' . str_replace(['%','_'], ['\%','\_'], $q) . '%';
                    $w->where('tag_number', 'like', $like)
                      ->orWhere('name', 'like', $like)
                      ->orWhere('registry_id', 'like', $like);
                });
            }
            if ($gender) { $query->where('gender', $gender); }
            if ($type)   { $query->where('type', $type); }

            $results = $query->orderBy('tag_number')->limit($limit)->get();

            return response()->json(['success' => true, 'data' => $results]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Search failed'], 500);
        }
    }

    /**
     * Get growth records for a specific livestock (admin).
     * Structure: date, weight_kg, height_cm, heart_girth_cm, body_length_cm
     */
    public function getLivestockGrowthRecords($id)
    {
        try {
            $livestock = Livestock::findOrFail($id);

            // Fallback: parse from remarks
            $remarks = (string)($livestock->remarks ?? '');
            $lines = preg_split('/\r?\n/', $remarks);
            $records = [];
            foreach ($lines as $line) {
                $trim = trim($line);
                if ($trim === '') continue;
                if (stripos($trim, '[Growth]') === 0 || stripos($trim, '[Growth Record]') === 0 || stripos($trim, '[GrowthRecord]') === 0) {
                    $entry = [
                        'date' => null,
                        'weight_kg' => null,
                        'height_cm' => null,
                        'heart_girth_cm' => null,
                        'body_length_cm' => null,
                    ];
                    $payload = preg_replace('/^\[(?:Growth(?:\s*Record)?|GrowthRecord)\]\s*/i', '', $trim);
                    $parts = array_map('trim', explode('|', $payload));
                    foreach ($parts as $p) {
                        $label = strtolower(substr($p, 0, strpos($p, ':') !== false ? strpos($p, ':') : 0));
                        $val = trim(substr($p, strpos($p, ':') !== false ? strpos($p, ':') + 1 : 0));
                        if ($label === 'date') $entry['date'] = $val;
                        if (in_array($label, ['weight','weight kg','weight (kg)'])) $entry['weight_kg'] = $val;
                        if (in_array($label, ['height','height cm','height (cm)'])) $entry['height_cm'] = $val;
                        if (in_array($label, ['heart girth','heart girth cm','heart_girth','heart_girth cm','heart girth (cm)'])) $entry['heart_girth_cm'] = $val;
                        if (in_array($label, ['body length','body length cm','body_length','body_length cm','body length (cm)'])) $entry['body_length_cm'] = $val;
                    }
                    $records[] = $entry;
                }
            }

            return response()->json(['success' => true, 'data' => $records]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch growth records'
            ], 500);
        }
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

    public function storeLivestockGrowthRecord(Request $request, $id)
    {
        $request->validate([
            'growth_date' => 'nullable|date',
            'weight_kg' => 'nullable|numeric|min:0',
            'height_cm' => 'nullable|numeric|min:0',
            'heart_girth_cm' => 'nullable|numeric|min:0',
            'body_length_cm' => 'nullable|numeric|min:0',
        ]);

        $livestock = Livestock::findOrFail($id);

        $parts = [];
        if ($request->growth_date) $parts[] = 'Date: ' . $request->growth_date;
        if ($request->weight_kg !== null && $request->weight_kg !== '') $parts[] = 'Weight: ' . $request->weight_kg;
        if ($request->height_cm !== null && $request->height_cm !== '') $parts[] = 'Height: ' . $request->height_cm;
        if ($request->heart_girth_cm !== null && $request->heart_girth_cm !== '') $parts[] = 'Heart girth: ' . $request->heart_girth_cm;
        if ($request->body_length_cm !== null && $request->body_length_cm !== '') $parts[] = 'Body length: ' . $request->body_length_cm;

        if (empty($parts)) {
            return response()->json([
                'success' => false,
                'message' => 'Provide at least one field to save.'
            ], 422);
        }

        $entry = '[Growth] ' . implode(' | ', $parts);
        $updatedRemarks = trim(($livestock->remarks ? $livestock->remarks . "\n" : '') . $entry);

        $payload = ['remarks' => $updatedRemarks];
        if ($request->weight_kg !== null && $request->weight_kg !== '') {
            $payload['weight'] = $request->weight_kg;
        }
        $livestock->update($payload);

        return response()->json([
            'success' => true,
            'message' => 'Growth record saved.'
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
            'sire_breed' => 'nullable|string|max:255',
            'dam_id' => 'nullable|string|max:255',
            'dam_name' => 'nullable|string|max:255',
            'dam_breed' => 'nullable|string|max:255',
            'dispersal_from' => 'nullable|string|max:255',
            'owned_by' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:1000',
            'description' => 'nullable|string|max:1000',
            // New cooperative/basic-info fields
            'distinct_characteristics' => 'nullable|string',
            'source_origin' => 'nullable|string|max:255',
            'cooperator_name' => 'nullable|string|max:255',
            'date_released' => 'nullable|date',
            'cooperative_name' => 'nullable|string|max:255',
            'cooperative_address' => 'nullable|string|max:255',
            'cooperative_contact_no' => 'nullable|string|max:255',
            'in_charge' => 'nullable|string|max:255',
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
                'sire_breed' => $request->sire_breed,
                'dam_id' => $request->dam_id,
                'dam_name' => $request->dam_name,
                'dam_breed' => $request->dam_breed,
                'dispersal_from' => $request->dispersal_from,
                'owned_by' => $request->owned_by,
                'remarks' => $request->remarks,
                'description' => $request->description,
                'distinct_characteristics' => $request->distinct_characteristics,
                'source_origin' => $request->source_origin,
                'cooperator_name' => $request->cooperator_name,
                'date_released' => $request->date_released,
                'cooperative_name' => $request->cooperative_name,
                'cooperative_address' => $request->cooperative_address,
                'cooperative_contact_no' => $request->cooperative_contact_no,
                'in_charge' => $request->in_charge,
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
            'sire_breed' => 'nullable|string|max:255',
            'dam_id' => 'nullable|string|max:255',
            'dam_name' => 'nullable|string|max:255',
            'dam_breed' => 'nullable|string|max:255',
            'dispersal_from' => 'nullable|string|max:255',
            'owned_by' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:1000',
            'description' => 'nullable|string|max:1000',
            // New cooperative/basic-info fields
            'distinct_characteristics' => 'nullable|string',
            'source_origin' => 'nullable|string|max:255',
            'cooperator_name' => 'nullable|string|max:255',
            'date_released' => 'nullable|date',
            'cooperative_name' => 'nullable|string|max:255',
            'cooperative_address' => 'nullable|string|max:255',
            'cooperative_contact_no' => 'nullable|string|max:255',
            'in_charge' => 'nullable|string|max:255',
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
                'sire_breed' => $request->sire_breed,
                'dam_id' => $request->dam_id,
                'dam_name' => $request->dam_name,
                'dam_breed' => $request->dam_breed,
                'dispersal_from' => $request->dispersal_from,
                'owned_by' => $request->owned_by,
                'remarks' => $request->remarks,
                'description' => $request->description,
                'distinct_characteristics' => $request->distinct_characteristics,
                'source_origin' => $request->source_origin,
                'cooperator_name' => $request->cooperator_name,
                'date_released' => $request->date_released,
                'cooperative_name' => $request->cooperative_name,
                'cooperative_address' => $request->cooperative_address,
                'cooperative_contact_no' => $request->cooperative_contact_no,
                'in_charge' => $request->in_charge,
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
            $remarks = (string)($livestock->remarks ?? '');
            $lines = preg_split('/\r?\n/', $remarks);
            $records = [];
            foreach ($lines as $line) {
                $trim = trim($line);
                if ($trim === '') { continue; }
                if (strpos($trim, '[Calving]') === 0) {
                    $entry = [
                        'date_of_calving' => null,
                        'calf_id' => null,
                        'sex' => null,
                        'breed' => null,
                        'sire_id' => null,
                        'milk_production' => null,
                        'dim' => null,
                    ];
                    $parts = array_map('trim', explode('|', substr($trim, 9)));
                    foreach ($parts as $p) {
                        $pLower = strtolower($p);
                        $val = trim(substr($p, strpos($p, ':') !== false ? strpos($p, ':') + 1 : 0));
                        if (stripos($pLower, 'date of calving:') === 0 || stripos($pLower, 'date:') === 0) $entry['date_of_calving'] = $val;
                        if (stripos($pLower, 'calf id') === 0 || stripos($pLower, 'calf:') === 0) $entry['calf_id'] = $val;
                        if (stripos($pLower, 'sex:') === 0) $entry['sex'] = ucfirst(strtolower($val));
                        if (stripos($pLower, 'breed:') === 0) $entry['breed'] = $val;
                        if (stripos($pLower, 'sire id') === 0) $entry['sire_id'] = $val;
                        if (stripos($pLower, 'milk') === 0) $entry['milk_production'] = $val;
                        if (stripos($pLower, 'dim') === 0 || stripos($pLower, 'days in milk') === 0) $entry['dim'] = $val;
                    }
                    // Fallbacks: try to infer milk production and DIM from ProductionRecord if not present
                    if (!$entry['milk_production'] && !empty($entry['date_of_calving'])) {
                        $firstProd = ProductionRecord::where('livestock_id', $livestock->id)
                            ->whereDate('production_date', '>=', $entry['date_of_calving'])
                            ->orderBy('production_date', 'asc')
                            ->first();
                        if ($firstProd) {
                            $entry['milk_production'] = (string) $firstProd->milk_quantity;
                            try {
                                $entry['dim'] = \Carbon\Carbon::parse($firstProd->production_date)
                                    ->diffInDays(\Carbon\Carbon::parse($entry['date_of_calving']));
                            } catch (\Exception $e) { $entry['dim'] = null; }
                        }
                    }
                    $records[] = $entry;
                }
            }
            return response()->json(['success' => true, 'data' => $records]);
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
                    $notes = (string)($r->notes ?? '');
                    $observations = $r->symptoms ?: $notes;
                    $test = null; $diagnosis = null;
                    if (preg_match('/\bTest\s*Performed\s*:\s*([^|\n]+)/i', $notes, $m) || preg_match('/\bTest\s*:\s*([^|\n]+)/i', $notes, $m)) {
                        $test = trim($m[1]);
                    }
                    if (preg_match('/\bDiagnosis\s*:\s*([^|\n]+)/i', $notes, $m) || preg_match('/\bRemarks\s*:\s*([^|\n]+)/i', $notes, $m)) {
                        $diagnosis = trim($m[1]);
                    }
                    if (!$diagnosis && $r->health_status) { $diagnosis = $r->health_status; }
                    return [
                        'date' => optional($r->health_date)->format('Y-m-d'),
                        'observations' => $observations,
                        'test' => $test,
                        'diagnosis' => $diagnosis,
                        'drugs' => $r->treatment,
                        'signature' => $r->veterinarian ? ($r->veterinarian->name ?: $r->veterinarian->email) : null,
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
                    $entry = ['date' => null, 'observations' => null, 'test' => null, 'diagnosis' => null, 'drugs' => null, 'signature' => null];
                    $parts = array_map('trim', explode('|', substr($line, 8)));
                    foreach ($parts as $p) {
                        if (stripos($p, 'Date:') === 0) $entry['date'] = trim(substr($p, 5));
                        if (stripos($p, 'Observations:') === 0 || stripos($p, 'Symptoms:') === 0) $entry['observations'] = trim(substr($p, strpos($p, ':') + 1));
                        if (stripos($p, 'Test Performed:') === 0 || stripos($p, 'Test:') === 0) $entry['test'] = trim(substr($p, strpos($p, ':') + 1));
                        if (stripos($p, 'Diagnosis:') === 0 || stripos($p, 'Remarks:') === 0) $entry['diagnosis'] = trim(substr($p, strpos($p, ':') + 1));
                        if (stripos($p, 'Drugs:') === 0 || stripos($p, 'Treatment:') === 0) $entry['drugs'] = trim(substr($p, strpos($p, ':') + 1));
                        if (stripos($p, 'Signature:') === 0 || stripos($p, 'Veterinarian:') === 0) $entry['signature'] = trim(substr($p, strpos($p, ':') + 1));
                    }
                    if (!$entry['diagnosis'] && isset($livestock->health_status)) {
                        $entry['diagnosis'] = $livestock->health_status;
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
                    $notes = (string)($r->notes ?? '');
                    $bcs = null; $vo = null; $ut = null; $md = null; $pdDate = null; $pdResult = null; $bullId = null; $bullName = null; $sig = null;
                    if (preg_match('/\bBCS\s*:\s*([0-9.]+)/i', $notes, $m)) $bcs = trim($m[1]);
                    if (preg_match('/\bVO\s*:\s*([123])/i', $notes, $m)) $vo = trim($m[1]);
                    if (preg_match('/\bUT\s*:\s*([123])/i', $notes, $m)) $ut = trim($m[1]);
                    if (preg_match('/\bMD\s*:\s*([123])/i', $notes, $m)) $md = trim($m[1]);
                    if (preg_match('/\bPD\s*Date\s*:\s*([^|\n]+)/i', $notes, $m) || preg_match('/\bPD\s*:\s*([^|\n]+)/i', $notes, $m)) $pdDate = trim($m[1]);
                    if (preg_match('/\bPD\s*Result\s*:\s*([^|\n]+)/i', $notes, $m)) $pdResult = trim($m[1]);
                    if (preg_match('/\b(Bull\s*ID|ID\s*No\.)\s*:\s*([^|\n]+)/i', $notes, $m)) $bullId = trim($m[2]);
                    if (preg_match('/\b(Bull\s*Name|Name)\s*:\s*([^|\n]+)/i', $notes, $m)) $bullName = trim($m[2]);
                    if (preg_match('/\b(AI\s*Tech|Signature)\s*:\s*([^|\n]+)/i', $notes, $m)) $sig = trim($m[2]);

                    // Resolve partner livestock to ID/Name if available
                    $partnerIdText = $r->partner_livestock_id ? (string)$r->partner_livestock_id : null;
                    if ($partnerIdText) {
                        $partner = Livestock::query()
                            ->where('tag_number', $partnerIdText)
                            ->orWhere('id', $partnerIdText)
                            ->first();
                        if ($partner) {
                            $bullId = $bullId ?: $partner->tag_number;
                            $bullName = $bullName ?: $partner->name;
                        } else {
                            $bullId = $bullId ?: $partnerIdText;
                        }
                    }
                    // Fallback for PD result from pregnancy/success
                    if (!$pdResult && $r->pregnancy_status) {
                        $pdResult = strtolower($r->pregnancy_status) === 'pregnant' ? 'Positive' : (strtolower($r->pregnancy_status) === 'not_pregnant' ? 'Negative' : $r->pregnancy_status);
                    }
                    if (!$sig && $r->recorded_by) {
                        $u = User::find($r->recorded_by);
                        if ($u) $sig = $u->name ?: $u->email;
                    }

                    return [
                        'date_of_service' => optional($r->breeding_date)->format('Y-m-d'),
                        'bcs' => $bcs,
                        'vo' => $vo,
                        'ut' => $ut,
                        'md' => $md,
                        'bull_id_no' => $bullId,
                        'bull_name' => $bullName,
                        'pd_date' => $pdDate,
                        'pd_result' => $pdResult,
                        'ai_signature' => $sig,
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
                    $entry = [
                        'date_of_service' => null,
                        'bcs' => null,
                        'vo' => null,
                        'ut' => null,
                        'md' => null,
                        'bull_id_no' => null,
                        'bull_name' => null,
                        'pd_date' => null,
                        'pd_result' => null,
                        'ai_signature' => null,
                    ];
                    $parts = array_map('trim', explode('|', substr($line, 10)));
                    foreach ($parts as $p) {
                        if (stripos($p, 'Date:') === 0) $entry['date_of_service'] = trim(substr($p, 5));
                        if (stripos($p, 'BCS:') === 0) $entry['bcs'] = trim(substr($p, 4));
                        if (stripos($p, 'VO:') === 0) $entry['vo'] = trim(substr($p, 3));
                        if (stripos($p, 'UT:') === 0) $entry['ut'] = trim(substr($p, 3));
                        if (stripos($p, 'MD:') === 0) $entry['md'] = trim(substr($p, 3));
                        if (stripos($p, 'Bull ID:') === 0 || stripos($p, 'ID No.:') === 0) $entry['bull_id_no'] = trim(substr($p, strpos($p, ':')+1));
                        if (stripos($p, 'Bull Name:') === 0 || preg_match('/^Name\s*:/i', $p)) $entry['bull_name'] = trim(substr($p, strpos($p, ':')+1));
                        if (stripos($p, 'PD Date:') === 0) $entry['pd_date'] = trim(substr($p, 8));
                        if (stripos($p, 'PD Result:') === 0) $entry['pd_result'] = trim(substr($p, 10));
                        if (stripos($p, 'AI Tech:') === 0 || stripos($p, 'Signature:') === 0) $entry['ai_signature'] = trim(substr($p, strpos($p, ':')+1));
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
