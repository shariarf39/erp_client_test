<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_code', 'first_name', 'last_name', 'email', 'phone',
        'date_of_birth', 'gender', 'marital_status', 'address', 'city',
        'state', 'country', 'postal_code', 'department_id', 'designation_id',
        'branch_id', 'manager_id', 'employment_type', 'join_date',
        'confirmation_date', 'resign_date', 'status', 'photo', 'national_id',
        'passport_no', 'bank_name', 'bank_account', 'bank_branch',
        'emergency_contact_name', 'emergency_contact_phone', 'created_by'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'join_date' => 'date',
        'confirmation_date' => 'date',
        'resign_date' => 'date',
    ];

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function salaryStructure()
    {
        return $this->hasOne(SalaryStructure::class)->where('is_active', true);
    }

    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function payroll()
    {
        return $this->hasMany(Payroll::class);
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }
}

