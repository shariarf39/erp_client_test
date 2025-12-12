@extends('layouts.app')

@section('title', 'Edit Applicant - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-user-edit me-2"></i>Edit Applicant</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.recruitment.applicants.index') }}">Applicants</a></li>
                    <li class="breadcrumb-item active">Edit {{ $applicant->first_name }} {{ $applicant->last_name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Update Applicant Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hr.recruitment.applicants.update', $applicant) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-user me-2"></i>Personal Details</h6>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="application_code" class="form-label">Application Code</label>
                                <input type="text" class="form-control" value="{{ $applicant->application_code }}" readonly>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" name="first_name" value="{{ old('first_name', $applicant->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" name="last_name" value="{{ old('last_name', $applicant->last_name) }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $applicant->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $applicant->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="2">{{ old('address', $applicant->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Job Application Details -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-briefcase me-2"></i>Job Application</h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="job_posting_id" class="form-label">Position Applied <span class="text-danger">*</span></label>
                                <select class="form-select @error('job_posting_id') is-invalid @enderror" 
                                        id="job_posting_id" name="job_posting_id" required>
                                    <option value="">Select Position</option>
                                    @foreach($jobPostings as $job)
                                        <option value="{{ $job->id }}" {{ old('job_posting_id', $applicant->job_posting_id) == $job->id ? 'selected' : '' }}>
                                            {{ $job->title }} - {{ $job->department->name ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('job_posting_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="source" class="form-label">Application Source</label>
                                <select class="form-select @error('source') is-invalid @enderror" id="source" name="source">
                                    <option value="">Select Source</option>
                                    <option value="Website" {{ old('source', $applicant->source) == 'Website' ? 'selected' : '' }}>Website</option>
                                    <option value="Job Board" {{ old('source', $applicant->source) == 'Job Board' ? 'selected' : '' }}>Job Board</option>
                                    <option value="LinkedIn" {{ old('source', $applicant->source) == 'LinkedIn' ? 'selected' : '' }}>LinkedIn</option>
                                    <option value="Referral" {{ old('source', $applicant->source) == 'Referral' ? 'selected' : '' }}>Referral</option>
                                    <option value="Career Fair" {{ old('source', $applicant->source) == 'Career Fair' ? 'selected' : '' }}>Career Fair</option>
                                    <option value="Direct" {{ old('source', $applicant->source) == 'Direct' ? 'selected' : '' }}>Direct Application</option>
                                    <option value="Other" {{ old('source', $applicant->source) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="experience_years" class="form-label">Years of Experience</label>
                                <input type="number" class="form-control @error('experience_years') is-invalid @enderror" 
                                       id="experience_years" name="experience_years" 
                                       value="{{ old('experience_years', $applicant->experience_years) }}" 
                                       min="0" max="50" step="0.5">
                                @error('experience_years')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="expected_salary" class="form-label">Expected Salary</label>
                                <input type="number" class="form-control @error('expected_salary') is-invalid @enderror" 
                                       id="expected_salary" name="expected_salary" 
                                       value="{{ old('expected_salary', $applicant->expected_salary) }}" 
                                       min="0" step="1000">
                                @error('expected_salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="education" class="form-label">Highest Education</label>
                            <input type="text" class="form-control @error('education') is-invalid @enderror" 
                                   id="education" name="education" value="{{ old('education', $applicant->education) }}">
                            @error('education')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="skills" class="form-label">Skills</label>
                            <textarea class="form-control @error('skills') is-invalid @enderror" 
                                      id="skills" name="skills" rows="2">{{ old('skills', $applicant->skills) }}</textarea>
                            @error('skills')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Separate multiple skills with commas</small>
                        </div>

                        <hr class="my-4">

                        <!-- Documents -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-file-upload me-2"></i>Documents</h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="resume" class="form-label">Resume/CV</label>
                                @if($applicant->resume_path)
                                    <div class="mb-2">
                                        <small class="text-muted">Current: 
                                            <a href="{{ Storage::url($applicant->resume_path) }}" target="_blank">View Resume</a>
                                        </small>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('resume') is-invalid @enderror" 
                                       id="resume" name="resume" accept=".pdf,.doc,.docx">
                                @error('resume')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave empty to keep current file</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cover_letter" class="form-label">Cover Letter</label>
                                @if($applicant->cover_letter_path)
                                    <div class="mb-2">
                                        <small class="text-muted">Current: 
                                            <a href="{{ Storage::url($applicant->cover_letter_path) }}" target="_blank">View Cover Letter</a>
                                        </small>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('cover_letter') is-invalid @enderror" 
                                       id="cover_letter" name="cover_letter" accept=".pdf,.doc,.docx">
                                @error('cover_letter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave empty to keep current file</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="linkedin_url" class="form-label">LinkedIn Profile URL</label>
                            <input type="url" class="form-control @error('linkedin_url') is-invalid @enderror" 
                                   id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $applicant->linkedin_url) }}">
                            @error('linkedin_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="portfolio_url" class="form-label">Portfolio/Website URL</label>
                            <input type="url" class="form-control @error('portfolio_url') is-invalid @enderror" 
                                   id="portfolio_url" name="portfolio_url" value="{{ old('portfolio_url', $applicant->portfolio_url) }}">
                            @error('portfolio_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Additional Info -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes', $applicant->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="New" {{ old('status', $applicant->status) == 'New' ? 'selected' : '' }}>New</option>
                                <option value="Screening" {{ old('status', $applicant->status) == 'Screening' ? 'selected' : '' }}>Screening</option>
                                <option value="Interview" {{ old('status', $applicant->status) == 'Interview' ? 'selected' : '' }}>Interview</option>
                                <option value="Assessment" {{ old('status', $applicant->status) == 'Assessment' ? 'selected' : '' }}>Assessment</option>
                                <option value="Offer" {{ old('status', $applicant->status) == 'Offer' ? 'selected' : '' }}>Offer</option>
                                <option value="Hired" {{ old('status', $applicant->status) == 'Hired' ? 'selected' : '' }}>Hired</option>
                                <option value="Rejected" {{ old('status', $applicant->status) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="Withdrawn" {{ old('status', $applicant->status) == 'Withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('hr.recruitment.applicants.show', $applicant) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Applicant
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
