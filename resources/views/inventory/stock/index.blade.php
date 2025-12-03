@extends('layouts.app')

@section('title', 'Stock Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Stock Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Inventory</li>
                    <li class="breadcrumb-item active">Stock</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('inventory.stock.report') }}" class="btn btn-info">
                <i class="fas fa-file-export"></i> Stock Report
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Items</p>
                            <h4 class="mb-0">{{ $stocks->total() }}</h4>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-boxes fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Stock Value</p>
                            <h4 class="mb-0">৳{{ number_format($totalValue, 2) }}</h4>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-dollar-sign fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Low Stock Items</p>
                            <h4 class="mb-0 text-warning">{{ $stocks->where('quantity', '<=', $stocks->pluck('reorder_level'))->count() }}</h4>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Warehouses</p>
                            <h4 class="mb-0">{{ $warehouses->count() }}</h4>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-warehouse fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('inventory.stock.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search Item</label>
                    <input type="text" name="search" class="form-control" placeholder="Item name or code..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Warehouse</label>
                    <select name="warehouse_id" class="form-select">
                        <option value="">All Warehouses</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->warehouse_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Stock Status</label>
                    <select name="low_stock" class="form-select">
                        <option value="">All Stock</option>
                        <option value="1" {{ request('low_stock') == '1' ? 'selected' : '' }}>Low Stock Only</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('inventory.stock.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Stock Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Warehouse</th>
                            <th>Current Stock</th>
                            <th>Unit</th>
                            <th>Reorder Level</th>
                            <th>Max Level</th>
                            <th>Status</th>
                            <th>Last Transaction</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stocks as $stock)
                            <tr>
                                <td><strong>{{ $stock->item->item_code ?? 'N/A' }}</strong></td>
                                <td>{{ $stock->item->item_name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $stock->item->category->category_name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ $stock->warehouse->warehouse_name ?? 'N/A' }}</td>
                                <td>
                                    <strong class="fs-5 
                                        @if($stock->quantity <= $stock->reorder_level) text-danger
                                        @elseif($stock->quantity >= $stock->max_level) text-success
                                        @else text-primary
                                        @endif
                                    ">
                                        {{ number_format($stock->quantity, 2) }}
                                    </strong>
                                </td>
                                <td>{{ $stock->item->unit->unit_name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-warning">
                                        {{ number_format($stock->reorder_level, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        {{ number_format($stock->max_level, 2) }}
                                    </span>
                                </td>
                                <td>
                                    @if($stock->quantity <= $stock->reorder_level)
                                        <span class="badge bg-danger">
                                            <i class="fas fa-exclamation-circle"></i> Low Stock
                                        </span>
                                    @elseif($stock->quantity >= $stock->max_level)
                                        <span class="badge bg-info">
                                            <i class="fas fa-check-circle"></i> Overstocked
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Normal
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($stock->last_transaction_date)
                                        <small>
                                            {{ $stock->last_transaction_date->format('d M Y') }}<br>
                                            <span class="badge badge-sm 
                                                {{ $stock->last_transaction_type == 'IN' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $stock->last_transaction_type }}
                                            </span>
                                        </small>
                                    @else
                                        <small class="text-muted">No transaction</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('inventory.stock.show', $stock->id) }}" class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('inventory.stock.edit', $stock->id) }}" class="btn btn-outline-warning" title="Adjust Stock">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No stock records found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $stocks->firstItem() ?? 0 }} to {{ $stocks->lastItem() ?? 0 }} of {{ $stocks->total() }} entries
                </div>
                <div>
                    {{ $stocks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
