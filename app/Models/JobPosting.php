<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'job_code',
        'department_id',
        'designation_id',
        'employment_type',
        'experience_required',
        'qualification',
        'skills_required',
        'job_description',
        'responsibilities',
        'salary_range_min',
        'salary_range_max',
        'vacancies',
        'location',
        'application_deadline',
        'status',
        'posted_by',
        'posted_at',
    ];
    
    protected $casts = [
        'salary_range_min' => 'decimal:2',
        'salary_range_max' => 'decimal:2',
        'vacancies' => 'integer',
        'application_deadline' => 'date',
        'posted_at' => 'datetime',
    ];
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
    
    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
    
    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }
}
