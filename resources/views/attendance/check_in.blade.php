@extends('layouts.app')

@section('page_title', 'Attendance Check-In/Out')
@section('page_description', 'Mark your daily attendance')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-clock me-2"></i> Attendance Check-In/Out
            </div>
            <div class="card-body">
                <!-- Current Time Display -->
                <div class="text-center mb-4">
                    <h2 id="currentTime" class="display-4 text-primary"></h2>
                    <p id="currentDate" class="text-muted"></p>
                </div>

                <!-- Today's Attendance Status -->
                @if(isset($todayAttendance))
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle me-2"></i> Today's Attendance</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Check-In Time:</strong> {{ $todayAttendance->check_in ? \Carbon\Carbon::parse($todayAttendance->date . ' ' . $todayAttendance->check_in)->format('h:i A') : 'N/A' }}</p>
                                <p class="mb-1"><strong>Status:</strong> 
                                    <span class="badge bg-{{ $todayAttendance->status === 'Present' ? 'success' : ($todayAttendance->status === 'Late' ? 'warning' : 'danger') }}">
                                        {{ $todayAttendance->status }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                @if($todayAttendance->check_out)
                                    <p class="mb-1"><strong>Check-Out Time:</strong> {{ \Carbon\Carbon::parse($todayAttendance->date . ' ' . $todayAttendance->check_out)->format('h:i A') }}</p>
                                    <p class="mb-1"><strong>Working Hours:</strong> {{ $todayAttendance->working_hours }} hours</p>
                                @else
                                    <p class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i> Not checked out yet</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> You haven't checked in today yet.
                    </div>
                @endif

                <!-- Check-In/Out Form -->
                <form method="POST" action="{{ route('attendance.store') }}" class="mt-4">
                    @csrf
                    <div class="row justify-content-center">
                        @if(!isset($todayAttendance))
                            <!-- Check-In Button -->
                            <div class="col-md-4 text-center">
                                <button type="submit" name="action" value="check_in" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-sign-in-alt fa-2x d-block mb-2"></i>
                                    <h4>Check In</h4>
                                </button>
                            </div>
                        @elseif(!$todayAttendance->check_out)
                            <!-- Check-Out Button -->
                            <div class="col-md-4 text-center">
                                <button type="submit" name="action" value="check_out" class="btn btn-danger btn-lg w-100">
                                    <i class="fas fa-sign-out-alt fa-2x d-block mb-2"></i>
                                    <h4>Check Out</h4>
                                </button>
                            </div>
                        @else
                            <div class="col-md-8 text-center">
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle fa-3x d-block mb-3"></i>
                                    <h4>You have completed your attendance for today!</h4>
                                    <p>Check-In: {{ $todayAttendance->check_in ? \Carbon\Carbon::parse($todayAttendance->date . ' ' . $todayAttendance->check_in)->format('h:i A') : 'N/A' }} | 
                                       Check-Out: {{ $todayAttendance->check_out ? \Carbon\Carbon::parse($todayAttendance->date . ' ' . $todayAttendance->check_out)->format('h:i A') : 'N/A' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Remarks (optional) -->
                    @if(isset($todayAttendance) && !$todayAttendance->check_out_time)
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="remarks" class="form-label">Remarks (Optional)</label>
                                <textarea class="form-control" id="remarks" name="remarks" rows="2" placeholder="Add any notes about your day..."></textarea>
                            </div>
                        </div>
                    @endif
                </form>

                <!-- Recent Attendance History -->
                <h5 class="border-top pt-3 mt-4">Recent Attendance History</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Check-In</th>
                                <th>Check-Out</th>
                                <th>Working Hours</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAttendance as $att)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($att->date)->format('d M Y') }}</td>
                                    <td>{{ $att->check_in ? \Carbon\Carbon::parse($att->date . ' ' . $att->check_in)->format('h:i A') : '-' }}</td>
                                    <td>{{ $att->check_out ? \Carbon\Carbon::parse($att->date . ' ' . $att->check_out)->format('h:i A') : '-' }}</td>
                                    <td>{{ $att->working_hours ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $att->status === 'Present' ? 'success' : ($att->status === 'Late' ? 'warning' : 'danger') }}">
                                            {{ $att->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No attendance records found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateDateTime() {
        const now = new Date();
        const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        
        document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', timeOptions);
        document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', dateOptions);
    }

    // Update time every second
    updateDateTime();
    setInterval(updateDateTime, 1000);
</script>
@endsection
