@extends('layouts.app')

@section('title', 'Edit Leave Application')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Edit Leave Application</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">HR</li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.leaves.index') }}">Leave Applications</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('hr.leaves.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <a href="{{ route('hr.leaves.show', $leaveApplication->id) }}" class="btn btn-info">
                <i class="fas fa-eye"></i> View Details
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('hr.leaves.update', $leaveApplication->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Leave Application Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                                <select class="form-select @error('employee_id') is-invalid @enderror" 
                                        id="employee_id" name="employee_id" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" 
                                                {{ old('employee_id', $leaveApplication->employee_id) == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->employee_code }} - {{ $employee->full_name }}
                                            ({{ $employee->department->name ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="leave_type_id" class="form-label">Leave Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('leave_type_id') is-invalid @enderror" 
                                        id="leave_type_id" name="leave_type_id" required>
                                    <option value="">Select Leave Type</option>
                                    @foreach($leaveTypes as $type)
                                        <option value="{{ $type->id }}" 
                                                {{ old('leave_type_id', $leaveApplication->leave_type_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }} ({{ $type->days_per_year }} days/year)
                                        </option>
                                    @endforeach
                                </select>
                                @error('leave_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="from_date" class="form-label">From Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('from_date') is-invalid @enderror" 
                                       id="from_date" 
                                       name="from_date" 
                                       value="{{ old('from_date', $leaveApplication->from_date->format('Y-m-d')) }}" 
                                       required
                                       onchange="calculateDays()">
                                @error('from_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="to_date" class="form-label">To Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('to_date') is-invalid @enderror" 
                                       id="to_date" 
                                       name="to_date" 
                                       value="{{ old('to_date', $leaveApplication->to_date->format('Y-m-d')) }}" 
                                       required
                                       onchange="calculateDays()">
                                @error('to_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="days" class="form-label">Number of Days <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('days') is-invalid @enderror" 
                                       id="days" 
                                       name="days" 
                                       value="{{ old('days', $leaveApplication->days) }}" 
                                       step="0.5" 
                                       min="0.5" 
                                       required
                                       readonly>
                                @error('days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Auto-calculated based on dates</small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('reason') is-invalid @enderror" 
                                          id="reason" 
                                          name="reason" 
                                          rows="4" 
                                          required
                                          placeholder="Enter reason for leave application...">{{ old('reason', $leaveApplication->reason) }}</textarea>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Leave Application
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function calculateDays() {
    const fromDate = document.getElementById('from_date').value;
    const toDate = document.getElementById('to_date').value;
    
    if (fromDate && toDate) {
        const from = new Date(fromDate);
        const to = new Date(toDate);
        
        if (to >= from) {
            const diffTime = Math.abs(to - from);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            document.getElementById('days').value = diffDays;
        } else {
            document.getElementById('days').value = '';
        }
    }
}
</script>

<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #6f42c1;
        box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
    }
    
    .text-danger {
        font-weight: 700;
    }
</style>
@endsection
