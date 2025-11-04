<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LivestockController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\AdminApprovalController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', [App\Http\Controllers\LandingController::class, 'index']);

// Test route - temporarily disabled
// Route::get('/test', [TestController::class, 'index']);

// Test helper functions
Route::get('/test-helpers', function() {
    return response()->json([
        'getActionBadgeClass' => function_exists('getActionBadgeClass'),
        'getSeverityBadgeClass' => function_exists('getSeverityBadgeClass'),
        'getActionDescription' => function_exists('getActionDescription'),
        'test_action_badge' => getActionBadgeClass('create'),
        'test_severity_badge' => getSeverityBadgeClass('info'),
        'test_description' => getActionDescription('create', 'users')
    ]);
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Password reset routes
Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])
    ->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
    ->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])
    ->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
    ->name('password.store');

// Email verification routes
Route::get('/verify-email', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->intended('/dashboard');
})->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Protected routes (require verified email)
Route::middleware(['auth', 'verified', 'prevent-back-history'])->group(function () {
    // Main dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Calendar routes (shared by roles)
    Route::get('/calendar/events', [App\Http\Controllers\CalendarController::class, 'index'])->name('calendar.events.index');
    Route::post('/calendar/events', [App\Http\Controllers\CalendarController::class, 'store'])->name('calendar.events.store');
    Route::put('/calendar/events/{id}', [App\Http\Controllers\CalendarController::class, 'update'])->name('calendar.events.update');
    
    // Farmer routes
    Route::middleware(['auth', 'role:farmer'])->prefix('farmer')->name('farmer.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'farmerDashboard'])->name('dashboard');
        Route::get('/profile', function () { return view('farmer.profile'); })->name('profile');
        Route::put('/profile', [FarmerController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [FarmerController::class, 'changePassword'])->name('profile.password');
        Route::post('/profile/picture', [FarmerController::class, 'uploadProfilePicture'])->name('profile.picture');
        Route::get('/livestock', [FarmerController::class, 'livestock'])->name('livestock');
        Route::post('/livestock', [FarmerController::class, 'storeLivestock'])->name('livestock.store');
        Route::get('/livestock/{id}', [FarmerController::class, 'showLivestock'])->name('livestock.show');
        Route::get('/livestock/{id}/print', [FarmerController::class, 'printLivestock'])->name('livestock.print');
        Route::get('/livestock/{id}/qr-code', [FarmerController::class, 'generateQRCode'])->name('livestock.qr-code');
        Route::get('/livestock/{id}/edit', [FarmerController::class, 'editLivestock'])->name('livestock.edit');
        Route::put('/livestock/{id}', [FarmerController::class, 'updateLivestock'])->name('livestock.update');
        Route::delete('/livestock/{id}', [FarmerController::class, 'deleteLivestock'])->name('livestock.destroy');
        Route::post('/livestock/{id}/status', [FarmerController::class, 'updateLivestockStatus'])->name('livestock.update-status');
        // Lightweight livestock search for sire/dam dropdowns
        Route::get('/livestock/search', [LivestockController::class, 'search'])->name('livestock.search');
        Route::get('/production', [FarmerController::class, 'production'])->name('production');
        Route::post('/production', [FarmerController::class, 'storeProduction'])->name('production.store');
        // Place static routes before parameterized routes to avoid conflicts
        Route::get('/production/history', [FarmerController::class, 'productionHistory'])->name('production.history');
        Route::get('/production/{id}', [FarmerController::class, 'showProduction'])->name('production.show');
        Route::put('/production/{id}', [FarmerController::class, 'updateProduction'])->name('production.update');
        Route::delete('/production/{id}', [FarmerController::class, 'deleteProduction'])->name('production.destroy');
        Route::get('/users', function () { return view('farmer.users'); })->name('users');
        Route::get('/suppliers', [App\Http\Controllers\FarmerController::class, 'suppliers'])->name('suppliers');
        Route::delete('/suppliers/{expenseType}', [App\Http\Controllers\FarmerController::class, 'deleteSupplier'])->name('suppliers.destroy');
        Route::get('/schedule', function () { return view('farmer.schedule'); })->name('schedule');
        Route::get('/scan', function () { return view('farmer.scan'); })->name('scan');
        Route::get('/scan/{id}', [FarmerController::class, 'scanLivestock'])->name('scan.livestock');
        Route::get('/sales', [App\Http\Controllers\FarmerController::class, 'sales'])->name('sales');
        Route::post('/sales', [App\Http\Controllers\FarmerController::class, 'storeSale'])->name('sales.store');
        Route::delete('/sales/{id}', [App\Http\Controllers\FarmerController::class, 'deleteSale'])->name('sales.destroy');
        Route::get('/sales/{id}', [App\Http\Controllers\FarmerController::class, 'showSale'])->name('sales.show');
        Route::get('/sales/{id}/edit', [App\Http\Controllers\FarmerController::class, 'editSale'])->name('sales.edit');
        Route::put('/sales/{id}', [App\Http\Controllers\FarmerController::class, 'updateSale'])->name('sales.update');
        Route::get('/expenses', [FarmerController::class, 'expenses'])->name('expenses');
        Route::post('/expenses', [FarmerController::class, 'storeExpense'])->name('expenses.store');
        // Static history route before parameterized routes
        Route::get('/expenses/history', [FarmerController::class, 'expenseHistory'])->name('expenses.history');
        Route::get('/expenses/{id}', [FarmerController::class, 'showExpense'])->name('expenses.show');
        Route::put('/expenses/{id}', [FarmerController::class, 'updateExpense'])->name('expenses.update');
        Route::delete('/expenses/{id}', [FarmerController::class, 'deleteExpense'])->name('expenses.destroy');
        Route::get('/issues', [FarmerController::class, 'issues'])->name('issues');
        Route::get('/issues/{id}', [FarmerController::class, 'showIssue'])->name('issues.show');
        Route::get('/issue-alerts', [FarmerController::class, 'issueAlerts'])->name('issue-alerts');
        Route::post('/issue-alerts', [FarmerController::class, 'storeAlert'])->name('issue-alerts.store');
        Route::get('/issue-alerts/{id}', [FarmerController::class, 'showAlert'])->name('issue-alerts.show');
        Route::patch('/issue-alerts/{id}/status', [FarmerController::class, 'updateAlertStatus'])->name('issue-alerts.update-status');
        Route::post('/alerts/{id}/mark-read', [FarmerController::class, 'markAlertAsRead'])->name('alerts.mark-read');
        Route::get('/farm-analysis', [App\Http\Controllers\FarmerController::class, 'farmAnalysis'])->name('farm-analysis');
        Route::get('/livestock-analysis', [App\Http\Controllers\FarmerController::class, 'livestockAnalysis'])->name('livestock-analysis');
        Route::get('/livestock/{id}/analysis', [App\Http\Controllers\FarmerController::class, 'getLivestockAnalysis'])->name('livestock.analysis');
        Route::get('/livestock/{id}/history', [App\Http\Controllers\FarmerController::class, 'getLivestockHistory'])->name('livestock.history');
        // Livestock auxiliary endpoints used by farmer/livestock view modals
        Route::get('/livestock/{id}/production-records', [FarmerController::class, 'getLivestockProductionRecords'])->name('livestock.production-records');
        Route::get('/livestock/{id}/health-records', [FarmerController::class, 'getLivestockHealthRecords'])->name('livestock.health-records');
        Route::get('/livestock/{id}/breeding-records', [FarmerController::class, 'getLivestockBreedingRecords'])->name('livestock.breeding-records');
        Route::get('/livestock/{id}/growth-records', [FarmerController::class, 'getLivestockGrowthRecords'])->name('livestock.growth-records');
        Route::post('/livestock/{id}/health', [FarmerController::class, 'storeHealthRecord'])->name('livestock.health.store');
        Route::post('/livestock/{id}/breeding', [FarmerController::class, 'storeBreedingRecord'])->name('livestock.breeding.store');
        Route::post('/livestock/{id}/growth', [FarmerController::class, 'storeGrowthRecord'])->name('livestock.growth.store');
        // Admins list for veterinarian dropdown
        Route::get('/admins', [FarmerController::class, 'listAdmins'])->name('admins.list');
        Route::get('/clients', [App\Http\Controllers\FarmerController::class, 'clients'])->name('clients');
        Route::get('/inventory', [App\Http\Controllers\FarmerController::class, 'inventory'])->name('inventory');
        // Static routes before parameterized routes
        Route::get('/inventory/history', [App\Http\Controllers\FarmerController::class, 'inventoryHistory'])->name('inventory.history');
        // CRUD routes for inventory
        Route::post('/inventory', [App\Http\Controllers\FarmerController::class, 'storeInventory'])->name('inventory.store');
        Route::get('/inventory/{id}', [App\Http\Controllers\FarmerController::class, 'showInventory'])->name('inventory.show');
        Route::put('/inventory/{id}', [App\Http\Controllers\FarmerController::class, 'updateInventory'])->name('inventory.update');
        Route::delete('/inventory/{id}', [App\Http\Controllers\FarmerController::class, 'deleteInventory'])->name('inventory.destroy');
        
        // Inspection routes for farmer
        Route::get('/inspections/{id}', [FarmerController::class, 'showInspection'])->name('inspections.show');
        Route::post('/inspections/{id}/complete', [FarmerController::class, 'completeInspection'])->name('inspections.complete');
        
        // Task routes for farmer
        Route::get('/tasks', [App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'show'])->name('tasks.show');
        Route::post('/tasks', [App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
        Route::put('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');

        // Notification routes for farmer
        Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'getNotifications'])->name('notifications');
        Route::post('/notifications/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    });
    
    // Admin routes
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        Route::get('/profile', function () { return view('admin.profile'); })->name('profile');
        Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [AdminController::class, 'changePassword'])->name('profile.password');
        Route::post('/profile/picture', [AdminController::class, 'uploadProfilePicture'])->name('profile.picture');
        Route::get('/profile/picture/current', [AdminController::class, 'getCurrentProfilePicture'])->name('profile.picture.current');
        Route::get('/farms', [AdminController::class, 'farms'])->name('farms');
        // Farmer management routes
        Route::get('/manage-farmers', [App\Http\Controllers\AdminController::class, 'manageFarmers'])->name('manage-farmers');
        Route::get('/farmers', [App\Http\Controllers\AdminController::class, 'farmers'])->name('farmers');
        Route::post('/farmers', [App\Http\Controllers\AdminController::class, 'storeFarmer'])->name('farmers.store');
        // Farmer management routes
        Route::get('/farmers/pending', [App\Http\Controllers\AdminController::class, 'getPendingFarmers'])->name('farmers.pending');
        Route::get('/farmers/active', [App\Http\Controllers\AdminController::class, 'getActiveFarmers'])->name('farmers.active');
        Route::get('/farmers/stats', [App\Http\Controllers\AdminController::class, 'getFarmerStats'])->name('farmers.stats');
        Route::post('/farmers/{id}/approve', [App\Http\Controllers\AdminController::class, 'approveFarmer'])->name('farmers.approve');
        Route::post('/farmers/{id}/reject', [App\Http\Controllers\AdminController::class, 'rejectFarmer'])->name('farmers.reject');
        Route::post('/farmers/{id}/deactivate', [App\Http\Controllers\AdminController::class, 'deactivateFarmer'])->name('farmers.deactivate');
        Route::post('/farmers/contact', [App\Http\Controllers\AdminController::class, 'contactFarmer'])->name('farmers.contact');
        
        Route::get('/farmers/{id}', [App\Http\Controllers\AdminController::class, 'showFarmer'])->name('farmers.show');
        Route::post('/farmers/{id}/update', [App\Http\Controllers\AdminController::class, 'updateFarmer'])->name('farmers.update');
        
        // User approval routes
        Route::get('/approvals', [AdminApprovalController::class, 'index'])->name('approvals');
        Route::get('/approvals/pending-data', [AdminApprovalController::class, 'pendingData'])->name('approvals.pending-data');
        Route::get('/approvals/{id}', [AdminApprovalController::class, 'show'])->name('approvals.show');
        Route::post('/approvals/{id}/approve', [AdminApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{id}/reject', [AdminApprovalController::class, 'reject'])->name('approvals.reject');
        
        // Pending registrations routes (for backward compatibility)
        Route::get('/pending-registrations', [AdminApprovalController::class, 'pendingRegistrations'])->name('pending-registrations');
        Route::get('/pending-registrations/{id}', [AdminApprovalController::class, 'show'])->name('pending-registrations.show');
        Route::post('/pending-registrations/{id}/approve', [AdminApprovalController::class, 'approve'])->name('pending-registrations.approve');
        Route::post('/pending-registrations/{id}/reject', [AdminApprovalController::class, 'reject'])->name('pending-registrations.reject');
        Route::delete('/farmers/{id}', [App\Http\Controllers\AdminController::class, 'deleteFarmer'])->name('farmers.destroy');
        Route::patch('/farmers/{id}/status', [App\Http\Controllers\AdminController::class, 'updateFarmerStatus'])->name('farmers.update-status');
        Route::post('/farmers/{id}/reset-password', [App\Http\Controllers\AdminController::class, 'resetFarmerPassword'])->name('farmers.reset-password');
        

        
        // Issue management routes
        Route::get('/manage-issues', [App\Http\Controllers\IssueController::class, 'index'])->name('issues.index');
        Route::get('/issues/create', [App\Http\Controllers\IssueController::class, 'create'])->name('issues.create');
        Route::post('/issues', [App\Http\Controllers\IssueController::class, 'store'])->name('issues.store');
        
        // New farmer-specific issue routes (must come before parameterized routes)
        Route::get('/issues/farmers', [App\Http\Controllers\IssueController::class, 'getFarmers'])->name('issues.farmers');
        Route::get('/issues/farmer/{id}/livestock', [App\Http\Controllers\IssueController::class, 'getFarmerLivestock'])->name('issues.farmer-livestock');
        
        // Parameterized issue routes (must come after specific routes)
        Route::get('/issues/{id}', [App\Http\Controllers\IssueController::class, 'show'])->name('issues.show');
        Route::get('/issues/{id}/edit', [App\Http\Controllers\IssueController::class, 'edit'])->name('issues.edit');
        Route::put('/issues/{id}', [App\Http\Controllers\IssueController::class, 'update'])->name('issues.update');
        Route::delete('/issues/{id}', [App\Http\Controllers\IssueController::class, 'destroy'])->name('issues.destroy');
        Route::post('/issues/{id}/status', [App\Http\Controllers\IssueController::class, 'updateStatus'])->name('issues.update-status');
        Route::get('/issues-stats', [App\Http\Controllers\IssueController::class, 'getStats'])->name('issues.stats');
        Route::get('/issues-export', [App\Http\Controllers\IssueController::class, 'export'])->name('issues.export');
        Route::get('/urgent-issues', [App\Http\Controllers\IssueController::class, 'getUrgentIssues'])->name('issues.urgent');
        
        // Farmer Alerts routes
        Route::get('/farmer-alerts', [App\Http\Controllers\AdminController::class, 'farmerAlerts'])->name('farmer-alerts');
        Route::get('/farmer-alerts/{id}', [App\Http\Controllers\AdminController::class, 'showFarmerAlert'])->name('farmer-alerts.show');
        Route::patch('/farmer-alerts/{id}/status', [App\Http\Controllers\AdminController::class, 'updateFarmerAlertStatus'])->name('farmer-alerts.update-status');
        Route::get('/farmer-alerts-stats', [App\Http\Controllers\AdminController::class, 'getAlertStats'])->name('farmer-alerts.stats');
        Route::get('/farmer-alerts-data', [App\Http\Controllers\AdminController::class, 'getFarmerAlertsData'])->name('farmer-alerts.data');
        
        // Analysis routes
        Route::get('/manage-analysis', [App\Http\Controllers\AnalysisController::class, 'index'])->name('analysis.index');
        Route::get('/analysis/farmer/{id}/data', [App\Http\Controllers\AnalysisController::class, 'getFarmerData'])->name('analysis.farmer-data');
        Route::get('/analysis/farmer/{id}/details', [App\Http\Controllers\AnalysisController::class, 'getFarmerDetails'])->name('analysis.farmer-details');
        Route::post('/analysis/farmer/{id}/status', [App\Http\Controllers\AnalysisController::class, 'updateStatus'])->name('analysis.update-status');
        Route::delete('/analysis/farmer/{id}', [App\Http\Controllers\AnalysisController::class, 'deleteFarmer'])->name('analysis.delete-farmer');
        Route::get('/analysis/export', [App\Http\Controllers\AnalysisController::class, 'export'])->name('analysis.export');
        
        // Debug route for testing farmer data
        Route::get('/debug/farmer/{id}', function($id) {
            try {
                $farmer = App\Models\User::find($id);
                if (!$farmer) {
                    return response()->json(['error' => 'Farmer not found'], 404);
                }
                return response()->json([
                    'farmer' => $farmer,
                    'farms_count' => $farmer->farms()->count(),
                    'livestock_count' => $farmer->livestock()->count(),
                    'production_records_count' => $farmer->productionRecords()->count()
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        });
        
        // Farm analysis routes
        Route::get('/farm-analysis', [App\Http\Controllers\AdminController::class, 'farmAnalysis'])->name('farm-analysis');
        
        // Livestock analysis routes
        Route::get('/livestock-analysis', [App\Http\Controllers\AdminController::class, 'livestockAnalysis'])->name('livestock-analysis');
        
        // Admin management routes
        Route::get('/manage-admins', [App\Http\Controllers\AdminController::class, 'manageAdmins'])->name('manage-admins');
        Route::post('/admins/{id}/status', [App\Http\Controllers\AdminController::class, 'updateAdminStatus'])->name('admins.update-status');
        Route::post('/admins/{id}/reset-password', [App\Http\Controllers\AdminController::class, 'resetAdminPassword'])->name('admins.reset-password');
        Route::delete('/admins/{id}', [App\Http\Controllers\AdminController::class, 'deleteAdmin'])->name('admins.delete');
        // Endpoints used by view JS expectations
        Route::get('/admins/{id}/details', [App\Http\Controllers\AdminController::class, 'adminDetails']);
        Route::get('/admins/{id}/edit', [App\Http\Controllers\AdminController::class, 'editAdmin']);
        Route::post('/admins/{id}/toggle-status', [App\Http\Controllers\AdminController::class, 'toggleAdminStatus']);
        Route::post('/admins/bulk-approve', [App\Http\Controllers\AdminController::class, 'bulkApproveAdmins']);
        Route::post('/admins/store', [App\Http\Controllers\AdminController::class, 'storeAdmin']);
        Route::get('/admins/export', [App\Http\Controllers\AdminController::class, 'exportAdmins']);
        
        // Client management routes
        Route::get('/clients', [App\Http\Controllers\AdminController::class, 'manageClients'])->name('clients');
        
        // Inventory routes disabled (feature not in current plan)
        
        // Expense routes disabled (feature not in current plan)
        
        // Farm management routes
        Route::get('/manage-farms', [App\Http\Controllers\FarmController::class, 'index'])->name('farms.index');
        Route::get('/farms/{id}', [App\Http\Controllers\FarmController::class, 'show'])->name('farms.show');
        Route::get('/farms/{id}/details', [App\Http\Controllers\FarmController::class, 'details'])->name('farms.details');
        Route::post('/farms/{id}/status', [App\Http\Controllers\FarmController::class, 'updateStatus'])->name('farms.update-status');
        Route::delete('/farms/{id}', [App\Http\Controllers\FarmController::class, 'destroy'])->name('farms.destroy');
        Route::post('/farms/import', [App\Http\Controllers\FarmController::class, 'import'])->name('farms.import');
        Route::get('/farms/export', [App\Http\Controllers\FarmController::class, 'export'])->name('farms.export');

        // Farmers management AJAX endpoints (used by manage-farmers view)
        Route::get('/farmers/pending', [App\Http\Controllers\AdminController::class, 'getPendingFarmers'])->name('farmers.pending');
        Route::get('/farmers/active', [App\Http\Controllers\AdminController::class, 'getActiveFarmers'])->name('farmers.active');
        Route::get('/farmers/stats', [App\Http\Controllers\AdminController::class, 'getFarmerStats'])->name('farmers.stats');
        Route::post('/farmers/{id}/approve', [App\Http\Controllers\AdminController::class, 'approveFarmer'])->name('farmers.approve');
        Route::post('/farmers/{id}/reject', [App\Http\Controllers\AdminController::class, 'rejectFarmer'])->name('farmers.reject');
        Route::post('/farmers/{id}/deactivate', [App\Http\Controllers\AdminController::class, 'deactivateFarmer'])->name('farmers.deactivate');
        Route::post('/farmers/contact', [App\Http\Controllers\AdminController::class, 'contactFarmer'])->name('farmers.contact');
        Route::get('/farmers/{id}', [App\Http\Controllers\AdminController::class, 'showFarmer'])->name('farmers.show');
        
        // Livestock management routes
        Route::get('/manage-livestock', [App\Http\Controllers\LivestockController::class, 'index'])->name('livestock.index');
        Route::get('/livestock/create', [App\Http\Controllers\LivestockController::class, 'create'])->name('livestock.create');
        Route::post('/livestock', [App\Http\Controllers\LivestockController::class, 'store'])->name('livestock.store');
        Route::get('/livestock/export', [App\Http\Controllers\LivestockController::class, 'export'])->name('livestock.export');
        
        // New farmer-specific livestock routes (must come before parameterized routes)
        Route::get('/livestock/farmers', [App\Http\Controllers\LivestockController::class, 'getFarmers'])->name('livestock.farmers');
        Route::get('/livestock/farmer/{id}/livestock', [App\Http\Controllers\LivestockController::class, 'getFarmerLivestock'])->name('livestock.farmer-livestock');
        Route::get('/livestock/farmer/{id}/farms', [App\Http\Controllers\LivestockController::class, 'getFarmerFarms'])->name('livestock.farmer-farms');
        
        // Parameterized livestock routes (must come after specific routes)
        Route::get('/livestock/{id}', [App\Http\Controllers\LivestockController::class, 'show'])->name('livestock.show');
        Route::get('/livestock/{id}/edit', [App\Http\Controllers\LivestockController::class, 'edit'])->name('livestock.edit');
        Route::put('/livestock/{id}', [App\Http\Controllers\LivestockController::class, 'update'])->name('livestock.update');
        Route::delete('/livestock/{id}', [App\Http\Controllers\LivestockController::class, 'destroy'])->name('livestock.destroy');
        Route::post('/livestock/{id}/status', [App\Http\Controllers\LivestockController::class, 'updateStatus'])->name('livestock.update-status');
        // Lightweight livestock search for sire/dam dropdowns
        Route::get('/livestock/search', [App\Http\Controllers\LivestockController::class, 'search'])->name('livestock.search');
        
        // New livestock management routes
        Route::get('/livestock/{id}/details', [App\Http\Controllers\LivestockController::class, 'details'])->name('livestock.details');
        Route::get('/livestock/{id}/production-records', [App\Http\Controllers\LivestockController::class, 'getLivestockProductionRecords'])->name('livestock.production-records');
        Route::get('/livestock/{id}/health-records', [App\Http\Controllers\LivestockController::class, 'getLivestockHealthRecords'])->name('livestock.health-records');
        Route::get('/livestock/{id}/breeding-records', [App\Http\Controllers\LivestockController::class, 'getLivestockBreedingRecords'])->name('livestock.breeding-records');
        Route::get('/livestock/{id}/growth-records', [App\Http\Controllers\LivestockController::class, 'getLivestockGrowthRecords'])->name('livestock.growth-records');
        Route::get('/livestock/{id}/qr-code', [App\Http\Controllers\LivestockController::class, 'generateQRCode'])->name('livestock.qr-code');
        Route::post('/livestock/issue-alert', [App\Http\Controllers\LivestockController::class, 'issueAlert'])->name('livestock.issue-alert');
        // Admin add record routes (per-livestock)
        Route::post('/livestock/{id}/production', [App\Http\Controllers\LivestockController::class, 'storeLivestockProductionRecord'])->name('livestock.production.store');
        Route::post('/livestock/{id}/health', [App\Http\Controllers\LivestockController::class, 'storeLivestockHealthRecord'])->name('livestock.health.store');
        Route::post('/livestock/{id}/breeding', [App\Http\Controllers\LivestockController::class, 'storeLivestockBreedingRecord'])->name('livestock.breeding.store');
        Route::post('/livestock/{id}/growth', [App\Http\Controllers\LivestockController::class, 'storeLivestockGrowthRecord'])->name('livestock.growth.store');
        // Veterinarian list for dropdowns
        Route::get('/veterinarians', [App\Http\Controllers\AdminController::class, 'listVeterinarians'])->name('veterinarians.list');
        
        // Inspection management routes
        Route::post('/inspections/schedule', [App\Http\Controllers\AdminController::class, 'scheduleInspection'])->name('inspections.schedule');
        Route::get('/inspections/list', [App\Http\Controllers\AdminController::class, 'getInspectionsList'])->name('inspections.list');
        Route::get('/inspections/{id}', [App\Http\Controllers\AdminController::class, 'showInspection'])->name('inspections.show');
        Route::put('/inspections/{id}', [App\Http\Controllers\AdminController::class, 'updateInspection'])->name('inspections.update');
        Route::post('/inspections/{id}/cancel', [App\Http\Controllers\AdminController::class, 'cancelInspection'])->name('inspections.cancel');
        Route::get('/inspections/stats', [App\Http\Controllers\AdminController::class, 'getInspectionStats'])->name('inspections.stats');
        Route::get('/inspections/farmer/{id}', [App\Http\Controllers\AdminController::class, 'getFarmerInspections'])->name('inspections.farmer');
        
        // Schedule Inspections page
        Route::get('/schedule-inspections', [App\Http\Controllers\AdminController::class, 'scheduleInspectionsPage'])->name('schedule-inspections');
        

        
        // Production routes disabled (feature not in current plan)
        // Sales routes disabled (feature not in current plan)
        // Expenses page route disabled (feature not in current plan)
        
        // Livestock trends API for dashboard chart
        Route::get('/livestock-trends', [App\Http\Controllers\AdminController::class, 'getLivestockTrends'])->name('livestock-trends');
        // Analysis chart data endpoints (admin)
        Route::get('/analysis/production-trend', [App\Http\Controllers\AdminController::class, 'getProductionTrendData'])->name('analysis.production-trend');
        Route::get('/analysis/region-distribution', [App\Http\Controllers\AdminController::class, 'getRegionDistributionData'])->name('analysis.region-distribution');
        Route::get('/analysis/regional-performance', [App\Http\Controllers\AdminController::class, 'getRegionalPerformanceData'])->name('analysis.regional-performance');
        Route::get('/analysis/growth-trends', [App\Http\Controllers\AdminController::class, 'getGrowthTrendsData'])->name('analysis.growth-trends');
        // Livestock analysis chart data
        Route::get('/livestock-productivity-trends', [App\Http\Controllers\AdminController::class, 'getLivestockProductivityTrends'])->name('livestock.productivity-trends');
        Route::get('/livestock/{id}/analysis-data', [App\Http\Controllers\AdminController::class, 'getLivestockAnalysisData'])->name('livestock.analysis-data');
        
        // Additional admin routes for missing functionality
        Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit-logs');
        Route::get('/audit-logs/{id}/details', [AdminController::class, 'getAuditLogDetails'])->name('audit-logs.details');
        Route::get('/audit-logs/export', [AdminController::class, 'exportAuditLogs'])->name('audit-logs.export');
        
        // Task management routes for admin dashboard
        Route::get('/tasks', [App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index');
        Route::post('/tasks', [App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
        Route::get('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'show'])->name('tasks.show');
        Route::put('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');
        
        // Notification routes for admin
        Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'getNotifications'])->name('notifications');
        Route::post('/notifications/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    });
    
    // Super Admin routes
    Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'superAdminDashboard'])->name('dashboard');
        // Livestock trends API for dashboard chart
        Route::get('/livestock-trends', [SuperAdminController::class, 'getLivestockTrends'])->name('livestock-trends');
        Route::get('/profile', function () { return view('superadmin.profile'); })->name('profile');
        Route::put('/profile', [SuperAdminController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [SuperAdminController::class, 'changePassword'])->name('profile.password');
        Route::post('/profile/picture', [SuperAdminController::class, 'uploadProfilePicture'])->name('profile.picture');
        Route::get('/profile/picture/current', [SuperAdminController::class, 'getCurrentProfilePicture'])->name('profile.picture.current');
        Route::get('/users', function () { return view('superadmin.users'); })->name('users');
        Route::get('/admins', function () { return view('superadmin.admins'); })->name('admins');
        // Route::get('/farms', function () { return view('superadmin.farms'); })->name('farms');
        // Audit logs routes for super admin (can see all logs)
        Route::get('/audit-logs', [SuperAdminController::class, 'auditLogs'])->name('audit-logs');
        Route::get('/audit-logs/{id}/details', [SuperAdminController::class, 'getAuditLogDetails'])->name('audit-logs.details');
        Route::get('/audit-logs/export', [SuperAdminController::class, 'exportAuditLogs'])->name('audit-logs.export');
        Route::post('/audit-logs/clear', [SuperAdminController::class, 'clearOldLogs'])->name('audit-logs.clear');
        Route::get('/audit-logs/chart-data', [SuperAdminController::class, 'getAuditLogChartData'])->name('audit-logs.chart-data');

        // Superadmin maintenance hard reset: truncate all non-user data, keep superadmin account(s)
        Route::match(['get', 'post'], '/maintenance/hard-reset', function (Request $request) {
            $user = auth()->user();
            if (!$user || $user->role !== 'superadmin') {
                abort(403);
            }

            // Determine keep mode: 'role' keeps all users with role=superadmin, 'current' keeps only the invoker
            $keepMode = $request->get('keep', 'role'); // role|current
            $confirm = $request->get('confirm'); // must be 'YES' to execute

            // Collect table names dynamically (MySQL)
            $rows = DB::select("SELECT table_name AS name FROM information_schema.tables WHERE table_schema = DATABASE()");
            $tables = array_map(fn($r) => $r->name, $rows);
            $exclude = ['migrations', 'users'];
            $toTruncate = array_values(array_diff($tables, $exclude));

            if ($confirm !== 'YES') {
                return response()->json([
                    'success' => false,
                    'message' => 'Dry run. To execute, call this endpoint with confirm=YES',
                    'keep_mode' => $keepMode,
                    'will_truncate' => $toTruncate,
                    'note' => 'This will truncate all listed tables and remove non-superadmin users.'
                ]);
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            foreach ($toTruncate as $table) {
                try { DB::table($table)->truncate(); } catch (\Exception $e) { /* ignore */ }
            }

            // Delete users except superadmin(s) or except current user based on keep mode
            if ($keepMode === 'current') {
                DB::table('users')->where('id', '!=', $user->id)->delete();
                $keptUsers = [$user->id];
            } else {
                DB::table('users')->where('role', '!=', 'superadmin')->delete();
                $keptUsers = DB::table('users')->where('role', 'superadmin')->pluck('id');
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return response()->json([
                'success' => true,
                'message' => 'Database cleared. Only superadmin account(s) retained.',
                'truncated' => $toTruncate,
                'kept_user_ids' => $keptUsers,
                'keep_mode' => $keepMode,
            ]);
        })->name('maintenance.hard-reset');
        Route::get('/system-overview', [SuperAdminController::class, 'getSystemOverview'])->name('system-overview');
        Route::get('/system-settings', function () { return view('superadmin.settings'); })->name('settings');
        
        // Notification routes
        Route::get('/notifications', [SuperAdminController::class, 'getNotifications'])->name('notifications');
        Route::post('/notifications/{id}/mark-read', [SuperAdminController::class, 'markNotificationAsRead'])->name('notifications.mark-read');
        Route::post('/notifications/mark-all-read', [SuperAdminController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-read');
        
        // Additional routes for superadmin functionality
        Route::get('/users/index', function () { return view('superadmin.users'); })->name('users.index');
        Route::get('/users/list', [SuperAdminController::class, 'getUsersList'])->name('users.list');
        Route::get('/users/stats', [SuperAdminController::class, 'getUserStats'])->name('users.stats');
        Route::get('/users/{id}', [SuperAdminController::class, 'showUser'])->name('users.show');
        Route::post('/users', [SuperAdminController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{id}', [SuperAdminController::class, 'updateUser'])->name('users.update');
        Route::patch('/users/{id}/toggle-status', [SuperAdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
        Route::delete('/users/{id}', [SuperAdminController::class, 'destroyUser'])->name('users.destroy');
        
        Route::get('/admins/pending', [SuperAdminController::class, 'getPendingAdmins'])->name('admins.pending');
        Route::get('/admins/active', [SuperAdminController::class, 'getActiveAdmins'])->name('admins.active');
        Route::get('/admins/stats', [SuperAdminController::class, 'getAdminStats'])->name('admins.stats');
        Route::get('/admins/{id}', [SuperAdminController::class, 'showAdmin'])->name('admins.show');
        Route::post('/admins', [SuperAdminController::class, 'storeAdmin'])->name('admins.store');
        Route::put('/admins/{id}', [SuperAdminController::class, 'updateAdmin'])->name('admins.update');
        Route::delete('/admins/{id}', [SuperAdminController::class, 'destroyAdmin'])->name('admins.destroy');
        Route::post('/admins/{id}/approve', [SuperAdminController::class, 'approveAdmin'])->name('admins.approve');
        Route::post('/admins/{id}/reject', [SuperAdminController::class, 'rejectAdmin'])->name('admins.reject');
        Route::post('/admins/{id}/deactivate', [SuperAdminController::class, 'deactivateAdmin'])->name('admins.deactivate');
        Route::post('/admins/contact', [SuperAdminController::class, 'contactAdmin'])->name('admins.contact');
        
        Route::get('/farms/index', [function () { return view('superadmin.farms'); }])->name('farms.index');
        Route::get('/farms/list', [SuperAdminController::class, 'getFarmsList'])->name('farms.list');
        Route::get('/farms/stats', [SuperAdminController::class, 'getFarmStats'])->name('farms.stats');
        Route::get('/farms/{id}', [SuperAdminController::class, 'showFarm'])->name('farms.show');
        Route::post('/farms', [SuperAdminController::class, 'storeFarm'])->name('farms.store');
        Route::put('/farms/{id}', [SuperAdminController::class, 'updateFarm'])->name('farms.update');
        Route::post('/farms/{id}/update-status', [SuperAdminController::class, 'updateFarmStatus'])->name('farms.update-status');
        Route::delete('/farms/{id}', [SuperAdminController::class, 'destroyFarm'])->name('farms.destroy');
        Route::post('/farms/import', [SuperAdminController::class, 'importFarms'])->name('farms.import');
        
        // Additional superadmin routes to match static website
        Route::get('/manage-farmers', [SuperAdminController::class, 'manageFarmers'])->name('manage-farmers');
Route::get('/farmers/{id}/details', [SuperAdminController::class, 'getFarmerDetails'])->name('farmers.details');
Route::put('/farmers/{id}/update', [SuperAdminController::class, 'updateFarmer'])->name('farmers.update');
Route::delete('/farmers/{id}', [SuperAdminController::class, 'deleteFarmer'])->name('farmers.destroy');
Route::post('/farmers', [SuperAdminController::class, 'storeFarmer'])->name('farmers.store');
        Route::get('/manage-analysis', function () { return view('superadmin.manage-analysis'); })->name('manage-analysis');
        // Analysis data endpoints
        Route::get('/analysis/summary', [SuperAdminController::class, 'getAnalysisSummary'])->name('analysis.summary');
        Route::get('/analysis/farm-performance', [SuperAdminController::class, 'getFarmPerformanceData'])->name('analysis.farm-performance');
        Route::get('/analysis/livestock-distribution', [SuperAdminController::class, 'getLivestockDistributionData'])->name('analysis.livestock-distribution');
        Route::get('/analysis/production-trends', [SuperAdminController::class, 'getProductionTrendsData'])->name('analysis.production-trends');
        Route::get('/analysis/top-performing-farms', [SuperAdminController::class, 'getTopPerformingFarms'])->name('analysis.top-performing-farms');
        
        Route::get('/settings/get', [SuperAdminController::class, 'getSettings'])->name('settings.get');
        Route::post('/settings/general', [SuperAdminController::class, 'updateGeneralSettings'])->name('settings.general');
        Route::post('/settings/security', [SuperAdminController::class, 'updateSecuritySettings'])->name('settings.security');
        Route::post('/settings/notifications', [SuperAdminController::class, 'updateNotificationSettings'])->name('settings.notifications');
        Route::post('/settings/test-email', [SuperAdminController::class, 'testEmail'])->name('settings.test-email');
        Route::post('/settings/create-backup', [SuperAdminController::class, 'createBackup'])->name('settings.create-backup');
        Route::post('/settings/clear-cache', [SuperAdminController::class, 'clearCache'])->name('settings.clear-cache');
        Route::post('/settings/optimize-database', [SuperAdminController::class, 'optimizeDatabase'])->name('settings.optimize-database');
        Route::get('/settings/logs', [SuperAdminController::class, 'getSettingsLogs'])->name('settings.logs');
        Route::post('/settings/clear-logs', [SuperAdminController::class, 'clearSettingsLogs'])->name('settings.clear-logs');
        Route::get('/settings/export-logs', [SuperAdminController::class, 'exportSettingsLogs'])->name('settings.export-logs');
        
        // Notification routes
        Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'getNotifications'])->name('notifications');
        Route::get('/notifications/user-stats', [App\Http\Controllers\NotificationController::class, 'getUserStats'])->name('notifications.user-stats');
        Route::post('/notifications/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

        // Task board routes
                Route::get('/tasks', [App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index');
        Route::post('/tasks', [App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
        Route::get('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'show'])->name('tasks.show');
        Route::put('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');
        Route::post('/tasks/reorder', [App\Http\Controllers\TaskController::class, 'reorder'])->name('tasks.reorder');
        Route::post('/tasks/{task}/move', [App\Http\Controllers\TaskController::class, 'move'])->name('tasks.move');

// Calendar routes
Route::get('/calendar/events', [App\Http\Controllers\TaskController::class, 'calendarEvents'])->name('calendar.events');
Route::post('/calendar/events', [App\Http\Controllers\TaskController::class, 'storeCalendarEvent'])->name('calendar.store');
Route::put('/calendar/events/{task}', [App\Http\Controllers\TaskController::class, 'updateCalendarEvent'])->name('calendar.update');
    });
});
