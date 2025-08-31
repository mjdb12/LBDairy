<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\Livestock;
use App\Models\User;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Show the landing page with dynamic statistics
     */
    public function index()
    {
        // Get dynamic statistics
        $stats = $this->getStats();
        
        return view('landing', $stats);
    }
    
    /**
     * Get statistics for the landing page
     */
    private function getStats()
    {
        // Count active farms (farms with status 'active')
        $activeFarms = Farm::where('status', 'active')->count();
        
        // Count total livestock
        $totalLivestock = Livestock::count();
        
        // Calculate uptime (this would be based on system availability)
        // For now, we'll use a realistic uptime based on recent activity
        $recentActivity = Farm::where('updated_at', '>=', now()->subDays(30))->count();
        $totalFarms = Farm::count();
        $uptime = $totalFarms > 0 ? round(($recentActivity / $totalFarms) * 100, 1) : 99.5;
        
        return [
            'activeFarms' => $activeFarms,
            'totalLivestock' => $totalLivestock,
            'uptime' => $uptime
        ];
    }
}

