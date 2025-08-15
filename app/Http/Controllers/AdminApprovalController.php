<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminApprovalController extends Controller
{
    /**
     * Show the admin approval dashboard.
     */
    public function index()
    {
        $pendingUsers = User::where('status', 'pending')->get();
        $approvedUsers = User::where('status', 'approved')->latest()->take(10)->get();
        $rejectedUsers = User::where('status', 'rejected')->latest()->take(10)->get();

        return view('admin.approvals.index', compact('pendingUsers', 'approvedUsers', 'rejectedUsers'));
    }

    /**
     * Show pending farmer registrations for admin users.
     */
    public function pendingRegistrations()
    {
        $pendingUsers = User::where('status', 'pending')
                           ->where('role', 'farmer')
                           ->get();

        return view('admin.pending-registrations', compact('pendingUsers'));
    }

    /**
     * Approve a user registration.
     */
    public function approve($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->status !== 'pending') {
            return back()->with('error', 'User is not in pending status.');
        }

        $user->update([
            'status' => 'approved',
            'is_active' => true
        ]);

        // Log the approval action
        $this->logAuditAction('approve', 'users', $user->id);

        // Send approval email (you can implement this later)
        // $this->sendApprovalEmail($user);

        return back()->with('success', 'User registration approved successfully.');
    }

    /**
     * Reject a user registration.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $user = User::findOrFail($id);
        
        if ($user->status !== 'pending') {
            return back()->with('error', 'User is not in pending status.');
        }

        $user->update([
            'status' => 'rejected',
            'is_active' => false
        ]);

        // Log the rejection action
        $this->logAuditAction('reject', 'users', $user->id, $request->rejection_reason);

        // Send rejection email (you can implement this later)
        // $this->sendRejectionEmail($user, $request->rejection_reason);

        return back()->with('success', 'User registration rejected.');
    }

    /**
     * Show user details for approval.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.approvals.show', compact('user'));
    }

    /**
     * Log audit action.
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
