@extends('layouts.app')

@section('page_title', 'Attendance Records')
@section('page_description', 'View all attendance records')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-clipboard-list me-2"></i> Attendance Records
        </div>
        <a href="{{ route('attendance.check-in') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-clock me-1"></i> Check-In/Out
        </a>
    </div>
    <div class="card-body">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filters -->
        <form method="GET" action="{{ route('attendance.attendance.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="date" name="date" class="form-control" value="{{ request('date', date('Y-m-d')) }}">
                </div>
                <div class="col-md-2">
                    <select name="department_id" class="form-control">
                        <option value="">All Departments</option>
                        @foreach(\App\Models\Department::all() as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->department_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>Present</option>
                        <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                        <option value="Late" {{ request('status') == 'Late' ? 'selected' : '' }}>Late</option>
                        <option value="Half Day" {{ request('status') == 'Half Day' ? 'selected' : '' }}>Half Day</option>
                        <option value="Leave" {{ request('status') == 'Leave' ? 'selected' : '' }}>Leave</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('attendance.attendance.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>

        <!-- Attendance Table -->
        @if($attendances->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Date</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Working Hours</th>
                            <th>Status</th>
                            <th>Device</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                            <tr>
                                <td>
                                    <strong>{{ $attendance->employee->full_name ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $attendance->employee->employee_code ?? '' }}</small>
                                </td>
                                <td>{{ $attendance->employee->department->department_name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                                <td>
                                    @if($attendance->check_in)
                                        <span class="text-success">
                                            <i class="fas fa-sign-in-alt me-1"></i>
                                            {{ \Carbon\Carbon::parse($attendance->date . ' ' . $attendance->check_in)->format('h:i A') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($attendance->check_out)
                                        <span class="text-danger">
                                            <i class="fas fa-sign-out-alt me-1"></i>
                                            {{ \Carbon\Carbon::parse($attendance->date . ' ' . $attendance->check_out)->format('h:i A') }}
                                        </span>
                                    @else
                                        <span class="text-warning">Not checked out</span>
                                    @endif
                                </td>
                                <td>
                                    @if($attendance->working_hours)
                                        <strong>{{ $attendance->working_hours }}</strong> hrs
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $attendance->status === 'Present' ? 'success' : 
                                        ($attendance->status === 'Late' ? 'warning' : 
                                        ($attendance->status === 'Absent' ? 'danger' : 'info')) 
                                    }}">
                                        {{ $attendance->status }}
                                    </span>
                                </td>
                                <td>
                                    @if($attendance->device_type)
                                        <small class="text-muted">
                                            <i class="fas fa-mobile-alt me-1"></i>
                                            {{ $attendance->device_type }}
                                        </small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('attendance.attendance.show', $attendance) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('attendance.attendance.edit', $attendance) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('attendance.attendance.destroy', $attendance) }}" 
                                              method="POST" 
                                              style="display: inline;"
                                              onsubmit="return confirm('Are you sure you want to delete this attendance record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
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
            <div class="d-flex justify-content-center">
                {{ $attendances->links() }}
            </div>

            <!-- Summary Statistics -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3>{{ $attendances->where('status', 'Present')->count() }}</h3>
                            <p class="mb-0">Present</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h3>{{ $attendances->where('status', 'Late')->count() }}</h3>
                            <p class="mb-0">Late</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h3>{{ $attendances->where('status', 'Absent')->count() }}</h3>
                            <p class="mb-0">Absent</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h3>{{ $attendances->where('status', 'Leave')->count() }}</h3>
                            <p class="mb-0">On Leave</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i> No attendance records found for the selected filters.
            </div>
        @endif
    </div>
</div>
@endsection
