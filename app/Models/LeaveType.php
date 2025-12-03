<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'days_per_year',
        'max_consecutive_days',
        'is_paid',
        'is_carry_forward',
        'is_active',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'is_carry_forward' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class);
    }
}
