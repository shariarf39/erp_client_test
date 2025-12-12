@extends('layouts.app')

@section('title', 'Skill Details - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-award me-2"></i>{{ $skill->skill_name }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.training.skills.index') }}">Employee Skills</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Skill Information</h5>
                        @php
                            $proficiencyColors = [
                                'Beginner' => 'info',
                                'Intermediate' => 'primary',
                                'Advanced' => 'warning',
                                'Expert' => 'success'
                            ];
                        @endphp
                        <span class="badge bg-{{ $proficiencyColors[$skill->proficiency_level] ?? 'secondary' }} fs-6">
                            {{ $skill->proficiency_level }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Employee Information</h6>
                            <p class="mb-2">
                                <strong>Name:</strong><br>
                                <span class="ms-3">{{ $skill->employee->first_name ?? '' }} {{ $skill->employee->last_name ?? '' }}</span>
                            </p>
                            <p class="mb-2">
                                <strong>Employee ID:</strong><br>
                                <span class="ms-3">{{ $skill->employee->employee_id ?? 'N/A' }}</span>
                            </p>
                            <p class="mb-2">
                                <strong>Department:</strong><br>
                                <span class="ms-3">{{ $skill->employee->department->name ?? 'N/A' }}</span>
                            </p>
                            <p class="mb-2">
                                <strong>Designation:</strong><br>
                                <span class="ms-3">{{ $skill->employee->designation->title ?? 'N/A' }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success mb-3"><i class="fas fa-star me-2"></i>Skill Details</h6>
                            <p class="mb-2">
                                <strong>Skill Name:</strong><br>
                                <span class="ms-3 fs-5 text-primary">{{ $skill->skill_name }}</span>
                            </p>
                            <p class="mb-2">
                                <strong>Category:</strong><br>
                                <span class="ms-3">
                                    @php
                                        $categoryIcons = [
                                            'Technical' => 'code',
                                            'Soft Skills' => 'comments',
                                            'Language' => 'language',
                                            'Management' => 'users-cog',
                                            'Industry Knowledge' => 'industry',
                                            'Tools & Software' => 'tools',
                                            'Certifications' => 'certificate',
                                            'Other' => 'tag'
                                        ];
                                    @endphp
                                    <i class="fas fa-{{ $categoryIcons[$skill->skill_category] ?? 'tag' }} me-1"></i>
                                    {{ $skill->skill_category }}
                                </span>
                            </p>
                            <p class="mb-2">
                                <strong>Proficiency Level:</strong><br>
                                <span class="ms-3">
                                    <span class="badge bg-{{ $proficiencyColors[$skill->proficiency_level] ?? 'secondary' }} fs-6">
                                        {{ $skill->proficiency_level }}
                                    </span>
                                </span>
                            </p>
                            @if($skill->years_of_experience !== null)
                            <p class="mb-2">
                                <strong>Years of Experience:</strong><br>
                                <span class="ms-3">{{ $skill->years_of_experience }} year{{ $skill->years_of_experience != 1 ? 's' : '' }}</span>
                            </p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        @if($skill->last_used)
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-calendar-alt me-2 text-primary"></i>Last Used:</strong><br>
                                <span class="ms-4">{{ \Carbon\Carbon::parse($skill->last_used)->format('d M Y') }}</span>
                                <small class="d-block ms-4 text-muted">
                                    ({{ \Carbon\Carbon::parse($skill->last_used)->diffForHumans() }})
                                </small>
                            </p>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-certificate me-2 text-warning"></i>Certification Status:</strong><br>
                                <span class="ms-4">
                                    @if($skill->certified)
                                        <span class="badge bg-success">Certified</span>
                                    @else
                                        <span class="badge bg-secondary">Not Certified</span>
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>

                    @if($skill->notes)
                    <div class="mb-3">
                        <p class="mb-2">
                            <strong><i class="fas fa-sticky-note me-2 text-info"></i>Notes:</strong>
                        </p>
                        <p class="ms-4 text-muted">{{ $skill->notes }}</p>
                    </div>
                    @endif

                    <div class="row mb-3 mt-4">
                        <div class="col-md-6">
                            <p class="mb-2 small text-muted">
                                <strong>Created:</strong><br>
                                {{ $skill->created_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2 small text-muted">
                                <strong>Last Updated:</strong><br>
                                {{ $skill->updated_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($skill->certified)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-certificate me-2"></i>Certification Details</h5>
                </div>
                <div class="card-body">
                    @if($skill->certification_name)
                    <div class="row mb-3">
                        <div class="col-12">
                            <p class="mb-2">
                                <strong><i class="fas fa-award me-2 text-warning"></i>Certification Name:</strong><br>
                                <span class="ms-4 fs-5 text-primary">{{ $skill->certification_name }}</span>
                            </p>
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        @if($skill->certification_date)
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-calendar-check me-2 text-success"></i>Certification Date:</strong><br>
                                <span class="ms-4">{{ \Carbon\Carbon::parse($skill->certification_date)->format('d M Y') }}</span>
                            </p>
                        </div>
                        @endif
                        @if($skill->expiry_date)
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-calendar-times me-2 text-danger"></i>Expiry Date:</strong><br>
                                <span class="ms-4">{{ \Carbon\Carbon::parse($skill->expiry_date)->format('d M Y') }}</span>
                                @php
                                    $expiryDate = \Carbon\Carbon::parse($skill->expiry_date);
                                    $daysUntilExpiry = now()->diffInDays($expiryDate, false);
                                @endphp
                                @if($daysUntilExpiry < 0)
                                    <span class="badge bg-danger ms-2">Expired</span>
                                @elseif($daysUntilExpiry <= 30)
                                    <span class="badge bg-warning ms-2">Expiring Soon</span>
                                @else
                                    <span class="badge bg-success ms-2">Valid</span>
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>

                    @if($skill->expiry_date)
                    @php
                        $expiryDate = \Carbon\Carbon::parse($skill->expiry_date);
                        $daysUntilExpiry = now()->diffInDays($expiryDate, false);
                    @endphp
                    @if($daysUntilExpiry >= 0 && $daysUntilExpiry <= 60)
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Renewal Notice:</strong> This certification will expire in {{ $daysUntilExpiry }} days. 
                        Please consider renewal.
                    </div>
                    @elseif($daysUntilExpiry < 0)
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-times-circle me-2"></i>
                        <strong>Expired:</strong> This certification expired {{ abs($daysUntilExpiry) }} days ago. 
                        Renewal is required.
                    </div>
                    @endif
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-tasks me-2"></i>Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('hr.training.skills.edit', $skill->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Update Skill
                        </a>
                        
                        <form action="{{ route('hr.training.skills.destroy', $skill->id) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this skill?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i>Delete Skill
                            </button>
                        </form>

                        <a href="{{ route('hr.training.skills.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Proficiency Scale</h6>
                </div>
                <div class="card-body">
                    @php
                        $proficiencyValue = [
                            'Beginner' => 25,
                            'Intermediate' => 50,
                            'Advanced' => 75,
                            'Expert' => 100
                        ];
                        $value = $proficiencyValue[$skill->proficiency_level] ?? 0;
                        $color = $proficiencyColors[$skill->proficiency_level] ?? 'secondary';
                    @endphp
                    <div class="progress" style="height: 30px;">
                        <div class="progress-bar bg-{{ $color }}" 
                             role="progressbar" 
                             style="width: {{ $value }}%"
                             aria-valuenow="{{ $value }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ $skill->proficiency_level }}
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">
                        @if($skill->proficiency_level == 'Beginner')
                            Basic understanding, limited practical experience
                        @elseif($skill->proficiency_level == 'Intermediate')
                            Working knowledge, can perform tasks independently
                        @elseif($skill->proficiency_level == 'Advanced')
                            Strong expertise, can guide and train others
                        @elseif($skill->proficiency_level == 'Expert')
                            Master level, industry recognized authority
                        @endif
                    </small>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Skill Summary</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2">
                            <strong>Category:</strong><br>
                            <span class="badge bg-primary">{{ $skill->skill_category }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>Level:</strong><br>
                            <span class="badge bg-{{ $proficiencyColors[$skill->proficiency_level] ?? 'secondary' }}">
                                {{ $skill->proficiency_level }}
                            </span>
                        </li>
                        @if($skill->years_of_experience)
                        <li class="mb-2">
                            <strong>Experience:</strong><br>
                            {{ $skill->years_of_experience }} year{{ $skill->years_of_experience != 1 ? 's' : '' }}
                        </li>
                        @endif
                        <li>
                            <strong>Certification:</strong><br>
                            @if($skill->certified)
                                <i class="fas fa-check-circle text-success"></i> Certified
                            @else
                                <i class="fas fa-times-circle text-secondary"></i> Not Certified
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
