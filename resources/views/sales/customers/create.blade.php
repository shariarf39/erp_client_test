@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Add New Customer</h2>
                <a href="{{ route('sales.customers.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('sales.customers.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <!-- Customer Code -->
                            <div class="col-md-6 mb-3">
                                <label for="customer_code" class="form-label required">Customer Code</label>
                                <input type="text" 
                                       name="customer_code" 
                                       id="customer_code" 
                                       class="form-control @error('customer_code') is-invalid @enderror"
                                       value="{{ old('customer_code', $customerCode) }}"
                                       readonly
                                       required>
                                @error('customer_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Customer Name -->
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label required">Customer Name</label>
                                <input type="text" 
                                       name="customer_name" 
                                       id="customer_name" 
                                       class="form-control @error('customer_name') is-invalid @enderror"
                                       value="{{ old('customer_name') }}"
                                       required>
                                @error('customer_name')
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
                            <div class="col-md-4 mb-3">
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
                            <div class="col-md-4 mb-3">
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

                            <!-- Opening Balance -->
                            <div class="col-md-4 mb-3">
                                <label for="opening_balance" class="form-label">Opening Balance</label>
                                <input type="number" 
                                       name="opening_balance" 
                                       id="opening_balance" 
                                       class="form-control @error('opening_balance') is-invalid @enderror"
                                       value="{{ old('opening_balance', 0) }}"
                                       step="0.01">
                                @error('opening_balance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Enter positive for receivable, negative for payable</div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Tax Number -->
                            <div class="col-md-6 mb-3">
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
                            <a href="{{ route('sales.customers.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Save Customer
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
                    <h6 class="text-primary">Customer Information</h6>
                    <ul class="small">
                        <li>Customer code is auto-generated</li>
                        <li>Customer name is required</li>
                        <li>Company name can be different from customer name</li>
                        <li>Add contact person for easy communication</li>
                    </ul>

                    <hr>

                    <h6 class="text-primary mt-3">Credit Terms</h6>
                    <ul class="small">
                        <li><strong>Credit Limit:</strong> Maximum amount customer can owe</li>
                        <li><strong>Credit Days:</strong> Payment due period (e.g., Net 30)</li>
                        <li>Set credit terms based on customer trustworthiness</li>
                        <li>Monitor credit utilization regularly</li>
                    </ul>

                    <hr>

                    <h6 class="text-primary mt-3">Opening Balance</h6>
                    <ul class="small">
                        <li><strong>Positive:</strong> Customer owes you (Receivable)</li>
                        <li><strong>Negative:</strong> You owe customer (Payable)</li>
                        <li><strong>Zero:</strong> Fresh start with no prior balance</li>
                        <li>Current balance will update with transactions</li>
                    </ul>

                    <hr>

                    <h6 class="text-primary mt-3">Tax Information</h6>
                    <ul class="small">
                        <li>Tax number required for tax invoices</li>
                        <li>Ensure accuracy for compliance</li>
                        <li>Keep customer records updated</li>
                    </ul>
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
