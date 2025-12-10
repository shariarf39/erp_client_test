@extends('layouts.app')

@section('title', 'Sales Contracts - SENA.ERP')
@section('page_title', 'Sales Contracts')
@section('page_description', 'Manage customer sales contracts')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-file-contract me-2"></i>Sales Contracts</h5>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('sales.contracts.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search by contract no or customer..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Contracts Table -->
                    @if($contracts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Contract No</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>End Date</th>
                                        <th>Contract Value</th>
                                        <th>Payment Terms</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contracts as $contract)
                                    <tr>
                                        <td><strong>{{ $contract->contract_no }}</strong></td>
                                        <td>
                                            <small>
                                                <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($contract->contract_date)->format('M d, Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $contract->customer_name }}</strong>
                                                @if($contract->customer_email)
                                                    <br><small class="text-muted"><i class="fas fa-envelope"></i> {{ $contract->customer_email }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($contract->end_date)
                                                <small>
                                                    <i class="fas fa-calendar-check"></i> {{ \Carbon\Carbon::parse($contract->end_date)->format('M d, Y') }}
                                                </small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td><strong>₹ {{ number_format($contract->total_amount, 2) }}</strong></td>
                                        <td>
                                            @if($contract->payment_terms)
                                                <small>{{ Str::limit($contract->payment_terms, 30) }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'Approved' => 'success',
                                                    'Processing' => 'info',
                                                    'Completed' => 'primary',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$contract->status] ?? 'secondary' }}">
                                                {{ $contract->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('sales.contracts.show', $contract->id) }}" class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $contracts->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No contracts found.
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
                    <h6 class="text-muted">Total Contracts</h6>
                    <h3 class="mb-0">{{ \App\Models\SalesOrder::whereIn('status', ['Approved', 'Processing', 'Completed'])->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <h6 class="text-muted">Active</h6>
                    <h3 class="mb-0 text-success">{{ \App\Models\SalesOrder::whereIn('status', ['Approved', 'Processing'])->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <h6 class="text-muted">Completed</h6>
                    <h3 class="mb-0 text-info">{{ \App\Models\SalesOrder::where('status', 'Completed')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <h6 class="text-muted">Total Value</h6>
                    <h3 class="mb-0 text-warning">₹ {{ number_format(\App\Models\SalesOrder::whereIn('status', ['Approved', 'Processing', 'Completed'])->sum('total_amount'), 2) }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
