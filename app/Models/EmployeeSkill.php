<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSkill extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'employee_id',
        'skill_name',
        'skill_category',
        'proficiency_level',
        'years_of_experience',
        'last_used_date',
        'acquired_through',
        'certification_name',
        'certification_date',
        'expiry_date',
        'is_verified',
    ];
    
    protected $casts = [
        'years_of_experience' => 'decimal:2',
        'last_used_date' => 'date',
        'certification_date' => 'date',
        'expiry_date' => 'date',
        'is_verified' => 'boolean',
    ];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
