<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'item_name',
        'category_id',
        'brand_id',
        'unit_id',
        'description',
        'specifications',
        'purchase_price',
        'sale_price',
        'reorder_level',
        'barcode',
        'sku',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'reorder_level' => 'integer',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
