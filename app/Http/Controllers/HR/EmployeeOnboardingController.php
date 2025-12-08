<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\EmployeeOnboarding;
use App\Models\Employee;
use App\Models\OnboardingChecklist;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeOnboardingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EmployeeOnboarding::with(['employee', 'checklist', 'assignedTo']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $onboardings = $query->orderBy('start_date', 'desc')->paginate(15);

        return view('hr.onboarding.employee-onboarding.index', compact('onboardings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('status', 'Active')
                            ->whereDoesntHave('employeeOnboarding')
                            ->orderBy('first_name')
                            ->get();
        $checklists = OnboardingChecklist::where('is_active', true)->orderBy('name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('hr.onboarding.employee-onboarding.create', compact('employees', 'checklists', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'checklist_id' => 'required|exists:onboarding_checklists,id',
            'assigned_to' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'expected_completion_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'Not Started';
        $validated['completion_percentage'] = 0;

        $onboarding = EmployeeOnboarding::create($validated);

        // Create employee onboarding tasks from checklist
        $checklist = OnboardingChecklist::with('tasks')->find($validated['checklist_id']);
        foreach ($checklist->tasks as $task) {
            $onboarding->taskProgress()->create([
                'task_id' => $task->id,
                'status' => 'Pending',
            ]);
        }

        return redirect()->route('hr.onboarding.employee-onboarding.index')
                        ->with('success', 'Employee onboarding created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeOnboarding $employeeOnboarding)
    {
        $employeeOnboarding->load([
            'employee',
            'checklist.tasks',
            'assignedTo',
            'taskProgress.task',
            'taskProgress.completedBy'
        ]);

        return view('hr.onboarding.employee-onboarding.show', compact('employeeOnboarding'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeOnboarding $employeeOnboarding)
    {
        $employees = Employee::where('status', 'Active')->orderBy('first_name')->get();
        $checklists = OnboardingChecklist::where('is_active', true)->orderBy('name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('hr.onboarding.employee-onboarding.edit', compact('employeeOnboarding', 'employees', 'checklists', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeOnboarding $employeeOnboarding)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'expected_completion_date' => 'required|date|after:start_date',
            'actual_completion_date' => 'nullable|date',
            'status' => 'required|in:Not Started,In Progress,Completed,On Hold',
            'notes' => 'nullable|string',
        ]);

        $employeeOnboarding->update($validated);

        // Recalculate progress
        $this->updateProgress($employeeOnboarding);

        return redirect()->route('hr.onboarding.employee-onboarding.index')
                        ->with('success', 'Employee onboarding updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeOnboarding $employeeOnboarding)
    {
        $employeeOnboarding->taskProgress()->delete();
        $employeeOnboarding->delete();

        return redirect()->route('hr.onboarding.employee-onboarding.index')
                        ->with('success', 'Employee onboarding deleted successfully.');
    }

    /**
     * Update task status
     */
    public function updateTask(Request $request, EmployeeOnboarding $employeeOnboarding, $taskId)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed,Skipped',
            'notes' => 'nullable|string',
        ]);

        $taskProgress = $employeeOnboarding->taskProgress()->where('task_id', $taskId)->first();
        
        if ($taskProgress) {
            $taskProgress->update([
                'status' => $validated['status'],
                'completed_by' => $validated['status'] == 'Completed' ? auth()->id() : null,
                'completed_at' => $validated['status'] == 'Completed' ? now() : null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Update progress
            $this->updateProgress($employeeOnboarding);
        }

        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    /**
     * Calculate and update progress percentage
     */
    private function updateProgress(EmployeeOnboarding $employeeOnboarding)
    {
        $totalTasks = $employeeOnboarding->taskProgress()->count();
        $completedTasks = $employeeOnboarding->taskProgress()->where('status', 'Completed')->count();
        
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        
        $employeeOnboarding->update([
            'completion_percentage' => $progress,
            'status' => $progress == 0 ? 'Not Started' : ($progress == 100 ? 'Completed' : 'In Progress'),
            'actual_completion_date' => $progress == 100 ? now() : null,
        ]);
    }
}
