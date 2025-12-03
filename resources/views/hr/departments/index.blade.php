@extends('layouts.app')

@section('page_title', 'Departments')
@section('page_description', 'Manage departments')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-building me-2"></i> Department List
        </div>
        <a href="{{ route('hr.departments.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add New Department
        </a>
    </div>
    <div class="card-body">
        @if($departments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Department Name</th>
                            <th>Code</th>
                            <th>Manager</th>
                            <th>Employees</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departments as $department)
                            <tr>
                                <td>{{ $department->id }}</td>
                                <td>{{ $department->name }}</td>
                                <td>{{ $department->code }}</td>
                                <td>{{ $department->manager->employee_name ?? 'N/A' }}</td>
                                <td>{{ $department->employees->count() }}</td>
                                <td>
                                    @if($department->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
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
                {{ $departments->links() }}
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i> No departments found. Click "Add New Department" to create one.
            </div>
        @endif
    </div>
</div>
@endsection
