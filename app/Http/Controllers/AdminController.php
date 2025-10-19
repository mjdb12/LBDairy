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
use App\Models\AuditLog;
use App\Models\LivestockAlert;
use App\Models\Inventory;
use App\Models\Notification;
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
            'phone' => ['nullable', 'regex:/^\d{11}$/'],
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
     * Farm Analysis: Production Trend (last 30 days)
     */
    public function getProductionTrendData(Request $request)
    {
        $days = (int) $request->get('days', 30);
        if ($days < 7 || $days > 90) { $days = 30; }

        $start = now()->startOfDay()->subDays($days - 1);
        $records = ProductionRecord::selectRaw("DATE(COALESCE(production_date, created_at)) as d, SUM(milk_quantity) as qty")
            ->where(function($q) use ($start) {
                $q->whereDate('production_date', '>=', $start)
                  ->orWhereDate('created_at', '>=', $start);
            })
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        $labels = [];
        $data = [];
        $cursor = $start->copy();
        for ($i = 0; $i < $days; $i++) {
            $labels[] = $cursor->format('M d');
            $val = optional($records->firstWhere('d', $cursor->format('Y-m-d')))->qty ?? 0;
            $data[] = (float) $val;
            $cursor->addDay();
        }

        return response()->json(['labels' => $labels, 'data' => $data]);
    }

    /**
     * Farm Analysis: Region Distribution (by Farm location)
     */
    public function getRegionDistributionData()
    {
        $rows = Farm::selectRaw('COALESCE(location, "Unknown") as region, COUNT(*) as cnt')
            ->groupBy('region')
            ->orderByDesc('cnt')
            ->limit(6)
            ->get();

        return response()->json([
            'labels' => $rows->pluck('region'),
            'data' => $rows->pluck('cnt')->map(fn($v) => (int) $v),
        ]);
    }

    /**
     * Farm Analysis: Regional Performance (avg daily production by region, last 30 days)
     */
    public function getRegionalPerformanceData(Request $request)
    {
        $days = (int) $request->get('days', 30);
        if ($days < 7 || $days > 90) { $days = 30; }
        $start = now()->startOfDay()->subDays($days - 1);

        $rows = ProductionRecord::selectRaw('COALESCE(farms.location, "Unknown") as region, SUM(production_records.milk_quantity) as total')
            ->leftJoin('farms', 'production_records.farm_id', '=', 'farms.id')
            ->where(function($q) use ($start) {
                $q->whereDate('production_records.production_date', '>=', $start)
                  ->orWhereDate('production_records.created_at', '>=', $start);
            })
            ->groupBy('region')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        // Average per day over the period
        $labels = $rows->pluck('region');
        $data = $rows->pluck('total')->map(fn($t) => round(($t ?? 0) / max($days,1), 1));
        return response()->json(['labels' => $labels, 'data' => $data]);
    }

    /**
     * Farm Analysis: Growth Trends (monthly totals last 6 months)
     */
    public function getGrowthTrendsData(Request $request)
    {
        $months = (int) $request->get('months', 6);
        if ($months < 3 || $months > 12) { $months = 6; }
        $start = now()->startOfMonth()->subMonths($months - 1);

        $rows = ProductionRecord::selectRaw("DATE_FORMAT(COALESCE(production_date, created_at), '%Y-%m') as ym, SUM(milk_quantity) as total")
            ->where(function($q) use ($start) {
                $q->whereDate('production_date', '>=', $start)
                  ->orWhereDate('created_at', '>=', $start);
            })
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        $labels = [];
        $data = [];
        $cursor = $start->copy();
        for ($i = 0; $i < $months; $i++) {
            $labels[] = $cursor->format('M');
            $ym = $cursor->format('Y-m');
            $val = optional($rows->firstWhere('ym', $ym))->total ?? 0;
            $data[] = round((float) $val, 1);
            $cursor->addMonth();
        }
        return response()->json(['labels' => $labels, 'data' => $data]);
    }

    /**
     * Livestock Analysis: Average Productivity Trend (last 6 months)
     */
    public function getLivestockProductivityTrends(Request $request)
    {
        $months = (int) $request->get('months', 6);
        if ($months < 3 || $months > 12) { $months = 6; }
        $start = now()->startOfMonth()->subMonths($months - 1);

        $rows = ProductionRecord::selectRaw("DATE_FORMAT(COALESCE(production_date, created_at), '%Y-%m') as ym, AVG(milk_quality_score) as avg_quality")
            ->where(function($q) use ($start) {
                $q->whereDate('production_date', '>=', $start)
                  ->orWhereDate('created_at', '>=', $start);
            })
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        $labels = [];
        $data = [];
        $cursor = $start->copy();
        for ($i = 0; $i < $months; $i++) {
            $labels[] = $cursor->format('M');
            $ym = $cursor->format('Y-m');
            $val = optional($rows->firstWhere('ym', $ym))->avg_quality ?? 0;
            $data[] = round((float) $val, 1);
            $cursor->addMonth();
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'label' => 'Average Productivity',
        ]);
    }

    /**
     * Livestock Analysis: per-livestock analysis dataset by type
     */
    public function getLivestockAnalysisData($id, Request $request)
    {
        $type = $request->get('type', 'growth');
        $months = 6;
        $start = now()->startOfMonth()->subMonths($months - 1);

        $labels = [];
        $data = [];
        $cursor = $start->copy();

        if ($type === 'health') {
            $rows = ProductionRecord::selectRaw("DATE_FORMAT(COALESCE(production_date, created_at), '%Y-%m') as ym, AVG(milk_quality_score) as avgq")
                ->where('livestock_id', $id)
                ->where(function($q) use ($start) {
                    $q->whereDate('production_date', '>=', $start)
                      ->orWhereDate('created_at', '>=', $start);
                })
                ->groupBy('ym')
                ->orderBy('ym')
                ->get();
            for ($i = 0; $i < $months; $i++) {
                $labels[] = $cursor->format('M');
                $ym = $cursor->format('Y-m');
                $val = optional($rows->firstWhere('ym', $ym))->avgq ?? 0;
                $data[] = round((float) $val, 1);
                $cursor->addMonth();
            }
            return response()->json(['labels' => $labels, 'data' => $data, 'label' => 'Health Score']);
        }

        if ($type === 'milk') {
            $rows = ProductionRecord::selectRaw("DATE_FORMAT(COALESCE(production_date, created_at), '%Y-%m') as ym, SUM(milk_quantity) as total")
                ->where('livestock_id', $id)
                ->where(function($q) use ($start) {
                    $q->whereDate('production_date', '>=', $start)
                      ->orWhereDate('created_at', '>=', $start);
                })
                ->groupBy('ym')
                ->orderBy('ym')
                ->get();
            for ($i = 0; $i < $months; $i++) {
                $labels[] = $cursor->format('M');
                $ym = $cursor->format('Y-m');
                $val = optional($rows->firstWhere('ym', $ym))->total ?? 0;
                $data[] = round((float) $val, 1);
                $cursor->addMonth();
            }
            return response()->json(['labels' => $labels, 'data' => $data, 'label' => 'Milk Production (L)']);
        }

        if ($type === 'breeding') {
            // Use monthly count of production records as a proxy activity metric
            $rows = ProductionRecord::selectRaw("DATE_FORMAT(COALESCE(production_date, created_at), '%Y-%m') as ym, COUNT(*) as cnt")
                ->where('livestock_id', $id)
                ->where(function($q) use ($start) {
                    $q->whereDate('production_date', '>=', $start)
                      ->orWhereDate('created_at', '>=', $start);
                })
                ->groupBy('ym')
                ->orderBy('ym')
                ->get();
            for ($i = 0; $i < $months; $i++) {
                $labels[] = $cursor->format('M');
                $ym = $cursor->format('Y-m');
                $val = optional($rows->firstWhere('ym', $ym))->cnt ?? 0;
                $data[] = (int) $val;
                $cursor->addMonth();
            }
            return response()->json(['labels' => $labels, 'data' => $data, 'label' => 'Breeding Success']);
        }

        // Default: growth = month-over-month change in milk (L)
        $rows = ProductionRecord::selectRaw("DATE_FORMAT(COALESCE(production_date, created_at), '%Y-%m') as ym, SUM(milk_quantity) as total")
            ->where('livestock_id', $id)
            ->where(function($q) use ($start) {
                $q->whereDate('production_date', '>=', $start)
                  ->orWhereDate('created_at', '>=', $start);
            })
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        $monthly = [];
        $cursor = $start->copy();
        for ($i = 0; $i < $months; $i++) {
            $labels[] = $cursor->format('M');
            $ym = $cursor->format('Y-m');
            $val = optional($rows->firstWhere('ym', $ym))->total ?? 0;
            $monthly[] = (float) $val;
            $cursor->addMonth();
        }
        $data = [];
        for ($i = 0; $i < count($monthly); $i++) {
            $prev = $i === 0 ? 0 : $monthly[$i-1];
            $growth = $prev > 0 ? (($monthly[$i] - $prev) / $prev) * 100 : 0;
            $data[] = round($growth, 1);
        }
        return response()->json(['labels' => array_slice($labels, 0, $months), 'data' => $data, 'label' => 'Growth Rate (%)']);
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
                // Map fields expected by the view without changing UI
                $farm->farm_id = 'F' . str_pad($farm->id, 3, '0', STR_PAD_LEFT);
                $farm->owner_name = $farm->owner->name ?? 'N/A';
                $farm->livestock_count = $farm->livestock->count();
                $today = now()->toDateString();
                $farm->daily_production = $farm->productionRecords
                    ->filter(function($r) use ($today) {
                        $date = $r->production_date ? $r->production_date->toDateString() : ($r->created_at ? $r->created_at->toDateString() : null);
                        return $date === $today;
                    })
                    ->sum('milk_quantity');
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
            ->with(['farms'])
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
        // Load real inventory data
        $inventory = Inventory::orderByDesc('date')->get()->map(function ($item) {
            // Map attributes to align with view expectations without changing UI
            $item->quantity = $item->quantity_text; // alias
            // Preserve UI: show business code in the first column used as ID
            $item->id = $item->code;
            return $item;
        });

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

        // Calculate additional metrics for the dashboard (map model fields to UI fields)
        $totalExpenses = $expenses->sum('amount') ?? 0;
        $feedExpenses = $expenses->where('expense_type', 'Feed')->sum('amount') ?? 0;
        $veterinaryExpenses = $expenses->where('expense_type', 'Veterinary')->sum('amount') ?? 0;
        $maintenanceExpenses = $expenses->where('expense_type', 'Maintenance')->sum('amount') ?? 0;
        
        // Calculate real month-over-month percentage changes
        $currentMonthStart = now()->startOfMonth();
        $prevMonthStart = (clone $currentMonthStart)->subMonth();
        $prevMonthEnd = (clone $currentMonthStart)->subDay();

        $currTotal = Expense::whereBetween('expense_date', [$currentMonthStart, now()])->sum('amount') ?? 0;
        $prevTotal = Expense::whereBetween('expense_date', [$prevMonthStart, $prevMonthEnd])->sum('amount') ?? 0;
        $expenseChange = $prevTotal > 0 ? round((($currTotal - $prevTotal) / $prevTotal) * 100) : 0;

        $currFeed = Expense::where('expense_type', 'Feed')->whereBetween('expense_date', [$currentMonthStart, now()])->sum('amount') ?? 0;
        $prevFeed = Expense::where('expense_type', 'Feed')->whereBetween('expense_date', [$prevMonthStart, $prevMonthEnd])->sum('amount') ?? 0;
        $feedChange = $prevFeed > 0 ? round((($currFeed - $prevFeed) / $prevFeed) * 100) : 0;

        $currVet = Expense::where('expense_type', 'Veterinary')->whereBetween('expense_date', [$currentMonthStart, now()])->sum('amount') ?? 0;
        $prevVet = Expense::where('expense_type', 'Veterinary')->whereBetween('expense_date', [$prevMonthStart, $prevMonthEnd])->sum('amount') ?? 0;
        $veterinaryChange = $prevVet > 0 ? round((($currVet - $prevVet) / $prevVet) * 100) : 0;

        $currMaint = Expense::where('expense_type', 'Maintenance')->whereBetween('expense_date', [$currentMonthStart, now()])->sum('amount') ?? 0;
        $prevMaint = Expense::where('expense_type', 'Maintenance')->whereBetween('expense_date', [$prevMonthStart, $prevMonthEnd])->sum('amount') ?? 0;
        $maintenanceChange = $prevMaint > 0 ? round((($currMaint - $prevMaint) / $prevMaint) * 100) : 0;

        // Enhance expenses with display properties
        $expenses = $expenses->map(function($expense) {
            // Map fields for UI without changing templates
            $expense->date = $expense->expense_date ? $expense->expense_date->format('Y-m-d') : ($expense->created_at ? $expense->created_at->format('Y-m-d') : null);
            $expense->name = $expense->description; // UI expects name
            $expense->icon = $this->getExpenseIcon($expense->expense_type);
            $expense->color = $this->getExpenseColor($expense->expense_type);
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
     * Inventory CRUD endpoints for admin
     */
    public function storeInventory(Request $request)
    {
        try {
            $request->validate([
                'inventoryId' => 'required|string|max:50',
                'inventoryDate' => 'required|date',
                'inventoryCategory' => 'required|string|max:50',
                'inventoryName' => 'required|string|max:255',
                'inventoryQuantity' => 'required|string|max:255',
                'inventoryFarmId' => 'required|string|max:50',
            ]);

            $item = Inventory::create([
                'code' => $request->inventoryId,
                'date' => $request->inventoryDate,
                'category' => $request->inventoryCategory,
                'name' => $request->inventoryName,
                'quantity_text' => $request->inventoryQuantity,
                'farm_id' => $request->inventoryFarmId,
            ]);

            return response()->json(['success' => true, 'item' => $item]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to save inventory item'], 500);
        }
    }

    public function showInventory($id)
    {
        try {
            $item = is_numeric($id)
                ? Inventory::findOrFail($id)
                : Inventory::where('code', $id)->firstOrFail();
            return response()->json(['success' => true, 'item' => $item]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Inventory item not found'], 404);
        }
    }

    public function updateInventory(Request $request, $id)
    {
        try {
            $request->validate([
                'inventoryDate' => 'required|date',
                'inventoryCategory' => 'required|string|max:50',
                'inventoryName' => 'required|string|max:255',
                'inventoryQuantity' => 'required|string|max:255',
                'inventoryFarmId' => 'required|string|max:50',
            ]);

            $item = is_numeric($id)
                ? Inventory::findOrFail($id)
                : Inventory::where('code', $id)->firstOrFail();
            $item->update([
                'date' => $request->inventoryDate,
                'category' => $request->inventoryCategory,
                'name' => $request->inventoryName,
                'quantity_text' => $request->inventoryQuantity,
                'farm_id' => $request->inventoryFarmId,
            ]);

            return response()->json(['success' => true, 'item' => $item]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update inventory item'], 500);
        }
    }

    public function deleteInventory($id)
    {
        try {
            $item = is_numeric($id)
                ? Inventory::findOrFail($id)
                : Inventory::where('code', $id)->firstOrFail();
            $item->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete inventory item'], 500);
        }
    }

    /**
     * Expense CRUD (admin)
     */
    public function storeExpense(Request $request)
    {
        try {
            $request->validate([
                'expenseDate' => 'required|date',
                'expenseName' => 'required|string|max:255',
                'expenseCategory' => 'required|string|max:50',
                'expenseAmount' => 'required|numeric|min:0',
                'expenseDescription' => 'nullable|string',
            ]);

            // Choose a farm to associate; fall back to first farm if current user has none
            $farmId = auth()->user()->farms->first()->id ?? (\App\Models\Farm::first()->id ?? null);

            $expense = Expense::create([
                'farm_id' => $farmId,
                'expense_type' => $request->expenseCategory,
                'description' => $request->expenseName,
                'amount' => $request->expenseAmount,
                'expense_date' => $request->expenseDate,
                'notes' => $request->expenseDescription,
                'recorded_by' => auth()->id(),
            ]);

            // Align fields for UI consumption
            $expense->date = $expense->expense_date ? $expense->expense_date->format('Y-m-d') : null;
            $expense->name = $expense->description;
            $expense->icon = $this->getExpenseIcon($expense->expense_type);
            $expense->color = $this->getExpenseColor($expense->expense_type);
            $expense->paid_amount = $expense->amount;

            return response()->json(['success' => true, 'expense' => $expense]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to save expense'], 500);
        }
    }

    public function deleteExpense($id)
    {
        try {
            $expense = Expense::findOrFail($id);
            $expense->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete expense'], 500);
        }
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
     * Store a new production record (Admin)
     */
    public function storeProduction(Request $request)
    {
        try {
            $request->validate([
                'productType' => 'required|string|max:100',
                'quantity' => 'required|numeric|min:0',
                'unit' => 'required|string|max:50',
                'farmId' => 'required|integer|exists:farms,id',
                'notes' => 'nullable|string|max:500',
            ]);

            $record = ProductionRecord::create([
                'farm_id' => (int) $request->farmId,
                'production_date' => now(),
                'milk_quantity' => (float) $request->quantity,
                'notes' => $request->notes,
                'recorded_by' => auth()->id(),
            ]);

            return response()->json(['success' => true, 'record' => $record]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to add production record'], 500);
        }
    }

    /**
     * Delete a production record (Admin)
     */
    public function deleteProduction($id)
    {
        try {
            $record = ProductionRecord::findOrFail($id);
            $record->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete production record'], 500);
        }
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
        // Basic stats from Inventory model
        $total = Inventory::count();
        $feed = Inventory::where('category', 'Feed')->count();
        $medicine = Inventory::where('category', 'Medicine')->count();
        $equipment = Inventory::where('category', 'Equipment')->count();

        return [
            'total_items' => $total,
            'low_stock_items' => 0, // no numeric stock tracking yet
            'total_value' => 0,     // requires pricing to compute
            'categories' => [
                'Feed' => $feed,
                'Medicine' => $medicine,
                'Equipment' => $equipment,
            ],
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
            
            // Return both keys for backward compatibility with existing UI code
            return response()->json(['success' => true, 'password' => $newPassword, 'newPassword' => $newPassword]);
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
     * Return admin details HTML used by Manage Admins modal.
     */
    public function adminDetails($id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);
            // Minimal HTML snippet (no separate view required)
            $roleBadge = '<span class="badge badge-primary">' . e(ucfirst($admin->role)) . '</span>';
            $statusBadge = '<span class="badge ' . ($admin->status === 'active' ? 'badge-success' : 'badge-secondary') . '">' . e(ucfirst($admin->status ?? 'inactive')) . '</span>';
            $html = '<div class="container-fluid">'
                . '<div class="row"><div class="col-md-6">'
                . '<h6 class="text-primary">Personal Information</h6>'
                . '<p><strong>Name:</strong> ' . e($admin->name ?? 'N/A') . '</p>'
                . '<p><strong>Email:</strong> ' . e($admin->email ?? 'N/A') . '</p>'
                . '<p><strong>Phone:</strong> ' . e($admin->phone ?? 'N/A') . '</p>'
                . '</div><div class="col-md-6">'
                . '<h6 class="text-primary">Account</h6>'
                . '<p><strong>Role:</strong> ' . $roleBadge . '</p>'
                . '<p><strong>Status:</strong> ' . $statusBadge . '</p>'
                . '<p><strong>Created:</strong> ' . e(optional($admin->created_at)->format('Y-m-d H:i') ?? 'N/A') . '</p>'
                . '<p><strong>Last Login:</strong> ' . e(optional($admin->last_login_at)->format('Y-m-d H:i') ?? 'Never') . '</p>'
                . '</div></div></div>';
            return response($html);
        } catch (\Exception $e) {
            return response('<div class="text-danger">Failed to load admin details.</div>', 404);
        }
    }

    /**
     * Return admin JSON data for editing.
     */
    public function editAdmin($id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);
            return response()->json(['success' => true, 'admin' => $admin]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Admin not found'], 404);
        }
    }

    /**
     * Toggle admin status between active and inactive.
     */
    public function toggleAdminStatus($id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);
            $newStatus = $admin->status === 'active' ? 'inactive' : 'active';
            $admin->update(['status' => $newStatus]);
            return response()->json(['success' => true, 'status' => $newStatus]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to toggle status'], 500);
        }
    }

    /**
     * Bulk-approve admins from pending/inactive to active.
     */
    public function bulkApproveAdmins(Request $request)
    {
        $ids = (array) ($request->admin_ids ?? []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No admin IDs provided'], 422);
        }
        try {
            User::where('role', 'admin')->whereIn('id', $ids)->update(['status' => 'active']);
            return response()->json(['success' => true, 'updated' => count($ids)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Bulk approve failed'], 500);
        }
    }

    /**
     * Create a new admin from the Manage Admins modal form.
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => ['nullable', 'regex:/^\d{11}$/'],
            'role' => 'required|string|in:admin,super_admin',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Derive a username from email if not supplied
            $baseUsername = strstr($request->email, '@', true) ?: str_replace(' ', '.', strtolower($request->name));
            $username = $baseUsername;
            $suffix = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $suffix++;
            }

            $admin = User::create([
                'name' => $request->name,
                'username' => $username,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => 'active',
            ]);

            return response()->json(['success' => true, 'admin' => $admin]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create admin'], 500);
        }
    }

    /**
     * Export admins as CSV (simple server-side export for the view button).
     */
    public function exportAdmins()
    {
        $admins = User::where('role', 'admin')->orderBy('created_at', 'desc')->get();
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=Admin_ManageAdminsReport.csv',
        ];

        $callback = function() use ($admins) {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Admin ID', 'Name', 'Email', 'Role', 'Status', 'Last Login', 'Created At']);
            foreach ($admins as $a) {
                fputcsv($output, [
                    'A' . str_pad($a->id, 3, '0', STR_PAD_LEFT),
                    $a->name,
                    $a->email,
                    $a->role,
                    $a->status,
                    optional($a->last_login_at)->format('Y-m-d H:i') ?? 'Never',
                    optional($a->created_at)->format('Y-m-d H:i') ?? '',
                ]);
            }
            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
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
            'phone' => ['nullable', 'regex:/^\d{11}$/'],
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
            'phone' => ['nullable', 'regex:/^\d{11}$/'],
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
                'farmer_id' => 'required|exists:users,id',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|max:1000',
            ]);

            $farmer = User::where('role', 'farmer')->findOrFail($request->farmer_id);

            Notification::create([
                'type' => 'message',
                'title' => 'Message from Admin',
                'message' => $request->subject . ' - ' . $request->message,
                'icon' => 'fas fa-envelope',
                'action_url' => '#',
                'severity' => 'info',
                'is_read' => false,
                'recipient_id' => $farmer->id,
                'metadata' => [
                    'sender_id' => Auth::id(),
                    'sender_name' => Auth::user()->name,
                    'subject' => $request->subject,
                    'message_type' => 'contact_farmer'
                ]
            ]);

            $this->logAuditAction('contact_farmer', 'users', $farmer->id, 'Subject: ' . $request->subject);

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

    /**
     * Get livestock population trends (last N months) for admin dashboard.
     */
    public function getLivestockTrends(Request $request)
    {
        try {
            $months = (int) $request->get('months', 6);
            if ($months < 1 || $months > 24) {
                $months = 6;
            }

            $startDate = now()->startOfMonth()->subMonths($months - 1);

            $aggregates = Livestock::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, type, COUNT(*) as cnt")
                ->where('created_at', '>=', $startDate)
                ->groupBy('ym', 'type')
                ->orderBy('ym')
                ->get();

            // Build month keys and labels
            $labels = [];
            $ymKeys = [];
            $cursor = $startDate->copy();
            for ($i = 0; $i < $months; $i++) {
                $labels[] = $cursor->format('M');
                $ymKeys[] = $cursor->format('Y-m');
                $cursor->addMonth();
            }

            $seriesCow = array_fill(0, $months, 0);
            $seriesGoat = array_fill(0, $months, 0);

            foreach ($aggregates as $row) {
                $index = array_search($row->ym, $ymKeys, true);
                if ($index === false) {
                    continue;
                }
                if ($row->type === 'cow') {
                    $seriesCow[$index] = (int) $row->cnt;
                } elseif ($row->type === 'goat') {
                    $seriesGoat[$index] = (int) $row->cnt;
                }
            }

            return response()->json([
                'success' => true,
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Cattle',
                        'data' => $seriesCow,
                        'borderColor' => '#007bff',
                        'backgroundColor' => 'rgba(0, 123, 255, 0.1)',
                        'tension' => 0.4,
                        'fill' => true,
                    ],
                    [
                        'label' => 'Goats',
                        'data' => $seriesGoat,
                        'borderColor' => '#28a745',
                        'backgroundColor' => 'rgba(40, 167, 69, 0.1)',
                        'tension' => 0.4,
                        'fill' => true,
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load livestock trends'], 500);
        }
    }

    /**
     * Display audit logs for the admin (can see admin and farmer logs, but not super admin).
     */
    public function auditLogs()
    {
        // Get audit logs for admin and farmer roles only (exclude super admin)
        $auditLogs = AuditLog::with(['user'])
                            ->whereHas('user', function($query) {
                                $query->whereIn('role', ['admin', 'farmer']);
                            })
                            ->orWhereNull('user_id') // Include system logs
                            ->orderBy('created_at', 'desc')
                            ->paginate(20);

        // Get stats for admin and farmer logs only
        $totalLogs = AuditLog::whereHas('user', function($query) {
                            $query->whereIn('role', ['admin', 'farmer']);
                        })
                        ->orWhereNull('user_id')
                        ->count();
        
        $todayLogs = AuditLog::whereHas('user', function($query) {
                            $query->whereIn('role', ['admin', 'farmer']);
                        })
                        ->orWhereNull('user_id')
                        ->whereDate('created_at', today())
                        ->count();
        
        $criticalEvents = AuditLog::whereHas('user', function($query) {
                            $query->whereIn('role', ['admin', 'farmer']);
                        })
                        ->orWhereNull('user_id')
                        ->whereIn('severity', ['error', 'critical'])
                        ->count();

        return view('admin.audit-logs', compact(
            'auditLogs',
            'totalLogs',
            'todayLogs',
            'criticalEvents'
        ));
    }

    /**
     * Get audit log details (exclude super admin logs)
     */
    public function getAuditLogDetails($id)
    {
        try {
            $auditLog = AuditLog::with(['user'])
                                ->whereHas('user', function($query) {
                                    $query->whereIn('role', ['admin', 'farmer']);
                                })
                                ->orWhereNull('user_id')
                                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'auditLog' => $auditLog
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load audit log details'
            ], 500);
        }
    }

    /**
     * Export audit logs (exclude super admin logs)
     */
    public function exportAuditLogs(Request $request)
    {
        try {
            $format = $request->get('format', 'csv');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $severity = $request->get('severity');
            
            $query = AuditLog::with(['user'])
                            ->whereHas('user', function($query) {
                                $query->whereIn('role', ['admin', 'farmer']);
                            })
                            ->orWhereNull('user_id');
            
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            
            if ($severity) {
                $query->where('severity', $severity);
            }
            
            $auditLogs = $query->get();
            
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
        $filename = 'admin_audit_logs_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($auditLogs) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Log ID',
                'User',
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
                    $log->user->name ?? 'System',
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
        $content = "Admin Audit Logs Report\n";
        $content .= "Generated on: " . now() . "\n\n";
        
        foreach ($auditLogs as $log) {
            $content .= "Log ID: {$log->id}\n";
            $content .= "User: " . ($log->user->name ?? 'System') . "\n";
            $content .= "Action: {$log->action}\n";
            $content .= "Description: {$log->description}\n";
            $content .= "Severity: {$log->severity}\n";
            $content .= "Created: {$log->created_at}\n";
            $content .= "---\n";
        }

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="admin_audit_logs_' . date('Y-m-d_H-i-s') . '.txt"');
    }

    /**
     * Get current profile picture for admin.
     */
    public function getCurrentProfilePicture()
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            
            return response()->json([
                'success' => true,
                'profile_image' => $user->profile_image ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get profile picture'
            ], 500);
        }
    }

    /**
     * Upload profile picture for admin.
     */
    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        try {
            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Store the file in the public/img directory
                $file->move(public_path('img'), $filename);
                
                // Update user's profile_image field
                $user->update(['profile_image' => $filename]);
                
                // Log the profile picture upload
                AuditLog::create([
                    'user_id' => $user->id,
                    'action' => 'profile_picture_uploaded',
                    'description' => 'Admin profile picture uploaded',
                    'severity' => 'info',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Profile picture uploaded successfully!',
                    'filename' => $filename
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No file uploaded'
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload profile picture: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display all alerts issued by farmers.
     */
    public function farmerAlerts()
    {
        // Get all alerts issued by farmers
        $alerts = LivestockAlert::with(['livestock.farm.owner', 'issuedBy'])
            ->whereHas('issuedBy', function($query) {
                $query->where('role', 'farmer');
            })
            ->orderBy('alert_date', 'desc')
            ->get();

        // Calculate statistics
        $totalAlerts = $alerts->count();
        $activeAlerts = $alerts->where('status', 'active')->count();
        $criticalAlerts = $alerts->where('severity', 'critical')->where('status', 'active')->count();
        $resolvedAlerts = $alerts->where('status', 'resolved')->count();

        return view('admin.farmer-alerts', compact(
            'alerts',
            'totalAlerts',
            'activeAlerts',
            'criticalAlerts',
            'resolvedAlerts'
        ));
    }

    /**
     * Provide farmer alerts as JSON for DataTables.
     */
    public function getFarmerAlertsData()
    {
        $alerts = LivestockAlert::with(['livestock.farm.owner', 'issuedBy'])
            ->whereHas('issuedBy', function($query) {
                $query->where('role', 'farmer');
            })
            ->orderBy('alert_date', 'desc')
            ->get();

        $data = $alerts->map(function($alert) {
            return [
                'id' => $alert->id,
                'farmer_name' => optional($alert->issuedBy)->name ?? 'Unknown',
                'farmer_email' => optional($alert->issuedBy)->email ?? '',
                'livestock_id' => optional($alert->livestock)->livestock_id ?? 'N/A',
                'livestock_type' => optional($alert->livestock)->type ?? '',
                'livestock_breed' => optional($alert->livestock)->breed ?? '',
                'topic' => $alert->topic,
                'description' => $alert->description,
                'severity' => $alert->severity,
                'severity_badge_class' => $alert->severity_badge_class,
                'alert_date' => optional($alert->alert_date)->format('M d, Y') ?? optional($alert->created_at)->format('M d, Y'),
                'status' => $alert->status,
                'status_badge_class' => $alert->status_badge_class,
            ];
        });

        return response()->json($data);
    }

    /**
     * Show alert details.
     */
    public function showFarmerAlert($id)
    {
        $alert = LivestockAlert::with(['livestock.farm.owner', 'issuedBy'])
            ->whereHas('issuedBy', function($query) {
                $query->where('role', 'farmer');
            })
            ->findOrFail($id);

        return response()->json(['success' => true, 'alert' => $alert]);
    }

    /**
     * Update alert status (respond to farmer alert).
     */
    public function updateFarmerAlertStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:resolved,dismissed',
            'resolution_notes' => 'nullable|string',
        ]);

        $alert = LivestockAlert::whereHas('issuedBy', function($query) {
            $query->where('role', 'farmer');
        })->findOrFail($id);

        if ($request->status === 'resolved') {
            $alert->markAsResolved($request->resolution_notes);
        } else {
            $alert->dismiss($request->resolution_notes);
        }

        // Log the action
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'alert_status_updated',
            'description' => "Alert #{$alert->id} status updated to {$request->status}",
            'severity' => 'info',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Return JSON for AJAX requests, otherwise redirect
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Alert status updated successfully!'
            ]);
        }

        return redirect()->route('admin.farmer-alerts')->with('success', 'Alert status updated successfully!');
    }

    /**
     * Get alert statistics for dashboard.
     */
    public function getAlertStats()
    {
        try {
            $stats = [
                'total' => LivestockAlert::whereHas('issuedBy', function($query) {
                    $query->where('role', 'farmer');
                })->count(),
                'active' => LivestockAlert::whereHas('issuedBy', function($query) {
                    $query->where('role', 'farmer');
                })->where('status', 'active')->count(),
                'critical' => LivestockAlert::whereHas('issuedBy', function($query) {
                    $query->where('role', 'farmer');
                })->where('severity', 'critical')->where('status', 'active')->count(),
                'resolved' => LivestockAlert::whereHas('issuedBy', function($query) {
                    $query->where('role', 'farmer');
                })->where('status', 'resolved')->count(),
                'by_severity' => LivestockAlert::whereHas('issuedBy', function($query) {
                    $query->where('role', 'farmer');
                })->select('severity', DB::raw('count(*) as count'))
                    ->groupBy('severity')
                    ->get(),
                'by_status' => LivestockAlert::whereHas('issuedBy', function($query) {
                    $query->where('role', 'farmer');
                })->select('status', DB::raw('count(*) as count'))
                    ->groupBy('status')
                    ->get()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get alert statistics'
            ], 500);
        }
    }

    /**
     * Schedule inspection for a farmer.
     */
    public function scheduleInspection(Request $request)
    {
        $request->validate([
            'farmer_id' => 'required|exists:users,id',
            'inspection_date' => 'required|date|after:today',
            'inspection_time' => 'required|date_format:H:i',
            'priority' => 'required|in:low,medium,high,urgent',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $inspection = \App\Models\Inspection::create([
                'farmer_id' => $request->farmer_id,
                'scheduled_by' => Auth::id(),
                'inspection_date' => $request->inspection_date,
                'inspection_time' => $request->inspection_time,
                'priority' => $request->priority,
                'notes' => $request->notes,
                'status' => 'scheduled',
            ]);

            // Log the action
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'inspection_scheduled',
                'description' => "Scheduled inspection for farmer ID {$request->farmer_id} on {$request->inspection_date}",
                'severity' => 'info',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inspection scheduled successfully',
                'inspection' => $inspection
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to schedule inspection'
            ], 500);
        }
    }

    /**
     * Get list of all inspections.
     */
    public function getInspectionsList()
    {
        try {
            $inspections = \App\Models\Inspection::with(['farmer', 'scheduledBy'])
                ->orderBy('inspection_date', 'asc')
                ->get()
                ->map(function ($inspection) {
                    return [
                        'id' => $inspection->id,
                        'farmer_id' => $inspection->farmer_id,
                        'inspection_date' => $inspection->inspection_date,
                        'inspection_time' => $inspection->inspection_time,
                        'priority' => $inspection->priority,
                        'status' => $inspection->status,
                        'notes' => $inspection->notes,
                        'findings' => $inspection->findings,
                        'farmer' => [
                            'first_name' => $inspection->farmer->first_name ?? null,
                            'last_name' => $inspection->farmer->last_name ?? null,
                            'email' => $inspection->farmer->email ?? null,
                            'phone' => $inspection->farmer->phone ?? null,
                            'farm_name' => $inspection->farmer->farm_name ?? null,
                            'barangay' => $inspection->farmer->barangay ?? null,
                        ],
                        'scheduled_by' => [
                            'name' => $inspection->scheduledBy->name ?? null,
                        ],
                    ];
                });

            return response()->json(['success' => true, 'data' => $inspections]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load inspections'], 500);
        }
    }

    /**
     * Show inspection details.
     */
    public function showInspection($id)
    {
        try {
            $inspection = \App\Models\Inspection::with(['farmer', 'scheduledBy'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $inspection
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inspection not found'
            ], 404);
        }
    }

    /**
     * Update inspection.
     */
    public function updateInspection(Request $request, $id)
    {
        $request->validate([
            'inspection_date' => 'required|date',
            'inspection_time' => 'required|date_format:H:i',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:scheduled,completed,cancelled,rescheduled',
            'notes' => 'nullable|string|max:1000',
            'findings' => 'nullable|string|max:1000',
        ]);

        try {
            $inspection = \App\Models\Inspection::findOrFail($id);
            
            $inspection->update([
                'inspection_date' => $request->inspection_date,
                'inspection_time' => $request->inspection_time,
                'priority' => $request->priority,
                'status' => $request->status,
                'notes' => $request->notes,
                'findings' => $request->findings,
            ]);

            // Log the action
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'inspection_updated',
                'description' => "Updated inspection #{$id}",
                'severity' => 'info',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inspection updated successfully',
                'inspection' => $inspection
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update inspection'
            ], 500);
        }
    }

    /**
     * Cancel inspection.
     */
    public function cancelInspection(Request $request, $id)
    {
        try {
            $inspection = \App\Models\Inspection::findOrFail($id);
            $inspection->update(['status' => 'cancelled']);

            // Log the action
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'inspection_cancelled',
                'description' => "Inspection #{$id} cancelled",
                'severity' => 'warning',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inspection cancelled successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel inspection'
            ], 500);
        }
    }

    /**
     * Get inspection statistics.
     */
    public function getInspectionStats()
    {
        try {
            $total = \App\Models\Inspection::count();
            $scheduled = \App\Models\Inspection::where('status', 'scheduled')->count();
            $completed = \App\Models\Inspection::where('status', 'completed')->count();
            $urgent = \App\Models\Inspection::where('priority', 'urgent')->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $total,
                    'scheduled' => $scheduled,
                    'completed' => $completed,
                    'urgent' => $urgent
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load stats'], 500);
        }
    }

    /**
     * Show Schedule Inspections page.
     */
    public function scheduleInspectionsPage()
    {
        return view('admin.schedule-inspections');
    }

    /**
     * Get inspections for a specific farmer.
     */
    public function getFarmerInspections($farmerId)
    {
        try {
            $inspections = \App\Models\Inspection::with('scheduledBy')
                ->where('farmer_id', $farmerId)
                ->orderBy('inspection_date', 'asc')
                ->get()
                ->map(function ($inspection) {
                    return [
                        'id' => $inspection->id,
                        'farmer_id' => $inspection->farmer_id,
                        'inspection_date' => $inspection->inspection_date,
                        'inspection_time' => $inspection->inspection_time,
                        'priority' => $inspection->priority,
                        'status' => $inspection->status,
                        'notes' => $inspection->notes,
                        'calendar_start' => $inspection->calendar_start,
                        'scheduled_by' => $inspection->scheduledBy ? [
                            'name' => $inspection->scheduledBy->name ?? 'Admin'
                        ] : null
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $inspections
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch farmer inspections'
            ], 500);
        }
    }
}
