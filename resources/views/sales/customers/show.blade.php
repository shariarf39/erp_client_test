@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-person-badge me-2"></i>Customer Details
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.customers.index') }}">Customers</a></li>
                    <li class="breadcrumb-item active">{{ $customer->customer_name }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('sales.customers.edit', $customer) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('sales.customers.index') }}" class="btn btn-secondary">
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
        <!-- Customer Information -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Customer Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Basic Details -->
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-person me-2"></i>Basic Details
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">Customer Code:</td>
                                    <td><strong>{{ $customer->customer_code }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Customer Name:</td>
                                    <td><strong>{{ $customer->customer_name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Company Name:</td>
                                    <td>{{ $customer->company_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Contact Person:</td>
                                    <td>{{ $customer->contact_person ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status:</td>
                                    <td>
                                        @if($customer->is_active)
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
                                        @if($customer->email)
                                            <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Phone:</td>
                                    <td>
                                        @if($customer->phone)
                                            <i class="bi bi-telephone me-1"></i>{{ $customer->phone }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">City:</td>
                                    <td>{{ $customer->city ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Country:</td>
                                    <td>{{ $customer->country ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Address -->
                        @if($customer->address)
                            <div class="col-12">
                                <hr>
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-map me-2"></i>Address
                                </h6>
                                <div class="p-3 bg-light rounded">
                                    {{ $customer->address }}
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
                                            @if($customer->credit_limit)
                                                ৳{{ number_format($customer->credit_limit, 2) }}
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
                                            @if($customer->credit_days)
                                                {{ $customer->credit_days }} days
                                            @else
                                                <span class="text-muted">Not Set</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 border rounded">
                                        <div class="text-muted small">Current Balance</div>
                                        <div class="h5 mb-0 mt-1">
                                            @if($customer->current_balance)
                                                <span class="{{ $customer->current_balance < 0 ? 'text-danger' : 'text-success' }}">
                                                    ৳{{ number_format($customer->current_balance, 2) }}
                                                </span>
                                            @else
                                                ৳0.00
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tax Number -->
                        @if($customer->tax_number)
                            <div class="col-12">
                                <hr>
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-receipt me-2"></i>Tax Information
                                </h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="text-muted" width="20%">Tax Number:</td>
                                        <td>{{ $customer->tax_number }}</td>
                                    </tr>
                                </table>
                            </div>
                        @endif

                        <!-- Timestamps -->
                        <div class="col-12">
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-plus me-1"></i>Created: {{ $customer->created_at->format('d M Y, h:i A') }}
                                    </small>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-check me-1"></i>Updated: {{ $customer->updated_at->format('d M Y, h:i A') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales Orders -->
            @if($customer->salesOrders->count() > 0)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-cart me-2"></i>Recent Sales Orders
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>SO Number</th>
                                        <th>Date</th>
                                        <th class="text-end">Total Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->salesOrders as $so)
                                        <tr>
                                            <td><strong>{{ $so->so_number }}</strong></td>
                                            <td>{{ $so->so_date->format('d M Y') }}</td>
                                            <td class="text-end">৳{{ number_format($so->total_amount, 2) }}</td>
                                            <td>
                                                @if($so->status == 'Confirmed')
                                                    <span class="badge bg-success">{{ $so->status }}</span>
                                                @elseif($so->status == 'Pending')
                                                    <span class="badge bg-warning">{{ $so->status }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $so->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('sales.orders.show', $so) }}" class="btn btn-sm btn-info">
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
                        <a href="{{ route('sales.customers.edit', $customer) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Edit Customer
                        </a>
                        <a href="{{ route('sales.orders.create') }}?customer_id={{ $customer->id }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-2"></i>Create Sales Order
                        </a>
                        <a href="{{ route('sales.orders.index') }}?customer_id={{ $customer->id }}" class="btn btn-info">
                            <i class="bi bi-cart me-2"></i>View All Sales Orders
                        </a>
                        <form action="{{ route('sales.customers.destroy', $customer) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this customer?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-danger w-100" 
                                    {{ $customer->salesOrders->count() > 0 ? 'disabled' : '' }}>
                                <i class="bi bi-trash me-2"></i>Delete Customer
                            </button>
                        </form>
                        @if($customer->salesOrders->count() > 0)
                            <small class="text-danger text-center">
                                <i class="bi bi-exclamation-triangle me-1"></i>Cannot delete with Sales Orders
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
                        <div class="text-muted small">Total Sales Orders</div>
                        <div class="h4 text-primary">{{ $customer->salesOrders()->count() }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Total Sales Value</div>
                        <div class="h4 text-success">
                            ৳{{ number_format($customer->salesOrders()->sum('total_amount'), 2) }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Pending Orders</div>
                        <div class="h4 text-warning">
                            {{ $customer->salesOrders()->where('status', 'Pending')->count() }}
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="text-muted small">Confirmed Orders</div>
                        <div class="h4 text-info">
                            {{ $customer->salesOrders()->where('status', 'Confirmed')->count() }}
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
