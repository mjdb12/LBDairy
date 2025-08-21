<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\Livestock;
use App\Models\Farm;
use App\Models\Issue;

class FarmerController extends Controller
{
    /**
     * Update the farmer's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'farm_name' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Update farm name if provided
        if ($request->farm_name && $user->farms->first()) {
            $user->farms->first()->update(['name' => $request->farm_name]);
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Change the farmer's password.
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
     * Display the farmer's issues.
     */
    public function issues()
    {
        $user = Auth::user();
        
        // Get issues for the farmer's farms
        $issues = Issue::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->orderBy('date_reported', 'desc')->get();

        // Get livestock for the farmer's farms
        $livestock = Livestock::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->get();

        // Get farms for the farmer
        $farms = Farm::where('owner_id', $user->id)->get();

        $totalIssues = $issues->count();
        $pendingIssues = $issues->where('status', 'Pending')->count();
        $urgentIssues = $issues->where('priority', 'High')->where('status', '!=', 'Resolved')->count();
        $resolvedIssues = $issues->where('status', 'Resolved')->count();

        // Ensure we always have valid numbers
        $totalIssues = $totalIssues ?: 0;
        $pendingIssues = $pendingIssues ?: 0;
        $urgentIssues = $urgentIssues ?: 0;
        $resolvedIssues = $resolvedIssues ?: 0;

        return view('farmer.issues', compact(
            'issues',
            'livestock',
            'farms',
            'totalIssues',
            'pendingIssues',
            'urgentIssues',
            'resolvedIssues'
        ));
    }

    /**
     * Store a newly created issue.
     */
    public function storeIssue(Request $request)
    {
        $request->validate([
            'issue_type' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'livestock_id' => 'required|exists:livestock,id',
        ]);

        $user = Auth::user();
        
        // Get the livestock and verify it belongs to the user's farm
        $livestock = Livestock::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($request->livestock_id);

        // Map the form fields to database fields
        $priority = $request->priority;
        $issueType = $request->issue_type;

        $issue = Issue::create([
            'livestock_id' => $request->livestock_id,
            'farm_id' => $livestock->farm_id,
            'issue_type' => $issueType,
            'description' => $request->description,
            'priority' => $priority,
            'status' => 'Pending',
            'date_reported' => $request->reported_date ?? now(),
            'reported_by' => $user->id,
        ]);

        return redirect()->route('farmer.issues')->with('success', 'Issue reported successfully!');
    }

    /**
     * Map issue type from form to database category.
     */
    private function mapIssueTypeToCategory($issueType)
    {
        $mapping = [
            'Health' => 'health',
            'Injury' => 'health',
            'Production' => 'management',
            'Behavioral' => 'management',
            'Environmental' => 'other',
            'Other' => 'other',
        ];

        return $mapping[$issueType] ?? 'other';
    }

    /**
     * Display the specified issue.
     */
    public function showIssue($id)
    {
        $user = Auth::user();
        $issue = Issue::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        return response()->json(['success' => true, 'issue' => $issue]);
    }

    /**
     * Show the form for editing the specified issue.
     */
    public function editIssue($id)
    {
        $user = Auth::user();
        $issue = Issue::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        return response()->json(['success' => true, 'issue' => $issue]);
    }

    /**
     * Update the specified issue.
     */
    public function updateIssue(Request $request, $id)
    {
        $request->validate([
            'issue_type' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'status' => 'required|in:Pending,In Progress,Resolved,Closed',
        ]);

        $user = Auth::user();
        $issue = Issue::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        $issue->update([
            'issue_type' => $request->issue_type,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => $request->status,
        ]);

        return redirect()->route('farmer.issues')->with('success', 'Issue updated successfully!');
    }

    /**
     * Remove the specified issue.
     */
    public function deleteIssue($id)
    {
        $user = Auth::user();
        $issue = Issue::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        $issue->delete();

        return redirect()->route('farmer.issues')->with('success', 'Issue deleted successfully!');
    }

    /**
     * Display the farmer's livestock inventory.
     */
    public function livestock()
    {
        $user = Auth::user();
        
        // Get livestock for the farmer's farms
        $livestock = Livestock::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->get();

        // Get farms for the farmer
        $farms = Farm::where('owner_id', $user->id)->get();

        $totalLivestock = $livestock->count();
        $healthyLivestock = $livestock->where('health_status', 'healthy')->count();
        $attentionNeeded = $livestock->where('health_status', '!=', 'healthy')->count();
        $productionReady = $livestock->where('status', 'active')->count(); // You can adjust this logic based on your needs

        return view('farmer.livestock', compact(
            'livestock',
            'farms',
            'totalLivestock',
            'healthyLivestock',
            'attentionNeeded',
            'productionReady'
        ));
    }

