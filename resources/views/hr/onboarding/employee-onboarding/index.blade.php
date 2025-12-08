@extends('layouts.app')

@section('title', 'Employee Onboarding - SENA.ERP')
@section('page_title', 'Employee Onboarding')
@section('page_description', 'Track and manage employee onboarding progress')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-check me-2"></i>Employee Onboarding</h5>
                    <a href="{{ route('hr.onboarding.employee-onboarding.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Start Onboarding
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('hr.onboarding.employee-onboarding.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <input type="text" name="search" class="form-control" placeholder="Search by employee name or code..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="Not Started" {{ request('status') == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                                    <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="On Hold" {{ request('status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('hr.onboarding.employee-onboarding.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Onboarding Table -->
                    @if($onboardings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Checklist</th>
                                        <th>Assigned To</th>
                                        <th>Start Date</th>
                                        <th>Target Date</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($onboardings as $onboarding)
                                    <tr>
                                        <td>
                                            @if($onboarding->employee)
                                                <div>
                                                    <strong>{{ $onboarding->employee->first_name }} {{ $onboarding->employee->last_name }}</strong>
                                                    <br><small class="text-muted">{{ $onboarding->employee->employee_code }}</small>
                                                    @if($onboarding->employee->designation)
                                                        <br><span class="badge bg-secondary">{{ $onboarding->employee->designation->name }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($onboarding->checklist)
                                                <strong>{{ $onboarding->checklist->name }}</strong>
                                                <br><small class="text-muted">
                                                    <i class="fas fa-tasks"></i> {{ $onboarding->checklist->tasks->count() }} tasks
                                                </small>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($onboarding->assignedTo)
                                                <div>
                                                    <i class="fas fa-user"></i> {{ $onboarding->assignedTo->name }}
                                                </div>
                                            @else
                                                <span class="text-muted">Not assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small><i class="fas fa-calendar-plus text-success"></i> {{ \Carbon\Carbon::parse($onboarding->start_date)->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <small><i class="fas fa-calendar-check text-warning"></i> {{ \Carbon\Carbon::parse($onboarding->expected_completion_date)->format('M d, Y') }}</small>
                                            @if($onboarding->actual_completion_date)
                                                <br><small class="text-success">
                                                    <i class="fas fa-check-circle"></i> Completed: {{ \Carbon\Carbon::parse($onboarding->actual_completion_date)->format('M d, Y') }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <div class="progress" style="height: 25px;">
                                                    <div class="progress-bar 
                                                        @if($onboarding->completion_percentage < 30) bg-danger
                                                        @elseif($onboarding->completion_percentage < 70) bg-warning
                                                        @else bg-success
                                                        @endif" 
                                                        role="progressbar" 
                                                        style="width: {{ $onboarding->completion_percentage }}%;" 
                                                        aria-valuenow="{{ $onboarding->completion_percentage }}" 
                                                        aria-valuemin="0" 
                                                        aria-valuemax="100">
                                                        <strong>{{ $onboarding->completion_percentage }}%</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'Not Started' => 'secondary',
                                                    'In Progress' => 'primary',
                                                    'Completed' => 'success',
                                                    'On Hold' => 'warning'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$onboarding->status] ?? 'secondary' }}">
                                                {{ $onboarding->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('hr.onboarding.employee-onboarding.show', $onboarding->id) }}" class="btn btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('hr.onboarding.employee-onboarding.edit', $onboarding->id) }}" class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('hr.onboarding.employee-onboarding.destroy', $onboarding->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this onboarding record?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $onboardings->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No employee onboarding records found. <a href="{{ route('hr.onboarding.employee-onboarding.create') }}" class="alert-link">Start your first onboarding</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="text-muted">Total Onboarding</h6>
                    <h3 class="mb-0">{{ \App\Models\EmployeeOnboarding::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <h6 class="text-muted">In Progress</h6>
                    <h3 class="mb-0 text-primary">{{ \App\Models\EmployeeOnboarding::where('status', 'In Progress')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <h6 class="text-muted">Completed</h6>
                    <h3 class="mb-0 text-success">{{ \App\Models\EmployeeOnboarding::where('status', 'Completed')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <h6 class="text-muted">Average Progress</h6>
                    <h3 class="mb-0 text-warning">{{ \App\Models\EmployeeOnboarding::avg('completion_percentage') ? round(\App\Models\EmployeeOnboarding::avg('completion_percentage')) : 0 }}%</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
