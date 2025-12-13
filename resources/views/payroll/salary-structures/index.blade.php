@extends('layouts.app')

@section('title', 'Salary Structures')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Salary Structures</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Payroll</li>
                    <li class="breadcrumb-item active">Salary Structures</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('payroll.salary-structures.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Salary Structure
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
                            <p class="text-muted mb-1 small">Total Structures</p>
                            <h4 class="mb-0">{{ $salaryStructures->total() }}</h4>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-money-check-alt fa-2x text-primary"></i>
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
                            <p class="text-muted mb-1 small">Active Structures</p>
                            <h4 class="mb-0 text-success">{{ $salaryStructures->where('is_active', 1)->count() }}</h4>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
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
                            <p class="text-muted mb-1 small">Average Salary</p>
                            <h4 class="mb-0">৳{{ number_format($salaryStructures->avg('net_salary'), 2) }}</h4>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-chart-line fa-2x text-info"></i>
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
                            <p class="text-muted mb-1 small">Total Payroll</p>
                            <h4 class="mb-0">৳{{ number_format($salaryStructures->sum('net_salary'), 2) }}</h4>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-dollar-sign fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('payroll.salary-structures.index') }}" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Search Employee</label>
                    <input type="text" name="search" class="form-control" placeholder="Employee name or code..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('payroll.salary-structures.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Salary Structures Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Basic Salary</th>
                            <th>Allowances</th>
                            <th>Deductions</th>
                            <th>Gross Salary</th>
                            <th>Net Salary</th>
                            <th>Effective From</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaryStructures as $structure)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <div>
                                            <strong>{{ ($structure->employee->first_name ?? '') . ' ' . ($structure->employee->last_name ?? '') }}</strong>
                                            <br><small class="text-muted">{{ $structure->employee->employee_code ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $structure->employee->department->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td><strong>৳{{ number_format($structure->basic_salary, 2) }}</strong></td>
                                <td>
                                    <small>
                                        @php
                                            $totalAllowances = $structure->house_rent + $structure->medical_allowance + 
                                                             $structure->transport_allowance + $structure->food_allowance + 
                                                             $structure->other_allowance;
                                        @endphp
                                        ৳{{ number_format($totalAllowances, 2) }}
                                    </small>
                                </td>
                                <td>
                                    <small>
                                        @php
                                            $totalDeductions = $structure->provident_fund + $structure->tax_deduction + 
                                                             $structure->other_deduction;
                                        @endphp
                                        ৳{{ number_format($totalDeductions, 2) }}
                                    </small>
                                </td>
                                <td><strong class="text-info">৳{{ number_format($structure->gross_salary, 2) }}</strong></td>
                                <td><strong class="text-success">৳{{ number_format($structure->net_salary, 2) }}</strong></td>
                                <td>
                                    <small>{{ $structure->effective_from->format('d M Y') }}</small>
                                    @if($structure->effective_to)
                                        <br><small class="text-muted">to {{ $structure->effective_to->format('d M Y') }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($structure->is_active)
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
                                        <a href="{{ route('payroll.salary-structures.show', $structure->id) }}" class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('payroll.salary-structures.edit', $structure->id) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" title="Delete" 
                                            onclick="if(confirm('Are you sure you want to delete this salary structure?')) { document.getElementById('delete-form-{{ $structure->id }}').submit(); }">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $structure->id }}" action="{{ route('payroll.salary-structures.destroy', $structure->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="fas fa-money-check-alt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No salary structures found</p>
                                    <a href="{{ route('payroll.salary-structures.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add First Salary Structure
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
                    Showing {{ $salaryStructures->firstItem() ?? 0 }} to {{ $salaryStructures->lastItem() ?? 0 }} of {{ $salaryStructures->total() }} entries
                </div>
                <div>
                    {{ $salaryStructures->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 35px;
    height: 35px;
}
</style>
@endsection
