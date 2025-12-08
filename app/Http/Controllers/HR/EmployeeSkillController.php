<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\EmployeeSkill;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeSkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EmployeeSkill::with(['employee']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('skill_name', 'like', "%{$search}%")
                  ->orWhereHas('employee', function($q2) use ($search) {
                      $q2->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by employee
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter by skill category
        if ($request->filled('skill_category')) {
            $query->where('skill_category', $request->skill_category);
        }

        // Filter by proficiency
        if ($request->filled('proficiency_level')) {
            $query->where('proficiency_level', $request->proficiency_level);
        }

        $skills = $query->orderBy('employee_id')->orderBy('skill_category')->paginate(20);

        return view('hr.training.skills.index', compact('skills'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('status', 'Active')
                            ->orderBy('first_name')
                            ->get();

        return view('hr.training.skills.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'skill_name' => 'required|string|max:100',
            'skill_category' => 'required|in:Technical,Soft Skills,Language,Management,Industry Knowledge,Tools & Software,Certifications,Other',
            'proficiency_level' => 'required|in:Beginner,Intermediate,Advanced,Expert',
            'years_of_experience' => 'nullable|integer|min:0',
            'last_used' => 'nullable|date',
            'certified' => 'boolean',
            'certification_name' => 'nullable|string|max:200',
            'certification_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        EmployeeSkill::create($validated);

        return redirect()->route('hr.training.skills.index')
                        ->with('success', 'Employee skill added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeSkill $skill)
    {
        $skill->load(['employee']);

        return view('hr.training.skills.show', compact('skill'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeSkill $skill)
    {
        $employees = Employee::where('status', 'Active')->orderBy('first_name')->get();

        return view('hr.training.skills.edit', compact('skill', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeSkill $skill)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'skill_name' => 'required|string|max:100',
            'skill_category' => 'required|in:Technical,Soft Skills,Language,Management,Industry Knowledge,Tools & Software,Certifications,Other',
            'proficiency_level' => 'required|in:Beginner,Intermediate,Advanced,Expert',
            'years_of_experience' => 'nullable|integer|min:0',
            'last_used' => 'nullable|date',
            'certified' => 'boolean',
            'certification_name' => 'nullable|string|max:200',
            'certification_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $skill->update($validated);

        return redirect()->route('hr.training.skills.index')
                        ->with('success', 'Employee skill updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeSkill $skill)
    {
        $skill->delete();

        return redirect()->route('hr.training.skills.index')
                        ->with('success', 'Employee skill deleted successfully.');
    }
}
