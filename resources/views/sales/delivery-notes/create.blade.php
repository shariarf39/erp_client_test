@extends('layouts.app')

@section('title', 'Create Delivery Note')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-truck"></i> Create Delivery Note</h2>
                <a href="{{ route('sales.delivery-notes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('sales.delivery-notes.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="delivery_note_no" class="form-label">Delivery Note No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('delivery_note_no') is-invalid @enderror" 
                                   id="delivery_note_no" name="delivery_note_no" value="{{ old('delivery_note_no') }}" required>
                            @error('delivery_note_no')
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
                            <label for="sales_order_id" class="form-label">Sales Order <span class="text-danger">*</span></label>
                            <select class="form-select @error('sales_order_id') is-invalid @enderror" 
                                    id="sales_order_id" name="sales_order_id" required>
                                <option value="">Select Sales Order</option>
                            </select>
                            @error('sales_order_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="delivery_date" class="form-label">Delivery Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" 
                                   id="delivery_date" name="delivery_date" value="{{ old('delivery_date') }}" required>
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vehicle_no" class="form-label">Vehicle Number</label>
                            <input type="text" class="form-control" id="vehicle_no" name="vehicle_no" 
                                   value="{{ old('vehicle_no') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="driver_name" class="form-label">Driver Name</label>
                            <input type="text" class="form-control" id="driver_name" name="driver_name" 
                                   value="{{ old('driver_name') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="driver_phone" class="form-label">Driver Phone</label>
                            <input type="text" class="form-control" id="driver_phone" name="driver_phone" 
                                   value="{{ old('driver_phone') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="Ready">Ready for Delivery</option>
                                <option value="In Transit">In Transit</option>
                                <option value="Partial">Partial Delivery</option>
                                <option value="Delivered" selected>Delivered</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="delivery_address" class="form-label">Delivery Address <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('delivery_address') is-invalid @enderror" 
                              id="delivery_address" name="delivery_address" rows="3" required>{{ old('delivery_address') }}</textarea>
                    @error('delivery_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Delivery Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Delivery Note
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
