@extends('layouts.app')

@section('title', 'Onboarding Checklists - SENA.ERP')
@section('page_title', 'Onboarding Checklists')
@section('page_description', 'Manage onboarding checklists and tasks')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list-check me-2"></i>Onboarding Checklists</h5>
                    <a href="{{ route('hr.onboarding.checklists.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Create Checklist
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('hr.onboarding.checklists.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search checklists..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
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
                                <a href="{{ route('hr.onboarding.checklists.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Checklists Table -->
                    @if($checklists->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Checklist Name</th>
                                        <th>Department</th>
                                        <th>Designation</th>
                                        <th>Total Tasks</th>
                                        <th>Mandatory Tasks</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($checklists as $checklist)
                                    <tr>
                                        <td>
                                            <strong>{{ $checklist->name }}</strong>
                                            @if($checklist->description)
                                                <br><small class="text-muted">{{ Str::limit($checklist->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($checklist->department)
                                                <span class="badge bg-info">{{ $checklist->department->name }}</span>
                                            @else
                                                <span class="text-muted">General</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($checklist->designation)
                                                <span class="badge bg-secondary">{{ $checklist->designation->name }}</span>
                                            @else
                                                <span class="text-muted">All</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <i class="fas fa-tasks"></i> {{ $checklist->tasks->count() }} tasks
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">
                                                {{ $checklist->tasks->where('is_mandatory', true)->count() }} mandatory
                                            </span>
                                        </td>
                                        <td>
                                            @if($checklist->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $checklist->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('hr.onboarding.checklists.show', $checklist->id) }}" class="btn btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('hr.onboarding.checklists.edit', $checklist->id) }}" class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('hr.onboarding.checklists.destroy', $checklist->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this checklist?');">
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
                            {{ $checklists->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No onboarding checklists found. <a href="{{ route('hr.onboarding.checklists.create') }}" class="alert-link">Create your first checklist</a>
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
                    <h6 class="text-muted">Total Checklists</h6>
                    <h3 class="mb-0">{{ \App\Models\OnboardingChecklist::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <h6 class="text-muted">Active Checklists</h6>
                    <h3 class="mb-0 text-success">{{ \App\Models\OnboardingChecklist::where('is_active', true)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <h6 class="text-muted">Total Tasks</h6>
                    <h3 class="mb-0 text-info">{{ \App\Models\OnboardingTask::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <h6 class="text-muted">In Use</h6>
                    <h3 class="mb-0 text-warning">{{ \App\Models\EmployeeOnboarding::count() }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
