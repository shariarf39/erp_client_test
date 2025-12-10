@extends('layouts.app')

@section('title', 'Edit Delivery Note')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-truck"></i> Edit Delivery Note - {{ $deliveryNote->so_no }}</h2>
                <a href="{{ route('sales.delivery-notes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('sales.delivery-notes.update', $deliveryNote->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Order No.</label>
                            <input type="text" class="form-control" value="{{ $deliveryNote->so_no }}" readonly>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Customer</label>
                            <input type="text" class="form-control" value="{{ $deliveryNote->customer->name }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="delivery_date" class="form-label">Delivery Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" 
                                   id="delivery_date" name="delivery_date" 
                                   value="{{ old('delivery_date', $deliveryNote->delivery_date) }}" required>
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Delivery Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="Ready" {{ old('status', $deliveryNote->status) == 'Approved' ? 'selected' : '' }}>Ready for Delivery</option>
                                <option value="In Transit" {{ old('status', $deliveryNote->status) == 'Processing' ? 'selected' : '' }}>In Transit</option>
                                <option value="Partial" {{ old('status', $deliveryNote->status) == 'Partial' ? 'selected' : '' }}>Partial Delivery</option>
                                <option value="Delivered" {{ old('status', $deliveryNote->status) == 'Completed' ? 'selected' : '' }}>Delivered</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Delivery Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $deliveryNote->notes) }}</textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Delivery Note
                    </button>
                    <a href="{{ route('sales.delivery-notes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
