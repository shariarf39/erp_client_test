@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">Item Details</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('inventory.items.index') }}">Items</a></li>
                            <li class="breadcrumb-item active">{{ $item->item_code }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('inventory.items.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
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

    <div class="row">
        <div class="col-md-8">
            <!-- Item Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-box me-2"></i>Item Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted">Item Code</label>
                            <p class="fw-bold">{{ $item->item_code }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">Status</label>
                            <p>
                                <span class="badge bg-{{ $item->is_active ? 'success' : 'danger' }} fs-6">
                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="text-muted">Item Name</label>
                            <p class="fw-bold fs-5">{{ $item->item_name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="text-muted">Category</label>
                            <p class="fw-bold">{{ $item->category->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted">Brand</label>
                            <p class="fw-bold">{{ $item->brand->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted">Unit</label>
                            <p class="fw-bold">{{ $item->unit->name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted">Barcode</label>
                            <p>{{ $item->barcode ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">SKU</label>
                            <p>{{ $item->sku ?? '-' }}</p>
                        </div>
                    </div>

                    @if($item->description)
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="text-muted">Description</label>
                            <p class="border p-3 rounded bg-light">{{ $item->description }}</p>
                        </div>
                    </div>
                    @endif

                    @if($item->specifications)
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="text-muted">Specifications</label>
                            <p class="border p-3 rounded bg-light">{{ $item->specifications }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Pricing Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-currency-dollar me-2"></i>Pricing Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted">Purchase Price</label>
                            <p class="fw-bold fs-5 text-primary">৳{{ number_format($item->purchase_price ?? 0, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">Sale Price</label>
                            <p class="fw-bold fs-5 text-success">৳{{ number_format($item->sale_price ?? 0, 2) }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted">Reorder Level</label>
                            <p class="fw-bold">
                                @if($item->reorder_level)
                                    <span class="badge bg-warning text-dark fs-6">{{ $item->reorder_level }} units</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                        @if($item->purchase_price && $item->sale_price)
                        <div class="col-md-6">
                            <label class="text-muted">Profit Margin</label>
                            <p class="fw-bold">
                                @php
                                    $margin = (($item->sale_price - $item->purchase_price) / $item->purchase_price) * 100;
                                @endphp
                                <span class="badge bg-{{ $margin > 0 ? 'success' : 'danger' }} fs-6">
                                    {{ number_format($margin, 2) }}%
                                </span>
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stock Information Card -->
            @if($item->stock->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-boxes me-2"></i>Stock Information</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Warehouse</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalQty = 0; $totalValue = 0; @endphp
                                @foreach($item->stock as $stock)
                                    @php
                                        $totalQty += $stock->quantity;
                                        $totalValue += ($stock->quantity * $item->purchase_price);
                                    @endphp
                                    <tr>
                                        <td>{{ $stock->warehouse->name ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $stock->quantity > 0 ? 'success' : 'danger' }}">
                                                {{ $stock->quantity }}
                                            </span>
                                        </td>
                                        <td class="text-end">৳{{ number_format($stock->quantity * $item->purchase_price, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-primary fw-bold">
                                    <td>Total</td>
                                    <td class="text-center">{{ $totalQty }}</td>
                                    <td class="text-end">৳{{ number_format($totalValue, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if($item->reorder_level && $totalQty <= $item->reorder_level)
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Low Stock Alert!</strong> Current stock ({{ $totalQty }}) is at or below reorder level ({{ $item->reorder_level }}).
                        </div>
                    @endif
                </div>
            </div>
            @else
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-boxes me-2"></i>Stock Information</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0 text-center">
                        <i class="bi bi-info-circle me-2"></i>No stock records found for this item.
                    </div>
                </div>
            </div>
            @endif

            <!-- Record Information -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Record Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Created At</label>
                            <p>{{ $item->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        @if($item->updated_at != $item->created_at)
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Last Updated</label>
                            <p>{{ $item->updated_at->format('d M Y, h:i A') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Item Image Card -->
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-image me-2"></i>Item Image</h5>
                </div>
                <div class="card-body text-center">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" 
                             class="img-fluid rounded" 
                             alt="{{ $item->item_name }}"
                             style="max-height: 300px;">
                    @else
                        <div class="py-5">
                            <i class="bi bi-image" style="font-size: 5rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">No image available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('inventory.items.edit', $item) }}" class="btn btn-warning w-100 mb-2">
                        <i class="bi bi-pencil me-2"></i>Edit Item
                    </a>

                    <form action="{{ route('inventory.items.destroy', $item) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this item? This action cannot be undone.');"
                          class="d-inline w-100">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100 mb-2">
                            <i class="bi bi-trash me-2"></i>Delete Item
                        </button>
                    </form>

                    <button onclick="window.print()" class="btn btn-info w-100 mb-2">
                        <i class="bi bi-printer me-2"></i>Print
                    </button>

                    <a href="{{ route('inventory.items.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Quick Stats</h5>
                </div>
                <div class="card-body">
                    @php
                        $totalStock = $item->stock->sum('quantity');
                        $totalValue = $totalStock * ($item->purchase_price ?? 0);
                    @endphp
                    <div class="mb-3">
                        <label class="text-muted small">Total Stock</label>
                        <h4 class="mb-0">{{ $totalStock }} {{ $item->unit->name ?? 'units' }}</h4>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Stock Value</label>
                        <h4 class="mb-0 text-success">৳{{ number_format($totalValue, 2) }}</h4>
                    </div>
                    <div class="mb-0">
                        <label class="text-muted small">Warehouses</label>
                        <h4 class="mb-0">{{ $item->stock->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
        border-radius: 0.5rem;
    }
    
    @media print {
        .btn, .breadcrumb, .card-header {
            display: none !important;
        }
        
        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }
    }
</style>
@endpush
