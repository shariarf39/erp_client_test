@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ isset($shift) ? 'Edit' : 'Create' }} Shift</h2>
        <a href="{{ route('hr.shifts.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Error!</strong> Please fix the following issues:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ isset($shift) ? route('hr.shifts.update', $shift) : route('hr.shifts.store') }}">
        @csrf
        @if(isset($shift))
            @method('PUT')
        @endif

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-clock"></i> Shift Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Shift Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $shift->name ?? '') }}" 
                               placeholder="e.g., Morning Shift" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                               id="start_time" name="start_time" 
                               value="{{ old('start_time', isset($shift) ? \Carbon\Carbon::parse($shift->start_time)->format('H:i') : '') }}" 
                               required>
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                               id="end_time" name="end_time" 
                               value="{{ old('end_time', isset($shift) ? \Carbon\Carbon::parse($shift->end_time)->format('H:i') : '') }}" 
                               required>
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="break_duration" class="form-label">Break Duration (minutes)</label>
                        <input type="number" class="form-control @error('break_duration') is-invalid @enderror" 
                               id="break_duration" name="break_duration" 
                               value="{{ old('break_duration', $shift->break_duration ?? 0) }}" 
                               min="0" placeholder="e.g., 60">
                        @error('break_duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Total break time during the shift</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="grace_time" class="form-label">Grace Time (minutes)</label>
                        <input type="number" class="form-control @error('grace_time') is-invalid @enderror" 
                               id="grace_time" name="grace_time" 
                               value="{{ old('grace_time', $shift->grace_time ?? 0) }}" 
                               min="0" placeholder="e.g., 15">
                        @error('grace_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Late arrival tolerance</small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   value="1" {{ old('is_active', $shift->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Shift
                            </label>
                        </div>
                        <small class="text-muted">Only active shifts can be assigned to employees</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> {{ isset($shift) ? 'Update Shift' : 'Create Shift' }}
            </button>
            <a href="{{ route('hr.shifts.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection
