<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\ShiftSchedule;
use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = ShiftSchedule::with(['employee', 'shift', 'createdBy']);
        
        if ($request->filled('date')) {
            $query->whereDate('schedule_date', $request->date);
        }
        
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        
        if ($request->filled('shift_id')) {
            $query->where('shift_id', $request->shift_id);
        }
        
        $schedules = $query->orderBy('schedule_date', 'desc')->paginate(20);
        $employees = Employee::where('status', 'Active')->orderBy('first_name')->get();
        $shifts = Shift::where('is_active', 1)->orderBy('name')->get();
        
        return view('hr.shift-schedules.index', compact('schedules', 'employees', 'shifts'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'Active')->orderBy('first_name')->get();
        $shifts = Shift::where('is_active', 1)->orderBy('name')->get();
        
        return view('hr.shift-schedules.create', compact('employees', 'shifts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_id' => 'required|exists:shifts,id',
            'schedule_date' => 'required|date',
            'is_overtime' => 'boolean',
            'status' => 'required|in:Scheduled,Confirmed,Cancelled,Swapped',
            'notes' => 'nullable',
        ]);
        
        $validated['created_by'] = auth()->id();
        
        ShiftSchedule::create($validated);
        
        return redirect()->route('hr.shift-schedules.index')
            ->with('success', 'Shift schedule created successfully.');
    }

    public function edit(ShiftSchedule $shiftSchedule)
    {
        $employees = Employee::where('status', 'Active')->orderBy('first_name')->get();
        $shifts = Shift::where('is_active', 1)->orderBy('name')->get();
        
        return view('hr.shift-schedules.edit', compact('shiftSchedule', 'employees', 'shifts'));
    }

    public function update(Request $request, ShiftSchedule $shiftSchedule)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_id' => 'required|exists:shifts,id',
            'schedule_date' => 'required|date',
            'is_overtime' => 'boolean',
            'status' => 'required|in:Scheduled,Confirmed,Cancelled,Swapped',
            'notes' => 'nullable',
        ]);
        
        $shiftSchedule->update($validated);
        
        return redirect()->route('hr.shift-schedules.index')
            ->with('success', 'Shift schedule updated successfully.');
    }

    public function destroy(ShiftSchedule $shiftSchedule)
    {
        $shiftSchedule->delete();
        
        return redirect()->route('hr.shift-schedules.index')
            ->with('success', 'Shift schedule deleted successfully.');
    }
}
