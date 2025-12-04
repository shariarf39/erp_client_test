<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'voucher_no',
        'voucher_type',
        'date',
        'reference',
        'description',
        'total_amount',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];
    
    protected $casts = [
        'date' => 'date',
        'total_amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];
    
    public function voucherDetails()
    {
        return $this->hasMany(VoucherDetail::class);
    }
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
