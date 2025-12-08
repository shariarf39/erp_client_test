@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Shift Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">HR</a></li>
                    <li class="breadcrumb-item active">Shifts</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('hr.shifts.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Create Shift
        </a>
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
                            <th>Shift Name</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Break Duration</th>
                            <th>Grace Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shifts as $shift)
                            <tr>
                                <td><strong>{{ $shift->name }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }}</td>
                                <td>{{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}</td>
                                <td>{{ $shift->break_duration ?? 0 }} mins</td>
                                <td>{{ $shift->grace_time ?? 0 }} mins</td>
                                <td>
                                    @if($shift->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('hr.shifts.edit', $shift) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('hr.shifts.destroy', $shift) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="alert alert-info mb-0">
                                        No shifts found. <a href="{{ route('hr.shifts.create') }}">Create your first shift</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($shifts->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $shifts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
