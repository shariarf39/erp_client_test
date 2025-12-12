@extends('layouts.app')

@section('title', 'Edit Job Posting - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Job Posting</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.recruitment.jobs.index') }}">Job Postings</a></li>
                    <li class="breadcrumb-item active">Edit Job</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <form action="{{ route('hr.recruitment.jobs.update', $jobPosting) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="job_code" class="form-label">Job Code</label>
                                <input type="text" class="form-control @error('job_code') is-invalid @enderror" 
                                       id="job_code" name="job_code" value="{{ old('job_code', $jobPosting->job_code) }}" readonly>
                                @error('job_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="title" class="form-label">Job Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $jobPosting->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                                <select class="form-select @error('department_id') is-invalid @enderror" 
                                        id="department_id" name="department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" 
                                                {{ old('department_id', $jobPosting->department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="designation_id" class="form-label">Designation <span class="text-danger">*</span></label>
                                <select class="form-select @error('designation_id') is-invalid @enderror" 
                                        id="designation_id" name="designation_id" required>
                                    <option value="">Select Designation</option>
                                    @foreach($designations as $designation)
                                        <option value="{{ $designation->id }}" 
                                                {{ old('designation_id', $jobPosting->designation_id) == $designation->id ? 'selected' : '' }}>
                                            {{ $designation->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('designation_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="employment_type" class="form-label">Employment Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('employment_type') is-invalid @enderror" 
                                        id="employment_type" name="employment_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Full-Time" {{ old('employment_type', $jobPosting->employment_type) == 'Full-Time' ? 'selected' : '' }}>Full-Time</option>
                                    <option value="Part-Time" {{ old('employment_type', $jobPosting->employment_type) == 'Part-Time' ? 'selected' : '' }}>Part-Time</option>
                                    <option value="Contract" {{ old('employment_type', $jobPosting->employment_type) == 'Contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="Temporary" {{ old('employment_type', $jobPosting->employment_type) == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                                    <option value="Internship" {{ old('employment_type', $jobPosting->employment_type) == 'Internship' ? 'selected' : '' }}>Internship</option>
                                </select>
                                @error('employment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location', $jobPosting->location) }}" required>
                                <small class="text-muted">City or specific address</small>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="vacancies" class="form-label">Number of Vacancies <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('vacancies') is-invalid @enderror" 
                                       id="vacancies" name="vacancies" min="1" value="{{ old('vacancies', $jobPosting->vacancies) }}" required>
                                @error('vacancies')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Salary & Experience</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="min_salary" class="form-label">Minimum Salary (৳)</label>
                                <input type="number" class="form-control @error('min_salary') is-invalid @enderror" 
                                       id="min_salary" name="min_salary" step="1000" value="{{ old('min_salary', $jobPosting->min_salary) }}">
                                @error('min_salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="max_salary" class="form-label">Maximum Salary (৳)</label>
                                <input type="number" class="form-control @error('max_salary') is-invalid @enderror" 
                                       id="max_salary" name="max_salary" step="1000" value="{{ old('max_salary', $jobPosting->max_salary) }}">
                                @error('max_salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="min_experience" class="form-label">Minimum Experience (years)</label>
                                <input type="number" class="form-control @error('min_experience') is-invalid @enderror" 
                                       id="min_experience" name="min_experience" min="0" step="0.5" value="{{ old('min_experience', $jobPosting->min_experience) }}">
                                @error('min_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="max_experience" class="form-label">Maximum Experience (years)</label>
                                <input type="number" class="form-control @error('max_experience') is-invalid @enderror" 
                                       id="max_experience" name="max_experience" min="0" step="0.5" value="{{ old('max_experience', $jobPosting->max_experience) }}">
                                @error('max_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Job Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="description" class="form-label">Job Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="6" required>{{ old('description', $jobPosting->description) }}</textarea>
                            <small class="text-muted">Detailed job description including responsibilities and expectations</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="requirements" class="form-label">Requirements <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('requirements') is-invalid @enderror" 
                                      id="requirements" name="requirements" rows="6" required>{{ old('requirements', $jobPosting->requirements) }}</textarea>
                            <small class="text-muted">Skills, qualifications, and experience required</small>
                            @error('requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="benefits" class="form-label">Benefits</label>
                            <textarea class="form-control @error('benefits') is-invalid @enderror" 
                                      id="benefits" name="benefits" rows="4">{{ old('benefits', $jobPosting->benefits) }}</textarea>
                            <small class="text-muted">Benefits, perks, and incentives offered</small>
                            @error('benefits')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Application Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="application_deadline" class="form-label">Application Deadline</label>
                                <input type="date" class="form-control @error('application_deadline') is-invalid @enderror" 
                                       id="application_deadline" name="application_deadline" 
                                       value="{{ old('application_deadline', $jobPosting->application_deadline ? $jobPosting->application_deadline->format('Y-m-d') : '') }}">
                                @error('application_deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="Draft" {{ old('status', $jobPosting->status) == 'Draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="Active" {{ old('status', $jobPosting->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Closed" {{ old('status', $jobPosting->status) == 'Closed' ? 'selected' : '' }}>Closed</option>
                                    <option value="On Hold" {{ old('status', $jobPosting->status) == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_remote" name="is_remote" value="1"
                                           {{ old('is_remote', $jobPosting->is_remote) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_remote">
                                        <i class="fas fa-home me-1"></i> Remote Work Available
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_urgent" name="is_urgent" value="1"
                                           {{ old('is_urgent', $jobPosting->is_urgent) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_urgent">
                                        <i class="fas fa-bolt me-1"></i> Urgent Hiring
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Job Posting
                    </button>
                    <a href="{{ route('hr.recruitment.jobs.show', $jobPosting) }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>

        <div class="col-lg-3">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Edit Information</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Update job details and requirements
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Change status to control visibility
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Set or update application deadline
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            Existing applicants remain unaffected
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Status Guide</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Draft:</strong>
                        <small class="text-muted d-block">Not visible to applicants, still being prepared</small>
                    </div>
                    <div class="mb-2">
                        <strong>Active:</strong>
                        <small class="text-muted d-block">Accepting applications, visible on job board</small>
                    </div>
                    <div class="mb-2">
                        <strong>On Hold:</strong>
                        <small class="text-muted d-block">Temporarily paused, no new applications</small>
                    </div>
                    <div>
                        <strong>Closed:</strong>
                        <small class="text-muted d-block">No longer accepting applications</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
