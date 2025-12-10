@extends('layouts.app')

@section('title', 'Quotation Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-file-alt"></i> Quotation - {{ $quotation->quotation_no }}</h2>
                <div>
                    <a href="{{ route('sales.quotations.edit', $quotation->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('sales.quotations.index') }}" class="btn btn-secondary">
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
                    <h5>Quotation Information</h5>
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Quotation No:</th>
                            <td>{{ $quotation->quotation_no }}</td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td>{{ \Carbon\Carbon::parse($quotation->date)->format('d M, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Valid Until:</th>
                            <td>
                                @if($quotation->validity_date)
                                    {{ \Carbon\Carbon::parse($quotation->validity_date)->format('d M, Y') }}
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($quotation->status == 'Draft')
                                    <span class="badge bg-secondary">Draft</span>
                                @elseif($quotation->status == 'Sent')
                                    <span class="badge bg-info">Sent</span>
                                @elseif($quotation->status == 'Accepted')
                                    <span class="badge bg-success">Accepted</span>
                                @elseif($quotation->status == 'Rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-warning">Expired</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created By:</th>
                            <td>{{ $quotation->createdBy->name ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5>Customer Information</h5>
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Customer Name:</th>
                            <td>{{ $quotation->customer->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $quotation->customer->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $quotation->customer->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $quotation->customer->address ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr class="my-4">

            <h5>Items</h5>
            @if($quotation->details && $quotation->details->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="35%">Item</th>
                                <th width="15%">Quantity</th>
                                <th width="15%">Unit Price</th>
                                <th width="15%">Total</th>
                                <th width="15%">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $subtotal = 0; @endphp
                            @foreach($quotation->details as $index => $detail)
                                @php $subtotal += $detail->total_price; @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail->item->name ?? 'N/A' }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ number_format($detail->unit_price, 2) }}</td>
                                    <td>{{ number_format($detail->total_price, 2) }}</td>
                                    <td>{{ $detail->description ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Subtotal:</th>
                                <th>{{ number_format($subtotal, 2) }}</th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">Tax:</th>
                                <th>0.00</th>
                                <th></th>
                            </tr>
                            <tr class="table-primary">
                                <th colspan="4" class="text-end">Grand Total:</th>
                                <th>{{ number_format($subtotal, 2) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="alert alert-info">No items added to this quotation yet.</div>
            @endif

            <div class="row mt-4">
                <div class="col-md-6">
                    @if($quotation->payment_terms)
                        <h6>Payment Terms:</h6>
                        <p>{{ $quotation->payment_terms }}</p>
                    @endif
                </div>
                <div class="col-md-6">
                    @if($quotation->delivery_terms)
                        <h6>Delivery Terms:</h6>
                        <p>{{ $quotation->delivery_terms }}</p>
                    @endif
                </div>
            </div>

            @if($quotation->notes)
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h6>Notes:</h6>
                        <p>{{ $quotation->notes }}</p>
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
