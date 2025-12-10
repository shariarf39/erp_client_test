@extends('layouts.app')

@section('title', 'Quotations - SENA.ERP')
@section('page_title', 'Quotation Management')
@section('page_description', 'Manage sales quotations')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Sales Quotations</h5>
                    <a href="{{ route('sales.quotations.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Create Quotation
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('sales.quotations.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Search by quotation no or customer..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="Sent" {{ request('status') == 'Sent' ? 'selected' : '' }}>Sent</option>
                                    <option value="Accepted" {{ request('status') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="Expired" {{ request('status') == 'Expired' ? 'selected' : '' }}>Expired</option>
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
                                <a href="{{ route('sales.quotations.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Quotations Table -->
                    @if($quotations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Quotation No</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Validity Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($quotations as $quotation)
                                    <tr>
                                        <td><strong>{{ $quotation->quotation_no }}</strong></td>
                                        <td>
                                            <small>
                                                <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($quotation->date)->format('M d, Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($quotation->customer)
                                                <div>
                                                    <strong>{{ $quotation->customer->name }}</strong>
                                                    @if($quotation->customer->email)
                                                        <br><small class="text-muted"><i class="fas fa-envelope"></i> {{ $quotation->customer->email }}</small>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($quotation->validity_date)
                                                <small>
                                                    <i class="fas fa-calendar-check"></i> {{ \Carbon\Carbon::parse($quotation->validity_date)->format('M d, Y') }}
                                                    @if(\Carbon\Carbon::parse($quotation->validity_date)->isPast())
                                                        <br><span class="badge bg-danger"><i class="fas fa-exclamation-triangle"></i> Expired</span>
                                                    @endif
                                                </small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>₹ {{ number_format($quotation->total_amount, 2) }}</strong>
                                            @if($quotation->subtotal)
                                                <br><small class="text-muted">Subtotal: ₹ {{ number_format($quotation->subtotal, 2) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'Draft' => 'secondary',
                                                    'Sent' => 'info',
                                                    'Accepted' => 'success',
                                                    'Rejected' => 'danger',
                                                    'Expired' => 'warning',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$quotation->status] ?? 'secondary' }}">
                                                {{ $quotation->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('sales.quotations.show', $quotation->id) }}" class="btn btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($quotation->status == 'Draft')
                                                    <a href="{{ route('sales.quotations.edit', $quotation->id) }}" class="btn btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('sales.quotations.destroy', $quotation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this quotation?');">
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
                            {{ $quotations->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No quotations found. <a href="{{ route('sales.quotations.create') }}" class="alert-link">Create your first quotation</a>
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
                    <h6 class="text-muted">Total Quotations</h6>
                    <h3 class="mb-0">{{ \App\Models\Quotation::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <h6 class="text-muted">Sent</h6>
                    <h3 class="mb-0 text-info">{{ \App\Models\Quotation::where('status', 'Sent')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <h6 class="text-muted">Accepted</h6>
                    <h3 class="mb-0 text-success">{{ \App\Models\Quotation::where('status', 'Accepted')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <h6 class="text-muted">Total Value</h6>
                    <h3 class="mb-0 text-warning">₹ {{ number_format(\App\Models\Quotation::whereIn('status', ['Sent', 'Accepted'])->sum('total_amount'), 2) }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
