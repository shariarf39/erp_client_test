@extends('layouts.app')

@section('page_title', 'Employee Details')
@section('page_description', 'View employee information')

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Profile Card -->
        <div class="card">
            <div class="card-body text-center">
                @if($employee->photo)
                    <img src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->full_name }}" class="rounded-circle mb-3" width="150" height="150">
                @else
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 150px; height: 150px; font-size: 3rem;">
                        {{ strtoupper(substr($employee->first_name, 0, 1)) }}{{ strtoupper(substr($employee->last_name, 0, 1)) }}
                    </div>
                @endif
                <h4>{{ $employee->full_name }}</h4>
                <p class="text-muted">{{ $employee->designation->title ?? 'N/A' }}</p>
                <span class="badge bg-{{ $employee->status === 'Active' ? 'success' : 'danger' }} mb-3">
                    {{ $employee->status }}
                </span>
                <hr>
                <div class="d-grid gap-2">
                    <a href="{{ route('hr.employees.edit', $employee->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit Profile
                    </a>
                    <form method="POST" action="{{ route('hr.employees.destroy', $employee->id) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this employee?')">
                            <i class="fas fa-trash me-1"></i> Delete Employee
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Info Card -->
        <div class="card mt-3">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i> Quick Info
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Employee Code:</strong></td>
                        <td>{{ $employee->employee_code }}</td>
                    </tr>
                    <tr>
                        <td><strong>Department:</strong></td>
                        <td>{{ $employee->department->department_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Branch:</strong></td>
                        <td>{{ $employee->branch->branch_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Type:</strong></td>
                        <td>{{ $employee->employee_type }}</td>
                    </tr>
                    <tr>
                        <td><strong>Joining Date:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($employee->date_of_joining)->format('d M Y') }}</td>
                    </tr>
                    @if($employee->manager)
                        <tr>
                            <td><strong>Reports To:</strong></td>
                            <td>{{ $employee->manager->full_name }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Personal Information Tab -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#personal">Personal Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#contact">Contact Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#documents">Documents</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#salary">Salary Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#leave">Leave Balance</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Personal Info Tab -->
                    <div class="tab-pane fade show active" id="personal">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Full Name</th>
                                <td>{{ $employee->full_name }}</td>
                            </tr>
                            <tr>
                                <th>Father's Name</th>
                                <td>{{ $employee->father_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Mother's Name</th>
                                <td>{{ $employee->mother_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Date of Birth</th>
                                <td>{{ $employee->date_of_birth ? \Carbon\Carbon::parse($employee->date_of_birth)->format('d M Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td>{{ $employee->gender }}</td>
                            </tr>
                            <tr>
                                <th>Marital Status</th>
                                <td>{{ $employee->marital_status ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Contact Info Tab -->
                    <div class="tab-pane fade" id="contact">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Email</th>
                                <td>{{ $employee->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $employee->phone }}</td>
                            </tr>
                            <tr>
                                <th>Emergency Contact</th>
                                <td>{{ $employee->emergency_contact ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Present Address</th>
                                <td>{{ $employee->present_address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Permanent Address</th>
                                <td>{{ $employee->permanent_address ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Documents Tab -->
                    <div class="tab-pane fade" id="documents">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">NID Number</th>
                                <td>{{ $employee->nid_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Passport Number</th>
                                <td>{{ $employee->passport_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>TIN Number</th>
                                <td>{{ $employee->tin_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Bank Name</th>
                                <td>{{ $employee->bank_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Bank Branch</th>
                                <td>{{ $employee->bank_branch ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Account Number</th>
                                <td>{{ $employee->account_no ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Salary Info Tab -->
                    <div class="tab-pane fade" id="salary">
                        @if($employee->salaryStructure)
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Basic Salary</th>
                                    <td>৳{{ number_format($employee->salaryStructure->basic_salary, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>House Rent</th>
                                    <td>৳{{ number_format($employee->salaryStructure->house_rent, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Medical Allowance</th>
                                    <td>৳{{ number_format($employee->salaryStructure->medical_allowance, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Transport Allowance</th>
                                    <td>৳{{ number_format($employee->salaryStructure->transport_allowance, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Other Allowance</th>
                                    <td>৳{{ number_format($employee->salaryStructure->other_allowance, 2) }}</td>
                                </tr>
                                <tr>
                                    <th><strong>Gross Salary</strong></th>
                                    <td><strong>৳{{ number_format($employee->salaryStructure->gross_salary, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Effective From</th>
                                    <td>{{ \Carbon\Carbon::parse($employee->salaryStructure->effective_from)->format('d M Y') }}</td>
                                </tr>
                            </table>
                        @else
                            <p class="text-muted">No salary structure assigned</p>
                        @endif
                    </div>

                    <!-- Leave Balance Tab -->
                    <div class="tab-pane fade" id="leave">
                        @if($employee->leaveBalances->count() > 0)
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Leave Type</th>
                                        <th>Total Days</th>
                                        <th>Used Days</th>
                                        <th>Available Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employee->leaveBalances as $balance)
                                        <tr>
                                            <td>{{ $balance->leaveType->leave_type_name ?? 'N/A' }}</td>
                                            <td>{{ $balance->total_days }}</td>
                                            <td>{{ $balance->used_days }}</td>
                                            <td><strong>{{ $balance->available_days }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">No leave balance assigned</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
