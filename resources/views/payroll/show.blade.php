@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">Payroll Details</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('payroll.payroll.index') }}">Payroll</a></li>
                            <li class="breadcrumb-item active">Payroll #{{ $payroll->id }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('payroll.payroll.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- Payroll Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calculator me-2"></i>Payroll Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted">Payroll ID</label>
                            <p class="fw-bold">#{{ $payroll->id }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">Status</label>
                            <p>
                                @switch($payroll->status)
                                    @case('Draft')
                                        <span class="badge bg-secondary fs-6">Draft</span>
                                        @break
                                    @case('Processed')
                                        <span class="badge bg-warning text-dark fs-6">Processed</span>
                                        @break
                                    @case('Paid')
                                        <span class="badge bg-success fs-6">Paid</span>
                                        @break
                                @endswitch
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted">Month / Year</label>
                            <p class="fw-bold">
                                <span class="badge bg-info fs-6">
                                    {{ date('F', mktime(0, 0, 0, $payroll->month, 1)) }} {{ $payroll->year }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">Working Days</label>
                            <p class="fw-bold">{{ $payroll->working_days }} days</p>
                        </div>
                    </div>

                    <hr>

                    <!-- Attendance Summary -->
                    <h6 class="text-primary mb-3"><i class="bi bi-calendar-check me-2"></i>Attendance Summary</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="text-muted">Present Days</label>
                            <p><span class="badge bg-success fs-6">{{ $payroll->present_days }}</span></p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted">Absent Days</label>
                            <p><span class="badge bg-danger fs-6">{{ $payroll->absent_days }}</span></p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted">Leave Days</label>
                            <p><span class="badge bg-warning text-dark fs-6">{{ $payroll->leave_days }}</span></p>
                        </div>
                    </div>

                    <hr>

                    <!-- Salary Breakdown -->
                    <h6 class="text-primary mb-3"><i class="bi bi-currency-dollar me-2"></i>Salary Breakdown</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="fw-bold">Basic Salary</td>
                                    <td class="text-end">৳{{ number_format($payroll->basic_salary, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Total Allowances</td>
                                    <td class="text-end text-success">+ ৳{{ number_format($payroll->total_allowance, 2) }}</td>
                                </tr>
                                <tr class="table-light">
                                    <td class="fw-bold">Gross Salary</td>
                                    <td class="text-end fw-bold">৳{{ number_format($payroll->gross_salary, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Total Deductions</td>
                                    <td class="text-end text-danger">- ৳{{ number_format($payroll->total_deduction, 2) }}</td>
                                </tr>
                                @if($payroll->overtime_hours > 0)
                                <tr>
                                    <td class="fw-bold">
                                        Overtime ({{ $payroll->overtime_hours }} hrs)
                                    </td>
                                    <td class="text-end text-success">+ ৳{{ number_format($payroll->overtime_amount, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="table-success">
                                    <td class="fw-bold fs-5">Net Salary</td>
                                    <td class="text-end fw-bold fs-5">৳{{ number_format($payroll->net_salary, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if($payroll->remarks)
                        <hr>
                        <div class="mb-3">
                            <label class="text-muted">Remarks</label>
                            <p class="border p-3 rounded bg-light">{{ $payroll->remarks }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Employee Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i>Employee Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Employee Name</label>
                            <p class="fw-bold">{{ $payroll->employee->full_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Employee Code</label>
                            <p class="fw-bold">{{ $payroll->employee->employee_code }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Department</label>
                            <p class="fw-bold">{{ $payroll->employee->department->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Designation</label>
                            <p class="fw-bold">{{ $payroll->employee->designation->title ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Email</label>
                            <p>{{ $payroll->employee->email ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Phone</label>
                            <p>{{ $payroll->employee->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Processing & Payment Information -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Processing & Payment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Processed By</label>
                            <p class="fw-bold">{{ $payroll->processor->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Processed At</label>
                            <p class="fw-bold">
                                @if($payroll->processed_at)
                                    {{ $payroll->processed_at->format('d M Y, h:i A') }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        @if($payroll->status === 'Paid' && $payroll->paid_at)
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Paid At</label>
                            <p class="fw-bold text-success">
                                {{ $payroll->paid_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Created At</label>
                            <p>{{ $payroll->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        @if($payroll->updated_at != $payroll->created_at)
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Last Updated</label>
                            <p>{{ $payroll->updated_at->format('d M Y, h:i A') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Quick Actions Card -->
            <div class="card mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    @if($payroll->status === 'Processed')
                        <a href="{{ route('payroll.payroll.edit', $payroll) }}" class="btn btn-warning w-100 mb-2">
                            <i class="bi bi-pencil me-2"></i>Edit Payroll
                        </a>
                    @endif

                    @if($payroll->status !== 'Paid')
                        <form action="{{ route('payroll.payroll.destroy', $payroll) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this payroll record? This action cannot be undone.');"
                              class="d-inline w-100">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100 mb-2">
                                <i class="bi bi-trash me-2"></i>Delete Payroll
                            </button>
                        </form>
                    @endif

                    <button onclick="window.print()" class="btn btn-info w-100 mb-2">
                        <i class="bi bi-printer me-2"></i>Print Payslip
                    </button>

                    <a href="{{ route('payroll.payroll.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>

            <!-- Status Timeline Card -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Status Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item {{ $payroll->status ? 'completed' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Created</h6>
                                <small class="text-muted">{{ $payroll->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                        </div>

                        <div class="timeline-item {{ in_array($payroll->status, ['Processed', 'Paid']) ? 'completed' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Processed</h6>
                                <small class="text-muted">
                                    @if($payroll->processed_at)
                                        {{ $payroll->processed_at->format('d M Y, h:i A') }}
                                    @else
                                        Pending
                                    @endif
                                </small>
                            </div>
                        </div>

                        <div class="timeline-item {{ $payroll->status === 'Paid' ? 'completed' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Paid</h6>
                                <small class="text-muted">
                                    @if($payroll->paid_at)
                                        {{ $payroll->paid_at->format('d M Y, h:i A') }}
                                    @else
                                        Pending
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
        border-radius: 0.5rem;
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    
    .timeline-marker {
        position: absolute;
        left: -26px;
        top: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #e9ecef;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #e9ecef;
    }
    
    .timeline-item.completed .timeline-marker {
        background: #28a745;
        box-shadow: 0 0 0 2px #28a745;
    }
    
    .timeline-content h6 {
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    @media print {
        .btn, .breadcrumb, .card-header, .timeline {
            display: none !important;
        }
        
        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }
    }
</style>
@endpush
