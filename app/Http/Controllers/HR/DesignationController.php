<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $designations = Designation::orderBy('title')->paginate(15);
        return view('hr.designations.index', compact('designations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('hr.designations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:designations,code|max:20',
            'title' => 'required|max:100',
            'description' => 'nullable|string',
            'level' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        Designation::create($validated);

        return redirect()->route('hr.designations.index')
                        ->with('success', 'Designation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Designation $designation)
    {
        return view('hr.designations.show', compact('designation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Designation $designation)
    {
        return view('hr.designations.edit', compact('designation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Designation $designation)
    {
        $validated = $request->validate([
            'code' => 'required|max:20|unique:designations,code,' . $designation->id,
            'title' => 'required|max:100',
            'description' => 'nullable|string',
            'level' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $designation->update($validated);

        return redirect()->route('hr.designations.index')
                        ->with('success', 'Designation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        $designation->delete();

        return redirect()->route('hr.designations.index')
                        ->with('success', 'Designation deleted successfully.');
    }
}
