@extends('layouts.app')

@section('page_title', 'Dashboard')
@section('page_description', 'Overview of your ERP system')

@section('content')
<div class="row">
    <!-- Stat Cards -->
    <div class="col-md-3">
        <div class="card stat-card info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Employees</h6>
                        <h2 class="mb-0">{{ $data['total_employees'] }}</h2>
                    </div>
                    <div class="text-info">
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Present Today</h6>
                        <h2 class="mb-0">{{ $data['present_today'] }}</h2>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-check-circle fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">PO This Month</h6>
                        <h2 class="mb-0">{{ $data['purchase_orders_month'] }}</h2>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-shopping-cart fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">SO This Month</h6>
                        <h2 class="mb-0">{{ $data['sales_orders_month'] }}</h2>
                    </div>
                    <div class="text-danger">
                        <i class="fas fa-shopping-bag fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Quick Actions -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-bolt me-2"></i> Quick Actions
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <a href="{{ route('hr.employees.create') }}" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-user-plus d-block mb-2 fa-2x"></i>
                            Add Employee
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('attendance.check-in') }}" class="btn btn-outline-success w-100 mb-2">
                            <i class="fas fa-clock d-block mb-2 fa-2x"></i>
                            Check In
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('inventory.items.create') }}" class="btn btn-outline-info w-100 mb-2">
                            <i class="fas fa-box d-block mb-2 fa-2x"></i>
                            Add Item
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('purchase.orders.create') }}" class="btn btn-outline-warning w-100 mb-2">
                            <i class="fas fa-file-invoice d-block mb-2 fa-2x"></i>
                            New PO
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('sales.orders.create') }}" class="btn btn-outline-danger w-100 mb-2">
                            <i class="fas fa-file-alt d-block mb-2 fa-2x"></i>
                            New SO
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('accounting.vouchers.create') }}" class="btn btn-outline-secondary w-100 mb-2">
                            <i class="fas fa-receipt d-block mb-2 fa-2x"></i>
                            New Voucher
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Recent Employees -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-users me-2"></i> Recent Employees
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @forelse($recent_employees as $employee)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0">{{ $employee->full_name }}</h6>
                                    <small class="text-muted">{{ $employee->designation->title ?? 'N/A' }}</small>
                                </div>
                                <span class="badge bg-success">{{ $employee->status }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No employees found</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Purchase Orders -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-shopping-cart me-2"></i> Recent Purchase Orders
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @forelse($recent_purchase_orders as $po)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0">{{ $po->po_no }}</h6>
                                    <small class="text-muted">{{ $po->vendor->vendor_name ?? 'N/A' }}</small>
                                </div>
                                <span class="badge bg-{{ $po->status === 'Approved' ? 'success' : 'warning' }}">
                                    {{ $po->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No purchase orders found</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Sales Orders -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-shopping-bag me-2"></i> Recent Sales Orders
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @forelse($recent_sales_orders as $so)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0">{{ $so->so_no }}</h6>
                                    <small class="text-muted">{{ $so->customer->customer_name ?? 'N/A' }}</small>
                                </div>
                                <span class="badge bg-{{ $so->status === 'Approved' ? 'success' : 'warning' }}">
                                    {{ $so->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No sales orders found</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Pending Approvals -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-tasks me-2"></i> Pending Approvals
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td><i class="fas fa-file-invoice text-warning me-2"></i> Purchase Orders</td>
                            <td class="text-end">
                                <span class="badge bg-warning">{{ $data['pending_pos'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-file-alt text-danger me-2"></i> Sales Orders</td>
                            <td class="text-end">
                                <span class="badge bg-danger">{{ $data['pending_sos'] }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- System Info -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i> System Information
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td><i class="fas fa-box text-info me-2"></i> Total Items</td>
                            <td class="text-end"><strong>{{ $data['total_items'] }}</strong></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-truck text-warning me-2"></i> Total Vendors</td>
                            <td class="text-end"><strong>{{ $data['total_vendors'] }}</strong></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-user-tie text-success me-2"></i> Total Customers</td>
                            <td class="text-end"><strong>{{ $data['total_customers'] }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
