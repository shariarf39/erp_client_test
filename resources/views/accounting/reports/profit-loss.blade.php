@extends('layouts.app')

@section('title', 'Profit & Loss Statement - SENA.ERP')
@section('page_title', 'Profit & Loss Statement')
@section('page_description', 'Income Statement Report')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Profit & Loss Statement</h5>
                </div>
                <div class="card-body">
                    <!-- Date Range Filter -->
                    <form method="GET" action="{{ route('accounting.reports.profit-loss') }}" class="mb-4">
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
                        <h4>Profit & Loss Statement</h4>
                        <p class="text-muted">Period: {{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('M d, Y') }}</p>
                    </div>

                    <div class="row">
                        <!-- Revenue Section -->
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">Revenue / Income</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tbody>
                                            @foreach($revenueAccounts as $account)
                                                @php
                                                    $revenue = $account->ledgers->sum('credit') - $account->ledgers->sum('debit');
                                                @endphp
                                                @if($revenue > 0)
                                                <tr>
                                                    <td>{{ $account->account_name }}</td>
                                                    <td class="text-end">{{ number_format($revenue, 2) }}</td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <th>Total Revenue:</th>
                                                <th class="text-end">{{ number_format($totalRevenue, 2) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Expense Section -->
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0">Expenses / Costs</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tbody>
                                            @foreach($expenseAccounts as $account)
                                                @php
                                                    $expense = $account->ledgers->sum('debit') - $account->ledgers->sum('credit');
                                                @endphp
                                                @if($expense > 0)
                                                <tr>
                                                    <td>{{ $account->account_name }}</td>
                                                    <td class="text-end">{{ number_format($expense, 2) }}</td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <th>Total Expenses:</th>
                                                <th class="text-end">{{ number_format($totalExpense, 2) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Net Profit/Loss -->
                    <div class="card {{ $netProfit >= 0 ? 'border-success' : 'border-danger' }}">
                        <div class="card-body text-center">
                            <h5 class="{{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $netProfit >= 0 ? 'Net Profit' : 'Net Loss' }}: 
                                <strong>₹ {{ number_format(abs($netProfit), 2) }}</strong>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
