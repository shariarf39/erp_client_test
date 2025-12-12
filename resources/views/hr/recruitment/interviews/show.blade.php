@extends('layouts.app')

@section('title', 'Interview Details - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-comments me-2"></i>Interview Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.recruitment.interviews.index') }}">Interviews</a></li>
                    <li class="breadcrumb-item active">Interview #{{ $interview->id }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Interview Information</h5>
                    <span class="badge bg-light text-dark">{{ $interview->status }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Applicant</h6>
                            <p class="mb-0">
                                <strong>{{ $interview->applicant->first_name }} {{ $interview->applicant->last_name }}</strong>
                            </p>
                            <small class="text-muted">{{ $interview->applicant->email }}</small><br>
                            <small class="text-muted">{{ $interview->applicant->phone }}</small>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Position Applied</h6>
                            <p class="mb-0"><strong>{{ $interview->applicant->jobPosting->title ?? 'N/A' }}</strong></p>
                            <small class="text-muted">{{ $interview->applicant->jobPosting->department->name ?? '' }}</small>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Interview Type</h6>
                            <p class="mb-0"><i class="fas fa-tag me-2"></i>{{ $interview->interview_type }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Interviewer</h6>
                            <p class="mb-0"><i class="fas fa-user me-2"></i>{{ $interview->interviewer->name }}</p>
                            <small class="text-muted">{{ $interview->interviewer->email }}</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Schedule</h6>
                            <p class="mb-0">
                                <i class="fas fa-calendar me-2"></i>{{ $interview->scheduled_date->format('F j, Y') }}<br>
                                <i class="fas fa-clock me-2"></i>{{ $interview->scheduled_time }} ({{ $interview->duration }} minutes)
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Location/Platform</h6>
                            <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>{{ $interview->location ?? 'Not specified' }}</p>
                        </div>
                    </div>

                    @if($interview->meeting_link)
                    <div class="mb-3">
                        <h6 class="text-muted">Meeting Link</h6>
                        <p class="mb-0">
                            <a href="{{ $interview->meeting_link }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fas fa-video me-2"></i>Join Meeting
                            </a>
                        </p>
                    </div>
                    @endif

                    @if($interview->notes)
                    <div class="mb-3">
                        <h6 class="text-muted">Notes/Instructions</h6>
                        <div class="alert alert-info">{{ $interview->notes }}</div>
                    </div>
                    @endif

                    @if($interview->feedback)
                    <div class="mb-3">
                        <h6 class="text-muted">Interview Feedback</h6>
                        <div class="alert alert-success">{{ $interview->feedback }}</div>
                    </div>
                    @endif

                    @if($interview->rating)
                    <div class="mb-3">
                        <h6 class="text-muted">Rating</h6>
                        <p class="mb-0">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $interview->rating ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                            <span class="ms-2">({{ $interview->rating }}/5)</span>
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Actions</h6>
                </div>
                <div class="card-body">
                    @if($interview->status == 'Scheduled')
                        <a href="{{ route('hr.recruitment.interviews.edit', $interview) }}" class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-edit me-2"></i>Reschedule
                        </a>
                        
                        <form action="{{ route('hr.recruitment.interviews.update', $interview) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Completed">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check me-2"></i>Mark as Completed
                            </button>
                        </form>

                        <form action="{{ route('hr.recruitment.interviews.update', $interview) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Cancelled">
                            <button type="submit" class="btn btn-danger w-100" 
                                    onclick="return confirm('Are you sure you want to cancel this interview?')">
                                <i class="fas fa-times me-2"></i>Cancel Interview
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('hr.recruitment.interviews.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Timeline</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <i class="fas fa-plus-circle text-primary me-2"></i>
                            <strong>Scheduled:</strong><br>
                            <small class="text-muted ms-3">{{ $interview->created_at->format('M j, Y H:i') }}</small>
                        </li>
                        @if($interview->updated_at != $interview->created_at)
                        <li class="mb-2">
                            <i class="fas fa-edit text-warning me-2"></i>
                            <strong>Last Updated:</strong><br>
                            <small class="text-muted ms-3">{{ $interview->updated_at->format('M j, Y H:i') }}</small>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
