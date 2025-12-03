<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $table = 'payroll';

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'working_days',
        'present_days',
        'absent_days',
        'leave_days',
        'overtime_hours',
        'overtime_amount',
        'basic_salary',
        'total_allowance',
        'total_deduction',
        'gross_salary',
        'net_salary',
        'status',
        'processed_by',
        'processed_at',
        'paid_at',
        'remarks',
    ];

    protected $casts = [
        'working_days' => 'integer',
        'present_days' => 'decimal:2',
        'absent_days' => 'decimal:2',
        'leave_days' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'overtime_amount' => 'decimal:2',
        'basic_salary' => 'decimal:2',
        'total_allowance' => 'decimal:2',
        'total_deduction' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'processed_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
