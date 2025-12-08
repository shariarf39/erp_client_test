<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'applicant_id',
        'interview_type',
        'scheduled_date',
        'scheduled_time',
        'duration',
        'location',
        'meeting_link',
        'interviewer_id',
        'status',
        'feedback',
        'rating',
        'result',
        'notes',
    ];
    
    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime',
        'duration' => 'integer',
        'rating' => 'decimal:2',
    ];
    
    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
    
    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }
}
