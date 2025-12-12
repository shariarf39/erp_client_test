@extends('layouts.app')

@section('title', 'Edit Employee Skill - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Employee Skill</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.training.skills.index') }}">Employee Skills</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.training.skills.show', $skill->id) }}">{{ $skill->skill_name }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <form action="{{ route('hr.training.skills.update', $skill->id) }}" method="POST" id="skillForm">
                @csrf
                @method('PUT')

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Employee & Skill Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                            <select class="form-select @error('employee_id') is-invalid @enderror" 
                                    id="employee_id" name="employee_id" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" 
                                            {{ old('employee_id', $skill->employee_id) == $employee->id ? 'selected' : '' }}
                                            data-code="{{ $employee->employee_id }}"
                                            data-department="{{ $employee->department->name ?? 'N/A' }}"
                                            data-designation="{{ $employee->designation->title ?? 'N/A' }}">
                                        {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Only active employees are shown</small>
                        </div>

                        <div id="employeeDetails" class="alert alert-info d-none mb-3">
                            <h6 class="mb-2"><i class="fas fa-user me-2"></i>Employee Details</h6>
                            <div class="row small">
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Employee ID:</strong> <span id="empCode">-</span></p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Department:</strong> <span id="empDepartment">-</span></p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Designation:</strong> <span id="empDesignation">-</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="skill_name" class="form-label">Skill Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('skill_name') is-invalid @enderror" 
                                       id="skill_name" name="skill_name" value="{{ old('skill_name', $skill->skill_name) }}" 
                                       required placeholder="e.g., Python, Leadership, English">
                                @error('skill_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="skill_category" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('skill_category') is-invalid @enderror" 
                                        id="skill_category" name="skill_category" required>
                                    <option value="">Select Category</option>
                                    <option value="Technical" {{ old('skill_category', $skill->skill_category) == 'Technical' ? 'selected' : '' }}>Technical</option>
                                    <option value="Soft Skills" {{ old('skill_category', $skill->skill_category) == 'Soft Skills' ? 'selected' : '' }}>Soft Skills</option>
                                    <option value="Language" {{ old('skill_category', $skill->skill_category) == 'Language' ? 'selected' : '' }}>Language</option>
                                    <option value="Management" {{ old('skill_category', $skill->skill_category) == 'Management' ? 'selected' : '' }}>Management</option>
                                    <option value="Industry Knowledge" {{ old('skill_category', $skill->skill_category) == 'Industry Knowledge' ? 'selected' : '' }}>Industry Knowledge</option>
                                    <option value="Tools & Software" {{ old('skill_category', $skill->skill_category) == 'Tools & Software' ? 'selected' : '' }}>Tools & Software</option>
                                    <option value="Certifications" {{ old('skill_category', $skill->skill_category) == 'Certifications' ? 'selected' : '' }}>Certifications</option>
                                    <option value="Other" {{ old('skill_category', $skill->skill_category) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('skill_category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="proficiency_level" class="form-label">Proficiency Level <span class="text-danger">*</span></label>
                                <select class="form-select @error('proficiency_level') is-invalid @enderror" 
                                        id="proficiency_level" name="proficiency_level" required>
                                    <option value="">Select Level</option>
                                    <option value="Beginner" {{ old('proficiency_level', $skill->proficiency_level) == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="Intermediate" {{ old('proficiency_level', $skill->proficiency_level) == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="Advanced" {{ old('proficiency_level', $skill->proficiency_level) == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                                    <option value="Expert" {{ old('proficiency_level', $skill->proficiency_level) == 'Expert' ? 'selected' : '' }}>Expert</option>
                                </select>
                                @error('proficiency_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="years_of_experience" class="form-label">Years of Experience</label>
                                <input type="number" min="0" step="0.5" class="form-control @error('years_of_experience') is-invalid @enderror" 
                                       id="years_of_experience" name="years_of_experience" 
                                       value="{{ old('years_of_experience', $skill->years_of_experience) }}">
                                @error('years_of_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="last_used" class="form-label">Last Used</label>
                                <input type="date" class="form-control @error('last_used') is-invalid @enderror" 
                                       id="last_used" name="last_used" value="{{ old('last_used', $skill->last_used) }}">
                                @error('last_used')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="2">{{ old('notes', $skill->notes) }}</textarea>
                            <small class="text-muted">Additional details about the skill</small>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Certification Details (Optional)</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input @error('certified') is-invalid @enderror" 
                                       id="certified" name="certified" value="1" 
                                       {{ old('certified', $skill->certified) ? 'checked' : '' }}>
                                <label class="form-check-label" for="certified">
                                    <strong>This skill is certified</strong>
                                    <small class="text-muted d-block">Check if the employee has a formal certification for this skill</small>
                                </label>
                                @error('certified')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div id="certificationFields" class="d-none">
                            <div class="mb-3">
                                <label for="certification_name" class="form-label">Certification Name</label>
                                <input type="text" class="form-control @error('certification_name') is-invalid @enderror" 
                                       id="certification_name" name="certification_name" 
                                       value="{{ old('certification_name', $skill->certification_name) }}"
                                       placeholder="e.g., AWS Certified Solutions Architect">
                                @error('certification_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="certification_date" class="form-label">Certification Date</label>
                                    <input type="date" class="form-control @error('certification_date') is-invalid @enderror" 
                                           id="certification_date" name="certification_date" 
                                           value="{{ old('certification_date', $skill->certification_date) }}">
                                    @error('certification_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                    <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" 
                                           id="expiry_date" name="expiry_date" 
                                           value="{{ old('expiry_date', $skill->expiry_date) }}">
                                    <small class="text-muted">Leave blank if certification does not expire</small>
                                    @error('expiry_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if($skill->expiry_date)
                            @php
                                $expiryDate = \Carbon\Carbon::parse($skill->expiry_date);
                                $daysUntilExpiry = now()->diffInDays($expiryDate, false);
                            @endphp
                            @if($daysUntilExpiry >= 0 && $daysUntilExpiry <= 60)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Renewal Notice:</strong> This certification will expire in {{ $daysUntilExpiry }} days.
                            </div>
                            @elseif($daysUntilExpiry < 0)
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle me-2"></i>
                                <strong>Expired:</strong> This certification expired {{ abs($daysUntilExpiry) }} days ago.
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Skill
                    </button>
                    <a href="{{ route('hr.training.skills.show', $skill->id) }}" class="btn btn-secondary">
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
                            Update proficiency as skills improve
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Keep experience years current
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Record last used date
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Update certification details
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            Monitor expiry dates
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-layer-group me-2"></i>Proficiency Levels</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <span class="badge bg-info">Beginner</span>
                            <span class="d-block mt-1 text-muted">Basic understanding, limited experience</span>
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-primary">Intermediate</span>
                            <span class="d-block mt-1 text-muted">Working knowledge, can work independently</span>
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-warning">Advanced</span>
                            <span class="d-block mt-1 text-muted">Strong expertise, can train others</span>
                        </li>
                        <li>
                            <span class="badge bg-success">Expert</span>
                            <span class="d-block mt-1 text-muted">Master level, industry recognized</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Current Status</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2 small">
                        <strong>Skill:</strong><br>
                        {{ $skill->skill_name }}
                    </p>
                    <p class="mb-2 small">
                        <strong>Current Level:</strong><br>
                        @php
                            $proficiencyColors = [
                                'Beginner' => 'info',
                                'Intermediate' => 'primary',
                                'Advanced' => 'warning',
                                'Expert' => 'success'
                            ];
                        @endphp
                        <span class="badge bg-{{ $proficiencyColors[$skill->proficiency_level] ?? 'secondary' }}">
                            {{ $skill->proficiency_level }}
                        </span>
                    </p>
                    <p class="mb-0 small">
                        <strong>Last Updated:</strong><br>
                        {{ $skill->updated_at->format('d M Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const employeeSelect = document.getElementById('employee_id');
    const employeeDetails = document.getElementById('employeeDetails');
    const certifiedCheckbox = document.getElementById('certified');
    const certificationFields = document.getElementById('certificationFields');

    // Show employee details when selected
    employeeSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (this.value) {
            document.getElementById('empCode').textContent = selected.dataset.code || '-';
            document.getElementById('empDepartment').textContent = selected.dataset.department || '-';
            document.getElementById('empDesignation').textContent = selected.dataset.designation || '-';
            employeeDetails.classList.remove('d-none');
        } else {
            employeeDetails.classList.add('d-none');
        }
    });

    // Show/hide certification fields
    function toggleCertificationFields() {
        if (certifiedCheckbox.checked) {
            certificationFields.classList.remove('d-none');
        } else {
            certificationFields.classList.add('d-none');
        }
    }

    certifiedCheckbox.addEventListener('change', toggleCertificationFields);
    
    // Initial state
    if (employeeSelect.value) {
        employeeSelect.dispatchEvent(new Event('change'));
    }
    toggleCertificationFields();
});
</script>
@endsection
