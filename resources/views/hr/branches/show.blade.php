@extends('layouts.app')

@section('title', 'Branch Details - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0"><i class="fas fa-code-branch me-2"></i>{{ $branch->name }}</h2>
                <div>
                    <a href="{{ route('hr.branches.edit', $branch->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <a href="{{ route('hr.branches.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Branch Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Branch Code:</strong>
                    <p>{{ $branch->code }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Branch Name:</strong>
                    <p>{{ $branch->name }}</p>
                </div>
            </div>

            @if($branch->address)
            <div class="row">
                <div class="col-12 mb-3">
                    <strong>Address:</strong>
                    <p>{{ $branch->address }}</p>
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>City:</strong>
                    <p>{{ $branch->city ?? '-' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Phone:</strong>
                    <p>{{ $branch->phone ?? '-' }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Email:</strong>
                    <p>{{ $branch->email ?? '-' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Branch Manager:</strong>
                    <p>
                        @if($branch->manager)
                            {{ $branch->manager->first_name }} {{ $branch->manager->last_name }}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Status:</strong>
                    <p>
                        @if($branch->is_active)
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
                    <p class="small text-muted">{{ $branch->created_at->format('d M Y, h:i A') }}</p>
                </div>
                <div class="col-md-6">
                    <strong>Last Updated:</strong>
                    <p class="small text-muted">{{ $branch->updated_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
