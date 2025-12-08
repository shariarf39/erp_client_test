<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceKpi extends Model
{
    use HasFactory;
    
    protected $table = 'performance_kpis';
    
    protected $fillable = [
        'name',
        'code',
        'description',
        'category',
        'measurement_type',
        'target_value',
        'unit',
        'weight',
        'department_id',
        'designation_id',
        'is_active',
    ];
    
    protected $casts = [
        'target_value' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
    
    public function reviewKpis()
    {
        return $this->hasMany(PerformanceReviewKpi::class, 'kpi_id');
    }
}
