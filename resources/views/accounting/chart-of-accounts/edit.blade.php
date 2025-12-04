@extends('layouts.app')

@section('title', 'Edit Account')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-pencil me-2"></i>Edit Account
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('accounting.chart-of-accounts.index') }}">Chart of Accounts</a></li>
                    <li class="breadcrumb-item active">Edit: {{ $chartOfAccount->account_name }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('accounting.chart-of-accounts.show', $chartOfAccount) }}" class="btn btn-info">
                <i class="bi bi-eye me-2"></i>View Details
            </a>
            <a href="{{ route('accounting.chart-of-accounts.index') }}" class="btn btn-secondary">
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
                <i class="bi bi-info-circle me-2"></i>Account Information
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('accounting.chart-of-accounts.update', $chartOfAccount) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Account Details -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="text-muted border-bottom pb-2">Account Details</h6>
                    </div>

                    <div class="col-md-6">
                        <label for="account_code" class="form-label">Account Code <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('account_code') is-invalid @enderror" 
                               id="account_code" 
                               name="account_code" 
                               value="{{ old('account_code', $chartOfAccount->account_code) }}"
                               required>
                        @error('account_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">e.g., 1010, 2050, 3000</small>
                    </div>

                    <div class="col-md-6">
                        <label for="account_name" class="form-label">Account Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('account_name') is-invalid @enderror" 
                               id="account_name" 
                               name="account_name" 
                               value="{{ old('account_name', $chartOfAccount->account_name) }}"
                               required>
                        @error('account_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="account_type_id" class="form-label">Account Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('account_type_id') is-invalid @enderror" 
                                id="account_type_id" 
                                name="account_type_id" 
                                required>
                            <option value="">Select Account Type</option>
                            @foreach($accountTypes as $type)
                                <option value="{{ $type->id }}" 
                                        {{ old('account_type_id', $chartOfAccount->account_type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }} ({{ $type->category }})
                                </option>
                            @endforeach
                        </select>
                        @error('account_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="parent_id" class="form-label">Parent Account (Optional)</label>
                        <select class="form-select @error('parent_id') is-invalid @enderror" 
                                id="parent_id" 
                                name="parent_id">
                            <option value="">No Parent (Root Account)</option>
                            @foreach($parentAccounts as $parent)
                                <option value="{{ $parent->id }}" 
                                        {{ old('parent_id', $chartOfAccount->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->account_code }} - {{ $parent->account_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Select parent to create account hierarchy</small>
                    </div>

                    <div class="col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3"
                                  placeholder="Optional account description or notes">{{ old('description', $chartOfAccount->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Balance Information -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="text-muted border-bottom pb-2">Balance Information</h6>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Opening Balance</label>
                        <input type="text" 
                               class="form-control" 
                               value="৳{{ number_format($chartOfAccount->opening_balance, 2) }}"
                               readonly>
                        <small class="form-text text-muted">Opening balance cannot be changed</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Current Balance</label>
                        <input type="text" 
                               class="form-control {{ $chartOfAccount->current_balance < 0 ? 'text-danger' : 'text-success' }}" 
                               value="৳{{ number_format($chartOfAccount->current_balance, 2) }}"
                               readonly>
                        <small class="form-text text-muted">Updated automatically by transactions</small>
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
                            <option value="1" {{ old('is_active', $chartOfAccount->is_active) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $chartOfAccount->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Additional Info -->
                @if($chartOfAccount->children->count() > 0)
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> This account has {{ $chartOfAccount->children->count() }} sub-account(s). 
                        Changing the parent account may affect the account hierarchy.
                    </div>
                @endif

                @if($chartOfAccount->voucherDetails()->count() > 0)
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Note:</strong> This account has {{ $chartOfAccount->voucherDetails()->count() }} transaction(s). 
                        Balance information is read-only.
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('accounting.chart-of-accounts.show', $chartOfAccount) }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Update Account
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
