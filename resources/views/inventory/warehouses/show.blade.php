@extends('layouts.app')

@section('title', 'Warehouse Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-building me-2"></i>Warehouse Details
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory.warehouses.index') }}">Warehouses</a></li>
                    <li class="breadcrumb-item active">{{ $warehouse->warehouse_name }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('inventory.warehouses.edit', $warehouse) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('inventory.warehouses.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Warehouse Information -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Warehouse Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Basic Details -->
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-building me-2"></i>Basic Details
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">Warehouse Code:</td>
                                    <td><strong>{{ $warehouse->warehouse_code }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Warehouse Name:</td>
                                    <td><strong>{{ $warehouse->warehouse_name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status:</td>
                                    <td>
                                        @if($warehouse->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Location & Contact -->
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-geo-alt me-2"></i>Location & Contact
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">City:</td>
                                    <td>{{ $warehouse->city ?? 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Phone:</td>
                                    <td>
                                        @if($warehouse->phone)
                                            <i class="bi bi-telephone me-1"></i>{{ $warehouse->phone }}
                                        @else
                                            <span class="text-muted">Not specified</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Manager:</td>
                                    <td>
                                        @if($warehouse->manager)
                                            <span class="badge bg-info">
                                                {{ $warehouse->manager->first_name }} {{ $warehouse->manager->last_name }}
                                            </span>
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Address -->
                        @if($warehouse->address)
                            <div class="col-12">
                                <hr>
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-map me-2"></i>Address
                                </h6>
                                <div class="p-3 bg-light rounded">
                                    {{ $warehouse->address }}
                                </div>
                            </div>
                        @endif

                        <!-- Stock Statistics -->
                        <div class="col-12">
                            <hr>
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-box-seam me-2"></i>Stock Statistics
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="p-3 border rounded text-center">
                                        <div class="text-muted small">Total Items</div>
                                        <div class="h4 mb-0 mt-1 text-primary">{{ $warehouse->stocks->count() }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 border rounded text-center">
                                        <div class="text-muted small">Total Quantity</div>
                                        <div class="h4 mb-0 mt-1 text-success">
                                            {{ number_format($warehouse->stocks->sum('quantity'), 2) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 border rounded text-center">
                                        <div class="text-muted small">Low Stock Items</div>
                                        <div class="h4 mb-0 mt-1 text-warning">
                                            {{ $warehouse->stocks->where('quantity', '<=', 'reorder_level')->count() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stock Items -->
                        @if($warehouse->stocks->count() > 0)
                            <div class="col-12">
                                <hr>
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-list-ul me-2"></i>Stock Items ({{ $warehouse->stocks->count() }})
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Item Code</th>
                                                <th>Item Name</th>
                                                <th class="text-end">Quantity</th>
                                                <th>Unit</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($warehouse->stocks->take(10) as $stock)
                                                <tr>
                                                    <td><strong>{{ $stock->item->code }}</strong></td>
                                                    <td>{{ $stock->item->name }}</td>
                                                    <td class="text-end">
                                                        <strong class="
                                                            @if($stock->quantity == 0)
                                                                text-danger
                                                            @elseif($stock->reorder_level && $stock->quantity <= $stock->reorder_level)
                                                                text-warning
                                                            @else
                                                                text-success
                                                            @endif
                                                        ">
                                                            {{ number_format($stock->quantity, 2) }}
                                                        </strong>
                                                    </td>
                                                    <td>{{ $stock->unit->name ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($stock->quantity == 0)
                                                            <span class="badge bg-danger">Out</span>
                                                        @elseif($stock->reorder_level && $stock->quantity <= $stock->reorder_level)
                                                            <span class="badge bg-warning">Low</span>
                                                        @else
                                                            <span class="badge bg-success">OK</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('inventory.stock.show', $stock) }}" class="btn btn-sm btn-info">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if($warehouse->stocks->count() > 10)
                                        <div class="text-center py-2">
                                            <small class="text-muted">Showing 10 of {{ $warehouse->stocks->count() }} items</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Timestamps -->
                        <div class="col-12">
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-plus me-1"></i>Created: {{ $warehouse->created_at->format('d M Y, h:i A') }}
                                    </small>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-check me-1"></i>Updated: {{ $warehouse->updated_at->format('d M Y, h:i A') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Manager Info -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('inventory.warehouses.edit', $warehouse) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Edit Warehouse
                        </a>
                        <a href="{{ route('inventory.stock.index') }}?warehouse_id={{ $warehouse->id }}" class="btn btn-info">
                            <i class="bi bi-box-seam me-2"></i>View Stock
                        </a>
                        <a href="{{ route('inventory.stock.create') }}?warehouse_id={{ $warehouse->id }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-2"></i>Add Stock
                        </a>
                        <form action="{{ route('inventory.warehouses.destroy', $warehouse) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this warehouse?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-danger w-100" 
                                    {{ $warehouse->stocks->count() > 0 ? 'disabled' : '' }}>
                                <i class="bi bi-trash me-2"></i>Delete Warehouse
                            </button>
                        </form>
                        @if($warehouse->stocks->count() > 0)
                            <small class="text-danger text-center">
                                <i class="bi bi-exclamation-triangle me-1"></i>Cannot delete with stock
                            </small>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Manager Information -->
            @if($warehouse->manager)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-person-badge me-2"></i>Manager Info
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="text-center mb-3">
                            @if($warehouse->manager->photo)
                                <img src="{{ asset('storage/' . $warehouse->manager->photo) }}" 
                                     alt="{{ $warehouse->manager->first_name }}"
                                     class="rounded-circle"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center"
                                     style="width: 80px; height: 80px;">
                                    <i class="bi bi-person text-white" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                        </div>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted">Name:</td>
                                <td><strong>{{ $warehouse->manager->first_name }} {{ $warehouse->manager->last_name }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Employee ID:</td>
                                <td>{{ $warehouse->manager->employee_id }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email:</td>
                                <td>{{ $warehouse->manager->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Phone:</td>
                                <td>{{ $warehouse->manager->phone ?? 'N/A' }}</td>
                            </tr>
                        </table>
                        <div class="d-grid mt-3">
                            <a href="{{ route('hr.employees.show', $warehouse->manager) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye me-2"></i>View Employee Profile
                            </a>
                        </div>
                    </div>
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
