@extends('layouts.app')

@section('page_title', isset($employee) ? 'Edit Employee' : 'Add New Employee')
@section('page_description', isset($employee) ? 'Update employee information' : 'Create a new employee record')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="fas fa-user-edit me-2"></i> {{ isset($employee) ? 'Edit Employee' : 'Add New Employee' }}
    </div>
    <div class="card-body">
        <form method="POST" action="{{ isset($employee) ? route('hr.employees.update', $employee->id) : route('hr.employees.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($employee))
                @method('PUT')
            @endif

            <!-- Personal Information -->
            <h5 class="border-bottom pb-2 mb-3">Personal Information</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="employee_code" class="form-label">Employee Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('employee_code') is-invalid @enderror" 
                           id="employee_code" name="employee_code" 
                           value="{{ old('employee_code', $employee->employee_code ?? '') }}" required>
                    @error('employee_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                           id="first_name" name="first_name" 
                           value="{{ old('first_name', $employee->first_name ?? '') }}" required>
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                           id="last_name" name="last_name" 
                           value="{{ old('last_name', $employee->last_name ?? '') }}" required>
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="father_name" class="form-label">Father's Name</label>
                    <input type="text" class="form-control" id="father_name" name="father_name" 
                           value="{{ old('father_name', $employee->father_name ?? '') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="mother_name" class="form-label">Mother's Name</label>
                    <input type="text" class="form-control" id="mother_name" name="mother_name" 
                           value="{{ old('mother_name', $employee->mother_name ?? '') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                           value="{{ old('date_of_birth', $employee->date_of_birth ?? '') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                    <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male" {{ old('gender', $employee->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $employee->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender', $employee->gender ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Contact Information -->
            <h5 class="border-bottom pb-2 mb-3 mt-4">Contact Information</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" 
                           value="{{ old('email', $employee->email ?? '') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                           id="phone" name="phone" 
                           value="{{ old('phone', $employee->phone ?? '') }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="emergency_contact" class="form-label">Emergency Contact</label>
                    <input type="text" class="form-control" id="emergency_contact" name="emergency_contact" 
                           value="{{ old('emergency_contact', $employee->emergency_contact ?? '') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="present_address" class="form-label">Present Address</label>
                    <textarea class="form-control" id="present_address" name="present_address" rows="2">{{ old('present_address', $employee->present_address ?? '') }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="permanent_address" class="form-label">Permanent Address</label>
                    <textarea class="form-control" id="permanent_address" name="permanent_address" rows="2">{{ old('permanent_address', $employee->permanent_address ?? '') }}</textarea>
                </div>
            </div>

            <!-- Employment Information -->
            <h5 class="border-bottom pb-2 mb-3 mt-4">Employment Information</h5>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                    <select class="form-control @error('department_id') is-invalid @enderror" id="department_id" name="department_id" required>
                        <option value="">Select Department</option>
                        @foreach(\App\Models\Department::where('is_active', 1)->get() as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id ?? '') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->code }} - {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="designation_id" class="form-label">Designation <span class="text-danger">*</span></label>
                    <select class="form-control @error('designation_id') is-invalid @enderror" id="designation_id" name="designation_id" required>
                        <option value="">Select Designation</option>
                        @foreach(\App\Models\Designation::where('is_active', 1)->get() as $desig)
                            <option value="{{ $desig->id }}" {{ old('designation_id', $employee->designation_id ?? '') == $desig->id ? 'selected' : '' }}>
                                {{ $desig->code }} - {{ $desig->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('designation_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select class="form-control" id="branch_id" name="branch_id">
                        <option value="">Select Branch</option>
                        @foreach(\App\Models\Branch::where('is_active', 1)->get() as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $employee->branch_id ?? '') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->code }} - {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="employee_type" class="form-label">Employee Type</label>
                    <select class="form-control" id="employee_type" name="employee_type">
                        <option value="Permanent" {{ old('employee_type', $employee->employee_type ?? '') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                        <option value="Contract" {{ old('employee_type', $employee->employee_type ?? '') == 'Contract' ? 'selected' : '' }}>Contract</option>
                        <option value="Temporary" {{ old('employee_type', $employee->employee_type ?? '') == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                        <option value="Intern" {{ old('employee_type', $employee->employee_type ?? '') == 'Intern' ? 'selected' : '' }}>Intern</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="date_of_joining" class="form-label">Date of Joining <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('date_of_joining') is-invalid @enderror" 
                           id="date_of_joining" name="date_of_joining" 
                           value="{{ old('date_of_joining', $employee->date_of_joining ?? '') }}" required>
                    @error('date_of_joining')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="manager_id" class="form-label">Reporting Manager</label>
                    <select class="form-control" id="manager_id" name="manager_id">
                        <option value="">Select Manager</option>
                        @foreach(\App\Models\Employee::where('id', '!=', $employee->id ?? 0)->get() as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id', $employee->manager_id ?? '') == $manager->id ? 'selected' : '' }}>
                                {{ $manager->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="Active" {{ old('status', $employee->status ?? 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status', $employee->status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="On Leave" {{ old('status', $employee->status ?? '') == 'On Leave' ? 'selected' : '' }}>On Leave</option>
                        <option value="Terminated" {{ old('status', $employee->status ?? '') == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="photo" class="form-label">Photo</label>
                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                    @if(isset($employee) && $employee->photo)
                        <img src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->full_name }}" class="mt-2" width="100">
                    @endif
                </div>
            </div>

            <!-- Documents -->
            <h5 class="border-bottom pb-2 mb-3 mt-4">Identity Documents</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="nid_no" class="form-label">NID Number</label>
                    <input type="text" class="form-control" id="nid_no" name="nid_no" 
                           value="{{ old('nid_no', $employee->nid_no ?? '') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="passport_no" class="form-label">Passport Number</label>
                    <input type="text" class="form-control" id="passport_no" name="passport_no" 
                           value="{{ old('passport_no', $employee->passport_no ?? '') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="tin_no" class="form-label">TIN Number</label>
                    <input type="text" class="form-control" id="tin_no" name="tin_no" 
                           value="{{ old('tin_no', $employee->tin_no ?? '') }}">
                </div>
            </div>

            <!-- Bank Information -->
            <h5 class="border-bottom pb-2 mb-3 mt-4">Bank Information</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="bank_name" class="form-label">Bank Name</label>
                    <input type="text" class="form-control" id="bank_name" name="bank_name" 
                           value="{{ old('bank_name', $employee->bank_name ?? '') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="bank_branch" class="form-label">Bank Branch</label>
                    <input type="text" class="form-control" id="bank_branch" name="bank_branch" 
                           value="{{ old('bank_branch', $employee->bank_branch ?? '') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="account_no" class="form-label">Account Number</label>
                    <input type="text" class="form-control" id="account_no" name="account_no" 
                           value="{{ old('account_no', $employee->account_no ?? '') }}">
                </div>
            </div>

            <!-- Form Actions -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> {{ isset($employee) ? 'Update Employee' : 'Create Employee' }}
                    </button>
                    <a href="{{ route('hr.employees.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
