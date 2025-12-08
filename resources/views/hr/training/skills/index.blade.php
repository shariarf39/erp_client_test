@extends('layouts.app')

@section('title', 'Employee Skills - SENA.ERP')
@section('page_title', 'Employee Skills')
@section('page_description', 'Manage employee skills and competencies')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Employee Skills Matrix</h5>
                    <a href="{{ route('hr.training.skills.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Add Skill
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('hr.training.skills.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Search by skill name..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="employee_id" class="form-select">
                                    <option value="">All Employees</option>
                                    @foreach(\App\Models\Employee::where('status', 'Active')->orderBy('first_name')->get() as $emp)
                                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->first_name }} {{ $emp->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="skill_category" class="form-select">
                                    <option value="">All Categories</option>
                                    <option value="Technical" {{ request('skill_category') == 'Technical' ? 'selected' : '' }}>Technical</option>
                                    <option value="Soft Skills" {{ request('skill_category') == 'Soft Skills' ? 'selected' : '' }}>Soft Skills</option>
                                    <option value="Language" {{ request('skill_category') == 'Language' ? 'selected' : '' }}>Language</option>
                                    <option value="Management" {{ request('skill_category') == 'Management' ? 'selected' : '' }}>Management</option>
                                    <option value="Industry Knowledge" {{ request('skill_category') == 'Industry Knowledge' ? 'selected' : '' }}>Industry Knowledge</option>
                                    <option value="Tools & Software" {{ request('skill_category') == 'Tools & Software' ? 'selected' : '' }}>Tools & Software</option>
                                    <option value="Certifications" {{ request('skill_category') == 'Certifications' ? 'selected' : '' }}>Certifications</option>
                                    <option value="Other" {{ request('skill_category') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="proficiency_level" class="form-select">
                                    <option value="">All Proficiency</option>
                                    <option value="Beginner" {{ request('proficiency_level') == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="Intermediate" {{ request('proficiency_level') == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="Advanced" {{ request('proficiency_level') == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                                    <option value="Expert" {{ request('proficiency_level') == 'Expert' ? 'selected' : '' }}>Expert</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="is_verified" class="form-select">
                                    <option value="">All Skills</option>
                                    <option value="1" {{ request('is_verified') == '1' ? 'selected' : '' }}>Verified</option>
                                    <option value="0" {{ request('is_verified') == '0' ? 'selected' : '' }}>Not Verified</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Skills Table -->
                    @if($skills->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Skill Name</th>
                                        <th>Category</th>
                                        <th>Proficiency Level</th>
                                        <th>Experience</th>
                                        <th>Last Used</th>
                                        <th>Certification</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($skills as $skill)
                                    <tr>
                                        <td>
                                            @if($skill->employee)
                                                <div>
                                                    <strong>{{ $skill->employee->first_name }} {{ $skill->employee->last_name }}</strong>
                                                    <br><small class="text-muted">{{ $skill->employee->employee_code }}</small>
                                                    @if($skill->employee->designation)
                                                        <br><span class="badge bg-secondary">{{ $skill->employee->designation->name }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $skill->skill_name }}</strong>
                                            @if($skill->notes)
                                                <br><small class="text-muted">{{ Str::limit($skill->notes, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $categoryColors = [
                                                    'Technical' => 'primary',
                                                    'Soft Skills' => 'info',
                                                    'Language' => 'success',
                                                    'Management' => 'warning',
                                                    'Industry Knowledge' => 'danger',
                                                    'Tools & Software' => 'secondary',
                                                    'Certifications' => 'dark',
                                                    'Other' => 'light'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $categoryColors[$skill->skill_category] ?? 'secondary' }}">
                                                {{ $skill->skill_category }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $proficiencyColors = [
                                                    'Beginner' => 'secondary',
                                                    'Intermediate' => 'info',
                                                    'Advanced' => 'warning',
                                                    'Expert' => 'success'
                                                ];
                                                $proficiencyIcons = [
                                                    'Beginner' => 'fa-star',
                                                    'Intermediate' => 'fa-star-half-alt',
                                                    'Advanced' => 'fa-award',
                                                    'Expert' => 'fa-trophy'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $proficiencyColors[$skill->proficiency_level] ?? 'secondary' }}">
                                                <i class="fas {{ $proficiencyIcons[$skill->proficiency_level] ?? 'fa-star' }}"></i> {{ $skill->proficiency_level }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($skill->years_of_experience)
                                                <i class="fas fa-clock"></i> {{ $skill->years_of_experience }} years
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($skill->last_used_date)
                                                <small>
                                                    <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($skill->last_used_date)->format('M Y') }}
                                                </small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($skill->certification_name)
                                                <div>
                                                    <span class="badge bg-{{ $skill->is_verified ? 'success' : 'secondary' }}">
                                                        <i class="fas fa-certificate"></i> {{ $skill->certification_name }}
                                                    </span>
                                                    @if($skill->is_verified)
                                                        <br><small class="text-success"><i class="fas fa-check-circle"></i> Verified</small>
                                                    @endif
                                                    @if($skill->expiry_date)
                                                        <br><small class="
                                                            @if(\Carbon\Carbon::parse($skill->expiry_date)->isPast()) text-danger
                                                            @elseif(\Carbon\Carbon::parse($skill->expiry_date)->diffInMonths(now()) < 3) text-warning
                                                            @else text-muted
                                                            @endif">
                                                            <i class="fas fa-calendar-times"></i> Exp: {{ \Carbon\Carbon::parse($skill->expiry_date)->format('M d, Y') }}
                                                        </small>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">No Certification</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('hr.training.skills.show', $skill->id) }}" class="btn btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('hr.training.skills.edit', $skill->id) }}" class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('hr.training.skills.destroy', $skill->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this skill?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $skills->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No skills found. <a href="{{ route('hr.training.skills.create') }}" class="alert-link">Add your first skill</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="text-muted">Total Skills</h6>
                    <h3 class="mb-0">{{ \App\Models\EmployeeSkill::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <h6 class="text-muted">Verified Skills</h6>
                    <h3 class="mb-0 text-success">{{ \App\Models\EmployeeSkill::where('is_verified', true)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <h6 class="text-muted">Expert Level</h6>
                    <h3 class="mb-0 text-warning">{{ \App\Models\EmployeeSkill::where('proficiency_level', 'Expert')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <h6 class="text-muted">Unique Skills</h6>
                    <h3 class="mb-0 text-info">{{ \App\Models\EmployeeSkill::distinct('skill_name')->count('skill_name') }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
