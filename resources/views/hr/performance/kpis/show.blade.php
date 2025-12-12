@extends('layouts.app')

@section('title', 'KPI Details - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-chart-line me-2"></i>{{ $kpi->name }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.performance.kpis.index') }}">Performance KPIs</a></li>
                    <li class="breadcrumb-item active">{{ $kpi->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">KPI Information</h5>
                    <span class="badge {{ $kpi->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $kpi->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted">KPI Name</h6>
                        <p class="mb-0"><strong>{{ $kpi->name }}</strong></p>
                    </div>

                    @if($kpi->description)
                    <div class="mb-3">
                        <h6 class="text-muted">Description</h6>
                        <p class="mb-0">{{ $kpi->description }}</p>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Category</h6>
                            <p class="mb-0"><span class="badge bg-info">{{ $kpi->category }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Measurement Type</h6>
                            <p class="mb-0"><span class="badge bg-secondary">{{ $kpi->measurement_type }}</span></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Unit of Measure</h6>
                            <p class="mb-0">{{ $kpi->unit_of_measure ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Target Value</h6>
                            <p class="mb-0">{{ $kpi->target_value ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Department</h6>
                            <p class="mb-0">{{ $kpi->department->name ?? 'All Departments' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Designation</h6>
                            <p class="mb-0">{{ $kpi->designation->title ?? 'All Designations' }}</p>
                        </div>
                    </div>

                    <div class="mb-0">
                        <h6 class="text-muted">Weight</h6>
                        <div class="d-flex align-items-center">
                            <div class="progress flex-grow-1 me-2" style="height: 25px;">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ $kpi->weight ?? 0 }}%">
                                    {{ $kpi->weight ?? 0 }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($kpi->reviewKpis && $kpi->reviewKpis->count() > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Performance Review Usage</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Review Period</th>
                                    <th>Employee</th>
                                    <th>Actual Value</th>
                                    <th>Achievement %</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kpi->reviewKpis as $reviewKpi)
                                <tr>
                                    <td>{{ $reviewKpi->review->review_period ?? 'N/A' }}</td>
                                    <td>{{ $reviewKpi->review->employee->full_name ?? 'N/A' }}</td>
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
                    <a href="{{ route('hr.performance.kpis.edit', $kpi) }}" class="btn btn-warning w-100 mb-2">
                        <i class="fas fa-edit me-2"></i>Edit KPI
                    </a>

                    <form action="{{ route('hr.performance.kpis.destroy', $kpi) }}" method="POST" class="mb-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('Are you sure you want to delete this KPI?')">
                            <i class="fas fa-trash me-2"></i>Delete KPI
                        </button>
                    </form>

                    <a href="{{ route('hr.performance.kpis.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Times Used:</span>
                        <strong>{{ $kpi->reviewKpis->count() }}</strong>
                    </div>
                    @if($kpi->reviewKpis->count() > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Avg Achievement:</span>
                        <strong>{{ number_format($kpi->reviewKpis->avg('achievement_percentage') ?? 0, 1) }}%</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Avg Rating:</span>
                        <strong>{{ number_format($kpi->reviewKpis->avg('rating') ?? 0, 1) }}</strong>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Timeline</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <i class="fas fa-plus-circle text-primary me-2"></i>
                            <strong>Created:</strong><br>
                            <small class="text-muted ms-3">{{ $kpi->created_at->format('M j, Y H:i') }}</small>
                        </li>
                        @if($kpi->updated_at != $kpi->created_at)
                        <li>
                            <i class="fas fa-edit text-warning me-2"></i>
                            <strong>Last Updated:</strong><br>
                            <small class="text-muted ms-3">{{ $kpi->updated_at->format('M j, Y H:i') }}</small>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
