@extends('layouts.app')

@section('title', 'Vendor Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-building me-2"></i>Vendor Details
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('purchase.vendors.index') }}">Vendors</a></li>
                    <li class="breadcrumb-item active">{{ $vendor->vendor_name }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('purchase.vendors.edit', $vendor) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('purchase.vendors.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Vendor Information -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Vendor Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Basic Details -->
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-building me-2"></i>Basic Details
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">Vendor Code:</td>
                                    <td><strong>{{ $vendor->vendor_code }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Vendor Name:</td>
                                    <td><strong>{{ $vendor->vendor_name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Company Name:</td>
                                    <td>{{ $vendor->company_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Contact Person:</td>
                                    <td>{{ $vendor->contact_person ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status:</td>
                                    <td>
                                        @if($vendor->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-telephone me-2"></i>Contact Information
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">Email:</td>
                                    <td>
                                        @if($vendor->email)
                                            <a href="mailto:{{ $vendor->email }}">{{ $vendor->email }}</a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Phone:</td>
                                    <td>
                                        @if($vendor->phone)
                                            <i class="bi bi-telephone me-1"></i>{{ $vendor->phone }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">City:</td>
                                    <td>{{ $vendor->city ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Country:</td>
                                    <td>{{ $vendor->country ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Address -->
                        @if($vendor->address)
                            <div class="col-12">
                                <hr>
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-map me-2"></i>Address
                                </h6>
                                <div class="p-3 bg-light rounded">
                                    {{ $vendor->address }}
                                </div>
                            </div>
                        @endif

                        <!-- Financial Information -->
                        <div class="col-12">
                            <hr>
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-cash-stack me-2"></i>Financial Information
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="p-3 border rounded">
                                        <div class="text-muted small">Credit Limit</div>
                                        <div class="h5 mb-0 mt-1">
                                            @if($vendor->credit_limit)
                                                ৳{{ number_format($vendor->credit_limit, 2) }}
                                            @else
                                                <span class="text-muted">Not Set</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 border rounded">
                                        <div class="text-muted small">Credit Days</div>
                                        <div class="h5 mb-0 mt-1">
                                            @if($vendor->credit_days)
                                                {{ $vendor->credit_days }} days
                                            @else
                                                <span class="text-muted">Not Set</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 border rounded">
                                        <div class="text-muted small">Rating</div>
                                        <div class="h5 mb-0 mt-1">
                                            @if($vendor->rating)
                                                <span class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $vendor->rating)
                                                            <i class="bi bi-star-fill"></i>
                                                        @else
                                                            <i class="bi bi-star"></i>
                                                        @endif
                                                    @endfor
                                                </span>
                                            @else
                                                <span class="text-muted">Not Rated</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Terms -->
                        @if($vendor->payment_terms)
                            <div class="col-12">
                                <hr>
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-credit-card me-2"></i>Payment Terms
                                </h6>
                                <div class="p-3 bg-light rounded">
                                    {{ $vendor->payment_terms }}
                                </div>
                            </div>
                        @endif

                        <!-- Banking Details -->
                        @if($vendor->bank_name || $vendor->bank_account)
                            <div class="col-12">
                                <hr>
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-bank me-2"></i>Banking Details
                                </h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="text-muted" width="20%">Bank Name:</td>
                                        <td>{{ $vendor->bank_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Account Number:</td>
                                        <td>{{ $vendor->bank_account ?? 'N/A' }}</td>
                                    </tr>
                                    @if($vendor->tax_number)
                                        <tr>
                                            <td class="text-muted">Tax Number:</td>
                                            <td>{{ $vendor->tax_number }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        @endif

                        <!-- Timestamps -->
                        <div class="col-12">
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-plus me-1"></i>Created: {{ $vendor->created_at->format('d M Y, h:i A') }}
                                    </small>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-check me-1"></i>Updated: {{ $vendor->updated_at->format('d M Y, h:i A') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Purchase Orders -->
            @if($vendor->purchaseOrders->count() > 0)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-cart me-2"></i>Recent Purchase Orders
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>PO Number</th>
                                        <th>Date</th>
                                        <th class="text-end">Total Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vendor->purchaseOrders as $po)
                                        <tr>
                                            <td><strong>{{ $po->po_number }}</strong></td>
                                            <td>{{ $po->po_date->format('d M Y') }}</td>
                                            <td class="text-end">৳{{ number_format($po->total_amount, 2) }}</td>
                                            <td>
                                                @if($po->status == 'Approved')
                                                    <span class="badge bg-success">{{ $po->status }}</span>
                                                @elseif($po->status == 'Pending')
                                                    <span class="badge bg-warning">{{ $po->status }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $po->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('purchase.orders.show', $po) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Quick Actions & Stats -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('purchase.vendors.edit', $vendor) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Edit Vendor
                        </a>
                        <a href="{{ route('purchase.orders.create') }}?vendor_id={{ $vendor->id }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-2"></i>Create Purchase Order
                        </a>
                        <a href="{{ route('purchase.orders.index') }}?vendor_id={{ $vendor->id }}" class="btn btn-info">
                            <i class="bi bi-cart me-2"></i>View All POs
                        </a>
                        <form action="{{ route('purchase.vendors.destroy', $vendor) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this vendor?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-danger w-100" 
                                    {{ $vendor->purchaseOrders->count() > 0 ? 'disabled' : '' }}>
                                <i class="bi bi-trash me-2"></i>Delete Vendor
                            </button>
                        </form>
                        @if($vendor->purchaseOrders->count() > 0)
                            <small class="text-danger text-center">
                                <i class="bi bi-exclamation-triangle me-1"></i>Cannot delete with POs
                            </small>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="text-muted small">Total Purchase Orders</div>
                        <div class="h4 text-primary">{{ $vendor->purchaseOrders()->count() }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Total Purchase Value</div>
                        <div class="h4 text-success">
                            ৳{{ number_format($vendor->purchaseOrders()->sum('total_amount'), 2) }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Pending Orders</div>
                        <div class="h4 text-warning">
                            {{ $vendor->purchaseOrders()->where('status', 'Pending')->count() }}
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="text-muted small">Approved Orders</div>
                        <div class="h4 text-info">
                            {{ $vendor->purchaseOrders()->where('status', 'Approved')->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection
