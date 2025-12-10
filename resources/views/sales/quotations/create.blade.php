@extends('layouts.app')

@section('title', 'Create Quotation')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-file-alt"></i> Create Quotation</h2>
                <a href="{{ route('sales.quotations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('sales.quotations.store') }}" method="POST" id="quotationForm">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="quotation_no" class="form-label">Quotation No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('quotation_no') is-invalid @enderror" 
                                   id="quotation_no" name="quotation_no" value="{{ old('quotation_no', $quotationNo) }}" readonly>
                            @error('quotation_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                   id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="form-select @error('customer_id') is-invalid @enderror" 
                                    id="customer_id" name="customer_id" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="validity_date" class="form-label">Valid Until</label>
                            <input type="date" class="form-control @error('validity_date') is-invalid @enderror" 
                                   id="validity_date" name="validity_date" value="{{ old('validity_date') }}">
                            @error('validity_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="payment_terms" class="form-label">Payment Terms</label>
                            <select class="form-select" id="payment_terms" name="payment_terms">
                                <option value="">Select Payment Terms</option>
                                <option value="Cash" {{ old('payment_terms') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="Net 15" {{ old('payment_terms') == 'Net 15' ? 'selected' : '' }}>Net 15 Days</option>
                                <option value="Net 30" {{ old('payment_terms') == 'Net 30' ? 'selected' : '' }}>Net 30 Days</option>
                                <option value="Net 45" {{ old('payment_terms') == 'Net 45' ? 'selected' : '' }}>Net 45 Days</option>
                                <option value="Net 60" {{ old('payment_terms') == 'Net 60' ? 'selected' : '' }}>Net 60 Days</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="Draft" {{ old('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                                <option value="Sent" {{ old('status') == 'Sent' ? 'selected' : '' }}>Sent</option>
                                <option value="Accepted" {{ old('status') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="Rejected" {{ old('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="Expired" {{ old('status') == 'Expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="delivery_terms" class="form-label">Delivery Terms</label>
                    <textarea class="form-control" id="delivery_terms" name="delivery_terms" rows="2">{{ old('delivery_terms') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                </div>

                <hr class="my-4">

                <h5>Items</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="itemsTable">
                        <thead>
                            <tr>
                                <th width="40%">Item</th>
                                <th width="15%">Quantity</th>
                                <th width="15%">Unit Price</th>
                                <th width="20%">Total</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select class="form-select item-select" name="items[0][item_id]">
                                        <option value="">Select Item</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" data-price="{{ $item->selling_price }}">
                                                {{ $item->name }} - {{ $item->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control quantity" name="items[0][quantity]" 
                                           min="1" step="0.01" value="1">
                                </td>
                                <td>
                                    <input type="number" class="form-control unit-price" name="items[0][unit_price]" 
                                           min="0" step="0.01">
                                </td>
                                <td>
                                    <input type="number" class="form-control total-price" name="items[0][total_price]" readonly>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <button type="button" class="btn btn-success btn-sm" id="addRow">
                    <i class="fas fa-plus"></i> Add Item
                </button>

                <div class="row mt-4">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <table class="table">
                            <tr>
                                <th>Subtotal:</th>
                                <td class="text-end" id="subtotal">0.00</td>
                            </tr>
                            <tr>
                                <th>Tax (0%):</th>
                                <td class="text-end" id="tax">0.00</td>
                            </tr>
                            <tr class="fw-bold">
                                <th>Total:</th>
                                <td class="text-end" id="grandTotal">0.00</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Quotation
                    </button>
                    <a href="{{ route('sales.quotations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let rowIndex = 1;

$(document).ready(function() {
    // Add row
    $('#addRow').click(function() {
        const newRow = `
            <tr>
                <td>
                    <select class="form-select item-select" name="items[${rowIndex}][item_id]">
                        <option value="">Select Item</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" data-price="{{ $item->selling_price }}">
                                {{ $item->name }} - {{ $item->code }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control quantity" name="items[${rowIndex}][quantity]" 
                           min="1" step="0.01" value="1">
                </td>
                <td>
                    <input type="number" class="form-control unit-price" name="items[${rowIndex}][unit_price]" 
                           min="0" step="0.01">
                </td>
                <td>
                    <input type="number" class="form-control total-price" name="items[${rowIndex}][total_price]" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#itemsTable tbody').append(newRow);
        rowIndex++;
    });

    // Remove row
    $(document).on('click', '.remove-row', function() {
        if ($('#itemsTable tbody tr').length > 1) {
            $(this).closest('tr').remove();
            calculateTotal();
        }
    });

    // Item select change
    $(document).on('change', '.item-select', function() {
        const price = $(this).find(':selected').data('price');
        const row = $(this).closest('tr');
        row.find('.unit-price').val(price);
        calculateRowTotal(row);
    });

    // Quantity/Price change
    $(document).on('input', '.quantity, .unit-price', function() {
        const row = $(this).closest('tr');
        calculateRowTotal(row);
    });

    function calculateRowTotal(row) {
        const quantity = parseFloat(row.find('.quantity').val()) || 0;
        const unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
        const total = quantity * unitPrice;
        row.find('.total-price').val(total.toFixed(2));
        calculateTotal();
    }

    function calculateTotal() {
        let subtotal = 0;
        $('.total-price').each(function() {
            subtotal += parseFloat($(this).val()) || 0;
        });
        const tax = 0; // Can be modified
        const grandTotal = subtotal + tax;
        
        $('#subtotal').text(subtotal.toFixed(2));
        $('#tax').text(tax.toFixed(2));
        $('#grandTotal').text(grandTotal.toFixed(2));
    }
});
</script>
@endpush
@endsection
