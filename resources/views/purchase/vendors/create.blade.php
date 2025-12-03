@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Add New Vendor</h2>
                <a href="{{ route('purchase.vendors.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Vendor Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('purchase.vendors.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <!-- Vendor Code -->
                            <div class="col-md-6 mb-3">
                                <label for="vendor_code" class="form-label required">Vendor Code</label>
                                <input type="text" 
                                       name="vendor_code" 
                                       id="vendor_code" 
                                       class="form-control @error('vendor_code') is-invalid @enderror"
                                       value="{{ old('vendor_code', $vendorCode) }}"
                                       readonly
                                       required>
                                @error('vendor_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Vendor Name -->
                            <div class="col-md-6 mb-3">
                                <label for="vendor_name" class="form-label required">Vendor Name</label>
                                <input type="text" 
                                       name="vendor_name" 
                                       id="vendor_name" 
                                       class="form-control @error('vendor_name') is-invalid @enderror"
                                       value="{{ old('vendor_name') }}"
                                       required>
                                @error('vendor_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Company Name -->
                            <div class="col-md-6 mb-3">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" 
                                       name="company_name" 
                                       id="company_name" 
                                       class="form-control @error('company_name') is-invalid @enderror"
                                       value="{{ old('company_name') }}">
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contact Person -->
                            <div class="col-md-6 mb-3">
                                <label for="contact_person" class="form-label">Contact Person</label>
                                <input type="text" 
                                       name="contact_person" 
                                       id="contact_person" 
                                       class="form-control @error('contact_person') is-invalid @enderror"
                                       value="{{ old('contact_person') }}">
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" 
                                       name="phone" 
                                       id="phone" 
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" 
                                      id="address" 
                                      rows="2" 
                                      class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- City -->
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" 
                                       name="city" 
                                       id="city" 
                                       class="form-control @error('city') is-invalid @enderror"
                                       value="{{ old('city') }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Country -->
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" 
                                       name="country" 
                                       id="country" 
                                       class="form-control @error('country') is-invalid @enderror"
                                       value="{{ old('country', 'Bangladesh') }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Credit Limit -->
                            <div class="col-md-6 mb-3">
                                <label for="credit_limit" class="form-label">Credit Limit</label>
                                <input type="number" 
                                       name="credit_limit" 
                                       id="credit_limit" 
                                       class="form-control @error('credit_limit') is-invalid @enderror"
                                       value="{{ old('credit_limit', 0) }}"
                                       step="0.01"
                                       min="0">
                                @error('credit_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Credit Days -->
                            <div class="col-md-6 mb-3">
                                <label for="credit_days" class="form-label">Credit Days</label>
                                <input type="number" 
                                       name="credit_days" 
                                       id="credit_days" 
                                       class="form-control @error('credit_days') is-invalid @enderror"
                                       value="{{ old('credit_days', 0) }}"
                                       min="0">
                                @error('credit_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Payment Terms -->
                        <div class="mb-3">
                            <label for="payment_terms" class="form-label">Payment Terms</label>
                            <textarea name="payment_terms" 
                                      id="payment_terms" 
                                      rows="2" 
                                      class="form-control @error('payment_terms') is-invalid @enderror"
                                      placeholder="e.g., Net 30, 50% advance, etc.">{{ old('payment_terms') }}</textarea>
                            @error('payment_terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Tax Number -->
                            <div class="col-md-4 mb-3">
                                <label for="tax_number" class="form-label">Tax Number / VAT</label>
                                <input type="text" 
                                       name="tax_number" 
                                       id="tax_number" 
                                       class="form-control @error('tax_number') is-invalid @enderror"
                                       value="{{ old('tax_number') }}">
                                @error('tax_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Bank Name -->
                            <div class="col-md-4 mb-3">
                                <label for="bank_name" class="form-label">Bank Name</label>
                                <input type="text" 
                                       name="bank_name" 
                                       id="bank_name" 
                                       class="form-control @error('bank_name') is-invalid @enderror"
                                       value="{{ old('bank_name') }}">
                                @error('bank_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Bank Account -->
                            <div class="col-md-4 mb-3">
                                <label for="bank_account" class="form-label">Bank Account</label>
                                <input type="text" 
                                       name="bank_account" 
                                       id="bank_account" 
                                       class="form-control @error('bank_account') is-invalid @enderror"
                                       value="{{ old('bank_account') }}">
                                @error('bank_account')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Rating -->
                            <div class="col-md-6 mb-3">
                                <label for="rating" class="form-label">Vendor Rating</label>
                                <select name="rating" id="rating" class="form-select @error('rating') is-invalid @enderror">
                                    <option value="0">Not Rated</option>
                                    <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>1 Star</option>
                                    <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>2 Stars</option>
                                    <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>3 Stars</option>
                                    <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>4 Stars</option>
                                    <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>5 Stars</option>
                                </select>
                                @error('rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="is_active" class="form-label">Status</label>
                                <select name="is_active" id="is_active" class="form-select @error('is_active') is-invalid @enderror">
                                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('purchase.vendors.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Save Vendor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Panel -->
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Quick Tips</h5>
                </div>
                <div class="card-body">
                    <h6 class="text-primary">Vendor Information</h6>
                    <ul class="small">
                        <li>Vendor code is auto-generated</li>
                        <li>Vendor name is required</li>
                        <li>Company name can be different from vendor name</li>
                        <li>Add contact person for easy communication</li>
                    </ul>

                    <hr>

                    <h6 class="text-primary mt-3">Credit Terms</h6>
                    <ul class="small">
                        <li><strong>Credit Limit:</strong> Maximum amount vendor can owe</li>
                        <li><strong>Credit Days:</strong> Payment due period (e.g., Net 30)</li>
                        <li>Set payment terms clearly to avoid disputes</li>
                    </ul>

                    <hr>

                    <h6 class="text-primary mt-3">Banking Details</h6>
                    <ul class="small">
                        <li>Bank information required for payments</li>
                        <li>Tax number/VAT for tax compliance</li>
                        <li>Keep accurate account numbers</li>
                    </ul>

                    <hr>

                    <h6 class="text-primary mt-3">Vendor Rating</h6>
                    <div class="small">
                        <p>Rate vendors based on:</p>
                        <ul>
                            <li>Product quality</li>
                            <li>Delivery time</li>
                            <li>Customer service</li>
                            <li>Pricing competitiveness</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .required::after {
        content: " *";
        color: red;
    }
    
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
        border-radius: 0.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
    }
</style>
@endpush
