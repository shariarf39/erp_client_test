@extends('layouts.app')

@section('title', 'Add New Stock')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-box-seam me-2"></i>Add New Stock
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory.stock.index') }}">Stock Management</a></li>
                    <li class="breadcrumb-item active">Add Stock</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('inventory.stock.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Stock
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validation Errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient text-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-plus-circle me-2"></i>Stock Information
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('inventory.stock.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <!-- Item Selection -->
                    <div class="col-md-6">
                        <label for="item_id" class="form-label">Item <span class="text-danger">*</span></label>
                        <select class="form-select @error('item_id') is-invalid @enderror" 
                                id="item_id" 
                                name="item_id" 
                                required>
                            <option value="">Select Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->code }} - {{ $item->name }}
                                    @if($item->category)
                                        ({{ $item->category->name }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Select the item to add to stock</small>
                    </div>

                    <!-- Warehouse Selection -->
                    <div class="col-md-6">
                        <label for="warehouse_id" class="form-label">Warehouse <span class="text-danger">*</span></label>
                        <select class="form-select @error('warehouse_id') is-invalid @enderror" 
                                id="warehouse_id" 
                                name="warehouse_id" 
                                required>
                            <option value="">Select Warehouse</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }}
                                    @if($warehouse->location)
                                        - {{ $warehouse->location }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('warehouse_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Select the warehouse location</small>
                    </div>

                    <!-- Initial Quantity -->
                    <div class="col-md-6">
                        <label for="quantity" class="form-label">Initial Quantity <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('quantity') is-invalid @enderror" 
                               id="quantity" 
                               name="quantity" 
                               step="0.01"
                               min="0"
                               value="{{ old('quantity', 0) }}"
                               required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Enter the initial stock quantity</small>
                    </div>

                    <!-- Reorder Level -->
                    <div class="col-md-6">
                        <label for="reorder_level" class="form-label">Reorder Level</label>
                        <input type="number" 
                               class="form-control @error('reorder_level') is-invalid @enderror" 
                               id="reorder_level" 
                               name="reorder_level" 
                               step="0.01"
                               min="0"
                               value="{{ old('reorder_level') }}"
                               placeholder="Minimum stock level">
                        @error('reorder_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Alert when stock reaches this level</small>
                    </div>

                    <!-- Maximum Level -->
                    <div class="col-md-6">
                        <label for="max_level" class="form-label">Maximum Level</label>
                        <input type="number" 
                               class="form-control @error('max_level') is-invalid @enderror" 
                               id="max_level" 
                               name="max_level" 
                               step="0.01"
                               min="0"
                               value="{{ old('max_level') }}"
                               placeholder="Maximum stock level">
                        @error('max_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Alert when stock exceeds this level</small>
                    </div>

                    <!-- Remarks -->
                    <div class="col-md-12">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                  id="remarks" 
                                  name="remarks" 
                                  rows="3"
                                  placeholder="Enter any additional notes or comments">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Optional notes about this stock entry</small>
                    </div>
                </div>

                <!-- Information Alert -->
                <div class="alert alert-info mt-4" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Note:</strong> A stock record must be unique for each item-warehouse combination. 
                    If a record already exists, please use the edit function to adjust quantities.
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('inventory.stock.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Create Stock Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection
