@extends('layouts.app')

@section('title', 'Edit Employee Onboarding - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Employee Onboarding</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.onboarding.employee-onboarding.index') }}">Employee Onboarding</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <form action="{{ route('hr.onboarding.employee-onboarding.update', $employeeOnboarding) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Onboarding Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee</label>
                            <input type="text" class="form-control" value="{{ $employeeOnboarding->employee->full_name }} ({{ $employeeOnboarding->employee->employee_code }})" readonly>
                            <small class="text-muted">Employee cannot be changed after onboarding is created</small>
                        </div>

                        <div class="mb-3">
                            <label for="checklist_id" class="form-label">Onboarding Checklist</label>
                            <input type="text" class="form-control" value="{{ $employeeOnboarding->checklist->name }}" readonly>
                            <small class="text-muted">Checklist cannot be changed after onboarding is created</small>
                        </div>

                        <div class="mb-3">
                            <label for="assigned_to" class="form-label">Assign To (HR Coordinator) <span class="text-danger">*</span></label>
                            <select class="form-select @error('assigned_to') is-invalid @enderror" 
                                    id="assigned_to" name="assigned_to" required>
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" 
                                            {{ old('assigned_to', $employeeOnboarding->assigned_to) == $user->id ? 'selected' : '' }}>
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
                            <div class="col-md-4">
                                <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" 
                                       value="{{ old('start_date', $employeeOnboarding->start_date->format('Y-m-d')) }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="expected_completion_date" class="form-label">Expected Completion <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('expected_completion_date') is-invalid @enderror" 
                                       id="expected_completion_date" name="expected_completion_date" 
                                       value="{{ old('expected_completion_date', $employeeOnboarding->expected_completion_date->format('Y-m-d')) }}" required>
                                @error('expected_completion_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="actual_completion_date" class="form-label">Actual Completion</label>
                                <input type="date" class="form-control @error('actual_completion_date') is-invalid @enderror" 
                                       id="actual_completion_date" name="actual_completion_date" 
                                       value="{{ old('actual_completion_date', $employeeOnboarding->actual_completion_date ? $employeeOnboarding->actual_completion_date->format('Y-m-d') : '') }}">
                                <small class="text-muted">Set when onboarding is completed</small>
                                @error('actual_completion_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="Not Started" {{ old('status', $employeeOnboarding->status) == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                                <option value="In Progress" {{ old('status', $employeeOnboarding->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Completed" {{ old('status', $employeeOnboarding->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="On Hold" {{ old('status', $employeeOnboarding->status) == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="4">{{ old('notes', $employeeOnboarding->notes) }}</textarea>
                            <small class="text-muted">Additional notes or special instructions</small>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Progress Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted">Completion Progress</h6>
                                <div class="progress" style="height: 30px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ $employeeOnboarding->completion_percentage }}%">
                                        {{ number_format($employeeOnboarding->completion_percentage, 0) }}%
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">Task Summary</h6>
                                <p class="mb-0">
                                    <span class="badge bg-success">{{ $employeeOnboarding->taskProgress->where('status', 'Completed')->count() }} Completed</span>
                                    <span class="badge bg-info">{{ $employeeOnboarding->taskProgress->where('status', 'In Progress')->count() }} In Progress</span>
                                    <span class="badge bg-secondary">{{ $employeeOnboarding->taskProgress->where('status', 'Pending')->count() }} Pending</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Onboarding
                    </button>
                    <a href="{{ route('hr.onboarding.employee-onboarding.show', $employeeOnboarding) }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>

        <div class="col-lg-3">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Edit Information</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Update onboarding timeline
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Change status as progress is made
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Reassign HR coordinator if needed
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            Employee and checklist cannot be changed
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Progress is auto-calculated from tasks
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-list me-2"></i>Status Guide</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Not Started:</strong>
                        <small class="text-muted d-block">Onboarding hasn't begun yet</small>
                    </div>
                    <div class="mb-2">
                        <strong>In Progress:</strong>
                        <small class="text-muted d-block">Tasks are being completed</small>
                    </div>
                    <div class="mb-2">
                        <strong>Completed:</strong>
                        <small class="text-muted d-block">All tasks finished, set actual date</small>
                    </div>
                    <div>
                        <strong>On Hold:</strong>
                        <small class="text-muted d-block">Temporarily paused</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-set actual completion date when status is set to Completed
document.getElementById('status').addEventListener('change', function() {
    const actualDateInput = document.getElementById('actual_completion_date');
    
    if (this.value === 'Completed' && !actualDateInput.value) {
        actualDateInput.value = new Date().toISOString().split('T')[0];
    }
});

// Validate dates
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
