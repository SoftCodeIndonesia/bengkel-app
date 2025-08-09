<?php

namespace App\Models;

use App\Models\Supply;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\ReturnItem;
use App\Models\MovementItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplyItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supply_id',
        'product_id',
        'item_id',
        'quantity_requested',
        'quantity_fulfilled',
        'unit_price',
        'total_price',
        'status'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function returnItems()
    {
        return $this->hasMany(ReturnItem::class, 'supply_item_id');
    }
}
