<?php

namespace App\Models;

use App\Models\Sales;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sales_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'discount_percentage',
        'discount_nominal',
        'price_after_discount'
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sales::class, 'sales_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
