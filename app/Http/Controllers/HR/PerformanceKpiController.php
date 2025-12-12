<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\PerformanceKpi;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;

class PerformanceKpiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PerformanceKpi::with(['department', 'designation']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kpi_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $kpis = $query->orderBy('category')->orderBy('kpi_name')->paginate(15);

        return view('hr.performance.kpis.index', compact('kpis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $designations = Designation::orderBy('title')->get();

        return view('hr.performance.kpis.create', compact('departments', 'designations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kpi_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'category' => 'required|in:Productivity,Quality,Efficiency,Customer Satisfaction,Financial,Innovation,Teamwork,Leadership',
            'measurement_type' => 'required|in:Quantitative,Qualitative,Both',
            'unit_of_measure' => 'nullable|string|max:50',
            'target_value' => 'nullable|numeric',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'weight' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        PerformanceKpi::create($validated);

        return redirect()->route('hr.performance.kpis.index')
                        ->with('success', 'KPI created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PerformanceKpi $kpi)
    {
        $kpi->load(['department', 'designation', 'reviewKpis']);

        return view('hr.performance.kpis.show', compact('kpi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PerformanceKpi $kpi)
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $designations = Designation::orderBy('title')->get();

        return view('hr.performance.kpis.edit', compact('kpi', 'departments', 'designations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PerformanceKpi $kpi)
    {
        $validated = $request->validate([
            'kpi_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'category' => 'required|in:Productivity,Quality,Efficiency,Customer Satisfaction,Financial,Innovation,Teamwork,Leadership',
            'measurement_type' => 'required|in:Quantitative,Qualitative,Both',
            'unit_of_measure' => 'nullable|string|max:50',
            'target_value' => 'nullable|numeric',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'weight' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $kpi->update($validated);

        return redirect()->route('hr.performance.kpis.index')
                        ->with('success', 'KPI updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PerformanceKpi $kpi)
    {
        // Check if KPI is being used in reviews
        if ($kpi->reviewKpis()->count() > 0) {
            return redirect()->route('hr.performance.kpis.index')
                            ->with('error', 'Cannot delete KPI that is being used in performance reviews.');
        }

        $kpi->delete();

        return redirect()->route('hr.performance.kpis.index')
                        ->with('success', 'KPI deleted successfully.');
    }
}
