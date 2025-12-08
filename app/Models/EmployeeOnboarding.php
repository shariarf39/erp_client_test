<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOnboarding extends Model
{
    use HasFactory;
    
    protected $table = 'employee_onboarding';
    
    protected $fillable = [
        'employee_id',
        'checklist_id',
        'start_date',
        'expected_completion_date',
        'actual_completion_date',
        'status',
        'completion_percentage',
        'assigned_to',
        'notes',
    ];
    
    protected $casts = [
        'start_date' => 'date',
        'expected_completion_date' => 'date',
        'actual_completion_date' => 'date',
        'completion_percentage' => 'decimal:2',
    ];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function checklist()
    {
        return $this->belongsTo(OnboardingChecklist::class);
    }
    
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    
    public function taskProgress()
    {
        return $this->hasMany(EmployeeOnboardingTask::class);
    }
}
