@extends('layouts.app')

@section('title', 'Edit Sales Order')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-pencil me-2"></i>Edit Sales Order
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.orders.index') }}">Sales Orders</a></li>
                    <li class="breadcrumb-item active">Edit: {{ $order->so_no }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('sales.orders.show', $order) }}" class="btn btn-info">
                <i class="bi bi-eye me-2"></i>View Details
            </a>
            <a href="{{ route('sales.orders.index') }}" class="btn btn-secondary">
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

    <form action="{{ route('sales.orders.update', $order) }}" method="POST" id="soForm">
        @csrf
        @method('PUT')
        
        <div class="row g-4">
            <!-- Main Form -->
            <div class="col-lg-8">
                <!-- Header Information -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-gradient text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-file-earmark-text me-2"></i>Order Information
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="so_no" class="form-label">SO Number <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('so_no') is-invalid @enderror" 
                                       id="so_no" 
                                       name="so_no" 
                                       value="{{ old('so_no', $order->so_no) }}"
                                       required>
                                @error('so_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="date" class="form-label">SO Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('date') is-invalid @enderror" 
                                       id="date" 
                                       name="date" 
                                       value="{{ old('date', $order->date->format('Y-m-d')) }}"
                                       required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                                <select class="form-select @error('customer_id') is-invalid @enderror" 
                                        id="customer_id" 
                                        name="customer_id" 
                                        required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" 
                                                {{ old('customer_id', $order->customer_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->customer_code }} - {{ $customer->customer_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="quotation_id" class="form-label">Reference Quotation (Optional)</label>
                                <select class="form-select @error('quotation_id') is-invalid @enderror" 
                                        id="quotation_id" 
                                        name="quotation_id">
                                    <option value="">No Reference</option>
                                    @foreach($quotations as $quotation)
                                        <option value="{{ $quotation->id }}" 
                                                {{ old('quotation_id', $order->quotation_id) == $quotation->id ? 'selected' : '' }}>
                                            {{ $quotation->quotation_number }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('quotation_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="delivery_date" class="form-label">Expected Delivery Date</label>
                                <input type="date" 
                                       class="form-control @error('delivery_date') is-invalid @enderror" 
                                       id="delivery_date" 
                                       name="delivery_date" 
                                       value="{{ old('delivery_date', $order->delivery_date?->format('Y-m-d')) }}">
                                @error('delivery_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="payment_terms" class="form-label">Payment Terms</label>
                                <textarea class="form-control @error('payment_terms') is-invalid @enderror" 
                                          id="payment_terms" 
                                          name="payment_terms" 
                                          rows="2"
                                          placeholder="e.g., 50% advance, 50% on delivery">{{ old('payment_terms', $order->payment_terms) }}</textarea>
                                @error('payment_terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                          id="remarks" 
                                          name="remarks" 
                                          rows="2">{{ old('remarks', $order->remarks) }}</textarea>
                                @error('remarks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-box-seam me-2"></i>Order Items
                            </h5>
                            <button type="button" class="btn btn-sm btn-light" onclick="addItemRow()">
                                <i class="bi bi-plus-circle me-1"></i>Add Item
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0" id="itemsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th width="30%">Item</th>
                                        <th width="12%">Quantity</th>
                                        <th width="14%">Unit Price</th>
                                        <th width="10%">Tax %</th>
                                        <th width="12%">Discount</th>
                                        <th width="14%">Total</th>
                                        <th width="8%">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsTableBody">
                                    @foreach(old('items', $order->details->map(function($detail) {
                                        return [
                                            'item_id' => $detail->item_id,
                                            'quantity' => $detail->quantity,
                                            'unit_price' => $detail->unit_price,
                                            'tax_rate' => $detail->tax_rate,
                                            'discount' => $detail->discount,
                                        ];
                                    })->toArray()) as $index => $item)
                                        <tr class="item-row">
                                            <td>
                                                <select class="form-select form-select-sm item-select" 
                                                        name="items[{{ $index }}][item_id]" 
                                                        required>
                                                    <option value="">Select Item</option>
                                                    @foreach($items as $itemOption)
                                                        <option value="{{ $itemOption->id }}"
                                                                data-price="{{ $itemOption->selling_price }}"
                                                                {{ $item['item_id'] == $itemOption->id ? 'selected' : '' }}>
                                                            {{ $itemOption->code }} - {{ $itemOption->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       class="form-control form-control-sm item-quantity" 
                                                       name="items[{{ $index }}][quantity]" 
                                                       step="0.01" 
                                                       min="0.01"
                                                       value="{{ $item['quantity'] }}"
                                                       required>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       class="form-control form-control-sm item-price" 
                                                       name="items[{{ $index }}][unit_price]" 
                                                       step="0.01" 
                                                       min="0"
                                                       value="{{ $item['unit_price'] }}"
                                                       required>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       class="form-control form-control-sm item-tax" 
                                                       name="items[{{ $index }}][tax_rate]" 
                                                       step="0.01" 
                                                       min="0"
                                                       value="{{ $item['tax_rate'] ?? 0 }}">
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       class="form-control form-control-sm item-discount" 
                                                       name="items[{{ $index }}][discount]" 
                                                       step="0.01" 
                                                       min="0"
                                                       value="{{ $item['discount'] ?? 0 }}">
                                            </td>
                                            <td>
                                                <input type="text" 
                                                       class="form-control form-control-sm item-total" 
                                                       readonly>
                                            </td>
                                            <td>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        onclick="removeItemRow(this)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="5" class="text-end"><strong>Grand Total:</strong></td>
                                        <td colspan="2">
                                            <input type="text" 
                                                   class="form-control form-control-sm" 
                                                   id="grandTotal" 
                                                   readonly>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('sales.orders.show', $order) }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Update Sales Order
                    </button>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Instructions
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Fill in all required fields marked with <span class="text-danger">*</span>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Add at least one item to the order
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Item totals are calculated automatically
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Only Draft orders can be edited
                            </li>
                            <li class="mb-0">
                                <i class="bi bi-info-circle text-info me-2"></i>
                                Changes will be saved to existing order
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>

<script>
let itemIndex = {{ count(old('items', $order->details->toArray())) }};

// Add new item row
function addItemRow() {
    const tbody = document.getElementById('itemsTableBody');
    const row = document.createElement('tr');
    row.className = 'item-row';
    row.innerHTML = `
        <td>
            <select class="form-select form-select-sm item-select" name="items[${itemIndex}][item_id]" required>
                <option value="">Select Item</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}" data-price="{{ $item->selling_price }}">
                        {{ $item->code }} - {{ $item->name }}
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm item-quantity" 
                   name="items[${itemIndex}][quantity]" step="0.01" min="0.01" required>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm item-price" 
                   name="items[${itemIndex}][unit_price]" step="0.01" min="0" required>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm item-tax" 
                   name="items[${itemIndex}][tax_rate]" step="0.01" min="0" value="0">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm item-discount" 
                   name="items[${itemIndex}][discount]" step="0.01" min="0" value="0">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm item-total" readonly>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeItemRow(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
    itemIndex++;
    attachEventListeners(row);
}

// Remove item row
function removeItemRow(button) {
    const row = button.closest('tr');
    row.remove();
    calculateGrandTotal();
}

// Calculate item total
function calculateItemTotal(row) {
    const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
    const price = parseFloat(row.querySelector('.item-price').value) || 0;
    const taxRate = parseFloat(row.querySelector('.item-tax').value) || 0;
    const discount = parseFloat(row.querySelector('.item-discount').value) || 0;
    
    const subtotal = quantity * price;
    const tax = subtotal * (taxRate / 100);
    const total = subtotal + tax - discount;
    
    row.querySelector('.item-total').value = '৳' + total.toFixed(2);
    calculateGrandTotal();
}

// Calculate grand total
function calculateGrandTotal() {
    let grandTotal = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const totalText = row.querySelector('.item-total').value.replace('৳', '');
        grandTotal += parseFloat(totalText) || 0;
    });
    document.getElementById('grandTotal').value = '৳' + grandTotal.toFixed(2);
}

// Attach event listeners to a row
function attachEventListeners(row) {
    const select = row.querySelector('.item-select');
    const quantity = row.querySelector('.item-quantity');
    const price = row.querySelector('.item-price');
    const tax = row.querySelector('.item-tax');
    const discount = row.querySelector('.item-discount');
    
    select.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const itemPrice = selectedOption.getAttribute('data-price');
        if (itemPrice) {
            price.value = itemPrice;
            calculateItemTotal(row);
        }
    });
    
    [quantity, price, tax, discount].forEach(input => {
        input.addEventListener('input', () => calculateItemTotal(row));
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.item-row').forEach(row => {
        attachEventListeners(row);
        calculateItemTotal(row);
    });
});
</script>
@endsection
