@extends('layouts.app')

@section('title', 'Performance KPIs - SENA.ERP')
@section('page_title', 'Performance KPIs')
@section('page_description', 'Manage Key Performance Indicators for performance reviews')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Performance KPIs</h5>
                    <a href="{{ route('hr.performance.kpis.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Add KPI
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('hr.performance.kpis.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Search KPIs..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="category" class="form-select">
                                    <option value="">All Categories</option>
                                    <option value="Productivity" {{ request('category') == 'Productivity' ? 'selected' : '' }}>Productivity</option>
                                    <option value="Quality" {{ request('category') == 'Quality' ? 'selected' : '' }}>Quality</option>
                                    <option value="Efficiency" {{ request('category') == 'Efficiency' ? 'selected' : '' }}>Efficiency</option>
                                    <option value="Customer Satisfaction" {{ request('category') == 'Customer Satisfaction' ? 'selected' : '' }}>Customer Satisfaction</option>
                                    <option value="Financial" {{ request('category') == 'Financial' ? 'selected' : '' }}>Financial</option>
                                    <option value="Innovation" {{ request('category') == 'Innovation' ? 'selected' : '' }}>Innovation</option>
                                    <option value="Teamwork" {{ request('category') == 'Teamwork' ? 'selected' : '' }}>Teamwork</option>
                                    <option value="Leadership" {{ request('category') == 'Leadership' ? 'selected' : '' }}>Leadership</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="department_id" class="form-select">
                                    <option value="">All Departments</option>
                                    @foreach(\App\Models\Department::where('is_active', true)->get() as $dept)
                                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="is_active" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('hr.performance.kpis.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- KPIs Table -->
                    @if($kpis->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>KPI Name</th>
                                        <th>Category</th>
                                        <th>Measurement</th>
                                        <th>Target Value</th>
                                        <th>Department</th>
                                        <th>Designation</th>
                                        <th>Weight</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kpis as $kpi)
                                    <tr>
                                        <td>
                                            <strong>{{ $kpi->kpi_name }}</strong>
                                            @if($kpi->description)
                                                <br><small class="text-muted">{{ Str::limit($kpi->description, 60) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $categoryColors = [
                                                    'Productivity' => 'primary',
                                                    'Quality' => 'success',
                                                    'Efficiency' => 'info',
                                                    'Customer Satisfaction' => 'warning',
                                                    'Financial' => 'danger',
                                                    'Innovation' => 'purple',
                                                    'Teamwork' => 'secondary',
                                                    'Leadership' => 'dark'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $categoryColors[$kpi->category] ?? 'secondary' }}">
                                                {{ $kpi->category }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $kpi->measurement_type }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($kpi->target_value)
                                                <strong>{{ $kpi->target_value }}</strong>
                                                @if($kpi->unit_of_measure)
                                                    <small class="text-muted">{{ $kpi->unit_of_measure }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($kpi->department)
                                                <span class="badge bg-info">{{ $kpi->department->name }}</span>
                                            @else
                                                <span class="text-muted">All</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($kpi->designation)
                                                <span class="badge bg-secondary">{{ $kpi->designation->name }}</span>
                                            @else
                                                <span class="text-muted">All</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($kpi->weight)
                                                <span class="badge bg-warning">{{ $kpi->weight }}%</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($kpi->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('hr.performance.kpis.show', $kpi->id) }}" class="btn btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('hr.performance.kpis.edit', $kpi->id) }}" class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('hr.performance.kpis.destroy', $kpi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this KPI?');">
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
                            {{ $kpis->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No KPIs found. <a href="{{ route('hr.performance.kpis.create') }}" class="alert-link">Add your first KPI</a>
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
                    <h6 class="text-muted">Total KPIs</h6>
                    <h3 class="mb-0">{{ \App\Models\PerformanceKpi::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <h6 class="text-muted">Active KPIs</h6>
                    <h3 class="mb-0 text-success">{{ \App\Models\PerformanceKpi::where('is_active', true)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <h6 class="text-muted">Categories</h6>
                    <h3 class="mb-0 text-info">{{ \App\Models\PerformanceKpi::distinct('category')->count('category') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <h6 class="text-muted">In Reviews</h6>
                    <h3 class="mb-0 text-warning">{{ \App\Models\PerformanceReviewKpi::distinct('kpi_id')->count('kpi_id') }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge.bg-purple {
        background-color: #6f42c1 !important;
    }
</style>
@endpush
@endsection
