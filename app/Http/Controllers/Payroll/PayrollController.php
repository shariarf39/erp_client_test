<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payrolls = Payroll::with(['employee'])->orderBy('month', 'desc')->paginate(15);
        return view('payroll.index', compact('payrolls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payroll.create');
    }

    /**
     * Process payroll for a specific month and year
     */
    public function process($month, $year)
    {
        try {
            $workingDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            
            // Get all active employees with salary structures
            $employees = \App\Models\Employee::where('status', 'Active')
                ->with('salaryStructure')
                ->whereHas('salaryStructure', function($query) {
                    $query->where('is_active', 1);
                })
                ->get();

            $processedCount = 0;
            $skippedCount = 0;

            foreach ($employees as $employee) {
                // Check if payroll already exists for this month/year
                $existingPayroll = Payroll::where('employee_id', $employee->id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->first();

                if ($existingPayroll) {
                    $skippedCount++;
                    continue;
                }

                $salaryStructure = $employee->salaryStructure;

                // Calculate attendance
                $attendance = \App\Models\Attendance::where('employee_id', $employee->id)
                    ->whereMonth('date', $month)
                    ->whereYear('date', $year)
                    ->get();

                $presentDays = $attendance->where('status', 'Present')->count();
                $absentDays = $attendance->where('status', 'Absent')->count();
                
                // Calculate leave days
                $leaveDays = \App\Models\LeaveApplication::where('employee_id', $employee->id)
                    ->where('status', 'Approved')
                    ->where(function($query) use ($month, $year) {
                        $query->whereMonth('from_date', $month)
                              ->whereYear('from_date', $year);
                    })
                    ->sum('days');

                // Calculate total allowances
                $totalAllowance = ($salaryStructure->house_rent_allowance ?? 0) +
                                ($salaryStructure->medical_allowance ?? 0) +
                                ($salaryStructure->transport_allowance ?? 0) +
                                ($salaryStructure->food_allowance ?? 0) +
                                ($salaryStructure->other_allowance ?? 0);

                // Calculate total deductions
                $totalDeduction = ($salaryStructure->provident_fund ?? 0) +
                                ($salaryStructure->tax ?? 0) +
                                ($salaryStructure->other_deduction ?? 0);

                // Calculate gross and net salary
                $basicSalary = $salaryStructure->basic_salary ?? 0;
                $grossSalary = $basicSalary + $totalAllowance;
                $netSalary = $grossSalary - $totalDeduction;

                // Adjust for absent days
                $perDaySalary = $netSalary / $workingDays;
                $absentDeduction = $perDaySalary * $absentDays;
                $netSalary = $netSalary - $absentDeduction;

                // Create payroll record
                Payroll::create([
                    'employee_id' => $employee->id,
                    'month' => $month,
                    'year' => $year,
                    'working_days' => $workingDays,
                    'present_days' => $presentDays,
                    'absent_days' => $absentDays,
                    'leave_days' => $leaveDays,
                    'overtime_hours' => 0,
                    'overtime_amount' => 0,
                    'basic_salary' => $basicSalary,
                    'total_allowance' => $totalAllowance,
                    'total_deduction' => $totalDeduction + $absentDeduction,
                    'gross_salary' => $grossSalary,
                    'net_salary' => $netSalary,
                    'status' => 'Processed',
                    'processed_by' => auth()->id(),
                    'processed_at' => now(),
                ]);

                $processedCount++;
            }

            return redirect()->route('payroll.payroll.index')
                ->with('success', "Payroll processed successfully! Processed: {$processedCount}, Skipped: {$skippedCount}");

        } catch (\Exception $e) {
            return redirect()->route('payroll.payroll.index')
                ->with('error', 'Error processing payroll: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
