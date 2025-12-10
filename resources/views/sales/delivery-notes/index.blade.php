@extends('layouts.app')

@section('title', 'Delivery Notes - SENA.ERP')
@section('page_title', 'Delivery Notes')
@section('page_description', 'Manage product delivery documentation')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Delivery Notes</h5>
                    <a href="{{ route('sales.delivery-notes.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Create Delivery Note
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('sales.delivery-notes.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Ready for Delivery</option>
                                    <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>In Transit</option>
                                    <option value="Partial" {{ request('status') == 'Partial' ? 'selected' : '' }}>Partial Delivered</option>
                                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Delivered</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Delivery Notes Table -->
                    @if($deliveryNotes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>DN No</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Delivery Address</th>
                                        <th>Delivery Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($deliveryNotes as $note)
                                    <tr>
                                        <td><strong>{{ $note->so_no }}</strong></td>
                                        <td>
                                            <small>
                                                <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($note->date)->format('M d, Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($note->customer)
                                                <strong>{{ $note->customer->name }}</strong>
                                            @endif
                                        </td>
                                        <td>
                                            @if($note->delivery_address)
                                                <small>{{ Str::limit($note->delivery_address, 40) }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($note->delivery_date)
                                                <small>
                                                    <i class="fas fa-calendar-check"></i> {{ \Carbon\Carbon::parse($note->delivery_date)->format('M d, Y') }}
                                                </small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'Approved' => 'info',
                                                    'Processing' => 'warning',
                                                    'Partial' => 'secondary',
                                                    'Completed' => 'success',
                                                ];
                                                $statusLabels = [
                                                    'Approved' => 'Ready',
                                                    'Processing' => 'In Transit',
                                                    'Partial' => 'Partial',
                                                    'Completed' => 'Delivered',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$note->status] ?? 'secondary' }}">
                                                {{ $statusLabels[$note->status] ?? $note->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('sales.delivery-notes.show', $note->id) }}" class="btn btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="btn btn-success" title="Print">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $deliveryNotes->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No delivery notes found.
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
                    <h6 class="text-muted">Total Deliveries</h6>
                    <h3 class="mb-0">{{ \App\Models\SalesOrder::whereIn('status', ['Approved', 'Processing', 'Completed'])->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <h6 class="text-muted">Ready for Delivery</h6>
                    <h3 class="mb-0 text-info">{{ \App\Models\SalesOrder::where('status', 'Approved')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <h6 class="text-muted">In Transit</h6>
                    <h3 class="mb-0 text-warning">{{ \App\Models\SalesOrder::where('status', 'Processing')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <h6 class="text-muted">Delivered</h6>
                    <h3 class="mb-0 text-success">{{ \App\Models\SalesOrder::where('status', 'Completed')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
