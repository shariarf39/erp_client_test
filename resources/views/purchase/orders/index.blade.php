@extends('layouts.app')

@section('page_title', 'Purchase Orders')
@section('page_description', 'Manage purchase orders')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-shopping-cart me-2"></i> Purchase Order List
        </div>
        <a href="{{ route('purchase.orders.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Create New PO
        </a>
    </div>
    <div class="card-body">
        @if($purchaseOrders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>PO No</th>
                            <th>Date</th>
                            <th>Vendor</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseOrders as $po)
                            <tr>
                                <td>{{ $po->po_no }}</td>
                                <td>{{ \Carbon\Carbon::parse($po->date)->format('d M Y') }}</td>
                                <td>{{ $po->vendor->vendor_name ?? 'N/A' }}</td>
                                <td>৳{{ number_format($po->total_amount ?? 0, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $po->status === 'Approved' ? 'success' : ($po->status === 'Draft' ? 'warning' : 'info') }}">
                                        {{ $po->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $purchaseOrders->links() }}
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i> No purchase orders found. Click "Create New PO" to create one.
            </div>
        @endif
    </div>
</div>
@endsection
