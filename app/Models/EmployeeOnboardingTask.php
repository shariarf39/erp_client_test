<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOnboardingTask extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'employee_onboarding_id',
        'task_id',
        'status',
        'completed_by',
        'completed_at',
        'notes',
    ];
    
    protected $casts = [
        'completed_at' => 'datetime',
    ];
    
    public function employeeOnboarding()
    {
        return $this->belongsTo(EmployeeOnboarding::class);
    }
    
    public function task()
    {
        return $this->belongsTo(OnboardingTask::class);
    }
    
    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}
