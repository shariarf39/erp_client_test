<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingChecklist extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'department_id',
        'designation_id',
        'is_active',
    ];
    
    protected $casts = [
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
    
    public function tasks()
    {
        return $this->hasMany(OnboardingTask::class, 'checklist_id');
    }
    
    public function employeeOnboardings()
    {
        return $this->hasMany(EmployeeOnboarding::class, 'checklist_id');
    }
}
