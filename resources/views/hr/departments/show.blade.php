@extends('layouts.app')

@section('title', 'Department Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Department Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">HR</li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.departments.index') }}">Departments</a></li>
                    <li class="breadcrumb-item active">{{ $department->name }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('hr.departments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <a href="{{ route('hr.departments.edit', $department->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('hr.departments.destroy', $department->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this department?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8 mb-4">
            <!-- Basic Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Department Code</label>
                            <p class="fw-bold fs-5">{{ $department->code }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Department Name</label>
                            <p class="fw-bold fs-5">{{ $department->name }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Description</label>
                            <p class="text-dark">{{ $department->description ?? 'No description provided' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Status</label>
                            <div>
                                @if($department->is_active)
                                    <span class="badge bg-success fs-6"><i class="fas fa-check-circle"></i> Active</span>
                                @else
                                    <span class="badge bg-danger fs-6"><i class="fas fa-times-circle"></i> Inactive</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Created At</label>
                            <p class="text-dark">{{ $department->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hierarchy Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-sitemap"></i> Organizational Hierarchy</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Parent Department</label>
                            @if($department->parent)
                                <p class="text-dark">
                                    <a href="{{ route('hr.departments.show', $department->parent->id) }}" class="text-decoration-none">
                                        <i class="fas fa-building text-primary"></i> {{ $department->parent->name }}
                                    </a>
                                </p>
                            @else
                                <p class="text-muted fst-italic">Top Level Department</p>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Sub-Departments</label>
                            @if($department->children->count() > 0)
                                <ul class="list-unstyled mb-0">
                                    @foreach($department->children as $child)
                                        <li class="mb-1">
                                            <a href="{{ route('hr.departments.show', $child->id) }}" class="text-decoration-none">
                                                <i class="fas fa-angle-right text-primary"></i> {{ $child->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted fst-italic">No sub-departments</p>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <label class="text-muted small">Department Manager</label>
                            @if($department->manager)
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        {{ strtoupper(substr($department->manager->employee_name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="fw-bold mb-0">{{ $department->manager->employee_name }}</p>
                                        <small class="text-muted">{{ $department->manager->employee_code }}</small>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted fst-italic">No manager assigned</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employees Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Department Employees ({{ $department->employees->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($department->employees->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Employee Code</th>
                                        <th>Name</th>
                                        <th>Designation</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($department->employees as $employee)
                                        <tr>
                                            <td>{{ $employee->employee_code }}</td>
                                            <td>{{ $employee->employee_name }}</td>
                                            <td>{{ $employee->designation->name ?? 'N/A' }}</td>
                                            <td>
                                                @if($employee->status == 'Active')
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $employee->status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle"></i> No employees assigned to this department.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics Sidebar -->
        <div class="col-lg-4">
            <!-- Stats Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="stat-item mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Total Employees</p>
                                <h3 class="mb-0 text-primary">{{ $department->employees->count() }}</h3>
                            </div>
                            <div class="stat-icon bg-primary-subtle">
                                <i class="fas fa-users text-primary"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-item mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Active Employees</p>
                                <h3 class="mb-0 text-success">{{ $department->employees->where('status', 'Active')->count() }}</h3>
                            </div>
                            <div class="stat-icon bg-success-subtle">
                                <i class="fas fa-user-check text-success"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-item mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Sub-Departments</p>
                                <h3 class="mb-0 text-info">{{ $department->children->count() }}</h3>
                            </div>
                            <div class="stat-icon bg-info-subtle">
                                <i class="fas fa-sitemap text-info"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Department Level</p>
                                <h3 class="mb-0 text-warning">
                                    @php
                                        $level = 1;
                                        $current = $department;
                                        while($current->parent) {
                                            $level++;
                                            $current = $current->parent;
                                        }
                                    @endphp
                                    Level {{ $level }}
                                </h3>
                            </div>
                            <div class="stat-icon bg-warning-subtle">
                                <i class="fas fa-layer-group text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('hr.departments.edit', $department->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit"></i> Edit Department
                        </a>
                        <button class="btn btn-outline-success" disabled>
                            <i class="fas fa-user-plus"></i> Add Employee
                        </button>
                        <button class="btn btn-outline-info" disabled>
                            <i class="fas fa-file-export"></i> Export Report
                        </button>
                        <form action="{{ route('hr.departments.destroy', $department->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this department?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash"></i> Delete Department
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .avatar-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .stat-item {
        padding-bottom: 1rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .stat-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .btn {
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    
    .breadcrumb {
        background: transparent;
        padding: 0;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    .bg-primary-subtle {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
    
    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
    
    .bg-info-subtle {
        background-color: rgba(13, 202, 240, 0.1) !important;
    }
    
    .bg-warning-subtle {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
</style>
@endsection
