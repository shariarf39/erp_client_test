@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Stock Report</h2>
                <div>
                    <button onclick="window.print()" class="btn btn-secondary me-2">
                        <i class="bi bi-printer me-2"></i>Print Report
                    </button>
                    <a href="{{ route('inventory.stock.index') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Stock
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Items</h5>
                    <h2>{{ $totalItems }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Quantity</h5>
                    <h2>{{ number_format($totalQuantity) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Low Stock Items</h5>
                    <h2>{{ $lowStockItems }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Out of Stock</h5>
                    <h2>{{ $outOfStockItems }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Value Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Stock Value</h5>
                    <h1>৳{{ number_format($totalValue, 2) }}</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4 no-print">
        <div class="card-body">
            <form method="GET" action="{{ route('inventory.stock.report') }}">
                <div class="row g-3">
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
                    <div class="col-md-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Stock Status</label>
                        <select name="stock_status" class="form-select">
                            <option value="">All Status</option>
                            <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-funnel me-2"></i>Filter
                        </button>
                        <a href="{{ route('inventory.stock.report') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stock Report Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Stock Report Details</h5>
        </div>
        <div class="card-body">
            @if($stocks->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Unit</th>
                                <th>Warehouse</th>
                                <th class="text-end">Quantity</th>
                                <th class="text-end">Reorder Level</th>
                                <th class="text-end">Cost Price</th>
                                <th class="text-end">Total Value</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $index => $stock)
                                <tr class="{{ $stock->quantity <= 0 ? 'table-danger' : ($stock->quantity <= $stock->reorder_level ? 'table-warning' : '') }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $stock->item->item_code ?? 'N/A' }}</td>
                                    <td>{{ $stock->item->item_name ?? 'N/A' }}</td>
                                    <td>{{ $stock->item->category->category_name ?? 'N/A' }}</td>
                                    <td>{{ $stock->item->brand->brand_name ?? 'N/A' }}</td>
                                    <td>{{ $stock->item->unit->unit_name ?? 'N/A' }}</td>
                                    <td>{{ $stock->warehouse->warehouse_name ?? 'N/A' }}</td>
                                    <td class="text-end">
                                        <strong>{{ number_format($stock->quantity, 2) }}</strong>
                                    </td>
                                    <td class="text-end">{{ number_format($stock->reorder_level, 2) }}</td>
                                    <td class="text-end">৳{{ number_format($stock->item->cost_price ?? 0, 2) }}</td>
                                    <td class="text-end">
                                        <strong>৳{{ number_format($stock->quantity * ($stock->item->cost_price ?? 0), 2) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($stock->quantity <= 0)
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @elseif($stock->quantity <= $stock->reorder_level)
                                            <span class="badge bg-warning text-dark">Low Stock</span>
                                        @else
                                            <span class="badge bg-success">Available</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="7" class="text-end">Total:</th>
                                <th class="text-end">{{ number_format($stocks->sum('quantity'), 2) }}</th>
                                <th colspan="2"></th>
                                <th class="text-end">
                                    <strong>৳{{ number_format($stocks->sum(function($stock) { 
                                        return $stock->quantity * ($stock->item->cost_price ?? 0); 
                                    }), 2) }}</strong>
                                </th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="mt-3 text-muted">No stock data found for the selected filters.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Report Footer -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Generated Date:</strong> {{ date('F d, Y') }}</p>
                    <p class="mb-1"><strong>Generated Time:</strong> {{ date('h:i A') }}</p>
                    <p class="mb-0"><strong>Generated By:</strong> {{ auth()->user()->name ?? 'System' }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-1"><strong>Total Records:</strong> {{ $stocks->count() }}</p>
                    <p class="mb-1"><strong>Filters Applied:</strong> 
                        @if(request()->hasAny(['warehouse_id', 'category_id', 'stock_status']))
                            Yes
                        @else
                            None
                        @endif
                    </p>
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
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        color: #495057;
    }

    @media print {
        .no-print {
            display: none !important;
        }
        
        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }
        
        body {
            font-size: 12px;
        }
        
        .table {
            font-size: 11px;
        }
    }
</style>
@endpush
