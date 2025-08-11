<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Livestock;
use App\Models\ProductionRecord;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AnalysisController extends Controller
{
    /**
     * Display the productivity analysis dashboard
     */
    public function index()
    {
        // Get statistics
        $stats = $this->getStats();
        
        // Get farmers with their productivity data
        $farmers = User::where('role', 'farmer')
            ->with(['farm', 'livestock', 'productionRecords'])
            ->get()
            ->map(function ($farmer) {
                $farmer->farmer_id = 'F' . str_pad($farmer->id, 3, '0', STR_PAD_LEFT);
                return $farmer;
            });

        return view('admin.manage-analysis', [
            'activeFarmsCount' => $stats['active_farms_count'],
            'avgProductivity' => $stats['avg_productivity'],
            'topProducer' => $stats['top_producer'],
            'totalFarmers' => $stats['total_farmers'],
            'farmers' => $farmers
        ]);
    }

    /**
     * Get farmer productivity data for charts
     */
    public function getFarmerData($id)
    {
        try {
            $farmer = User::with(['farm', 'livestock', 'productionRecords'])->findOrFail($id);
            
            // Get production data for the last 6 months
            $productionData = $this->getProductionData($farmer->id);
            
            // Generate analysis text
            $analysis = $this->generateAnalysis($productionData);
            
            return response()->json([
                'success' => true,
                'farmer' => $farmer,
                'chartData' => $productionData,
                'analysis' => $analysis
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load farmer data'
            ], 500);
        }
    }

    /**
     * Get farmer details and statistics
     */
    public function getFarmerDetails($id)
    {
        try {
            $farmer = User::with(['farm', 'livestock'])->findOrFail($id);
            
            // Get farmer statistics
            $stats = $this->getFarmerStats($farmer->id);
            
            return response()->json([
                'success' => true,
                'farmer' => $farmer,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load farmer details'
            ], 500);
        }
    }

    /**
     * Update farmer status
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

            $farmer = User::findOrFail($id);
            $farmer->status = $request->status;
            $farmer->save();

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
     * Delete farmer
     */
    public function deleteFarmer($id)
    {
        try {
            $farmer = User::findOrFail($id);
            
            // Check if farmer has associated data
            if ($farmer->livestock()->count() > 0 || $farmer->productionRecords()->count() > 0) {
                return redirect()->back()->with('error', 'Cannot delete farmer with associated livestock or production records');
            }
            
            $farmer->delete();
            
            return redirect()->route('admin.analysis.index')->with('success', 'Farmer deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete farmer');
        }
    }

    /**
     * Get overall statistics
     */
    private function getStats()
    {
        $activeFarmsCount = Farm::where('status', 'active')->count();
        $totalFarmers = User::where('role', 'farmer')->count();
        
        // Calculate average productivity from production records
        $avgProductivity = ProductionRecord::avg('quantity') ?? 0;
        
        // Find top producer (farmer with highest average production)
        $topProducer = ProductionRecord::select('user_id', DB::raw('AVG(quantity) as avg_production'))
            ->groupBy('user_id')
            ->orderBy('avg_production', 'desc')
            ->first();
        
        $topProducerId = $topProducer ? 'F' . str_pad($topProducer->user_id, 3, '0', STR_PAD_LEFT) : null;
        
        return [
            'active_farms_count' => $activeFarmsCount,
            'avg_productivity' => round($avgProductivity, 1),
            'top_producer' => $topProducerId,
            'total_farmers' => $totalFarmers
        ];
    }

    /**
     * Get production data for charts
     */
    private function getProductionData($farmerId)
    {
        // Get production data for the last 6 months
        $months = [];
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');
            
            // Get average production for this month
            $monthlyProduction = ProductionRecord::where('user_id', $farmerId)
                ->whereYear('production_date', $date->year)
                ->whereMonth('production_date', $date->month)
                ->avg('quantity') ?? 0;
            
            $data[] = round($monthlyProduction, 1);
        }
        
        return [
            'labels' => $months,
            'data' => $data
        ];
    }

    /**
     * Generate analysis text based on production data
     */
    private function generateAnalysis($productionData)
    {
        $data = $productionData['data'];
        $trend = $this->calculateTrend($data);
        
        if ($trend > 0.1) {
            return "Milk production shows a strong upward trend, indicating improved herd health and effective farm management practices. The consistent growth suggests that nutritional plans, breeding programs, and overall livestock care are positively impacting productivity.";
        } elseif ($trend > 0) {
            return "Milk production shows a moderate upward trend with steady improvement. The farm demonstrates consistent progress and good management practices. Consider implementing advanced techniques to further enhance productivity.";
        } elseif ($trend > -0.1) {
            return "Milk production shows a relatively stable pattern. While the trend is consistent, there is potential for improvement. Consider reviewing feeding protocols and health management strategies.";
        } else {
            return "Milk production shows a declining trend that requires attention. This pattern suggests potential issues with livestock health, feed quality, or management practices. Recommend comprehensive farm assessment and veterinary consultation.";
        }
    }

    /**
     * Calculate trend from production data
     */
    private function calculateTrend($data)
    {
        if (count($data) < 2) return 0;
        
        $n = count($data);
        $sumX = 0;
        $sumY = 0;
        $sumXY = 0;
        $sumX2 = 0;
        
        for ($i = 0; $i < $n; $i++) {
            $sumX += $i;
            $sumY += $data[$i];
            $sumXY += $i * $data[$i];
            $sumX2 += $i * $i;
        }
        
        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        return $slope;
    }

    /**
     * Get individual farmer statistics
     */
    private function getFarmerStats($farmerId)
    {
        $totalLivestock = Livestock::where('user_id', $farmerId)->count();
        $activeLivestock = Livestock::where('user_id', $farmerId)->where('status', 'active')->count();
        $totalProduction = ProductionRecord::where('user_id', $farmerId)->sum('quantity');
        
        return [
            'total_livestock' => $totalLivestock,
            'active_livestock' => $activeLivestock,
            'total_production' => round($totalProduction, 1)
        ];
    }

    /**
     * Export analysis data
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
     * Export to CSV
     */
    private function exportToCSV()
    {
        $farmers = User::where('role', 'farmer')
            ->with(['farm', 'livestock', 'productionRecords'])
            ->get();
        
        $filename = 'productivity_analysis_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($farmers) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Farmer ID', 'Name', 'Email', 'Location', 'Status',
                'Total Livestock', 'Active Livestock', 'Total Production (L)',
                'Average Monthly Production (L)'
            ]);
            
            foreach ($farmers as $farmer) {
                $totalLivestock = $farmer->livestock->count();
                $activeLivestock = $farmer->livestock->where('status', 'active')->count();
                $totalProduction = $farmer->productionRecords->sum('quantity');
                $avgMonthlyProduction = $farmer->productionRecords->avg('quantity') ?? 0;
                
                fputcsv($file, [
                    'F' . str_pad($farmer->id, 3, '0', STR_PAD_LEFT),
                    $farmer->name,
                    $farmer->email,
                    $farmer->location ?? 'N/A',
                    $farmer->status ?? 'active',
                    $totalLivestock,
                    $activeLivestock,
                    round($totalProduction, 1),
                    round($avgMonthlyProduction, 1)
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
