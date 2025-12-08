<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingTask extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'checklist_id',
        'task_name',
        'task_description',
        'category',
        'responsible_role',
        'days_to_complete',
        'is_mandatory',
        'sort_order',
    ];
    
    protected $casts = [
        'days_to_complete' => 'integer',
        'is_mandatory' => 'boolean',
        'sort_order' => 'integer',
    ];
    
    public function checklist()
    {
        return $this->belongsTo(OnboardingChecklist::class);
    }
}
