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
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
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

        $kpis = $query->orderBy('category')->orderBy('name')->paginate(15);

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
            'name' => 'required|string|max:200',
            'code' => 'required|string|max:50|unique:performance_kpis,code',
            'description' => 'nullable|string',
            'category' => 'required|in:Quality,Productivity,Efficiency,Customer Satisfaction,Innovation,Leadership,Teamwork,Other',
            'measurement_type' => 'required|in:Percentage,Number,Rating,Yes/No',
            'unit' => 'nullable|string|max:50',
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
            'name' => 'required|string|max:200',
            'code' => 'required|string|max:50|unique:performance_kpis,code,' . $kpi->id,
            'description' => 'nullable|string',
            'category' => 'required|in:Quality,Productivity,Efficiency,Customer Satisfaction,Innovation,Leadership,Teamwork,Other',
            'measurement_type' => 'required|in:Percentage,Number,Rating,Yes/No',
            'unit' => 'nullable|string|max:50',
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
