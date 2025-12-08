<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;

class JobPostingController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPosting::with(['department', 'designation', 'postedBy']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('job_code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        $jobPostings = $query->orderBy('created_at', 'desc')->paginate(15);
        $departments = Department::where('is_active', 1)->get();
        
        return view('hr.recruitment.jobs.index', compact('jobPostings', 'departments'));
    }

    public function create()
    {
        $departments = Department::where('is_active', 1)->orderBy('name')->get();
        $designations = Designation::where('is_active', 1)->orderBy('title')->get();
        
        // Generate job code
        $lastJob = JobPosting::latest('id')->first();
        $jobCode = 'JOB-' . date('Y') . '-' . str_pad(($lastJob ? $lastJob->id + 1 : 1), 4, '0', STR_PAD_LEFT);
        
        return view('hr.recruitment.jobs.create', compact('departments', 'designations', 'jobCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'job_code' => 'required|unique:job_postings|max:50',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'employment_type' => 'required|in:Full-Time,Part-Time,Contract,Internship',
            'experience_required' => 'nullable|max:50',
            'qualification' => 'nullable',
            'skills_required' => 'nullable',
            'job_description' => 'nullable',
            'responsibilities' => 'nullable',
            'salary_range_min' => 'nullable|numeric|min:0',
            'salary_range_max' => 'nullable|numeric|min:0',
            'vacancies' => 'required|integer|min:1',
            'location' => 'nullable|max:200',
            'application_deadline' => 'nullable|date',
            'status' => 'required|in:Draft,Active,Closed,On Hold',
        ]);
        
        $validated['posted_by'] = auth()->id();
        if ($validated['status'] === 'Active') {
            $validated['posted_at'] = now();
        }
        
        $jobPosting = JobPosting::create($validated);
        
        return redirect()->route('hr.recruitment.jobs.show', $jobPosting)
            ->with('success', 'Job posting created successfully.');
    }

    public function show(JobPosting $job)
    {
        $job->load(['department', 'designation', 'postedBy', 'applicants']);
        
        return view('hr.recruitment.jobs.show', compact('job'));
    }

    public function edit(JobPosting $job)
    {
        $departments = Department::where('is_active', 1)->orderBy('name')->get();
        $designations = Designation::where('is_active', 1)->orderBy('title')->get();
        
        return view('hr.recruitment.jobs.edit', compact('job', 'departments', 'designations'));
    }

    public function update(Request $request, JobPosting $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'job_code' => 'required|unique:job_postings,job_code,' . $job->id . '|max:50',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'employment_type' => 'required|in:Full-Time,Part-Time,Contract,Internship',
            'experience_required' => 'nullable|max:50',
            'qualification' => 'nullable',
            'skills_required' => 'nullable',
            'job_description' => 'nullable',
            'responsibilities' => 'nullable',
            'salary_range_min' => 'nullable|numeric|min:0',
            'salary_range_max' => 'nullable|numeric|min:0',
            'vacancies' => 'required|integer|min:1',
            'location' => 'nullable|max:200',
            'application_deadline' => 'nullable|date',
            'status' => 'required|in:Draft,Active,Closed,On Hold',
        ]);
        
        if ($job->status !== 'Active' && $validated['status'] === 'Active') {
            $validated['posted_at'] = now();
        }
        
        $job->update($validated);
        
        return redirect()->route('hr.recruitment.jobs.show', $job)
            ->with('success', 'Job posting updated successfully.');
    }

    public function destroy(JobPosting $job)
    {
        if ($job->applicants()->count() > 0) {
            return redirect()->route('hr.recruitment.jobs.index')
                ->with('error', 'Cannot delete job posting with existing applicants.');
        }
        
        $job->delete();
        
        return redirect()->route('hr.recruitment.jobs.index')
            ->with('success', 'Job posting deleted successfully.');
    }
}
