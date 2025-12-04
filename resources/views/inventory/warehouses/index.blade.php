@extends('layouts.app')

@section('title', 'Warehouses')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-building me-2"></i>Warehouses
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Inventory</li>
                    <li class="breadcrumb-item active">Warehouses</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('inventory.warehouses.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Add Warehouse
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Warehouses Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient text-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>All Warehouses
            </h5>
        </div>
        <div class="card-body p-0">
            @if($warehouses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="10%">Code</th>
                                <th width="20%">Warehouse Name</th>
                                <th width="20%">Location</th>
                                <th width="15%">Contact</th>
                                <th width="15%">Manager</th>
                                <th width="10%" class="text-center">Status</th>
                                <th width="10%" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($warehouses as $warehouse)
                                <tr>
                                    <td><strong>{{ $warehouse->warehouse_code }}</strong></td>
                                    <td>
                                        <i class="bi bi-building me-2"></i>{{ $warehouse->warehouse_name }}
                                    </td>
                                    <td>
                                        @if($warehouse->city)
                                            <i class="bi bi-geo-alt me-1"></i>{{ $warehouse->city }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($warehouse->phone)
                                            <i class="bi bi-telephone me-1"></i>{{ $warehouse->phone }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($warehouse->manager)
                                            <span class="badge bg-info">
                                                {{ $warehouse->manager->first_name }} {{ $warehouse->manager->last_name }}
                                            </span>
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($warehouse->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('inventory.warehouses.show', $warehouse) }}" 
                                               class="btn btn-info" 
                                               title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('inventory.warehouses.edit', $warehouse) }}" 
                                               class="btn btn-warning" 
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('inventory.warehouses.destroy', $warehouse) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this warehouse?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
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
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing {{ $warehouses->firstItem() }} to {{ $warehouses->lastItem() }} of {{ $warehouses->total() }} entries
                        </div>
                        {{ $warehouses->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">No warehouses found. Create your first warehouse!</p>
                    <a href="{{ route('inventory.warehouses.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add Warehouse
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection
