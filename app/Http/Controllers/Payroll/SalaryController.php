<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\SalaryStructure;
use App\Models\Employee;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SalaryStructure::with(['employee.department', 'employee.designation']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $salaryStructures = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('payroll.salary-structures.index', compact('salaryStructures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('status', 'Active')
            ->whereDoesntHave('salaryStructure', function($query) {
                $query->where('is_active', 1);
            })
            ->with(['department', 'designation'])
            ->get();
        
        return view('payroll.salary-structures.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'basic_salary' => 'required|numeric|min:0',
            'house_rent' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'food_allowance' => 'nullable|numeric|min:0',
            'other_allowance' => 'nullable|numeric|min:0',
            'provident_fund' => 'nullable|numeric|min:0',
            'tax_deduction' => 'nullable|numeric|min:0',
            'other_deduction' => 'nullable|numeric|min:0',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after:effective_from',
        ]);

        // Calculate gross and net salary
        $grossSalary = $validated['basic_salary'] +
                      ($validated['house_rent'] ?? 0) +
                      ($validated['medical_allowance'] ?? 0) +
                      ($validated['transport_allowance'] ?? 0) +
                      ($validated['food_allowance'] ?? 0) +
                      ($validated['other_allowance'] ?? 0);

        $netSalary = $grossSalary -
                    ($validated['provident_fund'] ?? 0) -
                    ($validated['tax_deduction'] ?? 0) -
                    ($validated['other_deduction'] ?? 0);

        $validated['gross_salary'] = $grossSalary;
        $validated['net_salary'] = $netSalary;
        $validated['created_by'] = auth()->id();
        $validated['is_active'] = 1;

        // Deactivate existing active salary structures for this employee
        SalaryStructure::where('employee_id', $validated['employee_id'])
                      ->where('is_active', 1)
                      ->update(['is_active' => 0, 'effective_to' => now()]);

        SalaryStructure::create($validated);

        return redirect()->route('payroll.salary-structures.index')
                        ->with('success', 'Salary structure created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SalaryStructure $salaryStructure)
    {
        $salaryStructure->load(['employee.department', 'employee.designation']);
        return view('payroll.salary-structures.show', compact('salaryStructure'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalaryStructure $salaryStructure)
    {
        $employees = Employee::where('status', 'Active')
            ->with(['department', 'designation'])
            ->get();
        
        return view('payroll.salary-structures.edit', compact('salaryStructure', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalaryStructure $salaryStructure)
    {
        $validated = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'house_rent' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'food_allowance' => 'nullable|numeric|min:0',
            'other_allowance' => 'nullable|numeric|min:0',
            'provident_fund' => 'nullable|numeric|min:0',
            'tax_deduction' => 'nullable|numeric|min:0',
            'other_deduction' => 'nullable|numeric|min:0',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after:effective_from',
            'is_active' => 'boolean',
        ]);

        // Calculate gross and net salary
        $grossSalary = $validated['basic_salary'] +
                      ($validated['house_rent'] ?? 0) +
                      ($validated['medical_allowance'] ?? 0) +
                      ($validated['transport_allowance'] ?? 0) +
                      ($validated['food_allowance'] ?? 0) +
                      ($validated['other_allowance'] ?? 0);

        $netSalary = $grossSalary -
                    ($validated['provident_fund'] ?? 0) -
                    ($validated['tax_deduction'] ?? 0) -
                    ($validated['other_deduction'] ?? 0);

        $validated['gross_salary'] = $grossSalary;
        $validated['net_salary'] = $netSalary;

        $salaryStructure->update($validated);

        return redirect()->route('payroll.salary-structures.index')
                        ->with('success', 'Salary structure updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryStructure $salaryStructure)
    {
        $salaryStructure->delete();

        return redirect()->route('payroll.salary-structures.index')
                        ->with('success', 'Salary structure deleted successfully.');
    }
}
