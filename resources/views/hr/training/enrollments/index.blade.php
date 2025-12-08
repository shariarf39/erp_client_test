@extends('layouts.app')

@section('title', 'Training Enrollments - SENA.ERP')
@section('page_title', 'Training Enrollments')
@section('page_description', 'Manage employee training enrollments and attendance')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Training Enrollments</h5>
                    <a href="{{ route('hr.training.enrollments.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Enroll Employee
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('hr.training.enrollments.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search by employee or program..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="Enrolled" {{ request('status') == 'Enrolled' ? 'selected' : '' }}>Enrolled</option>
                                    <option value="Attended" {{ request('status') == 'Attended' ? 'selected' : '' }}>Attended</option>
                                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="Failed" {{ request('status') == 'Failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="program_id" class="form-select">
                                    <option value="">All Programs</option>
                                    @foreach(\App\Models\TrainingProgram::orderBy('title')->get() as $prog)
                                        <option value="{{ $prog->id }}" {{ request('program_id') == $prog->id ? 'selected' : '' }}>
                                            {{ $prog->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="col-md-1">
                                <a href="{{ route('hr.training.enrollments.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Enrollments Table -->
                    @if($enrollments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Training Program</th>
                                        <th>Enrollment Date</th>
                                        <th>Status</th>
                                        <th>Attendance</th>
                                        <th>Assessment</th>
                                        <th>Certificate</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrollments as $enrollment)
                                    <tr>
                                        <td>
                                            @if($enrollment->employee)
                                                <div>
                                                    <strong>{{ $enrollment->employee->first_name }} {{ $enrollment->employee->last_name }}</strong>
                                                    <br><small class="text-muted">{{ $enrollment->employee->employee_code }}</small>
                                                    @if($enrollment->employee->designation)
                                                        <br><span class="badge bg-secondary">{{ $enrollment->employee->designation->name }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($enrollment->trainingProgram)
                                                <div>
                                                    <strong>{{ $enrollment->trainingProgram->title }}</strong>
                                                    <br><small class="text-muted">{{ $enrollment->trainingProgram->program_code }}</small>
                                                    @if($enrollment->is_mandatory)
                                                        <br><span class="badge bg-danger"><i class="fas fa-exclamation-circle"></i> Mandatory</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>
                                                <i class="fas fa-calendar-plus"></i> {{ \Carbon\Carbon::parse($enrollment->enrollment_date)->format('M d, Y') }}
                                            </small>
                                            @if($enrollment->completion_date)
                                                <br><small class="text-success">
                                                    <i class="fas fa-check-circle"></i> Completed: {{ \Carbon\Carbon::parse($enrollment->completion_date)->format('M d, Y') }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'Enrolled' => 'primary',
                                                    'Attended' => 'info',
                                                    'Completed' => 'success',
                                                    'Cancelled' => 'dark',
                                                    'Failed' => 'danger',
                                                    'Absent' => 'warning'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$enrollment->status] ?? 'secondary' }}">
                                                {{ $enrollment->status }}
                                            </span>
                                            @if($enrollment->approved_by && $enrollment->approved_date)
                                                <br><small class="text-muted">
                                                    Approved: {{ \Carbon\Carbon::parse($enrollment->approved_date)->format('M d, Y') }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($enrollment->attendance_percentage !== null)
                                                <div>
                                                    <div class="progress" style="height: 20px; width: 80px;">
                                                        <div class="progress-bar 
                                                            @if($enrollment->attendance_percentage < 50) bg-danger
                                                            @elseif($enrollment->attendance_percentage < 75) bg-warning
                                                            @else bg-success
                                                            @endif" 
                                                            role="progressbar" 
                                                            style="width: {{ $enrollment->attendance_percentage }}%;">
                                                            {{ $enrollment->attendance_percentage }}%
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($enrollment->assessment_score !== null)
                                                <div>
                                                    <strong class="
                                                        @if($enrollment->assessment_score < 50) text-danger
                                                        @elseif($enrollment->assessment_score < 75) text-warning
                                                        @else text-success
                                                        @endif">
                                                        {{ $enrollment->assessment_score }}%
                                                    </strong>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($enrollment->certificate_issued)
                                                <div>
                                                    <span class="badge bg-success"><i class="fas fa-certificate"></i> Issued</span>
                                                    @if($enrollment->certificate_number)
                                                        <br><small class="text-muted">{{ $enrollment->certificate_number }}</small>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">Not issued</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('hr.training.enrollments.show', $enrollment->id) }}" class="btn btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('hr.training.enrollments.edit', $enrollment->id) }}" class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($enrollment->status != 'Completed')
                                                    <form action="{{ route('hr.training.enrollments.destroy', $enrollment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this enrollment?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $enrollments->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No training enrollments found. <a href="{{ route('hr.training.enrollments.create') }}" class="alert-link">Enroll your first employee</a>
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
                    <h6 class="text-muted">Total Enrollments</h6>
                    <h3 class="mb-0">{{ \App\Models\TrainingEnrollment::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <h6 class="text-muted">Completed</h6>
                    <h3 class="mb-0 text-success">{{ \App\Models\TrainingEnrollment::where('status', 'Completed')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <h6 class="text-muted">Certificates Issued</h6>
                    <h3 class="mb-0 text-info">{{ \App\Models\TrainingEnrollment::where('certificate_issued', true)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <h6 class="text-muted">Pass Rate</h6>
                    <h3 class="mb-0 text-warning">
                        @php
                            $total = \App\Models\TrainingEnrollment::whereIn('status', ['Completed', 'Failed'])->count();
                            $passed = \App\Models\TrainingEnrollment::where('status', 'Completed')->count();
                        @endphp
                        {{ $total > 0 ? round(($passed / $total) * 100) : 0 }}%
                    </h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
