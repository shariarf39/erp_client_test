@extends('layouts.app')

@section('title', 'Create Onboarding Checklist - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Create Onboarding Checklist</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.onboarding.checklists.index') }}">Onboarding Checklists</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <form action="{{ route('hr.onboarding.checklists.store') }}" method="POST" id="checklistForm">
                @csrf

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Checklist Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Checklist Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            <small class="text-muted">Example: "IT Department Onboarding", "Sales Team Checklist"</small>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            <small class="text-muted">Brief description of this onboarding checklist</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="department_id" class="form-label">Department</label>
                                <select class="form-select @error('department_id') is-invalid @enderror" 
                                        id="department_id" name="department_id">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Leave blank for all departments</small>
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
                                        <option value="{{ $designation->id }}" {{ old('designation_id') == $designation->id ? 'selected' : '' }}>
                                            {{ $designation->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Leave blank for all designations</small>
                                @error('designation_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (checklist is available for use)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Onboarding Tasks</h5>
                        <button type="button" class="btn btn-light btn-sm" id="addTaskBtn">
                            <i class="fas fa-plus me-1"></i>Add Task
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="tasksContainer">
                            @if(old('tasks'))
                                @foreach(old('tasks') as $index => $task)
                                    <div class="task-item border rounded p-3 mb-3 position-relative" data-task-index="{{ $index }}">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 remove-task">
                                            <i class="fas fa-times"></i>
                                        </button>

                                        <div class="row mb-2">
                                            <div class="col-md-8">
                                                <label class="form-label">Task Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error("tasks.{$index}.task_name") is-invalid @enderror" 
                                                       name="tasks[{{ $index }}][task_name]" value="{{ $task['task_name'] ?? '' }}" required>
                                                @error("tasks.{$index}.task_name")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Sequence Order <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control @error("tasks.{$index}.sequence_order") is-invalid @enderror" 
                                                       name="tasks[{{ $index }}][sequence_order]" value="{{ $task['sequence_order'] ?? ($index + 1) }}" min="1" required>
                                                @error("tasks.{$index}.sequence_order")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control @error("tasks.{$index}.description") is-invalid @enderror" 
                                                      name="tasks[{{ $index }}][description]" rows="2">{{ $task['description'] ?? '' }}</textarea>
                                            @error("tasks.{$index}.description")
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                                <select class="form-select @error("tasks.{$index}.category") is-invalid @enderror" 
                                                        name="tasks[{{ $index }}][category]" required>
                                                    <option value="">Select Category</option>
                                                    <option value="Documentation" {{ ($task['category'] ?? '') == 'Documentation' ? 'selected' : '' }}>Documentation</option>
                                                    <option value="IT Setup" {{ ($task['category'] ?? '') == 'IT Setup' ? 'selected' : '' }}>IT Setup</option>
                                                    <option value="Access & Permissions" {{ ($task['category'] ?? '') == 'Access & Permissions' ? 'selected' : '' }}>Access & Permissions</option>
                                                    <option value="Training" {{ ($task['category'] ?? '') == 'Training' ? 'selected' : '' }}>Training</option>
                                                    <option value="Introduction" {{ ($task['category'] ?? '') == 'Introduction' ? 'selected' : '' }}>Introduction</option>
                                                    <option value="Equipment" {{ ($task['category'] ?? '') == 'Equipment' ? 'selected' : '' }}>Equipment</option>
                                                    <option value="Administrative" {{ ($task['category'] ?? '') == 'Administrative' ? 'selected' : '' }}>Administrative</option>
                                                    <option value="Other" {{ ($task['category'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @error("tasks.{$index}.category")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">&nbsp;</label>
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="tasks[{{ $index }}][is_mandatory]" value="1"
                                                           {{ ($task['is_mandatory'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label">
                                                        Mandatory Task
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <!-- Default first task -->
                                <div class="task-item border rounded p-3 mb-3 position-relative" data-task-index="0">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 remove-task">
                                        <i class="fas fa-times"></i>
                                    </button>

                                    <div class="row mb-2">
                                        <div class="col-md-8">
                                            <label class="form-label">Task Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="tasks[0][task_name]" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Sequence Order <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="tasks[0][sequence_order]" value="1" min="1" required>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="tasks[0][description]" rows="2"></textarea>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <label class="form-label">Category <span class="text-danger">*</span></label>
                                            <select class="form-select" name="tasks[0][category]" required>
                                                <option value="">Select Category</option>
                                                <option value="Documentation">Documentation</option>
                                                <option value="IT Setup">IT Setup</option>
                                                <option value="Access & Permissions">Access & Permissions</option>
                                                <option value="Training">Training</option>
                                                <option value="Introduction">Introduction</option>
                                                <option value="Equipment">Equipment</option>
                                                <option value="Administrative">Administrative</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">&nbsp;</label>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="tasks[0][is_mandatory]" value="1">
                                                <label class="form-check-label">
                                                    Mandatory Task
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <small>At least one task is required. Use sequence order to define the task completion order.</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Checklist
                    </button>
                    <a href="{{ route('hr.onboarding.checklists.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>

        <div class="col-lg-3">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Guidelines</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Create role-specific checklists for different positions
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Include all necessary tasks for new employees
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Set appropriate sequence for task completion
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Mark critical tasks as mandatory
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-list me-2"></i>Task Categories</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-1"><strong>Documentation:</strong> Forms, contracts, policies</li>
                        <li class="mb-1"><strong>IT Setup:</strong> Computer, email, software</li>
                        <li class="mb-1"><strong>Access & Permissions:</strong> System access, badges</li>
                        <li class="mb-1"><strong>Training:</strong> Orientation, skill training</li>
                        <li class="mb-1"><strong>Introduction:</strong> Team meetings, introductions</li>
                        <li class="mb-1"><strong>Equipment:</strong> Office supplies, tools</li>
                        <li class="mb-1"><strong>Administrative:</strong> HR processes, records</li>
                        <li class="mb-1"><strong>Other:</strong> Additional tasks</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let taskIndex = {{ old('tasks') ? count(old('tasks')) : 1 }};

document.getElementById('addTaskBtn').addEventListener('click', function() {
    const container = document.getElementById('tasksContainer');
    const taskHtml = `
        <div class="task-item border rounded p-3 mb-3 position-relative" data-task-index="${taskIndex}">
            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 remove-task">
                <i class="fas fa-times"></i>
            </button>

            <div class="row mb-2">
                <div class="col-md-8">
                    <label class="form-label">Task Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="tasks[${taskIndex}][task_name]" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sequence Order <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="tasks[${taskIndex}][sequence_order]" value="${taskIndex + 1}" min="1" required>
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="tasks[${taskIndex}][description]" rows="2"></textarea>
            </div>

            <div class="row mb-2">
                <div class="col-md-6">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
                    <select class="form-select" name="tasks[${taskIndex}][category]" required>
                        <option value="">Select Category</option>
                        <option value="Documentation">Documentation</option>
                        <option value="IT Setup">IT Setup</option>
                        <option value="Access & Permissions">Access & Permissions</option>
                        <option value="Training">Training</option>
                        <option value="Introduction">Introduction</option>
                        <option value="Equipment">Equipment</option>
                        <option value="Administrative">Administrative</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">&nbsp;</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="tasks[${taskIndex}][is_mandatory]" value="1">
                        <label class="form-check-label">
                            Mandatory Task
                        </label>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', taskHtml);
    taskIndex++;
});

document.getElementById('tasksContainer').addEventListener('click', function(e) {
    if (e.target.closest('.remove-task')) {
        const taskItem = e.target.closest('.task-item');
        const totalTasks = document.querySelectorAll('.task-item').length;
        
        if (totalTasks > 1) {
            taskItem.remove();
        } else {
            alert('At least one task is required.');
        }
    }
});
</script>
@endpush
@endsection
