@extends('layouts.app')

@section('title', 'Add New Item')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Add New Item</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Inventory</li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory.items.index') }}">Items</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('inventory.items.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <form action="{{ route('inventory.items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <!-- Basic Information -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-info-circle text-primary"></i> Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="item_code" class="form-label">Item Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('item_code') is-invalid @enderror" 
                                    id="item_code" name="item_code" value="{{ old('item_code') }}" required>
                                @error('item_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="item_name" class="form-label">Item Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('item_name') is-invalid @enderror" 
                                    id="item_name" name="item_name" value="{{ old('item_name') }}" required>
                                @error('item_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="brand_id" class="form-label">Brand</label>
                                <select class="form-select @error('brand_id') is-invalid @enderror" 
                                    id="brand_id" name="brand_id">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->brand_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="unit_id" class="form-label">Unit <span class="text-danger">*</span></label>
                                <select class="form-select @error('unit_id') is-invalid @enderror" 
                                    id="unit_id" name="unit_id" required>
                                    <option value="">Select Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->unit_name }} ({{ $unit->unit_code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="item_type" class="form-label">Item Type</label>
                                <select class="form-select @error('item_type') is-invalid @enderror" 
                                    id="item_type" name="item_type">
                                    <option value="Raw Material" {{ old('item_type') == 'Raw Material' ? 'selected' : '' }}>Raw Material</option>
                                    <option value="Finished Goods" {{ old('item_type') == 'Finished Goods' ? 'selected' : '' }}>Finished Goods</option>
                                    <option value="Consumable" {{ old('item_type') == 'Consumable' ? 'selected' : '' }}>Consumable</option>
                                    <option value="Trading" {{ old('item_type') == 'Trading' ? 'selected' : '' }}>Trading</option>
                                </select>
                                @error('item_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-dollar-sign text-success"></i> Pricing Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cost_price" class="form-label">Cost Price</label>
                                <input type="number" step="0.01" class="form-control @error('cost_price') is-invalid @enderror" 
                                    id="cost_price" name="cost_price" value="{{ old('cost_price', 0) }}">
                                @error('cost_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="selling_price" class="form-label">Selling Price</label>
                                <input type="number" step="0.01" class="form-control @error('selling_price') is-invalid @enderror" 
                                    id="selling_price" name="selling_price" value="{{ old('selling_price', 0) }}">
                                @error('selling_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="mrp" class="form-label">MRP (Maximum Retail Price)</label>
                                <input type="number" step="0.01" class="form-control @error('mrp') is-invalid @enderror" 
                                    id="mrp" name="mrp" value="{{ old('mrp', 0) }}">
                                @error('mrp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                                <input type="number" step="0.01" class="form-control @error('tax_rate') is-invalid @enderror" 
                                    id="tax_rate" name="tax_rate" value="{{ old('tax_rate', 0) }}">
                                @error('tax_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stock Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-boxes text-warning"></i> Stock Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="reorder_level" class="form-label">Reorder Level</label>
                                <input type="number" step="0.01" class="form-control @error('reorder_level') is-invalid @enderror" 
                                    id="reorder_level" name="reorder_level" value="{{ old('reorder_level', 0) }}">
                                @error('reorder_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="min_stock" class="form-label">Minimum Stock</label>
                                <input type="number" step="0.01" class="form-control @error('min_stock') is-invalid @enderror" 
                                    id="min_stock" name="min_stock" value="{{ old('min_stock', 0) }}">
                                @error('min_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="max_stock" class="form-label">Maximum Stock</label>
                                <input type="number" step="0.01" class="form-control @error('max_stock') is-invalid @enderror" 
                                    id="max_stock" name="max_stock" value="{{ old('max_stock', 0) }}">
                                @error('max_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Panel -->
            <div class="col-md-4">
                <!-- Item Image -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-image text-info"></i> Item Image</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img id="imagePreview" src="{{ asset('images/no-image.png') }}" 
                                class="img-fluid rounded" style="max-height: 200px; object-fit: cover;" 
                                onerror="this.src='https://via.placeholder.com/200x200?text=No+Image'">
                        </div>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                            id="image" name="image" accept="image/*" onchange="previewImage(event)">
                        <small class="text-muted">Max size: 2MB. Format: JPG, PNG</small>
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-cog text-secondary"></i> Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="hs_code" class="form-label">HS Code</label>
                            <input type="text" class="form-control @error('hs_code') is-invalid @enderror" 
                                id="hs_code" name="hs_code" value="{{ old('hs_code') }}">
                            @error('hs_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="barcode" class="form-label">Barcode</label>
                            <input type="text" class="form-control @error('barcode') is-invalid @enderror" 
                                id="barcode" name="barcode" value="{{ old('barcode') }}">
                            @error('barcode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <strong>Active Status</strong>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-save"></i> Save Item
                        </button>
                        <a href="{{ route('inventory.items.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('imagePreview');
        preview.src = reader.result;
    }
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>
@endsection
