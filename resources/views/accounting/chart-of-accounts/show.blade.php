@extends('layouts.app')

@section('title', 'Account Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-journal-text me-2"></i>Account Details
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('accounting.chart-of-accounts.index') }}">Chart of Accounts</a></li>
                    <li class="breadcrumb-item active">{{ $chartOfAccount->account_name }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('accounting.chart-of-accounts.edit', $chartOfAccount) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('accounting.chart-of-accounts.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Account Information -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Account Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Basic Details -->
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-card-list me-2"></i>Basic Details
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">Account Code:</td>
                                    <td><strong>{{ $chartOfAccount->account_code }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Account Name:</td>
                                    <td><strong>{{ $chartOfAccount->account_name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Account Type:</td>
                                    <td>
                                        @if($chartOfAccount->accountType)
                                            <span class="badge bg-info">{{ $chartOfAccount->accountType->name }}</span>
                                            <br><small class="text-muted">{{ $chartOfAccount->accountType->category }}</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status:</td>
                                    <td>
                                        @if($chartOfAccount->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Hierarchy & Balance -->
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-diagram-3 me-2"></i>Hierarchy & Balance
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">Parent Account:</td>
                                    <td>
                                        @if($chartOfAccount->parent)
                                            <a href="{{ route('accounting.chart-of-accounts.show', $chartOfAccount->parent) }}">
                                                {{ $chartOfAccount->parent->account_name }}
                                            </a>
                                            <br><small class="text-muted">{{ $chartOfAccount->parent->account_code }}</small>
                                        @else
                                            <span class="text-muted">Root Account</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Opening Balance:</td>
                                    <td>
                                        <strong>৳{{ number_format($chartOfAccount->opening_balance, 2) }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Current Balance:</td>
                                    <td>
                                        <strong class="{{ $chartOfAccount->current_balance < 0 ? 'text-danger' : 'text-success' }}">
                                            ৳{{ number_format($chartOfAccount->current_balance, 2) }}
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Account Type:</td>
                                    <td>
                                        @if($chartOfAccount->children->count() > 0)
                                            <span class="badge bg-warning">Group Account</span>
                                        @else
                                            <span class="badge bg-primary">Ledger Account</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Description -->
                        @if($chartOfAccount->description)
                            <div class="col-12">
                                <hr>
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-chat-left-text me-2"></i>Description
                                </h6>
                                <div class="p-3 bg-light rounded">
                                    {{ $chartOfAccount->description }}
                                </div>
                            </div>
                        @endif

                        <!-- Timestamps -->
                        <div class="col-12">
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-plus me-1"></i>Created: {{ $chartOfAccount->created_at->format('d M Y, h:i A') }}
                                    </small>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-check me-1"></i>Updated: {{ $chartOfAccount->updated_at->format('d M Y, h:i A') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sub Accounts -->
            @if($chartOfAccount->children->count() > 0)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-info text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-diagram-2 me-2"></i>Sub Accounts ({{ $chartOfAccount->children->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th class="text-end">Balance</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($chartOfAccount->children as $child)
                                        <tr>
                                            <td><strong>{{ $child->account_code }}</strong></td>
                                            <td>{{ $child->account_name }}</td>
                                            <td>
                                                @if($child->accountType)
                                                    <small class="text-muted">{{ $child->accountType->name }}</small>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <span class="{{ $child->current_balance < 0 ? 'text-danger' : 'text-success' }}">
                                                    ৳{{ number_format($child->current_balance, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($child->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('accounting.chart-of-accounts.show', $child) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Transactions -->
            @if($chartOfAccount->voucherDetails->count() > 0)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-receipt me-2"></i>Recent Transactions (Last 20)
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Voucher No</th>
                                        <th>Description</th>
                                        <th class="text-end">Debit</th>
                                        <th class="text-end">Credit</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($chartOfAccount->voucherDetails as $detail)
                                        <tr>
                                            <td>{{ $detail->voucher->voucher_date->format('d M Y') }}</td>
                                            <td><strong>{{ $detail->voucher->voucher_number }}</strong></td>
                                            <td>{{ $detail->description ?? $detail->voucher->description }}</td>
                                            <td class="text-end">
                                                @if($detail->debit > 0)
                                                    <span class="text-danger">৳{{ number_format($detail->debit, 2) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if($detail->credit > 0)
                                                    <span class="text-success">৳{{ number_format($detail->credit, 2) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('accounting.vouchers.show', $detail->voucher) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Quick Actions & Stats -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('accounting.chart-of-accounts.edit', $chartOfAccount) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Edit Account
                        </a>
                        <a href="{{ route('accounting.vouchers.create') }}?account_id={{ $chartOfAccount->id }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-2"></i>Create Voucher
                        </a>
                        @if($chartOfAccount->parent)
                            <a href="{{ route('accounting.chart-of-accounts.show', $chartOfAccount->parent) }}" class="btn btn-info">
                                <i class="bi bi-arrow-up me-2"></i>View Parent Account
                            </a>
                        @endif
                        <form action="{{ route('accounting.chart-of-accounts.destroy', $chartOfAccount) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this account?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-danger w-100" 
                                    {{ ($chartOfAccount->children->count() > 0 || $chartOfAccount->voucherDetails->count() > 0) ? 'disabled' : '' }}>
                                <i class="bi bi-trash me-2"></i>Delete Account
                            </button>
                        </form>
                        @if($chartOfAccount->children->count() > 0 || $chartOfAccount->voucherDetails->count() > 0)
                            <small class="text-danger text-center">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Cannot delete with sub-accounts or transactions
                            </small>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="text-muted small">Sub Accounts</div>
                        <div class="h4 text-primary">{{ $chartOfAccount->children->count() }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Total Transactions</div>
                        <div class="h4 text-info">{{ $chartOfAccount->voucherDetails()->count() }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Total Debit</div>
                        <div class="h4 text-danger">
                            ৳{{ number_format($chartOfAccount->voucherDetails()->sum('debit'), 2) }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Total Credit</div>
                        <div class="h4 text-success">
                            ৳{{ number_format($chartOfAccount->voucherDetails()->sum('credit'), 2) }}
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="text-muted small">Current Balance</div>
                        <div class="h4 {{ $chartOfAccount->current_balance < 0 ? 'text-danger' : 'text-success' }}">
                            ৳{{ number_format($chartOfAccount->current_balance, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection
