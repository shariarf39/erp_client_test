@extends('layouts.app')

@section('title', 'Branches - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0"><i class="fas fa-code-branch me-2"></i>Branches</h2>
                <a href="{{ route('hr.branches.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Branch
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Branch Name</th>
                            <th>City</th>
                            <th>Phone</th>
                            <th>Manager</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($branches as $branch)
                        <tr>
                            <td>{{ $loop->iteration + ($branches->currentPage() - 1) * $branches->perPage() }}</td>
                            <td><strong>{{ $branch->code }}</strong></td>
                            <td>{{ $branch->name }}</td>
                            <td>{{ $branch->city ?? '-' }}</td>
                            <td>{{ $branch->phone ?? '-' }}</td>
                            <td>
                                @if($branch->manager)
                                    {{ $branch->manager->first_name }} {{ $branch->manager->last_name }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($branch->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('hr.branches.show', $branch->id) }}" class="btn btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('hr.branches.edit', $branch->id) }}" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('hr.branches.destroy', $branch->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this branch?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                No branches found. <a href="{{ route('hr.branches.create') }}">Create one</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $branches->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
