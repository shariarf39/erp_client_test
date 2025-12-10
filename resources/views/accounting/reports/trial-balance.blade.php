@extends('layouts.app')

@section('title', 'Trial Balance - SENA.ERP')
@section('page_title', 'Trial Balance')
@section('page_description', 'Trial Balance Report')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-balance-scale me-2"></i>Trial Balance Report</h5>
                </div>
                <div class="card-body">
                    <!-- Date Range Filter -->
                    <form method="GET" action="{{ route('accounting.reports.trial-balance') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">From Date</label>
                                <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">To Date</label>
                                <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Generate Report
                                </button>
                                <button type="button" class="btn btn-secondary ms-2" onclick="window.print()">
                                    <i class="fas fa-print"></i> Print
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Report Header -->
                    <div class="text-center mb-4">
                        <h4>Trial Balance</h4>
                        <p class="text-muted">Period: {{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('M d, Y') }}</p>
                    </div>

                    <!-- Trial Balance Table -->
                    @if($accounts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Account Code</th>
                                        <th>Account Name</th>
                                        <th>Account Type</th>
                                        <th class="text-end">Debit (₹)</th>
                                        <th class="text-end">Credit (₹)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($accounts as $account)
                                    <tr>
                                        <td>{{ $account->account_code }}</td>
                                        <td>{{ $account->account_name }}</td>
                                        <td>
                                            @if($account->accountType)
                                                {{ $account->accountType->name }}
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($account->debit_total > 0)
                                                {{ number_format($account->debit_total, 2) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($account->credit_total > 0)
                                                {{ number_format($account->credit_total, 2) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-secondary">
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th class="text-end">{{ number_format($totalDebit, 2) }}</th>
                                        <th class="text-end">{{ number_format($totalCredit, 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Difference:</th>
                                        <th colspan="2" class="text-center {{ abs($totalDebit - $totalCredit) < 0.01 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format(abs($totalDebit - $totalCredit), 2) }}
                                            @if(abs($totalDebit - $totalCredit) < 0.01)
                                                <i class="fas fa-check-circle"></i> Balanced
                                            @else
                                                <i class="fas fa-exclamation-triangle"></i> Not Balanced
                                            @endif
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No transactions found for the selected period.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
