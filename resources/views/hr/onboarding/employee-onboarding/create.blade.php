@extends('layouts.app')

@section('title', 'Create Employee Onboarding - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-user-plus me-2"></i>Create Employee Onboarding</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.onboarding.employee-onboarding.index') }}">Employee Onboarding</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <form action="{{ route('hr.onboarding.employee-onboarding.store') }}" method="POST">
                @csrf

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Onboarding Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Select Employee <span class="text-danger">*</span></label>
                            <select class="form-select @error('employee_id') is-invalid @enderror" 
                                    id="employee_id" name="employee_id" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->employee_code }} - {{ $employee->full_name }} 
                                        ({{ $employee->designation->title ?? 'N/A' }}, {{ $employee->department->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Only employees without existing onboarding are shown</small>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="checklist_id" class="form-label">Onboarding Checklist <span class="text-danger">*</span></label>
                            <select class="form-select @error('checklist_id') is-invalid @enderror" 
                                    id="checklist_id" name="checklist_id" required>
                                <option value="">Select Checklist</option>
                                @foreach($checklists as $checklist)
                                    <option value="{{ $checklist->id }}" {{ old('checklist_id') == $checklist->id ? 'selected' : '' }}>
                                        {{ $checklist->name }}
                                        @if($checklist->department)
                                            - {{ $checklist->department->name }}
                                        @endif
                                        ({{ $checklist->tasks->count() }} tasks)
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Select the onboarding checklist template</small>
                            @error('checklist_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="assigned_to" class="form-label">Assign To (HR Coordinator) <span class="text-danger">*</span></label>
                            <select class="form-select @error('assigned_to') is-invalid @enderror" 
                                    id="assigned_to" name="assigned_to" required>
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">HR person responsible for coordinating this onboarding</small>
                            @error('assigned_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required>
                                <small class="text-muted">Onboarding start date</small>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="expected_completion_date" class="form-label">Expected Completion Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('expected_completion_date') is-invalid @enderror" 
                                       id="expected_completion_date" name="expected_completion_date" 
                                       value="{{ old('expected_completion_date', date('Y-m-d', strtotime('+14 days'))) }}" required>
                                <small class="text-muted">Target completion date</small>
                                @error('expected_completion_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="4">{{ old('notes') }}</textarea>
                            <small class="text-muted">Additional notes or special instructions</small>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Onboarding
                    </button>
                    <a href="{{ route('hr.onboarding.employee-onboarding.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>

        <div class="col-lg-3">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Onboarding Process</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Select new employee
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Choose appropriate checklist
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Assign HR coordinator
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Set realistic timeline
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Tasks will be auto-created from checklist
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Best Practices</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <strong>Timeline:</strong> Typically 2-4 weeks for standard roles
                        </li>
                        <li class="mb-2">
                            <strong>Coordinator:</strong> Assign HR team member familiar with role
                        </li>
                        <li class="mb-2">
                            <strong>Follow-up:</strong> Monitor progress regularly
                        </li>
                        <li class="mb-2">
                            <strong>Communication:</strong> Keep employee informed of expectations
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-calculate expected completion date based on start date
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = new Date(this.value);
    if (startDate) {
        const expectedDate = new Date(startDate);
        expectedDate.setDate(expectedDate.getDate() + 14); // Add 14 days
        
        const expectedInput = document.getElementById('expected_completion_date');
        const formattedDate = expectedDate.toISOString().split('T')[0];
        expectedInput.value = formattedDate;
    }
});

// Validate that expected completion date is after start date
document.getElementById('expected_completion_date').addEventListener('change', function() {
    const startDate = new Date(document.getElementById('start_date').value);
    const expectedDate = new Date(this.value);
    
    if (expectedDate <= startDate) {
        alert('Expected completion date must be after start date');
        this.value = '';
    }
});
</script>
@endpush
@endsection
