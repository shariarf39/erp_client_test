@extends('layouts.app')

@section('title', 'Schedule Interview - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Schedule New Interview</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.recruitment.interviews.index') }}">Interviews</a></li>
                    <li class="breadcrumb-item active">Schedule Interview</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Interview Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hr.recruitment.interviews.store') }}" method="POST">
                        @csrf
                        
                        <!-- Hidden status field -->
                        <input type="hidden" name="status" value="Scheduled">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="applicant_id" class="form-label">Applicant <span class="text-danger">*</span></label>
                                <select class="form-select @error('applicant_id') is-invalid @enderror" 
                                        id="applicant_id" name="applicant_id" required>
                                    <option value="">Select Applicant</option>
                                    @foreach($applicants as $applicant)
                                        <option value="{{ $applicant->id }}" {{ old('applicant_id') == $applicant->id ? 'selected' : '' }}>
                                            {{ $applicant->first_name }} {{ $applicant->last_name }} 
                                            ({{ $applicant->jobPosting->title ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('applicant_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="interview_type" class="form-label">Interview Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('interview_type') is-invalid @enderror" 
                                        id="interview_type" name="interview_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Phone" {{ old('interview_type') == 'Phone' ? 'selected' : '' }}>Phone</option>
                                    <option value="Video" {{ old('interview_type') == 'Video' ? 'selected' : '' }}>Video</option>
                                    <option value="In-Person" {{ old('interview_type') == 'In-Person' ? 'selected' : '' }}>In-Person</option>
                                    <option value="Technical" {{ old('interview_type') == 'Technical' ? 'selected' : '' }}>Technical</option>
                                    <option value="HR" {{ old('interview_type') == 'HR' ? 'selected' : '' }}>HR</option>
                                    <option value="Final" {{ old('interview_type') == 'Final' ? 'selected' : '' }}>Final</option>
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
                                       id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date') }}" required>
                                @error('scheduled_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="scheduled_time" class="form-label">Interview Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('scheduled_time') is-invalid @enderror" 
                                       id="scheduled_time" name="scheduled_time" value="{{ old('scheduled_time') }}" required>
                                @error('scheduled_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="duration" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                       id="duration" name="duration" value="{{ old('duration', 60) }}" 
                                       min="15" max="480" required>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location/Platform</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location') }}" 
                                       placeholder="e.g., Office, Zoom, Google Meet">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="interviewer_id" class="form-label">Interviewer <span class="text-danger">*</span></label>
                            <select class="form-select @error('interviewer_id') is-invalid @enderror" 
                                    id="interviewer_id" name="interviewer_id" required>
                                <option value="">Select Interviewer</option>
                                @foreach($interviewers as $interviewer)
                                    <option value="{{ $interviewer->id }}" {{ old('interviewer_id') == $interviewer->id ? 'selected' : '' }}>
                                        {{ $interviewer->name }} ({{ $interviewer->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('interviewer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="meeting_link" class="form-label">Meeting Link (Optional)</label>
                            <input type="url" class="form-control @error('meeting_link') is-invalid @enderror" 
                                   id="meeting_link" name="meeting_link" value="{{ old('meeting_link') }}" 
                                   placeholder="https://zoom.us/j/...">
                            @error('meeting_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">For online interviews, provide the video call link</small>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes/Instructions</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="4">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Add any additional information for the interview</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('hr.recruitment.interviews.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-2"></i>Schedule Interview
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Interview Guidelines</h6>
                </div>
                <div class="card-body">
                    <h6>Before Scheduling:</h6>
                    <ul class="small">
                        <li>Review the applicant's resume and application</li>
                        <li>Confirm interviewer availability</li>
                        <li>Prepare interview questions</li>
                        <li>Set up meeting room or video link</li>
                    </ul>
                    
                    <h6 class="mt-3">Interview Types:</h6>
                    <ul class="small">
                        <li><strong>Phone Screening:</strong> Initial 15-30 min call</li>
                        <li><strong>HR Interview:</strong> Cultural fit assessment</li>
                        <li><strong>Technical:</strong> Skills and competency test</li>
                        <li><strong>Manager:</strong> Role-specific discussion</li>
                        <li><strong>Panel:</strong> Multiple interviewers</li>
                        <li><strong>Final:</strong> Decision-making round</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Important Notes</h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li>Schedule interviews at least 24 hours in advance</li>
                        <li>Confirm applicant availability before scheduling</li>
                        <li>Send calendar invites to both parties</li>
                        <li>Test video conferencing tools beforehand</li>
                        <li>Keep backup interviewer contact ready</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
