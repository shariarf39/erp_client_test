@extends('layouts.app')

@section('title', 'Performance Review Details - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-star me-2"></i>Performance Review Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.performance.reviews.index') }}">Performance Reviews</a></li>
                    <li class="breadcrumb-item active">Review Details</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Review Information</h5>
                    <span class="badge 
                        {{ $review->status == 'Completed' ? 'bg-success' : '' }}
                        {{ $review->status == 'Submitted' ? 'bg-info' : '' }}
                        {{ $review->status == 'Draft' ? 'bg-secondary' : '' }}
                        {{ $review->status == 'Under Review' ? 'bg-warning' : '' }}
                        {{ $review->status == 'Acknowledged' ? 'bg-success' : '' }}">
                        {{ $review->status }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Employee</h6>
                            <p class="mb-0">
                                <strong>{{ $review->employee->full_name }}</strong><br>
                                <small>{{ $review->employee->employee_code }}</small><br>
                                <small>{{ $review->employee->designation->title ?? 'N/A' }} - {{ $review->employee->department->name ?? 'N/A' }}</small>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Reviewer</h6>
                            <p class="mb-0">{{ $review->reviewer->name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6 class="text-muted">Review Period</h6>
                            <p class="mb-0">
                                {{ $review->review_period_start->format('M j, Y') }} - 
                                {{ $review->review_period_end->format('M j, Y') }}
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Review Type</h6>
                            <p class="mb-0"><span class="badge bg-info">{{ $review->review_type }}</span></p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Overall Rating</h6>
                            <p class="mb-0">
                                @if($review->overall_rating)
                                    <span class="badge bg-warning text-dark" style="font-size: 1.1rem;">
                                        {{ number_format($review->overall_rating, 1) }} / 5.0
                                    </span>
                                @else
                                    <span class="text-muted">Not rated</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Performance Assessment</h5>
                </div>
                <div class="card-body">
                    @if($review->achievements)
                    <div class="mb-4">
                        <h6 class="text-primary"><i class="fas fa-trophy me-2"></i>Key Achievements</h6>
                        <p class="mb-0" style="white-space: pre-line;">{{ $review->achievements }}</p>
                    </div>
                    @endif

                    @if($review->strengths)
                    <div class="mb-4">
                        <h6 class="text-success"><i class="fas fa-thumbs-up me-2"></i>Strengths</h6>
                        <p class="mb-0" style="white-space: pre-line;">{{ $review->strengths }}</p>
                    </div>
                    @endif

                    @if($review->areas_for_improvement)
                    <div class="mb-4">
                        <h6 class="text-warning"><i class="fas fa-chart-line me-2"></i>Areas for Improvement</h6>
                        <p class="mb-0" style="white-space: pre-line;">{{ $review->areas_for_improvement }}</p>
                    </div>
                    @endif

                    @if($review->goals_for_next_period)
                    <div class="mb-4">
                        <h6 class="text-info"><i class="fas fa-bullseye me-2"></i>Goals for Next Period</h6>
                        <p class="mb-0" style="white-space: pre-line;">{{ $review->goals_for_next_period }}</p>
                    </div>
                    @endif

                    @if($review->comments)
                    <div class="mb-0">
                        <h6 class="text-secondary"><i class="fas fa-comment me-2"></i>Additional Comments</h6>
                        <p class="mb-0" style="white-space: pre-line;">{{ $review->comments }}</p>
                    </div>
                    @endif
                </div>
            </div>

            @if($review->kpis && $review->kpis->count() > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">KPI Assessment</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>KPI Name</th>
                                    <th>Target</th>
                                    <th>Actual</th>
                                    <th>Achievement %</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($review->kpis as $reviewKpi)
                                <tr>
                                    <td>{{ $reviewKpi->kpi->name ?? 'N/A' }}</td>
                                    <td>{{ $reviewKpi->kpi->target_value ?? 'N/A' }}</td>
                                    <td>{{ $reviewKpi->actual_value ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge 
                                            {{ $reviewKpi->achievement_percentage >= 100 ? 'bg-success' : '' }}
                                            {{ $reviewKpi->achievement_percentage >= 75 && $reviewKpi->achievement_percentage < 100 ? 'bg-info' : '' }}
                                            {{ $reviewKpi->achievement_percentage < 75 ? 'bg-warning' : '' }}">
                                            {{ number_format($reviewKpi->achievement_percentage ?? 0, 1) }}%
                                        </span>
                                    </td>
                                    <td>{{ $reviewKpi->rating ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('hr.performance.reviews.edit', $review) }}" class="btn btn-warning w-100 mb-2">
                        <i class="fas fa-edit me-2"></i>Edit Review
                    </a>

                    @if($review->status == 'Draft')
                    <form action="{{ route('hr.performance.reviews.destroy', $review) }}" method="POST" class="mb-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('Are you sure you want to delete this review?')">
                            <i class="fas fa-trash me-2"></i>Delete Review
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('hr.performance.reviews.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Timeline</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <i class="fas fa-plus-circle text-primary me-2"></i>
                            <strong>Created:</strong><br>
                            <small class="text-muted ms-3">{{ $review->created_at->format('M j, Y H:i') }}</small>
                        </li>
                        @if($review->submitted_at)
                        <li class="mb-2">
                            <i class="fas fa-paper-plane text-info me-2"></i>
                            <strong>Submitted:</strong><br>
                            <small class="text-muted ms-3">{{ $review->submitted_at->format('M j, Y H:i') }}</small>
                        </li>
                        @endif
                        @if($review->completed_at)
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Completed:</strong><br>
                            <small class="text-muted ms-3">{{ $review->completed_at->format('M j, Y H:i') }}</small>
                        </li>
                        @endif
                        @if($review->acknowledged_at)
                        <li>
                            <i class="fas fa-handshake text-success me-2"></i>
                            <strong>Acknowledged:</strong><br>
                            <small class="text-muted ms-3">{{ $review->acknowledged_at->format('M j, Y H:i') }}</small>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
