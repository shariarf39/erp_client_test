@extends('layouts.app')

@section('title', 'Chart of Accounts')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Chart of Accounts</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Accounting</li>
                    <li class="breadcrumb-item active">Chart of Accounts</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('accounting.chart-of-accounts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Account
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Accounts</p>
                            <h4 class="mb-0">{{ $accounts->total() }}</h4>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-chart-pie fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Group Accounts</p>
                            <h4 class="mb-0 text-info">{{ $accounts->where('is_group', 1)->count() }}</h4>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-folder fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Ledger Accounts</p>
                            <h4 class="mb-0 text-success">{{ $accounts->where('is_group', 0)->count() }}</h4>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-file-invoice fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Active Accounts</p>
                            <h4 class="mb-0 text-success">{{ $accounts->where('is_active', 1)->count() }}</h4>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('accounting.chart-of-accounts.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Account name or code..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Account Type</label>
                    <select name="account_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="Asset" {{ request('account_type') == 'Asset' ? 'selected' : '' }}>Asset</option>
                        <option value="Liability" {{ request('account_type') == 'Liability' ? 'selected' : '' }}>Liability</option>
                        <option value="Equity" {{ request('account_type') == 'Equity' ? 'selected' : '' }}>Equity</option>
                        <option value="Income" {{ request('account_type') == 'Income' ? 'selected' : '' }}>Income</option>
                        <option value="Expense" {{ request('account_type') == 'Expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Type</label>
                    <select name="is_group" class="form-select">
                        <option value="">All</option>
                        <option value="1" {{ request('is_group') == '1' ? 'selected' : '' }}>Group</option>
                        <option value="0" {{ request('is_group') == '0' ? 'selected' : '' }}>Ledger</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('accounting.chart-of-accounts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Accounts Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Account Code</th>
                            <th>Account Name</th>
                            <th>Account Type</th>
                            <th>Parent Account</th>
                            <th>Level</th>
                            <th>Type</th>
                            <th>Opening Balance</th>
                            <th>Current Balance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $account)
                            <tr>
                                <td><strong>{{ $account->account_code }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($account->is_group)
                                            <i class="fas fa-folder text-info me-2"></i>
                                        @else
                                            <i class="fas fa-file-invoice text-success me-2"></i>
                                        @endif
                                        <div>
                                            <strong>{{ $account->account_name }}</strong>
                                            @if($account->description)
                                                <br><small class="text-muted">{{ Str::limit($account->description, 40) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($account->account_type == 'Asset') bg-primary
                                        @elseif($account->account_type == 'Liability') bg-danger
                                        @elseif($account->account_type == 'Equity') bg-info
                                        @elseif($account->account_type == 'Income') bg-success
                                        @elseif($account->account_type == 'Expense') bg-warning
                                        @else bg-secondary
                                        @endif
                                    ">
                                        {{ $account->account_type }}
                                    </span>
                                </td>
                                <td>
                                    @if($account->parent)
                                        <small class="text-muted">
                                            {{ $account->parent->account_code }} - {{ $account->parent->account_name }}
                                        </small>
                                    @else
                                        <span class="badge bg-secondary">Root</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">Level {{ $account->level }}</span>
                                </td>
                                <td>
                                    @if($account->is_group)
                                        <span class="badge bg-info">
                                            <i class="fas fa-folder"></i> Group
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-file-invoice"></i> Ledger
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="{{ $account->opening_balance < 0 ? 'text-danger' : 'text-success' }}">
                                        ৳{{ number_format(abs($account->opening_balance), 2) }}
                                        @if($account->opening_balance < 0) Dr @else Cr @endif
                                    </strong>
                                </td>
                                <td>
                                    <strong class="{{ $account->current_balance < 0 ? 'text-danger' : 'text-success' }}">
                                        ৳{{ number_format(abs($account->current_balance), 2) }}
                                        @if($account->current_balance < 0) Dr @else Cr @endif
                                    </strong>
                                </td>
                                <td>
                                    @if($account->is_active)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle"></i> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('accounting.chart-of-accounts.show', $account->id) }}" class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('accounting.chart-of-accounts.edit', $account->id) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(!$account->is_group && $account->vouchers->isEmpty())
                                        <button type="button" class="btn btn-outline-danger" title="Delete" 
                                            onclick="if(confirm('Are you sure you want to delete this account?')) { document.getElementById('delete-form-{{ $account->id }}').submit(); }">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                    <form id="delete-form-{{ $account->id }}" action="{{ route('accounting.chart-of-accounts.destroy', $account->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No accounts found</p>
                                    <a href="{{ route('accounting.chart-of-accounts.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add First Account
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $accounts->firstItem() ?? 0 }} to {{ $accounts->lastItem() ?? 0 }} of {{ $accounts->total() }} entries
                </div>
                <div>
                    {{ $accounts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
