@extends('layouts.app')

@section('title', 'Applicant Details - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-user me-2"></i>Applicant Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.recruitment.applicants.index') }}">Applicants</a></li>
                    <li class="breadcrumb-item active">{{ $applicant->first_name }} {{ $applicant->last_name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h5>
                    <span class="badge bg-light text-dark">{{ $applicant->status }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Application Code</h6>
                            <p class="mb-0"><strong>{{ $applicant->application_code }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Full Name</h6>
                            <p class="mb-0"><strong>{{ $applicant->first_name }} {{ $applicant->last_name }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Email</h6>
                            <p class="mb-0"><i class="fas fa-envelope me-2"></i>{{ $applicant->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Phone</h6>
                            <p class="mb-0"><i class="fas fa-phone me-2"></i>{{ $applicant->phone }}</p>
                        </div>
                    </div>

                    @if($applicant->address)
                    <div class="mb-3">
                        <h6 class="text-muted">Address</h6>
                        <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>{{ $applicant->address }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Job Application Details -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Job Application</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Position Applied</h6>
                            <p class="mb-0"><strong>{{ $applicant->jobPosting->title ?? 'N/A' }}</strong></p>
                            <small class="text-muted">{{ $applicant->jobPosting->department->name ?? '' }}</small>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Application Source</h6>
                            <p class="mb-0">{{ $applicant->source ?? 'Not specified' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Experience</h6>
                            <p class="mb-0">{{ $applicant->experience_years ?? 0 }} years</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Expected Salary</h6>
                            <p class="mb-0">{{ $applicant->expected_salary ? '৳' . number_format($applicant->expected_salary) : 'Not specified' }}</p>
                        </div>
                    </div>

                    @if($applicant->education)
                    <div class="mb-3">
                        <h6 class="text-muted">Education</h6>
                        <p class="mb-0">{{ $applicant->education }}</p>
                    </div>
                    @endif

                    @if($applicant->skills)
                    <div class="mb-3">
                        <h6 class="text-muted">Skills</h6>
                        <p class="mb-0">
                            @foreach(explode(',', $applicant->skills) as $skill)
                                <span class="badge bg-secondary me-1">{{ trim($skill) }}</span>
                            @endforeach
                        </p>
                    </div>
                    @endif

                    @if($applicant->notes)
                    <div class="mb-3">
                        <h6 class="text-muted">Notes</h6>
                        <div class="alert alert-light">{{ $applicant->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Documents -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Documents & Links</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Resume/CV</h6>
                            @if($applicant->resume_path)
                                <a href="{{ Storage::url($applicant->resume_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-download me-2"></i>Download Resume
                                </a>
                            @else
                                <p class="text-muted">Not uploaded</p>
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Cover Letter</h6>
                            @if($applicant->cover_letter_path)
                                <a href="{{ Storage::url($applicant->cover_letter_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-download me-2"></i>Download Cover Letter
                                </a>
                            @else
                                <p class="text-muted">Not uploaded</p>
                            @endif
                        </div>
                    </div>

                    @if($applicant->linkedin_url)
                    <div class="mb-2">
                        <a href="{{ $applicant->linkedin_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fab fa-linkedin me-2"></i>LinkedIn Profile
                        </a>
                    </div>
                    @endif

                    @if($applicant->portfolio_url)
                    <div class="mb-2">
                        <a href="{{ $applicant->portfolio_url }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-globe me-2"></i>Portfolio/Website
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Actions -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('hr.recruitment.applicants.edit', $applicant) }}" class="btn btn-warning w-100 mb-2">
                        <i class="fas fa-edit me-2"></i>Edit Applicant
                    </a>

                    <a href="{{ route('hr.recruitment.interviews.create') }}?applicant={{ $applicant->id }}" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-calendar-plus me-2"></i>Schedule Interview
                    </a>

                    <form action="{{ route('hr.recruitment.applicants.destroy', $applicant) }}" method="POST" class="mb-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('Are you sure you want to delete this applicant?')">
                            <i class="fas fa-trash me-2"></i>Delete Applicant
                        </button>
                    </form>

                    <a href="{{ route('hr.recruitment.applicants.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>

            <!-- Status Update -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-tasks me-2"></i>Update Status</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('hr.recruitment.applicants.update', $applicant) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select class="form-select mb-2" name="status" required>
                            <option value="New" {{ $applicant->status == 'New' ? 'selected' : '' }}>New</option>
                            <option value="Screening" {{ $applicant->status == 'Screening' ? 'selected' : '' }}>Screening</option>
                            <option value="Interview" {{ $applicant->status == 'Interview' ? 'selected' : '' }}>Interview</option>
                            <option value="Assessment" {{ $applicant->status == 'Assessment' ? 'selected' : '' }}>Assessment</option>
                            <option value="Offer" {{ $applicant->status == 'Offer' ? 'selected' : '' }}>Offer</option>
                            <option value="Hired" {{ $applicant->status == 'Hired' ? 'selected' : '' }}>Hired</option>
                            <option value="Rejected" {{ $applicant->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Withdrawn" {{ $applicant->status == 'Withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                        </select>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check me-2"></i>Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Timeline</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <i class="fas fa-plus-circle text-success me-2"></i>
                            <strong>Applied:</strong><br>
                            <small class="text-muted ms-3">{{ $applicant->applied_date ? $applicant->applied_date->format('M j, Y') : $applicant->created_at->format('M j, Y') }}</small>
                        </li>
                        @if($applicant->updated_at != $applicant->created_at)
                        <li class="mb-2">
                            <i class="fas fa-edit text-warning me-2"></i>
                            <strong>Last Updated:</strong><br>
                            <small class="text-muted ms-3">{{ $applicant->updated_at->format('M j, Y H:i') }}</small>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
