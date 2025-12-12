@extends('layouts.app')

@section('title', 'Edit Interview - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Interview</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.recruitment.interviews.index') }}">Interviews</a></li>
                    <li class="breadcrumb-item active">Edit Interview #{{ $interview->id }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Update Interview Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hr.recruitment.interviews.update', $interview) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Applicant</label>
                                <input type="text" class="form-control" 
                                       value="{{ $interview->applicant->first_name }} {{ $interview->applicant->last_name }}" 
                                       readonly>
                                <small class="text-muted">{{ $interview->applicant->jobPosting->title ?? 'N/A' }}</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="interview_type" class="form-label">Interview Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('interview_type') is-invalid @enderror" 
                                        id="interview_type" name="interview_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Phone Screening" {{ old('interview_type', $interview->interview_type) == 'Phone Screening' ? 'selected' : '' }}>Phone Screening</option>
                                    <option value="HR Interview" {{ old('interview_type', $interview->interview_type) == 'HR Interview' ? 'selected' : '' }}>HR Interview</option>
                                    <option value="Technical Interview" {{ old('interview_type', $interview->interview_type) == 'Technical Interview' ? 'selected' : '' }}>Technical Interview</option>
                                    <option value="Manager Interview" {{ old('interview_type', $interview->interview_type) == 'Manager Interview' ? 'selected' : '' }}>Manager Interview</option>
                                    <option value="Panel Interview" {{ old('interview_type', $interview->interview_type) == 'Panel Interview' ? 'selected' : '' }}>Panel Interview</option>
                                    <option value="Final Interview" {{ old('interview_type', $interview->interview_type) == 'Final Interview' ? 'selected' : '' }}>Final Interview</option>
                                </select>
                                @error('interview_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="scheduled_date" class="form-label">Interview Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('scheduled_date') is-invalid @enderror" 
                                       id="scheduled_date" name="scheduled_date" 
                                       value="{{ old('scheduled_date', $interview->scheduled_date->format('Y-m-d')) }}" required>
                                @error('scheduled_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="scheduled_time" class="form-label">Interview Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('scheduled_time') is-invalid @enderror" 
                                       id="scheduled_time" name="scheduled_time" 
                                       value="{{ old('scheduled_time', $interview->scheduled_time) }}" required>
                                @error('scheduled_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="duration" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                       id="duration" name="duration" 
                                       value="{{ old('duration', $interview->duration) }}" 
                                       min="15" max="480" required>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location/Platform</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" 
                                       value="{{ old('location', $interview->location) }}" 
                                       placeholder="e.g., Office, Zoom, Google Meet">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="interviewer_id" class="form-label">Interviewer <span class="text-danger">*</span></label>
                                <select class="form-select @error('interviewer_id') is-invalid @enderror" 
                                        id="interviewer_id" name="interviewer_id" required>
                                    <option value="">Select Interviewer</option>
                                    @foreach($interviewers as $interviewer)
                                        <option value="{{ $interviewer->id }}" 
                                                {{ old('interviewer_id', $interview->interviewer_id) == $interviewer->id ? 'selected' : '' }}>
                                            {{ $interviewer->name }} ({{ $interviewer->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('interviewer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="Scheduled" {{ old('status', $interview->status) == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="Rescheduled" {{ old('status', $interview->status) == 'Rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                                    <option value="Completed" {{ old('status', $interview->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Cancelled" {{ old('status', $interview->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="No Show" {{ old('status', $interview->status) == 'No Show' ? 'selected' : '' }}>No Show</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="meeting_link" class="form-label">Meeting Link (Optional)</label>
                            <input type="url" class="form-control @error('meeting_link') is-invalid @enderror" 
                                   id="meeting_link" name="meeting_link" 
                                   value="{{ old('meeting_link', $interview->meeting_link) }}" 
                                   placeholder="https://zoom.us/j/...">
                            @error('meeting_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes/Instructions</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes', $interview->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="feedback" class="form-label">Interview Feedback</label>
                            <textarea class="form-control @error('feedback') is-invalid @enderror" 
                                      id="feedback" name="feedback" rows="4">{{ old('feedback', $interview->feedback) }}</textarea>
                            @error('feedback')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Add feedback after completing the interview</small>
                        </div>

                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating (1-5)</label>
                            <select class="form-select @error('rating') is-invalid @enderror" id="rating" name="rating">
                                <option value="">Not Rated</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('rating', $interview->rating) == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ str_repeat('⭐', $i) }}
                                    </option>
                                @endfor
                            </select>
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('hr.recruitment.interviews.show', $interview) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Interview
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
