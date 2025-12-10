@extends('layouts.app')

@section('title', 'Balance Sheet - SENA.ERP')
@section('page_title', 'Balance Sheet')
@section('page_description', 'Financial Position Statement')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Balance Sheet</h5>
                </div>
                <div class="card-body">
                    <!-- Date Filter -->
                    <form method="GET" action="{{ route('accounting.reports.balance-sheet') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">As of Date</label>
                                <input type="date" name="as_of_date" class="form-control" value="{{ $asOfDate }}">
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
                        <h4>Balance Sheet</h4>
                        <p class="text-muted">As of {{ \Carbon\Carbon::parse($asOfDate)->format('M d, Y') }}</p>
                    </div>

                    <div class="row">
                        <!-- Assets Section -->
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">Assets</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tbody>
                                            @foreach($assetAccounts as $account)
                                                @php
                                                    $balance = $account->ledgers->sum('debit') - $account->ledgers->sum('credit');
                                                @endphp
                                                @if($balance != 0)
                                                <tr>
                                                    <td>{{ $account->account_name }}</td>
                                                    <td class="text-end">{{ number_format(abs($balance), 2) }}</td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <th>Total Assets:</th>
                                                <th class="text-end">{{ number_format($totalAssets, 2) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Liabilities & Equity Section -->
                        <div class="col-md-6">
                            <!-- Liabilities -->
                            <div class="card mb-3">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0">Liabilities</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tbody>
                                            @foreach($liabilityAccounts as $account)
                                                @php
                                                    $balance = $account->ledgers->sum('credit') - $account->ledgers->sum('debit');
                                                @endphp
                                                @if($balance != 0)
                                                <tr>
                                                    <td>{{ $account->account_name }}</td>
                                                    <td class="text-end">{{ number_format(abs($balance), 2) }}</td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <th>Total Liabilities:</th>
                                                <th class="text-end">{{ number_format($totalLiabilities, 2) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!-- Equity -->
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">Equity</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tbody>
                                            @foreach($equityAccounts as $account)
                                                @php
                                                    $balance = $account->ledgers->sum('credit') - $account->ledgers->sum('debit');
                                                @endphp
                                                @if($balance != 0)
                                                <tr>
                                                    <td>{{ $account->account_name }}</td>
                                                    <td class="text-end">{{ number_format(abs($balance), 2) }}</td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <th>Total Equity:</th>
                                                <th class="text-end">{{ number_format($totalEquity, 2) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!-- Total Liabilities + Equity -->
                            <div class="card border-primary">
                                <div class="card-body">
                                    <table class="table table-sm mb-0">
                                        <tfoot class="table-secondary">
                                            <tr>
                                                <th>Total Liabilities + Equity:</th>
                                                <th class="text-end">{{ number_format($totalLiabilities + $totalEquity, 2) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Balance Check -->
                    <div class="card {{ abs($totalAssets - ($totalLiabilities + $totalEquity)) < 0.01 ? 'border-success' : 'border-danger' }} mt-3">
                        <div class="card-body text-center">
                            <h5 class="{{ abs($totalAssets - ($totalLiabilities + $totalEquity)) < 0.01 ? 'text-success' : 'text-danger' }}">
                                @if(abs($totalAssets - ($totalLiabilities + $totalEquity)) < 0.01)
                                    <i class="fas fa-check-circle"></i> Balance Sheet is Balanced
                                @else
                                    <i class="fas fa-exclamation-triangle"></i> Balance Sheet is Not Balanced
                                    <br><small>Difference: ₹ {{ number_format(abs($totalAssets - ($totalLiabilities + $totalEquity)), 2) }}</small>
                                @endif
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
