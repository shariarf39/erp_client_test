<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;

class TrainingProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = TrainingProgram::with('createdBy');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('program_code', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        $programs = $query->orderBy('start_date', 'desc')->paginate(15);
        
        return view('hr.training.programs.index', compact('programs'));
    }

    public function create()
    {
        $lastProgram = TrainingProgram::latest('id')->first();
        $programCode = 'TRN-' . date('Y') . '-' . str_pad(($lastProgram ? $lastProgram->id + 1 : 1), 4, '0', STR_PAD_LEFT);
        
        return view('hr.training.programs.create', compact('programCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_code' => 'required|unique:training_programs|max:50',
            'title' => 'required|string|max:200',
            'description' => 'nullable',
            'category' => 'required|in:Technical,Soft Skills,Leadership,Compliance,Safety,Product,Other',
            'training_type' => 'required|in:Classroom,Online,On-the-Job,Workshop,Seminar,Conference,Certification',
            'trainer_name' => 'nullable|max:200',
            'trainer_type' => 'nullable|in:Internal,External,Online Platform',
            'duration_days' => 'nullable|numeric|min:0',
            'duration_hours' => 'nullable|numeric|min:0',
            'max_participants' => 'nullable|integer|min:1',
            'cost_per_participant' => 'nullable|numeric|min:0',
            'venue' => 'nullable|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'enrollment_deadline' => 'nullable|date',
            'prerequisites' => 'nullable',
            'objectives' => 'nullable',
            'status' => 'required|in:Planned,Open for Enrollment,In Progress,Completed,Cancelled',
        ]);
        
        $validated['created_by'] = auth()->id();
        
        $program = TrainingProgram::create($validated);
        
        return redirect()->route('hr.training.programs.show', $program)
            ->with('success', 'Training program created successfully.');
    }

    public function show(TrainingProgram $program)
    {
        $program->load(['enrollments.employee', 'createdBy']);
        
        return view('hr.training.programs.show', compact('program'));
    }

    public function edit(TrainingProgram $program)
    {
        return view('hr.training.programs.edit', compact('program'));
    }

    public function update(Request $request, TrainingProgram $program)
    {
        $validated = $request->validate([
            'program_code' => 'required|unique:training_programs,program_code,' . $program->id . '|max:50',
            'title' => 'required|string|max:200',
            'description' => 'nullable',
            'category' => 'required|in:Technical,Soft Skills,Leadership,Compliance,Safety,Product,Other',
            'training_type' => 'required|in:Classroom,Online,On-the-Job,Workshop,Seminar,Conference,Certification',
            'trainer_name' => 'nullable|max:200',
            'trainer_type' => 'nullable|in:Internal,External,Online Platform',
            'duration_days' => 'nullable|numeric|min:0',
            'duration_hours' => 'nullable|numeric|min:0',
            'max_participants' => 'nullable|integer|min:1',
            'cost_per_participant' => 'nullable|numeric|min:0',
            'venue' => 'nullable|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'enrollment_deadline' => 'nullable|date',
            'prerequisites' => 'nullable',
            'objectives' => 'nullable',
            'status' => 'required|in:Planned,Open for Enrollment,In Progress,Completed,Cancelled',
        ]);
        
        $program->update($validated);
        
        return redirect()->route('hr.training.programs.show', $program)
            ->with('success', 'Training program updated successfully.');
    }

    public function destroy(TrainingProgram $program)
    {
        if ($program->enrollments()->count() > 0) {
            return redirect()->route('hr.training.programs.index')
                ->with('error', 'Cannot delete program with existing enrollments.');
        }
        
        $program->delete();
        
        return redirect()->route('hr.training.programs.index')
            ->with('success', 'Training program deleted successfully.');
    }
}
