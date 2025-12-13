@extends('layouts.app')

@section('title', 'Job Posting Details - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-briefcase me-2"></i>Job Posting Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.recruitment.jobs.index') }}">Job Postings</a></li>
                    <li class="breadcrumb-item active">{{ $job->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $job->title }}</h5>
                    <span class="badge bg-light text-dark">{{ $job->status }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Job Code</h6>
                            <p class="mb-0"><strong>{{ $job->job_code }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Department</h6>
                            <p class="mb-0">{{ $job->department->name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Employment Type</h6>
                            <p class="mb-0"><span class="badge bg-info">{{ $job->employment_type }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Location</h6>
                            <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>{{ $job->location }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Vacancies</h6>
                            <p class="mb-0">{{ $job->vacancies }} positions</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Application Deadline</h6>
                            <p class="mb-0">
                                <i class="fas fa-calendar me-2"></i>
                                {{ $job->application_deadline ? $job->application_deadline->format('F j, Y') : 'Not set' }}
                            </p>
                        </div>
                    </div>

                    @if($job->min_salary || $job->max_salary)
                    <div class="mb-3">
                        <h6 class="text-muted">Salary Range</h6>
                        <p class="mb-0">
                            <i class="fas fa-money-bill-wave me-2"></i>
                            ৳{{ number_format($job->min_salary ?? 0) }} - ৳{{ number_format($job->max_salary ?? 0) }}
                        </p>
                    </div>
                    @endif

                    @if($job->min_experience || $job->max_experience)
                    <div class="mb-3">
                        <h6 class="text-muted">Experience Required</h6>
                        <p class="mb-0">
                            {{ $job->min_experience ?? 0 }} - {{ $job->max_experience ?? 0 }} years
                        </p>
                    </div>
                    @endif

                    <div class="d-flex gap-2 mb-3">
                        @if($job->is_remote)
                            <span class="badge bg-success"><i class="fas fa-home me-1"></i>Remote Available</span>
                        @endif
                        @if($job->is_urgent)
                            <span class="badge bg-danger"><i class="fas fa-bolt me-1"></i>Urgent Hiring</span>
                        @endif
                    </div>

                    <hr>

                    <div class="mb-4">
                        <h5 class="text-primary mb-3"><i class="fas fa-file-alt me-2"></i>Job Description</h5>
                        <p style="white-space: pre-line;">{{ $job->description }}</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-primary mb-3"><i class="fas fa-check-circle me-2"></i>Requirements</h5>
                        <p style="white-space: pre-line;">{{ $job->requirements }}</p>
                    </div>

                    @if($job->benefits)
                    <div class="mb-4">
                        <h5 class="text-primary mb-3"><i class="fas fa-gift me-2"></i>Benefits</h5>
                        <p style="white-space: pre-line;">{{ $job->benefits }}</p>
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
                    <a href="{{ route('hr.recruitment.jobs.edit', $job) }}" class="btn btn-warning w-100 mb-2">
                        <i class="fas fa-edit me-2"></i>Edit Job Posting
                    </a>

                    <form action="{{ route('hr.recruitment.jobs.destroy', $job) }}" method="POST" class="mb-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('Are you sure you want to delete this job posting?')">
                            <i class="fas fa-trash me-2"></i>Delete Posting
                        </button>
                    </form>

                    <a href="{{ route('hr.recruitment.jobs.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Applications:</span>
                        <strong>{{ $job->applicants()->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>New:</span>
                        <strong>{{ $job->applicants()->where('status', 'New')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>In Interview:</span>
                        <strong>{{ $job->applicants()->where('status', 'Interview')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Hired:</span>
                        <strong class="text-success">{{ $job->applicants()->where('status', 'Hired')->count() }}</strong>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Timeline</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <i class="fas fa-plus-circle text-primary me-2"></i>
                            <strong>Posted:</strong><br>
                            <small class="text-muted ms-3">{{ $job->created_at->format('M j, Y H:i') }}</small>
                        </li>
                        @if($job->updated_at != $job->created_at)
                        <li class="mb-2">
                            <i class="fas fa-edit text-warning me-2"></i>
                            <strong>Last Updated:</strong><br>
                            <small class="text-muted ms-3">{{ $job->updated_at->format('M j, Y H:i') }}</small>
                        </li>
                        @endif
                        @if($job->application_deadline)
                        <li>
                            <i class="fas fa-hourglass-end text-danger me-2"></i>
                            <strong>Deadline:</strong><br>
                            <small class="text-muted ms-3">{{ $job->application_deadline->format('M j, Y') }}</small>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

