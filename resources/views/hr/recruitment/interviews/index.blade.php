@extends('layouts.app')

@section('title', 'Interviews - SENA.ERP')
@section('page_title', 'Interviews')
@section('page_description', 'Manage candidate interviews and schedules')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Interviews</h5>
                    <a href="{{ route('hr.recruitment.interviews.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Schedule Interview
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('hr.recruitment.interviews.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Search applicant..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="Scheduled" {{ request('status') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="Rescheduled" {{ request('status') == 'Rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                                    <option value="No Show" {{ request('status') == 'No Show' ? 'selected' : '' }}>No Show</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="interview_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="Phone Screening" {{ request('interview_type') == 'Phone Screening' ? 'selected' : '' }}>Phone Screening</option>
                                    <option value="HR Interview" {{ request('interview_type') == 'HR Interview' ? 'selected' : '' }}>HR Interview</option>
                                    <option value="Technical Interview" {{ request('interview_type') == 'Technical Interview' ? 'selected' : '' }}>Technical Interview</option>
                                    <option value="Manager Interview" {{ request('interview_type') == 'Manager Interview' ? 'selected' : '' }}>Manager Interview</option>
                                    <option value="Panel Interview" {{ request('interview_type') == 'Panel Interview' ? 'selected' : '' }}>Panel Interview</option>
                                    <option value="Final Interview" {{ request('interview_type') == 'Final Interview' ? 'selected' : '' }}>Final Interview</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('hr.recruitment.interviews.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Interviews Table -->
                    @if($interviews->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Applicant</th>
                                        <th>Job Position</th>
                                        <th>Interview Type</th>
                                        <th>Date & Time</th>
                                        <th>Duration</th>
                                        <th>Interviewer</th>
                                        <th>Location/Link</th>
                                        <th>Status</th>
                                        <th>Rating</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($interviews as $interview)
                                    <tr>
                                        <td>
                                            <strong>{{ $interview->applicant->first_name }} {{ $interview->applicant->last_name }}</strong>
                                            <br><small class="text-muted">{{ $interview->applicant->application_code }}</small>
                                        </td>
                                        <td>
                                            @if($interview->applicant->jobPosting)
                                                <span class="badge bg-info">{{ $interview->applicant->jobPosting->title }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $interview->interview_type }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <i class="fas fa-calendar text-primary"></i> {{ \Carbon\Carbon::parse($interview->scheduled_date)->format('M d, Y') }}<br>
                                                <i class="fas fa-clock text-primary"></i> {{ \Carbon\Carbon::parse($interview->scheduled_time)->format('h:i A') }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $interview->duration }} mins</span>
                                        </td>
                                        <td>
                                            @if($interview->interviewer)
                                                <div>
                                                    <strong>{{ $interview->interviewer->name }}</strong>
                                                    @if($interview->interviewer->role)
                                                        <br><small class="text-muted">{{ $interview->interviewer->role->name ?? '' }}</small>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">Not assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($interview->meeting_link)
                                                <a href="{{ $interview->meeting_link }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-video"></i> Join
                                                </a>
                                            @elseif($interview->location)
                                                <small><i class="fas fa-map-marker-alt"></i> {{ $interview->location }}</small>
                                            @else
                                                <span class="text-muted">TBD</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'Scheduled' => 'primary',
                                                    'Completed' => 'success',
                                                    'Cancelled' => 'danger',
                                                    'Rescheduled' => 'warning',
                                                    'No Show' => 'dark'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$interview->status] ?? 'secondary' }}">
                                                {{ $interview->status }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($interview->rating)
                                                <div>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $interview->rating)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-muted"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('hr.recruitment.interviews.show', $interview->id) }}" class="btn btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('hr.recruitment.interviews.edit', $interview->id) }}" class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('hr.recruitment.interviews.destroy', $interview->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this interview?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $interviews->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No interviews found. <a href="{{ route('hr.recruitment.interviews.create') }}" class="alert-link">Schedule your first interview</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="text-muted">Total Interviews</h6>
                    <h3 class="mb-0">{{ \App\Models\Interview::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <h6 class="text-muted">Scheduled</h6>
                    <h3 class="mb-0 text-primary">{{ \App\Models\Interview::where('status', 'Scheduled')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <h6 class="text-muted">Completed</h6>
                    <h3 class="mb-0 text-success">{{ \App\Models\Interview::where('status', 'Completed')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <h6 class="text-muted">Today's Interviews</h6>
                    <h3 class="mb-0 text-warning">{{ \App\Models\Interview::whereDate('scheduled_date', today())->where('status', 'Scheduled')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
