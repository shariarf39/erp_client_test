@extends('layouts.app')

@section('page_title', 'Items')
@section('page_description', 'Manage inventory items')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-boxes me-2"></i> Item List
        </div>
        <a href="{{ route('inventory.items.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add New Item
        </a>
    </div>
    <div class="card-body">
        @if($items->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Unit</th>
                            <th>Purchase Price</th>
                            <th>Sale Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->item_code }}</td>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->category->category_name ?? 'N/A' }}</td>
                                <td>{{ $item->unit->unit_name ?? 'N/A' }}</td>
                                <td>৳{{ number_format($item->purchase_price ?? 0, 2) }}</td>
                                <td>৳{{ number_format($item->sale_price ?? 0, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->is_active ? 'success' : 'danger' }}">
                                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $items->links() }}
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i> No items found. Click "Add New Item" to create one.
            </div>
        @endif
    </div>
</div>
@endsection
