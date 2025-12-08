@extends('layouts.app')

@section('title', 'Training Programs - SENA.ERP')
@section('page_title', 'Training Programs')
@section('page_description', 'Manage employee training and development programs')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Training Programs</h5>
                    <a href="{{ route('hr.training.programs.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Create Program
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('hr.training.programs.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Search programs..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="Planned" {{ request('status') == 'Planned' ? 'selected' : '' }}>Planned</option>
                                    <option value="Open for Enrollment" {{ request('status') == 'Open for Enrollment' ? 'selected' : '' }}>Open for Enrollment</option>
                                    <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="category" class="form-select">
                                    <option value="">All Categories</option>
                                    <option value="Technical" {{ request('category') == 'Technical' ? 'selected' : '' }}>Technical</option>
                                    <option value="Soft Skills" {{ request('category') == 'Soft Skills' ? 'selected' : '' }}>Soft Skills</option>
                                    <option value="Leadership" {{ request('category') == 'Leadership' ? 'selected' : '' }}>Leadership</option>
                                    <option value="Compliance" {{ request('category') == 'Compliance' ? 'selected' : '' }}>Compliance</option>
                                    <option value="Safety" {{ request('category') == 'Safety' ? 'selected' : '' }}>Safety</option>
                                    <option value="Product" {{ request('category') == 'Product' ? 'selected' : '' }}>Product</option>
                                    <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="training_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="Classroom" {{ request('training_type') == 'Classroom' ? 'selected' : '' }}>Classroom</option>
                                    <option value="Online" {{ request('training_type') == 'Online' ? 'selected' : '' }}>Online</option>
                                    <option value="On-the-Job" {{ request('training_type') == 'On-the-Job' ? 'selected' : '' }}>On-the-Job</option>
                                    <option value="Workshop" {{ request('training_type') == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                                    <option value="Seminar" {{ request('training_type') == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                                    <option value="Conference" {{ request('training_type') == 'Conference' ? 'selected' : '' }}>Conference</option>
                                    <option value="Certification" {{ request('training_type') == 'Certification' ? 'selected' : '' }}>Certification</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('hr.training.programs.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Programs Table -->
                    @if($programs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Program Code</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Type</th>
                                        <th>Duration</th>
                                        <th>Schedule</th>
                                        <th>Participants</th>
                                        <th>Cost</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($programs as $program)
                                    <tr>
                                        <td>
                                            <strong class="text-primary">{{ $program->program_code }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $program->title }}</strong>
                                            @if($program->description)
                                                <br><small class="text-muted">{{ Str::limit($program->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $categoryColors = [
                                                    'Technical' => 'primary',
                                                    'Soft Skills' => 'info',
                                                    'Leadership' => 'warning',
                                                    'Compliance' => 'danger',
                                                    'Safety' => 'success',
                                                    'Product' => 'secondary',
                                                    'Other' => 'dark'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $categoryColors[$program->category] ?? 'secondary' }}">
                                                {{ $program->category }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $program->training_type }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($program->duration_days || $program->duration_hours)
                                                <small>
                                                    @if($program->duration_days)
                                                        <i class="fas fa-calendar-day"></i> {{ $program->duration_days }} days
                                                    @endif
                                                    @if($program->duration_hours)
                                                        <br><i class="fas fa-clock"></i> {{ $program->duration_hours }} hours
                                                    @endif
                                                </small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($program->start_date && $program->end_date)
                                                <small>
                                                    <i class="fas fa-calendar-alt text-success"></i> {{ \Carbon\Carbon::parse($program->start_date)->format('M d, Y') }}
                                                    <br>
                                                    <i class="fas fa-calendar-check text-danger"></i> {{ \Carbon\Carbon::parse($program->end_date)->format('M d, Y') }}
                                                </small>
                                            @else
                                                <span class="text-muted">TBD</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($program->max_participants)
                                                <span class="badge bg-info">
                                                    <i class="fas fa-users"></i> {{ $program->enrollments->count() }}/{{ $program->max_participants }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-users"></i> {{ $program->enrollments->count() }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($program->cost_per_participant)
                                                <strong>${{ number_format($program->cost_per_participant, 2) }}</strong>
                                            @else
                                                <span class="text-muted">Free</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'Planned' => 'secondary',
                                                    'Open for Enrollment' => 'success',
                                                    'In Progress' => 'primary',
                                                    'Completed' => 'info',
                                                    'Cancelled' => 'danger'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$program->status] ?? 'secondary' }}">
                                                {{ $program->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('hr.training.programs.show', $program->id) }}" class="btn btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('hr.training.programs.edit', $program->id) }}" class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('hr.training.programs.destroy', $program->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this program?');">
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
                            {{ $programs->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No training programs found. <a href="{{ route('hr.training.programs.create') }}" class="alert-link">Create your first program</a>
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
                    <h6 class="text-muted">Total Programs</h6>
                    <h3 class="mb-0">{{ \App\Models\TrainingProgram::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <h6 class="text-muted">Open for Enrollment</h6>
                    <h3 class="mb-0 text-success">{{ \App\Models\TrainingProgram::where('status', 'Open for Enrollment')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <h6 class="text-muted">In Progress</h6>
                    <h3 class="mb-0 text-primary">{{ \App\Models\TrainingProgram::where('status', 'In Progress')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <h6 class="text-muted">Total Enrollments</h6>
                    <h3 class="mb-0 text-warning">{{ \App\Models\TrainingEnrollment::count() }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
