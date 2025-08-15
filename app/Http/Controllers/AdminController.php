<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Farm;
use App\Models\Livestock;
use App\Models\ProductionRecord;
use App\Models\Sale;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Update the admin's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Change the admin's password.
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
     * Display farm analysis dashboard
     */
    public function farmAnalysis()
    {
        // Get farm statistics
        $stats = $this->getFarmStats();
        
        // Get farms with performance data
        $farms = Farm::with(['owner', 'livestock', 'productionRecords'])
            ->get()
            ->map(function ($farm) {
                $farm->performance_score = $this->calculateFarmPerformance($farm);
                return $farm;
            });

        return view('admin.farm-analysis', [
            'totalFarms' => $stats['total_farms'],
            'activeFarms' => $stats['active_farms'],
            'avgProduction' => $stats['avg_production'],
            'totalRevenue' => $stats['total_revenue'],
            'farms' => $farms
        ]);
    }

    /**
     * Display livestock analysis dashboard
     */
    public function livestockAnalysis()
    {
        // Get livestock statistics
        $stats = $this->getLivestockStats();
        
        // Get livestock with performance data
        $livestock = Livestock::with(['farm', 'productionRecords'])
            ->get()
            ->map(function ($animal) {
                $animal->health_score = $this->calculateHealthScore($animal);
                $animal->productivity_score = $this->calculateProductivityScore($animal);
                $animal->age_months = $this->calculateAgeInMonths($animal);
                return $animal;
            });

        // Calculate additional metrics for the dashboard
        $activeLivestock = $livestock->where('status', 'active')->count();
        $avgProductivity = $livestock->avg('productivity_score') ?? 0;
        $totalProduction = $livestock->sum(function($animal) {
            return $animal->productionRecords->sum('milk_quantity') ?? 0;
        });
        
        // Get top performer
        $topPerformer = $livestock->sortByDesc('productivity_score')->first();
        $topPerformerName = $topPerformer ? $topPerformer->name : 'N/A';
        
        // Count livestock needing attention (low productivity or health issues)
        $needsAttention = $livestock->filter(function($animal) {
            return ($animal->productivity_score ?? 0) < 50 || ($animal->health_score ?? 0) < 70;
        })->count();
        
        // Calculate average age
        $avgAge = round($livestock->avg('age_months') ?? 0);

        return view('admin.livestock-analysis', [
            'totalLivestock' => $stats['total_livestock'],
            'activeLivestock' => $activeLivestock,
            'avgProductivity' => round($avgProductivity, 1),
            'totalProduction' => round($totalProduction, 1),
            'topPerformer' => $topPerformerName,
            'needsAttention' => $needsAttention,
            'avgAge' => $avgAge,
            'livestock' => $livestock
        ]);
    }

    /**
     * Display admin management dashboard
     */
    public function manageAdmins()
    {
        // Get admin statistics
        $stats = $this->getAdminStats();
        
        // Get all admin users
        $admins = User::where('role', 'admin')
            ->with(['farm'])
            ->get();

        return view('admin.manage-admins', [
            'totalAdmins' => $stats['total_admins'],
            'activeAdmins' => $stats['active_admins'],
            'totalFarms' => $stats['total_farms'],
            'avgFarmSize' => $stats['avg_farm_size'],
            'admins' => $admins
        ]);
    }

    /**
     * Display clients management dashboard
     */
    public function manageClients()
    {
        // Get client statistics
        $stats = $this->getClientStats();
        
        // Get all client users
        $clients = User::where('role', 'client')
            ->with(['farm', 'sales'])
            ->get();

        // Calculate additional metrics for the dashboard
        $outstandingBalance = $clients->sum(function($client) {
            return $client->sales->where('status', 'pending')->sum('amount') ?? 0;
        });
        
        $newThisMonth = $clients->filter(function($client) {
            return $client->created_at->format('Y-m') === now()->format('Y-m');
        })->count();

        return view('admin.clients', [
            'totalClients' => $stats['total_clients'],
            'activeClients' => $stats['active_clients'],
            'outstandingBalance' => $outstandingBalance,
            'newThisMonth' => $newThisMonth,
            'clients' => $clients
        ]);
    }

    /**
     * Display inventory management dashboard
     */
    public function manageInventory()
    {
        // Get inventory statistics
        $stats = $this->getInventoryStats();
        
        // For now, create sample inventory data since we don't have an Inventory model yet
        $inventory = collect([
            (object) [
                'id' => 'INV001',
                'date' => '2024-06-01',
                'category' => 'Feed',
                'name' => 'Corn Feed Bag',
                'quantity' => '150 bags',
                'farm_id' => 'FARM01'
            ],
            (object) [
                'id' => 'INV002',
                'date' => '2024-06-03',
                'category' => 'Medicine',
                'name' => 'Antibiotic Vial',
                'quantity' => '75 units',
                'farm_id' => 'FARM01'
            ],
            (object) [
                'id' => 'INV003',
                'date' => '2024-06-05',
                'category' => 'Equipment',
                'name' => 'Milking Machine',
                'quantity' => '3 units',
                'farm_id' => 'FARM02'
            ]
        ]);

        // Calculate category-specific counts
        $feedStock = $inventory->where('category', 'Feed')->count();
        $medicineStock = $inventory->where('category', 'Medicine')->count();
        $equipmentStock = $inventory->where('category', 'Equipment')->count();

        return view('admin.inventory', [
            'totalItems' => $inventory->count(),
            'feedStock' => $feedStock,
            'medicineStock' => $medicineStock,
            'equipmentStock' => $equipmentStock,
            'inventory' => $inventory
        ]);
    }

    /**
     * Display expenses management dashboard
     */
    public function manageExpenses()
    {
        // Get expense statistics
        $stats = $this->getExpenseStats();
        
        // Get expenses
        $expenses = Expense::with(['farm'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate additional metrics for the dashboard
        $totalExpenses = $expenses->sum('amount') ?? 0;
        $feedExpenses = $expenses->where('category', 'Feed')->sum('amount') ?? 0;
        $veterinaryExpenses = $expenses->where('category', 'Veterinary')->sum('amount') ?? 0;
        $maintenanceExpenses = $expenses->where('category', 'Maintenance')->sum('amount') ?? 0;
        
        // Calculate percentage changes (simplified - in real app this would compare with previous month)
        $expenseChange = 12; // Sample data
        $feedChange = -3;
        $veterinaryChange = 8;
        $maintenanceChange = 15;

        // Enhance expenses with display properties
        $expenses = $expenses->map(function($expense) {
            $expense->icon = $this->getExpenseIcon($expense->category);
            $expense->color = $this->getExpenseColor($expense->category);
            $expense->paid_amount = $expense->amount; // Assuming all expenses are paid for now
            return $expense;
        });

        return view('admin.expenses', [
            'totalExpenses' => $totalExpenses,
            'feedExpenses' => $feedExpenses,
            'veterinaryExpenses' => $veterinaryExpenses,
            'maintenanceExpenses' => $maintenanceExpenses,
            'expenseChange' => $expenseChange,
            'feedChange' => $feedChange,
            'veterinaryChange' => $veterinaryChange,
            'maintenanceChange' => $maintenanceChange,
            'expenses' => $expenses
        ]);
    }

    /**
     * Display farms overview dashboard
     */
    public function farms()
    {
        // Get farm statistics
        $stats = $this->getFarmsOverviewStats();
        
        // Get farms with owner information
        $farms = Farm::with(['owner'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.farms', [
            'activeFarmsCount' => $stats['active_farms_count'],
            'inactiveFarmsCount' => $stats['inactive_farms_count'],
            'totalFarmsCount' => $stats['total_farms_count'],
            'barangayCount' => $stats['barangay_count'],
            'farms' => $farms
        ]);
    }

        /**
     * Display production overview dashboard
     */
    public function production()
    {
        // Get production statistics
        $stats = $this->getProductionStats();

        // Get production records with farm information
        $productionRecords = ProductionRecord::with(['farm'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get production history for the modal
        $productionHistory = ProductionRecord::with(['farm'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        // Get farms for the add product modal
        $farms = Farm::where('status', 'active')->get();

        return view('admin.production', [
            'totalProducts' => $stats['total_products'],
            'totalStock' => $stats['total_stock'],
            'todayProduction' => $stats['today_production'],
            'lowStockAlerts' => $stats['low_stock_alerts'],
            'productionRecords' => $productionRecords,
            'productionHistory' => $productionHistory,
            'farms' => $farms
        ]);
    }

    /**
     * Display sales overview dashboard
     */
    public function sales()
    {
        // Get sales statistics
        $stats = $this->getSalesStats();

        // Get sales records
        $sales = Sale::with(['farm', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get sales history for the modal
        $salesHistory = Sale::with(['farm', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.sales', [
            'totalLivestock' => $stats['total_livestock'],
            'totalSoldQuantity' => $stats['total_sold_quantity'],
            'stockInShed' => $stats['stock_in_shed'],
            'totalSoldAmount' => $stats['total_sold_amount'],
            'sales' => $sales,
            'salesHistory' => $salesHistory
        ]);
    }

    /**
     * Store a new sale record
     */
    public function storeSale(Request $request)
    {
        try {
            $request->validate([
                'dateSold' => 'required|date',
                'type' => 'required|string|max:255',
                'amountSold' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:1',
                'notes' => 'nullable|string|max:500'
            ]);

            $sale = Sale::create([
                'type' => $request->type,
                'amount' => $request->amountSold,
                'quantity' => $request->quantity,
                'notes' => $request->notes,
                'sold_date' => $request->dateSold,
                'user_id' => auth()->id(),
                'farm_id' => auth()->user()->farms->first()->id ?? null
            ]);

            return response()->json(['success' => true, 'sale' => $sale]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a sale record
     */
    public function deleteSale($id)
    {
        try {
            $sale = Sale::findOrFail($id);
            $sale->delete();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Import sales from CSV
     */
    public function importSales(Request $request)
    {
        try {
            $request->validate([
                'csv_data' => 'required|array'
            ]);

            $imported = 0;
            foreach ($request->csv_data as $row) {
                if (isset($row['dateSold']) && isset($row['type']) && isset($row['amountSold'])) {
                    Sale::create([
                        'type' => $row['type'],
                        'amount' => $row['amountSold'],
                        'quantity' => $row['quantity'] ?? 1,
                        'sold_date' => $row['dateSold'],
                        'user_id' => auth()->id(),
                        'farm_id' => auth()->user()->farms->first()->id ?? null
                    ]);
                    $imported++;
                }
            }

            return response()->json(['success' => true, 'imported' => $imported]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display general analysis dashboard
     */
    public function analysis()
    {
        // Get analysis statistics
        $stats = $this->getAnalysisStats();

        // Get farmers with productivity scores
        $farmers = User::where('role', 'farmer')
            ->with(['farms', 'livestock', 'productionRecords'])
            ->get()
            ->map(function ($farmer) {
                $farmer->productivity_score = $this->calculateFarmerProductivity($farmer);
                return $farmer;
            });

        return view('admin.analysis', [
            'totalFarmers' => $stats['total_farmers'],
            'activeFarmers' => $stats['active_farmers'],
            'avgProductivity' => $stats['avg_productivity'],
            'totalRevenue' => $stats['total_revenue'],
            'farmers' => $farmers
        ]);
    }

    /**
     * Get farm statistics
     */
    private function getFarmStats()
    {
        $totalFarms = Farm::count();
        $activeFarms = Farm::where('status', 'active')->count();
        
        $avgProduction = ProductionRecord::avg('milk_quantity') ?? 0;
        $totalRevenue = Sale::sum('amount') ?? 0;

        return [
            'total_farms' => $totalFarms,
            'active_farms' => $activeFarms,
            'avg_production' => $avgProduction,
            'total_revenue' => $totalRevenue
        ];
    }

    /**
     * Get livestock statistics
     */
    private function getLivestockStats()
    {
        $totalLivestock = Livestock::count();
        $healthyCount = Livestock::where('health_status', 'healthy')->count();
        
        $avgProduction = ProductionRecord::avg('milk_quantity') ?? 0;
        $totalValue = Livestock::sum('estimated_value') ?? 0;

        return [
            'total_livestock' => $totalLivestock,
            'healthy_count' => $healthyCount,
            'avg_production' => $avgProduction,
            'total_value' => $totalValue
        ];
    }

    /**
     * Get admin statistics
     */
    private function getAdminStats()
    {
        $totalAdmins = User::where('role', 'admin')->count();
        $activeAdmins = User::where('role', 'admin')->where('status', 'active')->count();
        
        $totalFarms = Farm::count();
        $avgFarmSize = Farm::avg('size_hectares') ?? 0;

        return [
            'total_admins' => $totalAdmins,
            'active_admins' => $activeAdmins,
            'total_farms' => $totalFarms,
            'avg_farm_size' => $avgFarmSize
        ];
    }

    /**
     * Get client statistics
     */
    private function getClientStats()
    {
        $totalClients = User::where('role', 'client')->count();
        $activeClients = User::where('role', 'client')->where('status', 'active')->count();
        
        $totalRevenue = Sale::sum('amount') ?? 0;
        $avgOrderValue = Sale::avg('amount') ?? 0;

        return [
            'total_clients' => $totalClients,
            'active_clients' => $activeClients,
            'total_revenue' => $totalRevenue,
            'avg_order_value' => $avgOrderValue
        ];
    }

    /**
     * Get inventory statistics
     */
    private function getInventoryStats()
    {
        // Placeholder statistics - would need Inventory model
        return [
            'total_items' => 0,
            'low_stock_items' => 0,
            'total_value' => 0,
            'categories' => []
        ];
    }

    /**
     * Get expense statistics
     */
    private function getExpenseStats()
    {
        $totalExpenses = Expense::sum('amount') ?? 0;
        $monthlyBudget = 10000; // Placeholder - would come from settings
        $budgetUsed = Expense::whereMonth('created_at', now()->month)->sum('amount') ?? 0;
        $budgetRemaining = $monthlyBudget - $budgetUsed;

        return [
            'total_expenses' => $totalExpenses,
            'monthly_budget' => $monthlyBudget,
            'budget_used' => $budgetUsed,
            'budget_remaining' => $budgetRemaining
        ];
    }

    /**
     * Get farms overview statistics
     */
    private function getFarmsOverviewStats()
    {
        $totalFarms = Farm::count();
        $activeFarms = Farm::where('status', 'active')->count();
        $inactiveFarms = Farm::where('status', 'inactive')->count();
        
        // Count unique barangays/locations
        $barangayCount = Farm::distinct('location')->count('location');

        return [
            'total_farms_count' => $totalFarms,
            'active_farms_count' => $activeFarms,
            'inactive_farms_count' => $inactiveFarms,
            'barangay_count' => $barangayCount
        ];
    }

    /**
     * Get production statistics
     */
    private function getProductionStats()
    {
        $totalProducts = ProductionRecord::count();
        $totalStock = ProductionRecord::sum('milk_quantity') ?? 0;
        $todayProduction = ProductionRecord::whereDate('created_at', today())->sum('milk_quantity') ?? 0;
        
        // Placeholder for low stock alerts - would need a more sophisticated logic
        $lowStockAlerts = 0;

        return [
            'total_products' => $totalProducts,
            'total_stock' => $totalStock,
            'today_production' => $todayProduction,
            'low_stock_alerts' => $lowStockAlerts
        ];
    }

    /**
     * Get sales statistics
     */
    private function getSalesStats()
    {
        $totalLivestock = Livestock::count();
        $totalSoldQuantity = Sale::sum('quantity') ?? 0;
        $stockInShed = $totalLivestock - $totalSoldQuantity;
        $totalSoldAmount = Sale::sum('amount') ?? 0;

        return [
            'total_livestock' => $totalLivestock,
            'total_sold_quantity' => $totalSoldQuantity,
            'stock_in_shed' => $stockInShed,
            'total_sold_amount' => $totalSoldAmount
        ];
    }

    /**
     * Get analysis statistics
     */
    private function getAnalysisStats()
    {
        $totalFarmers = User::where('role', 'farmer')->count();
        $activeFarmers = User::where('role', 'farmer')->where('status', 'active')->count();
        $avgProductivity = User::where('role', 'farmer')->get()->avg(function ($farmer) {
            return $this->calculateFarmerProductivity($farmer);
        }) ?? 0;
        $totalRevenue = Sale::sum('amount') ?? 0;

        return [
            'total_farmers' => $totalFarmers,
            'active_farmers' => $activeFarmers,
            'avg_productivity' => number_format($avgProductivity, 1),
            'total_revenue' => number_format($totalRevenue)
        ];
    }

    /**
     * Calculate farmer productivity score
     */
    private function calculateFarmerProductivity($farmer)
    {
        // Simple productivity calculation based on production and livestock health
        $productionScore = $farmer->productionRecords->avg('milk_quantity') ?? 0;
        $healthScore = $farmer->livestock->where('health_status', 'healthy')->count() / max($farmer->livestock->count(), 1) * 100;
        $farmScore = $farmer->farms->where('status', 'active')->count() > 0 ? 100 : 0;
        
        return ($productionScore + $healthScore + $farmScore) / 3;
    }

    /**
     * Calculate farm performance score
     */
    private function calculateFarmPerformance($farm)
    {
        // Simple performance calculation based on production and livestock health
        $productionScore = $farm->productionRecords->avg('milk_quantity') ?? 0;
        $healthScore = $farm->livestock->where('health_status', 'healthy')->count() / max($farm->livestock->count(), 1) * 100;
        
        return ($productionScore + $healthScore) / 2;
    }

    /**
     * Calculate livestock health score
     */
    private function calculateHealthScore($animal)
    {
        // Simple health score based on health status and production
        $healthStatus = $animal->health_status === 'healthy' ? 100 : 50;
        $productionScore = $animal->productionRecords->avg('milk_quantity') ?? 0;
        
        return ($healthStatus + $productionScore) / 2;
    }

    /**
     * Calculate productivity score for livestock
     */
    private function calculateProductivityScore($animal)
    {
        $score = 0;
        
        // Base score from production records
        if ($animal->productionRecords && $animal->productionRecords->count() > 0) {
            $totalProduction = $animal->productionRecords->sum('milk_quantity');
            $avgProduction = $animal->productionRecords->avg('milk_quantity');
            
            // Score based on total production (0-50 points)
            $score += min(50, ($totalProduction / 100) * 50);
            
            // Score based on consistency (0-30 points)
            $variance = $animal->productionRecords->map(function($record) use ($avgProduction) {
                return abs($record->milk_quantity - $avgProduction);
            })->avg();
            $consistencyScore = max(0, 30 - ($variance / 10));
            $score += $consistencyScore;
            
            // Score based on recent performance (0-20 points)
            $recentRecords = $animal->productionRecords->sortByDesc('created_at')->take(3);
            if ($recentRecords->count() > 0) {
                $recentAvg = $recentRecords->avg('milk_quantity');
                $recentScore = min(20, ($recentAvg / 50) * 20);
                $score += $recentScore;
            }
        }
        
        return round($score, 1);
    }

    /**
     * Calculate age in months for livestock
     */
    private function calculateAgeInMonths($animal)
    {
        if (!$animal->birth_date) {
            return 0;
        }
        
        $birthDate = \Carbon\Carbon::parse($animal->birth_date);
        $now = \Carbon\Carbon::now();
        
        return $birthDate->diffInMonths($now);
    }

    /**
     * Get expense icon based on category
     */
    private function getExpenseIcon($category)
    {
        $iconMap = [
            'Feed' => 'seedling',
            'Veterinary' => 'stethoscope',
            'Maintenance' => 'tools',
            'Utilities' => 'bolt',
            'Medicine' => 'pills',
            'Equipment' => 'wrench',
            'Other' => 'receipt'
        ];
        
        return $iconMap[$category] ?? 'receipt';
    }

    /**
     * Get expense color based on category
     */
    private function getExpenseColor($category)
    {
        $colorMap = [
            'Feed' => 'success',
            'Veterinary' => 'info',
            'Maintenance' => 'warning',
            'Utilities' => 'warning',
            'Medicine' => 'info',
            'Equipment' => 'warning',
            'Other' => 'primary'
        ];
        
        return $colorMap[$category] ?? 'primary';
    }

    /**
     * Update admin status
     */
    public function updateAdminStatus(Request $request, $id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);
            $admin->update(['status' => $request->status]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }

    /**
     * Reset admin password
     */
    public function resetAdminPassword($id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);
            $newPassword = 'password123'; // Default password
            $admin->update(['password' => Hash::make($newPassword)]);
            
            return response()->json(['success' => true, 'password' => $newPassword]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to reset password'], 500);
        }
    }

    /**
     * Delete admin
     */
    public function deleteAdmin($id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);
            $admin->delete();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete admin'], 500);
        }
    }

    /**
     * Display farmer management interface
     */
    public function manageFarmers()
    {
        $farmers = User::where('role', 'farmer')
            ->with(['farms', 'livestock', 'productionRecords'])
            ->get()
            ->map(function ($farmer) {
                $farmer->total_farms = $farmer->farms->count();
                $farmer->total_livestock = $farmer->livestock->count();
                $farmer->total_production = $farmer->productionRecords->sum('milk_quantity');
                return $farmer;
            });

        return view('admin.manage-farmers', compact('farmers'));
    }

    /**
     * Display farmers list
     */
    public function farmers()
    {
        $farmers = User::where('role', 'farmer')
            ->with(['farms', 'livestock'])
            ->get();

        return view('admin.farmers', compact('farmers'));
    }

    /**
     * Store a new farmer
     */
    public function storeFarmer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $farmer = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => Hash::make($request->password),
                'role' => 'farmer',
                'status' => 'active',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Farmer created successfully!',
                'farmer' => $farmer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create farmer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show farmer details
     */
    public function showFarmer($id)
    {
        try {
            $farmer = User::where('role', 'farmer')
                ->with(['farms', 'livestock', 'productionRecords'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'farmer' => $farmer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer not found'
            ], 404);
        }
    }

    /**
     * Update farmer information
     */
    public function updateFarmer(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        try {
            $farmer = User::where('role', 'farmer')->findOrFail($id);
            $farmer->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Farmer updated successfully!',
                'farmer' => $farmer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update farmer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete farmer
     */
    public function deleteFarmer($id)
    {
        try {
            $farmer = User::where('role', 'farmer')->findOrFail($id);
            $farmer->delete();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete farmer'], 500);
        }
    }

    /**
     * Update farmer status
     */
    public function updateFarmerStatus(Request $request, $id)
    {
        try {
            $farmer = User::where('role', 'farmer')->findOrFail($id);
            $farmer->update(['status' => $request->status]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }

    /**
     * Reset farmer password
     */
    public function resetFarmerPassword($id)
    {
        try {
            $farmer = User::where('role', 'farmer')->findOrFail($id);
            $newPassword = 'password123'; // Default password
            $farmer->update(['password' => Hash::make($newPassword)]);
            
            return response()->json(['success' => true, 'password' => $newPassword]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to reset password'], 500);
        }
    }

    /**
     * Get pending farmers for approval
     */
    public function getPendingFarmers()
    {
        try {
            $pendingFarmers = User::where('role', 'farmer')
                ->where('status', 'pending')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $pendingFarmers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get pending farmers'
            ], 500);
        }
    }

    /**
     * Get active farmers
     */
    public function getActiveFarmers()
    {
        try {
            $activeFarmers = User::where('role', 'farmer')
                ->where('status', 'approved')
                ->where('is_active', true)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $activeFarmers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get active farmers'
            ], 500);
        }
    }

    /**
     * Get farmer statistics
     */
    public function getFarmerStats()
    {
        try {
            $stats = [
                'total' => User::where('role', 'farmer')->count(),
                'active' => User::where('role', 'farmer')->where('status', 'approved')->where('is_active', true)->count(),
                'pending' => User::where('role', 'farmer')->where('status', 'pending')->count(),
            ];
            
            return response()->json(['success' => true, 'data' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to get farmer stats'], 500);
        }
    }

    /**
     * Approve farmer registration
     */
    public function approveFarmer($id)
    {
        try {
            $farmer = User::where('role', 'farmer')->findOrFail($id);
            $farmer->update([
                'status' => 'approved',
                'is_active' => true
            ]);
            
            // Log the approval action
            $this->logAuditAction('approve', 'users', $farmer->id);
            
            return response()->json(['success' => true, 'message' => 'Farmer approved successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to approve farmer'], 500);
        }
    }

    /**
     * Reject farmer registration
     */
    public function rejectFarmer(Request $request, $id)
    {
        try {
            $request->validate([
                'rejection_reason' => 'required|string|max:500'
            ]);

            $farmer = User::where('role', 'farmer')->findOrFail($id);
            $farmer->update([
                'status' => 'rejected',
                'is_active' => false
            ]);
            
            // Log the rejection action
            $this->logAuditAction('reject', 'users', $farmer->id, $request->rejection_reason);
            
            return response()->json(['success' => true, 'message' => 'Farmer rejected successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to reject farmer'], 500);
        }
    }

    /**
     * Deactivate farmer
     */
    public function deactivateFarmer($id)
    {
        try {
            $farmer = User::where('role', 'farmer')->findOrFail($id);
            $farmer->update([
                'status' => 'rejected',
                'is_active' => false
            ]);
            
            // Log the deactivation action
            $this->logAuditAction('deactivate', 'users', $farmer->id);
            
            return response()->json(['success' => true, 'message' => 'Farmer deactivated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to deactivate farmer'], 500);
        }
    }

    /**
     * Contact farmer
     */
    public function contactFarmer(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|max:1000',
            ]);

            // This would typically send an email or notification
            // For now, just return success
            return response()->json(['success' => true, 'message' => 'Message sent to farmer successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send message'], 500);
        }
    }

    /**
     * Log audit action
     */
    private function logAuditAction($action, $tableName, $recordId, $details = null)
    {
        if (Auth::check()) {
            \App\Models\AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'table_name' => $tableName,
                'record_id' => $recordId,
                'details' => $details,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }
}
