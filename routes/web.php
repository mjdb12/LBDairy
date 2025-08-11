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
Route::get('/', function () {
    return redirect('/login');
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
        Route::get('/farms', function () { return view('farmer.farms'); })->name('farms');
        Route::get('/livestock', function () { return view('farmer.livestock'); })->name('livestock');
        Route::get('/production', function () { return view('farmer.production'); })->name('production');
        Route::get('/users', function () { return view('farmer.users'); })->name('users');
        Route::get('/suppliers', function () { return view('farmer.suppliers'); })->name('suppliers');
        Route::get('/schedule', function () { return view('farmer.schedule'); })->name('schedule');
        Route::get('/scan', function () { return view('farmer.scan'); })->name('scan');
        Route::get('/sales', function () { return view('farmer.sales'); })->name('sales');
        Route::get('/expenses', function () { return view('farmer.expenses'); })->name('expenses');
        Route::get('/issues', function () { return view('farmer.issues'); })->name('issues');
    });
    
    // Admin routes
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        Route::get('/profile', function () { return view('admin.profile'); })->name('profile');
        Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [AdminController::class, 'changePassword'])->name('profile.password');
        Route::get('/farms', function () { return view('admin.farms'); })->name('farms');
        Route::get('/manage-farmers', function () { return view('admin.manage-farmers'); })->name('manage-farmers');
        Route::get('/farmers', function () { return view('admin.farmers'); })->name('farmers');
        
        // Livestock management routes
        Route::get('/manage-livestock', [App\Http\Controllers\LivestockController::class, 'index'])->name('livestock.index');
        Route::get('/livestock/create', [App\Http\Controllers\LivestockController::class, 'create'])->name('livestock.create');
        Route::post('/livestock', [App\Http\Controllers\LivestockController::class, 'store'])->name('livestock.store');
        Route::get('/livestock/{id}', [App\Http\Controllers\LivestockController::class, 'show'])->name('livestock.show');
        Route::get('/livestock/{id}/edit', [App\Http\Controllers\LivestockController::class, 'edit'])->name('livestock.edit');
        Route::put('/livestock/{id}', [App\Http\Controllers\LivestockController::class, 'update'])->name('livestock.update');
        Route::delete('/livestock/{id}', [App\Http\Controllers\LivestockController::class, 'destroy'])->name('livestock.destroy');
        Route::post('/livestock/{id}/status', [App\Http\Controllers\LivestockController::class, 'updateStatus'])->name('livestock.update-status');
        Route::get('/livestock-stats', [App\Http\Controllers\LivestockController::class, 'getStats'])->name('livestock.stats');
        Route::get('/livestock-export', [App\Http\Controllers\LivestockController::class, 'export'])->name('livestock.export');
        
        // Issue management routes
        Route::get('/manage-issues', [App\Http\Controllers\IssueController::class, 'index'])->name('issues.index');
        Route::get('/issues/create', [App\Http\Controllers\IssueController::class, 'create'])->name('issues.create');
        Route::post('/issues', [App\Http\Controllers\IssueController::class, 'store'])->name('issues.store');
        Route::get('/issues/{id}', [App\Http\Controllers\IssueController::class, 'show'])->name('issues.show');
        Route::get('/issues/{id}/edit', [App\Http\Controllers\IssueController::class, 'edit'])->name('issues.edit');
        Route::put('/issues/{id}', [App\Http\Controllers\IssueController::class, 'update'])->name('issues.update');
        Route::delete('/issues/{id}', [App\Http\Controllers\IssueController::class, 'destroy'])->name('issues.destroy');
        Route::post('/issues/{id}/status', [App\Http\Controllers\IssueController::class, 'updateStatus'])->name('issues.update-status');
        Route::get('/issues-stats', [App\Http\Controllers\IssueController::class, 'getStats'])->name('issues.stats');
        Route::get('/issues-export', [App\Http\Controllers\IssueController::class, 'export'])->name('issues.export');
        Route::get('/urgent-issues', [App\Http\Controllers\IssueController::class, 'getUrgentIssues'])->name('issues.urgent');
        
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
        
        Route::get('/production', [App\Http\Controllers\AdminController::class, 'production'])->name('production');
        Route::get('/sales', [App\Http\Controllers\AdminController::class, 'sales'])->name('sales');
        Route::post('/sales', [App\Http\Controllers\AdminController::class, 'storeSale'])->name('sales.store');
        Route::delete('/sales/{id}', [App\Http\Controllers\AdminController::class, 'deleteSale'])->name('sales.destroy');
        Route::post('/sales/import', [App\Http\Controllers\AdminController::class, 'importSales'])->name('sales.import');
        Route::get('/expenses', [App\Http\Controllers\AdminController::class, 'manageExpenses'])->name('expenses');
        Route::get('/issues', function () { return view('admin.issues'); })->name('issues');
        Route::get('/analysis', [App\Http\Controllers\AdminController::class, 'analysis'])->name('analysis');
    });
    
    // Super Admin routes
    Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'superAdminDashboard'])->name('dashboard');
        Route::get('/profile', function () { return view('superadmin.profile'); })->name('profile');
        Route::put('/profile', [SuperAdminController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [SuperAdminController::class, 'changePassword'])->name('profile.password');
        Route::get('/users', function () { return view('superadmin.users'); })->name('users');
        Route::get('/admins', function () { return view('superadmin.admins'); })->name('admins');
        Route::get('/farms', function () { return view('superadmin.farms'); })->name('farms');
        Route::get('/audit-logs', [SuperAdminController::class, 'auditLogs'])->name('audit-logs');
        Route::get('/audit-logs/{id}/details', [SuperAdminController::class, 'getAuditLogDetails'])->name('audit-logs.details');
        Route::get('/audit-logs/export', [SuperAdminController::class, 'exportAuditLogs'])->name('audit-logs.export');
        Route::post('/audit-logs/clear', [SuperAdminController::class, 'clearOldLogs'])->name('audit-logs.clear');
        Route::get('/system-overview', [SuperAdminController::class, 'getSystemOverview'])->name('system-overview');
        Route::get('/system-settings', function () { return view('superadmin.settings'); })->name('settings');
    });
});
