@extends('layouts.app')

@section('title', 'Update Training Enrollment - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-edit me-2"></i>Update Training Enrollment</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.training.enrollments.index') }}">Training Enrollments</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.training.enrollments.show', $enrollment->id) }}">Details</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <form action="{{ route('hr.training.enrollments.update', $enrollment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Enrollment Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong><i class="fas fa-user me-2 text-primary"></i>Employee:</strong><br>
                                    <span class="ms-4">{{ $enrollment->employee->first_name ?? '' }} {{ $enrollment->employee->last_name ?? '' }}</span>
                                    <small class="d-block ms-4 text-muted">({{ $enrollment->employee->employee_id ?? 'N/A' }})</small>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong><i class="fas fa-graduation-cap me-2 text-success"></i>Training Program:</strong><br>
                                    <span class="ms-4">{{ $enrollment->trainingProgram->title ?? 'N/A' }}</span>
                                    <small class="d-block ms-4 text-muted">({{ $enrollment->trainingProgram->program_code ?? 'N/A' }})</small>
                                </p>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> Employee and program cannot be changed. To enroll in a different program, create a new enrollment.
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Enrollment Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="enrollment_status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('enrollment_status') is-invalid @enderror" 
                                    id="enrollment_status" name="enrollment_status" required>
                                <option value="Enrolled" {{ old('enrollment_status', $enrollment->enrollment_status) == 'Enrolled' ? 'selected' : '' }}>Enrolled</option>
                                <option value="Waitlisted" {{ old('enrollment_status', $enrollment->enrollment_status) == 'Waitlisted' ? 'selected' : '' }}>Waitlisted</option>
                                <option value="Approved" {{ old('enrollment_status', $enrollment->enrollment_status) == 'Approved' ? 'selected' : '' }}>Approved</option>
                                <option value="Rejected" {{ old('enrollment_status', $enrollment->enrollment_status) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="Completed" {{ old('enrollment_status', $enrollment->enrollment_status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Cancelled" {{ old('enrollment_status', $enrollment->enrollment_status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('enrollment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Performance & Completion</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="attendance_percentage" class="form-label">Attendance Percentage</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" max="100" 
                                           class="form-control @error('attendance_percentage') is-invalid @enderror" 
                                           id="attendance_percentage" name="attendance_percentage" 
                                           value="{{ old('attendance_percentage', $enrollment->attendance_percentage) }}">
                                    <span class="input-group-text">%</span>
                                    @error('attendance_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="assessment_score" class="form-label">Assessment Score</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" max="100" 
                                           class="form-control @error('assessment_score') is-invalid @enderror" 
                                           id="assessment_score" name="assessment_score" 
                                           value="{{ old('assessment_score', $enrollment->assessment_score) }}">
                                    <span class="input-group-text">%</span>
                                    @error('assessment_score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="completion_date" class="form-label">Completion Date</label>
                                <input type="date" class="form-control @error('completion_date') is-invalid @enderror" 
                                       id="completion_date" name="completion_date" 
                                       value="{{ old('completion_date', $enrollment->completion_date) }}">
                                @error('completion_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label d-block">Result</label>
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="passed" id="passed_yes" value="1" 
                                           {{ old('passed', $enrollment->passed) == '1' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success" for="passed_yes">
                                        <i class="fas fa-check-circle me-1"></i>Passed
                                    </label>

                                    <input type="radio" class="btn-check" name="passed" id="passed_no" value="0" 
                                           {{ old('passed', $enrollment->passed) === '0' || old('passed', $enrollment->passed) === 0 ? 'checked' : '' }}>
                                    <label class="btn btn-outline-danger" for="passed_no">
                                        <i class="fas fa-times-circle me-1"></i>Failed
                                    </label>

                                    <input type="radio" class="btn-check" name="passed" id="passed_null" value="" 
                                           {{ old('passed', $enrollment->passed) === null ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary" for="passed_null">
                                        <i class="fas fa-question-circle me-1"></i>Not Set
                                    </label>
                                </div>
                                @error('passed')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input @error('certificate_issued') is-invalid @enderror" 
                                           id="certificate_issued" name="certificate_issued" value="1" 
                                           {{ old('certificate_issued', $enrollment->certificate_issued) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="certificate_issued">
                                        <strong>Certificate Issued</strong>
                                    </label>
                                    @error('certificate_issued')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="certificate_number" class="form-label">Certificate Number</label>
                                <input type="text" class="form-control @error('certificate_number') is-invalid @enderror" 
                                       id="certificate_number" name="certificate_number" 
                                       value="{{ old('certificate_number', $enrollment->certificate_number) }}"
                                       placeholder="e.g., CERT-2025-001">
                                @error('certificate_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="feedback" class="form-label">Training Feedback</label>
                            <textarea class="form-control @error('feedback') is-invalid @enderror" 
                                      id="feedback" name="feedback" rows="3">{{ old('feedback', $enrollment->feedback) }}</textarea>
                            <small class="text-muted">Employee's feedback about the training program</small>
                            @error('feedback')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Additional Notes</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes', $enrollment->notes) }}</textarea>
                            <small class="text-muted">Administrative notes or special remarks</small>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Enrollment
                    </button>
                    <a href="{{ route('hr.training.enrollments.show', $enrollment->id) }}" class="btn btn-secondary">
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
                            Update status as training progresses
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Record attendance and scores accurately
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Set completion date when finished
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Issue certificate for passed participants
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            Cannot delete after marking completed
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Current Info</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2 small">
                        <strong>Enrolled On:</strong><br>
                        {{ $enrollment->enrollment_date ? \Carbon\Carbon::parse($enrollment->enrollment_date)->format('d M Y') : 'N/A' }}
                    </p>
                    @if($enrollment->approved_by)
                    <p class="mb-2 small">
                        <strong>Approved By:</strong><br>
                        {{ $enrollment->approvedBy->name ?? 'N/A' }}
                    </p>
                    @endif
                    @if($enrollment->is_mandatory)
                    <div class="alert alert-danger small mb-0 mt-2">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Mandatory Training
                    </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Passing Criteria</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <strong>Attendance:</strong> Usually ≥ 75%
                        </li>
                        <li class="mb-2">
                            <strong>Assessment:</strong> Usually ≥ 70%
                        </li>
                        <li class="mb-2">
                            <strong>Certificate:</strong> Only for passed participants
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const certificateCheckbox = document.getElementById('certificate_issued');
    const certificateNumber = document.getElementById('certificate_number');

    // Enable/disable certificate number based on checkbox
    function toggleCertificateNumber() {
        if (certificateCheckbox.checked) {
            certificateNumber.removeAttribute('disabled');
        } else {
            certificateNumber.setAttribute('disabled', 'disabled');
        }
    }

    certificateCheckbox.addEventListener('change', toggleCertificateNumber);
    toggleCertificateNumber(); // Initial state
});
</script>
@endsection
