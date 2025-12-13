@extends('layouts.app')

@section('title', 'Enrollment Details - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Training Enrollment Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.training.enrollments.index') }}">Training Enrollments</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Enrollment Information</h5>
                        @php
                            $statusColors = [
                                'Enrolled' => 'primary',
                                'Waitlisted' => 'warning',
                                'Approved' => 'success',
                                'Rejected' => 'danger',
                                'Completed' => 'info',
                                'Cancelled' => 'secondary'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$enrollment->status] ?? 'secondary' }} fs-6">
                            {{ $enrollment->status }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Employee Information</h6>
                            <p class="mb-2">
                                <strong>Name:</strong><br>
                                <span class="ms-3">{{ $enrollment->employee->first_name ?? '' }} {{ $enrollment->employee->last_name ?? '' }}</span>
                            </p>
                            <p class="mb-2">
                                <strong>Employee ID:</strong><br>
                                <span class="ms-3">{{ $enrollment->employee->employee_id ?? 'N/A' }}</span>
                            </p>
                            <p class="mb-2">
                                <strong>Department:</strong><br>
                                <span class="ms-3">{{ $enrollment->employee->department->name ?? 'N/A' }}</span>
                            </p>
                            <p class="mb-2">
                                <strong>Designation:</strong><br>
                                <span class="ms-3">{{ $enrollment->employee->designation->title ?? 'N/A' }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success mb-3"><i class="fas fa-graduation-cap me-2"></i>Training Program</h6>
                            <p class="mb-2">
                                <strong>Program:</strong><br>
                                <span class="ms-3">{{ $enrollment->trainingProgram->title ?? 'N/A' }}</span>
                            </p>
                            <p class="mb-2">
                                <strong>Program Code:</strong><br>
                                <span class="ms-3">{{ $enrollment->trainingProgram->program_code ?? 'N/A' }}</span>
                            </p>
                            <p class="mb-2">
                                <strong>Category:</strong><br>
                                <span class="ms-3">{{ $enrollment->trainingProgram->category ?? 'N/A' }}</span>
                            </p>
                            <p class="mb-2">
                                <strong>Training Type:</strong><br>
                                <span class="ms-3">{{ $enrollment->trainingProgram->training_type ?? 'N/A' }}</span>
                            </p>
                            @if($enrollment->trainingProgram->venue)
                            <p class="mb-2">
                                <strong>Venue:</strong><br>
                                <span class="ms-3">{{ $enrollment->trainingProgram->venue }}</span>
                            </p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-calendar-plus me-2 text-primary"></i>Enrollment Date:</strong><br>
                                <span class="ms-4">{{ $enrollment->enrollment_date ? \Carbon\Carbon::parse($enrollment->enrollment_date)->format('d M Y') : 'N/A' }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-tasks me-2 text-primary"></i>Mandatory Training:</strong><br>
                                <span class="ms-4">
                                    @if($enrollment->is_mandatory)
                                        <span class="badge bg-danger">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>

                    @if($enrollment->approved_by && $enrollment->approved_date)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-user-check me-2 text-success"></i>Approved By:</strong><br>
                                <span class="ms-4">{{ $enrollment->approvedBy->name ?? 'N/A' }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-calendar-check me-2 text-success"></i>Approved Date:</strong><br>
                                <span class="ms-4">{{ \Carbon\Carbon::parse($enrollment->approved_date)->format('d M Y') }}</span>
                            </p>
                        </div>
                    </div>
                    @endif

                    @if($enrollment->notes)
                    <div class="mb-3">
                        <p class="mb-2">
                            <strong><i class="fas fa-sticky-note me-2 text-warning"></i>Notes:</strong>
                        </p>
                        <p class="ms-4 text-muted">{{ $enrollment->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            @if($enrollment->status == 'Completed' || $enrollment->attendance_percentage || $enrollment->assessment_score)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Performance & Completion</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        @if($enrollment->attendance_percentage !== null)
                        <div class="col-md-4">
                            <p class="mb-2"><strong>Attendance:</strong></p>
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar {{ $enrollment->attendance_percentage >= 75 ? 'bg-success' : 'bg-warning' }}" 
                                     role="progressbar" 
                                     style="width: {{ $enrollment->attendance_percentage }}%"
                                     aria-valuenow="{{ $enrollment->attendance_percentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ number_format($enrollment->attendance_percentage, 1) }}%
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($enrollment->assessment_score !== null)
                        <div class="col-md-4">
                            <p class="mb-2"><strong>Assessment Score:</strong></p>
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar {{ $enrollment->assessment_score >= 70 ? 'bg-success' : ($enrollment->assessment_score >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                     role="progressbar" 
                                     style="width: {{ $enrollment->assessment_score }}%"
                                     aria-valuenow="{{ $enrollment->assessment_score }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ number_format($enrollment->assessment_score, 1) }}%
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-4">
                            <p class="mb-2"><strong>Result:</strong></p>
                            @if($enrollment->passed !== null)
                                @if($enrollment->passed)
                                    <span class="badge bg-success fs-5"><i class="fas fa-check-circle me-1"></i>Passed</span>
                                @else
                                    <span class="badge bg-danger fs-5"><i class="fas fa-times-circle me-1"></i>Failed</span>
                                @endif
                            @else
                                <span class="text-muted">Not evaluated yet</span>
                            @endif
                        </div>
                    </div>

                    @if($enrollment->completion_date)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-calendar-check me-2 text-success"></i>Completion Date:</strong><br>
                                <span class="ms-4">{{ \Carbon\Carbon::parse($enrollment->completion_date)->format('d M Y') }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            @if($enrollment->certificate_issued)
                            <p class="mb-2">
                                <strong><i class="fas fa-certificate me-2 text-warning"></i>Certificate:</strong><br>
                                <span class="ms-4">
                                    <span class="badge bg-success">Issued</span>
                                    @if($enrollment->certificate_number)
                                        <span class="text-muted">#{{ $enrollment->certificate_number }}</span>
                                    @endif
                                </span>
                            </p>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($enrollment->feedback)
                    <div class="mb-3">
                        <p class="mb-2">
                            <strong><i class="fas fa-comments me-2 text-info"></i>Feedback:</strong>
                        </p>
                        <p class="ms-4 text-muted">{{ $enrollment->feedback }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-tasks me-2"></i>Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('hr.training.enrollments.edit', $enrollment->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Update Enrollment
                        </a>
                        
                        @if($enrollment->status != 'Completed')
                        <form action="{{ route('hr.training.enrollments.destroy', $enrollment->id) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this enrollment?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i>Delete Enrollment
                            </button>
                        </form>
                        @else
                        <button type="button" class="btn btn-danger" disabled title="Cannot delete completed enrollments">
                            <i class="fas fa-trash me-2"></i>Delete Enrollment
                        </button>
                        @endif

                        <a href="{{ route('hr.training.programs.show', $enrollment->training_program_id) }}" class="btn btn-info">
                            <i class="fas fa-graduation-cap me-2"></i>View Program
                        </a>

                        <a href="{{ route('hr.training.enrollments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Timeline</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <small class="text-muted d-block">Enrolled On</small>
                            <strong>{{ $enrollment->enrollment_date ? \Carbon\Carbon::parse($enrollment->enrollment_date)->format('d M Y') : 'N/A' }}</strong>
                        </li>
                        @if($enrollment->approved_date)
                        <li class="mb-3">
                            <small class="text-muted d-block">Approved On</small>
                            <strong>{{ \Carbon\Carbon::parse($enrollment->approved_date)->format('d M Y') }}</strong>
                        </li>
                        @endif
                        @if($enrollment->completion_date)
                        <li class="mb-3">
                            <small class="text-muted d-block">Completed On</small>
                            <strong>{{ \Carbon\Carbon::parse($enrollment->completion_date)->format('d M Y') }}</strong>
                        </li>
                        @endif
                        <li>
                            <small class="text-muted d-block">Last Updated</small>
                            <strong>{{ $enrollment->updated_at->format('d M Y, h:i A') }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
