<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceReviewKpi extends Model
{
    use HasFactory;
    
    protected $table = 'performance_review_kpis';
    
    protected $fillable = [
        'review_id',
        'kpi_id',
        'target_value',
        'achieved_value',
        'rating',
        'comments',
    ];
    
    protected $casts = [
        'target_value' => 'decimal:2',
        'achieved_value' => 'decimal:2',
        'rating' => 'decimal:2',
    ];
    
    public function review()
    {
        return $this->belongsTo(PerformanceReview::class);
    }
    
    public function kpi()
    {
        return $this->belongsTo(PerformanceKpi::class);
    }
}
