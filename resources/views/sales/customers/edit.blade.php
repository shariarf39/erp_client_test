@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-pencil me-2"></i>Edit Customer
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.customers.index') }}">Customers</a></li>
                    <li class="breadcrumb-item active">Edit: {{ $customer->customer_name }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('sales.customers.show', $customer) }}" class="btn btn-info">
                <i class="bi bi-eye me-2"></i>View Details
            </a>
            <a href="{{ route('sales.customers.index') }}" class="btn btn-secondary">
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
                <i class="bi bi-info-circle me-2"></i>Customer Information
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('sales.customers.update', $customer) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="text-muted border-bottom pb-2">Basic Information</h6>
                    </div>

                    <div class="col-md-4">
                        <label for="customer_code" class="form-label">Customer Code <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('customer_code') is-invalid @enderror" 
                               id="customer_code" 
                               name="customer_code" 
                               value="{{ old('customer_code', $customer->customer_code) }}"
                               required>
                        @error('customer_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="customer_name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('customer_name') is-invalid @enderror" 
                               id="customer_name" 
                               name="customer_name" 
                               value="{{ old('customer_name', $customer->customer_name) }}"
                               required>
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" 
                               class="form-control @error('company_name') is-invalid @enderror" 
                               id="company_name" 
                               name="company_name" 
                               value="{{ old('company_name', $customer->company_name) }}">
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
                               value="{{ old('contact_person', $customer->contact_person) }}">
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
                               value="{{ old('email', $customer->email) }}">
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
                               value="{{ old('phone', $customer->phone) }}">
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
                                  rows="2">{{ old('address', $customer->address) }}</textarea>
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
                               value="{{ old('city', $customer->city) }}">
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
                               value="{{ old('country', $customer->country) }}">
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
                               value="{{ old('credit_limit', $customer->credit_limit) }}">
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
                               value="{{ old('credit_days', $customer->credit_days) }}">
                        @error('credit_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="tax_number" class="form-label">Tax Number / VAT</label>
                        <input type="text" 
                               class="form-control @error('tax_number') is-invalid @enderror" 
                               id="tax_number" 
                               name="tax_number" 
                               value="{{ old('tax_number', $customer->tax_number) }}">
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
                            <option value="1" {{ old('is_active', $customer->is_active) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $customer->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('sales.customers.show', $customer) }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Update Customer
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
