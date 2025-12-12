@extends('layouts.app')

@section('title', 'Create Shift Schedule - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Create Shift Schedule</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.shift-schedules.index') }}">Shift Schedules</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <form action="{{ route('hr.shift-schedules.store') }}" method="POST">
                @csrf

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Schedule Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                                <select class="form-select @error('employee_id') is-invalid @enderror" 
                                        id="employee_id" name="employee_id" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->employee_code }} - {{ $employee->full_name }}
                                            ({{ $employee->department->name ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="shift_id" class="form-label">Shift <span class="text-danger">*</span></label>
                                <select class="form-select @error('shift_id') is-invalid @enderror" 
                                        id="shift_id" name="shift_id" required>
                                    <option value="">Select Shift</option>
                                    @foreach($shifts as $shift)
                                        <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}
                                                data-start="{{ $shift->start_time }}" data-end="{{ $shift->end_time }}">
                                            {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('shift_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="schedule_date" class="form-label">Schedule Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('schedule_date') is-invalid @enderror" 
                                       id="schedule_date" name="schedule_date" value="{{ old('schedule_date', date('Y-m-d')) }}" required>
                                @error('schedule_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="Scheduled" {{ old('status', 'Scheduled') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="Confirmed" {{ old('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="Swapped" {{ old('status') == 'Swapped' ? 'selected' : '' }}>Swapped</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_overtime" name="is_overtime" value="1"
                                       {{ old('is_overtime') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_overtime">
                                    <i class="fas fa-clock me-1"></i> Overtime Shift
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            <small class="text-muted">Additional information or special instructions</small>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="shiftDetails" class="alert alert-info" style="display: none;">
                            <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Selected Shift Details</h6>
                            <div id="shiftDetailsContent"></div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Schedule
                    </button>
                    <a href="{{ route('hr.shift-schedules.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>

        <div class="col-lg-3">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Guidelines</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Assign employees to specific shifts
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Schedule can be created in advance
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Mark overtime shifts separately
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            Check for scheduling conflicts
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-list me-2"></i>Status Guide</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Scheduled:</strong>
                        <small class="text-muted d-block">Initial schedule created</small>
                    </div>
                    <div class="mb-2">
                        <strong>Confirmed:</strong>
                        <small class="text-muted d-block">Employee confirmed availability</small>
                    </div>
                    <div class="mb-2">
                        <strong>Cancelled:</strong>
                        <small class="text-muted d-block">Schedule cancelled</small>
                    </div>
                    <div>
                        <strong>Swapped:</strong>
                        <small class="text-muted d-block">Shift swapped with another employee</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('shift_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const startTime = selectedOption.getAttribute('data-start');
    const endTime = selectedOption.getAttribute('data-end');
    const shiftDetails = document.getElementById('shiftDetails');
    const shiftDetailsContent = document.getElementById('shiftDetailsContent');
    
    if (this.value && startTime && endTime) {
        shiftDetailsContent.innerHTML = `
            <p class="mb-1"><strong>Start Time:</strong> ${startTime}</p>
            <p class="mb-0"><strong>End Time:</strong> ${endTime}</p>
        `;
        shiftDetails.style.display = 'block';
    } else {
        shiftDetails.style.display = 'none';
    }
});

// Trigger on page load if shift is already selected
if (document.getElementById('shift_id').value) {
    document.getElementById('shift_id').dispatchEvent(new Event('change'));
}
</script>
@endpush
@endsection
