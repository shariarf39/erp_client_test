@extends('layouts.app')

@section('title', 'Sales Invoices - SENA.ERP')
@section('page_title', 'Sales Invoicing')
@section('page_description', 'Manage sales invoices and billing')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Sales Invoices</h5>
                    <a href="{{ route('sales.invoices.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Create Invoice
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('sales.invoices.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Search by invoice no or customer..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Partial" {{ request('status') == 'Partial' ? 'selected' : '' }}>Partial</option>
                                    <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="from_date" class="form-control" placeholder="From Date" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="to_date" class="form-control" placeholder="To Date" value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                            <div class="col-md-1">
                                <a href="{{ route('sales.invoices.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Invoices Table -->
                    @if($invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                    <tr>
                                        <td><strong>{{ $invoice->invoice_no }}</strong></td>
                                        <td>
                                            <small>
                                                <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($invoice->date)->format('M d, Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($invoice->customer)
                                                <div>
                                                    <strong>{{ $invoice->customer->name }}</strong>
                                                    @if($invoice->customer->phone)
                                                        <br><small class="text-muted"><i class="fas fa-phone"></i> {{ $invoice->customer->phone }}</small>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($invoice->due_date)
                                                <small>
                                                    <i class="fas fa-calendar-times"></i> {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}
                                                    @if(\Carbon\Carbon::parse($invoice->due_date)->isPast() && $invoice->status != 'Paid')
                                                        <br><span class="badge bg-danger"><i class="fas fa-exclamation-triangle"></i> Overdue</span>
                                                    @endif
                                                </small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>₹ {{ number_format($invoice->total_amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if($invoice->paid_amount > 0)
                                                <span class="text-success">₹ {{ number_format($invoice->paid_amount, 2) }}</span>
                                                @php
                                                    $balance = $invoice->total_amount - $invoice->paid_amount;
                                                @endphp
                                                @if($balance > 0)
                                                    <br><small class="text-danger">Balance: ₹ {{ number_format($balance, 2) }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">₹ 0.00</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'Pending' => 'warning',
                                                    'Partial' => 'info',
                                                    'Paid' => 'success',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$invoice->status] ?? 'secondary' }}">
                                                {{ $invoice->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('sales.invoices.show', $invoice->id) }}" class="btn btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($invoice->status == 'Pending')
                                                    <a href="{{ route('sales.invoices.edit', $invoice->id) }}" class="btn btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('sales.invoices.destroy', $invoice->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $invoices->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No invoices found. <a href="{{ route('sales.invoices.create') }}" class="alert-link">Create your first invoice</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="text-muted">Total Invoices</h6>
                    <h3 class="mb-0">{{ \App\Models\SalesInvoice::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <h6 class="text-muted">Paid</h6>
                    <h3 class="mb-0 text-success">{{ \App\Models\SalesInvoice::where('status', 'Paid')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card danger">
                <div class="card-body">
                    <h6 class="text-muted">Overdue</h6>
                    <h3 class="mb-0 text-danger">{{ \App\Models\SalesInvoice::where('status', 'Overdue')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <h6 class="text-muted">Total Revenue</h6>
                    <h3 class="mb-0 text-info">₹ {{ number_format(\App\Models\SalesInvoice::where('status', 'Paid')->sum('total_amount'), 2) }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
