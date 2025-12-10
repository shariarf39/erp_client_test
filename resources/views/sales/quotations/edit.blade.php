@extends('layouts.app')

@section('title', 'Edit Quotation')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-file-alt"></i> Edit Quotation - {{ $quotation->quotation_no }}</h2>
                <a href="{{ route('sales.quotations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('sales.quotations.update', $quotation->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="quotation_no" class="form-label">Quotation No.</label>
                            <input type="text" class="form-control" value="{{ $quotation->quotation_no }}" readonly>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                   id="date" name="date" value="{{ old('date', $quotation->date) }}" required>
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
                                    <option value="{{ $customer->id }}" 
                                        {{ old('customer_id', $quotation->customer_id) == $customer->id ? 'selected' : '' }}>
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
                                   id="validity_date" name="validity_date" 
                                   value="{{ old('validity_date', $quotation->validity_date) }}">
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
                                <option value="Cash" {{ old('payment_terms', $quotation->payment_terms) == 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="Net 15" {{ old('payment_terms', $quotation->payment_terms) == 'Net 15' ? 'selected' : '' }}>Net 15 Days</option>
                                <option value="Net 30" {{ old('payment_terms', $quotation->payment_terms) == 'Net 30' ? 'selected' : '' }}>Net 30 Days</option>
                                <option value="Net 45" {{ old('payment_terms', $quotation->payment_terms) == 'Net 45' ? 'selected' : '' }}>Net 45 Days</option>
                                <option value="Net 60" {{ old('payment_terms', $quotation->payment_terms) == 'Net 60' ? 'selected' : '' }}>Net 60 Days</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="Draft" {{ old('status', $quotation->status) == 'Draft' ? 'selected' : '' }}>Draft</option>
                                <option value="Sent" {{ old('status', $quotation->status) == 'Sent' ? 'selected' : '' }}>Sent</option>
                                <option value="Accepted" {{ old('status', $quotation->status) == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="Rejected" {{ old('status', $quotation->status) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="Expired" {{ old('status', $quotation->status) == 'Expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="delivery_terms" class="form-label">Delivery Terms</label>
                    <textarea class="form-control" id="delivery_terms" name="delivery_terms" rows="2">{{ old('delivery_terms', $quotation->delivery_terms) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $quotation->notes) }}</textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Quotation
                    </button>
                    <a href="{{ route('sales.quotations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
