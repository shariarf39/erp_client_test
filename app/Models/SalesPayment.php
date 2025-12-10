<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_no',
        'date',
        'customer_id',
        'invoice_id',
        'payment_method',
        'amount',
        'bank_name',
        'cheque_no',
        'reference_no',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the customer that made the payment
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the invoice this payment is for
     */
    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'invoice_id');
    }

    /**
     * Get the user who created the payment
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
