@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Add New Account</h2>
                <a href="{{ route('accounting.chart-of-accounts.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Account Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('accounting.chart-of-accounts.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <!-- Account Code -->
                            <div class="col-md-6 mb-3">
                                <label for="account_code" class="form-label required">Account Code</label>
                                <input type="text" 
                                       name="account_code" 
                                       id="account_code" 
                                       class="form-control @error('account_code') is-invalid @enderror"
                                       value="{{ old('account_code') }}"
                                       placeholder="e.g., 1010, 2020, etc."
                                       required>
                                @error('account_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Use numeric code for proper sorting</div>
                            </div>

                            <!-- Account Name -->
                            <div class="col-md-6 mb-3">
                                <label for="account_name" class="form-label required">Account Name</label>
                                <input type="text" 
                                       name="account_name" 
                                       id="account_name" 
                                       class="form-control @error('account_name') is-invalid @enderror"
                                       value="{{ old('account_name') }}"
                                       required>
                                @error('account_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Account Type -->
                            <div class="col-md-6 mb-3">
                                <label for="account_type_id" class="form-label required">Account Type</label>
                                <select name="account_type_id" 
                                        id="account_type_id" 
                                        class="form-select @error('account_type_id') is-invalid @enderror"
                                        required>
                                    <option value="">Select Account Type</option>
                                    @foreach($accountTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('account_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->code }} - {{ $type->name }} ({{ $type->category }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('account_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Parent Account -->
                            <div class="col-md-6 mb-3">
                                <label for="parent_id" class="form-label">Parent Account</label>
                                <select name="parent_id" 
                                        id="parent_id" 
                                        class="form-select @error('parent_id') is-invalid @enderror">
                                    <option value="">No Parent (Top Level)</option>
                                    @foreach($parentAccounts as $parent)
                                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->account_code }} - {{ $parent->account_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Optional: Create hierarchical account structure</div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3" 
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Enter account description or purpose">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Opening Balance -->
                            <div class="col-md-6 mb-3">
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
                            <a href="{{ route('accounting.chart-of-accounts.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Save Account
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
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Account Structure Guide</h5>
                </div>
                <div class="card-body">
                    <h6 class="text-primary">Account Code Format</h6>
                    <ul class="small">
                        <li><strong>1000-1999:</strong> Assets</li>
                        <li><strong>2000-2999:</strong> Liabilities</li>
                        <li><strong>3000-3999:</strong> Equity</li>
                        <li><strong>4000-4999:</strong> Income/Revenue</li>
                        <li><strong>5000-5999:</strong> Expenses</li>
                    </ul>

                    <hr>

                    <h6 class="text-primary mt-3">Examples</h6>
                    <div class="small">
                        <p class="mb-2"><strong>Assets:</strong></p>
                        <ul>
                            <li>1010 - Cash</li>
                            <li>1020 - Bank Account</li>
                            <li>1100 - Accounts Receivable</li>
                            <li>1500 - Fixed Assets</li>
                        </ul>

                        <p class="mb-2"><strong>Liabilities:</strong></p>
                        <ul>
                            <li>2010 - Accounts Payable</li>
                            <li>2100 - Bank Loans</li>
                            <li>2200 - Tax Payable</li>
                        </ul>

                        <p class="mb-2"><strong>Income:</strong></p>
                        <ul>
                            <li>4010 - Sales Revenue</li>
                            <li>4020 - Service Income</li>
                        </ul>

                        <p class="mb-2"><strong>Expenses:</strong></p>
                        <ul>
                            <li>5010 - Salary Expense</li>
                            <li>5020 - Rent Expense</li>
                            <li>5030 - Utilities</li>
                        </ul>
                    </div>

                    <hr>

                    <h6 class="text-primary mt-3">Hierarchy Tips</h6>
                    <ul class="small">
                        <li>Use parent accounts to group similar accounts</li>
                        <li>Parent accounts help in financial reporting</li>
                        <li>Keep hierarchy simple (2-3 levels max)</li>
                        <li>Main accounts don't need parents</li>
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
