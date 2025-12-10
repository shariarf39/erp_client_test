@extends('layouts.app')

@section('title', 'Sales Analytics - SENA.ERP')
@section('page_title', 'Sales Analytics')
@section('page_description', 'Comprehensive sales performance dashboard')

@section('content')
<div class="container-fluid">
    <!-- Date Range Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('sales.analytics.index') }}" class="row g-3">
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
                                <i class="fas fa-filter"></i> Apply Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="text-muted">Total Sales</h6>
                    <h3 class="mb-0">₹ {{ number_format($totalSales, 2) }}</h3>
                    <small class="text-muted">Orders Value</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <h6 class="text-muted">Revenue Collected</h6>
                    <h3 class="mb-0 text-success">₹ {{ number_format($totalRevenue, 2) }}</h3>
                    <small class="text-muted">Paid Invoices</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <h6 class="text-muted">Pending Amount</h6>
                    <h3 class="mb-0 text-warning">₹ {{ number_format($pendingAmount, 2) }}</h3>
                    <small class="text-muted">Outstanding</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <h6 class="text-muted">Total Orders</h6>
                    <h3 class="mb-0 text-info">{{ $totalOrders }}</h3>
                    <small class="text-muted">In Period</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Customers -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-users me-2"></i>Top 5 Customers</h6>
                </div>
                <div class="card-body">
                    @if($topCustomers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th class="text-end">Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topCustomers as $item)
                                    <tr>
                                        <td>
                                            @if($item->customer)
                                                <strong>{{ $item->customer->name }}</strong>
                                            @else
                                                <span class="text-muted">Unknown</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <strong>₹ {{ number_format($item->total, 2) }}</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">No data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Status Distribution -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Order Status Distribution</h6>
                </div>
                <div class="card-body">
                    @if($orderStatusDistribution->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th class="text-end">Count</th>
                                        <th class="text-end">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalCount = $orderStatusDistribution->sum('count');
                                    @endphp
                                    @foreach($orderStatusDistribution as $status)
                                    <tr>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'Draft' => 'secondary',
                                                    'Approved' => 'success',
                                                    'Processing' => 'info',
                                                    'Partial' => 'warning',
                                                    'Completed' => 'primary',
                                                    'Cancelled' => 'danger',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$status->status] ?? 'secondary' }}">
                                                {{ $status->status }}
                                            </span>
                                        </td>
                                        <td class="text-end"><strong>{{ $status->count }}</strong></td>
                                        <td class="text-end">
                                            {{ $totalCount > 0 ? number_format(($status->count / $totalCount) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">No data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Trend (Last 6 Months) -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>Sales Trend (Last 6 Months)</h6>
                </div>
                <div class="card-body">
                    @if($salesByMonth->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th class="text-end">Total Sales</th>
                                        <th>Trend</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salesByMonth as $index => $month)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($month->month . '-01')->format('F Y') }}</td>
                                        <td class="text-end">
                                            <strong>₹ {{ number_format($month->total, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if($index > 0)
                                                @php
                                                    $prevMonth = $salesByMonth[$index - 1];
                                                    $change = $prevMonth->total > 0 ? (($month->total - $prevMonth->total) / $prevMonth->total) * 100 : 0;
                                                @endphp
                                                @if($change > 0)
                                                    <span class="text-success">
                                                        <i class="fas fa-arrow-up"></i> {{ number_format($change, 1) }}%
                                                    </span>
                                                @elseif($change < 0)
                                                    <span class="text-danger">
                                                        <i class="fas fa-arrow-down"></i> {{ number_format(abs($change), 1) }}%
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">No data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
