@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-file-invoice-dollar"></i> Create Invoice</h2>
                <a href="{{ route('sales.invoices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('sales.invoices.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="invoice_no" class="form-label">Invoice No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('invoice_no') is-invalid @enderror" 
                                   id="invoice_no" name="invoice_no" value="{{ old('invoice_no', $invoiceNo) }}" readonly>
                            @error('invoice_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="date" class="form-label">Invoice Date <span class="text-danger">*</span></label>
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
                            <label for="so_id" class="form-label">Sales Order</label>
                            <select class="form-select @error('so_id') is-invalid @enderror" 
                                    id="so_id" name="so_id">
                                <option value="">Select Sales Order (Optional)</option>
                                @foreach($salesOrders as $order)
                                    <option value="{{ $order->id }}" {{ old('so_id') == $order->id ? 'selected' : '' }}>
                                        {{ $order->so_no }} - {{ $order->customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('so_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                   id="due_date" name="due_date" value="{{ old('due_date') }}">
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Partial" {{ old('status') == 'Partial' ? 'selected' : '' }}>Partial</option>
                                <option value="Paid" {{ old('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="subtotal" class="form-label">Subtotal</label>
                            <input type="number" class="form-control" id="subtotal" name="subtotal" 
                                   value="{{ old('subtotal', 0) }}" step="0.01" min="0">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="tax_amount" class="form-label">Tax Amount</label>
                            <input type="number" class="form-control" id="tax_amount" name="tax_amount" 
                                   value="{{ old('tax_amount', 0) }}" step="0.01" min="0">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="discount_amount" class="form-label">Discount</label>
                            <input type="number" class="form-control" id="discount_amount" name="discount_amount" 
                                   value="{{ old('discount_amount', 0) }}" step="0.01" min="0">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="total_amount" class="form-label">Total Amount</label>
                            <input type="number" class="form-control" id="total_amount" name="total_amount" 
                                   value="{{ old('total_amount', 0) }}" step="0.01" min="0" readonly>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="paid_amount" class="form-label">Paid Amount</label>
                            <input type="number" class="form-control" id="paid_amount" name="paid_amount" 
                                   value="{{ old('paid_amount', 0) }}" step="0.01" min="0">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="due_amount" class="form-label">Due Amount</label>
                            <input type="number" class="form-control" id="due_amount" name="due_amount" 
                                   value="{{ old('due_amount', 0) }}" step="0.01" min="0" readonly>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Invoice
                    </button>
                    <a href="{{ route('sales.invoices.index') }}" class="btn btn-secondary">
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
    function calculateTotal() {
        const subtotal = parseFloat($('#subtotal').val()) || 0;
        const tax = parseFloat($('#tax_amount').val()) || 0;
        const discount = parseFloat($('#discount_amount').val()) || 0;
        const total = subtotal + tax - discount;
        $('#total_amount').val(total.toFixed(2));
        
        const paid = parseFloat($('#paid_amount').val()) || 0;
        const due = total - paid;
        $('#due_amount').val(due.toFixed(2));
    }

    $('#subtotal, #tax_amount, #discount_amount, #paid_amount').on('input', calculateTotal);
});
</script>
@endpush
@endsection
