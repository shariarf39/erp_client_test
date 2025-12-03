<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'check_in_location',
        'check_out_location',
        'check_in_lat',
        'check_in_lng',
        'check_out_lat',
        'check_out_lng',
        'working_hours',
        'overtime_hours',
        'status',
        'device_type',
        'device_id',
        'remarks',
        'approved_by',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime:H:i:s',
        'check_out' => 'datetime:H:i:s',
        'working_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
