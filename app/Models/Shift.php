<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'break_duration',
        'grace_time',
        'is_active',
    ];
    
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'break_duration' => 'integer',
        'grace_time' => 'integer',
        'is_active' => 'boolean',
    ];
    
    public function schedules()
    {
        return $this->hasMany(ShiftSchedule::class);
    }
    
    public function employeeShifts()
    {
        return $this->hasMany(EmployeeShift::class);
    }
}

