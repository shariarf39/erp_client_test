@extends('layouts.app')

@section('title', 'Pricing & Discounts - SENA.ERP')
@section('page_title', 'Pricing & Discounts')
@section('page_description', 'Manage product pricing and discount rules')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Pricing & Discounts</h5>
                    <a href="{{ route('sales.pricing.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Create Pricing Rule
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('sales.pricing.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search by item name or code..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="category_id" class="form-select">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Pricing Table -->
                    @if($items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>Cost Price</th>
                                        <th>Selling Price</th>
                                        <th>Margin</th>
                                        <th>Discount</th>
                                        <th>Final Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                    <tr>
                                        <td><strong>{{ $item->item_code }}</strong></td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            @if($item->category)
                                                <span class="badge bg-light text-dark">{{ $item->category->name }}</span>
                                            @endif
                                        </td>
                                        <td>₹ {{ number_format($item->cost_price ?? 0, 2) }}</td>
                                        <td><strong>₹ {{ number_format($item->selling_price ?? 0, 2) }}</strong></td>
                                        <td>
                                            @php
                                                $margin = 0;
                                                if ($item->cost_price && $item->selling_price) {
                                                    $margin = (($item->selling_price - $item->cost_price) / $item->cost_price) * 100;
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $margin > 20 ? 'success' : ($margin > 10 ? 'warning' : 'danger') }}">
                                                {{ number_format($margin, 1) }}%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-success">0%</span>
                                        </td>
                                        <td>
                                            <strong class="text-primary">₹ {{ number_format($item->selling_price ?? 0, 2) }}</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $items->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No items found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="text-muted">Total Items</h6>
                    <h3 class="mb-0">{{ \App\Models\Item::where('is_active', 1)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <h6 class="text-muted">Avg Margin</h6>
                    <h3 class="mb-0 text-success">15.2%</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <h6 class="text-muted">Active Discounts</h6>
                    <h3 class="mb-0 text-info">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <h6 class="text-muted">Price Rules</h6>
                    <h3 class="mb-0 text-warning">0</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
