@extends('layouts.app')

@section('title', 'Applicants - SENA.ERP')
@section('page_title', 'Applicants')
@section('page_description', 'Manage job applicants and track their application status')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Applicants</h5>
                    <a href="{{ route('hr.recruitment.applicants.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Add New Applicant
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('hr.recruitment.applicants.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Search by name, email, code..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="New" {{ request('status') == 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Screening" {{ request('status') == 'Screening' ? 'selected' : '' }}>Screening</option>
                                    <option value="Interview" {{ request('status') == 'Interview' ? 'selected' : '' }}>Interview</option>
                                    <option value="Assessment" {{ request('status') == 'Assessment' ? 'selected' : '' }}>Assessment</option>
                                    <option value="Offer" {{ request('status') == 'Offer' ? 'selected' : '' }}>Offer</option>
                                    <option value="Hired" {{ request('status') == 'Hired' ? 'selected' : '' }}>Hired</option>
                                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="Withdrawn" {{ request('status') == 'Withdrawn' ?
                                         'selected' : '' }}>Withdrawn</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="job_id" class="form-select">
                                    <option value="">All Job Postings</option>
                                    @foreach(\App\Models\JobPosting::where('status', 'Active')->get() as $job)
                                        <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>
                                            {{ $job->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('hr.recruitment.applicants.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Applicants Table -->
                    @if($applicants->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Application Code</th>
                                        <th>Name</th>
                                        <th>Job Position</th>
                                        <th>Contact</th>
                                        <th>Experience</th>
                                        <th>Status</th>
                                        <th>Rating</th>
                                        <th>Applied Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applicants as $applicant)
                                    <tr>
                                        <td>
                                            <strong class="text-primary">{{ $applicant->application_code }}</strong>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $applicant->first_name }} {{ $applicant->last_name }}</strong>
                                                @if($applicant->current_position)
                                                    <br><small class="text-muted">{{ $applicant->current_position }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($applicant->jobPosting)
                                                <span class="badge bg-info">{{ $applicant->jobPosting->title }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <i class="fas fa-envelope text-muted"></i> {{ $applicant->email }}<br>
                                                <i class="fas fa-phone text-muted"></i> {{ $applicant->phone }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $applicant->experience_years }} years</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'New' => 'primary',
                                                    'Screening' => 'info',
                                                    'Interview' => 'warning',
                                                    'Assessment' => 'secondary',
                                                    'Offer' => 'success',
                                                    'Hired' => 'success',
                                                    'Rejected' => 'danger',
                                                    'Withdrawn' => 'dark'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$applicant->status] ?? 'secondary' }}">
                                                {{ $applicant->status }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($applicant->rating)
                                                <div>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $applicant->rating)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-muted"></i>
                                                        @endif
                                                    @endfor
                                                    <br><small class="text-muted">{{ $applicant->rating }}/5</small>
                                                </div>
                                            @else
                                                <span class="text-muted">Not rated</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $applicant->applied_at ? $applicant->applied_at->format('M d, Y') : 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('hr.recruitment.applicants.show', $applicant->id) }}" class="btn btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('hr.recruitment.applicants.edit', $applicant->id) }}" class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('hr.recruitment.applicants.destroy', $applicant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this applicant?');">
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
                            {{ $applicants->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No applicants found. <a href="{{ route('hr.recruitment.applicants.create') }}" class="alert-link">Add your first applicant</a>
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
                    <h6 class="text-muted">Total Applicants</h6>
                    <h3 class="mb-0">{{ \App\Models\Applicant::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <h6 class="text-muted">Hired</h6>
                    <h3 class="mb-0 text-success">{{ \App\Models\Applicant::where('status', 'Hired')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <h6 class="text-muted">In Interview</h6>
                    <h3 class="mb-0 text-warning">{{ \App\Models\Applicant::where('status', 'Interview')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <h6 class="text-muted">New Applications</h6>
                    <h3 class="mb-0 text-info">{{ \App\Models\Applicant::where('status', 'New')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
