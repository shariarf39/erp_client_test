@extends('layouts.app')

@section('title', 'Create Sales Contract')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-file-contract"></i> Create Sales Contract</h2>
                <a href="{{ route('sales.contracts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('sales.contracts.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="contract_no" class="form-label">Contract No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('contract_no') is-invalid @enderror" 
                                   id="contract_no" name="contract_no" value="{{ old('contract_no') }}" required>
                            @error('contract_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="date" class="form-label">Contract Date <span class="text-danger">*</span></label>
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
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="contract_value" class="form-label">Contract Value <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('contract_value') is-invalid @enderror" 
                                   id="contract_value" name="contract_value" value="{{ old('contract_value') }}" 
                                   step="0.01" min="0" required>
                            @error('contract_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="Draft">Draft</option>
                                <option value="Active" selected>Active</option>
                                <option value="Completed">Completed</option>
                                <option value="Terminated">Terminated</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="payment_terms" class="form-label">Payment Terms <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('payment_terms') is-invalid @enderror" 
                              id="payment_terms" name="payment_terms" rows="3" required>{{ old('payment_terms') }}</textarea>
                    @error('payment_terms')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="delivery_terms" class="form-label">Delivery Terms</label>
                    <textarea class="form-control" id="delivery_terms" name="delivery_terms" rows="2">{{ old('delivery_terms') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="terms_conditions" class="form-label">Terms & Conditions <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('terms_conditions') is-invalid @enderror" 
                              id="terms_conditions" name="terms_conditions" rows="5" required>{{ old('terms_conditions') }}</textarea>
                    @error('terms_conditions')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Contract
                    </button>
                    <a href="{{ route('sales.contracts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
