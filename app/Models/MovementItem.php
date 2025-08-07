<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MovementItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'move',
        'reference',
        'product_id',
        'reference_id',
        'item_name',
        'name',
        'item_description',
        'quantity',
        'buying_price',
        'selling_price',
        'total_price',
        'discount',
        'grand_total',
        'created_by',
        'status',
        'est_quantity',
        'note',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function relatedPurchaseItem()
    {
        return $this->belongsTo(PurchaseItem::class, 'reference_id')
            ->withTrashed();
    }

    public function getPurchaseItemAttribute()
    {
        if ($this->reference === 'purchase_items') {
            return $this->relatedPurchaseItem;
        }

        return null;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function get_reference_info()
    {
        if ($this->reference == 'purchase_items') {
            return 'Pembelian Sparepart';
        } else if ($this->refernce == 'sales_items') {
            return 'Penjualan Sparepart';
        } else if ($this->reference == 'order_items') {
            return 'Work Order';
        }
    }
}
