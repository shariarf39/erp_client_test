<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'employee_id',
        'reviewer_id',
        'review_period_start',
        'review_period_end',
        'review_type',
        'overall_rating',
        'strengths',
        'areas_for_improvement',
        'achievements',
        'goals_for_next_period',
        'comments',
        'status',
        'submitted_at',
        'completed_at',
        'employee_acknowledged_at',
    ];
    
    protected $casts = [
        'review_period_start' => 'date',
        'review_period_end' => 'date',
        'overall_rating' => 'decimal:2',
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
        'employee_acknowledged_at' => 'datetime',
    ];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
    
    public function kpis()
    {
        return $this->hasMany(PerformanceReviewKpi::class, 'review_id');
    }
}
