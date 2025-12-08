<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\PerformanceReview;
use App\Models\Employee;
use App\Models\PerformanceKpi;
use Illuminate\Http\Request;

class PerformanceReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = PerformanceReview::with(['employee', 'reviewer']);
        
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('review_type')) {
            $query->where('review_type', $request->review_type);
        }
        
        $reviews = $query->orderBy('review_period_end', 'desc')->paginate(15);
        $employees = Employee::where('status', 'Active')->orderBy('first_name')->get();
        
        return view('hr.performance.reviews.index', compact('reviews', 'employees'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'Active')->orderBy('first_name')->get();
        $kpis = PerformanceKpi::where('is_active', 1)->orderBy('name')->get();
        
        return view('hr.performance.reviews.create', compact('employees', 'kpis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'review_period_start' => 'required|date',
            'review_period_end' => 'required|date|after_or_equal:review_period_start',
            'review_type' => 'required|in:Probation,Annual,Mid-Year,Quarterly,Project-Based',
            'overall_rating' => 'nullable|numeric|min:1|max:5',
            'strengths' => 'nullable',
            'areas_for_improvement' => 'nullable',
            'achievements' => 'nullable',
            'goals_for_next_period' => 'nullable',
            'comments' => 'nullable',
            'status' => 'required|in:Draft,Submitted,Under Review,Completed,Acknowledged',
        ]);
        
        $validated['reviewer_id'] = auth()->id();
        
        if ($validated['status'] === 'Submitted') {
            $validated['submitted_at'] = now();
        }
        
        $review = PerformanceReview::create($validated);
        
        return redirect()->route('hr.performance.reviews.show', $review)
            ->with('success', 'Performance review created successfully.');
    }

    public function show(PerformanceReview $review)
    {
        $review->load(['employee', 'reviewer', 'kpis.kpi']);
        
        return view('hr.performance.reviews.show', compact('review'));
    }

    public function edit(PerformanceReview $review)
    {
        $employees = Employee::where('status', 'Active')->orderBy('first_name')->get();
        $kpis = PerformanceKpi::where('is_active', 1)->orderBy('name')->get();
        
        return view('hr.performance.reviews.edit', compact('review', 'employees', 'kpis'));
    }

    public function update(Request $request, PerformanceReview $review)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'review_period_start' => 'required|date',
            'review_period_end' => 'required|date|after_or_equal:review_period_start',
            'review_type' => 'required|in:Probation,Annual,Mid-Year,Quarterly,Project-Based',
            'overall_rating' => 'nullable|numeric|min:1|max:5',
            'strengths' => 'nullable',
            'areas_for_improvement' => 'nullable',
            'achievements' => 'nullable',
            'goals_for_next_period' => 'nullable',
            'comments' => 'nullable',
            'status' => 'required|in:Draft,Submitted,Under Review,Completed,Acknowledged',
        ]);
        
        if ($review->status !== 'Submitted' && $validated['status'] === 'Submitted') {
            $validated['submitted_at'] = now();
        }
        
        if ($review->status !== 'Completed' && $validated['status'] === 'Completed') {
            $validated['completed_at'] = now();
        }
        
        $review->update($validated);
        
        return redirect()->route('hr.performance.reviews.show', $review)
            ->with('success', 'Performance review updated successfully.');
    }

    public function destroy(PerformanceReview $review)
    {
        if ($review->status !== 'Draft') {
            return redirect()->route('hr.performance.reviews.index')
                ->with('error', 'Only draft reviews can be deleted.');
        }
        
        $review->delete();
        
        return redirect()->route('hr.performance.reviews.index')
            ->with('success', 'Performance review deleted successfully.');
    }
}
