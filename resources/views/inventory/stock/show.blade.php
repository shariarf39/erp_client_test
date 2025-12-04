@extends('layouts.app')

@section('title', 'Stock Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-box-seam me-2"></i>Stock Details
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory.stock.index') }}">Stock Management</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('inventory.stock.edit', $stock) }}" class="btn btn-warning">
                <i class="bi bi-pencil-square me-2"></i>Adjust Stock
            </a>
            <a href="{{ route('inventory.stock.index') }}" class="btn btn-secondary">
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
        <!-- Stock Overview Card -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Stock Overview
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Item Information -->
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-box me-2"></i>Item Information
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">Item Code:</td>
                                    <td><strong>{{ $stock->item->code }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Item Name:</td>
                                    <td><strong>{{ $stock->item->name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Category:</td>
                                    <td>
                                        @if($stock->item->category)
                                            <span class="badge bg-secondary">{{ $stock->item->category->name }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Brand:</td>
                                    <td>
                                        @if($stock->item->brand)
                                            <span class="badge bg-info">{{ $stock->item->brand->name }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Unit:</td>
                                    <td>
                                        @if($stock->item->unit)
                                            <span class="badge bg-light text-dark">{{ $stock->item->unit->name }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Warehouse Information -->
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-building me-2"></i>Warehouse Information
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">Warehouse:</td>
                                    <td><strong>{{ $stock->warehouse->name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Location:</td>
                                    <td>{{ $stock->warehouse->location ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Capacity:</td>
                                    <td>{{ $stock->warehouse->capacity ? number_format($stock->warehouse->capacity, 2) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Manager:</td>
                                    <td>{{ $stock->warehouse->manager_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status:</td>
                                    <td>
                                        @if($stock->warehouse->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-12">
                            <hr>
                        </div>

                        <!-- Stock Details -->
                        <div class="col-md-12">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-clipboard-data me-2"></i>Stock Details
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="p-3 border rounded text-center">
                                        <div class="text-muted small">Current Stock</div>
                                        <div class="h4 mb-0 mt-1
                                            @if($stock->reorder_level && $stock->quantity <= $stock->reorder_level)
                                                text-danger
                                            @elseif($stock->max_level && $stock->quantity > $stock->max_level)
                                                text-info
                                            @else
                                                text-success
                                            @endif">
                                            {{ number_format($stock->quantity, 2) }}
                                        </div>
                                        <small class="text-muted">{{ $stock->unit->name ?? 'N/A' }}</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-3 border rounded text-center">
                                        <div class="text-muted small">Reorder Level</div>
                                        <div class="h4 mb-0 mt-1">
                                            {{ $stock->reorder_level ? number_format($stock->reorder_level, 2) : 'Not Set' }}
                                        </div>
                                        <small class="text-muted">Minimum</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-3 border rounded text-center">
                                        <div class="text-muted small">Maximum Level</div>
                                        <div class="h4 mb-0 mt-1">
                                            {{ $stock->max_level ? number_format($stock->max_level, 2) : 'Not Set' }}
                                        </div>
                                        <small class="text-muted">Maximum</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-3 border rounded text-center">
                                        <div class="text-muted small">Stock Status</div>
                                        <div class="mt-1">
                                            @if($stock->quantity == 0)
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @elseif($stock->reorder_level && $stock->quantity <= $stock->reorder_level)
                                                <span class="badge bg-warning">Low Stock</span>
                                            @elseif($stock->max_level && $stock->quantity > $stock->max_level)
                                                <span class="badge bg-info">Overstocked</span>
                                            @else
                                                <span class="badge bg-success">Normal</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Last Transaction -->
                        <div class="col-md-12">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-clock-history me-2"></i>Last Transaction
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="20%">Transaction Date:</td>
                                    <td>
                                        @if($stock->last_transaction_date)
                                            {{ \Carbon\Carbon::parse($stock->last_transaction_date)->format('d M Y, h:i A') }}
                                        @else
                                            <span class="text-muted">No transactions yet</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Transaction Type:</td>
                                    <td>
                                        @if($stock->last_transaction_type)
                                            @if($stock->last_transaction_type == 'IN')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-arrow-down-circle me-1"></i>Stock In
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-arrow-up-circle me-1"></i>Stock Out
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Remarks -->
                        @if($stock->remarks)
                            <div class="col-md-12">
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-chat-left-text me-2"></i>Remarks
                                </h6>
                                <div class="p-3 bg-light rounded">
                                    {{ $stock->remarks }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Stats -->
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
                        <a href="{{ route('inventory.stock.edit', $stock) }}" class="btn btn-warning">
                            <i class="bi bi-pencil-square me-2"></i>Adjust Stock Quantity
                        </a>
                        <a href="{{ route('inventory.items.show', $stock->item) }}" class="btn btn-info">
                            <i class="bi bi-box me-2"></i>View Item Details
                        </a>
                        <form action="{{ route('inventory.stock.destroy', $stock) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this stock record?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" {{ $stock->quantity > 0 ? 'disabled' : '' }}>
                                <i class="bi bi-trash me-2"></i>Delete Stock Record
                            </button>
                        </form>
                        @if($stock->quantity > 0)
                            <small class="text-danger text-center">
                                <i class="bi bi-exclamation-triangle me-1"></i>Stock must be zero to delete
                            </small>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stock Valuation (if price available) -->
            @if($stock->item->price)
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-currency-dollar me-2"></i>Valuation
                    </h5>
                </div>
                <div class="card-body p-3">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Unit Price:</td>
                            <td class="text-end"><strong>৳{{ number_format($stock->item->price, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Quantity:</td>
                            <td class="text-end"><strong>{{ number_format($stock->quantity, 2) }}</strong></td>
                        </tr>
                        <tr class="border-top">
                            <td class="text-muted"><strong>Total Value:</strong></td>
                            <td class="text-end">
                                <strong class="text-primary">
                                    ৳{{ number_format($stock->item->price * $stock->quantity, 2) }}
                                </strong>
                            </td>
                        </tr>
                    </table>
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
