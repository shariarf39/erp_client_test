@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Job Postings</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">HR</a></li>
                    <li class="breadcrumb-item active">Recruitment</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('hr.recruitment.jobs.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Post New Job
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('hr.recruitment.jobs.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search jobs..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                            <option value="On Hold" {{ request('status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="department_id" class="form-select">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="{{ route('hr.recruitment.jobs.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Job Listings -->
    <div class="row">
        @forelse($jobPostings as $job)
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0">{{ $job->title }}</h5>
                            @if($job->status === 'Active')
                                <span class="badge bg-success">Active</span>
                            @elseif($job->status === 'Draft')
                                <span class="badge bg-secondary">Draft</span>
                            @elseif($job->status === 'Closed')
                                <span class="badge bg-danger">Closed</span>
                            @else
                                <span class="badge bg-warning">On Hold</span>
                            @endif
                        </div>
                        
                        <p class="text-muted mb-2"><small>{{ $job->job_code }}</small></p>
                        
                        <div class="mb-3">
                            <span class="badge bg-info me-2">{{ $job->employment_type }}</span>
                            @if($job->department)
                                <span class="badge bg-light text-dark me-2">
                                    <i class="bi bi-building"></i> {{ $job->department->name }}
                                </span>
                            @endif
                            @if($job->designation)
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-award"></i> {{ $job->designation->title }}
                                </span>
                            @endif
                        </div>

                        @if($job->job_description)
                            <p class="card-text text-muted small">
                                {{ Str::limit(strip_tags($job->job_description), 120) }}
                            </p>
                        @endif

                        <div class="row g-2 mb-3">
                            @if($job->location)
                                <div class="col-6">
                                    <small class="text-muted"><i class="bi bi-geo-alt"></i> {{ $job->location }}</small>
                                </div>
                            @endif
                            <div class="col-6">
                                <small class="text-muted"><i class="bi bi-people"></i> {{ $job->vacancies }} Position(s)</small>
                            </div>
                            @if($job->salary_range_min && $job->salary_range_max)
                                <div class="col-12">
                                    <small class="text-muted"><i class="bi bi-cash"></i> ৳{{ number_format($job->salary_range_min) }} - ৳{{ number_format($job->salary_range_max) }}</small>
                                </div>
                            @endif
                            @if($job->application_deadline)
                                <div class="col-12">
                                    <small class="text-muted"><i class="bi bi-calendar"></i> Deadline: {{ $job->application_deadline->format('d M Y') }}</small>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                {{ $job->applicants->count() }} Applicant(s)
                            </small>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('hr.recruitment.jobs.show', $job) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="{{ route('hr.recruitment.jobs.edit', $job) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No job postings found. <a href="{{ route('hr.recruitment.jobs.create') }}">Create your first job posting</a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($jobPostings->hasPages())
        <div class="d-flex justify-content-center">
            {{ $jobPostings->links() }}
        </div>
    @endif
</div>
@endsection
