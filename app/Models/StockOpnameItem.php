<?php

namespace App\Models;

use App\Models\Product;
use App\Models\StockOpname;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockOpnameItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'opname_id',
        'product_id',
        'system_stock',
        'physical_stock',
        'difference',
        'unit_price',
        'total_difference',
        'notes'
    ];

    public function opname()
    {
        return $this->belongsTo(StockOpname::class, 'opname_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
