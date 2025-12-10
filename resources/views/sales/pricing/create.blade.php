@extends('layouts.app')

@section('title', 'Create Pricing Rule')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-tags"></i> Create Pricing Rule</h2>
                <a href="{{ route('sales.pricing.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('sales.pricing.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="item_id" class="form-label">Product/Item <span class="text-danger">*</span></label>
                            <select class="form-select @error('item_id') is-invalid @enderror" 
                                    id="item_id" name="item_id" required>
                                <option value="">Select Product</option>
                            </select>
                            @error('item_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer (Optional)</label>
                            <select class="form-select" id="customer_id" name="customer_id">
                                <option value="">All Customers</option>
                            </select>
                            <small class="text-muted">Leave empty for general pricing</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="cost_price" class="form-label">Cost Price</label>
                            <input type="number" class="form-control @error('cost_price') is-invalid @enderror" 
                                   id="cost_price" name="cost_price" value="{{ old('cost_price') }}" 
                                   step="0.01" min="0">
                            @error('cost_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="selling_price" class="form-label">Selling Price <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('selling_price') is-invalid @enderror" 
                                   id="selling_price" name="selling_price" value="{{ old('selling_price') }}" 
                                   step="0.01" min="0" required>
                            @error('selling_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="discount_type" class="form-label">Discount Type</label>
                            <select class="form-select" id="discount_type" name="discount_type">
                                <option value="">No Discount</option>
                                <option value="Percentage">Percentage</option>
                                <option value="Fixed">Fixed Amount</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="discount_value" class="form-label">Discount Value</label>
                            <input type="number" class="form-control" id="discount_value" name="discount_value" 
                                   value="{{ old('discount_value', 0) }}" step="0.01" min="0">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="min_quantity" class="form-label">Minimum Quantity</label>
                            <input type="number" class="form-control" id="min_quantity" name="min_quantity" 
                                   value="{{ old('min_quantity', 1) }}" min="1">
                            <small class="text-muted">For bulk pricing</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="valid_from" class="form-label">Valid From</label>
                            <input type="date" class="form-control" id="valid_from" name="valid_from" 
                                   value="{{ old('valid_from', date('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="valid_to" class="form-label">Valid To</label>
                            <input type="date" class="form-control" id="valid_to" name="valid_to" 
                                   value="{{ old('valid_to') }}">
                            <small class="text-muted">Leave empty for no expiry</small>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                    <label class="form-check-label" for="is_active">
                        Active
                    </label>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Pricing Rule
                    </button>
                    <a href="{{ route('sales.pricing.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#cost_price, #selling_price').on('input', function() {
        const cost = parseFloat($('#cost_price').val()) || 0;
        const selling = parseFloat($('#selling_price').val()) || 0;
        
        if (cost > 0 && selling > 0) {
            const margin = ((selling - cost) / selling * 100).toFixed(2);
            console.log('Margin: ' + margin + '%');
        }
    });
});
</script>
@endpush
@endsection
