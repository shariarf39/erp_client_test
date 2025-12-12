@extends('layouts.app')

@section('title', 'Employee Onboarding Details - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-user-check me-2"></i>Employee Onboarding Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.onboarding.employee-onboarding.index') }}">Employee Onboarding</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Onboarding Information</h5>
                    <span class="badge 
                        {{ $employeeOnboarding->status == 'Completed' ? 'bg-success' : '' }}
                        {{ $employeeOnboarding->status == 'In Progress' ? 'bg-info' : '' }}
                        {{ $employeeOnboarding->status == 'Not Started' ? 'bg-secondary' : '' }}
                        {{ $employeeOnboarding->status == 'On Hold' ? 'bg-warning' : '' }}">
                        {{ $employeeOnboarding->status }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Employee</h6>
                            <p class="mb-0">
                                <strong>{{ $employeeOnboarding->employee->full_name }}</strong><br>
                                <small>{{ $employeeOnboarding->employee->employee_code }}</small><br>
                                <small>{{ $employeeOnboarding->employee->designation->title ?? 'N/A' }} - {{ $employeeOnboarding->employee->department->name ?? 'N/A' }}</small>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Checklist</h6>
                            <p class="mb-0">
                                <strong>{{ $employeeOnboarding->checklist->name }}</strong><br>
                                <small>{{ $employeeOnboarding->checklist->tasks->count() }} tasks total</small>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Assigned To (HR Coordinator)</h6>
                            <p class="mb-0">{{ $employeeOnboarding->assignedTo->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Completion Progress</h6>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ $employeeOnboarding->completion_percentage }}%">
                                    {{ number_format($employeeOnboarding->completion_percentage, 0) }}%
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6 class="text-muted">Start Date</h6>
                            <p class="mb-0">{{ $employeeOnboarding->start_date->format('M j, Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Expected Completion</h6>
                            <p class="mb-0">{{ $employeeOnboarding->expected_completion_date->format('M j, Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Actual Completion</h6>
                            <p class="mb-0">{{ $employeeOnboarding->actual_completion_date ? $employeeOnboarding->actual_completion_date->format('M j, Y') : 'Not completed' }}</p>
                        </div>
                    </div>

                    @if($employeeOnboarding->notes)
                    <div class="mb-0">
                        <h6 class="text-muted">Notes</h6>
                        <p class="mb-0">{{ $employeeOnboarding->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Onboarding Tasks Progress</h5>
                </div>
                <div class="card-body">
                    @if($employeeOnboarding->taskProgress->isEmpty())
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>No tasks assigned yet.
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($employeeOnboarding->taskProgress->sortBy('task.sequence_order') as $taskProgress)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge bg-secondary me-2">#{{ $taskProgress->task->sequence_order }}</span>
                                                <h6 class="mb-0">{{ $taskProgress->task->task_name }}</h6>
                                                @if($taskProgress->task->is_mandatory)
                                                    <span class="badge bg-danger ms-2">Mandatory</span>
                                                @endif
                                                <span class="badge ms-auto
                                                    {{ $taskProgress->status == 'Completed' ? 'bg-success' : '' }}
                                                    {{ $taskProgress->status == 'In Progress' ? 'bg-info' : '' }}
                                                    {{ $taskProgress->status == 'Pending' ? 'bg-secondary' : '' }}">
                                                    {{ $taskProgress->status }}
                                                </span>
                                            </div>
                                            
                                            @if($taskProgress->task->description)
                                                <p class="text-muted small mb-2">{{ $taskProgress->task->description }}</p>
                                            @endif
                                            
                                            <div class="d-flex gap-2 align-items-center">
                                                <span class="badge bg-info">{{ $taskProgress->task->category }}</span>
                                                
                                                @if($taskProgress->completed_date)
                                                    <small class="text-muted">
                                                        <i class="fas fa-check-circle text-success me-1"></i>
                                                        Completed: {{ $taskProgress->completed_date->format('M j, Y') }}
                                                    </small>
                                                @endif
                                                
                                                @if($taskProgress->completedBy)
                                                    <small class="text-muted">
                                                        by {{ $taskProgress->completedBy->name }}
                                                    </small>
                                                @endif
                                            </div>
                                            
                                            @if($taskProgress->notes)
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-sticky-note me-1"></i>
                                                        {{ $taskProgress->notes }}
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('hr.onboarding.employee-onboarding.edit', $employeeOnboarding) }}" class="btn btn-warning w-100 mb-2">
                        <i class="fas fa-edit me-2"></i>Edit Onboarding
                    </a>

                    <form action="{{ route('hr.onboarding.employee-onboarding.destroy', $employeeOnboarding) }}" method="POST" class="mb-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('Are you sure you want to delete this onboarding?')">
                            <i class="fas fa-trash me-2"></i>Delete Onboarding
                        </button>
                    </form>

                    <a href="{{ route('hr.onboarding.employee-onboarding.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Task Summary</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Tasks:</span>
                        <strong>{{ $employeeOnboarding->taskProgress->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Completed:</span>
                        <strong class="text-success">{{ $employeeOnboarding->taskProgress->where('status', 'Completed')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>In Progress:</span>
                        <strong class="text-info">{{ $employeeOnboarding->taskProgress->where('status', 'In Progress')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Pending:</span>
                        <strong class="text-secondary">{{ $employeeOnboarding->taskProgress->where('status', 'Pending')->count() }}</strong>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Timeline</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <i class="fas fa-play-circle text-primary me-2"></i>
                            <strong>Started:</strong><br>
                            <small class="text-muted ms-3">{{ $employeeOnboarding->start_date->format('M j, Y') }}</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-calendar-check text-warning me-2"></i>
                            <strong>Expected:</strong><br>
                            <small class="text-muted ms-3">{{ $employeeOnboarding->expected_completion_date->format('M j, Y') }}</small>
                        </li>
                        @if($employeeOnboarding->actual_completion_date)
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Completed:</strong><br>
                            <small class="text-muted ms-3">{{ $employeeOnboarding->actual_completion_date->format('M j, Y') }}</small>
                        </li>
                        @endif
                        <li>
                            <i class="fas fa-hourglass-half text-info me-2"></i>
                            <strong>Days Elapsed:</strong><br>
                            <small class="text-muted ms-3">{{ $employeeOnboarding->start_date->diffInDays(now()) }} days</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
