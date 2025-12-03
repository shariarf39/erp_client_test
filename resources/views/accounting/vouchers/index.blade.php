@extends('layouts.app')

@section('page_title', 'Vouchers')
@section('page_description', 'Manage accounting vouchers')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-receipt me-2"></i> Voucher List
        </div>
        <a href="{{ route('accounting.vouchers.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Create New Voucher
        </a>
    </div>
    <div class="card-body">
        @if($vouchers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Voucher No</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vouchers as $voucher)
                            <tr>
                                <td>{{ $voucher->voucher_no }}</td>
                                <td>{{ \Carbon\Carbon::parse($voucher->voucher_date)->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $voucher->voucher_type }}</span>
                                </td>
                                <td>৳{{ number_format($voucher->amount ?? 0, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $voucher->status === 'Posted' ? 'success' : 'warning' }}">
                                        {{ $voucher->status ?? 'Draft' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $vouchers->links() }}
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i> No vouchers found. Click "Create New Voucher" to create one.
            </div>
        @endif
    </div>
</div>
@endsection
