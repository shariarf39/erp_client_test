@extends('layouts.app')

@section('title', 'Checklist Details - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>{{ $checklist->name }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.onboarding.checklists.index') }}">Onboarding Checklists</a></li>
                    <li class="breadcrumb-item active">{{ $checklist->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Checklist Information</h5>
                    <span class="badge {{ $checklist->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $checklist->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Checklist Name</h6>
                            <p class="mb-0"><strong>{{ $checklist->name }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Total Tasks</h6>
                            <p class="mb-0">{{ $checklist->tasks->count() }} tasks</p>
                        </div>
                    </div>

                    @if($checklist->description)
                    <div class="mb-3">
                        <h6 class="text-muted">Description</h6>
                        <p class="mb-0">{{ $checklist->description }}</p>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Department</h6>
                            <p class="mb-0">{{ $checklist->department->name ?? 'All Departments' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Designation</h6>
                            <p class="mb-0">{{ $checklist->designation->title ?? 'All Designations' }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Mandatory Tasks</h6>
                            <p class="mb-0">{{ $checklist->tasks->where('is_mandatory', 1)->count() }} mandatory</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Optional Tasks</h6>
                            <p class="mb-0">{{ $checklist->tasks->where('is_mandatory', 0)->count() }} optional</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Onboarding Tasks ({{ $checklist->tasks->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($checklist->tasks->isEmpty())
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>No tasks defined for this checklist.
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($checklist->tasks as $task)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="badge bg-secondary me-2">#{{ $task->sequence_order }}</span>
                                                <h6 class="mb-0">{{ $task->task_name }}</h6>
                                                @if($task->is_mandatory)
                                                    <span class="badge bg-danger ms-2">Mandatory</span>
                                                @endif
                                            </div>
                                            
                                            @if($task->description)
                                                <p class="text-muted small mb-2">{{ $task->description }}</p>
                                            @endif
                                            
                                            <div class="d-flex gap-2">
                                                <span class="badge bg-info">{{ $task->category }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('hr.onboarding.checklists.edit', $checklist) }}" class="btn btn-warning w-100 mb-2">
                        <i class="fas fa-edit me-2"></i>Edit Checklist
                    </a>

                    <form action="{{ route('hr.onboarding.checklists.destroy', $checklist) }}" method="POST" class="mb-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('Are you sure you want to delete this checklist?')">
                            <i class="fas fa-trash me-2"></i>Delete Checklist
                        </button>
                    </form>

                    <a href="{{ route('hr.onboarding.checklists.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Task Breakdown</h6>
                </div>
                <div class="card-body">
                    @php
                        $categories = $checklist->tasks->groupBy('category');
                    @endphp
                    @foreach($categories as $category => $tasks)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $category }}:</span>
                            <strong>{{ $tasks->count() }}</strong>
                        </div>
                    @endforeach
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <span><strong>Total:</strong></span>
                        <strong>{{ $checklist->tasks->count() }}</strong>
                    </div>
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
                            <small class="text-muted ms-3">{{ $checklist->created_at->format('M j, Y H:i') }}</small>
                        </li>
                        @if($checklist->updated_at != $checklist->created_at)
                        <li>
                            <i class="fas fa-edit text-warning me-2"></i>
                            <strong>Last Updated:</strong><br>
                            <small class="text-muted ms-3">{{ $checklist->updated_at->format('M j, Y H:i') }}</small>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
