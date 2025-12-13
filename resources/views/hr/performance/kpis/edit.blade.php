@extends('layouts.app')

@section('title', 'Edit Performance KPI - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Performance KPI</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.performance.kpis.index') }}">Performance KPIs</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <form action="{{ route('hr.performance.kpis.update', $kpi) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">KPI Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">KPI Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $kpi->name) }}" required>
                            <small class="text-muted">Clear and concise name for this KPI</small>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="code" class="form-label">KPI Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code', $kpi->code) }}" required>
                            <small class="text-muted">Unique code for this KPI (e.g., KPI-001)</small>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $kpi->description) }}</textarea>
                            <small class="text-muted">Detailed description of what this KPI measures</small>
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
                                    <option value="Quality" {{ old('category', $kpi->category) == 'Quality' ? 'selected' : '' }}>Quality</option>
                                    <option value="Productivity" {{ old('category', $kpi->category) == 'Productivity' ? 'selected' : '' }}>Productivity</option>
                                    <option value="Efficiency" {{ old('category', $kpi->category) == 'Efficiency' ? 'selected' : '' }}>Efficiency</option>
                                    <option value="Customer Satisfaction" {{ old('category', $kpi->category) == 'Customer Satisfaction' ? 'selected' : '' }}>Customer Satisfaction</option>
                                    <option value="Innovation" {{ old('category', $kpi->category) == 'Innovation' ? 'selected' : '' }}>Innovation</option>
                                    <option value="Leadership" {{ old('category', $kpi->category) == 'Leadership' ? 'selected' : '' }}>Leadership</option>
                                    <option value="Teamwork" {{ old('category', $kpi->category) == 'Teamwork' ? 'selected' : '' }}>Teamwork</option>
                                    <option value="Other" {{ old('category', $kpi->category) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="measurement_type" class="form-label">Measurement Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('measurement_type') is-invalid @enderror" 
                                        id="measurement_type" name="measurement_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Percentage" {{ old('measurement_type', $kpi->measurement_type) == 'Percentage' ? 'selected' : '' }}>Percentage</option>
                                    <option value="Number" {{ old('measurement_type', $kpi->measurement_type) == 'Number' ? 'selected' : '' }}>Number</option>
                                    <option value="Rating" {{ old('measurement_type', $kpi->measurement_type) == 'Rating' ? 'selected' : '' }}>Rating</option>
                                    <option value="Yes/No" {{ old('measurement_type', $kpi->measurement_type) == 'Yes/No' ? 'selected' : '' }}>Yes/No</option>
                                </select>
                                @error('measurement_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="unit" class="form-label">Unit of Measure</label>
                                <input type="text" class="form-control @error('unit') is-invalid @enderror" 
                                       id="unit" name="unit" value="{{ old('unit', $kpi->unit) }}">
                                <small class="text-muted">e.g., %, units, hours, score</small>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="target_value" class="form-label">Target Value</label>
                                <input type="number" step="0.01" class="form-control @error('target_value') is-invalid @enderror" 
                                       id="target_value" name="target_value" value="{{ old('target_value', $kpi->target_value) }}">
                                <small class="text-muted">Expected target value to achieve</small>
                                @error('target_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="department_id" class="form-label">Department</label>
                                <select class="form-select @error('department_id') is-invalid @enderror" 
                                        id="department_id" name="department_id">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" 
                                                {{ old('department_id', $kpi->department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Specific department for this KPI</small>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="designation_id" class="form-label">Designation</label>
                                <select class="form-select @error('designation_id') is-invalid @enderror" 
                                        id="designation_id" name="designation_id">
                                    <option value="">All Designations</option>
                                    @foreach($designations as $designation)
                                        <option value="{{ $designation->id }}" 
                                                {{ old('designation_id', $kpi->designation_id) == $designation->id ? 'selected' : '' }}>
                                            {{ $designation->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Specific role for this KPI</small>
                                @error('designation_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="weight" class="form-label">Weight (%)</label>
                            <input type="number" step="0.01" min="0" max="100" 
                                   class="form-control @error('weight') is-invalid @enderror" 
                                   id="weight" name="weight" value="{{ old('weight', $kpi->weight) }}">
                            <small class="text-muted">Importance percentage in overall performance (0-100)</small>
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $kpi->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (KPI is available for performance reviews)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update KPI
                    </button>
                    <a href="{{ route('hr.performance.kpis.show', $kpi) }}" class="btn btn-secondary">
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
                            Update KPI details as needed
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Adjust target values based on performance
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            Changes affect future reviews only
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Existing review data remains unchanged
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-list me-2"></i>Category Examples</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-1"><strong>Productivity:</strong> Tasks completed, output volume</li>
                        <li class="mb-1"><strong>Quality:</strong> Error rate, accuracy</li>
                        <li class="mb-1"><strong>Efficiency:</strong> Time management, resource usage</li>
                        <li class="mb-1"><strong>Customer Satisfaction:</strong> Ratings, feedback scores</li>
                        <li class="mb-1"><strong>Financial:</strong> Revenue, cost reduction</li>
                        <li class="mb-1"><strong>Innovation:</strong> New ideas, improvements</li>
                        <li class="mb-1"><strong>Teamwork:</strong> Collaboration, communication</li>
                        <li class="mb-1"><strong>Leadership:</strong> Mentoring, decision making</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
