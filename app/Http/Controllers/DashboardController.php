<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\Livestock;
use App\Models\ProductionRecord;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the farmer dashboard.
     */
    public function farmerDashboard()
    {
        $user = Auth::user();
        $farms = $user->farms()->withCount('livestock')->get();
        $livestock = $user->livestock()->with('farm')->latest()->take(5)->get();
        $recentProduction = $user->productionRecords()->with('livestock')->latest()->take(5)->get();
        $recentSales = $user->sales()->latest()->take(5)->get();
        
        $totalLivestock = $user->livestock()->count();
        $totalProduction = $user->productionRecords()->sum('milk_quantity');
        $totalSales = $user->sales()->sum('total_amount');
        $totalExpenses = $user->expenses()->sum('amount');

        return view('dashboard.farmer', compact(
            'farms', 'livestock', 'recentProduction', 'recentSales',
            'totalLivestock', 'totalProduction', 'totalSales', 'totalExpenses'
        ));
    }

    /**
     * Show the admin dashboard.
     */
    public function adminDashboard()
    {
        $user = Auth::user();
        $farms = Farm::withCount('livestock')->get();
        $totalUsers = \App\Models\User::where('role', 'farmer')->count();
        $totalLivestock = Livestock::count();
        $totalProduction = ProductionRecord::sum('milk_quantity');
        $totalSales = Sale::sum('total_amount');
        $totalExpenses = Expense::sum('amount');
        $openIssues = Issue::where('status', 'open')->count();
        
        $recentIssues = Issue::with(['farm', 'reportedBy'])->latest()->take(5)->get();
        $recentProduction = ProductionRecord::with(['farm', 'livestock'])->latest()->take(5)->get();

        return view('dashboard.admin', compact(
            'farms', 'totalUsers', 'totalLivestock', 'totalProduction',
            'totalSales', 'totalExpenses', 'openIssues', 'recentIssues', 'recentProduction'
        ));
    }

    /**
     * Show the super admin dashboard.
     */
    public function superAdminDashboard()
    {
        $totalUsers = \App\Models\User::count();
        $totalFarms = Farm::count();
        $totalLivestock = Livestock::count();
        $totalProduction = ProductionRecord::sum('milk_quantity');
        $totalSales = Sale::sum('total_amount');
        $totalExpenses = Expense::sum('amount');
        $openIssues = Issue::where('status', 'open')->count();
        
        $usersByRole = \App\Models\User::selectRaw('role, count(*) as count')
            ->groupBy('role')
            ->get();
        
        $recentAuditLogs = \App\Models\AuditLog::with('user')->latest()->take(10)->get();
        $recentIssues = Issue::with(['farm', 'reportedBy'])->latest()->take(5)->get();

        return view('dashboard.superadmin', compact(
            'totalUsers', 'totalFarms', 'totalLivestock', 'totalProduction',
            'totalSales', 'totalExpenses', 'openIssues', 'usersByRole',
            'recentAuditLogs', 'recentIssues'
        ));
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
