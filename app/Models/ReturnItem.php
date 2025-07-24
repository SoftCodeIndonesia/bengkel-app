<?php

namespace App\Models;

use App\Models\User;
use App\Models\Supply;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'return_items';

    protected $fillable = [
        'supply_id',
        'product_id',
        'order_item_id',
        'quantity',
        'unit_price',
        'reason',
        'status',
        'processed_by',
        'processed_at'
    ];

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