    /**
     * Store a newly created livestock.
     */
    public function storeLivestock(Request $request)
    {
        $request->validate([
            'tag_number' => 'required|string|max:255|unique:livestock',
            'name' => 'required|string|max:255',
            'type' => 'required|in:cow,buffalo,goat,sheep',
            'breed' => 'required|in:holstein,jersey,guernsey,ayrshire,brown_swiss,other',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'weight' => 'nullable|numeric|min:0',
            'health_status' => 'required|in:healthy,sick,recovering,under_treatment',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $user = Auth::user();
            $farm = $user->farms->first();
            
            if (!$farm) {
                return response()->json([
                    'success' => false,
                    'message' => 'You need to have a farm to add livestock.'
                ], 400);
            }

            $livestock = Livestock::create([
                'tag_number' => $request->tag_number,
                'name' => $request->name,
                'type' => $request->type,
                'breed' => $request->breed,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'weight' => $request->weight,
                'health_status' => $request->health_status,
                'status' => $request->status,
                'farm_id' => $farm->id,
                'owner_id' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Livestock added successfully!',
                'livestock' => $livestock
            ]);
        } catch (\Exception $e) {
            \Log::error('Livestock creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add livestock. Please try again.'
            ], 500);
        }
    }

    /**
     * Display the specified livestock.
     */
    public function showLivestock($id)
    {
        $user = Auth::user();
        $livestock = Livestock::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        return response()->json([
            'success' => true,
            'livestock' => $livestock
        ]);
    }

    /**
     * Print the specified livestock record.
     */
    public function printLivestock($id)
    {
        $user = Auth::user();
        $livestock = Livestock::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        // Get related data
        $farm = $livestock->farm;
        $owner = $livestock->owner;
        
        // Get production records for this livestock
        $productionRecords = $livestock->productionRecords()
            ->orderBy('production_date', 'desc')
            ->take(15)
            ->get();
            
        // Mock data for growth records (you can replace with actual data when available)
        $growthRecords = collect([
            [
                'date' => $livestock->birth_date ? $livestock->birth_date->format('Y-m-d') : 'N/A',
                'weight' => $livestock->weight ?? 'N/A',
                'height' => 'N/A',
                'heart_girth' => 'N/A',
                'body_length' => 'N/A'
            ]
        ]);
        
        // Mock data for breeding records (you can replace with actual data when available)
        $breedingRecords = collect([]);
        
        // Mock data for calving records (you can replace with actual data when available)
        $calvingRecords = collect([]);

        return view('farmer.livestock-print', compact(
            'livestock',
            'farm',
            'owner',
            'productionRecords',
            'growthRecords',
            'breedingRecords',
            'calvingRecords'
        ));
    }

    /**
     * Show the form for editing the specified livestock.
     */
    public function editLivestock($id)
    {
        $user = Auth::user();
        $livestock = Livestock::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        return response()->json([
            'success' => true,
            'livestock' => $livestock
        ]);
    }

