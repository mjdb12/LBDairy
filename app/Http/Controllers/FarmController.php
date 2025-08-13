<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\User;
use App\Models\Livestock;
use App\Models\ProductionRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class FarmController extends Controller
{
    /**
     * Display the farm management dashboard
     */
    public function index()
    {
        // Get statistics
        $stats = $this->getStats();
        
        // Get farms with their owners and related data
        $farms = Farm::with(['user', 'livestock', 'productionRecords'])
            ->get()
            ->map(function ($farm) {
                $farm->farm_id = 'FS' . str_pad($farm->id, 3, '0', STR_PAD_LEFT);
                return $farm;
            });

        return view('admin.manage-farms', [
            'activeFarmsCount' => $stats['active_farms_count'],
            'inactiveFarmsCount' => $stats['inactive_farms_count'],
            'totalFarmsCount' => $stats['total_farms_count'],
            'barangayCount' => $stats['barangay_count'],
            'farms' => $farms
        ]);
    }

    /**
     * Show farm details
     */
    public function show($id)
    {
        try {
            $farm = Farm::with(['user', 'livestock', 'productionRecords'])->findOrFail($id);
            
            // Get farm statistics
            $stats = $this->getFarmStats($farm->id);
            
            return response()->json([
                'success' => true,
                'farm' => $farm,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load farm details'
            ], 500);
        }
    }

    /**
     * Update farm status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:active,inactive'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status value'
                ], 400);
            }

            $farm = Farm::findOrFail($id);
            $farm->status = $request->status;
            $farm->save();

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
     * Delete farm
     */
    public function destroy($id)
    {
        try {
            $farm = Farm::findOrFail($id);
            
            // Check if farm has associated data
            if ($farm->livestock()->count() > 0 || $farm->productionRecords()->count() > 0) {
                return redirect()->back()->with('error', 'Cannot delete farm with associated livestock or production records');
            }
            
            $farm->delete();
            
            return redirect()->route('admin.farms.index')->with('success', 'Farm deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete farm');
        }
    }

    /**
     * Import farms from CSV
     */
    public function import(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'farms' => 'required|array',
                'farms.*.owner_name' => 'required|string|max:255',
                'farms.*.email' => 'required|email|max:255',
                'farms.*.phone' => 'nullable|string|max:20',
                'farms.*.location' => 'required|string|max:255',
                'farms.*.status' => 'nullable|in:active,inactive'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            $importedCount = 0;
            $farms = $request->input('farms');

            foreach ($farms as $farmData) {
                // Check if user already exists
                $user = User::where('email', $farmData['email'])->first();
                
                if (!$user) {
                    // Create new user
                    $user = User::create([
                        'name' => $farmData['owner_name'],
                        'email' => $farmData['email'],
                        'password' => bcrypt('password123'), // Default password
                        'role' => 'farmer',
                        'status' => 'active'
                    ]);
                }

                // Create farm
                Farm::create([
                    'user_id' => $user->id,
                    'name' => $farmData['owner_name'] . "'s Farm",
                    'location' => $farmData['location'],
                    'phone' => $farmData['phone'] ?? null,
                    'status' => $farmData['status'] ?? 'active'
                ]);

                $importedCount++;
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully imported {$importedCount} farms",
                'imported_count' => $importedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to import farms: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export farms to CSV
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        
        if ($format === 'csv') {
            return $this->exportToCSV();
        } elseif ($format === 'pdf') {
            return $this->exportToPDF();
        }
        
        return redirect()->back()->with('error', 'Invalid export format');
    }

    /**
     * Get overall statistics
     */
    private function getStats()
    {
        $activeFarmsCount = Farm::where('status', 'active')->count();
        $inactiveFarmsCount = Farm::where('status', 'inactive')->count();
        $totalFarmsCount = Farm::count();
        $barangayCount = Farm::distinct('location')->count();
        
        return [
            'active_farms_count' => $activeFarmsCount,
            'inactive_farms_count' => $inactiveFarmsCount,
            'total_farms_count' => $totalFarmsCount,
            'barangay_count' => $barangayCount
        ];
    }

    /**
     * Get individual farm statistics
     */
    private function getFarmStats($farmId)
    {
        $totalLivestock = Livestock::where('farm_id', $farmId)->count();
        $activeLivestock = Livestock::where('farm_id', $farmId)->where('status', 'active')->count();
        $totalProduction = ProductionRecord::whereHas('livestock', function($query) use ($farmId) {
            $query->where('farm_id', $farmId);
        })->sum('milk_quantity');
        
        return [
            'total_livestock' => $totalLivestock,
            'active_livestock' => $activeLivestock,
            'total_production' => round($totalProduction, 1)
        ];
    }

    /**
     * Export to CSV
     */
    private function exportToCSV()
    {
        $farms = Farm::with(['user', 'livestock', 'productionRecords'])->get();
        
        $filename = 'farm_management_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($farms) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Farm ID', 'Owner Name', 'Email', 'Phone', 'Location', 'Status',
                'Total Livestock', 'Active Livestock', 'Total Production (L)',
                'Registration Date'
            ]);
            
            foreach ($farms as $farm) {
                $totalLivestock = $farm->livestock->count();
                $activeLivestock = $farm->livestock->where('status', 'active')->count();
                $totalProduction = $farm->productionRecords->sum('milk_quantity');
                
                fputcsv($file, [
                    'FS' . str_pad($farm->id, 3, '0', STR_PAD_LEFT),
                    $farm->user->name ?? 'N/A',
                    $farm->user->email ?? 'N/A',
                    $farm->phone ?? 'N/A',
                    $farm->location ?? 'N/A',
                    $farm->status ?? 'active',
                    $totalLivestock,
                    $activeLivestock,
                    round($totalProduction, 1),
                    $farm->created_at ? $farm->created_at->format('Y-m-d') : 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to PDF
     */
    private function exportToPDF()
    {
        // This would use a PDF library like DomPDF or Snappy
        // For now, redirect to CSV export
        return $this->exportToCSV();
    }
}
