<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::orderBy('name')->paginate(15);
        
        return view('hr.shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('hr.shifts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'start_time' => 'required',
            'end_time' => 'required',
            'break_duration' => 'nullable|integer|min:0',
            'grace_time' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        $shift = Shift::create($validated);
        
        return redirect()->route('hr.shifts.index')
            ->with('success', 'Shift created successfully.');
    }

    public function edit(Shift $shift)
    {
        return view('hr.shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'start_time' => 'required',
            'end_time' => 'required',
            'break_duration' => 'nullable|integer|min:0',
            'grace_time' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        $shift->update($validated);
        
        return redirect()->route('hr.shifts.index')
            ->with('success', 'Shift updated successfully.');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();
        
        return redirect()->route('hr.shifts.index')
            ->with('success', 'Shift deleted successfully.');
    }
}
