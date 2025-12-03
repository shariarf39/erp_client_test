@extends('layouts.app')

@section('title', 'Create Purchase Order')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Create Purchase Order</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Purchase</li>
                    <li class="breadcrumb-item"><a href="{{ route('purchase.orders.index') }}">Purchase Orders</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('purchase.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <form action="{{ route('purchase.orders.store') }}" method="POST" id="poForm">
        @csrf
        
        <div class="row">
            <div class="col-md-8">
                <!-- Header Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-file-invoice"></i> PO Header Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="po_no" class="form-label">PO Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('po_no') is-invalid @enderror" 
                                    id="po_no" name="po_no" value="{{ old('po_no', $poNumber) }}" readonly>
                                @error('po_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">PO Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                    id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="vendor_id" class="form-label">Vendor <span class="text-danger">*</span></label>
                                <select class="form-select @error('vendor_id') is-invalid @enderror" 
                                    id="vendor_id" name="vendor_id" required>
                                    <option value="">Select Vendor</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->vendor_code }} - {{ $vendor->vendor_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="pr_id" class="form-label">Reference PR (Optional)</label>
                                <select class="form-select @error('pr_id') is-invalid @enderror" 
                                    id="pr_id" name="pr_id">
                                    <option value="">No Reference</option>
                                    @foreach($requisitions as $pr)
                                        <option value="{{ $pr->id }}" {{ old('pr_id') == $pr->id ? 'selected' : '' }}>
                                            {{ $pr->pr_no }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pr_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="delivery_date" class="form-label">Expected Delivery Date</label>
                                <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" 
                                    id="delivery_date" name="delivery_date" value="{{ old('delivery_date') }}">
                                @error('delivery_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="payment_terms" class="form-label">Payment Terms</label>
                                <select class="form-select @error('payment_terms') is-invalid @enderror" 
                                    id="payment_terms" name="payment_terms">
                                    <option value="Cash">Cash</option>
                                    <option value="Credit">Credit</option>
                                    <option value="30 Days" selected>30 Days</option>
                                    <option value="60 Days">60 Days</option>
                                    <option value="90 Days">90 Days</option>
                                </select>
                                @error('payment_terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Line Items -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-list"></i> Item Details</h5>
                            <button type="button" class="btn btn-sm btn-success" onclick="addRow()">
                                <i class="fas fa-plus"></i> Add Item
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" id="itemsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th width="35%">Item</th>
                                        <th width="10%">Quantity</th>
                                        <th width="15%">Unit Price</th>
                                        <th width="10%">Tax (%)</th>
                                        <th width="15%">Amount</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsTableBody">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Click "Add Item" to add items to this purchase order
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Panel -->
            <div class="col-md-4">
                <!-- Summary Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-calculator"></i> Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong id="subtotalDisplay">৳0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax Amount:</span>
                            <strong id="taxDisplay">৳0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount:</span>
                            <div class="input-group input-group-sm" style="max-width: 150px;">
                                <input type="number" class="form-control" id="discount_amount" 
                                    name="discount_amount" value="0" step="0.01" onchange="calculateTotal()">
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h5>Total Amount:</h5>
                            <h5 class="text-success" id="totalDisplay">৳0.00</h5>
                        </div>
                        
                        <input type="hidden" name="subtotal" id="subtotal" value="0">
                        <input type="hidden" name="tax_amount" id="tax_amount" value="0">
                        <input type="hidden" name="total_amount" id="total_amount" value="0">
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Additional Info</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="delivery_address" class="form-label">Delivery Address</label>
                            <textarea class="form-control" id="delivery_address" name="delivery_address" 
                                rows="3">{{ old('delivery_address') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="remarks" name="remarks" 
                                rows="3">{{ old('remarks') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="Draft">Draft</option>
                                <option value="Pending" selected>Pending</option>
                                <option value="Approved">Approved</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-save"></i> Create Purchase Order
                        </button>
                        <a href="{{ route('purchase.orders.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let rowCount = 0;
const items = @json($items);

function addRow() {
    rowCount++;
    const tbody = document.getElementById('itemsTableBody');
    
    // Remove "no items" message if present
    if (tbody.children.length === 1 && tbody.children[0].cells.length === 1) {
        tbody.innerHTML = '';
    }
    
    const row = tbody.insertRow();
    row.innerHTML = `
        <td>
            <select class="form-select form-select-sm" name="items[${rowCount}][item_id]" required onchange="updatePrice(${rowCount})">
                <option value="">Select Item</option>
                ${items.map(item => `
                    <option value="${item.id}" 
                        data-price="${item.cost_price}" 
                        data-tax="${item.tax_rate}"
                        data-unit="${item.unit ? item.unit.unit_name : ''}">
                        ${item.item_code} - ${item.item_name} (${item.category ? item.category.category_name : 'N/A'})
                    </option>
                `).join('')}
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" name="items[${rowCount}][quantity]" 
                value="1" min="0.01" step="0.01" required onchange="calculateRow(${rowCount})">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" name="items[${rowCount}][unit_price]" 
                value="0" min="0" step="0.01" required onchange="calculateRow(${rowCount})">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" name="items[${rowCount}][tax_rate]" 
                value="0" min="0" step="0.01" onchange="calculateRow(${rowCount})">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="items[${rowCount}][amount]" 
                value="0.00" readonly>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
}

function updatePrice(rowNum) {
    const row = event.target.closest('tr');
    const select = row.querySelector('select');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        row.querySelector('input[name*="[unit_price]"]').value = option.dataset.price || 0;
        row.querySelector('input[name*="[tax_rate]"]').value = option.dataset.tax || 0;
        calculateRow(rowNum);
    }
}

function calculateRow(rowNum) {
    const row = event.target.closest('tr');
    const quantity = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
    const unitPrice = parseFloat(row.querySelector('input[name*="[unit_price]"]').value) || 0;
    const taxRate = parseFloat(row.querySelector('input[name*="[tax_rate]"]').value) || 0;
    
    const subtotal = quantity * unitPrice;
    const tax = (subtotal * taxRate) / 100;
    const amount = subtotal + tax;
    
    row.querySelector('input[name*="[amount]"]').value = amount.toFixed(2);
    
    calculateTotal();
}

function removeRow(button) {
    button.closest('tr').remove();
    
    const tbody = document.getElementById('itemsTableBody');
    if (tbody.children.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted py-4">
                    Click "Add Item" to add items to this purchase order
                </td>
            </tr>
        `;
    }
    
    calculateTotal();
}

function calculateTotal() {
    const tbody = document.getElementById('itemsTableBody');
    let subtotal = 0;
    let taxAmount = 0;
    
    Array.from(tbody.querySelectorAll('tr')).forEach(row => {
        const quantity = parseFloat(row.querySelector('input[name*="[quantity]"]')?.value) || 0;
        const unitPrice = parseFloat(row.querySelector('input[name*="[unit_price]"]')?.value) || 0;
        const taxRate = parseFloat(row.querySelector('input[name*="[tax_rate]"]')?.value) || 0;
        
        const rowSubtotal = quantity * unitPrice;
        const rowTax = (rowSubtotal * taxRate) / 100;
        
        subtotal += rowSubtotal;
        taxAmount += rowTax;
    });
    
    const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
    const total = subtotal + taxAmount - discount;
    
    document.getElementById('subtotalDisplay').textContent = '৳' + subtotal.toFixed(2);
    document.getElementById('taxDisplay').textContent = '৳' + taxAmount.toFixed(2);
    document.getElementById('totalDisplay').textContent = '৳' + total.toFixed(2);
    
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('tax_amount').value = taxAmount.toFixed(2);
    document.getElementById('total_amount').value = total.toFixed(2);
}

// Form validation
document.getElementById('poForm').addEventListener('submit', function(e) {
    const tbody = document.getElementById('itemsTableBody');
    if (tbody.children.length === 0 || (tbody.children.length === 1 && tbody.children[0].cells.length === 1)) {
        e.preventDefault();
        alert('Please add at least one item to the purchase order');
        return false;
    }
});
</script>
@endsection
