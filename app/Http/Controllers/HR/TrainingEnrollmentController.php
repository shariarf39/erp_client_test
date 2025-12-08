<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\TrainingEnrollment;
use App\Models\TrainingProgram;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class TrainingEnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TrainingEnrollment::with(['trainingProgram', 'employee', 'approvedBy']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            })->orWhereHas('trainingProgram', function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('program_code', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by program
        if ($request->filled('program_id')) {
            $query->where('training_program_id', $request->program_id);
        }

        $enrollments = $query->orderBy('enrollment_date', 'desc')->paginate(20);

        return view('hr.training.enrollments.index', compact('enrollments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programs = TrainingProgram::whereIn('status', ['Open for Enrollment', 'Planned'])
                                   ->orderBy('title')
                                   ->get();
        $employees = Employee::where('status', 'Active')
                            ->orderBy('first_name')
                            ->get();

        return view('hr.training.enrollments.create', compact('programs', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'training_program_id' => 'required|exists:training_programs,id',
            'employee_id' => 'required|exists:employees,id',
            'enrollment_date' => 'required|date',
            'enrollment_status' => 'required|in:Enrolled,Waitlisted,Approved,Rejected,Completed,Cancelled',
            'is_mandatory' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        // Check if employee is already enrolled
        $exists = TrainingEnrollment::where('training_program_id', $validated['training_program_id'])
                                    ->where('employee_id', $validated['employee_id'])
                                    ->whereIn('enrollment_status', ['Enrolled', 'Approved', 'Completed'])
                                    ->exists();

        if ($exists) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Employee is already enrolled in this program.');
        }

        TrainingEnrollment::create($validated);

        return redirect()->route('hr.training.enrollments.index')
                        ->with('success', 'Training enrollment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TrainingEnrollment $enrollment)
    {
        $enrollment->load(['trainingProgram', 'employee', 'approvedBy']);

        return view('hr.training.enrollments.show', compact('enrollment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TrainingEnrollment $enrollment)
    {
        $programs = TrainingProgram::orderBy('title')->get();
        $employees = Employee::where('status', 'Active')->orderBy('first_name')->get();

        return view('hr.training.enrollments.edit', compact('enrollment', 'programs', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrainingEnrollment $enrollment)
    {
        $validated = $request->validate([
            'enrollment_status' => 'required|in:Enrolled,Waitlisted,Approved,Rejected,Completed,Cancelled',
            'attendance_percentage' => 'nullable|numeric|min:0|max:100',
            'assessment_score' => 'nullable|numeric|min:0|max:100',
            'passed' => 'nullable|boolean',
            'certificate_issued' => 'nullable|boolean',
            'certificate_number' => 'nullable|string|max:100',
            'completion_date' => 'nullable|date',
            'feedback' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Auto-approve or set approved_by
        if ($request->enrollment_status == 'Approved' && !$enrollment->approved_by) {
            $validated['approved_by'] = auth()->id();
            $validated['approved_date'] = now();
        }

        $enrollment->update($validated);

        return redirect()->route('hr.training.enrollments.index')
                        ->with('success', 'Training enrollment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingEnrollment $enrollment)
    {
        // Only allow deletion if not completed
        if ($enrollment->enrollment_status == 'Completed') {
            return redirect()->route('hr.training.enrollments.index')
                            ->with('error', 'Cannot delete completed enrollments.');
        }

        $enrollment->delete();

        return redirect()->route('hr.training.enrollments.index')
                        ->with('success', 'Training enrollment deleted successfully.');
    }
}
