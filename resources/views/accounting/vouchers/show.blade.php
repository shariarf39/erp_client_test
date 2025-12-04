@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Voucher Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('accounting.vouchers.index') }}">Vouchers</a></li>
                    <li class="breadcrumb-item active">{{ $voucher->voucher_no }}</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            @if($voucher->status === 'Draft')
                <a href="{{ route('accounting.vouchers.edit', $voucher) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
            @endif
            <a href="{{ route('accounting.vouchers.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Voucher Information -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-file-text"></i> Voucher Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Voucher No:</strong>
                            <p class="mb-0">{{ $voucher->voucher_no }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Type:</strong>
                            <p class="mb-0">
                                @if($voucher->voucher_type === 'Journal')
                                    <span class="badge bg-info">Journal</span>
                                @elseif($voucher->voucher_type === 'Payment')
                                    <span class="badge bg-danger">Payment</span>
                                @elseif($voucher->voucher_type === 'Receipt')
                                    <span class="badge bg-success">Receipt</span>
                                @else
                                    <span class="badge bg-secondary">Contra</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Date:</strong>
                            <p class="mb-0">{{ $voucher->date->format('d M Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <p class="mb-0">
                                @if($voucher->status === 'Draft')
                                    <span class="badge bg-warning">Draft</span>
                                @elseif($voucher->status === 'Posted')
                                    <span class="badge bg-success">Posted</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Reference:</strong>
                            <p class="mb-0">{{ $voucher->reference ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Total Amount:</strong>
                            <p class="mb-0 text-primary fw-bold">৳{{ number_format($voucher->total_amount, 2) }}</p>
                        </div>
                    </div>
                    @if($voucher->description)
                        <div class="row mb-3">
                            <div class="col-12">
                                <strong>Description:</strong>
                                <p class="mb-0">{{ $voucher->description }}</p>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Created By:</strong>
                            <p class="mb-0">{{ $voucher->createdBy->name ?? 'N/A' }}</p>
                        </div>
                        @if($voucher->approved_by)
                            <div class="col-md-6">
                                <strong>Approved By:</strong>
                                <p class="mb-0">{{ $voucher->approvedBy->name ?? 'N/A' }} on {{ $voucher->approved_at->format('d M Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Voucher Entries -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-list-check"></i> Voucher Entries</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Account Code</th>
                                    <th>Account Name</th>
                                    <th>Description</th>
                                    <th class="text-end">Debit (৳)</th>
                                    <th class="text-end">Credit (৳)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalDebit = 0;
                                    $totalCredit = 0;
                                @endphp
                                @foreach($voucher->voucherDetails as $detail)
                                    @php
                                        $totalDebit += $detail->debit;
                                        $totalCredit += $detail->credit;
                                    @endphp
                                    <tr>
                                        <td>{{ $detail->account->account_code }}</td>
                                        <td>
                                            <a href="{{ route('accounting.chart-of-accounts.show', $detail->account) }}" class="text-decoration-none">
                                                {{ $detail->account->account_name }}
                                            </a>
                                        </td>
                                        <td>{{ $detail->description ?? '-' }}</td>
                                        <td class="text-end {{ $detail->debit > 0 ? 'text-danger fw-bold' : '' }}">
                                            {{ $detail->debit > 0 ? number_format($detail->debit, 2) : '-' }}
                                        </td>
                                        <td class="text-end {{ $detail->credit > 0 ? 'text-success fw-bold' : '' }}">
                                            {{ $detail->credit > 0 ? number_format($detail->credit, 2) : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th class="text-end text-danger">৳{{ number_format($totalDebit, 2) }}</th>
                                    <th class="text-end text-success">৳{{ number_format($totalCredit, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Difference:</th>
                                    <th colspan="2" class="text-center {{ abs($totalDebit - $totalCredit) < 0.01 ? 'text-success' : 'text-danger' }}">
                                        ৳{{ number_format(abs($totalDebit - $totalCredit), 2) }}
                                        @if(abs($totalDebit - $totalCredit) < 0.01)
                                            <i class="bi bi-check-circle-fill"></i> Balanced
                                        @else
                                            <i class="bi bi-exclamation-triangle-fill"></i> Unbalanced
                                        @endif
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-lightning-fill"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($voucher->status === 'Draft')
                            <a href="{{ route('accounting.vouchers.edit', $voucher) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit Voucher
                            </a>
                            <form action="{{ route('accounting.vouchers.destroy', $voucher) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this voucher?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bi bi-trash"></i> Delete Voucher
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary" disabled>
                                <i class="bi bi-lock"></i> Posted Voucher
                            </button>
                            <small class="text-muted">Posted vouchers cannot be edited or deleted. Create a reversal entry if needed.</small>
                        @endif
                        <a href="{{ route('accounting.vouchers.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> New Voucher
                        </a>
                        <a href="{{ route('accounting.vouchers.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list"></i> All Vouchers
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Number of Entries</small>
                        <h4 class="mb-0">{{ $voucher->voucherDetails->count() }}</h4>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Debit Entries</small>
                        <h4 class="mb-0">{{ $voucher->voucherDetails->where('debit', '>', 0)->count() }}</h4>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Credit Entries</small>
                        <h4 class="mb-0">{{ $voucher->voucherDetails->where('credit', '>', 0)->count() }}</h4>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted">Created On</small>
                        <p class="mb-0">{{ $voucher->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    @if($voucher->updated_at != $voucher->created_at)
                        <div class="mb-0 mt-2">
                            <small class="text-muted">Last Updated</small>
                            <p class="mb-0">{{ $voucher->updated_at->format('d M Y, h:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
