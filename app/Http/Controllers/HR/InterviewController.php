<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\Applicant;
use App\Models\User;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Interview::with(['applicant.jobPosting', 'interviewer']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('applicant', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('application_code', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by interview type
        if ($request->filled('interview_type')) {
            $query->where('interview_type', $request->interview_type);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('scheduled_date', $request->date);
        }

        $interviews = $query->orderBy('scheduled_date', 'desc')->paginate(15);

        return view('hr.recruitment.interviews.index', compact('interviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $applicants = Applicant::with('jobPosting')
                                ->whereNotIn('status', ['Rejected', 'Hired', 'Withdrawn'])
                                ->orderBy('first_name')
                                ->get();
        $interviewers = User::where('is_active', 1)
                            ->orderBy('name')
                            ->get();

        return view('hr.recruitment.interviews.create', compact('applicants', 'interviewers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'applicant_id' => 'required|exists:applicants,id',
            'interview_type' => 'required|in:Phone,Video,In-Person,Technical,HR,Final',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
            'duration' => 'required|integer|min:15',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url|max:255',
            'interviewer_id' => 'required|exists:users,id',
            'status' => 'nullable|in:Scheduled,Completed,Cancelled,No Show,Rescheduled',
            'notes' => 'nullable|string',
        ]);

        // Set default status if not provided
        if (!isset($validated['status'])) {
            $validated['status'] = 'Scheduled';
        }

        $interview = Interview::create($validated);

        // Update applicant status to Interview if not already
        $applicant = Applicant::find($validated['applicant_id']);
        if ($applicant->status == 'Screening' || $applicant->status == 'New') {
            $applicant->update(['status' => 'Interview']);
        }

        return redirect()->route('hr.recruitment.interviews.index')
                        ->with('success', 'Interview scheduled successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Interview $interview)
    {
        $interview->load(['applicant.jobPosting', 'interviewer']);
        
        return view('hr.recruitment.interviews.show', compact('interview'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Interview $interview)
    {
        $applicants = Applicant::with('jobPosting')
                                ->whereNotIn('status', ['Rejected', 'Hired', 'Withdrawn'])
                                ->orderBy('first_name')
                                ->get();
        $interviewers = User::where('is_active', true)
                            ->orderBy('name')
                            ->get();

        return view('hr.recruitment.interviews.edit', compact('interview', 'applicants', 'interviewers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Interview $interview)
    {
        $validated = $request->validate([
            'applicant_id' => 'required|exists:applicants,id',
            'interview_type' => 'required|in:Phone,Video,In-Person,Technical,HR,Final',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
            'duration' => 'required|integer|min:15',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url|max:255',
            'interviewer_id' => 'required|exists:users,id',
            'status' => 'required|in:Scheduled,Completed,Cancelled,No Show,Rescheduled',
            'feedback' => 'nullable|string',
            'rating' => 'nullable|numeric|min:1|max:5',
            'result' => 'nullable|in:Passed,Failed,On Hold',
            'notes' => 'nullable|string',
        ]);

        $interview->update($validated);

        return redirect()->route('hr.recruitment.interviews.index')
                        ->with('success', 'Interview updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Interview $interview)
    {
        // Only allow deletion of scheduled or cancelled interviews
        if (!in_array($interview->status, ['Scheduled', 'Cancelled'])) {
            return redirect()->route('hr.recruitment.interviews.index')
                            ->with('error', 'Cannot delete completed interviews.');
        }

        $interview->delete();

        return redirect()->route('hr.recruitment.interviews.index')
                        ->with('success', 'Interview deleted successfully.');
    }
}
