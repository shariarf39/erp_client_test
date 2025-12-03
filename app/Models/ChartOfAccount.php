<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $table = 'chart_of_accounts';

    protected $fillable = [
        'account_code',
        'account_name',
        'account_type_id',
        'parent_id',
        'opening_balance',
        'current_balance',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
    ];

    // Relationships
    public function parent()
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id');
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'account_id');
    }
    
    // Check if account is a group (has children)
    public function getIsGroupAttribute()
    {
        return $this->children()->count() > 0;
    }
}
