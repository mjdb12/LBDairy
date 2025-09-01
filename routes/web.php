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

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Main dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
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
        Route::get('/livestock/{id}/edit', [FarmerController::class, 'editLivestock'])->name('livestock.edit');
        Route::put('/livestock/{id}', [FarmerController::class, 'updateLivestock'])->name('livestock.update');
        Route::delete('/livestock/{id}', [FarmerController::class, 'deleteLivestock'])->name('livestock.destroy');
        Route::post('/livestock/{id}/status', [FarmerController::class, 'updateLivestockStatus'])->name('livestock.update-status');
        Route::get('/production', [FarmerController::class, 'production'])->name('production');
        Route::post('/production', [FarmerController::class, 'storeProduction'])->name('production.store');
        Route::get('/production/{id}', [FarmerController::class, 'showProduction'])->name('production.show');
        Route::put('/production/{id}', [FarmerController::class, 'updateProduction'])->name('production.update');
        Route::delete('/production/{id}', [FarmerController::class, 'deleteProduction'])->name('production.destroy');
        Route::get('/production/history', [FarmerController::class, 'productionHistory'])->name('production.history');
        Route::get('/users', function () { return view('farmer.users'); })->name('users');
        Route::get('/suppliers', [App\Http\Controllers\FarmerController::class, 'suppliers'])->name('suppliers');
        Route::get('/schedule', function () { return view('farmer.schedule'); })->name('schedule');
        Route::get('/scan', function () { return view('farmer.scan'); })->name('scan');
        Route::get('/farms', [FarmerController::class, 'farms'])->name('farms');
        Route::post('/farms', [FarmerController::class, 'storeFarm'])->name('farms.store');
        Route::get('/farm-details/{id}', [FarmerController::class, 'farmDetails'])->name('farm-details');
        Route::get('/sales', [App\Http\Controllers\FarmerController::class, 'sales'])->name('sales');
        Route::post('/sales', [App\Http\Controllers\FarmerController::class, 'storeSale'])->name('sales.store');
        Route::delete('/sales/{id}', [App\Http\Controllers\FarmerController::class, 'deleteSale'])->name('sales.destroy');
        Route::get('/expenses', [FarmerController::class, 'expenses'])->name('expenses');
        Route::post('/expenses', [FarmerController::class, 'storeExpense'])->name('expenses.store');
        Route::get('/expenses/{id}', [FarmerController::class, 'showExpense'])->name('expenses.show');
        Route::put('/expenses/{id}', [FarmerController::class, 'updateExpense'])->name('expenses.update');
        Route::delete('/expenses/{id}', [FarmerController::class, 'deleteExpense'])->name('expenses.destroy');
        Route::get('/issues', [FarmerController::class, 'issues'])->name('issues');
        Route::get('/issues/{id}', [FarmerController::class, 'showIssue'])->name('issues.show');
        Route::get('/issue-alerts', [FarmerController::class, 'issueAlerts'])->name('issue-alerts');
        Route::post('/issue-alerts', [FarmerController::class, 'storeAlert'])->name('issue-alerts.store');
        Route::get('/issue-alerts/{id}', [FarmerController::class, 'showAlert'])->name('issue-alerts.show');
        Route::patch('/issue-alerts/{id}/status', [FarmerController::class, 'updateAlertStatus'])->name('issue-alerts.update-status');
        Route::get('/farm-analysis', [App\Http\Controllers\FarmerController::class, 'farmAnalysis'])->name('farm-analysis');
        Route::get('/livestock-analysis', [App\Http\Controllers\FarmerController::class, 'livestockAnalysis'])->name('livestock-analysis');
        Route::get('/livestock/{id}/analysis', [App\Http\Controllers\FarmerController::class, 'getLivestockAnalysis'])->name('livestock.analysis');
        Route::get('/livestock/{id}/history', [App\Http\Controllers\FarmerController::class, 'getLivestockHistory'])->name('livestock.history');
        Route::get('/clients', [App\Http\Controllers\FarmerController::class, 'clients'])->name('clients');
        Route::get('/inventory', [App\Http\Controllers\FarmerController::class, 'inventory'])->name('inventory');
        
        // Audit logs routes for farmer
        Route::get('/audit-logs', [FarmerController::class, 'auditLogs'])->name('audit-logs');
        Route::get('/audit-logs/{id}/details', [FarmerController::class, 'getAuditLogDetails'])->name('audit-logs.details');
        Route::get('/audit-logs/export', [FarmerController::class, 'exportAuditLogs'])->name('audit-logs.export');
        
        // Inspection routes for farmer
        Route::get('/inspections/{id}', [FarmerController::class, 'showInspection'])->name('inspections.show');
        Route::post('/inspections/{id}/complete', [FarmerController::class, 'completeInspection'])->name('inspections.complete');
        
        // Task routes for farmer
        Route::get('/tasks', [App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'show'])->name('tasks.show');
        Route::post('/tasks', [App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
        Route::put('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');
    });
    
    // Admin routes
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        Route::get('/profile', function () { return view('admin.profile'); })->name('profile');
        Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [AdminController::class, 'changePassword'])->name('profile.password');
        Route::post('/profile/picture', [AdminController::class, 'uploadProfilePicture'])->name('profile.picture');
        Route::get('/profile/picture/current', [AdminController::class, 'getCurrentProfilePicture'])->name('profile.picture.current');
        Route::get('/farms', function () { return view('admin.farms'); })->name('farms');
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
        
        // Analysis routes
        Route::get('/manage-analysis', [App\Http\Controllers\AnalysisController::class, 'index'])->name('analysis.index');
        Route::get('/analysis/farmer/{id}/data', [App\Http\Controllers\AnalysisController::class, 'getFarmerData'])->name('analysis.farmer-data');
        Route::get('/analysis/farmer/{id}/details', [App\Http\Controllers\AnalysisController::class, 'getFarmerDetails'])->name('analysis.farmer-details');
        Route::post('/analysis/farmer/{id}/status', [App\Http\Controllers\AnalysisController::class, 'updateStatus'])->name('analysis.update-status');
        Route::delete('/analysis/farmer/{id}', [App\Http\Controllers\AnalysisController::class, 'deleteFarmer'])->name('analysis.delete-farmer');
        Route::get('/analysis/export', [App\Http\Controllers\AnalysisController::class, 'export'])->name('analysis.export');
        
        // Farm analysis routes
        Route::get('/farm-analysis', [App\Http\Controllers\AdminController::class, 'farmAnalysis'])->name('farm-analysis');
        
        // Livestock analysis routes
        Route::get('/livestock-analysis', [App\Http\Controllers\AdminController::class, 'livestockAnalysis'])->name('livestock-analysis');
        
        // Admin management routes
        Route::get('/manage-admins', [App\Http\Controllers\AdminController::class, 'manageAdmins'])->name('manage-admins');
        Route::post('/admins/{id}/status', [App\Http\Controllers\AdminController::class, 'updateAdminStatus'])->name('admins.update-status');
        Route::post('/admins/{id}/reset-password', [App\Http\Controllers\AdminController::class, 'resetAdminPassword'])->name('admins.reset-password');
        Route::delete('/admins/{id}', [App\Http\Controllers\AdminController::class, 'deleteAdmin'])->name('admins.delete');
        
        // Client management routes
        Route::get('/clients', [App\Http\Controllers\AdminController::class, 'manageClients'])->name('clients');
        
        // Inventory management routes
        Route::get('/inventory', [App\Http\Controllers\AdminController::class, 'manageInventory'])->name('inventory');
        
        // Expense management routes
        Route::get('/expenses', [App\Http\Controllers\AdminController::class, 'manageExpenses'])->name('expenses');
        
        // Farm management routes
        Route::get('/manage-farms', [App\Http\Controllers\FarmController::class, 'index'])->name('farms.index');
        Route::get('/farms/{id}', [App\Http\Controllers\FarmController::class, 'show'])->name('farms.show');
        Route::post('/farms/{id}/status', [App\Http\Controllers\FarmController::class, 'updateStatus'])->name('farms.update-status');
        Route::delete('/farms/{id}', [App\Http\Controllers\FarmController::class, 'destroy'])->name('farms.destroy');
        Route::post('/farms/import', [App\Http\Controllers\FarmController::class, 'import'])->name('farms.import');
        Route::get('/farms/export', [App\Http\Controllers\FarmController::class, 'export'])->name('farms.export');
        
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
        
        // New livestock management routes
        Route::get('/livestock/{id}/details', [App\Http\Controllers\LivestockController::class, 'details'])->name('livestock.details');
        Route::get('/livestock/{id}/qr-code', [App\Http\Controllers\LivestockController::class, 'generateQRCode'])->name('livestock.qr-code');
        Route::post('/livestock/issue-alert', [App\Http\Controllers\LivestockController::class, 'issueAlert'])->name('livestock.issue-alert');
        
        // Inspection management routes
        Route::post('/inspections/schedule', [App\Http\Controllers\AdminController::class, 'scheduleInspection'])->name('inspections.schedule');
        Route::get('/inspections/list', [App\Http\Controllers\AdminController::class, 'getInspectionsList'])->name('inspections.list');
        Route::get('/inspections/{id}', [App\Http\Controllers\AdminController::class, 'showInspection'])->name('inspections.show');
        Route::post('/inspections/{id}/cancel', [App\Http\Controllers\AdminController::class, 'cancelInspection'])->name('inspections.cancel');
        Route::get('/inspections/stats', [App\Http\Controllers\AdminController::class, 'getInspectionStats'])->name('inspections.stats');
        
        // Schedule Inspections page
        Route::get('/schedule-inspections', [App\Http\Controllers\AdminController::class, 'scheduleInspectionsPage'])->name('schedule-inspections');
        

        
        Route::get('/production', [App\Http\Controllers\AdminController::class, 'production'])->name('production');
        Route::get('/sales', [App\Http\Controllers\AdminController::class, 'sales'])->name('sales');
        Route::post('/sales', [App\Http\Controllers\AdminController::class, 'storeSale'])->name('sales.store');
        Route::delete('/sales/{id}', [App\Http\Controllers\AdminController::class, 'deleteSale'])->name('sales.destroy');
        Route::post('/sales/import', [App\Http\Controllers\AdminController::class, 'importSales'])->name('sales.import');
        Route::get('/expenses', [App\Http\Controllers\AdminController::class, 'manageExpenses'])->name('expenses');
        Route::get('/issues', function () { return view('admin.issues'); })->name('issues');
        Route::get('/analysis', [App\Http\Controllers\AdminController::class, 'analysis'])->name('analysis');
        
        // Livestock trends API for dashboard chart
        Route::get('/livestock-trends', [App\Http\Controllers\AdminController::class, 'getLivestockTrends'])->name('livestock-trends');
        
        // Additional admin routes for missing functionality
        Route::get('/clients', function () { return view('admin.clients'); })->name('clients');
        Route::get('/inventory', function () { return view('admin.inventory'); })->name('inventory');
        Route::get('/farm-analysis', function () { return view('admin.farm-analysis'); })->name('farm-analysis');
        Route::get('/livestock-analysis', function () { return view('admin.livestock-analysis'); })->name('livestock-analysis');
        Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit-logs');
        Route::get('/audit-logs/{id}/details', [AdminController::class, 'getAuditLogDetails'])->name('audit-logs.details');
        Route::get('/audit-logs/export', [AdminController::class, 'exportAuditLogs'])->name('audit-logs.export');
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
        Route::get('/system-overview', [SuperAdminController::class, 'getSystemOverview'])->name('system-overview');
        Route::get('/system-settings', function () { return view('superadmin.settings'); })->name('settings');
        
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
        Route::post('/farms/{id}/update-status', [SuperAdminController::class, 'updateFarmStatus'])->name('farms.update-status');
        Route::delete('/farms/{id}', [SuperAdminController::class, 'destroyFarm'])->name('farms.destroy');
        Route::post('/farms/import', [SuperAdminController::class, 'importFarms'])->name('farms.import');
        
        // Additional superadmin routes to match static website
        Route::get('/manage-farmers', function () { return view('superadmin.manage-farmers'); })->name('manage-farmers');
        Route::post('/farmers', [SuperAdminController::class, 'storeFarmer'])->name('farmers.store');
        Route::get('/manage-analysis', function () { return view('superadmin.manage-analysis'); })->name('manage-analysis');
        // Analysis data endpoints
        Route::get('/analysis/summary', [SuperAdminController::class, 'getAnalysisSummary'])->name('analysis.summary');
        Route::get('/analysis/farm-performance', [SuperAdminController::class, 'getFarmPerformanceData'])->name('analysis.farm-performance');
        Route::get('/analysis/livestock-distribution', [SuperAdminController::class, 'getLivestockDistributionData'])->name('analysis.livestock-distribution');
        Route::get('/analysis/production-trends', [SuperAdminController::class, 'getProductionTrendsData'])->name('analysis.production-trends');
        
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
