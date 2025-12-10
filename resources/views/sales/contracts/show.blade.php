@extends('layouts.app')

@section('title', 'Contract Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-file-contract"></i> Contract - {{ $contract->so_no }}</h2>
                <div>
                    <a href="{{ route('sales.contracts.edit', $contract->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('sales.contracts.index') }}" class="btn btn-secondary">
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
                    <h5>Contract Information</h5>
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Contract No:</th>
                            <td>{{ $contract->so_no }}</td>
                        </tr>
                        <tr>
                            <th>Contract Date:</th>
                            <td>{{ \Carbon\Carbon::parse($contract->date)->format('d M, Y') }}</td>
                        </tr>
                        <tr>
                            <th>End Date:</th>
                            <td>{{ \Carbon\Carbon::parse($contract->delivery_date)->format('d M, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Contract Value:</th>
                            <td><strong>{{ number_format($contract->total_amount, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($contract->status == 'Draft')
                                    <span class="badge bg-secondary">Draft</span>
                                @elseif($contract->status == 'Approved')
                                    <span class="badge bg-success">Active</span>
                                @elseif($contract->status == 'Completed')
                                    <span class="badge bg-info">Completed</span>
                                @else
                                    <span class="badge bg-danger">Terminated</span>
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
                            <td>{{ $contract->customer_name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $contract->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $contract->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $contract->address ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr class="my-4">

            <div class="row">
                <div class="col-md-12">
                    <h5>Payment Terms</h5>
                    <p>{{ $contract->payment_terms ?? 'Not specified' }}</p>
                </div>
            </div>

            @if($contract->notes)
                <hr class="my-4">
                <div class="row">
                    <div class="col-md-12">
                        <h5>Additional Notes</h5>
                        <p>{{ $contract->notes }}</p>
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
