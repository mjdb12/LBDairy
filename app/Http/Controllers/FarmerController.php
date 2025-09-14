<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\Livestock;
use App\Models\Farm;
use App\Models\Issue;
use App\Models\AuditLog;
use App\Models\LivestockAlert;
use App\Models\ProductionRecord;
use App\Models\Sale;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class FarmerController extends Controller
{
    /**
     * Update the farmer's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'farm_name' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Update farm name if provided
        if ($request->farm_name && $user->farms->first()) {
            $user->farms->first()->update(['name' => $request->farm_name]);
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Change the farmer's password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password changed successfully!');
    }

    /**
     * Upload profile picture for the farmer.
     */
    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        try {
            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                
                // Generate unique filename
                $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Store the file in the public/img directory
                $file->move(public_path('img'), $filename);
                
                // Delete old profile picture if it exists and is not the default
                if ($user->profile_image && $user->profile_image !== 'ronaldo.png' && file_exists(public_path('img/' . $user->profile_image))) {
                    unlink(public_path('img/' . $user->profile_image));
                }
                
                // Update user's profile image
                $user->update(['profile_image' => $filename]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Profile picture updated successfully!',
                    'image_url' => asset('img/' . $filename)
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No image file provided.'
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading profile picture: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the farmer's issues (created by admins).
     */
    public function issues()
    {
        $user = Auth::user();
        
        // Get issues for the farmer's farms that were created by admins
        $issues = Issue::with(['reportedBy', 'livestock'])
            ->whereHas('farm', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->whereHas('reportedBy', function($query) {
                $query->where('role', 'admin');
            })
            ->orderBy('date_reported', 'desc')
            ->get();

        // Get livestock alerts for the farmer's livestock that were created by admins
        $livestockAlerts = LivestockAlert::with(['livestock', 'issuedBy'])
            ->whereHas('livestock.farm', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->whereHas('issuedBy', function($query) {
                $query->where('role', 'admin');
            })
            ->where('status', 'active')
            ->orderBy('alert_date', 'desc')
            ->get();

        // Get livestock for the farmer's farms
        $livestock = Livestock::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->get();

        // Get farms for the farmer
        $farms = Farm::where('owner_id', $user->id)->get();

        $totalIssues = $issues->count();
        $pendingIssues = $issues->where('status', 'Pending')->count();
        $urgentIssues = $issues->where('priority', 'High')->where('status', '!=', 'Resolved')->count();
        $resolvedIssues = $issues->where('status', 'Resolved')->count();

        // Ensure we always have valid numbers
        $totalIssues = $totalIssues ?: 0;
        $pendingIssues = $pendingIssues ?: 0;
        $urgentIssues = $urgentIssues ?: 0;
        $resolvedIssues = $resolvedIssues ?: 0;

        // Get scheduled inspections for the farmer
        $scheduledInspections = \App\Models\Inspection::with('scheduledBy')
            ->where('farmer_id', $user->id)
            ->orderBy('inspection_date', 'asc')
            ->get();

        return view('farmer.issues', compact(
            'issues',
            'livestockAlerts',
            'livestock',
            'farms',
            'totalIssues',
            'pendingIssues',
            'urgentIssues',
            'resolvedIssues',
            'scheduledInspections'
        ));
    }

    /**
     * Display the specified issue (read-only for farmers).
     */
    public function showIssue($id)
    {
        $user = Auth::user();
        $issue = Issue::with('reportedBy')->whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->whereHas('reportedBy', function($query) {
            $query->where('role', 'admin');
        })->findOrFail($id);

        return response()->json(['success' => true, 'issue' => $issue]);
    }

    /**
     * Display the farmer's issue alerts (alerts created by farmer for admins).
     */
    public function issueAlerts()
    {
        $user = Auth::user();
        
        // Get alerts created by this farmer for their livestock
        $alerts = LivestockAlert::with('livestock.farm')
            ->whereHas('livestock.farm', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->where('issued_by', $user->id)
            ->orderBy('alert_date', 'desc')
            ->get();

        // Get livestock for the farmer's farms
        $livestock = Livestock::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->get();

        // Calculate statistics
        $totalAlerts = $alerts->count();
        $activeAlerts = $alerts->where('status', 'active')->count();
        $criticalAlerts = $alerts->where('severity', 'critical')->where('status', 'active')->count();
        $resolvedAlerts = $alerts->where('status', 'resolved')->count();

        return view('farmer.issue-alerts', compact(
            'alerts',
            'livestock',
            'totalAlerts',
            'activeAlerts',
            'criticalAlerts',
            'resolvedAlerts'
        ));
    }

    /**
     * Store a new alert created by farmer.
     */
    public function storeAlert(Request $request)
    {
        $request->validate([
            'livestock_id' => 'required|exists:livestock,id',
            'topic' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'alert_date' => 'required|date',
        ]);

        $user = Auth::user();
        
        // Verify the livestock belongs to the farmer's farm
        $livestock = Livestock::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($request->livestock_id);

        $alert = LivestockAlert::create([
            'livestock_id' => $request->livestock_id,
            'issued_by' => $user->id,
            'alert_date' => $request->alert_date,
            'topic' => $request->topic,
            'description' => $request->description,
            'severity' => $request->severity,
            'status' => 'active',
        ]);

        return redirect()->route('farmer.issue-alerts')->with('success', 'Alert created successfully!');
    }

    /**
     * Update alert status (mark as resolved or dismissed).
     */
    public function updateAlertStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:resolved,dismissed',
            'resolution_notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $alert = LivestockAlert::whereHas('livestock.farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->where('issued_by', $user->id)->findOrFail($id);

        if ($request->status === 'resolved') {
            $alert->markAsResolved($request->resolution_notes);
        } else {
            $alert->dismiss($request->resolution_notes);
        }

        return redirect()->route('farmer.issue-alerts')->with('success', 'Alert status updated successfully!');
    }

    /**
     * Display the specified alert (read-only for farmers).
     */
    public function showAlert($id)
    {
        $user = Auth::user();
        $alert = LivestockAlert::with(['livestock', 'issuedBy'])
            ->whereHas('livestock.farm', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->findOrFail($id);

        return response()->json(['success' => true, 'alert' => $alert]);
    }

    /**
     * Mark livestock alert as read.
     */
    public function markAlertAsRead($id)
    {
        try {
            $user = Auth::user();
            
            $alert = LivestockAlert::whereHas('livestock.farm', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->findOrFail($id);
            
            $alert->update(['status' => 'resolved']);
            
            return response()->json([
                'success' => true,
                'message' => 'Alert marked as read successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark alert as read'
            ], 500);
        }
    }


    /**
     * Display the farmer's livestock inventory.
     */
    public function livestock()
    {
        $user = Auth::user();
        
        // Get livestock for the farmer's farms
        $livestock = Livestock::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->get();

        // Get farms for the farmer
        $farms = Farm::where('owner_id', $user->id)->get();

        $totalLivestock = $livestock->count();
        $healthyLivestock = $livestock->where('health_status', 'healthy')->count();
        $attentionNeeded = $livestock->where('health_status', '!=', 'healthy')->count();
        $productionReady = $livestock->where('status', 'active')->count(); // You can adjust this logic based on your needs

        return view('farmer.livestock', compact(
            'livestock',
            'farms',
            'totalLivestock',
            'healthyLivestock',
            'attentionNeeded',
            'productionReady'
        ));
    }

    /**
     * Store a newly created livestock.
     */
    public function storeLivestock(Request $request)
    {
        $request->validate([
            'tag_number' => 'required|string|max:255|unique:livestock',
            'name' => 'required|string|max:255',
            'type' => 'required|in:cow,buffalo,goat,sheep',
            'breed' => 'required|in:holstein,jersey,guernsey,ayrshire,brown_swiss,other',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'weight' => 'nullable|numeric|min:0',
            'health_status' => 'required|in:healthy,sick,recovering,under_treatment',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $user = Auth::user();
            $farm = $user->farms->first();
            
            if (!$farm) {
                return response()->json([
                    'success' => false,
                    'message' => 'You need to have a farm to add livestock.'
                ], 400);
            }

            $livestock = Livestock::create([
                'tag_number' => $request->tag_number,
                'name' => $request->name,
                'type' => $request->type,
                'breed' => $request->breed,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'weight' => $request->weight,
                'health_status' => $request->health_status,
                'status' => $request->status,
                'farm_id' => $farm->id,
                'owner_id' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Livestock added successfully!',
                'livestock' => $livestock
            ]);
        } catch (\Exception $e) {
            Log::error('Livestock creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add livestock. Please try again.'
            ], 500);
        }
    }

    /**
     * Display the specified livestock.
     */
    public function showLivestock($id)
    {
        $user = Auth::user();
        $livestock = Livestock::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        return response()->json([
            'success' => true,
            'livestock' => $livestock
        ]);
    }

    /**
     * Print the specified livestock record.
     */
    public function printLivestock($id)
    {
        $user = Auth::user();
        $livestock = Livestock::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        // Get related data
        $farm = $livestock->farm;
        $owner = $livestock->owner;
        
        // Get production records for this livestock
        $productionRecords = $livestock->productionRecords()
            ->orderBy('production_date', 'desc')
            ->take(15)
            ->get();
            
        // Mock data for growth records (you can replace with actual data when available)
        $growthRecords = collect([
            [
                'date' => $livestock->birth_date ? $livestock->birth_date->format('Y-m-d') : 'N/A',
                'weight' => $livestock->weight ?? 'N/A',
                'height' => 'N/A',
                'heart_girth' => 'N/A',
                'body_length' => 'N/A'
            ]
        ]);
        
        // Mock data for breeding records (you can replace with actual data when available)
        $breedingRecords = collect([]);
        
        // Mock data for calving records (you can replace with actual data when available)
        $calvingRecords = collect([]);

        return view('farmer.livestock-print', compact(
            'livestock',
            'farm',
            'owner',
            'productionRecords',
            'growthRecords',
            'breedingRecords',
            'calvingRecords'
        ));
    }

    /**
     * Check QR code status for livestock (farmers can only view/download).
     */
    public function generateQRCode($id)
    {
        try {
            $user = Auth::user();
            $livestock = Livestock::whereHas('farm', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->with('qrCodeGenerator')->findOrFail($id);
            
            // Check if QR code has been generated
            if ($livestock->qr_code_generated && $livestock->qr_code_url) {
                return response()->json([
                    'success' => true,
                    'qr_code_exists' => true,
                    'qr_code' => $livestock->qr_code_url,
                    'livestock_id' => $livestock->tag_number,
                    'generated_at' => $livestock->qr_code_generated_at,
                    'generated_by' => $livestock->qrCodeGenerator ? $livestock->qrCodeGenerator->name : 'Unknown'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'qr_code_exists' => false,
                    'message' => 'QR code has not been generated yet. Please contact an administrator to generate the QR code.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check QR code status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified livestock.
     */
    public function editLivestock($id)
    {
        $user = Auth::user();
        $livestock = Livestock::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        return response()->json([
            'success' => true,
            'livestock' => $livestock
        ]);
    }

    /**
     * Update the specified livestock.
     */
    public function updateLivestock(Request $request, $id)
    {
        $request->validate([
            'tag_number' => 'required|string|max:255|unique:livestock,tag_number,' . $id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:cow,buffalo,goat,sheep',
            'breed' => 'required|in:holstein,jersey,guernsey,ayrshire,brown_swiss,other',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'weight' => 'nullable|numeric|min:0',
            'health_status' => 'required|in:healthy,sick,recovering,under_treatment',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $user = Auth::user();
            $livestock = Livestock::whereHas('farm', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->findOrFail($id);

            $livestock->update([
                'tag_number' => $request->tag_number,
                'name' => $request->name,
                'type' => $request->type,
                'breed' => $request->breed,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'weight' => $request->weight,
                'health_status' => $request->health_status,
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Livestock updated successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Livestock update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update livestock. Please try again.'
            ], 500);
        }
    }

    /**
     * Update the specified livestock status.
     */
    public function updateLivestockStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $user = Auth::user();
            $livestock = Livestock::whereHas('farm', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->findOrFail($id);

            $livestock->update([
                'status' => ucfirst($request->status),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Livestock status updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update livestock status. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove the specified livestock.
     */
    public function deleteLivestock($id)
    {
        try {
            $user = Auth::user();
            $livestock = Livestock::whereHas('farm', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->findOrFail($id);

            $livestock->delete();

            return response()->json([
                'success' => true,
                'message' => 'Livestock deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete livestock. Please try again.'
            ], 500);
        }
    }

    /**
     * Display the farmer's production records.
     */
    public function production()
    {
        $user = Auth::user();
        
        // Get farmer's farms
        $farms = Farm::where('owner_id', $user->id)->get();
        $farmIds = $farms->pluck('id')->toArray();
        
        if (empty($farmIds)) {
            return view('farmer.production', [
                'totalProduction' => 0,
                'monthlyProduction' => 0,
                'averageDaily' => 0,
                'qualityScore' => 0,
                'productionData' => [],
                'livestockList' => [],
                'productionStats' => []
            ]);
        }

        // Calculate production statistics
        $totalProduction = ProductionRecord::whereIn('farm_id', $farmIds)->sum('milk_quantity');
        
        // Monthly production (current month)
        $currentMonth = now()->format('Y-m');
        $monthlyProduction = ProductionRecord::whereIn('farm_id', $farmIds)
            ->whereRaw("DATE_FORMAT(production_date, '%Y-%m') = ?", [$currentMonth])
            ->sum('milk_quantity');
        
        // Average daily production (current month)
        $averageDaily = ProductionRecord::whereIn('farm_id', $farmIds)
            ->whereRaw("DATE_FORMAT(production_date, '%Y-%m') = ?", [$currentMonth])
            ->avg('milk_quantity') ?? 0;
        
        // Average quality score
        $qualityScore = ProductionRecord::whereIn('farm_id', $farmIds)
            ->avg('milk_quality_score') ?? 0;

        // Get production data for table
        $productionData = ProductionRecord::whereIn('farm_id', $farmIds)
            ->with(['livestock', 'farm'])
            ->orderBy('production_date', 'desc')
            ->take(20)
            ->get()
            ->map(function ($record) {
                return [
                    'id' => $record->id,
                    'production_date' => $record->production_date->format('M d, Y'),
                    'livestock_name' => $record->livestock ? $record->livestock->name : 'Unknown',
                    'livestock_tag' => $record->livestock ? $record->livestock->tag_number : 'N/A',
                    'milk_quantity' => $record->milk_quantity,
                    'milk_quality_score' => $record->milk_quality_score,
                    'notes' => $record->notes,
                    'farm_name' => $record->farm ? $record->farm->name : 'Unknown'
                ];
            });

        // Get livestock list for dropdown
        $livestockList = Livestock::whereIn('farm_id', $farmIds)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        // Production statistics for charts
        $productionStats = [
            'monthly_trend' => ProductionRecord::whereIn('farm_id', $farmIds)
                ->selectRaw("DATE_FORMAT(production_date, '%Y-%m') as month, SUM(milk_quantity) as total_production")
                ->where('production_date', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->map(function ($item) {
                    return [
                        'month' => date('M', strtotime($item->month . '-01')),
                        'production' => $item->total_production
                    ];
                }),
            
            'quality_distribution' => ProductionRecord::whereIn('farm_id', $farmIds)
                ->selectRaw('milk_quality_score, COUNT(*) as count')
                ->whereNotNull('milk_quality_score')
                ->groupBy('milk_quality_score')
                ->orderBy('milk_quality_score')
                ->get()
                ->map(function ($item) {
                    return [
                        'score' => $item->milk_quality_score,
                        'count' => $item->count
                    ];
                }),
            
            'top_producers' => ProductionRecord::whereIn('farm_id', $farmIds)
                ->with('livestock')
                ->selectRaw('livestock_id, SUM(milk_quantity) as total_production')
                ->groupBy('livestock_id')
                ->orderBy('total_production', 'desc')
                ->take(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'livestock_name' => $item->livestock ? $item->livestock->name : 'Unknown',
                        'total_production' => $item->total_production
                    ];
                })
        ];

        return view('farmer.production', compact(
            'totalProduction',
            'monthlyProduction',
            'averageDaily',
            'qualityScore',
            'productionData',
            'livestockList',
            'productionStats'
        ));
    }

    /**
     * Store a newly created production record.
     */
    public function storeProduction(Request $request)
    {
        $request->validate([
            'production_date' => 'required|date',
            'livestock_id' => 'required|exists:livestock,id',
            'milk_quantity' => 'required|numeric|min:0',
            'milk_quality_score' => 'nullable|integer|min:1|max:10',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        
        // Get the livestock and verify it belongs to the user's farm
        $livestock = Livestock::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($request->livestock_id);

        try {
            $productionRecord = \App\Models\ProductionRecord::create([
                'farm_id' => $livestock->farm_id,
                'livestock_id' => $livestock->id,
                'production_date' => $request->production_date,
                'milk_quantity' => $request->milk_quantity,
                'milk_quality_score' => $request->milk_quality_score,
                'notes' => $request->notes,
                'recorded_by' => $user->id,
            ]);

            // Log the action
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'production_created',
                'description' => "Production record created for livestock {$livestock->tag_number}",
                'severity' => 'info',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('farmer.production')->with('success', 'Production record added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create production record: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified production record.
     */
    public function showProduction($id)
    {
        $user = Auth::user();
        $productionRecord = \App\Models\ProductionRecord::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        return response()->json($productionRecord);
    }

    /**
     * Update the specified production record.
     */
    public function updateProduction(Request $request, $id)
    {
        $request->validate([
            'production_date' => 'required|date',
            'livestock_id' => 'required|exists:livestock,id',
            'milk_quantity' => 'required|numeric|min:0',
            'milk_quality_score' => 'nullable|integer|min:1|max:10',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $productionRecord = \App\Models\ProductionRecord::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        $productionRecord->update([
            'production_date' => $request->production_date,
            'livestock_id' => $request->livestock_id,
            'milk_quantity' => $request->milk_quantity,
            'milk_quality_score' => $request->milk_quality_score,
            'notes' => $request->notes,
        ]);

        return redirect()->route('farmer.production')->with('success', 'Production record updated successfully!');
    }

    /**
     * Remove the specified production record.
     */
    public function deleteProduction(Request $request, $id)
    {
        $user = Auth::user();
        $productionRecord = \App\Models\ProductionRecord::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        try {
            $productionRecord->delete();

            // Log the action
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'production_deleted',
                'description' => "Production record #{$productionRecord->id} deleted",
                'severity' => 'warning',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Production record deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete production record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get production history for AJAX requests.
     */
    public function productionHistory(Request $request)
    {
        $user = Auth::user();
        $sortBy = $request->get('sort', 'production_date');
        $filterBy = $request->get('filter', 'all');

        $query = \App\Models\ProductionRecord::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        });

        // Apply filters
        if ($filterBy !== 'all') {
            $query->where('milk_quality_score', '>=', $filterBy);
        }

        $records = $query->orderBy($sortBy, 'desc')->get();

        return response()->json([
            'success' => true,
            'records' => $records
        ]);
    }

    /**
     * Display the farmer's expenses.
     */
    public function expenses()
    {
        $user = Auth::user();
        
        // Get farmer's farms
        $farms = Farm::where('owner_id', $user->id)->get();
        $farmIds = $farms->pluck('id')->toArray();
        
        if (empty($farmIds)) {
            return view('farmer.expenses', [
                'expenses' => [],
                'totalExpenses' => 0,
                'feedExpenses' => 0,
                'veterinaryExpenses' => 0,
                'maintenanceExpenses' => 0,
                'expenseChange' => 0,
                'feedChange' => 0,
                'veterinaryChange' => 0,
                'maintenanceChange' => 0,
                'budgetExceeded' => false,
                'budgetExcess' => 0,
                'expensesData' => [],
                'farms' => [],
                'expenseStats' => []
            ]);
        }

        // Get expenses for the farmer's farms
        $expenses = \App\Models\Expense::whereIn('farm_id', $farmIds)
            ->orderBy('expense_date', 'desc')
            ->get();

        // Calculate total expenses
        $totalExpenses = $expenses->sum('amount');
        
        // Calculate expenses by type
        $feedExpenses = $expenses->where('expense_type', 'feed')->sum('amount');
        $veterinaryExpenses = $expenses->where('expense_type', 'medicine')->sum('amount');
        $maintenanceExpenses = $expenses->where('expense_type', 'equipment')->sum('amount');
        
        // Calculate month-over-month changes
        $currentMonthExpenses = $expenses->where('expense_date', '>=', now()->startOfMonth())->sum('amount');
        $lastMonthExpenses = $expenses->where('expense_date', '>=', now()->subMonth()->startOfMonth())->where('expense_date', '<', now()->startOfMonth())->sum('amount');
        
        $expenseChange = $lastMonthExpenses > 0 ? round((($currentMonthExpenses - $lastMonthExpenses) / $lastMonthExpenses) * 100, 1) : 0;
        
        // Calculate changes by category
        $currentMonthFeed = $expenses->where('expense_type', 'feed')->where('expense_date', '>=', now()->startOfMonth())->sum('amount');
        $lastMonthFeed = $expenses->where('expense_type', 'feed')->where('expense_date', '>=', now()->subMonth()->startOfMonth())->where('expense_date', '<', now()->startOfMonth())->sum('amount');
        $feedChange = $lastMonthFeed > 0 ? round((($currentMonthFeed - $lastMonthFeed) / $lastMonthFeed) * 100, 1) : 0;
        
        $currentMonthVet = $expenses->where('expense_type', 'medicine')->where('expense_date', '>=', now()->startOfMonth())->sum('amount');
        $lastMonthVet = $expenses->where('expense_type', 'medicine')->where('expense_date', '>=', now()->subMonth()->startOfMonth())->where('expense_date', '<', now()->startOfMonth())->sum('amount');
        $veterinaryChange = $lastMonthVet > 0 ? round((($currentMonthVet - $lastMonthVet) / $lastMonthVet) * 100, 1) : 0;
        
        $currentMonthMaintenance = $expenses->where('expense_type', 'equipment')->where('expense_date', '>=', now()->startOfMonth())->sum('amount');
        $lastMonthMaintenance = $expenses->where('expense_type', 'equipment')->where('expense_date', '>=', now()->subMonth()->startOfMonth())->where('expense_date', '<', now()->startOfMonth())->sum('amount');
        $maintenanceChange = $lastMonthMaintenance > 0 ? round((($currentMonthMaintenance - $lastMonthMaintenance) / $lastMonthMaintenance) * 100, 1) : 0;

        // Budget calculations (dynamic based on farm size)
        $monthlyBudget = $farms->count() * 25000; // 25k per farm as base budget
        $budgetExceeded = $currentMonthExpenses > $monthlyBudget;
        $budgetExcess = $budgetExceeded ? $currentMonthExpenses - $monthlyBudget : 0;

        // Get expenses data for table
        $expensesData = $expenses->take(20)->map(function ($expense) {
            return [
                'id' => $expense->id,
                'expense_id' => 'EXP' . str_pad($expense->id, 3, '0', STR_PAD_LEFT),
                'expense_date' => $expense->expense_date->format('M d, Y'),
                'expense_name' => $expense->description,
                'category' => ucfirst($expense->expense_type),
                'amount' => $expense->amount,
                'payment_status' => 'Paid', // Since we don't have payment_status, assume all are paid
                'payment_method' => $expense->payment_method ?? 'Cash',
                'farm_name' => $expense->farm ? $expense->farm->name : 'Unknown',
                'notes' => $expense->notes
            ];
        });

        // Expense statistics for charts
        $expenseStats = [
            'monthly_trend' => \App\Models\Expense::whereIn('farm_id', $farmIds)
                ->selectRaw("DATE_FORMAT(expense_date, '%Y-%m') as month, SUM(amount) as total_expenses")
                ->where('expense_date', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->map(function ($item) {
                    return [
                        'month' => date('M', strtotime($item->month . '-01')),
                        'expenses' => $item->total_expenses
                    ];
                }),
            
            'category_distribution' => \App\Models\Expense::whereIn('farm_id', $farmIds)
                ->selectRaw('expense_type, SUM(amount) as total_amount')
                ->groupBy('expense_type')
                ->orderBy('total_amount', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'category' => ucfirst($item->expense_type),
                        'amount' => $item->total_amount
                    ];
                }),
            
            'payment_method_distribution' => \App\Models\Expense::whereIn('farm_id', $farmIds)
                ->selectRaw('payment_method, COUNT(*) as count')
                ->whereNotNull('payment_method')
                ->groupBy('payment_method')
                ->get()
                ->map(function ($item) {
                    return [
                        'method' => $item->payment_method,
                        'count' => $item->count
                    ];
                })
        ];

        return view('farmer.expenses', compact(
            'expenses',
            'totalExpenses',
            'feedExpenses',
            'veterinaryExpenses',
            'maintenanceExpenses',
            'expenseChange',
            'feedChange',
            'veterinaryChange',
            'maintenanceChange',
            'budgetExceeded',
            'budgetExcess',
            'expensesData',
            'farms',
            'expenseStats'
        ));
    }

    /**
     * Display the farmer's inventory management.
     */
    public function inventory()
    {
        $user = Auth::user();
        
        // Get farmer's farms
        $farms = Farm::where('owner_id', $user->id)->get();
        $farmIds = $farms->pluck('id')->toArray();
        
        if (empty($farmIds)) {
            return view('farmer.inventory', [
                'totalItems' => 0,
                'inStock' => 0,
                'lowStock' => 0,
                'outOfStock' => 0,
                'inventoryData' => [],
                'farms' => [],
                'inventoryStats' => []
            ]);
        }

        // Since we don't have a dedicated inventory table, we'll create inventory data
        // based on expenses and livestock data to simulate inventory management
        $inventoryData = $this->generateInventoryData($farmIds);

        // Calculate inventory statistics
        $totalItems = $inventoryData->count();
        $inStock = $inventoryData->where('status', 'In Stock')->count();
        $lowStock = $inventoryData->where('status', 'Low Stock')->count();
        $outOfStock = $inventoryData->where('status', 'Out of Stock')->count();

        // Inventory statistics for charts
        $inventoryStats = [
            'category_distribution' => $inventoryData->groupBy('category')->map(function ($items, $category) {
                return [
                    'category' => $category,
                    'count' => $items->count(),
                    'total_value' => $items->sum('value')
                ];
            })->values(),
            
            'status_distribution' => $inventoryData->groupBy('status')->map(function ($items, $status) {
                return [
                    'status' => $status,
                    'count' => $items->count()
                ];
            })->values(),
            
            'value_by_category' => $inventoryData->groupBy('category')->map(function ($items, $category) {
                return [
                    'category' => $category,
                    'total_value' => $items->sum('value')
                ];
            })->values()
        ];

        return view('farmer.inventory', compact(
            'totalItems',
            'inStock',
            'lowStock',
            'outOfStock',
            'inventoryData',
            'farms',
            'inventoryStats'
        ));
    }

    /**
     * Generate inventory data based on expenses and livestock.
     */
    private function generateInventoryData($farmIds)
    {
        // Return empty collection to start with no inventory items
        return collect();
    }

    /**
     * Determine stock status based on quantity and category.
     */
    private function determineStockStatus($quantity, $category)
    {
        if ($quantity == 0) {
            return 'Out of Stock';
        }
        
        $thresholds = [
            'Feed' => 50,
            'Medicine' => 10,
            'Equipment' => 1
        ];
        
        $threshold = $thresholds[$category] ?? 10;
        
        if ($quantity <= $threshold) {
            return 'Low Stock';
        }
        
        return 'In Stock';
    }

    /**
     * Get item description.
     */
    private function getItemDescription($name)
    {
        $descriptions = [
            'Premium Feed Mix' => 'High protein content feed for optimal livestock growth',
            'Hay Bales' => 'Fresh grass hay for livestock feeding',
            'Corn Silage' => 'Fermented corn for livestock nutrition',
            'Mineral Supplements' => 'Essential minerals for livestock health',
            'Vitamin Supplements' => 'Multi-vitamin supplements for livestock',
            'Antibiotics' => 'Medical treatment for livestock infections',
            'Vaccines' => 'Preventive vaccines for livestock diseases',
            'Deworming Medicine' => 'Treatment for internal parasites',
            'Milking Equipment' => 'Automatic milking system for dairy operations',
            'Feed Mixers' => 'Equipment for mixing livestock feed',
            'Water Tanks' => 'Storage tanks for livestock water supply',
            'Fencing Materials' => 'Materials for livestock fencing'
        ];
        
        return $descriptions[$name] ?? 'Farm inventory item';
    }

    /**
     * Get item icon based on category.
     */
    private function getItemIcon($category)
    {
        $icons = [
            'Feed' => 'seedling',
            'Medicine' => 'pills',
            'Equipment' => 'tools'
        ];
        
        return $icons[$category] ?? 'box';
    }

    /**
     * Get item color based on category.
     */
    private function getItemColor($category)
    {
        $colors = [
            'Feed' => 'success',
            'Medicine' => 'info',
            'Equipment' => 'warning'
        ];
        
        return $colors[$category] ?? 'primary';
    }

    /**
     * Store a newly created expense.
     */
    public function storeExpense(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_type' => 'required|in:feed,medicine,equipment,labor,other',
            'expense_date' => 'required|date',
            'farm_id' => 'required|exists:farms,id',
            'payment_method' => 'nullable|string',
            'receipt_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        
        // Verify the farm belongs to the user
        $farm = Farm::where('id', $request->farm_id)
                   ->where('owner_id', $user->id)
                   ->firstOrFail();

        try {
            $expense = \App\Models\Expense::create([
                'description' => $request->description,
                'amount' => $request->amount,
                'expense_type' => $request->expense_type,
                'expense_date' => $request->expense_date,
                'farm_id' => $farm->id,
                'payment_method' => $request->payment_method,
                'receipt_number' => $request->receipt_number,
                'notes' => $request->notes,
                'recorded_by' => $user->id,
            ]);

            // Log the action
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'expense_created',
                'description' => "Expense record created: {$request->description} - ₱{$request->amount}",
                'severity' => 'info',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('farmer.expenses')->with('success', 'Expense recorded successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to record expense: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified expense.
     */
    public function showExpense($id)
    {
        $user = Auth::user();
        $expense = \App\Models\Expense::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        return response()->json($expense);
    }

    /**
     * Update the specified expense.
     */
    public function updateExpense(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_type' => 'required|in:feed,medicine,equipment,labor,other',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'receipt_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $expense = \App\Models\Expense::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        $expense->update([
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_type' => $request->expense_type,
            'expense_date' => $request->expense_date,
            'payment_method' => $request->payment_method,
            'receipt_number' => $request->receipt_number,
            'notes' => $request->notes,
        ]);

        return redirect()->route('farmer.expenses')->with('success', 'Expense updated successfully!');
    }

    /**
     * Remove the specified expense.
     */
    public function deleteExpense($id)
    {
        $user = Auth::user();
        $expense = \App\Models\Expense::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        $expense->delete();

        return redirect()->route('farmer.expenses')->with('success', 'Expense deleted successfully!');
    }

    /**
     * Display the farmer's farms.
     */
    public function farms()
    {
        $user = Auth::user();
        
        // Get farms owned by the farmer
        $farms = Farm::where('owner_id', $user->id)->get();

        return view('farmer.farms', compact('farms'));
    }

    /**
     * Store a newly created farm.
     */
    public function storeFarm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'location' => 'required|string|max:500',
            'size' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $user = Auth::user();
            
            Farm::create([
                'name' => $request->name,
                'description' => $request->description,
                'location' => $request->location,
                'size' => $request->size,
                'status' => $request->status,
                'owner_id' => $user->id,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Farm created successfully!'
                ]);
            }

            return redirect()->route('farmer.farms')->with('success', 'Farm created successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create farm. Please try again.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to create farm. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified farm details.
     */
    public function farmDetails($id)
    {
        $user = Auth::user();
        
        // Get the farm and verify ownership
        $farm = Farm::where('id', $id)
                   ->where('owner_id', $user->id)
                   ->firstOrFail();

        // Get livestock for this farm
        $livestock = $farm->livestock()->orderBy('created_at', 'desc')->get();

        return view('farmer.farm-details', compact('farm', 'livestock'));
    }

    /**
     * Display audit logs for the farmer.
     */
    public function auditLogs()
    {
        $user = Auth::user();
        
        // Get query parameters for filtering
        $search = request()->get('search');
        $severity = request()->get('severity');
        $action = request()->get('action');
        $startDate = request()->get('start_date');
        $endDate = request()->get('end_date');
        
        // Build query
        $query = AuditLog::where('user_id', $user->id);
        
        // Apply filters
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('table_name', 'like', "%{$search}%");
            });
        }
        
        if ($severity) {
            $query->where('severity', $severity);
        }
        
        if ($action) {
            $query->where('action', $action);
        }
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        
        // Get paginated results
        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get stats
        $totalLogs = AuditLog::where('user_id', $user->id)->count();
        $todayLogs = AuditLog::where('user_id', $user->id)
                            ->whereDate('created_at', today())
                            ->count();
        $criticalEvents = AuditLog::where('user_id', $user->id)
                                 ->whereIn('severity', ['error', 'critical'])
                                 ->count();
        
        // Get action and severity options for filters
        $actionOptions = AuditLog::where('user_id', $user->id)
                                ->distinct()
                                ->pluck('action')
                                ->filter()
                                ->values();
        
        $severityOptions = AuditLog::where('user_id', $user->id)
                                  ->distinct()
                                  ->pluck('severity')
                                  ->filter()
                                  ->values();

        return view('farmer.audit-logs', compact(
            'auditLogs',
            'totalLogs',
            'todayLogs',
            'criticalEvents',
            'actionOptions',
            'severityOptions'
        ));
    }





    /**
     * Get audit log details
     */
    public function getAuditLogDetails($id)
    {
        try {
            $user = Auth::user();
            $auditLog = AuditLog::where('user_id', $user->id)
                               ->with(['user'])
                               ->findOrFail($id);
            
            // Format the audit log data using helper
            $formattedLog = \App\Helpers\AuditLogHelper::formatAuditLogData($auditLog);
            
            return response()->json([
                'success' => true,
                'auditLog' => $formattedLog
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load audit log details'
            ], 500);
        }
    }

    /**
     * Export audit logs
     */
    public function exportAuditLogs(Request $request)
    {
        try {
            $user = Auth::user();
            $format = $request->get('format', 'csv');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $severity = $request->get('severity');
            $action = $request->get('action');
            $search = $request->get('search');
            
            $query = AuditLog::where('user_id', $user->id)->with(['user']);
            
            // Apply filters
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }
            
            if ($severity) {
                $query->where('severity', $severity);
            }
            
            if ($action) {
                $query->where('action', $action);
            }
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                      ->orWhere('action', 'like', "%{$search}%")
                      ->orWhere('table_name', 'like', "%{$search}%");
                });
            }
            
            $auditLogs = $query->orderBy('created_at', 'desc')->get();
            
            if ($format === 'csv') {
                return $this->exportToCSV($auditLogs);
            } elseif ($format === 'pdf') {
                return $this->exportToPDF($auditLogs);
            } else {
                return response()->json(['success' => false, 'message' => 'Unsupported format'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Export failed'], 500);
        }
    }

    /**
     * Export to CSV
     */
    private function exportToCSV($auditLogs)
    {
        $filename = 'farmer_audit_logs_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($auditLogs) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Log ID',
                'Action',
                'Description',
                'Severity',
                'IP Address',
                'User Agent',
                'Created At'
            ]);

            foreach ($auditLogs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->action,
                    $log->description,
                    $log->severity,
                    $log->ip_address,
                    $log->user_agent,
                    $log->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to PDF
     */
    private function exportToPDF($auditLogs)
    {
        // For now, return a simple text response
        // In a real implementation, you would use a PDF library like DomPDF
        $content = "Farmer Audit Logs Report\n";
        $content .= "Generated on: " . now() . "\n\n";
        
        foreach ($auditLogs as $log) {
            $content .= "Log ID: {$log->id}\n";
            $content .= "Action: {$log->action}\n";
            $content .= "Description: {$log->description}\n";
            $content .= "Severity: {$log->severity}\n";
            $content .= "Created: {$log->created_at}\n";
            $content .= "---\n";
        }

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="farmer_audit_logs_' . date('Y-m-d_H-i-s') . '.txt"');
    }

    /**
     * Display farm analysis dashboard for farmer.
     */
    public function farmAnalysis()
    {
        $user = Auth::user();
        
        // Get farmer's farms
        $farms = Farm::where('owner_id', $user->id)->get();
        $farmIds = $farms->pluck('id')->toArray();
        
        if (empty($farmIds)) {
            return view('farmer.farm-analysis', [
                'totalLivestock' => 0,
                'monthlyProduction' => 0,
                'monthlyRevenue' => 0,
                'activeIssues' => 0,
                'productionData' => [],
                'livestockDistribution' => [],
                'performanceMetrics' => [],
                'recommendations' => []
            ]);
        }

        // Calculate key metrics
        $totalLivestock = Livestock::whereIn('farm_id', $farmIds)->count();
        
        // Monthly production (current month)
        $currentMonth = now()->format('Y-m');
        $monthlyProduction = ProductionRecord::whereIn('farm_id', $farmIds)
            ->whereRaw("DATE_FORMAT(production_date, '%Y-%m') = ?", [$currentMonth])
            ->sum('milk_quantity');
        
        // Monthly revenue (current month)
        $monthlyRevenue = Sale::whereIn('farm_id', $farmIds)
            ->whereRaw("DATE_FORMAT(sale_date, '%Y-%m') = ?", [$currentMonth])
            ->sum('total_amount');
        
        // Active issues
        $activeIssues = Issue::whereIn('farm_id', $farmIds)
            ->where('status', '!=', 'Resolved')
            ->count();

        // Production data for chart (last 12 months)
        $productionData = ProductionRecord::whereIn('farm_id', $farmIds)
            ->selectRaw("DATE_FORMAT(production_date, '%Y-%m') as month, SUM(milk_quantity) as total_production")
            ->where('production_date', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [date('M', strtotime($item->month . '-01')) => $item->total_production];
            })
            ->toArray();

        // Livestock distribution by type
        $livestockDistribution = Livestock::whereIn('farm_id', $farmIds)
            ->select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type => $item->count];
            })
            ->toArray();

        // Performance metrics (current vs previous month)
        $currentMonthData = $this->getMonthData($farmIds, $currentMonth);
        $previousMonth = now()->subMonth()->format('Y-m');
        $previousMonthData = $this->getMonthData($farmIds, $previousMonth);
        
        $performanceMetrics = [
            'milk_production' => [
                'current' => $currentMonthData['production'] ?? 0,
                'previous' => $previousMonthData['production'] ?? 0,
                'change' => $this->calculateChange($currentMonthData['production'] ?? 0, $previousMonthData['production'] ?? 0)
            ],
            'feed_consumption' => [
                'current' => $currentMonthData['feed_cost'] ?? 0,
                'previous' => $previousMonthData['feed_cost'] ?? 0,
                'change' => $this->calculateChange($currentMonthData['feed_cost'] ?? 0, $previousMonthData['feed_cost'] ?? 0)
            ],
            'veterinary_costs' => [
                'current' => $currentMonthData['veterinary_cost'] ?? 0,
                'previous' => $previousMonthData['veterinary_cost'] ?? 0,
                'change' => $this->calculateChange($currentMonthData['veterinary_cost'] ?? 0, $previousMonthData['veterinary_cost'] ?? 0)
            ],
            'health_score' => [
                'current' => $this->calculateHealthScore($farmIds),
                'previous' => $this->calculateHealthScore($farmIds, now()->subMonth()),
                'change' => $this->calculateChange($this->calculateHealthScore($farmIds), $this->calculateHealthScore($farmIds, now()->subMonth()))
            ],
            'breeding_success' => [
                'current' => $this->calculateBreedingSuccess($farmIds),
                'previous' => $this->calculateBreedingSuccess($farmIds, now()->subMonth()),
                'change' => $this->calculateChange($this->calculateBreedingSuccess($farmIds), $this->calculateBreedingSuccess($farmIds, now()->subMonth()))
            ]
        ];

        // Generate recommendations
        $recommendations = $this->generateRecommendations($performanceMetrics, $activeIssues, $monthlyProduction, $monthlyRevenue);

        return view('farmer.farm-analysis', compact(
            'totalLivestock',
            'monthlyProduction',
            'monthlyRevenue',
            'activeIssues',
            'productionData',
            'livestockDistribution',
            'performanceMetrics',
            'recommendations'
        ));
    }

    /**
     * Get data for a specific month.
     */
    private function getMonthData($farmIds, $month)
    {
        $data = [];
        
        // Production
        $data['production'] = ProductionRecord::whereIn('farm_id', $farmIds)
            ->whereRaw("DATE_FORMAT(production_date, '%Y-%m') = ?", [$month])
            ->sum('milk_quantity');
        
        // Feed costs
        $data['feed_cost'] = Expense::whereIn('farm_id', $farmIds)
            ->where('expense_type', 'feed')
            ->whereRaw("DATE_FORMAT(expense_date, '%Y-%m') = ?", [$month])
            ->sum('amount');
        
        // Veterinary costs
        $data['veterinary_cost'] = Expense::whereIn('farm_id', $farmIds)
            ->where('expense_type', 'medicine')
            ->whereRaw("DATE_FORMAT(expense_date, '%Y-%m') = ?", [$month])
            ->sum('amount');
        
        return $data;
    }

    /**
     * Calculate percentage change between two values.
     */
    private function calculateChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Calculate health score based on livestock health status.
     */
    private function calculateHealthScore($farmIds, $date = null)
    {
        $query = Livestock::whereIn('farm_id', $farmIds);
        
        if ($date) {
            $query->where('created_at', '<=', $date);
        }
        
        $total = $query->count();
        if ($total == 0) return 0;
        
        $healthy = $query->where('health_status', 'Healthy')->count();
        return round(($healthy / $total) * 100, 1);
    }

    /**
     * Calculate breeding success rate.
     */
    private function calculateBreedingSuccess($farmIds, $date = null)
    {
        // This is a simplified calculation - in a real system you'd have breeding records
        $query = Livestock::whereIn('farm_id', $farmIds);
        
        if ($date) {
            $query->where('created_at', '<=', $date);
        }
        
        $total = $query->count();
        if ($total == 0) return 0;
        
        // Simplified: assume 75% success rate for demonstration
        return 75;
    }

    /**
     * Generate recommendations based on performance data.
     */
    private function generateRecommendations($performanceMetrics, $activeIssues, $monthlyProduction, $monthlyRevenue)
    {
        $recommendations = [];
        
        // Production optimization
        if ($performanceMetrics['milk_production']['change'] < 5) {
            $recommendations[] = [
                'type' => 'info',
                'title' => 'Production Optimization',
                'message' => 'Consider adjusting feeding schedules during peak production periods to maximize milk yield.',
                'icon' => 'fas fa-chart-line'
            ];
        }
        
        // Cost management
        if ($performanceMetrics['veterinary_costs']['change'] > 15) {
            $recommendations[] = [
                'type' => 'warning',
                'title' => 'Cost Management',
                'message' => 'Veterinary costs have increased. Review preventive care strategies to reduce emergency treatments.',
                'icon' => 'fas fa-exclamation-triangle'
            ];
        }
        
        // Health improvement
        if ($performanceMetrics['health_score']['change'] > 0) {
            $recommendations[] = [
                'type' => 'success',
                'title' => 'Health Improvement',
                'message' => 'Livestock health score is improving. Continue current vaccination and nutrition programs.',
                'icon' => 'fas fa-heart'
            ];
        }
        
        // Feed efficiency
        if ($performanceMetrics['feed_consumption']['change'] < 0 && $performanceMetrics['milk_production']['change'] > 0) {
            $recommendations[] = [
                'type' => 'primary',
                'title' => 'Feed Efficiency',
                'message' => 'Feed consumption has decreased while production increased. This indicates improved feed efficiency.',
                'icon' => 'fas fa-seedling'
            ];
        }
        
        // Active issues alert
        if ($activeIssues > 0) {
            $recommendations[] = [
                'type' => 'danger',
                'title' => 'Active Issues',
                'message' => "You have {$activeIssues} active issues that require attention. Please review and address them promptly.",
                'icon' => 'fas fa-exclamation-circle'
            ];
        }
        
        return $recommendations;
    }

    /**
     * Display livestock analysis dashboard for farmer.
     */
    public function livestockAnalysis()
    {
        $user = Auth::user();
        
        // Get farmer's farms
        $farms = Farm::where('owner_id', $user->id)->get();
        $farmIds = $farms->pluck('id')->toArray();
        
        if (empty($farmIds)) {
            return view('farmer.livestock-analysis', [
                'totalLivestock' => 0,
                'healthyAnimals' => 0,
                'breedingAge' => 0,
                'underTreatment' => 0,
                'livestockData' => [],
                'healthDistribution' => [],
                'breedingData' => [],
                'growthData' => [],
                'performanceMetrics' => []
            ]);
        }

        // Calculate key metrics
        $totalLivestock = Livestock::whereIn('farm_id', $farmIds)->count();
        $healthyAnimals = Livestock::whereIn('farm_id', $farmIds)->where('health_status', 'healthy')->count();
        $breedingAge = Livestock::whereIn('farm_id', $farmIds)
            ->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= ?', [2])
            ->count();
        $underTreatment = Livestock::whereIn('farm_id', $farmIds)->where('health_status', 'under_treatment')->count();

        // Get livestock data for table
        $livestockData = Livestock::whereIn('farm_id', $farmIds)
            ->with(['farm', 'productionRecords'])
            ->get()
            ->map(function ($livestock) {
                // Calculate average daily production
                $avgProduction = $livestock->productionRecords()
                    ->where('production_date', '>=', now()->subDays(30))
                    ->avg('milk_quantity') ?? 0;
                
                // Calculate age from birth date
                $age = \Carbon\Carbon::parse($livestock->birth_date)->age;
                
                // Calculate health score based on health status
                $healthScore = $this->calculateLivestockHealthScore($livestock);
                
                return [
                    'id' => $livestock->id,
                    'livestock_id' => $livestock->tag_number,
                    'type' => $livestock->type,
                    'breed' => $livestock->breed,
                    'age' => $age,
                    'health_score' => $healthScore,
                    'avg_production' => round($avgProduction, 1),
                    'weight' => $livestock->weight ?? 0,
                    'health_status' => $livestock->health_status,
                    'farm_name' => $livestock->farm->name ?? 'Unknown'
                ];
            });

        // Health status distribution
        $healthDistribution = Livestock::whereIn('farm_id', $farmIds)
            ->select('health_status', DB::raw('count(*) as count'))
            ->groupBy('health_status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->health_status => $item->count];
            })
            ->toArray();

        // Breeding analysis (simplified - in real system you'd have breeding records)
        $breedingAgeCount = Livestock::whereIn('farm_id', $farmIds)
            ->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= ?', [2])
            ->count();
        
        $breedingData = [
            'pregnant' => $breedingAgeCount * 0.3, // 30% pregnant
            'ready_to_breed' => $breedingAgeCount * 0.4, // 40% ready
            'under_observation' => $breedingAgeCount * 0.3 // 30% under observation
        ];

        // Growth trends (last 6 months average weight by type)
        $growthData = Livestock::whereIn('farm_id', $farmIds)
            ->select('type', DB::raw('AVG(weight) as avg_weight'))
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type => $item->avg_weight];
            })
            ->toArray();

        // Performance metrics for charts
        $performanceMetrics = $this->calculateLivestockPerformanceMetrics($farmIds);

        return view('farmer.livestock-analysis', compact(
            'totalLivestock',
            'healthyAnimals',
            'breedingAge',
            'underTreatment',
            'livestockData',
            'healthDistribution',
            'breedingData',
            'growthData',
            'performanceMetrics'
        ));
    }

    /**
     * Calculate health score for individual livestock.
     */
    private function calculateLivestockHealthScore($livestock)
    {
        $baseScore = 100;
        
        // Reduce score based on health status
        switch ($livestock->health_status) {
            case 'healthy':
                return 95;
            case 'under_treatment':
                return 75;
            case 'critical':
                return 50;
            case 'recovering':
                return 85;
            default:
                return 80;
        }
    }

    /**
     * Calculate livestock performance metrics for charts.
     */
    private function calculateLivestockPerformanceMetrics($farmIds)
    {
        // Get production data for last 12 months
        $productionData = ProductionRecord::whereIn('farm_id', $farmIds)
            ->selectRaw("DATE_FORMAT(production_date, '%Y-%m') as month, AVG(milk_quantity) as avg_production")
            ->where('production_date', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [date('M', strtotime($item->month . '-01')) => round($item->avg_production, 1)];
            })
            ->toArray();

        // Calculate health score trend (simplified)
        $healthScoreData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $totalLivestock = Livestock::whereIn('farm_id', $farmIds)->where('created_at', '<=', $date)->count();
            $healthyLivestock = Livestock::whereIn('farm_id', $farmIds)
                ->where('health_status', 'healthy')
                ->where('created_at', '<=', $date)
                ->count();
            
            $healthScore = $totalLivestock > 0 ? round(($healthyLivestock / $totalLivestock) * 100, 1) : 0;
            $healthScoreData[date('M', $date->timestamp)] = $healthScore;
        }

        return [
            'production' => $productionData,
            'health_score' => $healthScoreData
        ];
    }

    /**
     * Display sales management dashboard for farmer.
     */
    public function sales()
    {
        $user = Auth::user();
        
        // Get farmer's farms
        $farms = Farm::where('owner_id', $user->id)->get();
        $farmIds = $farms->pluck('id')->toArray();
        
        if (empty($farmIds)) {
            return view('farmer.sales', [
                'totalSales' => 0,
                'monthlySales' => 0,
                'totalTransactions' => 0,
                'averagePrice' => 0,
                'salesData' => [],
                'salesHistory' => []
            ]);
        }

        // Calculate key metrics
        $totalSales = Sale::whereIn('farm_id', $farmIds)->sum('total_amount');
        
        // Monthly sales (current month)
        $currentMonth = now()->format('Y-m');
        $monthlySales = Sale::whereIn('farm_id', $farmIds)
            ->whereRaw("DATE_FORMAT(sale_date, '%Y-%m') = ?", [$currentMonth])
            ->sum('total_amount');
        
        // Total transactions
        $totalTransactions = Sale::whereIn('farm_id', $farmIds)->count();
        
        // Average price
        $averagePrice = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;

        // Get sales data for table
        $salesData = Sale::whereIn('farm_id', $farmIds)
            ->with(['farm'])
            ->orderBy('sale_date', 'desc')
            ->get()
            ->map(function ($sale) {
                return [
                    'id' => $sale->id,
                    'sale_id' => 'SL' . str_pad($sale->id, 3, '0', STR_PAD_LEFT),
                    'sale_date' => $sale->sale_date->format('Y-m-d'),
                    'type' => 'Milk',
                    'amount' => $sale->total_amount,
                    'quantity' => $sale->quantity,
                    'unit_price' => $sale->unit_price,
                    'customer_name' => $sale->customer_name,
                    'farm_name' => $sale->farm ? $sale->farm->name : 'Unknown',
                    'payment_method' => $sale->payment_method ?? 'Cash',
                    'payment_status' => $sale->payment_status,
                    'notes' => $sale->notes ?? ''
                ];
            });

        // Get sales history for charts (last 12 months)
        $salesHistory = Sale::whereIn('farm_id', $farmIds)
            ->selectRaw("DATE_FORMAT(sale_date, '%Y-%m') as month, SUM(total_amount) as total_sales, COUNT(*) as transaction_count")
            ->where('sale_date', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => date('M', strtotime($item->month . '-01')),
                    'total_sales' => $item->total_sales,
                    'transaction_count' => $item->transaction_count
                ];
            });

        // Get farms list for dropdown
        $farms = Farm::where('owner_id', $user->id)->orderBy('name')->get();

        return view('farmer.sales', compact(
            'totalSales',
            'monthlySales',
            'totalTransactions',
            'averagePrice',
            'salesData',
            'salesHistory',
            'farms'
        ));
    }

    /**
     * Store a new sale record.
     */
    public function storeSale(Request $request)
    {
        $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'sale_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'payment_status' => 'nullable|in:pending,paid,partial',
            'notes' => 'nullable|string'
        ]);

        $user = Auth::user();
        
        // Verify farm ownership
        $farm = Farm::where('owner_id', $user->id)->findOrFail($request->farm_id);

        try {
            $totalAmount = $request->quantity * $request->unit_price;
            
            $sale = Sale::create([
                'farm_id' => $request->farm_id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'total_amount' => $totalAmount,
                'payment_status' => $request->payment_status ?? 'pending',
                'payment_method' => $request->payment_method ?? 'cash',
                'sale_date' => $request->sale_date,
                'notes' => $request->notes,
                'recorded_by' => $user->id
            ]);

            // Log the action
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'sale_created',
                'description' => "Milk sale record created for customer {$request->customer_name}",
                'severity' => 'info',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sale record created successfully!',
                'sale' => $sale
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create sale record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a sale record.
     */
    public function deleteSale(Request $request, $id)
    {
        $user = Auth::user();
        
        // Get the sale and verify ownership
        $sale = Sale::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        try {
            $sale->delete();

            // Log the action
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'sale_deleted',
                'description' => "Sale record #{$sale->id} deleted",
                'severity' => 'warning',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sale record deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete sale record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display clients management dashboard for farmer.
     */
    public function clients()
    {
        $user = Auth::user();
        
        // Get farmer's farms
        $farms = Farm::where('owner_id', $user->id)->get();
        $farmIds = $farms->pluck('id')->toArray();
        
        if (empty($farmIds)) {
            return view('farmer.clients', [
                'totalClients' => 0,
                'activeClients' => 0,
                'monthlyRevenue' => 0,
                'newThisMonth' => 0,
                'clientsData' => [],
                'clientDistribution' => [],
                'topClients' => []
            ]);
        }

        // Get unique customers from sales data
        $clientsData = Sale::whereIn('farm_id', $farmIds)
            ->select('customer_name', 'customer_phone', 'customer_email')
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('SUM(total_amount) as total_spent')
            ->selectRaw('MAX(sale_date) as last_order_date')
            ->selectRaw('MIN(sale_date) as first_order_date')
            ->groupBy('customer_name', 'customer_phone', 'customer_email')
            ->orderBy('total_spent', 'desc')
            ->get()
            ->map(function ($client) {
                // Determine client type based on total spent
                $totalSpent = $client->total_spent;
                if ($totalSpent >= 50000) {
                    $type = 'wholesale';
                    $typeLabel = 'Wholesale';
                    $typeBadge = 'badge-info';
                } elseif ($totalSpent >= 20000) {
                    $type = 'business';
                    $typeLabel = 'Business';
                    $typeBadge = 'badge-warning';
                } elseif ($totalSpent >= 10000) {
                    $type = 'market';
                    $typeLabel = 'Market';
                    $typeBadge = 'badge-secondary';
                } else {
                    $type = 'retail';
                    $typeLabel = 'Retail';
                    $typeBadge = 'badge-primary';
                }

                // Determine status based on last order
                $lastOrder = \Carbon\Carbon::parse($client->last_order_date);
                $daysSinceLastOrder = now()->diffInDays($lastOrder);
                
                if ($daysSinceLastOrder <= 30) {
                    $status = 'active';
                    $statusLabel = 'Active';
                    $statusBadge = 'badge-success';
                } elseif ($daysSinceLastOrder <= 90) {
                    $status = 'pending';
                    $statusLabel = 'Pending';
                    $statusBadge = 'badge-warning';
                } else {
                    $status = 'inactive';
                    $statusLabel = 'Inactive';
                    $statusBadge = 'badge-secondary';
                }

                return [
                    'name' => $client->customer_name,
                    'phone' => $client->customer_phone,
                    'email' => $client->customer_email,
                    'type' => $type,
                    'type_label' => $typeLabel,
                    'type_badge' => $typeBadge,
                    'status' => $status,
                    'status_label' => $statusLabel,
                    'status_badge' => $statusBadge,
                    'total_orders' => $client->total_orders,
                    'total_spent' => $client->total_spent,
                    'last_order_date' => $client->last_order_date,
                    'first_order_date' => $client->first_order_date,
                    'days_since_last_order' => $daysSinceLastOrder
                ];
            });

        // Calculate statistics
        $totalClients = $clientsData->count();
        $activeClients = $clientsData->where('status', 'active')->count();
        
        // Monthly revenue (current month)
        $currentMonth = now()->format('Y-m');
        $monthlyRevenue = Sale::whereIn('farm_id', $farmIds)
            ->whereRaw("DATE_FORMAT(sale_date, '%Y-%m') = ?", [$currentMonth])
            ->sum('total_amount');
        
        // New clients this month
        $newThisMonth = Sale::whereIn('farm_id', $farmIds)
            ->whereRaw("DATE_FORMAT(sale_date, '%Y-%m') = ?", [$currentMonth])
            ->select('customer_name')
            ->distinct()
            ->count();

        // Client distribution by type
        $clientDistribution = $clientsData->groupBy('type')
            ->map(function ($clients, $type) {
                return $clients->count();
            })
            ->toArray();

        // Top clients by total spent
        $topClients = $clientsData->take(5)->map(function ($client) {
            return [
                'name' => $client['name'],
                'type' => $client['type_label'],
                'total_spent' => $client['total_spent']
            ];
        });

        return view('farmer.clients', compact(
            'totalClients',
            'activeClients',
            'monthlyRevenue',
            'newThisMonth',
            'clientsData',
            'clientDistribution',
            'topClients'
        ));
    }

    /**
     * Display suppliers management dashboard for farmer.
     */
    public function suppliers()
    {
        $user = Auth::user();
        
        // Get farmer's farms
        $farms = Farm::where('owner_id', $user->id)->get();
        $farmIds = $farms->pluck('id')->toArray();
        
        if (empty($farmIds)) {
            return view('farmer.suppliers', [
                'totalSuppliers' => 0,
                'activeSuppliers' => 0,
                'totalSpent' => 0,
                'pendingPayments' => 0,
                'suppliersData' => [],
                'supplierStats' => []
            ]);
        }

        // Get unique suppliers from expenses data (based on expense types)
        $suppliersData = Expense::whereIn('farm_id', $farmIds)
            ->select('expense_type')
            ->selectRaw('COUNT(*) as total_transactions')
            ->selectRaw('SUM(amount) as total_spent')
            ->selectRaw('MAX(expense_date) as last_transaction_date')
            ->selectRaw('MIN(expense_date) as first_transaction_date')
            ->groupBy('expense_type')
            ->orderBy('total_spent', 'desc')
            ->get()
            ->map(function ($supplier, $index) {
                // Determine status based on last transaction
                $lastTransaction = \Carbon\Carbon::parse($supplier->last_transaction_date);
                $daysSinceLastTransaction = now()->diffInDays($lastTransaction);
                
                if ($daysSinceLastTransaction <= 30) {
                    $status = 'active';
                    $statusLabel = 'Active';
                    $statusBadge = 'status-active';
                } elseif ($daysSinceLastTransaction <= 90) {
                    $status = 'pending';
                    $statusLabel = 'Pending';
                    $statusBadge = 'status-pending';
                } else {
                    $status = 'inactive';
                    $statusLabel = 'Inactive';
                    $statusBadge = 'status-inactive';
                }

                // Generate supplier ID
                $supplierId = 'SP' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);

                // Create supplier name based on expense type
                $supplierName = ucfirst($supplier->expense_type) . ' Supplier';

                return [
                    'id' => $index + 1,
                    'supplier_id' => $supplierId,
                    'name' => $supplierName,
                    'contact' => 'Contact ' . ($index + 1),
                    'address' => ucfirst($supplier->expense_type) . ' Address',
                    'status' => $status,
                    'status_label' => $statusLabel,
                    'status_badge' => $statusBadge,
                    'total_transactions' => $supplier->total_transactions,
                    'total_spent' => $supplier->total_spent,
                    'last_transaction_date' => $supplier->last_transaction_date,
                    'first_transaction_date' => $supplier->first_transaction_date,
                    'days_since_last_transaction' => $daysSinceLastTransaction,
                    'expense_type' => $supplier->expense_type
                ];
            });

        // Calculate statistics
        $totalSuppliers = $suppliersData->count();
        $activeSuppliers = $suppliersData->where('status', 'active')->count();
        $totalSpent = $suppliersData->sum('total_spent');
        
        // Calculate pending payments (simplified - assuming some expenses might be unpaid)
        $pendingPayments = Expense::whereIn('farm_id', $farmIds)
            ->where('payment_method', 'credit')
            ->sum('amount');

        // Supplier statistics
        $supplierStats = [
            'total_transactions' => $suppliersData->sum('total_transactions'),
            'average_spent_per_supplier' => $totalSuppliers > 0 ? $totalSpent / $totalSuppliers : 0,
            'top_supplier' => $suppliersData->first(),
            'recent_transactions' => Expense::whereIn('farm_id', $farmIds)
                ->orderBy('expense_date', 'desc')
                ->take(5)
                ->get()
        ];

        return view('farmer.suppliers', compact(
            'totalSuppliers',
            'activeSuppliers',
            'totalSpent',
            'pendingPayments',
            'suppliersData',
            'supplierStats'
        ));
    }

    /**
     * Show inspection details.
     */
    public function showInspection($id)
    {
        try {
            $inspection = \App\Models\Inspection::with('scheduledBy')
                ->where('farmer_id', Auth::id())
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'inspection' => $inspection
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inspection not found'
            ], 404);
        }
    }

    /**
     * Mark inspection as complete.
     */
    public function completeInspection(Request $request, $id)
    {
        try {
            $inspection = \App\Models\Inspection::where('farmer_id', Auth::id())
                ->findOrFail($id);
            
            $inspection->update([
                'status' => 'completed',
                'findings' => $request->findings ?? 'Inspection completed successfully'
            ]);

            // Log the action
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'inspection_completed',
                'description' => "Inspection #{$id} marked as completed",
                'severity' => 'info',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inspection marked as completed'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete inspection'
            ], 500);
        }
    }

    /**
     * Get individual livestock analysis data.
     */
    public function getLivestockAnalysis($id)
    {
        try {
            $user = Auth::user();
            
            // Verify the livestock belongs to the farmer's farm
            $livestock = Livestock::whereHas('farm', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->with(['farm', 'productionRecords'])->findOrFail($id);

            // Calculate detailed metrics
            $age = \Carbon\Carbon::parse($livestock->birth_date)->age;
            $healthScore = $this->calculateLivestockHealthScore($livestock);
            
            // Get production data for the last 12 months
            $productionData = $livestock->productionRecords()
                ->where('production_date', '>=', now()->subMonths(12))
                ->orderBy('production_date')
                ->get()
                ->groupBy(function($record) {
                    return $record->production_date->format('Y-m');
                })
                ->map(function($group) {
                    return $group->avg('milk_quantity');
                });

            // Calculate growth trend
            $weightData = $livestock->productionRecords()
                ->where('production_date', '>=', now()->subMonths(6))
                ->orderBy('production_date')
                ->get()
                ->groupBy(function($record) {
                    return $record->production_date->format('Y-m');
                })
                ->map(function($group) {
                    return $group->avg('milk_quantity') * 0.1; // Simplified weight calculation
                });

            // Generate analysis insights
            $insights = $this->generateLivestockInsights($livestock, $productionData, $healthScore);

            $html = view('farmer.partials.livestock-analysis', compact(
                'livestock',
                'age',
                'healthScore',
                'productionData',
                'weightData',
                'insights'
            ))->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'data' => [
                    'livestock' => $livestock,
                    'age' => $age,
                    'health_score' => $healthScore,
                    'production_data' => $productionData,
                    'weight_data' => $weightData,
                    'insights' => $insights
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load livestock analysis'
            ], 500);
        }
    }

    /**
     * Get individual livestock history.
     */
    public function getLivestockHistory($id)
    {
        try {
            $user = Auth::user();
            
            // Verify the livestock belongs to the farmer's farm
            $livestock = Livestock::whereHas('farm', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->with(['farm', 'productionRecords'])->findOrFail($id);

            // Get production history
            $productionHistory = $livestock->productionRecords()
                ->orderBy('production_date', 'desc')
                ->take(50)
                ->get();

            // Get health history (simplified - in real system you'd have health records)
            $healthHistory = collect([
                [
                    'date' => now()->subDays(30),
                    'status' => $livestock->health_status,
                    'notes' => 'Regular health check'
                ],
                [
                    'date' => now()->subDays(60),
                    'status' => 'healthy',
                    'notes' => 'Vaccination administered'
                ],
                [
                    'date' => now()->subDays(90),
                    'status' => 'healthy',
                    'notes' => 'Routine examination'
                ]
            ]);

            $html = view('farmer.partials.livestock-history', compact(
                'livestock',
                'productionHistory',
                'healthHistory'
            ))->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'data' => [
                    'livestock' => $livestock,
                    'production_history' => $productionHistory,
                    'health_history' => $healthHistory
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load livestock history'
            ], 500);
        }
    }

    /**
     * Generate insights for individual livestock.
     */
    private function generateLivestockInsights($livestock, $productionData, $healthScore)
    {
        $insights = [];

        // Production insights
        if ($productionData->count() > 0) {
            $avgProduction = $productionData->avg();
            $maxProduction = $productionData->max();
            $minProduction = $productionData->min();
            
            if ($avgProduction > 20) {
                $insights[] = [
                    'type' => 'success',
                    'icon' => 'fas fa-chart-line',
                    'title' => 'High Production',
                    'message' => "This livestock shows excellent production with an average of {$avgProduction}L per day."
                ];
            } elseif ($avgProduction < 10) {
                $insights[] = [
                    'type' => 'warning',
                    'icon' => 'fas fa-exclamation-triangle',
                    'title' => 'Low Production',
                    'message' => "Production is below average. Consider reviewing feeding and health protocols."
                ];
            }

            if (($maxProduction - $minProduction) / $avgProduction > 0.5) {
                $insights[] = [
                    'type' => 'info',
                    'icon' => 'fas fa-chart-bar',
                    'title' => 'Variable Production',
                    'message' => "Production varies significantly. Monitor for consistency."
                ];
            }
        }

        // Health insights
        if ($healthScore >= 90) {
            $insights[] = [
                'type' => 'success',
                'icon' => 'fas fa-heart',
                'title' => 'Excellent Health',
                'message' => "Health score of {$healthScore}% indicates excellent condition."
            ];
        } elseif ($healthScore < 70) {
            $insights[] = [
                'type' => 'danger',
                'icon' => 'fas fa-stethoscope',
                'title' => 'Health Concern',
                'message' => "Health score of {$healthScore}% requires attention. Consider veterinary consultation."
            ];
        }

        // Age-based insights
        $age = \Carbon\Carbon::parse($livestock->birth_date)->age;
        if ($age >= 2 && $age <= 8) {
            $insights[] = [
                'type' => 'info',
                'icon' => 'fas fa-heart',
                'title' => 'Prime Breeding Age',
                'message' => "At {$age} years old, this livestock is in prime breeding condition."
            ];
        } elseif ($age > 10) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'fas fa-clock',
                'title' => 'Senior Livestock',
                'message' => "At {$age} years old, consider special care for senior livestock."
            ];
        }

        return $insights;
    }

    /**
     * Scan livestock by ID for QR code scanner.
     */
    public function scanLivestock($id)
    {
        try {
            $user = Auth::user();
            
            // First try to find by tag_number (primary identifier)
            $livestock = Livestock::with(['farm.owner'])
                ->whereHas('farm', function($query) use ($user) {
                    $query->where('owner_id', $user->id);
                })
                ->where('tag_number', $id)
                ->first();
            
            // If not found by tag_number, try by database ID
            if (!$livestock) {
                $livestock = Livestock::with(['farm.owner'])
                    ->whereHas('farm', function($query) use ($user) {
                        $query->where('owner_id', $user->id);
                    })
                    ->where('id', $id)
                    ->first();
            }
            
            // If still not found, try registry_id if it exists
            if (!$livestock) {
                $livestock = Livestock::with(['farm.owner'])
                    ->whereHas('farm', function($query) use ($user) {
                        $query->where('owner_id', $user->id);
                    })
                    ->where('registry_id', $id)
                    ->first();
            }
            
            if (!$livestock) {
                return response()->json([
                    'success' => false,
                    'message' => 'No livestock found with ID: ' . $id
                ], 404);
            }
            
            // Log the scan action
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'livestock_scanned',
                'description' => "QR code scanned for livestock: {$livestock->tag_number}",
                'severity' => 'info',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
            
            return response()->json([
                'success' => true,
                'livestock' => $livestock,
                'message' => 'Livestock found successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Livestock scan error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error scanning livestock: ' . $e->getMessage()
            ], 500);
        }
    }
}
