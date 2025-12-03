@extends('layouts.app')

@section('title', 'Vendor Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Vendor Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Purchase</li>
                    <li class="breadcrumb-item active">Vendors</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('purchase.vendors.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Vendor
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Vendors</p>
                            <h4 class="mb-0">{{ $vendors->total() }}</h4>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-building fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Active Vendors</p>
                            <h4 class="mb-0 text-success">{{ $vendors->where('is_active', 1)->count() }}</h4>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Inactive Vendors</p>
                            <h4 class="mb-0 text-warning">{{ $vendors->where('is_active', 0)->count() }}</h4>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-pause-circle fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">This Month</p>
                            <h4 class="mb-0">{{ $vendors->where('created_at', '>=', now()->startOfMonth())->count() }}</h4>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-calendar fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('purchase.vendors.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Name, code, contact, email, phone..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Vendor Type</label>
                    <select name="vendor_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="Local" {{ request('vendor_type') == 'Local' ? 'selected' : '' }}>Local</option>
                        <option value="Foreign" {{ request('vendor_type') == 'Foreign' ? 'selected' : '' }}>Foreign</option>
                        <option value="Import" {{ request('vendor_type') == 'Import' ? 'selected' : '' }}>Import</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('purchase.vendors.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Vendors Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Vendor Code</th>
                            <th>Vendor Name</th>
                            <th>Contact Person</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Type</th>
                            <th>Credit Limit</th>
                            <th>Payment Terms</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $vendor)
                            <tr>
                                <td><strong>{{ $vendor->vendor_code }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-building text-primary"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $vendor->vendor_name }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $vendor->contact_person ?? 'N/A' }}</td>
                                <td>
                                    @if($vendor->email)
                                        <a href="mailto:{{ $vendor->email }}" class="text-decoration-none">
                                            <i class="fas fa-envelope text-muted"></i> {{ $vendor->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($vendor->phone)
                                        <a href="tel:{{ $vendor->phone }}" class="text-decoration-none">
                                            <i class="fas fa-phone text-muted"></i> {{ $vendor->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <small>
                                        {{ $vendor->city ? $vendor->city . ', ' : '' }}{{ $vendor->country ?? 'N/A' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($vendor->vendor_type == 'Local') bg-success
                                        @elseif($vendor->vendor_type == 'Foreign') bg-info
                                        @elseif($vendor->vendor_type == 'Import') bg-warning
                                        @else bg-secondary
                                        @endif
                                    ">
                                        {{ $vendor->vendor_type ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if($vendor->credit_limit)
                                        <strong>৳{{ number_format($vendor->credit_limit, 2) }}</strong>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $vendor->payment_terms ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    @if($vendor->is_active)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle"></i> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('purchase.vendors.show', $vendor->id) }}" class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('purchase.vendors.edit', $vendor->id) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" title="Delete" 
                                            onclick="if(confirm('Are you sure you want to delete this vendor?')) { document.getElementById('delete-form-{{ $vendor->id }}').submit(); }">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $vendor->id }}" action="{{ route('purchase.vendors.destroy', $vendor->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No vendors found</p>
                                    <a href="{{ route('purchase.vendors.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add First Vendor
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $vendors->firstItem() ?? 0 }} to {{ $vendors->lastItem() ?? 0 }} of {{ $vendors->total() }} entries
                </div>
                <div>
                    {{ $vendors->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 35px;
    height: 35px;
}
</style>
@endsection
