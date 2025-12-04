@extends('layouts.app')

@section('title', 'Edit Vendor')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-pencil me-2"></i>Edit Vendor
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('purchase.vendors.index') }}">Vendors</a></li>
                    <li class="breadcrumb-item active">Edit: {{ $vendor->vendor_name }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('purchase.vendors.show', $vendor) }}" class="btn btn-info">
                <i class="bi bi-eye me-2"></i>View Details
            </a>
            <a href="{{ route('purchase.vendors.index') }}" class="btn btn-secondary">
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

    <!-- Form Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient text-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-info-circle me-2"></i>Vendor Information
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('purchase.vendors.update', $vendor) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="text-muted border-bottom pb-2">Basic Information</h6>
                    </div>

                    <div class="col-md-4">
                        <label for="vendor_code" class="form-label">Vendor Code <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('vendor_code') is-invalid @enderror" 
                               id="vendor_code" 
                               name="vendor_code" 
                               value="{{ old('vendor_code', $vendor->vendor_code) }}"
                               required>
                        @error('vendor_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="vendor_name" class="form-label">Vendor Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('vendor_name') is-invalid @enderror" 
                               id="vendor_name" 
                               name="vendor_name" 
                               value="{{ old('vendor_name', $vendor->vendor_name) }}"
                               required>
                        @error('vendor_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" 
                               class="form-control @error('company_name') is-invalid @enderror" 
                               id="company_name" 
                               name="company_name" 
                               value="{{ old('company_name', $vendor->company_name) }}">
                        @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="contact_person" class="form-label">Contact Person</label>
                        <input type="text" 
                               class="form-control @error('contact_person') is-invalid @enderror" 
                               id="contact_person" 
                               name="contact_person" 
                               value="{{ old('contact_person', $vendor->contact_person) }}">
                        @error('contact_person')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $vendor->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $vendor->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Address Information -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="text-muted border-bottom pb-2">Address Information</h6>
                    </div>

                    <div class="col-md-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="2">{{ old('address', $vendor->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="city" class="form-label">City</label>
                        <input type="text" 
                               class="form-control @error('city') is-invalid @enderror" 
                               id="city" 
                               name="city" 
                               value="{{ old('city', $vendor->city) }}">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" 
                               class="form-control @error('country') is-invalid @enderror" 
                               id="country" 
                               name="country" 
                               value="{{ old('country', $vendor->country) }}">
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Financial Information -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="text-muted border-bottom pb-2">Financial Information</h6>
                    </div>

                    <div class="col-md-4">
                        <label for="credit_limit" class="form-label">Credit Limit (৳)</label>
                        <input type="number" 
                               class="form-control @error('credit_limit') is-invalid @enderror" 
                               id="credit_limit" 
                               name="credit_limit" 
                               step="0.01"
                               min="0"
                               value="{{ old('credit_limit', $vendor->credit_limit) }}">
                        @error('credit_limit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="credit_days" class="form-label">Credit Days</label>
                        <input type="number" 
                               class="form-control @error('credit_days') is-invalid @enderror" 
                               id="credit_days" 
                               name="credit_days" 
                               min="0"
                               value="{{ old('credit_days', $vendor->credit_days) }}">
                        @error('credit_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="rating" class="form-label">Rating (0-5)</label>
                        <input type="number" 
                               class="form-control @error('rating') is-invalid @enderror" 
                               id="rating" 
                               name="rating" 
                               step="0.1"
                               min="0"
                               max="5"
                               value="{{ old('rating', $vendor->rating) }}">
                        @error('rating')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="payment_terms" class="form-label">Payment Terms</label>
                        <textarea class="form-control @error('payment_terms') is-invalid @enderror" 
                                  id="payment_terms" 
                                  name="payment_terms" 
                                  rows="2"
                                  placeholder="e.g., Net 30 days, 2% discount for early payment">{{ old('payment_terms', $vendor->payment_terms) }}</textarea>
                        @error('payment_terms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Banking Information -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="text-muted border-bottom pb-2">Banking Information</h6>
                    </div>

                    <div class="col-md-4">
                        <label for="bank_name" class="form-label">Bank Name</label>
                        <input type="text" 
                               class="form-control @error('bank_name') is-invalid @enderror" 
                               id="bank_name" 
                               name="bank_name" 
                               value="{{ old('bank_name', $vendor->bank_name) }}">
                        @error('bank_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="bank_account" class="form-label">Bank Account Number</label>
                        <input type="text" 
                               class="form-control @error('bank_account') is-invalid @enderror" 
                               id="bank_account" 
                               name="bank_account" 
                               value="{{ old('bank_account', $vendor->bank_account) }}">
                        @error('bank_account')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="tax_number" class="form-label">Tax Number / VAT</label>
                        <input type="text" 
                               class="form-control @error('tax_number') is-invalid @enderror" 
                               id="tax_number" 
                               name="tax_number" 
                               value="{{ old('tax_number', $vendor->tax_number) }}">
                        @error('tax_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="text-muted border-bottom pb-2">Status</h6>
                    </div>

                    <div class="col-md-6">
                        <label for="is_active" class="form-label">Status</label>
                        <select class="form-select @error('is_active') is-invalid @enderror" 
                                id="is_active" 
                                name="is_active">
                            <option value="1" {{ old('is_active', $vendor->is_active) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $vendor->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('purchase.vendors.show', $vendor) }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Update Vendor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection
