@extends('layouts.app')

@section('title', 'Adjust Stock')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-pencil-square me-2"></i>Adjust Stock
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory.stock.index') }}">Stock Management</a></li>
                    <li class="breadcrumb-item active">Adjust Stock</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('inventory.stock.show', $stock) }}" class="btn btn-info">
                <i class="bi bi-eye me-2"></i>View Details
            </a>
            <a href="{{ route('inventory.stock.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
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

    <div class="row g-4">
        <!-- Adjustment Form -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-arrow-left-right me-2"></i>Stock Adjustment
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('inventory.stock.update', $stock) }}" method="POST" id="adjustmentForm">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Current Information (Read-only) -->
                            <div class="col-12">
                                <div class="alert alert-info" role="alert">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Item:</strong> {{ $stock->item->code }} - {{ $stock->item->name }}<br>
                                            <strong>Warehouse:</strong> {{ $stock->warehouse->name }}
                                        </div>
                                        <div class="col-md-6 text-md-end">
                                            <strong>Current Stock:</strong> 
                                            <span class="fs-5 text-primary">{{ number_format($stock->quantity, 2) }}</span>
                                            {{ $stock->unit->name ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Adjustment Type -->
                            <div class="col-md-12">
                                <label class="form-label">Adjustment Type <span class="text-danger">*</span></label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="adjustment_type" id="add" value="add" 
                                           {{ old('adjustment_type', 'add') == 'add' ? 'checked' : '' }} required>
                                    <label class="btn btn-outline-success" for="add">
                                        <i class="bi bi-plus-circle me-2"></i>Add to Stock (IN)
                                    </label>

                                    <input type="radio" class="btn-check" name="adjustment_type" id="subtract" value="subtract"
                                           {{ old('adjustment_type') == 'subtract' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-danger" for="subtract">
                                        <i class="bi bi-dash-circle me-2"></i>Subtract from Stock (OUT)
                                    </label>

                                    <input type="radio" class="btn-check" name="adjustment_type" id="set" value="set"
                                           {{ old('adjustment_type') == 'set' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-warning" for="set">
                                        <i class="bi bi-arrow-repeat me-2"></i>Set Exact Quantity
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <span id="typeHelp">Select how you want to adjust the stock quantity</span>
                                </small>
                            </div>

                            <!-- Adjustment Quantity -->
                            <div class="col-md-6">
                                <label for="adjustment_quantity" class="form-label">
                                    <span id="quantityLabel">Quantity to Add</span> <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control form-control-lg @error('adjustment_quantity') is-invalid @enderror" 
                                       id="adjustment_quantity" 
                                       name="adjustment_quantity" 
                                       step="0.01"
                                       min="0"
                                       value="{{ old('adjustment_quantity') }}"
                                       required>
                                @error('adjustment_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Enter the adjustment quantity</small>
                            </div>

                            <!-- New Stock Preview -->
                            <div class="col-md-6">
                                <label class="form-label">New Stock Quantity</label>
                                <div class="form-control form-control-lg bg-light" id="newQuantityPreview">
                                    <span class="text-muted">Enter quantity to calculate</span>
                                </div>
                                <small class="text-muted">Calculated new stock level</small>
                            </div>

                            <div class="col-12">
                                <hr>
                            </div>

                            <!-- Stock Levels (Optional Updates) -->
                            <div class="col-md-6">
                                <label for="reorder_level" class="form-label">Reorder Level</label>
                                <input type="number" 
                                       class="form-control @error('reorder_level') is-invalid @enderror" 
                                       id="reorder_level" 
                                       name="reorder_level" 
                                       step="0.01"
                                       min="0"
                                       value="{{ old('reorder_level', $stock->reorder_level) }}"
                                       placeholder="Minimum stock level">
                                @error('reorder_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Update reorder level (optional)</small>
                            </div>

                            <div class="col-md-6">
                                <label for="max_level" class="form-label">Maximum Level</label>
                                <input type="number" 
                                       class="form-control @error('max_level') is-invalid @enderror" 
                                       id="max_level" 
                                       name="max_level" 
                                       step="0.01"
                                       min="0"
                                       value="{{ old('max_level', $stock->max_level) }}"
                                       placeholder="Maximum stock level">
                                @error('max_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Update maximum level (optional)</small>
                            </div>

                            <!-- Remarks -->
                            <div class="col-md-12">
                                <label for="remarks" class="form-label">Adjustment Reason / Remarks</label>
                                <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                          id="remarks" 
                                          name="remarks" 
                                          rows="3"
                                          placeholder="Enter reason for stock adjustment (e.g., damaged goods, returns, physical count)">{{ old('remarks', $stock->remarks) }}</textarea>
                                @error('remarks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Document why this adjustment is being made</small>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('inventory.stock.show', $stock) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Save Adjustment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Information Sidebar -->
        <div class="col-lg-4">
            <!-- Current Stock Info -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Current Stock Info
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="text-muted small">Current Quantity</div>
                        <div class="h4 text-primary">{{ number_format($stock->quantity, 2) }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Reorder Level</div>
                        <div class="h5">{{ $stock->reorder_level ? number_format($stock->reorder_level, 2) : 'Not Set' }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Maximum Level</div>
                        <div class="h5">{{ $stock->max_level ? number_format($stock->max_level, 2) : 'Not Set' }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Last Transaction</div>
                        <div>
                            @if($stock->last_transaction_date)
                                {{ \Carbon\Carbon::parse($stock->last_transaction_date)->format('d M Y') }}
                                @if($stock->last_transaction_type == 'IN')
                                    <span class="badge bg-success">IN</span>
                                @else
                                    <span class="badge bg-danger">OUT</span>
                                @endif
                            @else
                                <span class="text-muted">No transactions</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Adjustment Tips -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-lightbulb me-2"></i>Adjustment Tips
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0 ps-3">
                        <li class="mb-2"><strong>Add to Stock:</strong> Use when receiving new inventory or returns</li>
                        <li class="mb-2"><strong>Subtract from Stock:</strong> Use for damages, theft, or consumption</li>
                        <li class="mb-2"><strong>Set Exact Quantity:</strong> Use after physical stock count</li>
                        <li class="mb-2"><strong>Always document:</strong> Provide a clear reason in remarks</li>
                        <li><strong>Cannot subtract:</strong> More than available stock</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentStock = {{ $stock->quantity }};
    const adjustmentType = document.querySelectorAll('input[name="adjustment_type"]');
    const adjustmentQuantity = document.getElementById('adjustment_quantity');
    const newQuantityPreview = document.getElementById('newQuantityPreview');
    const quantityLabel = document.getElementById('quantityLabel');
    const typeHelp = document.getElementById('typeHelp');

    function updateLabelsAndPreview() {
        const selectedType = document.querySelector('input[name="adjustment_type"]:checked').value;
        const quantity = parseFloat(adjustmentQuantity.value) || 0;
        let newQuantity = currentStock;

        switch(selectedType) {
            case 'add':
                quantityLabel.textContent = 'Quantity to Add';
                typeHelp.textContent = 'Add this quantity to current stock';
                newQuantity = currentStock + quantity;
                newQuantityPreview.innerHTML = `<span class="text-success fw-bold">${newQuantity.toFixed(2)}</span>`;
                break;
            case 'subtract':
                quantityLabel.textContent = 'Quantity to Subtract';
                typeHelp.textContent = 'Subtract this quantity from current stock';
                newQuantity = currentStock - quantity;
                if (newQuantity < 0) {
                    newQuantityPreview.innerHTML = `<span class="text-danger fw-bold">${newQuantity.toFixed(2)} (Invalid - Cannot be negative)</span>`;
                } else {
                    newQuantityPreview.innerHTML = `<span class="text-warning fw-bold">${newQuantity.toFixed(2)}</span>`;
                }
                break;
            case 'set':
                quantityLabel.textContent = 'New Exact Quantity';
                typeHelp.textContent = 'Set stock to this exact quantity';
                newQuantity = quantity;
                newQuantityPreview.innerHTML = `<span class="text-primary fw-bold">${newQuantity.toFixed(2)}</span>`;
                break;
        }
    }

    adjustmentType.forEach(radio => {
        radio.addEventListener('change', updateLabelsAndPreview);
    });

    adjustmentQuantity.addEventListener('input', updateLabelsAndPreview);

    // Initial update
    updateLabelsAndPreview();
});
</script>
@endsection
