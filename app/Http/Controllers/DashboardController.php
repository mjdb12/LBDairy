<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\Livestock;
use App\Models\ProductionRecord;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Issue;
use App\Models\User;
use App\Models\Task;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Show the farmer dashboard.
     */
    public function farmerDashboard()
    {
        try {
            $user = Auth::user();
            
            // Get user's farms first
            $userFarms = Farm::where('owner_id', $user->id)->pluck('id')->toArray();
            
            if (empty($userFarms)) {
                // User has no farms, return empty data
                $livestock = collect();
                $recentProduction = collect();
                $recentSales = collect();
                $tasks = collect();
                $totalLivestock = 0;
                $totalProduction = 0;
                $totalSales = 0;
                $totalExpenses = 0;
                
                return view('dashboard.farmer', compact(
                    'livestock', 'recentProduction', 'recentSales',
                    'totalLivestock', 'totalProduction', 'totalSales', 'totalExpenses', 'tasks'
                ));
            }
            
            $livestock = Livestock::whereIn('farm_id', $userFarms)->with('farm')->latest()->take(5)->get();
            
            // Get recent production records with proper relationships and ordering
            $recentProduction = ProductionRecord::whereIn('farm_id', $userFarms)
                ->with(['livestock', 'farm'])
                ->orderBy('production_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            // Get recent sales with proper relationships and ordering
            $recentSales = Sale::whereIn('farm_id', $userFarms)
                ->with('farm')
                ->orderBy('sale_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            $totalLivestock = Livestock::whereIn('farm_id', $userFarms)->count();
            $totalProduction = ProductionRecord::whereIn('farm_id', $userFarms)->sum('milk_quantity') ?? 0;
            $totalSales = Sale::whereIn('farm_id', $userFarms)->sum('total_amount') ?? 0;
            $totalExpenses = Expense::whereIn('farm_id', $userFarms)->sum('amount') ?? 0;

            // Get tasks for the farmer
            $tasks = Task::where('assigned_to', $user->id)
                ->orderBy('sort_order')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            return view('dashboard.farmer', compact(
                'livestock', 'recentProduction', 'recentSales',
                'totalLivestock', 'totalProduction', 'totalSales', 'totalExpenses', 'tasks'
            ));
        } catch (\Exception $e) {
            Log::error('Error in farmerDashboard: ' . $e->getMessage());
            
            // Return empty collections on error
            $livestock = collect();
            $recentProduction = collect();
            $recentSales = collect();
            $tasks = collect();
            $totalLivestock = 0;
            $totalProduction = 0;
            $totalSales = 0;
            $totalExpenses = 0;
            
            return view('dashboard.farmer', compact(
                'livestock', 'recentProduction', 'recentSales',
                'totalLivestock', 'totalProduction', 'totalSales', 'totalExpenses', 'tasks'
            ));
        }
    }

    /**
     * Show the admin dashboard.
     */
    public function adminDashboard()
    {
        try {
            $user = Auth::user();
            $farms = Farm::withCount('livestock')->get();
            $totalUsers = User::where('role', 'farmer')->count();
            $totalLivestock = Livestock::count();
            $totalProduction = ProductionRecord::sum('milk_quantity') ?? 0;
            $totalSales = Sale::sum('total_amount') ?? 0;
            $totalExpenses = Expense::sum('amount') ?? 0;
            $openIssues = Issue::where('status', 'open')->count();
            
            $recentIssues = Issue::with(['farm', 'reportedBy'])->latest()->take(5)->get();
            $recentProduction = ProductionRecord::with(['farm', 'livestock'])->latest()->take(5)->get();

            return view('dashboard.admin', compact(
                'farms', 'totalUsers', 'totalLivestock', 'totalProduction',
                'totalSales', 'totalExpenses', 'openIssues', 'recentIssues', 'recentProduction'
            ));
        } catch (\Exception $e) {
            Log::error('Error in adminDashboard: ' . $e->getMessage());
            
            // Return empty collections and zero values on error
            $farms = collect();
            $totalUsers = 0;
            $totalLivestock = 0;
            $totalProduction = 0;
            $totalSales = 0;
            $totalExpenses = 0;
            $openIssues = 0;
            $recentIssues = collect();
            $recentProduction = collect();
            
            return view('dashboard.admin', compact(
                'farms', 'totalUsers', 'totalLivestock', 'totalProduction',
                'totalSales', 'totalExpenses', 'openIssues', 'recentIssues', 'recentProduction'
            ));
        }
    }

    /**
     * Show the super admin dashboard.
     */
    public function superAdminDashboard()
    {
        try {
            $totalUsers = User::count();
            $totalFarms = Farm::count();
            $totalLivestock = Livestock::count();
            $totalProduction = ProductionRecord::sum('milk_quantity') ?? 0;
            $totalSales = Sale::sum('total_amount') ?? 0;
            $totalExpenses = Expense::sum('amount') ?? 0;
            $openIssues = Issue::where('status', 'open')->count();

            // Accurate superadmin dashboard KPIs
            $totalAdmins = User::where('role', 'admin')->count();
            $activeAdmins = User::where('role', 'admin')->where('status', 'approved')->count();
            $totalFarmers = User::where('role', 'farmer')->count();
            $pendingAdminRequests = User::where('role', 'admin')->where('status', 'pending')->count();
            $serviceAreasCount = Farm::query()->distinct('location')->count('location');
            
            $usersByRole = User::selectRaw('role, count(*) as count')
                ->groupBy('role')
                ->get();
            
            $recentAuditLogs = AuditLog::with('user')->latest()->take(10)->get();
            $recentIssues = Issue::with(['farm', 'reportedBy'])->latest()->take(5)->get();

            return view('dashboard.superadmin', compact(
                'totalUsers', 'totalFarms', 'totalLivestock', 'totalProduction',
                'totalSales', 'totalExpenses', 'openIssues', 'usersByRole',
                'recentAuditLogs', 'recentIssues',
                'totalAdmins', 'activeAdmins', 'totalFarmers', 'pendingAdminRequests', 'serviceAreasCount'
            ));
        } catch (\Exception $e) {
            Log::error('Error in superAdminDashboard: ' . $e->getMessage());
            
            // Return empty collections and zero values on error
            $totalUsers = 0;
            $totalFarms = 0;
            $totalLivestock = 0;
            $totalProduction = 0;
            $totalSales = 0;
            $totalExpenses = 0;
            $openIssues = 0;
            $totalAdmins = 0;
            $activeAdmins = 0;
            $totalFarmers = 0;
            $pendingAdminRequests = 0;
            $serviceAreasCount = 0;
            $usersByRole = collect();
            $recentAuditLogs = collect();
            $recentIssues = collect();
            
            return view('dashboard.superadmin', compact(
                'totalUsers', 'totalFarms', 'totalLivestock', 'totalProduction',
                'totalSales', 'totalExpenses', 'openIssues', 'usersByRole',
                'recentAuditLogs', 'recentIssues',
                'totalAdmins', 'activeAdmins', 'totalFarmers', 'pendingAdminRequests', 'serviceAreasCount'
            ));
        }
    }

    /**
     * Show the main dashboard (redirects based on role).
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect('/login');
        }

        switch ($user->role) {
            case 'farmer':
                return redirect('/farmer/dashboard');
            case 'admin':
                return redirect('/admin/dashboard');
            case 'superadmin':
                return redirect('/superadmin/dashboard');
            default:
                return redirect('/login');
        }
    }
}
