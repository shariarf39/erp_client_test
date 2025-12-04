@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Edit Department</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">HR</li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.departments.index') }}">Departments</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('hr.departments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <a href="{{ route('hr.departments.show', $department->id) }}" class="btn btn-info">
                <i class="fas fa-eye"></i> View Details
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('hr.departments.update', $department->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Department Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="code" class="form-label">Department Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                    id="code" name="code" value="{{ old('code', $department->code) }}" required 
                                    placeholder="e.g., DEPT001">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Unique identifier for the department</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Department Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                    id="name" name="name" value="{{ old('name', $department->name) }}" required 
                                    placeholder="e.g., Human Resources">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="description" name="description" rows="3" 
                                    placeholder="Enter department description...">{{ old('description', $department->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hierarchy & Management -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-sitemap"></i> Hierarchy & Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="parent_id" class="form-label">Parent Department</label>
                                <select class="form-select @error('parent_id') is-invalid @enderror" 
                                    id="parent_id" name="parent_id">
                                    <option value="">No Parent (Top Level)</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('parent_id', $department->parent_id) == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->code }} - {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Select if this is a sub-department</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="manager_id" class="form-label">Department Manager</label>
                                <select class="form-select @error('manager_id') is-invalid @enderror" 
                                    id="manager_id" name="manager_id">
                                    <option value="">No Manager Assigned</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('manager_id', $department->manager_id) == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->employee_code }} - {{ $employee->employee_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('manager_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Assign an employee as department manager</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-toggle-on"></i> Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" 
                                id="is_active" name="is_active" value="1" 
                                {{ old('is_active', $department->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Department
                            </label>
                        </div>
                        <small class="text-muted d-block mt-2">Inactive departments will not be available for selection in other forms</small>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Department
                            </button>
                        </div>
                    </div>
                </div>
            </form>
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
    
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #6f42c1;
        box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
    }
    
    .text-danger {
        font-weight: 700;
    }
    
    small.text-muted {
        display: block;
        margin-top: 0.25rem;
    }
    
    .btn {
        font-weight: 500;
        padding: 0.5rem 1.25rem;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    
    .breadcrumb {
        background: transparent;
        padding: 0;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        color: #6c757d;
    }
    
    .form-check-input:checked {
        background-color: #6f42c1;
        border-color: #6f42c1;
    }
</style>
@endsection
