@extends('layouts.app')

@section('title', 'Enroll Employee in Training - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-user-plus me-2"></i>Enroll Employee in Training</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.training.enrollments.index') }}">Training Enrollments</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('hr.training.enrollments.store') }}" method="POST" id="enrollmentForm">
                @csrf

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Enrollment Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="training_program_id" class="form-label">Training Program <span class="text-danger">*</span></label>
                            <select class="form-select @error('training_program_id') is-invalid @enderror" 
                                    id="training_program_id" name="training_program_id" required>
                                <option value="">Select Training Program</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}" 
                                            {{ old('training_program_id') == $program->id ? 'selected' : '' }}
                                            data-code="{{ $program->program_code }}"
                                            data-category="{{ $program->category }}"
                                            data-type="{{ $program->training_type }}"
                                            data-start="{{ $program->start_date }}"
                                            data-end="{{ $program->end_date }}"
                                            data-venue="{{ $program->venue }}"
                                            data-capacity="{{ $program->max_participants }}"
                                            data-status="{{ $program->status }}">
                                        {{ $program->title }} ({{ $program->program_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('training_program_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Only programs open for enrollment or planned are shown</small>
                        </div>

                        <div id="programDetails" class="alert alert-info d-none mb-3">
                            <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Program Details</h6>
                            <div class="row small">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Code:</strong> <span id="detailCode">-</span></p>
                                    <p class="mb-1"><strong>Category:</strong> <span id="detailCategory">-</span></p>
                                    <p class="mb-1"><strong>Type:</strong> <span id="detailType">-</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Duration:</strong> <span id="detailDuration">-</span></p>
                                    <p class="mb-1"><strong>Venue:</strong> <span id="detailVenue">-</span></p>
                                    <p class="mb-1"><strong>Status:</strong> <span id="detailStatus" class="badge bg-info">-</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                            <select class="form-select @error('employee_id') is-invalid @enderror" 
                                    id="employee_id" name="employee_id" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" 
                                            {{ old('employee_id') == $employee->id ? 'selected' : '' }}
                                            data-code="{{ $employee->employee_id }}"
                                            data-department="{{ $employee->department->name ?? 'N/A' }}"
                                            data-designation="{{ $employee->designation->title ?? 'N/A' }}">
                                        {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Only active employees are shown</small>
                        </div>

                        <div id="employeeDetails" class="alert alert-secondary d-none mb-3">
                            <h6 class="mb-2"><i class="fas fa-user me-2"></i>Employee Details</h6>
                            <div class="row small">
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Employee ID:</strong> <span id="empCode">-</span></p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Department:</strong> <span id="empDepartment">-</span></p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Designation:</strong> <span id="empDesignation">-</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="enrollment_date" class="form-label">Enrollment Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('enrollment_date') is-invalid @enderror" 
                                       id="enrollment_date" name="enrollment_date" 
                                       value="{{ old('enrollment_date', date('Y-m-d')) }}" required>
                                @error('enrollment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="Enrolled" {{ old('status', 'Enrolled') == 'Enrolled' ? 'selected' : '' }}>Enrolled</option>
                                    <option value="Attended" {{ old('status') == 'Attended' ? 'selected' : '' }}>Attended</option>
                                    <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="Failed" {{ old('status') == 'Failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="Absent" {{ old('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input @error('is_mandatory') is-invalid @enderror" 
                                       id="is_mandatory" name="is_mandatory" value="1" 
                                       {{ old('is_mandatory') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_mandatory">
                                    <strong>Mandatory Training</strong>
                                    <small class="text-muted d-block">Check if this training is required for the employee</small>
                                </label>
                                @error('is_mandatory')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            <small class="text-muted">Additional information or special requirements</small>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enroll Employee
                    </button>
                    <a href="{{ route('hr.training.enrollments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>

        <div class="col-lg-3">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Guidelines</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Select an active training program
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Choose active employee only
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Set enrollment date accurately
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Mark mandatory trainings
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            Check program capacity before enrolling
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Status Options</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <span class="badge bg-primary">Enrolled</span>
                            <span class="d-block mt-1 text-muted">Initial enrollment</span>
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-warning">Waitlisted</span>
                            <span class="d-block mt-1 text-muted">Program at capacity</span>
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-success">Approved</span>
                            <span class="d-block mt-1 text-muted">Enrollment confirmed</span>
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-danger">Rejected</span>
                            <span class="d-block mt-1 text-muted">Not approved</span>
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-info">Completed</span>
                            <span class="d-block mt-1 text-muted">Training finished</span>
                        </li>
                        <li>
                            <span class="badge bg-secondary">Cancelled</span>
                            <span class="d-block mt-1 text-muted">Enrollment cancelled</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const programSelect = document.getElementById('training_program_id');
    const employeeSelect = document.getElementById('employee_id');
    const programDetails = document.getElementById('programDetails');
    const employeeDetails = document.getElementById('employeeDetails');

    // Show program details when selected
    programSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (this.value) {
            document.getElementById('detailCode').textContent = selected.dataset.code || '-';
            document.getElementById('detailCategory').textContent = selected.dataset.category || '-';
            document.getElementById('detailType').textContent = selected.dataset.type || '-';
            document.getElementById('detailVenue').textContent = selected.dataset.venue || '-';
            document.getElementById('detailStatus').textContent = selected.dataset.status || '-';
            
            const startDate = selected.dataset.start;
            const endDate = selected.dataset.end;
            let duration = '-';
            if (startDate && endDate) {
                duration = startDate + ' to ' + endDate;
            } else if (startDate) {
                duration = 'From ' + startDate;
            }
            document.getElementById('detailDuration').textContent = duration;
            
            programDetails.classList.remove('d-none');
        } else {
            programDetails.classList.add('d-none');
        }
    });

    // Show employee details when selected
    employeeSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (this.value) {
            document.getElementById('empCode').textContent = selected.dataset.code || '-';
            document.getElementById('empDepartment').textContent = selected.dataset.department || '-';
            document.getElementById('empDesignation').textContent = selected.dataset.designation || '-';
            employeeDetails.classList.remove('d-none');
        } else {
            employeeDetails.classList.add('d-none');
        }
    });

    // Trigger change if there's an old value
    if (programSelect.value) {
        programSelect.dispatchEvent(new Event('change'));
    }
    if (employeeSelect.value) {
        employeeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
