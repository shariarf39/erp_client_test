<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\OnboardingChecklist;
use App\Models\OnboardingTask;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;

class OnboardingChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = OnboardingChecklist::with(['department', 'designation', 'tasks']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $checklists = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('hr.onboarding.checklists.index', compact('checklists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $designations = Designation::orderBy('title')->get();

        return view('hr.onboarding.checklists.create', compact('departments', 'designations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'is_active' => 'boolean',
            'tasks' => 'required|array|min:1',
            'tasks.*.task_name' => 'required|string|max:200',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.category' => 'required|in:Documentation,IT Setup,Access & Permissions,Training,Introduction,Equipment,Administrative,Other',
            'tasks.*.is_mandatory' => 'boolean',
            'tasks.*.sequence_order' => 'required|integer|min:1',
        ]);

        // Create checklist
        $checklist = OnboardingChecklist::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
            'designation_id' => $validated['designation_id'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Create tasks
        foreach ($validated['tasks'] as $taskData) {
            OnboardingTask::create([
                'checklist_id' => $checklist->id,
                'task_name' => $taskData['task_name'],
                'description' => $taskData['description'] ?? null,
                'category' => $taskData['category'],
                'is_mandatory' => $taskData['is_mandatory'] ?? false,
                'sequence_order' => $taskData['sequence_order'],
            ]);
        }

        return redirect()->route('hr.onboarding.checklists.index')
                        ->with('success', 'Onboarding checklist created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OnboardingChecklist $checklist)
    {
        $checklist->load(['department', 'designation', 'tasks' => function($query) {
            $query->orderBy('sequence_order');
        }]);

        return view('hr.onboarding.checklists.show', compact('checklist'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OnboardingChecklist $checklist)
    {
        $checklist->load(['tasks' => function($query) {
            $query->orderBy('sequence_order');
        }]);
        
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $designations = Designation::orderBy('title')->get();

        return view('hr.onboarding.checklists.edit', compact('checklist', 'departments', 'designations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OnboardingChecklist $checklist)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'is_active' => 'boolean',
            'tasks' => 'required|array|min:1',
            'tasks.*.task_name' => 'required|string|max:200',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.category' => 'required|in:Documentation,IT Setup,Access & Permissions,Training,Introduction,Equipment,Administrative,Other',
            'tasks.*.is_mandatory' => 'boolean',
            'tasks.*.sequence_order' => 'required|integer|min:1',
        ]);

        // Update checklist
        $checklist->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
            'designation_id' => $validated['designation_id'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Delete existing tasks and create new ones
        $checklist->tasks()->delete();
        
        foreach ($validated['tasks'] as $taskData) {
            OnboardingTask::create([
                'checklist_id' => $checklist->id,
                'task_name' => $taskData['task_name'],
                'description' => $taskData['description'] ?? null,
                'category' => $taskData['category'],
                'is_mandatory' => $taskData['is_mandatory'] ?? false,
                'sequence_order' => $taskData['sequence_order'],
            ]);
        }

        return redirect()->route('hr.onboarding.checklists.index')
                        ->with('success', 'Onboarding checklist updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OnboardingChecklist $checklist)
    {
        // Check if checklist is being used
        if ($checklist->employeeOnboardings()->count() > 0) {
            return redirect()->route('hr.onboarding.checklists.index')
                            ->with('error', 'Cannot delete checklist that is being used by employees.');
        }

        $checklist->tasks()->delete();
        $checklist->delete();

        return redirect()->route('hr.onboarding.checklists.index')
                        ->with('success', 'Onboarding checklist deleted successfully.');
    }
}
