@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">Attendance Details</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('attendance.attendance.index') }}">Attendance</a></li>
                            <li class="breadcrumb-item active">Attendance #{{ $attendance->id }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('attendance.attendance.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

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

    <div class="row">
        <div class="col-md-8">
            <!-- Attendance Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Attendance Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted">Attendance ID</label>
                            <p class="fw-bold">#{{ $attendance->id }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">Status</label>
                            <p>
                                @switch($attendance->status)
                                    @case('Present')
                                        <span class="badge bg-success fs-6">Present</span>
                                        @break
                                    @case('Late')
                                        <span class="badge bg-warning text-dark fs-6">Late</span>
                                        @break
                                    @case('Absent')
                                        <span class="badge bg-danger fs-6">Absent</span>
                                        @break
                                    @case('Half Day')
                                        <span class="badge bg-info fs-6">Half Day</span>
                                        @break
                                    @case('Leave')
                                        <span class="badge bg-secondary fs-6">Leave</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary fs-6">{{ $attendance->status }}</span>
                                @endswitch
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted">Date</label>
                            <p class="fw-bold">{{ $attendance->date->format('d F Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">Day</label>
                            <p class="fw-bold">{{ $attendance->date->format('l') }}</p>
                        </div>
                    </div>

                    <hr>

                    <!-- Time Details -->
                    <h6 class="text-primary mb-3"><i class="bi bi-clock me-2"></i>Time Details</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted">Check-In Time</label>
                            <p class="fw-bold">
                                @if($attendance->check_in)
                                    <span class="text-success">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>
                                        {{ \Carbon\Carbon::parse($attendance->date . ' ' . $attendance->check_in)->format('h:i A') }}
                                    </span>
                                @else
                                    <span class="text-muted">Not checked in</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">Check-Out Time</label>
                            <p class="fw-bold">
                                @if($attendance->check_out)
                                    <span class="text-danger">
                                        <i class="bi bi-box-arrow-right me-1"></i>
                                        {{ \Carbon\Carbon::parse($attendance->date . ' ' . $attendance->check_out)->format('h:i A') }}
                                    </span>
                                @else
                                    <span class="text-warning">Not checked out</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted">Working Hours</label>
                            <p class="fw-bold">
                                @if($attendance->working_hours)
                                    <span class="badge bg-info fs-6">{{ $attendance->working_hours }} hrs</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">Overtime Hours</label>
                            <p class="fw-bold">
                                @if($attendance->overtime_hours > 0)
                                    <span class="badge bg-warning text-dark fs-6">{{ $attendance->overtime_hours }} hrs</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($attendance->remarks)
                        <hr>
                        <div class="mb-3">
                            <label class="text-muted">Remarks</label>
                            <p class="border p-3 rounded bg-light">{{ $attendance->remarks }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Employee Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i>Employee Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Employee Name</label>
                            <p class="fw-bold">{{ $attendance->employee->full_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Employee Code</label>
                            <p class="fw-bold">{{ $attendance->employee->employee_code }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Department</label>
                            <p class="fw-bold">{{ $attendance->employee->department->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Designation</label>
                            <p class="fw-bold">{{ $attendance->employee->designation->title ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Email</label>
                            <p>{{ $attendance->employee->email ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Phone</label>
                            <p>{{ $attendance->employee->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location & Device Information -->
            @if($attendance->check_in_location || $attendance->check_out_location || $attendance->device_type)
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Location & Device Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($attendance->check_in_location)
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Check-In Location</label>
                            <p class="mb-1">{{ $attendance->check_in_location }}</p>
                            @if($attendance->check_in_lat && $attendance->check_in_lng)
                                <small class="text-muted">
                                    <i class="bi bi-geo me-1"></i>
                                    <a href="https://www.google.com/maps?q={{ $attendance->check_in_lat }},{{ $attendance->check_in_lng }}" 
                                       target="_blank" 
                                       class="text-decoration-none">
                                        {{ $attendance->check_in_lat }}, {{ $attendance->check_in_lng }}
                                        <i class="bi bi-box-arrow-up-right ms-1"></i>
                                    </a>
                                </small>
                            @endif
                        </div>
                        @endif

                        @if($attendance->check_out_location)
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Check-Out Location</label>
                            <p class="mb-1">{{ $attendance->check_out_location }}</p>
                            @if($attendance->check_out_lat && $attendance->check_out_lng)
                                <small class="text-muted">
                                    <i class="bi bi-geo me-1"></i>
                                    <a href="https://www.google.com/maps?q={{ $attendance->check_out_lat }},{{ $attendance->check_out_lng }}" 
                                       target="_blank" 
                                       class="text-decoration-none">
                                        {{ $attendance->check_out_lat }}, {{ $attendance->check_out_lng }}
                                        <i class="bi bi-box-arrow-up-right ms-1"></i>
                                    </a>
                                </small>
                            @endif
                        </div>
                        @endif

                        @if($attendance->device_type)
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Device Type</label>
                            <p>
                                <i class="bi bi-phone me-1"></i>
                                {{ $attendance->device_type }}
                            </p>
                        </div>
                        @endif

                        @if($attendance->device_id)
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Device ID</label>
                            <p><code>{{ $attendance->device_id }}</code></p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <!-- Quick Actions Card -->
            <div class="card mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('attendance.attendance.edit', $attendance) }}" class="btn btn-warning w-100 mb-2">
                        <i class="bi bi-pencil me-2"></i>Edit Attendance
                    </a>

                    <form action="{{ route('attendance.attendance.destroy', $attendance) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this attendance record? This action cannot be undone.');"
                          class="d-inline w-100">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100 mb-2">
                            <i class="bi bi-trash me-2"></i>Delete Record
                        </button>
                    </form>

                    <button onclick="window.print()" class="btn btn-info w-100 mb-2">
                        <i class="bi bi-printer me-2"></i>Print
                    </button>

                    <a href="{{ route('attendance.attendance.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>

            <!-- Record Timeline Card -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Record Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item completed">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Check-In</h6>
                                <small class="text-muted">
                                    @if($attendance->check_in)
                                        {{ \Carbon\Carbon::parse($attendance->date . ' ' . $attendance->check_in)->format('h:i A') }}
                                    @else
                                        Not checked in
                                    @endif
                                </small>
                            </div>
                        </div>

                        <div class="timeline-item {{ $attendance->check_out ? 'completed' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Check-Out</h6>
                                <small class="text-muted">
                                    @if($attendance->check_out)
                                        {{ \Carbon\Carbon::parse($attendance->date . ' ' . $attendance->check_out)->format('h:i A') }}
                                    @else
                                        Not checked out
                                    @endif
                                </small>
                            </div>
                        </div>

                        <div class="timeline-item completed">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Created</h6>
                                <small class="text-muted">{{ $attendance->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                        </div>

                        @if($attendance->updated_at != $attendance->created_at)
                        <div class="timeline-item completed">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Last Updated</h6>
                                <small class="text-muted">{{ $attendance->updated_at->format('d M Y, h:i A') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
        border-radius: 0.5rem;
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    
    .timeline-marker {
        position: absolute;
        left: -26px;
        top: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #e9ecef;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #e9ecef;
    }
    
    .timeline-item.completed .timeline-marker {
        background: #28a745;
        box-shadow: 0 0 0 2px #28a745;
    }
    
    .timeline-content h6 {
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    @media print {
        .btn, .breadcrumb, .card-header, .timeline {
            display: none !important;
        }
        
        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }
    }
</style>
@endpush
