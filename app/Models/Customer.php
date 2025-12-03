<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'customer_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'credit_limit',
        'payment_terms',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credit_limit' => 'decimal:2',
    ];

    // Relationships
    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
}
