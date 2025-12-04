@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">Edit Payroll</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('payroll.payroll.index') }}">Payroll</a></li>
                            <li class="breadcrumb-item active">Edit #{{ $payroll->id }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('payroll.payroll.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Validation Error!</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Edit Form -->
    <form action="{{ route('payroll.payroll.update', $payroll) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <!-- Payroll Information Card -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-calculator me-2"></i>Payroll Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label"><strong>Employee:</strong></label>
                                <p class="form-control-plaintext">{{ $payroll->employee->full_name }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><strong>Month/Year:</strong></label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-info">
                                        {{ date('F', mktime(0, 0, 0, $payroll->month, 1)) }} {{ $payroll->year }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><strong>Employee Code:</strong></label>
                                <p class="form-control-plaintext">{{ $payroll->employee->employee_code }}</p>
                            </div>
                        </div>

                        <hr>

                        <!-- Attendance Details (Read-Only) -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label"><strong>Working Days:</strong></label>
                                <p class="form-control-plaintext">{{ $payroll->working_days }}</p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"><strong>Present Days:</strong></label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-success">{{ $payroll->present_days }}</span>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"><strong>Absent Days:</strong></label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-danger">{{ $payroll->absent_days }}</span>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"><strong>Leave Days:</strong></label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-warning text-dark">{{ $payroll->leave_days }}</span>
                                </p>
                            </div>
                        </div>

                        <hr>

                        <!-- Salary Details (Read-Only) -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label"><strong>Basic Salary:</strong></label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="text" class="form-control" value="{{ number_format($payroll->basic_salary, 2) }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Editable Fields -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="overtime_hours" class="form-label">Overtime Hours</label>
                                <input type="number" 
                                       name="overtime_hours" 
                                       id="overtime_hours" 
                                       class="form-control @error('overtime_hours') is-invalid @enderror" 
                                       value="{{ old('overtime_hours', $payroll->overtime_hours) }}"
                                       step="0.01"
                                       min="0">
                                @error('overtime_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="overtime_amount" class="form-label">Overtime Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" 
                                           name="overtime_amount" 
                                           id="overtime_amount" 
                                           class="form-control @error('overtime_amount') is-invalid @enderror" 
                                           value="{{ old('overtime_amount', $payroll->overtime_amount) }}"
                                           step="0.01"
                                           min="0">
                                </div>
                                @error('overtime_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="total_allowance" class="form-label required">Total Allowance</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" 
                                           name="total_allowance" 
                                           id="total_allowance" 
                                           class="form-control @error('total_allowance') is-invalid @enderror" 
                                           value="{{ old('total_allowance', $payroll->total_allowance) }}"
                                           step="0.01"
                                           min="0"
                                           required>
                                </div>
                                @error('total_allowance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="total_deduction" class="form-label required">Total Deduction</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" 
                                           name="total_deduction" 
                                           id="total_deduction" 
                                           class="form-control @error('total_deduction') is-invalid @enderror" 
                                           value="{{ old('total_deduction', $payroll->total_deduction) }}"
                                           step="0.01"
                                           min="0"
                                           required>
                                </div>
                                @error('total_deduction')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea name="remarks" 
                                          id="remarks" 
                                          class="form-control @error('remarks') is-invalid @enderror" 
                                          rows="3"
                                          maxlength="500">{{ old('remarks', $payroll->remarks) }}</textarea>
                                @error('remarks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Maximum 500 characters</div>
                            </div>
                        </div>

                        <!-- Calculated Totals (Auto-calculated via JavaScript) -->
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <strong>Gross Salary:</strong>
                                    <h4 class="mb-0">৳<span id="calculated_gross">{{ number_format($payroll->gross_salary, 2) }}</span></h4>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-success">
                                    <strong>Net Salary:</strong>
                                    <h4 class="mb-0">৳<span id="calculated_net">{{ number_format($payroll->net_salary, 2) }}</span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Status Card -->
                <div class="card mb-4">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Status & Payment</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label required">Status</label>
                            <select name="status" 
                                    id="status" 
                                    class="form-select @error('status') is-invalid @enderror" 
                                    required>
                                <option value="Draft" {{ old('status', $payroll->status) === 'Draft' ? 'selected' : '' }}>Draft</option>
                                <option value="Processed" {{ old('status', $payroll->status) === 'Processed' ? 'selected' : '' }}>Processed</option>
                                <option value="Paid" {{ old('status', $payroll->status) === 'Paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="paid_at_field" style="{{ old('status', $payroll->status) === 'Paid' ? '' : 'display: none;' }}">
                            <label for="paid_at" class="form-label">Paid Date</label>
                            <input type="date" 
                                   name="paid_at" 
                                   id="paid_at" 
                                   class="form-control @error('paid_at') is-invalid @enderror" 
                                   value="{{ old('paid_at', $payroll->paid_at ? $payroll->paid_at->format('Y-m-d') : '') }}">
                            @error('paid_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <i class="bi bi-info-circle me-1"></i>
                                <strong>Note:</strong> Only payrolls with status 'Processed' can be edited. Paid payrolls cannot be modified.
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-check-circle me-2"></i>Update Payroll
                        </button>
                        <a href="{{ route('payroll.payroll.show', $payroll) }}" class="btn btn-secondary w-100">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .required::after {
        content: " *";
        color: red;
    }
    
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
        border-radius: 0.5rem;
    }
    
    .form-control-plaintext {
        padding-top: 0.375rem;
        padding-bottom: 0.375rem;
        margin-bottom: 0;
        font-size: inherit;
        line-height: 1.5;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const paidAtField = document.getElementById('paid_at_field');
    const basicSalary = parseFloat({{ $payroll->basic_salary }});
    
    // Show/hide paid_at field based on status
    statusSelect.addEventListener('change', function() {
        if (this.value === 'Paid') {
            paidAtField.style.display = 'block';
            // Set today's date if empty
            const paidAtInput = document.getElementById('paid_at');
            if (!paidAtInput.value) {
                paidAtInput.value = new Date().toISOString().split('T')[0];
            }
        } else {
            paidAtField.style.display = 'none';
        }
    });
    
    // Auto-calculate gross and net salary
    const overtimeAmountInput = document.getElementById('overtime_amount');
    const totalAllowanceInput = document.getElementById('total_allowance');
    const totalDeductionInput = document.getElementById('total_deduction');
    
    function calculateSalary() {
        const overtimeAmount = parseFloat(overtimeAmountInput.value) || 0;
        const totalAllowance = parseFloat(totalAllowanceInput.value) || 0;
        const totalDeduction = parseFloat(totalDeductionInput.value) || 0;
        
        const grossSalary = basicSalary + totalAllowance;
        const netSalary = grossSalary - totalDeduction + overtimeAmount;
        
        document.getElementById('calculated_gross').textContent = grossSalary.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        document.getElementById('calculated_net').textContent = netSalary.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    
    // Add event listeners for auto-calculation
    overtimeAmountInput.addEventListener('input', calculateSalary);
    totalAllowanceInput.addEventListener('input', calculateSalary);
    totalDeductionInput.addEventListener('input', calculateSalary);
    
    // Calculate on page load
    calculateSalary();
});
</script>
@endpush
