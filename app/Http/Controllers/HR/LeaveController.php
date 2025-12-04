<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LeaveApplication::with(['employee.department', 'leaveType', 'approver']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date filter
        if ($request->filled('from_date')) {
            $query->whereDate('from_date', '>=', $request->from_date);
        }

        $leaveApplications = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('hr.leaves.index', compact('leaveApplications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = \App\Models\Employee::where('status', 'Active')
            ->with('department')
            ->orderBy('first_name')
            ->get();
        
        $leaveTypes = \App\Models\LeaveType::where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view('hr.leaves.create', compact('employees', 'leaveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'days' => 'required|numeric|min:0.5',
            'reason' => 'required',
            'status' => 'required|in:Pending,Approved,Rejected,Cancelled',
        ]);

        LeaveApplication::create($validated);

        return redirect()->route('hr.leaves.index')
            ->with('success', 'Leave application submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $leaveApplication = LeaveApplication::with([
            'employee.department',
            'employee.designation',
            'leaveType',
            'approver'
        ])->findOrFail($id);

        return view('hr.leaves.show', compact('leaveApplication'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $leaveApplication = LeaveApplication::findOrFail($id);
        
        // Only allow editing if status is Pending
        if ($leaveApplication->status !== 'Pending') {
            return redirect()->route('hr.leaves.index')
                ->with('error', 'Only pending leave applications can be edited.');
        }

        $employees = \App\Models\Employee::where('status', 'Active')
            ->with('department')
            ->orderBy('first_name')
            ->get();
        
        $leaveTypes = \App\Models\LeaveType::where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view('hr.leaves.edit', compact('leaveApplication', 'employees', 'leaveTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $leaveApplication = LeaveApplication::findOrFail($id);
        
        // Only allow updating if status is Pending
        if ($leaveApplication->status !== 'Pending') {
            return redirect()->route('hr.leaves.index')
                ->with('error', 'Only pending leave applications can be updated.');
        }

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'days' => 'required|numeric|min:0.5',
            'reason' => 'required',
        ]);

        $leaveApplication->update($validated);

        return redirect()->route('hr.leaves.index')
            ->with('success', 'Leave application updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $leaveApplication = LeaveApplication::findOrFail($id);
        
        // Only allow deletion if status is Pending or Cancelled
        if (!in_array($leaveApplication->status, ['Pending', 'Cancelled'])) {
            return redirect()->route('hr.leaves.index')
                ->with('error', 'Only pending or cancelled leave applications can be deleted.');
        }

        $leaveApplication->delete();

        return redirect()->route('hr.leaves.index')
            ->with('success', 'Leave application deleted successfully.');
    }

    /**
     * Approve leave application
     */
    public function approve(string $id)
    {
        $leaveApplication = LeaveApplication::findOrFail($id);
        
        if ($leaveApplication->status !== 'Pending') {
            return redirect()->route('hr.leaves.index')
                ->with('error', 'Only pending leave applications can be approved.');
        }

        $leaveApplication->update([
            'status' => 'Approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Update leave balance if exists
        $leaveBalance = \App\Models\LeaveBalance::where('employee_id', $leaveApplication->employee_id)
            ->where('leave_type_id', $leaveApplication->leave_type_id)
            ->first();

        if ($leaveBalance) {
            $leaveBalance->increment('used_days', $leaveApplication->days);
            $leaveBalance->decrement('available_days', $leaveApplication->days);
        }

        return redirect()->route('hr.leaves.index')
            ->with('success', 'Leave application approved successfully.');
    }

    /**
     * Reject leave application
     */
    public function reject(Request $request, string $id)
    {
        $leaveApplication = LeaveApplication::findOrFail($id);
        
        if ($leaveApplication->status !== 'Pending') {
            return redirect()->route('hr.leaves.index')
                ->with('error', 'Only pending leave applications can be rejected.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $leaveApplication->update([
            'status' => 'Rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('hr.leaves.index')
            ->with('success', 'Leave application rejected.');
    }

    /**
     * Cancel leave application
     */
    public function cancel(string $id)
    {
        $leaveApplication = LeaveApplication::findOrFail($id);
        
        if (!in_array($leaveApplication->status, ['Pending', 'Approved'])) {
            return redirect()->route('hr.leaves.index')
                ->with('error', 'Only pending or approved leave applications can be cancelled.');
        }

        // If already approved, restore leave balance
        if ($leaveApplication->status === 'Approved') {
            $leaveBalance = \App\Models\LeaveBalance::where('employee_id', $leaveApplication->employee_id)
                ->where('leave_type_id', $leaveApplication->leave_type_id)
                ->first();

            if ($leaveBalance) {
                $leaveBalance->decrement('used_days', $leaveApplication->days);
                $leaveBalance->increment('available_days', $leaveApplication->days);
            }
        }

        $leaveApplication->update(['status' => 'Cancelled']);

        return redirect()->route('hr.leaves.index')
            ->with('success', 'Leave application cancelled successfully.');
    }
}
