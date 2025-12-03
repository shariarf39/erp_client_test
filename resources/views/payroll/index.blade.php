@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Payroll Management</h2>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#processPayrollModal">
                    <i class="bi bi-calculator me-2"></i>Process Payroll
                </button>
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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Payroll</h5>
                    <h2>{{ \App\Models\Payroll::count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Paid This Month</h5>
                    <h2>{{ \App\Models\Payroll::where('status', 'Paid')->whereMonth('created_at', date('m'))->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Processed</h5>
                    <h2>{{ \App\Models\Payroll::where('status', 'Processed')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Total Amount</h5>
                    <h2>৳{{ number_format(\App\Models\Payroll::sum('net_salary'), 0) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Payroll Table -->
    <div class="card">
        <div class="card-body">
            @if($payrolls->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Employee</th>
                                <th>Month/Year</th>
                                <th>Working Days</th>
                                <th>Present</th>
                                <th>Absent</th>
                                <th>Basic Salary</th>
                                <th>Allowances</th>
                                <th>Deductions</th>
                                <th>Net Salary</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payrolls as $payroll)
                                <tr>
                                    <td>{{ $payroll->id }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $payroll->employee->full_name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $payroll->employee->employee_code ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ date('F', mktime(0, 0, 0, $payroll->month, 1)) }} {{ $payroll->year }}
                                        </span>
                                    </td>
                                    <td>{{ $payroll->working_days }}</td>
                                    <td><span class="badge bg-success">{{ $payroll->present_days }}</span></td>
                                    <td><span class="badge bg-danger">{{ $payroll->absent_days }}</span></td>
                                    <td>৳{{ number_format($payroll->basic_salary, 2) }}</td>
                                    <td>৳{{ number_format($payroll->total_allowance, 2) }}</td>
                                    <td>৳{{ number_format($payroll->total_deduction, 2) }}</td>
                                    <td class="fw-bold">৳{{ number_format($payroll->net_salary, 2) }}</td>
                                    <td>
                                        @switch($payroll->status)
                                            @case('Draft')
                                                <span class="badge bg-secondary">Draft</span>
                                                @break
                                            @case('Processed')
                                                <span class="badge bg-warning text-dark">Processed</span>
                                                @break
                                            @case('Paid')
                                                <span class="badge bg-success">Paid</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('payroll.payroll.show', $payroll) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($payroll->status === 'Processed')
                                                <a href="{{ route('payroll.payroll.edit', $payroll) }}" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $payrolls->firstItem() }} to {{ $payrolls->lastItem() }} 
                        of {{ $payrolls->total() }} records
                    </div>
                    <div>
                        {{ $payrolls->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-calculator" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="mt-3 text-muted">No payroll records found.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#processPayrollModal">
                        <i class="bi bi-calculator me-2"></i>Process Payroll
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Process Payroll Modal -->
<div class="modal fade" id="processPayrollModal" tabindex="-1" aria-labelledby="processPayrollModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="processPayrollModalLabel">
                    <i class="bi bi-calculator me-2"></i>Process Payroll
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="processPayrollForm">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Note:</strong> This will process payroll for all active employees with salary structures for the selected month.
                    </div>

                    <div class="mb-3">
                        <label for="payroll_month" class="form-label required">Month</label>
                        <select name="month" id="payroll_month" class="form-select" required>
                            <option value="">Select Month</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="payroll_year" class="form-label required">Year</label>
                        <select name="year" id="payroll_year" class="form-select" required>
                            <option value="">Select Year</option>
                            @for($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> If payroll already exists for an employee for this month/year, it will be skipped.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Process Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .required::after {
        content: " *";
        color: red;
    }
    
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
        border-radius: 0.5rem;
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        color: #495057;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const processPayrollForm = document.getElementById('processPayrollForm');
    
    // Set default month to current month
    const currentMonth = new Date().getMonth() + 1;
    document.getElementById('payroll_month').value = currentMonth;
    
    processPayrollForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const month = document.getElementById('payroll_month').value;
        const year = document.getElementById('payroll_year').value;
        
        if (!month || !year) {
            alert('Please select both month and year');
            return;
        }
        
        if (confirm(`Are you sure you want to process payroll for ${getMonthName(month)} ${year}? This action cannot be easily undone.`)) {
            // Redirect to process route
            window.location.href = `{{ route('payroll.payroll.index') }}/process/${month}/${year}`;
        }
    });
    
    function getMonthName(month) {
        const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                       'July', 'August', 'September', 'October', 'November', 'December'];
        return months[month - 1];
    }
});
</script>
@endpush
