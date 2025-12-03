@extends('layouts.app')

@section('page_title', 'Sales Orders')
@section('page_description', 'Manage sales orders')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-shopping-bag me-2"></i> Sales Order List
        </div>
        <a href="{{ route('sales.orders.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Create New SO
        </a>
    </div>
    <div class="card-body">
        @if($salesOrders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>SO No</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesOrders as $so)
                            <tr>
                                <td>{{ $so->so_no }}</td>
                                <td>{{ \Carbon\Carbon::parse($so->date)->format('d M Y') }}</td>
                                <td>{{ $so->customer->customer_name ?? 'N/A' }}</td>
                                <td>৳{{ number_format($so->total_amount ?? 0, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $so->status === 'Approved' ? 'success' : ($so->status === 'Draft' ? 'warning' : 'info') }}">
                                        {{ $so->status }}
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
                {{ $salesOrders->links() }}
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i> No sales orders found. Click "Create New SO" to create one.
            </div>
        @endif
    </div>
</div>
@endsection
