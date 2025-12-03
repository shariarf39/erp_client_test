@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Apply for Leave</h2>
                <a href="{{ route('hr.leaves.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Leave Application Form</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hr.leaves.store') }}" method="POST" id="leaveForm">
                        @csrf

                        <!-- Employee Selection -->
                        <div class="mb-3">
                            <label for="employee_id" class="form-label required">Employee</label>
                            <select name="employee_id" id="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" 
                                            data-department="{{ $employee->department->name ?? 'N/A' }}"
                                            {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->employee_code }} - {{ $employee->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="employeeDepartment" class="form-text"></div>
                        </div>

                        <!-- Leave Type Selection -->
                        <div class="mb-3">
                            <label for="leave_type_id" class="form-label required">Leave Type</label>
                            <select name="leave_type_id" id="leave_type_id" class="form-select @error('leave_type_id') is-invalid @enderror" required>
                                <option value="">Select Leave Type</option>
                                @foreach($leaveTypes as $leaveType)
                                    <option value="{{ $leaveType->id }}" 
                                            data-days="{{ $leaveType->days_per_year }}"
                                            data-max-consecutive="{{ $leaveType->max_consecutive_days }}"
                                            data-paid="{{ $leaveType->is_paid ? 'Yes' : 'No' }}"
                                            {{ old('leave_type_id') == $leaveType->id ? 'selected' : '' }}>
                                        {{ $leaveType->name }} ({{ $leaveType->days_per_year }} days/year)
                                    </option>
                                @endforeach
                            </select>
                            @error('leave_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="leaveTypeInfo" class="form-text"></div>
                        </div>

                        <!-- Date Range -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="from_date" class="form-label required">From Date</label>
                                <input type="date" 
                                       name="from_date" 
                                       id="from_date" 
                                       class="form-control @error('from_date') is-invalid @enderror"
                                       value="{{ old('from_date') }}"
                                       required>
                                @error('from_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="to_date" class="form-label required">To Date</label>
                                <input type="date" 
                                       name="to_date" 
                                       id="to_date" 
                                       class="form-control @error('to_date') is-invalid @enderror"
                                       value="{{ old('to_date') }}"
                                       required>
                                @error('to_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Days Calculated -->
                        <div class="mb-3">
                            <label for="days" class="form-label required">Number of Days</label>
                            <input type="number" 
                                   name="days" 
                                   id="days" 
                                   class="form-control @error('days') is-invalid @enderror"
                                   value="{{ old('days') }}"
                                   step="0.5"
                                   min="0.5"
                                   readonly
                                   required>
                            @error('days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Days will be calculated automatically based on date range</div>
                        </div>

                        <!-- Reason -->
                        <div class="mb-3">
                            <label for="reason" class="form-label required">Reason for Leave</label>
                            <textarea name="reason" 
                                      id="reason" 
                                      rows="4" 
                                      class="form-control @error('reason') is-invalid @enderror"
                                      required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status (default Pending) -->
                        <input type="hidden" name="status" value="Pending">

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('hr.leaves.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Panel -->
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Quick Tips</h5>
                </div>
                <div class="card-body">
                    <h6 class="text-primary">Leave Application Process</h6>
                    <ol class="small">
                        <li>Select the employee applying for leave</li>
                        <li>Choose the appropriate leave type</li>
                        <li>Specify the date range for your leave</li>
                        <li>Days will be calculated automatically</li>
                        <li>Provide a clear reason for your leave</li>
                        <li>Submit and wait for approval</li>
                    </ol>

                    <hr>

                    <h6 class="text-primary mt-3">Leave Status</h6>
                    <ul class="list-unstyled small">
                        <li><span class="badge bg-warning text-dark">Pending</span> - Awaiting approval</li>
                        <li><span class="badge bg-success">Approved</span> - Leave approved</li>
                        <li><span class="badge bg-danger">Rejected</span> - Leave rejected</li>
                        <li><span class="badge bg-secondary">Cancelled</span> - Leave cancelled</li>
                    </ul>

                    <hr>

                    <h6 class="text-primary mt-3">Important Notes</h6>
                    <ul class="small">
                        <li>Applications are pending by default</li>
                        <li>Check your leave balance before applying</li>
                        <li>Provide advance notice when possible</li>
                        <li>Some leave types may have maximum consecutive days</li>
                        <li>Contact HR for questions about leave policies</li>
                    </ul>
                </div>
            </div>

            <!-- Leave Type Details -->
            <div class="card mt-3" id="leaveTypeDetailsCard" style="display: none;">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Selected Leave Type Details</h6>
                </div>
                <div class="card-body">
                    <div id="leaveTypeDetails"></div>
                </div>
            </div>
        </div>
    </div>
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

    .form-label {
        font-weight: 600;
        color: #495057;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const employeeSelect = document.getElementById('employee_id');
    const leaveTypeSelect = document.getElementById('leave_type_id');
    const fromDateInput = document.getElementById('from_date');
    const toDateInput = document.getElementById('to_date');
    const daysInput = document.getElementById('days');
    const employeeDepartmentDiv = document.getElementById('employeeDepartment');
    const leaveTypeInfoDiv = document.getElementById('leaveTypeInfo');
    const leaveTypeDetailsCard = document.getElementById('leaveTypeDetailsCard');
    const leaveTypeDetailsDiv = document.getElementById('leaveTypeDetails');

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    fromDateInput.setAttribute('min', today);
    toDateInput.setAttribute('min', today);

    // Show employee department
    employeeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const department = selectedOption.getAttribute('data-department');
        
        if (department && department !== 'N/A') {
            employeeDepartmentDiv.innerHTML = '<i class="bi bi-building me-1"></i>Department: <strong>' + department + '</strong>';
        } else {
            employeeDepartmentDiv.innerHTML = '';
        }
    });

    // Show leave type details
    leaveTypeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const daysPerYear = selectedOption.getAttribute('data-days');
        const maxConsecutive = selectedOption.getAttribute('data-max-consecutive');
        const isPaid = selectedOption.getAttribute('data-paid');
        
        if (daysPerYear) {
            leaveTypeInfoDiv.innerHTML = '<i class="bi bi-info-circle me-1"></i>Annual allocation: <strong>' + daysPerYear + ' days</strong>';
            
            let detailsHTML = '<table class="table table-sm">';
            detailsHTML += '<tr><td><strong>Days per Year:</strong></td><td>' + daysPerYear + ' days</td></tr>';
            detailsHTML += '<tr><td><strong>Max Consecutive:</strong></td><td>' + (maxConsecutive || 'No limit') + (maxConsecutive ? ' days' : '') + '</td></tr>';
            detailsHTML += '<tr><td><strong>Paid Leave:</strong></td><td>' + isPaid + '</td></tr>';
            detailsHTML += '</table>';
            
            leaveTypeDetailsDiv.innerHTML = detailsHTML;
            leaveTypeDetailsCard.style.display = 'block';
        } else {
            leaveTypeInfoDiv.innerHTML = '';
            leaveTypeDetailsCard.style.display = 'none';
        }
    });

    // Calculate days when dates change
    function calculateDays() {
        const fromDate = new Date(fromDateInput.value);
        const toDate = new Date(toDateInput.value);
        
        if (fromDateInput.value && toDateInput.value) {
            if (toDate < fromDate) {
                toDateInput.setCustomValidity('End date must be after start date');
                daysInput.value = '';
                return;
            } else {
                toDateInput.setCustomValidity('');
            }
            
            // Calculate difference in days (inclusive)
            const timeDiff = toDate.getTime() - fromDate.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
            daysInput.value = daysDiff;
            
            // Check against max consecutive days
            const leaveTypeOption = leaveTypeSelect.options[leaveTypeSelect.selectedIndex];
            const maxConsecutive = leaveTypeOption.getAttribute('data-max-consecutive');
            
            if (maxConsecutive && daysDiff > parseInt(maxConsecutive)) {
                alert('Warning: This leave type allows maximum ' + maxConsecutive + ' consecutive days. You are requesting ' + daysDiff + ' days.');
            }
        }
    }

    fromDateInput.addEventListener('change', function() {
        // Update minimum date for to_date
        toDateInput.setAttribute('min', this.value);
        calculateDays();
    });

    toDateInput.addEventListener('change', calculateDays);

    // Trigger department display if employee is preselected
    if (employeeSelect.value) {
        employeeSelect.dispatchEvent(new Event('change'));
    }

    // Trigger leave type details if preselected
    if (leaveTypeSelect.value) {
        leaveTypeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
