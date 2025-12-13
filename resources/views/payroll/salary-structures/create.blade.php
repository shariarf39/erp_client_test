@extends('layouts.app')

@section('title', 'Create Salary Structure')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Create Salary Structure</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Payroll</li>
                    <li class="breadcrumb-item"><a href="{{ route('payroll.salary-structures.index') }}">Salary Structures</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('payroll.salary-structures.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <form action="{{ route('payroll.salary-structures.store') }}" method="POST" id="salaryForm">
        @csrf
        
        <div class="row">
            <div class="col-md-8">
                <!-- Employee Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Employee Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="employee_id" class="form-label">Select Employee <span class="text-danger">*</span></label>
                                <select class="form-select @error('employee_id') is-invalid @enderror" 
                                    id="employee_id" name="employee_id" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}
                                            data-department="{{ $employee->department->name ?? 'N/A' }}"
                                            data-designation="{{ $employee->designation->title ?? 'N/A' }}">
                                            {{ $employee->employee_code }} - {{ $employee->first_name }} {{ $employee->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" class="form-control" id="display_department" readonly placeholder="Select employee">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Designation</label>
                                <input type="text" class="form-control" id="display_designation" readonly placeholder="Select employee">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="effective_from" class="form-label">Effective From <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('effective_from') is-invalid @enderror" 
                                    id="effective_from" name="effective_from" value="{{ old('effective_from', date('Y-m-d')) }}" required>
                                @error('effective_from')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="effective_to" class="form-label">Effective To (Optional)</label>
                                <input type="date" class="form-control @error('effective_to') is-invalid @enderror" 
                                    id="effective_to" name="effective_to" value="{{ old('effective_to') }}">
                                @error('effective_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Salary -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-dollar-sign"></i> Basic Salary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="basic_salary" class="form-label">Basic Salary <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control form-control-lg @error('basic_salary') is-invalid @enderror" 
                                    id="basic_salary" name="basic_salary" value="{{ old('basic_salary', 0) }}" required onchange="calculateSalary()">
                                @error('basic_salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Allowances -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Allowances</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="house_rent" class="form-label">House Rent</label>
                                <input type="number" step="0.01" class="form-control allowance" 
                                    id="house_rent" name="house_rent" value="{{ old('house_rent', 0) }}" onchange="calculateSalary()">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="medical_allowance" class="form-label">Medical Allowance</label>
                                <input type="number" step="0.01" class="form-control allowance" 
                                    id="medical_allowance" name="medical_allowance" value="{{ old('medical_allowance', 0) }}" onchange="calculateSalary()">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="transport_allowance" class="form-label">Transport Allowance</label>
                                <input type="number" step="0.01" class="form-control allowance" 
                                    id="transport_allowance" name="transport_allowance" value="{{ old('transport_allowance', 0) }}" onchange="calculateSalary()">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="food_allowance" class="form-label">Food Allowance</label>
                                <input type="number" step="0.01" class="form-control allowance" 
                                    id="food_allowance" name="food_allowance" value="{{ old('food_allowance', 0) }}" onchange="calculateSalary()">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="other_allowance" class="form-label">Other Allowance</label>
                                <input type="number" step="0.01" class="form-control allowance" 
                                    id="other_allowance" name="other_allowance" value="{{ old('other_allowance', 0) }}" onchange="calculateSalary()">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deductions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-minus-circle"></i> Deductions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="provident_fund" class="form-label">Provident Fund</label>
                                <input type="number" step="0.01" class="form-control deduction" 
                                    id="provident_fund" name="provident_fund" value="{{ old('provident_fund', 0) }}" onchange="calculateSalary()">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tax_deduction" class="form-label">Tax Deduction</label>
                                <input type="number" step="0.01" class="form-control deduction" 
                                    id="tax_deduction" name="tax_deduction" value="{{ old('tax_deduction', 0) }}" onchange="calculateSalary()">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="other_deduction" class="form-label">Other Deduction</label>
                                <input type="number" step="0.01" class="form-control deduction" 
                                    id="other_deduction" name="other_deduction" value="{{ old('other_deduction', 0) }}" onchange="calculateSalary()">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Panel -->
            <div class="col-md-4">
                <!-- Salary Summary -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-calculator"></i> Salary Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Basic Salary:</span>
                            <strong id="display_basic">৳0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Allowances:</span>
                            <strong class="text-info" id="display_allowances">৳0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Deductions:</span>
                            <strong class="text-danger" id="display_deductions">৳0.00</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <h6>Gross Salary:</h6>
                            <h6 class="text-info" id="display_gross">৳0.00</h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h5>Net Salary:</h5>
                            <h5 class="text-success" id="display_net">৳0.00</h5>
                        </div>
                        
                        <input type="hidden" name="gross_salary" id="gross_salary" value="0">
                        <input type="hidden" name="net_salary" id="net_salary" value="0">
                    </div>
                </div>

                <!-- Status -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-cog"></i> Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <strong>Active Status</strong>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-save"></i> Create Salary Structure
                        </button>
                        <a href="{{ route('payroll.salary-structures.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Update department and designation when employee is selected
document.getElementById('employee_id').addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    document.getElementById('display_department').value = option.dataset.department || '';
    document.getElementById('display_designation').value = option.dataset.designation || '';
});

function calculateSalary() {
    // Get basic salary
    const basicSalary = parseFloat(document.getElementById('basic_salary').value) || 0;
    
    // Calculate total allowances
    let totalAllowances = 0;
    document.querySelectorAll('.allowance').forEach(input => {
        totalAllowances += parseFloat(input.value) || 0;
    });
    
    // Calculate total deductions
    let totalDeductions = 0;
    document.querySelectorAll('.deduction').forEach(input => {
        totalDeductions += parseFloat(input.value) || 0;
    });
    
    // Calculate gross and net salary
    const grossSalary = basicSalary + totalAllowances;
    const netSalary = grossSalary - totalDeductions;
    
    // Update display
    document.getElementById('display_basic').textContent = '৳' + basicSalary.toFixed(2);
    document.getElementById('display_allowances').textContent = '৳' + totalAllowances.toFixed(2);
    document.getElementById('display_deductions').textContent = '৳' + totalDeductions.toFixed(2);
    document.getElementById('display_gross').textContent = '৳' + grossSalary.toFixed(2);
    document.getElementById('display_net').textContent = '৳' + netSalary.toFixed(2);
    
    // Update hidden fields
    document.getElementById('gross_salary').value = grossSalary.toFixed(2);
    document.getElementById('net_salary').value = netSalary.toFixed(2);
}

// Calculate on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateSalary();
});
</script>
@endsection
