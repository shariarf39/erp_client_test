@extends('layouts.app')

@section('title', 'Invoice Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-file-invoice-dollar"></i> Invoice - {{ $invoice->invoice_no }}</h2>
                <div>
                    <a href="{{ route('sales.invoices.edit', $invoice->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('sales.invoices.index') }}" class="btn btn-secondary">
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
                    <h5>Invoice Information</h5>
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Invoice No:</th>
                            <td>{{ $invoice->invoice_no }}</td>
                        </tr>
                        <tr>
                            <th>Invoice Date:</th>
                            <td>{{ \Carbon\Carbon::parse($invoice->date)->format('d M, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Due Date:</th>
                            <td>
                                @if($invoice->due_date)
                                    {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M, Y') }}
                                    @if(\Carbon\Carbon::parse($invoice->due_date)->isPast() && $invoice->status != 'Paid')
                                        <span class="badge bg-danger ms-2">Overdue</span>
                                    @endif
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Sales Order:</th>
                            <td>{{ $invoice->salesOrder->so_no ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($invoice->status == 'Pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($invoice->status == 'Partial')
                                    <span class="badge bg-info">Partial</span>
                                @else
                                    <span class="badge bg-success">Paid</span>
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
                            <td>{{ $invoice->customer->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $invoice->customer->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $invoice->customer->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $invoice->customer->address ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr class="my-4">

            <h5>Financial Summary</h5>
            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <table class="table table-bordered">
                        <tr>
                            <th>Subtotal:</th>
                            <td class="text-end">{{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Tax Amount:</th>
                            <td class="text-end">{{ number_format($invoice->tax_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Discount:</th>
                            <td class="text-end">-{{ number_format($invoice->discount_amount, 2) }}</td>
                        </tr>
                        <tr class="table-primary">
                            <th>Total Amount:</th>
                            <th class="text-end">{{ number_format($invoice->total_amount, 2) }}</th>
                        </tr>
                        <tr class="table-success">
                            <th>Paid Amount:</th>
                            <th class="text-end">{{ number_format($invoice->paid_amount, 2) }}</th>
                        </tr>
                        <tr class="table-warning">
                            <th>Due Amount:</th>
                            <th class="text-end">{{ number_format($invoice->due_amount, 2) }}</th>
                        </tr>
                    </table>
                </div>
            </div>

            @if($invoice->payments && $invoice->payments->count() > 0)
                <hr class="my-4">
                <h5>Payment History</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Receipt No</th>
                                <th>Payment Method</th>
                                <th>Amount</th>
                                <th>Reference</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->payments as $payment)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($payment->date)->format('d M, Y') }}</td>
                                    <td>{{ $payment->receipt_no }}</td>
                                    <td>{{ $payment->payment_method }}</td>
                                    <td>{{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->reference_no ?? '-' }}</td>
                                    <td>{{ $payment->remarks ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