    /**
     * Update the specified livestock.
     */
    public function updateLivestock(Request $request, $id)
    {
        $request->validate([
            'tag_number' => 'required|string|max:255|unique:livestock,tag_number,' . $id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:cow,buffalo,goat,sheep',
            'breed' => 'required|in:holstein,jersey,guernsey,ayrshire,brown_swiss,other',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'weight' => 'nullable|numeric|min:0',
            'health_status' => 'required|in:healthy,sick,recovering,under_treatment',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $user = Auth::user();
            $livestock = Livestock::whereHas('farm', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->findOrFail($id);

            $livestock->update([
                'tag_number' => $request->tag_number,
                'name' => $request->name,
                'type' => $request->type,
                'breed' => $request->breed,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'weight' => $request->weight,
                'health_status' => $request->health_status,
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Livestock updated successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Livestock update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update livestock. Please try again.'
            ], 500);
        }
    }

    /**
     * Update the specified livestock status.
     */
    public function updateLivestockStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $user = Auth::user();
            $livestock = Livestock::whereHas('farm', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->findOrFail($id);

            $livestock->update([
                'status' => ucfirst($request->status),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Livestock status updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update livestock status. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove the specified livestock.
     */
    public function deleteLivestock($id)
    {
        try {
            $user = Auth::user();
            $livestock = Livestock::whereHas('farm', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->findOrFail($id);

            $livestock->delete();

            return response()->json([
                'success' => true,
                'message' => 'Livestock deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete livestock. Please try again.'
            ], 500);
        }
    }

    /**
     * Display the farmer's production records.
     */
    public function production()
    {
        $user = Auth::user();
        
        // Get production records for the farmer's farms
        $productionRecords = \App\Models\ProductionRecord::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->orderBy('production_date', 'desc')->get();

        // Get livestock for the farmer's farms
        $livestock = Livestock::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->get();

        return view('farmer.production', compact('productionRecords', 'livestock'));
    }

    /**
     * Store a newly created production record.
     */
    public function storeProduction(Request $request)
    {
        $request->validate([
            'production_date' => 'required|date',
            'livestock_id' => 'required|exists:livestock,id',
            'milk_quantity' => 'required|numeric|min:0',
            'milk_quality_score' => 'nullable|integer|min:1|max:10',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        
        // Get the livestock and verify it belongs to the user's farm
        $livestock = Livestock::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($request->livestock_id);

        $productionRecord = \App\Models\ProductionRecord::create([
            'farm_id' => $livestock->farm_id,
            'livestock_id' => $livestock->id,
            'production_date' => $request->production_date,
            'milk_quantity' => $request->milk_quantity,
            'milk_quality_score' => $request->milk_quality_score,
            'notes' => $request->notes,
            'recorded_by' => $user->id,
        ]);

        return redirect()->route('farmer.production')->with('success', 'Production record added successfully!');
    }

    /**
     * Display the specified production record.
     */
    public function showProduction($id)
    {
        $user = Auth::user();
        $productionRecord = \App\Models\ProductionRecord::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        return response()->json($productionRecord);
    }

    /**
     * Update the specified production record.
     */
    public function updateProduction(Request $request, $id)
    {
        $request->validate([
            'production_date' => 'required|date',
            'livestock_id' => 'required|exists:livestock,id',
            'milk_quantity' => 'required|numeric|min:0',
            'milk_quality_score' => 'nullable|integer|min:1|max:10',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $productionRecord = \App\Models\ProductionRecord::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        $productionRecord->update([
            'production_date' => $request->production_date,
            'livestock_id' => $request->livestock_id,
            'milk_quantity' => $request->milk_quantity,
            'milk_quality_score' => $request->milk_quality_score,
            'notes' => $request->notes,
        ]);

        return redirect()->route('farmer.production')->with('success', 'Production record updated successfully!');
    }

    /**
     * Remove the specified production record.
     */
    public function deleteProduction($id)
    {
        $user = Auth::user();
        $productionRecord = \App\Models\ProductionRecord::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        $productionRecord->delete();

        return redirect()->route('farmer.production')->with('success', 'Production record deleted successfully!');
    }

    /**
     * Get production history for AJAX requests.
     */
    public function productionHistory(Request $request)
    {
        $user = Auth::user();
        $sortBy = $request->get('sort', 'production_date');
        $filterBy = $request->get('filter', 'all');

        $query = \App\Models\ProductionRecord::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        });

        // Apply filters
        if ($filterBy !== 'all') {
            $query->where('milk_quality_score', '>=', $filterBy);
        }

        $records = $query->orderBy($sortBy, 'desc')->get();

        return response()->json([
            'success' => true,
            'records' => $records
        ]);
    }

    /**
     * Display the farmer's expenses.
     */
    public function expenses()
    {
        $user = Auth::user();
        
        // Get expenses for the farmer's farms
        $expenses = \App\Models\Expense::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->orderBy('expense_date', 'desc')->get();

        // Calculate total expenses
        $totalExpenses = $expenses->sum('amount');
        
        // Calculate expenses by type (mapping expense_type to category)
        $feedExpenses = $expenses->where('expense_type', 'feed')->sum('amount');
        $veterinaryExpenses = $expenses->where('expense_type', 'medicine')->sum('amount');
        $maintenanceExpenses = $expenses->where('expense_type', 'equipment')->sum('amount');
        
        // Calculate month-over-month changes (simplified calculation)
        $currentMonthExpenses = $expenses->where('expense_date', '>=', now()->startOfMonth())->sum('amount');
        $lastMonthExpenses = $expenses->where('expense_date', '>=', now()->subMonth()->startOfMonth())->where('expense_date', '<', now()->startOfMonth())->sum('amount');
        
        $expenseChange = $lastMonthExpenses > 0 ? round((($currentMonthExpenses - $lastMonthExpenses) / $lastMonthExpenses) * 100, 1) : 0;
        $feedChange = 0; // Simplified for now
        $veterinaryChange = 0; // Simplified for now
        $maintenanceChange = 0; // Simplified for now

        // Budget calculations (simplified - you can adjust these values)
        $monthlyBudget = 50000; // Example monthly budget in pesos
        $budgetExceeded = $currentMonthExpenses > $monthlyBudget;
        $budgetExcess = $budgetExceeded ? $currentMonthExpenses - $monthlyBudget : 0;

        return view('farmer.expenses', compact(
            'expenses',
            'totalExpenses',
            'feedExpenses',
            'veterinaryExpenses',
            'maintenanceExpenses',
            'expenseChange',
            'feedChange',
            'veterinaryChange',
            'maintenanceChange',
            'budgetExceeded',
            'budgetExcess'
        ));
    }

    /**
     * Store a newly created expense.
     */
    public function storeExpense(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_type' => 'required|in:feed,medicine,equipment,labor,other',
            'expense_date' => 'required|date',
            'farm_id' => 'required|exists:farms,id',
            'payment_method' => 'nullable|string',
            'receipt_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        
        // Verify the farm belongs to the user
        $farm = Farm::where('id', $request->farm_id)
                   ->where('owner_id', $user->id)
                   ->firstOrFail();

        $expense = \App\Models\Expense::create([
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_type' => $request->expense_type,
            'expense_date' => $request->expense_date,
            'farm_id' => $farm->id,
            'payment_method' => $request->payment_method,
            'receipt_number' => $request->receipt_number,
            'notes' => $request->notes,
            'recorded_by' => $user->id,
        ]);

        return redirect()->route('farmer.expenses')->with('success', 'Expense recorded successfully!');
    }

    /**
     * Display the specified expense.
     */
    public function showExpense($id)
    {
        $user = Auth::user();
        $expense = \App\Models\Expense::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        return response()->json($expense);
    }

    /**
     * Update the specified expense.
     */
    public function updateExpense(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_type' => 'required|in:feed,medicine,equipment,labor,other',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'receipt_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $expense = \App\Models\Expense::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        $expense->update([
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_type' => $request->expense_type,
            'expense_date' => $request->expense_date,
            'payment_method' => $request->payment_method,
            'receipt_number' => $request->receipt_number,
            'notes' => $request->notes,
        ]);

        return redirect()->route('farmer.expenses')->with('success', 'Expense updated successfully!');
    }

    /**
     * Remove the specified expense.
     */
    public function deleteExpense($id)
    {
        $user = Auth::user();
        $expense = \App\Models\Expense::whereHas('farm', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($id);

        $expense->delete();

        return redirect()->route('farmer.expenses')->with('success', 'Expense deleted successfully!');
    }

    /**
     * Display the farmer's farms.
     */
    public function farms()
    {
        $user = Auth::user();
        
        // Get farms owned by the farmer
        $farms = Farm::where('owner_id', $user->id)->get();

        return view('farmer.farms', compact('farms'));
    }

    /**
     * Store a newly created farm.
     */
    public function storeFarm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'location' => 'required|string|max:500',
            'size' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $user = Auth::user();
            
            Farm::create([
                'name' => $request->name,
                'description' => $request->description,
                'location' => $request->location,
                'size' => $request->size,
                'status' => $request->status,
                'owner_id' => $user->id,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Farm created successfully!'
                ]);
            }

            return redirect()->route('farmer.farms')->with('success', 'Farm created successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create farm. Please try again.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to create farm. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified farm details.
     */
    public function farmDetails($id)
    {
        $user = Auth::user();
        
        // Get the farm and verify ownership
        $farm = Farm::where('id', $id)
                   ->where('owner_id', $user->id)
                   ->firstOrFail();

        // Get livestock for this farm
        $livestock = $farm->livestock()->orderBy('created_at', 'desc')->get();

        return view('farmer.farm-details', compact('farm', 'livestock'));
    }
}
