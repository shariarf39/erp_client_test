@extends('layouts.app')

@section('title', $program->title . ' - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>{{ $program->title }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.training.programs.index') }}">Training Programs</a></li>
                    <li class="breadcrumb-item active">{{ $program->program_code }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Program Details</h5>
                        @php
                            $statusColors = [
                                'Planned' => 'secondary',
                                'Open for Enrollment' => 'info',
                                'In Progress' => 'warning',
                                'Completed' => 'success',
                                'Cancelled' => 'danger'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$program->status] ?? 'secondary' }} fs-6">
                            {{ $program->status }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-barcode me-2 text-primary"></i>Program Code:</strong><br>
                                <span class="ms-4">{{ $program->program_code }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-tag me-2 text-primary"></i>Category:</strong><br>
                                <span class="ms-4">{{ $program->category }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-laptop me-2 text-primary"></i>Training Type:</strong><br>
                                <span class="ms-4">{{ $program->training_type }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            @if($program->venue)
                            <p class="mb-2">
                                <strong><i class="fas fa-map-marker-alt me-2 text-primary"></i>Venue:</strong><br>
                                <span class="ms-4">{{ $program->venue }}</span>
                            </p>
                            @endif
                        </div>
                    </div>

                    @if($program->description)
                    <div class="mb-3">
                        <p class="mb-2">
                            <strong><i class="fas fa-align-left me-2 text-primary"></i>Description:</strong>
                        </p>
                        <p class="ms-4 text-muted">{{ $program->description }}</p>
                    </div>
                    @endif

                    @if($program->trainer_name)
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <p class="mb-2">
                                <strong><i class="fas fa-chalkboard-teacher me-2 text-primary"></i>Trainer:</strong><br>
                                <span class="ms-4">{{ $program->trainer_name }}</span>
                            </p>
                        </div>
                        <div class="col-md-4">
                            @if($program->trainer_type)
                            <p class="mb-2">
                                <strong><i class="fas fa-user-tag me-2 text-primary"></i>Trainer Type:</strong><br>
                                <span class="ms-4">{{ $program->trainer_type }}</span>
                            </p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        @if($program->duration_days)
                        <div class="col-md-3">
                            <p class="mb-2">
                                <strong><i class="fas fa-calendar me-2 text-primary"></i>Duration:</strong><br>
                                <span class="ms-4">{{ $program->duration_days }} days</span>
                            </p>
                        </div>
                        @endif
                        @if($program->duration_hours)
                        <div class="col-md-3">
                            <p class="mb-2">
                                <strong><i class="fas fa-clock me-2 text-primary"></i>Hours:</strong><br>
                                <span class="ms-4">{{ $program->duration_hours }} hrs</span>
                            </p>
                        </div>
                        @endif
                        @if($program->max_participants)
                        <div class="col-md-3">
                            <p class="mb-2">
                                <strong><i class="fas fa-users me-2 text-primary"></i>Capacity:</strong><br>
                                <span class="ms-4">{{ $program->max_participants }}</span>
                            </p>
                        </div>
                        @endif
                        @if($program->cost_per_participant)
                        <div class="col-md-3">
                            <p class="mb-2">
                                <strong><i class="fas fa-money-bill me-2 text-primary"></i>Cost:</strong><br>
                                <span class="ms-4">৳{{ number_format($program->cost_per_participant, 2) }}</span>
                            </p>
                        </div>
                        @endif
                    </div>

                    @if($program->start_date || $program->end_date || $program->enrollment_deadline)
                    <div class="row mb-3">
                        @if($program->start_date)
                        <div class="col-md-4">
                            <p class="mb-2">
                                <strong><i class="fas fa-play-circle me-2 text-success"></i>Start Date:</strong><br>
                                <span class="ms-4">{{ \Carbon\Carbon::parse($program->start_date)->format('d M Y') }}</span>
                            </p>
                        </div>
                        @endif
                        @if($program->end_date)
                        <div class="col-md-4">
                            <p class="mb-2">
                                <strong><i class="fas fa-stop-circle me-2 text-danger"></i>End Date:</strong><br>
                                <span class="ms-4">{{ \Carbon\Carbon::parse($program->end_date)->format('d M Y') }}</span>
                            </p>
                        </div>
                        @endif
                        @if($program->enrollment_deadline)
                        <div class="col-md-4">
                            <p class="mb-2">
                                <strong><i class="fas fa-hourglass-end me-2 text-warning"></i>Enrollment Deadline:</strong><br>
                                <span class="ms-4">{{ \Carbon\Carbon::parse($program->enrollment_deadline)->format('d M Y') }}</span>
                            </p>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($program->prerequisites)
                    <div class="mb-3">
                        <p class="mb-2">
                            <strong><i class="fas fa-list-check me-2 text-primary"></i>Prerequisites:</strong>
                        </p>
                        <p class="ms-4 text-muted">{{ $program->prerequisites }}</p>
                    </div>
                    @endif

                    @if($program->objectives)
                    <div class="mb-3">
                        <p class="mb-2">
                            <strong><i class="fas fa-bullseye me-2 text-primary"></i>Learning Objectives:</strong>
                        </p>
                        <p class="ms-4 text-muted">{{ $program->objectives }}</p>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-user-plus me-2 text-primary"></i>Created By:</strong><br>
                                <span class="ms-4">{{ $program->createdBy->name ?? 'N/A' }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-calendar-plus me-2 text-primary"></i>Created On:</strong><br>
                                <span class="ms-4">{{ $program->created_at->format('d M Y, h:i A') }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($program->enrollments->count() > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Enrolled Participants</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Employee ID</th>
                                    <th>Department</th>
                                    <th>Enrollment Date</th>
                                    <th>Status</th>
                                    <th>Completion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($program->enrollments as $enrollment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $enrollment->employee->name ?? 'N/A' }}</td>
                                    <td>{{ $enrollment->employee->employee_id ?? 'N/A' }}</td>
                                    <td>{{ $enrollment->employee->department->name ?? 'N/A' }}</td>
                                    <td>{{ $enrollment->enrollment_date ? \Carbon\Carbon::parse($enrollment->enrollment_date)->format('d M Y') : 'N/A' }}</td>
                                    <td>
                                        @php
                                            $enrollmentColors = [
                                                'Enrolled' => 'primary',
                                                'In Progress' => 'warning',
                                                'Completed' => 'success',
                                                'Withdrawn' => 'danger',
                                                'Failed' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $enrollmentColors[$enrollment->status] ?? 'secondary' }}">
                                            {{ $enrollment->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($enrollment->completion_date)
                                            {{ \Carbon\Carbon::parse($enrollment->completion_date)->format('d M Y') }}
                                        @else
                                            <span class="text-muted">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                No participants enrolled yet. Once enrollment opens, employees can register for this program.
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Statistics</h6>
                </div>
                <div class="card-body">
                    @php
                        $enrolledCount = $program->enrollments->count();
                        $capacityUsage = $program->max_participants ? ($enrolledCount / $program->max_participants * 100) : 0;
                        $totalCost = $program->cost_per_participant ? ($enrolledCount * $program->cost_per_participant) : 0;
                    @endphp
                    <p class="mb-2">
                        <strong>Enrolled Participants:</strong><br>
                        <span class="fs-3 text-primary">{{ $enrolledCount }}</span>
                        @if($program->max_participants)
                            <span class="text-muted">/ {{ $program->max_participants }}</span>
                        @endif
                    </p>
                    
                    @if($program->max_participants)
                    <div class="mb-3">
                        <label class="form-label small mb-1">Capacity Usage</label>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar {{ $capacityUsage > 90 ? 'bg-danger' : ($capacityUsage > 70 ? 'bg-warning' : 'bg-success') }}" 
                                 role="progressbar" 
                                 style="width: {{ $capacityUsage }}%"
                                 aria-valuenow="{{ $capacityUsage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ number_format($capacityUsage, 1) }}%
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($program->cost_per_participant)
                    <p class="mb-0">
                        <strong>Total Cost:</strong><br>
                        <span class="fs-4 text-success">৳{{ number_format($totalCost, 2) }}</span>
                    </p>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-tasks me-2"></i>Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('hr.training.programs.edit', $program->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Program
                        </a>
                        
                        @if($program->enrollments->count() == 0)
                        <form action="{{ route('hr.training.programs.destroy', $program->id) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this training program?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i>Delete Program
                            </button>
                        </form>
                        @else
                        <button type="button" class="btn btn-danger" disabled title="Cannot delete program with enrolled participants">
                            <i class="fas fa-trash me-2"></i>Delete Program
                        </button>
                        @endif

                        <a href="{{ route('hr.training.programs.index') }}" class="btn btn-secondary">
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
                            <small class="text-muted d-block">Created</small>
                            <strong>{{ $program->created_at->format('d M Y, h:i A') }}</strong>
                        </li>
                        @if($program->start_date)
                        <li class="mb-3">
                            <small class="text-muted d-block">Program Start</small>
                            <strong>{{ \Carbon\Carbon::parse($program->start_date)->format('d M Y') }}</strong>
                        </li>
                        @endif
                        @if($program->end_date)
                        <li class="mb-3">
                            <small class="text-muted d-block">Program End</small>
                            <strong>{{ \Carbon\Carbon::parse($program->end_date)->format('d M Y') }}</strong>
                        </li>
                        @endif
                        <li>
                            <small class="text-muted d-block">Last Updated</small>
                            <strong>{{ $program->updated_at->format('d M Y, h:i A') }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
