@extends('layouts.app')

@section('title', 'Edit Performance Review - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Performance Review</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.performance.reviews.index') }}">Performance Reviews</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <form action="{{ route('hr.performance.reviews.update', $review) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Review Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                            <select class="form-select @error('employee_id') is-invalid @enderror" 
                                    id="employee_id" name="employee_id" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" 
                                            {{ old('employee_id', $review->employee_id) == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->employee_code }} - {{ $employee->full_name }}
                                        ({{ $employee->department->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="review_period_start" class="form-label">Review Period Start <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('review_period_start') is-invalid @enderror" 
                                       id="review_period_start" name="review_period_start" 
                                       value="{{ old('review_period_start', $review->review_period_start->format('Y-m-d')) }}" required>
                                @error('review_period_start')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="review_period_end" class="form-label">Review Period End <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('review_period_end') is-invalid @enderror" 
                                       id="review_period_end" name="review_period_end" 
                                       value="{{ old('review_period_end', $review->review_period_end->format('Y-m-d')) }}" required>
                                @error('review_period_end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="review_type" class="form-label">Review Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('review_type') is-invalid @enderror" 
                                        id="review_type" name="review_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Probation" {{ old('review_type', $review->review_type) == 'Probation' ? 'selected' : '' }}>Probation</option>
                                    <option value="Annual" {{ old('review_type', $review->review_type) == 'Annual' ? 'selected' : '' }}>Annual</option>
                                    <option value="Mid-Year" {{ old('review_type', $review->review_type) == 'Mid-Year' ? 'selected' : '' }}>Mid-Year</option>
                                    <option value="Quarterly" {{ old('review_type', $review->review_type) == 'Quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="Project-Based" {{ old('review_type', $review->review_type) == 'Project-Based' ? 'selected' : '' }}>Project-Based</option>
                                </select>
                                @error('review_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="Draft" {{ old('status', $review->status) == 'Draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="Submitted" {{ old('status', $review->status) == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                    <option value="Under Review" {{ old('status', $review->status) == 'Under Review' ? 'selected' : '' }}>Under Review</option>
                                    <option value="Completed" {{ old('status', $review->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Acknowledged" {{ old('status', $review->status) == 'Acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="overall_rating" class="form-label">Overall Rating (1-5)</label>
                            <input type="number" step="0.1" min="1" max="5" 
                                   class="form-control @error('overall_rating') is-invalid @enderror" 
                                   id="overall_rating" name="overall_rating" 
                                   value="{{ old('overall_rating', $review->overall_rating) }}">
                            <small class="text-muted">Overall performance rating from 1 (Poor) to 5 (Excellent)</small>
                            @error('overall_rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Performance Assessment</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="achievements" class="form-label">Key Achievements</label>
                            <textarea class="form-control @error('achievements') is-invalid @enderror" 
                                      id="achievements" name="achievements" rows="4">{{ old('achievements', $review->achievements) }}</textarea>
                            <small class="text-muted">Notable accomplishments during the review period</small>
                            @error('achievements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="strengths" class="form-label">Strengths</label>
                            <textarea class="form-control @error('strengths') is-invalid @enderror" 
                                      id="strengths" name="strengths" rows="4">{{ old('strengths', $review->strengths) }}</textarea>
                            <small class="text-muted">Areas where employee excels</small>
                            @error('strengths')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="areas_for_improvement" class="form-label">Areas for Improvement</label>
                            <textarea class="form-control @error('areas_for_improvement') is-invalid @enderror" 
                                      id="areas_for_improvement" name="areas_for_improvement" rows="4">{{ old('areas_for_improvement', $review->areas_for_improvement) }}</textarea>
                            <small class="text-muted">Areas requiring development or improvement</small>
                            @error('areas_for_improvement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="goals_for_next_period" class="form-label">Goals for Next Period</label>
                            <textarea class="form-control @error('goals_for_next_period') is-invalid @enderror" 
                                      id="goals_for_next_period" name="goals_for_next_period" rows="4">{{ old('goals_for_next_period', $review->goals_for_next_period) }}</textarea>
                            <small class="text-muted">Objectives and targets for the upcoming period</small>
                            @error('goals_for_next_period')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label for="comments" class="form-label">Additional Comments</label>
                            <textarea class="form-control @error('comments') is-invalid @enderror" 
                                      id="comments" name="comments" rows="3">{{ old('comments', $review->comments) }}</textarea>
                            <small class="text-muted">Any other relevant notes or observations</small>
                            @error('comments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Review
                    </button>
                    <a href="{{ route('hr.performance.reviews.show', $review) }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>

        <div class="col-lg-3">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Edit Information</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Update review details as needed
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Change status to track progress
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            Submitting sets timestamp
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Only drafts can be deleted
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-list me-2"></i>Review Types</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-1"><strong>Probation:</strong> New employee evaluation</li>
                        <li class="mb-1"><strong>Annual:</strong> Yearly performance review</li>
                        <li class="mb-1"><strong>Mid-Year:</strong> 6-month check-in</li>
                        <li class="mb-1"><strong>Quarterly:</strong> 3-month review</li>
                        <li class="mb-1"><strong>Project-Based:</strong> End of project evaluation</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
