<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProgram extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'program_code',
        'title',
        'description',
        'category',
        'training_type',
        'trainer_name',
        'trainer_type',
        'duration_days',
        'duration_hours',
        'max_participants',
        'cost_per_participant',
        'venue',
        'start_date',
        'end_date',
        'enrollment_deadline',
        'prerequisites',
        'objectives',
        'status',
        'created_by',
    ];
    
    protected $casts = [
        'duration_days' => 'decimal:2',
        'duration_hours' => 'decimal:2',
        'max_participants' => 'integer',
        'cost_per_participant' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'enrollment_deadline' => 'date',
    ];
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function enrollments()
    {
        return $this->hasMany(TrainingEnrollment::class);
    }
}
