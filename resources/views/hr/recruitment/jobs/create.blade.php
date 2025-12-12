@extends('layouts.app')

@section('title', 'Create Job Posting - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-briefcase me-2"></i>Create Job Posting</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.recruitment.jobs.index') }}">Job Postings</a></li>
                    <li class="breadcrumb-item active">Create New</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>New Job Posting</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hr.recruitment.jobs.store') }}" method="POST">
                        @csrf
                        
                        <!-- Basic Information -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-info-circle me-2"></i>Basic Information</h6>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="job_code" class="form-label">Job Code</label>
                                <input type="text" class="form-control" id="job_code" 
                                       name="job_code" value="{{ $jobCode }}" readonly>
                            </div>
                            
                            <div class="col-md-8 mb-3">
                                <label for="title" class="form-label">Job Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" 
                                       placeholder="e.g., Senior Software Engineer" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                                <select class="form-select @error('department_id') is-invalid @enderror" 
                                        id="department_id" name="department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="designation_id" class="form-label">Designation</label>
                                <select class="form-select @error('designation_id') is-invalid @enderror" 
                                        id="designation_id" name="designation_id">
                                    <option value="">Select Designation</option>
                                    @foreach($designations as $designation)
                                        <option value="{{ $designation->id }}" {{ old('designation_id') == $designation->id ? 'selected' : '' }}>
                                            {{ $designation->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('designation_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="employment_type" class="form-label">Employment Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('employment_type') is-invalid @enderror" 
                                        id="employment_type" name="employment_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Full-Time" {{ old('employment_type') == 'Full-Time' ? 'selected' : '' }}>Full-Time</option>
                                    <option value="Part-Time" {{ old('employment_type') == 'Part-Time' ? 'selected' : '' }}>Part-Time</option>
                                    <option value="Contract" {{ old('employment_type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="Internship" {{ old('employment_type') == 'Internship' ? 'selected' : '' }}>Internship</option>
                                    <option value="Temporary" {{ old('employment_type') == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                                </select>
                                @error('employment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location') }}" 
                                       placeholder="e.g., Dhaka, Bangladesh" required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="vacancies" class="form-label">Number of Vacancies <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('vacancies') is-invalid @enderror" 
                                       id="vacancies" name="vacancies" value="{{ old('vacancies', 1) }}" 
                                       min="1" max="100" required>
                                @error('vacancies')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Salary & Experience -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-money-bill-wave me-2"></i>Salary & Experience</h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="min_salary" class="form-label">Minimum Salary (BDT)</label>
                                <input type="number" class="form-control @error('min_salary') is-invalid @enderror" 
                                       id="min_salary" name="min_salary" value="{{ old('min_salary') }}" 
                                       min="0" step="1000">
                                @error('min_salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="max_salary" class="form-label">Maximum Salary (BDT)</label>
                                <input type="number" class="form-control @error('max_salary') is-invalid @enderror" 
                                       id="max_salary" name="max_salary" value="{{ old('max_salary') }}" 
                                       min="0" step="1000">
                                @error('max_salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="min_experience" class="form-label">Minimum Experience (years)</label>
                                <input type="number" class="form-control @error('min_experience') is-invalid @enderror" 
                                       id="min_experience" name="min_experience" value="{{ old('min_experience', 0) }}" 
                                       min="0" max="50" step="0.5">
                                @error('min_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="max_experience" class="form-label">Maximum Experience (years)</label>
                                <input type="number" class="form-control @error('max_experience') is-invalid @enderror" 
                                       id="max_experience" name="max_experience" value="{{ old('max_experience') }}" 
                                       min="0" max="50" step="0.5">
                                @error('max_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Job Details -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-file-alt me-2"></i>Job Details</h6>

                        <div class="mb-3">
                            <label for="description" class="form-label">Job Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="6" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Provide a detailed description of the role and responsibilities</small>
                        </div>

                        <div class="mb-3">
                            <label for="requirements" class="form-label">Requirements <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('requirements') is-invalid @enderror" 
                                      id="requirements" name="requirements" rows="6" required>{{ old('requirements') }}</textarea>
                            @error('requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">List education, skills, and experience requirements</small>
                        </div>

                        <div class="mb-3">
                            <label for="benefits" class="form-label">Benefits</label>
                            <textarea class="form-control @error('benefits') is-invalid @enderror" 
                                      id="benefits" name="benefits" rows="4">{{ old('benefits') }}</textarea>
                            @error('benefits')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">List perks and benefits offered</small>
                        </div>

                        <hr class="my-4">

                        <!-- Application Details -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-calendar me-2"></i>Application Details</h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="application_deadline" class="form-label">Application Deadline <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('application_deadline') is-invalid @enderror" 
                                       id="application_deadline" name="application_deadline" 
                                       value="{{ old('application_deadline') }}" required>
                                @error('application_deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="Draft" {{ old('status', 'Draft') == 'Draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Closed" {{ old('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_remote" name="is_remote" 
                                   value="1" {{ old('is_remote') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_remote">
                                Remote Work Available
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_urgent" name="is_urgent" 
                                   value="1" {{ old('is_urgent') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_urgent">
                                Urgent Hiring
                            </label>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('hr.recruitment.jobs.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Job Posting
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
