@extends('layouts.app')

@section('title', 'Leave Application Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Leave Application Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">HR</li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.leaves.index') }}">Leave Applications</a></li>
                    <li class="breadcrumb-item active">#{{ $leaveApplication->id }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('hr.leaves.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            @if($leaveApplication->status === 'Pending')
                <a href="{{ route('hr.leaves.edit', $leaveApplication->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('hr.leaves.approve', $leaveApplication) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Approve this leave application?')">
                        <i class="fas fa-check"></i> Approve
                    </button>
                </form>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="fas fa-times"></i> Reject
                </button>
            @endif
            @if(in_array($leaveApplication->status, ['Pending', 'Approved']))
                <form action="{{ route('hr.leaves.cancel', $leaveApplication) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-secondary" onclick="return confirm('Cancel this leave application?')">
                        <i class="fas fa-ban"></i> Cancel
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8 mb-4">
            <!-- Leave Details Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Leave Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Application ID</label>
                            <p class="fw-bold fs-5">#{{ $leaveApplication->id }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Status</label>
                            <div>
                                @switch($leaveApplication->status)
                                    @case('Pending')
                                        <span class="badge bg-warning text-dark fs-6">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                        @break
                                    @case('Approved')
                                        <span class="badge bg-success fs-6">
                                            <i class="fas fa-check-circle"></i> Approved
                                        </span>
                                        @break
                                    @case('Rejected')
                                        <span class="badge bg-danger fs-6">
                                            <i class="fas fa-times-circle"></i> Rejected
                                        </span>
                                        @break
                                    @case('Cancelled')
                                        <span class="badge bg-secondary fs-6">
                                            <i class="fas fa-ban"></i> Cancelled
                                        </span>
                                        @break
                                @endswitch
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Leave Type</label>
                            <p class="text-dark fw-bold">{{ $leaveApplication->leaveType->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Duration</label>
                            <p class="text-dark fw-bold">{{ $leaveApplication->days }} days</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">From Date</label>
                            <p class="text-dark">{{ $leaveApplication->from_date->format('d M Y (l)') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">To Date</label>
                            <p class="text-dark">{{ $leaveApplication->to_date->format('d M Y (l)') }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Reason</label>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $leaveApplication->reason }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Applied On</label>
                            <p class="text-dark">{{ $leaveApplication->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Details Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Employee Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Employee Name</label>
                            <p class="fw-bold">{{ $leaveApplication->employee->full_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Employee Code</label>
                            <p class="text-dark">{{ $leaveApplication->employee->employee_code ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Department</label>
                            <p class="text-dark">{{ $leaveApplication->employee->department->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Designation</label>
                            <p class="text-dark">{{ $leaveApplication->employee->designation->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Email</label>
                            <p class="text-dark">{{ $leaveApplication->employee->email ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Phone</label>
                            <p class="text-dark">{{ $leaveApplication->employee->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approval Details Card -->
            @if($leaveApplication->status !== 'Pending')
                <div class="card border-0 shadow-sm">
                    <div class="card-header {{ $leaveApplication->status === 'Approved' ? 'bg-success' : 'bg-danger' }} text-white py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-{{ $leaveApplication->status === 'Approved' ? 'check-circle' : 'times-circle' }}"></i> 
                            {{ $leaveApplication->status }} Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">{{ $leaveApplication->status }} By</label>
                                <p class="text-dark">{{ $leaveApplication->approver->name ?? 'System' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">{{ $leaveApplication->status }} On</label>
                                <p class="text-dark">{{ $leaveApplication->approved_at ? $leaveApplication->approved_at->format('d M Y, h:i A') : 'N/A' }}</p>
                            </div>
                            @if($leaveApplication->status === 'Rejected' && $leaveApplication->rejection_reason)
                                <div class="col-md-12">
                                    <label class="text-muted small">Rejection Reason</label>
                                    <div class="alert alert-danger mb-0">
                                        {{ $leaveApplication->rejection_reason }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Timeline Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <small class="text-muted">Applied</small>
                                <p class="mb-0 small">{{ $leaveApplication->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                        @if($leaveApplication->approved_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-{{ $leaveApplication->status === 'Approved' ? 'success' : 'danger' }}"></div>
                                <div class="timeline-content">
                                    <small class="text-muted">{{ $leaveApplication->status }}</small>
                                    <p class="mb-0 small">{{ $leaveApplication->approved_at->format('d M Y, h:i A') }}</p>
                                    <p class="mb-0 small text-muted">By: {{ $leaveApplication->approver->name ?? 'System' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($leaveApplication->status === 'Pending')
                            <a href="{{ route('hr.leaves.edit', $leaveApplication->id) }}" class="btn btn-outline-warning">
                                <i class="fas fa-edit"></i> Edit Application
                            </a>
                            <form action="{{ route('hr.leaves.approve', $leaveApplication) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-success w-100" onclick="return confirm('Approve this leave application?')">
                                    <i class="fas fa-check"></i> Approve Leave
                                </button>
                            </form>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="fas fa-times"></i> Reject Leave
                            </button>
                        @endif
                        @if(in_array($leaveApplication->status, ['Pending', 'Approved']))
                            <form action="{{ route('hr.leaves.cancel', $leaveApplication) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary w-100" onclick="return confirm('Cancel this leave application?')">
                                    <i class="fas fa-ban"></i> Cancel Leave
                                </button>
                            </form>
                        @endif
                        @if(in_array($leaveApplication->status, ['Pending', 'Cancelled']))
                            <form action="{{ route('hr.leaves.destroy', $leaveApplication) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-dark w-100" onclick="return confirm('Delete this leave application?')">
                                    <i class="fas fa-trash"></i> Delete Application
                                </button>
                            </form>
                        @endif
                        <button class="btn btn-outline-info" onclick="window.print()">
                            <i class="fas fa-print"></i> Print Details
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('hr.leaves.reject', $leaveApplication) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Reject Leave Application</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="Please provide a reason for rejection..."></textarea>
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

<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline-item {
        position: relative;
    }

    .timeline-marker {
        position: absolute;
        left: -30px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #dee2e6;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: -24px;
        top: 6px;
        bottom: 6px;
        width: 2px;
        background: #dee2e6;
    }
</style>
@endsection
