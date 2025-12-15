@extends('layouts.app')

@section('title', 'User Role Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>User Role Management</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Employee</th>
                            <th>Current Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->name }}</strong>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->employee)
                                    {{ $user->employee->first_name }} {{ $user->employee->last_name }}
                                @else
                                    <span class="text-muted">No Employee</span>
                                @endif
                            </td>
                            <td>
                                @if($user->role)
                                    <span class="badge bg-primary">{{ $user->role->name }}</span>
                                    <br><small class="text-muted">{{ $user->role->description }}</small>
                                @else
                                    <span class="badge bg-secondary">No Role</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#changeRoleModal{{ $user->id }}">
                                    <i class="fas fa-edit"></i> Change Role
                                </button>

                                <!-- Change Role Modal -->
                                <div class="modal fade" id="changeRoleModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.users.update-role', $user) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Change Role for {{ $user->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Select Role</label>
                                                        <select name="role_id" class="form-select" required>
                                                            <option value="">Choose Role...</option>
                                                            @foreach($roles as $role)
                                                                <option value="{{ $role->id }}" 
                                                                        {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                                    {{ $role->name }} - {{ $role->description }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="alert alert-info">
                                                        <strong>Role Descriptions:</strong>
                                                        <ul class="mb-0 mt-2">
                                                            <li><strong>Super Admin:</strong> Full system access</li>
                                                            <li><strong>HR Manager:</strong> HR, Payroll & Attendance modules</li>
                                                            <li><strong>Accountant:</strong> Accounts module only</li>
                                                            <li><strong>Sales Manager:</strong> Sales module only</li>
                                                            <li><strong>Operations Manager:</strong> Accounts, Sales, Inventory & Purchase</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save"></i> Update Role
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No users found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
