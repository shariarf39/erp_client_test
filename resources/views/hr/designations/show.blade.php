@extends('layouts.app')

@section('title', 'Designation Details - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-id-badge me-2"></i>Designation Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.designations.index') }}">Designations</a></li>
                    <li class="breadcrumb-item active">{{ $designation->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $designation->title }}</h5>
                    <div>
                        <a href="{{ route('hr.designations.edit', $designation->id) }}" class="btn btn-sm btn-light">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Designation Code:</strong>
                            <p>{{ $designation->code }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Title:</strong>
                            <p>{{ $designation->title }}</p>
                        </div>
                    </div>

                    @if($designation->description)
                    <div class="row">
                        <div class="col-12 mb-3">
                            <strong>Description:</strong>
                            <p>{{ $designation->description }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Level:</strong>
                            <p>{{ $designation->level ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Status:</strong>
                            <p>
                                @if($designation->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <strong>Created:</strong>
                            <p class="small text-muted">{{ $designation->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Last Updated:</strong>
                            <p class="small text-muted">{{ $designation->updated_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('hr.designations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                    <a href="{{ route('hr.designations.edit', $designation->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <form action="{{ route('hr.designations.destroy', $designation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this designation?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Quick Info</h6>
                </div>
                <div class="card-body">
                    <p class="small mb-2"><strong>Code:</strong> {{ $designation->code }}</p>
                    <p class="small mb-2"><strong>Level:</strong> {{ $designation->level ?? 'Not Set' }}</p>
                    <p class="small mb-2">
                        <strong>Status:</strong> 
                        @if($designation->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
