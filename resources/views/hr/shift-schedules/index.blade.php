@extends('layouts.app')

@section('title', 'Shift Schedules - SENA.ERP')
@section('page_title', 'Shift Schedules')
@section('page_description', 'Manage daily shift assignments for employees')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-week me-2"></i>Shift Schedules</h5>
                    <a href="{{ route('hr.shift-schedules.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Assign Shift
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('hr.shift-schedules.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="date" name="date" class="form-control" value="{{ request('date', date('Y-m-d')) }}">
                            </div>
                            <div class="col-md-3">
                                <select name="employee_id" class="form-select">
                                    <option value="">All Employees</option>
                                    @foreach(\App\Models\Employee::where('status', 'Active')->orderBy('first_name')->get() as $emp)
                                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->first_name }} {{ $emp->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="shift_id" class="form-select">
                                    <option value="">All Shifts</option>
                                    @foreach(\App\Models\Shift::where('is_active', true)->orderBy('name')->get() as $shift)
                                        <option value="{{ $shift->id }}" {{ request('shift_id') == $shift->id ? 'selected' : '' }}>
                                            {{ $shift->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="Scheduled" {{ request('status') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="Swapped" {{ request('status') == 'Swapped' ? 'selected' : '' }}>Swapped</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="col-md-1">
                                <a href="{{ route('hr.shift-schedules.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Shift Schedules Table -->
                    @if($schedules->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Employee</th>
                                        <th>Shift</th>
                                        <th>Time</th>
                                        <th>Duration</th>
                                        <th>Overtime</th>
                                        <th>Status</th>
                                        <th>Assigned By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schedules as $schedule)
                                    <tr>
                                        <td>
                                            <strong>{{ \Carbon\Carbon::parse($schedule->schedule_date)->format('D, M d, Y') }}</strong>
                                        </td>
                                        <td>
                                            @if($schedule->employee)
                                                <div>
                                                    <strong>{{ $schedule->employee->first_name }} {{ $schedule->employee->last_name }}</strong>
                                                    <br><small class="text-muted">{{ $schedule->employee->employee_code }}</small>
                                                    @if($schedule->employee->designation)
                                                        <br><span class="badge bg-secondary">{{ $schedule->employee->designation->name }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($schedule->shift)
                                                <span class="badge bg-info">{{ $schedule->shift->name }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($schedule->shift)
                                                <div>
                                                    <i class="fas fa-clock text-success"></i> {{ \Carbon\Carbon::parse($schedule->shift->start_time)->format('h:i A') }}
                                                    <br>
                                                    <i class="fas fa-clock text-danger"></i> {{ \Carbon\Carbon::parse($schedule->shift->end_time)->format('h:i A') }}
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($schedule->shift)
                                                <span class="badge bg-light text-dark">
                                                    @php
                                                        $start = \Carbon\Carbon::parse($schedule->shift->start_time);
                                                        $end = \Carbon\Carbon::parse($schedule->shift->end_time);
                                                        $duration = $end->diffInHours($start);
                                                    @endphp
                                                    {{ $duration }} hrs
                                                </span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($schedule->is_overtime)
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-exclamation-circle"></i> Yes
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'Scheduled' => 'primary',
                                                    'Confirmed' => 'success',
                                                    'Cancelled' => 'danger',
                                                    'Swapped' => 'warning'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$schedule->status] ?? 'secondary' }}">
                                                {{ $schedule->status }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($schedule->createdBy)
                                                <small>
                                                    <i class="fas fa-user"></i> {{ $schedule->createdBy->name }}
                                                </small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('hr.shift-schedules.edit', $schedule->id) }}" class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('hr.shift-schedules.destroy', $schedule->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
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
                            {{ $schedules->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No shift schedules found. <a href="{{ route('hr.shift-schedules.create') }}" class="alert-link">Assign your first shift</a>
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
                    <h6 class="text-muted">Today's Schedules</h6>
                    <h3 class="mb-0">{{ \App\Models\ShiftSchedule::whereDate('schedule_date', today())->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <h6 class="text-muted">Confirmed</h6>
                    <h3 class="mb-0 text-success">{{ \App\Models\ShiftSchedule::where('status', 'Confirmed')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <h6 class="text-muted">Overtime Shifts</h6>
                    <h3 class="mb-0 text-warning">{{ \App\Models\ShiftSchedule::where('is_overtime', true)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <h6 class="text-muted">This Week</h6>
                    <h3 class="mb-0 text-info">{{ \App\Models\ShiftSchedule::whereBetween('schedule_date', [now()->startOfWeek(), now()->endOfWeek()])->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
