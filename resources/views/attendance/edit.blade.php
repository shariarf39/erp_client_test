@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">Edit Attendance</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('attendance.attendance.index') }}">Attendance</a></li>
                            <li class="breadcrumb-item active">Edit #{{ $attendance->id }}</li>
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
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Validation Error!</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Edit Form -->
    <form action="{{ route('attendance.attendance.update', $attendance) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <!-- Attendance Information Card -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Attendance Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label"><strong>Employee:</strong></label>
                                <p class="form-control-plaintext">
                                    {{ $attendance->employee->full_name }} 
                                    <span class="text-muted">({{ $attendance->employee->employee_code }})</span>
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label"><strong>Department:</strong></label>
                                <p class="form-control-plaintext">{{ $attendance->employee->department->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><strong>Designation:</strong></label>
                                <p class="form-control-plaintext">{{ $attendance->employee->designation->title ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <hr>

                        <!-- Editable Fields -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date" class="form-label required">Date</label>
                                <input type="date" 
                                       name="date" 
                                       id="date" 
                                       class="form-control @error('date') is-invalid @enderror" 
                                       value="{{ old('date', $attendance->date->format('Y-m-d')) }}"
                                       required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label required">Status</label>
                                <select name="status" 
                                        id="status" 
                                        class="form-select @error('status') is-invalid @enderror" 
                                        required>
                                    <option value="Present" {{ old('status', $attendance->status) === 'Present' ? 'selected' : '' }}>Present</option>
                                    <option value="Absent" {{ old('status', $attendance->status) === 'Absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="Late" {{ old('status', $attendance->status) === 'Late' ? 'selected' : '' }}>Late</option>
                                    <option value="Half Day" {{ old('status', $attendance->status) === 'Half Day' ? 'selected' : '' }}>Half Day</option>
                                    <option value="Leave" {{ old('status', $attendance->status) === 'Leave' ? 'selected' : '' }}>Leave</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="check_in" class="form-label required">Check-In Time</label>
                                <input type="time" 
                                       name="check_in" 
                                       id="check_in" 
                                       class="form-control @error('check_in') is-invalid @enderror" 
                                       value="{{ old('check_in', $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '') }}"
                                       required>
                                @error('check_in')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="check_out" class="form-label">Check-Out Time</label>
                                <input type="time" 
                                       name="check_out" 
                                       id="check_out" 
                                       class="form-control @error('check_out') is-invalid @enderror" 
                                       value="{{ old('check_out', $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '') }}">
                                @error('check_out')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="overtime_hours" class="form-label">Overtime Hours</label>
                                <input type="number" 
                                       name="overtime_hours" 
                                       id="overtime_hours" 
                                       class="form-control @error('overtime_hours') is-invalid @enderror" 
                                       value="{{ old('overtime_hours', $attendance->overtime_hours) }}"
                                       step="0.01"
                                       min="0"
                                       max="24">
                                @error('overtime_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Working Hours</label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           id="working_hours_display"
                                           value="{{ $attendance->working_hours ?? '-' }}" 
                                           readonly>
                                    <span class="input-group-text">hrs</span>
                                </div>
                                <small class="text-muted">Auto-calculated from check-in and check-out times</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea name="remarks" 
                                          id="remarks" 
                                          class="form-control @error('remarks') is-invalid @enderror" 
                                          rows="3"
                                          maxlength="500">{{ old('remarks', $attendance->remarks) }}</textarea>
                                @error('remarks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Maximum 500 characters</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Location Information (Read-Only) -->
                @if($attendance->check_in_location || $attendance->check_out_location)
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Location Information</h5>
                    </div>
                    <div class="card-body">
                        @if($attendance->check_in_location)
                            <div class="mb-3">
                                <label class="text-muted">Check-In Location</label>
                                <p class="mb-1">{{ $attendance->check_in_location }}</p>
                                @if($attendance->check_in_lat && $attendance->check_in_lng)
                                    <small class="text-muted">
                                        <i class="bi bi-geo me-1"></i>
                                        {{ $attendance->check_in_lat }}, {{ $attendance->check_in_lng }}
                                    </small>
                                @endif
                            </div>
                        @endif

                        @if($attendance->check_out_location)
                            <div class="mb-3">
                                <label class="text-muted">Check-Out Location</label>
                                <p class="mb-1">{{ $attendance->check_out_location }}</p>
                                @if($attendance->check_out_lat && $attendance->check_out_lng)
                                    <small class="text-muted">
                                        <i class="bi bi-geo me-1"></i>
                                        {{ $attendance->check_out_lat }}, {{ $attendance->check_out_lng }}
                                    </small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Device Information (Read-Only) -->
                @if($attendance->device_type || $attendance->device_id)
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="bi bi-phone me-2"></i>Device Information</h5>
                    </div>
                    <div class="card-body">
                        @if($attendance->device_type)
                            <div class="mb-2">
                                <label class="text-muted">Device Type</label>
                                <p class="mb-0">{{ $attendance->device_type }}</p>
                            </div>
                        @endif
                        @if($attendance->device_id)
                            <div class="mb-2">
                                <label class="text-muted">Device ID</label>
                                <p class="mb-0"><code>{{ $attendance->device_id }}</code></p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-check-circle me-2"></i>Update Attendance
                        </button>
                        <a href="{{ route('attendance.attendance.show', $attendance) }}" class="btn btn-secondary w-100">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .required::after {
        content: " *";
        color: red;
    }
    
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
        border-radius: 0.5rem;
    }
    
    .form-control-plaintext {
        padding-top: 0.375rem;
        padding-bottom: 0.375rem;
        margin-bottom: 0;
        font-size: inherit;
        line-height: 1.5;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    const workingHoursDisplay = document.getElementById('working_hours_display');
    
    function calculateWorkingHours() {
        const checkIn = checkInInput.value;
        const checkOut = checkOutInput.value;
        
        if (checkIn && checkOut) {
            const checkInTime = new Date('2000-01-01 ' + checkIn);
            const checkOutTime = new Date('2000-01-01 ' + checkOut);
            
            if (checkOutTime > checkInTime) {
                const diffMs = checkOutTime - checkInTime;
                const diffHrs = diffMs / (1000 * 60 * 60);
                workingHoursDisplay.value = diffHrs.toFixed(2);
            } else {
                workingHoursDisplay.value = '-';
            }
        } else {
            workingHoursDisplay.value = '-';
        }
    }
    
    checkInInput.addEventListener('change', calculateWorkingHours);
    checkOutInput.addEventListener('change', calculateWorkingHours);
    
    // Calculate on page load
    calculateWorkingHours();
});
</script>
@endpush
