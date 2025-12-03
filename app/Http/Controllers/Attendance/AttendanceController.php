<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display attendance check-in/out page
     */
    public function checkIn()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found!');
        }

        // Get today's attendance
        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', Carbon::today())
            ->first();

        // Get recent attendance (last 7 days)
        $recentAttendance = Attendance::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        return view('attendance.check_in', compact('todayAttendance', 'recentAttendance'));
    }

    /**
     * Store attendance (check-in or check-out)
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found!');
        }

        $action = $request->input('action');
        $today = Carbon::today();
        $now = Carbon::now();

        if ($action === 'check_in') {
            // Check if already checked in today
            $existing = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->first();

            if ($existing) {
                return redirect()->back()->with('error', 'You have already checked in today!');
            }

            // Determine attendance status based on check-in time
            // Assuming office starts at 9:00 AM
            $officeStartTime = Carbon::today()->setTime(9, 0, 0);
            $status = $now->lessThanOrEqualTo($officeStartTime) ? 'Present' : 'Late';

            // Create attendance record
            Attendance::create([
                'employee_id' => $employee->id,
                'date' => $today,
                'check_in' => $now->format('H:i:s'),
                'status' => $status,
            ]);

            return redirect()->back()->with('success', 'Checked in successfully at ' . $now->format('h:i A'));

        } elseif ($action === 'check_out') {
            // Find today's attendance
            $attendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->first();

            if (!$attendance) {
                return redirect()->back()->with('error', 'You need to check in first!');
            }

            if ($attendance->check_out) {
                return redirect()->back()->with('error', 'You have already checked out today!');
            }

            // Calculate working hours
            $checkInTime = Carbon::parse($attendance->date . ' ' . $attendance->check_in);
            $workingHours = $checkInTime->diffInHours($now, true);
            $workingHoursFormatted = number_format($workingHours, 2);

            // Update attendance record
            $attendance->update([
                'check_out' => $now->format('H:i:s'),
                'working_hours' => $workingHoursFormatted,
                'remarks' => $request->input('remarks'),
            ]);

            return redirect()->back()->with('success', 'Checked out successfully at ' . $now->format('h:i A') . '. Total working hours: ' . $workingHoursFormatted);
        }

        return redirect()->back()->with('error', 'Invalid action!');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Attendance::with(['employee.department', 'employee.designation']);

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', Carbon::today());
        }

        // Department filter
        if ($request->filled('department_id')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('check_in', 'desc')->paginate(20);

        return view('attendance.index', compact('attendances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store2(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
