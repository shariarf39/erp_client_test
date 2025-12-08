@extends('layouts.app')

@section('title', 'Performance Reviews - SENA.ERP')
@section('page_title', 'Performance Reviews')
@section('page_description', 'Manage employee performance reviews and evaluations')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Performance Reviews</h5>
                    <a href="{{ route('hr.performance.reviews.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Create Review
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('hr.performance.reviews.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
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
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="Submitted" {{ request('status') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                    <option value="Under Review" {{ request('status') == 'Under Review' ? 'selected' : '' }}>Under Review</option>
                                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Acknowledged" {{ request('status') == 'Acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="review_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="Probation" {{ request('review_type') == 'Probation' ? 'selected' : '' }}>Probation</option>
                                    <option value="Annual" {{ request('review_type') == 'Annual' ? 'selected' : '' }}>Annual</option>
                                    <option value="Mid-Year" {{ request('review_type') == 'Mid-Year' ? 'selected' : '' }}>Mid-Year</option>
                                    <option value="Quarterly" {{ request('review_type') == 'Quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="Project-Based" {{ request('review_type') == 'Project-Based' ? 'selected' : '' }}>Project-Based</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date" class="form-control" value="{{ request('date') }}" placeholder="Review Date">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('hr.performance.reviews.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Reviews Table -->
                    @if($reviews->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Review Type</th>
                                        <th>Review Period</th>
                                        <th>Reviewer</th>
                                        <th>Overall Rating</th>
                                        <th>Status</th>
                                        <th>Dates</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reviews as $review)
                                    <tr>
                                        <td>
                                            @if($review->employee)
                                                <div>
                                                    <strong>{{ $review->employee->first_name }} {{ $review->employee->last_name }}</strong>
                                                    <br><small class="text-muted">{{ $review->employee->employee_code }}</small>
                                                    @if($review->employee->designation)
                                                        <br><span class="badge bg-secondary">{{ $review->employee->designation->name }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $typeColors = [
                                                    'Probation' => 'warning',
                                                    'Annual' => 'primary',
                                                    'Mid-Year' => 'info',
                                                    'Quarterly' => 'secondary',
                                                    'Project-Based' => 'success'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $typeColors[$review->review_type] ?? 'secondary' }}">
                                                {{ $review->review_type }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>
                                                <i class="fas fa-calendar-alt text-success"></i> {{ \Carbon\Carbon::parse($review->review_period_start)->format('M d, Y') }}
                                                <br>
                                                <i class="fas fa-calendar-check text-danger"></i> {{ \Carbon\Carbon::parse($review->review_period_end)->format('M d, Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($review->reviewer)
                                                <small>
                                                    <i class="fas fa-user"></i> {{ $review->reviewer->first_name }} {{ $review->reviewer->last_name }}
                                                </small>
                                            @else
                                                <span class="text-muted">Not assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($review->overall_rating)
                                                <div>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->overall_rating)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-muted"></i>
                                                        @endif
                                                    @endfor
                                                    <br><strong class="text-warning">{{ number_format($review->overall_rating, 1) }}/5</strong>
                                                </div>
                                            @else
                                                <span class="text-muted">Not rated</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'Draft' => 'secondary',
                                                    'Submitted' => 'primary',
                                                    'Under Review' => 'info',
                                                    'Completed' => 'success',
                                                    'Acknowledged' => 'dark'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$review->status] ?? 'secondary' }}">
                                                {{ $review->status }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($review->submitted_at)
                                                <small class="text-muted">
                                                    <i class="fas fa-paper-plane"></i> Submitted: {{ \Carbon\Carbon::parse($review->submitted_at)->format('M d, Y') }}
                                                </small>
                                            @endif
                                            @if($review->completed_at)
                                                <br><small class="text-success">
                                                    <i class="fas fa-check-circle"></i> Completed: {{ \Carbon\Carbon::parse($review->completed_at)->format('M d, Y') }}
                                                </small>
                                            @endif
                                            @if($review->employee_acknowledged_at)
                                                <br><small class="text-info">
                                                    <i class="fas fa-user-check"></i> Acknowledged: {{ \Carbon\Carbon::parse($review->employee_acknowledged_at)->format('M d, Y') }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('hr.performance.reviews.show', $review->id) }}" class="btn btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('hr.performance.reviews.edit', $review->id) }}" class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($review->status == 'Draft')
                                                    <form action="{{ route('hr.performance.reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No performance reviews found. <a href="{{ route('hr.performance.reviews.create') }}" class="alert-link">Create your first review</a>
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
                    <h6 class="text-muted">Total Reviews</h6>
                    <h3 class="mb-0">{{ \App\Models\PerformanceReview::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <h6 class="text-muted">Under Review</h6>
                    <h3 class="mb-0 text-primary">{{ \App\Models\PerformanceReview::where('status', 'Under Review')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <h6 class="text-muted">Completed</h6>
                    <h3 class="mb-0 text-success">{{ \App\Models\PerformanceReview::where('status', 'Completed')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <h6 class="text-muted">Average Rating</h6>
                    <h3 class="mb-0 text-warning">
                        {{ \App\Models\PerformanceReview::whereNotNull('overall_rating')->avg('overall_rating') ? number_format(\App\Models\PerformanceReview::avg('overall_rating'), 1) : '0.0' }}/5
                    </h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
