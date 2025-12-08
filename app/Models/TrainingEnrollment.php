<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingEnrollment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'training_program_id',
        'employee_id',
        'enrollment_date',
        'status',
        'attendance_percentage',
        'assessment_score',
        'certificate_issued',
        'certificate_number',
        'certificate_date',
        'feedback_rating',
        'feedback_comments',
        'completion_date',
        'cost_incurred',
        'approved_by',
    ];
    
    protected $casts = [
        'enrollment_date' => 'date',
        'attendance_percentage' => 'decimal:2',
        'assessment_score' => 'decimal:2',
        'certificate_issued' => 'boolean',
        'certificate_date' => 'date',
        'feedback_rating' => 'decimal:2',
        'completion_date' => 'date',
        'cost_incurred' => 'decimal:2',
    ];
    
    public function trainingProgram()
    {
        return $this->belongsTo(TrainingProgram::class);
    }
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
