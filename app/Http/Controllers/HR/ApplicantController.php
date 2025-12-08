<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $query = Applicant::with(['jobPosting']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('application_code', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('job_posting_id')) {
            $query->where('job_posting_id', $request->job_posting_id);
        }
        
        $applicants = $query->orderBy('created_at', 'desc')->paginate(15);
        $jobPostings = JobPosting::where('status', 'Active')->get();
        
        return view('hr.recruitment.applicants.index', compact('applicants', 'jobPostings'));
    }

    public function create()
    {
        $jobPostings = JobPosting::where('status', 'Active')->orderBy('title')->get();
        
        // Generate application code
        $lastApp = Applicant::latest('id')->first();
        $appCode = 'APP-' . date('Y') . '-' . str_pad(($lastApp ? $lastApp->id + 1 : 1), 5, '0', STR_PAD_LEFT);
        
        return view('hr.recruitment.applicants.create', compact('jobPostings', 'appCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_code' => 'required|unique:applicants|max:50',
            'job_posting_id' => 'required|exists:job_postings,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:applicants|max:150',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'address' => 'nullable',
            'city' => 'nullable|max:100',
            'country' => 'nullable|max:100',
            'education' => 'nullable',
            'experience_years' => 'nullable|numeric|min:0',
            'current_company' => 'nullable|max:200',
            'current_position' => 'nullable|max:100',
            'current_salary' => 'nullable|numeric|min:0',
            'expected_salary' => 'nullable|numeric|min:0',
            'skills' => 'nullable',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter' => 'nullable',
            'linkedin_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
        ]);
        
        if ($request->hasFile('resume')) {
            $validated['resume_path'] = $request->file('resume')->store('applicants/resumes', 'public');
        }
        
        $validated['status'] = 'New';
        $validated['stage'] = 'Application Received';
        $validated['applied_at'] = now();
        
        $applicant = Applicant::create($validated);
        
        return redirect()->route('hr.recruitment.applicants.show', $applicant)
            ->with('success', 'Application submitted successfully.');
    }

    public function show(Applicant $applicant)
    {
        $applicant->load(['jobPosting', 'interviews.interviewer']);
        
        return view('hr.recruitment.applicants.show', compact('applicant'));
    }

    public function edit(Applicant $applicant)
    {
        $jobPostings = JobPosting::where('status', 'Active')->orderBy('title')->get();
        
        return view('hr.recruitment.applicants.edit', compact('applicant', 'jobPostings'));
    }

    public function update(Request $request, Applicant $applicant)
    {
        $validated = $request->validate([
            'application_code' => 'required|unique:applicants,application_code,' . $applicant->id . '|max:50',
            'job_posting_id' => 'required|exists:job_postings,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:applicants,email,' . $applicant->id . '|max:150',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'address' => 'nullable',
            'city' => 'nullable|max:100',
            'country' => 'nullable|max:100',
            'education' => 'nullable',
            'experience_years' => 'nullable|numeric|min:0',
            'current_company' => 'nullable|max:200',
            'current_position' => 'nullable|max:100',
            'current_salary' => 'nullable|numeric|min:0',
            'expected_salary' => 'nullable|numeric|min:0',
            'skills' => 'nullable',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter' => 'nullable',
            'linkedin_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'status' => 'required|in:New,Screening,Interview,Assessment,Offer,Hired,Rejected,Withdrawn',
            'rating' => 'nullable|numeric|min:1|max:5',
            'notes' => 'nullable',
        ]);
        
        if ($request->hasFile('resume')) {
            if ($applicant->resume_path) {
                Storage::disk('public')->delete($applicant->resume_path);
            }
            $validated['resume_path'] = $request->file('resume')->store('applicants/resumes', 'public');
        }
        
        $applicant->update($validated);
        
        return redirect()->route('hr.recruitment.applicants.show', $applicant)
            ->with('success', 'Applicant updated successfully.');
    }

    public function destroy(Applicant $applicant)
    {
        if ($applicant->resume_path) {
            Storage::disk('public')->delete($applicant->resume_path);
        }
        
        $applicant->delete();
        
        return redirect()->route('hr.recruitment.applicants.index')
            ->with('success', 'Applicant deleted successfully.');
    }
}
