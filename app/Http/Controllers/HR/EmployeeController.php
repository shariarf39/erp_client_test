<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'designation', 'branch']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        // Department filter
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('hr.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('hr.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_code' => 'required|unique:employees,employee_code',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string|max:15',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_joining' => 'required|date',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'branch_id' => 'nullable|exists:branches,id',
            'manager_id' => 'nullable|exists:employees,id',
            'employee_type' => 'nullable|in:Permanent,Contract,Temporary,Intern',
            'status' => 'nullable|in:Active,Inactive,On Leave,Terminated',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'date_of_birth' => 'nullable|date',
            'father_name' => 'nullable|string|max:100',
            'mother_name' => 'nullable|string|max:100',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:15',
            'nid_no' => 'nullable|string|max:20',
            'passport_no' => 'nullable|string|max:20',
            'tin_no' => 'nullable|string|max:20',
            'bank_name' => 'nullable|string|max:100',
            'bank_branch' => 'nullable|string|max:100',
            'account_no' => 'nullable|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            // Handle photo upload
            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('employees', 'public');
            }

            // Set full name
            $validated['employee_name'] = $validated['first_name'] . ' ' . $validated['last_name'];

            // Create employee
            $employee = Employee::create($validated);

            // Create user account for the employee
            $user = User::create([
                'name' => $employee->first_name . ' ' . $employee->last_name,
                'email' => $employee->email,
                'password' => Hash::make('password123'), // Default password
                'employee_id' => $employee->id,
                'role_id' => 2, // Default role (adjust as needed)
                'is_active' => true,
            ]);

            DB::commit();

            return redirect()->route('hr.employees.index')
                ->with('success', 'Employee created successfully! Default password is: password123');
        } catch (\Exception $e) {
            DB::rollback();
            if (isset($validated['photo'])) {
                Storage::disk('public')->delete($validated['photo']);
            }
            return back()->withInput()->with('error', 'Error creating employee: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = Employee::with([
            'department', 
            'designation', 
            'branch', 
            'manager',
            'user',
            'salaryStructure',
            'leaveBalances.leaveType'
        ])->findOrFail($id);

        return view('hr.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employee = Employee::findOrFail($id);
        return view('hr.form', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'employee_code' => 'required|unique:employees,employee_code,' . $id,
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:employees,email,' . $id,
            'phone' => 'required|string|max:15',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_joining' => 'required|date',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'branch_id' => 'nullable|exists:branches,id',
            'manager_id' => 'nullable|exists:employees,id',
            'employee_type' => 'nullable|in:Permanent,Contract,Temporary,Intern',
            'status' => 'nullable|in:Active,Inactive,On Leave,Terminated',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'date_of_birth' => 'nullable|date',
            'father_name' => 'nullable|string|max:100',
            'mother_name' => 'nullable|string|max:100',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:15',
            'nid_no' => 'nullable|string|max:20',
            'passport_no' => 'nullable|string|max:20',
            'tin_no' => 'nullable|string|max:20',
            'bank_name' => 'nullable|string|max:100',
            'bank_branch' => 'nullable|string|max:100',
            'account_no' => 'nullable|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo
                if ($employee->photo) {
                    Storage::disk('public')->delete($employee->photo);
                }
                $validated['photo'] = $request->file('photo')->store('employees', 'public');
            }

            // Set full name
            $validated['employee_name'] = $validated['first_name'] . ' ' . $validated['last_name'];

            // Update employee
            $employee->update($validated);

            // Update associated user account
            if ($employee->user) {
                $employee->user->update([
                    'name' => $employee->first_name . ' ' . $employee->last_name,
                    'email' => $employee->email,
                    'is_active' => $employee->status === 'Active',
                ]);
            }

            DB::commit();

            return redirect()->route('hr.employees.index')
                ->with('success', 'Employee updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Error updating employee: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            
            // Delete photo
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }

            // Soft delete the employee
            $employee->delete();

            return redirect()->route('hr.employees.index')
                ->with('success', 'Employee deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting employee: ' . $e->getMessage());
        }
    }
}
