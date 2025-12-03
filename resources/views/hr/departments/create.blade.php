@extends('layouts.app')

@section('title', 'Add New Department')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Add New Department</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">HR</li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.departments.index') }}">Departments</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('hr.departments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('hr.departments.store') }}" method="POST">
                @csrf
                
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
                                    id="code" name="code" value="{{ old('code') }}" required 
                                    placeholder="e.g., DEPT001">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Unique identifier for the department</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Department Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                    id="name" name="name" value="{{ old('name') }}" required 
                                    placeholder="e.g., Human Resources">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="description" name="description" rows="3" 
                                    placeholder="Enter department description...">{{ old('description') }}</textarea>
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
                                        <option value="{{ $dept->id }}" {{ old('parent_id') == $dept->id ? 'selected' : '' }}>
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
                                        <option value="{{ $employee->id }}" {{ old('manager_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->employee_code }} - {{ $employee->employee_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('manager_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Assign a manager for this department</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status & Actions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-cog"></i> Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <strong>Active Status</strong>
                                <br><small class="text-muted">Enable this department for operations</small>
                            </label>
                        </div>

                        <hr>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Department
                            </button>
                            <a href="{{ route('hr.departments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Help Panel -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Quick Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success"></i>
                            <strong>Department Code</strong>
                            <br><small class="text-muted">Use a unique code like DEPT001, HR, IT, etc.</small>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success"></i>
                            <strong>Department Name</strong>
                            <br><small class="text-muted">Use clear, descriptive names like "Human Resources", "IT Support"</small>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success"></i>
                            <strong>Parent Department</strong>
                            <br><small class="text-muted">Create hierarchical structure by selecting a parent</small>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success"></i>
                            <strong>Manager Assignment</strong>
                            <br><small class="text-muted">You can assign a manager now or later</small>
                        </li>
                        <li>
                            <i class="fas fa-check-circle text-success"></i>
                            <strong>Active Status</strong>
                            <br><small class="text-muted">Only active departments appear in dropdowns</small>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-chart-tree"></i> Example Structure</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-building text-primary"></i> <strong>Operations</strong>
                    </div>
                    <div class="ms-3 mb-2">
                        <i class="fas fa-angle-right text-muted"></i> Production
                    </div>
                    <div class="ms-3 mb-2">
                        <i class="fas fa-angle-right text-muted"></i> Quality Control
                    </div>
                    <div class="mb-2 mt-3">
                        <i class="fas fa-building text-primary"></i> <strong>Administration</strong>
                    </div>
                    <div class="ms-3 mb-2">
                        <i class="fas fa-angle-right text-muted"></i> Human Resources
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-angle-right text-muted"></i> Finance
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
