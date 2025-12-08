<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'application_code',
        'job_posting_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'country',
        'education',
        'experience_years',
        'current_company',
        'current_position',
        'current_salary',
        'expected_salary',
        'skills',
        'resume_path',
        'cover_letter',
        'linkedin_url',
        'portfolio_url',
        'status',
        'stage',
        'rating',
        'notes',
        'applied_at',
    ];
    
    protected $casts = [
        'date_of_birth' => 'date',
        'experience_years' => 'decimal:2',
        'current_salary' => 'decimal:2',
        'expected_salary' => 'decimal:2',
        'rating' => 'decimal:2',
        'applied_at' => 'datetime',
    ];
    
    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }
    
    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }
    
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
