@extends('layouts.app')

@section('title', 'Delivery Note Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-truck"></i> Delivery Note - {{ $deliveryNote->so_no }}</h2>
                <div>
                    <a href="{{ route('sales.delivery-notes.edit', $deliveryNote->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('sales.delivery-notes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    <button onclick="window.print()" class="btn btn-info">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Delivery Information</h5>
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Order No:</th>
                            <td>{{ $deliveryNote->so_no }}</td>
                        </tr>
                        <tr>
                            <th>Order Date:</th>
                            <td>{{ \Carbon\Carbon::parse($deliveryNote->date)->format('d M, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Delivery Date:</th>
                            <td>
                                @if($deliveryNote->delivery_date)
                                    {{ \Carbon\Carbon::parse($deliveryNote->delivery_date)->format('d M, Y') }}
                                @else
                                    <span class="text-muted">Not scheduled</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($deliveryNote->status == 'Approved')
                                    <span class="badge bg-info">Ready</span>
                                @elseif($deliveryNote->status == 'Processing')
                                    <span class="badge bg-warning">In Transit</span>
                                @elseif($deliveryNote->status == 'Partial')
                                    <span class="badge bg-secondary">Partial</span>
                                @else
                                    <span class="badge bg-success">Delivered</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5>Customer Information</h5>
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Customer Name:</th>
                            <td>{{ $deliveryNote->customer->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $deliveryNote->customer->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $deliveryNote->customer->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Delivery Address:</th>
                            <td>{{ $deliveryNote->customer->address ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr class="my-4">

            <h5>Items to Deliver</h5>
            @if($deliveryNote->details && $deliveryNote->details->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="40%">Item</th>
                                <th width="15%">Quantity</th>
                                <th width="15%">Unit Price</th>
                                <th width="15%">Total</th>
                                <th width="10%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deliveryNote->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail->item->name ?? 'N/A' }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ number_format($detail->unit_price, 2) }}</td>
                                    <td>{{ number_format($detail->total_price, 2) }}</td>
                                    <td>
                                        @if($deliveryNote->status == 'Completed')
                                            <span class="badge bg-success">Delivered</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">No items in this order.</div>
            @endif

            @if($deliveryNote->notes)
                <hr class="my-4">
                <div class="row">
                    <div class="col-md-12">
                        <h5>Delivery Notes</h5>
                        <p>{{ $deliveryNote->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .btn, .sidebar, .main-header {
            display: none !important;
        }
    }
</style>
@endpush
@endsection
