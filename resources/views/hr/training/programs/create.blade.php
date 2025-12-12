@extends('layouts.app')

@section('title', 'Create Training Program - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Create Training Program</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.training.programs.index') }}">Training Programs</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <form action="{{ route('hr.training.programs.store') }}" method="POST">
                @csrf

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Program Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="program_code" class="form-label">Program Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('program_code') is-invalid @enderror" 
                                       id="program_code" name="program_code" value="{{ old('program_code', $programCode) }}" required>
                                @error('program_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <label for="title" class="form-label">Program Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            <small class="text-muted">Detailed description of the training program</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                        id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Technical" {{ old('category') == 'Technical' ? 'selected' : '' }}>Technical</option>
                                    <option value="Soft Skills" {{ old('category') == 'Soft Skills' ? 'selected' : '' }}>Soft Skills</option>
                                    <option value="Leadership" {{ old('category') == 'Leadership' ? 'selected' : '' }}>Leadership</option>
                                    <option value="Compliance" {{ old('category') == 'Compliance' ? 'selected' : '' }}>Compliance</option>
                                    <option value="Safety" {{ old('category') == 'Safety' ? 'selected' : '' }}>Safety</option>
                                    <option value="Product" {{ old('category') == 'Product' ? 'selected' : '' }}>Product</option>
                                    <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="training_type" class="form-label">Training Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('training_type') is-invalid @enderror" 
                                        id="training_type" name="training_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Classroom" {{ old('training_type') == 'Classroom' ? 'selected' : '' }}>Classroom</option>
                                    <option value="Online" {{ old('training_type') == 'Online' ? 'selected' : '' }}>Online</option>
                                    <option value="On-the-Job" {{ old('training_type') == 'On-the-Job' ? 'selected' : '' }}>On-the-Job</option>
                                    <option value="Workshop" {{ old('training_type') == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                                    <option value="Seminar" {{ old('training_type') == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                                    <option value="Conference" {{ old('training_type') == 'Conference' ? 'selected' : '' }}>Conference</option>
                                    <option value="Certification" {{ old('training_type') == 'Certification' ? 'selected' : '' }}>Certification</option>
                                </select>
                                @error('training_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="trainer_name" class="form-label">Trainer Name</label>
                                <input type="text" class="form-control @error('trainer_name') is-invalid @enderror" 
                                       id="trainer_name" name="trainer_name" value="{{ old('trainer_name') }}">
                                @error('trainer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="trainer_type" class="form-label">Trainer Type</label>
                                <select class="form-select @error('trainer_type') is-invalid @enderror" 
                                        id="trainer_type" name="trainer_type">
                                    <option value="">Select Type</option>
                                    <option value="Internal" {{ old('trainer_type') == 'Internal' ? 'selected' : '' }}>Internal</option>
                                    <option value="External" {{ old('trainer_type') == 'External' ? 'selected' : '' }}>External</option>
                                    <option value="Online Platform" {{ old('trainer_type') == 'Online Platform' ? 'selected' : '' }}>Online Platform</option>
                                </select>
                                @error('trainer_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="duration_days" class="form-label">Duration (Days)</label>
                                <input type="number" step="0.5" min="0" class="form-control @error('duration_days') is-invalid @enderror" 
                                       id="duration_days" name="duration_days" value="{{ old('duration_days') }}">
                                @error('duration_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="duration_hours" class="form-label">Duration (Hours)</label>
                                <input type="number" step="0.5" min="0" class="form-control @error('duration_hours') is-invalid @enderror" 
                                       id="duration_hours" name="duration_hours" value="{{ old('duration_hours') }}">
                                @error('duration_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="max_participants" class="form-label">Max Participants</label>
                                <input type="number" min="1" class="form-control @error('max_participants') is-invalid @enderror" 
                                       id="max_participants" name="max_participants" value="{{ old('max_participants') }}">
                                @error('max_participants')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="cost_per_participant" class="form-label">Cost (৳)</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('cost_per_participant') is-invalid @enderror" 
                                       id="cost_per_participant" name="cost_per_participant" value="{{ old('cost_per_participant') }}">
                                @error('cost_per_participant')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="venue" class="form-label">Venue</label>
                            <input type="text" class="form-control @error('venue') is-invalid @enderror" 
                                   id="venue" name="venue" value="{{ old('venue') }}">
                            <small class="text-muted">Training location or online platform</small>
                            @error('venue')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" value="{{ old('start_date') }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" name="end_date" value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="enrollment_deadline" class="form-label">Enrollment Deadline</label>
                                <input type="date" class="form-control @error('enrollment_deadline') is-invalid @enderror" 
                                       id="enrollment_deadline" name="enrollment_deadline" value="{{ old('enrollment_deadline') }}">
                                @error('enrollment_deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="prerequisites" class="form-label">Prerequisites</label>
                            <textarea class="form-control @error('prerequisites') is-invalid @enderror" 
                                      id="prerequisites" name="prerequisites" rows="2">{{ old('prerequisites') }}</textarea>
                            <small class="text-muted">Requirements or qualifications needed</small>
                            @error('prerequisites')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="objectives" class="form-label">Learning Objectives</label>
                            <textarea class="form-control @error('objectives') is-invalid @enderror" 
                                      id="objectives" name="objectives" rows="3">{{ old('objectives') }}</textarea>
                            <small class="text-muted">What participants will learn</small>
                            @error('objectives')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="Planned" {{ old('status', 'Planned') == 'Planned' ? 'selected' : '' }}>Planned</option>
                                <option value="Open for Enrollment" {{ old('status') == 'Open for Enrollment' ? 'selected' : '' }}>Open for Enrollment</option>
                                <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Program
                    </button>
                    <a href="{{ route('hr.training.programs.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>

        <div class="col-lg-3">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Guidelines</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Define clear learning objectives
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Set realistic duration and capacity
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Specify prerequisites if any
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Plan enrollment timeline
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-list me-2"></i>Categories</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-1"><strong>Technical:</strong> Tech skills, tools</li>
                        <li class="mb-1"><strong>Soft Skills:</strong> Communication, teamwork</li>
                        <li class="mb-1"><strong>Leadership:</strong> Management, strategy</li>
                        <li class="mb-1"><strong>Compliance:</strong> Regulations, policies</li>
                        <li class="mb-1"><strong>Safety:</strong> Health and safety</li>
                        <li class="mb-1"><strong>Product:</strong> Product knowledge</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
