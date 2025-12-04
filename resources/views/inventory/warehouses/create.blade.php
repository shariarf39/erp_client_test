@extends('layouts.app')

@section('title', 'Add Warehouse')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-plus-circle me-2"></i>Add New Warehouse
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory.warehouses.index') }}">Warehouses</a></li>
                    <li class="breadcrumb-item active">Add Warehouse</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('inventory.warehouses.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Warehouses
        </a>
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

    <!-- Form Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient text-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-info-circle me-2"></i>Warehouse Information
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('inventory.warehouses.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <!-- Warehouse Code -->
                    <div class="col-md-6">
                        <label for="warehouse_code" class="form-label">Warehouse Code <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('warehouse_code') is-invalid @enderror" 
                               id="warehouse_code" 
                               name="warehouse_code" 
                               value="{{ old('warehouse_code') }}"
                               placeholder="e.g., WH001"
                               required>
                        @error('warehouse_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Unique warehouse identifier</small>
                    </div>

                    <!-- Warehouse Name -->
                    <div class="col-md-6">
                        <label for="warehouse_name" class="form-label">Warehouse Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('warehouse_name') is-invalid @enderror" 
                               id="warehouse_name" 
                               name="warehouse_name" 
                               value="{{ old('warehouse_name') }}"
                               placeholder="e.g., Main Warehouse"
                               required>
                        @error('warehouse_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Descriptive warehouse name</small>
                    </div>

                    <!-- Address -->
                    <div class="col-md-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="2"
                                  placeholder="Enter warehouse address">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Complete warehouse address</small>
                    </div>

                    <!-- City -->
                    <div class="col-md-6">
                        <label for="city" class="form-label">City</label>
                        <input type="text" 
                               class="form-control @error('city') is-invalid @enderror" 
                               id="city" 
                               name="city" 
                               value="{{ old('city') }}"
                               placeholder="e.g., Dhaka">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">City or district</small>
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               placeholder="e.g., +880-1234567890">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Contact phone number</small>
                    </div>

                    <!-- Manager -->
                    <div class="col-md-6">
                        <label for="manager_id" class="form-label">Warehouse Manager</label>
                        <select class="form-select @error('manager_id') is-invalid @enderror" 
                                id="manager_id" 
                                name="manager_id">
                            <option value="">Not Assigned</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('manager_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->first_name }} {{ $employee->last_name }} - {{ $employee->employee_id }}
                                </option>
                            @endforeach
                        </select>
                        @error('manager_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Select responsible manager</small>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label for="is_active" class="form-label">Status</label>
                        <select class="form-select @error('is_active') is-invalid @enderror" 
                                id="is_active" 
                                name="is_active">
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Active warehouses are available for use</small>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('inventory.warehouses.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Create Warehouse
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
