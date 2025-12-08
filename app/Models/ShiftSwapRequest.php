<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftSwapRequest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'requestor_id',
        'requestor_schedule_id',
        'requested_employee_id',
        'requested_schedule_id',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];
    
    protected $casts = [
        'approved_at' => 'datetime',
    ];
    
    public function requestor()
    {
        return $this->belongsTo(Employee::class, 'requestor_id');
    }
    
    public function requestorSchedule()
    {
        return $this->belongsTo(ShiftSchedule::class, 'requestor_schedule_id');
    }
    
    public function requestedEmployee()
    {
        return $this->belongsTo(Employee::class, 'requested_employee_id');
    }
    
    public function requestedSchedule()
    {
        return $this->belongsTo(ShiftSchedule::class, 'requested_schedule_id');
    }
    
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
