@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Leave Applications</h2>
                <a href="{{ route('hr.leaves.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Apply Leave
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <h2>{{ \App\Models\LeaveApplication::where('status', 'Pending')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Approved</h5>
                    <h2>{{ \App\Models\LeaveApplication::where('status', 'Approved')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Rejected</h5>
                    <h2>{{ \App\Models\LeaveApplication::where('status', 'Rejected')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-secondary">
                <div class="card-body">
                    <h5 class="card-title">Cancelled</h5>
                    <h2>{{ \App\Models\LeaveApplication::where('status', 'Cancelled')->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('hr.leaves.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Search by employee name or code" 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" 
                               name="from_date" 
                               class="form-control" 
                               placeholder="From Date"
                               value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-funnel me-2"></i>Filter
                        </button>
                        <a href="{{ route('hr.leaves.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Leave Applications Table -->
    <div class="card">
        <div class="card-body">
            @if($leaveApplications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Leave Type</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Applied On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaveApplications as $leave)
                                <tr>
                                    <td>{{ $leave->id }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $leave->employee->full_name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $leave->employee->employee_code ?? '' }}</small>
                                    </td>
                                    <td>{{ $leave->employee->department->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $leave->leaveType->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ $leave->from_date->format('M d, Y') }}</td>
                                    <td>{{ $leave->to_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $leave->days }} days</span>
                                    </td>
                                    <td>
                                        @switch($leave->status)
                                            @case('Pending')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-clock me-1"></i>Pending
                                                </span>
                                                @break
                                            @case('Approved')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Approved
                                                </span>
                                                @break
                                            @case('Rejected')
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle me-1"></i>Rejected
                                                </span>
                                                @break
                                            @case('Cancelled')
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-slash-circle me-1"></i>Cancelled
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <small>{{ $leave->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('hr.leaves.show', $leave) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($leave->status === 'Pending')
                                                <a href="{{ route('hr.leaves.edit', $leave) }}" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('hr.leaves.approve', $leave) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Approve" onclick="return confirm('Approve this leave application?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-danger" title="Reject" 
                                                        data-bs-toggle="modal" data-bs-target="#rejectModal{{ $leave->id }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                            @if(in_array($leave->status, ['Pending', 'Approved']))
                                                <form action="{{ route('hr.leaves.cancel', $leave) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-secondary" title="Cancel" onclick="return confirm('Cancel this leave application?')">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if(in_array($leave->status, ['Pending', 'Cancelled']))
                                                <form action="{{ route('hr.leaves.destroy', $leave) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-dark" title="Delete" onclick="return confirm('Delete this leave application?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('hr.leaves.reject', $leave) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reject Leave Application</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                                <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-danger">Reject Application</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
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
                        Showing {{ $leaveApplications->firstItem() }} to {{ $leaveApplications->lastItem() }} 
                        of {{ $leaveApplications->total() }} applications
                    </div>
                    <div>
                        {{ $leaveApplications->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="mt-3 text-muted">No leave applications found.</p>
                    <a href="{{ route('hr.leaves.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Apply for Leave
                    </a>
                </div>
            @endif
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
