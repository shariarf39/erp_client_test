<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftSchedule extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'employee_id',
        'shift_id',
        'schedule_date',
        'is_overtime',
        'status',
        'notes',
        'created_by',
    ];
    
    protected $casts = [
        'schedule_date' => 'date',
        'is_overtime' => 'boolean',
    ];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
